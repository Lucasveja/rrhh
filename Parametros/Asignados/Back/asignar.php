<? require("../../xajax/xajax_core/xajax.inc.php");
require_once("../../conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','../../xajax/');



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

function asignar($idE, $idEv, $id_ev, $per, $idAsig){

	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion

	$sql="SELECT * from asignados Where id='{$idAsig}'";
	$res=mysql_query($sql);
	$n=mysql_num_rows($res);

	//$objResp->alert($sql);

	//$objResp->alert($n);

	//$p=str_replace(array('','/'), '', $per);//quitamos el " / "
	//$objResp->alert($p);

	if($n==0){
		$sqlI="INSERT INTO asignados (IdEmpresa, IdEvaluador, IdEvaluado, Periodo) VALUES ('{$idE}', '{$idEv}', '{$id_ev}', '{$per}')";
		mysql_query($sqlI);
		/*$js="$('#tabla_asignar').load('tabla.php?id_emp={$idE}&periodo={$per}');";
		$objResp->script($js);*/
	}
	else{
		//$row=mysql_fetch_array($res);
		$sqlU="UPDATE asignados SET IdEmpresa='{$idE}', IdEvaluador='{$idEv}', IdEvaluado='{$id_ev}', Periodo='{$per}' Where id='{$idAsig[id]}'";
		//$objResp->alert($sqlU);
		mysql_query($sqlU);
		$js="$('#tabla_asignar').load('tabla.php?id_emp={$idE}&periodo={$per}');";
		$objResp->script($js);
	}


	return $objResp;
}





$xajax->registerFunction("ver_tabla");
$xajax->registerFunction("asignar");


$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Asignar Evaluadores sus Evaluados</title>

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

  function validateMMYYYY(cadena) {
    //var reg = new RegExp("(((0[123456789]|10|11|12)/(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))");
    var reg = new RegExp("(((([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))");
 
    if (reg.test(cadena))
        return true;
    else
        return false;
	}

  


  	function mascara(src, mask,e){

  		var tecla =""
  		if (document.all) // Internet Explorer
  			tecla = event.keyCode;
  		else
  			tecla = e.which;
  		//code = evente.keyCode;
  		if(tecla != 8){

  			if (src.value.length == src.maxlength){
  				return;
  			}
	  		var i = src.value.length;
	  		
	  		var saida = mask.substring(0,1);
	  		var texto = mask.substring(i);
	  		if (texto.substring(0,1) != saida){
	  			src.value += texto.substring(0,1);
	  			//alert(src.value);
	  		}
	  	}
	}


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



	function ver_tabla(id,p){
		//alert(p);
		if( id=="-1"){
				alerta("CUIDADO", "Debe Seleccionar una <strong>Empresa</strong>.", "info", "warning-sign");
				return false;
			}
			else{
				if(p==""){
					alerta("CUIDADO", "El campo <strong>Periodo</strong> no puede estar vacio.", "info", "warning-sign");
					return false;

				}
				else{

					if(validateMMYYYY(p)!=true){
						alerta("CUIDADO", "El periodo debe tener un formato valido <strong>aaaa</strong>.", "info", "warning-sign");
					return false;

					}
					else{
						xajax_ver_tabla(id,p);
					}
					
				}
				
			}
		

	}

	function ir_a_ver(e){
		var tecla =""
  		if (document.all) // Internet Explorer
  			tecla = event.keyCode;
  		else
  			tecla = e.which;
  		//code = evente.keyCode;
  		if(tecla == 13){
  			document.getElementById('Ver').focus();
  		}
	}

	function asignar(idE, idEv, id_ev, per, idAsig){
		// alert(idE);
		// alert(idEv);
		// alert(id_ev);
		//alert(idAsig);
		if(idEv=="-1"){
			alerta("CUIDADO", "Debe Seleccionar un <strong>Evaluador</strong>.", "info", "warning-sign");
			return false;
		}
		else{
			xajax_asignar(idE, idEv, id_ev, per,idAsig);
		}
	}

	function cancelar(){
		document.getElementById('empresa').value="-1";
		document.getElementById('periodo').value="";
		document.getElementById('tabla_asignar').style.display="none";
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
	    		Asignar Evaluador 
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

					<?
					$sqlP="Select * from periodos Where PeriodoActivo=1";
					$resP=mysql_query($sqlP);
					$periodoActivo=mysql_fetch_array($resP);
					?>


					<label class="col-sm-1 control-label" >Periodo</label>
					<div class="col-sm-2">
						<input name="periodo" id="periodo" type="text" class="form-control"  value="<? echo $periodoActivo[Periodo] ?>" placeholder="aaaa" onsubmit="return false;" onkeyup="mascara(this,'####',event);" maxlength="4" onkeydown="ir_a_ver(event);" readonly>
					</div>

					<div class="col-sm-1">
						<input type="button" class="btn btn-primary" name="Ver" id="Ver" value="Ver" onclick="/*ver_tabla(document.getElementById('empresa').value, document.getElementById('periodo').value)*/ver_tabla(empresa.value,periodo.value);">
					</div>

					<div class="col-sm-1">
						<input type="submit" reset class="btn btn-default" name="Cancelar" id="Cancelar" value="Cancelar" onclick="cancelar();">
					</div>
				</div>
				

				
		              

			
			</div>

			<br>
			<br>


			<div class="table-responsive" id="tabla_asignar" style="display:block">
				
			</div>





		</form>

	</div><!--CONTAINER-->





</body>

<script>
	//$('#tabla_asignar').load('tabla.php');
</script>
</html>