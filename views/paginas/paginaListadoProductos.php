<?php require __DIR__ . '/../assets/header.php'; ?>
<a href="index.php?pagina=home" class="volver">← Volver al inicio</a>
<section class="cabecera-catalogo">
    <div class="catalogo"><h1>Catálogo de productos</h1><p>Encuentra el calzado ideal para cada ocasión.</p></div>
    <form class="busqueda" action="index.php" method="get">
        <input type="hidden" name="pagina" value="productos">
        <?php if (!empty($_GET['categoria'])): ?><input type="hidden" name="categoria" value="<?= (int)$_GET['categoria'] ?>"><?php endif; ?>
        <input type="search" id="buscador" name="q" maxlength="50" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej.: Nike Air Max..." aria-label="Buscar productos">
        <button type="submit">Buscar</button>
    </form>
    <div class="filtros">
        <a class="<?= empty($_GET['categoria']) ? 'activo' : '' ?>" href="index.php?pagina=productos<?= $busqueda !== '' ? '&amp;q=' . rawurlencode($busqueda) : '' ?>">Todos</a>
        <?php foreach ($listaDeCategorias as $categoria): ?>
            <a class="<?= ((int)($_GET['categoria'] ?? 0) === (int)$categoria['id']) ? 'activo' : '' ?>" href="index.php?pagina=productos&amp;categoria=<?= (int)$categoria['id'] ?><?= $busqueda !== '' ? '&amp;q=' . rawurlencode($busqueda) : '' ?>">
                <?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php if ($busqueda !== ''): ?>
        <p class="resumen-busqueda"><?= count($listaDeProductos) ?> resultado(s) para <strong>“<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>”</strong> — <a href="index.php?pagina=productos<?= !empty($_GET['categoria']) ? '&amp;categoria=' . (int)$_GET['categoria'] : '' ?>">Limpiar búsqueda</a></p>
    <?php else: ?>
        <p class="cantidad-productos">Mostrando <?= count($listaDeProductos) ?> productos</p>
    <?php endif; ?>
</section>
<section class="productos-catalogo" aria-label="Productos disponibles">
<?php if (!$listaDeProductos): ?><p class="sin-resultados">No encontramos productos con esos criterios.</p><?php endif; ?>
<?php foreach ($listaDeProductos as $producto): ?>
    <article class="producto" data-marca="<?= htmlspecialchars(strtolower(preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $producto['marca']))), ENT_QUOTES, 'UTF-8') ?>">
        <a href="index.php?pagina=detalle&amp;id=<?= (int)$producto['id'] ?>"><img src="<?= htmlspecialchars($producto['imagen'] ?: 'public/img/logos/zapato.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"></a>
        <h3><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p class="marca-producto"><?= htmlspecialchars($producto['marca'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="estrellas" aria-label="Calificación ilustrativa">★★★★★</p>
        <p class="precio">$<?= number_format((float)$producto['precio'], 0, ',', '.') ?></p>
        <a href="index.php?pagina=detalle&amp;id=<?= (int)$producto['id'] ?>">Ver detalles</a>
    </article>
<?php endforeach; ?>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
