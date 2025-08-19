<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RRHH/Usuarios</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <!--Bootstrap-->
    <link href="CSS/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
   
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script> 
    <script type="text/javascript" src="jalert/jquery.alerts.js"></script>  
  <link href="jalert/jquery.alerts.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="js/messages_es.js" type="text/javascript"></script>


<script type="text/javascript">


function validar_dni(){
   var isNotOk;
   var dni=window.document.formTurno.dni.value;

    if(dni=="" )
     {
      document.getElementById("num").style.display="inline";
      isNotOk=true;
     }
  else
     {
    document.getElementById("num").style.display="none";
     }

     if(isNotOk)
 {
  return false;
 }
  else{
    return true;
   }

}
function validarFormTurnoUser()
   {
   var isNotOk;
   var dni=window.document.formTurno.dni.value;
   var nombre=window.document.formTurno.nombre.value;
   var apellido=window.document.formTurno.apellido.value;
   var telefono=window.document.formTurno.telefono.value;
   var domicilio=window.document.formTurno.domicilio.value;
   var user=window.document.formTurno.username.value;
   var pass=window.document.formTurno.password.value;   
   var tipoU=window.document.formTurno.tipoU.value;
   var mail=window.document.formTurno.email.value;
   
   
   
   if(dni=="" )
     {
      document.getElementById("num").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("num").style.display="none";
     }
   if(nombre=="" )
     {
      document.getElementById("name").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("name").style.display="none";
     }
   if(apellido=="" )
     {
      document.getElementById("lastn").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("lastn").style.display="none";
     }
	 
	 if(mail=="" )
     {
      document.getElementById("email").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("email").style.display="none";
     }
	
 if(user=="" )
     {
      document.getElementById("user").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("user").style.display="none";
     }  
  if(pass=="" )
       {
        document.getElementById("pass").style.display="inline";
        isNotOk=true;
       }
    else
       {
  	  document.getElementById("pass").style.display="none";
       } 	 
  if((tipoU=="") || (tipoU=="Seleccionar.."))
       {
        document.getElementById("TipoU").style.display="inline";
        isNotOk=true;
     }
  else
     {
	  document.getElementById("TipoU").style.display="none";
     } 	 
	   
  if(isNotOk)
   {
    return false;
   }
    else{
      return true;
     }
	
}

function actualizarVentanaTurnoUser()//Ventana que pregunta si deseamos Guardar  Proveedor
  { 
  //alert('entro');
    if(validarFormTurnoUser()==true) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana18'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('nombre').value;
	UI2=document.getElementById('apellido').value;
		
	document.getElementById('prueba18').innerHTML="Actualizar Datos del Usuario: "+UI2+" "+UI1+" ?";
	
    }//end if validar
  }
  


function ocultarVentana18()
  {
    var ventana = document.getElementById('miVentana18'); // Accedemos al contenedor
    ventana.style.display = 'none';
	
  }
</script>


</head>



<body>


    
   
<?php


include("conexion.php");
include("Funciones_Turno.php");


//SeSION

//Inicializar una sesion de PHP

 
//Validar que el usuario este logueado y exista un UID
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid'])) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la 
    //pantalla de login, enviando un codigo de error
?>
        <form name="formulario" method="post" action="index.php">
            <input type="hidden" name="msg_error" value="2">
        </form>
        <script type="text/javascript"> 
            document.formulario.submit();
        </script>
<? }
 
     
    //Sacar datos del usuario que ha iniciado sesion
    $sql = "SELECT  tx_nombre,tx_apellido,tx_TipoUsuario,id_dni
            FROM t_usuarios
            LEFT JOIN t_tipousuario
            ON t_usuarios.id_TipoUsuario = t_tipousuario.id_TipoUsuario
            WHERE id_dni = '".$_SESSION['uid']."'";         
    $result     =mysql_query($sql); 
 
    $nombreUsuario = "";
 
    //Formar el nombre completo del usuario
    if( $fila = mysql_fetch_array($result) )
        $nombreUsuario = $fila['tx_nombre']." ".$fila['tx_apellido'];
     
//Cerrrar conexion a la BD
//mysql_close($conexion);


//FIN SESION

$botonhabG="enabled"; //habilita el boton Guardar
$botonhabM="disabled"; //deshabilita el boton Modificar
$botonhabE="disabled"; //deshabilita el boton Eliminar
$botonhabC="enabled"; //deshabilita el boton Cancelar


	  
	   
	   switch($_POST['boton'])
	   { 
  	   case 'Guardar':
  	   				  
  					  //guardo 
  					  $hoy=date("Y-m-d H:i:s");
  					  $sql0="Select * From t_usuarios Where id_dni='$_POST[dni]'";
  					  $res0=mysql_query($sql0) or die("Error en $consulta <br>MySQL dice: ".mysql_error());;
  					   
  					  if(mysql_num_rows($res0)==0){ 

      						  $sql="Insert Into t_usuarios (id_dni, tx_nombre, tx_apellido, tx_domicilio, tx_telefono, tx_celular, tx_email, tx_username, tx_password, id_TipoUsuario, idEmpresa, dt_registro) VALUES ('$_POST[dni]', '$_POST[nombre]', '$_POST[apellido]', '$_POST[domicilio]', '$_POST[telefono]', '$_POST[celular]', '$_POST[email]', '$_POST[username]', '".encriptar($_POST[password])."', '$_POST[tipoU]', '$_POST[empresa]', '$hoy')";
      					  //echo $sql;
      					  $res=mysql_query($sql);
      					  
      					  
      					  
      					  //Si Agrego un Evaluado lo agrego a la tabla Evaluado
      					  if($_POST[tipoU]==3)
      					  {	
      					  	$sqlU="Select * from t_usuarios Where id_dni='$_POST[dni]'";
        					  $resU=mysql_query($sqlU);
        					  $rowU=mysql_fetch_array($resU);
                  }
      					  
      					  
      					  echo "<script>alert('El Usuario se Agrego Correctamente');</script>";
  						}
  						else
  						{
  						  $sql="UPDATE t_usuarios SET id_dni={$_POST[dni]}, tx_nombre='{$_POST[nombre]}', tx_apellido='{$_POST[apellido]}', tx_domicilio='{$_POST[domicilio]}', tx_telefono='{$_POST[telefono]}', tx_celular='{$_POST[celular]}', tx_email='{$_POST[email]}', tx_username='{$_POST[username]}', tx_password='".encriptar($_POST[password])."', id_TipoUsuario='{$_POST[tipoU]}', idEmpresa='{$_POST[empresa]}' Where id_dni='$_POST[dni]'";
    						$res=mysql_query($sql);
    						  //Si el Usuario es un Medico Modifico los datos de la tabla Medicos correspondiente al Usuario
    						// if($_POST[tipoU]==3){
          //         $sql="UPDATE medicos SET Dni=$_POST[dni], Nombre='$_POST[nombre]', Apellido='$_POST[apellido]', Domicilio='$_POST[domicilio]', Telefono='$_POST[telefono]', Celular='$_POST[celular]', Email='$_POST[email]', id_TipoUsuario='$_POST[tipoU]' Where Dni='$_POST[dni]'";
          //         $res=mysql_query($sql);
          //       }

  						echo "<script>alert('El Usuario se Modifico Correctamente');</script>";
              }

              $_POST[dni]="";
  					  $_POST[nombre]="";
  					  $_POST[apellido]="";
  					  $_POST[domicilio]="";
  					  $_POST[telefono]="";
  					  $_POST[celular]="";
  					  $_POST[email]="";
  					  $_POST[username]="";
    					$_POST[password]="";
  					  $_POST[tipoU]="";
              $_POST[empresa]="";
  					  
  					  break;
  					  
  		 case 'Cancelar':
  	   			    	 $_POST[dni]="";
      					   $_POST[nombre]="";
      					   $_POST[apellido]="";
      					   $_POST[domicilio]="";
      					   $_POST[telefono]="";
      					   $_POST[celular]="";
      					   $_POST[email]="";
      					   $_POST[username]="";
    					     $_POST[password]="";
                    $_POST[tipoU]="-1";
              $_POST[empresa]="-1";
  					   break;
  	   
  	   case 'Ver':
  	   				if($_POST[dni]!=""){
                $sql="Select * From t_usuarios Where id_dni=$_POST[dni]";
    					
      					$res=mysql_query($sql);
                $n=mysql_num_rows($res);
      					
      					if($n>0){//echo $sql;

      					 	$row=mysql_fetch_array($res);
      						
      						$_POST[nombre]=$row[tx_nombre];
      						//echo $_POST[nombre];
      						$_POST[apellido]=$row[tx_apellido];
      						$_POST[dni]=$row[id_dni];
      						$_POST[email]=$row[tx_email];
      						$_POST[domicilio]=$row[tx_domicilio];
      						$_POST[telefono]=$row[tx_telefono];
      						$_POST[celular]=$row[tx_celular];
      						$_POST[username]=$row[tx_username];
        					$_POST[password]=$row[tx_password];
      						$_POST[tipoU]=$row[id_TipoUsuario];
                  $_POST[empresa]=$row[idEmpresa];
                }
                else{

      					 	$_POST[nombre]="No existe";
      						$_POST[apellido]="No existe";
      						$_POST[dni]=$_POST[dni];
      						$_POST[domicilio]="";
      					  $_POST[telefono]="";
      					  $_POST[celular]="";
      					  $_POST[email]="";
      					  $_POST[username]="";
        					$_POST[password]="";
                }
              } 
              break;
	   
	   }//fin switch
  ?>

  <!--          COMIENZO  DE     ELEMENTOS                  -->
  <div class="container-fluid">

    <header id="header" class="">

      

      <div class="row">

        <?include("Menu/Menu_Bootstrap.php");?>

        

      </div><!--FIN ROW-->
    
    </header><!-- /header -->
  </div>
  
  <div class="container">

      <div class="row">
        <div id="cabecera_simple"></div>
      </div>
   
                
      <form method="post"  name="formTurno" onpresskey="anulaenter()" >
    	  
         
            <div class="form-group">
              <div class="row">
                <div class="form-inline">
                  <div id="num" style="display:none; color:red">&nbsp;DNI inválido*</div>&nbsp;<label for="dni">*DNI:</label>
                  <input class="form-control" type="text" name="dni" id="dni" size="15" value="<? echo $_POST[dni] ?>" maxlength="10"/>
                  <input class="btn btn-primary" title="Ver" type="submit" name="boton" value="Ver" onclick=" return validar_dni()" />
                </div>
              </div>
              
            </div>   
                
         
        
        	<div id="segmento" style="margin-left:100px">
          		<div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
                  		Usuarios
                  </div>
                  <div style="text-align:center; margin:10px 0px 0px 0px; font-size:10px; color:black; font-weight:bold">(*) Datos Obligatorios</div>
                  <div style="text-align:center;  font-size:10px; color:black; font-weight:bold">Unos de los dos telefonos es obligatorio</div>
          </div>
         
          
          
            <div class="form-group">
              <div class="row">
                <div class="form-inline">
                  <div id="name" style="display:none; color:red">&nbsp;Nombre inválido*</div>&nbsp;<label for="nombre">*Nombre</label>
                  <input class="form-control" id="nombre" type="text" name="nombre" size="15" value="<? echo $_POST[nombre] ?>"/>
                  <label for="apellido">*Apellido</label>
                  <input class="form-control" id="apellido" type="text" name="apellido" size="15" value="<? echo $_POST[apellido] ?>"/>&nbsp;<div id="lastn" style="display:none; color:red">&nbsp;Apellido inválido*</div>
                </div>
              </div>
            </div>
          
            
            <div class="form-group">
              <div class="row ">
                <div class="form-inline">
                 <div id="phone" style="display:none; color:red">&nbsp;Telefono inválido*</div>&nbsp;<label for="telefono">Telefono</label>
                 <input class="form-control" type="text" name="telefono" id="telefono" size="15" value="<? echo $_POST[telefono] ?>"/>
                 <label for="celular">Celular</label>
                 <input class="form-control" type="text" name="celular" id="celular" size="15" value="<? echo $_POST[celular] ?>" />&nbsp;<div id="cell" style="display:none; color:red">&nbsp;Celular inválido*</div>
                </div>
              </div>
            </div>

             
         
           
            <div class="form-group">
              <div class="row ">
                <div class="form-inline">
                 <div id="adress" style="display:none; color:red">&nbsp;Domicilio inválido*</div>&nbsp;<label for="domicilio">Domicilio</label>
                 <input class="form-control" type="text" name="domicilio" id="domicilio" size="15" value="<? echo $_POST[domicilio] ?>"/>
                 <label for="email">*Email</label>
                 <input class="form-control" type="text" name="email" id="email" size="15" value="<? echo $_POST[email] ?>"/>&nbsp;<div id="email" style="display:none; color:red">&nbsp;E-mail inválido*</div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row ">
                <div class="form-inline">
                 <div id="user" style="display:none; color:red">&nbsp;Usuario inválido*</div>&nbsp;<label for="username">*Usuario</label>
                 <input class="form-control" type="text" id="username" name="username" size="15" value="<? echo $_POST[username] ?>"/>
                 <label for="password">*Password</label>
                 <input class="form-control" type="text" name="password" id="password" size="15" value="<? echo desencriptar($_POST[password]); ?>"/>&nbsp;<div id="pass" style="display:none; color:red">&nbsp;Password inv&aacute;lida*</div>
                </div>
              </div>
            </div>

            <div class="form-group">

              <div class="row ">
                <div class="form-inline">
                   <label for="tipoU">*Empresa</label>
                 <select name="empresa" id="empresa"  class="form-control" >
                   <option value="-1" selected="selected">Seleccionar..</option>
                     <?
                     $sql="Select * from empresas";
                     $res=mysql_query($sql);
                     while($row=mysql_fetch_array($res)){
                      if($_POST[empresa]==$row[Id])
                        {
                        ?>
                          <option value="<? echo $row[Id] ?>" selected="selected"><? echo $row[Empresa] ?></option>
                        <?
                        } //end if 
                        else{
                        ?>
                        <option value="<? echo $row[Id] ?>"><? echo $row[Empresa] ?></option>
                        <? 
                        } //end else
                      } //end while
                        ?>
                  </select>&nbsp;<div id="TipoU" style="display:none; color:red">&nbsp;Empresa inválida*</div>

                 <label for="tipoU">*Tipo</label>
                 <select name="tipoU" id="tipoU"  class="form-control" >
                   <option value="-1" selected="selected">Seleccionar..</option>
                     <?
                     $sql="Select * from t_tipousuario";
                     $res=mysql_query($sql);
                     while($row=mysql_fetch_array($res)){
                      if($_POST[tipoU]==$row[id_TipoUsuario])
                        {
                        ?>
                          <option value="<? echo $row[id_TipoUsuario] ?>" selected="selected"><? echo $row[tx_TipoUsuario] ?></option>
                        <?
                        } //end if 
                        else{
                        ?>
                        <option value="<? echo $row[id_TipoUsuario] ?>"><? echo $row[tx_TipoUsuario] ?></option>
                        <? 
                        } //end else
                      } //end while
                        ?>
                  </select>&nbsp;<div id="TipoU" style="display:none; color:red">&nbsp;Tipo Usuario inválido*</div>
                </div>
              </div>
            </div>

           <div class="form-group">
             <div class="row">
              <div class="form-inline">
                <input name="boton" type="submit" class="btn btn-primary" title="Cancelar" reset value="Cancelar" <? echo $botonhabC  ?> />
                <input name="boton" type="button" class="btn btn-primary" title="Actualizar" onclick="actualizarVentanaTurnoUser()" value="Actualizar"<? echo $botonhabG  ?>/>
              </div>
            </div>

          </div> 

          <!-- Ventana Guardar -->
          <div id="miVentana18" style="position: fixed; width: 350px; height: auto; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;">
           
             <div  style="font-weight: bold; text-align: left; color: #FFFFFF; padding: 5px; background-color:#006394">&iexclATENCION! </div>
             <div id="prueba18" style=" text-align: center; margin-top: 44px;"><p style="padding: 5px; text-align: justify; line-height:normal"></p></div>
             <div  style="padding: 10px; background-color: #F0F0F0; text-align: center; margin-top: 54px;">
                <input id="btnAceptar" onclick="ocultarVentana18();" name="boton" class="btn btn-default" size="20" type="submit" value="Guardar" />
                <input id="btnCancelar"  onclick="ocultarVentana18();" name="boton"  class="btn btn-default" size="20" type="submit" value="Cancelar" />
             </div>
          </div>
      </form>
  </div><!--CONTAINER-->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>

</body>
</html>