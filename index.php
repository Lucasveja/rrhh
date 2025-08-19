<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <!--Bootstrap-->
    <link href="CSS/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script> 
    <script type="text/javascript" src="jalert/jquery.alerts.js"></script>  
	<link href="jalert/jquery.alerts.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="js/messages_es.js" type="text/javascript"></script>
	
    <script type="text/javascript">
    $().ready(function() {
        $("#frmlogin").validate({
            rules: {
                usuario: { required: true },
                password: { required: true, minlength: 3 }
            }
        });
        $("#usuario").focus();
    });
    </script>
</head>
<body>
    <?php
        //Mostrar errores de validacion de usuario
        if( isset( $_POST['msg_error'] ) ) {
            switch( $_POST['msg_error'] ) {
                case 1:
                    ?>
                    <script type="text/javascript"> 
                        jAlert("El usuario o password son incorrectos.", "Seguridad");
                        $("#password").focus();
                    </script>
                    <?php
                    break;          
                case 2:
                    ?>
                    <script type="text/javascript"> 
                        jAlert("La seccion a la que intentaste entrar esta restringida.\n Solo permitida para usuarios registrados.", "Seguridad");
                    </script>
                    <?php       
                    break;
            } //Fin switch
        }
 
        //Mostrar mensajes del estado del registro
        if( isset( $_POST['status_registro'] ) ) {
            switch( $_POST['status_registro'] ) {
                case 1:
                    if( $_POST['i_EmailEnviado'] == 1 ) {
                        ?>
                        <script type="text/javascript"> 
                            jAlert("Gracias, ha sido registrado exitosamente.\n Se le ha enviado un correo electronico de bienvenida, \npor favor, NO LO CONTESTE pues solo es informativo.", 'Registro');
                        </script>
                        <?php
                    } else {
                        ?>
                        <script type="text/javascript"> 
                            jAlert("Gracias, ha sido registrado exitosamente.\n No se le ha podido enviar correo electronico de bienvenida, \nsin embargo, ya puede utilizar sus datos de acceso para iniciar sesion.", 'Registro');
                        </script>
                        <?php
                    }
                    break;          
                 
                default:
                    ?>
                    <script type="text/javascript"> 
                        jAlert("Temporalmente NO se ha podido registrar, intente de nuevo mas tarde.", "Registro");
                    </script>
                    <?php       
                    break;
            } //Fin switch
        }
    ?>
     
<div class="container">
    <form id="frmlogin" name="frmlogin"  method="POST" action="validarUsuario.php" class="form-horizontal">
        <div id="cabecera"></div>
        <h3>Iniciar Sesion</h3>
       
       <div class="row">
           <div class="form-group">
                <label for="usuario" class="col-lg-1 control-label">Usuario:</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su Usuario" required>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <br> 

        <div class="row">
          <div class="form-group">
            <label for="password" class="col-lg-1 control-label">Password:</label>
            <div class="col-lg-3">
              <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su Password" required minlength="3">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group">
            <a href="recuperarPassword.php">Olvide mi contraseña</a>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-md-offset-2">
            <input type="submit" class="btn btn-default" name="enviar" id="enviar" value="Ingresar">
          </div>
        </div>

        <div class="row">
          <div class="col-md-offset-5">
            <a href="registro.php">Deseo Registrarme</a>
          </div>
        </div>
    </form>
</div><!--container-->

<script src="js/bootstrap.min.js"></script>
</body>
</html>
