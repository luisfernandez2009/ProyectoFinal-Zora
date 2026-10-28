
-- =========================================================
-- ZORA - Base de datos para el e-commerce
-- Motor: MySQL / MariaDB
-- =========================================================

CREATE DATABASE IF NOT EXISTS zora
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE zora;

-- =========================================================
-- 1. CATEGORIAS
-- Conserva las columnas existentes: id, nombre
-- =========================================================

CREATE TABLE IF NOT EXISTS categoria (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================================================
-- 2. TALLES
-- Conserva las columnas existentes: id, numero
-- =========================================================

CREATE TABLE IF NOT EXISTS talles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero SMALLINT UNSIGNED NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================================================
-- 3. PRODUCTOS
-- Conserva las columnas existentes.
-- categoria_id referencia categoria.id
-- =========================================================

CREATE TABLE IF NOT EXISTS producto (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    categoria_id INT UNSIGNED NOT NULL,
    stock_minimo INT UNSIGNED NOT NULL DEFAULT 5,
    activo TINYINT(1) NOT NULL DEFAULT 1,

    INDEX idx_producto_categoria (categoria_id),
    INDEX idx_producto_activo (activo),

    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categoria(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_producto_precio
        CHECK (precio >= 0)
) ENGINE=InnoDB;

-- =========================================================
-- 4. STOCK POR PRODUCTO Y TALLE
-- Columnas: producto_id, talle_id, stock
-- Un registro por combinación de producto y talle.
-- =========================================================

CREATE TABLE IF NOT EXISTS producto_talle (
    producto_id INT UNSIGNED NOT NULL,
    talle_id INT UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (producto_id, talle_id),
    INDEX idx_producto_talle_talle (talle_id),

    CONSTRAINT fk_producto_talle_producto
        FOREIGN KEY (producto_id)
        REFERENCES producto(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_producto_talle_talle
        FOREIGN KEY (talle_id)
        REFERENCES talles(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================================================
-- 5. USUARIOS
-- Se guardará el hash de la contraseña, nunca la contraseña
-- en texto plano.
-- =========================================================

CREATE TABLE IF NOT EXISTS usuario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'moderador', 'admin') NOT NULL DEFAULT 'cliente',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================
-- 6. PEDIDOS
-- Guarda una compra y una copia de los datos de entrega.
-- El total se calcula desde el servidor.
-- =========================================================

CREATE TABLE IF NOT EXISTS pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,

    fecha_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    estado ENUM(
        'pendiente',
        'confirmado',
        'preparando',
        'enviado',
        'entregado',
        'cancelado',
        'pagado',
        'rechazado'
    ) NOT NULL DEFAULT 'pendiente',

    estado_envio ENUM('preparando', 'enviado', 'entregado') NOT NULL DEFAULT 'preparando',

    nombre_entrega VARCHAR(150) NOT NULL,
    telefono_entrega VARCHAR(30),
    direccion_entrega VARCHAR(255) NOT NULL,
    ciudad_entrega VARCHAR(100) NOT NULL,
    departamento_entrega VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(20),

    metodo_pago VARCHAR(50),
    observaciones TEXT,

    total DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    INDEX idx_pedido_usuario (usuario_id),
    INDEX idx_pedido_fecha (fecha_pedido),
    INDEX idx_pedido_estado (estado),

    CONSTRAINT fk_pedido_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuario(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_pedido_total
        CHECK (total >= 0)
) ENGINE=InnoDB;

-- =========================================================
-- 7. DETALLE DE PEDIDO
-- Conserva el precio al momento de la compra.
-- El stock se controla usando producto_talle.
-- =========================================================

CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    talle_id INT UNSIGNED NOT NULL,

    cantidad INT UNSIGNED NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,

    INDEX idx_detalle_pedido (pedido_id),
    INDEX idx_detalle_producto (producto_id),
    INDEX idx_detalle_talle (talle_id),

    CONSTRAINT fk_detalle_pedido
        FOREIGN KEY (pedido_id)
        REFERENCES pedido(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (producto_id)
        REFERENCES producto(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_detalle_talle
        FOREIGN KEY (talle_id)
        REFERENCES talles(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_detalle_cantidad
        CHECK (cantidad > 0),

    CONSTRAINT chk_detalle_precio
        CHECK (precio_unitario >= 0)
) ENGINE=InnoDB;


-- =========================================================
-- 8. INTENTOS DE PAGO (simulación educativa)
-- Nunca almacena número completo, vencimiento ni CVV.
-- =========================================================
CREATE TABLE IF NOT EXISTS pago (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    monto DECIMAL(12,2) NOT NULL,
    ultimos4 CHAR(4) NOT NULL,
    resultado ENUM('aprobado', 'rechazado') NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pago_pedido (pedido_id),
    CONSTRAINT fk_pago_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedido(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_pago_monto CHECK (monto >= 0)
) ENGINE=InnoDB;

-- =========================================================
-- DATOS INICIALES DE EJEMPLO
-- No duplica categorías ni talles que ya existan.
-- =========================================================

-- =========================================
-- 1. CATEGORÍAS (MARCAS)
-- =========================================

INSERT INTO categoria (nombre)
SELECT 'Adidas'
WHERE NOT EXISTS (
    SELECT 1 FROM categoria WHERE nombre = 'Adidas'
);

INSERT INTO categoria (nombre)
SELECT 'Nike'
WHERE NOT EXISTS (
    SELECT 1 FROM categoria WHERE nombre = 'Nike'
);

INSERT INTO categoria (nombre)
SELECT 'Puma'
WHERE NOT EXISTS (
    SELECT 1 FROM categoria WHERE nombre = 'Puma'
);

INSERT INTO categoria (nombre)
SELECT 'New Balance'
WHERE NOT EXISTS (
    SELECT 1 FROM categoria WHERE nombre = 'New Balance'
);


-- =========================================
-- 2. TABLA PARA LAS IMÁGENES ADICIONALES
-- =========================================

CREATE TABLE IF NOT EXISTS producto_imagen (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    producto_id INT UNSIGNED NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    orden INT UNSIGNED NOT NULL DEFAULT 1,

    INDEX idx_producto_imagen_producto (producto_id),

    CONSTRAINT fk_producto_imagen_producto
        FOREIGN KEY (producto_id)
        REFERENCES producto(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;


-- =========================================
-- 3. PRODUCTOS
-- =========================================

-- NIKE

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Nike Air Max 270',
    'Nike',
    'Las Nike Air Max 270 combinan comodidad, estilo y tecnología Air para el uso diario. Destacan por su diseño moderno y su unidad Air Max ubicada en el talón.',
    6590,
    'public/img/productos/nike-airmax270.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Nike' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Nike Air Max 270'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Nike Revolution 7',
    'Nike',
    'Calzado deportivo de diseño versátil, pensado para acompañar entrenamientos y actividades cotidianas.',
    4390,
    'public/img/productos/nike-revolution-7.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Nike' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Nike Revolution 7'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Nike Pegasus 41',
    'Nike',
    'Calzado deportivo para correr, diseñado para ofrecer comodidad y rendimiento durante los entrenamientos.',
    7990,
    'public/img/productos/nike-pegasus-41.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Nike' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Nike Pegasus 41'
);


-- ADIDAS

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Adidas Ultraboost Light',
    'Adidas',
    'Calzado deportivo para running que combina un diseño moderno con una propuesta orientada a la comodidad.',
    8490,
    'public/img/productos/adidas-ultraboost-light.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Adidas' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Adidas Ultraboost Light'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Adidas Duramo SL',
    'Adidas',
    'Calzado deportivo versátil para correr y realizar actividades físicas cotidianas.',
    4290,
    'public/img/productos/adidas-duramo-sl.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Adidas' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Adidas Duramo SL'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Adidas Runfalcon 5',
    'Adidas',
    'Calzado deportivo de estilo dinámico, adecuado para entrenamientos y uso diario.',
    4990,
    'public/img/productos/adidas-runfalcon-5.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Adidas' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Adidas Runfalcon 5'
);


-- PUMA

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Puma RS-X B&W',
    'Puma',
    'Zapatillas de estilo urbano con una estética llamativa y una combinación de colores blanco y negro.',
    5290,
    'public/img/productos/puma_rs-x_b&w.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Puma' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Puma RS-X B&W'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Puma Caven 2.0',
    'Puma',
    'Zapatillas de inspiración deportiva y estilo clásico, pensadas para completar looks urbanos.',
    4690,
    'public/img/productos/puma-caven-2.0.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Puma' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Puma Caven 2.0'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'Puma Velocity Nitro',
    'Puma',
    'Calzado deportivo para running, diseñado para acompañar sesiones de entrenamiento.',
    7590,
    'public/img/productos/puma-velocity-nitro.jpg',
    (SELECT id FROM categoria WHERE nombre = 'Puma' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'Puma Velocity Nitro'
);


-- NEW BALANCE

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'New Balance 574',
    'New Balance',
    'Zapatillas de estilo clásico y urbano, ideales para combinar comodidad y uso cotidiano.',
    5990,
    'public/img/productos/new-balance-574.jpg',
    (SELECT id FROM categoria WHERE nombre = 'New Balance' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'New Balance 574'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'New Balance 530',
    'New Balance',
    'Zapatillas de inspiración retro con estética deportiva, adecuadas para el uso diario.',
    6490,
    'public/img/productos/new-balance-530.jpg',
    (SELECT id FROM categoria WHERE nombre = 'New Balance' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'New Balance 530'
);

INSERT INTO producto
    (nombre, marca, descripcion, precio, imagen, categoria_id, activo)
SELECT
    'New Balance Fresh Foam X',
    'New Balance',
    'Calzado deportivo orientado al running, con un diseño enfocado en la comodidad durante la actividad física.',
    8290,
    'public/img/productos/new-balance-fresh-foam-x.jpg',
    (SELECT id FROM categoria WHERE nombre = 'New Balance' LIMIT 1),
    1
WHERE NOT EXISTS (
    SELECT 1 FROM producto WHERE nombre = 'New Balance Fresh Foam X'
);


-- =========================================
-- 4. IMÁGENES DE CADA PRODUCTO
-- =========================================

-- Nike Air Max 270
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/nike-airmax270.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/nike-airmax270-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/nike-airmax270-suela.jpg', 3
) i
WHERE p.nombre = 'Nike Air Max 270'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Nike Revolution 7
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/nike-revolution-7.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/nike-revolution-7-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/nike-revolution-7-suela.jpg', 3
) i
WHERE p.nombre = 'Nike Revolution 7'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Nike Pegasus 41
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/nike-pegasus-41.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/nike-pegasus-41-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/nike-pegasus-41-suela.jpg', 3
) i
WHERE p.nombre = 'Nike Pegasus 41'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Adidas Ultraboost Light
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/adidas-ultraboost-light.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/adidas-ultraboost-light-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/adidas-ultraboost-light-suela.jpg', 3
) i
WHERE p.nombre = 'Adidas Ultraboost Light'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Adidas Duramo SL
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/adidas-duramo-sl.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/adidas-duramo-sl-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/adidas-duramo-sl-suela.jpg', 3
) i
WHERE p.nombre = 'Adidas Duramo SL'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Adidas Runfalcon 5
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/adidas-runfalcon-5.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/adidas-runfalcon-5-suela-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/adidas-runfalcon-5-suela.jpg', 3
) i
WHERE p.nombre = 'Adidas Runfalcon 5'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Puma RS-X B&W
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/puma_rs-x_b&w.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/puma_rs-x_b&w-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/puma_rs-x_b&w-suela.jpg', 3
) i
WHERE p.nombre = 'Puma RS-X B&W'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Puma Caven 2.0
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/puma-caven-2.0.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/puma-caven-2.0-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/puma-caven-2.0-suela.jpg', 3
) i
WHERE p.nombre = 'Puma Caven 2.0'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- Puma Velocity Nitro
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/puma-velocity-nitro.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/puma-velocity-nitro-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/puma-velocity-nitro-suela.jpg', 3
) i
WHERE p.nombre = 'Puma Velocity Nitro'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- New Balance 574
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/new-balance-574.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/new-balance-574-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/new-balance-574-suela.jpg', 3
) i
WHERE p.nombre = 'New Balance 574'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- New Balance 530
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/new-balance-530.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/new-balance-530-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/new-balance-530-suela.jpg', 3
) i
WHERE p.nombre = 'New Balance 530'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);

-- New Balance Fresh Foam X
INSERT INTO producto_imagen (producto_id, ruta, orden)
SELECT p.id, i.ruta, i.orden
FROM producto p
JOIN (
    SELECT 'public/img/productos/new-balance-fresh-foam-x.jpg' AS ruta, 1 AS orden
    UNION ALL SELECT 'public/img/productos/new-balance-fresh-foam-x-lado.jpg', 2
    UNION ALL SELECT 'public/img/productos/new-balance-fresh-foam-x-suela.jpg', 3
) i
WHERE p.nombre = 'New Balance Fresh Foam X'
AND NOT EXISTS (
    SELECT 1 FROM producto_imagen pi
    WHERE pi.producto_id = p.id AND pi.ruta = i.ruta
);


-- =========================================
-- 5. COMPROBACIÓN
-- =========================================

SELECT
    p.id,
    p.nombre,
    p.marca,
    c.nombre AS categoria,
    p.precio,
    p.imagen
FROM producto p
JOIN categoria c ON c.id = p.categoria_id
WHERE p.nombre IN (
    'Nike Air Max 270',
    'Nike Revolution 7',
    'Nike Pegasus 41',
    'Adidas Ultraboost Light',
    'Adidas Duramo SL',
    'Adidas Runfalcon 5',
    'Puma RS-X B&W',
    'Puma Caven 2.0',
    'Puma Velocity Nitro',
    'New Balance 574',
    'New Balance 530',
    'New Balance Fresh Foam X'
)
ORDER BY c.nombre, p.nombre;

-- =========================================
-- TALLES Y STOCK DE DEMOSTRACIÓN PARA DESARROLLO
-- Ajustar cantidades a la disponibilidad real antes de publicar.
-- =========================================
INSERT INTO talles (numero)
SELECT numeros.numero
FROM (
    SELECT 38 AS numero UNION ALL SELECT 39 UNION ALL SELECT 40 UNION ALL
    SELECT 41 UNION ALL SELECT 42 UNION ALL SELECT 43 UNION ALL SELECT 44 UNION ALL SELECT 45
) AS numeros
WHERE NOT EXISTS (SELECT 1 FROM talles t WHERE t.numero = numeros.numero);

INSERT INTO producto_talle (producto_id, talle_id, stock)
SELECT p.id, t.id, 5
FROM producto p
CROSS JOIN talles t
WHERE p.activo = 1
  AND t.numero BETWEEN 38 AND 45
  AND NOT EXISTS (
      SELECT 1 FROM producto_talle pt
      WHERE pt.producto_id = p.id AND pt.talle_id = t.id
  );


-- Comprobación final:
SHOW TABLES;


-- =========================================================
-- 9. MENSAJERÍA INTERNA ENTRE CLIENTES Y TIENDA
-- =========================================================
CREATE TABLE IF NOT EXISTS mensaje (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    asunto VARCHAR(100) NOT NULL,
    texto VARCHAR(1000) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    respuesta VARCHAR(1000) NULL,
    fecha_respuesta DATETIME NULL,
    INDEX idx_mensaje_usuario_fecha (id_usuario, fecha),
    INDEX idx_mensaje_respuesta_fecha (respuesta(100), fecha),
    CONSTRAINT fk_mensaje_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
