<?php 
require_once 'includes/db.php';
include_once 'includes/header.php'; 

$stmt = $pdo->query("SELECT s.id, i.nome AS item_nome, s.quantidade, s.data_saida, c.nome AS centro_de_custo_nome FROM saidas s JOIN itens i ON s.item_id = i.id LEFT JOIN centros_de_custo c ON s.centro_de_custo_id = c.id ORDER BY s.data_saida DESC");
$saidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Saídas</h1>
    <a href="index.php?page=adicionar_saida" class="btn btn-primary">Adicionar Saída</a>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Item</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Centro de Custo</th>
            <th scope="col">Data</th>
            <th scope="col">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($saidas) > 0): ?>
            <?php foreach ($saidas as $saida): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($saida['id']) ?></th>
                    <td><?= htmlspecialchars($saida['item_nome']) ?></td>
                    <td><?= htmlspecialchars($saida['quantidade']) ?></td>
                    <td><?= htmlspecialchars($saida['centro_de_custo_nome']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($saida['data_saida']))) ?></td>
                    <td>
                        <a href="index.php?page=excluir_saida&id=<?= $saida['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta saída? A quantidade do item será revertida.')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Nenhuma saída cadastrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once 'includes/footer.php'; ?>