<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="contenedor-banner">
    <img class="banner" src="public/img/banners/banner1.png" alt="Nueva colección de Zora">
    <div class="texto-banner"><h1>Nueva colección 2026</h1><p>Camina con confianza.</p><p>Descubre la nueva colección Zora.</p><a href="index.php?pagina=productos">Explorar colección</a></div>
</section>
<section class="productos">
    <h2>Productos destacados</h2>
    <div class="contenedor-productos">
    <?php foreach ($productosDestacados as $producto): ?>
        <article class="producto">
            <a href="index.php?pagina=detalle&amp;id=<?= (int)$producto['id'] ?>"><img src="<?= htmlspecialchars($producto['imagen'] ?: 'public/img/logos/zapato.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>"></a>
            <h3><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h3><p>★★★★★</p>
            <p>$<?= number_format((float)$producto['precio'], 0, ',', '.') ?></p>
            <a href="index.php?pagina=detalle&amp;id=<?= (int)$producto['id'] ?>">Ver detalles</a>
        </article>
    <?php endforeach; ?>
    <?php if (!$productosDestacados): ?><p>Pronto encontrarás productos destacados.</p><?php endif; ?>
    </div>
</section>
<section class="ventajas">
    <h2>¿Por qué elegirnos?</h2><div class="contenedor-ventajas">
        <div class="ventaja"><img src="public/img/logos/camion.png" alt="Envíos"><h3>🚚 Envíos a todo Uruguay</h3><p>Recibí tus zapatillas de forma cómoda y segura.</p></div>
        <div class="ventaja"><img src="public/img/logos/escudo.png" alt="Seguridad"><h3>🛡️ Compra segura</h3><p>Cuidamos tu experiencia de compra.</p></div>
        <div class="ventaja"><img src="public/img/logos/zapato.png" alt="Marcas"><h3>👟 Marcas reconocidas</h3><p>Explorá Adidas, Nike, Puma y New Balance.</p></div>
        <div class="ventaja"><img src="public/img/logos/estrella.png" alt="Calidad"><h3>⭐ Calidad y estilo</h3><p>Encontrá un modelo para tu día a día.</p></div>
    </div>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
