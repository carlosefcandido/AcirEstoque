<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db.php';
    $nome = $_POST['nome'] ?? '';

    if (!empty($nome)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO centros_de_custo (nome) VALUES (?)");
            $stmt->execute([$nome]);
            header('Location: index.php?page=centros_de_custo');
            exit;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao salvar centro de custo: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">O nome do centro de custo é obrigatório.</div>';
    }
}

include_once 'includes/header.php';
?>

<h1>Adicionar Centro de Custo</h1>

<form action="index.php?page=adicionar_centro_de_custo" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php?page=centros_de_custo" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>