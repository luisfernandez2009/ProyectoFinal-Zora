-- Migración para bases de datos Zora ya existentes.
-- Ejecutar una sola vez. Amplía estados del pedido y crea los intentos de pago.
ALTER TABLE pedido
    MODIFY estado ENUM(
        'pendiente',
        'confirmado',
        'preparando',
        'enviado',
        'entregado',
        'cancelado',
        'pagado',
        'rechazado'
    ) NOT NULL DEFAULT 'pendiente';

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
