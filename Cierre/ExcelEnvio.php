<?php
$a=explode(',',$_GET['nom']);
$nom=$a[0].$_GET['per'];
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=".$nom.".xls");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(0);
?>
<?php
$cn = mysql_connect("localhost","c2271701_datosar","rulageKO64");
mysql_select_db("c2271701_datosar", $cn);




//$peri=explode("/",$_GET['per']);
$per=$_GET['per'];//$peri[0].$peri[1];


$sqlDrop="DROP TABLE IF EXISTS `c2271701_datosar`.`tempenvios`;";
mysql_query($sqlDrop);

$sqlCreate="CREATE TABLE  `c2271701_datosar`.`tempenvios` (  
  `Legajo` varchar(45) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Puesto` varchar(45) ,
  `Contrato` varchar(45) ,
  `Sector` varchar(45) ,
  `Departamento` varchar(45) ,
  `Premio` varchar(45) NOT NULL,
  `Control` varchar(45) NOT NULL,
  `Desempenio` varchar(45) NOT NULL,";
  $sqlCompe="Select * from competencias where idempresa='{$_GET['empr']}'";
  $consultaComp= mysql_query($sqlCompe);
  while($rowComp= mysql_fetch_array($consultaComp))
  {
    $sqlCreate.='`'.$rowComp['titulo'].'` varchar(85) NOT NULL,';
  }
  
  
  $sqlCreate.="PRIMARY KEY (`Legajo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;";

mysql_query($sqlCreate);



$sqlMov="Select legajo,nombre,e.id,depto,posicion,area,m.idevaluado from movimientos m inner join evaluado e on m.idempresa=e.idempresa and m.idevaluado=e.id left join departamentos d on
e.iddepto=d.id left join posicion p on p.id=e.idposicion left join areas a on a.id=e.idarea where m.idempresa='{$_GET['empr']}' and m.periodo='{$per}' and m.idevaluador='{$_GET['eval']}' and m.fechacierre is not null group by idevaluado";

$consultaMov= mysql_query($sqlMov);
while($rowM= mysql_fetch_array($consultaMov))
{
   $sqlCompe="Select competencia from competencias where idempresa='{$_GET['empr']}'";
  $consultaComp= mysql_query($sqlCompe);
  $notas = array();
  while($rowComp= mysql_fetch_array($consultaComp))
  {
     $sqlNota="Select nivelAlcanzado from movimientos where idevaluado='{$rowM['id']}' and competencia='{$rowComp['competencia']}' and idempresa='{$_GET['empr']}' and periodo='{$per}' and idevaluador='{$_GET['eval']}'";
	 
	 $cosnultaNota= mysql_query($sqlNota);
	 $rowNota= mysql_fetch_array($cosnultaNota);
	 if($rowNota['nivelAlcanzado']>0)
	 {
	     array_push($notas, $rowNota['nivelAlcanzado']);
	 }
	 else
	 {
	   array_push($notas, 0);
	 }
	 
	
  }//fin while
  
  $sqlDemp="select descripcion from desempenio d inner join global g on d.resultado=g.codigo where  idempresa='{$_GET['empr']}' and periodo='{$per}' and idevaluador='{$_GET['eval']}' and idevaluado='{$rowM['idevaluado']}'";
   $consultaDesemp= mysql_query($sqlDemp);
  $rowDes= mysql_fetch_array($consultaDesemp);
  
  $sqlIn="insert into tempenvios values('{$rowM['legajo']}','{$rowM['nombre']}','{$rowM['depto']}',' ','{$rowM['posicion']}','{$rowM['area']}',' ',' ','{$rowDes['descripcion']}',";
  for($i=0;$i<count($notas);$i++)
  {
      if(($i+1)<count($notas))
	  {
       $sqlIn.=$notas[$i].',';
	   }
	   else
	   {
	      $sqlIn.=$notas[$i];
	   }
  }//for
  
  $sqlIn.=")";
  
  mysql_query($sqlIn);
  //echo $sqlIn;
    
}//while

$q = "Select * From tempenvios " ;
$rs = mysql_query($q, $cn);
$tot = mysql_num_rows($rs);
$empr='';
?>
<table>
    <thead>
        <tr>
            <td bgcolor="#999999">Id Empleado</td>
            <td bgcolor="#999999">Nombre</td>
            <td bgcolor="#999999">Puesto</td>
            <td bgcolor="#999999">Tipo Contrato</td>
            <td bgcolor="#999999">Sector</td>
            <td bgcolor="#999999">Departamento</td>
			<td bgcolor="#999999">Corresponde Premio</td>
			<td bgcolor="#999999">Control Estado Evaluacion</td>
			<td bgcolor="#999999">Orientacion a Resultados</td>			
			<td bgcolor="#999999">Comunicacion</td>
			<td bgcolor="#999999">Trabajo en equipo</td>
			<td bgcolor="#999999">Poactividad</td>
			<td bgcolor="#999999">Autonomia</td>
			<td bgcolor="#999999">Flexibilidad</td>
			<td bgcolor="#999999">Compromiso con la Calidad</td>
			<td bgcolor="#999999">Gestion en conduccion Segura</td>
			<td bgcolor="#999999">Desempenio</td>



        </tr>
    </thead>
    <tbody>

    <?php while($row = mysql_fetch_array($rs)):?>


    <tr>
	 

        <td bgcolor="<? echo $color?>"><?php echo $row[0]?></td>
        <td bgcolor="<? echo $color?>"><?php echo $row[1]?></td>
		<td><?php echo  $row[2]?></td>
		<td><?php echo  $row[3]?></td>
		<td><?php echo  $row[4]?></td>
		<td><?php echo  $row[5]?></td>
		<td><?php echo  $row[6]?></td>
        <td><?php echo  $row[7]?></td>		
		<td><?php echo  $row[9]?></td>
		<td><?php echo  $row[10]?></td>
		<td><?php echo  $row[11]?></td>
		<td><?php echo  $row[12]?></td>
        <td><?php echo  $row[13]?></td>
        <td><?php echo  $row[14]?></td>
		<td><?php echo  $row[15]?></td>
	   <td><?php echo  $row[16]?></td>
	   <td><?php echo  $row[8]?></td>





     </tr>

       <?




     endwhile;?>

    </tbody>
</table>



 

