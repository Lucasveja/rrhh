<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Informes Ambulatorios</title>


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
<?
  include("../conexionn/conexion.php");
  include("../funciones/Funciones_Turno.php");

/****SeSIION****/

//Inicializar una sesion de PHP

session_start(); 
//Validar que el usuario este logueado y exista un UID


 //$content ="<page backtop='80mm'><page_header></page_header></page>";
               
            


 $content ="<page backtop='80mm'><page_header>
        <table width='330' border='0' cellpadding='0' cellspacing='0'>            
<tr>
				<td colspan='2'><p align='right'>EVALUACI&Oacute;N DE DESEMPE&Ntilde;O PERSONAL NORMAL</p>
			    <p align='right'>JORNALIZADO-2017 asd </p></td>
				<td width='213'>&nbsp;</td>
		    </tr>
			
			  <tr>
				<td height='25' colspan='3' bgcolor='#999999'><b>Datos del Evaluado</b> </td>
		    </tr>
			  <tr>
				<td width='142' height='24'><span style='font-size: 14px'>Nombre</span></td>
				<td width='448'>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  
			  <tr>
				<td height='24' style='font-size: 14px'>Legajo</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>&Aacute;rea</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Departamento</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Posici&oacute;n</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Empresa</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='27' colspan='3' bgcolor='#999999'><b>Datos del Evaluador</b> </td>
</tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Apellido y Nombre </td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Legajo</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td height='24' style='font-size: 14px'>Posici&oacute;n</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr></table></page_header>";
     //           $content .="</table>
     // </page_header>";         
     
			   $sqlComp="SELECT subtitulo FROM competencias group by subtitulo";				
				$ruslt=query($sqlComp);
			    foreach ($ruslt->rows as $read) 
				{
				
				   $content.=" <table width='900' border='0'>
						  <tr>
							<td colspan='2' bgcolor='#CCCCCC'>{$read[subtitulo]}</td>
						  </tr>
 
						  <tr>
							<td width='350' bgcolor='#CCCCCC'>Competencias</td>
							<td width='450' bgcolor='#CCCCCC'><div align='left'>Nivel <span class='Estilo5'>---------------------</span>Grados </div></td>
						  </tr>";
 
			
					
						$sqlSub="SELECT * FROM competencias where subtitulo='{$read['subtitulo']}'";				
						$rusltSub=query($sqlSub);
						foreach ($rusltSub->rows as $competencia) 
						{
							
						  $content.=" <tr>
								<td>{$competencia[titulo]}</td>
								<td><div align='left'><table width='210' border='1'>
								  <tr>
									<td width='5' bordercolor='#000000'><div align='left' width='30'>1</div></td>
									<td width='180' rowspan='2'><div width='330'>".BuscarNivel(1,$competencia[competencia])."</div></td>
								  </tr>
								  <tr bordercolor='#000000'>
									<td><div align='left' width='30'>2</div></td>
									</tr>
								  <tr>
									<td bordercolor='#000000'><div align='left' width='30'>3</div></td>
									<td rowspan='2'><div width='330'>".BuscarNivel(3,$competencia[competencia])."</div></td>
								  </tr>
								  <tr bordercolor='#000000'>
									<td><div align='left' width='30'>4</div></td>
									</tr>
								  <tr>
									<td bordercolor='#000000'><div align='left' width='30'>5</div></td>
									<td rowspan='2'><div width='330'>".BuscarNivel(5,$competencia[competencia])."</div></td>
								  </tr>
								  <tr bordercolor='#000000'>
									<td><div align='left' width='30'>6</div></td>
									</tr>
								  <tr>
									<td bordercolor='#000000'><div align='left' width='30'>7</div></td>
									<td rowspan='2'><div width='330' >".BuscarNivel(7,$competencia[competencia])."</div></td>
								  </tr>
								  <tr bordercolor='#000000'>
									<td><div align='left' width='30'>8</div></td>
									</tr>
								  <tr>
									<td bordercolor='#000000'><div align='left' width='30'>9</div></td>
									<td rowspan='2'><div width='330'>".BuscarNivel(9,$competencia[competencia])."</div></td>
								  </tr>
								  <tr bordercolor='#000000'>
									<td><div align='left' width='30'>10</div></td>
									</tr>
								</table></div></td>
							  </tr>";
						
						}//fin for   
				    $content.="</table>";
				}//fin for
			  
			 
			  $content.="  
            </page>";        
            
       
            
         

       
      

?>


</body>
</html>
<?
  

  require_once('../html2pdf/html2pdf.class.php');

  $name="Informe";
  $path="../Informes/PDF/{$name}.pdf";  
  ob_clean(); // cleaning the buffer before Output()
    $html2pdf = new HTML2PDF('P','A4','es', array(10, 10, 10, 10) /*m?rgenes*/);
    $html2pdf->WriteHTML($content);
    $html2pdf->Output($path,'F');
    header('Location:'.$path);

function BuscarNivel($niv,$cc)
{
    $sql="SELECT descripcion FROM niveles where codcompetencia='{$cc}' and nivel='{$niv}' group by descripcion";
	$consulta= mysql_query($sql);
	$row= mysql_fetch_array($consulta);
	return $row['descripcion'];
	
}	
  ?>