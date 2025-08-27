<?php
session_start();
require_once __DIR__ . '/../conexionn/conexion.php';

// ====== Sesión / Identidades ======
$idEvaluador = (int)($_SESSION['uid'] ?? 0);
$idEmpresa   = (int)($_SESSION['idempresa'] ?? 0);

// Si no viene empresa en sesión, la buscamos por el evaluador
if (!$idEmpresa && $idEvaluador) {
    if ($st = $conexion->prepare('SELECT IdEmpresa FROM evaluador WHERE Id = ? LIMIT 1')) {
        $st->bind_param('i', $idEvaluador);
        $st->execute();
        $st->bind_result($idEmpTmp);
        if ($st->fetch()) {
            $idEmpresa = (int)$idEmpTmp;
            $_SESSION['idempresa'] = $idEmpresa; // cache
        }
        $st->close();
    }
}

// Datos bonitos para mostrar
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

// Año por defecto (actual)
$anioActual = (int)date('Y');

// helper
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Informe de Evaluaciones</title>

    <!-- Estilos locales (mismos que usa inicio.php) -->
    <link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../CSS/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../CSS/sticky-footer-navbar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../CSS/fonts.css" type="text/css" media="screen" />

    <style>
        body { background: #f7f7f7; }
        .page-wrap { max-width: 980px; margin: 30px auto; }
        .panel { box-shadow: 0 2px 10px rgba(0,0,0,.06); }
        .help { color:#777; font-size:12px; }
        .mt-10 { margin-top:10px; }
        .mt-20 { margin-top:20px; }
    </style>
</head>
<body>

<!-- NAV: el mismo de tu sitio -->
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
            <h3 class="panel-title">Informe de Evaluaciones</h3>
        </div>
        <div class="panel-body">

            <?php if (!$idEvaluador): ?>
                <div class="alert alert-danger">
                    No hay sesión iniciada. Inicie sesión para continuar.
                </div>
            <?php endif; ?>

            <form id="formInforme" class="form-horizontal" onsubmit="return false;">
                <input type="hidden" id="idEmp"  name="idEmp"  value="<?= h($idEmpresa); ?>">
                <input type="hidden" id="idEval" name="idEval" value="<?= h($idEvaluador); ?>">

                <div class="form-group">
                    <label class="col-sm-3 control-label">Empresa</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><strong><?=
                            h($nombreEmpresa ?: ('ID ' . $idEmpresa)); ?></strong></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Evaluador</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><strong><?=
                            h($nombreEvaluador ?: ('ID ' . $idEvaluador)); ?></strong></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="periodo" class="col-sm-3 control-label">Periodo</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="periodo" name="periodo"
                               placeholder="YYYY o MM/YYYY" value="<?= h($anioActual); ?>" maxlength="7">
                        <div class="help">Podés ingresar <code>YYYY</code> o <code>MM/YYYY</code>. Se usará el año.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="evaluado" class="col-sm-3 control-label">Evaluado</label>
                    <div class="col-sm-6">
                        <select id="evaluado" name="evaluado" class="form-control">
                            <option value="">-- Seleccione --</option>
                        </select>
                        <div class="help">La lista se llena según Empresa, Evaluador y Periodo.</div>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-default" id="btnRecargar" type="button">Recargar lista</button>
                    </div>
                </div>

                <div class="form-group mt-20">
                    <div class="col-sm-12 text-right">
                        <button type="button" id="btnPdfEval" class="btn btn-primary">
                            <span class="glyphicon glyphicon-file"></span> PDF por Evaluador
                        </button>
                        <button type="button" id="btnPdfEva" class="btn btn-success">
                            <span class="glyphicon glyphicon-user"></span> PDF por Evaluado
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS locales (mismos que usa inicio.php) -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script>
(function(){
    function normalizarAnio(valor){
        if(!valor) return '';
        var d = (''+valor).replace(/\D+/g,'');
        if (d.length === 6) return d.slice(-4); // MMYYYY -> YYYY
        if (d.length === 4) return d;          // YYYY
        var m = (''+valor).match(/(\d{4})/);
        return m ? m[1] : '';
    }

    function cargarEvaluados(){
        var idEval = $('#idEval').val();
        var idEmp  = $('#idEmp').val();
        var perRaw = $('#periodo').val();
        var per    = normalizarAnio(perRaw);

        if(!idEval || !idEmp || !per){
            $('#evaluado').html('<option value="">-- Complete Empresa/Evaluador/Periodo --</option>');
            return;
        }

        $('#evaluado').html('<option>Cargando...</option>');

        $.post('Filtro.php', {
            combo: 'evaluador',
            id: idEval,
            emp: idEmp,
            per: per
        }).done(function(html){
            if(!html || !$.trim(html).length){
                $('#evaluado').html('<option value="">-- Sin resultados --</option>');
            } else {
                $('#evaluado').html('<option value="">-- Seleccione --</option>' + html);
            }
        }).fail(function(){
            $('#evaluado').html('<option value="">-- Error al cargar --</option>');
        });
    }

    function abrirPDF(url){
        var win = window.open(url, '_blank');
        if(!win){ alert('Permití las ventanas emergentes (popups) para ver el PDF.'); }
    }

    $('#btnRecargar').on('click', cargarEvaluados);

    $('#periodo').on('change blur', function(){
        var y = normalizarAnio(this.value);
        if (y) this.value = y; // mostramos sólo el año
        cargarEvaluados();
    });

    // Botón PDF por Evaluador
    $('#btnPdfEval').on('click', function(){
        var idEval = $('#idEval').val();
        var idEmp  = $('#idEmp').val();
        var per    = normalizarAnio($('#periodo').val());
        if(!idEval || !idEmp || !per){
            return alert('Completá Empresa, Evaluador y Periodo.');
        }
        var url = 'pdf_completo.php?empresa='+ encodeURIComponent(idEmp) +
                  '&evaluador=' + encodeURIComponent(idEval) +
                  '&periodo='   + encodeURIComponent(per);
        abrirPDF(url);
    });

    // Botón PDF por Evaluado
    $('#btnPdfEva').on('click', function(){
        var idEval = $('#idEval').val();
        var idEmp  = $('#idEmp').val();
        var per    = normalizarAnio($('#periodo').val());
        var idEva  = $('#evaluado').val();
        if(!idEval || !idEmp || !per){
            return alert('Completá Empresa, Evaluador y Periodo.');
        }
        if(!idEva){
            return alert('Seleccioná un Evaluado.');
        }
        var url = 'pdf_completo.php?empresa='+ encodeURIComponent(idEmp) +
                  '&evaluador=' + encodeURIComponent(idEval) +
                  '&evaluado='  + encodeURIComponent(idEva) +
                  '&periodo='   + encodeURIComponent(per);
        abrirPDF(url);
    });

    // Primera carga
    cargarEvaluados();
})();
</script>
</body>
</html>
