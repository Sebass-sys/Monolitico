<?php
class VehiculoController extends Controller
{
    private Vehiculo $modelo;

    public function __construct()
    {
        $this->modelo = new Vehiculo();
    }

    public function index(): void
    {
        $this->render('vehiculos/index', [
            'titulo'    => 'Vehículos',
            'vehiculos' => $this->modelo->todos(),
        ]);
    }

    public function nuevo(): void
    {
        $this->formulario([
            'placa' => '', 'marca' => '', 'modelo' => '', 'anio' => date('Y'),
            'tipo' => Vehiculo::TIPOS[0], 'tarifa_diaria' => '', 'estado' => 'disponible',
        ], [], 'guardar', 'Nuevo vehículo');
    }

    public function guardar(): void
    {
        $datos   = $this->_leer();
        $errores = $this->_validar($datos, 0);

        if ($errores) {
            $this->formulario($datos, $errores, 'guardar', 'Nuevo vehículo');
            return;
        }
        $this->modelo->crear($datos);
        $this->flash('ok', 'Vehículo registrado correctamente.');
        $this->redirect('vehiculo');
    }

    public function editar(): void
    {
        $v = $this->modelo->buscar((int)($_GET['id'] ?? 0));
        if (!$v) {
            $this->flash('error', 'El vehículo no existe.');
            $this->redirect('vehiculo');
        }
        $this->formulario($v, [], 'actualizar', 'Editar vehículo');
    }

    public function actualizar(): void
    {
        $id      = (int)$this->post('id');
        $datos   = $this->_leer();
        $errores = $this->_validar($datos, $id);

        if (!$this->modelo->buscar($id)) {
            $this->flash('error', 'El vehículo no existe.');
            $this->redirect('vehiculo');
        }
        if ($errores) {
            $datos['id'] = $id;
            $this->formulario($datos, $errores, 'actualizar', 'Editar vehículo');
            return;
        }
        $this->modelo->actualizar($id, $datos);
        $this->flash('ok', 'Vehículo actualizado correctamente.');
        $this->redirect('vehiculo');
    }

    public function eliminar(): void
    {
        $id = (int)$this->post('id');
        try {
            $this->modelo->eliminar($id);
            $this->flash('ok', 'Vehículo eliminado.');
        } catch (PDOException $e) {
            $this->flash('error', 'No se puede eliminar: el vehículo tiene alquileres asociados.');
        }
        $this->redirect('vehiculo');
    }

    private function formulario(array $vehiculo, array $errores, string $accion, string $titulo): void
    {
        $this->render('vehiculos/form', compact('vehiculo', 'errores', 'accion', 'titulo'));
    }

    private function _leer(): array
    {
        return [
            'placa'         => strtoupper($this->post('placa')),
            'marca'         => $this->post('marca'),
            'modelo'        => $this->post('modelo'),
            'anio'          => $this->post('anio'),
            'tipo'          => $this->post('tipo'),
            'tarifa_diaria' => $this->post('tarifa_diaria'),
            'estado'        => $this->post('estado'),
        ];
    }

    private function _validar(array $d, int $id): array
    {
        $e = [];
        if (!preg_match('/^[A-Z]{3}[0-9]{2}[0-9A-Z]$/', $d['placa'])) {
            $e['placa'] = 'La placa debe tener formato ABC123 (o ABC12D para motos).';
        } elseif ($this->modelo->placaExiste($d['placa'], $id)) {
            $e['placa'] = 'Ya existe un vehículo con esa placa.';
        }
        if (mb_strlen($d['marca']) < 2)  { $e['marca']  = 'Ingresa la marca.'; }
        if (mb_strlen($d['modelo']) < 1) { $e['modelo'] = 'Ingresa el modelo.'; }

        $anio = filter_var($d['anio'], FILTER_VALIDATE_INT);
        if ($anio === false || $anio < 1990 || $anio > (int)date('Y') + 1) {
            $e['anio'] = 'El año debe estar entre 1990 y ' . ((int)date('Y') + 1) . '.';
        }
        if (!in_array($d['tipo'], Vehiculo::TIPOS, true)) {
            $e['tipo'] = 'Selecciona un tipo válido.';
        }
        if (!is_numeric($d['tarifa_diaria']) || (float)$d['tarifa_diaria'] <= 0) {
            $e['tarifa_diaria'] = 'La tarifa diaria debe ser un número mayor que 0.';
        }
        if (!in_array($d['estado'], Vehiculo::ESTADOS, true)) {
            $e['estado'] = 'Selecciona un estado válido.';
        }
        return $e;
    }
}
