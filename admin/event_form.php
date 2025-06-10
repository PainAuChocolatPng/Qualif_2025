<?php
include '../config.php';
include '../includes/navbar_admin.php';

$id = $_GET['id'] ?? null;
$event = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM Evenement WHERE id_evenement = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch();
}

$venues = $pdo->query("SELECT * FROM Venue ORDER BY nom")->fetchAll();
$artists = $pdo->query("SELECT * FROM Artiste ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_heure = $_POST['date_heure'];
    $prix = $_POST['prix'];
    $id_venue = $_POST['id_venue'];
    $id_artiste = $_POST['id_artiste'];
    $photo = $event['photo'] ?? null;

    // Gestion de l'upload
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = '../uploads/events/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Suppression ancienne photo
            if ($photo && file_exists($uploadDir . $photo)) {
                unlink($uploadDir . $photo);
            }

            // Génération nom unique
            $newFilename = uniqid('event_') . '.' . $fileExtension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename);
            $photo = $newFilename;
        } else {
            die("Format de fichier non supporté. Formats autorisés : " . implode(', ', $allowedExtensions));
        }
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE Evenement SET titre=?, description=?, date_heure=?, prix=?, id_venue=?, id_artiste=?, photo=? WHERE id_evenement=?");
            $stmt->execute([$titre, $description, $date_heure, $prix, $id_venue, $id_artiste, $photo, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Evenement (titre, description, date_heure, prix, id_venue, id_artiste, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titre, $description, $date_heure, $prix, $id_venue, $id_artiste, $photo]);
        }
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }
}
?>

<div class="container my-4">
    <h2><?= $id ? 'Modifier' : 'Ajouter' ?> un événement</h2>
    
    <form method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" name="titre" 
                   value="<?= htmlspecialchars($event['titre'] ?? '') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="date_heure" class="form-label">Date et heure</label>
            <input type="datetime-local" class="form-control" name="date_heure" 
                   value="<?= $event ? date('Y-m-d\TH:i', strtotime($event['date_heure'])) : '' ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="prix" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" name="prix" step="0.01" 
                   value="<?= $event['prix'] ?? '' ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="photo" class="form-label">Affiche de l'événement</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
            
            <?php if (!empty($event['photo'])): ?>
                <div class="mt-2">
                    <img src="../uploads/events/<?= $event['photo'] ?>" 
                         alt="Affiche actuelle" 
                         style="max-height: 200px;"
                         class="img-thumbnail">
                    <p class="text-muted mt-1">Affiche actuelle</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mb-3">
            <label for="id_venue" class="form-label">Lieu</label>
            <select class="form-control" name="id_venue" required>
                <option value="">Sélectionnez un lieu</option>
                <?php foreach ($venues as $venue): ?>
                <option value="<?= $venue['id_venue'] ?>" <?= ($event && $event['id_venue'] == $venue['id_venue']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($venue['nom']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="id_artiste" class="form-label">Artiste</label>
            <select class="form-control" name="id_artiste" required>
                <option value="">Sélectionnez un artiste</option>
                <?php foreach ($artists as $artist): ?>
                <option value="<?= $artist['id_artiste'] ?>" <?= ($event && $event['id_artiste'] == $artist['id_artiste']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($artist['nom']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Modifier' : 'Ajouter' ?></button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
