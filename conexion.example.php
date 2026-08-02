<?php
// conexion.example.php
// Copia este archivo, renómbralo a "conexion.php", y pon TU propio
// password de MySQL. El conexion.php real nunca se sube a GitHub
// (está en .gitignore) porque cada quien tiene un password distinto.

$servidor = "localhost";
$usuario  = "root";
$password = "TU_PASSWORD_AQUI"; // <-- cada quien pone el suyo
$base_datos = "db_tiendaonline";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>
