<?php
require_once 'includes/db.php';

// Check if user is logged in and is a stock_manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'stock_manager' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit();
}

$message = '';
$message_type = '';

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requisicao_id = $_POST['requisicao_id'] ?? null;
    $action = $_POST['action'] ?? null; // 'approve' or 'reject'
    $observacoes = $_POST['observacoes'] ?? null;
    $aprovador_user_id = $_SESSION['user_id'];
    $data_aprovacao = date('Y-m-d');

    if (!empty($requisicao_id) && !empty($action)) {
        try {
            $pdo->beginTransaction();

            // Fetch requisition details
            $stmt = $pdo->prepare("SELECT item_id, quantidade, centro_de_custo_id FROM requisicoes WHERE id = ? AND status = 'Pendente'");
            $stmt->execute([$requisicao_id]);
            $requisicao = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($requisicao) {
                if ($action === 'approve') {
                    // Check if there is enough stock
                    $stmt = $pdo->prepare("SELECT quantidade FROM itens WHERE id = ?");
                    $stmt->execute([$requisicao['item_id']]);
                    $item_stock = $stmt->fetchColumn();

                    if ($item_stock >= $requisicao['quantidade']) {
                        // Update requisition status
                        $stmt = $pdo->prepare("UPDATE requisicoes SET status = 'Aprovada', data_aprovacao = ?, aprovador_user_id = ? WHERE id = ?");
                        $stmt->execute([$data_aprovacao, $aprovador_user_id, $requisicao_id]);

                        // Insert into saidas table
                        $stmt = $pdo->prepare("INSERT INTO saidas (item_id, quantidade, data_saida, centro_de_custo_id, user_id) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$requisicao['item_id'], $requisicao['quantidade'], $data_aprovacao, $requisicao['centro_de_custo_id'], $aprovador_user_id]);

                        // Update quantity in itens table
                        $stmt = $pdo->prepare("UPDATE itens SET quantidade = quantidade - ? WHERE id = ?");
                        $stmt->execute([$requisicao['quantidade'], $requisicao['item_id']]);

                        $message = 'Requisição aprovada e saída registrada com sucesso!';
                        $message_type = 'success';
                    } else {
                        $message = 'Estoque insuficiente para aprovar esta requisição.';
                        $message_type = 'danger';
                    }
                } elseif ($action === 'reject') {
                    // Update requisition status
                    $stmt = $pdo->prepare("UPDATE requisicoes SET status = 'Rejeitada', data_aprovacao = ?, aprovador_user_id = ?, observacoes = ? WHERE id = ?");
                    $stmt->execute([$data_aprovacao, $aprovador_user_id, $observacoes, $requisicao_id]);

                    $message = 'Requisição rejeitada com sucesso!';
                    $message_type = 'success';
                }
            } else {
                $message = 'Requisição não encontrada ou já processada.';
                $message_type = 'danger';
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $message = 'Erro ao processar requisição: ' . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = 'Ação inválida.';
        $message_type = 'danger';
    }
}

// Fetch pending requisitions
$stmt = $pdo->query("SELECT r.id, i.nome AS item_nome, r.quantidade, r.data_requisicao, c.nome AS centro_de_custo_nome, u.username AS solicitante_nome
                        FROM requisicoes r
                        JOIN itens i ON r.item_id = i.id
                        JOIN centros_de_custo c ON r.centro_de_custo_id = c.id
                        JOIN users u ON r.user_id = u.id
                        WHERE r.status = 'Pendente'
                        ORDER BY r.data_requisicao ASC");
$requisicoes_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once 'includes/header.php';
?>

<h1>Gerenciar Requisições</h1>

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if (count($requisicoes_pendentes) > 0): ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Item</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Centro de Custo</th>
                <th scope="col">Solicitante</th>
                <th scope="col">Data da Requisição</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requisicoes_pendentes as $requisicao): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($requisicao['id']) ?></th>
                    <td><?= htmlspecialchars($requisicao['item_nome']) ?></td>
                    <td><?= htmlspecialchars($requisicao['quantidade']) ?></td>
                    <td><?= htmlspecialchars($requisicao['centro_de_custo_nome']) ?></td>
                    <td><?= htmlspecialchars($requisicao['solicitante_nome']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($requisicao['data_requisicao']))) ?></td>
                    <td>
                        <form action="index.php?page=gerenciar_requisicoes" method="POST" class="d-inline">
                            <input type="hidden" name="requisicao_id" value="<?= $requisicao['id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Aprovar</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $requisicao['id'] ?>">Rejeitar</button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal<?= $requisicao['id'] ?>" tabindex="-1" aria-labelledby="rejectModalLabel<?= $requisicao['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="index.php?page=gerenciar_requisicoes" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel<?= $requisicao['id'] ?>">Rejeitar Requisição #<?= $requisicao['id'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="requisicao_id" value="<?= $requisicao['id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <div class="mb-3">
                                                <label for="observacoes<?= $requisicao['id'] ?>" class="form-label">Observações (Opcional)</label>
                                                <textarea class="form-control" id="observacoes<?= $requisicao['id'] ?>" name="observacoes" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Rejeitar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">Nenhuma requisição pendente encontrada.</div>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>