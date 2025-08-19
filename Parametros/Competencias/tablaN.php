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
					      $sql="SELECT * from niveles Where IdEmpresa='{$_GET[id_emp]}' and codCompetencia='{$_GET[id_comp]}' order by codCompetencia, Nivel";
					      //echo $sql;
					//SELECCIONO LOS EVALUADOS QUE NO ESTAN ASIGNADOS TODAVIA A NINGUN EVALUADOR
					      /*$sql="SELECT T1.*
									From evaluado  T1
									Left Outer Join
									       asignados  T2
									ON   T1.id = T2.IdEvaluado
									where T2.IdEvaluado is null and T1.IdEmpresa='{$_GET[id_emp]}'";
					      //echo "id_emp: ".$_GET[id_emp]; 
					      //echo "periodo: ".$_GET[periodo];*/
						  $ruslt=mysql_query($sql);
						  $n=mysql_num_rows($ruslt);
						  //echo "<br><br><br><br><br><br>".$sql;




						if($n!=0){
						  	?>
						  	<div class="col-lg-10">
						  		<table class="table table-hover">
							  		<!-- <th class="text-center">#</th> -->
							  		<!-- <th class="text-center">Competencia</th> -->
							  		<th class="text-center">Nivel</th>
							  		<th class="text-center">Descripcion</th>
							  		
							  		

									<?		
									while($read=mysql_fetch_array($ruslt)){

										$sqlE="Select * from empresas where Id='{$read[IdEmpresa]}'";
									  	$resE=mysql_query($sqlE);
									  	$emp=mysql_fetch_array($resE);
									  	
									  
									  	?>
								 		<tr>	
			 								<!-- <td class="col-lg-1 text-center" ><? echo $read['id']; ?></td> -->
			 							    <!-- <td class="col-lg-1 text-center"><? echo $read['codCompetencia']; ?></td> -->
			 							    <td class="col-lg-1 text-center"><? echo $read['Nivel']; ?></td>
			 							    <td class="col-lg-6 text-center"><? echo utf8_encode($read['Descripcion']); ?></td>

			 							    <td class="col-lg-1 text-center">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Editar"><span  class="glyphicon glyphicon-pencil"  style="color:#18B1F9" onclick="editar('<? echo $read[Id] ?>', '<? echo $read['Nivel'];?>','<? echo utf8_encode($read[Descripcion]); ?>');"></span></button>
	 
	 							   	</td> 
	 							    <td class="col-lg-1">
	 							    	<button type="button"  style="border: 0;background-color: transparent;" title="Eliminar"><span  class="glyphicon glyphicon-remove"  style="color:red" onclick="eliminar('<? echo $read[Id] ?>', '<? echo $read[Nivel]; ?>','<? echo utf8_encode($read[Descripcion]); ?>');" ></span></button>
	 
	 							   	</td> 

			 							</tr>
			 						<?
			 						}
			 						?>
		 						</table>
		 					</div>
		 					<?
		 				} 
						else{
						 	?>
						 	<div class="text-center"><h3>No Existen Datos</h3></div>
						 	<?
						}	
						 ?>



