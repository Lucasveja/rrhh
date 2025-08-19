<?php
 session_start();
 $conexion= mysql_connect("localhost","c2271701_datosar","rulageKO64");
 if (!mysql_select_db("c2271701_datosar",$conexion))
 {
    echo "Error seleccionando la base de datos.";
    exit();
 }

$idcombo = $_POST["id"];
$action =$_POST["combo"];
$empresa=$_POST["emp"];
$peri=$_POST["per"];
$p= explode("/",$peri);
$peri=$p[0].$p[1];
 
switch($action){
    case "evaluador":
                  $os=$idcombo;
                  $sqlM="SELECT e.id,e.nombre FROM asignados a inner join evaluado e on a.idevaluado=e.id where
a.idevaluador={$idcombo} and a.idempresa={$empresa} and a.periodo={$peri}";
                  $consulta=mysql_query($sqlM,$conexion);
                  $Resultado = mysql_num_rows($consulta);

                 //echo'<option>'.htmlentities($sqlM).'</option>';
                  //$i=0;
                  echo "<script>document.getElementById('idEvall').value=".$idcombo.";</script><option value='-1'>Seleccionar..</option>";
                  while ($row = mysql_fetch_array($consulta))
                  {
                    echo"<option value='".htmlentities($row['id'])."'>".htmlentities($row['nombre'])."</option>";
                    //$i++;

                  }
                  

                  break;
    

   
              

    
                

    
        
}//fin switch
?>



