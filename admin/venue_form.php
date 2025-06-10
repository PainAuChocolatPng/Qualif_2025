<?php
include '../config.php';
include '../includes/navbar_admin.php';

$id = $_GET['id'] ?? null;
$venue = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM Venue WHERE id_venue = ?");
    $stmt->execute([$id]);
    $venue = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $adresse = $_POST['adresse'];
    $url = $_POST['url'];
    $photo = $venue['photo'] ?? null;

    // Gestion de l'upload
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = '../uploads/venues/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Suppression ancienne photo
            if ($photo && file_exists($uploadDir . $photo)) {
                unlink($uploadDir . $photo);
            }

            // Génération nom unique
            $newFilename = uniqid('venue_') . '.' . $fileExtension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename);
            $photo = $newFilename;
        } else {
            die("Format de fichier non supporté. Formats autorisés : " . implode(', ', $allowedExtensions));
        }
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE Venue SET nom=?, type=?, adresse=?, url=?, photo=? WHERE id_venue=?");
            $stmt->execute([$nom, $type, $adresse, $url, $photo, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Venue (nom, type, adresse, url, photo) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $type, $adresse, $url, $photo]);
        }
        header('Location: venues.php');
        exit;
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }
}
?>

<div class="container my-4">
    <h2><?= $id ? 'Modifier' : 'Ajouter' ?> un lieu</h2>
    
    <form method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" 
                   value="<?= htmlspecialchars($venue['nom'] ?? '') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" name="type" 
                   value="<?= htmlspecialchars($venue['type'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" name="adresse" 
                   value="<?= htmlspecialchars($venue['adresse'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" name="url" 
                   value="<?= htmlspecialchars($venue['url'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
            
            <?php if (!empty($venue['photo'])): ?>
                <div class="mt-2">
                    <img src="../uploads/venues/<?= $venue['photo'] ?>" 
                         alt="Photo actuelle" 
                         style="max-height: 150px;"
                         class="img-thumbnail">
                    <p class="text-muted mt-1">Photo actuelle</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Modifier' : 'Ajouter' ?></button>
            <a href="venues.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
