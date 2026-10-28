<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="mensajes-pagina">
    <p class="mensajes-eyebrow">ATENCIÓN AL CLIENTE</p>
    <h1>Contacto</h1>
    <p>Escribinos tu consulta sobre productos, pedidos o cualquier duda. Te responderemos desde esta misma página.</p>
    <p><a class="mensajes-boton-secundario" href="index.php?pagina=mis-mensajes">Ver mis mensajes →</a></p>
    <?php if ($aviso !== null): ?><div class="mensajes-aviso <?= htmlspecialchars($aviso['tipo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($aviso['texto'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if (!empty($errores)): ?><div class="mensajes-aviso error"><ul><?php foreach ($errores as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form action="index.php?pagina=mensaje-enviar" method="post" class="mensajes-formulario">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_mensajes'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <label for="asunto">Asunto</label>
        <input id="asunto" name="asunto" type="text" maxlength="<?= (int)$maxAsunto ?>" required value="<?= htmlspecialchars($asunto, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej.: Consulta sobre mi pedido">
        <label for="texto">Mensaje (máximo <?= (int)$maxTexto ?> caracteres)</label>
        <textarea id="texto" name="texto" rows="7" maxlength="<?= (int)$maxTexto ?>" required><?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?></textarea>
        <button class="mensajes-boton" type="submit">Enviar consulta</button>
    </form>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
