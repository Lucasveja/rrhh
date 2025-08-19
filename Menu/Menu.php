<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<title>Menu</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!--<link rel="stylesheet" href="estilos.css">-->
	<link rel="stylesheet" href="CSS/fonts.css">
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
	<!--<script src="main.js"></script>-->


<!--Estilos--CSS-->
<style>
/* {
	padding:0;
	margin:0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}*/


body {background:#FEFEFE;}

.menu_bar {
	display:none;
	
}

header {
	width: 100%;
}

header nav {
	/*background:#023859;*/background-image: url(images/Banda_Costado.jpg);
	z-index:1000;
	max-width: 1000px;
	width:100%;
	/* margin:20px auto; */
	border-radius:20px; /* Bordes redondeados de la barra principal */
}

header nav ul {
	list-style:none;
}

header nav ul li {
	display:inline-block;
	position: relative;
}

header nav ul li:hover {
	background:#5cbceb;
	text-shadow: 8px 8px 8px #fff;
	
}
#primerElem:hover{
border-radius:20px 0 0 20px; /* Bordes redondeados de la barra principal */
text-shadow: 8px 8px 8px #fff;
}

header nav ul li a {
	color:#fff;
	display:block;
	text-decoration:none;
	padding: 20px;
}

header nav ul li a span {
	margin-right:10px;
}

header nav ul li:hover .children {
	display:block;
}

header nav ul li .children {
	display: none;
	background:#011826;
	position: absolute;
	width: 150%;
	z-index:1000;
	
	
}

header nav ul li .children li {
	display:block;
	overflow: hidden;
	border-bottom: 1px solid rgba(255,255,255,.5);
	
	
}

header nav ul li .children li a {
	display: block;
}

header nav ul li .children li a span {
	float: right;
	position: relative;
	top:3px;
	margin-right:0;
	margin-left:10px;
}

header nav ul li .caret {
	position: relative;
	top:3px;
	margin-left:10px;
	margin-right:0px;
}

@media screen and (max-width: 800px) {
	body {
		padding-top:80px;
	}

	.menu_bar {
		display:block;
		width:100%;
		position: fixed;
		top:0;
		/*background:#E6344A;*/
		background-image: url(images/Banda_Costado.jpg);
	}

	.menu_bar .bt-menu {
		display: block;
		padding: 20px;
		color: #fff;
		overflow: hidden;
		font-size: 25px;
		font-weight: bold;
		text-decoration: none;
	}

	.menu_bar span {
		float: right;
		font-size: 40px;
	}

	header nav {
		width: 80%;
		height: calc(100% - 80px);
		position: fixed;
		right:100%;
		margin: 0;
		overflow: scroll;
			border-radius:0px; /* Bordes redondeados de la barra principal */
	}

	header nav ul li {
		display: block;
		border-bottom:1px solid rgba(255,255,255,.5);
	}

	header nav ul li a {
		display: block;
		
	}
#primerElem:hover{
border-radius:0px; /* Bordes redondeados de la barra principal */
}
	header nav ul li:hover .children {
		display: none;
		
	}

	header nav ul li .children {
		width: 100%;
		position: relative;
	}

	header nav ul li .children li a {
		margin-left:20px;
	}

	header nav ul li .caret {
		float: right;
	}
	
}
</style>

<script>
$(document).ready(main);

var contador = 1;

function main () {
	$('.menu_bar').click(function(){
		if (contador == 1) {
			$('nav').animate({
				left: '0'
			});
			contador = 0;
		} else {
			contador = 1;
			$('nav').animate({
				left: '-100%'
			});
		}
	});

	// Mostramos y ocultamos submenus
	$('.submenu').click(function(){
		$(this).children('.children').slideToggle();
	});
}


</script>

</head>

<body>

<header>
		<div class="menu_bar">
			<a href="#" class="bt-menu"><span class="icon-menu"></span>Men&uacute;</a>
		</div>
 
		<nav>
			<ul>
            <?	if(! ($_SESSION['autenticadoMedico'] == 'SI'  && isset($_SESSION['uid'])))
						{ ?>
				<li id="primerElem"><a href="Turnos_Ver.php"><span class="icon-home"></span>Inicio</a></li>
                <? } 
				else
				{ ?>
					<li id="primerElem"><a href="GrillaMedico.php"><span class="icon-home"></span>Inicio</a></li>
			<?	}
				
				?>
				<li class="submenu">
				<a ><span class="icon-wrench"></span>Parametros</a>
				<ul class="children">
					<?	if(! ($_SESSION['autenticadoMedico'] == 'SI' && isset($_SESSION['uid'])))
						{ ?>
                        <li><a href="Turnos_Actualiza.php">Datos Pacientes<span class="icon-users2"></span></a></li>
                        <li><a href="ObraSocial.php">Obras Sociales<span class="icon-office"></span></a></li>
                        
                       <? } ?>
                       <? if(! (($_SESSION['autenticadoSecretaria'] == 'SI' || $_SESSION['autenticado'] == 'SI') && isset($_SESSION['uid'])))
						{ ?>
						<li><a href="Medicos.php">Datos Medicos<span class="icon-user-tie"></span></a></li>
						<li><a href="GeneraTurnosAtencion.php">Generar T. Atencion<span class="icon-list2"></span></a></li>
                        <li><a href="GeneraTurnosPractica.php">Generar T. Practicas<span class="icon-list2"></span></a></li>
                        <li><a href="ConfigPestania.php">Configurar Pesta&ntilde;as<span class="icon-list2"></span></a></li>
                        <li><a href="Coberturas.php">Coberturas<span class="icon-price-tags"></span></a></li>
                        <li><a href="Practica.php">Practicas<span class="icon-clipboard"></span></a></li>
                          <? } ?>
						 <? if(! (($_SESSION['autenticadoMedico'] == 'SI' || $_SESSION['autenticadoSecretaria'] == 'SI' ) && isset($_SESSION['uid'])))
						{ ?>
						<li><a href="GeneraTurnosAtencion.php">Generar T. Atencion<span class="icon-list2"></span></a></li>
                        <li><a href="GeneraTurnosPractica.php">Generar T. Practicas<span class="icon-list2"></span></a></li>
                         <li><a href="Usuarios.php">Usuarios<span class="icon-users2"></span></a></li>
                         <? } ?>
                        <!--<li><a href="#">SubElemento #7 <span class="icon-dot-single"></span></a></li>-->
				  </ul>
				</li>
				 <? if(! ($_SESSION['autenticadoMedico'] == 'SI' && isset($_SESSION['uid'])))
						{ ?>
                <li class="submenu">
					<a><span class="icon-add-to-list"></span>Turnos<span class="caret icon-arrow-down6"></span></a>
					<ul class="children">
						<li><a href="SobreT.php">SobreTurnos<span class="icon-list"></span></a></li>
						<!--<li><a href="#">SubElemento #2 <span class="icon-dot-single"></span></a></li>
						<li><a href="#">SubElemento #3 <span class="icon-dot-single"></span></a></li>-->
					</ul>
				</li>
                <? } ?>
				<? if(! (($_SESSION['autenticadoSecretaria'] == 'SI' || $_SESSION['autenticado'] == 'SI' ) && isset($_SESSION['uid'])))
						{ ?>
                <li class="submenu"><a ><span class="icon-aid-kit"></span>Medicos</a>
                	<ul class="children">
						<li><a href="GrillaMedico.php">Turnos Medico<span class="icon-list2"></span></a></li>
						<!--<li><a href="Vista_Medico.php">Ficha<span class="icon-v-card"></span></a></li>-->
						<!--<li><a href="#">SubElemento #2 <span class="icon-dot-single"></span></a></li>
						<li><a href="#">SubElemento #3 <span class="icon-dot-single"></span></a></li>-->
					</ul>
                </li>
                <? } ?>
                <?	if(! ($_SESSION['autenticadoMedico'] == 'SI' && isset($_SESSION['uid'])))
						{ ?>
                <li class="submenu"><a ><span class="icon-info"></span>Informes</a>
                	<ul class="children">
						<li><a href="CierreCaja.php" >Cierre Caja<span class="icon-key"></span></a></li>
						<li><a href="At_Paciente.php">At. Paciente<span class="icon-profile"></span></a></li>
					</ul>
                </li>
                <? } ?>
				<li><a href="cerrarSesion.php"><span class="icon-remove-user"></span>Cerrar Sesion</a></li>
			</ul>
		</nav>
	</header>
</body>
</html>
