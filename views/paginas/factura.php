<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="factura">
    <div class="factura-cabecera"><div><h1>Zora</h1><p>Factura de compra</p></div><div><strong>Factura de pedido #<?= (int)$pedido['id'] ?></strong><p>Fecha: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($pago['fecha'])), ENT_QUOTES, 'UTF-8') ?></p><p>Estado del pago: Aprobado</p></div></div>
    <hr>
    <h2>Datos de entrega</h2><p><?= htmlspecialchars($pedido['nombre_entrega'], ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars($pedido['direccion_entrega'], ENT_QUOTES, 'UTF-8') ?><br><?= htmlspecialchars($pedido['ciudad_entrega'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($pedido['departamento_entrega'], ENT_QUOTES, 'UTF-8') ?><br>Teléfono: <?= htmlspecialchars((string)$pedido['telefono_entrega'], ENT_QUOTES, 'UTF-8') ?></p>
    <h2>Detalle</h2><div class="tabla-pedidos-contenedor"><table class="tabla-pedidos"><thead><tr><th>Producto</th><th>Talle</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th></tr></thead><tbody>
    <?php foreach ($detalle as $linea): ?><tr><td><?= htmlspecialchars($linea['nombre'], ENT_QUOTES, 'UTF-8') ?></td><td><?= (int)$linea['talle'] ?></td><td><?= (int)$linea['cantidad'] ?></td><td>$<?= number_format((float)$linea['precio_unitario'], 2, ',', '.') ?></td><td>$<?= number_format((float)$linea['subtotal'], 2, ',', '.') ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <h2 class="total-factura">Total pagado: $<?= number_format((float)$pago['monto'], 2, ',', '.') ?></h2>
    <p>Pago con tarjeta terminada en <?= htmlspecialchars($pago['ultimos4'], ENT_QUOTES, 'UTF-8') ?>. Nunca se guardan el número completo ni el código de seguridad.</p>
    <div class="acciones-factura"><button type="button" onclick="window.print()">Imprimir / guardar como PDF</button><a href="index.php?pagina=mis-pedidos">Volver a mis pedidos</a></div>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
