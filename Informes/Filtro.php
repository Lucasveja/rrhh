<?php
declare(strict_types=1);
session_start();

// Shim to make legacy mysql_* in conexion.php work on PHP 7/8
if (!function_exists('mysql_connect')) {
    $GLOBALS['__MYSQLI_CONN'] = null;
    function mysql_connect($host = null, $user = null, $pass = null) {
        $link = mysqli_connect($host ?? ini_get('mysqli.default_host'),
                               $user ?? ini_get('mysqli.default_user'),
                               $pass ?? ini_get('mysqli.default_pw'));
        $GLOBALS['__MYSQLI_CONN'] = $link;
        return $link;
    }
    function mysql_select_db($dbname, $link = null) {
        $link = $link ?: ($GLOBALS['__MYSQLI_CONN'] ?? null);
        return $link ? mysqli_select_db($link, $dbname) : false;
    }
    function mysql_query($query, $link = null) {
        $link = $link ?: ($GLOBALS['__MYSQLI_CONN'] ?? null);
        return $link ? mysqli_query($link, $query) : false;
    }
    function mysql_fetch_array($result, $result_type = MYSQLI_ASSOC) { return mysqli_fetch_array($result, $result_type); }
    function mysql_num_rows($result) { return mysqli_num_rows($result); }
    function mysql_real_escape_string($string, $link = null) {
        $link = $link ?: ($GLOBALS['__MYSQLI_CONN'] ?? null);
        return $link ? mysqli_real_escape_string($link, $string) : addslashes($string);
    }
    function mysql_error($link = null) {
        $link = $link ?: ($GLOBALS['__MYSQLI_CONN'] ?? null);
        return $link ? mysqli_error($link) : 'mysqli link not initialized';
    }
    function mysql_close($link = null) {
        $link = $link ?: ($GLOBALS['__MYSQLI_CONN'] ?? null);
        return $link ? mysqli_close($link) : true;
    }
}

require_once '../conexionn/conexion.php'; // este archivo puede seguir usando mysql_*

// Intentar obtener el enlace mysqli desde el shim o una variable $conexion si existe
$db = $GLOBALS['__MYSQLI_CONN'] ?? (isset($conexion) && $conexion instanceof mysqli ? $conexion : null);
if (!$db) {
    // Si tu conexion.php utiliza otra variable, podés asignarla acá.
    // die('No database connection');
}

$action  = filter_input(INPUT_POST, 'combo', FILTER_UNSAFE_RAW) ?? '';
$idcombo = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$empresa = filter_input(INPUT_POST, 'emp', FILTER_VALIDATE_INT) ?: 0;
$periRaw = trim((string)(filter_input(INPUT_POST, 'per', FILTER_UNSAFE_RAW) ?? ''));

// Normalizar período a 'YYYY'
$periDigits = preg_replace('/\D+/', '', $periRaw);
$peri = '';
if ($periDigits !== '') {
    if (strlen($periDigits) === 6) { $peri = substr($periDigits, -4); }
    elseif (strlen($periDigits) === 4) { $peri = $periDigits; }
    else {
        if (preg_match('/(\d{4})/', $periDigits, $m)) { $peri = $m[1]; }
    }
}

// Solo atendemos combo='evaluador'
if ($action === 'evaluador' && $idcombo > 0 && $empresa > 0 && $peri !== '') {

    // OJO: columnas según tu DDL
    $sql = "
        SELECT DISTINCT e.id, e.Nombre AS nombre
        FROM asignados a
        INNER JOIN evaluado e ON e.id = a.IdEvaluado
        WHERE a.IdEvaluador = ?
          AND a.IdEmpresa   = ?
          AND a.Periodo     = ?
          AND e.baja IS NULL
        ORDER BY e.Nombre
    ";

    if ($db && ($stmt = $db->prepare($sql))) {
        // Periodo es VARCHAR en tu DDL => bind como string
        $stmt->bind_param('iis', $idcombo, $empresa, $peri);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            echo '<option value="' . (int)$row['id'] . '">' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
        }
        $stmt->close();
    } else {
        // Fallback simple (escapado) si no disponemos de prepare()
        $idcombo_i = (int)$idcombo;
        $empresa_i = (int)$empresa;
        $peri_s    = "'" . mysqli_real_escape_string($db, $peri) . "'";
        $sql2 = "SELECT DISTINCT e.id, e.Nombre AS nombre
                 FROM asignados a
                 INNER JOIN evaluado e ON e.id = a.IdEvaluado
                 WHERE a.IdEvaluador = $idcombo_i
                   AND a.IdEmpresa   = $empresa_i
                   AND a.Periodo     = $peri_s
                   AND e.baja IS NULL
                 ORDER BY e.Nombre";
        if ($db && ($rs = mysqli_query($db, $sql2))) {
            while ($row = mysqli_fetch_assoc($rs)) {
                echo '<option value="' . (int)$row['id'] . '">' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
            }
        }
    }
}
// Fin
?>
