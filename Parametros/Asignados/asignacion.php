<?php
session_start();

// Incluir el archivo de conexión a la base de datos
require_once("../../conexionn/conexion.php");

// Lógica PHP para procesar el formulario y obtener datos del servidor
// Por ejemplo, procesar la tabla según los parámetros recibidos
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $idEmpresa = $_GET["id_emp"];
    $periodo = $_GET["periodo"];

    // Realizar consultas a la base de datos según los parámetros recibidos
    $sql = "SELECT * FROM evaluado WHERE IdEmpresa='$idEmpresa'";
   
    $result = mysql_query($sql);
    $numRows = mysql_num_rows($result);

    // Inicializar la variable HTML para construir la tabla
    $html = '';

    if ($numRows != 0) {
        $html .= '<div class="col-lg-10">';
        $html .= '<table class="table table-hover">';
        $html .= '<tr>';
        $html .= '<th class="text-center">Legajo</th>';
        $html .= '<th class="text-center">Nombre</th>';
        $html .= '<th class="text-center">Area</th>';
        $html .= '<th class="text-center">Departamento</th>';
        $html .= '<th class="text-center">Posicion</th>';
        $html .= '<th class="text-center">Empresa</th>';
        $html .= '<th class="text-center">Evaluador</th>';
        $html .= '</tr>';

        while ($read = mysql_fetch_array($result)) {
            // Obtener información adicional si es necesario
            $sqlA = "SELECT * FROM areas WHERE Id='{$read['IdArea']}'";
            $resA = mysql_query($sqlA);
            $area = mysql_fetch_array($resA);

            $sqlD = "SELECT * FROM departamentos WHERE Id='{$read['IdDepto']}'";
            $resD = mysql_query($sqlD);
            $depto = mysql_fetch_array($resD);

            $sqlE = "SELECT * FROM empresas WHERE Id='{$read['IdEmpresa']}'";
            $resE = mysql_query($sqlE);
            $emp = mysql_fetch_array($resE);

            $sqlP = "SELECT * FROM posicion WHERE Id='{$read['IdPosicion']}'";
            $resP = mysql_query($sqlP);
            $pos = mysql_fetch_array($resP);

            $sqlAsig = "SELECT * FROM asignados WHERE idEmpresa='$idEmpresa' AND IdEvaluado='{$read['id']}' AND Periodo='$periodo'";
            $resAsig = mysql_query($sqlAsig);
            $nAsig = mysql_num_rows($resAsig);
            $asig = mysql_fetch_array($resAsig);

            $html .= '<tr>';
            $html .= '<td class="col-lg-1 text-center">' . $read['Legajo'] . '</td>';
            $html .= '<td class="col-lg-3 text-center">' . utf8_encode($read['Nombre']) . '</td>';
            $html .= '<td class="col-lg-1 text-center">' . $area['Area'] . '</td>';
            $html .= '<td class="col-lg-1 text-center">' . $depto['Depto'] . '</td>';
            $html .= '<td class="col-lg-1 text-center">' . $pos['Posicion'] . '</td>';
            $html .= '<td class="col-lg-2 text-center">' . utf8_encode($emp['Empresa']) . '</td>';
            $html .= '<td class="col-lg-3 text-center">';
            $html .= '<select name="evaluador" id="evaluador" class="form-control" onchange="asignar(\'' . $idEmpresa . '\', this.value, \'' . $read['id'] . '\', \'' . $periodo . '\', \'' . ($nAsig != 0 ? $asig['id'] : $nAsig) . '\');">';
            
            if ($nAsig != 0) {
                $html .= '<option value="-1">Seleccionar..</option>';

                $sqlEV = "SELECT * FROM evaluador ORDER BY Nombre";
                $resEV = mysql_query($sqlEV);
                while ($ev = mysql_fetch_array($resEV)) {
                    if ($asig['IdEvaluador'] == $ev['Id']) {
                        $html .= '<option value="' . $ev['Id'] . '" selected>' . $ev['Nombre'] . '</option>';
                    } else {
                        $html .= '<option value="' . $ev['Id'] . '">' . $ev['Nombre'] . '</option>';
                    }
                }
            } else {
                $html .= '<option value="-1">Seleccionar..</option>';

                $sqlEV = "SELECT * FROM evaluador WHERE IdEmpresa='$idEmpresa' ORDER BY Nombre";
                $resEV = mysql_query($sqlEV);
                while ($ev = mysql_fetch_array($resEV)) {
                    $html .= '<option value="' . $ev['Id'] . '">' . $ev['Nombre'] . '</option>';
                }
            }

            $html .= '</select>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';
    } else {
        $html .= '<div class="text-center"><h3>No Existen Datos</h3></div>';
    }

    // Devolver la tabla HTML como respuesta al cliente
    echo $html;
}
?>
