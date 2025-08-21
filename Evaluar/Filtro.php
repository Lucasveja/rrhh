<?php
declare(strict_types=1);
session_start();
require_once '../conexionn/conexion.php';

/**
 * Entrada esperada (POST):
 *  - combo = 'evaluador'
 *  - id    = Id del evaluador (int)
 *  - emp   = Id de la empresa (int)
 *  - per   = Periodo. Acepta 'YYYY', 'MM/YYYY' o 'MMYYYY'
 */

// Leer parámetros
$action  = filter_input(INPUT_POST, 'combo', FILTER_UNSAFE_RAW) ?? '';
$idcombo = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$empresa = filter_input(INPUT_POST, 'emp', FILTER_VALIDATE_INT) ?: 0;
$periRaw = trim((string)(filter_input(INPUT_POST, 'per', FILTER_UNSAFE_RAW) ?? ''));

// Normalizar período a 'YYYY'
$periDigits = preg_replace('/\D+/', '', $periRaw ?? '');
if ($periDigits === null) { $periDigits = ''; }

if ($periDigits !== '') {
    // Si viene MMYYYY, me quedo con 'YYYY'
    if (strlen($periDigits) === 6) {
        $peri = substr($periDigits, -4);
    } elseif (strlen($periDigits) === 4) {
        $peri = $periDigits;
    } else {
        // Intento rescatar un año de 4 dígitos de lo que venga
        if (preg_match('/(\d{4})/', $periDigits, $m)) {
            $peri = $m[1];
        } else {
            $peri = '';
        }
    }
} else {
    $peri = '';
}

if ($action === 'evaluador' && $idcombo > 0 && $empresa > 0 && $peri !== '') {

    // ¡OJO con mayúsculas! Tus columnas son IdEvaluador, IdEmpresa, IdEvaluado, Periodo
    // 'baja' está en la tabla 'evaluado' (no en 'asignados')
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

    if ($stmt = $conexion->prepare($sql)) {
        // Periodo es VARCHAR en la DB => usar 's' para ese parámetro
        $stmt->bind_param('iis', $idcombo, $empresa, $peri);
        $stmt->execute();
        $res = $stmt->get_result();

        // Enviar las <option>
        while ($row = $res->fetch_assoc()) {
            $id     = (int)$row['id'];
            $nombre = (string)$row['nombre'];
            echo '<option value="' . $id . '">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        $stmt->close();
    } else {
        // Error al preparar (opcional: loguear)
        http_response_code(500);
    }
} else {
    // Parámetros insuficientes: devolver combo vacío (opcional)
    // echo '<option value="0">-- Sin datos --</option>';
}
