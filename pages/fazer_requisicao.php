<?php
require_once 'includes/db.php';

// Check if user is logged in and is a cost_center_employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'funcionario') {
    header('Location: index.php?page=login');
    exit();
}

$user_centro_de_custo_id = $_SESSION['centro_de_custo_id'];

// Fetch items for the dropdown
$stmt_itens = $pdo->query("SELECT id, nome, quantidade FROM itens ORDER BY nome");
$itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? null;
    $quantidade = $_POST['quantidade'] ?? 0;
    $data_requisicao = date('Y-m-d');
    $user_id = $_SESSION['user_id'];

    if (!empty($item_id) && is_numeric($quantidade) && $quantidade > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO requisicoes (item_id, quantidade, centro_de_custo_id, user_id, data_requisicao, status) VALUES (?, ?, ?, ?, ?, 'Pendente')");
            $stmt->execute([$item_id, $quantidade, $user_centro_de_custo_id, $user_id, $data_requisicao]);

            $message = 'Requisição enviada com sucesso!';
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = 'Erro ao enviar requisição: ' . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = 'Todos os campos são obrigatórios e a quantidade deve ser maior que zero.';
        $message_type = 'danger';
    }
}

include_once 'includes/header.php';
?>

<h1>Fazer Requisição de Material</h1>

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<form action="index.php?page=fazer_requisicao" method="POST">
    <div class="mb-3">
        <label for="item_id" class="form-label">Item</label>
        <select class="form-select" id="item_id" name="item_id" required>
            <option selected disabled value="">Selecione um item</option>
            <?php foreach ($itens as $item): ?>
                <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['nome']) ?> (Estoque atual: <?= $item['quantidade'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
    </div>
    <button type="submit" class="btn btn-primary">Enviar Requisição</button>
</form>

<?php include_once 'includes/footer.php'; ?>