<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="card form" method="post" action="index.php?c=alquiler&a=guardar" id="form-alquiler" novalidate>
    <div class="grid">
        <div class="field">
            <label for="cliente_id">Cliente</label>
            <select class="input <?= isset($errores['cliente_id']) ? 'is-invalid' : '' ?>" id="cliente_id" name="cliente_id" required>
                <option value="">— Selecciona —</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= (string)$alquiler['cliente_id'] === (string)$c['id'] ? 'selected' : '' ?>>
                        <?= e($c['nombre']) ?> (<?= e($c['documento']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['cliente_id'])): ?><small class="error"><?= e($errores['cliente_id']) ?></small><?php endif; ?>
        </div>

        <div class="field">
            <label for="vehiculo_id">Vehículo disponible</label>
            <select class="input <?= isset($errores['vehiculo_id']) ? 'is-invalid' : '' ?>" id="vehiculo_id" name="vehiculo_id" required>
                <option value="">— Selecciona —</option>
                <?php foreach ($disponibles as $v): ?>
                    <option value="<?= (int)$v['id'] ?>" data-tarifa="<?= e($v['tarifa_diaria']) ?>"
                        <?= (string)$alquiler['vehiculo_id'] === (string)$v['id'] ? 'selected' : '' ?>>
                        <?= e($v['marca'] . ' ' . $v['modelo']) ?> · <?= e($v['placa']) ?> · <?= dinero($v['tarifa_diaria']) ?>/día
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['vehiculo_id'])): ?><small class="error"><?= e($errores['vehiculo_id']) ?></small><?php endif; ?>
        </div>

        <div class="field">
            <label for="fecha_inicio">Fecha de inicio</label>
            <input class="input <?= isset($errores['fecha_inicio']) ? 'is-invalid' : '' ?>" id="fecha_inicio" name="fecha_inicio"
                   type="date" min="<?= date('Y-m-d') ?>" value="<?= e($alquiler['fecha_inicio']) ?>" required>
            <?php if (isset($errores['fecha_inicio'])): ?><small class="error"><?= e($errores['fecha_inicio']) ?></small><?php endif; ?>
        </div>

        <div class="field">
            <label for="fecha_fin">Fecha de devolución</label>
            <input class="input <?= isset($errores['fecha_fin']) ? 'is-invalid' : '' ?>" id="fecha_fin" name="fecha_fin"
                   type="date" min="<?= date('Y-m-d') ?>" value="<?= e($alquiler['fecha_fin']) ?>" required>
            <?php if (isset($errores['fecha_fin'])): ?><small class="error"><?= e($errores['fecha_fin']) ?></small><?php endif; ?>
        </div>
    </div>

    <div class="resumen" id="resumen" aria-live="polite">
        <span>Días: <strong id="resumen-dias">—</strong></span>
        <span>Total estimado: <strong id="resumen-total">—</strong></span>
    </div>

    <div class="form__actions">
        <button class="btn btn--primary" type="submit">Registrar alquiler</button>
        <a class="btn btn--ghost" href="index.php?c=alquiler">Cancelar</a>
    </div>
</form>
