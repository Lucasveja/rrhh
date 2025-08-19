<? require("../../xajax/xajax_core/xajax.inc.php");
require_once("../../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../../xajax/');

function guardar_evaluado($legajo, $nombre, $area, $depto, $posicion, $empresa){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	// $objResp->alert("XAJAX "+$id);
	// $objResp->alert("XAJAX "+$nuevo);

	$sql="INSERT INTO evaluado (Legajo, Nombre, IdArea, IdDepto, IdPosicion, IdEmpresa) VALUES ('{$legajo}','{$nombre}', '{$area}','{$depto}','{$posicion}','{$empresa}')";
	$res=mysql_query($sql);
	//$objResp->alert("XAJAX "+$sql);

	$js="$('#tabla_evaluado').load('tabla.php');$('#Modal_nuevo').modal('hide');";
	$objResp->script($js);



	return $objResp;
}

function eliminar_evaluado($id){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert("XAJAX ".$id);

	$sql="Delete from evaluado Where id='{$id}'";
	$res=mysql_query($sql);
	$js="$('#tabla_evaluado').load('tabla.php');";
	$objResp->script($js);

	return $objResp;

}

function editar_evaluado($id, $legajo, $nombre, $area, $depto, $posicion, $empresa){

	$objResp=new xajaxResponse();
	//$conn = new conexionBD (); //Genera una nueva coneccion
	include("../../conexionn/conexion.php");
	$sql="UPDATE evaluado SET Legajo='{$legajo}', Nombre='{$nombre}', IdArea='{$area}', IdDepto='{$depto}', IdPosicion='{$posicion}', IdEmpresa='{$empresa}' Where id='{$id}'";
	$objResp->alert($sql);
	$res=mysql_query($sql);

	$js="$('#tabla_evaluado').load('tabla.php');$('#Modal_edit').modal('hide');";
	$objResp->script($js);



	return $objResp;
}




$xajax->registerFunction("guardar_evaluado");
$xajax->registerFunction("editar_evaluado");
$xajax->registerFunction("eliminar_evaluado");


//$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Evaluados</title>

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

	

	function editar(id, legajo,  nombre, area, depto, posicion, empresa){

		$('#Modal_edit').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('oculto').value=id;
		document.getElementById('Edit_legajo').value=legajo;
		document.getElementById('Edit_nombre').value=nombre;
		document.getElementById('Edit_area').value=area;
		document.getElementById('Edit_depto').value=depto;
		document.getElementById('Edit_posicion').value=posicion;
		document.getElementById('Edit_empresa').value=empresa;
		
		//alert(emp);
		

		
	}

	function eliminar(id, nom){

		bootbox.confirm({
	    title: "Eliminar ",
	    message: "Eliminar Evaluado <strong>"+nom+"</strong> ?",
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
	        	xajax_eliminar_evaluado(id);
	        }
	    }
		});
	}

	function guardar_evaluado(legajo,nombre,area,depto,posicion,empresa){
		
		if(legajo=="" || nombre=="" || area=="-1" || depto=="-1" || posicion=="-1" || empresa=="-1"){

			
			alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
			return false;

		}
		else{
		//alert(legajo);
		//alert(nombre);
		//alert(area);
		//alert(depto);
		//alert(posicion);
		//alert(empresa);
			xajax_guardar_evaluado(legajo,nombre, area, depto, posicion,empresa);
			
		}
		

	}

	function editar_evaluado(id,legajo,nombre, area, depto, posicion,empresa){
		// alert(posicion);
		// 	alert(empresa);
		
		if(legajo=="" || nombre=="" || area=="-1" || depto=="-1" || posicion=="-1" || empresa=="-1"){

			
			alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			//alert(posicion);
			
			
			parametros={
                              "id":id,
                              "legajo":legajo,
                              "nombre":nombre,
                              "area":area,
							  "depto":depto,  
							  "posicion":posicion,  
							  "empresa":empresa,                              
                            }

							fetch("editar.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json" // Indica que se está enviando JSON
    },
    body: JSON.stringify(parametros) // Convertir el objeto a JSON
})
.then(response => {
    if (!response.ok) {
        throw new Error(`Error en la solicitud: ${response.statusText}`);
    }
    return response.text(); // O `response.json()` si el servidor devuelve JSON
})
.then(respons => {
    alert("Por favor, refrescar la página para visualizar los cambios.");
})
.catch(error => {
    console.error("Hubo un problema con la solicitud:", error);
});
			//xajax_editar_evaluado(id,legajo,nombre,area,depto,posicion,empresa);
			
		}
		

	}

	function nuevo_evaluado(){

		$('#Modal_nuevo').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('Nuevo_legajo').value="";
		document.getElementById('Nuevo_nombre').value="";
		document.getElementById('Nuevo_area').value="-1";
		document.getElementById('Nuevo_depto').value="-1";
		document.getElementById('Nuevo_posicion').value="-1";
		document.getElementById('Nuevo_empresa').value="-1";
		
		
		
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
        FROM t_usuarios
        LEFT JOIN t_tipousuario
        ON t_usuarios.id_TipoUsuario = t_tipousuario.id_TipoUsuario
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
	    		Evaluados
	    	</div>
	    </div>

		<div class="text-right">
			Operador Tonchote: 
			<input type="text" style="background-color: transparent;border:0; font-weight: bold" name="nombreUsuario" id="nombreUsuario" value="<? echo $_POST[nombreUsuario]; ?>" disabled />
		</div>
		<br>
		<br>
		<form class="form-horizontal" role="form"  id="formu" name="formu" method="post">

			<!--COMIENZO DE CONTENIDO-->
			<div class="row">
				<div class="form-group text-center">											
					
					<button type="button" class="btn btn-primary" name="Nuevo" id="Nuevo" onClick="nuevo_evaluado();" title="Nuevo Evaluado">
					
					  Nuevo <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</button>

					
				</div>

			</div>

			<br>
			<br>


			<div class="table-responsive" id="tabla_evaluado" style="display:block">
				
			</div>





		</form>

	</div><!--CONTAINER-->

<!-- Modal Nuevo -->

<div class="modal fade" id="Modal_nuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Nuevo Evaluado</h4>
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

		              <label class="col-sm-2 control-label" >Area</label>
		              <div class="col-sm-3">
		    
		                <select name="Nuevo_area" id="Nuevo_area" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from areas";
							$res=mysql_query($sql);
							while($area=mysql_fetch_array($res)){
								?>
								<option value="<? echo $area[Id];  ?>"><? echo $area[Area] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>

		            <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Departamento</label>
		              <div class="col-sm-3">
		    
		                <select name="Nuevo_depto" id="Nuevo_depto" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from departamentos";
							$res=mysql_query($sql);
							while($depto=mysql_fetch_array($res)){
								?>
								<option value="<? echo $depto[Id];  ?>"><? echo $depto[Depto] ?></option>
		
								<?

							}
							?>
						</select>
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
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onClick="return guardar_evaluado(Nuevo_legajo.value,Nuevo_nombre.value, Nuevo_area.value, Nuevo_depto.value, Nuevo_posicion.value,Nuevo_empresa.value);">Guardar</button>
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
        <h4 class="modal-title" id="myModalLabel">Editar Evaluado</h4>
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

		              <label class="col-sm-2 control-label" >Area</label>
		              <div class="col-sm-3">
		    
		                <select name="Edit_area" id="Edit_area" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from areas";
							$res=mysql_query($sql);
							while($area=mysql_fetch_array($res)){
								?>
								<option value="<? echo $area[Id];  ?>"><? echo $area[Area] ?></option>
		
								<?

							}
							?>
						</select>
		            </div>

		            <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Departamento</label>
		              <div class="col-sm-3">
		    
		                <select name="Edit_depto" id="Edit_depto" class="form-control">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="Select * from departamentos";
							$res=mysql_query($sql);
							while($depto=mysql_fetch_array($res)){
								?>
								<option value="<? echo $depto[Id];  ?>"><? echo $depto[Depto] ?></option>
		
								<?

							}
							?>
						</select>
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

		            
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
      	<input type="hidden" name="oculto" id="oculto">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onClick="return editar_evaluado(oculto.value,Edit_legajo.value,Edit_nombre.value, Edit_area.value, Edit_depto.value, Edit_posicion.value,Edit_empresa.value);">Editar</button>
      </div>
    </div>
  </div>
</div>



</body>

<script>
	$('#tabla_evaluado').load('tabla.php');
</script>
</html>