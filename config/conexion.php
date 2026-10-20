<?php
$servidorBaseDeDatos = "localhost";
$usuarioBaseDeDatos = "root";
$contrasenaBaseDeDatos = "";
$nombreBaseDeDatos= "zora";

$conexion = mysqli_connect(
$servidorBaseDeDatos,
$usuarioBaseDeDatos,
$contrasenaBaseDeDatos,
$nombreBaseDeDatos);

if (!$conexion) {
	die("Error de coneción a la base de datos: ". mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8mb4");