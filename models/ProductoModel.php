<?php
declare(strict_types=1);

/** El modelo contiene únicamente consultas a la base de datos. */
class ProductoModel
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion) { $this->conexion = $conexion; }

    public function listarProductos($idCategoriaFiltro = null, ?string $busqueda = null, ?int $limite = null): array
    {
        $sql = "SELECT p.id, p.nombre, p.marca, p.descripcion, p.precio, p.imagen,
                       p.categoria_id, c.nombre AS nombre_categoria
                FROM producto p
                INNER JOIN categoria c ON c.id = p.categoria_id
                WHERE p.activo = 1";
        $tipos = '';
        $params = [];
        if (filter_var($idCategoriaFiltro, FILTER_VALIDATE_INT) && (int)$idCategoriaFiltro > 0) {
            $sql .= " AND p.categoria_id = ?";
            $tipos .= 'i'; $params[] = (int)$idCategoriaFiltro;
        }
        if ($busqueda !== null && trim($busqueda) !== '') {
            $sql .= " AND (p.nombre LIKE ? OR p.marca LIKE ? OR p.descripcion LIKE ?)";
            $textoSeguro = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($busqueda));
            $like = '%' . $textoSeguro . '%';
            $tipos .= 'sss'; array_push($params, $like, $like, $like);
        }
        $sql .= " ORDER BY p.nombre ASC";
        if ($limite !== null && $limite > 0) $sql .= " LIMIT " . (int)$limite;
        $stmt = $this->conexion->prepare($sql);
        if ($tipos !== '') $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function obtenerProductoPorId(int $idProducto): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT p.id, p.nombre, p.marca, p.descripcion, p.precio, p.imagen,
                    p.categoria_id, c.nombre AS nombre_categoria
             FROM producto p INNER JOIN categoria c ON c.id = p.categoria_id
             WHERE p.id = ? AND p.activo = 1 LIMIT 1"
        );
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $producto;
    }

    public function listarCategorias(): array
    {
        $resultado = $this->conexion->query("SELECT id, nombre FROM categoria ORDER BY nombre");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function listarImagenes(int $idProducto, ?string $principal): array
    {
        $imagenes = $principal ? [$principal] : [];
        try {
            $stmt = $this->conexion->prepare("SELECT ruta FROM producto_imagen WHERE producto_id = ? ORDER BY orden, id");
            $stmt->bind_param('i', $idProducto);
            $stmt->execute();
            foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $fila) {
                if (!in_array($fila['ruta'], $imagenes, true)) $imagenes[] = $fila['ruta'];
            }
            $stmt->close();
        } catch (Throwable $e) { /* La imagen principal sigue disponible aunque no haya galería. */ }
        return $imagenes;
    }

    public function listarTallesConStock(int $idProducto): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT t.id, t.numero, pt.stock
             FROM producto_talle pt INNER JOIN talles t ON t.id = pt.talle_id
             WHERE pt.producto_id = ? AND pt.stock > 0 ORDER BY t.numero"
        );
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /** Catálogo completo para administración, incluido el stock por talle y productos inactivos. */
    public function listarTodosAdmin(): array
    {
        $sql = "SELECT p.id, p.nombre, p.marca, p.precio, p.imagen, p.activo, p.stock_minimo,
                       c.nombre AS nombre_categoria, COALESCE(SUM(pt.stock), 0) AS stock_total,
                       CASE
                           WHEN p.activo = 0 THEN 'inactivo'
                           WHEN COALESCE(SUM(pt.stock), 0) = 0 THEN 'agotado'
                           WHEN COALESCE(SUM(pt.stock), 0) <= p.stock_minimo THEN 'bajo'
                           ELSE 'ok'
                       END AS nivel_stock
                FROM producto p
                INNER JOIN categoria c ON c.id = p.categoria_id
                LEFT JOIN producto_talle pt ON pt.producto_id = p.id
                GROUP BY p.id, p.nombre, p.marca, p.precio, p.imagen, p.activo,
                         p.stock_minimo, c.nombre
                ORDER BY p.activo DESC, p.nombre ASC";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerParaAdmin(int $idProducto): ?array
    {
        $stmt = $this->conexion->prepare(
            "SELECT id, nombre, marca, descripcion, precio, imagen, categoria_id, stock_minimo, activo
             FROM producto WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $producto;
    }

    public function listarTallesAdmin(): array
    {
        return $this->conexion->query("SELECT id, numero FROM talles ORDER BY numero")->fetch_all(MYSQLI_ASSOC);
    }

    public function listarStocksAdmin(int $idProducto): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT t.id, t.numero, COALESCE(pt.stock, 0) AS stock
             FROM talles t
             LEFT JOIN producto_talle pt ON pt.talle_id = t.id AND pt.producto_id = ?
             ORDER BY t.numero"
        );
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();
        $stocks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $stocks;
    }

    public function crearAdmin(array $datos, array $stocks): int
    {
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare(
                "INSERT INTO producto (nombre, marca, descripcion, precio, imagen, categoria_id, stock_minimo, activo)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)"
            );
            $stmt->bind_param('sssdsii', $datos['nombre'], $datos['marca'], $datos['descripcion'],
                $datos['precio'], $datos['imagen'], $datos['categoria_id'], $datos['stock_minimo']);
            $stmt->execute();
            $id = (int)$this->conexion->insert_id;
            $stmt->close();
            $this->guardarStocksAdmin($id, $stocks);
            $this->conexion->commit();
            return $id;
        } catch (Throwable $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    public function actualizarAdmin(int $idProducto, array $datos, array $stocks): bool
    {
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare(
                "UPDATE producto SET nombre = ?, marca = ?, descripcion = ?, precio = ?, imagen = ?,
                    categoria_id = ?, stock_minimo = ? WHERE id = ?"
            );
            $stmt->bind_param('sssdsiii', $datos['nombre'], $datos['marca'], $datos['descripcion'],
                $datos['precio'], $datos['imagen'], $datos['categoria_id'], $datos['stock_minimo'], $idProducto);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok) { throw new RuntimeException('No se pudo actualizar el producto.'); }
            $this->guardarStocksAdmin($idProducto, $stocks);
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    private function guardarStocksAdmin(int $idProducto, array $stocks): void
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO producto_talle (producto_id, talle_id, stock) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE stock = VALUES(stock)"
        );
        foreach ($stocks as $talleId => $stock) {
            $talleId = (int)$talleId;
            $stock = (int)$stock;
            $stmt->bind_param('iii', $idProducto, $talleId, $stock);
            $stmt->execute();
        }
        $stmt->close();
    }

    public function cambiarActivoAdmin(int $idProducto, int $activo): bool
    {
        $stmt = $this->conexion->prepare("UPDATE producto SET activo = ? WHERE id = ?");
        $stmt->bind_param('ii', $activo, $idProducto);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
