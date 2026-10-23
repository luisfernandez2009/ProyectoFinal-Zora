<?php
declare(strict_types=1);

class PedidoModel
{
    private mysqli $conexion;
    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    /** Guarda pedido y detalles en una única transacción. El precio siempre se obtiene de BD. */
    public function crearPedido(int $usuarioId, array $datosEntrega, array $items): int
    {
        if (!$items) throw new RuntimeException('El carrito está vacío.');
        $this->conexion->begin_transaction();
        try {
            $normalizados = [];
            $total = 0.0;
            foreach ($items as $item) {
                $productoId = filter_var($item['producto_id'] ?? null, FILTER_VALIDATE_INT);
                $talleId = filter_var($item['talle_id'] ?? null, FILTER_VALIDATE_INT);
                $cantidad = filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT);
                if (!$productoId || !$talleId || !$cantidad || $cantidad < 1 || $cantidad > 20) {
                    throw new RuntimeException('Hay un artículo inválido en el carrito.');
                }
                $stmt = $this->conexion->prepare(
                    "SELECT p.nombre, p.precio, pt.stock FROM producto p
                     INNER JOIN producto_talle pt ON pt.producto_id = p.id
                     WHERE p.id = ? AND pt.talle_id = ? AND p.activo = 1 FOR UPDATE"
                );
                $stmt->bind_param('ii', $productoId, $talleId); $stmt->execute();
                $producto = $stmt->get_result()->fetch_assoc(); $stmt->close();
                if (!$producto || (int)$producto['stock'] < $cantidad) {
                    throw new RuntimeException('No hay stock suficiente para uno de los artículos seleccionados.');
                }
                $precio = (float)$producto['precio'];
                $total += $precio * $cantidad;
                $normalizados[] = ['producto_id'=>$productoId,'talle_id'=>$talleId,'cantidad'=>$cantidad,'precio'=>$precio];
            }

            $estado = 'pendiente';
            $stmt = $this->conexion->prepare(
                "INSERT INTO pedido (usuario_id, estado, nombre_entrega, telefono_entrega, direccion_entrega, ciudad_entrega, departamento_entrega, codigo_postal, metodo_pago, observaciones, total)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('isssssssssd', $usuarioId, $estado, $datosEntrega['nombre'], $datosEntrega['telefono'],
                $datosEntrega['direccion'], $datosEntrega['ciudad'], $datosEntrega['departamento'],
                $datosEntrega['codigo_postal'], $datosEntrega['metodo_pago'], $datosEntrega['observaciones'], $total);
            $stmt->execute(); $pedidoId = (int)$this->conexion->insert_id; $stmt->close();

            foreach ($normalizados as $item) {
                $stmt = $this->conexion->prepare(
                    "INSERT INTO detalle_pedido (pedido_id, producto_id, talle_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->bind_param('iiiid', $pedidoId, $item['producto_id'], $item['talle_id'], $item['cantidad'], $item['precio']);
                $stmt->execute(); $stmt->close();
                $stmt = $this->conexion->prepare(
                    "UPDATE producto_talle SET stock = stock - ? WHERE producto_id = ? AND talle_id = ? AND stock >= ?"
                );
                $stmt->bind_param('iiii', $item['cantidad'], $item['producto_id'], $item['talle_id'], $item['cantidad']);
                $stmt->execute();
                if ($stmt->affected_rows !== 1) { $stmt->close(); throw new RuntimeException('El stock cambió. Actualizá el carrito e intentá de nuevo.'); }
                $stmt->close();
            }
            $this->conexion->commit();
            return $pedidoId;
        } catch (Throwable $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    public function obtenerPedido(int $pedidoId, int $usuarioId): ?array
    {
        $stmt = $this->conexion->prepare("SELECT id, fecha_pedido, estado, total FROM pedido WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param('ii', $pedidoId, $usuarioId); $stmt->execute();
        $pedido = $stmt->get_result()->fetch_assoc() ?: null; $stmt->close();
        return $pedido;
    }

    public function obtenerPedidoCompleto(int $pedidoId, int $usuarioId): ?array
    {
        $stmt = $this->conexion->prepare("SELECT * FROM pedido WHERE id = ? AND usuario_id = ? LIMIT 1");
        $stmt->bind_param('ii', $pedidoId, $usuarioId);
        $stmt->execute();
        $pedido = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $pedido;
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, fecha_pedido, estado, total, metodo_pago
             FROM pedido WHERE usuario_id = ? ORDER BY fecha_pedido DESC, id DESC"
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $pedidos;
    }

    /** Usar después de comprobar que el pedido pertenece al usuario de la sesión. */
    public function obtenerDetalle(int $pedidoId): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT p.nombre, p.marca, t.numero AS talle, d.cantidad, d.precio_unitario,
                    (d.cantidad * d.precio_unitario) AS subtotal
             FROM detalle_pedido d
             INNER JOIN producto p ON p.id = d.producto_id
             INNER JOIN talles t ON t.id = d.talle_id
             WHERE d.pedido_id = ? ORDER BY p.nombre, t.numero"
        );
        $stmt->bind_param('i', $pedidoId);
        $stmt->execute();
        $detalle = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $detalle;
    }
}
