// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

//------------------------------------------------------------//

//	Me permite redondear a un numero decimales especificos
Number.prototype.decimal = function (num) {
    pot = Math.pow(10,num);
    return parseInt(this * pot) / pot;
}

var MAXLIMIT=30;

function oNumero(numero) {
	//Propiedades
	this.valor = numero || 0
	this.dec = -1;
	
	//M�todos
	this.formato = numFormat;
	this.ponValor = ponValor;
	
	//Definici�n de los m�todos
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
	else campo.value = setNumeroFormato(numero, 2, "", ".");
}

//	funcion para formtear un campo numerico cuando deja el campo
function numeroFocus(campo) {
	var numero = new Number(setNumero(campo.value));
	if (numero == 0) campo.value = "";
	else { 
		valor = setNumeroFormato(numero, 2, "", ".");
		
		var x = new String(valor);
		var sep = x.split(".");
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
//------------------------------------------------------------//

//------------------------------------------------------------//
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
//------------------------------------------------------------//


//------------------------------------------------------------//
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistros(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	//	---------------------------------------------------
	if (insert == "N") {
		if (document.getElementById("btNuevo")) document.getElementById("btNuevo").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btEditar")) document.getElementById("btEditar").disabled = true;
		if (document.getElementById("btAprobar")) document.getElementById("btAprobar").disabled = true;
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
	if (!rows) {
		if (document.getElementById("btVer")) document.getElementById("btVer").disabled = true;
		if (document.getElementById("btPDF")) document.getElementById("btPDF").disabled = true;
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
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
//------------------------------------------------------------//

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_Periodo(idSelectOrigen, idSelectDestino, idChkDestino, codorganismo, opt) {
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
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&codorganismo="+codorganismo+"&opt="+opt);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}
//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_Proceso(idSelectOrigen, idSelectDestino, idChkDestino, nomina, codorganismo, opt) {
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
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&nomina="+nomina+"&codorganismo="+codorganismo+"&opt="+opt);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") alert("¡Debe seleccionar un registro!");
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentana(form, pagina, param) {
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA EJECUTAR OPCION DE MENU DE REGISTROS
function opcionRegistro(form, registro, modulo, accion) {
	if (registro == "") alert("¡Debe seleccionar un registro!");
	else if (accion == "ELIMINAR" && confirm("¡Esta seguro de eliminar este registro?")) {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina_2.php", true);
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
//------------------------------------------------------------//

//------------------------------------------------------------//
//------------------------------------------------------------//
//------------------------------------------------------------//

//	calcular obligaciones
function calcularObligaciones(boton) {
	var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	if (fperiodo == "" || ftproceso == "") alert("¡ERROR: Debe seleccionar el periodo y la nómina!");
	else {
		if (confirm("¿Esta seguro de calcular las obligaciones de nomina?")) {
			document.getElementById("bloqueo").style.display = "block";
			document.getElementById("cargando").style.display = "block";
			boton.disabled = true;	
			//	CREO UN OBJETO AJAX
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina_2.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=INTERFASE-BANCARIA&accion=calcularObligaciones&organismo="+forganismo+"&nomina="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					document.getElementById("bloqueo").style.display = "none";
					document.getElementById("cargando").style.display = "none";
					boton.disabled = false;
					
					var resp = ajax.responseText;
					if (resp != "") alert(resp);
					else location.href = "interfase_cuentas_por_pagar.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso;
					
				}
			}
		}
	}
}
//------------------------------------------------------------//

//	generar obligaciones
function generarObligaciones(boton) {
	if (confirm("¿Esta seguro de generar las obligaciones seleccionadas?\nTenga en cuenta que no podrá volver a calcularlas nuevamente")) {
		var forganismo = document.getElementById("forganismo").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		
		//	interfase bancaria
		detalles_bancos = "";
		frmbancos = document.getElementById("frmbancos");
		for(i=0; n=frmbancos.elements[i]; i++) {
			if (n.name = "bancos" && n.checked) detalles_bancos += n.value + ";";
		}
		var len = detalles_bancos.length; len--;
		detalles_bancos = detalles_bancos.substr(0, len);
		
		//	cheques
		detalles_cheques = "";
		frmcheques = document.getElementById("frmcheques");
		for(i=0; n=frmcheques.elements[i]; i++) {
			if (n.name = "cheques" && n.checked) detalles_cheques += n.value + ";";
		}
		var len = detalles_cheques.length; len--;
		detalles_cheques = detalles_cheques.substr(0, len);
		
		//	terceros
		detalles_terceros = "";
		frmterceros = document.getElementById("frmterceros");
		for(i=0; n=frmterceros.elements[i]; i++) {
			if (n.name = "terceros" && n.checked) detalles_terceros += n.value + ";";
		}
		var len = detalles_terceros.length; len--;
		
		//	judiciales
		detalles_judiciales = "";
		frmjudiciales = document.getElementById("frmjudiciales");
		for(i=0; n=frmjudiciales.elements[i]; i++) {
			if (n.name = "judiciales" && n.checked) detalles_judiciales += n.value + ";";
		}
		var len = detalles_judiciales.length; len--;
		detalles_judiciales = detalles_judiciales.substr(0, len);
		
		if (fperiodo == "" || ftproceso == "") alert("¡ERROR: Debe seleccionar el periodo y la nómina!");
		else if (detalles_bancos == "" && detalles_cheques == "" && detalles_terceros == "" && detalles_judiciales == "") alert("¡ERROR: Debe seleccionar por lo menos una obligación para generarla!");
		else {
			document.getElementById("bloqueo").style.display = "block";
			document.getElementById("cargando").style.display = "block";
			boton.disabled = true;	
			//	CREO UN OBJETO AJAX
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina_2.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=INTERFASE-BANCARIA&accion=generarObligaciones&organismo="+forganismo+"&nomina="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso+"&detalles_bancos="+detalles_bancos+"&detalles_cheques="+detalles_cheques+"&detalles_terceros="+detalles_terceros+"&detalles_judiciales="+detalles_judiciales);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					document.getElementById("bloqueo").style.display = "none";
					document.getElementById("cargando").style.display = "none";
					boton.disabled = false;
					
					var resp = ajax.responseText;
					if (resp != "") alert(resp);
					else location.href = "interfase_cuentas_por_pagar.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso;
				}
			}
		}
	}
}
//------------------------------------------------------------//

//	consolidar obligacion
function consolidarObligacion(boton) {
	if (confirm("¿Esta seguro de consolidar las obligaciones seleccionadas?")) {
		var forganismo = document.getElementById("forganismo").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		var tabs = new Number(0);
		
		//	interfase bancaria
		detalles_bancos = "";
		frmbancos = document.getElementById("frmbancos");
		for(i=0; n=frmbancos.elements[i]; i++) {
			if (n.name = "bancos" && n.checked) { detalles_bancos += n.value + ";"; }
		}
		var len = detalles_bancos.length; len--;
		detalles_bancos = detalles_bancos.substr(0, len);
		
		//	cheques
		detalles_cheques = "";
		frmcheques = document.getElementById("frmcheques");
		for(i=0; n=frmcheques.elements[i]; i++) {
			if (n.name = "cheques" && n.checked) { detalles_cheques += n.value + ";"; }
		}
		var len = detalles_cheques.length; len--;
		detalles_cheques = detalles_cheques.substr(0, len);
		
		//	terceros
		detalles_terceros = "";
		frmterceros = document.getElementById("frmterceros");
		for(i=0; n=frmterceros.elements[i]; i++) {
			if (n.name = "terceros" && n.checked) { detalles_terceros += n.value + ";"; }
		}
		var len = detalles_terceros.length; len--;
		
		//	judiciales
		detalles_judiciales = "";
		frmjudiciales = document.getElementById("frmjudiciales");
		for(i=0; n=frmjudiciales.elements[i]; i++) {
			if (n.name = "judiciales" && n.checked) { detalles_judiciales += n.value + ";"; }
		}
		var len = detalles_judiciales.length; len--;
		detalles_judiciales = detalles_judiciales.substr(0, len);
		
		if (detalles_bancos != "") tabs++;
		if (detalles_cheques != "") tabs++;
		if (detalles_terceros != "") tabs++;
		if (detalles_judiciales != "") tabs++;
		
		if (fperiodo == "" || ftproceso == "") alert("¡ERROR: Debe seleccionar el periodo y la nómina!");
		else if (detalles_bancos == "" && detalles_cheques == "" && detalles_terceros == "" && detalles_judiciales == "") alert("¡ERROR: Debe seleccionar por lo menos una obligación para generarla!");
		else if (tabs > 1) alert("¡ERROR: No puede consolidar obligaciones pendientes de diferentes pestañas!");
		else {
			document.getElementById("bloqueo").style.display = "block";
			document.getElementById("cargando").style.display = "block";
			boton.disabled = true;	
			//	CREO UN OBJETO AJAX
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina_2.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=INTERFASE-BANCARIA&accion=consolidarObligacion&organismo="+forganismo+"&nomina="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso+"&detalles_bancos="+detalles_bancos+"&detalles_cheques="+detalles_cheques+"&detalles_terceros="+detalles_terceros+"&detalles_judiciales="+detalles_judiciales);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					document.getElementById("bloqueo").style.display = "none";
					document.getElementById("cargando").style.display = "none";
					boton.disabled = false;
					
					var resp = ajax.responseText;
					if (resp != "") alert(resp);
					else location.href = "interfase_cuentas_por_pagar.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso;
				}
			}
		}
	}
}
//------------------------------------------------------------//

//	selecciono todos las obligaciones calculados
function selTodosObligaciones() {
	frmbancos = document.getElementById("frmbancos");
	for(i=0; n=frmbancos.elements[i]; i++) {
		if (n.name = "bancos") {
			idrow = "row_bancos" + n.value;
			mClkMulti(document.getElementById(idrow));
		}
	}
	
	frmcheques = document.getElementById("frmcheques");
	for(i=0; n=frmcheques.elements[i]; i++) {
		if (n.name = "cheques") {
			idrow = "row_cheques" + n.value;
			mClkMulti(document.getElementById(idrow));
		}
	}
	
	frmterceros = document.getElementById("frmterceros");
	for(i=0; n=frmterceros.elements[i]; i++) {
		if (n.name = "terceros") {
			idrow = "row_terceros" + n.value;
			mClkMulti(document.getElementById(idrow));
		}
	}
	
	frmjudiciales = document.getElementById("frmjudiciales");
	for(i=0; n=frmjudiciales.elements[i]; i++) {
		if (n.name = "judiciales") {
			idrow = "row_judiciales" + n.value;
			mClkMulti(document.getElementById(idrow));
		}
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	AJUSTE SALARIAL
function verificarAjusteSalarial(form, accion) {
	document.getElementById("bloqueo").style.display = "block";
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var nroresolucion = document.getElementById("nroresolucion").value.trim();
	var nrogaceta = document.getElementById("nrogaceta").value.trim();
	var preparadopor = document.getElementById("preparadopor").value.trim();
	var fpreparacion = document.getElementById("fpreparacion").value.trim();
	var aprobadopor = document.getElementById("aprobadopor").value.trim();
	var faprobacion = document.getElementById("faprobacion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	
	var error_detalles = "";
	var detalles = "";
	var frmdetalles = document.getElementById("frmdetalles");	
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "grados" && n.checked) {
			var check = true;
			detalles += n.id + "|";
		}
		else if (n.name == "porcentaje" && check) {
			var p = new Number(setNumero(n.value));
			if (isNaN(p)) { error_detalles = "¡ERROR: El porcentaje debe ser un valor numérico!"; break; }
			else if (p < 0) { error_detalles = "¡ERROR: El porcentaje no puede ser negativo!"; break; }
			detalles += p + "|";
		}
		else if (n.name == "monto" && check) {
			var m = n.value;
			if (isNaN(m)) { error_detalles = "¡ERROR: El monto debe ser un valor numérico!"; break; }
			else if (m < 0) { error_detalles = "¡ERROR: El monto no puede ser negativo!"; break; }
			else if (p == 0 && m == 0) { error_detalles = "¡ERROR: Debe ingresar el porcentaje o un monto fijo para el aumento!"; break; }
			detalles += m + "|";
		}
		else if (n.name == "nuevo" && check) {
			var s = new Number(setNumero(n.value));
			detalles += s + ";";
			check = false;
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (descripcion == "" || nroresolucion == "") {
		document.getElementById("bloqueo").style.display = "none";		
		alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	}
	else if (detalles == "") {
		document.getElementById("bloqueo").style.display = "none";		
		alert("¡ERROR: No se ingreso ningún aumento!");
	}
	else if (error_detalles != "") {
		document.getElementById("bloqueo").style.display = "none";		
		alert(error_detalles);
	}
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=AJUSTE-SALARIAL&accion="+accion+"&codorganismo="+codorganismo+"&periodo="+periodo+"&secuencia="+secuencia+"&estado="+estado+"&descripcion="+descripcion+"&nroresolucion="+nroresolucion+"&nrogaceta="+nrogaceta+"&preparadopor="+preparadopor+"&fpreparacion="+fpreparacion+"&aprobadopor="+aprobadopor+"&faprobacion="+faprobacion+"&motivo="+motivo+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				document.getElementById("bloqueo").style.display = "none";				
				var resp = ajax.responseText;
				if (resp != "") alert(resp);
				else form.submit();
			}
		}
	}
	return false;
}
function enabledAjusteSalarial(idgrado, boo) {
	document.getElementById("P_"+idgrado).disabled = !boo;
	document.getElementById("P_"+idgrado).value = "";
	document.getElementById("M_"+idgrado).disabled = !boo;
	document.getElementById("M_"+idgrado).value = "";
}

function setAjusteSalarial(tipo, grado, actual) {
	
	if (tipo == "P") {
		var valor = new Number(setNumero(document.getElementById("P_"+grado).value));
		var nuevo = (actual * valor / 100) + actual;
		document.getElementById("M_"+grado).value = "";
	} else {
		//var valor = setNumeroFormato(document.getElementById("M_"+grado).value,2,",","."); 
		
		var valor = parseFloat(document.getElementById("M_"+grado).value);
		var nuevo = actual + valor;
		document.getElementById("P_"+grado).value = "";
	}
	
	if (nuevo == 0 || isNaN(nuevo)) 	
		document.getElementById("N_"+grado).value = "";
	else	
		document.getElementById("N_"+grado).value = setNumeroFormato(nuevo, 2, ".", ",");
}
//------------------------------------------------------------//
