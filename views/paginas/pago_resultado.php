<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="resultado-pago">
    <?php if ($pago['resultado'] === 'aprobado'): ?>
        <h1>¡Pago aprobado!</h1><p>Tu pedido #<?= (int)$pedido['id'] ?> quedó confirmado.</p>
        <p>Importe: <strong>$<?= number_format((float)$pago['monto'], 2, ',', '.') ?></strong></p>
        <p>Tarjeta terminada en <?= htmlspecialchars($pago['ultimos4'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars(date('d/m/Y H:i', strtotime($pago['fecha'])), ENT_QUOTES, 'UTF-8') ?></p>
        <p class="acciones-pago"><a href="index.php?pagina=factura&amp;id=<?= (int)$pedido['id'] ?>">Ver factura</a><a href="index.php?pagina=pedido-detalle&amp;id=<?= (int)$pedido['id'] ?>">Ver pedido</a></p>
    <?php else: ?>
        <h1>El pago fue rechazado</h1><p>El pedido #<?= (int)$pedido['id'] ?> sigue registrado, pero no tiene un pago aprobado.</p>
        <p>Si querés, podés volver a intentarlo con la tarjeta de prueba indicada en la pantalla de pago.</p>
        <p><a href="index.php?pagina=pago&amp;id=<?= (int)$pedido['id'] ?>">Reintentar pago</a></p>
    <?php endif; ?>
    <p><a href="index.php?pagina=mis-pedidos">Ir a mis pedidos</a></p>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
