<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="mensajes-pagina">
    <p class="mensajes-eyebrow">ATENCIÓN AL CLIENTE</p>
    <h1>Mis mensajes</h1>
    <p>Consultas que enviaste y respuestas de la tienda.</p>
    <p><a class="mensajes-boton" href="index.php?pagina=contacto">＋ Escribir una consulta</a></p>
    <?php if ($aviso !== null): ?><div class="mensajes-aviso <?= htmlspecialchars($aviso['tipo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($aviso['texto'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if (!$mensajes): ?><div class="mensajes-vacio">Todavía no enviaste ninguna consulta.</div><?php else: ?>
        <?php foreach ($mensajes as $m): ?>
        <article class="mensaje-caja">
            <div class="mensaje-caja-cabecera"><h2><?= htmlspecialchars($m['asunto'], ENT_QUOTES, 'UTF-8') ?></h2>
                <?php if ($m['respuesta'] === null): ?><span class="mensaje-estado pendiente">Pendiente de respuesta</span><?php else: ?><span class="mensaje-estado respondido">Respondido</span><?php endif; ?>
            </div>
            <p class="mensaje-fecha">Enviado el <?= htmlspecialchars(date('d/m/Y H:i', strtotime($m['fecha'])), ENT_QUOTES, 'UTF-8') ?></p>
            <div class="mensaje-texto"><?= nl2br(htmlspecialchars($m['texto'], ENT_QUOTES, 'UTF-8')) ?></div>
            <?php if ($m['respuesta'] !== null): ?>
            <section class="mensaje-respuesta"><strong>Respuesta de la tienda</strong><p class="mensaje-fecha"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($m['fecha_respuesta'])), ENT_QUOTES, 'UTF-8') ?></p><div><?= nl2br(htmlspecialchars($m['respuesta'], ENT_QUOTES, 'UTF-8')) ?></div></section>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
