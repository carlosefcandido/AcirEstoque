<?php
require_once 'includes/db.php';

// Check if user is logged in and is a cost_center_employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'funcionario') {
    header('Location: index.php?page=login');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT r.id, i.nome AS item_nome, r.quantidade, r.data_requisicao, r.status, r.observacoes
                        FROM requisicoes r
                        JOIN itens i ON r.item_id = i.id
                        WHERE r.user_id = ?
                        ORDER BY r.data_requisicao DESC");
$stmt->execute([$user_id]);
$requisicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once 'includes/header.php';
?>

<h1>Minhas Requisições</h1>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Item</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Data da Requisição</th>
            <th scope="col">Status</th>
            <th scope="col">Observações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($requisicoes) > 0): ?>
            <?php foreach ($requisicoes as $requisicao): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($requisicao['id']) ?></th>
                    <td><?= htmlspecialchars($requisicao['item_nome']) ?></td>
                    <td><?= htmlspecialchars($requisicao['quantidade']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($requisicao['data_requisicao']))) ?></td>
                    <td><?= htmlspecialchars($requisicao['status']) ?></td>
                    <td><?= htmlspecialchars($requisicao['observacoes'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Nenhuma requisição encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>