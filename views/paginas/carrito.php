<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="cabecera-catalogo">
    <h1>Carrito de compras</h1>
    <?php if (!empty($error = $_SESSION['error_formulario'] ?? null)): unset($_SESSION['error_formulario']); ?>
        <p class="mensaje-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <div id="contenidoCarrito"><p id="carritoVacio">Tu carrito está vacío.</p><div id="listaCarrito"></div><p id="totalCarrito"></p></div>
    <form id="formPedido" action="index.php?pagina=confirmar-pedido" method="post">
        <input type="hidden" name="carritoJson" id="carritoJson">
        <h2>Datos de entrega</h2>
        <label for="nombre_entrega">Nombre y apellido</label><input id="nombre_entrega" name="nombre_entrega" required maxlength="150" value="<?= htmlspecialchars((string)($_SESSION['usuario_nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        <label for="telefono_entrega">Teléfono</label><input id="telefono_entrega" name="telefono_entrega" maxlength="30">
        <label for="direccion_entrega">Dirección</label><input id="direccion_entrega" name="direccion_entrega" required maxlength="255">
        <label for="ciudad_entrega">Ciudad</label><input id="ciudad_entrega" name="ciudad_entrega" required maxlength="100">
        <label for="departamento_entrega">Departamento</label><input id="departamento_entrega" name="departamento_entrega" required maxlength="100">
        <label for="codigo_postal">Código postal</label><input id="codigo_postal" name="codigo_postal" maxlength="20">
        <label for="metodo_pago">Método de pago</label><select id="metodo_pago" name="metodo_pago"><option value="Contra entrega">Contra entrega</option><option value="Transferencia">Transferencia</option></select>
        <label for="observaciones">Observaciones</label><textarea id="observaciones" name="observaciones" maxlength="1000"></textarea>
        <button type="submit" id="confirmarCompra">Confirmar compra</button>
    </form>
    <p><a href="index.php?pagina=productos">Seguir comprando</a></p>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
