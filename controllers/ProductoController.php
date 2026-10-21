<?php
declare(strict_types=1);

class ProductoController
{
    private ProductoModel $productoModel;
    public function __construct(mysqli $conexion) { $this->productoModel = new ProductoModel($conexion); }

    public function mostrarCatalogo(): void
    {
        $categoria = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);
        $busqueda = isset($_GET['buscar']) ? trim((string)$_GET['buscar']) : null;
        $listaDeProductos = $this->productoModel->listarProductos($categoria ?: null, $busqueda);
        $listaDeCategorias = $this->productoModel->listarCategorias();
        $paginaCss = 'public/css/paginaListadoProductos.css';
        $paginaJs = 'public/js/paginaListadoProductos.js';
        require __DIR__ . '/../views/paginas/paginaListadoProductos.php';
    }

    public function mostrarDetalle(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1 || !($producto = $this->productoModel->obtenerProductoPorId($id))) {
            http_response_code(404);
            $paginaCss = null;
            require __DIR__ . '/../views/paginas/noEncontrado.php';
            return;
        }
        $imagenes = $this->productoModel->listarImagenes((int)$producto['id'], $producto['imagen'] ?? null);
        $talles = $this->productoModel->listarTallesConStock((int)$producto['id']);
        $paginaCss = 'public/css/paginaDetalleProducto.css';
        $paginaJs = 'public/js/paginaDetalleProducto.js';
        require __DIR__ . '/../views/paginas/paginaDetalleProducto.php';
    }
}
