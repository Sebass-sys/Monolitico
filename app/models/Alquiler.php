<?php
/**
 * Modelo Alquiler: contiene la regla de negocio principal del monolito
 * (disponibilidad, cálculo del total y cambio de estado del vehículo).
 */
class Alquiler extends Model
{
    private const SELECT_BASE =
        'SELECT a.*, c.nombre AS cliente, c.documento, v.placa, v.marca, v.modelo, v.tarifa_diaria
         FROM alquileres a
         JOIN clientes  c ON c.id = a.cliente_id
         JOIN vehiculos v ON v.id = a.vehiculo_id';

    public function listar(): array
    {
        return $this->db->query(self::SELECT_BASE . ' ORDER BY a.id DESC')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare(self::SELECT_BASE . ' WHERE a.id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    /** Días a cobrar: diferencia entre fechas, mínimo 1 día. */
    public static function calcularDias(string $inicio, string $fin): int
    {
        $d = (new DateTime($inicio))->diff(new DateTime($fin));
        return max(1, (int)$d->days);
    }

    /**
     * Registra un alquiler. Usa transacción para que el alquiler y el
     * cambio de estado del vehículo se guarden juntos o no se guarden.
     */
    public function crear(array $d): int
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare('SELECT * FROM vehiculos WHERE id = ?');
            $st->execute([$d['vehiculo_id']]);
            $vehiculo = $st->fetch();

            if (!$vehiculo) {
                throw new DomainException('El vehículo seleccionado no existe.');
            }
            if ($vehiculo['estado'] !== 'disponible') {
                throw new DomainException('El vehículo no está disponible para alquilar.');
            }

            $dias  = self::calcularDias($d['fecha_inicio'], $d['fecha_fin']);
            $total = $dias * (float)$vehiculo['tarifa_diaria'];

            $ins = $this->db->prepare(
                'INSERT INTO alquileres (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, dias, total, estado)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $ins->execute([$d['cliente_id'], $d['vehiculo_id'], $d['fecha_inicio'], $d['fecha_fin'], $dias, $total, 'activo']);
            $id = (int)$this->db->lastInsertId();

            $upd = $this->db->prepare("UPDATE vehiculos SET estado = 'alquilado' WHERE id = ?");
            $upd->execute([$d['vehiculo_id']]);

            $this->db->commit();
            return $id;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Cierra el alquiler y deja el vehículo otra vez disponible. */
    public function finalizar(int $id): void
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare('SELECT * FROM alquileres WHERE id = ?');
            $st->execute([$id]);
            $alq = $st->fetch();

            if (!$alq || $alq['estado'] !== 'activo') {
                throw new DomainException('El alquiler no existe o ya fue finalizado.');
            }

            $this->db->prepare("UPDATE alquileres SET estado = 'finalizado' WHERE id = ?")->execute([$id]);
            $this->db->prepare("UPDATE vehiculos SET estado = 'disponible' WHERE id = ?")->execute([$alq['vehiculo_id']]);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function totalActivos(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM alquileres WHERE estado = 'activo'")->fetchColumn();
    }

    public function ingresos(): float
    {
        return (float)$this->db->query('SELECT COALESCE(SUM(total), 0) FROM alquileres')->fetchColumn();
    }
}
