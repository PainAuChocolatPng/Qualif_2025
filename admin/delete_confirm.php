<?php
include '../config.php';
include '../includes/navbar_admin.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? null;

// Types autorisés
$valid_types = ['event', 'artist', 'venue', 'ticket'];
if (!in_array($type, $valid_types) || !$id) {
    header('Location: index.php');
    exit;
}

try {
    // Récupération des données selon le type
    switch ($type) {
        case 'event':
            $stmt = $pdo->prepare("
                SELECT e.titre, e.date_heure, v.nom AS lieu 
                FROM Evenement e
                JOIN Venue v ON e.id_venue = v.id_venue
                WHERE e.id_evenement = ?
            ");
            $redirect = 'index.php';
            break;

        case 'artist':
            $stmt = $pdo->prepare("SELECT nom FROM Artiste WHERE id_artiste = ?");
            $redirect = 'artists.php';
            break;

        case 'venue':
            $stmt = $pdo->prepare("SELECT nom FROM Venue WHERE id_venue = ?");
            $redirect = 'venues.php';
            break;

        case 'ticket':
            $stmt = $pdo->prepare("
                SELECT t.code_unique, e.titre 
                FROM Ticket t
                JOIN Evenement e ON t.id_evenement = e.id_evenement
                WHERE t.id_ticket = ?
            ");
            $redirect = 'tickets.php';
            break;
    }

    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if (!$item) {
        header("Location: $redirect");
        exit;
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        // Suppression en cascade via ON DELETE CASCADE
        $deleteStmt = $pdo->prepare(match($type) {
            'event' => "DELETE FROM Evenement WHERE id_evenement = ?",
            'artist' => "DELETE FROM Artiste WHERE id_artiste = ?",
            'venue' => "DELETE FROM Venue WHERE id_venue = ?",
            'ticket' => "DELETE FROM Ticket WHERE id_ticket = ?"
        });
        
        $deleteStmt->execute([$id]);
        header("Location: $redirect?deleted=1");
        exit;

    } catch (PDOException $e) {
        die("Erreur de suppression : " . $e->getMessage());
    }
}
?>

<div class="container my-5">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h3><i class="bi bi-exclamation-triangle"></i> Confirmer la suppression</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                Vous êtes sur le point de supprimer définitivement :
            </div>

            <?php if ($type === 'event'): ?>
                <p><strong>Événement :</strong> <?= htmlspecialchars($item['titre']) ?></p>
                <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($item['date_heure'])) ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($item['lieu']) ?></p>

            <?php elseif ($type === 'artist'): ?>
                <p><strong>Artiste :</strong> <?= htmlspecialchars($item['nom']) ?></p>

            <?php elseif ($type === 'venue'): ?>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($item['nom']) ?></p>

            <?php elseif ($type === 'ticket'): ?>
                <p><strong>Ticket :</strong> <?= htmlspecialchars($item['code_unique']) ?></p>
                <p><strong>Événement lié :</strong> <?= htmlspecialchars($item['titre']) ?></p>
            <?php endif; ?>

            <form method="POST" class="mt-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= $redirect ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                    <button type="submit" name="confirm_delete" class="btn btn-danger">
                        <i class="bi bi-trash3"></i> Confirmer la suppression
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
