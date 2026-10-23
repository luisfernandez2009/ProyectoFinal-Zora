<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="admin-productos">
    <h1>Administración de productos</h1>
    <p>Creá, editá, desactivá y reactivá productos. El stock se administra por talle.</p>
    <?php if (!empty($mensaje)): ?><p class="aviso-admin" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <p class="acciones-superiores"><a class="boton-admin" href="index.php?pagina=admin-producto-formulario">＋ Nuevo producto</a></p>
    <div class="tabla-productos-contenedor">
        <table class="tabla-admin-productos">
            <thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock total</th><th>Estado stock</th><th>Visibilidad</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr class="<?= (int)$producto['activo'] === 1 ? '' : 'producto-inactivo' ?>">
                    <td><div class="producto-admin-identidad"><?php if (!empty($producto['imagen'])): ?><img src="<?= htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy"><?php endif; ?><strong><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($producto['marca'], ENT_QUOTES, 'UTF-8') ?></small></div></td>
                    <td><?= htmlspecialchars($producto['nombre_categoria'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$<?= number_format((float)$producto['precio'], 0, ',', '.') ?></td>
                    <td><?= (int)$producto['stock_total'] ?></td>
                    <td><span class="stock-<?= htmlspecialchars($producto['nivel_stock'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($producto['nivel_stock'], ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td><?= (int)$producto['activo'] === 1 ? 'Activo' : 'Inactivo' ?></td>
                    <td class="acciones-admin-producto">
                        <a class="boton-admin" href="index.php?pagina=admin-producto-formulario&amp;id=<?= (int)$producto['id'] ?>">Editar</a>
                        <form action="index.php?pagina=admin-producto-estado" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin_productos'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
                            <input type="hidden" name="activo" value="<?= (int)$producto['activo'] === 1 ? 0 : 1 ?>">
                            <button type="submit" class="<?= (int)$producto['activo'] === 1 ? 'boton-baja' : 'boton-reactivar' ?>"><?= (int)$producto['activo'] === 1 ? 'Dar de baja' : 'Reactivar' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
