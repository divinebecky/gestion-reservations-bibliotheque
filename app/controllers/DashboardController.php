<?php
declare(strict_types=1);

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $reservation = new Reservation();
        $role = Auth::role();
        $room = new Room();
        $data = [
            'title' => 'Tableau de bord',
            'role' => $role,
        ];

        if ($role === 'etudiant') {
            $userId = Auth::id();
            $data += [
                'cards' => [
                    ['Mes reservations', $reservation->count('', $userId), 'bi-calendar-check', 'text-bg-primary'],
                    ['En attente', $reservation->countByStatus('en_attente', $userId), 'bi-hourglass-split', 'text-bg-warning'],
                    ['Validees', $reservation->countByStatus('validee', $userId), 'bi-check2-circle', 'text-bg-success'],
                    ['Salles disponibles', $room->availableCount(), 'bi-door-open', 'text-bg-info'],
                ],
                'monthlyStats' => array_reverse($reservation->monthlyStatsByUser($userId)),
                'statusStats' => $reservation->statusStatsByUser($userId),
                'upcomingReservations' => $reservation->upcomingForUser($userId),
                'recentReservations' => $reservation->recentForUser($userId),
            ];
        } elseif ($role === 'bibliothecaire') {
            $data += [
                'cards' => [
                    ['Reservations a valider', $reservation->countByStatus('en_attente'), 'bi-hourglass-split', 'text-bg-warning'],
                    ['Reservations du jour', $reservation->todayCount(), 'bi-calendar-day', 'text-bg-primary'],
                    ['Salles disponibles', $room->availableCount(), 'bi-door-open', 'text-bg-success'],
                    ['Reservations validees', $reservation->countByStatus('validee'), 'bi-check2-circle', 'text-bg-info'],
                ],
                'monthlyStats' => array_reverse($reservation->monthlyStats()),
                'statusStats' => $reservation->statusStats(),
                'recentReservations' => $reservation->recentAll(),
            ];
        } else {
            $stats = (new Stats())->dashboard();
            $data += [
                'cards' => [
                    ['Utilisateurs', $stats['users'], 'bi-people', 'text-bg-primary'],
                    ['Salles', $stats['rooms'], 'bi-door-open', 'text-bg-success'],
                    ['Reservations', $stats['reservations'], 'bi-calendar-check', 'text-bg-warning'],
                    ['Salles disponibles', $stats['available_rooms'], 'bi-check2-circle', 'text-bg-info'],
                ],
                'monthlyStats' => array_reverse($reservation->monthlyStats()),
                'statusStats' => $reservation->statusStats(),
                'recentReservations' => $reservation->recentAll(),
            ];
        }

        $this->view('dashboard/index', $data);
    }
}
