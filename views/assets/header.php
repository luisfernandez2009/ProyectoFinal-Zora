<?php
$paginaCss = $paginaCss ?? null;
$nombreUsuario = $_SESSION['usuario_nombre'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="public/img/logos/favicon.png" type="image/png">
    <link rel="stylesheet" href="public/css/header.css?v=20261023">
    <link rel="stylesheet" href="public/css/footer.css?v=20261023">
    <?php if ($paginaCss): ?><link rel="stylesheet" href="<?= htmlspecialchars($paginaCss, ENT_QUOTES, 'UTF-8') ?>?v=20261023"><?php endif; ?>
    <title>Zora | Más que calzados, tu esencia</title>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.php?pagina=home"><img src="public/img/logos/ZoraLogo.png" alt="Logo de Zora"></a>
        <h2>Zora: Más que calzados, tu esencia.</h2>
    </div>
    <nav aria-label="Navegación principal">
        <a href="index.php?pagina=home">Inicio</a>
        <a href="index.php?pagina=productos">Productos</a>
        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?><a href="index.php?pagina=admin-usuarios">Usuarios</a><a href="index.php?pagina=admin-productos">Productos admin</a><?php endif; ?>
        <?php if ($nombreUsuario): ?>
            <span>Hola, <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8') ?></span>
            <a href="index.php?pagina=mis-pedidos">Mis pedidos</a>
            <a href="index.php?pagina=logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="index.php?pagina=registro">Registro</a>
            <a href="index.php?pagina=login">Iniciar sesión</a>
        <?php endif; ?>
        <a href="index.php?pagina=carrito" id="carritoHeader" aria-label="Carrito de compras"><img src="public/img/logos/CarritoCompra.png" alt="Carrito"></a>
    </nav>
</header>
<p id="mensajeCarritoHeader" role="status" aria-live="polite"></p>
<main class="contenido-principal">
