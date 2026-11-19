<div class="page-head">
    <h1>Panel principal</h1>
    <a class="btn btn--primary" href="index.php?c=alquiler&a=nuevo">+ Nuevo alquiler</a>
</div>

<section class="stats">
    <article class="stat"><span class="stat__n"><?= $porEstado['disponible'] ?></span><span class="stat__l">Vehículos disponibles</span></article>
    <article class="stat"><span class="stat__n"><?= $porEstado['alquilado'] ?></span><span class="stat__l">Vehículos alquilados</span></article>
    <article class="stat"><span class="stat__n"><?= $porEstado['mantenimiento'] ?></span><span class="stat__l">En mantenimiento</span></article>
    <article class="stat"><span class="stat__n"><?= $clientes ?></span><span class="stat__l">Clientes</span></article>
    <article class="stat"><span class="stat__n"><?= $activos ?></span><span class="stat__l">Alquileres activos</span></article>
    <article class="stat"><span class="stat__n"><?= dinero($ingresos) ?></span><span class="stat__l">Ingresos registrados</span></article>
</section>

<section class="card">
    <h2>Últimos alquileres</h2>
    <?php if (!$recientes): ?>
        <p class="muted">Aún no hay alquileres registrados.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>#</th><th>Cliente</th><th>Vehículo</th><th>Periodo</th><th>Total</th><th>Estado</th></tr></thead>
            <tbody>
            <?php foreach ($recientes as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= e($a['cliente']) ?></td>
                    <td><?= e($a['marca'] . ' ' . $a['modelo']) ?> <span class="muted">(<?= e($a['placa']) ?>)</span></td>
                    <td><?= e($a['fecha_inicio']) ?> → <?= e($a['fecha_fin']) ?></td>
                    <td><?= dinero($a['total']) ?></td>
                    <td><span class="badge badge--<?= e($a['estado']) ?>"><?= e($a['estado']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</section>
