<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="admin-pedidos mensajes-pagina">
    <p class="acciones-superiores"><a class="boton-volver-panel" href="index.php?pagina=admin-panel">← Volver al panel de administración</a></p>
    <div class="admin-pedidos-cabecera"><div><p class="panel-eyebrow">ATENCIÓN AL CLIENTE</p><h1>Mensajes de clientes</h1><p>Leé las consultas y respondé desde la tienda.</p></div></div>
    <?php if ($aviso !== null): ?><div class="mensajes-aviso <?= htmlspecialchars($aviso['tipo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($aviso['texto'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <form action="index.php" method="get" class="panel-filtros">
        <input type="hidden" name="pagina" value="admin-mensajes">
        <label>Mostrar mensajes<select name="filtro"><option value="" <?= $filtroActual === '' ? 'selected' : '' ?>>Todos</option><option value="sin_responder" <?= $filtroActual === 'sin_responder' ? 'selected' : '' ?>>Sin responder</option><option value="respondidos" <?= $filtroActual === 'respondidos' ? 'selected' : '' ?>>Respondidos</option></select></label>
        <button class="panel-boton" type="submit">Filtrar</button>
        <a href="index.php?pagina=admin-mensajes">Limpiar</a>
    </form>
    <?php if (!$mensajes): ?><div class="mensajes-vacio">No hay mensajes para mostrar con este filtro.</div><?php else: ?>
    <div class="panel-tabla-scroll"><table class="panel-tabla"><thead><tr><th>N.º</th><th>Cliente</th><th>Asunto</th><th>Fecha</th><th>Estado</th><th></th></tr></thead><tbody>
    <?php foreach ($mensajes as $m): ?><tr>
        <td>#<?= (int)$m['id'] ?></td>
        <td><?= htmlspecialchars($m['cliente_nombre'].' '.$m['cliente_apellido'], ENT_QUOTES, 'UTF-8') ?><br><small><?= htmlspecialchars($m['cliente_correo'], ENT_QUOTES, 'UTF-8') ?></small></td>
        <td><?= htmlspecialchars($m['asunto'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($m['fecha'])), ENT_QUOTES, 'UTF-8') ?></td>
        <td><?php if ((int)$m['respondido'] === 1): ?><span class="mensaje-estado respondido">Respondido</span><?php else: ?><span class="mensaje-estado pendiente">Sin responder</span><?php endif; ?></td>
        <td><a href="index.php?pagina=admin-mensaje-detalle&amp;id=<?= (int)$m['id'] ?>">Leer consulta</a></td>
    </tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
