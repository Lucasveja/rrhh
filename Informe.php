<? session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Developsys/Informe</title>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../style.css" rel="stylesheet" type="text/css" media="screen" />
    
    <!--Bootstrap-->
    <link href="../CSS/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../fonts" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../css/animate.css">
   
   
    
       <!-- JAVASCRIPT -->
  <script type="text/javascript" src="../js/funciones_Turno.js"></script>
  
  <script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/bootbox.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap-notify.min.js"></script>

  <script>
// $(document).ready(function() {

// 		$('#periodo').blur(function (){

// 			/*	id_evaluador:$("#evaluador").get(0).value, // Valor seleccionado
// 				emp:$("#idEmp").get(0).value,
// 				per:$("#periodo").get(0).value*/
// 				// alert($("#evaluador").val());
// 				// alert($("#idEmp").val());
// 				// alert($("#periodo").val());
// 				$("#evaluado").empty();
// 				$.getJSON("Filtro.php?id_evaluador="+$('#evaluador').val()+"&emp="+$('#idEmp').val()+"&per="+$('#periodo').val(),function(data){
// 				    $.each(data, function(id,value){

// 					$("#evaluado").append('<option value="'+id+'">'+value+'</option>');
// 				    });
// 				});

// 		});

    	
// });
	



  </script>
  
 <script>
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


 
// function Completar(obj,cant){ 
// 	if(isNaN(obj.value))
// 	{
// 	  alert("Ingrese valor v?lido")
// 	  obj.value='';
// 	  return false;
// 	}
// 	 while (obj.value.length<cant)
// 	   obj.value = '0'+obj.value;
// }












 
//  function CambioPeriodo()
// {
   
//    document.getElementById("evaluado").selectedIndex = "0";
//    LlamarJquery();
// }

function validateMMYYYY(cadena) {
    //var reg = new RegExp("(((0[123456789]|10|11|12)/(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))");
    var reg = new RegExp("(((([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))");
 
    if (reg.test(cadena))
        return true;
    else
        return false;
	}

function Validar()
{
	var ok=1;
	 if(formu.evaluador.value== -1 || formu.evaluador.value=="Seleccionar..")
  {
     formu.evaluador.focus;
	 alerta("CUIDADO",'Ud. debe Seleccionar un <strong>Evaluador</strong>', "info", "warning-sign");
	 ok=-1;
  }

  if(formu.periodo.value== null || formu.periodo.value=="")
  {
     formu.periodo.focus;
	 alerta("CUIDADO",'Ud. debe ingresar un <strong>Periodo</strong>', "info", "warning-sign");
	 ok=-1;

	 }
if(validateMMYYYY(formu.periodo.value)==false)
  {
     formu.periodo.focus;
	 alerta("CUIDADO",'Ud. debe ingresar un <strong>Periodo</strong> Valido - <strong>aaaa</strong> -', "info", "warning-sign");
	 ok=-1;

	 }
 
  if(ok!=-1){


  	if(formu.evaluado.value== -1){

  		
  		var esVisible = $("#div_evaluado").is(":visible");
  		
  		

  		if(esVisible!=false){
  			//alert("pdf_completo");
  		  		//IMPRIMO TODOS LOS PDFS DE SUs EVALUADOs
  		  		
  		  		
  		  		empresa=document.getElementById('idEmp').value;
  		  		evaluador=document.getElementById('evaluador').value;
  		  		periodo=document.getElementById('periodo').value;
  		  		window.open('pdf_completo.php?empresa='+empresa+'&evaluador='+evaluador+'&periodo='+periodo,'','');
  		  		
  		  	}

  	}
  	else{
  		//IMPRIMO EL PDF DE UN EVALUADO ESPECIFICO
  		//alert("pdf_simple");

  		empresa=document.getElementById('idEmp').value;
  		//alert(empresa);
  		evaluador=document.getElementById('evaluador').value;
  		evaluado=document.getElementById('evaluado').value;
  		//alert(evaluado);
  		periodo=document.getElementById('periodo').value;
  		 window.open('../pdf_simple.php?empresa='+empresa+'&evaluador='+evaluador+'&evaluado='+evaluado+'&periodo='+periodo,'','');
  		// window.open('test.php','','');

  	}



   return true;
  }
  else{
  	return false;
  }
   
  
}//validar

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
 
function resetear_campos(){
	
	document.getElementById('evaluador').value=-1;
	document.getElementById('periodo').value="";
	document.getElementById('idEval').value="";
	document.getElementById('idEvaluado').value="";
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
  include("../Funciones_Turno.php");
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
	$sql="select tx_nombre, tx_apellido, idEmpresa, e.Empresa from t_usuarios u inner join empresas e  on u.idEmpresa=e.Id where u.id_dni= '".$_SESSION['uid']."'";
  //echo $sql;
  $result     =mysql_query($sql);
  $nombreUsuario = "";

  //Formar el nombre completo del usuario
  if( $fila = mysql_fetch_array($result) )
    $nombreUsuario =$fila['tx_nombre']." ".$fila['tx_apellido'];
    $idEmpresa= $fila['idEmpresa'];
    //echo $fila[Empresa];
	//$idEvaluador=$_SESSION['uid'];
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
        <div id="cabecera_simple"></div>
      </div>

      <div id="segmento" style="margin-left:100px">
        <div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
          <small>Informes PDF</small>
        </div>
      </div>
  
     
      </br> 

      <div class="form-group  has-feedback ">
          <label class="col-sm-1 control-label" >Empresa</label>
		   <div class="col-sm-3">
		    <input type="text" class="form-control" id="empresa" name="empresa" value="<? echo  $fila['Empresa']?>" readonly requerid>
             
          
		  </div>
		  <label class="col-sm-1 control-label" >Evaluador</label>
           <div class="col-sm-3">
		   <!--  <input type="text" class="form-control" id="evaluador" name="evaluador" value="<? echo  $nombreUsuario?>"  requerid> -->
		   	<select name="evaluador" id="evaluador" class="form-control" onChange="document.getElementById('idEval').value=this.value;document.getElementById('periodo').focus();">

			    		
			    		<option value="-1">Seleccionar..</option>
			    		<?
			    		$sqlEV="SELECT * from evaluador Where IdEmpresa='{$idEmpresa}'";
			    		$resEV=mysql_query($sqlEV);
			    		while($ev=mysql_fetch_array($resEV)){
			    			?>
			    			<option value="<? echo $ev[Id] ?>"><? echo $ev[Nombre] ?></option>
			    			<?
			    		}
		    		
		    		?>
		    	</select>
		  
        
      </div>  
		   
	  </div>	  
		
		<?
					$sqlP="Select * from periodos Where PeriodoActivo=1";
					$resP=mysql_query($sqlP);
					$periodoActivo=mysql_fetch_array($resP);
					?>

		 <div class="form-group  has-feedback ">         
	 <label class="col-sm-1 control-label" >Periodo</label>	
		    <div class="col-sm-3">
			 <input type="text" class="form-control" id="periodo" maxlength="4" placeholder="aaaa" name="periodo" value="<? echo $periodoActivo[Periodo] ?>"  onKeyUp="mascara(this,'####',event)" readonly>
			</div> 	 	  
	 
	 <div id="div_evaluado" style="display: none">
		 <label class="col-sm-1 control-label" >Evaluado</label>

	           <div class="col-sm-3">
			        <select  data-size="5" class="form-control"  name="evaluado" id="evaluado" onChange="document.getElementById('idEvaluado').value=this.value">
					   <option value="-1">Seleccionar..</option>
					   <?
					    //$p= explode("/",$_POST[periodo]);
						//$peri=$p[0].$p[1];
					   	$query="SELECT e.id,e.nombre FROM asignados a inner join evaluado e on a.idevaluado=e.id where a.idevaluador={$_POST[idEval]} and a.idempresa={$_POST[idEmp]} and a.periodo='{$_POST[periodo]}'";
						$result = mysql_query($query);
						while($row=mysql_fetch_array($result)){
							?>

							 <option value="<? echo $row[id]  ?>"><? echo $row[nombre]  ?></option>
		
							<?


						}
					   ?>
						   
					</select>
				</div>  
	</div>
	 
	</div>	
    	
    <div class="form-group  has-feedback ">
          
		   <div class="form-group col-sm-6">
    	    <div align="right">
    	      <input name="botons" type="submit" class="btn btn-primary "  value="PDF" onClick="return Validar()">
    	       <input name="boton" type="submit" class="btn btn-default "  value="Cancelar" onClick="resetear_campos()">
    	      
  	        </div>
    	  </div>
	  </div>	  	
    	  
    

    
    </div><!--CONTAINER--> 
	
<!-- <input name="nivelAl" type="text">
<input name="idnivel" type="text"> -->
<input name="idEmp" id="idEmp" type="hidden" value="<? echo $idEmpresa ?>">
<input name="idEval" id="idEval" type="hidden" value="<? echo $_POST[idEval] ?>">
<input name="idEvaluado" id="idEvaluado" type="hidden" value="<? echo $_POST[idEvaluado] ?>">

  </form>



       <!-- Js vinculados -->
  <!-- <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.js"></script> -->
    
    
 

</body>
<script>
	evaluador=document.getElementById('idEval').value;
	if(evaluador!=""){
		document.getElementById("div_evaluado").style.display="block";
		document.getElementById('evaluador').value=evaluador;
	}
	else{
		document.getElementById('div_evaluado').style.display="none";
	}
</script>
</html>
<?



?>
    
    
 


