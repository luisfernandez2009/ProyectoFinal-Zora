-- Permite que el rol moderador gestione productos sin acceder a la administración de usuarios.
-- Ejecutar una sola vez sobre bases de datos existentes.
ALTER TABLE usuario
    MODIFY COLUMN rol ENUM('cliente', 'moderador', 'admin') NOT NULL DEFAULT 'cliente';
