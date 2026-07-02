<?php
declare(strict_types=1);

final class Reservation extends Model
{
    public function paginate(string $search, int $limit, int $offset, ?int $userId = null): array
    {
        $where = '(u.nom LIKE :search_nom OR u.prenom LIKE :search_prenom OR s.nom LIKE :search_salle OR s.numero LIKE :search_numero OR r.statut LIKE :search_statut)';
        if ($userId !== null) {
            $where .= ' AND r.utilisateur_id = :user_id';
        }

        $sql = "SELECT r.*, CONCAT(u.prenom, ' ', u.nom) AS utilisateur, s.nom AS salle, s.numero
                FROM reservations r
                JOIN utilisateurs u ON u.id = r.utilisateur_id
                JOIN salles s ON s.id = r.salle_id
                WHERE $where
                ORDER BY r.date_reservation DESC, r.heure_debut DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $this->bindSearch($stmt, $search);
        if ($userId !== null) {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(string $search = '', ?int $userId = null): int
    {
        $where = '(u.nom LIKE :search_nom OR u.prenom LIKE :search_prenom OR s.nom LIKE :search_salle OR s.numero LIKE :search_numero OR r.statut LIKE :search_statut)';
        if ($userId !== null) {
            $where .= ' AND r.utilisateur_id = :user_id';
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations r JOIN utilisateurs u ON u.id = r.utilisateur_id JOIN salles s ON s.id = r.salle_id WHERE $where");
        $this->bindSearch($stmt, $search);
        if ($userId !== null) {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM reservations WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function hasConflict(int $roomId, string $date, string $start, string $end, ?int $ignoreId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM reservations
                WHERE salle_id = ?
                AND date_reservation = ?
                AND statut IN ('en_attente', 'validee')
                AND heure_debut < ?
                AND heure_fin > ?";
        $params = [$roomId, $date, $end, $start];

        if ($ignoreId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO reservations (utilisateur_id, salle_id, date_reservation, heure_debut, heure_fin, statut) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$data['utilisateur_id'], $data['salle_id'], $data['date_reservation'], $data['heure_debut'], $data['heure_fin'], $data['statut'] ?? 'en_attente']);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE reservations SET utilisateur_id = ?, salle_id = ?, date_reservation = ?, heure_debut = ?, heure_fin = ?, statut = ? WHERE id = ?');
        return $stmt->execute([$data['utilisateur_id'], $data['salle_id'], $data['date_reservation'], $data['heure_debut'], $data['heure_fin'], $data['statut'], $id]);
    }

    public function setStatus(int $id, string $status): bool
    {
        return $this->db->prepare('UPDATE reservations SET statut = ? WHERE id = ?')->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare('DELETE FROM reservations WHERE id = ?')->execute([$id]);
    }

    public function monthlyStats(): array
    {
        return $this->db->query("SELECT DATE_FORMAT(date_reservation, '%Y-%m') AS mois, COUNT(*) AS total FROM reservations GROUP BY mois ORDER BY mois DESC LIMIT 8")->fetchAll();
    }

    public function monthlyStatsByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT DATE_FORMAT(date_reservation, '%Y-%m') AS mois, COUNT(*) AS total FROM reservations WHERE utilisateur_id = ? GROUP BY mois ORDER BY mois DESC LIMIT 8");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function statusStats(): array
    {
        return $this->db->query('SELECT statut, COUNT(*) AS total FROM reservations GROUP BY statut')->fetchAll();
    }

    public function statusStatsByUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT statut, COUNT(*) AS total FROM reservations WHERE utilisateur_id = ? GROUP BY statut');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function countByStatus(?string $status = null, ?int $userId = null): int
    {
        $where = [];
        $params = [];

        if ($status !== null) {
            $where[] = 'statut = ?';
            $params[] = $status;
        }

        if ($userId !== null) {
            $where[] = 'utilisateur_id = ?';
            $params[] = $userId;
        }

        $sql = 'SELECT COUNT(*) FROM reservations';
        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function upcomingForUser(int $userId, int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT r.*, s.nom AS salle, s.numero
            FROM reservations r
            JOIN salles s ON s.id = r.salle_id
            WHERE r.utilisateur_id = ?
            AND r.statut IN ('en_attente', 'validee')
            AND CONCAT(r.date_reservation, ' ', r.heure_fin) >= NOW()
            ORDER BY r.date_reservation ASC, r.heure_debut ASC
            LIMIT ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function recentForUser(int $userId, int $limit = 6): array
    {
        $stmt = $this->db->prepare("SELECT r.*, s.nom AS salle, s.numero
            FROM reservations r
            JOIN salles s ON s.id = r.salle_id
            WHERE r.utilisateur_id = ?
            ORDER BY r.date_reservation DESC, r.heure_debut DESC
            LIMIT ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function recentAll(int $limit = 6): array
    {
        $stmt = $this->db->prepare("SELECT r.*, CONCAT(u.prenom, ' ', u.nom) AS utilisateur, s.nom AS salle, s.numero
            FROM reservations r
            JOIN utilisateurs u ON u.id = r.utilisateur_id
            JOIN salles s ON s.id = r.salle_id
            ORDER BY r.date_creation DESC
            LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function todayCount(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM reservations WHERE date_reservation = CURDATE()')->fetchColumn();
    }

    private function bindSearch(PDOStatement $stmt, string $search): void
    {
        $like = '%' . $search . '%';
        foreach ([':search_nom', ':search_prenom', ':search_salle', ':search_numero', ':search_statut'] as $param) {
            $stmt->bindValue($param, $like);
        }
    }
}
