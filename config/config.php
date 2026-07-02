<?php
declare(strict_types=1);

// Configuration generale de l'application.
define('APP_NAME', 'Gestion des salles de lecture');
define('BASE_URL', getenv('APP_BASE_URL') ?: '/GestionSalle/public');

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'gestion_salle');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

date_default_timezone_set('Africa/Lagos');

