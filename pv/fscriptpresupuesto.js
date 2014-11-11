// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }
// FUNCION AJAX
function nuevoAjax() { 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{
			// Creacion del objeto AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 
	return xmlhttp;
}
// FUNCION RETORNA PAGINA ANTERIOR
function irAnterior(){
 history.back( );
}
// FUNCION QUE VALIDA FORMATO FECHA
function validaFormatoFecha(){
	var I = document.getElementById("fgaceta").value;
  if(I.length<10){
    alert('HA INTRODUCIDO DATOS DE ME MANERA INCORRECTA,CORRIGA F.GACETA');
	I.focus();
  }
  for ( j=0; j<I.length; j++) {
   //all figures and spacers in place?
     if ((j == 2) || (j == 5)) {
       if(I.charAt(j) != "-"){
		 alert('HA INTRODUCIDO CARACTERES INCORRECTOS,CORRIGA F.GACETA');
		 formulario.I.focus();
	   }
     }else{
	   if ((I.charAt(j)<"0") || (I.charAt(j)>"9")){ 
	     alert('HA INTRODUCIDO DATOS INCORRECTOS,CORRIGA F.GACETA');
		 I.focus();
	   }
	 }
   }
//using right format dd/mm/yyyy and year 2002 or more? Change this for diff formats...
 var Actual = new Date();
 var Ano = Actual.getFullYear();
 var dia = Actual.getDay();
 var mes = Actual.getMonth();
 var bits = I.split("-");
 var days = Number(bits[0]);
 var month = Number(bits[1]);
 var year=Number(bits[2]);
 if(days > 31){
   alert('LA FECHA INTRODUCIDA ES INCORRECTA');
 }else{
   if(month > 12){ 
     alert('LA FECHA INTRODUCIDA ES INCORRECTA');
   }else{
	 if(year > Ano){ 
	   alert('LA FECHA INTRODUCIDA ES INCORRECTA');
	 }else{
	   if(I < Actual){
		 alert('LA FECHA INTRODUCIDA ES INCORRECTA');
	   }
	 }
   return true;
 } 
}
}

// FUNCION QUE ELIMINA REGISTRO EN PANTALLA
function eliminarAjustePartida(){
  var idFila = document.getElementById("registro").value; //alert(idFila);
  var fila = document.getElementById(idFila); //alert(fila);
  var trquitar = fila.parentNode;
      trquitar.removeChild(fila);
}
//	FUNCION PARA SELECCIONAR UN PRESUPUESTO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selCodpresupuesto(busqueda, campo) {
	var registro=document.getElementById("registro").value;
	if (campo==1) {
		opener.document.frmentrada.npresupuesto.value=registro;
	} 
	window.close();
}
//	funcion para insertar un item/commodity en detalle de la orden de compra
function insertarItemRequerimiento(codigo, accion) {
	var ventana = document.getElementById("ventana").value;
	var tabla = document.getElementById("tabla").value;
	var form = opener.document.getElementById("frmentrada");
	//var ccosto = opener.document.getElementById("ccosto").value;
	//if (opener.document.getElementById("flagcompras").checked) var dirigidoa = "Compras"; else dirigidoa = "Almacén";
	var nrodetalles = new Number(opener.document.getElementById("nrodetalles").value); nrodetalles++;
	var cantdetalles = new Number(opener.document.getElementById("cantdetalles").value); cantdetalles++;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones02.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemRequerimiento&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles+"&nrodetalles="+nrodetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				if (accion == "insertarItemRequerimiento")
					opener.document.getElementById("btInsertarCommodity").disabled = true;
				else
					opener.document.getElementById("btInsertarItem").disabled = true;
				
				opener.document.getElementById("nrodetalles").value = nrodetalles;
				opener.document.getElementById("cantdetalles").value = cantdetalles;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
				newTr.id = codigo;
				opener.document.getElementById("listaDetalles").appendChild(newTr);
				opener.document.getElementById(codigo).innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	funcion para convertir un numero frmateado en su valor real
function setNumero(num_formateado) {
	var num = num_formateado.toString();
	num = num.replace(/[.]/gi, "");
	num = num.replace(/[,]/gi, ".");
	
	var numero = new Number(num);
	return numero;
}
// ***** FUNCION CAMBIAR FORMATO MONTO AL CAMPO MONTOAJUSTADO
function operacion(monto,id){
	var id= id.split("|");
	var partida = id[0];
	var generica= id[1]; 
	
	var monto_detalle = new Number(setNumero(monto)); //alert(monto_detalle);
	var monto_generica = new Number(setNumero(document.getElementById(generica).value));//alert('MontoGenerica=' + monto_generica);
    var monto_partida = new Number(setNumero(document.getElementById(partida).value));//alert('MontoPartida='  + monto_partida);
	var monto_ajuste = new Number(setNumero(document.getElementById("montoAjustado").value)); //alert('Monto Ajuste=' + monto_ajuste);
	
}
///// ******* FUNCION PARA VERIFICAR DATOS DE AJUSTE
function verificarDatosAjuste(formulario) {

//// **** VALIDACION NUMERO DE PRESUPUESTO
if(formulario.npresupuesto.value.length <4) {
 alert("¡DEBE SELECCIONAR EL PRESUPUESTO AJUSTAR \"HACER CLICK EN BOTON ...\"!");
 formulario.npresupuesto.focus();
return (false);
}
var checkOK ="0123456789";
var checkStr = formulario.npresupuesto.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++){
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if(!allValid) { 
 alert("¡DEBE SELECCIONAR EL PRESUPUESTO AJUSTAR \"HACER CLICK EN BOTON ...\"!"); 
 formulario.npresupuesto.focus(); 
 return (false); 
} 
//// **** VALIDACION TIPO AJUSTE
if(formulario.tAjuste.value.length<1) {
 alert("¡DEBE ESCOGER EL TIPO DE AJUSTE!");
 formulario.tAjuste.focus();
return (false);
}
var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú";
var checkStr = formulario.tAjuste.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++){
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if(!allValid) { 
 alert("¡DEBE ESCOGER EL TIPO DE AJUSTE!"); 
 formulario.tAjuste.focus(); 
 return (false); 
} 
//// **** VALIDACION PERIODO AJUSTE
if(formulario.fperiodo.value.length<1) {
 alert("¡DEBE INGRESAR EL PERIODO DE DURACION DE AJUSTE!");
 formulario.tAjuste.focus();
return (false);
}
var checkOK ="0123456789" + "-";
var checkStr = formulario.fperiodo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++){
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if(!allValid) { 
 alert("¡DEBE INGRESAR LOS DATOS CORRECTOS EN EL CAMPO \"Período\"!"); 
 formulario.fperiodo.focus(); 
 return (false); 
} 
//// **** VALIDACION DESCRIPCION DEL AJUSTE
if(formulario.descripcion.value.length<1) {
 alert("¡DEBE INGRESAR DESCRIPCION DEL AJUSTE A REALIZAR!");
 formulario.descripcion.focus();
return (false);
}
var checkOK ="0123456789" + "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " -/.,;";
var checkStr = formulario.descripcion.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++){
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if(!allValid) { 
 alert("¡DEBE INGRESAR LOS DATOS CORRECTOS EN EL CAMPO \"Período\"!"); 
 formulario.descripcion.focus(); 
 return (false); 
} 
//// **** VALIDACION DESCRIPCION DEL AJUSTE
if(formulario.nomempleado.value.length<1) {
 alert("¡DEBE SELECCIONAR QUIEN APROBARA EL AJUSTE \"HACER CLICK EN BOTON ...\"!");
 formulario.nomempleado.focus();
return (false);
}
var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " ,´";
var checkStr = formulario.nomempleado.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++){
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if(!allValid) { 
 alert("¡DEBE SELECCIONAR QUIEN APROBARA EL AJUSTE \"HACER CLICK EN BOTON ...\"!"); 
 formulario.nomempleado.focus(); 
 return (false); 
} 
 return (true); 
} 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// **** FUNCION SUMAR MONTO AJUSTADO
function sumarAjuste(monto, id) {
	var id = id.split("|");	
	var partida = id[0];
	var generica = id[1];
	var detalle = id[2];

	var monto_detalle = new Number(setNumero(monto));//alert('MontoDetalle='+monto_detalle);
	var monto_generica = new Number(setNumero(document.getElementById(generica).value));//alert('MontoGenerica');alert(monto_generica);
    var monto_partida = new Number(setNumero(document.getElementById(partida).value));//alert('MontoPartida');alert(monto_partida);
	var monto_codpartida = new Number(setNumero(document.getElementById(detalle).value));//alert('Monto CodPartida= '+ monto_codpartida);
	var Total_ajuste = new Number(setNumero(document.getElementById('total_ajuste').value)); //alert('Total Ajuste=' + Total_ajuste);
	
	// Utilizado para calcular el monto restante
	var valorGuardado = new Number(setNumero(document.getElementById('montovoy').value)); //alert('Valor Guardado='+valorGuardado);
	var tipoAjuste = document.getElementById('tAjuste').value; //alert('tipoAjuste='+ tipoAjuste);

	if(valorGuardado < monto_detalle ){//alert('Paso 1');/////// VALORGUARDADO < MONTO_DETALE
	  if(monto_codpartida<monto_detalle){
		  if(tipoAjuste=='IN'){//alert('Paso one');
			var calc = monto_detalle + valorGuardado ;
	        var total_generica = monto_generica + calc;
            var total_partida = monto_partida + calc;
            document.getElementById(generica).value = setNumeroFormato(total_generica,2,'.',',');
            document.getElementById(partida).value = setNumeroFormato(total_partida,2,'.',',');
			Total_ajuste = Total_ajuste + monto_detalle - valorGuardado;
			//document.getElementById('total_ajuste').value = setNumeroFormato(total_partida,2,'.',',');
			document.getElementById('total_ajuste').value = setNumeroFormato(Total_ajuste,2,'.',',');
		  }else{
		    alert('!MONTO INGRESADO EXCEDE DISPONIBILIDAD DE PARTIDA, DEBE CORREGIR¡');
		    document.getElementById(id).focus();
		  }
	  }else{//alert('Paso 1.1');
	    var calc = monto_detalle - valorGuardado ;
	    var total_generica = monto_generica + calc;
        var total_partida = monto_partida + calc;
        document.getElementById(generica).value = setNumeroFormato(total_generica,2,'.',',');
        document.getElementById(partida).value = setNumeroFormato(total_partida,2,'.',',');
		Total_ajuste = Total_ajuste + monto_detalle - valorGuardado;
		document.getElementById('total_ajuste').value = setNumeroFormato(Total_ajuste,2,'.',',');
		//document.getElementById('total_ajuste').value = setNumeroFormato(total_partida,2,'.',',');
	  }
	}else{/////// VALORGUARDADO > MONTO_DETALE
	  if(monto_codpartida<monto_detalle){//alert('Paso 2');
	     if(tipoAjuste=='IN'){//alert('Paso Two');
			var calc = monto_detalle + valorGuardado ;
	        var total_generica = monto_generica + calc;
            var total_partida = monto_partida + calc;
            document.getElementById(generica).value = setNumeroFormato(total_generica,2,'.',',');
            document.getElementById(partida).value = setNumeroFormato(total_partida,2,'.',','); 
			//document.getElementById('total_ajuste').value = setNumeroFormato(total_partida,2,'.',',');
			Total_ajuste = Total_ajuste + monto_detalle - valorGuardado;
		    document.getElementById('total_ajuste').value = setNumeroFormato(Total_ajuste,2,'.',',');
		 }else{
		    alert('!MONTO INGRESADO EXCEDE DISPONIBILIDAD DE PARTIDA, DEBE CORREGIR¡');
		 }
	  }else{//alert('Paso 2.1')
	    if(valorGuardado>monto_detalle){
		   calc = valorGuardado - monto_detalle;
		   total_partida = monto_partida - calc;
		   total_generica = monto_generica - calc;
		   document.getElementById(generica).value= setNumeroFormato(total_generica,2,'.',',');
		   document.getElementById(partida).value= setNumeroFormato(total_partida,2,'.',',');
		   //document.getElementById('total_ajuste').value = setNumeroFormato(total_partida,2,'.',',');
		   Total_ajuste = Total_ajuste + monto_detalle - valorGuardado;
		   document.getElementById('total_ajuste').value = setNumeroFormato(Total_ajuste,2,'.',',');
		}else{
	       var total_generica = monto_generica + monto_detalle;
           var total_partida = monto_partida + monto_detalle;
           document.getElementById(generica).value = setNumeroFormato(total_generica,2,'.',',');
           document.getElementById(partida).value = setNumeroFormato(total_partida,2,'.',',');
		   //document.getElementById('total_ajuste').value = setNumeroFormato(total_partida,2,'.',',');
		   Total_ajuste = Total_ajuste + monto_detalle - valorGuardado;
		   document.getElementById('total_ajuste').value = setNumeroFormato(Total_ajuste,2,'.',',');
		}
	 }
}
}
//// **** FUNCION OBTENER MONTOD
function obtenerMontoD(valor){
 document.getElementById('montova').value = valor;
}
//// ****  FUNCION OBTENER
function obtener(valor){
  document.getElementById('montovoy').value = valor;
}
function obtener2(fI){
 document.getElementById('fechaObt').value = fI;	
}
//// **** VOLVER
function volver(){
  history.back(-1);
}
//// **************************  FUNCION PERMITIR ACTIVAR PAGINA
/*function permitirPagina(){
  var permitir = new Number(document.getElementById('permitir').value); alert()	
}*/
//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentanaPart(form, pagina, param) {
 var permitir = new Number(document.getElementById('permitir').value); //alert('PERMITIR cargar ventana= '+ permitir);
 if(permitir!=1){
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
 }
}
//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentanaGen(form, pagina, param) {
 /*var permitir = new Number(document.getElementById('permitir').value); //alert('PERMITIR cargar ventana= '+ permitir);
 if(permitir!=1){*/
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
// }
}

/// ---------------------------------------------------------------------------------
/// Funcion 
function cambiarMonto(){
  var monto = new Number(document.getElementById("partida_preDMA").value); //alert('monto='+monto);
      document.getElementById("partida_preDMA").value = setNumeroFormato(monto,2,'.',',');
      document.getElementById("MontoAjuste").value = setNumeroFormato(monto,2,'.',',');
}
/// ---------------------------------------------------------------------------------
/// 					GUARDAR AJUSTE
/// ---------------------------------------------------------------------------------
/*function guardarAjuste(form, accion){
  	var Org = document.getElementById("Org").value; 
	var num_presupuesto = document.getElementById("num_presupuesto").value;
	var status = document.getElementById("status").value;
	var fgaceta = document.getElementById("fgaceta").value;
	var fresolucion = document.getElementById("fresolucion").value;
	var tAjuste = document.getElementById("tAjuste").value;
	var fAjuste = document.getElementById("fAjuste").value;
	var montivoAjuste = document.getElementById("montivoAjuste").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var descripcion = document.getElementById("descripcion").value;
	var cod_preparado = document.getElementById("cod_preparado").value;
	var fpreparacion = document.getElementById("fpreparacion").value;
	var regresar = document.getElementById("regresar").value;
	var nresolucion = document.getElementById("").value;
	//alert('Paso');
	var ajax=nuevoAjax();
		ajax.open("POST", "gpresupuesto.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=GuardarAjuste&Org="+Org+"&num_presupuesto="+num_presupuesto+"&status="+status+"&fgaceta="+fgaceta+"&fresolucion="+fresolucion+"&tAjuste="+tAjuste+"&fAjuste="+fAjuste+"&montivoAjuste="+montivoAjuste+"&fperiodo="+fperiodo+"&descripcion="+descripcion+"&cod_preparado="+cod_preparado+"&fpreparacion="+fpreparacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				cargarPagina(form,regresar);
			}
		}

	//return false;
}*/
/// ---------------------------------------------------------------------------------
///     ********  ANULAR REFORMULACION
function anularReformulacion(form) {
	var codigo=form.registro.value; //alert('=='+codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ANULAR
		var anular=confirm("¡Esta seguro de anular?");
		if (anular) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=anularReformulacion&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, "reformulacion_listar.php");
				}
			}
		}	
	}
}
/// ---------------------------------------------------------------------------------
//// **** FUNCION ELIMINAR REFORMULACION
function eliminarReformulacion(form, pagina, foraneo, modulo) {
	var codigo=form.registro.value; //alert('Paso=='+codigo);
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("&accion=eliminarReformulacion&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else cargarPagina(form, pagina);
					}
				}
			} else cargarPagina(form, pagina);
		}
	}
}


/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------
/// ---------------------------------------------------------------------------------



