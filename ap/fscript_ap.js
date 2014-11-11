// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

//	Me permite redondear a un numero decimales especificos
Number.prototype.decimal = function (num) {
    pot = Math.pow(10,num);
    return parseInt(this * pot) / pot;
}

var MAXLIMIT=30;

//	FUNCIONES PARA VALIDAR FECHA
// Valido que sean numeros 
function esDigito(sChr){
	var sCod = sChr.charCodeAt(0);
	return ((sCod > 47) && (sCod < 58));
}
//	---------------------------------------

// Valido separadores de fecha...
function valSep(fecha){
	var bOk = false;
	bOk = bOk || ((fecha.charAt(2) == "-") && (fecha.charAt(5) == "-"));
	bOk = bOk || ((fecha.charAt(2) == "/") && (fecha.charAt(5) == "/"));
	return bOk;
}
//	---------------------------------------

// Valido dia fin de mes...
function finMes(fecha){
	var nAno = fecha.substr(6);
	var nMes = parseInt(fecha.substr(3, 2), 10);
	var nRes = 0;
	switch (nMes){
		case 1: nRes = 31; break;
		case 2: 
				if (nAno % 4 == 0) nRes = 29; 
				else nRes = 28;
				break;
		case 3: nRes = 31; break;
		case 4: nRes = 30; break;
		case 5: nRes = 31; break;
		case 6: nRes = 30; break;
		case 7: nRes = 31; break;
		case 8: nRes = 31; break;
		case 9: nRes = 30; break;
		case 10: nRes = 31; break;
		case 11: nRes = 30; break;
		case 12: nRes = 31; break;
	}
	return nRes;
}
//	---------------------------------------

// Valido dia...
function valDia(fecha){
	var nDia = parseInt(fecha.substr(0, 2), 10);
	var bOk = false;
	bOk = bOk || ((nDia >= 1) && (nDia <= finMes(fecha)));
	return bOk;
}
//	---------------------------------------

// Valido mes...
function valMes(nMes){
	var bOk = false;
	var nMesInt = parseInt(nMes, 10);
	bOk = bOk || ((nMesInt >= 1) && (nMesInt <= 12) && (nMes.length == 2));
	return bOk;
}
//	---------------------------------------

// Valido a�o...
function valAno(nAno){
	var bOk = true;
	var nAnoInt = parseInt(nAno, 10);
	bOk = bOk && ((nAno.length == 4) && (nAnoInt > 0));
	if (bOk){
		for (var i = 0; i < nAno.length; i++){
			bOk = bOk && esDigito(nAno.charAt(i));
		}
	}
	return bOk;
}
//	---------------------------------------

// Valido fecha completa...
function valFecha(fecha){
	var bOk = true;
	var nAno = fecha.substr(6);
	var nMes = fecha.substr(3, 2);
	if (fecha != "") {
		bOk = bOk && (valAno(nAno));
		bOk = bOk && (valMes(nMes));
		bOk = bOk && (valDia(fecha));
		bOk = bOk && (valSep(fecha));
		if (!bOk) return false; else return true;
	}
}

//	valido el periodo de fecha
function valPeriodo(fecha) {
	var bOk = true;
	var nAno = fecha.substr(0, 4);
	var nMes = fecha.substr(5);
	if (fecha != "") {
		bOk = bOk && (valAno(nAno));
		bOk = bOk && (valMes(nMes));
		if (!bOk) return false; else return true;
	}
}

function oNumero(numero) {
	//Propiedades
	this.valor = numero || 0
	this.dec = -1;
	
	//Métodos
	this.formato = numFormat;
	this.ponValor = ponValor;
	
	//Definición de los métodos
	function ponValor(cad) {
		if (cad =='-' || cad=='+') return
		if (cad.length ==0) return
		if (cad.indexOf('.') >=0)
			this.valor = parseFloat(cad);
		else
			this.valor = parseInt(cad);
	}

	function numFormat(dec, miles) {
		var num = this.valor, signo=3, expr;
		var cad = ""+this.valor;
		var ceros = "", pos, pdec, i;
		for (i=0; i < dec; i++)
			ceros += '0';
		pos = cad.indexOf('.')
		if (pos < 0)
		    cad = cad+"."+ceros;
		else {
		    pdec = cad.length - pos -1;
		    if (pdec <= dec) {
		        for (i=0; i< (dec-pdec); i++)
		            cad += '0';
		    }
		    else {
		        num = num*Math.pow(10, dec);
		        num = Math.round(num);
		        num = num/Math.pow(10, dec);
		        cad = new String(num);
		    }
		}
		pos = cad.indexOf('.')
		if (pos < 0) pos = cad.lentgh
		if (cad.substr(0,1)=='-' || cad.substr(0,1) == '+')
			signo = 4;
		if (miles && pos > signo)
		    do {
		        expr = /([+-]?\d)(\d{3}[\.\,]\d*)/
		        cad.match(expr)
		        cad=cad.replace(expr, RegExp.$1+','+RegExp.$2)
			}
		while (cad.indexOf(',') > signo)
		    if (dec<0) cad = cad.replace(/\./,'')
		        return cad;
	}
}

//	funcion para formtear un campo numerico cuando deja el campo
function numeroBlur(campo) {
	var numero = new Number(setNumero(campo.value));
	if (numero == 0) campo.value = "0,00";
	else campo.value = setNumeroFormato(numero, 2, ".", ",");
}

//	funcion para formtear un campo numerico cuando deja el campo
function numeroFocus(campo) {
	var numero = new Number(setNumero(campo.value));
	if (numero == 0) campo.value = "";
	else { 
		valor = setNumeroFormato(numero, 2, "", ",");
		
		var x = new String(valor);
		var sep = x.split(",");
		var dec = new Number(sep[1]);
		if (dec == 0) campo.value = sep[0];
		else if (dec <= 10) campo.value = setNumeroFormato(numero, 1, "", ",");
		else campo.value = setNumeroFormato(numero, 2, "", ",");
	}
}

//	funcion para convertir un numero formateado en su valor real
function setNumero(num_formateado) {
	var num = num_formateado.toString();
	num = num.replace(/[.]/gi, "");
	num = num.replace(/[,]/gi, ".");
	
	var numero = new Number(num);
	return numero;
}

//	funcion para formatear un numero con separadores de miles 
function setNumeroFormato(num, dec, sep_mil, sep_dec) {
	var oNum = new oNumero(num);
	var num_formateado = oNum.formato(dec, true);
	var numero = num_formateado.toString();
	
	numero = numero.replace(/[.]/gi, ";");
	numero = numero.replace(/[,]/gi, sep_mil); 
	numero = numero.replace(/[;]/gi, sep_dec);
	
	return numero;
}

//	FUNCION PARA REDONDEAR LOS VALORES DECIMALES
function redondear(valor, decimales) {
	var ceros = "";
	for (i=0; i<decimales; i++) ceros = ceros + "0";
	var unidad = "1" + ceros;
	
	var m = valor * unidad;
	
	var multiplicamos = new String(m);
	
	var partes = multiplicamos.split(".");
	var parte_entera = partes[0];
	var numero_redondeo = new String(partes[1]);
	numero_redondeo = numero_redondeo.substring(0, 1);
	if (numero_redondeo >= 5) parte_entera++;
	var resultado = new Number(parte_entera/unidad);
	
	var r = new String(resultado);
	var partes = r.split(".");
	
	if (partes.length == 1) partes[1] = "00";
	else if (partes[1].length < 2) partes[1] = partes[1] + "0";
	
	var resultado = partes[0] + "." + partes[1];
	return resultado;
}

function formatFechaAMD(fecha) {
	var f = new String();
	var nDia = fecha.substr(0, 2);
	var nMes = fecha.substr(3, 2);
	var nAno = fecha.substr(6);
	f = nAno + "-" + nMes + "-" + nAno;
	return f;
}

//	FUNCION QUE ME PERMITE CREAR UN NUEVO OBJETO AJAX
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

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL HACER CLICK EL PUNTERO DEL MOUSE SOBRE ELLA
function mClk(src, registro) {
	var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	//
	var row=document.getElementById(src.id);	//	OBTENGO LA FILA DEL CLICK
	row.className="trListaBodySel";	//	CAMBIO EL COLOR DE LA FILA
	
	var registro=document.getElementById(registro);
	registro.value=src.id;
}
function mClkMulti(src) {
	//	Obtengo el id para seleccionar el check
	var fila = src.id.split("_");
	var id = fila[1];
	
	//	Obtengo la fila y le cambio el color
	var row = document.getElementById(src.id);
	if (row.className == "trListaBodySel") {
		row.className="trListaBody";
		document.getElementById(id).checked = false;
	} else {
		row.className="trListaBodySel";
		document.getElementById(id).checked = true;
	}
	
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") alert("¡Debe seleccionar un registro!");
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcionVerObligacion() {
	var codigo = document.getElementById("registro").value;
	if (codigo == "") alert("¡Debe seleccionar un registro!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=cargarOpcionVerObligacion&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				window.open('ap_obligaciones_editar.php?accion=VER&registro='+resp.trim(), 'wObligacion', 'height=550, width=1175, left=50, top=50, resizable=no');
			}
		}
	}
}

//	FUNCION PARA EJECUTAR OPCION DE MENU DE REGISTROS
function opcionRegistro(form, registro, modulo, accion) {
	if (registro == "") alert("¡Debe seleccionar un registro!");
	else if (accion == "ELIMINAR" && confirm("¡Esta seguro de eliminar este registro?")) {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo="+modulo+"&accion="+accion+"&registro="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert (resp);
				else cargarPagina(form, form.action);
			}
		}
	}
}

function abrirOpcion(form, registro, pagina, target, param) {
	if (registro == "") alert("¡Debe seleccionar un registro!");
	else {
		if (target == "SELF") cargarPagina(form, pagina);
		else { pagina = pagina + "?limit=0&accion=VER&registro=" + registro; cargarVentana(form, pagina, param); }
	}
	
}

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, pagina, foraneo, modulo, accion) {
	var codigo=form.registro.value;
	if (codigo=="") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_lg.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion="+accion+"&codigo="+codigo);
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

//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentana(form, pagina, param) {
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistros(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
		
	if (!rows) {
		if (document.getElementById("btVer")) document.getElementById("btVer").disabled = true;
		if (document.getElementById("btVerDoc")) document.getElementById("btVerDoc").disabled = true;
		if (document.getElementById("btImprimir")) document.getElementById("btImprimir").disabled = true;
		if (document.getElementById("btPDF")) document.getElementById("btPDF").disabled = true;
	}
	if (insert == "N") {
		if (document.getElementById("btNuevo")) document.getElementById("btNuevo").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btEditar")) document.getElementById("btEditar").disabled = true;
		if (document.getElementById("btRevisar")) document.getElementById("btRevisar").disabled = true;
		if (document.getElementById("btAprobar")) document.getElementById("btAprobar").disabled = true;
		if (document.getElementById("btAnular")) document.getElementById("btAnular").disabled = true;
		if (document.getElementById("btPrepago")) document.getElementById("btPrepago").disabled = true;
		if (document.getElementById("btPagoParcial")) document.getElementById("btPagoParcial").disabled = true;
		if (document.getElementById("btBeneficiario")) document.getElementById("btBeneficiario").disabled = true;
		if (document.getElementById("btActualizar")) document.getElementById("btActualizar").disabled = true;
		if (document.getElementById("btDesactualizar")) document.getElementById("btDesactualizar").disabled = true;
		if (document.getElementById("btProcesar")) document.getElementById("btProcesar").disabled = true;
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
}
function botones(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
		
	if (!rows) {
		if (document.getElementById("btVer")) document.getElementById("btVer").disabled = true;
		if (document.getElementById("btImprimir")) document.getElementById("btImprimir").disabled = true;
	}
	if (insert == "N") {
		if (document.getElementById("btNuevo")) document.getElementById("btNuevo").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btEditar")) document.getElementById("btEditar").disabled = true;
		if (document.getElementById("btRevisar")) document.getElementById("btRevisar").disabled = true;
		if (document.getElementById("btAprobar")) document.getElementById("btAprobar").disabled = true;
		if (document.getElementById("btAnular")) document.getElementById("btAnular").disabled = true;
		if (document.getElementById("btPrepago")) document.getElementById("btPrepago").disabled = true;
		if (document.getElementById("btPagoParcial")) document.getElementById("btPagoParcial").disabled = true;
		if (document.getElementById("btBeneficiario")) document.getElementById("btBeneficiario").disabled = true;
		if (document.getElementById("btActualizar")) document.getElementById("btActualizar").disabled = true;
		if (document.getElementById("btDesactualizar")) document.getElementById("btDesactualizar").disabled = true;
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
}
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
function totalListaRows(id, rows) {
	var numreg = document.getElementById(id);
	numreg.innerHTML="Registros: "+rows;
}
function setLotes(form, lote, registros, limit, ordenar) {
	switch (lote) {
		case "P":
			limit=0;
			break;
		case "A":
			limit=limit-MAXLIMIT;
			break;
		case "S":
			limit=limit+MAXLIMIT;
			break;
		case "U":
			var num=(registros/MAXLIMIT);
			num=parseInt(num);
			limit=num*MAXLIMIT;
			if (limit==registros) limit=limit-MAXLIMIT;
			break;
	}
	document.getElementById("limit").value = limit;
	var pagina=form.action;
	cargarPagina(form, pagina);
}

function selListado(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	window.close();
}

function selListadoConceptoDistribucion(busqueda, cod, nom, codpartida, nompartida, codcuenta, nomcuenta) {
	var partes = cod.split("_");
	var nro = partes[1];
	var idcodpartida = "codpartida_" + nro;
	var idnompartida = "nompartida_" + nro;
	var idcodcuenta = "codcuenta_" + nro;
	var idnomcuenta = "nomcuenta_" + nro;	
	var registro = document.getElementById("registro").value;
	opener.document.getElementById(cod).value = registro;
	opener.document.getElementById(nom).value = busqueda;
	opener.document.getElementById(idcodpartida).value = codpartida;
	opener.document.getElementById(idnompartida).value = nompartida;
	opener.document.getElementById(idcodcuenta).value = codcuenta;
	opener.document.getElementById(idnomcuenta).value = nomcuenta;
	window.close();
}

//	FUNCION PARA BLOQUEAR/DESBLOQUEAR INPUTS DEL FILTRO
function chkFiltro(boo, id) {
	document.getElementById(id).value = "";
	document.getElementById(id).disabled = !boo;
}
function chkFiltro_2(boo, id1, id2) {
	document.getElementById(id1).value = "";
	document.getElementById(id2).value = "";
	document.getElementById(id1).disabled = !boo;
	document.getElementById(id2).disabled = !boo;
}
function chkFiltro_3(boo, id) {
	document.getElementById(id).disabled = !boo;
}
function chkBtFiltro(boo, id1, id2, id3) {
	document.getElementById(id1).value = "";
	document.getElementById(id2).value = "";
	document.getElementById(id3).disabled = !boo;
}
function chkFiltroProveedor(boo) {
	document.getElementById("fcodproveedor").value = "";
	document.getElementById("fnomproveedor").value = "";
	document.getElementById("btProveedor").disabled = !boo;
}
function chkFiltroIngresado(boo) {
	document.getElementById("fcodingresado").value = "";
	document.getElementById("fnomingresado").value = "";
	document.getElementById("btIngresado").disabled = !boo;
}
function chkFiltroCCosto(boo) {
	document.getElementById("fcodccosto").value = "";
	document.getElementById("fnomccosto").value = "";
	document.getElementById("btCCosto").disabled = !boo;
}
function chkFiltroFDocumento(boo) {
	document.getElementById("fdocumentod").value = "";
	document.getElementById("fdocumentoh").value = "";
	document.getElementById("fdocumentod").disabled = !boo;
	document.getElementById("fdocumentoh").disabled = !boo;
}
function chkFiltroFRegistro(boo) {
	document.getElementById("fregistrod").value = "";
	document.getElementById("fregistroh").value = "";
	document.getElementById("fregistrod").disabled = !boo;
	document.getElementById("fregistroh").disabled = !boo;
}
function enabledBuscar(form) {
	if (form.chkbuscar.checked) { form.fbuscar.disabled=false; form.sltbuscar.disabled=false; } 
	else { form.fbuscar.disabled=true; form.sltbuscar.disabled=true; form.fbuscar.value=""; form.sltbuscar.value=""; }
}
function enabledOrganismo(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; 
	else { form.forganismo.disabled=true; form.fdependencia.disabled=true; form.chkdependencia.checked=false; form.forganismo.value=""; form.fdependencia.value=""; }
}
function enabledDependencia(form) {
	if (form.chkorganismo.checked && form.chkdependencia.checked) form.fdependencia.disabled=false;
	else { form.fdependencia.disabled=true; form.fdependencia.value=""; }
}

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getOptions_2(idSelectOrigen, idSelectDestino) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		nuevaOpcion=document.createElement("option");
		nuevaOpcion.value="";
		nuevaOpcion.innerHTML="";
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;
	} else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion);
				selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_2(idSelectOrigen, idSelectDestino, idChkDestino) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	var chkDestino=document.getElementById(idChkDestino);
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		nuevaOpcion=document.createElement("option");
		nuevaOpcion.value="";
		nuevaOpcion.innerHTML="";
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;
		chkDestino.checked=false;
	} else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion);
				selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getCuentaBancariaDefault(organismo, tpago, ctabancaria) {
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=getCuentaBancariaDefault&codorganismo="+organismo.value+"&codtipopago="+tpago.value);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			ctabancaria.value = resp;
		}
	}
}

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

function abrirListado(seldetalle, pagina) {
	if (seldetalle == "") alert("¡Debe seleccionar un registro!");
	else {
		window.open(pagina+"&seldetalle="+seldetalle, "wListado", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes");
	}
}

function insertarLinea(boton, nro, cant, accion, seldetalle, listaDetalles) {
	boton.disabled = true;
	nrodetalles = new Number(document.getElementById(nro).value); nrodetalles++;
	cantdetalles = new Number(document.getElementById(cant).value); cantdetalles++;
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion="+accion+"&nrodetalles="+nrodetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById(nro).value = nrodetalles;
			document.getElementById(cant).value = cantdetalles;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, '"+seldetalle+"');");
			newTr.id = nrodetalles;
			document.getElementById(listaDetalles).appendChild(newTr);
			document.getElementById(nrodetalles).innerHTML = resp;
			boton.disabled = false;
		}
	}
}

function quitarLinea(seldetalle, listaDetalles) {
	if (document.getElementById(seldetalle).value == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById(listaDetalles); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById(seldetalle).value = "";
	}
}

function listadoTipoTransaccionBancaria(cod, nom, tipo, seldetalle) {
	opener.document.getElementById("codtransaccion"+seldetalle).value = cod;
	opener.document.getElementById("nomtransaccion"+seldetalle).value = nom;
	opener.document.getElementById("tipotransaccion"+seldetalle).value = tipo;
	window.close();
}

//	--------------------------------------------------------------------------------
//	--
//	--------------------------------------------------------------------------------

//	funcion para mostrar los detalles de los documentos en facturacion
function verDocumentoDetalles(seldocumento) {
	var detalles = "";
	frmdetalles = document.getElementById("frmdocumentos");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=verDocumentoDetalles&seldocumento="+seldocumento+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("trDetalle").innerHTML = resp;
		}
	}
}

//	CLASIFICACION DE DOCUMENTOS
function verificarDocumentoClasificacion(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var codcuenta = document.getElementById("codcuenta").value; codcuenta = codcuenta.trim();
	if (document.getElementById("flagitem").checked) var flagitem = "S"; else var flagitem = "N";
	if (document.getElementById("flagcliente").checked) var flagcliente = "S"; else var flagcliente = "N";
	if (document.getElementById("flagcompromiso").checked) var flagcompromiso = "S"; else var flagcompromiso = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DOCUMENTOS-CLASIFICACION&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagitem="+flagitem+"&flagcliente="+flagcliente+"&flagcompromiso="+flagcompromiso+"&flagtransaccion="+flagtransaccion+"&estado="+estado+"&codcuenta="+codcuenta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else cargarPagina(form, "ap_documentos_clasificacion.php");
			}
		}
	}
	return false;
}

//	PREPARA FACTURA (OBLIGACION)
function prepararFactura(form) {
	var codorganismo = document.getElementById("codorganismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var clasificacion = document.getElementById("clasificacion").value;
	var detalles = document.getElementById("detalles").value;
	var tpago = document.getElementById("tpago").value;
	var porcentaje = document.getElementById("porcentaje").value;
	var tservicio = document.getElementById("tservicio").value;
	var tdoc = document.getElementById("tdoc").value;
	var nrodoc = document.getElementById("nrodoc").value;
	var codccosto = document.getElementById("codccosto").value;
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value));
	var monto_noafecto = new Number(setNumero(document.getElementById("monto_noafecto").value));
	var monto_impuesto = new Number(setNumero(document.getElementById("monto_impuesto").value));
	var monto_total = new Number(setNumero(document.getElementById("monto_total").value));
	var femision = document.getElementById("femision").value; femision = femision.trim();
	var frecepcion = document.getElementById("frecepcion").value; frecepcion = frecepcion.trim();
	var fpago = document.getElementById("fpago").value; fpago = fpago.trim();
	var fvencimiento = document.getElementById("fvencimiento").value; fvencimiento = fvencimiento.trim();
	var nroregistro = document.getElementById("nroregistro").value; nroregistro = nroregistro.trim();
	var glosavoucher = document.getElementById("glosavoucher").value; glosavoucher = glosavoucher.trim();
	var comentarios = document.getElementById("comentarios").value; comentarios = comentarios.trim();
	var nrofactura = document.getElementById("nrofactura").value.trim();
	if (document.getElementById("flagdiferir").checked) var flagdiferir = "S"; else var flagdiferir = "N";
	
	if (nrodoc == "" || codccosto == "" || femision == "" || frecepcion == "" || fpago == "" || fvencimiento == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FACTURACION-LOGISTICA&accion=prepararFactura&codorganismo="+codorganismo+"&codproveedor="+codproveedor+"&clasificacion="+clasificacion+"&detalles="+detalles+"&porcentaje="+porcentaje+"&tservicio="+tservicio+"&tdoc="+tdoc+"&nrodoc="+nrodoc+"&codccosto="+codccosto+"&monto_afecto="+monto_afecto+"&monto_noafecto="+monto_noafecto+"&monto_impuesto="+monto_impuesto+"&monto_total="+monto_total+"&femision="+femision+"&frecepcion="+frecepcion+"&fpago="+fpago+"&fvencimiento="+fvencimiento+"&nroregistro="+nroregistro+"&glosavoucher="+glosavoucher+"&comentarios="+comentarios+"&flagdiferir="+flagdiferir+"&tpago="+tpago+"&nrofactura="+nrofactura);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else { form.submit(); window.close(); }
			}
		}
	}
	return false;
}

function abrirPreparacionFactura() {
	var organismo = document.getElementById("forganismo").value;
	var proveedor = document.getElementById("fcodproveedor").value;
	var clasificacion = document.getElementById("fclasificacion").value;
	
	var detalles = "";
	frmdetalles = document.getElementById("frmdocumentos");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡ERROR: Debe seleccionar por lo menos un documento!");
	else window.open('facturacion_logistica_obligacion.php?detalles='+detalles+'&organismo='+organismo+'&proveedor='+proveedor+'&clasificacion='+clasificacion, 'wFactura', 'height=500, width=750, left=200, top=200, resizable=no');
}

//	CUENTAS BANCARIAS
function verificarCuentaBancaria(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var banco = document.getElementById("banco").value.trim();
	var nrocuenta = document.getElementById("nrocuenta").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var ctabanco = document.getElementById("ctabanco").value.trim();
	var tcuenta = document.getElementById("tcuenta").value.trim();
	var fapertura = document.getElementById("fapertura").value.trim();
	var codcuenta = document.getElementById("codcuenta").value.trim();
	var agencia = document.getElementById("agencia").value.trim();
	var distrito = document.getElementById("distrito").value.trim();
	var atencion = document.getElementById("atencion").value.trim();
	var cargo = document.getElementById("cargo").value.trim();
	if (document.getElementById("flagconciliacionb").checked) var flagconciliacionb = "S"; else var flagconciliacionb = "N";
	if (document.getElementById("flagconciliacionr").checked) var flagconciliacionr = "S"; else var flagconciliacionr = "N";
	if (document.getElementById("flagdebito").checked) var flagdebito = "S"; else var flagdebito = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	var detalles = "";
	
	frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "tpago") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (banco == "" || nrocuenta == "" || descripcion == "" || ctabanco == "" || tcuenta == "" || fapertura == "" || codcuenta == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CUENTA-BANCARIA&accion="+accion+"&organismo="+organismo+"&banco="+banco+"&nrocuenta="+nrocuenta+"&descripcion="+descripcion+"&ctabanco="+ctabanco+"&tcuenta="+tcuenta+"&fapertura="+fapertura+"&codcuenta="+codcuenta+"&agencia="+agencia+"&distrito="+distrito+"&atencion="+atencion+"&cargo="+cargo+"&flagconciliacionb="+flagconciliacionb+"&flagconciliacionr="+flagconciliacionr+"&flagdebito="+flagdebito+"&estado="+estado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else cargarPagina(form, "ap_cuentas_bancarias.php");
			}
		}
	}
	return false;
}
//	
function insertarTipoPagoDisponible() {
	nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
	cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles++;
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarTipoPagoDisponible&nrodetalles="+nrodetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("nrodetalles").value = nrodetalles;
			document.getElementById("cantdetalles").value = cantdetalles;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = nrodetalles;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById(nrodetalles).innerHTML = resp;
		}
	}
}
//	
function quitarTipoPagoDisponible(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById("seldetalle").value = "";
	}
}

//	CUENTAS BANCARIAS DEFAULT
function verificarCuentaBancariaDefault(form, accion) {
	var registro = document.getElementById("registro").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var nrocuenta = document.getElementById("nrocuenta").value.trim();
	var tpago = document.getElementById("tpago").value.trim();
	
	if (nrocuenta == "" || tpago == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CUENTA-BANCARIA-DEFAULT&accion="+accion+"&registro="+registro+"&organismo="+organismo+"&tpago="+tpago+"&nrocuenta="+nrocuenta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else cargarPagina(form, "ap_cuentas_bancarias_default.php");
			}
		}
	}
	return false;
}

//	TIPOS DE TRANSACCIONES BANCARIAS
function verificarTipoTransaccionBancaria(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var ttransaccion = document.getElementById("ttransaccion").value.trim();
	var codcuenta = document.getElementById("codcuenta").value.trim();
	var voucher = document.getElementById("voucher").value.trim();
	if (document.getElementById("flagvoucher").checked) var flagvoucher = "S"; else var flagvoucher = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPO-TRANSACCION-BANCARIA&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&ttransaccion="+ttransaccion+"&codcuenta="+codcuenta+"&voucher="+voucher+"&flagvoucher="+flagvoucher+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else cargarPagina(form, "ap_tipo_transaccion_bancaria.php");
			}
		}
	}
	return false;
}

//	OBLIGACIONES
function verificarObligaciones(form, accion) {
	var FechaFactura = document.getElementById("FechaFactura").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var nomingresado = document.getElementById("nomingresado").value.trim();
	var codvoucher = document.getElementById("codvoucher").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var fingresado = document.getElementById("fingresado").value.trim();
	var flagprovision = document.getElementById("flagprovision").value.trim();
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var nompagara = document.getElementById("nompagara").value.trim();
	var nomproveedor = document.getElementById("nomproveedor").value.trim();
	var diaspago = document.getElementById("diaspago").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var codpagara = document.getElementById("codpagara").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tdoc = document.getElementById("tdoc").value.trim();
	var nrocontrol = document.getElementById("nrocontrol").value.trim();
	var nrofactura = document.getElementById("nrofactura").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var nroregistro = document.getElementById("nroregistro").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var femision = document.getElementById("femision").value.trim();
	var frecepcion = document.getElementById("frecepcion").value.trim();
	var fvencimiento = document.getElementById("fvencimiento").value.trim();
	var fpago = document.getElementById("fpago").value.trim();
	var tservicio = document.getElementById("tservicio").value.trim();
	var tpago = document.getElementById("tpago").value.trim();
	var glosavoucher = document.getElementById("glosavoucher").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	var ctabancaria = document.getElementById("ctabancaria").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value.trim()));
	var monto_noafecto = new Number(setNumero(document.getElementById("monto_noafecto").value.trim()));
	var monto_impuesto = new Number(setNumero(document.getElementById("monto_impuesto").value.trim()));
	var monto_retenciones = new Number(setNumero(document.getElementById("monto_retenciones").value.trim()));
	var monto_obligacion = new Number(setNumero(document.getElementById("monto_obligacion").value.trim()));
	var monto_adelanto = new Number(setNumero(document.getElementById("monto_adelanto").value.trim()));
	var monto_pagar = new Number(setNumero(document.getElementById("monto_pagar").value.trim()));
	var monto_parcial = new Number(setNumero(document.getElementById("monto_parcial").value.trim()));
	var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value.trim()));
	if (document.getElementById("flagcajachica").checked) var flagcajachica = "S"; else var flagcajachica = "N";
	if (document.getElementById("flagpagoindividual").checked) var flagpagoindividual = "S"; else var flagpagoindividual = "N";
	if (document.getElementById("flagpagoauto").checked) var flagpagoauto = "S"; else var flagpagoauto = "N";
	if (document.getElementById("flagpagodiferido").checked) var flagpagodiferido = "S"; else var flagpagodiferido = "N";
	if (document.getElementById("flagdiferido").checked) var flagdiferido = "S"; else var flagdiferido = "N";
	if (document.getElementById("flagafectoigv").checked) var flagafectoigv = "S"; else var flagafectoigv = "N";
	if (document.getElementById("flagcompromiso").checked) var flagcompromiso = "S"; else var flagcompromiso = "N";
	if (document.getElementById("flagpresupuesto").checked) var flagpresupuesto = "S"; else var flagpresupuesto = "N";
	if (document.getElementById("flagdistribucionmanual").checked) var flagdistribucionmanual = "S"; else var flagdistribucionmanual = "N";
	var detalles_imp = "";
	var error_detalles_imp = "";
	var detalles_doc = "";
	var error_detalles_doc = "";
	var detalles_dis = "";
	var error_detalles_dis = "";
	var error = "";
	
	// impuestos 
	var frmimpuestos = document.getElementById("frmimpuestos");
	for(i=0; n=frmimpuestos.elements[i]; i++) {
		if (n.name == "codimpuesto") detalles_imp += n.value + "|";
		else if (n.name == "signo") detalles_imp += n.value + "|";
		else if (n.name == "imponible") detalles_imp += n.value + "|";
		else if (n.name == "afecto") {
			var afecto = new Number(setNumero(n.value));
			if (isNaN(afecto)) { error_detalles_imp = "¡Debe ingresar un valor númerico en el campo afecto de los impuestos!"; break; }
			//else if (afecto == 0) { error_detalles_imp = "¡Debe ingresar un valor mayor a cero en el campo afecto de los impuestos!"; break; }
			detalles_imp += afecto + "|";
		}
		else if (n.name == "factor") {
			var factor = new Number(setNumero(n.value));
			detalles_imp += factor + ";";
		}
	}
	var len = detalles_imp.length; len--;
	detalles_imp = detalles_imp.substr(0, len);
	
	// documentos 
	var frmdocumentos = document.getElementById("frmdocumentos");
	for(i=0; n=frmdocumentos.elements[i]; i++) {
		if (n.name == "documento") detalles_doc += n.value + "|";
		else if (n.name == "doc_clasificacion") detalles_doc += n.value + "|";
		else if (n.name == "doc_referencia") detalles_doc += n.value + "|";
		else if (n.name == "monto") detalles_doc += n.value + "|";
		else if (n.name == "afecto") detalles_doc += n.value + "|";
		else if (n.name == "noafecto") detalles_doc += n.value + "|";
		else if (n.name == "impuesto") detalles_doc += n.value + "|";
		else if (n.name == "porcentaje_monto") detalles_doc += n.value + ";";
	}
	var len = detalles_doc.length; len--;
	detalles_doc = detalles_doc.substr(0, len);
	
	// distribucion 
	var frmdistribucion = document.getElementById("frmdistribucion");
	for(i=0; n=frmdistribucion.elements[i]; i++) {
		if (n.name == "codpartida") { detalles_dis += n.value + "|"; }
		else if (n.name == "codcuenta") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón lineas en la distribución sin cuentas contables!"; break; }
			else detalles_dis += n.value + "|";
		}
		else if (n.name == "ccosto") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón lineas en la distribución sin centro de costo!"; break; }
			else detalles_dis += n.value + "|";
		}
		else if (n.name == "flagnoafecto") {
			if (n.checked) detalles_dis += "S" + "|"; else detalles_dis += "N" + "|";
		}
		else if (n.name == "monto") detalles_dis += setNumero(n.value) + "|";
		else if (n.name == "nroorden") detalles_dis += n.value + "|";
		else if (n.name == "referencia") detalles_dis += n.value + "|";
		else if (n.name == "descripcion") detalles_dis += n.value + "|";
		else if (n.name == "codpersona") detalles_dis += n.value + "|";
		else if (n.name == "nroactivo") detalles_dis += n.value + "|";
		else if (n.name == "flagdiferido") {
			if (n.checked) detalles_dis += "S" + ";"; else detalles_dis += "N" + ";";
		}
	}
	var len = detalles_dis.length; len--;
	detalles_dis = detalles_dis.substr(0, len);
	
	if (accion != "ANULAR" && (codproveedor == "" || codpagara == "" || tdoc == "" || tservicio == "" || tpago == "" || nrofactura == "" || codccosto == "")) alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (accion != "ANULAR" && monto_obligacion == 0) alert("¡ERROR: El monto de la obligación no puede ser cero!");
	else if (accion != "ANULAR" && detalles_dis == "") alert("¡ERROR: Debe insertar la distribución para la obligación!");
	else if (accion != "ANULAR" && error_detalles_imp != "") alert(error_detalles_imp);
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=OBLIGACIONES&accion="+accion+"&codvoucher="+codvoucher+"&codingresado="+codingresado+"&fingresado="+fingresado+"&flagprovision="+flagprovision+"&codproveedor="+codproveedor+"&diaspago="+diaspago+"&organismo="+organismo+"&codpagara="+codpagara+"&tdoc="+tdoc+"&codccosto="+codccosto+"&nrocontrol="+nrocontrol+"&nrofactura="+nrofactura+"&estado="+estado+"&nroregistro="+nroregistro+"&fregistro="+fregistro+"&femision="+femision+"&frecepcion="+frecepcion+"&fvencimiento="+fvencimiento+"&fpago="+fpago+"&tservicio="+tservicio+"&tpago="+tpago+"&glosavoucher="+glosavoucher+"&comentarios="+comentarios+"&docinterno="+docinterno+"&ctabancaria="+ctabancaria+"&codingresado="+codingresado+"&fingresado="+fingresado+"&monto_afecto="+monto_afecto+"&monto_noafecto="+monto_noafecto+"&monto_impuesto="+monto_impuesto+"&monto_retenciones="+monto_retenciones+"&monto_obligacion="+monto_obligacion+"&monto_adelanto="+monto_adelanto+"&monto_pagar="+monto_pagar+"&monto_parcial="+monto_parcial+"&monto_pendiente="+monto_pendiente+"&flagcajachica="+flagcajachica+"&flagpagoindividual="+flagpagoindividual+"&flagpagoauto="+flagpagoauto+"&flagpagodiferido="+flagpagodiferido+"&flagdiferido="+flagdiferido+"&flagafectoigv="+flagafectoigv+"&flagcompromiso="+flagcompromiso+"&flagpresupuesto="+flagpresupuesto+"&detalles_imp="+detalles_imp+"&detalles_doc="+detalles_doc+"&detalles_dis="+detalles_dis+"&nompagara="+nompagara+"&nomproveedor="+nomproveedor+"&flagdistribucionmanual="+flagdistribucionmanual+"&periodo="+periodo+"&FechaFactura="+FechaFactura);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else {
					//if (accion == "APROBAR" && flagprovision == "S") window.open("ap_obligaciones_provision.php?codproveedor="+codproveedor+"&tdoc="+tdoc+"&nrofactura="+nrofactura+"&organismo="+organismo, "w_aprobar_obligacion_provision", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes");
					if (accion == "APROBAR" && flagprovision == "S") {
						var registro = organismo + "." + codproveedor + "." + tdoc + "." + nrofactura + "." + codvoucher;
						generar_vouchers_abrir(registro, 'ap_generar_vouchers_provision_voucher');
					}
					form.submit();
				}
			}
		}
	}
	return false;
}

function generarVoucher(form) {
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var tdoc = document.getElementById("tdoc").value.trim();
	var nrofactura = document.getElementById("nrofactura").value.trim();
	var codsistemafuente = document.getElementById("codsistemafuente").value.trim();
	var voucher = document.getElementById("voucher").value.trim();
	var codvoucher = document.getElementById("codvoucher").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var fecha = document.getElementById("fecha").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var libro_contable = document.getElementById("libro_contable").value.trim();
	var CodDependencia = document.getElementById("CodDependencia").value.trim();
	
	// distribucion 
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "_codcuenta") detalles += n.value + "|";
		if (n.name == "_monto") detalles += n.value + "|";
		if (n.name == "_ccosto") detalles += n.value + "|";
		if (n.name == "_comentarios") detalles += n.value + "|";
		if (n.name == "_tiposaldo") detalles += n.value + "|";
		if (n.name == "_torden") detalles += n.value + "|";
		if (n.name == "_norden") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (!valFecha(fecha)) alert("¡ERROR: Formato de fecha incorrecta!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=OBLIGACIONES&accion=PROVISION&codproveedor="+codproveedor+"&tdoc="+tdoc+"&nrofactura="+nrofactura+"&codsistemafuente="+codsistemafuente+"&voucher="+voucher+"&codvoucher="+codvoucher+"&organismo="+organismo+"&descripcion="+descripcion+"&comentarios="+comentarios+"&fecha="+fecha+"&codingresado="+codingresado+"&libro_contable="+libro_contable+"&detalles="+detalles+"&CodDependencia="+CodDependencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else window.close();
			}
		}
	}
	return false;
}

function insertarImpuesto(canimpuesto) {
	var monto_obligacion = new Number(setNumero(document.getElementById("monto_obligacion").value));	
	if (monto_obligacion == 0) alert("¡No se pueden ingresar impuestos si el monto de la obligación es igual a cero!");
	else {
		var canimpuesto = new Number(document.getElementById('canimpuesto').value);	canimpuesto++;
		document.getElementById('canimpuesto').value = canimpuesto;
		
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarImpuesto&canimpuesto="+canimpuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				if (datos[0].trim() != "") alert(resp);
				else {
					var newTr = document.createElement("tr");
					newTr.className = "trListaBody";
					newTr.setAttribute("onclick", "mClk(this, 'selimpuesto');");
					newTr.id = "imp_"+canimpuesto;
					document.getElementById("listaImpuestos").appendChild(newTr);
					document.getElementById("imp_"+canimpuesto).innerHTML = datos[1];
				}
			}
		}
	}
}

function quitarLineaImpuesto(selimpuesto) {
	if (selimpuesto == "") alert("¡Debe seleccionar una linea!");
	else {;
		var monto_obligacion = new Number(setNumero(document.getElementById("monto_obligacion").value));
		var monto_pagar = new Number(setNumero(document.getElementById("monto_pagar").value));
		var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value));
		var imp_monto = new Number(setNumero(document.getElementById("imp_monto").innerHTML));
		var partes_sel = selimpuesto.split("_");
		var monto = new Number(setNumero(document.getElementById("monto_"+partes_sel[1]).innerHTML));
		
		imp_monto -= monto;
		monto_obligacion -= monto;
		monto_pagar -= monto;
		monto_pendiente -= monto;
		
		document.getElementById("monto_retenciones").value = setNumeroFormato(imp_monto, 2, ".", ",");
		document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, ".", ",");
		document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, ".", ",");
		document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, ".", ",");
		document.getElementById("imp_monto").innerHTML = setNumeroFormato(imp_monto, 2, ".", ",");
		
		var listaDetalles = document.getElementById("listaImpuestos"); 
		var tr = document.getElementById(selimpuesto);
		listaDetalles.removeChild(tr);
		document.getElementById("selimpuesto").value = "";
	}
}

function getFactorPorcentaje(codimpuesto, canimpuesto) {
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=getFactorPorcentaje&codimpuesto="+codimpuesto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("|");
			if (datos[0].trim() != "") alert(resp);
			else {
				document.getElementById("factor_"+canimpuesto).value = setNumeroFormato(datos[1], 2, ".", ",");
				if (datos[2] == "N")
					document.getElementById("afecto_"+canimpuesto).value = document.getElementById("monto_afecto").value;
				else if (datos[2] == "I")
					document.getElementById("afecto_"+canimpuesto).value = document.getElementById("monto_impuesto").value;
				
				document.getElementById("imponible_"+canimpuesto).value = datos[2];
				document.getElementById("signo_"+canimpuesto).value = datos[3];
				calcularTotalImpuesto(canimpuesto);
			}
		}
	}
}

function calcularTotalImpuesto(i) {
	var s = new Number(setNumero(document.getElementById("signo_"+i).value));
	if (s == "P") var signo = new Number(1); else var signo = new Number(-1);
	var afecto = new Number(setNumero(document.getElementById("afecto_"+i).value));
	var factor = new Number(setNumero(document.getElementById("factor_"+i).value));
	var total = (afecto * factor / 100) * signo;
	document.getElementById("monto_"+i).innerHTML = setNumeroFormato(total, 2, ".", ",");
	
	// impuestos 
	var imp_monto = 0;
	var frmimpuestos = document.getElementById("frmimpuestos");
	for(i=0; n=frmimpuestos.elements[i]; i++) {
		if (n.name == "signo") {
			if (n.value == "P") var signo = new Number(1);
			else var signo = new Number(-1);
		}
		else if (n.name == "afecto") var afecto = new Number(setNumero(n.value));
		else if (n.name == "factor") {
			var factor = new Number(setNumero(n.value));
			imp_monto += (afecto * factor / 100) * signo;
		}
	}
	document.getElementById("imp_monto").innerHTML = setNumeroFormato(imp_monto, 2, ".", ",");
	var imp_monto = new Number(setNumero(document.getElementById("imp_monto").innerHTML));
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value));
	var monto_noafecto = new Number(setNumero(document.getElementById("monto_noafecto").value));
	var monto_impuesto = new Number(setNumero(document.getElementById("monto_impuesto").value));
	var monto_adelanto = new Number(setNumero(document.getElementById("monto_adelanto").value));
	var monto_parcial = new Number(setNumero(document.getElementById("monto_parcial").value));
	var monto_obligacion = monto_afecto + monto_noafecto + monto_impuesto + imp_monto;
	var monto_pagar = monto_obligacion + monto_adelanto;
	var monto_pendiente = monto_pagar + monto_parcial;
	document.getElementById("monto_retenciones").value = setNumeroFormato(imp_monto, 2, ".", ",");
	document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, ".", ",");
	document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, ".", ",");
	document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, ".", ",");
}

function resetearListaImpuesto() {
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value));
	var monto_impuesto = new Number(setNumero(document.getElementById("monto_impuesto").value));
	var monto_retenciones = new Number(0.00);
	
	if (monto_afecto == 0) limpiarMontosObligacionImpuesto();
	else {
		// impuestos
		var frmimpuestos = document.getElementById("frmimpuestos");
		for(i=0; n=frmimpuestos.elements[i]; i++) {
			if (n.name == "signo") {
				if (n.value == "P") var signo = new Number(1);
				else var signo = new Number(-1);
			}
			else if (n.name == "imponible") var imponible = n.value;
			else if (n.name == "afecto") {
				if (imponible == "I") var afecto = monto_impuesto;
				else if (imponible == "N") var afecto = monto_afecto;
				n.value = setNumeroFormato(afecto, 2, ".", ",");
			}
			else if (n.name == "factor") {
				var factor = new Number(setNumero(n.value));
				monto_retenciones += (afecto * factor / 100) * signo;
			}
		}
		document.getElementById("monto_retenciones").value = setNumeroFormato(monto_retenciones, 2, ".", ",");
		document.getElementById("imp_monto").innerHTML = setNumeroFormato(monto_retenciones, 2, ".", ",");
	}
}

function limpiarImpuestos() {
		var monto_retenciones = new Number(setNumero(document.getElementById("monto_retenciones").value));
		var monto_obligacion = new Number(setNumero(document.getElementById("monto_obligacion").value));
		var monto_pagar = new Number(setNumero(document.getElementById("monto_pagar").value));
		var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value));		
		monto_obligacion -= monto_retenciones;
		monto_pagar -= monto_retenciones;
		monto_pendiente -= monto_retenciones;
		document.getElementById("listaImpuestos").innerHTML = "";
		document.getElementById("selimpuesto").value = "";
		document.getElementById("canimpuesto").value = "0";
		document.getElementById("monto_retenciones").value = "0,00";
		document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, ".", ",");
		document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, ".", ",");
		document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, ".", ",");
}

function mostrarListaDocumentosObligaciones() {
	var codorganismo = document.getElementById("organismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	
	// documentos
	var detalles_doc = "";
	var error_detalles_doc = ""; 
	var frmdocumentos = document.getElementById("frmdocumentos");
	for(i=0; n=frmdocumentos.elements[i]; i++) {
		if (n.name == "documento") detalles_doc += n.value + "|";
		if (n.name == "monto") detalles_doc += n.value + ";";
	}
	var len = detalles_doc.length; len--;
	detalles_doc = detalles_doc.substr(0, len);
	
	if (codproveedor == "") alert("¡Debe seleccionar un proveedor para poder mostrar la lista de documentos pendientes!");
	else {
		window.open("listado_documentos_obligaciones.php?ventana=ap_obligaciones&limit=0&codorganismo="+codorganismo+"&codproveedor="+codproveedor+"&detalles_doc="+detalles_doc, "wListaDocumentos", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes");
	}
}

function insertarDocumento(documento) {
	var detalles_doc = document.getElementById("detalles_doc").value;
	var monto = new Number(setNumero(document.getElementById("monto_"+documento).value));
	
	if (isNaN(monto) || monto == 0) alert("ERROR: Debe ingresar un valor numérico");
	else if (monto > pendiente) alert("ERROR: El monto a pagar no puede ser mayor que el monto pendiente");
	else {
		var pendiente = document.getElementById("pendiente_"+documento).value;
		var pagado = document.getElementById("pagado_"+documento).value;
		var total = document.getElementById("total_"+documento).value;
		
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarDocumento&documento="+documento+"&monto="+monto+"&pendiente="+pendiente+"&pagado="+pagado+"&total="+total+"&detalles_doc="+detalles_doc);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				if (datos[0].trim() != "") alert(resp);
				else {
					if (opener.document.getElementById(documento) == null) {
						var newTr = opener.document.createElement("tr");
						newTr.className = "trListaBody";
						newTr.setAttribute("onclick", "mClk(this, 'seldocumento');");
						newTr.id = documento;
						opener.document.getElementById("listaDocumentos").appendChild(newTr);
						opener.document.getElementById(documento).innerHTML = datos[1];
						
						var newTbody = opener.document.createElement("tbody");
						newTbody.id = "listaDistribucion_"+documento;
						opener.document.getElementById("tblListaDis").appendChild(newTbody);
						opener.document.getElementById("listaDistribucion_"+documento).innerHTML = datos[2];
						
						var total = new Number(datos[3]);
						var afecto = new Number(datos[4]);
						var impuesto = new Number(datos[5]);
						var noafecto = new Number(datos[6]);
						var doc_total = new Number(setNumero(opener.document.getElementById("doc_total").innerHTML)); doc_total += total;
						var doc_afecto = new Number(setNumero(opener.document.getElementById("doc_afecto").innerHTML)); doc_afecto += afecto;
						var doc_impuesto = new Number(setNumero(opener.document.getElementById("doc_impuesto").innerHTML)); doc_impuesto += impuesto;
						var doc_noafecto = new Number(setNumero(opener.document.getElementById("doc_noafecto").innerHTML)); doc_noafecto += noafecto;
						var monto_parcial = new Number(setNumero(opener.document.getElementById("monto_parcial").value));
						var monto_adelanto = new Number(setNumero(opener.document.getElementById("monto_adelanto").value));
						opener.document.getElementById("doc_total").innerHTML = setNumeroFormato(doc_total, 2, '.', ',');
						opener.document.getElementById("doc_afecto").innerHTML = setNumeroFormato(doc_afecto, 2, '.', ',');
						opener.document.getElementById("doc_impuesto").innerHTML = setNumeroFormato(doc_impuesto, 2, '.', ',');
						opener.document.getElementById("doc_noafecto").innerHTML = setNumeroFormato(doc_noafecto, 2, '.', ',');
												
						var partida = new Number(datos[7]);
						var dis_total = new Number(setNumero(opener.document.getElementById("dis_total").innerHTML));	dis_total += partida;
						opener.document.getElementById("dis_total").innerHTML = setNumeroFormato(dis_total, 2, '.', ',');
						
						opener.document.getElementById("monto_afecto").value = setNumeroFormato(doc_afecto, 2, '.', ',');
						opener.document.getElementById("monto_noafecto").value = setNumeroFormato(doc_noafecto, 2, '.', ',');
						opener.document.getElementById("monto_impuesto").value = setNumeroFormato(doc_impuesto, 2, '.', ',');
						
						// impuestos 
						var total_retenciones = new Number(0.00);
						var frmimpuestos = opener.document.getElementById("frmimpuestos");
						for(i=0; n=frmimpuestos.elements[i]; i++) {
							if (n.name == "nroimpuesto") var nro = n.value;
							if (n.name == "signo") var isigno = n.value;
							if (n.name == "imponible") var iimponible = n.value;
							if (n.name == "afecto") {
								if (iimponible == "N") {
									var iafecto = doc_afecto;
									opener.document.getElementById("afecto_"+nro).value = setNumeroFormato(doc_afecto, 2, '.', ',');;
								}
								else if (iimponible == "I") {
									var iafecto = doc_impuesto;
									opener.document.getElementById("afecto_"+nro).value = setNumeroFormato(doc_impuesto, 2, '.', ',');;
								}
							}
							if (n.name == "factor") {
								var ifactor = new Number(setNumero(n.value));
								var imonto = iafecto * ifactor / 100;
								opener.document.getElementById("monto_"+nro).innerHTML = setNumeroFormato(imonto, 2, '.', ',');;
								total_retenciones += imonto;
							}
						}
						opener.document.getElementById("imp_monto").innerHTML = setNumeroFormato(total_retenciones, 2, ".", ",");
						var monto_obligacion = doc_total - total_retenciones; 
						var monto_pagar = monto_obligacion - monto_adelanto; 
						var monto_pendiente = monto_pagar - monto_parcial; 
						opener.document.getElementById("monto_retenciones").value = setNumeroFormato(total_retenciones, 2, '.', ',');
						opener.document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, '.', ',');
						opener.document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, '.', ',');
						opener.document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, '.', ',');
						
						window.close();
					} else alert("¡No puede seleccionar dos veces la misma orden!");
				}
			}
		}
	}
}

function quitarLineaDocumento(seldocumento) {
	var total_monto = new Number(0.00);
	var total_afecto = new Number(0.00);
	var total_noafecto = new Number(0.00);
	var total_impuesto = new Number(0.00);
	
	if (seldocumento == "") alert("¡Debe seleccionar una linea!");
	else if (confirm("¿Está seguro de quitar el documento?")) {
		var listaDetalles = document.getElementById("listaDocumentos"); 
		var tr = document.getElementById(seldocumento);
		listaDetalles.removeChild(tr);
		document.getElementById("seldocumento").value = "";
		
		//	actualizo los totales
		var frmdocumentos = document.getElementById("frmdocumentos");
		for(i=0; n=frmdocumentos.elements[i]; i++) {
			if (n.name == "monto") {
				var monto = new Number(setNumero(n.value));
				total_monto += monto;
			}
			if (n.name == "afecto") {
				var afecto = new Number(setNumero(n.value));
				total_afecto += afecto;
			}
			if (n.name == "noafecto") {
				var noafecto = new Number(setNumero(n.value));
				total_noafecto += noafecto;
			}
			if (n.name == "impuesto") {
				var impuesto = new Number(setNumero(n.value));
				total_impuesto += impuesto;
			}
		}		
		document.getElementById("doc_total").innerHTML = setNumeroFormato(total_monto, 2, '.', ',');
		document.getElementById("doc_afecto").innerHTML = setNumeroFormato(total_afecto, 2, '.', ',');
		document.getElementById("doc_noafecto").innerHTML = setNumeroFormato(total_noafecto, 2, '.', ',');
		document.getElementById("doc_impuesto").innerHTML = setNumeroFormato(total_impuesto, 2, '.', ',');
		
		//	elimino la distribucion de la orden
		var listaDetalles = document.getElementById("tblListaDis");
		var tr = document.getElementById("listaDistribucion_"+seldocumento);
		listaDetalles.removeChild(tr);
		
		var total_partida = total_afecto + total_noafecto;
		document.getElementById("dis_total").innerHTML = setNumeroFormato(total_partida, 2, '.', ',');
		
		//	si el monto de la obligacion es igual a cero elimino los impuestos y los valores
		if (total_monto == 0) {
			document.getElementById("listaImpuestos").innerHTML = "";
			document.getElementById("imp_monto").innerHTML = "0,00";
			document.getElementById("monto_afecto").value = "0,00";
			document.getElementById("monto_noafecto").value = "0,00";
			document.getElementById("monto_impuesto").value = "0,00";
			document.getElementById("monto_retenciones").value = "0,00";
			document.getElementById("monto_obligacion").value = "0,00";
			document.getElementById("monto_adelanto").value = "0,00";
			document.getElementById("monto_pagar").value = "0,00";
			document.getElementById("monto_parcial").value = "0,00";
			document.getElementById("monto_pendiente").value = "0,00";
		} else {
			// impuestos 
			var total_retenciones = new Number(0.00);
			var frmimpuestos = document.getElementById("frmimpuestos");
			for(i=0; n=frmimpuestos.elements[i]; i++) {
				if (n.name == "nroimpuesto") var nro = n.value;
				if (n.name == "signo") var isigno = n.value;
				if (n.name == "imponible") var iimponible = n.value;
				if (n.name == "afecto") {
					if (iimponible == "N") {
						var iafecto = new Number(setNumero(document.getElementById("doc_afecto").innerHTML));
						document.getElementById("afecto_"+nro).value = document.getElementById("doc_afecto").innerHTML;
					}
					else if (iimponible == "I") {
						var iafecto = new Number(setNumero(document.getElementById("doc_impuesto").innerHTML));
						document.getElementById("afecto_"+nro).value = document.getElementById("doc_impuesto").innerHTML;
					}
				}
				if (n.name == "factor") {
					var ifactor = new Number(setNumero(n.value));
					var imonto = iafecto * ifactor / 100;
					document.getElementById("monto_"+nro).innerHTML = setNumeroFormato(imonto, 2, '.', ',');;
					total_retenciones += imonto;
				}
			}
			document.getElementById("imp_monto").innerHTML = setNumeroFormato(total_retenciones, 2, ".", ",");
			document.getElementById("monto_afecto").value = setNumeroFormato(total_afecto, 2, ".", ",");
			document.getElementById("monto_noafecto").value = setNumeroFormato(total_noafecto, 2, ".", ",");
			document.getElementById("monto_impuesto").value = setNumeroFormato(total_impuesto, 2, ".", ",");
			document.getElementById("monto_retenciones").value = setNumeroFormato(total_retenciones, 2, ".", ",");
			
			var imp_monto = new Number(setNumero(document.getElementById("imp_monto").innerHTML));
			var monto_afecto = new Number(setNumero(document.getElementById("doc_afecto").innerHTML));
			var monto_noafecto = new Number(setNumero(document.getElementById("doc_noafecto").innerHTML));
			var monto_impuesto = new Number(setNumero(document.getElementById("doc_impuesto").innerHTML));
			var monto_adelanto = new Number(setNumero(document.getElementById("monto_adelanto").value));
			var monto_parcial = new Number(setNumero(document.getElementById("monto_parcial").value));
			var monto_obligacion = monto_afecto + monto_noafecto + monto_impuesto - imp_monto;
			var monto_pagar = monto_obligacion + monto_adelanto;
			var monto_pendiente = monto_pagar + monto_parcial;
			document.getElementById("monto_afecto").value = setNumeroFormato(monto_afecto, 2, ".", ",");
			document.getElementById("monto_retenciones").value = setNumeroFormato(monto_noafecto, 2, ".", ",");
			document.getElementById("monto_impuesto").value = setNumeroFormato(monto_impuesto, 2, ".", ",");
			document.getElementById("monto_retenciones").value = setNumeroFormato(imp_monto, 2, ".", ",");
			document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, ".", ",");
			document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, ".", ",");
			document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, ".", ",");
		}
	}
}

function insertarObligacionCuenta() {
	var codproveedor = document.getElementById("codproveedor").value;
	var codccosto = document.getElementById("codccosto").value;
	if (codproveedor == "") alert("¡Debe seleccionar un proveedor para poder insertar una linea!");
	else {
		var can = new Number(document.getElementById("candistribucion").value); can++;
		var nro = new Number(document.getElementById("nrodistribucion").value); nro++;
		document.getElementById("candistribucion").value = can;
		document.getElementById("nrodistribucion").value = nro;
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarObligacionCuenta&can="+can+"&codproveedor="+codproveedor+"&codccosto="+codccosto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldistribucion');");
				newTr.id = "distribucion_"+can;
				document.getElementById("listaDistribucion").appendChild(newTr);
				document.getElementById("distribucion_"+can).innerHTML = resp;
				document.getElementById("btInsertarDocumento").disabled = true;
				document.getElementById("btQuitarDocumento").disabled = true;
			}
		}
	}
}

function quitarObligacionCuenta(seldistribucion) {
	if (seldistribucion == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDistribucion"); 
		var tr = document.getElementById(seldistribucion);
		listaDetalles.removeChild(tr);
		document.getElementById("seldistribucion").value = "";
		setTotalDistribucionObligacion();
		resetearListaImpuesto();
		setTotalDistribucionObligacion();
		
		var nro = new Number(document.getElementById("nrodistribucion").value); nro--;
		document.getElementById("nrodistribucion").value = nro;
		if (nro == 0) {
			document.getElementById("btInsertarDocumento").disabled = false;
			document.getElementById("btQuitarDocumento").disabled = false;
		}
	}
}

function abrirSelLista(frm, selcampo, codcampo, nomcampo, lista, h , w, codcampo2, nomcampo2) {
	var form = document.getElementById(frm);
	var sel = document.getElementById(selcampo).value;
	
	if (sel == "") alert("¡Debe seleccionar una linea!");
	else {
		var partes = sel.split("_");
		var cod = codcampo + "_" + partes[1];
		var nom = nomcampo + "_" + partes[1];
		if (codcampo2) var cod2 = codcampo2 + "_" + partes[1]; else var cod2 = "";
		if (nomcampo2) var nom2 = nomcampo2 + "_" + partes[1]; else var nom2 = "";
		var pagina = lista + "cod="+cod+"&nom="+nom+"&cod2="+cod2+"&nom2="+nom2;
		cargarVentana(form, pagina, "height="+h+", width="+w+", left=50, top=50, resizable=yes");
	}
}

function setTotalDistribucionObligacion() {
	var total_distribucion = new Number(0.00);
	var monto_afecto = new Number(0.00);
	var monto_noafecto = new Number(0.00);
	var monto_impuesto = new Number(0.00);
	var monto_retenciones = new Number(setNumero(document.getElementById("monto_retenciones").value));
	var monto_obligacion = new Number(0.00);
	var monto_adelanto = new Number(setNumero(document.getElementById("monto_adelanto").value));
	var monto_pagar = new Number(0.00);
	var monto_parcial = new Number(setNumero(document.getElementById("monto_parcial").value));
	var monto_pendiente = new Number(0.00);
	
	// distribucion
	var frmdistribucion = document.getElementById("frmdistribucion");
	for(i=0; n=frmdistribucion.elements[i]; i++) {
		if (n.name == "flagnoafecto") {
			if (n.checked) {
				var flagnoafecto = "S";
				var factor = new Number(0.00);
			} else {
				var flagnoafecto = "N";
				var factor = new Number(n.value);
			}
		}
		else if (n.name == "monto") {
			var monto = new Number(setNumero(n.value));
			var impuesto = monto * factor / 100;
			if (flagnoafecto == "S") monto_noafecto += monto; else monto_afecto += monto;
			monto_impuesto += impuesto;
			total_distribucion += monto;
		}
	}
	
	monto_obligacion = monto_afecto + monto_noafecto + monto_impuesto + monto_retenciones;
	monto_pagar = monto_obligacion - monto_adelanto;
	monto_pendiente = monto_pagar - monto_parcial;	
	document.getElementById("dis_total").innerHTML = setNumeroFormato(total_distribucion, 2, ".", ",");
	document.getElementById("monto_afecto").value = setNumeroFormato(monto_afecto, 2, ".", ",");
	document.getElementById("monto_noafecto").value = setNumeroFormato(monto_noafecto, 2, ".", ",");
	document.getElementById("monto_impuesto").value = setNumeroFormato(monto_impuesto, 2, ".", ",");
	document.getElementById("monto_obligacion").value = setNumeroFormato(monto_obligacion, 2, ".", ",");
	document.getElementById("monto_pagar").value = setNumeroFormato(monto_pagar, 2, ".", ",");
	document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 2, ".", ",");
}

function limpiarMontosObligacion() {
	document.getElementById('seldistribucion').value = "";
	document.getElementById('nrodistribucion').value = "0";
	document.getElementById('candistribucion').value = "0";
	document.getElementById('listaDistribucion').innerHTML = "";
	document.getElementById('seldocumento').value = "";
	document.getElementById('candocumento').value = "0";
	document.getElementById('listaDocumentos').innerHTML = "";	
	document.getElementById('selimpuesto').value = "";
	document.getElementById('canimpuesto').value = "0";
	document.getElementById('listaImpuestos').innerHTML = "";	
	document.getElementById('imp_monto').innerHTML = "0,00";
	document.getElementById('doc_total').innerHTML = "0,00";
	document.getElementById('doc_afecto').innerHTML = "0,00";
	document.getElementById('doc_impuesto').innerHTML = "0,00";
	document.getElementById('doc_noafecto').innerHTML = "0,00";
	document.getElementById('dis_total').innerHTML = "0,00";	
	document.getElementById('monto_afecto').value = "0,00";
	document.getElementById('monto_noafecto').value = "0,00";
	document.getElementById('monto_impuesto').value = "0,00";
	document.getElementById('monto_retenciones').value = "0,00";
	document.getElementById('monto_obligacion').value = "0,00";
	document.getElementById('monto_adelanto').value = "0,00";
	document.getElementById('monto_pagar').value = "0,00";
	document.getElementById('monto_parcial').value = "0,00";
	document.getElementById('monto_pendiente').value = "0,00";
}

function limpiarMontosObligacionImpuesto() {
	document.getElementById('selimpuesto').value = "";
	document.getElementById('canimpuesto').value = "0";
	document.getElementById('listaImpuestos').innerHTML = "";	
	document.getElementById('imp_monto').innerHTML = "0,00";
	document.getElementById('monto_retenciones').value = "0,00";
}

function afectaPresupuesto(chk) {
	document.getElementById('flagdistribucionmanual').disabled = !chk;
	document.getElementById('flagdistribucionmanual').checked = !chk;
	distribuirObligacion(!chk);
	limpiarMontosObligacion();
}

function distribuirObligacion(chk) {
	document.getElementById('btSelCuenta').disabled = !chk;
	document.getElementById('btSelCCosto').disabled = !chk;
	document.getElementById('btSelPersona').disabled = !chk;
	document.getElementById('btSelActivo').disabled = !chk;
	document.getElementById('btInsertarDistribucion').disabled = !chk;
	document.getElementById('btQuitarDistribucion').disabled = !chk;	
	document.getElementById('btInsertarDocumento').disabled = chk;
	document.getElementById('btQuitarDocumento').disabled = chk;
	limpiarMontosObligacion();
	if (document.getElementById('flagdistribucionmanual').checked && document.getElementById('flagpresupuesto').checked) {
		document.getElementById('btSelPartida').disabled = false;
	} else {
		document.getElementById('btSelPartida').disabled = true;
	}
}

function validarFiltroFacturacionLogistica(form) {
	if (document.getElementById("fcodproveedor").value.trim() == "") {
		alert("¡Debe seleccionar un proveedor!");
		return false;
	} else return true;
}

function tservicio_obligacion(codtiposervicio) {
	document.getElementById("listaImpuestos").innerHTML = "";
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value));
	var monto_impuesto = new Number(setNumero(document.getElementById("monto_impuesto").value));
	var monto_obligacion = new Number(setNumero(document.getElementById("monto_obligacion").value));
	var monto_adelanto = new Number(setNumero(document.getElementById("monto_adelanto").value));
	var monto_pagar = new Number(setNumero(document.getElementById("monto_pagar").value));
	var monto_parcial = new Number(setNumero(document.getElementById("monto_parcial").value));
	var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value));
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=tservicio_obligacion&codtiposervicio="+codtiposervicio+"&monto_afecto="+monto_afecto+"&monto_impuesto="+monto_impuesto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			var monto_retenciones = new Number(datos[2]);
			monto_obligacion -= monto_retenciones;
			monto_pagar -= monto_retenciones;
			monto_pendiente -= monto_retenciones;
			document.getElementById("imp_monto").innerHTML = setNumeroFormato(monto_retenciones, 2, ".", ",");
			document.getElementById("monto_obligacion").innerHTML = setNumeroFormato(monto_obligacion, 2, ".", ",");
			document.getElementById("monto_pagar").innerHTML = setNumeroFormato(monto_pagar, 2, ".", ",");
			document.getElementById("monto_pendiente").innerHTML = setNumeroFormato(monto_pendiente, 2, ".", ",");
			document.getElementById("listaImpuestos").innerHTML = datos[0];
			document.getElementById("canimpuesto").value = datos[1];
		}
	}
}

//	ORDEN DE PAGO
function verificarOrdenPago(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var nroorden = document.getElementById("nroorden").value.trim();
	var tpago = document.getElementById("tpago").value.trim();
	var nompagara = document.getElementById("nompagara").value.trim();
	var ctabancaria = document.getElementById("ctabancaria").value.trim();
	var fvencimientor = document.getElementById("fvencimientor").value.trim();
	if (document.getElementById("flagpagodiferido").checked) var flagpagodiferido = "S"; else var flagpagodiferido = "N";
	if (document.getElementById("flagsuspension").checked) var flagsuspension = "S"; else var flagsuspension = "N";
	
	if (!valFecha(fvencimientor)) alert("¡ERROR: Formato de fecha incorrecta (Fecha de Vencimiento Real)!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDEN-PAGO&accion="+accion+"&organismo="+organismo+"&nroorden="+nroorden+"&tpago="+tpago+"&nompagara="+nompagara+"&ctabancaria="+ctabancaria+"&fvencimientor="+fvencimientor+"&flagpagodiferido="+flagpagodiferido+"&flagsuspension="+flagsuspension);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

function validarSeleccionOrden(accion) {
	var nrodetalles = 0;
	var detalles = "";
	frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "detalle" && n.checked) { detalles += n.value + ";"; nrodetalles++; }
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	document.getElementById("registro").value = detalles;
	
	if (detalles == "") alert("¡ERROR: Debe seleccionar por lo menos un registro!");
	else {
		if (accion == "ACTUALIZAR" && nrodetalles > 1) alert("¡ERROR: No puede modificar varias ordenes a la vez, por favor seleccione una orden!");
		else if (accion == "IMPRIMIR" && nrodetalles > 1) alert("¡ERROR: No puede imprimir varias ordenes a la vez, por favor seleccione una orden!");
		else if (accion == "ACTUALIZAR" && nrodetalles == 1) cargarOpcion(document.getElementById('frmfiltro'), 'ap_ordenes_pago_editar.php?opcion='+accion, 'SELF');
		else if (accion == "PREPAGO") cargarOpcion(document.getElementById('frmfiltro'), 'ap_ordenes_pago_prepago.php?accion='+accion+'&detalles='+detalles, 'SELF');
		else if (accion == "IMPRIMIR" && nrodetalles == 1) window.open("ap_ordenes_pago_pdf.php?registro="+detalles, "wOrdenPago", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=750, left=200, top=200, resizable=no");
		else if (accion == "ANULAR") {
			if (nrodetalles > 1) alert("¡ERROR: No puede anular varias ordenes a la vez, por favor seleccione una orden!");
			else cargarOpcion(document.getElementById('frmfiltro'), 'ap_ordenes_pago_editar.php?opcion='+accion, 'SELF');
		}
	}
}

function generarPrepago() {
	var detalles = document.getElementById("detalles").value.trim();
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=ORDEN-PAGO&accion=PRE-PAGO&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp.trim() != "") alert(resp.trim());
			else document.getElementById('frmentrada').submit();
		}
	}
}

//	funcion para mostrar los detalles por invitacion
function verOrdenesPrepago(registro) {	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=verOrdenesPrepago&registro="+registro);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("trDetalle").innerHTML = resp;
		}
	}
}

//	imprimir/transferir
function verificarImprimirTransferir(form) {
	var nroproceso = document.getElementById("nroproceso").value;
	var Secuencia = document.getElementById("Secuencia").value;
	var codtipopago = document.getElementById("codtipopago").value;
	var nrocuenta = document.getElementById("nrocuenta").value;
	var codproveedor = document.getElementById("codproveedor").value;
	
	if (confirm("¿Está seguro de realizar esta acción?")) {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDEN-PAGO&accion=IMPRIMIR-TRANSFERIR&nroproceso="+nroproceso+"&codtipopago="+codtipopago+"&nrocuenta="+nrocuenta+"&codproveedor="+codproveedor+"&Secuencia="+Secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					var registro = nroproceso + "." + Secuencia;
					generar_vouchers_abrir(registro, 'ap_generar_vouchers_pagos_voucher', 'imprimir');
					//window.open("ap_pagos_imprimir_transferir_voucher.php?nroproceso="+nroproceso+"&codtipopago="+codtipopago+"&nrocuenta="+nrocuenta+"&codproveedor="+codproveedor, "ap_pagos_imprimir_transferir_voucher", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes");
					form.submit();
				}
			}
		}
	}
	return false;
}

//	ABRIR GENERAR VOUCHERS
function generar_vouchers_abrir(registro, pagina, imprimir) {
	window.open("gehen.php?anz="+pagina+"&registro="+registro+"&imprimir="+imprimir, pagina, "toolbar=no, menubar=no, location=no, scrollbars=yes, width=1000, height=600");
}
//	--------------------------------------

function generarVoucherPago(form) {
	var nroproceso = document.getElementById("nroproceso").value.trim();
	var codtipopago = document.getElementById("codtipopago").value.trim();
	var nrocuenta = document.getElementById("nrocuenta").value.trim();	
	var organismo = document.getElementById("organismo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var fecha = document.getElementById("fecha").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var codvoucher = document.getElementById("codvoucher").value.trim();
	var nrovoucher = document.getElementById("nrovoucher").value.trim();
	var codaprobado = document.getElementById("codaprobado").value.trim();
	var codsistemafuente = document.getElementById("codsistemafuente").value.trim();
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var nroorden = document.getElementById("nroorden").value.trim();
	var codproveedor = document.getElementById("codproveedor").value;
	var libro_contable = document.getElementById("libro_contable").value;
	var CodDependencia = document.getElementById("CodDependencia").value;
	
	// distribucion
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "_codcuenta") detalles += n.value + "|";
		if (n.name == "_monto") detalles += n.value + "|";
		if (n.name == "_ccosto") detalles += n.value + "|";
		if (n.name == "_comentarios") detalles += n.value + "|";
		if (n.name == "_tsaldo") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (!valFecha(fecha)) alert("¡ERROR: Formato de fecha incorrecta!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDEN-PAGO&accion=VOUCHER&nroproceso="+nroproceso+"&codtipopago="+codtipopago+"&nrocuenta="+nrocuenta+"&detalles="+detalles+"&organismo="+organismo+"&descripcion="+descripcion+"&fecha="+fecha+"&codingresado="+codingresado+"&codvoucher="+codvoucher+"&nrovoucher="+nrovoucher+"&codaprobado="+codaprobado+"&codsistemafuente="+codsistemafuente+"&codproveedor="+codproveedor+"&nroorden="+nroorden+"&codproveedor="+codproveedor+"&libro_contable="+libro_contable+"&CodDependencia="+CodDependencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else {
					window.open("ap_pagos_imprimir_transferir_reportes.php?nroproceso="+nroproceso+"&codtipopago="+codtipopago+"&nrocuenta="+nrocuenta+"&codproveedor="+codproveedor, "ap_pagos_imprimir_transferir_voucher_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=50, top=50, resizable=yes");
					window.close();
				}
			}
		}
	}
	return false;
}

function tab_reporte(tab) {
	iReporte.location.href = tab;
}

//	TRANSACCIONES BANCARIAS
function verificarTransaccionesBancarias(form, accion) {
	var secuencia = document.getElementById("secuencia").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	var nrotransaccion = document.getElementById("nrotransaccion").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var codpreparado = document.getElementById("codpreparado").value.trim();
	var fpreparado = document.getElementById("fpreparado").value.trim();
	if (document.getElementById("FlagPresupuesto").checked) var FlagPresupuesto = "S"; else var FlagPresupuesto = "N";
	
	var error_detalles = "";
	var detalles = "";
	frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "nrosecuencia") detalles += n.value + "|";
		else if (n.name == "codtransaccion") {
			if (n.value.trim() == "") error_detalles = "¡ERROR: Debe seleccionar un tipo de transaccion para todos los detalles!";
			else detalles += n.value + "|";
		}
		else if (n.name == "tipotransaccion") detalles += n.value + "|";
		else if (n.name == "nrocuenta") {
			if (n.value.trim() == "") error_detalles = "¡ERROR: Debe seleccionar una cuenta bancaria para todos los detalles!";
			else detalles += n.value + "|";
		}
		else if (n.name == "monto") {
			var monto = new Number(setNumero(n.value));
			if (monto == 0 || isNaN(monto)) error_detalles = "¡ERROR: Debe ingresar un monto válido para la transacción bancaria!";
			else detalles += monto + "|";
		}
		else if (n.name == "tipodocumento") {
			if (n.value.trim() == "") error_detalles = "¡ERROR: Debe seleccionar un tipo de documento para todos los detalles!";
			else detalles += n.value + "|";
		}
		else if (n.name == "referenciabanco") detalles += n.value + "|";
		else if (n.name == "persona") detalles += n.value + "|";
		else if (n.name == "ccosto") detalles += n.value + "|";
		else if (n.name == "cod_partida") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	valido losc ampos obligatorios
	if (!valFecha(ftransaccion)) alert("¡ERROR: Formato de fecha incorrecta (Fecha de Transacción)!");
	else if (detalles == "") alert("¡ERROR: Debe ingresar por lo menos una transacción!");
	else if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TRANSACCION-BANCARIA&accion="+accion+"&organismo="+organismo+"&periodo="+periodo+"&nrotransaccion="+nrotransaccion+"&estado="+estado+"&ftransaccion="+ftransaccion+"&comentarios="+comentarios+"&codpreparado="+codpreparado+"&fpreparado="+fpreparado+"&detalles="+detalles+"&FlagPresupuesto="+FlagPresupuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else {
					if (accion == "ACTUALIZAR") {
						var registro = nrotransaccion + "|" + secuencia + "|" + estado;
						window.open("ap_transacciones_bancarias_pdf.php?registro="+registro, "", "toolbar=no, menubar=no, location=no, scrollbars=yes");
					}
					form.submit();
				}
			}
		}
	}
	return false;
}

function validarFiltroSaldoBancos() {
	var fecha = document.getElementById("fhasta").value.trim();
	if (!valFecha(fecha) || fecha == "") { alert("¡ERROR: Formato de fecha incorrecta!"); return false; }
	else return true;
}

function validarFiltroSaldoBancosVer() {
	var desde = document.getElementById("ffechad").value.trim();
	var hasta = document.getElementById("ffechah").value.trim();
		
	if (desde != "" && !valFecha(desde)) { alert("¡ERROR: Criterio de fechas incorrecta!"); return false; }
	else if (hasta != "" && !valFecha(hasta)) { alert("¡ERROR: Criterio de fechas incorrecta!"); return false; }
	else if (desde != "" && hasta != "" && formatFechaAMD(desde) > formatFechaAMD(hasta)) { alert("¡ERROR: Criterio de fechas incorrecta!"); return false; }
	else return true;
}

function verSaldoBancos(form, id) {
	var partes = id.split("|");
	window.open("ap_saldo_bancos_ver.php?filtrar=DEFAULT&registro="+partes[2], "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=950, left=100, top=0, resizable=no");
}

//	GRUPO DE CONCEPTO DE GASTOS
function verificarGrupoConceptoGasto(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	//	valido los campos obligatorios
	if (descripcion == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GRUPO-CONCEPTO-GASTO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	AUTORIZACION DE CAJA CHICA
function verificarAutorizacionCajaChica(form, accion) {
	var codempleado = document.getElementById("codempleado").value.trim();
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var monto = new Number(setNumero(document.getElementById("monto").value.trim()));
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	//	valido los campos obligatorios
	if (codempleado == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else if (isNaN(monto)) alert("¡ERROR: El monto es incorrecto!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=AUTORIZACION-CAJA-CHICA&accion="+accion+"&codempleado="+codempleado+"&codorganismo="+codorganismo+"&monto="+monto+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

function limpiar_empleado() {
	document.getElementById("codpersona").value = "";
	document.getElementById("codempleado").value = "";
	document.getElementById("nomempleado").value = "";
}

//	CLASIFICACION DE GASTOS
function verificarClasificacionGasto(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var aplicacion = document.getElementById("aplicacion").value.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	// clasificaciones
	var detalles = "";
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "concepto") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	valido los campos obligatorios
	if (codigo == "" || descripcion == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACION-GASTO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&aplicacion="+aplicacion+"&estado="+estado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//	
function insertarConceptoGasto() {
	nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
	cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles++;
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarConceptoGasto&nrodetalles="+nrodetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("nrodetalles").value = nrodetalles;
			document.getElementById("cantdetalles").value = cantdetalles;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = nrodetalles;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById(nrodetalles).innerHTML = resp;
		}
	}
}
//	
function quitarConceptoGasto(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById("seldetalle").value = "";
	}
}

//	CONCEPTO DE GASTOS
function verificarConceptoGasto(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var grupo = document.getElementById("grupo").value.trim();
	var codpartida = document.getElementById("codpartida").value.trim();
	var codcuenta = document.getElementById("codcuenta").value.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	// clasificaciones
	var detalles = "";
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "clasificacion") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	valido los campos obligatorios
	if (descripcion == "" || grupo == "" || codpartida == "" || codcuenta == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CONCEPTO-GASTO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&grupo="+grupo+"&codpartida="+codpartida+"&codcuenta="+codcuenta+"&estado="+estado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}
//	
function insertarClasificacionGasto() {
	nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
	cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles++;
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarClasificacionGasto&nrodetalles="+nrodetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("nrodetalles").value = nrodetalles;
			document.getElementById("cantdetalles").value = cantdetalles;
			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = nrodetalles;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById(nrodetalles).innerHTML = resp;
		}
	}
}
//	
function quitarClasificacionGasto(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById("seldetalle").value = "";
	}
}

//	CAJA CHICA
function verificarCajaChica(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	var flagcajachica = document.getElementById("flagcajachica").value.trim();
	var codbeneficiario = document.getElementById("codbeneficiario").value.trim();
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var codpagara = document.getElementById("codpagara").value.trim();
	var nompagara = document.getElementById("nompagara").value.trim();
	var coddependencia = document.getElementById("coddependencia").value.trim();
	var codclasificacion = document.getElementById("codclasificacion").value.trim();
	var obligaciontipodoc = document.getElementById("obligaciontipodoc").value.trim();
	var obligacionnrodoc = document.getElementById("obligacionnrodoc").value.trim();
	var codtipopago = document.getElementById("codtipopago").value.trim();
	var nrodocinterno = document.getElementById("nrodocinterno").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var monto_reembolsar = setNumero(document.getElementById("monto_reembolsar").value.trim());
	var monto_autorizado = setNumero(document.getElementById("monto_autorizado").value.trim());
	var codpreparadopor = document.getElementById("codpreparadopor").value.trim();
	var fpreparadopor = document.getElementById("fpreparadopor").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var codaprobadopor = document.getElementById("codaprobadopor").value.trim();
	var faprobadopor = document.getElementById("faprobadopor").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	
	// detalles
	var detalles = "";
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "fdocumento") detalles += n.value + "*";
		else if (n.name == "concepto") detalles += n.value + "*";
		else if (n.name == "descripcion") detalles += n.value + "*";
		else if (n.name == "pagado") detalles += setNumero(n.value) + "*";
		else if (n.name == "tipoimpuesto") detalles += n.value + "*";
		else if (n.name == "tiposervicio") detalles += n.value + "*";
		else if (n.name == "afecto") detalles += setNumero(n.value) + "*";
		else if (n.name == "noafecto") detalles += setNumero(n.value) + "*";
		else if (n.name == "impuesto") detalles += setNumero(n.value) + "*";
		else if (n.name == "retencion") detalles += setNumero(n.value) + "*";
		else if (n.name == "rif") detalles += n.value + "*";
		else if (n.name == "tipodocumento") detalles += n.value + "*";
		else if (n.name == "nrodocumento") detalles += n.value + "*";
		else if (n.name == "nrofactura") detalles += n.value + "*";
		else if (n.name == "codpersona") detalles += n.value + "*";
		else if (n.name == "nompersona") detalles += n.value + ";.;"; //"*";
		//else if (n.name == "distribucion") detalles += n.value + ";.;";
	//alert(detalles);
	}
	var len = detalles.length; len-=3;
	detalles = detalles.substr(0, len);
	

//return;

	//	valido los campos obligatorios
	if (codccosto == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else if (detalles == "") alert("¡ERROR: Debe ingresar los detalles de la reposición de la caja chica!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CAJA-CHICA&accion="+accion+"&codigo="+codigo+"&periodo="+periodo+"&flagcajachica="+flagcajachica+"&codbeneficiario="+codbeneficiario+"&codorganismo="+codorganismo+"&codpagara="+codpagara+"&nompagara="+nompagara+"&coddependencia="+coddependencia+"&codclasificacion="+codclasificacion+"&obligaciontipodoc="+obligaciontipodoc+"&obligacionnrodoc="+obligacionnrodoc+"&codtipopago="+codtipopago+"&nrodocinterno="+nrodocinterno+"&estado="+estado+"&monto_reembolsar="+monto_reembolsar+"&monto_autorizado="+monto_autorizado+"&codpreparadopor="+codpreparadopor+"&fpreparadopor="+fpreparadopor+"&codccosto="+codccosto+"&codaprobadopor="+codaprobadopor+"&faprobadopor="+faprobadopor+"&descripcion="+descripcion+"&motivo="+motivo+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;	
}
//	
function insertarCajaChicaDetalle(boton) {
	var ccosto = document.getElementById("codccosto").value;
	if (ccosto == "") alert("¡ERROR: Debe seleccionar el centro de costo por defecto!");
	else {
		boton.disabled = true;
		nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
		candetalles = new Number(document.getElementById("candetalles").value); candetalles++;
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarCajaChicaDetalle&nrodetalles="+nrodetalles+"&candetalles="+candetalles+"&ccosto="+ccosto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var partes = resp.split("||");			
				document.getElementById("nrodetalles").value = nrodetalles;
				document.getElementById("candetalles").value = candetalles;
				//	creo linea en el detalle			
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
				newTr.id = "det_" + nrodetalles;
				document.getElementById("listaDetalles").appendChild(newTr);
				document.getElementById("det_"+nrodetalles).innerHTML = partes[0];
				boton.disabled = false;
			}
		}
	}
}
//	
function quitarCajaChicaDetalle(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById("seldetalle").value = "";
		quitarCajaChicaDetalleDistribucion(seldetalle);
	}
}
//
function quitarCajaChicaDetalleDistribucion(seldetalle) {
	var partes = seldetalle.split("_");
	var tblDistribucion = document.getElementById("tblDistribucion");
	var tbody = document.getElementById("listaDistribucion_"+partes[1]);
	tblDistribucion.removeChild(tbody);
}
//
function setTipoServicioCajaChica(candetalles, tipoimpuesto) {
	var idtservicio = "tiposervicio_" + candetalles;
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setTipoServicioCajaChica&tipoimpuesto="+tipoimpuesto+"&candetalles="+candetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById(idtservicio).parentNode.innerHTML = resp;
		}
	}
}
//
function setMontosCajaChica(nro, campo) {	
	var pagado = new Number(setNumero(document.getElementById("pagado_"+nro).value));
	var afecto = new Number(setNumero(document.getElementById("afecto_"+nro).value));
	var noafecto = new Number(setNumero(document.getElementById("noafecto_"+nro).value));
	var impuesto = new Number(setNumero(document.getElementById("impuesto_"+nro).value));
	var retencion = new Number(setNumero(document.getElementById("retencion_"+nro).value));
	var tipoimpuesto = document.getElementById("tipoimpuesto_"+nro).value;
	var tiposervicio = document.getElementById("tiposervicio_"+nro).value;
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setMontosCajaChica&pagado="+pagado+"&afecto="+afecto+"&noafecto="+noafecto+"&impuesto="+impuesto+"&retencion="+retencion+"&tipoimpuesto="+tipoimpuesto+"&tiposervicio="+tiposervicio+"&campo="+campo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("|");
			document.getElementById("pagado_"+nro).value = datos[0];
			document.getElementById("afecto_"+nro).value = datos[1];
			document.getElementById("noafecto_"+nro).value = datos[2];
			document.getElementById("impuesto_"+nro).value = datos[3];
			document.getElementById("retencion_"+nro).value = datos[4];
		}
	}
}
//
function setMontosCajaChicaIva(nro) {	
	var pagado = new Number(setNumero(document.getElementById("pagado_"+nro).value));
	var afecto = new Number(setNumero(document.getElementById("afecto_"+nro).value));
	var noafecto = new Number(setNumero(document.getElementById("noafecto_"+nro).value));
	var impuesto = new Number(setNumero(document.getElementById("impuesto_"+nro).value));
	var retencion = new Number(setNumero(document.getElementById("retencion_"+nro).value));
	var tipoimpuesto = document.getElementById("tipoimpuesto_"+nro).value;
	var tiposervicio = document.getElementById("tiposervicio_"+nro).value;
	var pagado = afecto + noafecto + impuesto;
	document.getElementById("pagado_"+nro).value = setNumeroFormato(pagado, 2, ".", ",");
}
//
function limpiarMontosCajaChicaDetalle(nro) {
	document.getElementById("pagado_"+nro).value = "0,00";
	document.getElementById("afecto_"+nro).value = "0,00";
	document.getElementById("noafecto_"+nro).value = "0,00";
	document.getElementById("impuesto_"+nro).value = "0,00";
	document.getElementById("retencion_"+nro).value = "0,00";
	var tipoimpuesto = document.getElementById("tipoimpuesto_"+nro).value;
	if (tipoimpuesto == "I") {
		document.getElementById("pagado_"+nro).disabled = false;
		document.getElementById("afecto_"+nro).disabled = false;
		document.getElementById("noafecto_"+nro).disabled = false;
	}
	else if (tipoimpuesto == "N") {
		document.getElementById("pagado_"+nro).disabled = false;
		document.getElementById("afecto_"+nro).disabled = true;
		document.getElementById("noafecto_"+nro).disabled = true;
	}
	else if (tipoimpuesto == "R") {
		document.getElementById("pagado_"+nro).disabled = true;
		document.getElementById("afecto_"+nro).disabled = false;
		document.getElementById("noafecto_"+nro).disabled = false;
	}
	else if (tipoimpuesto == "M") {
		document.getElementById("pagado_"+nro).disabled = true;
		document.getElementById("afecto_"+nro).disabled = false;
		document.getElementById("noafecto_"+nro).disabled = false;
	}
}
//
function agregarDistribucionCajaChica() {
	var sel = document.getElementById("seldetalle").value;
	if (sel == "") alert("¡ERROR: Debe seleccionar una linea!");
	else {
		var partes = sel.split("_");
		var nro = partes[1];
		var idconcepto = "concepto_" + nro;
		var iddistribucion = "distribucion_" + nro;
		var idafecto = "afecto_" + nro;
		var idnoafecto = "noafecto_" + nro;
		var concepto = document.getElementById(idconcepto).value;
		var distribucion = document.getElementById(iddistribucion).value;
		var afecto = new Number(setNumero(document.getElementById(idafecto).value));
		var noafecto = new Number(setNumero(document.getElementById(idnoafecto).value));
		var codigo = document.getElementById("codigo").value;
		var periodo = document.getElementById("periodo").value;
		var flagcajachica = document.getElementById("flagcajachica").value;
		var codccosto = document.getElementById("codccosto").value;
		//	valido
		if (concepto == "") alert("¡ERROR: Debe seleccionar primero un concepto!");
		else window.open('ap_caja_chica_distribucion.php?concepto='+concepto+'&codigo='+codigo+'&periodo='+periodo+'&flagcajachica='+flagcajachica+'&nro='+nro+'&distribucion='+distribucion+'&codccosto='+codccosto+'&afecto='+afecto+'&noafecto='+noafecto, 'wCajaChica', 'height=400, width=900, left=200, top=100, resizable=no');
	}
}
//	
function insertarDistribucionCajaChica(boton) {
	boton.disabled = true;
	var codccosto = document.getElementById("codccosto").value;
	nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
	candetalles = new Number(document.getElementById("candetalles").value); candetalles++;
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarDistribucionCajaChica&nrodetalles="+nrodetalles+"&candetalles="+candetalles+"&codccosto="+codccosto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("nrodetalles").value = nrodetalles;
			document.getElementById("candetalles").value = candetalles;			
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldistribucion');");
			newTr.id = "dis_" + nrodetalles;
			document.getElementById("listaDistribucion").appendChild(newTr);
			document.getElementById("dis_"+nrodetalles).innerHTML = resp;
			boton.disabled = false;
		}
	}
}
//	
function quitarDistribucionCajaChica(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDistribucion = document.getElementById("listaDistribucion"); 
		var tr = document.getElementById(seldetalle); 
		listaDistribucion.removeChild(tr);
		document.getElementById("seldistribucion").value = "";
		setTotalesDistribucionCajaChica();
	}
}
//
function aceptarDistribucionCajaChicaDetalle(nro) {
	var pordistribuir = new Number(setNumero(document.getElementById("pordistribuir").value));
	if (pordistribuir > 0) alert("¡ERROR: Debe distribuir todo el monto a pagar!");
	else if (pordistribuir < 0) alert("¡ERROR: El monto distribuido es mayor que el monto a pagar!");
	else {
		// detalles
		var detalles = "";
		var frmdetalles = document.getElementById("frmdistribucion");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "concepto") var concepto = n.value;
			else if (n.name == "codpartida") var codpartida = n.value;
			else if (n.name == "codcuenta") var codcuenta = n.value;
			else if (n.name == "ccosto") var ccosto = n.value;
			else if (n.name == "monto") {
				var monto = n.value;
				detalles += monto + "|" + concepto + "|" + codpartida + "|" + codcuenta + "|" + ccosto + ";";
			}
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		//	cambio distribucion de la linea seleccionada
		opener.document.getElementById("distribucion_"+nro).value = detalles;
		window.close();
	}
}
//
function setTotalesDistribucionCajaChica() {
	// detalles
	//var total_bruto = new Number(setNumero(document.getElementById("bruto").value));
	//var total_pordistribuir = new Number(setNumero(document.getElementById("bruto").value));
	var total_bruto = new Number(document.getElementById("bruto").value);
	var total_pordistribuir = new Number(document.getElementById("bruto").value);
	var detalles = "";
	var frmdetalles = document.getElementById("frmdistribucion");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "monto") {
			var monto = new Number(setNumero(n.value));
			total_pordistribuir -= monto;
		}
	}
	document.getElementById("pordistribuir").value = setNumeroFormato(total_pordistribuir, 2, ".", ",");
}

//	entregar cheque
function cheque(registro, boton, form, accion) {
	if (registro == "") alert("¡ERROR: Debe seleccionar un cheque!");
	else {
		if (confirm("¿Confirmar cambio?")) {
			var fcobranza = document.getElementById("fcobranza").value;
			var nrooperacion = document.getElementById("nrooperacion").value;
			boton.disabled = true;
			//	CREO UN OBJETO AJAX
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_ap.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CHEQUES&accion="+accion+"&registro="+registro+"&fcobranza="+fcobranza+"&nrooperacion="+nrooperacion);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					boton.disabled = false;
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp);
					else form.submit();
				}
			}
		}
	}
}

function enabledCargando(display) {
	document.getElementById("bloqueo").style.display = display;
	document.getElementById("cargando").style.display = display;
}

//	importar registro de compras
function importarRegistroCompra(form) {
	var organismo = document.getElementById("organismo").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	if (document.getElementById("flagcp").checked) var flagcp = "S"; else var flagcp = "N";
	if (document.getElementById("flagcf").checked) var flagcf = "S"; else var flagcf = "N";
	
	if (periodo == "") alert("¡ERROR: Debe ingresar el periodo a importar!");
	else if (!valPeriodo) alert("¡ERROR: Periodo contable incorrecto!");
	else if (flagcp == "N" && flagcf == "N") alert("¡ERROR: Debe seleccionar un sistema fuente para exportar los registros de compras!");
	else {
		enabledCargando("block");
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REGISTRO-COMPRAS&accion=IMPORTAR&organismo="+organismo+"&periodo="+periodo+"&flagcp="+flagcp+"&flagcf="+flagcf);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				enabledCargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					document.getElementById("nrocp").innerHTML = datos[1];
					document.getElementById("nrocf").innerHTML = datos[2];
				}
			}
		}
	}
	return false;
}

function getDescripcionLista(accion, campo, iddescripcion, campo2, iddescripcion2) {
	var codigo = campo.value;
	if (codigo.trim() != "") {
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_ap.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(accion+"&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					if (datos[1]) campo.value = datos[1]; 
					else campo.value = "";
					if (datos[2]) document.getElementById(iddescripcion).value = datos[2]; 
					else document.getElementById(iddescripcion).value = "";
					if (datos[3] && document.getElementById(campo2)) document.getElementById(campo2).value = datos[3];
					else document.getElementById(campo2).value = "";
					if (datos[4] && document.getElementById(iddescripcion2)) document.getElementById(iddescripcion2).value = datos[4]; 
					else document.getElementById(iddescripcion2).value = "";
				}
			}
		}
	} else {
		campo.value = "";
		document.getElementById(iddescripcion).value = "";
		if (document.getElementById(campo2)) document.getElementById(campo2).value = "";
		if (document.getElementById(iddescripcion2)) document.getElementById(iddescripcion2).value = "";
	}
}

function setPeriodoFecha(valor, campo) {
	var partes = valor.split("-");
	document.getElementById(campo).value = partes[2] + "-" + partes[1];
}
