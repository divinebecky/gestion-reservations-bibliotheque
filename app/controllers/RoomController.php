<?php
declare(strict_types=1);

final class RoomController extends Controller
{
    private Room $rooms;

    public function __construct()
    {
        $this->rooms = new Room();
    }

    public function index(): void
    {
        Auth::requireLogin();
        [$search, $page, $limit, $offset] = $this->paginationInput();
        $this->view('rooms/index', [
            'title' => 'Salles',
            'rooms' => $this->rooms->paginate($search, $limit, $offset),
            'total' => $this->rooms->count($search),
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function create(): void
    {
        Auth::requireRoles(['administrateur']);
        $room = ['numero' => '', 'nom' => '', 'capacite' => 1, 'localisation' => '', 'description' => '', 'etat' => 'disponible'];
        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->valid($data)) {
                $this->rooms->create($data);
                flash('success', 'Salle creee avec succes.');
                $this->redirect('rooms');
            }
            flash('error', 'Veuillez verifier les informations saisies.');
            $room = $data;
        }
        $this->view('rooms/form', ['title' => 'Nouvelle salle', 'room' => $room, 'isEdit' => false]);
    }

    public function edit(): void
    {
        Auth::requireRoles(['administrateur']);
        $id = (int) ($_GET['id'] ?? 0);
        $room = $this->rooms->find($id);
        if (!$room) {
            flash('error', 'Salle introuvable.');
            $this->redirect('rooms');
        }
        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->valid($data)) {
                $this->rooms->update($id, $data);
                flash('success', 'Salle mise a jour.');
                $this->redirect('rooms');
            }
            flash('error', 'Veuillez verifier les informations saisies.');
            $room = array_merge($room, $data);
        }
        $this->view('rooms/form', ['title' => 'Modifier salle', 'room' => $room, 'isEdit' => true]);
    }

    public function delete(): void
    {
        Auth::requireRoles(['administrateur']);
        verify_csrf();
        $this->rooms->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Salle supprimee.');
        $this->redirect('rooms');
    }

    private function payload(): array
    {
        return [
            'numero' => trim($_POST['numero'] ?? ''),
            'nom' => trim($_POST['nom'] ?? ''),
            'capacite' => max(1, (int) ($_POST['capacite'] ?? 1)),
            'localisation' => trim($_POST['localisation'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'etat' => $_POST['etat'] ?? 'disponible',
        ];
    }

    private function valid(array $data): bool
    {
        return $data['numero'] !== ''
            && $data['nom'] !== ''
            && $data['capacite'] > 0
            && in_array($data['etat'], ['disponible', 'indisponible', 'maintenance'], true);
    }

    private function paginationInput(): array
    {
        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 8;
        return [$search, $page, $limit, ($page - 1) * $limit];
    }
}

