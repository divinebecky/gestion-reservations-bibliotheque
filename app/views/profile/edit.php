<form class="panel form-grid needs-validation" method="post" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <div class="grid-full profile-photo-editor">
        <div class="profile-photo-preview">
            <?php if (!empty($user['photo'])): ?>
                <img id="photoPreview" src="<?= public_file($user['photo']) ?>" alt="Photo de profil actuelle">
            <?php else: ?>
                <span id="photoFallback"><?= e(strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? '', 0, 1))) ?></span>
                <img id="photoPreview" src="" alt="Apercu de la photo" hidden>
            <?php endif; ?>
        </div>
        <div>
            <label class="form-label" for="photo">Photo de profil</label>
            <input class="form-control" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">Formats acceptes : JPG, PNG ou WEBP. Taille maximale : 2 Mo.</div>
        </div>
    </div>
    <div><label class="form-label">Nom</label><input class="form-control" name="nom" value="<?= e($user['nom']) ?>" required></div>
    <div><label class="form-label">Prenom</label><input class="form-control" name="prenom" value="<?= e($user['prenom']) ?>" required></div>
    <div><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($user['email']) ?>" required></div>
    <div><label class="form-label">Telephone</label><input class="form-control" name="telephone" value="<?= e($user['telephone']) ?>"></div>
    <div class="grid-full"><label class="form-label">Nouveau mot de passe</label><input class="form-control" type="password" name="mot_de_passe" minlength="6"></div>
    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('dashboard') ?>">Retour</a>
        <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Enregistrer</button>
    </div>
</form>
