<?php
declare(strict_types=1);

final class ProfileController extends Controller
{
    public function edit(): void
    {
        Auth::requireLogin();
        $model = new User();
        $user = $model->find(Auth::id());

        if (is_post()) {
            verify_csrf();
            $data = $this->payload();
            if ($this->validProfile($data)) {
                $photo = $this->uploadPhoto();
                if ($photo === false) {
                    flash('error', 'Photo invalide. Formats acceptes : JPG, PNG ou WEBP, 2 Mo maximum.');
                    $this->redirect('profile');
                }
                if ($photo !== null) {
                    $data['photo'] = $photo;
                }
                $model->updateProfile(Auth::id(), $data);
                $_SESSION['user']['nom'] = $data['nom'];
                $_SESSION['user']['prenom'] = $data['prenom'];
                $_SESSION['user']['email'] = $data['email'];
                if ($photo !== null) {
                    $_SESSION['user']['photo'] = $photo;
                }
                flash('success', 'Profil mis a jour.');
                $this->redirect('profile');
            }
            flash('error', 'Veuillez renseigner les champs obligatoires.');
        }

        $this->view('profile/edit', ['title' => 'Mon profil', 'user' => $user]);
    }

    private function payload(): array
    {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
        ];
    }

    private function validProfile(array $data): bool
    {
        return $data['nom'] !== '' && $data['prenom'] !== '' && filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    }

    private function uploadPhoto(): string|false|null
    {
        if (empty($_FILES['photo']['name'])) {
            return null;
        }

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK || $_FILES['photo']['size'] > 2 * 1024 * 1024) {
            return false;
        }

        $tmpName = $_FILES['photo']['tmp_name'];
        $mime = mime_content_type($tmpName);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensions[$mime])) {
            return false;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/profiles';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'user_' . Auth::id() . '_' . bin2hex(random_bytes(8)) . '.' . $extensions[$mime];
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpName, $target)) {
            return false;
        }

        return 'uploads/profiles/' . $fileName;
    }
}
