<?php
declare(strict_types=1);

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/layouts/main.php';
    }

    protected function redirect(string $route): void
    {
        header('Location: ' . url($route));
        exit;
    }
}

