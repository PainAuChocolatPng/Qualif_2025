<?php
include '../config.php';

$ticket_id = $_GET['id'] ?? null;

if ($ticket_id) {
    $stmt = $pdo->prepare("DELETE FROM Ticket WHERE id_ticket = ?");
    $stmt->execute([$ticket_id]);
}

header('Location: tickets.php' . (isset($_GET['event_id']) ? '?event_id=' . $_GET['event_id'] : ''));
exit;
?>
