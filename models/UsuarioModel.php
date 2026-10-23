<?php
declare(strict_types=1);

class UsuarioModel
{
    private mysqli $conexion;
    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    public function existeCorreo(string $correo): bool
    {
        $stmt = $this->conexion->prepare("SELECT id FROM usuario WHERE correo = ? LIMIT 1");
        $stmt->bind_param('s', $correo); $stmt->execute();
        $existe = (bool)$stmt->get_result()->fetch_assoc(); $stmt->close();
        return $existe;
    }

    public function crearUsuario(string $nombre, string $apellido, string $correo, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conexion->prepare(
            "INSERT INTO usuario (nombre, apellido, correo, password_hash, rol, activo) VALUES (?, ?, ?, ?, 'cliente', 1)"
        );
        $stmt->bind_param('ssss', $nombre, $apellido, $correo, $hash);
        $ok = $stmt->execute(); $stmt->close();
        return $ok;
    }

    public function buscarPorCorreo(string $correo): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, nombre, apellido, correo, password_hash, rol, activo FROM usuario WHERE correo = ? LIMIT 1"
        );
        $stmt->bind_param('s', $correo); $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc() ?: null; $stmt->close();
        return $usuario;
    }
    
    public function obtenerEstadoSesion(int $id): ?array
    {
        $stmt = $this->conexion->prepare("SELECT id, nombre, rol, activo FROM usuario WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $usuario;
    }

    public function obtenerPorIdAdmin(int $id): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, nombre, apellido, correo, rol, activo, fecha_registro FROM usuario WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $usuario;
    }

    /** Lista cuentas activas e inactivas sin exponer contraseñas ni hashes. */
    public function listarTodosAdmin(): array
    {
        $sql = "SELECT u.id, u.nombre, u.apellido, u.correo, u.rol, u.activo, u.fecha_registro,
                       COUNT(p.id) AS cantidad_pedidos
                FROM usuario u
                LEFT JOIN pedido p ON p.usuario_id = u.id
                GROUP BY u.id, u.nombre, u.apellido, u.correo, u.rol, u.activo, u.fecha_registro
                ORDER BY u.activo DESC, u.nombre ASC, u.apellido ASC";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function cambiarRol(int $id, string $rol): bool
    {
        $stmt = $this->conexion->prepare("UPDATE usuario SET rol = ? WHERE id = ?");
        $stmt->bind_param('si', $rol, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function cambiarActivo(int $id, int $activo): bool
    {
        $stmt = $this->conexion->prepare("UPDATE usuario SET activo = ? WHERE id = ?");
        $stmt->bind_param('ii', $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
