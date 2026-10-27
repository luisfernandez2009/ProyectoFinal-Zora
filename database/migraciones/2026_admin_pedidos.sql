-- Ejecutar una sola vez en bases existentes.
-- Separa el estado de envío del estado del pago.
ALTER TABLE pedido
    ADD COLUMN estado_envio ENUM('preparando', 'enviado', 'entregado')
    NOT NULL DEFAULT 'preparando';
