<?php
declare(strict_types=1);

class MensajeController
{
    private MensajeModel $mensajeModel;
    private int $maxAsunto = 100;
    private int $maxTexto = 1000;

    public function __construct(mysqli $conexion)
    {
        $this->requerirLogin();
        $this->mensajeModel = new MensajeModel($conexion);
        if (empty($_SESSION['csrf_mensajes'])) $_SESSION['csrf_mensajes'] = bin2hex(random_bytes(32));
    }

    public function mostrarFormulario(): void
    {
        $asunto = '';
        $texto = '';
        $errores = [];
        $aviso = $this->leerAviso();
        $maxAsunto = $this->maxAsunto;
        $maxTexto = $this->maxTexto;
        $paginaCss = 'public/css/mensajes.css';
        require __DIR__ . '/../views/paginas/contacto.php';
    }

    public function enviar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('contacto');
        $token = (string)($_POST['csrf_token'] ?? '');
        if (!hash_equals((string)($_SESSION['csrf_mensajes'] ?? ''), $token)) {
            $this->redirigir('contacto', 'fallo');
        }
        $asunto = $this->leerTexto('asunto');
        $texto = $this->leerTexto('texto');
        $errores = [];
        if ($asunto === '') $errores[] = 'El asunto es obligatorio.';
        elseif (mb_strlen($asunto, 'UTF-8') > $this->maxAsunto) $errores[] = 'El asunto no puede superar los 100 caracteres.';
        elseif (preg_match('/[\r\n]/', $asunto)) $errores[] = 'El asunto debe estar en una sola línea.';
        if ($texto === '') $errores[] = 'El mensaje es obligatorio.';
        elseif (mb_strlen($texto, 'UTF-8') > $this->maxTexto) $errores[] = 'El mensaje no puede superar los 1000 caracteres.';

        if ($errores) {
            $aviso = null; $maxAsunto = $this->maxAsunto; $maxTexto = $this->maxTexto;
            $paginaCss = 'public/css/mensajes.css';
            require __DIR__ . '/../views/paginas/contacto.php';
            return;
        }
        try {
            if ($this->mensajeModel->crear((int)$_SESSION['usuario_id'], $asunto, $texto)) {
                $this->redirigir('mis-mensajes', 'enviado');
            }
        } catch (Throwable $e) {
            // No se muestran detalles de la base de datos al usuario.
        }
        $this->redirigir('contacto', 'fallo');
    }

    public function misMensajes(): void
    {
        $mensajes = $this->mensajeModel->listarPorUsuario((int)$_SESSION['usuario_id']);
        $aviso = $this->leerAviso();
        $paginaCss = 'public/css/mensajes.css';
        require __DIR__ . '/../views/paginas/mis_mensajes.php';
    }

    private function requerirLogin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: index.php?pagina=login');
            exit;
        }
    }

    private function leerTexto(string $campo): string
    {
        $valor = $_POST[$campo] ?? '';
        if (!is_string($valor)) return '';
        return trim(str_replace("\r\n", "\n", $valor));
    }

    private function leerAviso(): ?array
    {
        $clave = $_GET['msg'] ?? '';
        $avisos = [
            'enviado' => ['tipo' => 'exito', 'texto' => '¡Gracias! Recibimos tu consulta. Podés ver la respuesta en Mis mensajes.'],
            'fallo' => ['tipo' => 'error', 'texto' => 'No pudimos guardar la consulta. Intentá nuevamente.']
        ];
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
