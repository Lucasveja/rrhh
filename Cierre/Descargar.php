<?php
// Guardar como UTF-8 sin BOM
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (!headers_sent()) header('Content-Type: text/plain; charset=utf-8');
        echo "Error fatal: {$e['message']} en {$e['file']}:{$e['line']}";
    }
});

session_start();
require_once __DIR__ . '/../conexionn/conexion.php';

// ====== Guard de sesión ======
if ( !isset($_SESSION['uid']) || (!isset($_SESSION['autenticado']) && !isset($_SESSION['Evaluador'])) ) {
    header('Location: /arcorsj/apps/rrhh/index.php');
    exit;
}

// ====== Helpers ======
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function table_exists(mysqli $cx, string $t): bool {
    $t = mysqli_real_escape_string($cx, $t);
    $r = $cx->query("SHOW TABLES LIKE '{$t}'");
    return $r && $r->num_rows > 0;
}
function normalizar_anio(string $v): string {
    $d = preg_replace('/\D+/', '', $v);
    if ($d === null) return '';
    if (strlen($d) === 6) return substr($d, -4);
    if (strlen($d) >= 4) return substr($d, -4);
    return '';
}

// ====== Identidades ======
$idEvaluador = (int)($_SESSION['uid'] ?? 0);
$idEmpresa   = (int)($_SESSION['idempresa'] ?? 0);

// Completar empresa desde evaluador si hace falta
if (!$idEmpresa && $idEvaluador) {
    if ($st = $conexion->prepare('SELECT IdEmpresa FROM evaluador WHERE Id = ? LIMIT 1')) {
        $st->bind_param('i', $idEvaluador);
        $st->execute();
        $st->bind_result($idEmpTmp);
        if ($st->fetch()) {
            $idEmpresa = (int)$idEmpTmp;
            $_SESSION['idempresa'] = $idEmpresa;
        }
        $st->close();
    }
}

// Datos display
$nombreEmpresa   = '';
$nombreEvaluador = '';
if ($idEmpresa) {
    if ($st = $conexion->prepare('SELECT Empresa FROM empresas WHERE Id = ? LIMIT 1')) {
        $st->bind_param('i', $idEmpresa);
        $st->execute();
        $st->bind_result($nombreEmpresa);
        $st->fetch();
        $st->close();
    }
}
if ($idEvaluador) {
    if ($st = $conexion->prepare('SELECT Nombre FROM evaluador WHERE Id = ? LIMIT 1')) {
        $st->bind_param('i', $idEvaluador);
        $st->execute();
        $st->bind_result($nombreEvaluador);
        $st->fetch();
        $st->close();
    }
}

// ====== Período por defecto ======
$periodo = '';
if (table_exists($conexion, 'periodos')) {
    if ($st = $conexion->prepare("SELECT Periodo FROM periodos WHERE PeriodoActivo = 1 LIMIT 1")) {
        $st->execute();
        $st->bind_result($p);
        if ($st->fetch()) $periodo = normalizar_anio((string)$p);
        $st->close();
    }
}
if ($periodo === '') $periodo = date('Y');

// ====== Lista de evaluados para el combo ======
$evaluados = [];
if ($st = $conexion->prepare("
        SELECT DISTINCT e.id, e.Nombre
        FROM asignados a
        INNER JOIN evaluado e ON e.id = a.IdEvaluado
        WHERE a.IdEmpresa=? AND a.IdEvaluador=? AND a.Periodo=? AND e.baja IS NULL
        ORDER BY e.Nombre
    ")) {
    $st->bind_param('iis', $idEmpresa, $idEvaluador, $periodo);
    $st->execute();
    $rs = $st->get_result();
    while ($row = $rs->fetch_assoc()) $evaluados[] = $row;
    $st->close();
}

// ====== Cierres ya realizados (para lista informativa) ======
$cerrados = 0;
if ($st = $conexion->prepare("
        SELECT COUNT(*) 
        FROM movimientos 
        WHERE IdEmpresa=? AND IdEvaluador=? AND Periodo=? AND fechacierre IS NOT NULL AND fechacierre <> '0000-00-00'
    ")) {
    $st->bind_param('iis', $idEmpresa, $idEvaluador, $periodo);
    $st->execute();
    $st->bind_result($cerrados);
    $st->fetch();
    $st->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Descargar Evaluación</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS locales -->
  <link rel="stylesheet" href="../CSS/bootstrap.min.css" />
  <link rel="stylesheet" href="../CSS/sticky-footer-navbar.css" />
  <link rel="stylesheet" href="../CSS/fonts.css" />
  <link rel="stylesheet" href="../style.css" />
  <style>
    body{background:#f7f7f7}
    .page-wrap{max-width:980px;margin:30px auto}
    .panel{box-shadow:0 2px 10px rgba(0,0,0,.06)}
    .help{color:#777;font-size:12px}
  </style>
</head>
<body>

<!-- NAV -->
<div class="container-fluid">
  <header id="header">
    <div class="row">
      <?php include __DIR__ . "/../Menu/Menu_Bootstrap.php"; ?>
    </div>
  </header>
</div>

<div class="page-wrap">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Descargar Evaluación</h3>
    </div>
    <div class="panel-body">

      <form id="formDesc" class="form-horizontal" onsubmit="return false;">
        <div class="form-group">
          <label class="col-sm-3 control-label">Empresa</label>
          <div class="col-sm-9">
            <p class="form-control-static"><strong><?= h($nombreEmpresa ?: ('ID '.$idEmpresa)) ?></strong></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Evaluador</label>
          <div class="col-sm-9">
            <p class="form-control-static"><strong><?= h($nombreEvaluador ?: ('ID '.$idEvaluador)) ?></strong></p>
          </div>
        </div>

        <div class="form-group">
          <label for="periodo" class="col-sm-3 control-label">Período</label>
          <div class="col-sm-3">
            <input type="text" id="periodo" class="form-control" value="<?= h($periodo) ?>" placeholder="YYYY o MM/YYYY" maxlength="7">
            <div class="help">Ingresá <code>YYYY</code> o <code>MM/YYYY</code>. Se usará el año.</div>
          </div>
          <div class="col-sm-6">
            <p class="form-control-static">Cierres registrados en el período: <strong><?= (int)$cerrados ?></strong></p>
          </div>
        </div>

        <div class="form-group">
          <label for="evaluado" class="col-sm-3 control-label">Evaluado</label>
          <div class="col-sm-6">
            <select id="evaluado" class="form-control">
              <option value="">-- Seleccione --</option>
              <?php foreach ($evaluados as $ev): ?>
                <option value="<?= (int)$ev['id'] ?>"><?= h($ev['Nombre']) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="help">La lista se llena según Empresa, Evaluador y Período.</div>
          </div>
          <div class="col-sm-3">
            <button class="btn btn-default" id="btnRecargar" type="button">Recargar lista</button>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-12 text-right">
            <a class="btn btn-primary" id="btnEval" target="_blank"
               href="/arcorsj/apps/rrhh/Informes/pdf_completo.php?empresa=<?= (int)$idEmpresa ?>&evaluador=<?= (int)$idEvaluador ?>&periodo=<?= h($periodo) ?>">
              <span class="glyphicon glyphicon-file"></span> PDF por Evaluador
            </a>
            <button type="button" class="btn btn-success" id="btnEva">
              <span class="glyphicon glyphicon-user"></span> PDF por Evaluado
            </button>
          </div>
        </div>
      </form>

      <hr>
      <p class="help">
        Para descargar por <strong>Evaluador</strong> se genera un PDF combinado (una página por persona).
        Para descargar por <strong>Evaluado</strong> seleccioná a la persona y presioná el botón verde.
      </p>

    </div>
  </div>
</div>

<!-- JS -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
(function(){
  function normalizarAnio(v){
    if(!v) return '';
    var d = (''+v).replace(/\D+/g,'');
    if (d.length === 6) return d.slice(-4); // MMYYYY -> YYYY
    if (d.length >= 4)  return d.slice(-4);
    return '';
  }

  var $periodo = $('#periodo');
  $periodo.on('blur change', function(){
    var y = normalizarAnio(this.value);
    if (y) this.value = y;

    // Actualizar link de "por Evaluador"
    var href = '/arcorsj/apps/rrhh/Informes/pdf_completo.php?empresa=<?= (int)$idEmpresa ?>&evaluador=<?= (int)$idEvaluador ?>&periodo=' + encodeURIComponent(y || '<?= h($periodo) ?>');
    $('#btnEval').attr('href', href);
  });

  $('#btnEva').on('click', function(){
    var y  = normalizarAnio($periodo.val()) || '<?= h($periodo) ?>';
    var id = $('#evaluado').val();
    if (!id) { alert('Seleccioná un Evaluado.'); return; }
    var url = '/arcorsj/apps/rrhh/Informes/pdf_completo.php?empresa=<?= (int)$idEmpresa ?>&evaluador=<?= (int)$idEvaluador ?>&evaluado=' + encodeURIComponent(id) + '&periodo=' + encodeURIComponent(y);
    var w = window.open(url, '_blank');
    if (!w) alert('Permití ventanas emergentes para ver el PDF.');
  });

  $('#btnRecargar').on('click', function(){
    // Simple recarga con el período normalizado
    var y = normalizarAnio($periodo.val()) || '<?= h($periodo) ?>';
    location.href = 'Descargar.php?periodo=' + encodeURIComponent(y);
  });

  // Si llega ?periodo= en la URL (recarga), mantenerlo en el input
  <?php if (isset($_GET['periodo'])): ?>
  $('#periodo').val('<?= h(normalizar_anio($_GET["periodo"])) ?>').trigger('change');
  <?php endif; ?>
})();
</script>
</body>
</html>
