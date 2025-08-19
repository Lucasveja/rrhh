<? require("../../xajax/xajax_core/xajax.inc.php");
require_once("../../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../../xajax/');

function editar_nombre($id, $nuevo){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	// $objResp->alert("XAJAX "+$id);
	// $objResp->alert("XAJAX "+$nuevo);

	$sql="UPDATE empresas SET Empresa='{$nuevo}' Where Id='{$id}'";
	$res=mysql_query($sql);

	$js="$('#tabla_empresa').load('tabla.php');$('#Modal_editar').modal('hide');";
	$objResp->script($js);



	return $objResp;
}

function eliminar_empresa($id){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	//$objResp->alert("XAJAX ".$id);

	$sql="Delete from empresas Where Id='{$id}'";
	$res=mysql_query($sql);
	$js="$('#tabla_empresa').load('tabla.php');";
	$objResp->script($js);

	return $objResp;

}




$xajax->registerFunction("editar_nombre");
$xajax->registerFunction("eliminar_empresa");



///$xajax->registerFunction("actualiza_tipoOrden");

$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Empresas</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../../style.css" rel="stylesheet" type="text/css" media="screen" />
    
    <!--Bootstrap-->
    <link href="../../CSS/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../fonts" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../../css/animate.css">
   
   
    
       <!-- JAVASCRIPT -->
  <script type="text/javascript" src="../js/funciones_Turno.js"></script>
  
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

	function guardar(nom){

		//alert(nom);

		if(nom!=''){
			flag=0;
		}
		else{
			flag=1;
		}
		//alert(flag);
		if(flag==1){
			
			alerta("CUIDADO", "El <strong>Nombre</strong> No puede estar Vacio.", "info", "warning-sign");
			document.getElementById('nombre').value="";
			return false;

		}
		else{
			$('#tabla_empresa').load('tabla.php');
			return true;
		}

	}

	function editar(id, nom){

		//alert(id);
		$('#Modal_editar').modal(
		{keyboard:false,
			backdrop: 'static',
			show: true}
		);
		document.getElementById('oculto').value=id;
		document.getElementById('Empresa_edit').value=nom;
	}

	function eliminar(id, nom){

		bootbox.confirm({
    title: "Eliminar ",
    message: "Eliminar la Empresa <strong>"+nom+"</strong> ?",
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
        	xajax_eliminar_empresa(id);
        }
    }
});
	}

	function editar_nombre(id, nuevo){
		// alert(id);
		// alert(nuevo);
		if(nuevo==""){

			
			alerta("CUIDADO", "El <strong>Nombre Nuevo</strong> No puede estar Vacio.", "info", "warning-sign");
			return false;

		}
		else{
			xajax_editar_nombre(id,nuevo);
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

    if($_POST[Guardar]){

    	$sql="INSERT INTO empresas (Empresa) VALUES ('$_POST[nombre]')";
    	//echo $sql;
    	$res=mysql_query($sql);



    }
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
	    		Empresas
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
				<div class="form-group">
					<div class="col-sm-3">
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre de la Empresa">
					</div>
					<input type="submit" class="btn btn-primary" name="Guardar" id="Guardar" value="Guardar" onclick="return guardar(nombre.value);">
				</div>

			</div>

			<br>
			<br>


			<div id="tabla_empresa" style="display:block">
				
			</div>





		</form>

	</div><!--CONTAINER-->

<!-- Modal -->

<div class="modal fade" id="Modal_editar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Editar Nombre de la Empresa</h4>
      </div>
      <div class="modal-body">

       	<div class="container">
			<form class="form-horizontal" data-toggle="validator" id="formEdit" name="formuEdit" method="post">	
						        
			   <div id="Editar" name="Editar" > 
		           <div class="form-group  has-feedback">
		              <label class="col-sm-2 control-label" >Empresa</label>
		              <div class="col-sm-3">
		    
		                <input name="Empresa_edit" id="Empresa_edit" type="text" class="form-control"  value="" placeholder="Nombre de la Empresa">
		             
		               
		              </div>
		            </div>
		       </div>
		        
				
			</form>
		</div><!--container-->
      </div>
      <div class="modal-footer">
      <input name="oculto" id="oculto" type="hidden" class="form-control col-sm-2"  value="<? echo $_POST['oculto']?>" placeholder="Nombre de la Empresa">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
        <button type="button" class="btn btn-primary" value="Listo" id="Listo" name="Listo"  onclick="return editar_nombre(oculto.value,Empresa_edit.value)">Listo</button>
      </div>
    </div>
  </div>
</div>
</body>

<script>
	$('#tabla_empresa').load('tabla.php');
</script>
</html>