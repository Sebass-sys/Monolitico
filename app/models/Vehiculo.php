<?php
class Vehiculo extends Model
{
    public const TIPOS   = ['Automóvil', 'Camioneta', 'Van', 'Moto'];
    public const ESTADOS = ['disponible', 'alquilado', 'mantenimiento'];

    public function todos(): array
    {
        return $this->db->query('SELECT * FROM vehiculos ORDER BY marca, modelo')->fetchAll();
    }

    public function disponibles(): array
    {
        return $this->db->query("SELECT * FROM vehiculos WHERE estado = 'disponible' ORDER BY marca, modelo")->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM vehiculos WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function placaExiste(string $placa, int $excluirId = 0): bool
    {
        $st = $this->db->prepare('SELECT COUNT(*) FROM vehiculos WHERE placa = ? AND id <> ?');
        $st->execute([$placa, $excluirId]);
        return (int)$st->fetchColumn() > 0;
    }

    public function crear(array $d): void
    {
        $st = $this->db->prepare(
            'INSERT INTO vehiculos (placa, marca, modelo, anio, tipo, tarifa_diaria, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([$d['placa'], $d['marca'], $d['modelo'], $d['anio'], $d['tipo'], $d['tarifa_diaria'], $d['estado']]);
    }

    public function actualizar(int $id, array $d): void
    {
        $st = $this->db->prepare(
            'UPDATE vehiculos SET placa=?, marca=?, modelo=?, anio=?, tipo=?, tarifa_diaria=?, estado=? WHERE id=?'
        );
        $st->execute([$d['placa'], $d['marca'], $d['modelo'], $d['anio'], $d['tipo'], $d['tarifa_diaria'], $d['estado'], $id]);
    }

    public function eliminar(int $id): void
    {
        $st = $this->db->prepare('DELETE FROM vehiculos WHERE id = ?');
        $st->execute([$id]);
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        $st = $this->db->prepare('UPDATE vehiculos SET estado = ? WHERE id = ?');
        $st->execute([$estado, $id]);
    }

    public function contarPorEstado(): array
    {
        $filas = $this->db->query('SELECT estado, COUNT(*) AS n FROM vehiculos GROUP BY estado')->fetchAll();
        $r = ['disponible' => 0, 'alquilado' => 0, 'mantenimiento' => 0];
        foreach ($filas as $f) {
            $r[$f['estado']] = (int)$f['n'];
        }
        return $r;
    }
}
