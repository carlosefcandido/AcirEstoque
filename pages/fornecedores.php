<?php 
require_once 'includes/db.php';
include_once 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Fornecedores</h1>
    <a href="index.php?page=adicionar_fornecedor" class="btn btn-primary">Adicionar Fornecedor</a>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">CNPJ</th>
            <th scope="col">Telefone</th>
            <th scope="col">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($fornecedores) > 0): ?>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($fornecedor['id']) ?></th>
                    <td><?= htmlspecialchars($fornecedor['nome']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['cnpj']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                    <td>
                        <a href="index.php?page=editar_fornecedor&id=<?= $fornecedor['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=excluir_fornecedor&id=<?= $fornecedor['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Nenhum fornecedor cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>