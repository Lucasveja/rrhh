<? require("../../xajax/xajax_core/xajax.inc.php");
require_once("../../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../../xajax/');

 function encriptarr($cadena){

     $clave = 3;
     // Cifrado

     $encriptada = "";
     for ($k = 0; $k < strlen($cadena); $k++){

        $car = chr((ord(substr ($cadena, $k ,1)) + $clave) % 256);
        $d='\ ';
        if ($car==trim($d)){
          $car='&';
        }
        $encriptada .= $car;
      }
      return $encriptada ;
 }
 
function desencriptarr($encriptada){
    $clave = 3;
    $desencriptada = "";
    for ($k = 0; $k < strlen($encriptada); $k++){

        $car = chr((ord(substr ($encriptada, $k ,1))));
       if ($car=="&")
        {
          $car='Y';
       }
       else
       {

        $car = chr((ord(substr ($encriptada, $k ,1))
             + (256 - $clave)) % 256);
        }
        $desencriptada .= $car;
    }

    return $desencriptada;
}

function guardar_evaluador($legajo, $nombre, $posicion, $empresa, $clave){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	// $objResp->alert("XAJAX "+$id);
	// $objResp->alert("XAJAX "+$nuevo);
	//$objResp->alert($clave);
	$c=encriptarr($clave);
	//$objResp->alert($c);

	$sql="INSERT INTO evaluador (Legajo, Nombre, IdPosicion, IdEmpresa, clave) VALUES ('{$legajo}','{$nombre}','{$posicion}','{$empresa}','{$c}')";
	//$objResp->alert($sql);
	$res=mysql_query($sql);

	$js="$('#tabla_evaluador').load('tabla.php');$('#Modal_nuevo').modal('hide');";
	$objResp->script($js);



	return $objResp;
}

function eliminar_evaluador($id){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert("XAJAX ".$id);

	$sql="Delete from evaluador Where Id='{$id}'";
	$res=mysql_query($sql);
	$js="$('#tabla_evaluador').load('tabla.php');";
	$objResp->script($js);

	return $objResp;

}

function editar_evaluador($id, $legajo, $nombre, $posicion, $empresa, $clave){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert($clave);

	$c=encriptarr($clave);

	$sql="UPDATE evaluador SET legajo='{$legajo}', Nombre='{$nombre}', IdPosicion='{$posicion}', IdEmpresa='{$empresa}', Clave='{$c}' Where Id='{$id}'";
	//$objResp->alert($sql);
	$res=mysql_query($sql);

	$js="$('#tabla_evaluador').load('tabla.php');$('#Modal_edit').modal('hide');";
	$objResp->script($js);



	return $objResp;
}




$xajax->registerFunction("guardar_evaluador");
$xajax->registerFunction("editar_evaluador");
$xajax->registerFunction("eliminar_evaluador");
$xajax->registerFunction("encriptarr");
$xajax->registerFunction("desencriptarr");


$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Evaluadores</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../../style.css" rel="stylesheet" type="text/css" media="screen" />
    
    <!--Bootstrap-->
    <link href="../../CSS/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../fonts" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../../css/animate.css">
   
   
    
       <!-- JAVASCRIPT -->
  <script type="text/javascript" src="../../js/funciones_Turno.js"></script>
  
  <script type="text/javascript" language="javascript" src="../../js/jquery.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/bootbox.min.js"></script>
  <script type="text/javascript" src="../../js/bootstrap-notify.min.js"></script>

  

  <script>



  	function alerta(titulo, cadena, tipo, icon){

		//alert(titulo+" "+cadena+" "+tipo);
		$.notify({
		// options
		icon: 'glyphicon glyphicon-'+icon,
		title: ' <strong>'+titulo+'</strong><br>',
		message: cadena,
		//url: 'https://github.com/mouse0270/bootstrap-notify',
		target: '_blank'
		},{
		// settings
		element: 'body',
		position: null,
		type: tipo,
		allow_dismiss: false,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "center"
		},
		offset: 20,
		spacing: 10,
		z_index: 5000,
		delay: 3500,
		timer:500,
		url_target: '_blank',
		mouse_over: null,
		animate: {
			// enter: 'animated fadeInDown',
			// exit: 'animated fadeOutUp'

			// enter: 'animated bounceInDown',
			// exit: 'animated bounceOutUp'

			// enter: 'animated bounceIn',
			// exit: 'animated bounceOut'
			
			// enter: 'animated fadeInRight',
			// exit: 'animated fadeOutRight'

			enter: 'animated flipInY',
			exit: 'animated flipOutX'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
		template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
			'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
			'<span data-notify="icon" style="font-size:18px;"></span> ' +
			'<span data-notify="title">{1}</span> ' +
			'<span data-notify="message">{2}</span>' +
			/*'<div class="progress" data-notify="progressbar">' +
				'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
			'</div>' +*/
			'<a href="{3}" target="{4}" data-notify="url"></a>' +
		'</div>' 
		});
	}

	

	function editar(id, legajo, nombre, posicion, empresa, clave){

		$('#Modal_edit').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('oculto').value=id;
		document.getElementById('Edit_legajo').value=legajo;
		document.getElementById('Edit_nombre').value=nombre;
		document.getElementById('Edit_posicion').value=posicion;
		document.getElementById('Edit_empresa').value=empresa;
		document.getElementById('Edit_clave').value=clave;
		//alert(emp);
		

		
	}

	function eliminar(id, nom){

		bootbox.confirm({
	    title: "Eliminar ",
	    message: "Eliminar Evaluador <strong>"+nom+"</strong> ?",
	    closeButton: false,
	    buttons: {
	        cancel: {
	            label: 'Cancelar'
	        },
	        confirm: {
	            label: 'Confirmar'
	        }
	    },
	    callback: function (result) {
	        //alert(result);
	        if(result==true){
	        	xajax_eliminar_evaluador(id);
	        }
	    }
		});
	}

	function guardar_evaluador(legajo,nombre,posicion,empresa,clave){
		
		if(legajo=="" || nombre=="" || posicion=="-1" || empresa=="-1" || clave==""){

			
			alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			xajax_guardar_evaluador(legajo,nombre,posicion,empresa,clave);
			
		}
		

	}

	function editar_evaluador(id,legajo,nombre,posicion,empresa,clave){
		// alert(posicion);
		// 	alert(empresa);
		
		if(legajo=="" || nombre=="" || posicion=="-1" || empresa=="-1" || clave==""){

			
			alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			//alert(posicion);
			//alert(empresa);
			xajax_editar_evaluador(id,legajo,nombre,posicion,empresa,clave);
			
		}
		

	}

	function nuevo_evaluador(){

		$('#Modal_nuevo').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('Nuevo_legajo').value="";
		document.getElementById('Nuevo_nombre').value="";
		document.getElementById('Nuevo_posicion').value="-1";
		document.getElementById('Nuevo_empresa').value="-1";
		document.getElementById('Nuevo_clave').value="";
		
		
	}
  </script>
  <? $xajax->printJavascript();?>
</head>
<body>

	<?
	include("../../conexionn/conexion.php");
	include("../../Funciones_Turno.php");

	//SESSION
	//Inicializar una sesion de PHP

	//Validar que el usuario este logueado y exista un UID
	if (($_SESSION['autenticado'] == "") || $_SESSION['uid']==""){

		//echo "<br><br><br><br><br>SESSION: ".$_SESSION['autenticado'];

		//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la 
		//pantalla de login, enviando un codigo de error
		?>
		<form name="formulario" method="post" action="../../index.php">
			<input type="hidden" name="msg_error" value="2">
		</form>

		<script type="text/javascript"> 
			document.formulario.submit();
		</script>
	<?
	}
	else{
		//echo "SESSION: ".$_SESSION['autenticado']."<br>UID: ".$_SESSION['uid'];
	}

	//Sacar datos del usuario que ha iniciado sesion
	$sql = "SELECT  tx_nombre,tx_apellido,tx_TipoUsuario,id_dni
        FROM T_Usuarios
        LEFT JOIN T_tipoUsuario
        ON T_Usuarios.id_TipoUsuario = T_tipoUsuario.id_TipoUsuario
        WHERE id_dni = '".$_SESSION['uid']."'";
    $result     =mysql_query($sql); 

    $nombreUsuario = "";

    //Formar el nombre completo del usuario
    if( $fila = mysql_fetch_array($result) ){
    	$nombreUsuario = $fila['tx_nombre']." ".$fila['tx_apellido'];
    	$_POST[nombreUsuario]=$nombreUsuario;
    }
    //Cerrrar conexion a la BD
    //mysql_close($conexion);

    //FIN SESION

      ?>

    <div class="container-fluid">
		<header id="header" >
			<div class="row">
				<? include("../../Menu/Menu_Bootstrap.php"); ?>
			</div><!--FIN ROW-->
		</header><!-- header -->
	</div>

	<div class="container" >
	
   
		<div class="row">
	    	<div id="cabecera_simple"></div>
	    </div>

	    <div id="segmento" style="margin-left:100px">
	    	<div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
	    		Evaluadores
	    	</div>
	    </div>

		<div class="text-right">
			Operador: 
			<input type="text" style="background-color: transparent;border:0; font-weight: bold" name="nombreUsuario" id="nombreUsuario" value="<? echo $_POST[nombreUsuario]; ?>" disabled />
		</div>
		<br>
		<br>
		<form class="form-horizontal" role="form"  id="formu" name="formu" method="post">

			<!--COMIENZO DE CONTENIDO-->
			<div class="row">
				<div class="form-group text-center">											
					
					<button type="button" class="btn btn-primary" name="Nuevo" id="Nuevo" onClick="nuevo_evaluador();" title="Nuevo Evaluador">
					
					  Nuevo <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</button>

					
				</div>

			</div>

			<br>
			<br>


			<div class="table-responsive" id="tabla_evaluador" style="display:block">
				
			</div>





		</form>

	</div><!--CONTAINER-->

<!-- Modal Nuevo -->

<div class="modal fade" id="Modal_nuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Nuevo Evaluador</h4>
      </div>
      <div class="modal-body">

       	<div class="container">
			<form class="form-horizontal" data-toggle="validator" id="formNuevo" name="formuNuevo" method="post">	
						        
			   
		           <div class="form-group  has-feedback">
		              <label class="col-sm-2 control-label" >Legajo</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_legajo" id="Nuevo_legajo" type="text" class="form-control"  value="" placeholder="Legajo">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Nombre</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_nombre" id="Nuevo_nombre" type="text" class="form-control"  value="" placeholder="nombre">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Posicion</label>
		              <div class="col-sm-3">
		    
		                <select name="Nuevo_posicion" id="Nuevo_posicion" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from posicion";
							$res=mysql_query($sql);
							while($pos=mysql_fetch_array($res)){
								?>
								<option value="<? echo $pos[Id];  ?>"><? echo $pos[Posicion] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>

		            <div class="clearfix"></div>
		            <br>

		              <label class="col-sm-2 control-label" >Empresa</label>
		              <div class="col-sm-3">
		    
		                <select name="Nuevo_empresa" id="Nuevo_empresa" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from empresas";
							$res=mysql_query($sql);
							while($pos=mysql_fetch_array($res)){
								?>
								<option value="<? echo $pos[Id];  ?>"><? echo $pos[Empresa] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>
		            
		            <div class="clearfix"></div>
		            <br>

		            <label class="col-sm-2 control-label" >Clave</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_clave" id="Nuevo_clave" type="text" class="form-control"  value="" placeholder="Clave">
		             
		               
		              </div>
		              <div class="clearfix"></div>
		              <br>
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onClick="return guardar_evaluador(Nuevo_legajo.value,Nuevo_nombre.value,Nuevo_posicion.value,Nuevo_empresa.value,Nuevo_clave.value);">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar -->

<div class="modal fade" id="Modal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Editar Evaluador</h4>
      </div>
      <div class="modal-body">

       	<div class="container">
			<form class="form-horizontal" data-toggle="validator" id="formNuevo" name="formuNuevo" method="post">	
						        
			   
		           <div class="form-group  has-feedback">
		              <label class="col-sm-2 control-label" >Legajo</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_legajo" id="Edit_legajo" type="text" class="form-control"  value="" placeholder="Legajo">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Nombre</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_nombre" id="Edit_nombre" type="text" class="form-control"  value="" placeholder="nombre">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Posicion</label>
		              <div class="col-sm-3">
		    
		                <select name="Edit_posicion" id="Edit_posicion" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from posicion";
							$res=mysql_query($sql);
							while($pos=mysql_fetch_array($res)){
								?>
								<option value="<? echo $pos[Id];  ?>"><? echo $pos[Posicion] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>

		            <div class="clearfix"></div>
		            <br>

		              <label class="col-sm-2 control-label" >Empresa</label>
		              <div class="col-sm-3">
		    
		                <select name="Edit_empresa" id="Edit_empresa" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from empresas";
							$res=mysql_query($sql);
							while($emp=mysql_fetch_array($res)){
								?>
								<option value="<? echo $emp[Id];  ?>"><? echo $emp[Empresa] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>
		            
		            <div class="clearfix"></div>
		            <br>

		            <label class="col-sm-2 control-label" >Clave</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_clave" id="Edit_clave" type="text" class="form-control"  value="" placeholder="Clave">
		             
		               
		              </div>
		              <div class="clearfix"></div>
		              <br>
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
      	<input type="hidden" name="oculto" id="oculto">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onClick="return editar_evaluador(oculto.value,Edit_legajo.value,Edit_nombre.value,Edit_posicion.value,Edit_empresa.value,Edit_clave.value);">Editar</button>
      </div>
    </div>
  </div>
</div>



</body>

<script>
	$('#tabla_evaluador').load('tabla.php');
</script>
</html>