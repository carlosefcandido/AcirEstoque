<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // You might want to handle errors more gracefully
        die("Erro ao excluir fornecedor: " . $e->getMessage());
    }
}

header('Location: index.php?page=fornecedores');
exit;
?>