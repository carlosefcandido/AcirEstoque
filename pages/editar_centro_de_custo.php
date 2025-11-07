<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php?page=centros_de_custo');
    exit;
}

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';

    if (!empty($nome)) {
        try {
            $stmt = $pdo->prepare("UPDATE centros_de_custo SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
            header('Location: index.php?page=centros_de_custo');
            exit;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erro ao atualizar centro de custo: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">O nome do centro de custo é obrigatório.</div>';
    }
}

// Fetch the cost center to edit
try {
    $stmt = $pdo->prepare("SELECT * FROM centros_de_custo WHERE id = ?");
    $stmt->execute([$id]);
    $centro_de_custo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$centro_de_custo) {
        header('Location: index.php?page=centros_de_custo');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar centro de custo: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<h1>Editar Centro de Custo</h1>

<form action="index.php?page=editar_centro_de_custo&id=<?= $id ?>" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($centro_de_custo['nome']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="index.php?page=centros_de_custo" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once 'includes/footer.php'; ?>