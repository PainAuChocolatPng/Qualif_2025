<?php
include '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: tickets.php');
    exit;
}

// Récupère l'état actuel du ticket
$stmt = $pdo->prepare("SELECT utilise FROM Ticket WHERE id_ticket = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header('Location: tickets.php');
    exit;
}

// Inverse l'état (valide/dévalide)
$nouvel_etat = $ticket['utilise'] ? 0 : 1;

try {
    $stmt = $pdo->prepare("UPDATE Ticket SET utilise = ? WHERE id_ticket = ?");
    $stmt->execute([$nouvel_etat, $id]);
    header('Location: tickets.php' . (isset($_GET['event_id']) ? '?event_id=' . $_GET['event_id'] : ''));
    exit;
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
