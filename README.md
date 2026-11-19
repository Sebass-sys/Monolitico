# RentCar UB — Taller monolítico: alquiler de vehículos

Aplicación web **monolítica** (front end + back end en un solo proyecto y un solo despliegue) hecha en **PHP 8 + MySQL** con patrón **MVC**, HTML, CSS y JavaScript puros.
Materia: Programación Avanzada · Universidad de Boyacá.

## Instalación en XAMPP

1. Copia la carpeta `alquiler_vehiculos` dentro de `C:\xampp\htdocs\`.
2. Inicia **Apache** y **MySQL** desde el panel de XAMPP.
3. Abre `http://localhost/phpmyadmin`, ve a **Importar** y carga el archivo `database.sql` (crea la base `alquiler_vehiculos`, las tablas y datos de ejemplo).
4. Si tu MySQL tiene contraseña, cámbiala en `config/config.php`.
5. Entra a `http://localhost/alquiler_vehiculos/`.

> Requiere PHP 8.0 o superior (usa `str_starts_with`).

## Funcionalidades

| Módulo | Qué hace |
|---|---|
| Panel | Totales por estado de vehículo, clientes, alquileres activos, ingresos y últimos alquileres |
| Vehículos | CRUD completo, búsqueda en vivo, botón "Alquilar" si está disponible |
| Clientes | CRUD completo con validación (documento único, correo, teléfono) |
| Alquileres | Registrar alquiler, calcular días y total, comprobante imprimible, devolver vehículo |

**Reglas de negocio** (en el modelo `Alquiler`):
- Solo se alquila un vehículo en estado `disponible`.
- Días = diferencia entre fechas (mínimo 1). Total = días × tarifa diaria.
- Al alquilar el vehículo pasa a `alquilado`; al devolverlo vuelve a `disponible`. Ambos cambios van en una **transacción** (se guardan juntos o no se guardan).
- No se puede eliminar un cliente o vehículo que tenga alquileres (llave foránea).

## Estructura MVC

```
alquiler_vehiculos/
├── index.php                  Front Controller (único punto de entrada y router)
├── config/config.php          Configuración de BD y nombre de la app
├── core/                      Núcleo: Database (Singleton PDO), Model, Controller
├── app/
│   ├── models/                Modelo   → acceso a datos y reglas de negocio
│   ├── controllers/           Controlador → recibe la petición, valida, elige la vista
│   └── views/                 Vista    → HTML (layout + una carpeta por módulo)
├── public/css, public/js      Front end: estilos y comportamiento en el navegador
└── database.sql               Script de la base de datos
```

Flujo de una petición: `index.php?c=alquiler&a=guardar` → el router crea `AlquilerController` y llama `guardar()` → el controlador valida y usa el modelo `Alquiler` → redirige o renderiza una vista dentro del layout.

## Por qué es un monolito

Interfaz, lógica de negocio y acceso a datos viven en **una sola aplicación** que se despliega junta (una carpeta en `htdocs`), a diferencia de una arquitectura de microservicios o de un front y un back separados que se comunican por API.

## Conceptos aplicados (para la sustentación)

- **MVC**: separación en modelos, vistas y controladores.
- **Singleton**: `Database::get()` mantiene una única conexión PDO.
- **Front Controller**: todo pasa por `index.php`.
- **Herencia y abstracción (POO)**: `Model` y `Controller` son clases abstractas base.
- **Responsabilidad única (SOLID)**: cada clase tiene un propósito (el modelo no imprime HTML, la vista no consulta la BD).
- **Seguridad**: consultas preparadas PDO (evita inyección SQL), `htmlspecialchars` en toda salida (evita XSS), lista blanca de controladores y acciones privadas con prefijo `_`.
- **Front end**: diseño responsive y modo oscuro automático en CSS; JavaScript para búsqueda en vivo, confirmaciones y total estimado del alquiler.

## Mejoras posibles

Login con roles, validar que no se crucen fechas de reservas futuras, tokens CSRF, paginación y reportes en PDF.
