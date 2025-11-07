<?php 
require_once 'includes/db.php';
include_once 'includes/header.php'; 

$stmt = $pdo->query("SELECT e.id, i.nome AS item_nome, e.quantidade, e.data_entrada FROM entradas e JOIN itens i ON e.item_id = i.id ORDER BY e.data_entrada DESC");
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Entradas</h1>
    <a href="index.php?page=adicionar_entrada" class="btn btn-primary">Adicionar Entrada</a>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Item</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Data</th>
            <th scope="col">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($entradas) > 0): ?>
            <?php foreach ($entradas as $entrada): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($entrada['id']) ?></th>
                    <td><?= htmlspecialchars($entrada['item_nome']) ?></td>
                    <td><?= htmlspecialchars($entrada['quantidade']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($entrada['data_entrada']))) ?></td>
                    <td>
                        <a href="index.php?page=excluir_entrada&id=<?= $entrada['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta entrada? A quantidade do item será revertida.')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Nenhuma entrada cadastrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>