<?php
error_reporting(0); // Considera cambiar esto a E_ALL durante el desarrollo para ver errores.

// Configuración de la base de datos
$hostname = "localhost";
$username = "root";
$password = "";
$database = "c2271701_datosar";

// Establecer la conexión MySQLi
$conexion = new mysqli($hostname, $username, $password, $database);

// Verificar la conexión
if ($conexion->connect_error) {
    // En un entorno de producción, es mejor no mostrar el error detallado al usuario.
    // Podrías registrarlo en un log de errores.
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
if (!$conexion->set_charset("utf8")) {
    die("Error al establecer el juego de caracteres: " . $conexion->error);
}

/**
 * Función para ejecutar consultas SQL usando MySQLi.
 *
 * @param string $sqlP La consulta SQL a ejecutar.
 * @return stdClass Un objeto con las filas, el número de filas y la primera fila (si existe).
 */
function query($sqlP)
{
    global $conexion; // Accedemos a la variable de conexión global

    $query_result = $conexion->query($sqlP);

    $query = new stdClass();
    $query->row = array();
    $query->rows = array();
    $query->num_rows = 0;

    if ($query_result) {
        // Si la consulta es un SELECT, procesamos los resultados
        if ($query_result instanceof mysqli_result) {
            $data = array();
            while ($row = $query_result->fetch_assoc()) {
                $data[] = $row;
            }
            $query->rows = $data;
            $query->num_rows = $query_result->num_rows;
            $query->row = isset($data[0]) ? $data[0] : array();
            
            $query_result->free(); // Liberar el conjunto de resultados
        } else {
            // Para INSERT, UPDATE, DELETE, etc., num_rows podría no ser relevante o ser 0
            // y no hay filas para obtener. Solo confirmamos que la consulta se ejecutó sin errores.
            $query->num_rows = $conexion->affected_rows; // Opcional: para ver cuántas filas fueron afectadas
        }
    } else {
        // En caso de error en la consulta
        // Puedes agregar aquí un manejo de errores más sofisticado, como logging.
        // echo "Error en la consulta: " . $conexion->error . " (SQL: " . $sqlP . ")";
    }

    return $query;
}

// La función mysql_close($conexion); ya no es necesaria con MySQLi
// La conexión se cierra automáticamente al finalizar el script o al cerrar el objeto $conexion.
// Si deseas cerrarla explícitamente, puedes usar $conexion->close();
?>
