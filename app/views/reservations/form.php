<form class="panel form-grid needs-validation" method="post" novalidate>
    <?= csrf_field() ?>
    <?php if (Auth::role() !== 'etudiant'): ?>
        <div>
            <label class="form-label">Utilisateur</label>
            <select class="form-select" name="utilisateur_id" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= (int) $user['id'] ?>" <?= (int) $reservation['utilisateur_id'] === (int) $user['id'] ? 'selected' : '' ?>><?= e($user['prenom'] . ' ' . $user['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="utilisateur_id" value="<?= Auth::id() ?>">
    <?php endif; ?>
    <div>
        <label class="form-label">Salle</label>
        <select class="form-select" name="salle_id" required>
            <option value="">Choisir une salle</option>
            <?php foreach ($rooms as $room): ?>
                <?php $selectedRoom = (int) ($_GET['room'] ?? $reservation['salle_id']) === (int) $room['id']; ?>
                <option value="<?= (int) $room['id'] ?>" <?= $selectedRoom ? 'selected' : '' ?>><?= e($room['numero'] . ' - ' . $room['nom'] . ' (' . $room['capacite'] . ' places)') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div><label class="form-label">Date</label><input class="form-control" type="date" name="date_reservation" value="<?= e($reservation['date_reservation']) ?>" required></div>
    <div><label class="form-label">Heure debut</label><input class="form-control" type="time" name="heure_debut" value="<?= e(substr($reservation['heure_debut'], 0, 5)) ?>" required></div>
    <div><label class="form-label">Heure fin</label><input class="form-control" type="time" name="heure_fin" value="<?= e(substr($reservation['heure_fin'], 0, 5)) ?>" required></div>
    <?php if (Auth::role() !== 'etudiant'): ?>
        <div>
            <label class="form-label">Statut</label>
            <select class="form-select" name="statut">
                <?php foreach (['en_attente', 'validee', 'annulee', 'refusee'] as $statut): ?>
                    <option value="<?= $statut ?>" <?= $reservation['statut'] === $statut ? 'selected' : '' ?>><?= e($statut) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="statut" value="en_attente">
    <?php endif; ?>
    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('reservations') ?>">Annuler</a>
        <button class="btn btn-primary" type="submit"><i class="bi bi-calendar-plus me-1"></i>Enregistrer</button>
    </div>
</form>

