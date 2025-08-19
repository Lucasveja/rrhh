<?
session_start();

include("../../conexionn/conexion.php");
include("../../funciones/Funciones_Turno.php");
     
//Cerrrar conexion a la BD
//mysql_close($conexion);
//echo $nombreUsuario." ".$_SESSION['uid'];

//FIN SESSION
?>


					<?
					      $sql="SELECT * from evaluado";
					      //echo "refrescar: ".$sqlT;
						  $ruslt=mysql_query($sql);
						  $n=mysql_num_rows($ruslt);



						  if($n!=0){
						  	?>
						  	<div class="col-lg-10">
						  		<table class="table table-hover">
						  		<th class="text-center">#</th>
						  		<th class="text-center">Legajo</th>
						  		<th class="text-center">Nombre</th>
						  		<th class="text-center">Area</th>
						  		<th class="text-center">Departamento</th>
						  		<th class="text-center">Posicion</th>
						  		<th class="text-center">Empresa</th>
						  		

						<?		
						  while($read=mysql_fetch_array($ruslt)){

						  	$sqlA="Select * from areas where Id='{$read[IdArea]}'";
						  	$resA=mysql_query($sqlA);
						  	$area=mysql_fetch_array($resA);

						  	$sqlD="Select * from departamentos where Id='{$read[IdDepto]}'";
						  	$resD=mysql_query($sqlD);
						  	$depto=mysql_fetch_array($resD);

						  	$sqlE="Select * from empresas where Id='{$read[IdEmpresa]}'";
						  	$resE=mysql_query($sqlE);
						  	$emp=mysql_fetch_array($resE);
						  	
						  	$sqlP="Select * from posicion where Id='{$read[IdPosicion]}'";
						  	$resP=mysql_query($sqlP);
						  	$pos=mysql_fetch_array($resP);
						  	?>
						 		<tr>	
	 								<td class="col-lg-1 text-center" ><? echo $read['id']; ?></td>
	 							    <td class="col-lg-1 text-center"><? echo utf8_encode($read['Legajo']); ?></td>
	 							    <td class="col-lg-3 text-center"><? echo utf8_encode($read['Nombre']); ?></td>
	 							    <td class="col-lg-1 text-center"><? echo utf8_encode($area['Area']); ?></td>
	 							    <td class="col-lg-1 text-center"><? echo utf8_encode($depto['Depto']); ?></td>
	 							    <td class="col-lg-1 text-center"><? echo utf8_encode($pos['Posicion']); ?></td>
	 							    <td class="col-lg-2 text-center"><? echo utf8_encode($emp['Empresa']); ?></td>
	 							    
	 							    <td class="col-lg-1 text-center">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Editar"><span  class="glyphicon glyphicon-pencil"  style="color:#18B1F9" onclick="editar('<? echo $read[id] ?>', '<? echo $read['Legajo'] ?>', '<? echo $read['Nombre'] ?>','<? echo $read[IdArea] ?>','<? echo $read[IdDepto] ?>','<? echo $read[IdPosicion] ?>', '<? echo $read[IdEmpresa] ?>');"></span></button>
	 
	 							   	</td> 
	 							    <td class="col-lg-1">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Eliminar"><span  class="glyphicon glyphicon-remove"  style="color:red" onclick="eliminar('<? echo $read[id] ?>','<? echo $read[Nombre] ?>');" ></span></button>
	 
	 							   	</td> 								
	 							</tr>
						 							<?
						 						    }//foreach
						 						  
						 						  ?>
						 						</table>
						 					</div>
						 <? } ?>



