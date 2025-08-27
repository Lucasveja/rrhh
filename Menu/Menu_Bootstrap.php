<style>
  .dropdown-submenu { position: relative; }
  .dropdown-submenu > .dropdown-menu {
    top: 0; left: 100%; margin-top: -6px; margin-left: -1px;
    border-radius: 0 6px 6px 6px;
  }
  .dropdown-submenu:hover > .dropdown-menu { display: block; }
  .dropdown-submenu > a:after {
    content: " "; float: right; width: 0; height: 0;
    border-color: transparent; border-style: solid;
    border-width: 5px 0 5px 5px; border-left-color: #333;
    margin-top: 5px; margin-right: 5px;
  }
  .dropdown-submenu:hover > a:after { border-left-color: #777; }
  .dropdown-submenu.pull-left { float:none !important; }
  .dropdown-submenu.pull-left > .dropdown-menu { left: -100%; margin-left: 10px; border-radius: 6px 0 6px 6px; }

  /* Mejores micro-detalles visuales (sin tocar la estructura) */
  .navbar-default .navbar-brand { font-weight: 600; }
  .navbar-default .navbar-nav > li > a { padding-right: 14px; padding-left: 14px; }
  .navbar-default .navbar-nav > li > a:hover { background: rgba(0,0,0,.04); }
  .navbar-default .dropdown-menu { box-shadow: 0 4px 16px rgba(0,0,0,.12); }
  .navbar-default .dropdown-menu > li > a { padding: 8px 16px; }
  /* Opcional: estilo del item activo */
  .navbar-default .navbar-nav > .active > a,
  .navbar-default .navbar-nav > .active > a:focus,
  .navbar-default .navbar-nav > .active > a:hover { background-color: #e7e7e7; }
</style>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand y botón mobile -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
              data-target="#bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Abrir menú">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- Brand ahora va a Inicio correcto -->
      <a class="navbar-brand" href="/arcorsj/apps/rrhh/inicio.php">Developsys</a>
    </div>

    <!-- Contenido del nav -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <li>
          <?php
          if ( ( ($_SESSION['Evaluador'] ?? '') === 'SI' || ($_SESSION['autenticado'] ?? '') === 'SI' ) && isset($_SESSION['uid']) ) { ?>
            <!-- Inicio corregido -->
            <a href="/arcorsj/apps/rrhh/inicio.php"><span class="icon-home"></span>&nbsp;Inicio</a>
          <?php } else { ?>
            <!-- Si querés un inicio alternativo para Evaluado, dejalo aquí -->
            <!-- <a href="/arcorsj/apps/rrhh/GrillaMedico.php"><span class="icon-home"></span>&nbsp;Inicio</a> -->
          <?php } ?>
        </li>

        <?php
        // ============== PARÁMETROS (solo autenticado) ==============
        if ( ( $_SESSION['autenticado'] ?? '' ) === 'SI' && isset($_SESSION['uid']) ) { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-haspopup="true" aria-expanded="false">
              <span class="icon-wrench"></span>&nbsp;Paramentros <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="/arcorsj/apps/rrhh/Parametros/Periodos/periodo.php">Periodo Activo&nbsp;<span class="glyphicon glyphicon-calendar pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Usuarios.php">Usuarios&nbsp;<span class="icon-user pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Empresas/empresa.php">Empresas&nbsp;<span class="icon-list2 pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Departamentos/departamento.php">Departamentos&nbsp;<span class="icon-list2 pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Area/area.php">Areas&nbsp;<span class="icon-list2 pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Posiciones/posicion.php">Posicion&nbsp;<span class="icon-price-tags pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Evaluados/evaluado.php">Evaluados&nbsp;<span class="icon-clipboard pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Parametros/Evaluadores/evaluador.php">Evaluadores&nbsp;<span class="icon-clipboard pull-right"></span></a></li>

              <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Competencias</a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="/arcorsj/apps/rrhh/Parametros/Competencias/competencias.php">Competencias</a></li>
                  <li><a tabindex="-1" href="/arcorsj/apps/rrhh/Parametros/Competencias/niveles.php">Niveles</a></li>
                </ul>
              </li>

              <li><a href="/arcorsj/apps/rrhh/Parametros/Asignados/asignar.php">Asignados&nbsp;<span class="icon-clipboard pull-right"></span></a></li>
            </ul>
          </li>
        <?php } ?>

        <?php
        // ============== CARGA (autenticado o evaluador) ==============
        if ( (($_SESSION['autenticado'] ?? '') === 'SI') || (($_SESSION['Evaluador'] ?? '') === 'SI' && isset($_SESSION['uid'])) ) { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-haspopup="true" aria-expanded="false">
              <span class="icon-add-to-list"></span>&nbsp;Carga <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="/arcorsj/apps/rrhh/Evaluar/Evaluacion.php">Llenado&nbsp;<span class="icon-list pull-right"></span></a></li>
              <?php if ( ( $_SESSION['autenticado'] ?? '' ) === 'SI' && isset($_SESSION['uid']) ) { ?>
                <li><a href="/arcorsj/apps/rrhh/Evaluar/EvaluacionMod.php">Modificar Eval.&nbsp;<span class="icon-list pull-right"></span></a></li>
              <?php } ?>
              <li><a href="/arcorsj/apps/rrhh/Cierre/CierreyEnvio.php">Cierre y Envio.&nbsp;<span class="icon-list pull-right"></span></a></li>
              <li><a href="/arcorsj/apps/rrhh/Cierre/Descargar.php">Descargar Evaluaci&oacute;n.&nbsp;<span class="icon-list pull-right"></span></a></li>
            </ul>
          </li>
        <?php } ?>

        <?php
        // ============== INFORMES (autenticado o evaluador) ==============
        if ( (($_SESSION['autenticado'] ?? '') === 'SI' || ($_SESSION['Evaluador'] ?? '') === 'SI') && isset($_SESSION['uid']) ) { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-haspopup="true" aria-expanded="false">
              <span class="icon-info"></span>&nbsp;Informes <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="/arcorsj/apps/rrhh/Informes/Informe.php" title="Informe Presentacion PDF">
                PDF <span class="glyphicon glyphicon-list-alt pull-right"></span></a>
              </li>
              <li><a href="/arcorsj/apps/rrhh/Informes/EvaluacionHistorial.php" title="Evaluacion Historial">
                Historial Evaluacion <span class="glyphicon glyphicon-list-alt pull-right"></span></a>
              </li>
              <!-- <li><a href="/apps/rrhh/Informes/InformeQ.php" title="Informe Presentacion Quirurgica">
                Presentacion Q. <span class="glyphicon glyphicon-list-alt pull-right"></span></a>
              </li> -->
            </ul>
          </li>
        <?php } ?>

      </ul>

      <!-- Derecha -->
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/arcorsj/apps/rrhh/Tutorial.php"><span class="icon-home"></span>&nbsp;Tutorial</a></li>
        <li><a href="/arcorsj/apps/rrhh/cerrarSesion.php"><span class="icon-remove-user"></span>&nbsp;Cerrar Sesion</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<script>
/* Marca activo el item cuya ruta coincide con la actual (solo UI, no cambia lógica) */
(function(){
  var path = location.pathname.replace(/\/+$/,'');
  var links = document.querySelectorAll('.navbar-nav a[href]');
  for (var i=0; i<links.length; i++){
    var a = links[i], href = a.getAttribute('href'); if(!href) continue;
    try {
      var u = new URL(href, location.origin);
      var linkPath = u.pathname.replace(/\/+$/,'');
      if (path === linkPath) {
        var li = a.closest('li'); if(li) li.classList.add('active');
        var dd = a.closest('.dropdown'); if(dd) dd.classList.add('active');
      }
    } catch(e){}
  }
})();
</script>
