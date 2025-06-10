<?php
include '../config.php';
include '../includes/navbar_admin.php';

$id = $_GET['id'] ?? null;
$artist = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM Artiste WHERE id_artiste = ?");
    $stmt->execute([$id]);
    $artist = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $url = $_POST['url'];
    $photo = $artist['photo'] ?? null; // Conserver l'ancienne photo par défaut

    // Gestion de l'upload
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = '../uploads/artists/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        // Validation du type de fichier
        if (in_array($fileExtension, $allowedExtensions)) {
            // Suppression de l'ancienne photo si elle existe
            if ($photo && file_exists($uploadDir . $photo)) {
                unlink($uploadDir . $photo);
            }

            // Génération d'un nom unique
            $newFilename = uniqid('artist_') . '.' . $fileExtension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename);
            $photo = $newFilename;
        } else {
            die("Format de fichier non supporté. Formats autorisés : " . implode(', ', $allowedExtensions));
        }
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE Artiste SET nom=?, url=?, photo=? WHERE id_artiste=?");
            $stmt->execute([$nom, $url, $photo, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Artiste (nom, url, photo) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $url, $photo]);
        }
        header('Location: artists.php');
        exit;
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }
}
?>

<div class="container my-4">
    <h2><?= $id ? 'Modifier' : 'Ajouter' ?> un artiste</h2>
    
    <form method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" 
                   value="<?= htmlspecialchars($artist['nom'] ?? '') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" name="url" 
                   value="<?= htmlspecialchars($artist['url'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
            
            <?php if (!empty($artist['photo'])): ?>
                <div class="mt-2">
                    <img src="../uploads/artists/<?= $artist['photo'] ?>" 
                         alt="Photo actuelle" 
                         style="max-height: 150px;"
                         class="img-thumbnail">
                    <p class="text-muted mt-1">Photo actuelle</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?= $id ? 'Modifier' : 'Ajouter' ?></button>
            <a href="artists.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
