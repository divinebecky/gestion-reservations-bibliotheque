<main class="login-wrap">
    <form class="login-card needs-validation" method="post" novalidate>
        <?= csrf_field() ?>
        <center>
        <div class="login-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
        <h1>Connexion</h1>
        <p>Gestion des reservations des salles de lecture</p>
        </center>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" required autofocus>
            <div class="invalid-feedback">Email obligatoire.</div>
        </div>
        <div class="mb-4">
            <label class="form-label" for="mot_de_passe">Mot de passe</label>
            <input class="form-control" type="password" id="mot_de_passe" name="mot_de_passe" required>
            <div class="invalid-feedback">Mot de passe obligatoire.</div>
        </div>
        <button class="btn btn-primary w-100" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Se connecter</button>
        <div class="demo-box">
            <span>Comptes de test</span>
            <code>admin@univ.test / 1234</code>
        </div>
    </form>
</main>

