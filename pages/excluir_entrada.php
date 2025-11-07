<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $pdo->beginTransaction();

        // Get the entry details before deleting
        $stmt = $pdo->prepare("SELECT item_id, quantidade FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
        $entrada = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entrada) {
            // Update the item quantity
            $stmt = $pdo->prepare("UPDATE itens SET quantidade = quantidade - ? WHERE id = ?");
            $stmt->execute([$entrada['quantidade'], $entrada['item_id']]);

            // Delete the entry
            $stmt = $pdo->prepare("DELETE FROM entradas WHERE id = ?");
            $stmt->execute([$id]);
        }

        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao excluir entrada: " . $e->getMessage());
    }
}

header('Location: index.php?page=entradas');
exit;
?>