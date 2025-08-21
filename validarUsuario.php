<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require_once 'conexionn/conexion.php';
require_once 'Funciones_Turno.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$usr = trim(filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW) ?? '');
$pw  = trim(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW) ?? '');

if ($usr === '' || $pw === '') {
    header('Location: index.php?msg_error=1');
    exit;
}

$pw_enc = encriptar($pw);

// ----------------------
// Verificar conexión
// ----------------------
if ($conexion->connect_errno) {
    die('Error de conexión a la base de datos: ' . $conexion->connect_error);
}

// ----------------------
// Intento Admin
// ----------------------
$stmtA = $conexion->prepare(
    "SELECT id_dni, id_TipoUsuario, tx_nombre, tx_apellido
     FROM t_usuarios
     WHERE tx_username = ? AND tx_password = ?"
);

if (!$stmtA) {
    die('Error en consulta Admin: ' . $conexion->error);
}

$stmtA->bind_param('ss', $usr, $pw_enc);
$stmtA->execute();
$resA = $stmtA->get_result();

if ($resA && ($rowA = $resA->fetch_assoc())) {
    $_SESSION['autenticado'] = 'SI';
    $_SESSION['uid'] = $rowA['id_dni'];
    $_SESSION['user'] = $usr;
    $_SESSION['nombre_usuario'] = $rowA['tx_nombre'] . ' ' . $rowA['tx_apellido'];
    header('Location: inicio.php');
    exit;
}
$stmtA->close();

// ----------------------
// Intento Evaluador
// ----------------------
$stmtE = $conexion->prepare(
    "SELECT Id, Nombre
     FROM evaluador
     WHERE Legajo = ? AND clave = ?"
);

if (!$stmtE) {
    die('Error en consulta Evaluador: ' . $conexion->error);
}

$stmtE->bind_param('ss', $usr, $pw_enc);
$stmtE->execute();
$resE = $stmtE->get_result();

if ($resE && ($rowE = $resE->fetch_assoc())) {
    $_SESSION['Evaluador'] = 'SI';
    $_SESSION['uid'] = $rowE['Id'];
    $_SESSION['user'] = $usr;
    $_SESSION['nombre_usuario'] = $rowE['Nombre'];
    $_SESSION['idempresa'] = (int)$rowE['IdEmpresa'];
    header('Location: inicio.php');
    exit;
}
$stmtE->close();

// ----------------------
// Credenciales inválidas
// ----------------------
header('Location: index.php?msg_error=1');
exit;
