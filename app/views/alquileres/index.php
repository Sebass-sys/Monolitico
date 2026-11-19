<div class="page-head">
    <h1>Alquileres</h1>
    <a class="btn btn--primary" href="index.php?c=alquiler&a=nuevo">+ Nuevo alquiler</a>
</div>

<div class="card">
    <input type="search" class="input input--search" placeholder="Buscar por cliente, placa o estado…"
           data-filtro="tabla-alquileres" aria-label="Buscar alquileres">

    <div class="table-wrap">
    <table class="table" id="tabla-alquileres">
        <thead><tr><th>#</th><th>Cliente</th><th>Vehículo</th><th>Periodo</th><th>Días</th><th>Total</th><th>Estado</th><th class="right">Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($alquileres as $a): ?>
            <tr>
                <td><?= (int)$a['id'] ?></td>
                <td><?= e($a['cliente']) ?></td>
                <td><?= e($a['marca'] . ' ' . $a['modelo']) ?> <span class="muted">(<?= e($a['placa']) ?>)</span></td>
                <td><?= e($a['fecha_inicio']) ?> → <?= e($a['fecha_fin']) ?></td>
                <td><?= (int)$a['dias'] ?></td>
                <td><?= dinero($a['total']) ?></td>
                <td><span class="badge badge--<?= e($a['estado']) ?>"><?= e($a['estado']) ?></span></td>
                <td class="right actions">
                    <a class="btn btn--small btn--ghost" href="index.php?c=alquiler&a=ver&id=<?= (int)$a['id'] ?>">Ver</a>
                    <?php if ($a['estado'] === 'activo'): ?>
                        <form method="post" action="index.php?c=alquiler&a=finalizar" data-confirmar="¿Registrar la devolución del vehículo <?= e($a['placa']) ?>?">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button class="btn btn--small" type="submit">Devolver</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$alquileres): ?>
            <tr><td colspan="8" class="muted">No hay alquileres registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
