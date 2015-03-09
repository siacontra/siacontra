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

//	funcion para convertir un numero frmateado en su valor real
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

//	FUNCION QUE DEVUELVE SI UN VALOR ES UNA FECHA
function esFecha(fecha) {
	var dma = fecha.split("-");
	if (typeof dma[0]=="undefined") dma[0]="";
	if (typeof dma[1]=="undefined") dma[1]="";
	if (typeof dma[2]=="undefined") dma[2]="";	
	var d = new String (dma[0]); d=d.trim();
	var m = new String (dma[1]); m=m.trim();
	var a = new String (dma[2]); a=a.trim();
	if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	if (!esFecha) {
		var dma = fecha.split("/");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";	
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	}
	if (!esFecha) return false;
	else {
		var mes = new Array();
		mes[1]=31; mes[3]=31; mes[4]=30; mes[5]=31; mes[6]=30; mes[7]=31; mes[8]=31; mes[9]=30; mes[10]=31; mes[11]=30; mes[12]=31;
		if (a%4==0) mes[2]=29; else mes[2]=28;
		//
		var dias = new Number (d);
		var meses = new Number (m);
		var annios = new Number (a);
		if ((dias>mes[meses] || dias<1) || (meses>12 || meses<=0) || (annios<=0)) return false; else return true;
	}
	if ((dias>mes[meses] || dias<1) || (meses>12 || meses<=0) || (annios<=0)) return false; else return true;
}

//	FUNCION QUE DEVUELVE SI UN VALOR ES UNA FECHA
function esPContable(fecha) {
	var dma = fecha.split("-");
	if (typeof dma[0]=="undefined") dma[0]="";
	if (typeof dma[1]=="undefined") dma[1]="";	
	var a = new String (dma[0]); a=a.trim();
	var m = new String (dma[1]); m=m.trim();
	if (m=="" || a=="" || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	if (!esFecha) {
		var dma = fecha.split("/");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		var a = new String (dma[0]); a=a.trim();
		var m = new String (dma[1]); m=m.trim();	
		if (m=="" || a=="" || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	}
	if (!esFecha) return false;
	else {
		var annios = new Number (a);
		var meses = new Number (m);
		if (meses>12 || meses<=0 || annios<=0) return false; else return true;
	}
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
	if (codigo=="") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion_2(form, pagina, target, param, campo) {
	var codigo = document.getElementById(campo).value;
	if (codigo == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		pagina = pagina+"&limit=0&registro="+codigo;
		if (target == "SELF") cargarPagina(form, pagina);
		else cargarVentana(form, pagina, param);
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
				ajax.open("POST", "fphp_ajax_lg_2.php", true);
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
function chkFiltroLinea(boo) {
	document.getElementById("btLinea").disabled = !boo;
	document.getElementById("fcodlinea").value = "";
	document.getElementById("fnomlinea").value = "";
	document.getElementById("fcodfamilia").value = "";
	document.getElementById("fnomfamilia").value = "";
	document.getElementById("fcodsubfamilia").value = "";
	document.getElementById("fnomsubfamilia").value = "";
}
function chkFiltroItem(boo) {
	document.getElementById("btItem").disabled = !boo;
	document.getElementById("fcoditem").value = "";
	document.getElementById("fnomitem").value = "";
}
function enabledCCosto(form) {
	if (form.chkccosto.checked) form.btCCosto.disabled=false;
	else { form.btCCosto.disabled=true; form.fccosto.value=""; }
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

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistros(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
		
	if (!rows) {
		if (document.getElementById("btVer")) document.getElementById("btVer").disabled = true;
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
		if (document.getElementById("btCopiar")) document.getElementById("btCopiar").disabled = true;
		if (document.getElementById("btReversa")) document.getElementById("btReversa").disabled = true;
		if (document.getElementById("btDevolucion")) document.getElementById("btDevolucion").disabled = true;
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
}
function numRegistros(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
function totalLotes(registros, rows, limit) {
	var desde = document.getElementById("desde");
	var hasta = document.getElementById("hasta");
	var btPrimero=document.getElementById("btPrimero");
	var btAtras=document.getElementById("btAtras");
	var btSiguiente=document.getElementById("btSiguiente");
	var btUltimo=document.getElementById("btUltimo");
	if (registros) {
		if (limit==0) { btPrimero.disabled=true; btAtras.disabled=true; } else { btPrimero.disabled=false; btAtras.disabled=false; }		
		if ((registros<=MAXLIMIT) || ((limit+rows)==registros) || (limit==registros)) { btSiguiente.disabled=true; btUltimo.disabled=true; }
		else { btSiguiente.disabled=false; btUltimo.disabled=false; }		
		desde.innerHTML=limit+1;
		hasta.innerHTML=limit+rows;
	} else {
		btPrimero.disabled=true; btAtras.disabled=true;
		btSiguiente.disabled=true; btUltimo.disabled=true;
		desde.innerHTML=0;
		hasta.innerHTML=0;
	}
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
//	----------------------------------------------------------------------
//	
//	----------------------------------------------------------------------

//	CONTROL DE ALMACEN
function verificarRecepcionAlmacen(form, accion) {
	var anio = document.getElementById("anio").value;
	var nroorden = document.getElementById("nroorden").value;
	var organismo = document.getElementById("codorganismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var transaccion = document.getElementById("transaccion").value;
	var docgenerar = document.getElementById("docgenerar").value;
	var nrodocumento = document.getElementById("nrodocumento").value;
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var periodo = document.getElementById("periodo").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var docreferencia = document.getElementById("docreferencia").value;
	var nrodocreferencia = document.getElementById("nrodocreferencia").value.trim();
	var ingresadopor = document.getElementById("ingresadopor").value;
	var recibidopor = document.getElementById("recibidopor").value;
	var comentarios = document.getElementById("comentarios").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var docref = document.getElementById("docref").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	var nota_entrega = document.getElementById("nota_entrega").value.trim();
	if (document.getElementById("flagmanual").checked) var flagmanual = "S"; else var flagmanual = "N";
	if (document.getElementById("flagpendiente").checked) var flagpendiente = "S"; else var flagpendiente = "N";
	var detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		if (n.name == "txtcodunidad") detalles += n.value + "|";
		if (n.name == "txtstock") detalles += setNumero(n.value) + "|";
		if (n.name == "txtcantidadpedida") detalles += n.value + "|";
		if (n.name == "txtcantidadrecibida") detalles += n.value + "|";
		if (n.name == "txtcantidadpendiente") detalles += n.value + "|";
		if (n.name == "txtcantidad") detalles += setNumero(n.value) + "|";
		if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (ftransaccion == "" || ccosto == "" || almacen == "" || docref == "" || docinterno == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!esFecha) alert("¡Fecha de transacción incorrecta!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-RECEPCION&accion="+accion+"&nroorden="+nroorden+"&organismo="+organismo+"&transaccion="+transaccion+"&docgenerar="+docgenerar+"&ftransaccion="+ftransaccion+"&periodo="+periodo+"&ccosto="+ccosto+"&almacen="+almacen+"&docreferencia="+docreferencia+"&nrodocreferencia="+nrodocreferencia+"&ingresadopor="+ingresadopor+"&recibidopor="+recibidopor+"&comentarios="+comentarios+"&razon="+razon+"&flagmanual="+flagmanual+"&flagpendiente="+flagpendiente+"&detalles="+detalles+"&nrodocumento="+nrodocumento+"&docref="+docref+"&docinterno="+docinterno+"&nota_entrega="+nota_entrega+"&dependencia="+dependencia+"&anio="+anio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != "") alert(datos[0]);
				else {
					form.submit(); 
					window.close();
					window.open("almacen_recepcion_recepcionar_pdf.php?nrodocumento="+datos[1]+"&docgenerar="+docgenerar+"&organismo="+organismo, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=0, top=0, resizable=yes');
				}
			}
		}
	}
	return false;
}

//	funcion para mostrar los detalles por invitacion
function verDetallesOrdenCompra() {
	var orden = document.getElementById("selorden").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=verDetallesOrdenCompra&orden="+orden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("trDetalle").innerHTML = resp;
		}
	}
}

//	funcion para cerrar las lineas de la orden compra seleccionada
function cerrarLineaOrdenCompra(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var nroorden = document.getElementById("selorden").value;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else if (confirm("¿Está seguro de cerrar los items seleccionado?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-RECEPCION&accion=cerrarLineaOrdenCompra&detalles="+detalles+"&nroorden="+nroorden+"&codorganismo="+codorganismo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(resp);
				else {
					if (datos[1] != "0") verDetallesOrdenCompra();
					else document.getElementById("frmfiltro").submit();
				}
			}
		}
	}
}

//	funcion para cerrar las lineas de la orden compra seleccionada
function recepcionarLineaOrdenCompra(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var orden = document.getElementById("selorden").value;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else window.open("almacen_recepcion_recepcionar.php?detalles="+detalles+"&orden="+orden+"&codorganismo="+codorganismo, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1000, left=0, top=0, resizable=yes');
}

//	funcion para mostrar los detalles de los requerimientos en despacho de almacen
function mostrarRequerimientoDetalles() {
	var selrequerimiento = document.getElementById("selrequerimiento").value;
	var codorganismo = document.getElementById("forganismo").value;
	
	if (selrequerimiento != "") {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=mostrarRequerimientoDetalles&codrequerimiento="+selrequerimiento+"&codorganismo="+codorganismo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("trDetalle").innerHTML = resp;
			}
		}
	}
}

function verRequerimiento(form) {
	var selrequerimiento = document.getElementById("selrequerimiento").value;
	var codorganismo = document.getElementById("forganismo").value;
	var registro = codorganismo + "|" + selrequerimiento;
	
	if (selrequerimiento == "") alert("¡Debe seleccionar un requerimiento!");
	else window.open("lg_requerimientos_ver.php?registro="+registro+"&dver=disabled", '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1000, left=200, top=200, resizable=no');
}

//	funcion para cerrar las lineas de la orden compra seleccionada
function cerrarLineaRequerimiento(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var codrequerimiento = document.getElementById("selrequerimiento").value;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else if (confirm("¿Está seguro de cerrar los items seleccionado?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-DESPACHO&accion=cerrarLineaRequerimiento&detalles="+detalles+"&codrequerimiento="+codrequerimiento+"&codorganismo="+codorganismo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(resp);
				else {
					if (datos[1] != "0") mostrarRequerimientoDetalles();
					else document.getElementById("frmfiltro").submit();
				}
			}
		}
	}
}

//	funcion para realizar la transaccion de despacho en almacen
function despachoAlmacen(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var codrequerimiento = document.getElementById("selrequerimiento").value;
	var detalles = "";
	var error_stock = false;
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) {
			detalles += n.value + ";";
			var stock = new Number(document.getElementById("stock_"+n.value).value);
			if (stock <= 0) error_stock = true;
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else if (error_stock) alert("¡No puede seleccionar items con Stock Actual igual a cero!");
	else window.open("transacciones_despachar.php?detalles="+detalles+"&codrequerimiento="+codrequerimiento+"&codorganismo="+codorganismo, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=650, width=1000, left=0, top=0, resizable=yes');
}

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleRequerimientoDespacho(coddetalle) {
	if (coddetalle == "") alert("¡Debe seleccionar un detalle!");
	else if (confirm("¿Desea quitar este detalle de la lista?")) {		
		var total_old = new Number(setNumero(document.getElementById("total_"+coddetalle).innerHTML));
		var total_transaccion = new Number(setNumero(document.getElementById("total_transaccion").innerHTML));
		total_transaccion -= total_old;
		document.getElementById("total_transaccion").innerHTML = setNumeroFormato(redondear(total_transaccion, 2), 2, ".", ",");
		
		var cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
		document.getElementById("cantdetalles").value = cantdetalles;
		if (cantdetalles == 0) document.getElementById("nrodetalles").value = 0;
	}
}

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleTransaccionEspecial(coddetalle) {
	if (coddetalle == "") alert("¡Debe seleccionar un detalle!");
	else if (confirm("¿Desea quitar este detalle de la lista?")) {		
		var total_old = new Number(setNumero(document.getElementById("total_"+coddetalle).innerHTML));
		var total_transaccion = new Number(setNumero(document.getElementById("total_transaccion").innerHTML));
		total_transaccion -= total_old;
		document.getElementById("total_transaccion").innerHTML = setNumeroFormato(redondear(total_transaccion, 2), 2, ".", ",");
		
		var cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
		document.getElementById("cantdetalles").value = cantdetalles;
		if (cantdetalles == 0) document.getElementById("nrodetalles").value = 0;
		
		var idgrupo = "grupo_" + coddetalle;
		if (document.getElementById(idgrupo) != null) {
			var listaDetalles = document.getElementById("listaDetallesActivo"); 
			var trDetalle = document.getElementById(idgrupo); 
			listaDetalles.removeChild(trDetalle);
			
			var j = 1;	
			var frmdetallesactivo = document.getElementById("frmdetallesactivo");
			for(i=0; n=frmdetallesactivo.elements[i]; i++) {
				var id = j + "_" + coddetalle;
				var listaDetalles = document.getElementById("listaDetallesActivo"); 
				var trDetalle = document.getElementById(id); 
				listaDetalles.removeChild(trDetalle);
				
				j++;
			}
		}
	}
}

function verificarDespachoAlmacen(form, accion) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	var organismo = document.getElementById("codorganismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var transaccion = document.getElementById("transaccion").value;
	var docgenerar = document.getElementById("docgenerar").value;
	var nrodocumento = document.getElementById("nrodocumento").value;
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var periodo = document.getElementById("periodo").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var docreferencia = document.getElementById("docreferencia").value;
	var nrodocreferencia = document.getElementById("nrodocreferencia").value.trim();
	var ingresadopor = document.getElementById("ingresadopor").value;
	var recibidopor = document.getElementById("recibidopor").value;
	var comentarios = document.getElementById("comentarios").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var docref = document.getElementById("docref").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	var nota_entrega = document.getElementById("nota_entrega").value.trim();
	if (document.getElementById("flagmanual").checked) var flagmanual = "S"; else var flagmanual = "N";
	if (document.getElementById("flagpendiente").checked) var flagpendiente = "S"; else var flagpendiente = "N";
	var detalles = "";
	var error = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		if (n.name == "txtcodunidad") detalles += n.value + "|";
		if (n.name == "txtstock") {
			var stock = new Number(setNumero(n.value));
			detalles += setNumero(n.value) + "|";
		}
		if (n.name == "txtcantidadpedida") detalles += n.value + "|";
		if (n.name == "txtcantidadrecibida") detalles += n.value + "|";
		if (n.name == "txtcantidadpendiente") detalles += n.value + "|";
		if (n.name == "txtcantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (cantidad > stock) error = "¡ERROR:Se encontrarón cantidades en los Items mayores al Stock Actual!"
			detalles += setNumero(n.value) + "|";
		}
		if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (ftransaccion == "" || ccosto == "" || almacen == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!esFecha) alert("¡Fecha de transacción incorrecta!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle!");
	else if (error != "") alert(error);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-DESPACHO&accion="+accion+"&codrequerimiento="+codrequerimiento+"&organismo="+organismo+"&transaccion="+transaccion+"&docgenerar="+docgenerar+"&ftransaccion="+ftransaccion+"&periodo="+periodo+"&ccosto="+ccosto+"&almacen="+almacen+"&docreferencia="+docreferencia+"&nrodocreferencia="+nrodocreferencia+"&ingresadopor="+ingresadopor+"&recibidopor="+recibidopor+"&comentarios="+comentarios+"&razon="+razon+"&flagmanual="+flagmanual+"&flagpendiente="+flagpendiente+"&detalles="+detalles+"&nrodocumento="+nrodocumento+"&docref="+docref+"&docinterno="+docinterno+"&nota_entrega="+nota_entrega+"&dependencia="+dependencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != "") alert(datos[0]);
				else {
					form.submit(); 
					window.close(); 
					window.open("almacen_despacho_pdf.php?nrodocumento="+datos[1]+"&docgenerar="+docgenerar+"&organismo="+organismo, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=0, top=0, resizable=yes');
				}
			}
		}
	}
	return false;
}

function dirigirCompraReposicion() {
	var codorganismo = document.getElementById("forganismo").value;
	var codrequerimiento = document.getElementById("selrequerimiento").value;
	
	if (codrequerimiento == "") alert("¡Debe seleccionar por lo menos un detalle!"); 
	else if (confirm("¿Está seguro de pasar a compras el requerimiento seleccionado?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-DESPACHO&accion=dirigirCompraReposicion&codorganismo="+codorganismo+"&codrequerimiento="+codrequerimiento);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else document.getElementById("frmfiltro").submit();
			}
		}
	}
}

function pasarCompras(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var codrequerimiento = document.getElementById("selrequerimiento").value;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else if (confirm("¿Está seguro de pasar a compras los items seleccionado?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACEN-DESPACHO&accion=pasarCompras&detalles="+detalles+"&codrequerimiento="+codrequerimiento+"&codorganismo="+codorganismo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else document.getElementById("frmfiltro").submit();
			}
		}
	}
}

function verificarNuevaTransaccion(form, accion) {
	var organismo = document.getElementById("codorganismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var transaccion = document.getElementById("transaccion").value;
	var movimiento = document.getElementById("movimiento").value;
	var docgenerar = document.getElementById("docgenerar").value;
	var nrodocumento = document.getElementById("nrodocumento").value;
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var periodo = document.getElementById("periodo").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var docreferencia = document.getElementById("docreferencia").value;
	var nrodocreferencia = document.getElementById("nrodocreferencia").value.trim();
	var ingresadopor = document.getElementById("ingresadopor").value;
	var recibidopor = document.getElementById("recibidopor").value;
	var comentarios = document.getElementById("comentarios").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var docref = document.getElementById("docref").value;
	var docinterno = document.getElementById("docinterno").value;
	if (document.getElementById("flagmanual").checked) var flagmanual = "S"; else var flagmanual = "N";
	if (document.getElementById("flagpendiente").checked) var flagpendiente = "S"; else var flagpendiente = "N";
	var detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		if (n.name == "txtcodunidad") detalles += n.value + "|";
		if (n.name == "txtstock") detalles += setNumero(n.value) + "|";
		if (n.name == "txtcantidad") detalles += setNumero(n.value) + "|";
		if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (ftransaccion == "" || ccosto == "" || almacen == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!esFecha) alert("¡Fecha de transacción incorrecta!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TRANSACCIONES&accion="+accion+"&organismo="+organismo+"&transaccion="+transaccion+"&docgenerar="+docgenerar+"&ftransaccion="+ftransaccion+"&periodo="+periodo+"&ccosto="+ccosto+"&almacen="+almacen+"&docreferencia="+docreferencia+"&nrodocreferencia="+nrodocreferencia+"&ingresadopor="+ingresadopor+"&recibidopor="+recibidopor+"&comentarios="+comentarios+"&razon="+razon+"&flagmanual="+flagmanual+"&flagpendiente="+flagpendiente+"&detalles="+detalles+"&nrodocumento="+nrodocumento+"&movimiento="+movimiento+"&docref="+docref+"&docinterno="+docinterno+"&dependencia="+dependencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != "") alert(resp);
				else form.submit();
			}
		}
	}
	return false;
}
function selListadoAgregarTransaccion(busqueda, cod, nom, movimiento, docgenerar, nomdocgenerar, doctransaccion) {
	var registro = document.getElementById("registro").value;
	opener.document.getElementById(cod).value = registro;
	opener.document.getElementById(nom).value = busqueda;
	opener.document.getElementById("movimiento").value = movimiento;
	opener.document.getElementById("docgenerar").value = docgenerar;
	opener.document.getElementById("nomdocgenerar").value = nomdocgenerar;
	opener.document.getElementById("docreferencia").value = doctransaccion;
	opener.document.getElementById("btInsertar").disabled = false;
	opener.document.getElementById("seldetalle").value = "";
	opener.document.getElementById("nrodetalles").value = "0";
	opener.document.getElementById("cantdetalles").value = "0";
	//	------------------------------------------------
	if (registro == "DRO" || registro == "ECP" || registro == "EDP" || registro == "SME" || registro == "SRO" || registro == "SCM" || registro == "SPA" || registro == "SPI" || registro == "SRS" || registro == "IAI" || registro == "ICP" || registro == "IDP" || registro == "IRS") {
		opener.document.getElementById("flagmanual").disabled = true;
		opener.document.getElementById("flagpendiente").disabled = true;
		opener.document.getElementById("nota_entrega").disabled = true;
		opener.document.getElementById("flagmanual").checked = false;
		opener.document.getElementById("flagpendiente").checked = false;
	}
	else if (registro == "FAL" || registro == "RES" || registro == "SDP" || registro == "TFE" || registro == "TLE" || registro == "IRU" || registro == "REG" || registro == "RID" || registro == "SOB" || registro == "STI" || registro == "TRN" || registro == "VTI") {
		opener.document.getElementById("flagmanual").disabled = false;
		opener.document.getElementById("flagpendiente").disabled = true;
		opener.document.getElementById("nota_entrega").disabled = true;
		opener.document.getElementById("flagmanual").checked = false;
		opener.document.getElementById("flagpendiente").checked = false;
	}
	else if (registro == "AJP" || registro == "IMP" || registro == "PRI") {
		opener.document.getElementById("flagmanual").disabled = true;
		opener.document.getElementById("flagpendiente").disabled = true;
		opener.document.getElementById("nota_entrega").disabled = true;
		opener.document.getElementById("flagmanual").checked = true;
		opener.document.getElementById("flagpendiente").checked = false;
	}
	//	------------------------------------------------
	
	var detalles = "";
	var k = 0;
	var frmdetalles = opener.document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles != "") {
		var listaDetalles = opener.document.getElementById("listaDetalles"); 
		if (detalles != "") {
			var registros = detalles.split("|");
			for (i=0; i<registros.length; i++) {
				var trDetalle = opener.document.getElementById(registros[i]); 
				listaDetalles.removeChild(trDetalle);
			}
		}
	}
	window.close();
}
function selListadoAgregarTransaccionEspecial(busqueda, cod, nom, movimiento, docgenerar, nomdocgenerar) {
	var registro = document.getElementById("registro").value;
	opener.document.getElementById(cod).value = registro;
	opener.document.getElementById(nom).value = busqueda;
	opener.document.getElementById("movimiento").value = movimiento;
	opener.document.getElementById("docgenerar").value = docgenerar;
	opener.document.getElementById("nomdocgenerar").value = nomdocgenerar;
	opener.document.getElementById("btInsertar").disabled = false;
	opener.document.getElementById("seldetalle").value = "";
	opener.document.getElementById("nrodetalles").value = "0";
	opener.document.getElementById("cantdetalles").value = "0";
	opener.document.getElementById("clasificacion").value = "";
	//	------------------------------------------------
	
	var detalles = "";
	var k = 0;
	var frmdetalles = opener.document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles != "") {
		var listaDetalles = opener.document.getElementById("listaDetalles"); 
		if (detalles != "") {
			var registros = detalles.split("|");
			for (i=0; i<registros.length; i++) {
				var trDetalle = opener.document.getElementById(registros[i]); 
				listaDetalles.removeChild(trDetalle);
			}
		}
	}
	window.close();
}

function setTabActivosFijos(boo) {
	if (boo) document.getElementById("tabActivo").style.display = "block";
	else document.getElementById("tabActivo").style.display = "none";
}

//	ITEM X ALMACEN
function verificarItemAlmacen(form, accion) {
	var codalmacen = document.getElementById("codalmacen").value.trim();
	var coditem = document.getElementById("coditem").value.trim();
	var codunidad = document.getElementById("codunidad").value.trim();
	var stockmin = setNumero(document.getElementById("stockmin").value.trim());
	var stockmax = setNumero(document.getElementById("stockmax").value.trim());
	var reorden = setNumero(document.getElementById("reorden").value.trim());
	var espera = setNumero(document.getElementById("espera").value.trim());
	var ubicacion1 = document.getElementById("ubicacion1").value.trim();
	var ubicacion2 = document.getElementById("ubicacion2").value.trim();
	var ubicacion3 = document.getElementById("ubicacion3").value.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (coditem == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ITEM-ALMACEN&accion="+accion+"&codalmacen="+codalmacen+"&coditem="+coditem+"&codunidad="+codunidad+"&stockmin="+stockmin+"&stockmax="+stockmax+"&reorden="+reorden+"&espera="+espera+"&ubicacion1="+ubicacion1+"&ubicacion2="+ubicacion2+"&ubicacion3="+ubicacion3+"&estado="+estado);
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

//	CONTROL DE PERIODOS
function verificarControlPeriodo(form, accion) {
	var organismo = document.getElementById("organismo").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (periodo == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (!esPContable) alert("¡Periodo Contable Incorrecto!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CONTROL-PERIODOS&accion="+accion+"&organismo="+organismo+"&periodo="+periodo+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
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

//	REQUERIMIENTOS PENDIENTES
function verRequerimientoPendiente(form) {
	var detalles = "";
	var con = 0;
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "detalle" && n.checked) { detalles += n.value + ";"; con+=1; }
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (con == 0) alert("¡Debe seleccionar un requerimiento!");
	else if (con > 1) alert("¡Debe seleccionar solo un requerimiento!");
	else {
		document.getElementById("registro").value = detalles;
		window.open("lg_requerimientos_form.php?opcion=ver&registro="+detalles, 'requerimientos_form', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=200, top=0, resizable=no');
	}
}

function cerrarRequerimientoPendiente(form) {
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un requerimiento!");
	else if (confirm("¿Desea cerrar los requerimientos seleccionados?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-PENDIENTES&accion=CERRAR&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(form, "lg_requerimientos_pendientes.php?limit=0");
			}
		}
	}
}

//	REQUERIMIENTOS PENDIENTES
function generarRequerimientoPendiente(form) {
	var detalles = "";
	var con = 0;
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar un requerimiento!");
	else {
		cargarPagina(form, "lg_requerimientos_pendientes_nuevo.php?limit=0&detalles="+detalles);
	}
}

//	TIPOS DE TRANSACCIONES
function verificarTiposTransaccionesCommodities(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("ingreso").checked) var tipo = "I"; 
	else if (document.getElementById("egreso").checked) var tipo = "E"; 
	else if (document.getElementById("transferencia").checked) var tipo = "T"; 
	var docgenerado = document.getElementById("docgenerado").value;
	var doctransaccion = document.getElementById("doctransaccion").value;
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "" || doctransaccion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-TRANSACCIONES-COMMODITIES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&docgenerado="+docgenerado+"&doctransaccion="+doctransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_transacciones_commodities.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function setDocumentoGenerado(tipo) {
	if (tipo == "NI") {
		document.getElementById("docgenerado").value = "NI";
		document.getElementById("docgeneradodesc").value = "Nota de Ingreso";
	}
	else if (tipo == "NS") {
		document.getElementById("docgenerado").value = "NS";
		document.getElementById("docgeneradodesc").value = "Nota de Salida";
	}
	else if (tipo == "NT") {
		document.getElementById("docgenerado").value = "NT";
		document.getElementById("docgeneradodesc").value = "Nota de Transferencia";
	}
}

//	---------------------------------------------------

//	RECEPCION COMMODITIES
function verificarRecepcionCommodity(form, accion) {
	document.getElementById("btSubmit").disabled = true;
	var dependencia = document.getElementById("dependencia").value;
	var clasificacion = document.getElementById("clasificacion").value;
	var anio = document.getElementById("anio").value;
	var nroorden = document.getElementById("nroorden").value;
	var organismo = document.getElementById("codorganismo").value;
	var transaccion = document.getElementById("transaccion").value;
	var docgenerar = document.getElementById("docgenerar").value;
	var nrodocumento = document.getElementById("nrodocumento").value;
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var periodo = document.getElementById("periodo").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var docreferencia = document.getElementById("docreferencia").value;
	var nrodocreferencia = document.getElementById("nrodocreferencia").value.trim();
	var ingresadopor = document.getElementById("ingresadopor").value;
	var recibidopor = document.getElementById("recibidopor").value;
	var comentarios = document.getElementById("comentarios").value.trim();
	var codubicacion = document.getElementById("codubicacion").value.trim();
	var docrefremision = document.getElementById("docref").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	if (document.getElementById("flagactivo").checked) var flagactivo = "S"; else var flagactivo = "N";
	var detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "secuencia") detalles += n.value + "|";
		else if (n.name == "txtcodunidad") detalles += n.value + "|";
		else if (n.name == "txtstock") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtcantidadpedida") detalles += n.value + "|";
		else if (n.name == "txtcantidadrecibida") detalles += n.value + "|";
		else if (n.name == "txtcantidadpendiente") detalles += n.value + "|";
		else if (n.name == "txtcantidad") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	var error_detalles_activo = false;
	var detalles_activo = "";
	
	var frmdetallesactivo = document.getElementById("frmdetallesactivo");
	for(i=0; n=frmdetallesactivo.elements[i]; i++) {
		if (n.name == "chkdetallesactivo") { detalles_activo += n.checked + "|"; detalles_activo += n.title + "|"; }
		else if (n.name == "reforden") { detalles_activo += n.value + "|"; }
		else if (n.name == "descripcion") detalles_activo += n.value + "|";
		else if (n.name == "clasificacion") detalles_activo += n.value + "|";
		else if (n.name == "preciounit") detalles_activo += n.value + "|";
		else if (n.name == "nroserie") {
			if (n.value.trim() == "") error_detalles_activo = true;
			detalles_activo += n.value.trim() + "|";
		}
		else if (n.name == "fingreso") {
			if (n.value.trim() == "") error_detalles_activo = true;
			detalles_activo += n.value + "|";
		}
		else if (n.name == "modelo") detalles_activo += n.value + "|";
		else if (n.name == "codbarra") detalles_activo += n.value + "|";
		else if (n.name == "txtubicacion") {
			if (n.value.trim() == "") error_detalles_activo = true;
			detalles_activo += n.value + "|";
		}
		else if (n.name == "txtccostos") {
			if (n.value.trim() == "") error_detalles_activo = true;
			detalles_activo += n.value + "|";
		}
		else if (n.name == "nroplaca") detalles_activo += n.value + "|";
		else if (n.name == "marca") detalles_activo += n.value + "|";
		else if (n.name == "color") detalles_activo += n.value + "|";
		else if (n.name == "flagfactura") detalles_activo += n.checked + ";";
	}
	var len = detalles_activo.length; len--;
	detalles_activo = detalles_activo.substr(0, len);
	
	if (ftransaccion == "" || ccosto == "" || almacen == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!esFecha) alert("¡Fecha de transacción incorrecta!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle!");
	else if (flagactivo == "S" && error_detalles_activo) alert("¡Debe llenar los campos obligatorios en las lineas de activos!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITY-RECEPCION&accion="+accion+"&nroorden="+nroorden+"&organismo="+organismo+"&transaccion="+transaccion+"&docgenerar="+docgenerar+"&ftransaccion="+ftransaccion+"&periodo="+periodo+"&ccosto="+ccosto+"&almacen="+almacen+"&docreferencia="+docreferencia+"&nrodocreferencia="+nrodocreferencia+"&ingresadopor="+ingresadopor+"&recibidopor="+recibidopor+"&comentarios="+comentarios+"&flagactivo="+flagactivo+"&docrefremision="+docrefremision+"&docinterno="+docinterno+"&codubicacion="+codubicacion+"&detalles="+detalles+"&nrodocumento="+nrodocumento+"&clasificacion="+clasificacion+"&detalles_activo="+detalles_activo+"&dependencia="+dependencia+"&anio="+anio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != "") alert(datos[0]);
				else {
					form.submit();  
					alert("Nueva Transacción " + datos[2] + "-" + datos[1] + " ha sido creada");
					setTimeout('', 10000);
					if (flagactivo == "S") {
						window.open("transacciones_especiales_activos_pdf.php?nrodocumento="+datos[1]+"&docgenerar="+docgenerar+"&organismo="+organismo, 'pdf2', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=0, top=0, resizable=yes');
						setTimeout('', 10000);
					}
					window.open("transacciones_especiales_pdf.php?nrodocumento="+datos[1]+"&docgenerar="+docgenerar+"&organismo="+organismo, 'pdf1', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=50, top=50, resizable=yes');
					setTimeout('', 10000);
					window.close();
				}
			}
		}
	}
	document.getElementById("btSubmit").disabled = false;
	return false;
}

//	funcion para cerrar las lineas de la orden compra seleccionada
function recepcionarLineaOrdenCompraCommodity(form) {
	var codorganismo = document.getElementById("forganismo").value;
	var nroorden = document.getElementById("selorden").value;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name=="detalle" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else window.open("transacciones_especiales_recepcionar.php?detalles="+detalles+"&nroorden="+nroorden+"&codorganismo="+codorganismo, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=1000, left=0, top=0, resizable=yes');
}

//	funcion para mostrar los detalles por invitacion
function verDetallesOrdenCompraCommodity() {
	var codorganismo = document.getElementById("forganismo").value;
	var nroorden = document.getElementById("selorden").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=verDetallesOrdenCompraCommodity&nroorden="+nroorden+"&codorganismo="+codorganismo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("trDetalle").innerHTML = resp;
		}
	}
}

function setActivoFijo(boo) {
	document.getElementById("codubicacion").value = "";
	document.getElementById("nomubicacion").value = "";
	document.getElementById("clasificacion").value = "";
	document.getElementById("nrodetalles").value = "0";
	document.getElementById("cantdetalles").value = "0";
	if (boo) {
		document.getElementById("btUbicacion").disabled = false;
		document.getElementById("tabActivo").style.display = "block";
	} else {
		document.getElementById("btUbicacion").disabled = true;
		document.getElementById("tabActivo").style.display = "none";
	}
	
	var detalles = "";
	var k = 0;
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles != "") {
		var listaDetalles = document.getElementById("listaDetalles"); 
		if (detalles != "") {
			var registros = detalles.split("|");
			for (i=0; i<registros.length; i++) {
				var trDetalle = document.getElementById(registros[i]); 
				listaDetalles.removeChild(trDetalle);
			}
		}
	}
}

function selCCosto(form, detalle) {
	if (detalle == "") alert("¡Debe seleccionar un detalle!");
	else {
		cargarVentana(form, 'listado_centro_costos.php?cod=txtccostos&nom=txtnomccostos&ventana=lista&detalle='+detalle, 'height=600, width=1100, left=50, top=50, resizable=yes');
	}
}

function selUbicacionActivo(form, detalle) {
	if (detalle == "") alert("¡Debe seleccionar un detalle!");
	else {
		cargarVentana(form, 'listado_ubicaciones_activos.php?cod=txtubicacion&nom=txtnomubicacion&ventana=lista&detalle='+detalle, 'height=600, width=1100, left=50, top=50, resizable=yes');
	}
}

function imprimirTransaccion(seltransaccion) {
	if (seltransaccion == "") alert("¡Debe seleccionar una transacción!");
	else {
		var partes = seltransaccion.split("-");
		
		var organismo = partes[0];
		var docgenerar = partes[1];
		var nrodocumento = partes[2];
		
		if (docgenerar == "NS") var pagina = "almacen_despacho_pdf.php";
		else if (docgenerar == "NI") var pagina = "almacen_recepcion_recepcionar_pdf.php";
		else var pagina = "";
		
		window.open(pagina+"?organismo="+organismo+"&docgenerar="+docgenerar+"&nrodocumento="+nrodocumento, "wPdfTransaccion", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
	}
}

//	
function setValorizacionManual(boo) {
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") var coditem = n.title;
		else if (n.name == "txtpreciounit") { n.value = "0,00"; n.disabled = !boo; document.getElementById("total_"+coditem).innerHTML = "0,00"; }
	}
	document.getElementById("total_transaccion").innerHTML = "0,00";
}

//	
function setTotalTransaccion(coditem) {
	var cantidad = new Number(setNumero(document.getElementById("txtcantidad_"+coditem).value));
	if (cantidad == 0) {
		alert("¡La cantidad no puede ser cero!");
		document.getElementById("txtcantidad_"+coditem).value = "1,00";
	} else {
		var preciounit = new Number(setNumero(document.getElementById("txtpreciounit_"+coditem).value));
		var total = cantidad * preciounit;
		
		var total_old = new Number(setNumero(document.getElementById("total_"+coditem).innerHTML));
		var total_transaccion_old = new Number(setNumero(document.getElementById("total_transaccion").innerHTML));
		var total_transaccion = total_transaccion_old + (total - total_old);
		
		document.getElementById("total_"+coditem).innerHTML = setNumeroFormato(redondear(total, 2), 2, ".", ",");
		document.getElementById("total_transaccion").innerHTML = setNumeroFormato(redondear(total_transaccion, 2), 2, ".", ",");
	}
}

//	
function setCantidadActivos(valor, cantidad_pendiente, coditem, reforden) {
	var cantidad = new Number(setNumero(valor));
	if (cantidad > cantidad_pendiente) {
		alert("¡La cantidad ingresada no puede ser mayor a " + cantidad_pendiente +"!");
		document.getElementById("txtcantidad_"+coditem).value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	}
	else if (cantidad == 0) {
		alert("¡La cantidad ingresada no puede ser cero");
		document.getElementById("txtcantidad_"+coditem).value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	}
	setLineasActivos(reforden);
}

function setLineasActivos(reforden) {
	var detalles = "";
	var ccosto = document.getElementById("ccosto").value;
	var codubicacion = document.getElementById("codubicacion").value;
	var frmdetalles = document.getElementById("frmdetalles");
	var docinterno = document.getElementById("docinterno");
	var codorganismo = document.getElementById("codorganismo");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtcantidad") detalles += n.value + "|";
		else if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setLineasActivos&detalles="+detalles+"&ccosto="+ccosto+"&codubicacion="+codubicacion+"&codorganismo="+codorganismo+"&docinterno="+docinterno+"&reforden="+reforden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("listaDetallesActivo").innerHTML = resp;
		}
	}
}

//	NUEVA TRANSACCION COMMODITIES
function verificarNuevaTransaccionEspecial(form, accion) {
	var clasificacion = document.getElementById("clasificacion").value;
	var organismo = document.getElementById("codorganismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var transaccion = document.getElementById("transaccion").value;
	var docgenerar = document.getElementById("docgenerar").value;
	var nrodocumento = document.getElementById("nrodocumento").value;
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var movimiento = document.getElementById("movimiento").value;
	var periodo = document.getElementById("periodo").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var docreferencia = document.getElementById("docreferencia").value;
	var nrodocreferencia = document.getElementById("nrodocreferencia").value.trim();
	var ingresadopor = document.getElementById("ingresadopor").value;
	var recibidopor = document.getElementById("recibidopor").value;
	var comentarios = document.getElementById("comentarios").value.trim();
	var codubicacion = document.getElementById("codubicacion").value.trim();
	var docrefremision = document.getElementById("docref").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	if (document.getElementById("flagactivo").checked) var flagactivo = "S"; else var flagactivo = "N";
	var detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtcodunidad") detalles += n.value + "|";
		else if (n.name == "txtstock") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtcantidad") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtpreciounit") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtdoc") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (ftransaccion == "" || ccosto == "" || almacen == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!esFecha) alert("¡Fecha de transacción incorrecta!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TRANSACCIONES-ESPECIALES&accion="+accion+"&organismo="+organismo+"&transaccion="+transaccion+"&docgenerar="+docgenerar+"&ftransaccion="+ftransaccion+"&periodo="+periodo+"&ccosto="+ccosto+"&almacen="+almacen+"&docreferencia="+docreferencia+"&nrodocreferencia="+nrodocreferencia+"&ingresadopor="+ingresadopor+"&recibidopor="+recibidopor+"&comentarios="+comentarios+"&flagactivo="+flagactivo+"&docrefremision="+docrefremision+"&docinterno="+docinterno+"&codubicacion="+codubicacion+"&detalles="+detalles+"&nrodocumento="+nrodocumento+"&clasificacion="+clasificacion+"&movimiento="+movimiento+"&dependencia="+dependencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != "") alert(datos[0]);
				else {
					alert("Nueva Transacción " + datos[2] + "-" + datos[1] + " ha sido creada");
					form.submit(); 
				}
			}
		}
	}
	return false;
}

function imprimirTransaccionEspecial(seltransaccion) {
	if (seltransaccion == "") alert("¡Debe seleccionar una transacción!");
	else {
		var partes = seltransaccion.split("-");
		
		var organismo = partes[0];
		var docgenerar = partes[1];
		var nrodocumento = partes[2];
		
		window.open("transacciones_especiales_activos_pdf.php?nrodocumento="+nrodocumento+"&docgenerar="+docgenerar+"&organismo="+organismo, 'pdf2', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=0, top=0, resizable=yes');
		window.open("transacciones_especiales_pdf.php?nrodocumento="+nrodocumento+"&docgenerar="+docgenerar+"&organismo="+organismo, 'pdf1', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=0, top=0, resizable=yes');
	}
}

//	
function setCantidadTransaccion(valor, cantidad_pendiente, coditem) {
	var cantidad = new Number(setNumero(valor));
	if (cantidad > cantidad_pendiente) {
		alert("¡La cantidad ingresada no puede ser mayor a " + cantidad_pendiente +"!");
		document.getElementById("txtcantidad_"+coditem).value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	}
	else if (cantidad == 0) {
		alert("¡La cantidad ingresada no puede ser cero");
		document.getElementById("txtcantidad_"+coditem).value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	}
	setTotalTransaccion(coditem);
}

function validar_reporte_movimientos_de_almacen_pdf() {
	var fdesde = document.getElementById('fdesde').value;
	var fhasta = document.getElementById('fhasta').value;
	
	if (!esPContable(fdesde) || !esPContable(fhasta) || fdesde > fhasta) { alert("¡ERROR: Debe ingresar un periodo válido!"); return false; }
	else return true;
}

function tab_reporte_movimientos_de_almacen(tab) {
	var form = document.getElementById("frmentrada");
	if (tab == "general") form.action = "reporte_movimientos_de_almacen_pdf.php";
	else form.action = "reporte_movimientos_de_almacen_detallado_pdf.php";	
	form.submit();
}

function validar_reporte_inventario_valorizado_pdf() {
	var fdesde = document.getElementById('fdesde').value;
	
	if (!esPContable(fdesde)) { alert("¡ERROR: Debe ingresar un periodo válido!"); return false; }
	else return true;
}

//	REVERSAR TRANSACCION
function reversarTransaccion(form) {
	var organismo = document.getElementById("codorganismo").value.trim();
	var coddocumento = document.getElementById("docgenerar").value.trim();
	var nrodocumento = document.getElementById("nrodocumento").value.trim();
	var transaccion = document.getElementById("transaccion").value.trim();
	var ftransaccion = document.getElementById("ftransaccion").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var movimiento = document.getElementById("movimiento").value.trim();
	var docinterno = document.getElementById("docinterno").value.trim();
	var coddocumento_transaccion = document.getElementById("coddocumento_transaccion").value.trim();
	var nrodocumento_transaccion = document.getElementById("nrodocumento_transaccion").value.trim();
	
	//	detalles
	var detalles = "";
	var error = false;
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "txtstock") var stock = new Number(setNumero(n.value));
		else if (n.name == "txtcantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (stock < cantidad) error = true;
		}
	}
	
	//	valido si se encontró algún error
	if (error) alert("¡ERROR: No puede ingresar items mayores al stock actual!");
	else {
		if (razon == "") var razon = prompt("Ingrese el motivo de la reversa:");
		//	cargo el php para actualizar
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TRANSACCIONES&accion=REVERSA&organismo="+organismo+"&coddocumento="+coddocumento+"&nrodocumento="+nrodocumento+"&razon="+razon+"&coddocumento_transaccion="+coddocumento_transaccion+"&nrodocumento_transaccion="+nrodocumento_transaccion+"&transaccion="+transaccion+"&ftransaccion="+ftransaccion+"&movimiento="+movimiento+"&docinterno="+docinterno);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					if (coddocumento == "NS") var pagina = "almacen_despacho_pdf.php";
					else if (coddocumento == "NI") var pagina = "almacen_recepcion_recepcionar_pdf.php";
					else var pagina = "";					
					window.open(pagina+"?organismo="+organismo+"&docgenerar="+coddocumento+"&nrodocumento="+nrodocumento, "wPdfTransaccion", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
					form.submit(); 
				}
			}
		}
	}
	return false;
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion_ReversaTransaccion(form, pagina, target, param, campo) {
	var codigo = document.getElementById(campo).value;
	if (codigo == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	cargo el php para actualizar
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=cargarOpcion_ReversaTransaccion&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					pagina = pagina+"&limit=0&registro="+codigo;
					if (target == "SELF") cargarPagina(form, pagina);
					else cargarVentana(form, pagina, param);
				}
			}
		}
	}
}

function setPeriodo(fecha, idperiodo) {
	var partes = fecha.split("-");
	document.getElementById(idperiodo).value = partes[2] + "-" + partes[1];
}