<?php
declare(strict_types=1);

class AdminUsuarioController
{
    private UsuarioModel $usuarioModel;

    public function __construct(mysqli $conexion)
    {
        $this->usuarioModel = new UsuarioModel($conexion);
    }

    public function listar(): void
    {
        $this->exigirAdmin();
        if (empty($_SESSION['csrf_admin_usuarios'])) {
            $_SESSION['csrf_admin_usuarios'] = bin2hex(random_bytes(32));
        }
        $usuarios = $this->usuarioModel->listarTodosAdmin();
        $idActual = (int)$_SESSION['usuario_id'];
        $mensaje = $_SESSION['mensaje_admin_usuarios'] ?? null;
        unset($_SESSION['mensaje_admin_usuarios']);
        $paginaCss = 'public/css/adminUsuarios.css';
        require __DIR__ . '/../views/admin/usuarios_lista.php';
    }

    public function cambiarRol(): void
    {
        $this->exigirAdmin();
        $this->validarPeticion();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $rol = (string)($_POST['rol'] ?? '');
        $rolesValidos = ['cliente', 'admin'];

        if (!$id || $id < 1 || !in_array($rol, $rolesValidos, true)) {
            $this->redirigirConMensaje('Los datos enviados no son válidos.');
        }
        if ((int)$id === (int)$_SESSION['usuario_id'] && $rol !== 'admin') {
            $this->redirigirConMensaje('No podés quitarte el rol de administrador.');
        }
        if (!$this->usuarioModel->obtenerPorIdAdmin((int)$id)) {
            $this->redirigirConMensaje('El usuario indicado no existe.');
        }

        try {
            if (!$this->usuarioModel->cambiarRol((int)$id, $rol)) {
                $this->redirigirConMensaje('No se pudo actualizar el rol.');
            }
            $this->redirigirConMensaje('El rol del usuario fue actualizado.');
        } catch (Throwable $e) {
            $this->redirigirConMensaje('No se pudo actualizar el rol. Revisá la base de datos.');
        }
    }

    public function cambiarActivo(): void
    {
        $this->exigirAdmin();
        $this->validarPeticion();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $activo = filter_input(INPUT_POST, 'activo', FILTER_VALIDATE_INT);

        if (!$id || $id < 1 || !in_array($activo, [0, 1], true)) {
            $this->redirigirConMensaje('Los datos enviados no son válidos.');
        }
        if ((int)$id === (int)$_SESSION['usuario_id']) {
            $this->redirigirConMensaje('No podés desactivar tu propia cuenta.');
        }
        if (!$this->usuarioModel->obtenerPorIdAdmin((int)$id)) {
            $this->redirigirConMensaje('El usuario indicado no existe.');
        }

        try {
            if (!$this->usuarioModel->cambiarActivo((int)$id, (int)$activo)) {
                $this->redirigirConMensaje('No se pudo cambiar el estado de la cuenta.');
            }
            $this->redirigirConMensaje($activo === 1 ? 'La cuenta fue reactivada.' : 'La cuenta fue desactivada.');
        } catch (Throwable $e) {
            $this->redirigirConMensaje('No se pudo cambiar el estado de la cuenta.');
        }
    }

    private function exigirAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: index.php?pagina=login');
            exit;
        }
        if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
            http_response_code(403);
            $_SESSION['error_formulario'] = 'No tenés permiso para acceder a esa sección.';
            header('Location: index.php?pagina=home');
            exit;
        }
    }

    private function validarPeticion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=admin-usuarios');
            exit;
        }
        $token = (string)($_POST['csrf_token'] ?? '');
        $esperado = (string)($_SESSION['csrf_admin_usuarios'] ?? '');
        if ($esperado === '' || !hash_equals($esperado, $token)) {
            $this->redirigirConMensaje('La sesión del formulario venció. Volvé a intentarlo.');
        }
    }

    private function redirigirConMensaje(string $mensaje): void
    {
        $_SESSION['mensaje_admin_usuarios'] = $mensaje;
        header('Location: index.php?pagina=admin-usuarios');
        exit;
    }
}
