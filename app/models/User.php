<?php
declare(strict_types=1);

final class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function paginate(string $search, int $limit, int $offset): array
    {
        $like = '%' . $search . '%';
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR role LIKE ? ORDER BY date_creation DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $like);
        $stmt->bindValue(2, $like);
        $stmt->bindValue(3, $like);
        $stmt->bindValue(4, $like);
        $stmt->bindValue(5, $limit, PDO::PARAM_INT);
        $stmt->bindValue(6, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(string $search = ''): int
    {
        $like = '%' . $search . '%';
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM utilisateurs WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR role LIKE ?');
        $stmt->execute([$like, $like, $like, $like]);
        return (int) $stmt->fetchColumn();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, telephone) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            $data['role'],
            $data['telephone'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $params = [$data['nom'], $data['prenom'], $data['email'], $data['role'], $data['telephone'] ?? null];
        $sql = 'UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, role = ?, telephone = ?';

        if (!empty($data['mot_de_passe'])) {
            $sql .= ', mot_de_passe = ?';
            $params[] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = ?';
        $params[] = $id;

        return $this->db->prepare($sql)->execute($params);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $params = [$data['nom'], $data['prenom'], $data['email'], $data['telephone'] ?? null];
        $sql = 'UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?';

        if (!empty($data['mot_de_passe'])) {
            $sql .= ', mot_de_passe = ?';
            $params[] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }

        if (!empty($data['photo'])) {
            $sql .= ', photo = ?';
            $params[] = $data['photo'];
        }

        $sql .= ' WHERE id = ?';
        $params[] = $id;
        return $this->db->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?')->execute([$id]);
    }
}
