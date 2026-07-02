<div class="toolbar">
    <form class="search-form" method="get">
        <input type="hidden" name="route" value="reservations">
        <i class="bi bi-search"></i>
        <input class="form-control live-search" name="q" value="<?= e($search) ?>" placeholder="Rechercher une reservation">
    </form>
    <a class="btn btn-primary" href="<?= url('reservations/create') ?>"><i class="bi bi-plus-lg me-1"></i>Reserver</a>
</div>

<div class="table-responsive panel">
    <table class="table table-hover align-middle sortable-table">
        <thead><tr><th>Utilisateur</th><th>Salle</th><th>Date</th><th>Debut</th><th>Fin</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= e($reservation['utilisateur']) ?></td>
                <td><?= e($reservation['numero'] . ' - ' . $reservation['salle']) ?></td>
                <td><?= e($reservation['date_reservation']) ?></td>
                <td><?= e(substr($reservation['heure_debut'], 0, 5)) ?></td>
                <td><?= e(substr($reservation['heure_fin'], 0, 5)) ?></td>
                <td><span class="badge status-<?= e($reservation['statut']) ?>"><?= e($reservation['statut']) ?></span></td>
                <td class="actions">
                    <?php if (in_array(Auth::role(), ['bibliothecaire', 'administrateur'], true)): ?>
                        <form method="post" action="<?= url('reservations/validate') ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $reservation['id'] ?>"><button class="btn btn-sm btn-outline-success" title="Valider"><i class="bi bi-check2"></i></button></form>
                        <a class="btn btn-sm btn-outline-primary" href="<?= url('reservations/edit') ?>&id=<?= (int) $reservation['id'] ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= url('reservations/delete') ?>" onsubmit="return confirm('Supprimer cette reservation ?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $reservation['id'] ?>"><button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button></form>
                    <?php endif; ?>
                    <?php if ($reservation['statut'] !== 'annulee'): ?>
                        <form method="post" action="<?= url('reservations/cancel') ?>" onsubmit="return confirm('Annuler cette reservation ?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $reservation['id'] ?>"><button class="btn btn-sm btn-outline-warning" title="Annuler"><i class="bi bi-x-lg"></i></button></form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../partials/pagination.php'; ?>

