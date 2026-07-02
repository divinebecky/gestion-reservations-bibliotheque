<?php
declare(strict_types=1);

final class Room extends Model
{
    public function allAvailable(): array
    {
        return $this->db->query("SELECT * FROM salles WHERE etat = 'disponible' ORDER BY numero")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM salles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function paginate(string $search, int $limit, int $offset): array
    {
        $like = '%' . $search . '%';
        $stmt = $this->db->prepare('SELECT * FROM salles WHERE numero LIKE ? OR nom LIKE ? OR localisation LIKE ? OR etat LIKE ? ORDER BY numero LIMIT ? OFFSET ?');
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
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM salles WHERE numero LIKE ? OR nom LIKE ? OR localisation LIKE ? OR etat LIKE ?');
        $stmt->execute([$like, $like, $like, $like]);
        return (int) $stmt->fetchColumn();
    }

    public function availableCount(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM salles WHERE etat = 'disponible'")->fetchColumn();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO salles (numero, nom, capacite, localisation, description, etat) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$data['numero'], $data['nom'], $data['capacite'], $data['localisation'], $data['description'], $data['etat']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE salles SET numero = ?, nom = ?, capacite = ?, localisation = ?, description = ?, etat = ? WHERE id = ?');
        return $stmt->execute([$data['numero'], $data['nom'], $data['capacite'], $data['localisation'], $data['description'], $data['etat'], $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare('DELETE FROM salles WHERE id = ?')->execute([$id]);
    }
}

