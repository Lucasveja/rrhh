<?php ob_start(); 

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Generando Pdfs</title>
	<link rel="stylesheet" href="">
	<style>
		.precarga {
    background:transparent url(images/ajaxload.gif) center no-repeat;
 }
	</style>
</head>
<body>
	<?
	  include("../conexionn/conexion.php");
  //include("../funciones/Funciones_Turno.php");

function BuscarNivel($niv,$cc)
{
    $sql="SELECT descripcion FROM niveles where codcompetencia='{$cc}' and nivel='{$niv}' group by descripcion";
	$consulta= mysql_query($sql);
	$row= mysql_fetch_array($consulta);
	return $row['descripcion'];
	
}	


 //$content ="<page backtop='80mm'><page_header></page_header></page>";
               
   require_once('../html2pdf/html2pdf.class.php');         
  $año=date('Y');

  //DATOS DE LA EMPRESA
  $sqlEmp="Select * from empresas Where id={$_GET[empresa]}";
  $resEmp=mysql_query($sqlEmp);
  $empresa=mysql_fetch_array($resEmp);


  


  //DATOS DEL EVALUADOR
  $sqlEv="Select * from evaluador ev inner join posicion p on p.Id=ev.IdPosicion  Where ev.id={$_GET[evaluador]}";
  $resEv=mysql_query($sqlEv);
  $evaluador=mysql_fetch_array($resEv);

  $peri=$_GET["periodo"];
  //$p= explode("/",$peri);
  //$peri=$p[0].$p[1];

  $sqlTodos="select * from movimientos WHERE IdEmpresa='{$_GET[empresa]}' and IdEvaluador='{$_GET[evaluador]}' and Periodo='{$peri}' group by IdEvaluado";

  $resTodos=mysql_query($sqlTodos);
  
//$content="<page>";
  $i=0;
while($roww=mysql_fetch_array($resTodos)){
	 //$n=mysql_num_rows($resTodos);

	//DATOS DEL EVALUADO
	$sqlE="Select * from evaluado ev inner join areas a inner join departamentos d inner join empresas e inner join posicion p on a.Id=ev.IdArea and d.Id=ev.IdDepto and e.Id=ev.IdEmpresa and p.Id=ev.IdPosicion Where ev.id={$roww[idevaluado]}";
	
	$resE=mysql_query($sqlE);
	$evaluado=mysql_fetch_array($resE);

	 $content.="<page>

 			<div align='center'><p align='center'>EVALUACI&Oacute;N DE DESEMPE&Ntilde;O PERSONAL&nbsp;&nbsp;JORNALIZADO-{$peri}</p></div>  
			<br>
 			<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>

 				<tr style='text-align:center'>
 					<td width='750' height='25'  bgcolor='#999999' ><b>Datos del Evaluado</b> </td>
 				</tr>

 			</table> 
 						

 			<table align='left' border='0' width='100%' cellpadding='0' cellspacing='0'>
 				<tr>
 					<td width='20' height='24' style='font-size: 14px'>Nombre:</td>
 					<td width='165'><b>{$evaluado[Nombre]}</b></td>
 					<td width='20' height='24' style='font-size: 14px'>Legajo: </td>
					<td width='165'><b>{$evaluado[Legajo]}</b></td>
 					<td height='24' style='font-size: 14px'>Departamento:</td>
					<td><b>{$evaluado[Depto]}</b></td>
 					
 				</tr>
			  
			  <tr>
				<td width='20' height='24' style='font-size: 14px'>&Aacute;rea:</td>
				<td width='165'><b>{$evaluado[Area]}</b></td>
				<td width='20' height='24' style='font-size: 14px'>Posici&oacute;n:</td>
				<td width='165'><b>{$evaluado[Posicion]}</b></td>
				<td width='20' height='24' style='font-size: 14px'>Empresa:</td>
				<td ><b>{$evaluado[Empresa]}</b></td>
				
			  </tr>
			  </table>

			  <br>
 			<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>

 				<tr style='text-align:center'>
 					<td width='750' height='25'  bgcolor='#999999' ><b>Datos del Evaluador</b> </td>
 				</tr>

 			</table> 
 			

			<table align='left' border='0' width='100%' cellpadding='0' cellspacing='0'>
			 
			  <tr>
 					<td width='20' height='24' style='font-size: 14px'>Nombre:</td>
 					<td width='165'><b>{$evaluador[Nombre]}</b></td>
 					<td width='20' height='24' style='font-size: 14px'>Legajo: </td>
					<td width='175'><b>{$evaluador[Legajo]}</b></td>
					<td width='20' height='24' style='font-size: 14px'>Posicion:</td>
					<td><b>{$evaluador[Posicion]}</b></td>
 					
 				</tr></table>"; 
                     
     
			   $sqlComp="SELECT subtitulo FROM competencias group by subtitulo order by competencia;";				
				$ruslt=query($sqlComp);
			    foreach ($ruslt->rows as $read){

			    	$content.="<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
			    			<tr style='text-align:center'>
			    				<td width='750' height='25'  bgcolor='#CCCCCC' ><b>{$read[subtitulo]}</b> </td>
			    			</tr></table>";
					
				   $content.="<table border='0'  height='60' width='100%' cellpadding='0' cellspacing='0'> 
						  <tr>
							<td width='305' bgcolor='#CCCCCC' align='center'>Competencias</td>
							<td width='35' bgcolor='#CCCCCC' align='center'>Nivel</td>
							<td width='405' bgcolor='#CCCCCC' align='center'>Grados</td>
						  </tr></table>";
 
			
					
						$sqlSub="SELECT competencia,titulo FROM competencias where subtitulo='{$read['subtitulo']}'";				
						$rusltSub=query($sqlSub);
						$content.="<table align='center' border='1' height='60' width='100%' cellpadding='0' cellspacing='0'>";
						foreach ($rusltSub->rows as $competencia) 
						{
							 //DATOS DE MOVIMIENTOS
							  $sqlMov="select NivelAlcanzado from movimientos WHERE IdEmpresa='{$_GET[empresa]}' and IdEvaluador='{$_GET[evaluador]}' and IdEvaluado='{$roww[idevaluado]}' and Periodo='{$peri}' and competencia='{$competencia[competencia]}'";
							  
							  $resMov=mysql_query($sqlMov);
							  $mov=mysql_fetch_array($resMov);

							  switch ($mov[NivelAlcanzado]) {
							  	case '1':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(1,$competencia[competencia])."</td>
										</tr>";
							  		
							  		break;
							  	case '2':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(2,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '3':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(3,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '4':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(4,$competencia[competencia])."</td>
										</tr>";
							  		break;

							  	case '5':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(5,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '6':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(6,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '7':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(7,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '8':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(8,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '9':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(9,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	case '10':
							  		$content.="<tr>
											<td width='300'  align='center' >{$competencia[titulo]}</td>
											<td width='35' align='center'>";


											
												$content.="{$mov[NivelAlcanzado]}<img src='../images/confirmar.png' height='12' width='12'/>";										
											
											$content.="</td>
											<td width='390'>".BuscarNivel(10,$competencia[competencia])."</td>
										</tr>";
							  		break;
							  	
							  
							  }//end switch
							
							
									
						 
						
						}//fin for   
				    $content.="</table><br><br>";

				}//fin for
				 $content.="<strong>IMPORTANTE:</strong>&nbsp;Califique en relación directa a la definición del concepto y no según su equivalencia numérica.<br><br>";

				 $content.="<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
			    			<tr style='text-align:center'>
			    				<td width='750' height='25'  bgcolor='#CCCCCC' ><b>DESEMPE&Ntilde;O GLOBAL</b> </td>
			    			</tr></table>";

			    
			     $content.="<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'><tr style='text-align:center'><td width='750' height='25'><p>Esta evaluación general tiene por objeto resumir la opinión sobre el evaluado. Representa la síntesis conceptual de los rubros evaluados anteriormente.</p></td></tr></table><br>";

			    $sqlDesemp="SELECT * from desempenio where idempresa='{$_GET[empresa]}' and idevaluador='{$_GET[evaluador]}' and periodo='{$peri}' and idevaluado='{$roww[idevaluado]}'";
			     			
			     			$resDesemp=mysql_query($sqlDesemp);
			     			$valor_des=mysql_num_rows($resDesemp);
			     			
			     			if($valor_des==0){

			     				$content.="<br><table border='0'  height='60' width='100%' cellpadding='0' cellspacing='0'> 
									  <tr>
										<td width='305' ><b>Excelente</b></td>
										<td width='35' border='1'></td>
										<td width='35'></td>
										<td width='390'>Supera ampliamente los requirimientos del puesto.</td>
									  </tr>
									  <tr>
										<td width='305' ><b>Muy Bueno</b></td>
										<td width='35' border='1'></td>
										<td width='35'></td>
										<td width='390'>Supera los requirimientos del puesto.</td>
									  </tr>
									  <tr>
										<td width='305' ><b>Bueno</b></td>
										<td width='35' border='1'></td>
										<td width='35'></td>
										<td width='390'>Alcanza los requirimientos del puesto.</td>
									</tr>
									<tr>
										<td width='305' ><b>Necesita Mejorar</b></td>
										<td width='35' border='1'></td>
										<td width='35'></td>
										<td width='390'>No alcanza los requirimientos del puesto.</td>
									</tr>
									<tr>
										<td width='305' ><b>No Satisfactorio</b></td>
										<td width='35' border='1'></td>
										<td width='35'></td>
										<td width='390'>Se aleja visiblemente de los requirimientos del puesto.</td>
									</tr></table>";
							}
								
							else{

					     				$desemp=mysql_fetch_array($resDesemp);
					     				$content.="<br><table border='0'  height='60' width='100%' cellpadding='0' cellspacing='0'>
					     				<tr>
											<td width='305' ><b>Excelente</b></td>
											<td width='35' border='1' align='center' >";
											if($desemp[resultado]=='E'){
												
												$content.="<img src='../images/confirmar.png' height='12' width='12'/>";
											}

											$content.="</td>
											<td width='35'></td>
											<td width='390'>Supera ampliamente los requirimientos del puesto.</td>
										  </tr>
										  <tr>
											<td width='305' ><b>Muy Bueno</b></td>
											<td width='35' border='1' align='center'>";
											if($desemp[resultado]=='MB'){
												
												$content.="<img src='../images/confirmar.png' height='12' width='12'/>";
												

											}

											$content.="</td>
											<td width='35'></td>
											<td width='390'>Supera los requirimientos del puesto.</td>
										  </tr>
										  <tr>
											<td width='305' ><b>Bueno</b></td>
											<td width='35' border='1' align='center'>";
											if($desemp[resultado]=='B'){
												
												$content.="<img src='../images/confirmar.png' height='12' width='12'/>";
												

											}

											$content.="</td>
											<td width='35'></td>
											<td width='390'>Alcanza los requirimientos del puesto.</td>
										</tr>
										<tr>
											<td width='305' ><b>Necesita Mejorar</b></td>
											<td width='35' border='1' align='center'>";
											if($desemp[resultado]=='NM'){
												
												$content.="<img src='../images/confirmar.png' height='12' width='12'/>";
												

											}

											$content.="</td>
											<td width='35'></td>
											<td width='390'>No alcanza los requirimientos del puesto.</td>
										</tr>
										<tr>
											<td width='305' ><b>No Satisfactorio</b></td>
											<td width='35' border='1' align='center'>";
											if($desemp[resultado]=='NS'){
												
												$content.="<img src='../images/confirmar.png' height='12' width='12'/>";
												

											}

											$content.="</td>
											<td width='35'></td>
											<td width='390'>Se aleja visiblemente de los requirimientos del puesto.</td>
										</tr></table>";
							}

							
							 $content.="<br><table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
						    			<tr style='text-align:center'>
						    				<td width='750' height='25'  bgcolor='#CCCCCC' ><b>ENTREVISTA DE MEJORA</b> </td>
						    			</tr></table>";
						    $content.="
						    		<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
						    			<tr>
						    				<td width='750' height='25'><p>Destaque aspectos positivos de su desempeño.(Fortalezas)</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p>".$desemp[fortalezas]."</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr>
						    			</table><br>";
						     $content.="
						    		<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
						    			<tr>
						    				<td width='750' height='25'><p>Destaque aspectos a mejorar de su desempeño.(Debilidades)</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p>".$desemp[debilidades]."</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr>
						    			</table><br>";

						    $content.="
						    		<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
						    			<tr>
						    				<td width='750' height='25'><p>Compromiso de mejoras:</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p>".$desemp[compromiso]."</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr></table><br>";

						   $content.="
						    		<table align='center' border='0' height='60' width='100%' cellpadding='0' cellspacing='0'>
						    			<tr>
						    				<td width='750' height='25'><p>Acciones de capacitacíon sugeridas:</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p>".$desemp[capacitacion]."</p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr>
						    			<tr>
						    				<td width='750' height='25'><p></p></td>
						    			</tr></table><br>";

						   
						    $content.="<br>
						    			<div height='60' width='100%'>
						    				<div align='center'>
						    					<p><b><u>Observaciones del Evaluador:</u></b>
												<br><br>
												<p  style='text-align:justify;margin:0 50px 0 50px'>".$desemp[observaciones]."</p>				
						    					</p>
						    				</div>
						    			</div>";

							 $content.="<br><br><br><br><br><br><br><br><br><br><table border='0'  height='60' width='100%' cellpadding='0' cellspacing='0'> 
									  <tr>
										<td width='190' ></td>
										<td width='150' align='center' style='border-top-color:black; border-top-style:dashed; border-top-width:1px;'><p ><b>Firma Evaluado</b></p></td>
										<td width='35'></td>
										<td width='150' align='center' style=' border-top-color:black; border-top-style:dashed; border-top-width:1px;'><p ><b>Firma Evaluador</b></p></td>
										<td width='35'></td>
										<td width='150' align='center' style=' border-top-color:black; border-top-style:dashed; border-top-width:1px;'><p ><b>Firma de RR.HH.</b></p></td>
										<td width='35'></td>
									  </tr></table><br><b>FECHA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/</b>";

						  $content.="</page>";

$i++;
						   

  

  $name="Informe_Completo_Parte_{$i}";
  $path="../Informes/PDF/PARTES/{$name}.pdf";  
  ob_clean(); // cleaning the buffer before Output()
 // $content = ob_get_clean();
    $html2pdf = new HTML2PDF('P','A4','es',true, 'UTF-8', array(10, 10, 10, 10) /*margenes*/);
    $html2pdf->WriteHTML($content);
    $html2pdf->Output($path,'F');
  
    $content="";
}

 //header('Location: ../Informes/PDF/PARTES/Informe_Completo.pdf');

?>
<div align="center"  style="padding: 300px 0 0 125px" class="precarga">
	<!-- <a href="tp://www.paginaweb.com/> -->
		<img src="images/loader.gif" alt="texto descriptivo" border="0" />
	<!-- </a> -->
</div>



</body>
</html>
<?
if($i<10){
	?>
	<script>setTimeout(function(){window.close(); }, 5000);</script>
	<?
}
if($i>=10 && $i<=30){
	?>
	<script>setTimeout(function(){window.close(); }, 10000);</script>
	<?
}
if($i>=31 && $i<=200){
	?>
	<script>setTimeout(function(){window.close(); }, 90000);</script>
	<?
}


?>
  