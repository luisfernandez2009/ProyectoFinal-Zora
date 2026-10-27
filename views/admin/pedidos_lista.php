<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="admin-pedidos">
    <div class="admin-pedidos-cabecera"><div><p class="panel-eyebrow">ADMINISTRACIÓN</p><h1>Pedidos</h1><p>Consultá las compras, los pagos y el avance de cada envío.</p></div><a class="panel-boton panel-boton-secundario" href="index.php?pagina=admin-panel">← Panel de administración</a></div>
    <?php if ($mensaje): ?><p class="panel-mensaje"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form class="panel-filtros" action="index.php" method="get">
        <input type="hidden" name="pagina" value="admin-pedidos">
        <label>Estado del pago<select name="estado"><option value="">Todos</option><?php foreach (['pendiente','pagado','rechazado','cancelado'] as $e): ?><option value="<?= $e ?>" <?= $estado === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option><?php endforeach; ?></select></label>
        <label>Estado del envío<select name="estado_envio"><option value="">Todos</option><?php foreach (['preparando','enviado','entregado'] as $e): ?><option value="<?= $e ?>" <?= $envio === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option><?php endforeach; ?></select></label>
        <label>Desde<input type="date" name="desde" value="<?= htmlspecialchars($desde, ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Hasta<input type="date" name="hasta" value="<?= htmlspecialchars($hasta, ENT_QUOTES, 'UTF-8') ?>"></label>
        <button class="panel-boton" type="submit">Filtrar</button><a href="index.php?pagina=admin-pedidos">Limpiar</a>
    </form>
    <?php if (!$pedidos): ?><p class="panel-vacio">No hay pedidos que coincidan con esos filtros.</p><?php else: ?>
    <p class="panel-conteo"><?= count($pedidos) ?> pedido(s)</p><div class="panel-tabla-scroll"><table class="panel-tabla"><thead><tr><th>N.º</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Pago</th><th>Envío</th><th>Acciones</th></tr></thead><tbody>
    <?php foreach ($pedidos as $p): ?><tr><td>#<?= (int)$p['id'] ?></td><td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($p['fecha_pedido'])), ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($p['cliente_nombre'].' '.$p['cliente_apellido'], ENT_QUOTES, 'UTF-8') ?></td><td>$<?= number_format((float)$p['total'], 2, ',', '.') ?></td><td><span class="panel-estado estado-<?= htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst($p['estado']), ENT_QUOTES, 'UTF-8') ?></span></td><td><?= $p['estado'] === 'pagado' ? htmlspecialchars(ucfirst($p['estado_envio'] ?? 'preparando'), ENT_QUOTES, 'UTF-8') : '—' ?></td><td><a href="index.php?pagina=admin-pedido-detalle&amp;id=<?= (int)$p['id'] ?>">Ver detalle</a></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
