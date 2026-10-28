<?php
declare(strict_types=1);

class MensajeModel
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    public function crear(int $idUsuario, string $asunto, string $texto): bool
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO mensaje (id_usuario, asunto, texto) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('iss', $idUsuario, $asunto, $texto);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function listarPorUsuario(int $idUsuario): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, asunto, texto, fecha, respuesta, fecha_respuesta
             FROM mensaje WHERE id_usuario = ? ORDER BY fecha DESC"
        );
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $filas;
    }

    public function listarAdmin(string $filtro = ''): array
    {
        $sql = "SELECT m.id, m.asunto, m.fecha,
                       (m.respuesta IS NOT NULL) AS respondido,
                       u.nombre AS cliente_nombre, u.apellido AS cliente_apellido,
                       u.correo AS cliente_correo
                FROM mensaje m
                INNER JOIN usuario u ON u.id = m.id_usuario";
        if ($filtro === 'sin_responder') {
            $sql .= " WHERE m.respuesta IS NULL ORDER BY m.fecha ASC";
        } elseif ($filtro === 'respondidos') {
            $sql .= " WHERE m.respuesta IS NOT NULL ORDER BY m.fecha DESC";
        } else {
            $sql .= " ORDER BY m.fecha DESC";
        }
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerParaAdmin(int $id): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT m.*, u.nombre AS cliente_nombre, u.apellido AS cliente_apellido,
                    u.correo AS cliente_correo
             FROM mensaje m INNER JOIN usuario u ON u.id = m.id_usuario
             WHERE m.id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $fila;
    }

    public function responder(int $id, string $respuesta): bool
    {
        $stmt = $this->conexion->prepare(
            "UPDATE mensaje SET respuesta = ?, fecha_respuesta = NOW()
             WHERE id = ? AND respuesta IS NULL"
        );
        $stmt->bind_param('si', $respuesta, $id);
        $stmt->execute();
        $guardado = $stmt->affected_rows === 1;
        $stmt->close();
        return $guardado;
    }

    public function contarSinResponder(): int
    {
        $resultado = $this->conexion->query(
            "SELECT COUNT(*) AS cantidad FROM mensaje WHERE respuesta IS NULL"
        );
        return (int)$resultado->fetch_assoc()['cantidad'];
    }
}
