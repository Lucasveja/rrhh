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
					      $sql="SELECT * from posicion";
					      //echo "refrescar: ".$sqlT;
						  $ruslt=mysql_query($sql);
						  $n=mysql_num_rows($ruslt);



						  if($n!=0){
						  	?>
						  	<div class="col-lg-6">
						  		<table class="table table-hover">
						  		<th class="align-center">#</th>
						  		<th>Posicion</th>
						  		<th>Empresa</th>
						  		<th></th>
						  		<th></th>

						<?		
						  while($read=mysql_fetch_array($ruslt)){

						  	$sqlE="Select * from empresas where Id='{$read[IdEmpresa]}'";
						  	$resE=mysql_query($sqlE);
						  	$emp=mysql_fetch_array($resE);
						  	?>
						 											 							<tr>	
						 								<td class="col-lg-1" ><? echo $read['Id']; ?></td>
						 							    <td class="col-lg-2"><? echo utf8_encode($read['Posicion']); ?></td>
						 							    <td class="col-lg-2"><? echo utf8_encode($emp['Empresa']); ?></td>
						 							     <td class="col-lg-1">
						 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Editar"><span  class="glyphicon glyphicon-pencil"  style="color:#18B1F9" onclick="editar('<? echo $read[Id] ?>','<? echo $read[Posicion] ?>', '<? echo $read[IdEmpresa] ?>');"></span></button>
						 
						 							   	</td> 
						 							    <td class="col-lg-1">
						 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Eliminar"><span  class="glyphicon glyphicon-remove"  style="color:red" onclick="eliminar('<? echo $read[Id] ?>','<? echo $read[Posicion] ?>');" ></span></button>
						 
						 							   	</td> 								
						 							</tr>
						 							<?
						 						    }//foreach
						 						  
						 						  ?>
						 						</table>
						 					</div>
						 <? } ?>



