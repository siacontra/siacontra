//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina){
	form.method="POST";
	form.action=pagina;
	form.submit();
}
//// ----------------------------------------------------------------------
//// FUNCION GUARDAR TIPO TRANSACCIONES
function guardarTipoTransacciones(formulario){ 
	
  var TipoTransaccion = document.getElementById("cod_trans").value;
  var FlagAltaBaja = document.getElementById("tipo_transa").value;  
  var Descripcion = document.getElementById("descp_tipotrans").value;
  var TipoVoucher = document.getElementById("tipo_voucher").value;
  var Estado = document.getElementById("radioEstado").value; 
  if(formulario.flagTranSistema.checked) var flagTranSistema = 'S';
  else var flagTranSistema = 'N';
  
  //VALIDACION CODIGO
   if (formulario.cod_trans.value.length <2) {
	 alert("Escriba el código de la Categoría en el campo \"Transacción Código\".");
	 formulario.cod_trans.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_trans.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo numeros en el campo \"Transacción Código\"."); 
	 formulario.cod_trans.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION
  if (formulario.descp_tipotrans.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción\".");
	 formulario.descp_tipotrans.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_tipotrans.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción\"."); 
	 formulario.descp_tipotrans.focus(); 
	 return (false); 
   } 
   
  
  
  var detalles = "";
  var error_detalles = "";
	
  // obtengo los valoes de las lineas insertadas
  var frmdetalles = document.getElementById("frmdetalles");
  for(i=0; n=frmdetalles.elements[i]; i++) {
	if (n.name == "select2") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos una categoría¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "select1") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "secuencia") detalles += n.value + "|";
	if (n.name == "descripcion") detalles += n.value + "|";
	if (n.name == "cuenta") detalles += n.value + "|";
	
	if (n.name == "select3") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un signo¡"; break; }
		else detalles += n.value + ";";
	}
	//if (n.name == "signo") detalles += n.value + ";";
	//if (n.name == "campoMonedaLocal") detalles += n.value + ";";
	
 }
 var len = detalles.length; len--;
 detalles = detalles.substr(0, len); 

 if (error_detalles != "") alert(error_detalles);
 else {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=guardarTipoTransacciones&TipoTransaccion="+TipoTransaccion+"&FlagAltaBaja="+FlagAltaBaja+"&Descripcion="+Descripcion+"&TipoVoucher="+TipoVoucher+"&Estado="+Estado+"&detalles="+detalles+"&flagTranSistema="+flagTranSistema);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != ""){ 
			  //alert(resp.trim());
			  //alert('Paso ;)'); 
			  cargarPagina(formulario, "af_transaccionestipotransaccion.php?limit=0")};
		}
	}
	return false;
}

}
//// ----------------------------------------------------------------------
function asumoInsert(id){
  document.getElementById("nrodetalle").value= id; 
}
//// ----------------------------------------------------------------------
//// FUNCION ACTIVAR EL BOTON ACTIVO MAYOR / ACTIVO MENOR
function activarBotones(form,valor){
 //var val = document.getElementById("valor");	
  if(valor == 'ACT'){ 
     form.btActivoMayor.disabled=false; form.btActivoMenor.disabled=true; 
  }else{ 
     if (valor == 'BME'){ form.btActivoMenor.disabled=false; form.btActivoMayor.disabled=true;}
  }
}
//// ----------------------------------------------------------------------
////               FUNCTION CARGAR DIRECCION DE PAGINA ACTIVAR
//// ----------------------------------------------------------------------
function cargarDireccion(form,valor){
   if(valor == 'AN'){ document.getElementById("direccion").value = 'af_procesoctivosaprobar';  //alert(document.getElementById("direccion").value);
   }
   //if(valor == 'AN') document.getElementById("direccion").value = 'af_procaprobaractivo';
   else if(valor == 'AM'){ document.getElementById("direccion").value='af_listactivosmenoraprobar';  //alert(document.getElementById("direccion").value);
   }
}
//// ----------------------------------------------------------------------
//// 	funcion para convertir un numero formateado en su valor real
//// ----------------------------------------------------------------------
function setNumero(num_formateado) {
	var num = num_formateado.toString();
	num = num.replace(/[.]/gi, "");
	num = num.replace(/[,]/gi, ".");
	
	var numero = new Number(num);
	return numero;
}
//	funcion para formatear un numero con separadores de miles 
function setNumeroFormato1(num, dec, sep_mil, sep_dec) {
	var oNum = new oNumero(num);
	var num_formateado = oNum.formato(dec, true);
	var numero = num_formateado.toString();
	
	numero = numero.replace(/[.]/gi, ";");
	numero = numero.replace(/[,]/gi, sep_mil); 
	numero = numero.replace(/[;]/gi, sep_dec);
	
	return numero;
}
//// ----------------------------------------------------------------------
////            FUNCION CAMBIO FORMATO DE MONTO Y ASIGNACION
//// ----------------------------------------------------------------------
function cambioFormatoMonto(){
  //alert('  lll  ');
    valor = new Number(setNumero(document.getElementById('h_inicioAnoLocal').value)); 
	valorF = setNumeroFormato1(valor,2,'.',',');
	document.getElementById('h_inicioAnoLocal').value = valorF;
	document.getElementById('h_inicioAnoHistAjust').value = valorF;
	document.getElementById('h_inicioAnoLocalAjust').value = valorF;
	document.getElementById('h_acumMesAntLocal').value = valorF;
	document.getElementById('h_acumMesAntHistAjust').value = '0.00';
	document.getElementById('h_acumMesAntLocalAjust').value = valorF;
	
	document.getElementById('h_ajustInfMesLocalAjust').value = '0.00';
	document.getElementById('h_adicRetLocal').value = '0.00';
	document.getElementById('h_adicRetLocalAjust').value = '0.00';
	
	document.getElementById('h_montoIniMesLocal').value = valorF;
	document.getElementById('h_montoIniMesHistAjust').value = valorF;
	document.getElementById('h_montoIniMesLocalAjust').value = valorF;
	
	document.getElementById('d_iniAnoLocal').value = '0.00';
	document.getElementById('d_iniAnoHistAjust').value = '0.00';
	document.getElementById('d_iniAnoLocalAjust').value = '0.00';
	
	document.getElementById('d_acumMesAntLocal').value = '0.00';
	document.getElementById('d_acumMesAntLocalAjust').value = '0.00';
	
	document.getElementById('d_ajustInfMesLocalAjust').value = '0.00';
	
	document.getElementById('d_ajustInfMesLocalAjust').value = '0.00';
	
	document.getElementById('d_ajusteLocal').value = '0.00';
	document.getElementById('d_ajusteLocalAjust').value = '0.00';
	
	document.getElementById('d_depreMesLocal').value = '0.00';
	document.getElementById('d_depreMesHistAjust').value = '0.00';
	document.getElementById('d_depreMesLocalAjust').value = '0.00';
	
	document.getElementById('d_depreAcumLocal').value = '0.00';
	document.getElementById('d_depreAcumLocalAjustado').value = '0.00';
	
	document.getElementById('ia_montoNetoLocal').value = valorF;
	document.getElementById('ia_montoNetoLocalAjust').value = valorF;
	
	document.getElementById('ia_depreAnualLocal').value = '0.00';
	document.getElementById('ia_depreAnualLocalAjust').value = '0.00';
	
	document.getElementById('ia_adiRetAnoLocal').value = '0.00';
	document.getElementById('ia_adiRetAnoLocalAjust').value = '0.00';
	
	document.getElementById('ia_infAnualHistLocal').value = '0.00';
	
	document.getElementById('ia_infAnualDepreLocal').value = '0.00';
	document.getElementById('ia_infAnualDepreHistAjust').value = 'R.E.I.';
	document.getElementById('ia_infAnualDepreLocalAjust').value = '0.00';
	
	
	
}
//// ----------------------------------------------------------------------
////            FUNCION INSERTAR CLASIFICACION PUB 20
//// ----------------------------------------------------------------------
function insertarClasificacionPub20(busqueda, variable, campo, valor, valor2){
 var registro=document.getElementById("registro").value; //alert('Registro='+registro);
 
 if(campo==1){
		parent.document.frmentrada.clasificacion20.value=registro; //alert(opener.document.frmentrada.clasificacion20.value);
		parent.document.frmentrada.clasificacion20Descp.value=valor; //alert(opener.document.frmentrada.clasificacion20Descp.value);
 }else
 if(campo==2){
		parent.document.frmentrada.fCodclasficacionPub20.value=registro; //alert(opener.document.frmentrada.fCodclasficacionPub20.value);
		parent.document.frmentrada.fClasificacionPub20.value=valor; //alert(opener.document.frmentrada.fClasificacionPub20.value);
 }
 
parent.$.prettyPhoto.close();
 
}
//// ----------------------------------------------------------------------
////  ---- FUNCION ACTIVAR DESACTIVAR BOTONES AF_LISTADOCLASIFICACION20
function enabledBienes(form){
 if(form.chkbienes.checked) form.fBienes.disabled=false;
 else{ form.fBienes.disabled=true; form.fBienes.value="";}
}
//// ----------------------------------------------------------------------
////  ---- FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentanaDetallesActivosAprobar(form, pagina, param) {
	var registro=document.getElementById("nro_activo").value;
	    window.open(pagina+'?registro='+registro,'', param);
}
//// ----------------------------------------------------------------------
////  ---- 
function cargarOpcionVerCategoriaAprobarActivo(form, pagina, param) {
	var registro=document.getElementById("select_categoria").value;
	    window.open(pagina+'?registro='+registro,'', param);
}
//// ----------------------------------------------------------------------
//// ---------- EDITAR TIPO TRANSACCION DEL MAESTRO TIPO DE TRANSACCIONES
//// ----------------------------------------------------------------------
function EditarTipoTransacciones(formulario){
	
  var TipoTransaccion = document.getElementById("cod_trans").value; 
  var FlagAltaBaja = document.getElementById("tipo_transa").value; 
  var Descripcion = document.getElementById("descp_tipotrans").value;
  var TipoVoucher = document.getElementById("tipo_voucher").value;
  var Estado = document.getElementById("radioEstado").value; 
  if(formulario.flagTranSistema.checked) var flagTranSistema = 'S';
  else var flagTranSistema = 'N';
  
  //VALIDACION CODIGO
   if (formulario.cod_trans.value.length <2) {
	 alert("Escriba el código de la Categoría en el campo \"Transacción Código\".");
	 formulario.cod_trans.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ";
  var checkStr = formulario.cod_trans.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo numeros en el campo \"Transacción Código\"."); 
	 formulario.cod_trans.focus(); 
	 return (false); 
   } 
   
  //VALIDACION  DESCRIPCION
  if (formulario.descp_tipotrans.value.length <2) {
	 alert("Escriba la descripción en el campo \"Descripción\".");
	 formulario.descp_tipotrans.focus();
  return (false);
  }
  var checkOK = "0123456789" + "ABCDEFGHIJKLMNOPQRSTUVWXYZÑ" + "abcdefghijklmnopqrstuvwxyzñ" + " .,;:_-/";
  var checkStr = formulario.descp_tipotrans.value;
  var allValid = true; 
  for (i = 0; i < checkStr.length; i++) {
	  ch = checkStr.charAt(i); 
	  for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
		  break;
		  if (j == checkOK.length) { 
			 allValid = false; 
		  break; 
		  }
  }
  if (!allValid) { 
	 alert("Escriba sólo caracteres permitidos en el campo \"Descripción\"."); 
	 formulario.descp_tipotrans.focus(); 
	 return (false); 
   } 
   
  
  
  var detalles = "";
  var error_detalles = "";
	
  // obtengo los valoes de las lineas insertadas
  var frmdetalles = document.getElementById("frmdetalles");
  for(i=0; n=frmdetalles.elements[i]; i++) {
	if (n.name == "select2") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos una categoría¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "select1") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un libro contable¡"; break; }
		else detalles += n.value + "|";
	}
	if (n.name == "secuencia") detalles += n.value + "|";
	if (n.name == "descripcion") detalles += n.value + "|";
	if (n.name == "cuenta") detalles += n.value + "|";
	
	if (n.name == "select3") {
		if (n.value == "") { error_detalles = "¡Debe seleccionar por lo menos un signo¡"; break; }
		else detalles += n.value + ";";
	}
	//if (n.name == "signo") detalles += n.value + ";";
	//if (n.name == "campoMonedaLocal") detalles += n.value + ";";
	
 }
 var len = detalles.length; len--;
 detalles = detalles.substr(0, len); //alert(detalles);

 if (error_detalles != "") alert(error_detalles);
 else {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=editarTipoTransacciones&TipoTransaccion="+TipoTransaccion+"&FlagAltaBaja="+FlagAltaBaja+"&Descripcion="+Descripcion+"&TipoVoucher="+TipoVoucher+"&Estado="+Estado+"&detalles="+detalles+"&flagTranSistema="+flagTranSistema);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4){
			var resp = ajax.responseText;
			if (resp.trim() != "") alert(resp);
			  //alert('Paso ;)'); 
			  //cargarPagina(formulario, "af_transaccionestipotransaccion.php?limit=0")};
		}
	}
	return false;
}
}
//// ----------------------------------------------------------------------
//// ----------	INSERTAR LINEA TIPO TRANSACCION
//// ----------------------------------------------------------------------
function insertarLineaTipoTransaccionNuevo() {	
	//var  contador= Number(document.getElementById("contador").value); contador++; alert(contador);
var  contador= document.getElementById("contador").value; contador++; //alert(contador);
	     document.getElementById("contador").value = contador;
	
	var  nrodetalle= Number(document.getElementById("nrodetalle").value); nrodetalle++; //alert(nrodetalle) ;	
	     document.getElementById("nrodetalle").value = nrodetalle;
	
	var candetalle = document.getElementById("candetalle").value; candetalle++;
	
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaTipoTransaccion&candetalle="+candetalle+"&contador="+contador);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle").value = candetalle;
			var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle; //alert(newTr.id);
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById("det_"+candetalle).innerHTML = resp;
		}
	}
}
//// -----------------------------------------------------------------------------------
//// ----------	ACTIVAR TAB 5
//// ----------------------------------------------------------------------------------
function activarTab5(form){
 if(form.gen_voucher.checked)document.getElementById("newTab5").style.visibility='visible';
 else document.getElementById("newTab5").style.visibility='hidden';
}
//// ----------------------------------------------------------------------------------
//// ---------- FUNCION QUE PERMITE MOSTRAR DATOS
//// ----------------------------------------------------------------------------------
function CargarInformacion(form, id, accion){
 var tipobaja = document.getElementById("tipobaja").value;
 //var  contador= document.getElementById("contador").value; contador++; //alert(contador);
	     //document.getElementById("contador").value = contador;
	
	//var  nrodetalle= Number(document.getElementById("nrodetalle").value); nrodetalle++; //alert(nrodetalle) ;	
	     //document.getElementById("nrodetalle").value = nrodetalle;
	
	var candetalle3 = document.getElementById("candetalle3").value; candetalle3++;
	
	var divResultado = document.getElementById('resultados');
	var monto = document.getElementById("monto_local").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion="+accion+"&candetalle3="+candetalle3+"&tipobaja="+tipobaja+'&monto='+monto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle3").value = candetalle3;
			
			divResultado.innerHTML = ajax.responseText;
			
			/*var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle3; //alert(newTr.id);
			document.getElementById("listaDetalles3").appendChild(newTr);
			document.getElementById("det_"+candetalle3).innerHTML = resp;*/
		}
	}  
}
//// ----------------------------------------------------------------------------------
//// FUNCION PARA VOLCAR MONTO
//// ----------------------------------------------------------------------------------
function volcarMonto(campo){
	var monto = new Number(setNumero(campo.value));
	if ((monto == 0)||(monto == '0,00')) campo.value = "0,00";
	else campo.value = setNumeroFormato1(monto, 2, ".", ",");
}
//// ----------------------------------------------------------------------------------
//// FUNCION QUE PERMITE ACTIVAR Y DESACTIVAR CAMPOS (Proceso - aprobacion alta de activos)
//// ----------------------------------------------------------------------------------
function enabledProveedorAprobacionAltaActivos(form){
if(form.chkproveedor.checked){
	form.proveedor.disabled=false; 
	form.btProveedor.disabled=false;
	document.getElementById("proveedor_01").style.visibility= 'visible';
}else{
	form.proveedor.disabled=true; 
	form.proveedor.value=""; 
	form.fproveedor.value=""; 
	form.btProveedor.disabled=true;
	document.getElementById("proveedor_01").style.visibility= 'hidden';
}
}
//// ----------------------------------------------------------------------------------
//// FUNCION GUARDAR AGRUPAR/CONSOLIDAR ACTIVO
//// ----------------------------------------------------------------------------------
function grabarAgrupacionConsolidacion(formulario){ 
  var detalles = "";
  var error_detalles = "";
	
 var activo = document.getElementById("fNroActivo").value; 	alert('activo='+activo);
  // obtengo los valoes de las lineas insertadas
  var frmdetalles = document.getElementById("frmdetalles");
  for(i=0; n=frmdetalles.elements[i]; i++) {
	 
	 if (n.name == "numero_activo") detalles += n.value + ";";
  }
 
 var len = detalles.length; len--;
 detalles = detalles.substr(0, len); 

 if (error_detalles != "") alert(error_detalles);
 else {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=grabarAgrupacionConsolidacion&detalles="+detalles+"&activo="+activo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != ""){ 
			  cargarPagina(formulario, "af_agruparconsolidaract.php?limit=0");
			}
		}
	}
	return false;
}
}
//// ----------------------------------------------------------------------------------
//// FUNCION PARA CARGAR UNA NUEVA PAGINA SEGUN EL TIPO DE NATURALEZA
//// ----------------------------------------------------------------------------------
function cargarOpcionTipo(form, target, param) {
	var codigo = form.registro.value; 
	    codigo = codigo.split("|");
		
	var Naturaleza = document.getElementById("Naturaleza").value;
	
	if (codigo[1]=='AN') var pagina = 'af_listactivosver.php?';
	else var pagina = 'af_activosmenoresver.php?';
	if (codigo == "") alert("¡Debe seleccionar un registro!");
	else {
		if (target == "SELF") cargarPagina(form, pagina);
		else if (target == "BLANK") {
			pagina = pagina + "&registro=" + codigo[0];
			window.open(pagina, pagina, "toolbar=no, menubar=no, location=no, scrollbars=yes, " + param);
		}
	}
}
//// ----------------------------------------------------------------------------------
//// FUNCION MUESTRA INFORMACION AL HACER CLICK EN UNA LINEA DE REGISTRO
//// ----------------------------------------------------------------------------------
function mostrar_informacion(form, id, accion){
	
	var id = id.split('|'); alert(id[0]);
	
 //var tipobaja = document.getElementById("tipobaja").value;
 //var  contador= document.getElementById("contador").value; contador++; //alert(contador);
	     //document.getElementById("contador").value = contador;
	
	//var  nrodetalle= Number(document.getElementById("nrodetalle").value); nrodetalle++; //alert(nrodetalle) ;	
	     //document.getElementById("nrodetalle").value = nrodetalle;
	
	var candetalle3 = document.getElementById("candetalle3").value; candetalle3++;
	
	var divResultado = document.getElementById('resultados');
	var monto = document.getElementById("monto_local").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "gmactivofijo.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion="+accion+"&candetalle3="+candetalle3+"&tipobaja="+tipobaja+'&monto='+monto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			document.getElementById("candetalle3").value = candetalle3;
			
			divResultado.innerHTML = ajax.responseText;
			
			/*var resp = ajax.responseText;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = "det_"+candetalle3; //alert(newTr.id);
			document.getElementById("listaDetalles3").appendChild(newTr);
			document.getElementById("det_"+candetalle3).innerHTML = resp;*/
		}
	}  
}
//// -------------------------------------------------------------------------------------
function quitarLineaTransaccion(seldetalle) {
	var listaDetalles = document.getElementById("listaDetalles");
	var tr = document.getElementById(seldetalle);
	listaDetalles.removeChild(tr);
	document.getElementById("seldetalle").value = "";
}