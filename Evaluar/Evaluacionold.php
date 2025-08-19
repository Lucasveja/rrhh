<? session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Evaluaci&oacute;n</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Estilos CSS vinculados -->
  <link rel="stylesheet" type="text/css" href="../fonts/style.css">
  <link rel="stylesheet" type="text/css" href="../style.css">

  <link href="../css/bootstrap.css" rel="stylesheet">
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

function Marcar(niv,ids)
{

  
   for (var i=1;i < document.getElementById('tabla').rows.length ; i++){
            // for (var j=0; j<2; j++){
			        //  if(j==2)
					 // {
					   // alert(document.getElementById('tabla').rows[i].cells[2].innerHTML);
					    //document.getElementById('tabla').rows[i].cells[2].innerHTML='0';
					 // }
					 document.getElementById(i).innerHTML='';
                    
             }
    
	 
  document.getElementById(niv).innerHTML=niv+'<img src="../images/nota.png"></img>';
  formu.nivelAl.value=niv;
  formu.idnivel.value=ids;
  
  //document.getElementById("registro").rows[0].cells[0].innerHTML ='cambiar';
 
}//marcar

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

  
  
if($_POST['boton']=="Guardar")
{
   $sqlComp="select id,competencia from competencias where id='{$_POST['competencia']}'";
   $consultComp=mysql_query($sqlComp);
   $rowComp= mysql_fetch_array($consultComp);
   
  //$peri= explode('/',$_POST['periodo']);
  //$periodo=$peri[0].$peri[1];
  // $sqlIn="insert into movimientos(periodo,idempresa,idevaluador,idevaluado,fechacarga,idcompetencia,competencia,nivelalcanzado,idnivel) value('{$periodo}','{$idEmpresa}','{$idEvaluador}','{$_POST['evaluado']}',curdate(),'{$rowComp['id']}','{$rowComp['competencia']}','{$_POST['nivelAl']}','{$_POST['idnivel']}')";
   $sqlIn="insert into movimientos(periodo,idempresa,idevaluador,idevaluado,fechacarga,idcompetencia,competencia,nivelalcanzado,idnivel) value('{$_POST['periodo']}','{$idEmpresa}','{$idEvaluador}','{$_POST['evaluado']}',curdate(),'{$rowComp['id']}','{$rowComp['competencia']}','{$_POST['nivelAl']}','{$_POST['idnivel']}')";
 echo $sqlIn;
 
  if(mysql_query($sqlIn))
  {
   ?>
	  <div class="alert alert-success alert-dismissable">
		 <button type="button" class="close" data-dismiss="alert">&times;</button>
		 <strong>¡&Eacute;xito!</strong> La actualizaci&oacute;n finaliz&oacute; correctamente.
	 </div>
	  <?
	   $_POST['competencia']='';
  }
  else
  {
     $sqlUp="update movimientos set nivelalcanzado='{$_POST['nivelAl']}',fechacarga=curdate() where periodo='{$_POST['periodo']}' and idempresa='{$idEmpresa}' and idevaluador='{$idEvaluador}' and idevaluado='{$_POST['evaluado']}' and idcompetencia='{$rowComp['id']}'";
	
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
  }
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
		    <input type="text" class="form-control" id="empresa" name="empresa" value="<? echo  $fila['empresa']?>" readonly requerid>
             
          
		  </div>
		  <label class="col-sm-1 control-label" >Evaluador</label>
           <div class="col-sm-3">
		    <input type="text" class="form-control" id="evaluador" name="evaluador" value="<? echo  $nombreUsuario?>" readonly requerid>
		  
        
      </div>  
		   
	  </div>	  
		
		
		 <div class="form-group  has-feedback ">         
	 <label class="col-sm-1 control-label" >Periodo</label>	
		    <div class="col-sm-3">
			 <input type="text" class="form-control" id="periodo" name="periodo" value="<? echo $_POST['periodo']?>" onChange="CambioPeriodo()"  requerid>
			</div> 	 	  
	  
	 <label class="col-sm-1 control-label" >Evaluado</label>
           <div class="col-sm-3">
        <select  data-size="5" class="form-control"  name="evaluado" id="evaluado" onChange="Refrescar()">
		   <option></option>
		   <?
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
	 
	</div>	
    	
    <div class="form-group  has-feedback ">
          <label class="col-sm-1 control-label" >Competencias</label>
		   <div class="col-sm-7">
             <select  data-size="5" class="form-control"  name="competencia" id="competencia" >
		   <option></option>
		   <?
		       $sqlOs="select id,competencia,titulo from competencias where idempresa='{$idEmpresa}'";
			   $ruslt=query($sqlOs);
			  foreach ($ruslt->rows as $read) 
    	     {
			     if($_POST['competencia']==$read['id'])
				 {

			   ?>
			      
			      <option value="<? echo $read['id']?>" selected><? echo utf8_encode($read['titulo'])?></option>
			   <?
			      }
				  else
				  {
				     ?>
			      
			      <option value="<? echo $read['id']?>"><? echo utf8_encode($read['titulo'])?></option>
			       <?
				  }
			 }//for
		   ?>
		 
		   </select>
          
		  </div>	
		   <div class="form-group col-sm-2">
    	    <div align="left">
    	      <input name="boton" type="submit" class="btn btn-default "  value="Buscar" onClick="return Validar()">
    	      
  	        </div>
    	  </div>
	  </div>	  	
    	  
    
<?
   if($_POST['boton']=="Buscar")
   {
    //$per= explode('/',$_POST['periodo']);
    //$periHay=$per[0].$per[1];
    $periHay=$_POST['periodo'];
	 $sqlHay="Select count(nivelalcanzado) as cantidad from movimientos where periodo='{$periHay}' and idcompetencia='{$_POST['competencia']}' and idevaluado='{$_POST['evaluado']}' and idevaluador='{$idEvaluador}' and idempresa='{$idEmpresa}' and (fechacierre<>'' or fechacierre is not null)";
    $consultaHay= mysql_query($sqlHay);
	$rowCant= mysql_fetch_array($consultaHay);
		if($rowCant['cantidad']>0)
		{
			?>
		  <div class="alert alert-warning; alert-dismissable">
			 <button type="button" class="close" data-dismiss="alert">&times;</button>
			 <strong>¡Atenci&oacute;n!</strong> Ya hay un fecha de cierre para la competencia seleccionada.
		 </div>
		  <?
		  exit;
		}
		
?>

         <div class="container" align="center">		
           <table id="tabla"  class="table table-striped table-condensed table-bordered dt-responsive nowrap table-hover"  align="center" 

cellspacing="0" width="80%">
             <thead>
              <tr>
			            <th>Nivel</th>
						<th>Descripci&oacute;n</th>
					    <th>Evaluaci&oacuten</th>
              </tr>
             </thead>
             <tbody>
             <?php
			  $sqlComp="select id,competencia from competencias where id='{$_POST['competencia']}'";
			   $consultComp=mysql_query($sqlComp);
			   $rowComp= mysql_fetch_array($consultComp);
			   $sql="SELECT nivel,descripcion,id FROM niveles where codcompetencia='{$rowComp['competencia']}'";  
			  
			    
			      $ruslt=query($sql);
    		  
    		   foreach ($ruslt->rows as $read) 
    	     {           
			   								
				              


               echo "<tr onclick='Marcar($read[nivel],$read[id])'>";              
               echo '<td style="font-size:13px">'.utf8_encode ($read['nivel']).'</td>';   		   
    		   echo '<td style="font-size:13px">'.utf8_encode ($read['descripcion']).'</td>'; 
			   echo '<td id="'.$read['nivel'].'" style="font-size:13px;color: #AD140C;
	font-weight: bold; align:center">'.HayNota($_POST['periodo'],$_POST['competencia'],$_POST['evaluado'],$idEvaluador,$idEmpresa,$read['nivel']).'</td>';    		  
			   
			  
    		  
    		    
               echo '</tr>';
              }
			   
            
			
             ?>
			<tbody>
            </table>
			
      </div>
    
        <script type="text/javascript">
      // For demo to fit into DataTables site builder...
      $('#registro')
        .removeClass( 'display' )
        .addClass('table table-striped table-bordered');
    </script>
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
	
<input name="nivelAl" type="hidden">
<input name="idnivel" type="hidden">
<input name="idEmp" id="idEmp" type="hidden" value="<? echo $idEmpresa?>">
<input name="idEval" id="idEval" type="hidden" value="<? echo $idEvaluador?>">
  </form>



       <!-- Js vinculados -->
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.js"></script>
    
    
 

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
    
    
 


