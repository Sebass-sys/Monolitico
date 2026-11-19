<div class="page-head">
    <h1>Clientes</h1>
    <a class="btn btn--primary" href="index.php?c=cliente&a=nuevo">+ Nuevo cliente</a>
</div>

<div class="card">
    <input type="search" class="input input--search" placeholder="Buscar por nombre, documento o correo…"
           data-filtro="tabla-clientes" aria-label="Buscar clientes">

    <div class="table-wrap">
    <table class="table" id="tabla-clientes">
        <thead><tr><th>Nombre</th><th>Documento</th><th>Teléfono</th><th>Correo</th><th class="right">Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><strong><?= e($c['nombre']) ?></strong></td>
                <td><?= e($c['documento']) ?></td>
                <td><?= e($c['telefono']) ?></td>
                <td><?= e($c['email']) ?></td>
                <td class="right actions">
                    <a class="btn btn--small btn--ghost" href="index.php?c=cliente&a=editar&id=<?= (int)$c['id'] ?>">Editar</a>
                    <form method="post" action="index.php?c=cliente&a=eliminar" data-confirmar="¿Eliminar a <?= e($c['nombre']) ?>?">
                        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                        <button class="btn btn--small btn--danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$clientes): ?>
            <tr><td colspan="5" class="muted">No hay clientes registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
