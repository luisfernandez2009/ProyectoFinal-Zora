<?php
declare(strict_types=1);

class PagoModel
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    /** Guarda únicamente los últimos cuatro dígitos y actualiza el estado del pedido en la misma transacción. */
    public function registrarIntento(int $pedidoId, float $monto, string $ultimos4, string $resultado): bool
    {
        if (!in_array($resultado, ['aprobado', 'rechazado'], true) || !preg_match('/^\d{4}$/', $ultimos4)) {
            return false;
        }
        $estadoPedido = $resultado === 'aprobado' ? 'confirmado' : 'pendiente';
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare(
                "INSERT INTO pago (pedido_id, monto, ultimos4, resultado) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('idss', $pedidoId, $monto, $ultimos4, $resultado);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->conexion->prepare("UPDATE pedido SET estado = ? WHERE id = ?");
            $stmt->bind_param('si', $estadoPedido, $pedidoId);
            $stmt->execute();
            $stmt->close();

            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    public function obtenerUltimo(int $pedidoId): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, pedido_id, monto, ultimos4, resultado, fecha
             FROM pago WHERE pedido_id = ? ORDER BY id DESC LIMIT 1"
        );
        $stmt->bind_param('i', $pedidoId);
        $stmt->execute();
        $pago = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $pago;
    }

    public function obtenerAprobado(int $pedidoId): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, pedido_id, monto, ultimos4, resultado, fecha
             FROM pago WHERE pedido_id = ? AND resultado = 'aprobado'
             ORDER BY id DESC LIMIT 1"
        );
        $stmt->bind_param('i', $pedidoId);
        $stmt->execute();
        $pago = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $pago;
    }
}
