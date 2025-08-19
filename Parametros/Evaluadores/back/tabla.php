<?
session_start();

include("../../conexionn/conexion.php");
include("../../funciones/Funciones_Turno.php");
function desencriptarr($encriptada){
    $clave = 3;
    $desencriptada = "";
    for ($k = 0; $k < strlen($encriptada); $k++){

        $car = chr((ord(substr ($encriptada, $k ,1))));
       if ($car=="&")
        {
          $car='Y';
       }
       else
       {

        $car = chr((ord(substr ($encriptada, $k ,1))
             + (256 - $clave)) % 256);
        }
        $desencriptada .= $car;
    }

    return $desencriptada;
}     
//Cerrrar conexion a la BD
//mysql_close($conexion);
//echo $nombreUsuario." ".$_SESSION['uid'];

//FIN SESSION
?>


					<?
					      $sql="SELECT * from evaluador";
					      //echo "refrescar: ".$sqlT;
						  $ruslt=mysql_query($sql);
						  $n=mysql_num_rows($ruslt);



						  if($n!=0){
						  	?>
						  	<div class="col-lg-8">
						  		<table class="table table-hover">
						  		<th class="text-center">#</th>
						  		<th class="text-center">Legajo</th>
						  		<th class="text-center">Nombre</th>
						  		<th class="text-center">Posicion</th>
						  		<th class="text-center">Empresa</th>
						  		<th class="text-center">Clave</th>

						<?		
						  while($read=mysql_fetch_array($ruslt)){

						  	$c=desencriptar($read['Clave']);
						  	//echo $c;

						  	$sqlE="Select * from empresas where Id='{$read[IdEmpresa]}'";
						  	$resE=mysql_query($sqlE);
						  	$emp=mysql_fetch_array($resE);
						  	
						  	$sqlP="Select * from posicion where Id='{$read[IdPosicion]}'";
						  	$resP=mysql_query($sqlP);
						  	$pos=mysql_fetch_array($resP);
						  	?>
						 		<tr>	
	 								<td class="col-lg-1 text-center" ><? echo $read['Id']; ?></td>
	 							    <td class="col-lg-1 text-center"><? echo utf8_encode($read['Legajo']); ?></td>
	 							    <td class="col-lg-3 text-center"><? echo utf8_encode($read['Nombre']); ?></td>
	 							    <td class="col-lg-2 text-center"><? echo utf8_encode($pos['Posicion']); ?></td>
	 							    <td class="col-lg-2 text-center"><? echo utf8_encode($emp['Empresa']); ?></td>
	 							     <td class="col-lg-2 text-center"><? echo desencriptarr($read['clave']); ?></td>
	 							    <td class="col-lg-1 text-center">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Editar"><span  class="glyphicon glyphicon-pencil"  style="color:#18B1F9" onclick="editar('<? echo $read[Id] ?>', '<? echo $read['Legajo'] ?>', '<? echo $read['Nombre'] ?>','<? echo $read[IdPosicion] ?>', '<? echo $read[IdEmpresa] ?>','<? echo $c ?>');"></span></button>
	 
	 							   	</td> 
	 							    <td class="col-lg-1">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Eliminar"><span  class="glyphicon glyphicon-remove"  style="color:red" onclick="eliminar('<? echo $read[Id] ?>','<? echo $read[Nombre] ?>');" ></span></button>
	 
	 							   	</td> 								
	 							</tr>
						 							<?
						 						    }//foreach
						 						  
						 						  ?>
						 						</table>
						 					</div>
						 <? } ?>



