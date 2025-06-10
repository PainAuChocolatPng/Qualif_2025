<?php
include '../config.php';
include '../includes/navbar_admin.php';

$event_id = $_GET['event_id'] ?? null;

if ($event_id) {
    // Récupération des tickets de l'événement
    $stmt = $pdo->prepare("
        SELECT t.*, e.titre AS evenement_titre, e.date_heure
        FROM Ticket t
        JOIN Evenement e ON t.id_evenement = e.id_evenement
        WHERE t.id_evenement = ?
        ORDER BY t.date_reservation DESC
    ");
    $stmt->execute([$event_id]);
    $tickets = $stmt->fetchAll();

    // Titre de l'événement
    $stmt = $pdo->prepare("SELECT titre FROM Evenement WHERE id_evenement = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();
    $titre_page = 'Tickets pour : ' . htmlspecialchars($event['titre']);
} else {
    // Tous les tickets
    $tickets = $pdo->query("
        SELECT t.*, e.titre AS evenement_titre, e.date_heure
        FROM Ticket t
        JOIN Evenement e ON t.id_evenement = e.id_evenement
        ORDER BY t.date_reservation DESC
    ")->fetchAll();
    $titre_page = 'Liste de tous les tickets';
}
?>

<div class="container my-4">
    <h2><?= $titre_page ?></h2>
    <div class="table-responsive">
        <table class="table table-dark table-striped align-middle">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Prix total</th>
                    <th>Date réservation</th>
                    <th>Utilisé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['code_unique']) ?></td>
                    <td><?= htmlspecialchars($ticket['nom_complet']) ?></td>
                    <td><?= htmlspecialchars($ticket['email']) ?></td>
                    <td><?= $ticket['quantite'] ?></td>
                    <td><?= number_format($ticket['prix_personne'], 2) ?> €</td>
                    <td><?= number_format($ticket['prix_total'], 2) ?> €</td>
                    <td><?= date('d/m/Y H:i', strtotime($ticket['date_reservation'])) ?></td>
                    <td><?= $ticket['utilise'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="delete_confirm.php?type=ticket&id=<?= $ticket['id_ticket'] ?>" class="btn btn-danger btn-sm" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a href="ticket_validate.php?id=<?= $ticket['id_ticket'] ?>" 
                        class="btn <?= $ticket['utilise'] ? 'btn-warning' : 'btn-success' ?> btn-sm"
                        title="<?= $ticket['utilise'] ? 'Dévalider ce ticket' : 'Valider ce ticket' ?>">
                            <i class="bi <?= $ticket['utilise'] ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                            <?= $ticket['utilise'] ? 'Dévalider' : 'Valider' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="index.php" class="btn btn-secondary">
            Retour à la liste des événements
        </a>
        <?php if ($event_id): ?>
            <a href="tickets.php" class="btn btn-outline-secondary ms-2">
                Voir tous les tickets
            </a>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
