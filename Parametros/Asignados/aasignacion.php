<?php
session_start();

// Incluir el archivo de conexión a la base de datos
require_once("../../conexionn/conexion.php");

// Lógica PHP para procesar el formulario y obtener datos del servidor
// Por ejemplo, procesar la tabla según los parámetros recibidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idE = $_POST['idE'];
    $idEv = $_POST['idEv'];
    $id_ev = $_POST['id_ev'];
    $per = $_POST['per'];
    $idAsig = $_POST['idAsig'];

    if($idAsig==0){
		$sqlI="INSERT INTO asignados (IdEmpresa, IdEvaluador, IdEvaluado, Periodo) VALUES ('{$idE}', '{$idEv}', '{$id_ev}', '{$per}')";
		mysql_query($sqlI);
		
	}
	else{
		
		$sqlU="UPDATE asignados SET IdEmpresa='{$idE}', IdEvaluador='{$idEv}', IdEvaluado='{$id_ev}', Periodo='{$per}' Where id='{$idAsig}'";	
		mysql_query($sqlU);
		
	}
}
?>
