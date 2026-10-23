<?php
$pedidoId = (int)($_SESSION['pedido_confirmado_id'] ?? 0);
unset($_SESSION['pedido_confirmado_id']);
require __DIR__ . '/../assets/header.php';
?>
<section class="cabecera-catalogo">
    <h1>¡Gracias por tu compra!</h1>
    <?php if ($pedidoId > 0): ?><p>Tu pedido #<?= $pedidoId ?> fue registrado correctamente.</p><?php else: ?><p>Si acabás de confirmar tu compra, revisá el estado con el administrador de la tienda.</p><?php endif; ?>
    <p>El equipo de Zora podrá contactarte para coordinar la entrega.</p>
    <a href="index.php?pagina=productos">Volver al catálogo</a>
</section>
<script>try { localStorage.removeItem('zora_carrito'); } catch (e) {}</script>
<?php require __DIR__ . '/../assets/footer.php'; ?>
