<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=fornecedores');
    exit;
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $endereco = $_POST['endereco'] ?? '';

    if (!empty($nome)) {
        try {
            $stmt = $pdo->prepare("UPDATE fornecedores SET nome = ?, cnpj = ?, telefone = ?, email = ?, endereco = ? WHERE id = ?");
            $stmt->execute([$nome, $cnpj, $telefone, $email, $endereco, $id]);
            header('Location: index.php?page=fornecedores');
            exit;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao atualizar fornecedor: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">O nome do fornecedor é obrigatório.</div>';
    }
}

// Fetch the supplier to edit
try {
    $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = ?");
    $stmt->execute([$id]);
    $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fornecedor) {
        header('Location: index.php?page=fornecedores');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar fornecedor: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<h1>Editar Fornecedor</h1>

<form action="index.php?page=editar_fornecedor&id=<?= $id ?>" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($fornecedor['nome']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="cnpj" class="form-label">CNPJ</label>
        <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= htmlspecialchars($fornecedor['cnpj']) ?>">
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone']) ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($fornecedor['email']) ?>">
    </div>
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <textarea class="form-control" id="endereco" name="endereco" rows="3"><?= htmlspecialchars($fornecedor['endereco']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="index.php?page=fornecedores" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>