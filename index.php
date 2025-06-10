<?php
include 'config.php';
include 'includes/navbar_front.php';

// Récupération des événements à venir avec la photo de l'événement
$stmt = $pdo->query("
    SELECT e.*, v.nom AS nom_venue, e.photo
    FROM evenement e
    JOIN venue v ON e.id_venue = v.id_venue
    WHERE e.date_heure >= NOW()
    ORDER BY e.date_heure ASC
");
$evenements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager — Événements à venir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4"><span class="me-2">🎉</span> Événements à venir</h2>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php foreach ($evenements as $evt): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0" style="background: #fafbfc;">
                    <img src="uploads/events/<?= htmlspecialchars($evt['photo'] ?? 'default.jpg') ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($evt['titre']) ?>" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?= htmlspecialchars($evt['titre']) ?></h5>
                        <ul class="list-unstyled small mb-3">
                            <li>
                                <i class="bi bi-calendar-event text-danger me-1"></i>
                                <?= date('d/m/Y à H:i', strtotime($evt['date_heure'])) ?>
                            </li>
                            <li>
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                <?= htmlspecialchars($evt['nom_venue']) ?>
                            </li>
                            <li>
                                <i class="bi bi-currency-euro text-success me-1"></i>
                                <?= number_format($evt['prix'], 2, ',', ' ') ?> €
                            </li>
                        </ul>
                        <a href="event.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-primary w-100">Réserver</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
