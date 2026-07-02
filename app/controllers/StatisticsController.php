<?php
declare(strict_types=1);

final class StatisticsController extends Controller
{
    public function index(): void
    {
        Auth::requireRoles(['bibliothecaire', 'administrateur']);
        $reservation = new Reservation();
        $this->view('statistics/index', [
            'title' => 'Statistiques',
            'stats' => (new Stats())->dashboard(),
            'monthlyStats' => array_reverse($reservation->monthlyStats()),
            'statusStats' => $reservation->statusStats(),
        ]);
    }
}

