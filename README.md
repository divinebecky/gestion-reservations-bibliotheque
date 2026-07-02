# GestionSalle

Application web MVC en PHP 8 pour la gestion des reservations des salles de lecture d'une bibliotheque universitaire.

## Technologies

- PHP 8 avec PDO
- MySQL
- HTML5, CSS3, JavaScript
- Bootstrap 5, Bootstrap Icons, Chart.js

## Installation XAMPP

1. Copier le projet dans `C:\xampp\htdocs\GestionSalle`.
2. Demarrer Apache et MySQL depuis XAMPP.
3. Creer la base automatiquement en ouvrant :

```text
http://localhost/GestionSalle/public/install.php
```

Ou importer manuellement `database/gestion_salle.sql` dans phpMyAdmin, ou lancer :

```bash
mysql -u root < database/gestion_salle.sql
```

4. Verifier les identifiants MySQL dans `config/config.php`.
5. Ouvrir :

```text
http://localhost/GestionSalle/public/index.php
```

Si la base existait deja avant l'ajout des photos de profil, lancer une fois :

```bash
mysql -u root gestion_salle < database/migrations/2026_07_02_add_user_photo.sql
```

## Comptes de test

Tous utilisent le mot de passe `password`.

- Administrateur : `admin@univ.test`
- Bibliothecaire : `luc@univ.test`
- Etudiant : `amina@univ.test`

## Architecture

```text
app/
  controllers/  Controleurs MVC
  core/         Classes Controller, Model, Auth
  helpers.php   Fonctions URL, flash, CSRF, echappement
  models/       Acces aux donnees
  views/        Pages et layouts
config/         Configuration application et PDO
database/       Script SQL de creation
public/         Point d'entree et assets
```

## Securite

- Mots de passe hashes avec `password_hash()`.
- Sessions PHP avec regeneration d'identifiant a la connexion.
- Protection CSRF sur les formulaires.
- Requetes preparees PDO contre les injections SQL.
- Controle des roles sur les pages protegees.
- Echappement HTML avec `htmlspecialchars()`.

## Fonctionnalites

- Authentification et deconnexion.
- CRUD utilisateurs pour administrateur.
- CRUD salles pour administrateur.
- Creation, validation, modification, annulation et suppression de reservations selon le role.
- Verification des conflits de creneaux pour empecher les doubles reservations.
- Recherche dynamique locale, tri de tableaux et pagination serveur.
- Tableau de bord avec cartes statistiques et graphiques.
- Rapport imprimable pour administrateur.

## Configuration Replit

Definir les variables d'environnement si la base n'utilise pas les valeurs par defaut :

```text
APP_BASE_URL=/public
DB_HOST=...
DB_NAME=gestion_salle
DB_USER=...
DB_PASS=...
```

Puis importer `database/gestion_salle.sql` dans le serveur MySQL disponible.
