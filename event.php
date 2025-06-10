<?php
include 'config.php';
include 'includes/navbar_front.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Récupération des infos détaillées de l'événement
$stmt = $pdo->prepare("
    SELECT e.*, v.nom AS nom_venue, v.adresse, v.url AS url_venue,
           a.nom AS nom_artiste, a.url AS url_artiste, e.photo
    FROM evenement e
    JOIN venue v ON e.id_venue = v.id_venue
    JOIN artiste a ON e.id_artiste = a.id_artiste
    WHERE e.id_evenement = ?
");
$stmt->execute([$id]);
$evt = $stmt->fetch();

if (!$evt) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager — <?= htmlspecialchars($evt['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .img-event { max-height: 400px; object-fit: cover; width: 100%; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-6">
                <img src="uploads/events/<?= htmlspecialchars($evt['photo'] ?? 'default.jpg') ?>" 
                     alt="<?= htmlspecialchars($evt['titre']) ?>" 
                     class="img-fluid rounded img-event">
            </div>
            <div class="col-md-6">
                <h1><?= htmlspecialchars($evt['titre']) ?></h1>
                <p class="text-muted"><?= date('d/m/Y à H:i', strtotime($evt['date_heure'])) ?></p>
                <p class="mb-2">
                    <strong>Lieu :</strong> <?= htmlspecialchars($evt['nom_venue']) ?> — <?= htmlspecialchars($evt['adresse']) ?>
                    <?php if ($evt['url_venue']): ?>
                        <a href="<?= htmlspecialchars($evt['url_venue']) ?>" target="_blank" class="ms-2">
                            <i class="bi bi-box-arrow-up-right"></i> Site du lieu
                        </a>
                    <?php endif; ?>
                </p>
                <p class="mb-2">
                    <strong>Artiste :</strong> <?= htmlspecialchars($evt['nom_artiste']) ?>
                    <?php if ($evt['url_artiste']): ?>
                        <a href="<?= htmlspecialchars($evt['url_artiste']) ?>" target="_blank" class="ms-2">
                            <i class="bi bi-box-arrow-up-right"></i> Site de l'artiste
                        </a>
                    <?php endif; ?>
                </p>
                <p><strong>Prix :</strong> <?= number_format($evt['prix'], 2, ',', ' ') ?> €</p>
                <hr>
                <h4>Description</h4>
                <p><?= nl2br(htmlspecialchars($evt['description'])) ?></p>
                <hr>
                <h4>Réserver votre place</h4>
                <form method="POST" action="reservation.php" id="reservationForm">
                    <input type="hidden" name="id_evenement" value="<?= $evt['id_evenement'] ?>">
                    <div class="mb-3">
                        <label for="nom_complet" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom_complet" name="nom_complet" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantite" class="form-label">Nombre de places</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" min="1" max="10" value="1" required>
                    </div>
                    <div class="mb-3">
                        <strong>Prix total : <span id="prix_unitaire"><?= number_format($evt['prix'], 2, ',', ' ') ?></span> € x <span id="quantite_affiche">1</span> = <span id="prix_total"><?= number_format($evt['prix'], 2, ',', ' ') ?></span> €</strong>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Confirmer la réservation</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const quantiteInput = document.getElementById('quantite');
        const quantiteAffiche = document.getElementById('quantite_affiche');
        const prixUnitaire = parseFloat(document.getElementById('prix_unitaire').textContent.replace(',', '.'));
        const prixTotal = document.getElementById('prix_total');

        quantiteInput.addEventListener('input', () => {
            let qty = parseInt(quantiteInput.value);
            if (isNaN(qty) || qty < 1) qty = 1;
            else if (qty > 10) qty = 10;
            quantiteInput.value = qty;
            quantiteAffiche.textContent = qty;
            prixTotal.textContent = (prixUnitaire * qty).toFixed(2).replace('.', ',');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
