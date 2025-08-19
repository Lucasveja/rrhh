<HTML>
<HEAD>
 <TITLE>New Document</TITLE>
</HEAD>
<BODY>
<?
  /*Inserta en la tabla Log el DNI del operador, lo que hizo en el sistema, la fecha y hora de*/
  function insertaLog($operador,$cadena)
    {
         $sql="Insert into log (Operador,Cambios,Fecha,Hora) values('{$operador}','{$cadena}',CURDATE(),CURTIME())";

         $consulta= mysql_query($sql);

    }
  /*Compara un Campo de una tabla para verificar si cambio */
  function compare($campo,$tabla,$valor,$condicion)
  {
    /*$sqlT = "SHOW FIELDS FROM pagador";
       $consulT= mysql_query($sqlT);
        while($datos = mysql_fetch_array($consulT))
        {
          echo $datos['Field'];
         }*/
    $sqlT = "Select $campo from $tabla where $condicion";

    $consulT= mysql_query($sqlT);
    $rowT = mysql_fetch_array($consulT);

    if (empty($rowT[0])&& empty($valor))
    {
      return 0;
    }
    else
    {
      if (empty($rowT[0]))
      {
        $rowT[0]='vacio';
      }

    }

    if (trim($rowT[0])==$valor)
    {

      return 0;
    }
    else
    {

      return $rowT[0];

    }
    
  } //end function
  
 
  function CalcularEdad($fechaNac)//Año mes dia
  {

     $fecha = explode("/",$fechaNac);
     $anio_dif = date("Y") - $fecha[0];
     $mes_dif = date("m") - $fecha[1];
     $dia_dif = date("d") - $fecha[2];

    if ( $mes_dif < 0)//$dia_dif < 0 ||
        $anio_dif--;
     return $anio_dif;

  }//end function
  
   function CalcularEdadAlIngresar($fechaNac,$fAlta)//Año mes dia
  {

     $fechaN = explode("/",$fechaNac);
     $fechaA = explode("/",$fAlta);
     $anio_dif =$fechaA[2] - $fechaN[0];
     $mes_dif = $fechaA[1] - $fechaN[1];
     $dia_dif = $fechaA[0] - $fechaN[2];

    if ( $mes_dif < 0)//$dia_dif < 0 ||
        $anio_dif--;
     return $anio_dif;

  }//end function


  
  function fechaBaseAInput($var)
    {

      if($var<>'')
      {
       $time = explode("/",$var);
       $fecha=$time[2]."/".$time[1]."/".$time[0];
       return $fecha;
       }
       return '' ;
   }
   
  function ultiPago($periodo)
  {
    $primeras= substr( $periodo, 0, 2 );
    $segundas= substr( $periodo, 2, 4 );
    $retornar=$primeras."/".$segundas;
    return $retornar;
  }

  function ObtenerPeriodo($fecha)
  {
    $primeras= substr($fecha, 3, 7 );
    return $primeras;
  }
  
 function restarDias($fecha,$dias)
 {
   $hoy=$fecha;//date("Y-m-d"); // tu sabrás como la obtienes, solo asegurate que tenga este formato
    $dias= $dias; // los días a restar
    return date("Y-m-d", strtotime("$hoy -$dias day"));
 }
 function dateDiff($fecha1,$fecha2,$op)
  {
    //defino fecha 1
    $date1=$fecha1;//"2003-10-21";
    $date2=$fecha2;//"2003-10-10";


    $s = strtotime($date1)-strtotime($date2);
    $mes=intval($s/2592000);
    $d = intval($s/86400);
    $s -= $d*86400;
    $h = intval($s/3600);
    $s -= $h*3600;
    $m = intval($s/60);
    $s -= $m*60;

//$dif= (($d*24)+$h).hrs." ".$m."min";
    if ($op=="m")//obtiene el mes
   {
     $dif2= $mes.$space;//." ".$h.hrs." ".$m."min";
   }
 else
    {
       if($op=="d")
       {
         $dif2= $d.$space;  //obtiene dias
       }
    }
//echo "Diferencia en horas: ".$dif;


   return $dif2;
  }

  function llenar($par,$hasta) //llena una cadena con 0 con limite max de $hasta
  {
        return str_pad($par, $hasta, '0', STR_PAD_LEFT);
  }

   function llenarBlanco($par,$hasta) //llena una cadena con 0 con limite max de $hasta
  {

    return str_pad($par, $hasta, " ", STR_PAD_RIGHT);
  }
   function llenarBlancoI($par,$hasta) //llena una cadena con 0 con limite max de $hasta
  {

    return str_pad($par, $hasta, " ", STR_PAD_RIGHT);
  }
   function llenarBlancoD($par,$hasta) //llena una cadena con 0 con limite max de $hasta
  {

    return str_pad($par, $hasta, " ", STR_PAD_LEFT);
  }




  function right($value, $count)//corta la cedena de derecha a izquierda
   {
    return substr($value, ($count*-1));
   }

  function left($string, $count)//corta la cadena de izquierda a derecha
  {
    return substr($string, 0, $count);
  }

  function fechaBaseAInputBis($var)
    {

      if($var<>'')
      {
       $time = explode("-",$var);
       $fecha=$time[2]."/".$time[1]."/".$time[0];
       return $fecha;
       }
       return '' ;
   }
   function fechaMysql($fecha)
   {
     
     if(!is_null($fecha)and  $fecha<>'')
     {
      $hasta = explode("/",$fecha);
      $fechaM=date("Y-m-d", mktime(0,0,0,$hasta[1],$hasta[0],$hasta[2]));
     
      return $fechaM;
     }
     else
     {
       return '';
     }
   }

   function MysqlF($fecha)//dd/mm/Y
   {
     $hasta = explode("/",$fecha);
     $fechaM=trim($hasta[2]).'-'.trim($hasta[1]).'-'.trim($hasta[0]);

     return $fechaM;
   }

   function dateadd2($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0)//data=dd/mm/aaaa
  {
    $date_r = getdate(strtotime($date));
    $date_result = date("d/m/Y ", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mday"]+$dd),($date_r["mon"]+$mm),($date_r["year"]+$yy)));
    return $date_result;
  }

       
   


 function redondear($valor)//redondeo a 2 decimales
{
 $float_redondeado=round($valor * 100) / 100;
 return $float_redondeado;
}



 function encriptar($cadena){

     $clave = 3;
     // Cifrado

     $encriptada = "";
     for ($k = 0; $k < strlen($cadena); $k++){

        $car = chr((ord(substr ($cadena, $k ,1)) + $clave) % 256);
        $d='\ ';
        if ($car==trim($d)){
          $car='&';
        }
        $encriptada .= $car;
      }
      return $encriptada ;
 }
 
 function desencriptar($encriptada)
 {
   $clave = 3;
    $desencriptada = "";
    for ($k = 0; $k < strlen($encriptada); $k++)
    {

        $car = chr((ord(substr ($encriptada, $k ,1))));
       if ($car=="&")
        {
          $car='Y';
       }
       else
       {

        $car = chr((ord(substr ($encriptada, $k ,1))
             + (256 - $clave)) % 256);
        }
        $desencriptada .= $car;
    }

   return $desencriptada;
 }
 





 


function SiNo($valor)
{
   if($valor==1)
   {
     return 'Sí';
   }
   else
   {
     return 'No';
   }
}
 
  ?>
</BODY>
</HTML>

