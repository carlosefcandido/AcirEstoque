<?php
require_once 'includes/db.php';

// Fetch items for the dropdown
$stmt = $pdo->query("SELECT id, nome FROM itens ORDER BY nome");
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? null;
    $quantidade = $_POST['quantidade'] ?? 0;
    $data_entrada = $_POST['data_entrada'] ?? date('Y-m-d');

    if (!empty($item_id) && is_numeric($quantidade) && $quantidade > 0) {
        try {
            $pdo->beginTransaction();

            // Insert into entradas table
            $stmt = $pdo->prepare("INSERT INTO entradas (item_id, quantidade, data_entrada) VALUES (?, ?, ?)");
            $stmt->execute([$item_id, $quantidade, $data_entrada]);

            // Update quantity in itens table
            $stmt = $pdo->prepare("UPDATE itens SET quantidade = quantidade + ? WHERE id = ?");
            $stmt->execute([$quantidade, $item_id]);

            $pdo->commit();
            echo '<div class="alert alert-success">Entrada salva com sucesso!</div>';
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Erro ao salvar entrada: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Todos os campos são obrigatórios e a quantidade deve ser maior que zero.</div>';
    }
}

include_once 'includes/header.php';
?>

<h1>Adicionar Entrada</h1>

<form action="index.php?page=adicionar_entrada" method="POST">
    <div class="mb-3">
        <label for="item_id" class="form-label">Item</label>
        <select class="form-select" id="item_id" name="item_id" required>
            <option selected disabled value="">Selecione um item</option>
            <?php foreach ($itens as $item): ?>
                <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
    </div>
    <div class="mb-3">
        <label for="data_entrada" class="form-label">Data</label>
        <input type="date" class="form-control" id="data_entrada" name="data_entrada" value="<?= date('Y-m-d') ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php?page=entradas" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>