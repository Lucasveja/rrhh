<?php
session_start();

// Incluir el archivo de conexión a la base de datos
require_once("../../conexionn/conexion.php");



    // Realizar consultas a la base de datos según los parámetros recibidos
    $sql = "SELECT tx_apellido,tx_nombre FROM t_usuarios WHERE id_dni='{$_SESSION['uid']}'";
  
    $result = mysql_query($sql);
    $rows = mysql_fetch_array($result);
    echo $rows['tx_nombre'].' '.$rows['tx_apellido'];

    
?>
