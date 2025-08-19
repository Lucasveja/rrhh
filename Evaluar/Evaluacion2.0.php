<?php
session_start();
echo 'a'.$_SESSION['autenticado'];
echo 'b'.$_SESSION['Evaluador'];
// Incluir el archivo de conexión actualizado a MySQLi
include("../conexionn/conexion.php");

// error_reporting(0); // Ya manejado en conexion.php, puedes descomentarlo si lo prefieres aquí también

// Sesión
// Validar que el usuario esté logueado y exista un UID
if (! (($_SESSION['autenticado'] == 'SI' || $_SESSION['Evaluador'] == 'SI') && isset($_SESSION['uid']))) {
    // En caso de que el usuario no esté autenticado, crear un formulario y redireccionar a la
    // pantalla de login, enviando un código de error
?>
    <form name="formulario" method="post" action="../index.php">
        <input type="hidden" name="msg_error" value="2">
    </form>
    <script type="text/javascript">
        //document.formulario.submit();
    </script>
<?php
    exit; // Importante: Terminar la ejecución para evitar que se muestre el resto de la página
}

// Sacar datos del usuario que ha iniciado sesión
// Se usa la conexión $conexion definida en conexion.php
$sql = "SELECT nombre, idempresa, empresa FROM evaluador e INNER JOIN empresas m ON e.idempresa = m.id WHERE e.id = '" . $_SESSION['uid'] . "'";
$result = $conexion->query($sql); // Usar $conexion->query()

$nombreUsuario = "";
$idEmpresa = "";
$idEvaluador = $_SESSION['uid'];

// Formar el nombre completo del usuario
if ($result && $fila = $result->fetch_assoc()) { // Usar fetch_assoc()
    $nombreUsuario = $fila['nombre'];
    $idEmpresa = $fila['idempresa'];
}
// El cierre de conexión mysql_close($conexion); ya no es necesario aquí, lo maneja el objeto mysqli o el fin del script.

if (isset($_POST['boton']) && $_POST['boton'] == "Guardar") {
    $i = 1; // Reiniciar o asegurar que $i comienza en 1 para los campos dinámicos
    $sqlOs = "SELECT id, competencia, titulo FROM competencias WHERE idempresa='{$idEmpresa}'";
    $ruslt = query($sqlOs); // La función query() ya fue actualizada en conexion.php

    foreach ($ruslt->rows as $read) {
        $nivel = 'nivelAl' . $i;
        $idniv = 'idnivel' . $i;

        if (isset($_POST[$nivel]) && $_POST[$nivel] <> '') {
            // Usar mysqli_real_escape_string para sanear los datos antes de la consulta
            // ¡IMPORTANTE! Las sentencias preparadas son la MEJOR forma de evitar inyección SQL.
            // Esto es solo una medida de saneamiento básica para strings.
            $periodo_esc = $conexion->real_escape_string($_POST['periodo']);
            $evaluado_esc = $conexion->real_escape_string($_POST['evaluado']);
            $nivel_esc = $conexion->real_escape_string($_POST[$nivel]);
            $idniv_esc = $conexion->real_escape_string($_POST[$idniv]);
            $id_comp_esc = $conexion->real_escape_string($read['id']);
            $comp_esc = $conexion->real_escape_string($read['competencia']);


            $sqlIn = "INSERT INTO movimientos(periodo,idempresa,idevaluador,idevaluado,fechacarga,idcompetencia,competencia,nivelalcanzado,idnivel) VALUES('{$periodo_esc}','{$idEmpresa}','{$idEvaluador}','{$evaluado_esc}',CURDATE(),'{$id_comp_esc}','{$comp_esc}','{$nivel_esc}','{$idniv_esc}')";

            if ($conexion->query($sqlIn)) { // Usar $conexion->query()
?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Éxito!</strong> La actualización finalizó correctamente.
                </div>
            <?php
                $_POST['competencia'] = ''; // ¿Quizás debería ser $_POST['competencia_' . $i]? o revisar la lógica
            } else {
                // Si falla el INSERT, intenta un UPDATE
                $sqlUp = "UPDATE movimientos SET nivelalcanzado='{$nivel_esc}',fechacarga=CURDATE() WHERE periodo='{$periodo_esc}' AND idempresa='{$idEmpresa}' AND idevaluador='{$idEvaluador}' AND idevaluado='{$evaluado_esc}' AND idcompetencia='{$id_comp_esc}'";

                if ($conexion->query($sqlUp)) { // Usar $conexion->query()
            ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Éxito!</strong> La actualización finalizó correctamente.
                    </div>
                <?php
                } else {
                ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Error!</strong> Hubo un error en la actualización: <?php echo $conexion->error; ?>
                    </div>
<?php
                }
            }
        } // if nivel<>''
        $i++;
    } // foreach

    if (isset($_POST['optradio'])) { // desempeño global
        // Saneamiento de datos para la tabla 'desempenio'
        $periodo_des_esc = $conexion->real_escape_string($_POST['periodo']);
        $evaluado_des_esc = $conexion->real_escape_string($_POST['evaluado']);
        $porc_des_esc = $conexion->real_escape_string($_POST['porc']);
        $optradio_des_esc = $conexion->real_escape_string($_POST['optradio']);
        $observaciones_des_esc = $conexion->real_escape_string($_POST['observaciones']);
        $fortaleza_des_esc = $conexion->real_escape_string($_POST['fortaleza']);
        $debilidades_des_esc = $conexion->real_escape_string($_POST['debilidades']);
        $compromiso_des_esc = $conexion->real_escape_string($_POST['compromiso']);
        $capacitacion_des_esc = $conexion->real_escape_string($_POST['capacitacion']);


        $sqlGlobal = "INSERT INTO desempenio(periodo,idempresa,idevaluador,idevaluado,porcentaje,resultado,observaciones,fortalezas,debilidades,compromiso,capacitacion) VALUES('{$periodo_des_esc}','{$idEmpresa}','{$idEvaluador}','{$evaluado_des_esc}','{$porc_des_esc}','{$optradio_des_esc}','{$observaciones_des_esc}','{$fortaleza_des_esc}','{$debilidades_des_esc}','{$compromiso_des_esc}','{$capacitacion_des_esc}')";

        if (!$conexion->query($sqlGlobal)) { // Usar $conexion->query()
            // Ya estaba en la tabla, intentar actualizar
            $sqlGlobalUp = "UPDATE desempenio SET porcentaje='{$porc_des_esc}',resultado='{$optradio_des_esc}',observaciones='{$observaciones_des_esc}', fortalezas='{$fortaleza_des_esc}',debilidades='{$debilidades_des_esc}',compromiso='{$compromiso_des_esc}',capacitacion='{$capacitacion_des_esc}' WHERE periodo='{$periodo_des_esc}' AND idempresa='{$idEmpresa}' AND idevaluador='{$idEvaluador}' AND idevaluado='{$evaluado_des_esc}'";
            $conexion->query($sqlGlobalUp); // Usar $conexion->query()
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Developsys/Evaluación</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../fonts/style.css">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link href="../CSS/bootstrap.css" rel="stylesheet">
    <script type="text/javascript" src="../js/funciones_Turno.js"></script>
    <script src="../js/11.3/jquery.min.js"></script>
    <script>
        function LlamarJquery() {
            var combos = new Array();
            combos['evaluador'] = "evaluado";
            posicion = "evaluador";
            valor = 1;
            if (posicion == 'evaluador' && valor == 0) {
                $("#evaluado").html(' <option value="0" selected="selected">----------------</option>')
            } else {
                $("#" + combos[posicion]).html('<option selected="selected" value="0">Cargando...</option>')
                if (valor != "0" || posicion != 'ciudad') {
                    $.post("Filtro.php", {
                        combo: 'evaluador', // Nombre del combo
                        id: $("#idEval").get(0).value, // Valor seleccionado
                        emp: $("#idEmp").get(0).value,
                        per: $("#periodo").get(0).value
                    }, function(data) {
                        $("#" + combos[posicion]).html(data);
                    })
                }
            }
        }
    </script>
    <script>
        var patronPeriodo = new Array(2, 4)

        function mascaraPeriodo(d, sep, pat, nums) {
            if (d.valant != d.value) {
                val = d.value
                largo = val.length
                val = val.split(sep)
                val2 = ''
                for (r = 0; r < val.length; r++) {
                    val2 += val[r]
                }
                if (nums) {
                    for (z = 0; z < val2.length; z++) {
                        if (isNaN(val2.charAt(z))) {
                            letra = new RegExp(val2.charAt(z), "g")
                            val2 = val2.replace(letra, "")
                        }
                    }
                }
                val = ''
                val3 = new Array()
                for (s = 0; s < pat.length; s++) {
                    val3[s] = val2.substring(0, pat[s])
                    val2 = val2.substr(pat[s])
                }
                for (q = 0; q < val3.length; q++) {
                    if (q == 0) {
                        val = val3[q]
                    } else {
                        if (val3[q] != "") {
                            val += sep + val3[q]
                        }
                    }
                }
                d.value = val
                d.valant = val
            }
        } //mascara

        function recargar(id) {
            formu.oculto.value = id;
            formu.submit();
        } //recargar

        function Completar(obj, cant) {
            if (isNaN(obj.value)) {
                alert("Ingrese valor válido")
                obj.value = '';
                return false;
            }
            while (obj.value.length < cant)
                obj.value = '0' + obj.value;
        }

        function Marcar(niv, ids, compe, tbl) {
            var nombre;
            nombre = 'tabla' + tbl;
            var tableReg = document.getElementById(nombre);
            for (var i = 1; i < document.getElementById(nombre).rows.length; i++) {
                cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                found = false;
                for (var j = 0; j < cellsOfRow.length; j++) {
                    if (j == 2) {
                        cellsOfRow[j].innerHTML = '';
                    }
                }
            }
            var indice;
            indice = ids;
            document.getElementById(indice).innerHTML = niv + '<img src="../images/nota.png"></img>';
            nivell = 'nivelAl' + tbl;
            idnivell = 'idnivel' + tbl;
            document.getElementById(nivell).value = niv;
            document.getElementById(idnivell).value = ids;
            recorrer();
        } //marcar

        function recorrer() {
            var nombre;
            var acumula = 0;
            var promedio = 0;
            var cuenta = 0;

            for (var t = 1; t <= 8; t++) {
                nombre = 'tabla' + t;
                var tableReg = document.getElementById(nombre);
                for (var i = 1; i < document.getElementById(nombre).rows.length; i++) {
                    cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                    found = false;
                    for (var j = 2; j < cellsOfRow.length; j++) {
                        if (cellsOfRow[j].innerHTML != '') {
                            acumula = acumula + i; //obtengo la nota
                            cuenta = cuenta + 1; //cuanto las cantidad de notas
                        }
                    } //for j
                } //for i
            } //for t

            promedio = acumula / cuenta; //obtengo promedio

            $("#prome").html('');
            var cadena = "Promedio: " + promedio.toFixed(2);
            $("#prome").append(cadena);
            Controlar(promedio * 10);
        } //recorrer

        function Refrescar() {
            for (var i = 1; i < document.getElementById('tabla').rows.length; i++) {
                document.getElementById(i).innerHTML = '';
            }
        } //refrescar

        function CambioPeriodo() {
            document.getElementById("evaluado").selectedIndex = "0";
            LlamarJquery();
        }

        function Validar() {
            if (formu.periodo.value == null || formu.periodo.value == "") {
                formu.periodo.focus;
                alert('Ud. debe completar todos los datos');
                return false;
            }
            if (formu.evaluado.value == null || formu.evaluado.value == "") {
                formu.evaluado.focus;
                alert('Ud. debe completar todos los datos');
                return false;
            }
            // La validación de 'competencia' parece redundante si se evalúan varias competencias.
            // if (formu.competencia.value == null || formu.competencia.value == "") {
            //     formu.competencia.focus;
            //     alert('Ud. debe completar todos los datos');
            //     return false;
            // }
            return true;
        } //validar

        function ValidarGlobal(canti, porc, id) {
            // Este bloque de código está reseteando los checked boxes y luego intentando volver a marcarlos.
            // Parece que la lógica es un poco confusa. Podría simplificarse si el objetivo es solo validar
            // la consistencia entre el promedio y la selección del radio button.
            //
            // document.getElementById('E').checked = false;
            // document.getElementById('MB').checked = false;
            // document.getElementById('B').checked = false;

            // if ($(this).is(":checked") == false) {
            //     document.getElementById(id).checked = false;
            // } else {
            //     document.getElementById(id).checked = false;
            // }
            // var mar = document.getElementById('marcado').value;
            // document.getElementById(mar).checked = true;
            // return false; // Esto siempre hará que la función devuelva false, impidiendo la validación deseada.

            if (canti != 8) { // Asumiendo 8 es el número total de competencias
                alert('Existen Competencias Pendientes de Calificar');
                // document.getElementById(id).checked = false; // Quita el check si no hay 8 competencias calificadas
                return false;
            } else {
                if (id != Controlar(porc)) {
                    alert('El resultado de la evaluaciones no es coherente con la elección de su desempeño global');
                    return false;
                }
            }
            return true;
        } //validar

        function Controlar(porcentaje) {
            if (porcentaje >= 95) {
                document.getElementById('E').checked = true;
                document.getElementById('marcado').value = 'E';
                return 'E';
            }
            if (porcentaje >= 80 && porcentaje <= 94) {
                document.getElementById('MB').checked = true;
                document.getElementById('marcado').value = 'MB';
                return 'MB';
            }
            if (porcentaje >= 50 && porcentaje <= 79) {
                document.getElementById('B').checked = true;
                document.getElementById('marcado').value = 'B';
                return 'B';
            }
            if (porcentaje >= 35 && porcentaje <= 49) {
                document.getElementById('NM').checked = true;
                document.getElementById('marcado').value = 'NM';
                return 'NM';
            }
            if (porcentaje <= 34) {
                document.getElementById('NS').checked = true;
                document.getElementById('marcado').value = 'NS';
                return 'NS';
            }
        } //control
    </script>
    <style>
        td:hover {
            background: red
        }

        tr td:hover {
            background: blue;
        }

        tr:hover td {
            background: #F6AC89;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <header id="header" class="">
            <div class="row">
                <?php include("../Menu/Menu_Bootstrap.php"); ?>
            </div></header></div>
    <form class="form-horizontal" data-toggle="validator" id="formu" name="formu" method="post">
        <div class="container">
            <div class="row">
                <div id="cabecera_simple"> By Developsys</div>
            </div>
            <div id="segmento" style="margin-left:100px">
                <div style="text-align:center; margin:10px; font-size:16px; color:white; font-weight:bold">
                    <small>Evaluación del Personal</small>
                </div>
            </div>
            </br>
            <div class="form-group has-feedback ">
                <label class="col-sm-1 control-label">Empresa</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo $fila['empresa'] ?>" readonly required>
                </div>
                <label class="col-sm-1 control-label">Evaluador</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="evaluador" name="evaluador" value="<?php echo $nombreUsuario ?>" readonly required>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-1 control-label">Periodo</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="periodo" name="periodo" value="<?php echo $_POST['periodo'] ?? '' ?>" onChange="CambioPeriodo()" required>
                </div>
                <label class="col-sm-1 control-label">Evaluado</label>
                <div class="col-sm-3">
                    <select data-size="5" class="form-control" name="evaluado" id="evaluado" onChange="Refrescar()">
                        <option></option>
                        <?php
                        $periHay = $_POST['periodo'] ?? ''; // Usar operador de fusión null para evitar undefined index
                        $sqlOs = "SELECT e.id, e.nombre FROM asignados a INNER JOIN evaluado e ON a.idevaluado = e.id WHERE a.idevaluador='{$idEvaluador}' AND a.idempresa='{$idEmpresa}' AND a.periodo='{$periHay}'";
                        $ruslt = query($sqlOs); // La función query() ya fue actualizada

                        foreach ($ruslt->rows as $read) {
                            if (isset($_POST['evaluado']) && $_POST['evaluado'] == $read['id']) {
                        ?>
                                <option value="<?php echo $read['id'] ?>" selected><?php echo $read['nombre'] ?></option>
                            <?php
                            } else {
                            ?>
                                <option value="<?php echo $read['id'] ?>"><?php echo $read['nombre'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <input name="boton" type="submit" class="btn btn-default " value="Buscar" onClick="return Validar()">
            </div>
            <div class="form-group has-feedback ">
                <div class="form-group col-sm-2">
                    <div align="left"></div>
                </div>
            </div>
            <?php
            if (isset($_POST['boton']) && $_POST['boton'] == "Buscar") {
                $sqlDes = "SELECT * FROM desempenio WHERE periodo='{$_POST['periodo']}' AND idevaluado='{$_POST['evaluado']}' AND idevaluador='{$idEvaluador}' AND idempresa='{$idEmpresa}'";
                $consultaDes = $conexion->query($sqlDes); // Usar $conexion->query()
                $nums = $consultaDes->num_rows; // Usar $consultaDes->num_rows

                $e = $mb = $b = $nm = $ns = $marca = ''; // Inicializar variables

                if ($nums > 0) {
                    $rowDes = $consultaDes->fetch_assoc(); // Usar fetch_assoc()
                    switch ($rowDes['resultado']) {
                        case 'E':
                            $e = 'checked';
                            $marca = 'E';
                            break;
                        case 'MB':
                            $mb = 'checked';
                            $marca = 'MB';
                            break;
                        case 'B':
                            $b = 'checked';
                            $marca = 'B';
                            break;
                        case 'NM':
                            $nm = 'checked';
                            $marca = 'NM';
                            break;
                        case 'NS':
                            $ns = 'checked';
                            $marca = 'NS';
                            break;
                    }
                }

                $sqlSum = "SELECT SUM(nivelalcanzado) as suma FROM movimientos WHERE periodo='{$_POST['periodo']}' AND idevaluado='{$_POST['evaluado']}' AND idevaluador='{$idEvaluador}' AND idempresa='{$idEmpresa}'";
                $consultaSum = $conexion->query($sqlSum); // Usar $conexion->query()
                $rowSum = $consultaSum->fetch_assoc(); // Usar fetch_assoc()

                $sqlCant = "SELECT COUNT(idevaluador) as cantidad FROM movimientos WHERE periodo='{$_POST['periodo']}' AND idevaluado='{$_POST['evaluado']}' AND idevaluador='{$idEvaluador}' AND idempresa='{$idEmpresa}'";
                $consultaCant = $conexion->query($sqlCant); // Usar $conexion->query()
                $rowCant = $consultaCant->fetch_assoc(); // Usar fetch_assoc()

                if ($rowCant['cantidad'] > 0) {
                    $porcentaje = ($rowSum['suma'] * 100) / ($rowCant['cantidad'] * 10);
                    $cantidades = $rowCant['cantidad'];
                } else {
                    $porcentaje = 0;
                    $cantidades = 0;
                }
            ?>
                <h2>Competencias <small>Acumulado<?php echo ' ' . round($porcentaje, 2); ?> % <div id="prome"></div></small></h2>
                <p>
                    <?php
                    $i = 1;
                    $sqlOs = "SELECT id, competencia, titulo FROM competencias WHERE idempresa='{$idEmpresa}'";
                    $ruslt = query($sqlOs); // La función query() ya fue actualizada

                    foreach ($ruslt->rows as $read) {
                    ?>
                </p>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>"><?php echo $read['titulo'] ?></a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $i ?>" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?php
                                $periHay = $_POST['periodo'];
                                // Uso de $conexion directamente para la consulta, ya que query() no maneja este caso de retorno de 'cantidad' directamente
                                $sqlHay = "SELECT COUNT(nivelalcanzado) as cantidad FROM movimientos WHERE periodo='{$periHay}' AND idcompetencia='{$read['id']}' AND idevaluado='{$_POST['evaluado']}' AND idevaluador='{$idEvaluador}' AND idempresa='{$idEmpresa}' AND (fechacierre<>'' OR fechacierre IS NOT NULL)";
                                $consultaHay = $conexion->query($sqlHay);
                                $rowCant_Hay = $consultaHay->fetch_assoc();

                                if ($rowCant_Hay['cantidad'] > 0) {
                                ?>
                                    <div class="alert alert-warning; alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>¡Atención!</strong> Ya hay una fecha de cierre para la competencia seleccionada.
                                    </div>
                                <?php
                                    // exit; // Descomentar esto si quieres que la página termine aquí.
                                    // Si no haces exit, se seguirá mostrando el contenido de la tabla, pero el usuario no podrá modificar la competencia.
                                }
                                ?>
                                <table id="tabla<?php echo $i; ?>" class="table table-striped table-condensed table-bordered dt-responsive nowrap table-hover" cellspacing="0" width="50%">
                                    <thead>
                                        <tr>
                                            <th>Nivel</th>
                                            <th>Descripción</th>
                                            <th>Evaluación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // La consulta para obtener la competencia por ID y luego usar su 'competencia' parece redundante si ya tienes $read['competencia']
                                        // $sqlComp = "SELECT id, competencia FROM competencias WHERE id='{$read['id']}'";
                                        // $consultComp = $conexion->query($sqlComp);
                                        // $rowComp = $consultComp->fetch_assoc();
                                        $rowComp = $read; // Usar directamente el $read del foreach principal

                                        $sql1 = "SELECT nivel, descripcion, id FROM niveles WHERE codcompetencia='{$rowComp['competencia']}'";
                                        $ruslt1 = query($sql1);

                                        foreach ($ruslt1->rows as $read1) {
                                            echo "<tr onclick='Marcar(" . $read1['nivel'] . "," . $read1['id'] . "," . $rowComp['competencia'] . "," . $i . ")'>";
                                            echo '<td style="font-size:13px">' . $read1['nivel'] . '</td>';
                                            echo '<td style="font-size:13px">' . $read1['descripcion'] . '</td>';
                                            echo '<td id="' . $read1['id'] . '" style="font-size:13px;color: #AD140C; font-weight: bold; align:center">' . HayNota($_POST['periodo'], $read['id'], $_POST['evaluado'], $idEvaluador, $idEmpresa, $read1['nivel'], $conexion) . '</td>'; // Pasa $conexion a HayNota
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <input name="nivelAl<?php echo $i; ?>" id="nivelAl<?php echo $i; ?>" type="hidden">
                                <input name="idnivel<?php echo $i; ?>" id="idnivel<?php echo $i; ?>" type="hidden">
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                        $i++;
                    } //fin for competancias
            ?>
        </div>
        <script type="text/javascript">
            // For demo to fit into DataTables site builder...
            $('#registro')
                .removeClass('display')
                .addClass('table table-striped table-bordered');
        </script>
        <div id="recuadro" style="border:groove">
            <h3 align="center">Desempeño Global </h3>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Excelente</label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label><input type="radio" id="E" name="optradio" value="E" <?php echo $e; ?> onClick="ValidarGlobal(<?php echo $cantidades ?>,<?php echo round($porcentaje, 2) ?>,this.id)">Supera ampliamente los requerimientos del puesto</label>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Muy Bueno</label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label><input type="radio" id="MB" name="optradio" value="MB" <?php echo $mb; ?> onClick="ValidarGlobal(<?php echo $cantidades ?>,<?php echo round($porcentaje, 2) ?>,this.id)">Supera los requerimientos del puesto</label>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Bueno</label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label><input type="radio" id="B" name="optradio" value="B" <?php echo $b; ?> onClick="ValidarGlobal(<?php echo $cantidades ?>,<?php echo round($porcentaje, 2) ?>,this.id)">Alcanza los requerimientos del puesto</label>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Necesita Mejorar</label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label><input type="radio" id="NM" name="optradio" value="NM" <?php echo $nm; ?> onClick="ValidarGlobal(<?php echo $cantidades ?>,<?php echo round($porcentaje, 2) ?>,this.id)">No alcanza los requerimientos del puesto</label>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">No Satisfactorio</label>
                <div class="col-sm-4">
                    <div class="radio">
                        <label><input type="radio" id="NS" name="optradio" value="NS" <?php echo $ns; ?> onClick="ValidarGlobal(<?php echo $cantidades ?>,<?php echo round($porcentaje, 2) ?>,this.id)">Se aleja visiblemente de los requerimientos del puesto</label>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Observaciones del Evaluador</label>
                <div class="col-sm-7">
                    <textarea name="observaciones" cols="50" rows="5"><?php echo $rowDes['observaciones'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Fortaleza</label>
                <div class="col-sm-7">
                    <textarea name="fortaleza" cols="50" rows="5"><?php echo $rowDes['fortalezas'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Debilidades</label>
                <div class="col-sm-7">
                    <textarea name="debilidades" cols="50" rows="5"><?php echo $rowDes['debilidades'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Compromiso</label>
                <div class="col-sm-7">
                    <textarea name="compromiso" cols="50" rows="5"><?php echo $rowDes['compromiso'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="form-group has-feedback ">
                <label class="col-sm-5 control-label">Capacitación</label>
                <div class="col-sm-7">
                    <textarea name="capacitacion" cols="50" rows="5"><?php echo $rowDes['capacitacion'] ?? '' ?></textarea>
                </div>
            </div>
        </div>
        </br>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <input type="submit" class="btn btn-primary" value="Guardar" name="boton" onClick="return Validar()">
                <input type="submit" class="btn btn-alert" value="Cancelar">
                <input type="reset" class="btn btn-warning" value="Salir" onClick="window.location.assign('/apps/rrhh/inicio.php')">
            </div>
        </div>
        <?php
            } //buscar
        ?>
        </div><input name="idEmp" id="idEmp" type="hidden" value="<?php echo $idEmpresa ?>">
        <input name="idEval" id="idEval" type="hidden" value="<?php echo $idEvaluador ?>">
        <input name="porc" id="porc" type="hidden" value="<?php echo round($porcentaje, 2) ?>">
        <input name="marcado" id="marcado" type="hidden" value="<?php echo $marca; ?>">
    </form>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>
</html>
<?php
// Función HayNota actualizada para usar MySQLi
function HayNota($peri, $compe, $evalua, $dor, $empre, $niv, $conexion)
{
    $periodoHay = $peri;

    // Saneamiento básico para evitar inyección SQL (aunque las sentencias preparadas son mejores)
    $periodoHay_esc = $conexion->real_escape_string($periodoHay);
    $compe_esc = $conexion->real_escape_string($compe);
    $evalua_esc = $conexion->real_escape_string($evalua);
    $dor_esc = $conexion->real_escape_string($dor);
    $empre_esc = $conexion->real_escape_string($empre);

    $sqlHay = "SELECT nivelalcanzado FROM movimientos WHERE periodo='{$periodoHay_esc}' AND idcompetencia='{$compe_esc}' AND idevaluado='{$evalua_esc}' AND idevaluador='{$dor_esc}' AND idempresa='{$empre_esc}'";

    $consultaHay = $conexion->query($sqlHay); // Usar $conexion->query()
    
    if ($consultaHay && $rowHay = $consultaHay->fetch_assoc()) { // Usar fetch_assoc()
        if ($niv == $rowHay['nivelalcanzado']) {
            return $rowHay['nivelalcanzado'] . '<img src="../images/nota.png"></img>';
        }
    }
    return '';
}
?>