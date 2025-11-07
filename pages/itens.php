<?php 
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

include_once 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM itens ORDER BY nome");
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Itens</h1>
    <?php if ($_SESSION['role'] !== 'funcionario'): ?>
        <a href="index.php?page=adicionar_item" class="btn btn-primary">Adicionar Item</a>
    <?php endif; ?>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Descrição</th>
            <th scope="col">Quantidade</th>
            <?php if ($_SESSION['role'] !== 'funcionario'): ?>
                <th scope="col">Ações</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (count($itens) > 0): ?>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($item['id']) ?></th>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td><?= htmlspecialchars($item['quantidade']) ?></td>
                    <?php if ($_SESSION['role'] !== 'funcionario'): ?>
                        <td>
                            <a href="index.php?page=editar_item&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="index.php?page=excluir_item&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item?')">Excluir</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= ($_SESSION['role'] !== 'funcionario') ? 5 : 4 ?>" class="text-center">Nenhum item cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>
