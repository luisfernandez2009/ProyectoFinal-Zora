<?php require __DIR__ . '/../assets/header.php'; ?>
<a href="index.php?pagina=productos" class="volver">← Volver al catálogo</a>
<section class="detalle-producto">
    <div class="galeria">
        <?php $imagenPrincipal = $imagenes[0] ?? 'public/img/logos/zapato.png'; ?>
        <img src="<?= htmlspecialchars($imagenPrincipal, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" class="imagen-principal">
        <div class="miniaturas">
            <?php foreach ($imagenes as $i => $ruta): ?>
                <img src="<?= htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto['nombre'] . ' vista ' . ($i+1), ENT_QUOTES, 'UTF-8') ?>" class="miniatura <?= $i === 0 ? 'activa' : '' ?>">
            <?php endforeach; ?>
        </div>
    </div>
    <div class="info-producto">
        <p class="marca-producto"><?= htmlspecialchars($producto['marca'], ENT_QUOTES, 'UTF-8') ?></p>
        <h1><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="precio">$<?= number_format((float)$producto['precio'], 0, ',', '.') ?></p>
        <p class="estrellas" aria-label="Calificación ilustrativa">★★★★★</p>
        <p class="descripcion"><?= nl2br(htmlspecialchars($producto['descripcion'] ?? 'Consultá disponibilidad de este modelo.', ENT_QUOTES, 'UTF-8')) ?></p>
        <h3>Talles disponibles</h3>
        <?php if (!$talles): ?><p class="stock">Por el momento no hay talles cargados con stock.</p><?php else: ?>
            <p class="stock">✔ Seleccioná tu talle</p>
            <div class="talles">
            <?php foreach ($talles as $talle): ?>
                <button type="button" class="talle" data-talle-id="<?= (int)$talle['id'] ?>" data-stock="<?= (int)$talle['stock'] ?>"><?= (int)$talle['numero'] ?></button>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button class="agregar-carrito" id="carrito-compra" type="button" data-producto-id="<?= (int)$producto['id'] ?>" data-nombre="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" data-precio="<?= htmlspecialchars((string)$producto['precio'], ENT_QUOTES, 'UTF-8') ?>" data-imagen="<?= htmlspecialchars($imagenPrincipal, ENT_QUOTES, 'UTF-8') ?>" <?= !$talles ? 'disabled' : '' ?>>Agregar al carrito</button>
        <p id="mensajeCarrito" role="status" aria-live="polite"></p>
    </div>
</section>
<section class="descripcion-completa"><h2>Descripción del producto</h2><p><?= nl2br(htmlspecialchars($producto['descripcion'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p><h2>Detalles</h2><ul><li>Marca: <?= htmlspecialchars($producto['marca'], ENT_QUOTES, 'UTF-8') ?>.</li><li>El precio y la disponibilidad se consultan desde la base de datos.</li></ul></section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
