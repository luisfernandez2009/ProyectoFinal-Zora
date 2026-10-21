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
            $like = '%' . trim($busqueda) . '%';
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
}
