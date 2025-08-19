<? 
require("xajax/xajax_core/xajax.inc.php");
require_once("conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

session_start();
$xajax= new xajax();
$xajax->configure('javascript URI','xajax/');


function canjear($id){

  $objResp=new xajaxResponse();
  $conn = new conexionBD (); //Genera una nueva coneccion

  //$objResp->alert("XAJAX ".$id);

  // $sql="Delete from areas Where Id='{$id}'";
  // $res=mysql_query($sql);
  // $js="$('#tabla_area').load('tabla.php');";
  // $objResp->script($js);

  return $objResp;

}




$xajax->registerFunction("c<njear");

$xajax->processRequest();

?>
<!DOCTYPE>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">





<title>Sin Servicios</title>

     
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <!--Bootstrap-->
    <link href="CSS/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="CSS/sticky-footer-navbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script> 
    <script type="text/javascript" src="jalert/jquery.alerts.js"></script>  
	<link href="jalert/jquery.alerts.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="js/messages_es.js" type="text/javascript"></script>
	
	
	
	
     

</head>

<body>
</br>
</br>
</br>
<div align="center">
<img src='images/SinServer.JPG'/>
</br>
<p style='font-size:20px'>Sin Servicio</p>
</div>   



<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
