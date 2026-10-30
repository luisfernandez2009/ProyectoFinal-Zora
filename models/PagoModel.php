<?php
declare(strict_types=1);

class PagoModel
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    /** Un pago aprobado consume stock exactamente una vez, de forma atomica. */
    public function registrarIntento(int $pedidoId, float $monto, string $ultimos4, string $resultado): bool
    {
        if (!in_array($resultado, ['aprobado', 'rechazado'], true) || !preg_match('/^\\d{4}$/', $ultimos4)) {
            return false;
        }
        $this->conexion->begin_transaction();
        try {
            // Bloquea el pedido para impedir pagos simultaneos y doble descuento.
            $stmt = $this->conexion->prepare("SELECT estado, total FROM pedido WHERE id = ? FOR UPDATE");
            $stmt->bind_param('i', $pedidoId);
            $stmt->execute();
            $pedido = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$pedido || !in_array($pedido['estado'], ['pendiente', 'rechazado'], true)) {
                $this->conexion->rollback();
                return false;
            }

            if ($resultado === 'aprobado') {
                $stmt = $this->conexion->prepare(
                    "SELECT producto_id, talle_id, SUM(cantidad) AS cantidad
                     FROM detalle_pedido WHERE pedido_id = ?
                     GROUP BY producto_id, talle_id ORDER BY producto_id, talle_id"
                );
                $stmt->bind_param('i', $pedidoId);
                $stmt->execute();
                $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                if (!$items) throw new RuntimeException('Este pedido no contiene productos.');

                foreach ($items as $item) {
                    // Descuento condicionado: ni stock negativo ni sobreventa entre usuarios.
                    $stmt = $this->conexion->prepare(
                        "UPDATE producto_talle SET stock = stock - ?
                         WHERE producto_id = ? AND talle_id = ? AND stock >= ?"
                    );
                    $cantidad = (int)$item['cantidad'];
                    $productoId = (int)$item['producto_id'];
                    $talleId = (int)$item['talle_id'];
                    $stmt->bind_param('iiii', $cantidad, $productoId, $talleId, $cantidad);
                    $stmt->execute();
                    $afectadas = $stmt->affected_rows;
                    $stmt->close();
                    if ($afectadas !== 1) {
                        throw new RuntimeException('Ya no hay stock suficiente para completar este pedido. No se registró el pago.');
                    }
                }
            }

            $estadoPedido = $resultado === 'aprobado' ? 'pagado' : 'rechazado';
            $stmt = $this->conexion->prepare("UPDATE pedido SET estado = ? WHERE id = ?");
            $stmt->bind_param('si', $estadoPedido, $pedidoId);
            $stmt->execute();
            $stmt->close();

            // El monto procede del pedido guardado, no del navegador.
            $montoReal = (float)$pedido['total'];
            $stmt = $this->conexion->prepare(
                "INSERT INTO pago (pedido_id, monto, ultimos4, resultado) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('idss', $pedidoId, $montoReal, $ultimos4, $resultado);
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
