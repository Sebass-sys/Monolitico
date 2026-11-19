<div class="page-head"><h1><?= e($titulo) ?></h1></div>

<form class="card form" method="post" action="index.php?c=cliente&a=<?= e($accion) ?>" novalidate>
    <?php if (!empty($cliente['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$cliente['id'] ?>">
    <?php endif; ?>

    <div class="grid">
        <div class="field field--wide">
            <label for="nombre">Nombre completo</label>
            <input class="input <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" id="nombre" name="nombre"
                   value="<?= e($cliente['nombre']) ?>" required>
            <?php if (isset($errores['nombre'])): ?><small class="error"><?= e($errores['nombre']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="documento">Documento</label>
            <input class="input <?= isset($errores['documento']) ? 'is-invalid' : '' ?>" id="documento" name="documento"
                   inputmode="numeric" value="<?= e($cliente['documento']) ?>" required>
            <?php if (isset($errores['documento'])): ?><small class="error"><?= e($errores['documento']) ?></small><?php endif; ?>
        </div>
        <div class="field">
            <label for="telefono">Teléfono</label>
            <input class="input <?= isset($errores['telefono']) ? 'is-invalid' : '' ?>" id="telefono" name="telefono"
                   inputmode="tel" value="<?= e($cliente['telefono']) ?>" required>
            <?php if (isset($errores['telefono'])): ?><small class="error"><?= e($errores['telefono']) ?></small><?php endif; ?>
        </div>
        <div class="field field--wide">
            <label for="email">Correo electrónico</label>
            <input class="input <?= isset($errores['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email"
                   value="<?= e($cliente['email']) ?>" required>
            <?php if (isset($errores['email'])): ?><small class="error"><?= e($errores['email']) ?></small><?php endif; ?>
        </div>
    </div>

    <div class="form__actions">
        <button class="btn btn--primary" type="submit">Guardar</button>
        <a class="btn btn--ghost" href="index.php?c=cliente">Cancelar</a>
    </div>
</form>
