<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="panel-admin">
    <div class="panel-admin-cabecera">
        <div><p class="panel-eyebrow">ZORA · ADMINISTRACIÓN</p><h1>Panel de administración</h1><p>Gestioná el catálogo, los pedidos y la actividad de la tienda desde un solo lugar.</p></div>
        <span class="panel-rol"><?= ($_SESSION['usuario_rol'] ?? '') === 'admin' ? 'Administrador' : 'Moderador' ?></span>
    </div>

    <?php if ($mensajesSinResponder > 0): ?>
        <div class="panel-alerta"><strong><?= (int)$mensajesSinResponder ?> mensaje(s) de clientes esperan respuesta.</strong><a href="index.php?pagina=admin-mensajes&amp;filtro=sin_responder">Revisar mensajes →</a></div>
    <?php endif; ?>

    <?php if (!empty($pedidosPorEnviar)): ?>
        <div class="panel-alerta"><strong><?= count($pedidosPorEnviar) ?> pedido(s) pagado(s) esperan preparación.</strong><a href="index.php?pagina=admin-pedidos&amp;estado=pagado&amp;estado_envio=preparando">Revisar pedidos →</a></div>
    <?php endif; ?>

    <div class="panel-modulos">
        <article class="panel-modulo">
            <div class="panel-modulo-icono">📦</div><h2>Gestionar productos</h2>
            <p>Crear y editar productos, administrar imágenes, stock por talle y disponibilidad.</p>
            <a class="panel-boton" href="index.php?pagina=admin-productos">Abrir productos</a>
        </article>
        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
        <article class="panel-modulo">
            <div class="panel-modulo-icono">👥</div><h2>Gestionar usuarios</h2>
            <p>Administrar cuentas, cambiar roles y activar o desactivar usuarios.</p>
            <a class="panel-boton" href="index.php?pagina=admin-usuarios">Abrir usuarios</a>
        </article>
        <?php endif; ?>
        <article class="panel-modulo">
            <div class="panel-modulo-icono">🧾</div><h2>Gestionar pedidos</h2>
            <p>Consultar compras, revisar pagos y datos de entrega, avanzar envíos y abrir facturas.</p>
            <a class="panel-boton" href="index.php?pagina=admin-pedidos">Abrir pedidos</a>
        </article>
        <article class="panel-modulo">
            <div class="panel-modulo-icono">💬</div><h2>Mensajes</h2>
            <p>Leer las consultas de los clientes y responderlas desde la tienda.</p>
            <a class="panel-boton" href="index.php?pagina=admin-mensajes">Ver mensajes</a>
        </article>
        <article class="panel-modulo">
            <div class="panel-modulo-icono">📊</div><h2>Informe de transacciones</h2>
            <p>Consultar ventas, totales y promedios para un rango de fechas.</p>
            <a class="panel-boton panel-boton-secundario" href="index.php?pagina=admin-informe">Ver informe</a>
        </article>
    </div>

    <section class="panel-ultimos">
        <div class="panel-seccion-titulo"><div><h2>Últimos pedidos</h2><p>Actividad reciente de la tienda.</p></div><a href="index.php?pagina=admin-pedidos">Ver todos →</a></div>
        <?php if (empty($ultimosPedidos)): ?><p class="panel-vacio">Todavía no hay pedidos registrados.</p><?php else: ?>
        <div class="panel-tabla-scroll"><table class="panel-tabla"><thead><tr><th>Pedido</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Pago</th><th>Envío</th><th></th></tr></thead><tbody>
        <?php foreach ($ultimosPedidos as $p): ?><tr>
            <td>#<?= (int)$p['id'] ?></td><td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($p['fecha_pedido'])), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($p['cliente_nombre'] . ' ' . $p['cliente_apellido'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>$<?= number_format((float)$p['total'], 2, ',', '.') ?></td>
            <td><span class="panel-estado estado-<?= htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst($p['estado']), ENT_QUOTES, 'UTF-8') ?></span></td>
            <td><?= $p['estado'] === 'pagado' ? htmlspecialchars(ucfirst($p['estado_envio'] ?? 'preparando'), ENT_QUOTES, 'UTF-8') : '—' ?></td>
            <td><a href="index.php?pagina=admin-pedido-detalle&amp;id=<?= (int)$p['id'] ?>">Detalle</a></td>
        </tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
