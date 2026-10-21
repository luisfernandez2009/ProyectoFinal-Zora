<?php
/**
 * models/ProductoModel.php
 * -------------------------------------------------------
 * Este es el MODELO de Producto.
 * Su única responsabilidad es hablar con la base de datos
 * sobre las tablas "producto" y "categoria". No sabe nada
 * de HTML ni de formularios: solo busca datos y los
 * devuelve como arrays.
 * -------------------------------------------------------
 */

class ProductoModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Devuelve todos los productos, con el nombre de su categoría
     * ya incluido (mediante un JOIN). Si se indica una categoría,
     * filtra solo los productos de esa categoría.
     */
    public function listarProductos($idCategoriaFiltro = null)
    {
        $consultaSql = "
            SELECT
                producto.id,
                producto.nombre,
                producto.marca,
				producto.precio,
                producto.descripcion,
                producto.imagen,
                producto.categoria_id,
				producto.activo,
                categoria.id,
                categoria.nombre AS nombre_categoria
            FROM producto
            INNER JOIN categoria ON producto.categoria_id = categoria.id
        ";

        if (!empty($idCategoriaFiltro)) {
            $consultaSql .= " WHERE producto.categoria_id = ?";
        }

        $consultaSql .= " ORDER BY producto.nombre ASC";

        $consultaPreparada = mysqli_prepare($this->conexion, $consultaSql);

        if (!empty($idCategoriaFiltro)) {
            mysqli_stmt_bind_param($consultaPreparada, "i", $idCategoriaFiltro);
        }

        mysqli_stmt_execute($consultaPreparada);
        $resultado = mysqli_stmt_get_result($consultaPreparada);

        $listaDeProductos = [];
        while ($filaProducto = mysqli_fetch_assoc($resultado)) {
            $listaDeProductos[] = $filaProducto;
        }

        return $listaDeProductos;
    }

    /**
     * Devuelve un único producto según su id.
     * Se usa, por ejemplo, para recalcular precios al confirmar un pedido.
     */
    public function obtenerProductoPorId($idProducto)
    {
        // Usamos el mismo JOIN que en listarProductos() para tener
        // también el nombre de la categoría, no solo su id
        $consultaSql = "
            SELECT
                producto.id,
                producto.nombre,
				producto.marca,
				producto.precio,
                producto.descripcion,
                producto.imagen,
                producto.categoria_id,
                producto.activo,
                categoria.categoria_id,
                categoria.nombre AS nombre_categoria
            FROM producto
            INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
            WHERE producto.id_producto = ?
        ";
        $consultaPreparada = mysqli_prepare($this->conexion, $consultaSql);
        mysqli_stmt_bind_param($consultaPreparada, "i", $idProducto);
        mysqli_stmt_execute($consultaPreparada);
        $resultado = mysqli_stmt_get_result($consultaPreparada);

        return mysqli_fetch_assoc($resultado); // devuelve el array del producto, o null si no existe
    }

    /**
     * Devuelve todas las categorías (para armar el filtro del catálogo).
     */
    public function listarCategorias()
    {
        $consultaSql = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC";
        $resultado = mysqli_query($this->conexion, $consultaSql);

        $listaDeCategorias = [];
        while ($filaCategoria = mysqli_fetch_assoc($resultado)) {
            $listaDeCategorias[] = $filaCategoria;
        }

        return $listaDeCategorias;
    }
}
