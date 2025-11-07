<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db.php';

    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;

    if (!empty($nome) && is_numeric($quantidade)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO itens (nome, descricao, quantidade) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $descricao, $quantidade]);
            echo '<div class="alert alert-success">Item salvo com sucesso!</div>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao salvar item: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Nome e quantidade são obrigatórios.</div>';
    }
}

include_once 'includes/header.php';
?>

<h1>Adicionar Item</h1>

<form action="index.php?page=adicionar_item" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php?page=itens" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>