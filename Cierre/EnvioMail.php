  <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cierre Evaluador</title>
 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
   
 
</head>
<body>
 
<?
   $str_email="castro_gf@yahoo.com.ar";
   // $str_elNombre='JJJJ';
	//$str_username='dasd';
$str_password='dasda';
    // Le  Envio  un correo electronico  de bienvenida
    $destinatario = $str_email;                    //A quien se envia
    $nomAdmin           = 'DevelopSys';           //Quien envia
    $mailAdmin      = 'djfox84@gmail.com';       //Mail de quien envia
    $urlAccessLogin = 'http://developsys.com.ar/Turnos/index.php';       //Url de la pantalla de login
 
    $elmensaje = "";
    $asunto = $str_elNombre."Cierre del evaluador";
 
    $cuerpomsg ='
    <h2>.::Cierre Evaluador::.</h2>
    <p>Se produho el cierre de un evaluador.</p>
        <table border="0" >
        <tr>
            <td colspan="2" align="center" >Para visualizarlo ingresar a: <a href="'.$urlAccessLogin.'">'.$urlAccessLogin.'</a><br></td>
        </tr>
        
        </table> <br/><br/>
   <br><br>';
 
  
 
    //Establecer cabeceras para la funcion mail()
    //version MIME
    $cabeceras = "MIME-Version: 1.0\r\n";
    //Tipo de info
    $cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
    //direccion del remitente
    $cabeceras .= "From: ".$nomAdmin." <".$mailAdmin.">";
    $i_EmailEnviado = 0;
     
    //Si se envio el email
    if( mail($destinatario,$asunto,$cuerpomsg,$cabeceras) ) 
        $i_EmailEnviado = 1;
     
    //Cerrrar conexion a la BD
    mysql_close($conexion);
 
    // Mostrar resultado del registro
    ?>
  <form id="frm_registro_status"   name="frm_registro_status" method="post" action="index.php">
        <input type="hidden" name="status_registro" value="1" />
        <input type="hidden" name="i_EmailEnviado" value='<?php echo $i_EmailEnviado ?>' />
    </form>
    <script type="text/javascript">
        //Redireccionar con el formulario creado
        document.frm_registro_status.submit();
    </script>
</body>
</html>