<form class="panel form-grid needs-validation" method="post" novalidate>
    <?= csrf_field() ?>
    <div>
        <label class="form-label">Nom</label>
        <input class="form-control" name="nom" value="<?= e($user['nom']) ?>" required>
    </div>
    <div>
        <label class="form-label">Prenom</label>
        <input class="form-control" name="prenom" value="<?= e($user['prenom']) ?>" required>
    </div>
    <div>
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?= e($user['email']) ?>" required>
    </div>
    <div>
        <label class="form-label">Telephone</label>
        <input class="form-control" name="telephone" value="<?= e($user['telephone']) ?>">
    </div>
    <div>
        <label class="form-label">Role</label>
        <select class="form-select" name="role" required>
            <?php foreach (['etudiant', 'bibliothecaire', 'administrateur'] as $roleOption): ?>
                <option value="<?= $roleOption ?>" <?= $user['role'] === $roleOption ? 'selected' : '' ?>><?= ucfirst($roleOption) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="form-label">Mot de passe <?= $isEdit ? '(laisser vide si inchange)' : '' ?></label>
        <input class="form-control" type="password" name="mot_de_passe" <?= $isEdit ? '' : 'required minlength="6"' ?>>
    </div>
    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('users') ?>">Annuler</a>
        <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Enregistrer</button>
    </div>
</form>

