<?php
declare(strict_types=1);

final class UserController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): void
    {
        Auth::requireRoles(['administrateur']);
        [$search, $page, $limit, $offset] = $this->paginationInput();
        $this->view('users/index', [
            'title' => 'Gestion des utilisateurs',
            'users' => $this->users->paginate($search, $limit, $offset),
            'total' => $this->users->count($search),
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function create(): void
    {
        Auth::requireRoles(['administrateur']);
        $user = ['nom' => '', 'prenom' => '', 'email' => '', 'role' => 'etudiant', 'telephone' => ''];
        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->valid($data, true)) {
                $this->users->create($data);
                flash('success', 'Utilisateur cree avec succes.');
                $this->redirect('users');
            }
            flash('error', 'Veuillez verifier les informations saisies.');
            $user = $data;
        }
        $this->view('users/form', ['title' => 'Nouvel utilisateur', 'user' => $user, 'isEdit' => false]);
    }

    public function edit(): void
    {
        Auth::requireRoles(['administrateur']);
        $id = (int) ($_GET['id'] ?? 0);
        $user = $this->users->find($id);
        if (!$user) {
            flash('error', 'Utilisateur introuvable.');
            $this->redirect('users');
        }
        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->valid($data, false)) {
                $this->users->update($id, $data);
                flash('success', 'Utilisateur mis a jour.');
                $this->redirect('users');
            }
            flash('error', 'Veuillez verifier les informations saisies.');
            $user = array_merge($user, $data);
        }
        $this->view('users/form', ['title' => 'Modifier utilisateur', 'user' => $user, 'isEdit' => true]);
    }

    public function delete(): void
    {
        Auth::requireRoles(['administrateur']);
        verify_csrf();
        $this->users->delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Utilisateur supprime.');
        $this->redirect('users');
    }

    private function payload(): array
    {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'etudiant',
            'telephone' => trim($_POST['telephone'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
        ];
    }

    private function valid(array $data, bool $passwordRequired): bool
    {
        return $data['nom'] !== ''
            && $data['prenom'] !== ''
            && filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            && in_array($data['role'], ['etudiant', 'bibliothecaire', 'administrateur'], true)
            && (!$passwordRequired || strlen($data['mot_de_passe']) >= 6);
    }

    private function paginationInput(): array
    {
        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 8;
        return [$search, $page, $limit, ($page - 1) * $limit];
    }
}

