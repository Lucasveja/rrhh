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
require_once __DIR__ . '/../conexionn/conexion.php'; // debe exponer $conexion (mysqli)

// ====== Guard de sesión ======
if ( !isset($_SESSION['uid']) || (!isset($_SESSION['autenticado']) && !isset($_SESSION['Evaluador'])) ) {
    header('Location: /arcorsj/apps/rrhh/index.php');
    exit;
}

// ====== Helpers ======
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function table_exists(mysqli $cx, string $table): bool {
    $tbl = mysqli_real_escape_string($cx, $table);
    $res = mysqli_query($cx, "SHOW TABLES LIKE '{$tbl}'");
    return $res && mysqli_num_rows($res) > 0;
}
function normalizar_anio(string $v): string {
    $d = preg_replace('/\D+/', '', $v);
    if ($d === null) return '';
    if (strlen($d) === 6) return substr($d, -4); // MMYYYY -> YYYY
    if (strlen($d) >= 4) return substr($d, -4);  // ...YYYY
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

// ====== Estadísticas (asignados vs movimientos) ======
$totAsignados = 0; $totCargados = 0;
if ($st = $conexion->prepare("SELECT COUNT(*) FROM asignados WHERE IdEmpresa=? AND IdEvaluador=? AND Periodo=?")) {
    $st->bind_param('iis', $idEmpresa, $idEvaluador, $periodo);
    $st->execute();
    $st->bind_result($totAsignados);
    $st->fetch();
    $st->close();
}
if ($st = $conexion->prepare("SELECT COUNT(DISTINCT IdEvaluado) FROM movimientos WHERE IdEmpresa=? AND IdEvaluador=? AND Periodo=?")) {
    $st->bind_param('iis', $idEmpresa, $idEvaluador, $periodo);
    $st->execute();
    $st->bind_result($totCargados);
    $st->fetch();
    $st->close();
}
$pendientes = max(0, $totAsignados - $totCargados);

// ====== Cierre (POST) ======
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion']==='cerrar') {
    $perReq = normalizar_anio((string)($_POST['periodo'] ?? ''));
    if ($perReq === '') $perReq = $periodo;

    // Marcar cierre en movimientos (solo los que no tengan fechacierre)
    if ($st = $conexion->prepare("UPDATE movimientos SET fechacierre = NOW() WHERE IdEmpresa=? AND IdEvaluador=? AND Periodo=? AND (fechacierre IS NULL OR fechacierre='0000-00-00' OR fechacierre='')")) {
        $st->bind_param('iis', $idEmpresa, $idEvaluador, $perReq);
        $st->execute();
        $af = $st->affected_rows;
        $st->close();
        $mensaje = "Se marcaron {$af} registros como cerrados para el período {$perReq}.";
    } else {
        $mensaje = "No se pudo cerrar el período.";
    }

    // (Opcional) Notificación por email a administradores – robusto, no rompe si faltan tablas/columnas
    if (table_exists($conexion, 't_usuarios')) {
        // Detectar columna de email disponible
        $colEmail = null;
        $cols = [];
        if ($rs = $conexion->query("DESCRIBE t_usuarios")) {
            while ($r = $rs->fetch_assoc()) $cols[] = $r['Field'];
            $rs->close();
        }
        foreach (['email','tx_mail','tx_email','correo','mail'] as $c) {
            if (in_array($c, $cols, true)) { $colEmail = $c; break; }
        }
        if ($colEmail) {
            $sql = "SELECT {$colEmail} FROM t_usuarios WHERE {$colEmail} IS NOT NULL AND {$colEmail}<>''";
            // si existe id_tipousuario lo filtro como admin (=1)
            if (in_array('id_tipousuario', $cols, true)) $sql .= " AND id_tipousuario=1";
            $emails = [];
            if ($rs = $conexion->query($sql)) {
                while ($r = $rs->fetch_row()) { if (filter_var($r[0], FILTER_VALIDATE_EMAIL)) $emails[] = $r[0]; }
                $rs->close();
            }
            $emails = array_unique($emails);

            if ($emails) {
                $to = implode(',', $emails);
                $asunto = "Cierre de Evaluaciones – {$periodo}";
                $body = "<html><body style=\"font-family:Arial,sans-serif;font-size:14px\">
                    <h3>Se realizó el cierre de Evaluaciones</h3>
                    <p><strong>Empresa:</strong> ".h($nombreEmpresa)." (ID ".h($idEmpresa).")<br>
                       <strong>Evaluador:</strong> ".h($nombreEvaluador)." (ID ".h($idEvaluador).")<br>
                       <strong>Período:</strong> ".h($perReq)."</p>
                    <p><strong>Asignados:</strong> ".(int)$totAsignados." &nbsp; 
                       <strong>Cargados:</strong> ".(int)$totCargados." &nbsp; 
                       <strong>Pendientes:</strong> ".(int)$pendientes."</p>
                    <p>Este es un mensaje automático del sistema de RRHH.</p>
                    </body></html>";
                $hdr  = "MIME-Version: 1.0\r\n";
                $hdr .= "Content-Type: text/html; charset=UTF-8\r\n";
                $hdr .= "From: RRHH <no-reply@localhost>\r\n";
                // Silencioso: si falla, no interrumpe el flujo
                @mail($to, $asunto, $body, $hdr);
            }
        }
    }

    // Refrescar métricas luego del cierre
    header("Location: CierreyEnvio.php?ok=1&msg=".urlencode($mensaje));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Cierre y Envío</title>
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
      <h3 class="panel-title">Cierre y Envío</h3>
    </div>
    <div class="panel-body">

      <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success"><?= h($_GET['msg'] ?? 'Operación realizada.') ?></div>
      <?php elseif ($mensaje): ?>
        <div class="alert alert-info"><?= h($mensaje) ?></div>
      <?php endif; ?>

      <form method="post" class="form-horizontal">
        <input type="hidden" name="accion" value="cerrar">

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
            <input type="text" id="periodo" name="periodo" class="form-control" value="<?= h($periodo) ?>" placeholder="YYYY o MM/YYYY" maxlength="7">
            <div class="help">Ingresá <code>YYYY</code> o <code>MM/YYYY</code> (se usará el año).</div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Estado</label>
          <div class="col-sm-9">
            <p class="form-control-static">
              Asignados: <strong><?= (int)$totAsignados ?></strong> &nbsp; | &nbsp;
              Cargados: <strong><?= (int)$totCargados ?></strong> &nbsp; | &nbsp;
              Pendientes: <strong><?= (int)$pendientes ?></strong>
            </p>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-12 text-right">
            <a class="btn btn-default" target="_blank"
               href="/arcorsj/apps/rrhh/Informes/pdf_completo.php?empresa=<?= (int)$idEmpresa ?>&evaluador=<?= (int)$idEvaluador ?>&periodo=<?= h($periodo) ?>">
              Ver PDF combinado
            </a>
            <button type="submit" class="btn btn-primary"
                    onclick="var y=(this.form.periodo.value||'').replace(/\\D+/g,''); if(y.length>=4) this.form.periodo.value=y.slice(-4);">
              Cerrar y Enviar
            </button>
          </div>
        </div>
      </form>

      <hr>

      <p class="help">
        El botón <strong>Cerrar y Enviar</strong> marca como cerradas (fecha de cierre) las evaluaciones del período,
        y notifica por email a los administradores (si hay emails configurados). El PDF combinado usa Dompdf.
      </p>

    </div>
  </div>
</div>

<!-- JS -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
  (function(){
    var $p = document.getElementById('periodo');
    if ($p) $p.addEventListener('blur', function(){
      var d = (this.value||'').replace(/\D+/g,'');
      if (d.length >= 4) this.value = d.slice(-4);
    });
  })();
</script>
</body>
</html>
