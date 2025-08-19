
<style>

  .dropdown-submenu {
    /*position: relative;*/
  }

  .dropdown-submenu>.dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -6px;
      margin-left: -1px;
      -webkit-border-radius: 0 6px 6px 6px;
      -moz-border-radius: 0 6px 6px;
      border-radius: 0 6px 6px 6px;
  }

  .dropdown-submenu:hover>.dropdown-menu {
      display: block;
  }

  .dropdown-submenu>a:after {
      display: block;
      content: " ";
      float: right;
      width: 0;
      height: 0;
      border-color: transparent;
      border-style: solid;
      border-width: 5px 0 5px 5px;
      border-left-color: black;/*color del caret*/
      margin-top: 5px;
     /* margin-right: -10px;*/
      margin-right: 5px;
  }

  .dropdown-submenu:hover>a:after {
      border-left-color: grey;
  }

  .dropdown-submenu.pull-left {
      float: none;
  }

  .dropdown-submenu.pull-left>.dropdown-menu {
      left: -100%;
      margin-left: 10px;
      -webkit-border-radius: 6px 0 6px 6px;
      -moz-border-radius: 6px 0 6px 6px;
      border-radius: 6px 0 6px 6px;
  }

</style>

  <nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Developsys</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
       <!--  <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
       <li><a href="#">Link</a></li> -->
        <li>
        <? //echo $_SESSION['Evaluador'];
        //echo $_SESSION['Evaluador'];
        //echo $_SESSION['autenticado'];
        //echo $_SESSION['uid'];
          if(($_SESSION['Evaluador'] == 'SI' || $_SESSION['autenticado']=='SI')  && isset($_SESSION['uid'])){ ?>
               <a href="/apps/rrhh/inicio.php"><span class="icon-home"></span>&nbsp;Inicio</a>
          <?
          } 
          else{ ?>
                <!--ir a pantalla principal de Evaluado-->
               <!-- <a href="/apps/rrhh/GrillaMedico.php"><span class="icon-home"></span>&nbsp;Inicio</a> -->
        <?  }
          
          ?>
          </li>
          <?
            //              PARAMETROS

            if(($_SESSION['autenticado'] == 'SI')  && isset($_SESSION['uid'])){ ?>
              <li class="dropdown">
           
                <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon-wrench"></span>&nbsp;Paramentros<span class="caret"></span></a>
                <ul class="dropdown-menu">
                      <li><a href="/apps/rrhh/Parametros/Periodos/periodo.php">Periodo Activo&nbsp;<span class="glyphicon glyphicon-calendar pull-right"></span></a></li>
                      <li><a href="/apps/rrhh/Usuarios.php">Usuarios&nbsp;<span class="icon-user pull-right"></span></a></li>
                      <li><a href="/apps/rrhh/Parametros/Empresas/empresa.php">Empresas&nbsp;<span class="icon-list2 pull-right"></a></li>
                      <li><a href="/apps/rrhh/Parametros/Departamentos/departamento.php">Departamentos&nbsp;<span class="icon-list2 pull-right"></a></li>
                      <li><a href="/apps/rrhh/Parametros/Area/area.php">Areas&nbsp;<span class="icon-list2 pull-right"></a></li>
                      <li><a href="/apps/rrhh/Parametros/Posiciones/posicion.php">Posicion&nbsp;<span class="icon-price-tags pull-right"></a></li>
                      <li><a href="/apps/rrhh/Parametros/Evaluados/evaluado.php">Evaluados&nbsp;<span class="icon-clipboard pull-right"></a></li>
                      <li><a href="/apps/rrhh/Parametros/Evaluadores/evaluador.php">Evaluadores&nbsp;<span class="icon-clipboard pull-right"></a></li>
                      <li class="dropdown-submenu">
                        <a tabindex="-1" href="#">Competencias&nbsp;<!-- <span class="icon-clipboard pull-right"> --></a>
                        <ul class="dropdown-menu">
                         <li><a tabindex="-1" href="/apps/rrhh/Parametros/Competencias/competencias.php">Competencias</a></li>
                          <li><a tabindex="-1" href="/apps/rrhh/Parametros/Competencias/niveles.php">Niveles</a></li>
                        </ul>
                      </li>
                      <li><a href="/apps/rrhh/Parametros/Asignados/asignar.php">Asignados&nbsp;<span class="icon-clipboard pull-right"></a></li>

                  
                </ul>
              </li>
            <?
            }


                              //CARGA
        if(($_SESSION['autenticado'] == 'SI') || ($_SESSION['Evaluador'] == 'SI')  && (isset($_SESSION[uid]))){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon-add-to-list"></span>&nbsp;Carga<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/apps/rrhh/Evaluar/Evaluacion.php">Llenado.&nbsp;<span class="icon-list pull-right"></span></a></li>
            <li><a href="/apps/rrhh/Cierre/CierreyEnvio.php">Cierre y Envio.&nbsp;<span class="icon-list pull-right"></span></a></li>
            <li><a href="/apps/rrhh/Cierre/Descargar.php">Descargar Evaluaci&oacute;n.&nbsp;<span class="icon-list pull-right"></span></a></li>
           
           
          </ul>
        </li>
        <?
        }
       //                       INFORMES
		 
        if(($_SESSION['autenticado'] == 'SI' || $_SESSION['Evaluador'] == 'SI')  && isset($_SESSION['uid'])){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon-info"></span>&nbsp;Informes<span class="caret"></span></a>
          <ul class="dropdown-menu">
             <li><a href="/apps/rrhh/Informes/Informe.php" title="Informe Presentacion PDF">PDF<span class="glyphicon glyphicon-list-alt pull-right"></span></a></li>
             <!-- <li><a href="/apps/rrhh/Informes/InformeQ.php" title="Informe Presentacion Quirurgica">Presentacion Q.<span class="glyphicon glyphicon-list-alt pull-right"></span></a></li> -->
           
           
          </ul>
        </li>
        <?
        }
        ?>




      </ul>
     
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/apps/rrhh/cerrarSesion.php"><span class="icon-remove-user"></span>&nbsp;Cerrar Sesion</a></li>
        
      </ul>
    <ul class="nav navbar-nav navbar-right">
        <li> <a href="/apps/rrhh/Tutorial.php"><span class="icon-home"></span>&nbsp;Tutorial</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
