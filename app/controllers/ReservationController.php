<?php
declare(strict_types=1);

final class ReservationController extends Controller
{
    private Reservation $reservations;
    private Room $rooms;

    public function __construct()
    {
        $this->reservations = new Reservation();
        $this->rooms = new Room();
    }

    public function index(): void
    {
        Auth::requireLogin();
        [$search, $page, $limit, $offset] = $this->paginationInput();
        $userId = Auth::role() === 'etudiant' ? Auth::id() : null;
        $this->view('reservations/index', [
            'title' => 'Reservations',
            'reservations' => $this->reservations->paginate($search, $limit, $offset, $userId),
            'total' => $this->reservations->count($search, $userId),
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $reservation = [
            'utilisateur_id' => Auth::id(),
            'salle_id' => '',
            'date_reservation' => date('Y-m-d'),
            'heure_debut' => '08:00',
            'heure_fin' => '10:00',
            'statut' => 'en_attente',
        ];

        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if (Auth::role() === 'etudiant') {
                $data['utilisateur_id'] = Auth::id();
                $data['statut'] = 'en_attente';
            }

            if ($this->valid($data) && !$this->reservations->hasConflict((int) $data['salle_id'], $data['date_reservation'], $data['heure_debut'], $data['heure_fin'])) {
                $this->reservations->create($data);
                flash('success', 'Reservation enregistree.');
                $this->redirect('reservations');
            }

            flash('error', 'La salle est deja reservee sur ce creneau ou les informations sont invalides.');
            $reservation = $data;
        }

        $this->view('reservations/form', [
            'title' => 'Nouvelle reservation',
            'reservation' => $reservation,
            'rooms' => $this->rooms->allAvailable(),
            'users' => Auth::role() === 'etudiant' ? [] : $this->allUsers(),
            'isEdit' => false,
        ]);
    }

    public function edit(): void
    {
        Auth::requireRoles(['bibliothecaire', 'administrateur']);
        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->reservations->find($id);
        if (!$reservation) {
            flash('error', 'Reservation introuvable.');
            $this->redirect('reservations');
        }

        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->valid($data) && !$this->reservations->hasConflict((int) $data['salle_id'], $data['date_reservation'], $data['heure_debut'], $data['heure_fin'], $id)) {
                $this->reservations->update($id, $data);
                flash('success', 'Reservation mise a jour.');
                $this->redirect('reservations');
            }
            flash('error', 'Creneau indisponible ou informations invalides.');
            $reservation = array_merge($reservation, $data);
        }

        $this->view('reservations/form', [
            'title' => 'Modifier reservation',
            'reservation' => $reservation,
            'rooms' => $this->rooms->allAvailable(),
            'users' => $this->allUsers(),
            'isEdit' => true,
        ]);
    }

    public function cancel(): void
    {
        Auth::requireLogin();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $reservation = $this->reservations->find($id);
        if (!$reservation || (Auth::role() === 'etudiant' && (int) $reservation['utilisateur_id'] !== Auth::id())) {
            flash('error', 'Action non autorisee.');
            $this->redirect('reservations');
        }
        $this->reservations->setStatus($id, 'annulee');
        flash('success', 'Reservation annulee.');
        $this->redirect('reservations');
    }

    public function validateReservation(): void
    {
        Auth::requireRoles(['bibliothecaire', 'administrateur']);
        verify_csrf();
        $this->reservations->setStatus((int) ($_POST['id'] ?? 0), 'validee');
        flash('success', 'Reservation validee.');
        $this->redirect('reservations');
    }

    public function delete(): void
    {
        Auth::requireRoles(['bibliothecaire', 'administrateur']);
        verify_csrf();
        $this->reservations->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Reservation supprimee.');
        $this->redirect('reservations');
    }

    private function payload(): array
    {
        return [
            'utilisateur_id' => (int) ($_POST['utilisateur_id'] ?? Auth::id()),
            'salle_id' => (int) ($_POST['salle_id'] ?? 0),
            'date_reservation' => $_POST['date_reservation'] ?? '',
            'heure_debut' => $_POST['heure_debut'] ?? '',
            'heure_fin' => $_POST['heure_fin'] ?? '',
            'statut' => $_POST['statut'] ?? 'en_attente',
        ];
    }

    private function valid(array $data): bool
    {
        return $data['utilisateur_id'] > 0
            && $data['salle_id'] > 0
            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_reservation']) === 1
            && $data['heure_debut'] < $data['heure_fin']
            && in_array($data['statut'], ['en_attente', 'validee', 'annulee', 'refusee'], true);
    }

    private function allUsers(): array
    {
        return (new User())->paginate('', 1000, 0);
    }

    private function paginationInput(): array
    {
        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 8;
        return [$search, $page, $limit, ($page - 1) * $limit];
    }
}

