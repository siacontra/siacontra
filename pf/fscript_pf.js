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
	var bOk = false;
	var nDia = parseInt(fecha.substr(0, 2), 10);
	bOk = bOk || ((nDia >= 1) && (nDia <= finMes(fecha)));
	return bOk;
}
//	---------------------------------------

// Valido mes...
function valMes(fecha){
	var bOk = false;
	var nMes = fecha.substr(3, 2);
	var nMesInt = parseInt(nMes, 10);
	bOk = bOk || ((nMesInt >= 1) && (nMesInt <= 12) && (nMes.length == 2));
	return bOk;
}
//	---------------------------------------

// Valido a�o...
function valAno(fecha){
	var bOk = true;
	var nAno = fecha.substr(6);
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
	if (fecha != "") {
		bOk = bOk && (valAno(fecha));
		bOk = bOk && (valMes(fecha));
		bOk = bOk && (valDia(fecha));
		bOk = bOk && (valSep(fecha));
		if (!bOk) return false; else return true;
	}
}

function formatFechaDMA(fecha) {
	var nAno = fecha.substr(0, 4);
	var nMes = fecha.substr(5, 2);
	var nDia = fecha.substr(8);	
	return nDia+"-"+nMes+"-"+nAno;
}

//
function formatFechaAMD(fecha) {
	var nDia = fecha.substr(0, 2);
	var nMes = fecha.substr(3, 2);
	var nAno = fecha.substr(6);	
	return nAno+"-"+nMes+"-"+nDia;
}
//	---------------------------------------

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

//	FUNCION PARA EJECUTAR OPCION DE MENU DE REGISTROS
function opcionRegistro(form, registro, modulo, accion) {
	if (registro == "") alert("¡Debe seleccionar un registro!");
	else if (accion == "ELIMINAR" && confirm("¡Esta seguro de eliminar este registro?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
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
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_pf.php", true);
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
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btEliminar=document.getElementById("btEliminar");
	var btPDF=document.getElementById("btPDF");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) btEditar.disabled=true;
	if (del=="N" || !rows) btEliminar.disabled=true;
	if (!rows) {
		btVer.disabled=true;
		btPDF.disabled=true;
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

//	FUNCION PARA BLOQUEAR/DESBLOQUEAR INPUTS DEL FILTRO
function chkFiltro(boo, id) {
	document.getElementById(id).value = "";
	document.getElementById(id).disabled = !boo;
}
function chkFiltroLista(boo, id, boton) {
	document.getElementById(id).value = "";
	document.getElementById(boton).disabled = !boo;
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
function enabledEnte(form) {
	if (form.chkente.checked) form.btEnte.disabled=false;
	else { form.btEnte.disabled=true; form.fente.value=""; form.fnomente.value=""; }
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
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
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
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
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

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (3 SELECTS)
function getOptions_3(idSelectOrigen, idSelectDestino, idSelect3) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	nuevaOpcion=document.createElement("option");	nuevaOpcion.value="";	nuevaOpcion.innerHTML="";
	var select3=document.getElementById(idSelect3);	//---------
	select3.length=0;	//---------
	select3.appendChild(nuevaOpcion);	//---------
	select3.disabled=true;	//---------
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;		
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_3&tabla="+idSelectDestino+"&opcion="+optSelectOrigen);
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

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (4 SELECTS)
function getOptions_4(idSelectOrigen, idSelectDestino, idSelect3, idSelect4) {
	var selectOrigen=document.getElementById(idSelectOrigen);
	var optSelectOrigen=selectOrigen.options[selectOrigen.selectedIndex].value;
	var selectDestino=document.getElementById(idSelectDestino);
	nuevaOpcion=document.createElement("option");	nuevaOpcion.value="";	nuevaOpcion.innerHTML="";
	var select3=document.getElementById(idSelect3);	//---------
	select3.length=0;	//---------
	select3.appendChild(nuevaOpcion);	//---------
	select3.disabled=true;	//---------
	var select4=document.getElementById(idSelect4);	//---------
	select4.length=0;	//---------
	select4.appendChild(nuevaOpcion);	//---------
	select4.disabled=true;	//---------
	if (optSelectOrigen=="") {
		selectDestino.length=0;
		selectDestino.appendChild(nuevaOpcion);
		selectDestino.disabled=true;		
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_4&tabla="+idSelectDestino+"&opcion="+optSelectOrigen);
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

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

//	MUESTRA EL LOGO
function setLogo() {	
	var logo=document.getElementById("logo").value; logo=logo.trim();
	var path=document.getElementById("path").value;
	if (logo=="") var src=path+"blank.png"; else var src=path+logo;
	document.getElementById("img_logo").src=src;
}

//	FUNCION PARA OBTENER LOS DIAS HABILES ENTRES DOS FECHAS
function getDiasHabiles(desde, hasta, campo){
	if (formatFechaAMD(desde) > formatFechaAMD(hasta)) {
		campo.value = 0;
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/funciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getDiasHabiles&desde="+desde+"&hasta="+hasta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				campo.value = ajax.responseText;
			}
		}
	}
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosActuacion(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btNuevo = document.getElementById("btNuevo");
	var btEditar = document.getElementById("btEditar");
	var btVer = document.getElementById("btVer");
	var btAprobar = document.getElementById("btAprobar");
	var btAnular = document.getElementById("btAnular");
	var btRevisar = document.getElementById("btRevisar");
	
	if (insert == "N") btNuevo.disabled = true;
	if (update == "N" || !rows) {
		btEditar.disabled = true;
		btAprobar.disabled = true;
		btAnular.disabled = true;
		btRevisar.disabled = true;
		if (!rows) btVer.disabled = true;
	}
}
function totalRegistrosActuacionEjecucion(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btTerminar = document.getElementById("btTerminar");
	
	if (update == "N" || !rows) {
		btTerminar.disabled = true;
	}
}
function totalRegistrosActuacionListar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btGenerar = document.getElementById("btGenerar");
	
	if (update == "N" || !rows) {
		btGenerar.disabled = true;
	}
}

//	--------------------------------------------------------------------------------
//	--------------------------------------------------------------------------------
//	--------------------------------------------------------------------------------

//	ORGANISMO EXTERNOS
function verificarOrganismoExterno(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var codpersona = new String (form.codpersona.value); codpersona=codpersona.trim(); form.codpersona.value=codpersona;
	var descripcionc = new String (form.descripcionc.value); descripcionc=descripcionc.trim(); form.descripcionc.value=descripcionc;
	var rep = new String (form.rep.value); rep=rep.trim(); form.rep.value=rep;
	var docr = new String (form.docr.value); docr=docr.trim(); form.docr.value=docr;
	var cargo = new String (form.cargo.value); cargo=cargo.trim(); form.cargo.value=cargo;
	var www = new String (form.www.value); www=www.trim(); form.www.value=www;
	var docf = new String (form.docf.value); docf=docf.trim(); form.docf.value=docf;
	var fecha = new String (form.fecha.value); fecha=fecha.trim(); form.fecha.value=fecha;
	var dir = new String (form.dir.value); dir=dir.trim(); form.dir.value=dir;
	var tel1 = new String (form.tel1.value); tel1=tel1.trim(); form.tel1.value=tel1;
	var tel2 = new String (form.tel2.value); tel2=tel2.trim(); form.tel2.value=tel2;
	var tel3 = new String (form.tel3.value); tel3=tel3.trim(); form.tel3.value=tel3;
	var fax1 = new String (form.fax1.value); fax1=fax1.trim(); form.fax1.value=fax1;
	var fax2 = new String (form.fax2.value); fax2=fax2.trim(); form.fax2.value=fax2;
	var logo = new String (form.logo.value); logo=logo.trim(); form.logo.value=logo;
	var nreg = new String (form.nreg.value); nreg=nreg.trim(); form.nreg.value=nreg;
	var treg = new String (form.treg.value); treg=treg.trim(); form.treg.value=treg;
	if (form.control[0].checked) var control=form.control[0].value; else var control=form.control[1].value;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || descripcionc == "" || form.ciudad.value == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORGANISMOS-EXTERNOS&accion="+accion+"&codigo="+form.codigo.value+"&codpersona="+codpersona+"&descripcion="+descripcion+"&descripcionc="+descripcionc+"&rep="+rep+"&docr="+docr+"&www="+www+"&docf="+docf+"&fecha="+fecha+"&dir="+dir+"&ciudad="+form.ciudad.value+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&fax1="+fax1+"&fax2="+fax2+"&logo="+logo+"&status="+status+"&nreg="+nreg+"&treg="+treg+"&control="+control+"&cargo="+cargo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert (resp);
				else form.submit();
			}
		}
	}
	return false;
}

//	DEPENDENCIAS EXTERNAS
function verificarDependenciaExterna(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var direccion = document.getElementById("direccion").value.trim();
	var representante = document.getElementById("representante").value.trim();
	var cargo = document.getElementById("cargo").value.trim();
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var tel1 = document.getElementById("tel1").value.trim();
	var tel2 = document.getElementById("tel2").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || codorganismo == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DEPENDENCIAS-EXTERNAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&codorganismo="+codorganismo+"&representante="+representante+"&tel1="+tel1+"&tel2="+tel2+"&status="+status+"&cargo="+cargo+"&direccion="+direccion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else form.submit();
			}
		}
	}
	return false;
}

//	PROCESOS
function verificarProcesos(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCESOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
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

//	FASES
function verificarFases(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || proceso == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FASES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&proceso="+proceso+"&status="+status);
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

//	ACTIVIDADES
function verificarActividades(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var fase = document.getElementById("fase").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	if (document.getElementById("flagauto").checked) var flagauto = "S"; else var flagauto = "N";
	if (document.getElementById("flagnoafecto").checked) var flagnoafecto = "S"; else var flagnoafecto = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "" || fase == "") alert("¡Debe ingresar los campos obligatorios!");
	else if (duracion == ""  || duracion == "0") alert("¡La duración es obligatoria!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ACTIVIDADES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&proceso="+proceso+"&fase="+fase+"&duracion="+duracion+"&flagauto="+flagauto+"&flagnoafecto="+flagnoafecto+"&status="+status);
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

//	TIPO DE ACTUACION FISCAL
function verificarTipoActuacionFiscal(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion == "") alert("¡Debe ingresar los campos obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPO-ACTUACION-FISCAL&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
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

//	ACTUACIONES FISCALES
function verificarPlanificacion(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los auditores!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANIFICACION&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_actuacion_fiscal.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	abrir lista
function abrirListaEmpleadosPlanificacion(frmdetalles) {
	var detalles = "";
	var error_detalles = "";
	
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "persona") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	window.open('listado_personas.php?ventana=insertarListaEmpleadoActuacion&filtrar=DEFAULT&limit=0&flagempleado=S&detalles='+detalles, 'listaEmpleados', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=200, top=200, resizable=yes');
}
//	quitar de la lista
function quitarListaEmpleadoActuacion(codigo) {
	if (codigo.value == "") alert("¡ERROR: Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaAuditores"); 
		var trDetalle = document.getElementById(codigo.value); 
		listaDetalles.removeChild(trDetalle);
		codigo.value = "";
	}
}
//	abrir lista
function abrirListaActividadesPlanificacion(frmdetalles) {
	var proceso = document.getElementById("proceso").value;
	var fecha_inicio = document.getElementById("finicio").value.trim();
	
	if (proceso == "") alert("¡ERROR: No ha seleccionado un proceso!");
	else if (fecha_inicio == "" || !valFecha(fecha_inicio)) alert("¡ERROR: Fecha de Inicio incorrecta");
	else {
		var detalles = "";
		var error_detalles = "";	
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "actividad") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		window.open('pf_lista_actividades.php?ventana=insertarListaActividadesActuacion&filtrar=DEFAULT&limit=0&detalles='+detalles+'&fecha_inicio='+fecha_inicio+'&proceso='+proceso, 'listaActividades', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=200, top=200, resizable=yes');
	}
}
//	quitar de la lista
function quitarListaActividadesActuacion(codigo) {
	if (codigo.value == "") alert("¡ERROR: Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaActividades"); 
		var trDetalle = document.getElementById(codigo.value); 
		listaDetalles.removeChild(trDetalle);
		codigo.value = "";
		
		setFechaActividades();
	}
}
//	insertar linea
function insertarListaActividadesActuacion(actividad, detalles, fecha_inicio) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarListaActividadesActuacion&actividad="+actividad+"&detalles="+detalles+"&fecha_inicio="+fecha_inicio+"&proceso="+opener.document.getElementById("proceso").value);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				opener.document.getElementById("duracion").value = datos[2];
				opener.document.getElementById("total_duracion").innerHTML = datos[2];
				opener.document.getElementById("ftermino").value = datos[3];
				setFechaActividadesOpener();
				window.close();
			}
		}
	}
}
//	insertar lista de actividades
function setListaActividades(proceso, fecha_inicio) {
	if (proceso == "" || fecha_inicio == "" || !valFecha(fecha_inicio)) {
		document.getElementById("listaActividades").innerHTML = "";
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=insertarListaActividadesActuacion&proceso="+proceso+"&fecha_inicio="+fecha_inicio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				if (datos[0].trim() != "") alert(resp);
				else {
					document.getElementById("listaActividades").innerHTML = datos[1];
					document.getElementById("duracion").value = datos[2];
					document.getElementById("total_duracion").innerHTML = datos[2];
					document.getElementById("ftermino").value = datos[3];
				}
			}
		}
	}
}
//	actualizar las fechas de las actividades
function setFechaActividades() {
	var proceso = document.getElementById('proceso').value.trim();
	var fecha_inicio = document.getElementById('finicio').value.trim();
	
	if (proceso == "" || fecha_inicio == "" || ! valFecha(fecha_inicio)) {
		document.getElementById("listaActividades").innerHTML = "";
	} else {
		var detalles = "";
		var error_detalles = "";		
		var frmdetalles = document.getElementById("frmactividades");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "fase") detalles += n.value + "|";
			if (n.name == "nomfase") detalles += n.value + "|";
			if (n.name == "actividad") detalles += n.value + "|";
			if (n.name == "nomactividad") detalles += n.value + "|";
			if (n.name == "flagautoarchivo") detalles += n.value + "|";
			if (n.name == "flagnoafectoplan") detalles += n.value + "|";
			if (n.name == "duracion") {
				var duracion = new Number(setNumero(n.value));
				if (isNaN(duracion)) { error_detalles = "¡Debe ingresar un valor numérico en la duración!"; n.value = 1; n.focus(); }
				if (duracion <= 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración!"; n.value = 1; n.focus(); }
				detalles += n.value + ";";
			}
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		if (error_detalles != "") alert(error_detalles);
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_funciones_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("accion=setFechaActividades&proceso="+proceso+"&fecha_inicio="+fecha_inicio+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					var datos = resp.split("||");
					if (datos[0] != "") document.getElementById("finicio").value = datos[0];
					document.getElementById("listaActividades").innerHTML = datos[1];
					document.getElementById("duracion").value = datos[2];
					document.getElementById("total_duracion").innerHTML = datos[2];
					document.getElementById("ftermino").value = datos[3];
				}
			}
		}
	}
}
//	actualizar las fechas de las actividades
function setFechaActividadesOpener() {
	var proceso = opener.document.getElementById('proceso').value.trim();
	var fecha_inicio = opener.document.getElementById('finicio').value.trim();
	
	if (proceso == "" || fecha_inicio == "" || ! valFecha(fecha_inicio)) {
		opener.document.getElementById("listaActividades").innerHTML = "";
	} else {
		var detalles = "";
		var error_detalles = "";		
		var frmdetalles = opener.document.getElementById("frmactividades");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "fase") detalles += n.value + "|";
			if (n.name == "nomfase") detalles += n.value + "|";
			if (n.name == "actividad") detalles += n.value + "|";
			if (n.name == "nomactividad") detalles += n.value + "|";
			if (n.name == "duracion") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
	
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setFechaActividades&proceso="+proceso+"&fecha_inicio="+fecha_inicio+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("||");
				opener.document.getElementById("listaActividades").innerHTML = datos[0];
				opener.document.getElementById("duracion").value = datos[1];
				opener.document.getElementById("total_duracion").innerHTML = datos[1];
				opener.document.getElementById("ftermino").value = datos[2];
			}
		}
	}
}
//	terminar actividad
function terminarActividad(form) {
	var actuacion = document.getElementById("actuacion").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else if (formatFechaAMD(finicio_real) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de inicio real es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANIFICACION&accion=TERMINAR&actuacion="+actuacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
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

//	PRORROGAS
function verificarProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var actuacion = document.getElementById("actuacion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || actuacion == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&actuacion="+actuacion+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
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
//	seleccionar actividades de la actuacion
function setActuacionActividades(actuacion, nomactuacion) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setActuacionActividades&actuacion="+actuacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("actuacion").value = actuacion;
				opener.document.getElementById("nomactuacion").value = nomactuacion;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	POTESTAD INVESTIGATIVA
function verificarPotestadInvestigativa(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var actuacion = document.getElementById("actuacion").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los abogados!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&actuacion="+actuacion+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_potestad_investigativa.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	terminar actividad
function terminarPotestadActividad(form) {
	var potestad = document.getElementById("potestad").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else if (formatFechaAMD(finicio_real) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de inicio real es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion=TERMINAR&potestad="+potestad+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
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

//	auto archivar la actuación
function autoArchivoPotestad(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var potestad = document.getElementById("potestad").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=POTESTAD-INVESTIGATIVA&accion=AUTO-ARCHIVO&potestad="+potestad+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	auto archivar la actuación
function autoArchivoActuacion(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var actuacion = document.getElementById("actuacion").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PLANIFICACION&accion=AUTO-ARCHIVO&actuacion="+actuacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	auto archivar la actuación
function autoArchivoDeterminacion(form) {
	if (confirm("¿Está seguro de ordenar el Archivo de la Actuación?")) {
		var determinacion = document.getElementById("determinacion").value.trim();
		var actividad = document.getElementById("actividad").value.trim();
		var secuencia = document.getElementById("secuencia").value.trim();
		var finicio_real = document.getElementById("finicio_real").value.trim();
		var ftermino_real = document.getElementById("ftermino_real").value.trim();
		var dias_real = document.getElementById("dias_real").value.trim();
		var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
		var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
		var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
		var observaciones = document.getElementById("observaciones").value.trim();
		var detalles = "";
		var error_detalles = "";
		
		var frmdetalles = document.getElementById("frmdetalles");
		for(i=0; n=frmdetalles.elements[i]; i++) {
			if (n.name == "documento") detalles += n.value + "|";
			if (n.name == "nrodocumento") detalles += n.value + "|";
			if (n.name == "fdocumento") detalles += n.value + ";";
		}
		var len = detalles.length; len--;
		detalles = detalles.substr(0, len);
		
		//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
		if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
		else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
		else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_pf.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion=AUTO-ARCHIVO&determinacion="+determinacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp.trim() != "") alert(resp.trim());
					else form.submit();
				}
			}
		}
	}
}

//	PRORROGAS(POTESTAD)
function verificarPotestadProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var potestad = document.getElementById("potestad").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || potestad == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POTESTAD-PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&potestad="+potestad+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
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
//	seleccionar actividades de la potestad
function setPotestadActividades(potestad, nompotestad) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setPotestadActividades&potestad="+potestad);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("potestad").value = potestad;
				opener.document.getElementById("nompotestad").value = nompotestad;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setPotestadProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setPotestadProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	DETERMINACION DE RESPONSABILIDADES
function verificarDeterminacionResponsabilidades(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var potestad = document.getElementById("potestad").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var finicio = document.getElementById("finicio").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var codccosto = document.getElementById("codccosto").value.trim();
	var tipo_actuacion = document.getElementById("tipo_actuacion").value.trim();
	var duracion = document.getElementById("duracion").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var organismoext = document.getElementById("organismoext").value.trim();
	var dependenciaext = document.getElementById("dependenciaext").value.trim();
	var objetivo_general = document.getElementById("objetivo_general").value.trim();
	var alcance = document.getElementById("alcance").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	auditores
	var detalles_auditores = "";
	var error_detalles_auditores = "";		
	var frmauditores = document.getElementById("frmauditores");
	for(i=0; n=frmauditores.elements[i]; i++) {
		if (n.name == "flagcoordinador") detalles_auditores += n.checked + "|";
		if (n.name == "persona") detalles_auditores += n.value + ";";
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";		
	var frmactividades = document.getElementById("frmactividades");
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "actividad") detalles_actividades += n.value + "|";
		if (n.name == "duracion") detalles_actividades += n.value + "|";
		if (n.name == "finicio") detalles_actividades += n.value + "|";
		if (n.name == "ftermino") detalles_actividades += n.value + "|";
		if (n.name == "prorroga") detalles_actividades += n.value + "|";
		if (n.name == "finicioreal") detalles_actividades += n.value + "|";
		if (n.name == "fterminoreal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || dependencia == "" || fregistro == "" || finicio == "" || ftermino == "" || codccosto == "" || tipo_actuacion == "" || proceso == "" || organismoext == "" || objetivo_general == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(finicio)) alert("¡ERROR: Formato de la fecha inicio incorrecta (dd-mm-aaaa)!");
	else if (detalles_auditores == "") alert("¡ERROR: Debe ingresar los abogados!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&potestad="+potestad+"&codigo="+codigo+"&fregistro="+fregistro+"&finicio="+finicio+"&ftermino="+ftermino+"&codccosto="+codccosto+"&tipo_actuacion="+tipo_actuacion+"&duracion="+duracion+"&proceso="+proceso+"&organismoext="+organismoext+"&dependenciaext="+dependenciaext+"&objetivo_general="+objetivo_general+"&alcance="+alcance+"&observaciones="+observaciones+"&estado="+estado+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else if (accion == "APROBAR") {
					window.open("pf_pdf_determinacion_responsabilidades.php?codigo="+codigo+"&forganismo="+organismo+"&fregistrod="+fregistro+"&fregistroh="+fregistro+"&forganismoext="+organismoext+"&fdependenciaext="+dependenciaext+"&fedoreg="+estado+"&fproceso="+proceso, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
					form.submit();
				}
				else form.submit();
			}
		}
	}
	return false;
}
//	terminar actividad
function terminarDeterminacionActividad(form) {
	var determinacion = document.getElementById("determinacion").value.trim();
	var actividad = document.getElementById("actividad").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var finicio_real = document.getElementById("finicio_real").value.trim();
	var ftermino_real = document.getElementById("ftermino_real").value.trim();
	var dias_real = document.getElementById("dias_real").value.trim();
	var fregistro_cierre = document.getElementById("fregistro_cierre").value.trim();
	var ftermino_cierre = document.getElementById("ftermino_cierre").value.trim();
	var duracion_cierre = document.getElementById("duracion_cierre").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "documento") detalles += n.value + "|";
		if (n.name == "nrodocumento") detalles += n.value + "|";
		if (n.name == "fdocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (ftermino_cierre == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (!valFecha(ftermino_cierre)) alert("¡ERROR: Formato de la fecha terminado incorrecta (dd-mm-aaaa)!");
	else if (formatFechaAMD(ftermino_cierre) > formatFechaAMD(fregistro_cierre)) alert("¡ERROR: La fecha de terminado es mayor a la fecha actual!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-RESPONSABILIDADES&accion=TERMINAR&determinacion="+determinacion+"&actividad="+actividad+"&secuencia="+secuencia+"&finicio_real="+finicio_real+"&ftermino_real="+ftermino_real+"&dias_real="+dias_real+"&fregistro_cierre="+fregistro_cierre+"&ftermino_cierre="+ftermino_cierre+"&duracion_cierre="+duracion_cierre+"&observaciones="+observaciones+"&detalles="+detalles);
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

//	PRORROGAS(DETERMINACION)
function verificarDeterminacionProrroga(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var fregistro = document.getElementById("fregistro").value.trim();
	var determinacion = document.getElementById("determinacion").value.trim();
	var motivo = document.getElementById("motivo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	
	//	actividades
	var detalles_actividades = "";
	var error_detalles_actividades = "";
	var frmactividades = document.getElementById("frmactividades");
	var dias = new Number();
	for(i=0; n=frmactividades.elements[i]; i++) {
		if (n.name == "estado") {
			var estado_actividad = n.value;
			detalles_actividades += n.value + "|";
		}
		else if (n.name == "fase") detalles_actividades += n.value + "|";
		else if (n.name == "nomfase") detalles_actividades += n.value + "|";
		else if (n.name == "actividad") detalles_actividades += n.value + "|";
		else if (n.name == "nomactividad") detalles_actividades += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles_actividades += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles_actividades += n.value + "|";
		else if (n.name == "duracion") detalles_actividades += n.value + "|";
		else if (n.name == "finicio") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino") detalles_actividades += n.value + "|";
		else if (n.name == "acumulado") detalles_actividades += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (dias == 0 && prorroga != 0) dias = prorroga;
			if (isNaN(prorroga)) { error_detalles_actividades = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga < 0) { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; }
			else if (prorroga == 0 && estado_actividad == "EJ") { error_detalles_actividades = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; break; } 
			else detalles_actividades += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles_actividades += n.value + "|";
		else if (n.name == "ftermino_real") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo == "" || fregistro == "" || determinacion == "" || motivo == "") alert("¡ERROR: Debe ingresar los campos obligatorios!");
	else if (detalles_actividades == "") alert("¡ERROR: Debe ingresar las actividades!");
	else if (error_detalles_actividades != "") alert(error_detalles_actividades);
	else if (dias == 0) alert("¡la cantidad de dias de la prórroga no puede ser cero!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DETERMINACION-PRORROGA&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&fregistro="+fregistro+"&determinacion="+determinacion+"&motivo="+motivo+"&estado="+estado+"&detalles_actividades="+detalles_actividades);
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
//	seleccionar actividades de la potestad
function setDeterminacionActividades(determinacion, nomdeterminacion) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setDeterminacionActividades&determinacion="+determinacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(datos[0]);
			else {
				opener.document.getElementById("determinacion").value = determinacion;
				opener.document.getElementById("nomdeterminacion").value = nomdeterminacion;
				opener.document.getElementById("listaActividades").innerHTML = datos[1];
				window.close();
			}
		}
	}
}
//	actualizar lista de actividades (prorroga)
function setDeterminacionProrrogaActividades() {
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frmactividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "estado") detalles += n.value + "|";
		else if (n.name == "fase") detalles += n.value + "|";
		else if (n.name == "nomfase") detalles += n.value + "|";
		else if (n.name == "actividad") detalles += n.value + "|";
		else if (n.name == "nomactividad") detalles += n.value + "|";
		else if (n.name == "flagautoarchivo") detalles += n.value + "|";
		else if (n.name == "flagnoafectoplan") detalles += n.value + "|";
		else if (n.name == "duracion") detalles += n.value + "|";
		else if (n.name == "finicio") detalles += n.value + "|";
		else if (n.name == "ftermino") detalles += n.value + "|";
		else if (n.name == "acumulado") detalles += n.value + "|";
		else if (n.name == "prorroga") {
			var prorroga = new Number(setNumero(n.value));
			if (isNaN(prorroga)) { error_detalles = "¡Debe ingresar un valor numérico en la duración de prórroga!"; n.value = 1; }
			else if (prorroga < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración de prórroga!"; n.value = 1; }
			detalles += n.value + "|";
		}
		else if (n.name == "finicio_real") detalles += n.value + "|";
		else if (n.name == "ftermino_real") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_pf.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setDeterminacionProrrogaActividades&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("listaActividades").innerHTML = "";
				document.getElementById("listaActividades").innerHTML = resp;
			}
		}
	}
	return false;
}

//	FUNCION PARA VALIDAR EL FILTRO
function pdf_actuacion_fiscal(form, pdf) {
	var forganismo = document.getElementById("forganismo").value;
	var fregistrod = document.getElementById("fregistrod").value;
	var fregistroh = document.getElementById("fregistroh").value;
	var forganismoext = document.getElementById("forganismoext").value;
	var fdependenciaext = document.getElementById("fdependenciaext").value;
	var fedoreg = document.getElementById("fedoreg").value;
	var factuacion = document.getElementById("factuacion").value;
	var fproceso = document.getElementById("fproceso").value;
	
	if (factuacion == "") alert("¡ERROR: Debe seleccionar una actuación!");
	else {
		if (fproceso == "01") {
			if (pdf == "planificacion")
				window.open("pf_pdf_actuacion_fiscal.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
			else if (pdf == "ejecucion")
				window.open("pf_pdf_actuacion_fiscal_ejecucion.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
		}
		else if (fproceso == "02") {
			if (pdf == "planificacion")
				window.open("pf_pdf_potestad_investigativa.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
			else if (pdf == "ejecucion")
				window.open("pf_pdf_potestad_investigativa_ejecucion.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
		}
		else if (fproceso == "03") {
			if (pdf == "planificacion")
				window.open("pf_pdf_determinacion_responsabilidades.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
			else if (pdf == "ejecucion")
				window.open("pf_pdf_determinacion_responsabilidades_ejecucion.php?forganismo="+forganismo+"&fregistrod="+fregistrod+"&fregistroh="+fregistroh+"&forganismoext="+forganismoext+"&fdependenciaext="+fdependenciaext+"&fedoreg="+fedoreg+"&fproceso="+fproceso+"&codigo="+factuacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
		}
	}
	return false;
}

//
function insertarDocumentoPlanificacionFiscal() {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_pf.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarDocumentoPlanificacionFiscal");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var codigo = new Number(document.getElementById("nrodetalle").value);
			var newTr = document.createElement("tr");
			newTr.className = "trListaBody";
			newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
			newTr.id = codigo;
			document.getElementById("listaDetalles").appendChild(newTr);
			document.getElementById(codigo).innerHTML += resp;
			document.getElementById("nrodetalle").value = ++codigo;
		}
	}
}

//	
function quitarDocumentoPlanificacionFiscal(detalle) {
	var nrodetalle = new Number(document.getElementById("nrodetalle").value);
	var listaDetalles = document.getElementById("listaDetalles"); 
	var trDetalle = document.getElementById(detalle.value); 
	listaDetalles.removeChild(trDetalle);
	detalle.value = "";
}

function validarListaActuacion(form) {
	fproceso = document.getElementById('fproceso').value;
	if (fproceso == "01")
		cargarVentana(form, 'pf_listado_actuaciones.php?filtrar=DEFAULT&ventana=&estado=TODOS&cod=factuacion&nom=nomactuacion', 'height=800, width=1100, left=50, top=50, resizable=yes');
	if (fproceso == "02")
		cargarVentana(form, 'pf_listado_potestad.php?filtrar=DEFAULT&ventana=&estado=TODOS&cod=factuacion&nom=nomactuacion', 'height=800, width=1100, left=50, top=50, resizable=yes');
	if (fproceso == "03")
		cargarVentana(form, 'pf_listado_determinacion.php?filtrar=DEFAULT&ventana=&estado=TODOS&cod=factuacion&nom=nomactuacion', 'height=800, width=1100, left=50, top=50, resizable=yes');
}