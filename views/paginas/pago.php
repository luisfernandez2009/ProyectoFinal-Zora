<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="pantalla-pago">
    <h1>Pago del pedido #<?= (int)$pedido['id'] ?></h1>
    <p>El total se obtiene del pedido guardado en el servidor; no se recibe desde el formulario.</p>
    <div class="resumen-pago">
        <h2>Resumen de compra</h2>
        <ul><?php foreach ($detalle as $linea): ?><li><?= htmlspecialchars($linea['nombre'], ENT_QUOTES, 'UTF-8') ?> — talle <?= (int)$linea['talle'] ?> × <?= (int)$linea['cantidad'] ?> <strong>$<?= number_format((float)$linea['subtotal'], 2, ',', '.') ?></strong></li><?php endforeach; ?></ul>
        <p class="total-pago">Total: $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></p>
    </div>
    <?php if (!empty($errores)): ?><ul class="errores-pago" role="alert"><?php foreach ($errores as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
    <div class="aviso-simulacion"><strong>Simulación educativa:</strong> usá la tarjeta de prueba <code>4242 4242 4242 4242</code>, una fecha futura y cualquier CVV de 3 o 4 dígitos. Otra tarjeta válida se simula como rechazada. No ingreses datos de una tarjeta real.</div>
    <form class="form-pago" action="index.php?pagina=pago&amp;id=<?= (int)$pedido['id'] ?>" method="post" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_pago'], ENT_QUOTES, 'UTF-8') ?>">
        <label for="titular">Titular de la tarjeta</label><input id="titular" name="titular" maxlength="80" required autocomplete="cc-name" value="<?= htmlspecialchars($titular, ENT_QUOTES, 'UTF-8') ?>" placeholder="Nombre que aparece en la tarjeta">
        <label for="numero">Número de tarjeta</label><input id="numero" name="numero" inputmode="numeric" autocomplete="cc-number" minlength="13" maxlength="23" required placeholder="4242 4242 4242 4242">
        <label for="vencimiento">Vencimiento (MM/AA)</label><input id="vencimiento" name="vencimiento" inputmode="numeric" autocomplete="cc-exp" pattern="(0[1-9]|1[0-2])/[0-9]{2}" required placeholder="12/30">
        <label for="cvv">Código de seguridad</label><input id="cvv" name="cvv" inputmode="numeric" autocomplete="cc-csc" minlength="3" maxlength="4" required placeholder="123">
        <button type="submit">Pagar $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></button>
    </form>
    <p><a href="index.php?pagina=mis-pedidos">Ver mis pedidos</a></p>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
