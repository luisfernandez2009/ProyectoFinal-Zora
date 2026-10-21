<?php
/**
 * controllers/ProductoController.php
 * -------------------------------------------------------
 * Este es el CONTROLADOR de todo lo relacionado a productos:
 * el catálogo (listado) y el detalle de un producto puntual.
 * 
 * Para listar el catálogo realiza 3 pasos:
 *   1. Leer qué categoría eligió el usuario (si eligió alguna)
 *   2. Pedirle los datos al Modelo
 *   3. Entregarle esos datos a la Vista para que los muestre
 * 
 * Para la página de detalle, recibe el id del producto por GET, 
 * por ejemplo: index.php?pagina=producto&id=3
 * -------------------------------------------------------
 */

class ProductoController
{
    private $conexion;
    private $productoModel;

    /**
     * El constructor recibe la conexión UNA sola vez, y ahí mismo
     * arma el Model que va a usar en cualquiera de sus métodos.
     * Así, cada método no tiene que preocuparse por crear su
     * propio ProductoModel.
     */
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->productoModel = new ProductoModel($conexion);
    }

    /**
     * Muestra el catálogo completo, opcionalmente filtrado por categoría.
     */
    public function mostrarCatalogo()
    {
        $idCategoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        $listaDeProductos  = $this->productoModel->listarProductos($idCategoriaSeleccionada);
        $listaDeCategorias = $this->productoModel->listarCategorias();

        require __DIR__ . '/../views/paginaListadoProductos.php';
    }

    /**
     * Muestra el detalle de un único producto.
     */
    public function mostrarDetalle()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            require __DIR__ . '/../views/header.php';
            echo "<p>Producto no encontrado.</p>";
            require __DIR__ . '/../views/footer.php';
            exit;
        }

        $idProducto = (int) $_GET['id'];
        $producto = $this->productoModel->obtenerProductoPorId($idProducto);

        if (!$producto) {
            require __DIR__ . '/../views/header.php';
            echo "<p>El producto solicitado no existe.</p>";
            require __DIR__ . '/../views/footer.php';
            exit;
        }

        require __DIR__ . '/../views/paginaDetalleProducto.php';
    }
}