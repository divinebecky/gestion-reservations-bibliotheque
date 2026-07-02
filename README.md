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
