<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Evaluaci&oacute;n</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Estilos CSS vinculados -->
  <link rel="stylesheet" type="text/css" href="../fonts/style.css">
  <link rel="stylesheet" type="text/css" href="../style.css">

  <link href="../CSS/bootstrap.css" rel="stylesheet">
   <script type="text/javascript" src="../js/funciones_Turno.js"></script>
    <script src="../js/11.3/jquery.min.js"></script> 
<!--  <link href="../css/bootstrap.min.css" rel="stylesheet">  
 <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css">
 <link rel="stylesheet" type="text/css" href="../css/DT_bootstrap.css">
   JAVASCRIPT
 <script type="text/javascript" src="../js/funciones.js"></script>

 <script src="../js/bootstrap.min.js"></script>
 <script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
 <script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script> -->

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
		  
		            $.post("Filtro.php",{
		                               combo:'evaluador', // Nombre del combo
		                                id:$("#idEval").get(0).value, // Valor seleccionado
										 emp:$("#idEmp").get(0).value,
										 per:$("#periodo").get(0).value
		                                },function(data){
		                                                $("#"+combos[posicion]).html(data);    //Tomo el resultado de pagina e inserto los datos en el combo indicado
		                                                })

		        }
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
recorrer();
 
}//marcar

function recorrer()
{
   var nombre;
   var acumula=0;
   var promedio=0;
   var cuenta=0;
   
   for( var t=1;t<=8;t++)
   {
     nombre='tabla'+t;

   var tableReg = document.getElementById(nombre);
   for (var i=1;i < document.getElementById(nombre).rows.length ; i++){

          cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
				found = false;
				// Recorremos todas las celdas
				for (var j = 2; j < cellsOfRow.length; j++)
				{
					
				     if(cellsOfRow[j].innerHTML!='')
					{
					  acumula=acumula+i;//obtengo la nota
					  cuenta=cuenta+1;//cuanto las cantidad de notas 
					 
					}	
					
					
				}//for j	
                    
            }//for i
 
   }//for t

 promedio=acumula/cuenta;//obtengo promedio

$("#prome").html('');	
var cadena="Promedio: "+promedio.toFixed(2);
 $("#prome").append(cadena);
  Controlar(promedio*10);	
	
}//recorrer

function Refrescar()
{
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
	 alert('Ud. debe completar todos los datos');
	 return false;
  }
 
   if(formu.evaluado.value== null || formu.evaluado.value=="")
  {
     formu.evaluado.focus;
	 alert('Ud. debe completar todos los datos');
	 return false;
  }
   if(formu.competencia.value== null || formu.competencia.value=="")
  {
     formu.competencia.focus;
	 alert('Ud. debe completar todos los datos');
	 return false;
  }
  return true;
}//validar
 
 function ValidarGlobal(canti,porc,id)
{
 document.getElementById('E').checked=false;
 document.getElementById('MB').checked=false;
 document.getElementById('B').checked=false;

  if($(this).is(":checked")==false)
  {
     document.getElementById(id).checked = false;
  }
  else
  {
      document.getElementById(id).checked = false;
  }
  var mar=document.getElementById('marcado').value;
 
  document.getElementById(mar).checked=true;
  return false;
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
		  return false; 
	  }		
  }
 
  
  return true;
}//validar

function Controlar(porcentaje)
{
  
   if(porcentaje>=95)
   {
      document.getElementById('E').checked = true;
	   document.getElementById('marcado').value = 'E';
     return 'E';
   }
   
   if(porcentaje>=80 && porcentaje<=94)
   {
     document.getElementById('MB').checked = true;
	  document.getElementById('marcado').value = 'MB';
     return 'MB';
   }
   
   if(porcentaje>=50 &&  porcentaje<=79)
   {
    document.getElementById('B').checked = true;
	 document.getElementById('marcado').value = 'B';
     return 'B';
   }
   if(porcentaje>=35 && porcentaje<=49)
   {
     document.getElementById('NM').checked = true;
	  document.getElementById('marcado').value = 'NM';
     return 'NM';
   }
   
   if(porcentaje<=34)
   {
     document.getElementById('NS').checked = true;
	  document.getElementById('marcado').value = 'NS';
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

</head>
<body>


  <?php
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
  <?php 
  }

  //Sacar datos del usuario que ha iniciado sesion
  /*$sql = "SELECT  tx_nombre,tx_apellido,tx_TipoUsuario,id_dni
                FROM T_Usuarios
                LEFT JOIN T_tipoUsuario
                ON T_Usuarios.id_TipoUsuario = T_tipoUsuario.id_TipoUsuario
                WHERE id_dni = '".$_SESSION['uid']."'";   */
$sql="select e.nombre, e.idempresa, m.empresa
      from evaluador e
      inner join empresas m on e.idempresa=m.id
      where e.id = '".mysqli_real_escape_string($conexion, $_SESSION['uid'])."'";				      
  $result = mysqli_query($conexion, $sql);
  $nombreUsuario = "";

  //Formar el nombre completo del usuario
  if ($fila = mysqli_fetch_array($result)) {
    $nombreUsuario = $fila['nombre'];
    $idEmpresa = $fila['idempresa'];
    $empresa = $fila['empresa'];
  }
  //Cerrrar conexion a la BD
  //mysql_close($conexion);


  //Fin Session
  // $nombreUsuario='Gyllote'; 

  
  
if($_POST['boton']=="Guardar")
{
     
      $i++;
      $sqlOs="select id,competencia,titulo from competencias where idempresa='{$idEmpresa}'";
      $ruslt=query($sqlOs);
      foreach ($ruslt->rows as $read) 
      {
         $nivel='nivelAl'.$i;
         $idniv='idnivel'.$i;
         
         if(!empty($_POST[$nivel]))
         {
          
          $sqlIn="insert into movimientos(periodo,idempresa,idevaluador,idevaluado,fechacarga,idcompetencia,competencia,nivelalcanzado,idnivel)
                  value('{$_POST['periodo']}','{$idEmpresa}','{$idEvaluador}','{$_POST['evaluado']}',curdate(),'{$read['id']}','{$read['competencia']}','{$_POST[$nivel]}','{$_POST[$idniv]}')";

 
              if(mysqli_query($conexion, $sqlIn))
              {
               ?>
                  <div class="alert alert-success alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <strong>¡&Eacute;xito!</strong> La actualizaci&oacute;n finaliz&oacute; correctamente.
                   </div>
                  <?php
                   $_POST['competencia']='';
              }
              else
              {
                 $sqlUp="update movimientos set nivelalcanzado='{$_POST[$nivel]}',fechacarga=curdate() where periodo='{$_POST['periodo']}' and idempresa='{$idEmpresa}' and idevaluador='{$idEvaluador}' and idevaluado='{$_POST['evaluado']}' and idcompetencia='{$read['id']}'";
            
                  if(mysqli_query($conexion, $sqlUp))
                  {?>
                     <div class="alert alert-success alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <strong>¡&Eacute;xito!</strong> La actualizaci&oacute;n finaliz&oacute; correctamente.
                   </div>
                   <?php
                  }
                  else
                  {
               ?>
                  <div class="alert alert-warning alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <strong>¡Error!</strong> Hubo un error en la actualizaci&oacute;n.
                   </div>
                  <?php
                  }
              }
              }//if nivel<>''
              $i++;
      }//foreach
    	
		if(!empty($_POST['optradio']))//desempeño global
		{
		   $sqlGlobal="insert into desempenio(periodo,idempresa,idevaluador,idevaluado,porcentaje,resultado,observaciones,fortalezas,debilidades,compromiso,capacitacion) values('{$_POST['periodo']}','{$idEmpresa}','{$idEvaluador}','{$_POST['evaluado']}','{$_POST['porc']}','{$_POST['optradio']}','{$_POST['observaciones']}','{$_POST['fortaleza']}','{$_POST['debilidades']}','{$_POST['compromiso']}','{$_POST['capacitacion']}')";
		  
		   if(!mysqli_query($conexion, $sqlGlobal))
		   {//ya estaba en la tabla
		      $sqlGlobalUp="update desempenio set porcentaje='{$_POST['porc']}',resultado='{$_POST['optradio']}',observaciones='{$_POST['observaciones']}', fortalezas='{$_POST['fortaleza']}',debilidades='{$_POST['debilidades']}',compromiso='{$_POST['compromiso']}',capacitacion='{$_POST['capacitacion']}' where periodo='{$_POST['periodo']}' and idempresa='{$idEmpresa}' and idevaluador='{$idEvaluador}' and idevaluado='{$_POST['evaluado']}'";
			  mysqli_query($conexion, $sqlGlobalUp);
			 
		   }
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
        <?php include("../Menu/Menu_Bootstrap.php"); ?>
      </div><!--FIN ROW-->
    </header><!-- /header -->
  </div>
 
  

  <form class="form-horizontal" data-toggle="validator" id="formu" name="formu" method="post">


    <div class="container">
     

       <div class="row">
        <div id="cabecera_simple"> By Developsys</div>
      </div>

      <div id="segmento" style="margin-left:100px">
        <div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
          <small>Evaluaci&oacute;n del Personal</small>
        </div>
      </div>
  
     
      </br> 

      <div class="form-group  has-feedback ">
          <label class="col-sm-1 control-label" >Empresa</label>
		   <div class="col-sm-3">
		    <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo $empresa ?>" readonly requerid>
             
          
		  </div>
		  <label class="col-sm-1 control-label" >Evaluador</label>
           <div class="col-sm-3">
		    <input type="text" class="form-control" id="evaluador" name="evaluador" value="<?php echo  $nombreUsuario?>" readonly requerid>
		  
        
      </div>  
		   
	  </div>	  
		
		
		 <div class="form-group  has-feedback ">         
	 <label class="col-sm-1 control-label" >Periodo</label>	
		    <div class="col-sm-3">
			 <input type="text" class="form-control" id="periodo" name="periodo" value="<?php echo $_POST['periodo']?>" onChange="CambioPeriodo()"  requerid>
			</div> 	 	  
	  
	 <label class="col-sm-1 control-label" >Evaluado</label>
           <div class="col-sm-3">
        <select  data-size="5" class="form-control"  name="evaluado" id="evaluado" onChange="Refrescar()">
		   <option></option>
		   <?php
		        //$peri= explode('/',$_POST['periodo']);
                //$periHay=$peri[0].$peri[1];
                $periHay=$_POST['periodo'];
		       $sqlOs="SELECT e.id,e.nombre FROM asignados a inner join evaluado e on a.idevaluado=e.id where
a.idevaluador='{$idEvaluador}' and a.idempresa='{$idEmpresa}' and a.periodo='{$periHay}'";
			   $ruslt=query($sqlOs);
			  foreach ($ruslt->rows as $read) 
    	     {
			     if($_POST['evaluado']==$read['id'])
				 {

			   ?>
			      
			      <option value="<?php echo $read['id']?>" selected><?php echo $read['nombre']?></option>
			   <?php
			      }
				  else
				  {
				     ?>
			      
			      <option value="<?php echo $read['id']?>"><?php echo $read['nombre']?></option>
			       <?php
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
    	  
    
<?php
   if($_POST['boton']=="Buscar")
   {
    //$per= explode('/',$_POST['periodo']);
    //$periHay=$per[0].$per[1];
	$sqlDes="Select * from desempenio where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$idEvaluador}' and idempresa='{$idEmpresa}'";
	$consultaDes= mysqli_query($conexion, $sqlDes);
	$nums= mysqli_num_rows($consultaDes);
	
	if($nums>0)
	{
	   $rowDes= mysqli_fetch_array($consultaDes);
	   switch ($rowDes['resultado']) {
				case 'E':
					 $e='checked';
					 $marca='E';
					break;
				case 'MB':
					$mb='checked';
					$marca='MB';
					break;
				case 'B':
					$b='checked';
					$marca='B';
					break;
				case 'NM':
					$nm='checked';
					$marca='NM';
					break;
				case 'NS':
					$ns='checked';
					$marca='NS';
					break;
			}
	   
	}
	
	
	$sqlSum="Select sum(nivelalcanzado) as suma from movimientos where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$idEvaluador}' and idempresa='{$idEmpresa}'";
	$consultaSum= mysqli_query($conexion, $sqlSum);
	$rowSum= mysqli_fetch_array($consultaSum);
	
	$sqlCant="Select count(idevaluador) as cantidad from movimientos where periodo='{$_POST['periodo']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$idEvaluador}' and idempresa='{$idEmpresa}'";
	
	$consultaCant= mysqli_query($conexion, $sqlCant);
	$rowCant= mysqli_fetch_array($consultaCant);
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
	<h2>Competencias <small>Acumulado<?php echo ' '.round($porcentaje,2);?> %  <div id="prome"></div></small></h2>
			  <p>
			    <?php
		 $i=1;
	  $sqlOs="select id,competencia,titulo from competencias where idempresa='{$idEmpresa}'";
	 
			   $ruslt=query($sqlOs);
			  foreach ($ruslt->rows as $read) 
    	     {?>
			  </p>
			  <div class="panel-group" id="accordion">
			    <div class="panel panel-default">
				  <div class="panel-heading">
					<h4 class="panel-title">
					  <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i?>"><?php echo $read['titulo']?></a>
					</h4>
				  </div>
				  <div id="collapse<?php echo $i?>" class="panel-collapse collapse in">
					<div class="panel-body">
					<!--Aqui va el codigo de notas-->
														  <?php	
									 $periHay=$_POST['periodo'];
									 $sqlHay="Select count(nivelalcanzado) as cantidad from movimientos where periodo='{$periHay}' and idcompetencia='{$read['id']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$idEvaluador}' and idempresa='{$idEmpresa}' and (fechacierre<>'' or fechacierre is not null)";
									
									$consultaHay= mysqli_query($conexion, $sqlHay);
									$rowCant= mysqli_fetch_array($consultaHay);
										if($rowCant['cantidad']>0)
										{
											?>
										  <div class="alert alert-warning; alert-dismissable">
											 <button type="button" class="close" data-dismiss="alert">&times;</button>
											 <strong>¡Atenci&oacute;n!</strong> Ya hay un fecha de cierre para la competencia seleccionada.
										 </div>
										  <?php
										  exit;
										}
										
								?>
								
										
										   <table id="tabla<?php echo $i;?>"  class="table table-striped table-condensed table-bordered dt-responsive nowrap table-hover"  						
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
											   $consultComp=mysqli_query($conexion, $sqlComp);
											   $rowComp= mysqli_fetch_array($consultComp);
											  
											   $sql1="SELECT nivel,descripcion,id FROM niveles where codcompetencia='{$rowComp['competencia']}'";  
											  
												
												  $ruslt1=query($sql1);
											  
											   foreach ($ruslt1->rows as $read1) 
											 {           
																			
															  
								
								
											   echo "<tr onclick='Marcar($read1[nivel],$read1[id],$rowComp[competencia],$i)'>";              
											   echo '<td style="font-size:13px">'.$read1['nivel'].'</td>';   		   
											   echo '<td style="font-size:13px">'.$read1['descripcion'].'</td>'; 
											   echo '<td id="'.$read1['id'].'" style="font-size:13px;color: #AD140C;
									font-weight: bold; align:center">'.HayNota($_POST['periodo'],$read['id'],$_POST['evaluado'],$idEvaluador,$idEmpresa,$read1['nivel']).'</td>';    		  
											   
											  
											  
												
											   echo '</tr>';
											  }
											   
											
											
											 ?>
											<tbody>
					  </table>
											
						<input name="nivelAl<?php echo $i; ?>" id="nivelAl<?php echo $i; ?>" type="hidden">
                        <input name="idnivel<?php echo $i; ?>" id="idnivel<?php echo $i; ?>" type="hidden">			  
					</div>
				  </div>
				</div>
			
			<?php
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
      <label><input type="radio" id="E" name="optradio"  value="E" <?php echo $e;?> onClick="ValidarGlobal(<?php echo $cantidades?>,<?php echo round($porcentaje,2)?>,this.id)">Supera ampliamente los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
	<div class="form-group  has-feedback ">   
	
	 <label class="col-sm-5 control-label" >Muy Bueno</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio"  id="MB" name="optradio"  value="MB" <?php echo $mb;?> onClick="ValidarGlobal(<?php echo $cantidades?>,<?php echo round($porcentaje,2)?>,this.id)">Supera los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
	<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Bueno</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="B" name="optradio"  value="B" <?php echo $b;?> onClick="ValidarGlobal(<?php echo $cantidades?>,<?php echo round($porcentaje,2)?>,this.id)">Alcanza los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>			
		
	<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Necesita Mejorar</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="NM" name="optradio"  value="NM" <?php echo $nm;?>  onClick="ValidarGlobal(<?php echo $cantidades?>,<?php echo round($porcentaje,2)?>,this.id)">No alcanza los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>	
		
			<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >No Satisfactorio</label>	
		    <div class="col-sm-4">
			 <div class="radio">
      <label><input type="radio" id="NS" name="optradio"  value="NS" <?php echo $ns;?> onClick="ValidarGlobal(<?php echo $cantidades?>,<?php echo round($porcentaje,2)?>,this.id)">Se aleja visiblimente de los requerimientos del puesto</label>
    </div>
			</div> 	 	  
		</div>
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Observaciones del Evaluador</label>	
		    <div class="col-sm-7">
			 <textarea name="observaciones" cols="50" rows="5"><?php echo $rowDes['observaciones']?></textarea>
			</div> 	 	  
		</div>	
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Fortaleza</label>	
		    <div class="col-sm-7">
			 <textarea name="fortaleza" cols="50" rows="5"><?php echo $rowDes['fortalezas']?></textarea>
			</div> 	 	  
		</div>		
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Debilidades</label>	
		    <div class="col-sm-7">
			 <textarea name="debilidades" cols="50" rows="5"><?php echo $rowDes['debilidades']?></textarea>
			</div> 	 	  
		</div>		
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Compromiso</label>	
		    <div class="col-sm-7">
			 <textarea name="compromiso" cols="50" rows="5"><?php echo $rowDes['compromiso']?></textarea>
			</div> 	 	  
		</div>		
		
		<div class="form-group  has-feedback ">         
	 <label class="col-sm-5 control-label" >Capacitacion</label>	
		    <div class="col-sm-7">
			 <textarea name="capacitacion" cols="50" rows="5"><?php echo $rowDes['capacitacion']?></textarea>
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
	 <?php
	  }//buscar
	 ?>  
    
    </div><!--CONTAINER--> 
	

<input name="idEmp" id="idEmp" type="hidden" value="<?php echo $idEmpresa?>">
<input name="idEval" id="idEval" type="hidden" value="<?php echo $idEvaluador?>">
<input name="porc" id="porc" type="hidden" value="<?php echo round($porcentaje,2)?>">
<input name="marcado" id="marcado" type="hidden" value="<?php echo $marca;?>">

  </form>



       <!-- Js vinculados -->
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.js"></script>
    
    
 

</body>
</html>
<?php
function HayNota($peri,$compe,$evalua,$dor,$empre,$niv)
{
  global $conexion;
  $periodoHay = $_POST['periodo'];
  $sqlHay = "Select nivelalcanzado from movimientos
             where periodo='{$periodoHay}'
               and idcompetencia='{$compe}'
               and idevaluado='{$evalua}'
               and idevaluador='{$dor}'
               and idempresa='{$empre}'";
  $consultaHay = mysqli_query($conexion, $sqlHay);
  $rowHay = mysqli_fetch_array($consultaHay);
  if ($niv == $rowHay['nivelalcanzado']) {
     return $rowHay['nivelalcanzado'].'<img src="../images/nota.png"></img>';
  }
  return '';
}
?>





