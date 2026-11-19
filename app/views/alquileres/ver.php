<div class="page-head">
    <h1>Comprobante #<?= (int)$alquiler['id'] ?></h1>
    <div class="actions">
        <button class="btn btn--ghost" type="button" onclick="window.print()">Imprimir</button>
        <a class="btn btn--primary" href="index.php?c=alquiler">Volver</a>
    </div>
</div>

<section class="card comprobante">
    <dl class="detalle">
        <dt>Cliente</dt><dd><?= e($alquiler['cliente']) ?> · <?= e($alquiler['documento']) ?></dd>
        <dt>Vehículo</dt><dd><?= e($alquiler['marca'] . ' ' . $alquiler['modelo']) ?> · <?= e($alquiler['placa']) ?></dd>
        <dt>Fecha de inicio</dt><dd><?= e($alquiler['fecha_inicio']) ?></dd>
        <dt>Fecha de devolución</dt><dd><?= e($alquiler['fecha_fin']) ?></dd>
        <dt>Tarifa diaria</dt><dd><?= dinero($alquiler['tarifa_diaria']) ?></dd>
        <dt>Días</dt><dd><?= (int)$alquiler['dias'] ?></dd>
        <dt>Estado</dt><dd><span class="badge badge--<?= e($alquiler['estado']) ?>"><?= e($alquiler['estado']) ?></span></dd>
    </dl>
    <p class="total">Total a pagar <strong><?= dinero($alquiler['total']) ?></strong></p>
</section>
