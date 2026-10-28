-- Mensajería interna cliente-tienda para bases Zora existentes.
-- Seguro de ejecutar nuevamente: no recrea la tabla si ya existe.
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
