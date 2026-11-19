<div class="page-head">
    <h1>Vehículos</h1>
    <a class="btn btn--primary" href="index.php?c=vehiculo&a=nuevo">+ Nuevo vehículo</a>
</div>

<div class="card">
    <input type="search" class="input input--search" placeholder="Buscar por placa, marca, modelo o estado…"
           data-filtro="tabla-vehiculos" aria-label="Buscar vehículos">

    <div class="table-wrap">
    <table class="table" id="tabla-vehiculos">
        <thead>
            <tr><th>Placa</th><th>Vehículo</th><th>Año</th><th>Tipo</th><th>Tarifa / día</th><th>Estado</th><th class="right">Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($vehiculos as $v): ?>
            <tr>
                <td><strong><?= e($v['placa']) ?></strong></td>
                <td><?= e($v['marca'] . ' ' . $v['modelo']) ?></td>
                <td><?= (int)$v['anio'] ?></td>
                <td><?= e($v['tipo']) ?></td>
                <td><?= dinero($v['tarifa_diaria']) ?></td>
                <td><span class="badge badge--<?= e($v['estado']) ?>"><?= e($v['estado']) ?></span></td>
                <td class="right actions">
                    <?php if ($v['estado'] === 'disponible'): ?>
                        <a class="btn btn--small" href="index.php?c=alquiler&a=nuevo&vehiculo=<?= (int)$v['id'] ?>">Alquilar</a>
                    <?php endif; ?>
                    <a class="btn btn--small btn--ghost" href="index.php?c=vehiculo&a=editar&id=<?= (int)$v['id'] ?>">Editar</a>
                    <form method="post" action="index.php?c=vehiculo&a=eliminar" data-confirmar="¿Eliminar el vehículo <?= e($v['placa']) ?>?">
                        <input type="hidden" name="id" value="<?= (int)$v['id'] ?>">
                        <button class="btn btn--small btn--danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$vehiculos): ?>
            <tr><td colspan="7" class="muted">No hay vehículos registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
