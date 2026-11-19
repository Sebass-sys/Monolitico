<?php
/**
 * Front Controller: único punto de entrada de la aplicación monolítica.
 * Uso: index.php?c=<controlador>&a=<accion>
 */
session_start();

// Autocarga de clases desde las carpetas del monolito
spl_autoload_register(function (string $clase) {
    $carpetas = ['core', 'app/models', 'app/controllers'];
    foreach ($carpetas as $carpeta) {
        $archivo = __DIR__ . "/{$carpeta}/{$clase}.php";
        if (is_file($archivo)) {
            require $archivo;
            return;
        }
    }
});

// Helper para escapar salida en las vistas (previene XSS)
function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

// Helper para mostrar dinero en pesos colombianos
function dinero($valor): string
{
    return '$' . number_format((float)$valor, 0, ',', '.');
}

// Enrutamiento
$controlador = $_GET['c'] ?? 'home';
$accion      = $_GET['a'] ?? 'index';

$permitidos = ['home', 'vehiculo', 'cliente', 'alquiler'];
if (!in_array($controlador, $permitidos, true)) {
    $controlador = 'home';
}

$clase = ucfirst($controlador) . 'Controller';
$obj   = new $clase();

if (!is_callable([$obj, $accion]) || str_starts_with($accion, '_')) {
    http_response_code(404);
    exit('Acción no encontrada');
}

try {
    $obj->$accion();
} catch (PDOException $ex) {
    http_response_code(500);
    echo '<h3>Error de base de datos</h3><p>Verifica que importaste database.sql y la configuración en config/config.php.</p>';
}
