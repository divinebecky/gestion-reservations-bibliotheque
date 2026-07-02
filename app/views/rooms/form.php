<form class="panel form-grid needs-validation" method="post" novalidate>
    <?= csrf_field() ?>
    <div><label class="form-label">Numero</label><input class="form-control" name="numero" value="<?= e($room['numero']) ?>" required></div>
    <div><label class="form-label">Nom</label><input class="form-control" name="nom" value="<?= e($room['nom']) ?>" required></div>
    <div><label class="form-label">Capacite</label><input class="form-control" type="number" min="1" name="capacite" value="<?= (int) $room['capacite'] ?>" required></div>
    <div><label class="form-label">Localisation</label><input class="form-control" name="localisation" value="<?= e($room['localisation']) ?>"></div>
    <div>
        <label class="form-label">Etat</label>
        <select class="form-select" name="etat">
            <?php foreach (['disponible', 'indisponible', 'maintenance'] as $etat): ?>
                <option value="<?= $etat ?>" <?= $room['etat'] === $etat ? 'selected' : '' ?>><?= ucfirst($etat) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="grid-full"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4"><?= e($room['description']) ?></textarea></div>
    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('rooms') ?>">Annuler</a>
        <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Enregistrer</button>
    </div>
</form>

