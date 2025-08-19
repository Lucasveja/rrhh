<? require("../xajax/xajax_core/xajax.inc.php");
require_once("../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');



function ver_tabla($id_emp,$per){

	$objResp=new xajaxResponse();

	//$p=str_replace(array('','/'), '', $per);//quitamos el " / "
	//$objResp->alert($id_emp);
	//$objResp->alert($per);
	
	$js="$('#tabla_asignar').load('tabla.php?id_emp={$id_emp}&periodo={$per}');";
	//$objResp->alert($js);
	$objResp->script($js);



	return $objResp;

}

function asignar($formu){

	extract($formu);

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert($evaluador);
	//$objResp->alert($evaluado);


	return $objResp;
}





$xajax->registerFunction("ver_tabla");
$xajax->registerFunction("asignar");


//$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Evaluaci&oacute;n</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <link href="../style.css" rel="stylesheet" type="text/css" media="screen" />
    
    <!--Bootstrap-->
    <link href="../CSS/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
    
    <link rel="stylesheet" type="text/css" href="../css/animate.css">
   
   
    
       <!-- JAVASCRIPT -->
  <script type="text/javascript" src="../js/funciones_Turno.js"></script>
  
  <script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/bootbox.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap-notify.min.js"></script>

  <script>
function LlamarJquery(){
    	

		   // Vector para saber cu?l es el siguiente combo a llenar
			
		    var combos = new Array();
		    combos['evaluador'] = "evaluado";
		 
			
		    // Tomo el nombre del combo al que se le a dado el clic por ejemplo: pa?s
		    posicion ="evaluador"; //$(this).attr("name");
			
		    // Tomo el valor de la opci?n seleccionada
		    valor = 1;//$(this).val();
		     // Evalu?  que si es pa?s y el valor es 0, vaci? los combos de estado y ciudad
		    if(posicion == 'evaluador' && valor==0){
		        $("#evaluado").html('    <option value="0" selected="selected">----------------</option>')
		      
		    }else{
		   //En caso contrario agregado el letreo de cargando a el combo siguiente
		   // Ejemplo: Si seleccione pa?s voy a tener que el siguiente seg?n mi vector combos es: estado  por qu?  combos [pa?s] = estado
		       
		        $("#"+combos[posicion]).html('<option selected="selected" value="0">Cargando...</option>')
		       // Verificamos si el valor seleccionado es diferente de 0 y si el combo es diferente de ciudad, esto 

//porque no tendr?a caso hacer la consulta a ciudad porque no existe un combo dependiente de este 
		        
		        if(valor!="0" || posicion !='ciudad'){
		        // Llamamos a pagina de combos.php donde ejecuto las consultas para llenar los combos
		  
		            $.post("FiltroMod.php",{
		                               combo:'evaluador', // Nombre del combo
		                                id:$("#evaluador").get(0).value, // Valor seleccionado
										 emp:$("#idEmp").get(0).value,
										 per:$("#periodo").get(0).value
		                                },function(data){
		                                                $("#"+combos[posicion]).html(data);    //Tomo el resultado de pagina e inserto los datos en el combo indicado
		                                                //alert(data);
		                                                 document.getElementById("evaluado").selectedIndex = "0";
		                                                })

		        }
		        //document.getElementById('id_evaluado').value=document.getElementById('evaluado').value

		        
		    }
	}
	



  </script>
  
 <script>
 var patronPeriodo = new Array(2,4) 
function mascaraPeriodo(d,sep,pat,nums){
	if(d.valant != d.value){
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
				      
					     val += sep + val3[q]
						
					}
			}
		}
		
		d.value = val
		d.valant = val
		}
}//mascara

 function recargar(id)
 {
     formu.oculto.value=id;
	 formu.submit();
 }//recargar
 
 function Completar(obj,cant)
 { 
   if(isNaN(obj.value))
   {
      alert("Ingrese valor v?lido")
	  obj.value='';
	  return false;
   }
     while (obj.value.length<cant)
       obj.value = '0'+obj.value;
	   
	
 }

function Marcar(niv,ids,compe,tbl)
{

  var nombre;
 
  nombre='tabla'+tbl;
 
   var tableReg = document.getElementById(nombre);
   for (var i=1;i < document.getElementById(nombre).rows.length ; i++){
            // for (var j=0; j<2; j++){
			        //  if(j==2)
					 // {
					   // alert(document.getElementById('tabla').rows[i].cells[2].innerHTML);
					    //document.getElementById('tabla').rows[i].cells[2].innerHTML='0';
					 // }
					 
					 cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
				found = false;
				// Recorremos todas las celdas
				for (var j = 0; j < cellsOfRow.length; j++)
				{
					//compareWith = cellsOfRow[j].innerHTML.toLowerCase();
					// Buscamos el texto en el contenido de la celda
					if(j==2)
					{
					  cellsOfRow[j].innerHTML='';
					}
					
					
				}
					
                    
            }
   
	 var indice;
	 indice=ids;
	
  document.getElementById(indice).innerHTML=niv+'<img src="../images/nota.png"></img>';
   nivell='nivelAl'+tbl;
   idnivell='idnivel'+tbl;
  
   document.getElementById(nivell).value=niv;
   document.getElementById(idnivell).value=ids;
 // formu.nivelAl.value=niv;
  //formu.idnivel.value=ids;
  
  //document.getElementById("registro").rows[0].cells[0].innerHTML ='cambiar';
 
}//marcar

function Refrescar()
{	combo_evaluado=document.getElementById('evaluado').value;
	  //alert(combo_evaluado);
	document.getElementById('id_evaluado').value=combo_evaluado;  
	  xajax_asignar(xajax.getFormValues('formu'));
      for (var i=1;i < document.getElementById('tabla').rows.length ; i++){
      	document.getElementById(i).innerHTML='';
      }


}//refrescar
 
 function CambioPeriodo()
{
   
   document.getElementById("evaluado").selectedIndex = "0";
   LlamarJquery();
}

function Validar()
{

  if(formu.periodo.value== null || formu.periodo.value=="")
  {
     formu.periodo.focus;
	 alert('Ud. debe completar todos los datos (periodo)');
	 return false;
  }
 
   if(formu.evaluado.value== null || formu.evaluado.value=="")
  {
     formu.evaluado.focus;
	 alert('Ud. debe completar todos los datos (evaluado)');
	 return false;
  }
   if(formu.competencia.value== null || formu.competencia.value=="")
  {
     formu.competencia.focus;
	 alert('Ud. debe completar todos los datos (competencias)');
	 return false;
  }
  return true;
}//validar
 
 function ValidarGlobal(canti,porc,id)
{
 
  if(canti!=8)
  {
     
	 alert('Existen Competencias Pendientes de Calificar');
	 document.getElementById(id).checked = false;
	 return false;
  }
  else
  {
	 if(id!=Controlar(porc))
	  {
	      alert('El resultado de la evaluaciones no es coherente con la elección de su desempeño global');  
	  }		
  }
 
  
  return true;
}//validar

function Controlar(porcentaje)
{
   if(porcentaje>=95)
   {
     return 'E';
   }
   
   if(porcentaje>=80 && porcentaje<=94)
   {
     return 'MB';
   }
   
   if(porcentaje>=50 &&  porcentaje<=79)
   {
     return 'B';
   }
   if(porcentaje>=35 && porcentaje<=49)
   {
     return 'NM';
   }
   
   if(porcentaje<=34)
   {
     return 'NS';
   }
   
}//control

 </script>

<style>
td:hover {
            background:red
        }
        
        tr td:hover {
            background:blue;
        }
        
        tr:hover td {
            background: #F6AC89;
        }

</style>
  <? $xajax->printJavascript();?>
</head>
<body>


  <?
  include("../conexionn/conexion.php");
 // include("../funciones/Funciones_Turno.php");

  //SeSSIon

 //Inicializar una sesion de PHP
 //Validar que el usuario este logueado y exista un UID

 if ( ! (($_SESSION['autenticado'] == 'SI' || $_SESSION['Evaluador'] == 'SI' )&& isset($_SESSION['uid'])) ){
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la 
    //pantalla de login, enviando un codigo de error
  ?>
    <form name="formulario" method="post" action="../index.php">
      <input type="hidden" name="msg_error" value="2">
    </form>
    <script type="text/javascript"> 
      document.formulario.submit();
    </script>
  <? 
  }

  //Sacar datos del usuario que ha iniciado sesion
  /*$sql = "SELECT  tx_nombre,tx_apellido,tx_TipoUsuario,id_dni
                FROM T_Usuarios
                LEFT JOIN T_tipoUsuario
                ON T_Usuarios.id_TipoUsuario = T_tipoUsuario.id_TipoUsuario
                WHERE id_dni = '".$_SESSION['uid']."'";   */
$sql="select nombre,idempresa,empresa from evaluador e inner join empresas m on e.idempresa=m.id where e.id= '".$_SESSION['uid']."'";				      
  $result     =mysql_query($sql);
  $nombreUsuario = "";

  //Formar el nombre completo del usuario
  if( $fila = mysql_fetch_array($result) )
    $nombreUsuario =$fila['nombre']; //$fila['tx_nombre']." ".$fila['tx_apellido'];
    $idEmpresa= $fila['idempresa'];
	$idEvaluador=$_SESSION['uid'];
  //Cerrrar conexion a la BD
  //mysql_close($conexion);


  //Fin Session
  // $nombreUsuario='Gyllote';
  
		   	$sqlEmp="SELECT * from empresas where Id=1";
		   	$res=mysql_query($sqlEmp);
		   	$emp=mysql_fetch_array($res);

		   	$sqlPeriodo="SELECT * from periodos where PeriodoActivo=1";
		   	$resPeriodo=mysql_query($sqlPeriodo);
		   	$periodoActivo=mysql_fetch_array($resPeriodo);


  
  
if($_POST['boton']=="Guardar")
{
     
      $i++;
      $sqlOs="select id,competencia,titulo from competencias where idempresa='{$emp[Id]}'";
	  $ruslt=query($sqlOs);
	  foreach ($ruslt->rows as $read) 
	  {
	     /*$sqlIn="insert into movimientos(periodo,idempresa,idevaluador,idevaluado,fechacarga,idcompetencia,competencia,nivelalcanzado,idnivel) value('{$_POST['periodo']}','{$idEmpresa}','{$idEvaluador}','{$_POST['evaluado']}',curdate(),'{$rowComp['id']}','{$rowComp['competencia']}','{$_POST['nivelAl']}','{$_POST['idnivel']}')";*/
		 $nivel='nivelAl'.$i;
		 $idniv='idnivel'.$i;
		 
		 if($_POST[$nivel]<>'')
		 {
		 
		  $sqlUp="update movimientos set nivelalcanzado='{$_POST[$nivel]}',fechacarga=curdate() where periodo='{$_POST['periodo']}' and idempresa='{$emp[Id]}' and idevaluador='{$_POST[idEvall]}' and idevaluado='{$_POST['id_evaluado']}' and idcompetencia='{$read['id']}'";
				// echo "UPDATE_ ". $sqlUp;
			
				  if(mysql_query($sqlUp))
				  {?>
					 <div class="alert alert-success alert-dismissable">
					 <button type="button" class="close" data-dismiss="alert">&times;</button>
					 <strong>¡&Eacute;xito!</strong> La actualizaci&oacute;n finaliz&oacute; correctamente.
				 </div>
				 <?
				  }
				  else
				  {
			   ?>
				  <div class="alert alert-warning alert-dismissable">
					 <button type="button" class="close" data-dismiss="alert">&times;</button>
					 <strong>¡Error!</strong> Hubo un error en la actualizaci&oacute;n.
				 </div>
				  <?
				  }
			  }//if nivel<>''
			  $i++;
	  }//foreach
    	
		if($_POST['optradio'])//desempeño global
		{
		   
		      $sqlGlobalUp="update desempenio set porcentaje='{$_POST['porc']}',resultado='{$_POST['optradio']}',observaciones='{$_POST['observaciones']}' where periodo='{$_POST['periodo']}' and idempresa='{$emp[Id]}' and idevaluador='{$_POST[idEvall]}' and idevaluado='{$_POST['id_evaluado']}'";
			  mysql_query($sqlGlobalUp);
		   
		}     
  /* $sqlComp="select id,competencia from competencias where id='{$_POST['competencia']}'";
   $consultComp=mysql_query($sqlComp);
   $rowComp= mysql_fetch_array($consultComp);*/
   
  
   
}//guardar


  
  ?>

  <!--          COMIENZO  DE     ELEMENTOS                  -->
  <div class="container-fluid">
    <header id="header" class="">
      <div class="row">
        <? include("../Menu/Menu_Bootstrap.php"); ?>
      </div><!--FIN ROW-->
    </header><!-- /header -->
  </div>
 
  

  <form class="form-horizontal" data-toggle="validator" id="formu" name="formu" method="post">


    <div class="container">
     

       <div class="row">
        <div id="cabecera_simple"></div>
      </div>

      <div id="segmento" style="margin-left:100px">
        <div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
          <small>Modificar Evaluaci&oacute;n del Personal</small>
        </div>
      </div>
  
     
      </br> 

      <div class="form-group  has-feedback ">
          <label class="col-sm-1 control-label" >Empresa</label>
		   <div class="col-sm-3">
			
		    <input type="text" class="form-control" id="empresa" name="empresa" value="<? echo $emp[Empresa] ?>" readonly requerid>
             
          
		  </div>
		  <label class="col-sm-1 control-label" >Evaluador</label>
           <div class="col-sm-3">
           	<select name="evaluador" id="evaluador" class="form-control" onchange="LlamarJquery(); ">
           		<option value="-1">Seleccionar..</option>
           	<?
		   	$sqlEv="SELECT * from evaluador";
		   	$resEv=mysql_query($sqlEv);		   
		   	while($evaluador=mysql_fetch_array($resEv)){
		   		?>
		   		<option value="<? echo $evaluador[Id] ?>"><? echo $evaluador[Nombre] ?></option>
		   		<?
		   	}
		   	?>
		   		</select>
		    <!-- <input type="text" class="form-control" id="evaluador" name="evaluador" value="<? echo  $nombreUsuario?>" readonly requerid> -->
		  
        
      </div>  
		   
	  </div>	  
		
		
		 <div class="form-group  has-feedback ">         
	 <label class="col-sm-1 control-label" >Periodo</label>	
		    <div class="col-sm-3">
			 <input type="text" class="form-control" id="periodo" name="periodo" value="<? echo $periodoActivo[Periodo]?>" onChange="CambioPeriodo()"  requerid>
			</div> 	 	  
	  
	 <label class="col-sm-1 control-label" >Evaluado</label>
           <div class="col-sm-3">
        <select  data-size="5" class="form-control"  name="evaluado" id="evaluado" onchange="Refrescar()">
		   <option value="-1">Seleccionar..</option>
		   <?
		        //$peri= explode('/',$_POST['periodo']);
                //$periHay=$peri[0].$peri[1];
                $periHay=$_POST['periodo'];
		       $sqlOs="SELECT e.id,e.nombre FROM asignados a inner join evaluado e on a.idevaluado=e.id where
a.idevaluador='{$_POST[evaluador]}' and a.idempresa='{$emp[Empresa]}' and a.periodo='{$periHay}'";
echo $sqlOs;

			   $ruslt=query($sqlOs);
			  foreach ($ruslt->rows as $read) 
    	     {
			     if($_POST['evaluado']==$read['id'])
				 {

			   ?>
			      
			      <option value="<? echo $read['id']?>" selected><? echo $read['nombre']?></option>
			   <?
			      }
				  else
				  {
				     ?>
			      
			      <option value="<? echo $read['id']?>"><? echo $read['nombre']?></option>
			       <?
				  }
			 }//for
		   ?>
		 
		   </select>
      </div>  
	    <input name="boton" type="submit" class="btn btn-default "  value="Buscar" onClick="return Validar()">
	</div>	
    	
    <div class="form-group  has-feedback ">
       
		   	
		   <div class="form-group col-sm-2">
    	    <div align="left">
    	     
    	      
  	        </div>
    	  </div>
	  </div>	  	
    	  
    
<?
   if($_POST['boton']=="Buscar")
   {
    //$per= explode('/',$_POST['periodo']);
    //$periHay=$per[0].$per[1];

	$sqlDes="Select * from desempenio where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$_POST[evaluador]}' and idempresa='{$emp[Id]}'";
	$consultaDes= mysql_query($sqlDes);
	$nums= mysql_num_rows($consultaDes);
	
	if($nums>0)
	{
	   $rowDes= mysql_fetch_array($consultaDes);
	   switch ($rowDes['resultado']) {
				case 'E':
					 $e='checked';
					break;
				case 'MB':
					$mb='checked';
					break;
				case 'B':
					$b='checked';
					break;
				case 'NM':
					$nm='checked';
					break;
				case 'NS':
					$ns='checked';
					break;
			}
	   
	}
	
	
	$sqlSum="Select sum(nivelalcanzado) as suma from movimientos where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$_POST[evaluador]}' and idempresa='{$emp[Id]}'";
	$consultaSum= mysql_query($sqlSum);
	$rowSum= mysql_fetch_array($consultaSum);
	
	$sqlCant="Select count(idevaluador) as cantidad from movimientos where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$_POST[evaluador]}' and idempresa='{$emp[Id]}'";
	
	$consultaCant= mysql_query($sqlCant);
	$rowCant= mysql_fetch_array($consultaCant);
	if($rowCant['cantidad']>0)
	{
		$porcentaje= ($rowSum['suma']*100)/($rowCant['cantidad']*10);
		$cantidades=$rowCant['cantidad'];
	}
	else
	{
	    $porcentaje=0;
		$cantidades=0;
	}
	?>
	<h2>Competencias <small>Acumulado<? echo ' '.round($porcentaje,2);?> % </small></h2>
			  <p>
			    <?
		 $i=1;
	  $sqlOs="select id,competencia,titulo from competencias where idempresa='{$emp[Id]}'";
	 
			   $ruslt=query($sqlOs);
			  foreach ($ruslt->rows as $read) 
    	     {?>
			  </p>
			  <div class="panel-group" id="accordion">
			    <div class="panel panel-default">
				  <div class="panel-heading">
					<h4 class="panel-title">
					  <a data-toggle="collapse" data-parent="#accordion" href="#collapse<? echo $i?>"><? echo $read['titulo']?></a>
					</h4>
				  </div>
				  <div id="collapse<? echo $i?>" class="panel-collapse collapse in">
					<div class="panel-body">
					<!--Aqui va el codigo de notas-->
														  <?	
									 $periHay=$_POST['periodo'];
									 $sqlHay="Select count(nivelalcanzado) as cantidad from movimientos where periodo='{$periHay}' and idcompetencia='{$read['id']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$_POST[evaluador]}' and idempresa='{$emp[Id]}' and (fechacierre<>'' or fechacierre is not null)";
									
									$consultaHay= mysql_query($sqlHay);
									$rowCant= mysql_fetch_array($consultaHay);
										/*if($rowCant['cantidad']>0)
										{
											?>
										  <div class="alert alert-warning; alert-dismissable">
											 <button type="button" class="close" data-dismiss="alert">&times;</button>
											 <strong>¡Atenci&oacute;n!</strong> Ya hay un fecha de cierre para la competencia seleccionada.
										 </div>
										  <?
										  exit;
										}*/
										
								?>
								
										
										   <table id="tabla<? echo $i;?>"  class="table table-striped table-condensed table-bordered dt-responsive nowrap table-hover"  						
								cellspacing="0" width="50%">
											 <thead>
											  <tr>
														<th>Nivel</th>
														<th>Descripci&oacute;n</th>
														<th>Evaluaci&oacuten</th>
											  </tr>
											 </thead>
											 <tbody>
											 <?php
											  $sqlComp="select id,competencia from competencias where id='{$read['id']}'";
											   $consultComp=mysql_query($sqlComp);
											   $rowComp= mysql_fetch_array($consultComp);
											  
											   $sql1="SELECT nivel,descripcion,id FROM niveles where codcompetencia='{$rowComp['competencia']}'";  
											  
												
												  $ruslt1=query($sql1);
											  
											   foreach ($ruslt1->rows as $read1) 
											 {           
																			
															  
								
								
											   echo "<tr onclick='Marcar($read1[nivel],$read1[id],$rowComp[competencia],$i)'>";              
											   echo '<td style="font-size:13px">'.$read1['nivel'].'</td>';   		   
											   echo '<td style="font-size:13px">'.$read1['descripcion'].'</td>'; 
											   echo '<td id="'.$read1['id'].'" style="font-size:13px;color: #AD140C;
									font-weight: bold; align:center">'.HayNota($_POST['periodo'],$read['id'],$_POST['evaluado'],$_POST[evaluador],$emp[Id],$read1['nivel']).'</td>';    		  
											   
											  
											  
												
											   echo '</tr>';
											  }
											   
											
											
											 ?>
											<tbody>
					  </table>
											
						<input name="nivelAl<? echo $i; ?>" id="nivelAl<? echo $i; ?>" type="hidden">
                        <input name="idnivel<? echo $i; ?>" id="idnivel<? echo $i; ?>" type="hidden">			  
					</div>
				  </div>
				</div>
			
			<?
			$i++;
			}//fin for competancias
			
	?>
	</div>
	
	 
  
	
    
    

    
        <script type="text/javascript">
      // For demo to fit into DataTables site builder...
      $('#registro')
        .removeClass( 'display' )
        .addClass('table table-striped table-bordered');
    </script>
	<div id="recuadro"  style="border:groove">
	  <h3 align="center">Desempeño Global </h3>
	   <div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Excelente</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="E" name="optradio" value="E" <? echo $e;?> onClick="ValidarGlobal(<? echo $cantidades?>,<? echo round($porcentaje,2)?>,this.id)">Supera ampliamente los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
	<div class="form-group  has-feedback ">   
	
	 <label class="col-sm-5 control-label" >Muy Bueno</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio"  id="MB" name="optradio" value="MB" <? echo $mb;?> onClick="ValidarGlobal(<? echo $cantidades?>,<? echo round($porcentaje,2)?>,this.id)">Supera los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
	<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Bueno</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="B" name="optradio"  value="B" <? echo $b;?> onClick="ValidarGlobal(<? echo $cantidades?>,<? echo round($porcentaje,2)?>,this.id)">Alcanza los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>			
		
	<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Necesita Mejorar</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="NM" name="optradio" value="NM" <? echo $nm;?> onClick="ValidarGlobal(<? echo $cantidades?>,<? echo round($porcentaje,2)?>,this.id)">No alcanza los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
			<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >No Satisfactorio</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="NS" name="optradio" value="NS" <? echo $ns;?> onClick="ValidarGlobal(<? echo $cantidades?>,<? echo round($porcentaje,2)?>,this.id)">Se aleja visiblimente de los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Observaciones del Evaluador</label>	
		    <div class="col-sm-7">
			 <textarea name="observaciones" cols="50" rows="5"><? echo $rowDes['observaciones']?></textarea>
			</div> 	 	  
		</div>			
	  
   </div> 
   
   
   
   
	
	</br>
        <div class="form-group">
      <div class="col-xs-offset-3 col-xs-9">
            <input type="submit" class="btn btn-primary" value="Guardar" name="boton" onClick="return Validar()" >
			<input type="submit" class="btn btn-alert" value="Cancelar">
			<input type="reset" class="btn btn-warning" value="Salir" onClick="window.location.assign('/apps/rrhh/inicio.php')">
      </div>
    </div>
	 <?
	  }//buscar
	 ?>  
    
    </div><!--CONTAINER--> 
	

<input name="idEmp" id="idEmp" type="hidden" value="<? echo $emp[Id] ?>">
<input name="idEval" id="idEval" type="hidden" value="<? echo $idEvaluador ?>">
<input name="idEvall" id="idEvall" type="hidden" value="<? echo $_POST[idEvall] ?>">
<input name="id_evaluado" id="id_evaluado" type="hidden" value="<? echo $_POST[id_evaluado] ?>">
<input name="porc" id="porc" type="hidden" value="<? echo round($porcentaje,2)?>">

  </form>



 
    
    
 

</body>
</html>
<?
function HayNota($peri,$compe,$evalua,$dor,$empre,$niv)
{
  // $peri= explode('/',$_POST['periodo']);
  //$periodoHay=$peri[0].$peri[1];
  $periodoHay=$_POST['periodo'];
  $sqlHay="Select nivelalcanzado from movimientos where periodo='{$periodoHay}' and idcompetencia='{$compe}' and idevaluado='{$evalua}' and idevaluador='{$dor}' and idempresa='{$empre}'";

   $consultaHay= mysql_query($sqlHay);
  $rowHay= mysql_fetch_array($consultaHay);
  if($niv==$rowHay['nivelalcanzado'])
  {
     return $rowHay['nivelalcanzado'].'<img src="../images/nota.png"></img>';
   }
  return '';
}

?>
    
    
 


