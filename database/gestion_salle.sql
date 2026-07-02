CREATE DATABASE IF NOT EXISTS gestion_salle
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_salle;

CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('etudiant', 'bibliothecaire', 'administrateur') NOT NULL DEFAULT 'etudiant',
  telephone VARCHAR(30) NULL,
  photo VARCHAR(255) NULL,
  date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE utilisateurs ADD COLUMN IF NOT EXISTS photo VARCHAR(255) NULL AFTER telephone;

CREATE TABLE IF NOT EXISTS salles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(30) NOT NULL UNIQUE,
  nom VARCHAR(120) NOT NULL,
  capacite INT NOT NULL,
  localisation VARCHAR(160) NULL,
  description TEXT NULL,
  etat ENUM('disponible', 'indisponible', 'maintenance') NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  salle_id INT NOT NULL,
  date_reservation DATE NOT NULL,
  heure_debut TIME NOT NULL,
  heure_fin TIME NOT NULL,
  statut ENUM('en_attente', 'validee', 'annulee', 'refusee') NOT NULL DEFAULT 'en_attente',
  date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reservations_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  CONSTRAINT fk_reservations_salle FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE CASCADE,
  INDEX idx_creneau (salle_id, date_reservation, heure_debut, heure_fin, statut)
) ENGINE=InnoDB;

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, telephone) VALUES
('Admin', 'Systeme', 'admin@univ.test', '$2y$10$7rpGz0j5HAGftM5rA1tcx.qsyWe.96DYhPaoNlgWrZQzrWBmw0K5W', 'administrateur', '0100000000'),
('Diallo', 'Amina', 'amina@univ.test', '$2y$10$7rpGz0j5HAGftM5rA1tcx.qsyWe.96DYhPaoNlgWrZQzrWBmw0K5W', 'etudiant', '0200000000'),
('Martin', 'Luc', 'luc@univ.test', '$2y$10$7rpGz0j5HAGftM5rA1tcx.qsyWe.96DYhPaoNlgWrZQzrWBmw0K5W', 'bibliothecaire', '0300000000')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO salles (numero, nom, capacite, localisation, description, etat) VALUES
('A101', 'Salle silencieuse', 24, 'Batiment A - Niveau 1', 'Salle adaptee au travail individuel.', 'disponible'),
('B205', 'Salle groupe', 12, 'Batiment B - Niveau 2', 'Espace pour travaux collaboratifs.', 'disponible'),
('C010', 'Salle multimedia', 18, 'Batiment C - Rez-de-chaussee', 'Salle equipee pour les ressources numeriques.', 'maintenance')
ON DUPLICATE KEY UPDATE numero = numero;
