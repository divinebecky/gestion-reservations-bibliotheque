<div class="toolbar">
    <form class="search-form" method="get">
        <input type="hidden" name="route" value="users">
        <i class="bi bi-search"></i>
        <input class="form-control live-search" name="q" value="<?= e($search) ?>" placeholder="Rechercher un utilisateur">
    </form>
    <a class="btn btn-primary" href="<?= url('users/create') ?>"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
</div>

<div class="table-responsive panel">
    <table class="table table-hover align-middle sortable-table">
        <thead><tr><th>Utilisateur</th><th>Email</th><th>Role</th><th>Telephone</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <div class="user-cell">
                        <?php if (!empty($user['photo'])): ?>
                            <img src="<?= public_file($user['photo']) ?>" alt="Photo de <?= e($user['prenom']) ?>">
                        <?php else: ?>
                            <span><?= e(strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? '', 0, 1))) ?></span>
                        <?php endif; ?>
                        <strong><?= e($user['prenom'] . ' ' . $user['nom']) ?></strong>
                    </div>
                </td>
                <td><?= e($user['email']) ?></td>
                <td><span class="badge text-bg-secondary"><?= e($user['role']) ?></span></td>
                <td><?= e($user['telephone']) ?></td>
                <td><?= e($user['date_creation']) ?></td>
                <td class="actions">
                    <a class="btn btn-sm btn-outline-primary" href="<?= url('users/edit') ?>&id=<?= (int) $user['id'] ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="<?= url('users/delete') ?>" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../partials/pagination.php'; ?>
