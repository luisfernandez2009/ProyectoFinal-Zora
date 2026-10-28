<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/ProductoModel.php';
require_once __DIR__ . '/models/UsuarioModel.php';
require_once __DIR__ . '/models/PedidoModel.php';
require_once __DIR__ . '/models/PagoModel.php';
require_once __DIR__ . '/models/MensajeModel.php';
require_once __DIR__ . '/controllers/ProductoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/PedidoController.php';
require_once __DIR__ . '/controllers/AdminUsuarioController.php';
require_once __DIR__ . '/controllers/AdminProductoController.php';
require_once __DIR__ . '/controllers/AdminPanelController.php';
require_once __DIR__ . '/controllers/AdminPedidoController.php';
require_once __DIR__ . '/controllers/MensajeController.php';
require_once __DIR__ . '/controllers/AdminMensajeController.php';

// Sincroniza rol y estado con la base en cada petición: una cuenta desactivada
// pierde el acceso incluso si ya tenía una sesión abierta.
if (!empty($_SESSION['usuario_id'])) {
    $usuarioSesion = (new UsuarioModel($conexion))->obtenerEstadoSesion((int)$_SESSION['usuario_id']);
    if (!$usuarioSesion || (int)$usuarioSesion['activo'] !== 1) {
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['mensaje_formulario'] = 'Tu cuenta está desactivada o ya no existe. Contactá con la tienda si necesitás ayuda.';
        header('Location: index.php?pagina=login');
        exit;
    }
    $_SESSION['usuario_nombre'] = $usuarioSesion['nombre'];
    $_SESSION['usuario_rol'] = $usuarioSesion['rol'];
}

$paginaSolicitada = $_GET['pagina'] ?? 'home';
$productoController = new ProductoController($conexion);
$usuarioController = new UsuarioController($conexion);
$pedidoController = new PedidoController($conexion);

switch ($paginaSolicitada) {
    case 'contacto':
        (new MensajeController($conexion))->mostrarFormulario();
        break;
    case 'mensaje-enviar':
        (new MensajeController($conexion))->enviar();
        break;
    case 'mis-mensajes':
        (new MensajeController($conexion))->misMensajes();
        break;
    case 'admin-mensajes':
        (new AdminMensajeController($conexion))->listar();
        break;
    case 'admin-mensaje-detalle':
        (new AdminMensajeController($conexion))->ver();
        break;
    case 'admin-mensaje-responder':
        (new AdminMensajeController($conexion))->responder();
        break;
    case 'admin-panel':
        (new AdminPanelController())->mostrar($conexion);
        break;
    case 'admin-pedidos':
        (new AdminPedidoController($conexion))->listar();
        break;
    case 'admin-pedido-detalle':
        (new AdminPedidoController($conexion))->ver();
        break;
    case 'admin-pedido-envio':
        (new AdminPedidoController($conexion))->cambiarEnvio();
        break;
    case 'admin-pedido-factura':
        (new AdminPedidoController($conexion))->factura();
        break;
    case 'admin-informe':
        (new AdminPedidoController($conexion))->informe();
        break;
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
    case 'admin-usuarios':
        (new AdminUsuarioController($conexion))->listar();
        break;
    case 'admin-usuario-rol':
        (new AdminUsuarioController($conexion))->cambiarRol();
        break;
    case 'admin-usuario-activo':
        (new AdminUsuarioController($conexion))->cambiarActivo();
        break;
    case 'admin-productos':
        (new AdminProductoController($conexion))->listar();
        break;
    case 'admin-producto-formulario':
        (new AdminProductoController($conexion))->formulario();
        break;
    case 'admin-producto-guardar':
        (new AdminProductoController($conexion))->guardar();
        break;
    case 'admin-producto-estado':
        (new AdminProductoController($conexion))->cambiarEstado();
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
    case 'pago':
        $pedidoController->pagar();
        break;
    case 'pago-resultado':
        $pedidoController->resultadoPago();
        break;
    case 'mis-pedidos':
        $pedidoController->misPedidos();
        break;
    case 'pedido-detalle':
        $pedidoController->detallePedido();
        break;
    case 'factura':
        $pedidoController->factura();
        break;
    default:
        http_response_code(404);
        $paginaCss = null;
        require __DIR__ . '/views/paginas/noEncontrado.php';
        break;
}
