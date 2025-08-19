<? require("../../xajax/xajax_core/xajax.inc.php");
require_once("../../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../../xajax/');



function ver_tabla($id_emp){

	$objResp=new xajaxResponse();
	
	$js="$('#tabla_competencia').load('tablaC.php?id_emp={$id_emp}');";
	$objResp->alert($js);
	$objResp->script($js);



	return $objResp;

}

function editar_competencia($id, $comp, $titulo, $sub, $emp){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert("entro XAJAX");

	

	$sql="UPDATE competencias SET competencia='{$comp}', titulo='{$titulo}', subtitulo='{$sub}' Where id='{$id}'";
	//$objResp->alert($sql);
	$res=mysql_query($sql);

	$js="$('#tabla_competencia').load('tablaC.php?id_emp={$emp}');$('#Modal_edit').modal('hide');";
	$objResp->script($js);



	return $objResp;
}

function eliminar_competencia($id, $emp){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert("XAJAX ".$id);

	$sql="Delete from competencias Where id='{$id}'";
	$res=mysql_query($sql);
	$js="$('#tabla_competencia').load('tablaC.php?id_emp={$emp}');";
	$objResp->script($js);

	return $objResp;

}

function guardar_competencia($comp, $titulo, $sub, $emp){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	// $objResp->alert("XAJAX "+$id);
	
	$sql="INSERT INTO competencias (competencia, titulo, subtitulo, IdEmpresa) VALUES ('{$comp}','{$titulo}','{$sub}','{$emp}')";
	$objResp->alert($sql);
	$res=mysql_query($sql);

	$js="$('#tabla_competencia').load('tablaC.php?id_emp={$emp}');$('#Modal_nuevo').modal('hide');";
	$objResp->script($js);



	return $objResp;
}






$xajax->registerFunction("ver_tabla");
$xajax->registerFunction("editar_competencia");
$xajax->registerFunction("eliminar_competencia");
$xajax->registerFunction("guardar_competencia");



//$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Competencias</title>

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



	function ver_tabla(id){
		//alert(p);
		if( id=="-1"){
				alerta("CUIDADO", "Debe Seleccionar una <strong>Empresa</strong>.", "info", "warning-sign");
				return false;
			}
			else{
				//$('#tabla_competencia').load('tablaC.php?id_emp=1');
				xajax_ver_tabla(id);

				}				
		

	}

	function cancelar(){
		document.getElementById('empresa').value="-1";
		//document.getElementById('periodo').value="";
		document.getElementById('tabla_competencia').style.display="none";
	}

	function eliminar(id, titulo, sub){

		empresa=document.getElementById('empresa').value;

		bootbox.confirm({
	    title: "Eliminar ",
	    message: "Eliminar la Competencia: <strong>"+titulo+": "+sub+"</strong> ?",
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
	        	xajax_eliminar_competencia(id,empresa);
	        }
	    }
		});
	}

	function editar_competencia(id,competencia,titulo,sub){
		// alert(posicion);
		//alert(empresa);
		//alert(id);
		//alert(competencia);
		//alert(titulo);
		//alert(sub);
		empresa=document.getElementById('empresa').value;
		//alert(empresa);
		if(competencia=="" || titulo=="" || sub=="" || empresa=="-1"){

			
			alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			//alert(posicion);
			//alert(empresa);
			xajax_editar_competencia(id,competencia,titulo,sub, empresa);
			
		}
		

	}

	function editar(id, competencia, titulo, subtitulo){

		//alert("entro editar JS");
		//alert(id);
		//alert(competencia);
		//alert(titulo);
		//alert(subtitulo);

		$('#Modal_edit').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('oculto').value=id;
		document.getElementById('Edit_competencia').value=competencia;
		document.getElementById('Edit_titulo').value=titulo;
		document.getElementById('Edit_subtitulo').value=subtitulo;
		
		
		//alert(emp);
		

		
	}

	function guardar_competencia(comp, titulo, sub){

		empresa=document.getElementById('empresa').value;
		
		if(empresa=="-1"){
			alerta("CUIDADO", "Debe Seleccionar una <strong>Empresa</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			if(comp=="" || titulo=="" || sub==""){

				
				alerta("CUIDADO", "Hay Campos <strong>Vacios</strong>.", "info", "warning-sign");
				return false;

			}
			else{
				xajax_guardar_competencia(comp, titulo, sub, empresa);
				
			}
		}
		

	}
	function nueva_competencia(){

		empresa=document.getElementById('empresa').value;
		
		if(empresa=="-1"){
			alerta("CUIDADO", "Debe Seleccionar una <strong>Empresa</strong>.", "info", "warning-sign");
			return false;

		}
		else{
			$('#Modal_nuevo').modal(
			{keyboard:false,
				backdrop: 'static',
				show: true}
			);
			document.getElementById('Nuevo_competencia').value="";
			document.getElementById('Nuevo_titulo').value="";
			document.getElementById('Nuevo_subtitulo').value="";
		}
		
		
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
	    		Competencias
	    	</div>
	    </div>

		<div class="text-right">
			Operador tinchote: 
			<input type="text" style="background-color: transparent;border:0; font-weight: bold" name="nombreUsuario" id="nombreUsuario" value="<? echo $_POST[nombreUsuario]; ?>" disabled />
		</div>
		<br>
		<br>
		<form class="form-horizontal" role="form"  id="formu" name="formu" method="post">

			<!--COMIENZO DE CONTENIDO-->
			<div class="row">

				<div class="form-group  has-feedback">					

					<label class="col-sm-2 control-label" >Empresa</label>
					<div class="col-sm-3">
						<select name="empresa" id="empresa" class="form-control" onchange="//ver_tabla(this.value);">
							<option value="-1">Seleccionar..</option>
							<?
							$sql="SELECT * from empresas";
							$res=mysql_query($sql);
							while($row=mysql_fetch_array($res)){

								?>
									<option value="<? echo $row[Id] ?>"><? echo $row[Empresa] ?></option>						
								<?

							}
							?>
						</select>
					</div>


					<div class="col-sm-1">
						<input type="button" class="btn btn-primary" name="Ver" id="Ver" value="Ver" onclick="ver_tabla(empresa.value);">
					</div>

					<div class="col-sm-1">

						<button type="button" class="btn btn-primary" name="Nuevo" id="Nuevo" onclick="nueva_competencia();" title="Nueva Competencia">
							Nuevo <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						</button>
					</div>

					<div class="col-sm-1">
						<input type="submit" reset class="btn btn-default" name="Cancelar" id="Cancelar" value="Cancelar" onclick="cancelar();">
					</div>
				</div>
				

				
		              

			
			</div>

			<br>
			<br>


			<div class="table-responsive" id="tabla_competencia" style="display:block">
				
			</div>





		</form>

	</div><!--CONTAINER-->




<!-- Modal Nuevo -->

<div class="modal fade" id="Modal_nuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Nueva Competencia</h4>
      </div>
      <div class="modal-body">

       	<div class="container">
			<form class="form-horizontal" data-toggle="validator" id="formNuevo" name="formuNuevo" method="post">	
						        
			   
		           <div class="form-group  has-feedback">
		              <label class="col-sm-2 control-label" >Competencia</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_competencia" id="Nuevo_competencia" type="text" class="form-control"  value="" placeholder="Competencia">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Titul</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_titulo" id="Nuevo_titulo" type="text" class="form-control"  value="" placeholder="Titulo">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>


		           
		            <label class="col-sm-2 control-label" >Subtitulo</label>
		              <div class="col-sm-3">
		    
		                <input name="Nuevo_subtitulo" id="Nuevo_subtitulo" type="text" class="form-control"  value="" placeholder="Subtitulo">
		             
		               
		              </div>
		              <div class="clearfix"></div>
		              <br>
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onclick="return guardar_competencia(Nuevo_competencia.value,Nuevo_titulo.value,Nuevo_subtitulo.value);">Guardar</button>
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
        <h4 class="modal-title" id="myModalLabel">Editar Competencia</h4>
      </div>
      <div class="modal-body">

       	<div class="container">
			<form class="form-horizontal" data-toggle="validator" id="formEdit" name="formuEdit" method="post">	
						        
			   
		           <div class="form-group  has-feedback">
		              <label class="col-sm-2 control-label" >Competencia</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_competencia" id="Edit_competencia" type="text" class="form-control"  value="" placeholder="Competencia">
		             
		               
		              </div>

		              <div class="clearfix"></div>
		              <br>

		              <label class="col-sm-2 control-label" >Titulo</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_titulo" id="Edit_titulo" type="text" class="form-control"  value="" placeholder="Titulo">
		             
		               
		              </div>

		              		            
		            <div class="clearfix"></div>
		            <br>

		            <label class="col-sm-2 control-label" >Subtitulo</label>
		              <div class="col-sm-3">
		    
		                <input name="Edit_subtitulo" id="Edit_subtitulo" type="text" class="form-control"  value="" placeholder="Subtitulo">
		             
		               
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
        <button type="button" class="btn btn-primary" value="Guardar" id="Guardar" name="Guardar" onclick="return editar_competencia(oculto.value,Edit_competencia.value,Edit_titulo.value,Edit_subtitulo.value);">Editar</button>
      </div>
    </div>
  </div>
</div>

</body>

<script>
	//$('#tabla_competencia').load('tabla.php');
</script>
</html>