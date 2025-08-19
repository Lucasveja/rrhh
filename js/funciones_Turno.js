// JavaScript Document
 var patron = new Array(2,2,4)  

   function mascara(d,sep,pat,nums){

if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}//mascara



 var patronCuit = new Array(2,8,1)
   function mascaraCuit(d,sep,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<patronCuit.length; s++){
		val3[s] = val2.substring(0,patronCuit[s])
		val2 = val2.substr(patronCuit[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}//mascara para CUIL

var patronHora = new Array(2,2,0)
   function mascaraHora(d,sep,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<patronHora.length; s++){
		val3[s] = val2.substring(0,patronHora[s])
		val2 = val2.substr(patronHora[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}//mascara para Hora


/******** truncar un numero ****/

//ClipFloatByScriptma.03 
function clipFloat(num,dec){ 
var t=num+""; 
num = parseFloat(t.substring(0,(t.indexOf(".")+dec+1))); 
return (num) 
} 
 

function completCeros(x,n)
{
	   
    x = x.toString();
    while( x.length < n )
        x = "0"+x;
    return x;
}


function ponerCeros(obj,hasta)
{
  while (obj.value.length<hasta)
    obj.value = '0'+obj.value;
}


//VAlidaCiones Para los ClienteeeeS!!!*******************

function validarFormCli(){

    var isNotOk;

   //validaci�n nombre de Cliente

   var nombre = window.document.formCli.Nombre.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
//validacion Apellido de cliente

var apellido = window.document.formCli.Apellido.value;
if (apellido==""){

       document.getElementById("lastName").style.display="inline";
       isNotOk=true;
    }
else{

    document.getElementById("lastName").style.display="none";
}

//validacion Domicilio de cliente

var domicilio = window.document.formCli.Domicilio.value;
if (domicilio==""){

       document.getElementById("adress").style.display="inline";
       isNotOk=true;
    }
else{

    document.getElementById("adress").style.display="none";
}

//validacion Telefono de cliente

var telefono = window.document.formCli.Telefono.value;
if (telefono==""){

       document.getElementById("phone").style.display="inline";
       isNotOk=true;
    }
else{

    document.getElementById("phone").style.display="none";
}

  if (isNotOk){
  return false;

    }
  else{
    return true;
   }
}

//Validacioneees para Los ProveedoreeS!!*******************

function validarFormProv(){

    var isNotOk;

   //validaci�n nombre del ProveedoR

   var nombre = window.document.formProv.Nombre.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }

//validacion Domicilio de ProveedoR

var domicilio = window.document.formProv.Domicilio.value;
if (domicilio==""){

       document.getElementById("adress").style.display="inline";
       isNotOk=true;
    }
else{

    document.getElementById("adress").style.display="none";
}

//validacion Telefono de ProveedoR!�

var telefono = window.document.formProv.Telefono.value;
if (telefono==""){

       document.getElementById("phone").style.display="inline";
       isNotOk=true;
    }
else{

    document.getElementById("phone").style.display="none";
}

  if (isNotOk){
  return false;

    }
  else{
    return true;
   }
}

//************VAlidacion para Rubros!!*********************************

function validarFormRubroNombre(){

    var isNotOk;

   //validaci�n nombre del Rubro al Guardar

   var nombre = window.document.formRub.Nombre.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
if (isNotOk){
  return false;

    }
  else{
    return true;
   }


}




//************VAlidacion para Articulos!!*********************************

function validarFormArticuloNombre(){

    var isNotOk;

   //validaci�n nombre del Rubro al Guardar

   var nombre = window.document.formArt.Nombre.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
if (isNotOk){
  return false;

    }
  else{
    return true;
   }


}




//************VAlidacion para Marcas!!*********************************

function validarFormMarcasNombre(){

    var isNotOk;

   //validaci�n nombre del Rubro al Guardar

   var nombre = window.document.formMarcas.Nombre.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
if (isNotOk){
  return false;

    }
  else{
    return true;
   }


}



/*************VALidacION Para COMPRAS ***********/

function validarFormCompraCantidad(){

    var isNotOk;

   //validaci�n nombre del Rubro al Guardar

   var cantidad = window.document.formCompras.Cantidad.value;
   
   if (cantidad==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
if (isNotOk){
  return false;

    }
  else{
    return true;
   }


}

/**** VAlidadar NombRE de las LIStas de COmpras *****/

function validanombreLista(){

    var isNotOk;

   //validaci�n nombre del ProveedoR

   var nombre = window.document.formListaProd.CrearLista.value;
   
   if (nombre==""){

            document.getElementById("name").style.display="inline";
            isNotOk=true;
     }
  else{

     document.getElementById("name").style.display="none";

   }
   if (isNotOk){
  return false;

    }
  else{
    return true;
   }
}


//***********Ventana que pregunta si deseamos Guardar o Eliminar  Clientes*******************

   function guardarVentanaClientes()//Ventana que pregunta si deseamos Guardar  Cliente
  { 
    if(validarFormCli()==true) //antes Validar los Campos de los CLientes
	{
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Nombre').value;
    UI2=document.getElementById('Apellido').value;
	document.getElementById('prueba').innerHTML="Esta seguro de Guardar el Cliente "+UI1+' '+UI2+"?";
    }//end if validar
  }
  function eliminarVentanaClientes()//Ventana que pregunta si deseamos Elimanar  Clientes
  {
    var ventana = document.getElementById('miVentana1'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
    UI1=document.getElementById('Nombre').value;
    UI2=document.getElementById('Apellido').value;
    document.getElementById('prueba1').innerHTML="Esta seguro de Eliminar el Cliente "+UI1+' '+UI2+"?";
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }

//*********Ventana que pregunta si deseamos Guardar o Eliminar Proveedores*******************

  function guardarVentanaProveedores()//Ventana que pregunta si deseamos Guardar  Proveedor
  {
    if(validarFormProv()==true) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana2'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Nombre').value;
   
	document.getElementById('prueba2').innerHTML="Esta seguro de Guardar el Cliente "+UI1+" ?";
    }//end if validar
  }
  
  function eliminarVentanaProveedores()//Ventana que pregunta si deseamos Eliminar  Proveedor
  {
    var ventana = document.getElementById('miVentana3'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
    UI1=document.getElementById('Nombre').value;
    
    document.getElementById('prueba3').innerHTML="Esta seguro de Eliminar el Cliente "+UI1+" ?";
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }


//*********Ventana que pregunta si deseamos Guardar o Eliminar RUBROS*******************

  function guardarVentanaRubro()//Ventana que pregunta si deseamos Guardar  Proveedor
  {
    if(validarFormRubroNombre()==true) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana4'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Nombre').value;
   
	document.getElementById('prueba4').innerHTML="Esta seguro de Guardar el Rubro "+UI1+" ?";
    }//end if validar
  }
  
  function eliminarVentanaRubro(nom, id)//Ventana que pregunta si deseamos Eliminar  Proveedor
  { 
   var id_oculto=id;    	
    var ventana = document.getElementById('miVentana5'); // Accedemos al contenedor
	var Nom=nom; //traigo el Nombre del Rubro para mostrarlo en vez de mostrar el Codigo
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	      
    document.getElementById('prueba5').innerHTML="Esta seguro de Eliminar el Rubro "+Nom+" ?";
	document.getElementById('id_oculto').value=id_oculto;
  
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }
  
  
  
  //*********Ventana que pregunta si deseamos Guardar o Eliminar Marcas*******************

  function guardarVentanaMarca()//Ventana que pregunta si deseamos Guardar  Proveedor
  {
    if(validarFormMarcasNombre()==true) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana6'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Nombre').value;
   
	document.getElementById('prueba6').innerHTML="Esta seguro de Guardar la Marca "+UI1+" ?";
    }//end if validar
  }
  
  function eliminarVentanaMarca(nom,id)//Ventana que pregunta si deseamos Eliminar  Proveedor
  { 
    var id_oculto=id;    	
    var ventana = document.getElementById('miVentana7'); // Accedemos al contenedor
	var Nom=nom; //traigo el Nombre del Rubro para mostrarlo en vez de mostrar el Codigo
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	      
    document.getElementById('prueba7').innerHTML="Esta seguro de Eliminar la Marca "+Nom+" ?";
	document.getElementById('id_oculto').value=id_oculto;
  
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }
  


//*********Ventana que pregunta si deseamos Guardar o Eliminar Articulos*******************

  function guardarVentanaArticulos()//Ventana que pregunta si deseamos Guardar  Proveedor
  {
     if(validarFormArticuloNombre()==true) //Antes Validamos los campos de Proveedores
	{
	var ventana = document.getElementById('miVentana7'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Nombre').value;
   
	document.getElementById('prueba7').innerHTML="Esta seguro de Guardar el Articulo "+UI1+" ?";
    
  }//end if validar
  }
  
  function eliminarVentanaArticulos()//Ventana que pregunta si deseamos Eliminar  Proveedor
  {
    var ventana = document.getElementById('miVentana8'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
    UI1=document.getElementById('Nombre').value;
    
    document.getElementById('prueba8').innerHTML="Esta seguro de Eliminar el Articulo "+UI1+" ?";
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }

function obtener_value(elem){
	
    var indice = elem.selectedIndex;;
    var textoEscogido = elem.options[indice].text;
	return(textoEscogido);

}



/******* Cargar Articulo Ventana COMPRA 

function guardarVentanaCompra()//Ventana que pregunta si deseamos Guardar  Proveedor
  { //alert(subt);
    
     if(validarFormCompraCantidad()==true) //Antes Validamos los campos de Proveedores
	{
	var ventana = document.getElementById('miVentana10'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Descripcion').value;
     
   
   
   
	document.getElementById('prueba10').innerHTML="Esta seguro de Guardar el Articulo "+UI1+" ?";
    
  }//end if validar
  }*/






/****Eliminar Articulo Ventana LISTAS de PRoductos ********/

function eliminarVentanaCompra(desc, id)//Ventana que pregunta si deseamos Eliminar  Proveedor
  { 
     
    var id_oculto=id;
    var ventana = document.getElementById('miVentana9'); // Accedemos al contenedor
	var Desc=desc;	
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	//alert(id_oculto);
   // UI1=document.getElementById('DescripcionP').value;
    
    document.getElementById('id_oculto').value=id_oculto;
	//alert( document.getElementById('id_oculto').value);
    document.getElementById('prueba9').innerHTML="Esta seguro de Eliminar el Articulo "+Desc+" ?";
	
  }

  function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }


/****** Comprar Ventnaa CoMPra ********/


function guardarVentanaCompras()//Ventana que pregunta si deseamos Guardar  Proveedor
  { //alert(subt);
    
     
	var ventana = document.getElementById('miVentana11'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('Total2').value;
     
   
   
   
	document.getElementById('prueba11').innerHTML="Esta seguro de Comprar los Articulos por un total de $"+UI1+"?";
    
  
  }
 function ocultarVentana()
  {
    var ventana = document.getElementById('miVentana'); // Accedemos al contenedor
    ventana.style.display = 'none'; 
  }

function calcularsubtotal(){
	
	var posicion=document.getElementById('DescuentoSelect').options.selectedIndex; //posicion
	var Desc=document.getElementById('DescuentoSelect').options[posicion].text; //valor
	
	var Desc=parseFloat(Desc);
	//alert(Desc);
	var subtot=document.getElementById('subtotal').value;
	var subtot=parseFloat(subtot);
	//alert(subtot);
	subtot=clipFloat(subtot,2);
	
	var descuento=(Desc * subtot) / 100;
	var descuento= parseFloat(descuento);
	descuento=clipFloat(descuento,2);
	//alert(descuento);
	var res= subtot-descuento;
	 res=parseFloat(res);

	//alert(res);
	//alert(Desc);
	 res=clipFloat(res,2);
	 //res=parseFloat(res);
	
	if(Desc!=0){
		document.getElementById('subt').value=res ;
		document.getElementById('Total2').value=res ;
		document.getElementById('Desc').value=Desc;
		return true;
		if(Desc==null)
		{
		document.getElementById('subt')=document.getElementById('subtotal').value;
		document.getElementById('Total2')=document.getElementById('subtotal').value;
		return true;	
		}
			
	}

	return false;
}

function eliminarVentanaLista()//Ventana que pregunta si deseamos Eliminar Listas
  { 
     
  
   var posicion=document.getElementById('EliminarLista').options.selectedIndex; //posicion
	var Desc=document.getElementById('EliminarLista').options[posicion].text; //valor
    var ventana = document.getElementById('miVentana15'); // Accedemos al contenedor
	
  
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
   // UI1=document.getElementById('DescripcionP').value;
    
    
	//alert( document.getElementById('id_oculto').value);
    document.getElementById('prueba15').innerHTML="Esta seguro de Eliminar la Lista "+Desc+" ?";
	
	
	
  }
  
  

  function ocultarVentana15()
  {
    var ventana = document.getElementById('miVentana15'); // Accedemos al contenedor
    ventana.style.display = 'none';
	
	
  }
  
function anulaenter()
{
if (event.keyCode == 13) 
   event.returnValue = false;	
}

function idoculto(id)
{
var oculto=id;
document.getElementById('oculto').value=oculto;
}

function calcularprecio(id)
{ 
 var margen=document.getElementById('margen'+id).value;
 margen=parseFloat(margen);

 
 var costo= document.getElementById('costo'+id).value;
 costo=parseFloat(costo,2);
 
 
 
 var precio=costo+((margen*costo)/100);
 precio=clipFloat(precio,2);

 
 document.getElementById('precio'+id).value=precio;	
}




/***Valida los Campos Especialidad y Fecha PAra Solcitar Turno***/

function validaCampos()
{
	 var isNotOk;

   //validaci�n Especialidad 
   
   
   var especialidad = window.document.formTurno.Especialidad.value;
   var fecha= window.document.formTurno.calendario.value;
   
  if(especialidad=="" )
     {
      document.getElementById("espec").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("espec").style.display="none";
     }
  if(fecha=="")
    {
	   document.getElementById("calend").style.display="inline";
	   isNotOk=true;
    }
  else
     {
	   document.getElementById("calend").style.display="none";
     }
   
   if(isNotOk)
 {
  return false;
 }
  else{
    return true;
   }
   
}


   function validarFormTurnoPaciente()
   {
   var isNotOk;
   var dni=window.document.formTurno.dni.value;
   var nombre=window.document.formTurno.nombre.value;
   var apellido=window.document.formTurno.apellido.value;
   var telefono=window.document.formTurno.telefono.value;
   /*var obrasocial=window.document.formTurno.ObraSocial.value;
   var coseguro=window.document.formTurno.Coseguro.value;*/
   
   if(dni=="" )
     {
      document.getElementById("num").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("num").style.display="none";
     }
   if((nombre=="")||(nombre=="No existe") )
     {
      document.getElementById("name").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("name").style.display="none";
     }
   if((apellido=="")||(apellido=="No existe") )
     {
      document.getElementById("lastn").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("lastn").style.display="none";
     }
   if((telefono=="")||(telefono=="No existe") )
     {
      document.getElementById("phone").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("phone").style.display="none";
     }
  /* if(obrasocial=="" )
     {
      document.getElementById("obraSocial").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("obraSocial").style.display="none";
     }
  if(coseguro=="" )
     {
      document.getElementById("coSeguro").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("coSeguro").style.display="none";
     }*/
	   
if(isNotOk)
 {
  return false;
 }
  else{
    return true;
   }
	
}


function validarFormTurnoMed()
   {
   var isNotOk;
   var dni=window.document.formTurno.dni.value;
   var nombre=window.document.formTurno.nombre.value;
   var apellido=window.document.formTurno.apellido.value;
   var telefono=window.document.formTurno.telefono.value;
   var matricula=window.document.formTurno.matricula.value;
   
   
   
   if(dni=="" )
     {
      document.getElementById("num").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("num").style.display="none";
     }
   if(nombre=="" )
     {
      document.getElementById("name").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("name").style.display="none";
     }
   if(apellido=="" )
     {
      document.getElementById("lastn").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("lastn").style.display="none";
     }
	if(matricula=="" )
     {
      document.getElementById("e_mail").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("e_mail").style.display="none";
     } 
   if(telefono=="")
     {
      document.getElementById("phone").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("phone").style.display="none";
     }
	 
   
	   
if(isNotOk)
 {
  return false;
 }
  else{
    return true;
   }
	
}

function guardarVentanaTurno()//Ventana que pregunta si deseamos Guardar  Proveedor
  {
    if((validarFormTurnoPaciente()==true) && (validaCampos()==true)) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana17'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	var posicion=document.getElementById('Hora').options.selectedIndex; //posicion
	var hr=document.getElementById('Hora').options[posicion].text; //valor
	
	var pos=document.getElementById('Especialidad').options.selectedIndex; //posicion
	var es=document.getElementById('Especialidad').options[pos].text; //valor
	//alert(es);
	UI1=document.getElementById('calendario').value;
	
	
	document.getElementById('prueba17').innerHTML="Guardar Turno para el Dia: "+UI1+" Hora:"+hr+" Especialidad: "+es+" ?";
	
    }//end if validar
  }
  
  function ocultarVentana17()
  {
    var ventana = document.getElementById('miVentana17'); // Accedemos al contenedor
    ventana.style.display = 'none';
	
  }
 
  function borrarSelectHora()
{
	document.getElementById('Hora').length =0;
}

function idpaciente(id)
{

document.getElementById('idP').value=id;	
/*document.getElementById('idHora').value=document.getElementById('Hora'+id).value;
document.getElementById('idllegada').value=document.getElementById('llego'+id).value;*/
}


  function actualizarVentanaTurnoMed()//Ventana que pregunta si deseamos Guardar  Proveedor
  { 
  //alert('entro');
    if(validarFormTurnoMed()==true) //Antes Validamos los campos de Proveedores
	{
	
	var ventana = document.getElementById('miVentana18'); // Accedemos al contenedor
		
    ventana.style.marginTop = "100px"; 
    ventana.style.marginLeft = ((document.body.clientWidth-350) / 2) +  "px"; 
    ventana.style.display = 'block'; 
	
	UI1=document.getElementById('nombre').value;
	UI2=document.getElementById('apellido').value;
		
	document.getElementById('prueba18').innerHTML="Actualizar Datos del Medico: "+UI2+" "+UI1+" ?";
	
    }//end if validar
  }
  
  function ocultarVentana18()
  {
    var ventana = document.getElementById('miVentana18'); // Accedemos al contenedor
    ventana.style.display = 'none';
	
  }
  
  function aviso_Ver()
  {
  
   var isNotOk;
   var fecha=window.document.formTurnoVerTest.fechamysql.value;
   var medico=window.document.formTurnoVerTest.Medico.value;
   //alert(medico);
   //alert('entro aviso_Ver');
   if(fecha=="")
     {
      document.getElementById("aviso").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("aviso").style.display="none";
     }
	if(medico=="Seleccionar..")
     {
      document.getElementById("avisoMed").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("avisoMed").style.display="none";
     }   
if(isNotOk)
 {
  return false;
 }
 else
 {
    return true;
  }
	  
	  }
	  
  
	  

function VerificarDatos()
{
	
   var isNotOk;
   var Intervalo=window.document.generaTurno.intervalo.value;
   var especialidad=window.document.generaTurno.Especialidad.value;
   var desdem=window.document.generaTurno.desdem.value;
   var hastam=window.document.generaTurno.hastam.value;
   var desdet=window.document.generaTurno.desdet.value;
   var hastat=window.document.generaTurno.hastat.value;
   var calendarioDesde=window.document.generaTurno.calendarioDesde.value;
   var calendarioHasta=window.document.generaTurno.calendarioHasta.value;
   //alert(fecha,especialidad);
   
   if(Intervalo=="")
     {
      document.getElementById("interv").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("interv").style.display="none";
     }

if(especialidad=="")
     {
      document.getElementById("ErrorEsp").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("ErrorEsp").style.display="none";
     }
if(desdem=="")
     {
      document.getElementById("HoraDTM").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("HoraDTM").style.display="none";
     }
if(hastam=="")
     {
      document.getElementById("HoraHTM").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("HoraHTM").style.display="none";
     }	 
if(desdet=="")
     {
      document.getElementById("HoraDTT").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("HoraDTT").style.display="none";
     }
if(hastat=="")
     {
      document.getElementById("HoraHTT").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("HoraHTT").style.display="none";
     }	 
	 
if(calendarioDesde=="")
     {
      document.getElementById("FechaD").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("FechaD").style.display="none";
     }	 

if(calendarioHasta=="")
     {
      document.getElementById("FechaH").style.display="inline";
      isNotOk=true;
     }
  else
     {
	  document.getElementById("FechaH").style.display="none";
     }
if(isNotOk)
 {
  return false;
 }
  else{
    return true;
   }
	  
	  
 
 
}


function verificaEsp()
{
	var Esp=document.getElementById('Especialidad').options.selectedIndex; //posicion
	/*var Esp=document.getElementById('Especialidad').options[posicion].text; //valor*/
	if((Esp==1) ||( Esp==2) ||(Esp==3))
	 {document.getElementById("ErrorEsp").style.display="none";
	 return true;
	 }
	else
	 {document.getElementById("ErrorEsp").style.display="inline";
	 return false;
	 }
}

function BorrarUltimaFecha()
{
document.getElementById('Ultimafecha').value ="";	
}




