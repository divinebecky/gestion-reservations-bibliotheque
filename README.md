<<<<<<< HEAD
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
=======
# 📚 Gestion des Réservations des Salles de Lecture d'une Bibliothèque Universitaire

## Description

Ce projet a été réalisé dans le cadre du cours de **Génie Logiciel**. Il consiste à concevoir et développer une application web permettant d'assurer la gestion des réservations des salles de lecture d'une bibliothèque universitaire.

L'objectif principal de cette application est d'automatiser le processus de réservation des salles, de faciliter la gestion des utilisateurs et d'améliorer l'organisation des ressources de la bibliothèque. L'application permet aux étudiants de consulter les salles disponibles et d'effectuer des réservations, tandis que les bibliothécaires et les administrateurs disposent de fonctionnalités avancées pour gérer les réservations, les salles et les utilisateurs.

---

# Fonctionnalités

## Authentification

- Connexion sécurisée des utilisateurs
- Déconnexion
- Gestion des sessions
- Gestion des rôles (Administrateur, Bibliothécaire, Étudiant)

## Gestion des utilisateurs

- Ajouter un utilisateur
- Modifier un utilisateur
- Supprimer un utilisateur
- Consulter la liste des utilisateurs

## Gestion des salles

- Ajouter une salle
- Modifier une salle
- Supprimer une salle
- Consulter les salles disponibles

## Gestion des réservations

- Réserver une salle
- Vérifier la disponibilité
- Consulter les réservations
- Annuler une réservation

## Tableau de bord

- Nombre total des salles
- Nombre total des réservations
- Nombre des utilisateurs
- Salles disponibles

## Statistiques

- Réservations par salle
- Réservations par période
- Taux d'occupation
- Salles les plus utilisées

---

# Technologies utilisées

## Front-end

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

## Back-end

- PHP 8

## Base de données

- MySQL

## Outils

- Replit
- GitHub
- Trello
- Draw.io
- Penpot

---

# Installation

## Prérequis

- PHP 8 ou supérieur
- MySQL
- Serveur Apache (XAMPP recommandé)
- Navigateur Web moderne

## Étapes d'installation

1. Cloner le dépôt GitHub :

```bash
git clone https://github.com/divinebecky/gestion-reservations-bibliotheque.git
```

2. Copier le projet dans le dossier :

```
xampp/htdocs/
```

3. Créer la base de données MySQL.

4. Importer le fichier :

```
database.sql
```

5. Configurer les paramètres de connexion dans :

```
config/database.php
```

6. Lancer Apache et MySQL.

7. Ouvrir le navigateur :

```
http://localhost/gestion-reservations-bibliotheque
```

---

# Structure du projet

```
gestion-reservations-bibliotheque/

├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── icons/
│
├── config/
│   └── database.php
│
├── controllers/
│
├── models/
│
├── views/
│
├── database/
│   └── database.sql
│
├── includes/
│
├── index.php
├── login.php
├── dashboard.php
├── logout.php
├── README.md
└── .gitignore
```

---

# Méthodologie

Le développement de cette application suit les différentes phases du cycle de développement logiciel (Software Development Life Cycle - SDLC) :

- Analyse des besoins
- Rédaction du cahier des charges
- Conception UML
- Maquettage des interfaces
- Développement
- Tests
- Déploiement

---

# Outils de gestion du projet

- Trello (Gestion des tâches)
- GitHub (Gestion des versions)
- Draw.io (Diagrammes UML)
- Penpot (Maquettes)
- Replit (Développement)

---

# Auteurs

**Bulabula Adalbert Serge**

Master en Génie Informatique

Projet réalisé dans le cadre du cours de **Génie Logiciel**.

---

# Licence

Ce projet est réalisé exclusivement à des fins académiques dans le cadre du cours de Génie Logiciel.

© 2026 Tous droits réservés.
>>>>>>> 7d5291cd0d8b186f56769b558ba9a40ae219822c
