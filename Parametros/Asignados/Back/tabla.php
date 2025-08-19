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
					      $sql="SELECT * from evaluado Where IdEmpresa='{$_GET[id_emp]}'";
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




						if($n!=0){
						  	?>
						  	<div class="col-lg-10">
						  		<table class="table table-hover">
							  		<!-- <th class="text-center">#</th> -->
							  		<th class="text-center">Legajo</th>
							  		<th class="text-center">Nombre</th>
							  		<th class="text-center">Area</th>
							  		<th class="text-center">Departamento</th>
							  		<th class="text-center">Posicion</th>
							  		<th class="text-center">Empresa</th>
							  		<th class="text-center">Evaluador</th>
							  		

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

									  	$sqlAsig="Select * from asignados Where idEmpresa='{$_GET[id_emp]}' and IdEvaluado='{$read[id]}' and Periodo='{$_GET[periodo]}'";
									  	//echo $sqlAsig."<br>";
									  	$resAsig=mysql_query($sqlAsig);
									  	$nAsig=mysql_num_rows($resAsig);
									  	//echo " ".$nAsig."<br>";
									  	$asig=mysql_fetch_array($resAsig);
									  	?>
								 		<tr>	
			 								<!-- <td class="col-lg-1 text-center" ><? echo $read['id']; ?></td> -->
			 							    <td class="col-lg-1 text-center"><? echo utf8_encode($read['Legajo']); ?></td>
			 							    <td class="col-lg-3 text-center"><? echo utf8_encode($read['Nombre']); ?></td>
			 							    <td class="col-lg-1 text-center"><? echo utf8_encode($area['Area']); ?></td>
			 							    <td class="col-lg-1 text-center"><? echo utf8_encode($depto['Depto']); ?></td>
			 							    <td class="col-lg-1 text-center"><? echo utf8_encode($pos['Posicion']); ?></td>
			 							    <td class="col-lg-2 text-center"><? echo utf8_encode($emp['Empresa']); ?></td>
			 							    
			 							    <td class="col-lg-3 text-center">
			 							    	<select name="evaluador" id="evaluador" class="form-control" onchange="return asignar('<? echo $_GET[id_emp] ?>',this.value,'<? echo $read[id] ?>','<? echo $_GET[periodo] ?>','<? if($nAsig!=0){echo $asig[id];}else{echo $nAsig;} ?>')">
			 							    		<?
			 							    		if($nAsig!=0){ ?>
				 							    		<option value="-1">Seleccionar..</option>

				 							    		<?
				 							    		
				 							    		$sqlEV="SELECT * from evaluador";
				 							    		$resEV=mysql_query($sqlEV);
				 							    		while($ev=mysql_fetch_array($resEV)){
				 							    			if($asig[IdEvaluador]==$ev[Id]){
				 							    			?>
				 							    				<option value="<? echo $ev[Id] ?>" Selected><? echo $ev[Nombre] ?></option>
				 							    			<?
				 							    			}
				 							    			else{ ?>
				 							    				<option value="<? echo $ev[Id] ?>"><? echo $ev[Nombre] ?></option>

				 							    			<?
				 							    			}
				 							    		}
				 							    	
			 							    		}
			 							    		else{

				 							    		?>
				 							    		<option value="-1">Seleccionar..</option>
				 							    		<?
				 							    		$sqlEV="SELECT * from evaluador Where IdEmpresa='{$_GET[id_emp]}'";
				 							    		$resEV=mysql_query($sqlEV);
				 							    		while($ev=mysql_fetch_array($resEV)){
				 							    			?>
				 							    			<option value="<? echo $ev[Id] ?>"><? echo $ev[Nombre] ?></option>
				 							    			<?
				 							    		}
			 							    		}	
			 							    		?>
			 							    	</select>	 
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



