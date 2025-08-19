<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("conexionn/conexion.php");
include("Funciones_Turno.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$usr = trim($_POST['usuario'] ?? '');
$pw  = trim($_POST['password'] ?? '');
$pw_enc = encriptar($pw);

// ----------------------
// Verificar conexión
// ----------------------
if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

// ----------------------
// Intento Admin
// ----------------------
$stmtA = mysqli_prepare(
    $conexion,
    "SELECT id_dni, id_TipoUsuario, tx_nombre, tx_apellido
     FROM t_usuarios
     WHERE tx_username = ? AND tx_password = ?"
);

if (!$stmtA) {
    die("Error en consulta Admin: " . mysqli_error($conexion));
}

mysqli_stmt_bind_param($stmtA, "ss", $usr, $pw_enc);
mysqli_stmt_execute($stmtA);
$resA = mysqli_stmt_get_result($stmtA);

if ($resA && ($rowA = mysqli_fetch_assoc($resA))) {
    $_SESSION['autenticado'] = 'SI';
    $_SESSION['uid'] = $rowA['id_dni'];
    $_SESSION['user'] = $usr;
    $_SESSION['nombre_usuario'] = $rowA['tx_nombre'] . " " . $rowA['tx_apellido'];
    header("Location: inicio.php");
    exit;
}
mysqli_stmt_close($stmtA);

// ----------------------
// Intento Evaluador
// ----------------------
$stmtE = mysqli_prepare(
    $conexion,
    "SELECT Id, Nombre
     FROM evaluador
     WHERE Legajo = ? AND clave = ?"
);

if (!$stmtE) {
    die("Error en consulta Evaluador: " . mysqli_error($conexion));
}

mysqli_stmt_bind_param($stmtE, "ss", $usr, $pw_enc);
mysqli_stmt_execute($stmtE);
$resE = mysqli_stmt_get_result($stmtE);

if ($resE && ($rowE = mysqli_fetch_assoc($resE))) {
    $_SESSION['Evaluador'] = 'SI';
    $_SESSION['uid'] = $rowE['Id'];
    $_SESSION['user'] = $usr;
    $_SESSION['nombre_usuario'] = $rowE['Nombre'];
    header("Location: inicio.php");
    exit;
}
mysqli_stmt_close($stmtE);

// ----------------------
// Credenciales inválidas
// ----------------------
echo "<h2>Usuario o clave incorrectos</h2>";
echo "<p>Usuario ingresado: " . htmlspecialchars($usr) . "</p>";
?>
<a href="index.php">Volver al login</a>
