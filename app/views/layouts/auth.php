<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body class="auth-page">
    <?php foreach (['success' => 'success', 'error' => 'danger'] as $flashType => $alert): ?>
        <?php if ($message = get_flash($flashType)): ?>
            <div class="alert alert-<?= $alert ?> position-fixed top-0 start-50 translate-middle-x mt-3 shadow"><?= e($message) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php require __DIR__ . '/../' . $view . '.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

