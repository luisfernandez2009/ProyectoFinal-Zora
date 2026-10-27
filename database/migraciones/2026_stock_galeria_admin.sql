-- Ejecutar UNA SOLA VEZ en bases existentes.
-- Crea la galería si la base fue creada antes de incorporar las miniaturas.
CREATE TABLE IF NOT EXISTS producto_imagen (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    producto_id INT UNSIGNED NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    orden INT UNSIGNED NOT NULL DEFAULT 1,
    INDEX idx_producto_imagen_producto (producto_id),
    CONSTRAINT fk_producto_imagen_producto
        FOREIGN KEY (producto_id) REFERENCES producto(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Asegura talles comunes y conserva los que ya existan.
INSERT INTO talles (numero)
SELECT numeros.numero
FROM (
    SELECT 38 AS numero UNION ALL SELECT 39 UNION ALL SELECT 40 UNION ALL
    SELECT 41 UNION ALL SELECT 42 UNION ALL SELECT 43 UNION ALL SELECT 44 UNION ALL SELECT 45
) AS numeros
WHERE NOT EXISTS (SELECT 1 FROM talles t WHERE t.numero = numeros.numero);

-- Los productos existentes reciben 5 unidades en cada combinación producto/talle
-- que todavía no tenga registro. Las cantidades ya existentes no se modifican.
INSERT INTO producto_talle (producto_id, talle_id, stock)
SELECT p.id, t.id, 5
FROM producto p
CROSS JOIN talles t
WHERE NOT EXISTS (
    SELECT 1 FROM producto_talle pt
    WHERE pt.producto_id = p.id AND pt.talle_id = t.id
);
