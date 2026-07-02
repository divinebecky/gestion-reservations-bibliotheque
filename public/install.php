<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion au serveur MySQL sans selectionner de base afin de pouvoir la creer.
        $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $sql = file_get_contents(__DIR__ . '/../database/gestion_salle.sql');
        if ($sql === false) {
            throw new RuntimeException('Fichier SQL introuvable.');
        }

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            $pdo->exec($statement);
        }

        $success = true;
        $message = 'Base de donnees creee et donnees de test importees.';
    } catch (Throwable $exception) {
        $message = 'Erreur installation : ' . $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installation - <?= htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 640px;">
        <div class="card-body p-4">
            <h1 class="h3 mb-3">Installation de la base MySQL</h1>
            <p class="text-secondary">Cette page cree la base <code><?= htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8') ?></code>, les tables et les comptes de test.</p>
            <?php if ($message !== ''): ?>
                <div class="alert alert-<?= $success ? 'success' : 'danger' ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form method="post">
                <button class="btn btn-primary" type="submit">Installer maintenant</button>
                <a class="btn btn-light" href="index.php">Aller a l'application</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>

