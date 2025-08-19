<?
$conexion = mysqli_connect("localhost", "c2271701_datosar", "rulageKO64", "c2271701_datosar");

// Verificamos la conexión
if (!$conexion) {
    die("Error conectando a la base de datos: " . mysqli_connect_error());
}
?>