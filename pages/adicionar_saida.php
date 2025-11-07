<?php
require_once 'includes/db.php';

// Fetch items for the dropdown
$stmt_itens = $pdo->query("SELECT id, nome, quantidade FROM itens ORDER BY nome");
$itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

// Fetch cost centers for the dropdown
$stmt_centros = $pdo->query("SELECT id, nome FROM centros_de_custo ORDER BY nome");
$centros_de_custo = $stmt_centros->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? null;
    $quantidade = $_POST['quantidade'] ?? 0;
    $data_saida = $_POST['data_saida'] ?? date('Y-m-d');
    $centro_de_custo_id = $_POST['centro_de_custo_id'] ?? null;

    if (!empty($item_id) && is_numeric($quantidade) && $quantidade > 0 && !empty($centro_de_custo_id)) {
        try {
            // Check if there is enough stock
            $stmt = $pdo->prepare("SELECT quantidade FROM itens WHERE id = ?");
            $stmt->execute([$item_id]);
            $item_stock = $stmt->fetchColumn();

            if ($item_stock >= $quantidade) {
                $pdo->beginTransaction();

                // Insert into saidas table
                $stmt = $pdo->prepare("INSERT INTO saidas (item_id, quantidade, data_saida, centro_de_custo_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$item_id, $quantidade, $data_saida, $centro_de_custo_id]);

                // Update quantity in itens table
                $stmt = $pdo->prepare("UPDATE itens SET quantidade = quantidade - ? WHERE id = ?");
                $stmt->execute([$quantidade, $item_id]);

                $pdo->commit();
                echo '<div class="alert alert-success">Saída salva com sucesso!</div>';
            } else {
                echo '<div class="alert alert-danger">Quantidade em estoque insuficiente.</div>';
            }

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Erro ao salvar saída: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Todos os campos são obrigatórios e a quantidade deve ser maior que zero.</div>';
    }
}

include_once 'includes/header.php';
?>

<h1>Adicionar Saída</h1>

<form action="index.php?page=adicionar_saida" method="POST">
    <div class="mb-3">
        <label for="item_id" class="form-label">Item</label>
        <select class="form-select" id="item_id" name="item_id" required>
            <option selected disabled value="">Selecione um item</option>
            <?php foreach ($itens as $item): ?>
                <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['nome']) ?> (Estoque: <?= $item['quantidade'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="centro_de_custo_id" class="form-label">Centro de Custo</label>
        <select class="form-select" id="centro_de_custo_id" name="centro_de_custo_id" required>
            <option selected disabled value="">Selecione um centro de custo</option>
            <?php foreach ($centros_de_custo as $centro): ?>
                <option value="<?= $centro['id'] ?>"><?= htmlspecialchars($centro['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
    </div>
    <div class="mb-3">
        <label for="data_saida" class="form-label">Data</label>
        <input type="date" class="form-control" id="data_saida" name="data_saida" value="<?= date('Y-m-d') ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php?page=saidas" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>