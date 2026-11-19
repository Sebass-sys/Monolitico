<?php
class AlquilerController extends Controller
{
    private Alquiler $modelo;

    public function __construct()
    {
        $this->modelo = new Alquiler();
    }

    public function index(): void
    {
        $this->render('alquileres/index', [
            'titulo'     => 'Alquileres',
            'alquileres' => $this->modelo->listar(),
        ]);
    }

    public function nuevo(): void
    {
        $this->formulario([
            'cliente_id'   => '',
            'vehiculo_id'  => (string)(int)($_GET['vehiculo'] ?? 0),
            'fecha_inicio' => date('Y-m-d'),
            'fecha_fin'    => date('Y-m-d', strtotime('+1 day')),
        ], []);
    }

    public function guardar(): void
    {
        $datos = [
            'cliente_id'   => $this->post('cliente_id'),
            'vehiculo_id'  => $this->post('vehiculo_id'),
            'fecha_inicio' => $this->post('fecha_inicio'),
            'fecha_fin'    => $this->post('fecha_fin'),
        ];
        $errores = $this->_validar($datos);

        if (!$errores) {
            try {
                $id = $this->modelo->crear($datos);
                $this->flash('ok', 'Alquiler registrado correctamente.');
                header("Location: index.php?c=alquiler&a=ver&id={$id}");
                exit;
            } catch (DomainException $e) {
                $errores['vehiculo_id'] = $e->getMessage();
            }
        }
        $this->formulario($datos, $errores);
    }

    public function ver(): void
    {
        $alquiler = $this->modelo->buscar((int)($_GET['id'] ?? 0));
        if (!$alquiler) {
            $this->flash('error', 'El alquiler no existe.');
            $this->redirect('alquiler');
        }
        $this->render('alquileres/ver', ['titulo' => 'Comprobante de alquiler', 'alquiler' => $alquiler]);
    }

    public function finalizar(): void
    {
        try {
            $this->modelo->finalizar((int)$this->post('id'));
            $this->flash('ok', 'Vehículo devuelto: el alquiler fue finalizado.');
        } catch (DomainException $e) {
            $this->flash('error', $e->getMessage());
        }
        $this->redirect('alquiler');
    }

    private function formulario(array $alquiler, array $errores): void
    {
        $this->render('alquileres/form', [
            'titulo'      => 'Nuevo alquiler',
            'alquiler'    => $alquiler,
            'errores'     => $errores,
            'clientes'    => (new Cliente())->todos(),
            'disponibles' => (new Vehiculo())->disponibles(),
        ]);
    }

    private function _validar(array $d): array
    {
        $e = [];
        if ((new Cliente())->buscar((int)$d['cliente_id']) === null) {
            $e['cliente_id'] = 'Selecciona un cliente.';
        }
        if ($d['vehiculo_id'] === '') {
            $e['vehiculo_id'] = 'Selecciona un vehículo.';
        }

        $ini = DateTime::createFromFormat('Y-m-d', $d['fecha_inicio']);
        $fin = DateTime::createFromFormat('Y-m-d', $d['fecha_fin']);
        if (!$ini || $ini->format('Y-m-d') !== $d['fecha_inicio']) {
            $e['fecha_inicio'] = 'Fecha de inicio inválida.';
        } elseif ($d['fecha_inicio'] < date('Y-m-d')) {
            $e['fecha_inicio'] = 'La fecha de inicio no puede ser anterior a hoy.';
        }
        if (!$fin || $fin->format('Y-m-d') !== $d['fecha_fin']) {
            $e['fecha_fin'] = 'Fecha de fin inválida.';
        } elseif (!isset($e['fecha_inicio']) && $d['fecha_fin'] < $d['fecha_inicio']) {
            $e['fecha_fin'] = 'La fecha de fin no puede ser anterior al inicio.';
        }
        return $e;
    }
}
