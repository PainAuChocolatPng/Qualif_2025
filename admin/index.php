<?php
include '../config.php';
include '../includes/navbar_admin.php';

$stmt = $pdo->query("
    SELECT e.id_evenement, e.titre, e.date_heure, v.nom AS venue_name, a.nom AS artist_name, e.prix
    FROM Evenement e
    JOIN Venue v ON e.id_venue = v.id_venue
    JOIN Artiste a ON e.id_artiste = a.id_artiste
    ORDER BY e.date_heure ASC
");
$evenements = $stmt->fetchAll();
?>

<div class="container my-4">
    <h2 class="mb-4">
        <i class="bi bi-calendar-event"></i>
        Dashboard – Événements
    </h2>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Événement supprimé avec succès
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="event_form.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter un événement
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Lieu</th>
                    <th>Artiste</th>
                    <th>Prix (€)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($evenements as $evt): ?>
                <tr>
                    <td><?= $evt['id_evenement'] ?></td>
                    <td><?= htmlspecialchars($evt['titre']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($evt['date_heure'])) ?></td>
                    <td><?= htmlspecialchars($evt['venue_name']) ?></td>
                    <td><?= htmlspecialchars($evt['artist_name']) ?></td>
                    <td><?= number_format($evt['prix'], 2) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="event_form.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_confirm.php?type=event&id=<?= $evt['id_evenement'] ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </a>
                            <a href="tickets.php?event_id=<?= $evt['id_evenement'] ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Tickets">
                                <i class="bi bi-ticket"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Activation des tooltips Bootstrap
const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]')
tooltips.forEach(t => new bootstrap.Tooltip(t))
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
