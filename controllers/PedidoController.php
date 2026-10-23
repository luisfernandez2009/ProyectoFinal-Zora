<?php
declare(strict_types=1);

class PedidoController
{
    private PedidoModel $pedidoModel;
    private PagoModel $pagoModel;
    public function __construct(mysqli $conexion) { $this->pedidoModel = new PedidoModel($conexion); $this->pagoModel = new PagoModel($conexion); }

    public function confirmarPedido(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?pagina=carrito'); exit; }
        if (empty($_SESSION['usuario_id'])) {
            $_SESSION['error_formulario'] = 'Iniciá sesión antes de confirmar tu compra.';
            header('Location: index.php?pagina=login&redirect=carrito'); exit;
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
            header('Location: index.php?pagina=pago&id=' . $pedidoId); exit;
        } catch (Throwable $e) {
            $_SESSION['error_formulario'] = $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo guardar el pedido. Verificá la base de datos e intentá nuevamente.';
            header('Location: index.php?pagina=carrito'); exit;
        }
    }

    public function pagar(): void
    {
        $this->requerirLogin();
        $pedido = $this->obtenerPedidoDelUsuario();
        $pagoAprobado = $this->pagoModel->obtenerAprobado((int)$pedido['id']);
        if ($pagoAprobado) {
            header('Location: index.php?pagina=pago-resultado&id=' . (int)$pedido['id']);
            exit;
        }

        if (empty($_SESSION['csrf_pago'])) {
            $_SESSION['csrf_pago'] = bin2hex(random_bytes(32));
        }
        $errores = [];
        $titular = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titular = trim((string)($_POST['titular'] ?? ''));
            $numero = preg_replace('/[^0-9]/', '', (string)($_POST['numero'] ?? ''));
            $vencimiento = trim((string)($_POST['vencimiento'] ?? ''));
            $cvv = trim((string)($_POST['cvv'] ?? ''));
            $token = (string)($_POST['csrf_token'] ?? '');

            if (!hash_equals((string)$_SESSION['csrf_pago'], $token)) {
                $errores[] = 'La sesión del formulario venció. Recargá la página e intentá de nuevo.';
            }
            $largoTitular = function_exists('mb_strlen') ? mb_strlen($titular, 'UTF-8') : strlen($titular);
            if ($largoTitular < 3 || $largoTitular > 80) {
                $errores[] = 'El nombre del titular debe tener entre 3 y 80 caracteres.';
            }
            if (strlen($numero) < 13 || strlen($numero) > 19 || !$this->validarLuhn($numero)) {
                $errores[] = 'Ingresá un número de tarjeta válido.';
            }
            if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $vencimiento, $coincidencias)) {
                $errores[] = 'El vencimiento debe tener el formato MM/AA.';
            } else {
                $mes = (int)$coincidencias[1];
                $anio = 2000 + (int)$coincidencias[2];
                $anioActual = (int)date('Y');
                $mesActual = (int)date('n');
                if ($anio < $anioActual || ($anio === $anioActual && $mes < $mesActual)) {
                    $errores[] = 'La tarjeta está vencida.';
                }
            }
            if (!ctype_digit($cvv) || !in_array(strlen($cvv), [3, 4], true)) {
                $errores[] = 'El código de seguridad debe tener 3 o 4 dígitos.';
            }

            if (!$errores) {
                // Simulación didáctica: la tarjeta de prueba terminada en 4242 se aprueba.
                // No se guardan el número completo, vencimiento ni CVV.
                $resultado = substr($numero, -4) === '4242' ? 'aprobado' : 'rechazado';
                $this->pagoModel->registrarIntento(
                    (int)$pedido['id'],
                    (float)$pedido['total'],
                    substr($numero, -4),
                    $resultado
                );
                header('Location: index.php?pagina=pago-resultado&id=' . (int)$pedido['id']);
                exit;
            }
        }

        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/../views/paginas/pago.php';
    }

    public function resultadoPago(): void
    {
        $this->requerirLogin();
        $pedido = $this->obtenerPedidoDelUsuario();
        $pago = $this->pagoModel->obtenerUltimo((int)$pedido['id']);
        if (!$pago) {
            header('Location: index.php?pagina=pago&id=' . (int)$pedido['id']);
            exit;
        }
        $detalle = $this->pedidoModel->obtenerDetalle((int)$pedido['id']);
        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/../views/paginas/pago_resultado.php';
    }

    public function misPedidos(): void
    {
        $this->requerirLogin();
        $pedidos = $this->pedidoModel->listarPorUsuario((int)$_SESSION['usuario_id']);
        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/../views/paginas/mis_pedidos.php';
    }

    public function detallePedido(): void
    {
        $this->requerirLogin();
        $pedido = $this->obtenerPedidoDelUsuario();
        $detalle = $this->pedidoModel->obtenerDetalle((int)$pedido['id']);
        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/../views/paginas/pedido_detalle.php';
    }

    public function factura(): void
    {
        $this->requerirLogin();
        $pedido = $this->obtenerPedidoDelUsuario();
        $pago = $this->pagoModel->obtenerAprobado((int)$pedido['id']);
        if (!$pago) {
            header('Location: index.php?pagina=pago&id=' . (int)$pedido['id']);
            exit;
        }
        $detalle = $this->pedidoModel->obtenerDetalle((int)$pedido['id']);
        $paginaCss = 'public/css/paginaListadoProductos.css';
        require __DIR__ . '/../views/paginas/factura.php';
    }

    private function obtenerPedidoDelUsuario(): array
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $pedido = ($id && $id > 0)
            ? $this->pedidoModel->obtenerPedidoCompleto((int)$id, (int)$_SESSION['usuario_id'])
            : null;
        if (!$pedido) {
            http_response_code(403);
            $paginaCss = 'public/css/paginaListadoProductos.css';
            require __DIR__ . '/../views/paginas/sin_permiso.php';
            exit;
        }
        return $pedido;
    }

    private function requerirLogin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: index.php?pagina=login&redirect=carrito');
            exit;
        }
    }

    private function validarLuhn(string $numero): bool
    {
        $suma = 0;
        $doblar = false;
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $digito = (int)$numero[$i];
            if ($doblar) {
                $digito *= 2;
                if ($digito > 9) { $digito -= 9; }
            }
            $suma += $digito;
            $doblar = !$doblar;
        }
        return $suma % 10 === 0;
    }
}
