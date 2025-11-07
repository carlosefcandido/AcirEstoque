<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $pdo->beginTransaction();

        // Get the exit details before deleting
        $stmt = $pdo->prepare("SELECT item_id, quantidade FROM saidas WHERE id = ?");
        $stmt->execute([$id]);
        $saida = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($saida) {
            // Update the item quantity
            $stmt = $pdo->prepare("UPDATE itens SET quantidade = quantidade + ? WHERE id = ?");
            $stmt->execute([$saida['quantidade'], $saida['item_id']]);

            // Delete the exit
            $stmt = $pdo->prepare("DELETE FROM saidas WHERE id = ?");
            $stmt->execute([$id]);
        }

        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao excluir saída: " . $e->getMessage());
    }
}

header('Location: index.php?page=saidas');
exit;
?>