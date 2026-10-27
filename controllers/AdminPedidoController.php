<?php
declare(strict_types=1);

class AdminPedidoController
{
    private PedidoModel $pedidoModel;
    private PagoModel $pagoModel;
    private array $estadosPago = ['pendiente', 'pagado', 'rechazado', 'cancelado'];
    private array $estadosEnvio = ['preparando', 'enviado', 'entregado'];
    private array $siguienteEnvio = ['preparando' => 'enviado', 'enviado' => 'entregado'];

    public function __construct(mysqli $conexion)
    {
        $this->exigirPermiso();
        $this->pedidoModel = new PedidoModel($conexion);
        $this->pagoModel = new PagoModel($conexion);
        if (empty($_SESSION['csrf_admin_pedidos'])) $_SESSION['csrf_admin_pedidos'] = bin2hex(random_bytes(32));
    }

    public function listar(): void
    {
        $estado = $this->leerOpcion('estado', $this->estadosPago);
        $envio = $this->leerOpcion('estado_envio', $this->estadosEnvio);
        $desde = $this->leerFecha('desde');
        $hasta = $this->leerFecha('hasta');
        $pedidos = $this->pedidoModel->listarAdmin(['estado'=>$estado, 'estado_envio'=>$envio, 'desde'=>$desde, 'hasta'=>$hasta]);
        $mensaje = $this->leerAviso();
        $paginaCss = 'public/css/adminPanel.css';
        require __DIR__ . '/../views/admin/pedidos_lista.php';
    }

    public function ver(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $pedido = ($id && $id > 0) ? $this->pedidoModel->obtenerParaAdmin((int)$id) : null;
        if (!$pedido) $this->redirigir('admin-pedidos', 'Pedido inexistente o identificador inválido.');
        $detalle = $this->pedidoModel->obtenerDetalle((int)$pedido['id']);
        $pago = $this->pagoModel->obtenerAprobado((int)$pedido['id']);
        $siguiente = $this->siguienteEnvio[$pedido['estado_envio'] ?? 'preparando'] ?? null;
        $mensaje = $this->leerAviso();
        $paginaCss = 'public/css/adminPanel.css';
        require __DIR__ . '/../views/admin/pedido_detalle.php';
    }

    public function cambiarEnvio(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('admin-pedidos', 'La solicitud no es válida.');
        $token = (string)($_POST['csrf_token'] ?? '');
        if (!hash_equals((string)($_SESSION['csrf_admin_pedidos'] ?? ''), $token)) $this->redirigir('admin-pedidos', 'La sesión venció. Volvé a intentarlo.');
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) $this->redirigir('admin-pedidos', 'El pedido indicado no es válido.');
        $pedido = $this->pedidoModel->obtenerParaAdmin((int)$id);
        if (!$pedido) $this->redirigir('admin-pedidos', 'No se encontró el pedido.');
        $actual = (string)($pedido['estado_envio'] ?? 'preparando');
        $siguiente = $this->siguienteEnvio[$actual] ?? null;
        if ($pedido['estado'] !== 'pagado' || !$siguiente) $this->redirigir('admin-pedido-detalle&id=' . (int)$id, 'Este avance de envío no está permitido.');
        if (!$this->pedidoModel->cambiarEstadoEnvio((int)$id, $actual, $siguiente)) $this->redirigir('admin-pedido-detalle&id=' . (int)$id, 'El estado cambió o no se pudo guardar. Actualizá la página.');
        $this->redirigir('admin-pedido-detalle&id=' . (int)$id, 'Estado de envío actualizado.');
    }

    public function factura(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $pedido = ($id && $id > 0) ? $this->pedidoModel->obtenerParaAdmin((int)$id) : null;
        if (!$pedido) $this->redirigir('admin-pedidos', 'No se encontró el pedido.');
        $pago = $this->pagoModel->obtenerAprobado((int)$pedido['id']);
        if (!$pago || $pedido['estado'] !== 'pagado') $this->redirigir('admin-pedido-detalle&id=' . (int)$id, 'Solo se puede emitir la factura de un pedido pagado.');
        $detalle = $this->pedidoModel->obtenerDetalle((int)$pedido['id']);
        $paginaCss = 'public/css/pagosPedidos.css';
        require __DIR__ . '/../views/admin/factura_pedido.php';
    }

    public function informe(): void
    {
        $desde = $this->leerFecha('desde') ?: date('Y-m-01');
        $hasta = $this->leerFecha('hasta') ?: date('Y-m-d');
        $errores = [];
        if ($desde > $hasta) {
            $errores[] = 'La fecha desde no puede ser posterior a la fecha hasta.';
            $resumen = []; $ventas = []; $cantidadVentas = 0; $totalVentas = 0.0; $promedio = 0.0;
        } else {
            $resumen = ['pendiente'=>['cantidad'=>0,'total'=>0.0], 'pagado'=>['cantidad'=>0,'total'=>0.0], 'rechazado'=>['cantidad'=>0,'total'=>0.0], 'cancelado'=>['cantidad'=>0,'total'=>0.0]];
            foreach ($this->pedidoModel->resumenPorEstado($desde, $hasta) as $fila) {
                if (isset($resumen[$fila['estado']])) $resumen[$fila['estado']] = ['cantidad'=>(int)$fila['cantidad'], 'total'=>(float)$fila['total']];
            }
            $ventas = $this->pedidoModel->listarAdmin(['estado'=>'pagado', 'desde'=>$desde, 'hasta'=>$hasta]);
            $cantidadVentas = $resumen['pagado']['cantidad'];
            $totalVentas = $resumen['pagado']['total'];
            $promedio = $cantidadVentas ? $totalVentas / $cantidadVentas : 0.0;
        }
        $paginaCss = 'public/css/adminPanel.css';
        require __DIR__ . '/../views/admin/informe.php';
    }

    private function exigirPermiso(): void
    {
        if (empty($_SESSION['usuario_id'])) { header('Location: index.php?pagina=login'); exit; }
        if (!in_array($_SESSION['usuario_rol'] ?? '', ['admin', 'moderador'], true)) {
            http_response_code(403); $_SESSION['error_formulario'] = 'No tenés permiso para acceder al panel administrativo.';
            header('Location: index.php?pagina=home'); exit;
        }
    }

    private function leerOpcion(string $clave, array $permitidos): string
    {
        $valor = $_GET[$clave] ?? '';
        return is_string($valor) && in_array($valor, $permitidos, true) ? $valor : '';
    }

    private function leerFecha(string $clave): string
    {
        $valor = $_GET[$clave] ?? '';
        if (!is_string($valor) || $valor === '') return '';
        $fecha = DateTime::createFromFormat('!Y-m-d', $valor);
        return $fecha && $fecha->format('Y-m-d') === $valor ? $valor : '';
    }

    private function leerAviso(): ?string
    {
        $mensaje = $_SESSION['mensaje_admin_pedidos'] ?? null;
        unset($_SESSION['mensaje_admin_pedidos']);
        return is_string($mensaje) ? $mensaje : null;
    }

    private function redirigir(string $destino, string $mensaje): void
    {
        $_SESSION['mensaje_admin_pedidos'] = $mensaje;
        header('Location: index.php?pagina=' . $destino);
        exit;
    }
}
