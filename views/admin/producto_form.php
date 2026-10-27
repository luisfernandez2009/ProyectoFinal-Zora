<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="form-admin-producto">
    <h1><?= (int)$producto['id'] > 0 ? 'Editar producto' : 'Nuevo producto' ?></h1>
    <?php if (!empty($error)): ?><p class="mensaje-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form action="index.php?pagina=admin-producto-guardar" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin_productos'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
        <label for="nombre">Nombre</label><input id="nombre" name="nombre" required maxlength="150" value="<?= htmlspecialchars((string)$producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej.: Nike Air Max 270">
        <label for="marca">Marca</label><input id="marca" name="marca" required maxlength="100" value="<?= htmlspecialchars((string)$producto['marca'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej.: Nike">
        <label for="descripcion">Descripción</label><textarea id="descripcion" name="descripcion" rows="5"><?= htmlspecialchars((string)$producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>
        <label for="precio">Precio (UYU)</label><input id="precio" name="precio" type="number" min="0" step="0.01" required value="<?= htmlspecialchars((string)$producto['precio'], ENT_QUOTES, 'UTF-8') ?>">
        <label for="categoria_id">Categoría</label><select id="categoria_id" name="categoria_id" required><option value="">Elegí una categoría</option><?php foreach ($categorias as $categoria): ?><option value="<?= (int)$categoria['id'] ?>" <?= (int)$producto['categoria_id'] === (int)$categoria['id'] ? 'selected' : '' ?>><?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select>
        <label for="stock_minimo">Umbral de stock bajo (total de todos los talles)</label><input id="stock_minimo" name="stock_minimo" type="number" min="0" max="10000" value="<?= (int)$producto['stock_minimo'] ?>" required>
        <fieldset><legend>Stock por talle</legend><p>Ingresá la cantidad disponible para cada talle. Podés dejar 0 si no hay unidades.</p><?php if (!$talles): ?><p class="mensaje-error">No hay talles cargados en la base de datos. Ejecutá la migración de stock incluida en el proyecto.</p><?php else: ?><div class="stock-talles"><?php foreach ($talles as $talle): ?><label for="stock-<?= (int)$talle['id'] ?>">Talle <?= (int)$talle['numero'] ?><input id="stock-<?= (int)$talle['id'] ?>" name="stock[<?= (int)$talle['id'] ?>]" type="text" inputmode="numeric" pattern="[0-9]{1,5}" maxlength="5" value="<?= (int)($stocks[(int)$talle['id']] ?? 0) ?>" required aria-label="Unidades disponibles del talle <?= (int)$talle['numero'] ?>"></label><?php endforeach; ?></div><?php endif; ?></fieldset>
        <label for="imagen">Imagen principal (JPG, PNG o WEBP; máximo 5 MB)</label><input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp">
        <?php if (!empty($producto['imagen'])): ?><p class="vista-previa-imagen">Imagen actual: <img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8') ?>" alt="Imagen actual del producto"></p><?php endif; ?>
        <label for="imagenes">Imágenes adicionales para las miniaturas (podés seleccionar varias; JPG, PNG o WEBP, máximo 5 MB cada una)</label><input id="imagenes" name="imagenes[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
        <?php if (!empty($imagenesAdicionales)): ?><div class="imagenes-adicionales-actuales"><p>Miniaturas ya cargadas:</p><?php foreach ($imagenesAdicionales as $imagenAdicional): ?><img src="<?= htmlspecialchars($imagenAdicional['ruta'], ENT_QUOTES, 'UTF-8') ?>" alt="Imagen adicional del producto"><?php endforeach; ?></div><?php endif; ?>
        <div class="acciones-form-admin"><button type="submit">Guardar producto</button><a href="index.php?pagina=admin-productos">Cancelar</a></div>
    </form>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
