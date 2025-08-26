<?php
/**
 * Genera el PDF individual:
 * GET: empresa, evaluador, evaluado, periodo (YYYY o MM/YYYY)
 * - Si se define PDF_SIMPLE_SILENT: no imprime HTML ni hace exit; deja $PDF_SIMPLE_RESULT.
 * - Si NO está definido: muestra un link al PDF generado (uso directo por URL).
 */

ob_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(300);

$SILENT = defined('PDF_SIMPLE_SILENT');

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

/* --------- Conexión --------- */
require_once __DIR__ . '/../conexionn/conexion.php';
$db = (isset($conexion) && $conexion instanceof mysqli) ? $conexion : ($GLOBALS['__MYSQLI_CONN'] ?? null);
if (!$db) {
    $msg = "No hay conexión a BD.";
    if ($SILENT) { $PDF_SIMPLE_RESULT = ['ok'=>false,'error'=>$msg]; return; }
    header('Content-Type:text/plain; charset=utf-8'); echo $msg; exit;
}

/* --------- Dompdf (ruta que me indicaste) --------- */
// intentamos con autoload.inc.php y, por las dudas, con autoload.inc
if (!class_exists('Dompdf\\Dompdf')) {
    $try1 = __DIR__ . '/../vendor/dompdf/autoload.inc.php';
    $try2 = __DIR__ . '/../vendor/dompdf/autoload.inc';
    if (file_exists($try1)) {
        require_once $try1;
    } elseif (file_exists($try2)) {
        require_once $try2;
    }
}
// fallback extremo: algunas distribuciones tienen un vendor interno
if (!class_exists('Dompdf\\Dompdf')) {
    $alt = __DIR__ . '/../vendor/dompdf/vendor/autoload.php';
    if (file_exists($alt)) require_once $alt;
}

if (!class_exists('Dompdf\\Dompdf')) {
    $msg = "No se encontró Dompdf. Verificá que exista apps/rrhh/vendor/dompdf/autoload.inc.php";
    if ($SILENT) { $PDF_SIMPLE_RESULT = ['ok'=>false,'error'=>$msg]; return; }
    header('Content-Type:text/plain; charset=utf-8'); echo $msg; exit;
}

use Dompdf\Dompdf;
use Dompdf\Options;

/* --------- Helpers protegidos --------- */
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
if (!function_exists('fetch_one_assoc')) {
    function fetch_one_assoc(mysqli $db, string $sql, string $types, array $params): ?array {
        $st = $db->prepare($sql);
        if (!$st) return null;
        if ($types !== '') $st->bind_param($types, ...$params);
        if (!$st->execute()) { $st->close(); return null; }
        $rs = $st->get_result();
        $row = $rs ? $rs->fetch_assoc() : null;
        $st->close();
        return $row ?: null;
    }
}

/* --------- Parámetros --------- */
$empresa   = isset($_GET['empresa'])   ? (int)$_GET['empresa']   : 0;
$evaluador = isset($_GET['evaluador']) ? (int)$_GET['evaluador'] : 0;
$evaluado  = isset($_GET['evaluado'])  ? (int)$_GET['evaluado']  : 0;
$periodo   = isset($_GET['periodo'])   ? (string)$_GET['periodo'] : '';
$periodo   = normalizar_anio($periodo);

if ($empresa<=0 || $evaluador<=0 || $evaluado<=0 || $periodo==='') {
    $msg = "Parámetros inválidos. Requiere empresa, evaluador, evaluado, periodo (YYYY).";
    if ($SILENT) { $PDF_SIMPLE_RESULT = ['ok'=>false,'error'=>$msg]; return; }
    header('Content-Type:text/plain; charset=utf-8'); echo $msg; exit;
}

/* --------- Datos --------- */
$empresaRow = fetch_one_assoc($db,"SELECT Id AS id, Empresa AS nombre FROM empresas WHERE Id=? LIMIT 1",'i',[$empresa]);
$evaldorRow = fetch_one_assoc($db,"SELECT Id AS id, Nombre AS nombre, Legajo FROM evaluador WHERE Id=? LIMIT 1",'i',[$evaluador]);
$evaldoRow  = fetch_one_assoc($db,"SELECT id, Nombre AS nombre, Legajo FROM evaluado WHERE id=? LIMIT 1",'i',[$evaluado]);

$desempenio = fetch_one_assoc(
    $db,
    "SELECT porcentaje, resultado, fortalezas, debilidades, compromiso, capacitacion
     FROM desempenio
     WHERE periodo=? AND idempresa=? AND idevaluador=? AND idevaluado=? LIMIT 1",
    'siii', [$periodo, $empresa, $evaluador, $evaluado]
);
if (!$desempenio) $desempenio = [
    'porcentaje'=>null,'resultado'=>'Sin registro','fortalezas'=>'—','debilidades'=>'—','compromiso'=>'—','capacitacion'=>'—'
];

/* --------- Render --------- */
$empresaNom = $empresaRow['nombre'] ?? ('Empresa ID ' . $empresa);
$evaldorNom = $evaldorRow['nombre'] ?? ('Evaluador ID ' . $evaluador);
$evaldorLeg = $evaldorRow['Legajo'] ?? '';
$evaldoNom  = $evaldoRow['nombre'] ?? ('Evaluado ID ' . $evaluado);
$evaldoLeg  = $evaldoRow['Legajo'] ?? '';

$legajoEvaluadorTxt = $evaldorLeg !== '' ? "(Legajo: {$evaldorLeg})" : "";
$legajoEvaluadoTxt  = $evaldoLeg  !== '' ? "(Legajo: {$evaldoLeg})"  : "";
$fechaGen = date('d/m/Y H:i');

$porc =( $desempenio['porcentaje']!==null && $desempenio['porcentaje']!=='') ? (int)$desempenio['porcentaje'].'%' : '—';
$res  = htmlspecialchars((string)$desempenio['resultado'], ENT_QUOTES,'UTF-8');
$fort = nl2br(htmlspecialchars((string)$desempenio['fortalezas'], ENT_QUOTES,'UTF-8'));
$deb  = nl2br(htmlspecialchars((string)$desempenio['debilidades'], ENT_QUOTES,'UTF-8'));
$comp = nl2br(htmlspecialchars((string)$desempenio['compromiso'],  ENT_QUOTES,'UTF-8'));
$cap  = nl2br(htmlspecialchars((string)$desempenio['capacitacion'],ENT_QUOTES,'UTF-8'));

$css = '<style>*{box-sizing:border-box}body{font-family:DejaVu Sans,Arial,Helvetica,sans-serif;font-size:12px;color:#111}
.hdr{border-bottom:2px solid #444;margin-bottom:10px;padding-bottom:6px}.title{font-size:18px;font-weight:bold}
.meta{font-size:12px;color:#555;margin-top:2px}.section h3{background:#f0f0f0;padding:6px 8px;border:1px solid #e0e0e0;margin:16px 0 8px}
.table{width:100%;border-collapse:collapse;margin-top:8px}.table td{border:1px solid #ddd;padding:6px 8px;vertical-align:top}
.kv td.k{width:28%;background:#fbfbfb;font-weight:bold}.badge{display:inline-block;padding:3px 8px;border:1px solid #999;border-radius:10px}
.footer{margin-top:40px;font-size:10px;color:#777;text-align:right}</style>';

$html = <<<HTML
{$css}
<div class="hdr">
  <div class="title">Informe de Evaluación – {$periodo}</div>
  <div class="meta">
    <strong>Empresa:</strong> {$empresaNom}<br/>
    <strong>Evaluador:</strong> {$evaldorNom} {$legajoEvaluadorTxt}<br/>
    <strong>Evaluado:</strong> {$evaldoNom} {$legajoEvaluadoTxt}<br/>
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

/* --------- Guardar PDF --------- */
$pdfDirAbs = __DIR__ . '/PDF';
$pdfDirRel = 'PDF';
if (!is_dir($pdfDirAbs)) @mkdir($pdfDirAbs, 0775, true);

$name    = "Informe_Simple_{$evaluado}.pdf";
$pathAbs = $pdfDirAbs . '/' . $name;
$pathRel = $pdfDirRel . '/' . $name;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'portrait');
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->render();
file_put_contents($pathAbs, $dompdf->output());

if ($SILENT) { $PDF_SIMPLE_RESULT = ['ok'=>true,'pathAbs'=>$pathAbs,'pathRel'=>$pathRel]; return; }

header('Content-Type: text/html; charset=utf-8'); ?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>PDF generado</title>
<style>body{font-family:Arial,sans-serif;padding:20px}a.btn{display:inline-block;padding:10px 14px;border:1px solid #0a7;border-radius:4px;text-decoration:none}</style>
</head><body>
<h3>PDF generado</h3>
<p><a class="btn" href="<?= htmlspecialchars($pathRel, ENT_QUOTES, 'UTF-8') ?>" target="_blank">Abrir / Descargar</a></p>
</body></html>
