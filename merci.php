<?php 
include 'config.php';
include 'includes/navbar_front.php';

$code = $_GET['code'] ?? null;
if (!$code) {
    header('Location: index.php');
    exit;
}

// Récupération des infos du ticket
$stmt = $pdo->prepare("
    SELECT t.*, e.titre, e.date_heure, v.nom as venue_name 
    FROM Ticket t
    JOIN Evenement e ON t.id_evenement = e.id_evenement
    JOIN Venue v ON e.id_venue = v.id_venue
    WHERE t.code_unique = ?
");
$stmt->execute([$code]);
$ticket = $stmt->fetch();
?>

<div class="container my-5">
    <div class="text-center">
        <h2 class="text-success mb-4">🎉 Réservation confirmée !</h2>
        
        <?php if ($ticket): ?>
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white">
                <h5>Votre ticket</h5>
            </div>
            <div class="card-body">
                <p><strong>Code :</strong> <?= htmlspecialchars($ticket['code_unique']) ?></p>
                <p><strong>Événement :</strong> <?= htmlspecialchars($ticket['titre']) ?></p>
                <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($ticket['date_heure'])) ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($ticket['venue_name']) ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($ticket['nom_complet']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($ticket['email']) ?></p>
                <p><strong>Quantité :</strong> <?= $ticket['quantite'] ?> place(s)</p>
                <p><strong>Prix total :</strong> <?= number_format($ticket['prix_total'], 2) ?> €</p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
