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
            "SELECT id, nombre, apellido, correo, password_hash, rol FROM usuario WHERE correo = ? AND activo = 1 LIMIT 1"
        );
        $stmt->bind_param('s', $correo); $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc() ?: null; $stmt->close();
        return $usuario;
    }
}
