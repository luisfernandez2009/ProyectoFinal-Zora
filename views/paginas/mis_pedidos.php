<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="mis-pedidos">
    <h1>Mis pedidos</h1>
    <?php if (!$pedidos): ?><p>Todavía no realizaste pedidos.</p><?php else: ?>
    <div class="tabla-pedidos-contenedor"><table class="tabla-pedidos"><thead><tr><th>Pedido</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Acciones</th></tr></thead><tbody>
    <?php foreach ($pedidos as $pedido): ?><tr><td>#<?= (int)$pedido['id'] ?></td><td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])), ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8') ?></td><td>$<?= number_format((float)$pedido['total'], 2, ',', '.') ?></td><td class="acciones-pedido"><a href="index.php?pagina=pedido-detalle&amp;id=<?= (int)$pedido['id'] ?>">Ver detalle</a><?php if ((int)$pedido['pago_aprobado'] === 1): ?><a href="index.php?pagina=factura&amp;id=<?= (int)$pedido['id'] ?>">Factura</a><?php elseif ($pedido['estado'] !== 'cancelado'): ?><a href="index.php?pagina=pago&amp;id=<?= (int)$pedido['id'] ?>">Pagar / reintentar</a><?php endif; ?></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
    <p><a href="index.php?pagina=productos">Seguir comprando</a></p>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
