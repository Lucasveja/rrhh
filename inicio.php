<?php
// ¡IMPORTANTE! Guardar este archivo como UTF-8 **sin BOM** y sin espacios antes de esta línea.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Muestra errores fatales en pantalla en vez de quedar en blanco:
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (!headers_sent()) header('Content-Type: text/plain; charset=utf-8');
        echo "Error fatal: {$e['message']} en {$e['file']}:{$e['line']}";
    }
});

session_start();

include("conexionn/conexion.php");
include("Funciones_Turno.php");

// Guard de sesión (Admin o Evaluador)
if ( !isset($_SESSION['uid']) || (!isset($_SESSION['autenticado']) && !isset($_SESSION['Evaluador'])) ) {
    header("Location: index.php");
    exit;
}

// Helper para escapar
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// -------------------------------
// Datos para “Bienvenido” (admin)
// -------------------------------
$nombreUsuario = "";
if (!empty($_SESSION['autenticado'])) {
    $uidEsc = mysqli_real_escape_string($conexion, $_SESSION['uid']);
    $sql = "SELECT tx_nombre, tx_apellido FROM t_usuarios WHERE id_dni = '{$uidEsc}'";
    if ($result = mysqli_query($conexion, $sql)) {
        if ($fila = mysqli_fetch_assoc($result)) {
            $nombreUsuario = trim(($fila['tx_nombre'] ?? '') . " " . ($fila['tx_apellido'] ?? ''));
        }
        mysqli_free_result($result);
    }
}

// -------------------------------------
// Inicializaciones y cálculo Evaluador
//--------------------------------------
$isEvaluador = false;
$porcentaje = 0;
$p = 0.00;
$evaluador = [];
$periodo = ['Periodo' => ''];

if (!empty($_SESSION['Evaluador'])) {
    $uidEsc = mysqli_real_escape_string($conexion, $_SESSION['uid']);

    $sqlEv = "SELECT * FROM evaluador WHERE Id = '{$uidEsc}'";
    if ($resEv = mysqli_query($conexion, $sqlEv)) {
        if (mysqli_num_rows($resEv) > 0) {
            $evaluador = mysqli_fetch_array($resEv);
            $sqlP = "SELECT Periodo FROM periodos WHERE PeriodoActivo = 1 LIMIT 1";
            if ($resP = mysqli_query($conexion, $sqlP)) {
                $periodo = mysqli_fetch_array($resP) ?: $periodo;
                mysqli_free_result($resP);
            }
            $sqlAs = "SELECT COUNT(*) AS c
                      FROM asignados
                      WHERE IdEvaluador = '{$uidEsc}'
                        AND Periodo = '{$periodo['Periodo']}'
                        AND IdEmpresa = '{$evaluador['IdEmpresa']}'";
            $resAs = mysqli_query($conexion, $sqlAs);
            $rowAs = $resAs ? mysqli_fetch_assoc($resAs) : ['c'=>0];
            $nAs = (int)($rowAs['c'] ?? 0);
            if ($resAs) mysqli_free_result($resAs);

            $sqlMov = "SELECT COUNT(DISTINCT IdEvaluado) AS c
                       FROM movimientos
                       WHERE idEvaluador = '{$uidEsc}'
                         AND IdEmpresa = '{$evaluador['IdEmpresa']}'
                         AND Periodo = '{$periodo['Periodo']}'";
            $resMov = mysqli_query($conexion, $sqlMov);
            $rowMov = $resMov ? mysqli_fetch_assoc($resMov) : ['c'=>0];
            $nMov = (int)($rowMov['c'] ?? 0);
            if ($resMov) mysqli_free_result($resMov);

            $porcentaje = ($nAs > 0) ? ($nMov * 100 / $nAs) : 0;
            $p = (float)number_format($porcentaje, 2, '.', '');
            $isEvaluador = true;
        }
        mysqli_free_result($resEv);
    }
}

// Nombre final a mostrar
$nombreBienvenida = $isEvaluador ? trim((string)($evaluador['Nombre'] ?? '')) : trim($nombreUsuario);

// (opcional) Nombre empresa para placeholder de logo
$nombreEmpresa = '';
if ($isEvaluador && !empty($evaluador['IdEmpresa'])) {
    $idEmp = (int)$evaluador['IdEmpresa'];
    $qEmp = mysqli_query($conexion, "SELECT Empresa FROM empresas WHERE Id = {$idEmp} LIMIT 1");
    if ($qEmp && $rEmp = mysqli_fetch_assoc($qEmp)) { $nombreEmpresa = $rEmp['Empresa']; }
    if ($qEmp) mysqli_free_result($qEmp);
}

// Logo central
$logoSrc = '';
$logoCandidates = [
    'images/logo.png','images/logo.jpg','images/logo.jpeg','images/logo.svg',
    'img/logo.png','img/logo.jpg','img/logo.svg',
    'Imagenes/logo.png','Imagenes/logo.jpg','Imagenes/logo_arcor.png',
    'assets/images/logo.png','assets/img/logo.png'
];
foreach ($logoCandidates as $relPath) {
    $fsPath = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relPath);
    if (is_file($fsPath)) { $logoSrc = $relPath; break; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bienvenidoss</title>
  <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="CSS/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="CSS/sticky-footer-navbar.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="CSS/fonts.css" rel="stylesheet" type="text/css" media="screen" />
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="jalert/jquery.alerts.js"></script>
  <link href="jalert/jquery.alerts.css" rel="stylesheet" type="text/css" />
  <script src="js/jquery.validate.js" type="text/javascript"></script>
  <script src="js/messages_es.js" type="text/javascript"></script>

  <style>
    .hero-home{ text-align:center; padding:24px 10px; margin:10px auto 20px; }
    .hero-home .logo-central{ max-width:320px; width:100%; height:auto; display:inline-block; }
    .hero-home .logo-fallback{ display:inline-block; padding:16px 20px; border:2px dashed #ddd; border-radius:10px; background:#fff; color:#666; }
    .hero-home .bienvenido{ margin-top:10px; font-size:18px; color:#333; }
  </style>
</head>
<body>
<div class="container-fluid">
  <header id="header">
    <div class="row">
      <?php include("Menu/Menu_Bootstrap.php"); ?>
    </div>
  </header>
</div>

<div class="container">
  <!-- Bloque central: Logo + Bienvenido -->
  <div class="hero-home">
    <?php if ($logoSrc): ?>
      <img class="logo-central" src="<?php echo h($logoSrc); ?>" alt="Logo">
    <?php else: ?>
      <div class="logo-fallback">
        <?php echo $nombreEmpresa ? 'Logo de '.h($nombreEmpresa) : 'Colocá tu logo en <code>images/logo.png</code>'; ?>
      </div>
    <?php endif; ?>

    <div class="bienvenido">
      Bienvenido<?php echo $nombreBienvenida ? ':' : ''; ?>
      <strong><?php echo h($nombreBienvenida); ?></strong>
    </div>
  </div>
  <!-- Contenido central -->
</div>

<?php if ($isEvaluador && $porcentaje != 100) { ?>
  <div class="col-sm-3 col-sm-offset-9">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Personas que faltan evaluar:</h3>
      </div>
      <?php
      $sql = "SELECT * FROM asignados
              INNER JOIN evaluado ON asignados.idevaluado = evaluado.id
              WHERE idevaluador = '{$_SESSION['uid']}'
                AND periodo = '{$periodo['Periodo']}'
                AND NOT EXISTS (
                   SELECT * FROM movimientos
                   WHERE movimientos.idevaluado = asignados.idevaluado
                     AND movimientos.idevaluador = '{$_SESSION['uid']}'
                     AND movimientos.periodo = '{$periodo['Periodo']}'
                )
                AND evaluado.baja IS NULL";
      if ($res = mysqli_query($conexion, $sql)) {
          while ($row = mysqli_fetch_array($res)) {
              echo "<div class='panel-body'>" . h($row['Nombre']) . "</div>";
          }
          mysqli_free_result($res);
      }
      ?>
    </div>
  </div>
<?php } ?>

<footer class="footer">
  <div class="container">
    <div>
      <span>Bienvenido: <strong><?php echo h($nombreBienvenida); ?></strong></span>
    </div>
    <div class="col-sm-12">
      <span>Llevas un total de Cargas de Evaluaciones: </span>
      <div class="progress">
        <div class="progress-bar" role="progressbar"
             aria-valuenow="<?php echo h($p); ?>" aria-valuemin="0" aria-valuemax="100"
             style="width:<?php echo h($p); ?>%;">
          <strong><?php echo number_format((float)$p, 2, ',', '') . "%"; ?></strong>
        </div>
      </div>
    </div>
  </div>
</footer>
</body>
</html>
