<div class="row g-3 mb-4">
    <?php foreach ($cards as $card): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card <?= $card[3] ?>">
                <i class="bi <?= $card[2] ?>"></i>
                <div><span><?= e($card[0]) ?></span><strong><?= (int) $card[1] ?></strong></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title">Réservationsaaa par mois</div>
            <canvas id="monthlyChart" data-labels='<?= e(json_encode(array_column($monthlyStats, 'mois'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($monthlyStats, 'total')))) ?>'></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title">Statuts</div>
            <canvas id="statusChart" data-labels='<?= e(json_encode(array_column($statusStats, 'statut'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($statusStats, 'total')))) ?>'></canvas>
        </div>
    </div>
</div>

<?php if ($role === 'etudiant'): ?>
    <div class="row g-4 mt-1">
        <div class="col-lg-5">
            <div class="panel h-100">
                <div class="panel-title">Mes prochaines reservations</div>
                <?php if ($upcomingReservations === []): ?>
                    <p class="empty-state">Aucune reservation a venir.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush dashboard-list">
                        <?php foreach ($upcomingReservations as $reservation): ?>
                            <a class="list-group-item list-group-item-action" href="<?= url('reservations') ?>">
                                <div>
                                    <strong><?= e($reservation['numero'] . ' - ' . $reservation['salle']) ?></strong>
                                    <span><?= e($reservation['date_reservation']) ?>, <?= e(substr($reservation['heure_debut'], 0, 5)) ?> - <?= e(substr($reservation['heure_fin'], 0, 5)) ?></span>
                                </div>
                                <span class="badge status-<?= e($reservation['statut']) ?>"><?= e($reservation['statut']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="panel-title mb-0">Historique recent</div>
                    <a class="btn btn-sm btn-outline-primary" href="<?= url('reservations') ?>">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Salle</th><th>Date</th><th>Horaire</th><th>Statut</th></tr></thead>
                        <tbody>
                        <?php foreach ($recentReservations as $reservation): ?>
                            <tr>
                                <td><?= e($reservation['numero'] . ' - ' . $reservation['salle']) ?></td>
                                <td><?= e($reservation['date_reservation']) ?></td>
                                <td><?= e(substr($reservation['heure_debut'], 0, 5)) ?> - <?= e(substr($reservation['heure_fin'], 0, 5)) ?></td>
                                <td><span class="badge status-<?= e($reservation['statut']) ?>"><?= e($reservation['statut']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="panel mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="panel-title mb-0"><?= $role === 'bibliothecaire' ? 'Dernieres reservations a suivre' : 'Dernieres reservations' ?></div>
            <a class="btn btn-sm btn-outline-primary" href="<?= url('reservations') ?>">Gerer</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead><tr><th>Utilisateur</th><th>Salle</th><th>Date</th><th>Horaire</th><th>Statut</th></tr></thead>
                <tbody>
                <?php foreach ($recentReservations as $reservation): ?>
                    <tr>
                        <td><?= e($reservation['utilisateur']) ?></td>
                        <td><?= e($reservation['numero'] . ' - ' . $reservation['salle']) ?></td>
                        <td><?= e($reservation['date_reservation']) ?></td>
                        <td><?= e(substr($reservation['heure_debut'], 0, 5)) ?> - <?= e(substr($reservation['heure_fin'], 0, 5)) ?></td>
                        <td><span class="badge status-<?= e($reservation['statut']) ?>"><?= e($reservation['statut']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
