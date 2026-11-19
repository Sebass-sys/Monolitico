<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $vehiculos = new Vehiculo();
        $clientes  = new Cliente();
        $alquileres = new Alquiler();

        $this->render('home/index', [
            'titulo'     => 'Panel principal',
            'porEstado'  => $vehiculos->contarPorEstado(),
            'clientes'   => $clientes->total(),
            'activos'    => $alquileres->totalActivos(),
            'ingresos'   => $alquileres->ingresos(),
            'recientes'  => array_slice($alquileres->listar(), 0, 5),
        ]);
    }
}
