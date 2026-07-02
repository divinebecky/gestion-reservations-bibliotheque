<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="mini-stat"><span>Utilisateurs</span><strong><?= (int) $stats['users'] ?></strong></div></div>
    <div class="col-md-3"><div class="mini-stat"><span>Salles</span><strong><?= (int) $stats['rooms'] ?></strong></div></div>
    <div class="col-md-3"><div class="mini-stat"><span>Reservations</span><strong><?= (int) $stats['reservations'] ?></strong></div></div>
    <div class="col-md-3"><div class="mini-stat"><span>Disponibles</span><strong><?= (int) $stats['available_rooms'] ?></strong></div></div>
</div>
<div class="row g-4">
    <div class="col-lg-7"><div class="panel"><div class="panel-title">Evolution mensuelle</div><canvas id="monthlyChart" data-labels='<?= e(json_encode(array_column($monthlyStats, 'mois'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($monthlyStats, 'total')))) ?>'></canvas></div></div>
    <div class="col-lg-5"><div class="panel"><div class="panel-title">Repartition par statut</div><canvas id="statusChart" data-labels='<?= e(json_encode(array_column($statusStats, 'statut'))) ?>' data-values='<?= e(json_encode(array_map('intval', array_column($statusStats, 'total')))) ?>'></canvas></div></div>
</div>

