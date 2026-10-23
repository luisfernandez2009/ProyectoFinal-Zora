<?php
declare(strict_types=1);

class AdminProductoController
{
    private ProductoModel $productoModel;

    public function __construct(mysqli $conexion)
    {
        $this->productoModel = new ProductoModel($conexion);
    }

    public function listar(): void
    {
        $this->exigirAdmin();
        $this->prepararCsrf();
        $productos = $this->productoModel->listarTodosAdmin();
        $idActual = (int)$_SESSION['usuario_id'];
        $mensaje = $_SESSION['mensaje_admin_producto'] ?? null;
        unset($_SESSION['mensaje_admin_producto']);
        $paginaCss = 'public/css/adminProductos.css';
        require __DIR__ . '/../views/admin/productos_lista.php';
    }

    public function formulario(): void
    {
        $this->exigirAdmin();
        $this->prepararCsrf();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $producto = $id ? $this->productoModel->obtenerParaAdmin((int)$id) : null;
        if ($id && !$producto) {
            http_response_code(404);
            header('Location: index.php?pagina=admin-productos');
            exit;
        }
        if (!$producto) {
            $producto = ['id'=>0,'nombre'=>'','marca'=>'','descripcion'=>'','precio'=>'','imagen'=>null,
                'categoria_id'=>'','stock_minimo'=>5,'activo'=>1];
        }
        $categorias = $this->productoModel->listarCategorias();
        $talles = $this->productoModel->listarStocksAdmin((int)$producto['id']);
        if ((int)$producto['id'] === 0) {
            $talles = $this->productoModel->listarTallesAdmin();
            foreach ($talles as &$talle) { $talle['stock'] = 0; }
            unset($talle);
        }
        $stocks = [];
        foreach ($talles as $talle) { $stocks[(int)$talle['id']] = (int)$talle['stock']; }
        $error = $_SESSION['error_admin_producto'] ?? null;
        unset($_SESSION['error_admin_producto']);
        $paginaCss = 'public/css/adminProductos.css';
        require __DIR__ . '/../views/admin/producto_form.php';
    }

    public function guardar(): void
    {
        $this->exigirAdmin();
        $this->validarPeticion();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $marca = trim((string)($_POST['marca'] ?? ''));
        $descripcion = trim((string)($_POST['descripcion'] ?? ''));
        $precio = filter_var(str_replace(',', '.', (string)($_POST['precio'] ?? '')), FILTER_VALIDATE_FLOAT);
        $categoriaId = filter_var($_POST['categoria_id'] ?? null, FILTER_VALIDATE_INT);
        $stockMinimo = filter_var($_POST['stock_minimo'] ?? 5, FILTER_VALIDATE_INT);
        $stocksEntrada = $_POST['stock'] ?? [];
        $stocks = [];

        if ($id === false || $id === null || $id < 0 || $nombre === '' || (function_exists('mb_strlen') ? mb_strlen($nombre, 'UTF-8') : strlen($nombre)) > 150 || $marca === '' ||
            (function_exists('mb_strlen') ? mb_strlen($marca, 'UTF-8') : strlen($marca)) > 100 || $precio === false || $precio < 0 || !$categoriaId ||
            $stockMinimo === false || $stockMinimo < 0 || !is_array($stocksEntrada)) {
            $this->volverConError('Completá los campos obligatorios con valores válidos.', $id > 0 ? (int)$id : null);
        }
        $categoriasValidas = array_map(static fn($c) => (int)$c['id'], $this->productoModel->listarCategorias());
        if (!in_array((int)$categoriaId, $categoriasValidas, true)) {
            $this->volverConError('La categoría seleccionada no es válida.', $id > 0 ? (int)$id : null);
        }
        foreach ($stocksEntrada as $talleId => $cantidad) {
            $talleId = filter_var($talleId, FILTER_VALIDATE_INT);
            $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT);
            if (!$talleId || $cantidad === false || $cantidad < 0 || $cantidad > 10000) {
                $this->volverConError('Revisá las cantidades de stock por talle.', $id > 0 ? (int)$id : null);
            }
            $stocks[(int)$talleId] = (int)$cantidad;
        }

        $existente = $id > 0 ? $this->productoModel->obtenerParaAdmin((int)$id) : null;
        if ($id > 0 && !$existente) {
            $this->volverConError('El producto que intentás editar no existe.');
        }
        $imagen = $existente['imagen'] ?? null;
        try {
            if (isset($_FILES['imagen']) && (int)$_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
                $imagen = $this->subirImagen($_FILES['imagen']);
            }
            $datos = [
                'nombre'=>$nombre, 'marca'=>$marca, 'descripcion'=>$descripcion,
                'precio'=>(float)$precio, 'imagen'=>$imagen, 'categoria_id'=>(int)$categoriaId,
                'stock_minimo'=>(int)$stockMinimo
            ];
            if ($id > 0) {
                $this->productoModel->actualizarAdmin((int)$id, $datos, $stocks);
                $mensaje = 'Producto actualizado correctamente.';
            } else {
                $this->productoModel->crearAdmin($datos, $stocks);
                $mensaje = 'Producto creado correctamente.';
            }
            $_SESSION['mensaje_admin_producto'] = $mensaje;
            header('Location: index.php?pagina=admin-productos');
            exit;
        } catch (Throwable $e) {
            $this->volverConError($e instanceof RuntimeException ? $e->getMessage() : 'No se pudo guardar el producto. Verificá la base de datos y la carpeta de imágenes.', $id > 0 ? (int)$id : null);
        }
    }

    public function cambiarEstado(): void
    {
        $this->exigirAdmin();
        $this->validarPeticion();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $activo = filter_var($_POST['activo'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1 || !in_array($activo, [0, 1], true)) {
            $this->redirigirConMensaje('Los datos enviados no son válidos.');
        }
        try {
            $this->productoModel->cambiarActivoAdmin((int)$id, (int)$activo);
            $this->redirigirConMensaje($activo === 1 ? 'Producto reactivado.' : 'Producto dado de baja.');
        } catch (Throwable $e) {
            $this->redirigirConMensaje('No se pudo cambiar el estado del producto.');
        }
    }

    private function subirImagen(array $archivo): string
    {
        if ((int)$archivo['error'] !== UPLOAD_ERR_OK || (int)$archivo['size'] > 5 * 1024 * 1024) {
            throw new RuntimeException('La imagen debe pesar menos de 5 MB.');
        }
        if (!is_uploaded_file($archivo['tmp_name'])) {
            throw new RuntimeException('La carga de la imagen no es válida.');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($archivo['tmp_name']);
        $extensiones = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
        if (!isset($extensiones[$mime]) || @getimagesize($archivo['tmp_name']) === false) {
            throw new RuntimeException('Usá una imagen JPG, PNG o WEBP.');
        }
        $directorio = __DIR__ . '/../public/img/productos';
        if (!is_dir($directorio) && !mkdir($directorio, 0755, true) && !is_dir($directorio)) {
            throw new RuntimeException('No se pudo preparar la carpeta de imágenes.');
        }
        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
        if (!move_uploaded_file($archivo['tmp_name'], $directorio . '/' . $nombreArchivo)) {
            throw new RuntimeException('No se pudo guardar la imagen.');
        }
        return 'public/img/productos/' . $nombreArchivo;
    }

    private function exigirAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: index.php?pagina=login');
            exit;
        }
        if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
            http_response_code(403);
            header('Location: index.php?pagina=home');
            exit;
        }
    }

    private function prepararCsrf(): void
    {
        if (empty($_SESSION['csrf_admin_productos'])) {
            $_SESSION['csrf_admin_productos'] = bin2hex(random_bytes(32));
        }
    }

    private function validarPeticion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=admin-productos');
            exit;
        }
        $token = (string)($_POST['csrf_token'] ?? '');
        $esperado = (string)($_SESSION['csrf_admin_productos'] ?? '');
        if ($esperado === '' || !hash_equals($esperado, $token)) {
            $this->redirigirConMensaje('La sesión del formulario venció. Volvé a intentarlo.');
        }
    }

    private function volverConError(string $mensaje, ?int $id = null): void
    {
        $_SESSION['error_admin_producto'] = $mensaje;
        header('Location: index.php?pagina=admin-producto-formulario' . ($id ? '&id=' . $id : ''));
        exit;
    }

    private function redirigirConMensaje(string $mensaje): void
    {
        $_SESSION['mensaje_admin_producto'] = $mensaje;
        header('Location: index.php?pagina=admin-productos');
        exit;
    }
}
