<?php
declare(strict_types=1);

class PedidoController
{
    private PedidoModel $pedidoModel;
    public function __construct(mysqli $conexion) { $this->pedidoModel = new PedidoModel($conexion); }

    public function confirmarPedido(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?pagina=carrito'); exit; }
        if (empty($_SESSION['usuario_id'])) {
            $_SESSION['error_formulario'] = 'Iniciá sesión antes de confirmar tu compra.';
            header('Location: index.php?pagina=login'); exit;
        }
        $raw = (string)($_POST['carritoJson'] ?? '');
        $items = json_decode($raw, true);
        if (!is_array($items) || !$items) {
            $_SESSION['error_formulario'] = 'El carrito está vacío o no es válido.';
            header('Location: index.php?pagina=carrito'); exit;
        }
        $datos = [
            'nombre'=>trim((string)($_POST['nombre_entrega'] ?? '')),
            'telefono'=>trim((string)($_POST['telefono_entrega'] ?? '')),
            'direccion'=>trim((string)($_POST['direccion_entrega'] ?? '')),
            'ciudad'=>trim((string)($_POST['ciudad_entrega'] ?? '')),
            'departamento'=>trim((string)($_POST['departamento_entrega'] ?? '')),
            'codigo_postal'=>trim((string)($_POST['codigo_postal'] ?? '')),
            'metodo_pago'=>trim((string)($_POST['metodo_pago'] ?? '')),
            'observaciones'=>trim((string)($_POST['observaciones'] ?? ''))
        ];
        if ($datos['nombre']==='' || $datos['direccion']==='' || $datos['ciudad']==='' || $datos['departamento']==='') {
            $_SESSION['error_formulario'] = 'Completá nombre, dirección, ciudad y departamento para la entrega.';
            header('Location: index.php?pagina=carrito'); exit;
        }
        try {
            $pedidoId = $this->pedidoModel->crearPedido((int)$_SESSION['usuario_id'], $datos, $items);
            $_SESSION['pedido_confirmado_id'] = $pedidoId;
            header('Location: index.php?pagina=pedido-confirmado'); exit;
        } catch (Throwable $e) {
            $_SESSION['error_formulario'] = $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo guardar el pedido. Verificá la base de datos e intentá nuevamente.';
            header('Location: index.php?pagina=carrito'); exit;
        }
    }
}
