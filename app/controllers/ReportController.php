<?php
declare(strict_types=1);

final class ReportController extends Controller
{
    public function index(): void
    {
        Auth::requireRoles(['administrateur']);
        $this->view('reports/index', [
            'title' => 'Rapports',
            'stats' => (new Stats())->dashboard(),
            'monthlyStats' => array_reverse((new Reservation())->monthlyStats()),
        ]);
    }
}

