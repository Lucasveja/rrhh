<?php
declare(strict_types=1);
session_start();
require_once '../conexionn/conexion.php';

$action  = filter_input(INPUT_POST, 'combo');
$idcombo = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$empresa = filter_input(INPUT_POST, 'emp', FILTER_VALIDATE_INT);
$periRaw = filter_input(INPUT_POST, 'per', FILTER_UNSAFE_RAW);

$peri = '';
if ($periRaw !== null) {
    $parts = explode('/', $periRaw);
    if (count($parts) >= 2) {
        $peri = $parts[0] . $parts[1];
    }
}

if ($action === 'evaluador' && $idcombo && $empresa && $peri !== '') {
    $stmt = $conexion->prepare(
        'SELECT e.id, e.nombre
         FROM asignados a
         INNER JOIN evaluado e ON a.idevaluado = e.id
         WHERE a.idevaluador = ? AND a.idempresa = ? AND a.periodo = ? AND a.baja IS NULL
         ORDER BY e.nombre'
    );
    if ($stmt) {
        $stmt->bind_param('iii', $idcombo, $empresa, (int)$peri);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8') . '">' .
                 htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') .
                 '</option>';
        }
        $stmt->close();
    }
}
?>
