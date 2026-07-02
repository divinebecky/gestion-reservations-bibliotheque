<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Auth.php';

spl_autoload_register(static function (string $class): void {
    foreach (['controllers', 'models'] as $folder) {
        $file = __DIR__ . '/../app/' . $folder . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$route = $_GET['route'] ?? 'dashboard';

$routes = [
    'login' => [AuthController::class, 'login'],
    'logout' => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],
    'profile' => [ProfileController::class, 'edit'],
    'users' => [UserController::class, 'index'],
    'users/create' => [UserController::class, 'create'],
    'users/edit' => [UserController::class, 'edit'],
    'users/delete' => [UserController::class, 'delete'],
    'rooms' => [RoomController::class, 'index'],
    'rooms/create' => [RoomController::class, 'create'],
    'rooms/edit' => [RoomController::class, 'edit'],
    'rooms/delete' => [RoomController::class, 'delete'],
    'reservations' => [ReservationController::class, 'index'],
    'reservations/create' => [ReservationController::class, 'create'],
    'reservations/edit' => [ReservationController::class, 'edit'],
    'reservations/delete' => [ReservationController::class, 'delete'],
    'reservations/cancel' => [ReservationController::class, 'cancel'],
    'reservations/validate' => [ReservationController::class, 'validateReservation'],
    'statistics' => [StatisticsController::class, 'index'],
    'reports' => [ReportController::class, 'index'],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    require __DIR__ . '/../app/views/errors/404.php';
    exit;
}

[$controllerClass, $method] = $routes[$route];
(new $controllerClass())->$method();

