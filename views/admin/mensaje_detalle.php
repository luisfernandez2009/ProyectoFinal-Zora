<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="admin-pedidos mensajes-pagina">
    <p class="acciones-superiores"><a class="boton-volver-panel" href="index.php?pagina=admin-mensajes">← Volver a mensajes</a></p>
    <div class="admin-pedidos-cabecera"><div><p class="panel-eyebrow">ATENCIÓN AL CLIENTE</p><h1>Consulta #<?= (int)$mensaje['id'] ?></h1><p>Revisá el mensaje del cliente y respondé si está pendiente.</p></div></div>
    <?php if ($aviso !== null): ?><div class="mensajes-aviso <?= htmlspecialchars($aviso['tipo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($aviso['texto'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <article class="mensaje-caja">
        <h2><?= htmlspecialchars($mensaje['asunto'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($mensaje['cliente_nombre'].' '.$mensaje['cliente_apellido'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($mensaje['cliente_correo'], ENT_QUOTES, 'UTF-8') ?>)</p>
        <p class="mensaje-fecha">Enviado el <?= htmlspecialchars(date('d/m/Y H:i', strtotime($mensaje['fecha'])), ENT_QUOTES, 'UTF-8') ?></p>
        <div class="mensaje-texto"><?= nl2br(htmlspecialchars($mensaje['texto'], ENT_QUOTES, 'UTF-8')) ?></div>
    </article>
    <?php if ($mensaje['respuesta'] !== null): ?>
        <article class="mensaje-respuesta"><h2>Respuesta enviada</h2><p class="mensaje-fecha"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($mensaje['fecha_respuesta'])), ENT_QUOTES, 'UTF-8') ?></p><div><?= nl2br(htmlspecialchars($mensaje['respuesta'], ENT_QUOTES, 'UTF-8')) ?></div></article>
    <?php else: ?>
        <form action="index.php?pagina=admin-mensaje-responder&amp;id=<?= (int)$mensaje['id'] ?>" method="post" class="mensajes-formulario">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin_mensajes'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <label for="respuesta">Tu respuesta (máximo <?= (int)$maxRespuesta ?> caracteres)</label>
            <textarea id="respuesta" name="respuesta" rows="7" maxlength="<?= (int)$maxRespuesta ?>" required><?= htmlspecialchars($textoRespuesta, ENT_QUOTES, 'UTF-8') ?></textarea>
            <button class="mensajes-boton" type="submit" onclick="return confirm('Una vez enviada, la respuesta no se puede modificar. ¿Querés enviarla?')">Enviar respuesta</button>
        </form>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
