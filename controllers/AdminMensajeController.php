<?php
declare(strict_types=1);

class AdminMensajeController
{
    private MensajeModel $mensajeModel;
    private int $maxRespuesta = 1000;

    public function __construct(mysqli $conexion)
    {
        $this->exigirAdmin();
        $this->mensajeModel = new MensajeModel($conexion);
        if (empty($_SESSION['csrf_admin_mensajes'])) $_SESSION['csrf_admin_mensajes'] = bin2hex(random_bytes(32));
    }

    public function listar(): void
    {
        $filtro = $_GET['filtro'] ?? '';
        if (!is_string($filtro) || !in_array($filtro, ['sin_responder', 'respondidos'], true)) $filtro = '';
        $mensajes = $this->mensajeModel->listarAdmin($filtro);
        $filtroActual = $filtro;
        $aviso = $this->leerAviso();
        $paginaCss = 'public/css/mensajes.css';
        require __DIR__ . '/../views/admin/mensajes_lista.php';
    }

    public function ver(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $mensaje = ($id && $id > 0) ? $this->mensajeModel->obtenerParaAdmin((int)$id) : null;
        if (!$mensaje) $this->redirigir('admin-mensajes', 'invalido');
        $aviso = $this->leerAviso();
        $textoRespuesta = '';
        $maxRespuesta = $this->maxRespuesta;
        $paginaCss = 'public/css/mensajes.css';
        require __DIR__ . '/../views/admin/mensaje_detalle.php';
    }

    public function responder(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('admin-mensajes', 'invalido');
        $token = (string)($_POST['csrf_token'] ?? '');
        if (!hash_equals((string)($_SESSION['csrf_admin_mensajes'] ?? ''), $token)) $this->redirigir('admin-mensajes', 'invalido');
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) $this->redirigir('admin-mensajes', 'invalido');
        $mensaje = $this->mensajeModel->obtenerParaAdmin((int)$id);
        if (!$mensaje) $this->redirigir('admin-mensajes', 'invalido');
        if ($mensaje['respuesta'] !== null) $this->redirigir('admin-mensaje-detalle&id=' . (int)$id, 'ya_respondido');

        $respuesta = $_POST['respuesta'] ?? '';
        if (!is_string($respuesta)) $respuesta = '';
        $respuesta = trim(str_replace("\r\n", "\n", $respuesta));
        if ($respuesta === '' || mb_strlen($respuesta, 'UTF-8') > $this->maxRespuesta) {
            $aviso = ['tipo' => 'error', 'texto' => 'La respuesta no puede estar vacía ni superar los 1000 caracteres.'];
            $textoRespuesta = $respuesta;
            $maxRespuesta = $this->maxRespuesta;
            $paginaCss = 'public/css/mensajes.css';
            require __DIR__ . '/../views/admin/mensaje_detalle.php';
            return;
        }
        if ($this->mensajeModel->responder((int)$id, $respuesta)) $this->redirigir('admin-mensaje-detalle&id=' . (int)$id, 'respondido');
        $this->redirigir('admin-mensaje-detalle&id=' . (int)$id, 'ya_respondido');
    }

    private function exigirAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) { header('Location: index.php?pagina=login'); exit; }
        if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
            http_response_code(403);
            header('Location: index.php?pagina=home');
            exit;
        }
    }

    private function leerAviso(): ?array
    {
        $avisos = [
            'respondido' => ['tipo' => 'exito', 'texto' => 'La respuesta fue guardada. El cliente ya puede verla.'],
            'ya_respondido' => ['tipo' => 'error', 'texto' => 'Este mensaje ya fue respondido; no se sobrescribió la respuesta existente.'],
            'invalido' => ['tipo' => 'error', 'texto' => 'No se pudo realizar la acción. Revisá los datos e intentá de nuevo.']
        ];
        $clave = $_GET['msg'] ?? '';
        return is_string($clave) && isset($avisos[$clave]) ? $avisos[$clave] : null;
    }

    private function redirigir(string $pagina, string $aviso = ''): void
    {
        $url = 'index.php?pagina=' . $pagina;
        if ($aviso !== '') $url .= '&msg=' . rawurlencode($aviso);
        header('Location: ' . $url);
        exit;
    }
}
