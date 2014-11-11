// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

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
		ajax.open("POST", "fphp_ajax_ac.php", true);
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
function chkFiltro_2(boo, id1, id2) {
	document.getElementById(id1).value = "";
	document.getElementById(id2).value = "";
	document.getElementById(id1).disabled = !boo;
	document.getElementById(id2).disabled = !boo;
}
function chkFiltro_3(boo, id) {
	document.getElementById(id).disabled = !boo;
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

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

//	--------------------------------------------------------------------------------
//	--
//	--------------------------------------------------------------------------------

//	SISTEMAS FUENTE
function verificarSistemaFuente(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ac.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SISTEMA-FUENTE&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

//	MODELOS DE VOUCHER
function validarModeloVoucher(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var distribucion = document.getElementById("distribucion").value.trim();
	var libro = document.getElementById("libro").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	if (document.getElementById("activo").value) var estado = "A"; else var estado = "I";
	var detalles = "";
	var error_detalles = "";
	
	// lineas
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "cuenta") {
			if (n.value == "") { error_detalles = "¡ERROR: Debe seleccionar la cuenta contable para todas las lineas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "ccosto") {
			if (n.value == "") { error_detalles = "¡ERROR: Debe seleccionar el centro de costo para todas las lineas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "porcentaje") {
			var porcentaje = new Number(setNumero(n.value));
			if (isNaN(porcentaje)) { error_detalles = "¡ERROR: Debe ingresar un valor númerico para el porcentaje!"; break; }
			else detalles += porcentaje + "|";
		}
		else if (n.name == "monto") {
			var monto = new Number(setNumero(n.value));
			if (isNaN(monto)) { error_detalles = "¡ERROR: Debe ingresar un valor númerico para el monto!"; break; }
			else detalles += monto + "|";
		}
		else if (n.name == "persona") {
			if (n.value == "") { error_detalles = "¡ERROR: Debe seleccionar la persona para todas las lineas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "nrodocumento") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (codigo == "" || descripcion == "" || libro == "" || dependencia == "") alert("ERROR: Los campos marcados con (*) son obligatorios");
	else if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_ac.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MODELO-VOUCHER&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&distribucion="+distribucion+"&libro="+libro+"&organismo="+organismo+"&dependencia="+dependencia+"&estado="+estado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp.trim());
				else form.submit();
			}
		}
	}
	return false;
}

function insertarLineaVoucher(boton) {
	boton.disabled = true;
	nrodetalles = new Number(document.getElementById("nrodetalles").value); nrodetalles++;
	cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles++;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ac.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarLineaVoucher&nrodetalles="+nrodetalles);
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
			boton.disabled = false;
		}
	}
}
function quitarLineaVoucher(seldetalle) {
	if (seldetalle == "") alert("¡Debe seleccionar una linea!");
	else {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var tr = document.getElementById(seldetalle); 
		listaDetalles.removeChild(tr);
		document.getElementById("seldetalle").value = "";
	}
}

function abrirListado(seldetalle, pagina) {
	if (seldetalle == "") alert("¡Debe seleccionar un registro!");
	else {
		window.open(pagina+"&seldetalle="+seldetalle, "wListado", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=525, width=1050, left=50, top=50, resizable=yes");
	}
}