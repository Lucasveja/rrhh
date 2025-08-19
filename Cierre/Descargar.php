<? session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Descargar</title>
  <meta charset="utf-8">
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

function Enviar(per,emp,eva,nombre)
{


  ventMET = window.open("ExcelEnvio.php?per="+per+"&eval="+eva+"&empr="+emp+"&nom="+nombre,"ventSint","width=840, height=650, scrollbars=yes, menubar=no, location=no, center=yes, help=no,resizable=no");

}
 

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

  
  



  
  ?>

  <!--          COMIENZO  DE     ELEMENTOS                  -->
  <div class="container-fluid">
    <header id="header" class="">
      <div class="row">
        <?include("../Menu/Menu_Bootstrap.php");?>
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
          <small>Descargar Archivo</small>
        </div>
      </div>
  
     
      </br> 

     <?
	      $sqlCierre="SELECT periodo,empresa,nombre,m.idempresa,idevaluador FROM movimientos m inner join empresas e on e.id=m.idempresa inner join evaluador v on m.idevaluador=v.id
where m.fechacierre is not null group by m.periodo,m.idempresa,m.idevaluador ";
		  $resCierre=mysql_query($sqlCierre);
		  while($cierre=mysql_fetch_array($resCierre))
		  {
		     ?>
			  <li> <a href="#" onClick="Enviar(<? echo "'".$cierre['periodo']."'"?>,<? echo $cierre['idempresa']?>,<? echo $cierre['idevaluador']?>,<? echo "'".$cierre['nombre']."'"?>)">Empresa <strong><? echo $cierre['empresa']?></strong> Evaluador <strong><? echo $cierre['nombre']?></strong> Periodo <strong><? echo $cierre['periodo']?></strong></a></li>
			 <? 
			
			}				
	 
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

 


