<?php
/**
 * index.php — PUNTO DE ENTRADA ÚNICO DEL SITIO (front controller)
 * -------------------------------------------------------
 * TODAS las páginas del sitio pasan por acá. Este archivo:
 *   1. Incluye lo que va a necesitar cualquier página (conexión, modelos, controllers)
 *   2. Mira qué página se pidió (?pagina=catalogo, ?pagina=carrito, etc.)
 *   3. Instancia el Controlador correspondiente y llama al método que resuelve esa página
 *
 * Si algún día agregan una página nueva, acá es donde hay que
 * sumar un "case" nuevo.
 * -------------------------------------------------------
 */

// 1. Conexión a la base de datos (queda disponible como $conexion para todos)
require_once __DIR__ . '/config/conexion.php';

// 1.1 Clases de modelos que vamos a necesitar en distintas páginas.
require_once __DIR__ . '/models/ProductoModel.php';
//require_once __DIR__ . '/models/PedidoModel.php';

// 1.2 Clases de controllers.
require_once __DIR__ . '/controllers/ProductoController.php';
//require_once __DIR__ . '/controllers/PedidoController.php';

// 2. ¿Qué página pidió el usuario? Si no especificó nada, mostramos "home"
$paginaSolicitada = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';

// 3. Router: decide qué controlador (y qué método) ejecutar, o qué vista mostrar directamente
switch ($paginaSolicitada) {

    case 'home':
        // Esta vista es simple y no necesita datos de la base de datos
        require __DIR__ . '/views/home.php';
        break;

    case 'productos':
        $productoController = new ProductoController($conexion);
        $productoController->mostrarCatalogo();
        break;

    case 'detalle':
        // Detalle de un producto puntual: index.php?pagina=producto&id=3
        $productoController = new ProductoController($conexion);
        $productoController->mostrarDetalle();
        break;

    case 'carrito':
        // El carrito se arma con JavaScript y LocalStorage,
        // así que tampoco necesita pasar por un controlador
        require __DIR__ . '/views/carrito.php';
        break;

    case 'confirmar-pedido':
        // Acá sí necesitamos un controlador: hay que guardar el
        // pedido en la base de datos antes de mostrar nada
        $pedidoController = new PedidoController($conexion);
        $pedidoController->confirmarPedido();
        break;

    case 'registro':
        require __DIR__ . '/views/registro.php';
        break;

    case 'login':
        require __DIR__ . '/views/login.php';
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}