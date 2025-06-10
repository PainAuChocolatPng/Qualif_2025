<?php
include '../config.php';
include '../includes/navbar_admin.php';

$artists = $pdo->query("SELECT * FROM Artiste ORDER BY nom")->fetchAll();
?>

<div class="container my-4">
    <h2 class="mb-4"><i class="bi bi-people-fill"></i> Gestion des artistes</h2>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Artiste supprimé avec succès
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="artist_form.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter un artiste
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>URL</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($artists as $artist): ?>
                <tr>
                    <td><?= $artist['id_artiste'] ?></td>
                    <td><?= htmlspecialchars($artist['nom']) ?></td>
                    <td>
                        <?php if ($artist['url']): ?>
                        <a href="<?= htmlspecialchars($artist['url']) ?>" target="_blank" class="text-info">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($artist['photo']) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="artist_form.php?id=<?= $artist['id_artiste'] ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Modifier cet artiste">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_confirm.php?type=artist&id=<?= $artist['id_artiste'] ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer cet artiste">
                                <i class="bi bi-trash"></i>
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
const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]')
tooltips.forEach(t => new bootstrap.Tooltip(t))
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
