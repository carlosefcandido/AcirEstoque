<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Before deleting the cost center, you might want to check if it is being used in the 'saidas' table.
        // For simplicity, we are deleting it directly.
        $stmt = $pdo->prepare("DELETE FROM centros_de_custo WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // If the cost center is in use, a foreign key constraint violation will occur.
        // You should handle this gracefully.
        die("Erro ao excluir centro de custo: " . $e->getMessage());
    }
}

header('Location: index.php?page=centros_de_custo');
exit;
?>