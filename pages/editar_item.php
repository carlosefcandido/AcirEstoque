<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=itens');
    exit;
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;

    if (!empty($nome) && is_numeric($quantidade)) {
        try {
            $stmt = $pdo->prepare("UPDATE itens SET nome = ?, descricao = ?, quantidade = ? WHERE id = ?");
            $stmt->execute([$nome, $descricao, $quantidade, $id]);
            header('Location: index.php?page=itens');
            exit;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao atualizar item: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Nome e quantidade são obrigatórios.</div>';
    }
}

// Fetch the item to edit
try {
    $stmt = $pdo->prepare("SELECT * FROM itens WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        header('Location: index.php?page=itens');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar item: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<h1>Editar Item</h1>

<form action="index.php?page=editar_item&id=<?= $id ?>" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($item['descricao']) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?= htmlspecialchars($item['quantidade']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="index.php?page=itens" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>