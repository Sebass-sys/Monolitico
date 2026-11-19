<?php
class Cliente extends Model
{
    public function todos(): array
    {
        return $this->db->query('SELECT * FROM clientes ORDER BY nombre')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM clientes WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function documentoExiste(string $documento, int $excluirId = 0): bool
    {
        $st = $this->db->prepare('SELECT COUNT(*) FROM clientes WHERE documento = ? AND id <> ?');
        $st->execute([$documento, $excluirId]);
        return (int)$st->fetchColumn() > 0;
    }

    public function crear(array $d): void
    {
        $st = $this->db->prepare('INSERT INTO clientes (nombre, documento, telefono, email) VALUES (?, ?, ?, ?)');
        $st->execute([$d['nombre'], $d['documento'], $d['telefono'], $d['email']]);
    }

    public function actualizar(int $id, array $d): void
    {
        $st = $this->db->prepare('UPDATE clientes SET nombre=?, documento=?, telefono=?, email=? WHERE id=?');
        $st->execute([$d['nombre'], $d['documento'], $d['telefono'], $d['email'], $id]);
    }

    public function eliminar(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM clientes WHERE id = ?');
        $st->execute([$id]);
    }

    public function total(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM clientes')->fetchColumn();
    }
}
