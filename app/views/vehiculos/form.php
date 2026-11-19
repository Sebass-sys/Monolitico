<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="card form" method="post" action="index.php?c=vehiculo&a=<?= e($accion) ?>" novalidate>
    <?php if (!empty($vehiculo['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$vehiculo['id'] ?>">
    <?php endif; ?>

    <div class="grid">
        <div class="field">
            <label for="placa">Placa</label>
            <input class="input <?= isset($errores['placa']) ? 'is-invalid' : '' ?>" id="placa" name="placa"
                   maxlength="6" placeholder="ABC123" value="<?= e($vehiculo['placa']) ?>" required>
            <?php if (isset($errores['placa'])): ?><small class="error"><?= e($errores['placa']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="marca">Marca</label>
            <input class="input <?= isset($errores['marca']) ? 'is-invalid' : '' ?>" id="marca" name="marca"
                   value="<?= e($vehiculo['marca']) ?>" required>
            <?php if (isset($errores['marca'])): ?><small class="error"><?= e($errores['marca']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="modelo">Modelo</label>
            <input class="input <?= isset($errores['modelo']) ? 'is-invalid' : '' ?>" id="modelo" name="modelo"
                   value="<?= e($vehiculo['modelo']) ?>" required>
            <?php if (isset($errores['modelo'])): ?><small class="error"><?= e($errores['modelo']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="anio">Año</label>
            <input class="input <?= isset($errores['anio']) ? 'is-invalid' : '' ?>" id="anio" name="anio" type="number"
                   min="1990" max="<?= (int)date('Y') + 1 ?>" value="<?= e($vehiculo['anio']) ?>" required>
            <?php if (isset($errores['anio'])): ?><small class="error"><?= e($errores['anio']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="tipo">Tipo</label>
            <select class="input" id="tipo" name="tipo">
                <?php foreach (Vehiculo::TIPOS as $t): ?>
                    <option value="<?= e($t) ?>" <?= $vehiculo['tipo'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['tipo'])): ?><small class="error"><?= e($errores['tipo']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="tarifa_diaria">Tarifa diaria (COP)</label>
            <input class="input <?= isset($errores['tarifa_diaria']) ? 'is-invalid' : '' ?>" id="tarifa_diaria"
                   name="tarifa_diaria" type="number" min="1" step="1000" value="<?= e($vehiculo['tarifa_diaria']) ?>" required>
            <?php if (isset($errores['tarifa_diaria'])): ?><small class="error"><?= e($errores['tarifa_diaria']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="estado">Estado</label>
            <select class="input" id="estado" name="estado">
                <?php foreach (Vehiculo::ESTADOS as $s): ?>
                    <option value="<?= e($s) ?>" <?= $vehiculo['estado'] === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['estado'])): ?><small class="error"><?= e($errores['estado']) ?></small><?php endif; ?>
        </div>
    </div>

    <div class="form__actions">
        <button class="btn btn--primary" type="submit">Guardar</button>
        <a class="btn btn--ghost" href="index.php?c=vehiculo">Cancelar</a>
    </div>
</form>
