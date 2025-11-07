<?php 
require_once 'includes/db.php';
include_once 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM centros_de_custo ORDER BY nome");
$centros_de_custo = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Centros de Custo</h1>
    <a href="index.php?page=adicionar_centro_de_custo" class="btn btn-primary">Adicionar Centro de Custo</a>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($centros_de_custo) > 0): ?>
            <?php foreach ($centros_de_custo as $centro): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($centro['id']) ?></th>
                    <td><?= htmlspecialchars($centro['nome']) ?></td>
                    <td>
                        <a href="index.php?page=editar_centro_de_custo&id=<?= $centro['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?page=excluir_centro_de_custo&id=<?= $centro['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este centro de custo?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Nenhum centro de custo cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>