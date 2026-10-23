-- Migración para bases de datos Zora ya creadas.
-- Ejecutar UNA SOLA VEZ si producto todavía no tiene la columna stock_minimo.
ALTER TABLE producto
    ADD COLUMN stock_minimo INT UNSIGNED NOT NULL DEFAULT 5;
