<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="panel-title mb-0">Rapport synthetique</div>
        <button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Imprimer</button>
    </div>
    <div class="report-grid">
        <div><span>Utilisateurs</span><strong><?= (int) $stats['users'] ?></strong></div>
        <div><span>Salles</span><strong><?= (int) $stats['rooms'] ?></strong></div>
        <div><span>Reservations</span><strong><?= (int) $stats['reservations'] ?></strong></div>
        <div><span>Salles disponibles</span><strong><?= (int) $stats['available_rooms'] ?></strong></div>
    </div>
    <hr>
    <h2 class="h5">Reservations par mois</h2>
    <table class="table">
        <thead><tr><th>Mois</th><th>Total</th></tr></thead>
        <tbody>
        <?php foreach ($monthlyStats as $row): ?>
            <tr><td><?= e($row['mois']) ?></td><td><?= (int) $row['total'] ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

