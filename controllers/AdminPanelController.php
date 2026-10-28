<?php
declare(strict_types=1);

class AdminPanelController
{
    public function __construct() { $this->exigirPermiso(); }

    public function mostrar(mysqli $conexion): void
    {
        $pedidoModel = new PedidoModel($conexion);
        $mensajeModel = new MensajeModel($conexion);
        $mensajesSinResponder = $mensajeModel->contarSinResponder();
        $mensajeModel = new MensajeModel($conexion);
        $mensajesSinResponder = $mensajeModel->contarSinResponder();
        $ultimosPedidos = $pedidoModel->listarAdmin(['limite' => 5]);
        $pedidosPorEnviar = $pedidoModel->listarAdmin(['estado' => 'pagado', 'estado_envio' => 'preparando']);
        $paginaCss = 'public/css/adminPanel.css';
        require __DIR__ . '/../views/admin/panel.php';
    }

    private function exigirPermiso(): void
    {
        if (empty($_SESSION['usuario_id'])) { header('Location: index.php?pagina=login'); exit; }
        if (!in_array($_SESSION['usuario_rol'] ?? '', ['admin', 'moderador'], true)) {
            http_response_code(403); header('Location: index.php?pagina=home'); exit;
        }
    }
}
