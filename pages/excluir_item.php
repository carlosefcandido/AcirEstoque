<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Before deleting the item, we should delete the related entries in item_fornecedor
        $stmt = $pdo->prepare("DELETE FROM item_fornecedor WHERE item_id = ?");
        $stmt->execute([$id]);

        $stmt = $pdo->prepare("DELETE FROM itens WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // You might want to handle errors more gracefully
        die("Erro ao excluir item: " . $e->getMessage());
    }
}

header('Location: index.php?page=itens');
exit;
?>