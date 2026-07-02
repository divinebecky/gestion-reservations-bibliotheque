<?php
declare(strict_types=1);

final class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        if (is_post()) {
            verify_csrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['mot_de_passe'] ?? '';
            $user = (new User())->findByEmail($email);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => (int) $user['id'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'photo' => $user['photo'] ?? null,
                ];
                flash('success', 'Connexion reussie.');
                $this->redirect('dashboard');
            }

            flash('error', 'Email ou mot de passe incorrect.');
        }

        $view = 'auth/login';
        require __DIR__ . '/../views/layouts/auth.php';
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
        flash('success', 'Vous etes deconnecte.');
        $this->redirect('login');
    }
}
