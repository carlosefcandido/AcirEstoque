<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db.php';

    // Basic validation
    if (isset($_POST['nome']) && !empty($_POST['nome'])) {
        $nome = $_POST['nome'];
        $cnpj = $_POST['cnpj'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $email = $_POST['email'] ?? '';
        $endereco = $_POST['endereco'] ?? '';

        try {
            $stmt = $pdo->prepare("INSERT INTO fornecedores (nome, cnpj, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco]);
            echo '<div class="alert alert-success">Fornecedor salvo com sucesso!</div>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao salvar fornecedor: ' . $e->getMessage() . '</div>';
        }

    } else {
        echo '<div class="alert alert-danger">O nome do fornecedor é obrigatório.</div>';
    }
}

include_once 'includes/header.php'; 

?>

<h1>Adicionar Fornecedor</h1>

<form action="index.php?page=adicionar_fornecedor" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="cnpj" class="form-label">CNPJ</label>
        <input type="text" class="form-control" id="cnpj" name="cnpj">
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <textarea class="form-control" id="endereco" name="endereco" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php?page=fornecedores" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>