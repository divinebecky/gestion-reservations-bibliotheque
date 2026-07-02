USE gestion_salle;

ALTER TABLE utilisateurs
  ADD COLUMN IF NOT EXISTS photo VARCHAR(255) NULL AFTER telephone;

