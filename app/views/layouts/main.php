<?php
$currentRoute = $_GET['route'] ?? 'dashboard';
$content = __DIR__ . '/../' . $view . '.php';
$role = Auth::role();
$currentUser = Auth::user();
$fullName = trim(($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''));
$initials = strtoupper(substr($currentUser['prenom'] ?? 'U', 0, 1) . substr($currentUser['nom'] ?? '', 0, 1));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? APP_NAME) ?> - <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="page-progress" id="pageProgress" aria-hidden="true"><span></span></div>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand"><span class="brand-mark"><i class="bi bi-journal-bookmark-fill"></i></span><span>Bibliotheque</span></div>
        <a class="sidebar-user" href="<?= url('profile') ?>">
            <?php if (!empty($currentUser['photo'])): ?>
                <img src="<?= public_file($currentUser['photo']) ?>" alt="Photo de profil">
            <?php else: ?>
                <span class="avatar-fallback"><?= e($initials) ?></span>
            <?php endif; ?>
            <span>
                <strong><?= e($fullName) ?></strong>
                <small><?= e($role ?? '') ?></small>
            </span>
        </a>
        <div class="nav-section-label">Navigation</div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link <?= $currentRoute === 'dashboard' ? 'active' : '' ?>" href="<?= url('dashboard') ?>"><i class="bi bi-speedometer2"></i>Tableau de bord</a>
            <a class="nav-link <?= str_starts_with($currentRoute, 'rooms') ? 'active' : '' ?>" href="<?= url('rooms') ?>"><i class="bi bi-door-open"></i>Salles</a>
            <a class="nav-link <?= str_starts_with($currentRoute, 'reservations') ? 'active' : '' ?>" href="<?= url('reservations') ?>"><i class="bi bi-calendar-check"></i>Reservations</a>
            <?php if ($role === 'administrateur'): ?>
                <a class="nav-link <?= str_starts_with($currentRoute, 'users') ? 'active' : '' ?>" href="<?= url('users') ?>"><i class="bi bi-people"></i>Utilisateurs</a>
            <?php endif; ?>
            <?php if (in_array($role, ['bibliothecaire', 'administrateur'], true)): ?>
                <a class="nav-link <?= $currentRoute === 'statistics' ? 'active' : '' ?>" href="<?= url('statistics') ?>"><i class="bi bi-bar-chart"></i>Statistiques</a>
            <?php endif; ?>
            <?php if ($role === 'administrateur'): ?>
                <a class="nav-link <?= $currentRoute === 'reports' ? 'active' : '' ?>" href="<?= url('reports') ?>"><i class="bi bi-file-earmark-text"></i>Rapports</a>
            <?php endif; ?>
        </nav>
    </aside>

    <main class="main">
        <header class="topbar">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggle" type="button"><i class="bi bi-list"></i></button>
            <div>
                <h1><?= e($title ?? APP_NAME) ?></h1>
                <p><?= e($_SESSION['user']['prenom'] ?? '') ?> <?= e($_SESSION['user']['nom'] ?? '') ?> - <?= e($role ?? '') ?></p>
            </div>
            <div class="topbar-actions ms-auto">
                <a class="btn btn-primary" href="<?= url('reservations/create') ?>"><i class="bi bi-calendar-plus me-1"></i>Reserver</a>
                <a class="btn btn-light icon-btn" href="<?= url('profile') ?>" title="Profil"><i class="bi bi-person-circle"></i></a>
                <a class="btn btn-danger icon-btn" href="<?= url('logout') ?>" title="Deconnexion"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </header>

        <section class="content">
            <?php foreach (['success' => 'success', 'error' => 'danger'] as $flashType => $alert): ?>
                <?php if ($message = get_flash($flashType)): ?>
                    <div class="alert alert-<?= $alert ?> alert-dismissible fade show" role="alert">
                        <?= e($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php require $content; ?>
        </section>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
