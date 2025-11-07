<?php
require_once 'includes/db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit();
}

$message = '';
$message_type = '';

// Fetch cost centers for the dropdown
$stmt_centros = $pdo->query("SELECT id, nome FROM centros_de_custo ORDER BY nome");
$centros_de_custo = $stmt_centros->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $centro_de_custo_id = $_POST['centro_de_custo_id'] ?? null;

    if (empty($username) || empty($password) || empty($role)) {
        $message = 'Por favor, preencha todos os campos obrigatórios.';
        $message_type = 'danger';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Validate role and centro_de_custo_id
        if ($role === 'cost_center_employee' && empty($centro_de_custo_id)) {
            $message = 'Funcionários de centro de custo devem ter um centro de custo associado.';
            $message_type = 'danger';
        } elseif ($role !== 'cost_center_employee') {
            $centro_de_custo_id = null; // Ensure it's null for other roles
        }

        if (empty($message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, centro_de_custo_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $role, $centro_de_custo_id]);

                $message = 'Usuário cadastrado com sucesso!';
                $message_type = 'success';
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') { // Duplicate entry for unique constraint
                    $message = 'Nome de usuário já existe. Por favor, escolha outro.';
                } else {
                    $message = 'Erro ao cadastrar usuário: ' . $e->getMessage();
                }
                $message_type = 'danger';
            }
        }
    }
}

include_once 'includes/header.php';
?>

<h1>Adicionar Novo Usuário</h1>

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<form action="index.php?page=adicionar_usuario" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Nome de Usuário</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Função</label>
        <select class="form-select" id="role" name="role" required>
            <option selected disabled value="">Selecione a função</option>
            <option value="admin">Administrador</option>
            <option value="stock_manager">Gerente de Estoque</option>
            <option value="cost_center_employee">Funcionário de Centro de Custo</option>
        </select>
    </div>
    <div class="mb-3" id="centro_de_custo_div" style="display: none;">
        <label for="centro_de_custo_id" class="form-label">Centro de Custo (para Funcionário de Centro de Custo)</label>
        <select class="form-select" id="centro_de_custo_id" name="centro_de_custo_id">
            <option selected value="">Nenhum</option>
            <?php foreach ($centros_de_custo as $centro): ?>
                <option value="<?= $centro['id'] ?>"><?= htmlspecialchars($centro['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
</form>

<script>
    document.getElementById('role').addEventListener('change', function() {
        var centroDeCustoDiv = document.getElementById('centro_de_custo_div');
        if (this.value === 'cost_center_employee') {
            centroDeCustoDiv.style.display = 'block';
            document.getElementById('centro_de_custo_id').setAttribute('required', 'required');
        } else {
            centroDeCustoDiv.style.display = 'none';
            document.getElementById('centro_de_custo_id').removeAttribute('required');
            document.getElementById('centro_de_custo_id').value = ''; // Clear selection
        }
    });
</script>

<?php include_once 'includes/footer.php'; ?>