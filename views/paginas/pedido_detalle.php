<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="detalle-pedido">
    <h1>Pedido #<?= (int)$pedido['id'] ?></h1>
    <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])), ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8') ?></p>
    <h2>Entrega</h2>
    <p><?= htmlspecialchars($pedido['nombre_entrega'], ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars($pedido['direccion_entrega'], ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars($pedido['ciudad_entrega'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($pedido['departamento_entrega'], ENT_QUOTES, 'UTF-8') ?></p>
    <h2>Productos</h2><div class="tabla-pedidos-contenedor"><table class="tabla-pedidos"><thead><tr><th>Producto</th><th>Talle</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th></tr></thead><tbody>
    <?php foreach ($detalle as $linea): ?><tr><td><?= htmlspecialchars($linea['nombre'], ENT_QUOTES, 'UTF-8') ?></td><td><?= (int)$linea['talle'] ?></td><td><?= (int)$linea['cantidad'] ?></td><td>$<?= number_format((float)$linea['precio_unitario'], 2, ',', '.') ?></td><td>$<?= number_format((float)$linea['subtotal'], 2, ',', '.') ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <h2>Total: $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></h2>
    <p><a href="index.php?pagina=mis-pedidos">Volver a mis pedidos</a></p>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
