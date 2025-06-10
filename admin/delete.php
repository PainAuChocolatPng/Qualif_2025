<?php
include '../config.php';

$type = $_GET['type'];
$id = $_GET['id'];

try {
    switch ($type) {
        case 'event':
            $stmt = $pdo->prepare("DELETE FROM Evenement WHERE id_evenement = ?");
            $stmt->execute([$id]);
            header('Location: index.php');
            break;
        case 'venue':
            $stmt = $pdo->prepare("DELETE FROM Venue WHERE id_venue = ?");
            $stmt->execute([$id]);
            header('Location: venues.php');
            break;
        case 'artist':
            $stmt = $pdo->prepare("DELETE FROM Artiste WHERE id_artiste = ?");
            $stmt->execute([$id]);
            header('Location: artists.php');
            break;
    }
} catch (PDOException $e) {
    echo "Erreur: Impossible de supprimer cet élément car il est utilisé dans d'autres données.";
}
?>
