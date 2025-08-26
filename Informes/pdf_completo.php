<?php
/**
 * Genera:
 *  - Un PDF por Evaluado (si GET trae ?evaluado=ID) -> usa pdf_simple.php en modo SILENT.
 *  - Un PDF COMBINADO (todas las personas del Evaluador para Empresa/Periodo): arma un único HTML con page-breaks y lo renderiza con Dompdf (sin PDFMerger).
 *
 * Requiere:
 *  - apps/rrhh/vendor/dompdf/autoload.inc.php (o autoload.inc)
 *  - carpeta ./PDF con permisos de escritura
 */
ob_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(600);

/* ---- Captura fatales ---- */
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Error fatal: {$e['message']} en {$e['file']}:{$e['line']}\n";
    }
});

/* --------- Shim mysql_* -> mysqli (compat) --------- */
if (!function_exists('mysql_connect')) {
    $GLOBALS['__MYSQLI_CONN'] = null;
    function mysql_connect($h=null,$u=null,$p=null){$l=mysqli_connect($h??ini_get('mysqli.default_host'),$u??ini_get('mysqli.default_user'),$p??ini_get('mysqli.default_pw'));$GLOBALS['__MYSQLI_CONN']=$l;return $l;}
    function mysql_select_db($d,$l=null){$l=$l?:($GLOBALS['__MYSQLI_CONN']??null);return $l?mysqli_select_db($l,$d):false;}
    function mysql_query($q,$l=null){$l=$l?:($GLOBALS['__MYSQLI_CONN']??null);return $l?mysqli_query($l,$q):false;}
    function mysql_fetch_array($r,$t=MYSQLI_ASSOC){return mysqli_fetch_array($r,$t);}
    function mysql_num_rows($r){return mysqli_num_rows($r);}
    function mysql_real_escape_string($s,$l=null){$l=$l?:($GLOBALS['__MYSQLI_CONN']??null);return $l?mysqli_real_escape_string($l,$s):addslashes($s);}
    function mysql_error($l=null){$l=$l?:($GLOBALS['__MYSQLI_CONN']??null);return $l?mysqli_error($l):'mysqli link not initialized';}
    function mysql_close($l=null){$l=$l?:($GLOBALS['__MYSQLI_CONN']??null);return $l?mysqli_close($l):true;}
}

/* ---- Conexión ---- */
require_once __DIR__ . '/../conexionn/conexion.php';
$db = (isset($conexion) && $conexion instanceof mysqli) ? $conexion : ($GLOBALS['__MYSQLI_CONN'] ?? null);
if (!$db) { header('Content-Type:text/plain; charset=utf-8'); echo "Sin conexión a BD."; exit; }

/* --------- Dompdf (ruta que me indicaste) --------- */
if (!class_exists('Dompdf\\Dompdf')) {
    $try1 = __DIR__ . '/../vendor/dompdf/autoload.inc.php';
    $try2 = __DIR__ . '/../vendor/dompdf/autoload.inc';
    if (file_exists($try1)) {
        require_once $try1;
    } elseif (file_exists($try2)) {
        require_once $try2;
    }
}
if (!class_exists('Dompdf\\Dompdf')) {
    $alt = __DIR__ . '/../vendor/dompdf/vendor/autoload.php';
    if (file_exists($alt)) require_once $alt;
}
if (!class_exists('Dompdf\\Dompdf')) {
    header('Content-Type:text/plain; charset=utf-8');
    echo "No se encontró Dompdf. Verificá apps/rrhh/vendor/dompdf/autoload.inc.php";
    exit;
}

use Dompdf\Dompdf;
use Dompdf\Options;

/* ---- Helpers ---- */
if (!function_exists('normalizar_anio')) {
    function normalizar_anio(string $v): string {
        $d = preg_replace('/\D+/', '', $v);
        if ($d === null) return '';
        if (strlen($d) === 6) return substr($d, -4);
        if (strlen($d) === 4) return $d;
        if (preg_match('/(\d{4})/',$d,$m)) return $m[1];
        return '';
    }
}

/* ---- Parámetros ---- */
$empresa   = isset($_GET['empresa'])   ? (int)$_GET['empresa']   : 0;
$evaluador = isset($_GET['evaluador']) ? (int)$_GET['evaluador'] : 0;
$evaluado  = isset($_GET['evaluado'])  ? (int)$_GET['evaluado']  : 0;
$periodo   = isset($_GET['periodo'])   ? (string)$_GET['periodo'] : '';
$periodo   = normalizar_anio($periodo);

if ($empresa <= 0 || $evaluador <= 0 || $periodo === '') {
    header('Content-Type:text/plain; charset=utf-8');
    echo "Parámetros inválidos. Requiere empresa, evaluador, periodo (YYYY).";
    exit;
}

/* ---- Directorio salida ---- */
$pdfDirAbs = __DIR__ . '/PDF';
$pdfDirRel = 'PDF';
if (!is_dir($pdfDirAbs)) { @mkdir($pdfDirAbs, 0775, true); }

/* ---- Caso: evaluado puntual -> delego a pdf_simple SILENT ---- */
if ($evaluado > 0) {
    define('PDF_SIMPLE_SILENT', true);
    $_GET['empresa']   = (string)$empresa;
    $_GET['evaluador'] = (string)$evaluador;
    $_GET['evaluado']  = (string)$evaluado;
    $_GET['periodo']   = (string)$periodo;

    $PDF_SIMPLE_RESULT = null;
    include __DIR__ . '/pdf_simple.php';

    header('Content-Type: text/html; charset=utf-8'); ?>
    <!doctype html><html lang="es"><head><meta charset="utf-8"><title>PDF generado</title>
    <style>body{font-family:Arial,sans-serif;padding:20px}a.btn{display:inline-block;padding:10px 14px;border:1px solid #0a7;border-radius:4px;text-decoration:none}</style>
    </head><body>
    <h3>PDF del evaluado</h3>
    <?php if (is_array($PDF_SIMPLE_RESULT) && !empty($PDF_SIMPLE_RESULT['ok'])): ?>
        <p><a class="btn" href="<?= htmlspecialchars($PDF_SIMPLE_RESULT['pathRel'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Abrir / Descargar</a></p>
    <?php else: ?>
        <pre><?= htmlspecialchars($PDF_SIMPLE_RESULT['error'] ?? 'Error al generar PDF.', ENT_QUOTES, 'UTF-8') ?></pre>
    <?php endif; ?>
    </body></html>
    <?php exit;
}

/* ---- Caso: combinado (todas las personas) ---- */
$evaluados = [];
$sql = "SELECT DISTINCT e.id AS id, e.Nombre AS nombre
        FROM asignados a
        INNER JOIN evaluado e ON e.id = a.IdEvaluado
        WHERE a.IdEmpresa = ?
          AND a.IdEvaluador = ?
          AND a.Periodo = ?
          AND e.baja IS NULL
        ORDER BY e.Nombre";
if ($st = $db->prepare($sql)) {
    $st->bind_param('iis', $empresa, $evaluador, $periodo);
    $st->execute();
    $rs = $st->get_result();
    while ($row = $rs->fetch_assoc()) $evaluados[] = ['id'=>(int)$row['id'], 'nombre'=>$row['nombre']];
    $st->close();
}
if (!$evaluados) { header('Content-Type:text/plain; charset=utf-8'); echo "No hay evaluados para esos filtros."; exit; }

/* ---- Armar un ÚNICO HTML con page-breaks ---- */
$css = '<style>*{box-sizing:border-box}body{font-family:DejaVu Sans,Arial,Helvetica,sans-serif;font-size:12px;color:#111}
.hdr{border-bottom:2px solid #444;margin-bottom:10px;padding-bottom:6px}.title{font-size:18px;font-weight:bold}
.meta{font-size:12px;color:#555;margin-top:2px}.section h3{background:#f0f0f0;padding:6px 8px;border:1px solid #e0e0e0;margin:16px 0 8px}
.table{width:100%;border-collapse:collapse;margin-top:8px}.table td{border:1px solid #ddd;padding:6px 8px;vertical-align:top}
.kv td.k{width:28%;background:#fbfbfb;font-weight:bold}.badge{display:inline-block;padding:3px 8px;border:1px solid #999;border-radius:10px}
.footer{margin-top:40px;font-size:10px;color:#777;text-align:right}.pb{page-break-after:always}</style>';

$html = $css;

foreach ($evaluados as $idx => $ev) {
    $idEva = (int)$ev['id'];

    // Datos mínimos por cada evaluado
    $q1 = $db->prepare("SELECT Empresa FROM empresas WHERE Id=? LIMIT 1");
    $q1->bind_param('i', $empresa); $q1->execute(); $q1->bind_result($empresaNom); $q1->fetch(); $q1->close();

    $q2 = $db->prepare("SELECT Nombre, Legajo FROM evaluador WHERE Id=? LIMIT 1");
    $q2->bind_param('i', $evaluador); $q2->execute(); $q2->bind_result($evaldorNom, $evaldorLeg); $q2->fetch(); $q2->close();

    $q3 = $db->prepare("SELECT Nombre, Legajo FROM evaluado WHERE id=? LIMIT 1");
    $q3->bind_param('i', $idEva); $q3->execute(); $q3->bind_result($evaldoNom, $evaldoLeg); $q3->fetch(); $q3->close();

    $q4 = $db->prepare("SELECT porcentaje, resultado, fortalezas, debilidades, compromiso, capacitacion
                        FROM desempenio WHERE periodo=? AND idempresa=? AND idevaluador=? AND idevaluado=? LIMIT 1");
    $q4->bind_param('siii', $periodo, $empresa, $evaluador, $idEva);
    $q4->execute(); $r4 = $q4->get_result(); $des = $r4->fetch_assoc(); $q4->close();
    if (!$des) $des = ['porcentaje'=>null,'resultado'=>'Sin registro','fortalezas'=>'—','debilidades'=>'—','compromiso'=>'—','capacitacion'=>'—'];

    $legEval = $evaldorLeg ? "(Legajo: {$evaldorLeg})" : "";
    $legEvao = $evaldoLeg  ? "(Legajo: {$evaldoLeg})"  : "";
    $fechaGen = date('d/m/Y H:i');

    $porc = ($des['porcentaje']!==null && $des['porcentaje']!=='') ? ((int)$des['porcentaje']).'%' : '—';
    $res  = htmlspecialchars((string)$des['resultado'], ENT_QUOTES,'UTF-8');
    $fort = nl2br(htmlspecialchars((string)$des['fortalezas'], ENT_QUOTES,'UTF-8'));
    $deb  = nl2br(htmlspecialchars((string)$des['debilidades'], ENT_QUOTES,'UTF-8'));
    $comp = nl2br(htmlspecialchars((string)$des['compromiso'],  ENT_QUOTES,'UTF-8'));
    $cap  = nl2br(htmlspecialchars((string)$des['capacitacion'],ENT_QUOTES,'UTF-8'));

    $html .= <<<HTML
<div class="hdr">
  <div class="title">Informe de Evaluación – {$periodo}</div>
  <div class="meta">
    <strong>Empresa:</strong> {$empresaNom}<br/>
    <strong>Evaluador:</strong> {$evaldorNom} {$legEval}<br/>
    <strong>Evaluado:</strong> {$evaldoNom} {$legEvao}<br/>
  </div>
</div>
<div class="section">
  <h3>Resumen</h3>
  <table class="table kv">
    <tr><td class="k">Periodo</td><td>{$periodo}</td></tr>
    <tr><td class="k">Resultado</td><td><span class="badge">{$res}</span></td></tr>
    <tr><td class="k">Cumplimiento</td><td>{$porc}</td></tr>
  </table>
</div>
<div class="section"><h3>Fortalezas</h3><table class="table"><tr><td>{$fort}</td></tr></table></div>
<div class="section"><h3>Debilidades</h3><table class="table"><tr><td>{$deb}</td></tr></table></div>
<div class="section"><h3>Compromiso de mejoras</h3><table class="table"><tr><td>{$comp}</td></tr></table></div>
<div class="section"><h3>Capacitación sugerida</h3><table class="table"><tr><td>{$cap}</td></tr></table></div>
<div class="footer">Generado el {$fechaGen}</div>
HTML;
    if ($idx < count($evaluados)-1) $html .= '<div class="pb"></div>'; // salto de página salvo en la última
}

/* ---- Render combinado ---- */
$mergedName = "Informe_Completo_Eval{$evaluador}_Emp{$empresa}_{$periodo}.pdf";
$mergedAbs  = $pdfDirAbs . '/' . $mergedName;
$mergedRel  = $pdfDirRel . '/' . $mergedName;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'portrait');
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->render();
file_put_contents($mergedAbs, $dompdf->output());

header('Content-Type: text/html; charset=utf-8'); ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>PDF combinado</title>
<style>body{font-family:Arial,sans-serif;padding:20px}a.btn{display:inline-block;padding:10px 14px;border:1px solid #0a7;border-radius:4px;text-decoration:none}</style>
</head><body>
<h3>Informe combinado</h3>
<p><a class="btn" href="<?= htmlspecialchars($mergedRel, ENT_QUOTES, 'UTF-8') ?>" target="_blank">Abrir PDF</a></p>
</body></html>
