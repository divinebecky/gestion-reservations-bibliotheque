<div class="toolbar">
    <form class="search-form" method="get">
        <input type="hidden" name="route" value="rooms">
        <i class="bi bi-search"></i>
        <input class="form-control live-search" name="q" value="<?= e($search) ?>" placeholder="Rechercher une salle">
    </form>
    <?php if (Auth::role() === 'administrateur'): ?>
        <a class="btn btn-primary" href="<?= url('rooms/create') ?>"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
    <?php endif; ?>
</div>

<div class="table-responsive panel">
    <table class="table table-hover align-middle sortable-table">
        <thead><tr><th>Numero</th><th>Nom</th><th>Capacite</th><th>Localisation</th><th>Etat</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= e($room['numero']) ?></td>
                <td><?= e($room['nom']) ?></td>
                <td><?= (int) $room['capacite'] ?></td>
                <td><?= e($room['localisation']) ?></td>
                <td><span class="badge status-<?= e($room['etat']) ?>"><?= e($room['etat']) ?></span></td>
                <td class="actions">
                    <?php if (Auth::role() === 'etudiant' && $room['etat'] === 'disponible'): ?>
                        <a class="btn btn-sm btn-outline-success" href="<?= url('reservations/create') ?>&room=<?= (int) $room['id'] ?>" title="Reserver"><i class="bi bi-calendar-plus"></i></a>
                    <?php endif; ?>
                    <?php if (Auth::role() === 'administrateur'): ?>
                        <a class="btn btn-sm btn-outline-primary" href="<?= url('rooms/edit') ?>&id=<?= (int) $room['id'] ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= url('rooms/delete') ?>" onsubmit="return confirm('Supprimer cette salle ?')">
                            <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $room['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../partials/pagination.php'; ?>

