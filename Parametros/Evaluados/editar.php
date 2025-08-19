<?
session_start();

include("../../conexionn/conexion.php");
include("../../funciones/Funciones_Turno.php");
 
$data = json_decode(file_get_contents('php://input'), true);

// Acceder a los datos
$id = $data['id'];
$legajo = $data['legajo'];
$nombre = $data['nombre'];
$area = $data['area'];
$depto = $data['depto'];
$posicion = $data['posicion'];
$empresa = $data['empresa'];
$sql="UPDATE evaluado SET Legajo='{$legajo}', Nombre='{$nombre}', IdArea='{$area}', IdDepto='{$depto}', IdPosicion='{$posicion}', IdEmpresa='{$empresa}' Where id='{$id}'";
echo $sql;
$res=mysql_query($sql);
?>