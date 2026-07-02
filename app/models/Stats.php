<?php
declare(strict_types=1);

final class Stats extends Model
{
    public function dashboard(): array
    {
        return [
            'users' => (int) $this->db->query('SELECT COUNT(*) FROM utilisateurs')->fetchColumn(),
            'rooms' => (int) $this->db->query('SELECT COUNT(*) FROM salles')->fetchColumn(),
            'reservations' => (int) $this->db->query('SELECT COUNT(*) FROM reservations')->fetchColumn(),
            'available_rooms' => (int) $this->db->query("SELECT COUNT(*) FROM salles WHERE etat = 'disponible'")->fetchColumn(),
        ];
    }
}

