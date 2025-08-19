<?
 
/******************************************
 * Autocompletar creado por Geynen Montenegro Cochas
 * Copyright Geynen.
 * Fecha: 03-02-2011 Chiclayo - Perú
 * Version: 1.0
 * http://geynen.wordpress.com
 * Ref: http://blog.rarecore.eu/autocompleter-using-xajax-updated.html
 ******************************************/

require("xajax/xajax_core/xajax.inc.php");
require_once("conexionn/class.conexionDB.inc.php"); //incluimos la librelia xajax

$xajax= new xajax();
$xajax->configure('javascript URI','xajax/');

//$xajax->configure('debug', true);//ver errores

//require("datos/cado.php");
function llenar($par,$hasta) //llena una cadena con 0 con limite max de $hasta
  {
        return str_pad($par, $hasta, '0', STR_PAD_LEFT);
  }

function eliminarFila($idtempV,$id_campo, $cant_campos,$contado,$contados,$listado,$listados){

	$respuesta = new xajaxResponse();
	
$conn = new conexionBD ( ); //Genera una nueva coneccion
		$sqlN="select * from tempventas Where (operador=".$_GET[id].")";
		$resN=mysql_query($sqlN);
		$num=mysql_num_rows($resN);
		//$respuesta->alert($num);
		$sql="Delete from tempventas Where (id=".$idtempV.")";
		$res=mysql_query($sql);
	
		$cant_campos=$num;
	
	--$cant_campos; //Resto uno al numero de campos y si es cero borro todo
	
		$respuesta->Remove("rowDetalle_$id_campo"); //borro el detalle que indica el parametro id_campo
    $respuesta->assign("cant_campos", "value", $cant_campos);
	 $respuesta->assign("totCanti","value",$cant_campos);

	 $resta= $contados-$contado;
	  $respuesta->assign("tot_Contado", "value",$resta);
	 $respuesta->assign("totContado","value",$resta);

	  $resta= $listados-$listado;
	  $respuesta->assign("tot_Listado", "value",$resta);
	 $respuesta->assign("totListado","value",$resta);
	//}
	
	return $respuesta;
	
}
	
	

function listadopersona($campo,$frase,$pag,$TotalReg){


	Global $ObjPersona;
 	//Global $cnx;
	 $conn = new conexionBD ( ); //Genera una nueva coneccion

	$EncabezadoTabla=array("Producto","C&oacutedigo");
	$regxpag=10;
	$nr1=$TotalReg;
	$inicio=$regxpag*($pag-1);
	$limite="";
	$frase=utf8_decode($frase);
	 if($inicio==0){
		
				$rs = mysql_query("SELECT Distinct articulos.IDARTICULO , articulos.NOMBRE, articulos.CODIGO FROM articulos WHERE ".$campo." LIKE '%" . $frase . "%' ". $limite);
		$nr1= mysql_num_rows($rs);
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	
	$rs = mysql_query("SELECT Distinct  articulos.IDARTICULO, articulos.NOMBRE,articulos.CODIGO FROM articulos WHERE ".$campo." LIKE '%". $frase."%' ".$limite);
		$nr= mysql_num_rows($rs)*($pag);
	$CantCampos=4;//$rs->columnCount();
    $cadena="Encontrados: $nr de $nr1";
   $registros="<table id='tablaPersona' class=registros><tr>";
	for($i=0;$i<count($EncabezadoTabla);$i++){
	$registros.="<th>".$EncabezadoTabla[$i]."</th>";
	}
	$cont=0;
    while($reg=mysql_fetch_array($rs)){//$rs->fetch()
	   $cont++;
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr id='".$reg[0]."' class='$estilo' style='cursor:pointer;' onClick='mostrarPersona(".$reg[0].")'>";
	   for($i=0;$i<$CantCampos;$i++){
		   if($i<>0){
			   //LO SGTE PARA OBTENER LA PORcION DE TEXTO QUE COINCIDE Y CAMBIARLE DE ESTILO, $cadena2 -> está variable contiene el valor q coincide, al cual lo ubico en una etiqueta span para cambiarle de estilo.
				$posicion  = stripos($reg[$i], $frase);
				if($posicion>-1){
					$cadena1 = substr($reg[$i], 0, $posicion);
					$cadena2 = substr($reg[$i], $posicion, strlen($frase));
					$cadena3 = substr($reg[$i], ($posicion + strlen($frase)));

					$dato = $cadena1.'<span>'.$cadena2.'</span>'.$cadena3;
					$registros.= "<td>".$dato."</td>";
				}else{
					$registros.= "<td>".$reg[$i]."</td>";
					}
		   }
	   }
	   $registros.= "</tr>";
    }
	//PAGINACION
	$registros.="</table><br/><center>".$cadena."</center><br/><center>Pag: ";
	for($i=1;$i<=$nunPag;$i++){
		$registros.='<a href="#" onClick="javascript:pagPersona.value='.$i.';buscarPersona(event)">'.$i.' </a>';
	}

	$registros.='</center>';

	$registros=utf8_encode($registros);
	$objResp=new xajaxResponse();
	$objResp->assign('divregistrosPersona','innerHTML',$registros);
	$objResp->assign('TotalRegPersona','value',$nr1);

	return $objResp;
}

function cambiaLista($formu)
{


	 	 extract($formu);	
	Global $ObjPersona;
	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion
	
	
		
	$sql="Select * from tempventas Where operador='".$_GET['id']."'";
	$res=mysql_query($sql);
	//$objResp->alert($sql);
	$nums=mysql_num_rows($res);
	
	
	if($nums!=0)
	{
		
	$conn = new conexionBD (); //Genera una nueva coneccion
	 
		$sqlUpdate="Update tempventas Inner Join articuloslista On tempventas.idArt=articuloslista.idArt SET tempventas.precio=articuloslista.precio, tempventas.idLista=".$lista.",tempventas.total=articuloslista.precio Where tempventas.operador=".$_GET['id']." And articuloslista.idLista=".$lista."";
	$resU=mysql_query($sqlUpdate);
	
 extract($formu);

	$conn = new conexionBD (); //Genera una nueva coneccion
	$sqlDetalle="Select * From tempventas where operador=".$_GET['id']."";
	$resv=mysql_query($sqlDetalle);
	$numms=mysql_num_rows($resv);
		
		for($i=0;$i<=$numms;$i++)
	{
		
		if($numms!=0)
		{
			
	  $objResp->Remove("rowDetalle_$i");	//Elimino todos los elementos de la tabla
		}
		
	}
	
	
	 $tot_Contado=0;
	 $tot_Listado=0;
	 $tot_Total=0;
	 $totCanti=0;
	 $totContado=0;
	 $totListado=0;
	 $suma=0;
	 $sumaL=0;

$id_campos=1; 
	
	$objResp->assign('totCanti','value',$numms);

	
	while($reg=mysql_fetch_array($resv))
	{
	

 $objResp->assign('txtIdPersona','value',$reg['codigo']);
 $objResp->assign('frasePersona','value',utf8_encode($reg['descripcion']));


   

 $str_html_td1 = $txtNombre . '<input class="prueba" readonly id="hdnNombre_' . $id_campos . '" name="hdnNombre_' . $id_campos . '" value="' . $reg['codigo'] . '"/>' ;
 $str_html_td2 = "$txtEdad" . '<input class="prueba" readonly size=40 id="hdnEdad_' . $id_campos . '" name="hdnEdad_' . $id_campos . '" value="' .  utf8_encode($reg['descripcion']) . '"/>' ;
 $str_html_td3 = "$txtDireccion" . '<input  class="prueba" readonly id="hdnDireccion_' . $id_campos . '" size=10 name="hdnDireccion_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
//$str_html_td4 = "$selSexo" . '<input  class="prueba" readonly id="hdnSexo_' . $id_campos . '" size=10 name="hdnSexo_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
 $str_html_td5 = "$selSexoo" . '<input  class="prueba"  id="hdnSexoo_' . $id_campos . '" size=10 name="hdnSexoo_' . $id_campos . '" value="' . $reg[cantidad] . '"/>' ;
 $str_html_td7 = "$selSexooo" . '<input  class="prueba" readonly id="hdnSexooo_' . $id_campos . '" size=10 name="hdnSexooo_' . $id_campos . '" value="' . $reg[total] . '"/>' ;
 $str_html_td6 = '<img src="images/delete.png" style="cursor:pointer" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){xajax_eliminarFila('.$reg[id].','.$id_campos.', proyecto.cant_campos.value,'.$reg[precio].',proyecto.tot_Contado.value,'.$reg[precio].',proyecto.tot_Listado.value);}"/>';
 $str_html_td6 .= '<input type="hidden" id="hdnIdCampos_'. $id_campos .'" name="hdnIdCampos[]" size="5" value="'. $id_campos .'" />';

	$idRow = "rowDetalle_$id_campos";
	//$objResp->alert($idRow);
    $idTd = "tdDetalle_$id_campos";
	//$objResp->alert($idTd);



	$objResp->Create("tbDetalle", "tr", $idRow);
    $objResp->Create($idRow, "td", $idTd."1");     //creamos los campos
    $objResp->Create($idRow, "td", $idTd."2");
    $objResp->Create($idRow, "td", $idTd."3");
   // $objResp->Create($idRow, "td", $idTd."4");
	$objResp->Create($idRow, "td", $idTd."5");
	$objResp->Create($idRow, "td", $idTd."7");

    $objResp->Create($idRow, "td", $idTd."6");
	
	
 //     Esta parte podria estar dentro de algun ciclo iterativo  

    $objResp->Assign($idTd."1", "innerHTML", $str_html_td1);   //asignamos el contenido
    $objResp->Assign($idTd."2", "innerHTML", $str_html_td2);
    $objResp->Assign($idTd."3", "innerHTML", $str_html_td3);
    //$objResp->Assign($idTd."4", "innerHTML", $str_html_td4);
	 $objResp->Assign($idTd."5", "innerHTML", $str_html_td5);
	  $objResp->Assign($idTd."7", "innerHTML", $str_html_td7);
    $objResp->Assign($idTd."6", "innerHTML", $str_html_td6);

//  aumentamos el contador de campos 



	
	$id_campos++;
		$suma+=$reg['precio'];

	$objResp->assign('tot_Contado','value',$suma);
	$objResp->assign('totContado','value',$suma);


	$sumaL+=$reg['precio'];

	$objResp->assign('tot_Listado','value',$sumaL);
	$objResp->assign('totListado','value',$sumaL);

  //agregarFila();
   $objResp->assign('txtIdPersona','value','');
  $objResp->assign('frasePersona','value','');


}//end while
	
	}//end if*/

	return $objResp;
}


function VienedeTrabajo($formu)
{


	 	 extract($formu);	
	Global $ObjPersona;
	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion
	
	
		
	$sql="Select * from tempventas Where operador='".$_GET['id']."'";
	$res=mysql_query($sql);
	//$objResp->alert($sql);
	$numsss= mysql_num_rows($res);
//$objResp->alert('Entro VienedeTrabajo');
		//$objResp->alert($numsss);
	if($numsss!=0)
	{
	
	for($i=0;$i<=$numsss;$i++)
	{
		
	  $objResp->Remove("rowDetalle_$i");	//Elimino todos los elementos de la tabla
		
		
	}
	
	
	 $tot_Contado=0;
	 $tot_Listado=0;
	 $tot_Total=0;
	 $totCanti=0;
	 $totContado=0;
	 $totListado=0;
	 $suma=0;
	 $sumaL=0;

$id_campos=1; 
	
	$objResp->assign('totCanti','value',$numsss);

	
	
	
	
	
	while($reg=mysql_fetch_array($res))
	{
	

 $objResp->assign('txtIdPersona','value',$reg['codigo']);
 $objResp->assign('frasePersona','value',utf8_encode($reg['descripcion']));


   

 $str_html_td1 = $txtNombre . '<input class="prueba" readonly id="hdnNombre_' . $id_campos . '" name="hdnNombre_' . $id_campos . '" value="' . $reg['codigo'] . '"/>' ;
 $str_html_td2 = "$txtEdad" . '<input class="prueba" readonly size=40 id="hdnEdad_' . $id_campos . '" name="hdnEdad_' . $id_campos . '" value="' .  utf8_encode($reg['descripcion']) . '"/>' ;
 $str_html_td3 = "$txtDireccion" . '<input  class="prueba" readonly id="hdnDireccion_' . $id_campos . '" size=10 name="hdnDireccion_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
//$str_html_td4 = "$selSexo" . '<input  class="prueba" readonly id="hdnSexo_' . $id_campos . '" size=10 name="hdnSexo_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
 $str_html_td5 = "$selSexoo" . '<input  class="prueba"  id="hdnSexoo_' . $id_campos . '" size=10 name="hdnSexoo_' . $id_campos . '" value="' . $reg[cantidad] . '"/>' ;
 $str_html_td7 = "$selSexooo" . '<input  class="prueba" readonly id="hdnSexooo_' . $id_campos . '" size=10 name="hdnSexooo_' . $id_campos . '" value="' . $reg[total] . '"/>' ;
 $str_html_td6 = '<img src="images/delete.png" style="cursor:pointer" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){xajax_eliminarFila('.$reg[id].','.$id_campos.', proyecto.cant_campos.value,'.$reg[precio].',proyecto.tot_Contado.value,'.$reg[precio].',proyecto.tot_Listado.value);}"/>';
 $str_html_td6 .= '<input type="hidden" id="hdnIdCampos_'. $id_campos .'" name="hdnIdCampos[]" size="5" value="'. $id_campos .'" />';


	$idRow = "rowDetalle_$id_campos";
	//$objResp->alert($idRow);
    $idTd = "tdDetalle_$id_campos";
	//$objResp->alert($idTd);



	$objResp->Create("tbDetalle", "tr", $idRow);
    $objResp->Create($idRow, "td", $idTd."1");     //creamos los campos
    $objResp->Create($idRow, "td", $idTd."2");
    $objResp->Create($idRow, "td", $idTd."3");
   // $objResp->Create($idRow, "td", $idTd."4");
	$objResp->Create($idRow, "td", $idTd."5");
	$objResp->Create($idRow, "td", $idTd."7");

    $objResp->Create($idRow, "td", $idTd."6");
	
	
 //     Esta parte podria estar dentro de algun ciclo iterativo  

    $objResp->Assign($idTd."1", "innerHTML", $str_html_td1);   //asignamos el contenido
    $objResp->Assign($idTd."2", "innerHTML", $str_html_td2);
    $objResp->Assign($idTd."3", "innerHTML", $str_html_td3);
    //$objResp->Assign($idTd."4", "innerHTML", $str_html_td4);
	 $objResp->Assign($idTd."5", "innerHTML", $str_html_td5);
	  $objResp->Assign($idTd."7", "innerHTML", $str_html_td7);
    $objResp->Assign($idTd."6", "innerHTML", $str_html_td6);

//  aumentamos el contador de campos 



	
	$id_campos++;
		$suma+=$reg['precio'];

	$objResp->assign('tot_Contado','value',$suma);
	$objResp->assign('totContado','value',$suma);


	$sumaL+=$reg['precio'];

	$objResp->assign('tot_Listado','value',$sumaL);
	$objResp->assign('totListado','value',$sumaL);

  //agregarFila();
   $objResp->assign('txtIdPersona','value','');
  $objResp->assign('frasePersona','value','');


}//end while
	
	}//end if*/

	
	
	
	
	return $objResp;
}//trabajo


function mostrarPersona($idA,$formu)
{


	 extract($formu);
	 	
	Global $ObjPersona;
	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion
		
	 $sql = "SELECT idarticulo,nombre,codigo FROM articulos WHERE 1=1";
     $sql .= " AND idarticulo=".$idA; 
	//$objResp->alert($idA);
	$res=mysql_query($sql);
	$rowArt=mysql_fetch_array($res);
	
	$sqlPrecio="Select idArt,idLista,precio from articuloslista Where idArt=".$idA." and idLista=".$lista."";
	$res=mysql_query($sqlPrecio);
	$precio=mysql_fetch_array($res);
		//$objResp->alert($precio[precio]);
	
	if($cli=="")
	{
		$cli="Consumidor Final: ".$nombreC;	
	}
	//$objResp->alert($cli);	
		
	$sqltempVentas="Insert into tempventas (idArt,idLista,codigo,descripcion,precio,operador,total,cliente) Values (".$idA.",".$lista.",'".$rowArt[codigo]."','".$rowArt[nombre]."',".$precio[precio].",'".$_GET['id']."',".$precio[precio].",'".$cli."')";
	//$objResp->alert($sqltempVentas);
	$res=mysql_query($sqltempVentas);
	
		
	
  	
	
 extract($formu);
   
  
	$conn = new conexionBD (); //Genera una nueva coneccion
	$sqlDetalle="Select * From tempventas where operador=".$_GET['id']."";
	$resv=mysql_query($sqlDetalle);
	$numms=mysql_num_rows($resv);
		//$objResp->alert($numms);
	
	
	
	 for($i=0;$i<=$numms;$i++)
	{
		//$objResp->alert($i);
		if($numms!=0)
		{
	  $objResp->Remove("rowDetalle_$i");	//Elimino todos los elementos de la tabla
		}
		
	}
	
	
	 $tot_Contado=0;
	 $tot_Listado=0;
	 $tot_Total=0;
	 $totCanti=0;
	 $totContado=0;
	 $totListado=0;
	 $suma=0;
	 $sumaL=0;

$id_campos=1; 
	
	$objResp->assign('totCanti','value',$numms);

	
	
	
	
	
	while($reg=mysql_fetch_array($resv))
	{
	

 $objResp->assign('txtIdPersona','value',$reg['codigo']);
 $objResp->assign('frasePersona','value',utf8_encode($reg['descripcion']));


   

 $str_html_td1 = $txtNombre . '<input class="prueba" readonly id="hdnNombre_' . $id_campos . '" name="hdnNombre_' . $id_campos . '" value="' . $reg['codigo'] . '"/>' ;
 $str_html_td2 = "$txtEdad" . '<input class="prueba" readonly size=40 id="hdnEdad_' . $id_campos . '" name="hdnEdad_' . $id_campos . '" value="' .  utf8_encode($reg['descripcion']) . '"/>' ;
 $str_html_td3 = "$txtDireccion" . '<input  class="prueba" readonly id="hdnDireccion_' . $id_campos . '" size=10 name="hdnDireccion_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
//$str_html_td4 = "$selSexo" . '<input  class="prueba" readonly id="hdnSexo_' . $id_campos . '" size=10 name="hdnSexo_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
 $str_html_td5 = "$selSexoo" . '<input  class="prueba"  id="hdnSexoo_' . $id_campos . '" size=10 name="hdnSexoo_' . $id_campos . '" value="' . $reg[cantidad] . '"/>' ;
 $str_html_td7 = "$selSexooo" . '<input  class="prueba" readonly id="hdnSexooo_' . $id_campos . '" size=10 name="hdnSexooo_' . $id_campos . '" value="' . $reg[total] . '"/>' ;
 $str_html_td6 = '<img src="images/delete.png" style="cursor:pointer" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){xajax_eliminarFila('.$reg[id].','.$id_campos.', proyecto.totCanti.value,'.$reg[precio].',proyecto.tot_Contado.value,'.$reg[precio].',proyecto.tot_Listado.value);}"/>';
 $str_html_td6 .= '<input type="hidden" id="hdnIdCampos_'. $id_campos .'" name="hdnIdCampos[]" size="5" value="'. $id_campos .'" />';

	$idRow = "rowDetalle_$id_campos";
	//$objResp->alert($idRow);
    $idTd = "tdDetalle_$id_campos";
	//$objResp->alert($idTd);



	$objResp->Create("tbDetalle", "tr", $idRow);
    $objResp->Create($idRow, "td", $idTd."1");     //creamos los campos
    $objResp->Create($idRow, "td", $idTd."2");
    $objResp->Create($idRow, "td", $idTd."3");
   // $objResp->Create($idRow, "td", $idTd."4");
	$objResp->Create($idRow, "td", $idTd."5");
	$objResp->Create($idRow, "td", $idTd."7");

    $objResp->Create($idRow, "td", $idTd."6");
	
	
 //     Esta parte podria estar dentro de algun ciclo iterativo  

    $objResp->Assign($idTd."1", "innerHTML", $str_html_td1);   //asignamos el contenido
    $objResp->Assign($idTd."2", "innerHTML", $str_html_td2);
    $objResp->Assign($idTd."3", "innerHTML", $str_html_td3);
    //$objResp->Assign($idTd."4", "innerHTML", $str_html_td4);
	 $objResp->Assign($idTd."5", "innerHTML", $str_html_td5);
	  $objResp->Assign($idTd."7", "innerHTML", $str_html_td7);
    $objResp->Assign($idTd."6", "innerHTML", $str_html_td6);

//  aumentamos el contador de campos 



	
	$id_campos++;
		$suma+=$reg['precio'];

	$objResp->assign('tot_Contado','value',$suma);
	$objResp->assign('totContado','value',$suma);


	$sumaL+=$reg['precio'];

	$objResp->assign('tot_Listado','value',$suma);
	$objResp->assign('totListado','value',$suma);

  //agregarFila();
   $objResp->assign('txtIdPersona','value','');
  $objResp->assign('frasePersona','value','');


}//end while
  

	 return $objResp;
}




function mostrarOrden($id,$formu){


  Global $ObjPersona;
  //Global $cnx;

  $objResp=new xajaxResponse();
   $conn = new conexionBD ( ); //Genera una nueva coneccion
   $objResp->alert('entrooo');
   extract($formu);

   $sql = "SELECT descripcion,codigo,precio_lis,precio FROM movtrabajos WHERE 1=1";
     $sql .= " AND idTrabajo=".$id;
  //$objResp->alert($sql);
  $rs= mysql_query($sql);

$reg=mysql_fetch_array($rs);



  return $objResp;


}



function agregarFila(){

  $respuesta = new xajaxResponse();
   $respuesta->assign('prueba','value','1212');
	
	return $respuesta;
}

function mostrarDetalle($formu)
{

 $objResp=new xajaxResponse();
extract($formu);
$objResp->alert($num_campos);
$objResp->alert('entrooo-XAJAX');
$objResp->alert($hdnEdad_1);
return $objResp;
}


function calculaTotal($totCont,$formu)
{
	
 $objResp=new xajaxResponse();
 //$objResp->alert('entrooo-XAJAX');
 extract($formu);
 //$objResp->alert('Desde XAJAX: ');
 //$objResp->alert($pag0);
 $rest=$totCont-$pag0;
 
 if($rest!=0)
   {
    $objResp->assign('pago1',value,$rest);
    $objResp->assign('resto',value,$rest);
	}
 else
 {
 $objResp->assign('pago1',value,$rest);
 $objResp->assign('rest',value,0);
 }
 

 	
	
 return $objResp;

}



function limpiarTempVentas()
{
	$objResp=new xajaxResponse();
	
	
	
	$conn = new conexionBD (); //Genera una nueva coneccion
	$sql="Delete from tempventas where operador=".$_GET['id']."";
	//$objResp->alert($sql);
	$res=mysql_query($sql);
	$sql2="Delete from tempformapago where operador=".$_GET['id']."";
	$res2=mysql_query($sql2);
	$sql3="Delete from tempdatosfpagos where operador=".$_GET['id']."";
	$res3=mysql_query($sql3);
	
	return $objResp;
	
}



/*********************Si tiene Algo la tabla tempventas, q la muestre******************************/
/**************************************************************************************************/

function mostrar_TDetalles($formu)
{


	extract($formu);	
	Global $ObjPersona;
	$objResp=new xajaxResponse();
	$conn = new conexionBD (); //Genera una nueva coneccion
	
	
		
	$sql="Select * from tempventas Where operador='".$_GET['id']."'";
	$resv=mysql_query($sql);
	//$objResp->alert($sql);
	$nums=mysql_num_rows($resv);
	
	
	if($nums!=0)
	{
		
	extract($formu);

	for($i=0;$i<=$nums;$i++)
	{
		
	 $objResp->Remove("rowDetalle_$i");	//Elimino todos los elementos de la tabla
	
		
	}
	
	
	 $tot_Contado=0;
	 $tot_Listado=0;
	 $tot_Total=0;
	 $totCanti=0;
	 $totContado=0;
	 $totListado=0;
	 $suma=0;
	 $sumaL=0;

$id_campos=1; 
	
	$objResp->assign('totCanti','value',$nums);
	
		
	while($reg=mysql_fetch_array($resv))
	{
	$objResp->assign('lista','value',$reg[idLista]);
	if(preg_match("/Consumidor Final/i",$reg['cliente']))
		{
		$objResp->assign('nombreC','value',$reg[cliente]);
		}
	else
	{
	$objResp->assign('cli','value',$reg[cliente]);
	}	
		

 $objResp->assign('txtIdPersona','value',$reg['codigo']);
 $objResp->assign('frasePersona','value',utf8_encode($reg['descripcion']));


   

 $str_html_td1 = $txtNombre . '<input class="prueba" readonly id="hdnNombre_' . $id_campos . '" name="hdnNombre_' . $id_campos . '" value="' . $reg['codigo'] . '"/>' ;
 $str_html_td2 = "$txtEdad" . '<input class="prueba" readonly size=40 id="hdnEdad_' . $id_campos . '" name="hdnEdad_' . $id_campos . '" value="' .  utf8_encode($reg['descripcion']) . '"/>' ;
 $str_html_td3 = "$txtDireccion" . '<input  class="prueba" readonly id="hdnDireccion_' . $id_campos . '" size=10 name="hdnDireccion_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
//$str_html_td4 = "$selSexo" . '<input  class="prueba" readonly id="hdnSexo_' . $id_campos . '" size=10 name="hdnSexo_' . $id_campos . '" value="' . $reg[precio] . '"/>' ;
 $str_html_td5 = "$selSexoo" . '<input  class="prueba"  id="hdnSexoo_' . $id_campos . '" size=10 name="hdnSexoo_' . $id_campos . '" value="' . $reg[cantidad] . '"/>' ;
 $str_html_td7 = "$selSexooo" . '<input  class="prueba" readonly id="hdnSexooo_' . $id_campos . '" size=10 name="hdnSexooo_' . $id_campos . '" value="' . $reg[total] . '"/>' ;
 $str_html_td6 = '<img src="images/delete.png" style="cursor:pointer" width="16" height="16" alt="Eliminar" onclick="if(confirm(\'Realmente desea eliminar este detalle?\')){xajax_eliminarFila('.$reg[id].','.$id_campos.', proyecto.cant_campos.value,'.$reg[precio].',proyecto.tot_Contado.value,'.$reg[precio].',proyecto.tot_Listado.value);}"/>';
 $str_html_td6 .= '<input type="hidden" id="hdnIdCampos_'. $id_campos .'" name="hdnIdCampos[]" size="5" value="'. $id_campos .'" />';

	$idRow = "rowDetalle_$id_campos";
	//$objResp->alert($idRow);
    $idTd = "tdDetalle_$id_campos";
	//$objResp->alert($idTd);



	$objResp->Create("tbDetalle", "tr", $idRow);
    $objResp->Create($idRow, "td", $idTd."1");     //creamos los campos
    $objResp->Create($idRow, "td", $idTd."2");
    $objResp->Create($idRow, "td", $idTd."3");
   // $objResp->Create($idRow, "td", $idTd."4");
	$objResp->Create($idRow, "td", $idTd."5");
	$objResp->Create($idRow, "td", $idTd."7");

    $objResp->Create($idRow, "td", $idTd."6");
	
	
 //     Esta parte podria estar dentro de algun ciclo iterativo  

    $objResp->Assign($idTd."1", "innerHTML", $str_html_td1);   //asignamos el contenido
    $objResp->Assign($idTd."2", "innerHTML", $str_html_td2);
    $objResp->Assign($idTd."3", "innerHTML", $str_html_td3);
    //$objResp->Assign($idTd."4", "innerHTML", $str_html_td4);
	 $objResp->Assign($idTd."5", "innerHTML", $str_html_td5);
	  $objResp->Assign($idTd."7", "innerHTML", $str_html_td7);
    $objResp->Assign($idTd."6", "innerHTML", $str_html_td6);

//  aumentamos el contador de campos 



	
	$id_campos++;
		$suma+=$reg['precio'];

	$objResp->assign('tot_Contado','value',$suma);
	$objResp->assign('totContado','value',$suma);


	$sumaL+=$reg['precio'];

	$objResp->assign('tot_Listado','value',$sumaL);
	$objResp->assign('totListado','value',$sumaL);

  //agregarFila();
   $objResp->assign('txtIdPersona','value','');
  $objResp->assign('frasePersona','value','');


}//end while
	
	}//end if*/

	return $objResp;
}


$xajax->registerFunction("mostrar_TDetalles"); 
$xajax->registerFunction("limpiarTempVentas");
$xajax->registerFunction("cambiaLista");
$xajax->registerFunction("VienedeTrabajo");
//$xajax->registerFunction("guardarVenta");
$xajax->registerFunction("calculaTotal");
$xajax->registerFunction("eliminarFila");
$xajax->registerFunction('mostrarPersona');
$xajax->registerFunction('mostrarOrden');
$xajax->registerFunction('mostrarDetalle');
$flistadopersona = & $xajax-> registerFunction('listadopersona');
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');
$flistadopersona->setParameter(2,XAJAX_INPUT_VALUE,'pagPersona');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'TotalRegPersona');

$xajax->processRequest();



?>