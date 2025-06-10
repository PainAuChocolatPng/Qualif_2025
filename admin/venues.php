<?php
include '../config.php';
include '../includes/navbar_admin.php';

$venues = $pdo->query("SELECT * FROM Venue ORDER BY nom")->fetchAll();
?>

<div class="container my-4">
    <h2 class="mb-4"><i class="bi bi-geo-alt-fill"></i> Gestion des lieux</h2>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Lieu supprimé avec succès
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="venue_form.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter un lieu
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Adresse</th>
                    <th>URL</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($venues as $venue): ?>
                <tr>
                    <td><?= $venue['id_venue'] ?></td>
                    <td><?= htmlspecialchars($venue['nom']) ?></td>
                    <td><?= htmlspecialchars($venue['type']) ?></td>
                    <td><?= htmlspecialchars($venue['adresse']) ?></td>
                    <td>
                        <?php if ($venue['url']): ?>
                        <a href="<?= htmlspecialchars($venue['url']) ?>" target="_blank" class="text-info">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($venue['photo']) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="venue_form.php?id=<?= $venue['id_venue'] ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Modifier ce lieu">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_confirm.php?type=venue&id=<?= $venue['id_venue'] ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer ce lieu">
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
