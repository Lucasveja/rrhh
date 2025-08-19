<?php
// Requiere $conexion (mysqli) si se usan funciones con DB.
function insertaLog($operador,$cadena){
  global $conexion;
  $sql="INSERT INTO log (Operador,Cambios,Fecha,Hora) 
        VALUES('{$operador}','{$cadena}',CURDATE(),CURTIME())";
  if (isset($conexion)) { mysqli_query($conexion, $sql); }
}

function compare($campo,$tabla,$valor,$condicion){
  global $conexion;
  $sqlT = "SELECT $campo FROM $tabla WHERE $condicion";
  $consulT= isset($conexion) ? mysqli_query($conexion, $sqlT) : false;
  $rowT = $consulT ? mysqli_fetch_array($consulT) : [0=>null];
  if (empty($rowT[0]) && empty($valor)) return 0;
  if (empty($rowT[0])) $rowT[0]='vacio';
  return (trim($rowT[0])==$valor) ? 0 : $rowT[0];
}

function CalcularEdad($fechaNac){
  $f = explode("/",$fechaNac);
  $y = date("Y") - (int)$f[0];
  $m = date("m") - (int)$f[1];
  if ($m < 0) $y--;
  return $y;
}

function CalcularEdadAlIngresar($fechaNac,$fAlta){
  $n = explode("/",$fechaNac); 
  $a = explode("/",$fAlta);
  $y = (int)$a[2] - (int)$n[0]; 
  $m = (int)$a[1] - (int)$n[1];
  if ($m < 0) $y--;
  return $y;
}

function fechaBaseAInput($var){ 
  if($var<>''){ 
    $t=explode("/",$var); 
    return $t[2]."/".$t[1]."/".$t[0]; 
  } 
  return ''; 
}

function ultiPago($periodo){ return substr($periodo,0,2)."/".substr($periodo,2,4); }
function ObtenerPeriodo($fecha){ return substr($fecha, 3, 7); }
function restarDias($fecha,$dias){ return date("Y-m-d", strtotime("$fecha -$dias day")); }

function dateDiff($f1,$f2,$op){
  $s=strtotime($f1)-strtotime($f2);
  $mes=intval($s/2592000); 
  $d=intval($s/86400);
  if ($op=="m") return $mes;
  if ($op=="d") return $d;
  return 0;
}

function llenar($par,$hasta){ return str_pad($par, $hasta, '0', STR_PAD_LEFT); }
function llenarBlanco($par,$hasta){ return str_pad($par, $hasta, " ", STR_PAD_RIGHT); }
function llenarBlancoD($par,$hasta){ return str_pad($par, $hasta, " ", STR_PAD_LEFT); }
function right($v,$c){ return substr($v, ($c*-1)); }
function left($s,$c){ return substr($s, 0, $c); }

function fechaBaseAInputBis($var){ 
  if($var<>''){ 
    $t=explode("-",$var); 
    return $t[2]."/".$t[1]."/".$t[0]; 
  } 
  return ''; 
}

function fechaMysql($fecha){ 
  if(!empty($fecha)){ 
    $h=explode("/",$fecha); 
    return date("Y-m-d", mktime(0,0,0,$h[1],$h[0],$h[2])); 
  } 
  return ''; 
}

function MysqlF($fecha){ 
  $h=explode("/",$fecha); 
  return trim($h[2]).'-'.trim($h[1]).'-'.trim($h[0]); 
}

function dateadd2($date,$dd=0,$mm=0,$yy=0,$hh=0,$mn=0,$ss=0){ 
  $r=getdate(strtotime($date)); 
  return date("d/m/Y", mktime(($r["hours"]+$hh),($r["minutes"]+$mn),($r["seconds"]+$ss),($r["mday"]+$dd),($r["mon"]+$mm),($r["year"]+$yy))); 
}

function redondear($v){ return round($v * 100) / 100; }

function encriptar($cadena){
  $clave = 3; $encriptada = "";
  for ($k = 0; $k < strlen($cadena); $k++){
    $car = chr((ord(substr ($cadena, $k ,1)) + $clave) % 256);
    $d='\ '; if ($car==trim($d)){ $car='&'; }
    $encriptada .= $car;
  }
  return $encriptada ;
}

function desencriptar($encriptada){
  $clave = 3; $desencriptada = "";
  for ($k = 0; $k < strlen($encriptada); $k++){
    $car = chr((ord(substr ($encriptada, $k ,1)))); 
    if ($car=="&") { $car='Y'; }
    else { $car = chr((ord(substr ($encriptada, $k ,1)) + (256 - $clave)) % 256); }
    $desencriptada .= $car;
  }
  return $desencriptada;
}

function SiNo($valor){ return $valor==1 ? 'Sí' : 'No'; }
