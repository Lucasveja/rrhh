<?
$conexion= mysql_connect("localhost","c2271701_datosar","rulageKO64");
if (!mysql_select_db("c2271701_datosar",$conexion))
{
   echo "Error seleccionando la base de datos.";
   exit();
}

?>