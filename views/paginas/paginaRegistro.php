<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="registro">
    <div class="titulo-registro"><h1>Crear cuenta</h1><p>Regístrate para comprar en Zora.</p></div>
    <?php if (!empty($error)): ?><p class="mensaje-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form id="formRegistro" class="formulario-registro" action="index.php?pagina=procesar-registro" method="post">
        <label for="nombre">Nombre</label><input type="text" id="nombre" name="nombre" required maxlength="100" autocomplete="given-name">
        <label for="apellido">Apellido</label><input type="text" id="apellido" name="apellido" required maxlength="100" autocomplete="family-name">
        <label for="correo">Correo electrónico</label><input type="email" id="correo" name="correo" required maxlength="190" autocomplete="email">
        <label for="password">Contraseña (mínimo 8 caracteres)</label><input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
        <label for="confirmarPassword">Confirmar contraseña</label><input type="password" id="confirmarPassword" name="confirmarPassword" required minlength="8" autocomplete="new-password">
        <button type="submit">Crear cuenta</button>
        <p class="iniciar-sesion">¿Ya tienes una cuenta? <a href="index.php?pagina=login">Inicia sesión</a></p>
    </form>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
