<?php
declare(strict_types=1);

class UsuarioController
{
    private UsuarioModel $usuarioModel;
    public function __construct(mysqli $conexion) { $this->usuarioModel = new UsuarioModel($conexion); }

    public function mostrarRegistro(): void
    {
        $paginaCss = 'public/css/paginaRegistro.css';
        $paginaJs = 'public/js/paginaRegistro.js';
        $error = $_SESSION['error_formulario'] ?? null; unset($_SESSION['error_formulario']);
        require __DIR__ . '/../views/paginas/paginaRegistro.php';
    }

    public function procesarRegistro(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?pagina=registro'); exit; }
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $apellido = trim((string)($_POST['apellido'] ?? ''));
        $correo = trim((string)($_POST['correo'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $confirmar = (string)($_POST['confirmarPassword'] ?? '');

        if ($nombre === '' || $apellido === '') {
            $_SESSION['error_formulario'] = 'Completá tu nombre y apellido.';
            header('Location: index.php?pagina=registro'); exit;
        }
        if (!$this->correoPermitido($correo)) {
            $_SESSION['error_formulario'] = 'El correo debe ser válido y terminar en @gmail.com o @hotmail.com.';
            header('Location: index.php?pagina=registro'); exit;
        }
        if (strlen($password) < 8 || $password !== $confirmar) {
            $_SESSION['error_formulario'] = 'La contraseña debe tener al menos 8 caracteres y coincidir con la confirmación.';
            header('Location: index.php?pagina=registro'); exit;
        }
        try {
            if ($this->usuarioModel->existeCorreo($correo)) {
                $_SESSION['error_formulario'] = 'Ya existe una cuenta registrada con ese correo.';
                header('Location: index.php?pagina=registro'); exit;
            }
            $this->usuarioModel->crearUsuario($nombre, $apellido, $correo, $password);
            $usuario = $this->usuarioModel->buscarPorCorreo($correo);
            if (!$usuario) { throw new RuntimeException('No se pudo recuperar la cuenta recién creada.'); }
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            header('Location: index.php?pagina=home'); exit;
        } catch (Throwable $e) {
            $_SESSION['error_formulario'] = 'No se pudo crear la cuenta. Revisá los datos e intentá nuevamente.';
            header('Location: index.php?pagina=registro'); exit;
        }
    }

    public function mostrarLogin(): void
    {
        $paginaCss = 'public/css/paginaInicioSesion.css';
        $paginaJs = 'public/js/paginaInicioSesion.js';
        $error = $_SESSION['error_formulario'] ?? null; unset($_SESSION['error_formulario']);
        $mensaje = $_SESSION['mensaje_formulario'] ?? null; unset($_SESSION['mensaje_formulario']);
        $redirect = (($_GET['redirect'] ?? 'home') === 'carrito') ? 'carrito' : 'home';
        require __DIR__ . '/../views/paginas/paginaInicioSesion.php';
    }

    public function procesarLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?pagina=login' . ((($_POST['redirect'] ?? 'home') === 'carrito') ? '&redirect=carrito' : '')); exit; }
        $correo = trim((string)($_POST['correo'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (!$this->correoPermitido($correo)) {
            $_SESSION['error_formulario'] = 'Ingresá un correo válido que termine en @gmail.com o @hotmail.com.';
            header('Location: index.php?pagina=login'); exit;
        }
        $usuario = $this->usuarioModel->buscarPorCorreo($correo);
        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            $_SESSION['error_formulario'] = 'Correo o contraseña incorrectos.';
            header('Location: index.php?pagina=login'); exit;
        }
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        $destino = (($_POST['redirect'] ?? 'home') === 'carrito') ? 'carrito' : 'home';
        header('Location: index.php?pagina=' . $destino); exit;
    }

    private function correoPermitido(string $correo): bool
    {
        return (bool) filter_var($correo, FILTER_VALIDATE_EMAIL)
            && (bool) preg_match('/@(gmail\\.com|hotmail\\.com)$/i', $correo);
    }

    public function cerrarSesion(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: index.php?pagina=home'); exit;
    }
}
