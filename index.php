<?php
session_start();

include_once 'includes/header.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php?page=login');
    exit();
}

// Simple router
$page = $_GET['page'] ?? 'home';

// Allow access to login page without authentication
if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header('Location: index.php?page=login');
    exit();
}

$page_path = "pages/{$page}.php";

if (file_exists($page_path)) {
    include_once $page_path;
} else {
    // You can create a home.php or a 404.php page
    echo "<h1>Bem-vindo ao Acir Estoque!</h1>";
    echo "<p>Selecione uma opção no menu acima para começar.</p>";
}

include_once 'includes/footer.php';

?>
