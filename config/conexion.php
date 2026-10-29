<?php
// Variables de entorno para Ubuntu Server (u otros servidores).
// Sin variables configuradas conserva los valores originales de XAMPP.
$servidorBaseDeDatos = getenv('ZORA_DB_HOST') ?: 'localhost';
$usuarioBaseDeDatos = getenv('ZORA_DB_USER') ?: 'root';
$contrasenaEnv = getenv('ZORA_DB_PASSWORD');
$contrasenaBaseDeDatos = $contrasenaEnv === false ? '' : $contrasenaEnv;
$nombreBaseDeDatos = getenv('ZORA_DB_NAME') ?: 'zora';

$conexion = mysqli_connect(
    $servidorBaseDeDatos,
    $usuarioBaseDeDatos,
    $contrasenaBaseDeDatos,
    $nombreBaseDeDatos
);

if (!$conexion) {
    error_log('Zora: error al conectar con MySQL: ' . mysqli_connect_error());
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}
mysqli_set_charset($conexion, 'utf8mb4');
