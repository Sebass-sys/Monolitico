<?php
class ClienteController extends Controller
{
    private Cliente $modelo;

    public function __construct()
    {
        $this->modelo = new Cliente();
    }

    public function index(): void
    {
        $this->render('clientes/index', [
            'titulo'   => 'Clientes',
            'clientes' => $this->modelo->todos(),
        ]);
    }

    public function nuevo(): void
    {
        $this->formulario(['nombre' => '', 'documento' => '', 'telefono' => '', 'email' => ''], [], 'guardar', 'Nuevo cliente');
    }

    public function guardar(): void
    {
        $datos   = $this->_leer();
        $errores = $this->_validar($datos, 0);

        if ($errores) {
            $this->formulario($datos, $errores, 'guardar', 'Nuevo cliente');
            return;
        }
        $this->modelo->crear($datos);
        $this->flash('ok', 'Cliente registrado correctamente.');
        $this->redirect('cliente');
    }

    public function editar(): void
    {
        $c = $this->modelo->buscar((int)($_GET['id'] ?? 0));
        if (!$c) {
            $this->flash('error', 'El cliente no existe.');
            $this->redirect('cliente');
        }
        $this->formulario($c, [], 'actualizar', 'Editar cliente');
    }

    public function actualizar(): void
    {
        $id      = (int)$this->post('id');
        $datos   = $this->_leer();
        $errores = $this->_validar($datos, $id);

        if (!$this->modelo->buscar($id)) {
            $this->flash('error', 'El cliente no existe.');
            $this->redirect('cliente');
        }
        if ($errores) {
            $datos['id'] = $id;
            $this->formulario($datos, $errores, 'actualizar', 'Editar cliente');
            return;
        }
        $this->modelo->actualizar($id, $datos);
        $this->flash('ok', 'Cliente actualizado correctamente.');
        $this->redirect('cliente');
    }

    public function eliminar(): void
    {
        try {
            $this->modelo->eliminar((int)$this->post('id'));
            $this->flash('ok', 'Cliente eliminado.');
        } catch (PDOException $e) {
            $this->flash('error', 'No se puede eliminar: el cliente tiene alquileres asociados.');
        }
        $this->redirect('cliente');
    }

    private function formulario(array $cliente, array $errores, string $accion, string $titulo): void
    {
        $this->render('clientes/form', compact('cliente', 'errores', 'accion', 'titulo'));
    }

    private function _leer(): array
    {
        return [
            'nombre'    => $this->post('nombre'),
            'documento' => $this->post('documento'),
            'telefono'  => $this->post('telefono'),
            'email'     => strtolower($this->post('email')),
        ];
    }

    private function _validar(array $d, int $id): array
    {
        $e = [];
        if (mb_strlen($d['nombre']) < 3) {
            $e['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (!preg_match('/^[0-9]{5,15}$/', $d['documento'])) {
            $e['documento'] = 'El documento debe tener entre 5 y 15 dígitos.';
        } elseif ($this->modelo->documentoExiste($d['documento'], $id)) {
            $e['documento'] = 'Ya existe un cliente con ese documento.';
        }
        if (!preg_match('/^[0-9]{7,15}$/', $d['telefono'])) {
            $e['telefono'] = 'El teléfono debe tener entre 7 y 15 dígitos.';
        }
        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $e['email'] = 'Ingresa un correo válido.';
        }
        return $e;
    }
}
