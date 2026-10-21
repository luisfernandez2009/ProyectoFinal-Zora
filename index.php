<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/ProductoModel.php';
require_once __DIR__ . '/models/UsuarioModel.php';
require_once __DIR__ . '/models/PedidoModel.php';
require_once __DIR__ . '/controllers/ProductoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/PedidoController.php';

$paginaSolicitada = $_GET['pagina'] ?? 'home';
$productoController = new ProductoController($conexion);
$usuarioController = new UsuarioController($conexion);
$pedidoController = new PedidoController($conexion);

switch ($paginaSolicitada) {
    case 'home':
        $productosDestacados = (new ProductoModel($conexion))->listarProductos(null, null, 4);
        $paginaCss = 'public/css/paginaInicio.css';
        $paginaJs = 'public/js/paginaInicio.js';
        require __DIR__ . '/views/paginas/home.php';
        break;
    case 'productos':
        $productoController->mostrarCatalogo();
        break;
    case 'detalle':
        $productoController->mostrarDetalle();
        break;
    case 'registro':
        $usuarioController->mostrarRegistro();
        break;
    case 'procesar-registro':
        $usuarioController->procesarRegistro();
        break;
    case 'login':
        $usuarioController->mostrarLogin();
        break;
    case 'procesar-login':
        $usuarioController->procesarLogin();
        break;
    case 'logout':
        $usuarioController->cerrarSesion();
        break;
    case 'carrito':
        $paginaCss = 'public/css/paginaListadoProductos.css';
        $paginaJs = 'public/js/carrito.js';
        require __DIR__ . '/views/paginas/carrito.php';
        break;
    case 'confirmar-pedido':
        $pedidoController->confirmarPedido();
        break;
    case 'pedido-confirmado':
        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/views/paginas/pedido_confirmado.php';
        break;
    default:
        http_response_code(404);
        $paginaCss = null;
        require __DIR__ . '/views/paginas/noEncontrado.php';
        break;
}
