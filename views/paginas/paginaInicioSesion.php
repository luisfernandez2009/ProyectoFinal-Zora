<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="login">
    <div class="titulo-login"><h1>Inicio de sesión</h1><p>Ingresa a tu cuenta para poder comprar.</p></div>
    <?php if (!empty($mensaje)): ?><p class="mensaje-exito" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if (!empty($error)): ?><p class="mensaje-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form id="formLogin" class="formulario-login" action="index.php?pagina=procesar-login" method="post">
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect ?? 'home', ENT_QUOTES, 'UTF-8') ?>">
        <label for="correo">Correo electrónico</label><input type="email" id="correo" name="correo" required autocomplete="email" placeholder="ejemplo@gmail.com">
        <label for="password">Contraseña</label><input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Escribe tu contraseña">
        <button type="submit">Iniciar sesión</button>
        <p class="registro">¿No tienes una cuenta? <a href="index.php?pagina=registro">Regístrate</a></p>
    </form>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
