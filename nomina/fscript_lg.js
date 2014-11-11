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
	if (codigo=="") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion_2(form, pagina, target, param, campo) {
	var codigo = document.getElementById(campo).value;
	if (codigo == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		pagina = pagina+"&limit=0&registro="+codigo;
		if (target == "SELF") cargarPagina(form, pagina);
		else cargarVentana(form, pagina, param);
	}
}

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, pagina, foraneo, modulo, accion) {
	var codigo=form.registro.value;
	if (codigo=="") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("�Esta seguro de eliminar este registro?");
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
						if (error!=0) alert ("�"+error+"!");
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
		if (document.getElementById("btImprimir")) document.getElementById("btImprimir").disabled = true;
		if (document.getElementById("btPDF")) document.getElementById("btPDF").disabled = true;
		if (document.getElementById("btVerStock")) document.getElementById("btVerStock").disabled = true;
		if (document.getElementById("btCuadroStock")) document.getElementById("btCuadroStock").disabled = true;
		if (document.getElementById("btVerComm")) document.getElementById("btVerComm").disabled = true;
		if (document.getElementById("btCuadroComm")) document.getElementById("btCuadroComm").disabled = true;
		if (document.getElementById("btDisponibilidad")) document.getElementById("btDisponibilidad").disabled = true;
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
		if (document.getElementById("btInvitarStock")) document.getElementById("btInvitarStock").disabled = true;
		if (document.getElementById("btCotizarStock")) document.getElementById("btCotizarStock").disabled = true;
		if (document.getElementById("btInvitarComm")) document.getElementById("btInvitarComm").disabled = true;
		if (document.getElementById("btCotizarComm")) document.getElementById("btCotizarComm").disabled = true;
		if (document.getElementById("btCotizar")) document.getElementById("btCotizar").disabled = true;
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
}
function totalRegistrosRequerimientos(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btRevisar=document.getElementById("btRevisar");
	var btConformar=document.getElementById("btConformar");
	var btAprobar=document.getElementById("btAprobar");
	var btAnular=document.getElementById("btAnular");
	var btCerrar=document.getElementById("btCerrar");
	var btVer=document.getElementById("btVer");
	var btRechazar=document.getElementById("btRechazar");
	var btImprimir=document.getElementById("btImprimir");
	//
	
	if (insert=="N") btNuevo.disabled=true;
	if (!rows) btImprimir.disabled=true;
	if (update=="N" || !rows) {
		btEditar.disabled=true;
		btRevisar.disabled=true;
		btConformar.disabled=true;
		btAprobar.disabled=true;
		btAnular.disabled=true;
		btCerrar.disabled=true;
		btVer.disabled=true;
		btRechazar.disabled=true;
	}
}
function totalRegistrosRequerimientosDet(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btVer=document.getElementById("btVer");
	var btCerrar=document.getElementById("btCerrar");
	//
	if (!rows) { btVer.disabled=true; btCerrar.disabled=true;  }
}
function totalRegistrosStock(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows_stock");
	numreg.innerHTML = "Registros: "+rows;		
	if (!rows) {
		if (document.getElementById("btVerStock")) document.getElementById("btVerStock").disabled = true;
		if (document.getElementById("btCuadroStock")) document.getElementById("btCuadroStock").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btInvitarStock")) document.getElementById("btInvitarStock").disabled = true;
		if (document.getElementById("btCotizarStock")) document.getElementById("btCotizarStock").disabled = true;
	}
}
function totalRegistrosComm(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows_comm");
	numreg.innerHTML = "Registros: "+rows;		
	if (!rows) {
		if (document.getElementById("btVerComm")) document.getElementById("btVerComm").disabled = true;
		if (document.getElementById("btCuadroComm")) document.getElementById("btCuadroComm").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btInvitarComm")) document.getElementById("btInvitarComm").disabled = true;
		if (document.getElementById("btCotizarComm")) document.getElementById("btCotizarComm").disabled = true;
	}
}
function totalRegistrosCompra(rows) {
	var numreg = document.getElementById("rows_compra");
	numreg.innerHTML="Registros: "+rows;
	var btGenerarCompra=document.getElementById("btGenerarCompra");
	//
	if (!rows) {
		btGenerarCompra.disabled=true;
	}
}
function totalRegistrosServicio(rows) {
	var numreg = document.getElementById("rows_servicio");
	numreg.innerHTML="Registros: "+rows;
	var btGenerarServicio=document.getElementById("btGenerarServicio");
	//
	if (!rows) {
		btGenerarServicio.disabled=true;
	}
}
function totalRegistrosOrdenCompra(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btAprobar=document.getElementById("btAprobar");
	var btAnular=document.getElementById("btAnular");
	var btCerrar=document.getElementById("btCerrar");
	var btRevisar=document.getElementById("btRevisar");
	var btImprimir=document.getElementById("btImprimir");
	//
	if (insert == "N") btNuevo.disabled = true;
	if (update == "N" || !rows) {
		btEditar.disabled=true;
		btAprobar.disabled=true;
		btAnular.disabled=true;
		btCerrar.disabled=true;
		btRevisar.disabled=true;
		if (!rows) {
			btVer.disabled=true;
			btImprimir.disabled=true;
		}
	}
}
function totalRegistrosOrdenConfirmar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows_confirmar");
	numreg.innerHTML="Registros: "+rows;
	var btConfirmar=document.getElementById("btConfirmar");
	//
	if (update == "N" || !rows) {
		btConfirmar.disabled=true;
	}
}
function totalRegistrosOrdenDesconfirmar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows_desconfirmar");
	numreg.innerHTML="Registros: "+rows;
	var btDesconfirmar=document.getElementById("btDesconfirmar");
	//
	if (update == "N" || !rows) {
		btDesconfirmar.disabled=true;
	}
}
function totalRegistrosOrdenCompraDet(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btVer=document.getElementById("btVer");
	//
	if (!rows) {
		btVer.disabled=true;
	}
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
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
function totalListaRows(id, rows) {
	var numreg = document.getElementById(id);
	numreg.innerHTML="Registros: "+rows;
}
function totalElementosRequerimientos(rows) {
	if (rows) {
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=false;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false;
	} else {		
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=true;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true;
	}
}
function numRegistros(rows) {
	var numreg = document.getElementById("rows");
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

//	FUNCION PARA SELECCIONAR UN REGISTRO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selLinea(campo, busqueda) {
	var registro=document.getElementById("registro").value;
	switch (campo) {
		case "linea":
			opener.document.frmentrada.codlinea.value=registro;
			opener.document.frmentrada.nomlinea.value=busqueda;
			break;
	}
	window.close();
}
function selFamilia(campo1, campo2, busqueda1, busqueda2) {
	var registro = document.getElementById("registro").value;
	var codigo = registro.split("|");
	switch (campo1) {
		case "linea":
			opener.document.frmentrada.codlinea.value=codigo[0];
			opener.document.frmentrada.nomlinea.value=busqueda1;
			break;
	}
	switch (campo2) {
		case "familia":
			opener.document.frmentrada.codfamilia.value=codigo[1];
			opener.document.frmentrada.nomfamilia.value=busqueda2;
			break;
	}
	window.close();
}
function selSubFamilia(campo1, campo2, campo3, busqueda1, busqueda2, busqueda3) {
	var registro = document.getElementById("registro").value;
	var codigo = registro.split("|");
	switch (campo1) {
		case "linea":
			opener.document.frmentrada.codlinea.value=codigo[0];
			opener.document.frmentrada.nomlinea.value=busqueda1;
			break;
		case "flinea":
			opener.document.frmentrada.fcodlinea.value=codigo[0];
			opener.document.frmentrada.fnomlinea.value=busqueda1;
			break;
	}
	switch (campo2) {
		case "familia":
			opener.document.frmentrada.codfamilia.value=codigo[1];
			opener.document.frmentrada.nomfamilia.value=busqueda2;
			break;
		case "ffamilia":
			opener.document.frmentrada.fcodfamilia.value=codigo[1];
			opener.document.frmentrada.fnomfamilia.value=busqueda2;
			break;
	}
	switch (campo3) {
		case "subfamilia":
			opener.document.frmentrada.codsubfamilia.value=codigo[2];
			opener.document.frmentrada.nomsubfamilia.value=busqueda3;
			break;
		case "fsubfamilia":
			opener.document.frmentrada.fcodsubfamilia.value=codigo[2];
			opener.document.frmentrada.fnomsubfamilia.value=busqueda3;
			break;
	}
	window.close();
}
function selListado(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;	
	opener.document.getElementById(nom).value=busqueda;
	window.close();
}
function selListadoFormularioDetalle(busqueda, cod, nom) {
	var registro = document.getElementById("registro").value;
	opener.document.getElementById(cod).value = registro;
	opener.document.getElementById(nom).value = busqueda;
	
	var centros = opener.document.getElementsByName("txtccostos");
	for (i=0; i<centros.length; i++) {
		centros[i].value = registro;
	}
	
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
function chkFiltroLista(boo, id1, id2, bt) {
	document.getElementById(id1).value = "";
	document.getElementById(id2).value = "";
	document.getElementById(id1).disabled = !boo;
	document.getElementById(id2).disabled = !boo;
	document.getElementById(bt).disabled = !boo;
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
function chkFiltroFamilia(boo) {
	document.getElementById("fcodfamilia").value = "";
	document.getElementById("fnomfamilia").value = "";
	
}
function chkFiltroSubFamilia(boo) {
	document.getElementById("fcodsubfamilia").value = "";
	document.getElementById("fnomsubfamilia").value = "";
	
}
function enabledBuscar(form) {
	if (form.chkbuscar.checked) { form.fbuscar.disabled=false; form.sltbuscar.disabled=false; } 
	else { form.fbuscar.disabled=true; form.sltbuscar.disabled=true; form.fbuscar.value=""; form.sltbuscar.value=""; }
}
function enabledCCosto(form) {
	if (form.chkccosto.checked) form.btCCosto.disabled=false;
	else { form.btCCosto.disabled=true; form.fccosto.value=""; }
}
function enabledProveedor(form) {
	if (form.chkproveedor.checked) form.btProveedor.disabled=false;
	else { form.btProveedor.disabled=true; form.fproveedor.value=""; form.fnomproveedor.value=""; }
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

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarUnCheck(id) {
	document.getElementById(id).checked = !document.getElementById(id).checked;
}

//	--------------------------------------------------------------------------------
//	--
//	--------------------------------------------------------------------------------


//	CLASIFICACION DE COMMODITIES
function verificarClasificacionCommodities(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (codigo == "" || descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (codigo.length < 3) alert("�LA CLASIFICACION DEBE TENER 3 DIGITOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACION-COMMODITIES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagtransaccion="+flagtransaccion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "clasificacion_commodities.php");
			}
		}
	}
	return false;
}

//	COMMODITIES
function verificarCommodities(form, accion) {
	var clasificacion = document.getElementById("clasificacion").value;
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (clasificacion == "" || descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&clasificacion="+clasificacion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != 0) alert(datos[0]);
				else  {
					if (accion == "GUARDAR-MAST") {
						if (confirm("�Desea ingresar las sub clasificaciones?")) {
							document.getElementById("bt_guardar").disabled = true;
							document.getElementById("frameSub").src = "commodity_sub_clasificacion.php?commoditymast="+datos[1]+"&clasificacion_commodity="+clasificacion;
						} else cargarPagina(form, "commodity.php");
					} else cargarPagina(form, "commodity.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function verificarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var unidad = document.getElementById("unidad").value;
	var codcuenta = document.getElementById("codcuenta").value;
	var codpartida = document.getElementById("codpartida").value;
	var codactivo = document.getElementById("codactivo").value;
	var estado = document.getElementById("estado").value;
	
	if (descripcion == "" || unidad == "" || codpartida == "" || codcuenta == "" || estado == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (!document.getElementById("btActivo").disabled && codactivo == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+codigo+"&descripcion="+descripcion+"&codcuenta="+codcuenta+"&codpartida="+codpartida+"&unidad="+unidad+"&codactivo="+codactivo+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					cargarPagina(form, "commodity_sub_clasificacion.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function editarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var dato = resp.split("|:|");
				if (dato[0] != 0) alert(resp);
				else  {
					document.getElementById("codigo").value = dato[1];
					document.getElementById("descripcion").value = dato[2];
					document.getElementById("unidad").value = dato[3];
					document.getElementById("estado").value = dato[4];
					document.getElementById("codpartida").value = dato[5];
					document.getElementById("codcuenta").value = dato[6];
					document.getElementById("codactivo").value = dato[7];
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btEliminar").disabled = true;
					document.getElementById("accion").value = "ACTUALIZAR-DET";
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function borrarSubCommodity(form, accion) {
	var commoditymast = document.getElementById("commoditymast").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("�Esta seguro de eliminar este registro?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=COMMODITIES&accion="+accion+"&commoditymast="+commoditymast+"&codigo="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp != 0) alert(resp);
					else  {
						cargarPagina(form, "commodity_sub_clasificacion.php");
					}
				}
			}
		}
	}
	return false;
}

//	UNIDADES DE MEDIDA
function verificarUnidadesMedida(form, accion) {
	var tipo = document.getElementById("tipo").value;
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (tipo == "" || codigo == "" || descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					if (accion == "GUARDAR-MAST") {
						if (confirm("�Desea ingresar las unidades equivalentes?")) {
							document.getElementById("bt_guardar").disabled = true;
							document.getElementById("frameEquivalentes").src = "unidades_medida_equivalentes.php?codunidad="+codigo;
						} else cargarPagina(form, "unidades_medida.php");
					} else cargarPagina(form, "unidades_medida.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function verificarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var equivalente = document.getElementById("equivalente").value;
	var cantidad = document.getElementById("cantidad").value; cantidad = cantidad.trim();
	var estado = document.getElementById("estado").value;
	
	if (equivalente == "" || cantidad == "" || estado == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (equivalente == codunidad) alert("�NO PUEDE INGRESAR COMO EQUIVALENTE LA MISMA UNIDAD!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+equivalente+"&cantidad="+cantidad+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else  {
					cargarPagina(form, "unidades_medida_equivalentes.php");
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function editarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var dato = resp.split("|:|");
				if (dato[0] != 0) alert(resp);
				else  {
					document.getElementById("equivalente").value = dato[1];
					document.getElementById("cantidad").value = dato[2];
					document.getElementById("estado").value = dato[3];
					document.getElementById("equivalente").disabled = true;
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btEliminar").disabled = true;
					document.getElementById("accion").value = "ACTUALIZAR-DET";
				}
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function borrarUnidadesEquivalentes(form, accion) {
	var codunidad = document.getElementById("codunidad").value;
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("�Esta seguro de eliminar este registro?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=UNIDADES-MEDIDA&accion="+accion+"&codunidad="+codunidad+"&equivalente="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp != 0) alert(resp);
					else  {
						cargarPagina(form, "unidades_medida_equivalentes.php");
					}
				}
			}
		}
	}
	return false;
}

//	ALMACENES
function verificarAlmacenes(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var tipo = document.getElementById("tipo").value; tipo = tipo.trim();
	var principal = document.getElementById("principal").value; principal = principal.trim();
	var organismo = document.getElementById("organismo").value; organismo = organismo.trim();
	var dependencia = document.getElementById("dependencia").value; dependencia = dependencia.trim();
	var codpersona = document.getElementById("codpersona").value; codpersona = codpersona.trim();
	var cuenta = document.getElementById("cuenta").value; cuenta = cuenta.trim();
	var direccion = document.getElementById("direccion").value; direccion = direccion.trim();
	if (document.getElementById("flagventa").checked) var flagventa = "S"; else var flagventa = "N";
	if (document.getElementById("flagproduccion").checked) var flagproduccion = "S"; else var flagproduccion = "N";
	if (document.getElementById("flagcommodities").checked) var flagcommodities = "S"; else var flagcommodities = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "" || tipo == "" || codpersona == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ALMACENES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&principal="+principal+"&organismo="+organismo+"&dependencia="+dependencia+"&codpersona="+codpersona+"&cuenta="+cuenta+"&direccion="+direccion+"&flagventa="+flagventa+"&flagproduccion="+flagproduccion+"&flagcommodities="+flagcommodities+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "almacenes.php");
			}
		}
	}
	return false;
}
//	---------------------------------------------------
function enabledAlmacenPrincipal(tipo) {
	if (tipo != "T") document.getElementById("principal").disabled = true;
	else document.getElementById("principal").disabled = false;
}

//	CLASIFICACIONES
function verificarClasificaciones(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("requisiciones").checked) var disponible = "R"; else var disponible = "O";
	var codalmacen = document.getElementById("codalmacen").value;
	var requerimiento = document.getElementById("requerimiento").value;
	if (document.getElementById("flagrevision").checked) var flagrevision = "S"; else var flagrevision = "N";
	if (document.getElementById("flagrecepcion").checked) var flagrecepcion = "S"; else var flagrecepcion = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	if (document.getElementById("almacen").checked) var almacen_compra = "A"; else var almacen_compra = "C";
	
	if (codigo == "" || descripcion == "" || requerimiento == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CLASIFICACIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&requerimiento="+requerimiento+"&disponible="+disponible+"&codalmacen="+codalmacen+"&flagrevision="+flagrevision+"&flagrecepcion="+flagrecepcion+"&flagtransaccion="+flagtransaccion+"&almacen_compra="+almacen_compra+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "clasificaciones.php");
			}
		}
	}
	return false;
}

//	TIPOS DE DOCUMENTOS
function verificarTiposDocumentos(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagdocfiscal").checked) var flagdocfiscal = "S"; else var flagdocfiscal = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-DOCUMENTOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagdocfiscal="+flagdocfiscal+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_documentos.php");
			}
		}
	}
	return false;
}

//	TIPOS DE TRANSACCIONES
function verificarTiposTransacciones(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("ingreso").checked) var tipo = "I"; 
	else if (document.getElementById("egreso").checked) var tipo = "E"; 
	else if (document.getElementById("transferencia").checked) var tipo = "T"; 
	var docgenerado = document.getElementById("docgenerado").value;
	var doctransaccion = document.getElementById("doctransaccion").value;
	if (document.getElementById("flagconsumo").checked) var flagconsumo = "S"; else var flagconsumo = "N";
	if (document.getElementById("flagajuste").checked) var flagajuste = "S"; else var flagajuste = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "" || doctransaccion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-TRANSACCIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&docgenerado="+docgenerado+"&doctransaccion="+doctransaccion+"&flagconsumo="+flagconsumo+"&flagajuste="+flagajuste+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_transacciones.php");
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

//	TIPOS DE ITEMS
function verificarTiposItems(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOS-ITEMS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagtransaccion="+flagtransaccion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "tipos_items.php");
			}
		}
	}
	return false;
}

//	ITEMS
function verificarItem(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var titem = document.getElementById("titem").value;
	var unidad = document.getElementById("unidad").value;
	var unidad_compra = document.getElementById("unidad_compra").value;
	var unidad_embalaje = document.getElementById("unidad_embalaje").value;
	var codlinea = document.getElementById("codlinea").value;
	var codfamilia = document.getElementById("codfamilia").value;
	var codsubfamilia = document.getElementById("codsubfamilia").value;
	var codinterno = document.getElementById("codinterno").value; codinterno = codinterno.trim();
	var imagen = document.getElementById("imagen").value; imagen = imagen.trim();
	var marca = document.getElementById("marca").value;
	var color = document.getElementById("color").value;
	var procedencia = document.getElementById("procedencia").value;
	var codbarra = document.getElementById("codbarra").value; codbarra = codbarra.trim();
	var stockmin = document.getElementById("stockmin").value; stockmin = stockmin.trim();
	var stockmax = document.getElementById("stockmax").value; stockmax = stockmax.trim();
	var ctainventario = document.getElementById("ctainventario").value; ctainventario = ctainventario.trim();
	var ctagasto = document.getElementById("ctagasto").value; ctagasto = ctagasto.trim();
	var ctaventa = document.getElementById("ctaventa").value; ctaventa = ctaventa.trim();
	var partida = document.getElementById("partida").value; partida = partida.trim();
	if (document.getElementById("flaglotes").checked) var flaglotes = "S"; else var flaglotes = "N";
	if (document.getElementById("flagkit").checked) var flagkit = "S"; else var flagkit = "N";
	if (document.getElementById("flagitem").checked) var flagitem = "S"; else var flagitem = "N";
	if (document.getElementById("flagimpuesto").checked) var flagimpuesto = "S"; else var flagimpuesto = "N";
	if (document.getElementById("flagauto").checked) var flagauto = "S"; else var flagauto = "N";
	if (document.getElementById("flagdisponible").checked) var flagdisponible = "S"; else var flagdisponible = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "" || unidad == "" || codsubfamilia == "" || codinterno == "" || procedencia == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ITEMS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&titem="+titem+"&unidad="+unidad+"&unidad_compra="+unidad_compra+"&unidad_embalaje="+unidad_embalaje+"&codlinea="+codlinea+"&codfamilia="+codfamilia+"&codsubfamilia="+codsubfamilia+"&codinterno="+codinterno+"&imagen="+imagen+"&marca="+marca+"&color="+color+"&procedencia="+procedencia+"&codbarra="+codbarra+"&stockmin="+stockmin+"&stockmax="+stockmax+"&ctainventario="+ctainventario+"&ctagasto="+ctagasto+"&ctaventa="+ctaventa+"&partida="+partida+"&flaglotes="+flaglotes+"&flagkit="+flagkit+"&flagitem="+flagitem+"&flagimpuesto="+flagimpuesto+"&flagauto="+flagauto+"&flagdisponible="+flagdisponible+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "items.php?limit=0");
			}
		}
	}
	return false;
}

//	LINEAS
function verificarLineas(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=LINEAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "lineas.php");
			}
		}
	}
	return false;
}

//	FAMILIAS
function verificarFamilias(form, accion) {
	var codlinea = document.getElementById("codlinea").value; codlinea = codlinea.trim();
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var ctainventario = document.getElementById("ctainventario").value; ctainventario = ctainventario.trim();
	var ctagasto = document.getElementById("ctagasto").value; ctagasto = ctagasto.trim();
	var ctaventas = document.getElementById("ctaventas").value; ctaventas = ctaventas.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codlinea == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FAMILIAS&accion="+accion+"&codlinea="+codlinea+"&codigo="+codigo+"&descripcion="+descripcion+"&ctainventario="+ctainventario+"&ctagasto="+ctagasto+"&ctaventas="+ctaventas+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "familias.php");
			}
		}
	}
	return false;
}

//	SUBFAMILIAS
function verificarSubFamilias(form, accion) {
	var codlinea = document.getElementById("codlinea").value; codlinea = codlinea.trim();
	var codfamilia = document.getElementById("codfamilia").value; codfamilia = codfamilia.trim();
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codlinea == "" || codfamilia == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUBFAMILIAS&accion="+accion+"&codlinea="+codlinea+"&codfamilia="+codfamilia+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "subfamilias.php");
			}
		}
	}
	return false;
}

//	PROCEDENCIAS
function verificarProcedencias(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (codigo == "" || descripcion == "") alert("¡DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCEDENCIAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "procedencias.php");
			}
		}
	}
	return false;
}

//	MARCAS
function verificarMarcas(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MARCAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "marcas.php");
			}
		}
	}
	return false;
}

//	REQUERIMIENTOS
function verificarRequerimiento(form, accion, detalles_anterior) {
	var codigo = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var estado = document.getElementById("estado").value;
	var prioridad = document.getElementById("prioridad").value;
	var clasificacion = document.getElementById("clasificacion").value;
	var ccosto = document.getElementById("ccosto").value;
	var almacen = document.getElementById("almacen").value;
	var frequerida = document.getElementById("frequerida").value;
	var fpreparado = document.getElementById("fpreparado").value;
	var frevisado = document.getElementById("frevisado").value;
	var faprobado = document.getElementById("faprobado").value;
	var preparadopor = document.getElementById("preparadopor").value;
	var revisadopor = document.getElementById("revisadopor").value;
	var aprobadopor = document.getElementById("aprobadopor").value;
	var comentarios = document.getElementById("comentarios").value; comentarios = comentarios.trim();
	var razon = document.getElementById("razon").value; razon = razon.trim();
	if (document.getElementById("flagcompras").checked) var flagdirigido = "C"; else var flagdirigido = "A";
	var tiporeq = document.getElementById("tiporeq").value;
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		if (n.name == "txtdescripcion") detalles += n.value + "|";
		if (n.name == "txtcodunidad") detalles += n.value + "|";
		if (n.name == "txtccostos") detalles += n.value + "|";
		if (n.name == "chkexon") detalles += n.checked + "|";
		if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			if (cant <= 0) { error_detalles = "�ERROR: No puede ingresar items con cantidad en cero!"; break; }
			detalles += setNumero(n.value) + "|";
		}
		if (n.name == "txtdocreferencia") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (prioridad == "" || clasificacion == "" || ccosto == "" || almacen == "" || frequerida == "" || preparadopor == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (detalles == "") alert("�Debe ingresar por lo menos un detalle!");
	else if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&codigo="+codigo+"&organismo="+organismo+"&dependencia="+dependencia+"&estado="+estado+"&prioridad="+prioridad+"&clasificacion="+clasificacion+"&ccosto="+ccosto+"&almacen="+almacen+"&frequerida="+frequerida+"&fpreparado="+fpreparado+"&frevisado="+frevisado+"&faprobado="+faprobado+"&preparadopor="+preparadopor+"&revisadopor="+revisadopor+"&aprobadopor="+aprobadopor+"&comentarios="+comentarios+"&razon="+razon+"&flagdirigido="+flagdirigido+"&detalles="+detalles+"&tiporeq="+tiporeq+"&detalles_anterior="+detalles_anterior);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0] != 0) alert(datos[0]);
				else {
					if (accion == "GUARDAR") alert("Nuevo Requerimiento " + datos[1] + " ha sido creado");
					var opciones = document.getElementById("opciones").value;
					if (opciones == "") var regresar = "framemain"; else var regresar = "lg_requerimientos";
					cargarPagina(form, regresar+".php?limit=0");
				}
			}
		}
	}
	return false;
}
function setEstadoRequerimiento(form, accion) {
	var codrequerimiento = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var razon = document.getElementById("razon").value; razon = razon.trim();
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&codrequerimiento="+codrequerimiento+"&organismo="+organismo+"&dependencia="+dependencia+"&razon="+razon);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("|");
			if (datos[0] != 0) alert(datos[0]);
			else if (accion == "APROBAR") {
				var registro = organismo + "|" + codrequerimiento;
				window.open("lg_requerimientos_pdf.php?registro="+registro, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
				cargarPagina(form, "lg_requerimientos.php?limit=0");
			}
			else cargarPagina(form, "lg_requerimientos.php?limit=0");
		}
	}
}
function verificarRequerimientoDetalle(form, seleccion, codrequerimiento) {
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var cant = document.getElementById("cant").value;
	var ccosto = document.getElementById("codccosto").value;
	if (document.getElementById("flagexonerado").checked) var flagexonerado = "S"; else var flagexonerado = "N";
	
	if (cant == 0 || ccosto == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=UPDATE&codrequerimiento="+codrequerimiento+"&seleccion="+seleccion+"&ccosto="+ccosto+"&cant="+cant+"&flagexonerado="+flagexonerado+"&descripcion="+descripcion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else {
					form.submit();
					window.close();
				}
			}
		}
	}
	return false;
}
function editarRequerimientoDetalle(form, seleccion) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	var tiporeq = document.getElementById("tiporeq").value;
	
	if (seleccion == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=EDITAR&seleccion="+seleccion+"&codrequerimiento="+codrequerimiento+"&tiporeq="+tiporeq);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var datos = ajax.responseText.split("|.|");
				if (datos[0].trim() != "") alert ("�"+datos[0]+"!");
				else {
					document.getElementById("accion").value = "UPDATE";
					document.getElementById("secuencia").value = datos[1];
					document.getElementById("codigo").value = datos[2];
					document.getElementById("descripcion").value = datos[3];
					document.getElementById("cant").value = datos[4];
					document.getElementById("ccosto").value = datos[5];
					document.getElementById("estado").value = datos[6];
					document.getElementById("comentario").value = datos[7];
					document.getElementById("documento").value = datos[8];
					if (datos[9] == "S") document.getElementById("flagexonerado").checked = true;
					else document.getElementById("flagexonerado").checked = false;
					document.getElementById("btExaminar").disabled = true;
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btBorrar").disabled = true;
				}
			}
		}
	}
}
function borrarRequerimientoDetalle(form, seleccion) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	
	if (seleccion == "") alert("�DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (confirm("�REALMENTE DESEA ELIMINAR ESTE REGISTRO?")) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=DELETE&seleccion="+seleccion+"&codrequerimiento="+codrequerimiento);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var datos = ajax.responseText;
					if (datos.trim() != "") alert ("�"+datos+"!");
					else cargarPagina(form, "lg_requerimientos_detalle.php");
				}
			}
		}
	}
}
function insertarCantidadRequerimiento(form) {
	var codrequerimiento = document.getElementById("codrequerimiento").value;
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var flagdirigido = document.getElementById("flagdirigido").value;
	var ccostoreq = document.getElementById("ccostoreq").value;
	var edoreq = document.getElementById("edoreq").value;
	var tiporeq = document.getElementById("tiporeq").value;
	
	var limit = document.getElementById("limit").value;
	var cod = document.getElementById("cod").value;
	var nom = document.getElementById("nom").value;
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="text" && n.value != "" && n.value != 0 && !isNaN(n.value)) {
			idunidad = "unidad:" + n.id;
			iddescripcion = "descripcion:" + n.id;
			var codigo = n.id;
			var codunidad = document.getElementById(idunidad).value;
			var descripcion = document.getElementById(iddescripcion).value;
			
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_lg.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=INSERTAR&codrequerimiento="+codrequerimiento+"&organismo="+organismo+"&dependencia="+dependencia+"&flagdirigido="+flagdirigido+"&ccosto="+ccostoreq+"&estado="+edoreq+"&tiporeq="+tiporeq+"&cant="+n.value+"&codunidad="+codunidad+"&descripcion="+descripcion+"&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var datos = ajax.responseText;
					if (datos.trim() != "") alert ("�"+datos+"!");
					else {
						document.getElementById(codigo).value = "";
						form.submit();						
						document.getElementById("frmentrada").submit();
					}
				}
			}
		}
	}
	return false;
}


//	FORMAS DE PAGO
function verificarFormaPago(form, accion) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagcredito").checked) var flagcredito = "S"; else var flagcredito = "N";
	var dias = document.getElementById("dias").value; dias = dias.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "") alert("�DEBE LLENAR LOS CAMPOS OBLIGATORIOS!");
	else if (flagcredito == "S" && (dias == "" || isNaN(dias) || dias == 0)) alert("�DEBE INGRESAR LOS DIAS DE VENCIMIENTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FORMAS-PAGO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&flagcredito="+flagcredito+"&dias="+dias+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert(resp);
				else cargarPagina(form, "formas_pago.php");
			}
		}
	}
	return false;
}

//	
function setDirigidoA(clasificacion) {
	var cantdetalles = new Number (document.getElementById("cantdetalles").value);
	var clasificacion = document.getElementById("clasificacion").value;
	var tiporeq = document.getElementById("tiporeq").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setDirigidoA&clasificacion="+clasificacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("|");
			if (datos[0].trim() != "")  alert(resp);
			
			if (datos[1] == "A") document.getElementById("flagalmacen").checked = true;
			else if (datos[1] == "C") document.getElementById("flagcompras").checked = true;
			
			if (cantdetalles > 0 && datos[2] != clasificacion) {
				alert("�Al cambiar el tipo de clasificaci�n se eliminaran los detalles del requerimiento!");
				document.getElementById("nrodetalles").value = 0;
				document.getElementById("cantdetalles").value = 0;
				document.getElementById("listaDetalles").innerHTML = "";
			}
			
			document.getElementById("tiporeq").value = datos[2];
			if (datos[2] == "01") {
				document.getElementById("btInsertarItem").disabled = false;
				document.getElementById("btInsertarCommodity").disabled = true;
			} else {
				document.getElementById("btInsertarItem").disabled = true;
				document.getElementById("btInsertarCommodity").disabled = false;
			}
		}
	}
}

//	
function cerrarRequerimientoDetalle(form) {
	var registro = document.getElementById("registro").value;
	
	if (registro == "") alert("�Debe seleccionar un detalle!");
	else if (confirm("�Seguro de cerrar este detalle?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS-DETALLE&accion=CERRAR-DETALLE&registro="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "0") alert(resp);
				else cargarPagina(form, "lg_requerimientos_lista_detalle.php?limit=0");
			}
		}
	}
}

//	------------
//	COTIZACIONES
//	------------
//	funcion para obtener las filas seleccionadas de invitar/cotizar proveedores y despues cargar ajax...
function invitarProveedor(form) {
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else window.open("invitar_proveedores_proceso.php?detalles="+detalles+"&formulario="+form.id, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=0, top=0, resizable=yes'); 
}

//	funcion para obtener las filas seleccionadas de invitar/cotizar proveedores y despues cargar reporte
function cuadroCotizacion(form) {
	var detalles = "";	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else window.open("pdf_cuadro_cotizaciones.php?detalles="+detalles+"&formulario="+form.id, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1000, left=0, top=0, resizable=yes'); 
}

//	funcion para obtener las filas seleccionadas de invitar/cotizar proveedores y despues cargar ajax...
function cotizarProveedor(form) {
	var detalles = "";
	var cantidad = 0;
	var val = new Array();
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) {
			cantidad++;
			detalles += n.value + ";";
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar por lo menos un detalle!");
	else {
		if (cantidad == 1) {
			window.open("cotizar_proveedores_proceso.php?detalles="+detalles+"&formulario="+form.id, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=1150, left=0, top=100, resizable=yes');
		} else {
			alert("validacion multiples detalles");
		}
	}
}

//	funcion para obtener las filas seleccionadas de invitar/cotizar proveedores y despues cargar ajax...
function verRequerimientoCotizacion(form) {
	var detalles = "";
	var con = new Number(0);
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.checked) { detalles += n.value + ";"; con++; }
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡Debe seleccionar un detalle!");
	else if (con > 1) alert("¡Debe seleccionar un solo detalle!");
	else window.open('lg_requerimientos_ver.php?registro='+detalles, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1000, left=200, top=200, resizable=no'); 
}

// funcion para insertar un proveedor  en invitar proveedores proceso
function insertarProveedorCotizacion(codproveedor, nomproveedor, codformapago, ventana_desde) {
	var form = opener.document.getElementById("frmentrada");
	var detalles = opener.document.getElementById("detalles").value;
	var formulario = opener.document.getElementById("formulario").value;
	var cantidad = opener.document.getElementById("cantidad").value;
	var nroproveedores = new Number(opener.document.getElementById("nroproveedores").value); nroproveedores++;
	var proveedores = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkproveedores") proveedores += n.title + ";";
	}
	var len = proveedores.length; len--;
	proveedores = proveedores.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion="+ventana_desde+"&detalles="+detalles+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&formulario="+formulario+"&nroproveedores="+nroproveedores+"&codformapago="+codformapago+"&proveedores="+proveedores+"&cantidad="+cantidad);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("nroproveedores").value = nroproveedores;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'selproveedor');");
				newTr.id = codproveedor;
				opener.document.getElementById("listaProveedores").appendChild(newTr);
				opener.document.getElementById(codproveedor).innerHTML = opener.document.getElementById(codproveedor).innerHTML + datos[1];
				window.close();
			}
		}
	}
}

//	funcion para quitar un proveedor de la lista
function quitarProveedor(codproveedor) {
	if (confirm("¿Desea quitar este proveedor de la lista?")) {
		var listaProveedores = document.getElementById("listaProveedores"); 
		var trProveedor = document.getElementById(codproveedor); 
		listaProveedores.removeChild(trProveedor);
		document.getElementById("selproveedor").value = "";
	}
}

//	funcion para guaradr la invitaciones de los proveedores y mostrar los reportes
function guardarInvitacionProveedores(form) {
	var detalles = document.getElementById("detalles").value;
	var formulario = document.getElementById("formulario").value;
	var flimite = document.getElementById("flimite").value;
	var condiciones = document.getElementById("condiciones").value; condiciones = condiciones.trim();
	var observaciones = document.getElementById("observaciones").value; observaciones = observaciones.trim();
	var proveedores = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkproveedores") proveedores += n.title + "|";
		if (n.name == "txtnombre") proveedores += n.value + "|";
		if (n.name == "sltformapago") proveedores += n.value + ";";
	}
	var len = proveedores.length; len--;
	proveedores = proveedores.substr(0, len);
	
	if (proveedores == "") alert("¡Debe ingresar por lo menos un proveedor!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarInvitacionProveedores&detalles="+detalles+"&proveedores="+proveedores+"&formulario="+formulario+"&flimite="+flimite+"&condiciones="+condiciones+"&observaciones="+observaciones);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					window.opener.location.href = 'invitar_proveedores.php';
					window.close();
					cargarVentana(form, 'invitar_proveedores_proceso_pdf.php?numero='+datos[1], 'height=700, width=1100, left=50, top=50, resizable=yes');
				}
			}
		}
	}
}

function eliminarInvitacion(form) {
	var cotizacion_numero = document.getElementById("selinvitacion").value;
	if (cotizacion_numero == "") alert("¡Debe seleccionar una invitaci�n!");
	else if (confirm("¡Está seguro de eliminar esta invitaci�n?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=eliminarInvitacionProveedores&cotizacion_numero="+cotizacion_numero);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else cargarPagina(form, form.action);
			}
		}
	}
}

function imprimirInvitacion(from) {
	var cotizacion_numero = document.getElementById("selinvitacion").value;
	if (cotizacion_numero == "") alert("¡Debe seleccionar una invitación!");
	else {
		window.open('pdf_invitar_proveedores_proceso_invitacion.php?cotizacion_numero='+cotizacion_numero, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=1100, left=50, top=50, resizable=yes'); 
	}
	
}

//	funcion para mostrar en otro tab las invitaciones realizadas a un requerimiento
function verInvitacionesRequerimiento () {
	var formulario = document.getElementById("formulario").value;
	var seleccionado = document.getElementById('selrequerimiento').value;
	var datos = seleccionado.split("_");
	
	if (seleccionado == "") alert("¡Debe seleccionar un detalle del requerimiento!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=verInvitacionesRequerimiento&seleccionado="+seleccionado+"&datos="+datos[1]+"&formulario="+formulario);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				document.getElementById("tab101").innerHTML = resp;
				document.getElementById('tab101').style.display='block'; 
				document.getElementById('tab100').style.display='none';
			}
		}
	}
}

//	funcion para guaradr la invitaciones de los proveedores y mostrar los reportes
function guardarCotizacionProveedoresPorItem(form) {
	var coditem = document.getElementById("coditem").value;
	var detalles = document.getElementById("detalles").value;
	var formulario = document.getElementById("formulario").value;
	var minimo = new Number(document.getElementById("minimo").value);
	flagobs = false;
	var proveedores = "";
	var obs_invitacion = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkproveedores") {
			proveedores += n.title + "|";
			var pu = new Number(setNumero(document.getElementById("pu_"+n.title).value));
			if (pu > minimo && document.getElementById("chkasig_"+n.title).checked) flagobs = true;
			obs_invitacion = document.getElementById("obs_"+n.title).value;
		}
		if (n.name == "txtnombre") proveedores += n.value + "|";
		else if (n.name == "chkasig") proveedores += n.checked + "|";
		else if (n.name == "cantidad") proveedores += setNumero(n.value) + "|";
		else if (n.name == "pu") proveedores += setNumero(n.value) + "|";
		else if (n.name == "pui") proveedores += setNumero(n.value) + "|";
		else if (n.name == "chkexon") proveedores += n.checked + "|" + n.value + "|";
		else if (n.name == "desc") proveedores += setNumero(n.value) + "|";
		else if (n.name == "descf") proveedores += setNumero(n.value) + "|";	
		else if (n.name == "chkmejor") proveedores += n.checked + "|";	
		else if (n.name == "sltformapago") proveedores += n.value + "|";
		else if (n.name == "finvitacion") proveedores += n.value + "|";
		else if (n.name == "flimite") proveedores += n.value + "|";
		else if (n.name == "condiciones") proveedores += n.value + "|";
		else if (n.name == "observaciones") proveedores += n.value + "|";
		else if (n.name == "dias") proveedores += n.value + "|";
		else if (n.name == "validez") proveedores += n.value + "|";
		else if (n.name == "nro_cotizacion") proveedores += n.value + ";";
	}
	var len = proveedores.length; len--;
	proveedores = proveedores.substr(0, len);
		
	if (proveedores == "") alert("�Debe ingresar por lo menos un proveedor!");
	else {
		if (flagobs) var obs = prompt('Ingrese porque no seleccciono el mejor precio', obs_invitacion); else var obs = obs_invitacion;
				
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCotizacionProveedoresPorItem&detalles="+detalles+"&proveedores="+proveedores+"&formulario="+formulario+"&obs="+obs);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					window.close();
				}
			}
		}
	}
}

//	funcion para actualizar los montos de la cotizacion
function setTotalesCotizacion(codproveedor) {
 	var form = document.getElementById("frmentrada");
	var cantidad = new Number(setNumero(document.getElementById("cantidad_"+codproveedor).value));
	var pu = new Number(setNumero(document.getElementById("pu_"+codproveedor).value));
	//var pui = new Number(setNumero(document.getElementById("pui_"+codproveedor).value));
	var desc = new Number(setNumero(document.getElementById("desc_"+codproveedor).value));
	var descf = new Number(setNumero(document.getElementById("descf_"+codproveedor).value));
	
	if (document.getElementById("chkexon_"+codproveedor).checked) 
		var impuesto = new Number(0.00);
	else 
		var impuesto = new Number(setNumero(document.getElementById("chkexon_"+codproveedor).value));
	
	if (desc != 0) {
		var precio_unitario_con_descuento = pu - (pu * desc / 100);
	} else {
		var precio_unitario_con_descuento = pu - descf;
	}
		
	//	calculo el precio unitario con impuesto
	var precio_unitario_con_impuesto = pu + (pu * impuesto / 100);
	var precio_unitario_final = precio_unitario_con_descuento + (precio_unitario_con_descuento * impuesto / 100);
	var total = precio_unitario_final * cantidad;
	
	//	actualizo los montos totales
	document.getElementById("pui_"+codproveedor).value = setNumeroFormato(redondear(precio_unitario_con_impuesto, 2), 2, ".", ",");
	document.getElementById("spufinal_"+codproveedor).innerHTML = setNumeroFormato(redondear(precio_unitario_final, 2), 2, ".", ",");
	document.getElementById("smontofinal_"+codproveedor).innerHTML = setNumeroFormato(redondear(total, 2), 2, ".", ",");
	document.getElementById("monto_comparar_"+codproveedor).innerHTML = setNumeroFormato(redondear(precio_unitario_final, 2), 2, ".", ",");
	
	
	var minimo = new Number(precio_unitario_final);
	
	//	verifico el monto minimo
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkproveedores") {
			var proveedor = n.title;
			var precio = new Number(setNumero(document.getElementById("spufinal_"+proveedor).innerHTML));
			
			if (precio > 0 && precio <= minimo) minimo = precio;
		}
	}
	document.getElementById("minimo").value = minimo;
	
	minimo = new Number(redondear(minimo, 2));
	//	chequeo el mejor precio
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkproveedores") {
			var proveedor = n.title;
			var precio = setNumero(document.getElementById("spufinal_"+proveedor).innerHTML);
			
			if (precio == 0 || precio > minimo) {
				document.getElementById("chkmejor_"+proveedor).checked = false;
				document.getElementById("chkasig_"+proveedor).checked = false;
			} else {
				document.getElementById("chkmejor_"+proveedor).checked = true;
				document.getElementById("chkasig_"+proveedor).checked = true;
			}
		}
	}
}

//	funcion para mostrar los detalles por invitacion
function verProveedorDetallesRequerimiento(flagasignado) {
	var selinvitacion = document.getElementById("selinvitacion").value;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=verProveedorDetallesRequerimiento&selinvitacion="+selinvitacion+"&flagasignado="+flagasignado);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			document.getElementById("trDetalle").innerHTML = resp;
		}
	}
}

//	funcion para abrir la cotizacion por invitacion
function cotizarInvitacion() {
	var selinvitacion = document.getElementById('selinvitacion').value;
	if (selinvitacion == "") alert("¡Debe seleccionar una invitación!");
	else {
		window.open('cotizar_proveedores_invitacion.php?selinvitacion='+selinvitacion, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=1150, left=0, top=100, resizable=yes');
	}
	
}

//	funcion para guaradr la invitaciones de los proveedores y mostrar los reportes
function guardarCotizacionProveedoresPorInvitacion(form) {
	var codproveedor = document.getElementById("codproveedor").value;
	var descuento = setNumero(document.getElementById("descuento").value);
	var cotizacion = document.getElementById("cotizacion").value;
	var fcotizacion = document.getElementById("fcotizacion").value;
	var descuento_fijo = setNumero(document.getElementById("descuento_fijo").value);
	var frecepcion = document.getElementById("frecepcion").value;
	var forma_pago = document.getElementById("forma_pago").value;
	var validez = setNumero(document.getElementById("validez").value);
	var fapertura = document.getElementById("fapertura").value;
	var plazo = setNumero(document.getElementById("plazo").value);
	
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalle") detalles += n.id + "|";
		if (n.name == "cantidad") detalles += setNumero(n.value) + "|";
		if (n.name == "pusi") detalles += setNumero(n.value) + "|";
		if (n.name == "chkasig") detalles += n.checked + "|";
		if (n.name == "chkasiginicial") detalles += n.checked + "|";
		if (n.name == "chkexon") detalles += n.checked + "|";
		if (n.name == "puci") detalles += setNumero(n.value) + "|";
		if (n.name == "desc") detalles += setNumero(n.value) + "|";
		if (n.name == "descf") detalles += setNumero(n.value) + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (frecepcion != "" && !esFecha(frecepcion)) alert("¡Fecha de recepci�n incorrecta!");
	else if (fcotizacion != "" && !esFecha(fcotizacion)) alert("¡Fecha de cotización incorrecta!");
	else if (fapertura != "" && !esFecha(fapertura)) alert("¡Fecha de apertura incorrecta!");
	else if (isNaN(descuento)) alert("¡Porcentaje de descuento incorrecto!");
	else if (isNaN(descuento_fijo)) alert("¡Monto de descuento incorrecto!");
	else if (isNaN(validez)) alert("¡Validez de oferta incorrecta!");
	else if (isNaN(plazo)) alert("¡Plazo de entrega incorrecta!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=guardarCotizacionProveedoresPorInvitacion&detalles="+detalles+"&codproveedor="+codproveedor+"&descuento="+descuento+"&cotizacion="+cotizacion+"&fcotizacion="+fcotizacion+"&descuento_fijo="+descuento_fijo+"&frecepcion="+frecepcion+"&forma_pago="+forma_pago+"&validez="+validez+"&fapertura="+fapertura+"&plazo="+plazo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					window.opener.location.href = "invitar_proveedores_listar.php";
					window.close();
				}
			}
		}
	}
}

//	funcion para actualizar los montos de la cotizacion
function setTotalesCotizacionProveedor(secuencia) {
 	var form = document.getElementById("frmentrada");
	var cantidad = new Number(setNumero(document.getElementById("cantidad_"+secuencia).value));
	var pusi = new Number(setNumero(document.getElementById("pusi_"+secuencia).value));
	var desc = new Number(setNumero(document.getElementById("desc_"+secuencia).value));
	var descf = new Number(setNumero(document.getElementById("descf_"+secuencia).value));
	var total_old = new Number(setNumero(document.getElementById("total_"+secuencia).innerHTML));
	var total_general = new Number(setNumero(document.getElementById("total").innerHTML));
	
	if (document.getElementById("chkexon_"+secuencia).checked) 
		var impuesto = new Number(0.00);
	else 
		var impuesto = new Number(setNumero(document.getElementById("chkexon_"+secuencia).value));
	
	//	si tiene impuesto caluculo el precio unitario con impuesto
	if (impuesto == 0) var puci = pusi;
	else var puci = pusi + (pusi * impuesto / 100);
	
	var puci = new Number(setNumeroFormato(puci, 2, "", "."));
	
	//	calculo el total de los precion uniotarios con descuentos
	if (desc != 0) {
		var pusicd = pusi - (pusi * desc / 100);
		var pucicd = puci - (puci * desc / 100);
	} else {
		var pusicd = pusi - descf;
		var pucicd = puci - descf;
	}
	
	var pucicd = new Number(setNumeroFormato(pucicd, 2, "", "."));
	
	//	calcul el total con impuesto y descuento
	var total = pucicd * cantidad;
	total_general += (total - total_old);
	
	//	actualizo los montos totales
	document.getElementById("puci_"+secuencia).value = setNumeroFormato(puci, 2, ".", ",");
	document.getElementById("pusicd_"+secuencia).innerHTML = setNumeroFormato(pusicd, 2, ".", ",");
	document.getElementById("pucicd_"+secuencia).innerHTML = setNumeroFormato(pucicd, 2, ".", ",");
	document.getElementById("total_"+secuencia).innerHTML = setNumeroFormato(total, 2, ".", ",");
	document.getElementById("total").innerHTML = setNumeroFormato(total_general, 2, ".", ",");
}

//	funcion para actualizar los montos de la cotizacion
function setTotalesCotizacionProveedorDescuentos(form, id) {
	var descuento = new Number(setNumero(document.getElementById("descuento").value));
	var descuento_fijo = new Number(setNumero(document.getElementById("descuento_fijo").value));
	
	if (descuento > 0 && id == "descuento") {
		document.getElementById("descuento_fijo").value = "0,00";
		descuento_fijo = 0;
	} else {
		document.getElementById("descuento").value = "0,00";
		descuento = 0;
	}
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalle") var secuencia = n.id;
		
		if (n.name == "desc" && descuento > 0) {
			n.value = setNumeroFormato(descuento, 2, ".", ",");
			document.getElementById("descf_"+secuencia).value = "0,00";
			setTotalesCotizacionProveedor(secuencia);
		}
		
		if (n.name == "descf" && descuento_fijo > 0) {
			n.value = setNumeroFormato(descuento_fijo, 2, ".", ",");
			document.getElementById("desc_"+secuencia).value = "0,00";
			setTotalesCotizacionProveedor(secuencia);
		}
	}
}



//	------------
//	ORDENES
//	------------
//	funcion  para generar ordenes de compra pendientes
function generarOrdenPendiente(cotizacion_numero) {
	var organismo = document.getElementById("organismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var formapago = document.getElementById("formapago").value;
	var codservicio = document.getElementById("codservicio").value;
	var almacen_entrega = document.getElementById("almacen_entrega").value;
	var almacen_ingreso = document.getElementById("almacen_ingreso").value;
	var nomcontacto = document.getElementById("nomcontacto").value.trim();
	var faxcontacto = document.getElementById("faxcontacto").value.trim();
	var entregaren = document.getElementById("entregaren").value.trim();
	var direccion = document.getElementById("direccion").value.trim();
	var instruccion = document.getElementById("instruccion").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var tipo_orden = document.getElementById("tipo_orden").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	
	var monto_afecto = new Number (setNumero(document.getElementById("monto_afecto").value.trim()));
	var monto_noafecto = new Number (setNumero(document.getElementById("monto_noafecto").value.trim()));
	var monto_bruto = new Number (setNumero(document.getElementById("monto_bruto").value.trim()));
	var monto_impuestos = new Number (setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number (setNumero(document.getElementById("monto_total").value.trim()));
	var monto_pendiente = new Number (setNumero(document.getElementById("monto_pendiente").value.trim()));
	var monto_otros = new Number (setNumero(document.getElementById("monto_otros").value.trim()));
	
	if (fentrega == "" || nomproveedor == "" || formapago == "" || almacen_entrega == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (!esFecha(fentrega)) alert("¡Fecha de entrega incorrecta!");
	else if (confirm("¿Está seguro de generar esta orden?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=generarOrdenPendiente&cotizacion_numero="+cotizacion_numero+"&organismo="+organismo+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&fentrega="+fentrega+"&formapago="+formapago+"&codservicio="+codservicio+"&almacen_entrega="+almacen_entrega+"&almacen_ingreso="+almacen_ingreso+"&nomcontacto="+nomcontacto+"&faxcontacto="+faxcontacto+"&entregaren="+entregaren+"&direccion="+direccion+"&instruccion="+instruccion+"&observaciones="+observaciones+"&clasificacion="+clasificacion+"&dependencia="+dependencia+"&monto_afecto="+monto_afecto+"&monto_noafecto="+monto_noafecto+"&monto_bruto="+monto_bruto+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&monto_pendiente="+monto_pendiente+"&monto_otros="+monto_otros+"&tipo_orden="+tipo_orden+"&ccosto="+ccosto+"&dias_entrega="+dias_entrega);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				
				if (datos[0].trim() != "") alert(datos[0]);
				else if (datos[1].trim() != "") {
					alert(datos[1]);
					location.href = "generar_ordenes_pendientes.php?filtrar=DEFAULT";
				}
				else alert(resp);
			}
		}
	}
	return false;
}

//	funcion  para generar ordenes de servicio pendientes
function generarOrdenPendienteServicio(cotizacion_numero) {
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value.trim();
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var formapago = document.getElementById("formapago").value;
	var codservicio = document.getElementById("codservicio").value;
	var descripcion = document.getElementById("descripcion").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var tipo_orden = document.getElementById("tipo_orden").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	
	var monto_bruto = new Number (setNumero(document.getElementById("monto_bruto").value.trim()));
	var monto_impuestos = new Number (setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number (setNumero(document.getElementById("monto_total").value.trim()));
	
	if (fentrega == "" || nomproveedor == "" || formapago == "" || descripcion == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (!esFecha(fentrega)) alert("¡Fecha de entrega incorrecta!");
	else if (confirm("¿Esta seguro de generar esta orden?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=generarOrdenPendiente&cotizacion_numero="+cotizacion_numero+"&organismo="+organismo+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&fentrega="+fentrega+"&formapago="+formapago+"&codservicio="+codservicio+"&observaciones="+observaciones+"&clasificacion="+clasificacion+"&dependencia="+dependencia+"&monto_bruto="+monto_bruto+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&tipo_orden="+tipo_orden+"&ccosto="+ccosto+"&dias_entrega="+dias_entrega+"&descripcion="+descripcion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				
				if (datos[0].trim() != "") alert(datos[0]);
				else if (datos[1].trim() != "") {
					alert(datos[1]);
					location.href = "generar_ordenes_pendientes.php?filtrar=DEFAULT";
				}
				else alert(resp);
			}
		}
	}
	return false;
}

//	funcion para validar el formulario de la orden de compra y guardar o modificar
function verificarOrdenCompra(form, accion) {
	var organismo = document.getElementById("organismo").value;
	var nroorden = document.getElementById("nroorden").value;
	var estado = document.getElementById("estado").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var clasificacion = document.getElementById("clasificacion").value;
	var formapago = document.getElementById("formapago").value;
	var codservicio = document.getElementById("codservicio").value;
	var almacen_entrega = document.getElementById("almacen_entrega").value;
	var almacen_ingreso = document.getElementById("almacen_ingreso").value;
	var nomcontacto = document.getElementById("nomcontacto").value.trim();
	var faxcontacto = document.getElementById("faxcontacto").value.trim();
	var entregaren = document.getElementById("entregaren").value.trim();
	var direccion = document.getElementById("direccion").value.trim();
	var instruccion = document.getElementById("instruccion").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var detallada = document.getElementById("detallada").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var impuesto = document.getElementById("impuesto").value.trim();
	var total_impuesto = document.getElementById("monto_impuestos").value.trim();
	var btInsertarItem = !document.getElementById("btInsertarItem").disabled;
	var btInsertarCommodity = !document.getElementById("btInsertarCommodity").disabled;
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcodunidad") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			if (cant <= 0) { error_detalles = "¡ERROR: No puede ingresar items con cantidad en cero!"; break; } 
			else detalles += setNumero(n.value) + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			if (pu <= 0) { error_detalles = "¡ERROR: No puede ingresar items con precio unitario en cero!"; break; } 
			else detalles += setNumero(n.value) + "|";
		}
		else if (n.name == "txtdescp") detalles += setNumero(n.value) + "|";
		else if (n.name == "txtdescf") detalles += setNumero(n.value) + "|";
		else if (n.name == "chkexon") detalles += n.checked + "|";
		else if (n.name == "txtfentrega") detalles += n.value + "|";
		else if (n.name == "txtccostos") detalles += n.value + "|";
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + "|";
		else if (n.name == "txtobs") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (organismo == "" || codproveedor == "" || nomproveedor == "" || dias_entrega == "" || formapago == "" || codservicio == "" || almacen_entrega == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle a la orden!");
	else if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDENES-COMPRA&accion="+accion+"&organismo="+organismo+"&nroorden="+nroorden+"&estado="+estado+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&fentrega="+fentrega+"&clasificacion="+clasificacion+"&formapago="+formapago+"&codservicio="+codservicio+"&almacen_entrega="+almacen_entrega+"&almacen_ingreso="+almacen_ingreso+"&nomcontacto="+nomcontacto+"&faxcontacto="+faxcontacto+"&entregaren="+entregaren+"&direccion="+direccion+"&instruccion="+instruccion+"&observaciones="+observaciones+"&detallada="+detallada+"&razon="+razon+"&detalles="+detalles+"&btInsertarItem="+btInsertarItem+"&btInsertarCommodity="+btInsertarCommodity+"&impuesto="+impuesto+"&dias_entrega="+dias_entrega+"&total_impuesto="+total_impuesto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				
				if (datos[0].trim() != "") alert(datos[0]);
				else if (accion == "INSERTAR") {					
					alert("Nueva Orden de Compra " + datos[1] + " ha sido creada");
					cargarPagina(form, 'ordenes_compras.php?limit=0');
				}
				else cargarPagina(form, 'ordenes_compras.php?limit=0');
			}
		}
	}
	return false;
}

//	funcion para obtener el tipo de requerimiento de la clasificacion y bloquear o desbloquear la lista de items o commodities
function setClasificacionTipoReq(clasificacion) {
	if (clasificacion == "") {
		document.getElementById("btInsertarItem").disabled = true;
		document.getElementById("btInsertarCommodity").disabled = true;
		document.getElementById("listaDetalles").innerHTML =  "";
		document.getElementById("nrodetalles").value = 0;
		document.getElementById("tiporeq_clasificacion").value = "";
	} else {
		var ejecutar_ajax = false;
		
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=setClasificacionTipoReq&clasificacion="+clasificacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					if (document.getElementById("tiporeq_clasificacion").value != "") {
						if (document.getElementById("tiporeq_clasificacion").value != datos[1]) {
							if (confirm("Al cambiar la clasificaci�n se eliminar�n todos los detalles de la orden.\n�Est� seguro?")) {
								ejecutar_ajax = true
								document.getElementById("listaDetalles").innerHTML =  "";
								document.getElementById("nrodetalles").value = 0;
							}
						} else ejecutar_ajax = false;
					} else ejecutar_ajax = true;
					
					if (ejecutar_ajax) {
						if (datos[1] == "01") {
								document.getElementById("btInsertarItem").disabled = false;
								document.getElementById("btInsertarCommodity").disabled = true;
						} else {
								document.getElementById("btInsertarItem").disabled = true;
								document.getElementById("btInsertarCommodity").disabled = false;
						}
						document.getElementById("tiporeq_clasificacion").value = datos[1];
					}
				}
			}
		}
	}
}

//	funcion para mostrar la ventana de disponibilidad presupuestaria
function verDisponibilidadItems(tipoorden) {
	var nroorden = document.getElementById("nroorden").value;
	var organismo = document.getElementById("organismo").value;
	var total_impuesto = new Number(setNumero(document.getElementById("monto_impuestos").value));
	
	//	recorro los detalles para obtener los valores de la partida
	var form = document.getElementById("frmdetalles");
	var detalles = "";	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			detalles += cant + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			detalles += pu + "|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (document.getElementById("btInsertarItem").disabled) var tabla = "commodity"; else var tabla = "item";
	
	window.open("disponibilidad_presupuestaria_items.php?detalles="+detalles+"&tabla="+tabla+"&organismo="+organismo+"&total_impuesto="+total_impuesto+"&nroorden="+nroorden+"&tipoorden="+tipoorden, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=1100, left=50, top=50, resizable=yes");
}

//	funcion para insertar un item/commodity en detalle de la orden de compra
function insertarItemOrden(codigo, accion) {
	var ventana = document.getElementById("ventana").value;
	var tabla = document.getElementById("tabla").value;
	var form = opener.document.getElementById("frmdetalles");
	var nroorden = opener.document.getElementById("nroorden").value;
	var organismo = opener.document.getElementById("organismo").value;
	var total_impuesto = new Number(setNumero(opener.document.getElementById("monto_impuestos").value));
	var fentrega = opener.document.getElementById("fentrega").value;
	var observaciones = opener.document.getElementById("observaciones").value;
	var nrodetalles = new Number(opener.document.getElementById("nrodetalles").value); nrodetalles++;
	var cantdetalles = new Number(opener.document.getElementById("cantdetalles").value); cantdetalles++;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			detalles += cant + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			detalles += pu + "|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemOrden&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&fentrega="+fentrega+"&observaciones="+observaciones+"&organismo="+organismo+"&total_impuesto="+total_impuesto+"&nroorden="+nroorden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				if (accion == "insertarItemOrden")
					opener.document.getElementById("btInsertarCommodity").disabled = true;
				else
					opener.document.getElementById("btInsertarItem").disabled = true;
				
				opener.document.getElementById("nrodetalles").value = nrodetalles;
				opener.document.getElementById("cantdetalles").value = cantdetalles;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
				newTr.id = datos[2];
				opener.document.getElementById("listaDetalles").appendChild(newTr);
				opener.document.getElementById(datos[2]).innerHTML = opener.document.getElementById(datos[2]).innerHTML + datos[1];
				opener.document.getElementById("listaPartidas").innerHTML = datos[3];
				opener.document.getElementById("listaCuentas").innerHTML = datos[4];
				window.close();
			}
		}
	}
}

//	funcion para insertar un item/commodity en detalle de la orden de compra
function insertarItemRequerimiento(codigo, accion) {
	var ventana = document.getElementById("ventana").value;
	var tabla = document.getElementById("tabla").value;
	var form = opener.document.getElementById("frmdetalles");
	var ccosto = opener.document.getElementById("ccosto").value;
	if (opener.document.getElementById("flagcompras").checked) var dirigidoa = "Compras"; else dirigidoa = "Almacen";
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
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemRequerimiento&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&ccosto="+ccosto+"&dirigidoa="+dirigidoa);
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
				newTr.id = datos[2];
				opener.document.getElementById("listaDetalles").appendChild(newTr);
				opener.document.getElementById(datos[2]).innerHTML = datos[1];
				window.close();
			}
		}
	}
}

//	funcion para insertar un item en una transaccion
function insertarItemTransaccion(codigo, accion) {
	var ventana = document.getElementById("ventana").value;
	var tabla = document.getElementById("tabla").value;
	var almacen = opener.document.getElementById("almacen").value;
	var form = opener.document.getElementById("frmdetalles");
	var flagmanual = opener.document.getElementById("flagmanual").checked;
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
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemTransaccion&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&almacen="+almacen+"&flagmanual="+flagmanual);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
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

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleOrden(coddetalle) {
	if (confirm("¿Desea quitar este detalle de la lista?")) {
		var cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
		document.getElementById("cantdetalles").value = cantdetalles;
		
		if (cantdetalles == 0) {
			document.getElementById("btInsertarCommodity").disabled = false;
			document.getElementById("btInsertarItem").disabled = false;
		}
		setTotalesOrden();
	}
}

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleOrdenServicio(coddetalle) {
	if (confirm("¿Desea quitar este detalle de la lista?")) {
		var cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
		document.getElementById("cantdetalles").value = cantdetalles;
		
		if (cantdetalles == 0) {
			document.getElementById("btInsertarCommodity").disabled = false;
			document.getElementById("btInsertarItem").disabled = false;
		}
		setTotalesOrdenServicio();
	}
}

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleRequerimiento(coddetalle) {
	if (confirm("¿Desea quitar este detalle de la lista?")) {
		var cantdetalles = new Number(document.getElementById("cantdetalles").value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
		document.getElementById("cantdetalles").value = cantdetalles;
	}
}

//	funcion para actualizar los montos de la orden
function setTotalesOrden(id) {
	var igv = new Number(document.getElementById("impuesto").value);
	if (id) {
		var cantidad = new Number(setNumero(document.getElementById("txtcantidad_"+id).value));
		var unitario = new Number(setNumero(document.getElementById("txtpu_"+id).value));
		var descporc = new Number(setNumero(document.getElementById("txtdescp_"+id).value));
		var descfijo = new Number(setNumero(document.getElementById("txtdescf_"+id).value));
		if (document.getElementById("chkexon_"+id).checked) var impuesto = new Number(0.00);
		else var impuesto = new Number(document.getElementById("impuesto").value);
		
		if (descporc > 0) var monto_unitario = unitario - (unitario * descporc / 100);
		else if (descfijo > 0) var monto_unitario = unitario - (descfijo);
		else var monto_unitario = unitario;		
		monto_unitario = monto_unitario + (monto_unitario * impuesto / 100);
		var total = cantidad * monto_unitario;		
		document.getElementById("spufinal_"+id).innerHTML = setNumeroFormato(monto_unitario, 2, ".", ",");
		document.getElementById("smontofinal_"+id).innerHTML = setNumeroFormato(total, 2, ".", ",");
	}
	
	var monto_afecto = new Number(0.00);
	var monto_noafecto = new Number(0.00);
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "txtcantidad") var cantidad = setNumero(n.value);
		if (n.name == "txtpu") var pu = setNumero(n.value);
		if (n.name == "txtdescp") var descp = setNumero(n.value);
		if (n.name == "txtdescf") var descf = setNumero(n.value);
		if (n.name == "chkexon") {
			var flagexon = n.checked;
			
			if (descp > 0) var monto_unitario = pu - (pu * descp / 100);
			else if (descf > 0) var monto_unitario = pu - (descf);
			else var monto_unitario = pu;
			var precio_cantidad = cantidad * monto_unitario;
			
			if (flagexon) monto_noafecto += precio_cantidad;
			else monto_afecto += precio_cantidad;
		}
	}
	var monto_bruto = monto_afecto + monto_noafecto;
	var monto_impuesto = monto_afecto * igv / 100;
	var monto_total = monto_bruto + monto_impuesto;
		
	document.getElementById("monto_afecto").value = setNumeroFormato(monto_afecto, 2, ".", ",");
	document.getElementById("monto_noafecto").value = setNumeroFormato(monto_noafecto, 2, ".", ",");
	document.getElementById("monto_bruto").value = setNumeroFormato(monto_bruto, 2, ".", ",");
	document.getElementById("monto_impuestos").value = setNumeroFormato(monto_impuesto, 2, ".", ",");
	document.getElementById("monto_total").value = setNumeroFormato(monto_total, 2, ".", ",");
	document.getElementById("monto_pendiente").value = setNumeroFormato(monto_total, 2, ".", ",");
	document.getElementById("total").innerHTML = setNumeroFormato(monto_total, 2, ".", ",");
	
	//	imprimo la distribucion de la orden
	var form = document.getElementById("frmdetalles");
	var nroorden = document.getElementById("nroorden").value;
	var organismo = document.getElementById("organismo").value;
	var total_impuesto = new Number(setNumero(document.getElementById("monto_impuestos").value));
	if (document.getElementById("btInsertarItem").disabled) var tabla = "commodity"; else var tabla = "item";
	var detalles = "";	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			detalles += cant + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			detalles += pu + "|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=imprimirDistribucionOrden&organismo="+organismo+"&total_impuesto="+total_impuesto+"&detalles="+detalles+"&tabla="+tabla+"&nroorden="+nroorden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			document.getElementById("listaPartidas").innerHTML = datos[0];
			document.getElementById("listaCuentas").innerHTML = datos[1];
		}
	}
}

//	funcion para seleccionar de la lista de proveedores y colocar su forma de pago y el tipo dervicio
function selListadoOrdenCompra(busqueda, cod, nom, formapago, codservicio, nomservicio, impuesto, tipopago, dias_pagar, fhasta) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	opener.document.getElementById("formapago").value=formapago;
	opener.document.getElementById("codservicio").value=codservicio;
	opener.document.getElementById("nomservicio").value=nomservicio;
	opener.document.getElementById("impuesto").value=impuesto;
	opener.document.getElementById("tipopago").value=tipopago;
	opener.document.getElementById("dias_pagar").value=dias_pagar;
	opener.document.getElementById("fhasta").value=fhasta;
	window.close();
}

function selListadoDetalle(descripcion, cod, nom) {
	var codigo = document.getElementById("registro").value;
	var id = document.getElementById("detalle").value;	
	var idcod = cod+"_"+id;
	var idnom = nom+"_"+id;
	
	opener.document.getElementById(idcod).value = codigo;
	opener.document.getElementById(idcod).title = descripcion;
	opener.document.getElementById(idnom).value = descripcion;
	window.close();
}

function seleccionarCCosto(form) {
	var detalle = document.getElementById("seldetalle").value;
	
	if (detalle == "") alert("¡Debe seleccionar un detalle!");
	else {
		cargarVentana(form, 'listado_centro_costos.php?cod=txtccostos&nom=txtnomccostos&ventana=lista&detalle='+detalle, 'height=600, width=1100, left=50, top=50, resizable=yes');
	}
}

//	funcion para validar el formulario de la orden de servicio y guardar o modificar
function verificarOrdenServicio(form, accion) {
	var organismo = document.getElementById("organismo").value;
	var dependencia = document.getElementById("dependencia").value;
	var nroorden = document.getElementById("nroorden").value;
	var estado = document.getElementById("estado").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var nrointerno = document.getElementById("nrointerno").value.trim();
	var dentrega = document.getElementById("dentrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var codservicio = document.getElementById("codservicio").value;
	var tipopago = document.getElementById("tipopago").value;
	var fdocumento = document.getElementById("fdocumento").value.trim();
	var formapago = document.getElementById("formapago").value;
	var dias_pagar = document.getElementById("dias_pagar").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var descripcion_adicional = document.getElementById("descripcion_adicional").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var impuesto = document.getElementById("impuesto").value.trim();
	var fdesde = document.getElementById("fdesde").value.trim();
	var fhasta = document.getElementById("fhasta").value.trim();
	var codccosto = document.getElementById("codccosto").value;
	var detalles = "";
	var error_detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		if (n.name == "txtdescripcion") detalles += n.value + "|";
		if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			if (cant <= 0) { error_detalles = "¡ERROR: No puede ingresar items con cantidad en cero!"; break; } 
			else detalles += setNumero(n.value) + "|";
		}
		if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			if (pu <= 0) { error_detalles = "¡ERROR: No puede ingresar items con precio unitario en cero!"; break; } 
			else detalles += setNumero(n.value) + "|";
		}
		if (n.name == "txtfesperada") detalles += n.value + "|";
		if (n.name == "txtccostos") detalles += n.value + "|";
		if (n.name == "txtactivo") detalles += n.value + "|";
		if (n.name == "chkterminado") detalles += n.checked + "|";
		if (n.name == "codpartida") detalles += n.value + "|";
		if (n.name == "codcuenta") detalles += n.value + "|";
		if (n.name == "txtobs") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (organismo == "" || codproveedor == "" || nomproveedor == "" || fentrega == "" || dentrega == "" || formapago == "" || codservicio == "" || tipopago == "" || fdocumento == "" || codccosto == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (!esFecha(fentrega)) alert("¡Formato de fecha de entrega incorrecta!");
	else if (!esFecha(fdocumento)) alert("¡Formato de fecha de entrega incorrecta!");
	else if (!esFecha(fdesde) || !esFecha(fhasta)) alert("¡Formato de fecha incorrecta!");
	else if (isNaN(dentrega)) alert("¡Dias de entrega debe ser numérico!");
	else if (dias_pagar != "" && isNaN(dias_pagar)) alert("¡Dias de entrega debe ser númerico!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un detalle a la orden!");
	else if (error_detalles != "") alert(error_detalles);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDENES-SERVICIO&accion="+accion+"&organismo="+organismo+"&nroorden="+nroorden+"&estado="+estado+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&fentrega="+fentrega+"&dentrega="+dentrega+"&formapago="+formapago+"&codservicio="+codservicio+"&nrointerno="+nrointerno+"&fdocumento="+fdocumento+"&dias_pagar="+dias_pagar+"&descripcion="+descripcion+"&descripcion_adicional="+descripcion_adicional+"&observaciones="+observaciones+"&razon="+razon+"&detalles="+detalles+"&impuesto="+impuesto+"&codccosto="+codccosto+"&tipopago="+tipopago+"&dependencia="+dependencia+"&fdesde="+fdesde+"&fhasta="+fhasta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|");
				
				if (datos[0].trim() != "") alert(datos[0]);
				else if (accion == "INSERTAR") {					
					alert("Nueva Orden de Servicio " + datos[1] + " ha sido creada");
					cargarPagina(form, 'ordenes_servicios.php?limit=0');
				}
				else cargarPagina(form, 'ordenes_servicios.php?limit=0');
			}
		}
	}
	return false;
}

//	funcion para insertar un item/commodity en detalle de la orden de servicio
function insertarItemOrdenServicio(codigo, accion, codccosto, descripcion, fentrega) {
	var ventana = document.getElementById("ventana").value;
	var tabla = document.getElementById("tabla").value;
	var form = opener.document.getElementById("frmdetalles");
	var nroorden = opener.document.getElementById("nroorden").value;
	var organismo = opener.document.getElementById("organismo").value;
	var total_impuesto = new Number(setNumero(opener.document.getElementById("monto_impuestos").value));
	var nrodetalles = new Number(opener.document.getElementById("nrodetalles").value); nrodetalles++;
	var cantdetalles = new Number(opener.document.getElementById("cantdetalles").value); cantdetalles++;
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			detalles += cant + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			detalles += pu + "|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemOrdenServicio&codigo="+codigo+"&ventana="+ventana+"&tabla="+tabla+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&codccosto="+codccosto+"&descripcion="+descripcion+"&fentrega="+fentrega+"&organismo="+organismo+"&total_impuesto="+total_impuesto+"&nroorden="+nroorden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("nrodetalles").value = nrodetalles;
				opener.document.getElementById("cantdetalles").value = cantdetalles;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
				newTr.id = datos[2];
				opener.document.getElementById("listaDetalles").appendChild(newTr);
				opener.document.getElementById(datos[2]).innerHTML = opener.document.getElementById(datos[2]).innerHTML + datos[1];
				opener.document.getElementById("listaPartidas").innerHTML = datos[3];
				opener.document.getElementById("listaCuentas").innerHTML = datos[4];
				window.close();
			}
		}
	}
}

//	funcion para actualizar los montos de la orden
function setTotalesOrdenServicio(id) {
	var impuesto = new Number(document.getElementById("impuesto").value);
	var igv = new Number(document.getElementById("impuesto").value);
	if (id) {
		var cantidad = new Number(setNumero(document.getElementById("txtcantidad_"+id).value));
		var unitario = new Number(setNumero(document.getElementById("txtpu_"+id).value));	
		var monto_unitario = unitario + (unitario * impuesto / 100);
		var total = cantidad * monto_unitario;		
		document.getElementById("smontofinal_"+id).innerHTML = setNumeroFormato(total, 2, ".", ",");
	}
	
	var monto_afecto = new Number(0.00);
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "txtcantidad") var cantidad = setNumero(n.value);
		if (n.name == "txtpu") { var pu = setNumero(n.value); monto_afecto += (pu * cantidad); }
	}
	var monto_bruto = monto_afecto;
	var monto_impuesto = monto_afecto * igv / 100;
	var monto_total = monto_bruto + monto_impuesto;
	
	document.getElementById("monto_original").value = setNumeroFormato(monto_bruto, 2, ".", ",");
	document.getElementById("monto_impuestos").value = setNumeroFormato(monto_impuesto, 2, ".", ",");
	document.getElementById("monto_total").value = setNumeroFormato(monto_total, 2, ".", ",");
	document.getElementById("total").innerHTML = setNumeroFormato(monto_total, 2, ".", ",");
	
	//	imprimo la distribucion de la orden
	var form = document.getElementById("frmdetalles");
	var nroorden = document.getElementById("nroorden").value;
	var organismo = document.getElementById("organismo").value;
	var total_impuesto = new Number(setNumero(document.getElementById("monto_impuestos").value));
	var tabla = "commodity";
	var detalles = "";	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + "|";
		else if (n.name == "txtdescripcion") detalles += n.value + "|";
		else if (n.name == "txtcantidad") {
			var cant = new Number(setNumero(n.value));
			detalles += cant + "|";
		}
		else if (n.name == "txtpu") {
			var pu = new Number(setNumero(n.value));
			detalles += pu + "|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=imprimirDistribucionOrdenServicio&organismo="+organismo+"&total_impuesto="+total_impuesto+"&detalles="+detalles+"&tabla="+tabla+"&nroorden="+nroorden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			document.getElementById("listaPartidas").innerHTML = datos[0];
			document.getElementById("listaCuentas").innerHTML = datos[1];
		}
	}
}

//	funcion para confirmar orden de servicio
function confirmarOrdenServicio() {
	var codorganismo = document.getElementById("codorganismo").value;
	var nroorden = document.getElementById("nroorden").value;
	var secuencia = document.getElementById("secuencia").value;
	var ftermino = document.getElementById("ftermino").value.trim();
	var cantidad_pedida = new Number(setNumero(document.getElementById("cantidad_pedida").value.trim()));
	var cantidad_recibida = new Number(setNumero(document.getElementById("cantidad_recibida").value.trim()));
	var cantidad_recibir = new Number(setNumero(document.getElementById("cantidad_recibir").value.trim()));
	var saldo = new Number(setNumero(document.getElementById("saldo").value.trim()));
	
	if (ftermino == "") alert("¡ERROR: Debe ingresar la fecha de termino real!");
	else if (!esFecha(ftermino)) alert("¡Fecha de término incorrecta!");
	else if (isNaN(cantidad_recibir) || cantidad_recibir == "" || cantidad_recibir == 0) alert("¡La cantidad por recibir debe ser numerica!");
	else if ((cantidad_recibir + cantidad_recibida) > cantidad_pedida) alert("¡ERROR: La cantidad por recibir no puede ser mayor a la cantidad pedida!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDENES-SERVICIO&accion=confirmarOrdenServicio&codorganismo="+codorganismo+"&nroorden="+nroorden+"&secuencia="+secuencia+"&cantidad_recibir="+cantidad_recibir+"&ftermino="+ftermino+"&saldo="+saldo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					var registro = codorganismo + "|" + nroorden;
					window.close();
					document.getElementById("frmentrada").submit();
					
					window.open('generar_ordenes_pendientes_confirmar_pdf.php?registro='+registro+'&secuencia='+secuencia+'&cantidad_recibida='+cantidad_recibir, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
				}
			}
		}
	}
	return false;
}

//	funcion para desconfirmar orden de servicio
function desconfirmarOrdenServicio() {
	var seldesconfirmar = document.getElementById("seldesconfirmar").value;
	
	if (seldesconfirmar == "") alert("¡Debe seleccionar un registro!");
	else if (confirm("¿Desea desconfirmar este servicio?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_lg.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORDENES-SERVICIO&accion=desconfirmarOrdenServicio&seldesconfirmar="+seldesconfirmar);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else document.getElementById("frmentrada").submit();
			}
		}
	}
	return false;
}

//	funcion para insertar un item en detalle de la recepcion
function insertarItemAlmacenRecepcion(codigo, accion) {
	var ventana = document.getElementById("ventana").value;
	var form = opener.document.getElementById("frmdetalles");
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
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarItemAlmacenRecepcion&ventana="+ventana+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&codigo="+codigo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
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

//	funcion para seleccionar
function selListadoItemAlmacen(cod, nom, uni) {
	opener.document.getElementById("coditem").value = cod;
	opener.document.getElementById("nomitem").value = nom;
	opener.document.getElementById("codunidad").value = uni;
	window.close();
}

function selUbicacionActivo(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	
	var form = opener.document.getElementById("frmdetallesactivo");
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "txtnomubicacion") n.value = busqueda;
		else if (n.name == "txtubicacion") n.value = registro;
	}
	window.close();
}

function selTransaccionCCosto(busqueda, cod, nom) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	
	var form = opener.document.getElementById("frmdetallesactivo");
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "txtnomccostos") n.value = busqueda;
		else if (n.name == "txtccostos") n.value = registro;
	}
	window.close();
}

//	funcion para insertar un item/commodity en detalle de la transaccion
function insertarCommodityTransaccionEspecial(codigo, accion, clasificacion) {
	var ventana = document.getElementById("ventana").value;
	var form = opener.document.getElementById("frmdetalles");
	var ccosto = opener.document.getElementById("ccosto").value;
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
	ajax.open("POST", "fphp_funciones_lg.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=insertarCommodityTransaccionEspecial&codigo="+codigo+"&ventana="+ventana+"&detalles="+detalles+"&nrodetalles="+nrodetalles+"&ccosto="+ccosto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("nrodetalles").value = nrodetalles;
				opener.document.getElementById("cantdetalles").value = cantdetalles;
				opener.document.getElementById("clasificacion").value = clasificacion;
				
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

function setCantidadPorRecibir(porrecibir) {
	var cantidad_recibir = new Number(setNumero(porrecibir));
	var cantidad_pendiente = new Number(setNumero(document.getElementById("cantidad_pendiente").value));
	var cantidad_pedida = new Number(setNumero(document.getElementById("cantidad_pedida").value));
	var cantidad_recibida =  new Number(setNumero(document.getElementById("cantidad_recibida").value));
	var saldo =  new Number(setNumero(document.getElementById("saldo").value));
	
	if ((cantidad_recibida + cantidad_recibir) > cantidad_pedida) {
		alert("ERROR: La cantidad por recibir no puede ser mayor a " + cantidad_pendiente);
		document.getElementById("cantidad_recibir").value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	} 
	else if (cantidad_recibir == 0) {
		alert("ERROR: La cantidad por recibir no puede ser 0");
		document.getElementById("cantidad_recibir").value = setNumeroFormato(cantidad_pendiente, 2, ".", ",");
	} 
	else {
		saldo = cantidad_pedida - cantidad_recibida - cantidad_recibir;
		psaldo = saldo * 100 / cantidad_pedida;
		pcantidad_recibir = cantidad_recibir * 100 / cantidad_pedida;
		
		document.getElementById("pcantidad_recibir").value = setNumeroFormato(pcantidad_recibir, 2, ".", ",");
		document.getElementById("saldo").value = setNumeroFormato(saldo, 2, ".", ",");
		document.getElementById("psaldo").value = setNumeroFormato(psaldo, 2, ".", ",");
	}
}

function listadoCentroCosto(cod, seldetalle) {
	opener.document.getElementById("ccosto"+seldetalle).value = cod;
	window.close();
}

function listadoPersonas(cod, seldetalle) {
	opener.document.getElementById("persona"+seldetalle).value = cod;
	window.close();
}

function selListadoObligacion(codorganismo, codpersona) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_selects.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=getOptions_2&tabla=tservicio_obligacion&codpersona="+codpersona+"&codorganismo="+codorganismo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var datos = ajax.responseText.split("||");
			limpiarMontosObligacionOpener();
			opener.document.getElementById("codproveedor").value = datos[0];
			opener.document.getElementById("nomproveedor").value = datos[1];
			opener.document.getElementById("codpagara").value = datos[0];
			opener.document.getElementById("nompagara").value = datos[1];
			opener.document.getElementById("rif").value = datos[2];
			opener.document.getElementById("nombusqueda").value = datos[3];
			opener.document.getElementById("diaspago").value = datos[4];
			opener.document.getElementById("tdoc").value = datos[5];
			opener.document.getElementById("tpago").value = datos[6];
			opener.document.getElementById("ctabancaria").value = datos[7];			
			opener.document.getElementById("tservicio").parentNode.innerHTML = datos[8];
			opener.document.getElementById("listaImpuestos").innerHTML = datos[9];
			opener.document.getElementById("canimpuesto").value = datos[10];			
			window.close();
		}
	}
}

function limpiarMontosObligacionOpener() {
	opener.document.getElementById('seldistribucion').value = "";
	opener.document.getElementById('nrodistribucion').value = "0";
	opener.document.getElementById('candistribucion').value = "0";
	opener.document.getElementById('listaDistribucion').innerHTML = "";
	opener.document.getElementById('seldocumento').value = "";
	opener.document.getElementById('candocumento').value = "0";
	opener.document.getElementById('listaDocumentos').innerHTML = "";	
	opener.document.getElementById('selimpuesto').value = "";
	opener.document.getElementById('canimpuesto').value = "0";
	opener.document.getElementById('listaImpuestos').innerHTML = "";	
	opener.document.getElementById('imp_monto').innerHTML = "0,00";
	opener.document.getElementById('doc_total').innerHTML = "0,00";
	opener.document.getElementById('doc_afecto').innerHTML = "0,00";
	opener.document.getElementById('doc_impuesto').innerHTML = "0,00";
	opener.document.getElementById('doc_noafecto').innerHTML = "0,00";
	opener.document.getElementById('dis_total').innerHTML = "0,00";	
	opener.document.getElementById('monto_afecto').value = "0,00";
	opener.document.getElementById('monto_noafecto').value = "0,00";
	opener.document.getElementById('monto_impuesto').value = "0,00";
	opener.document.getElementById('monto_retenciones').value = "0,00";
	opener.document.getElementById('monto_obligacion').value = "0,00";
	opener.document.getElementById('monto_adelanto').value = "0,00";
	opener.document.getElementById('monto_pagar').value = "0,00";
	opener.document.getElementById('monto_parcial').value = "0,00";
	opener.document.getElementById('monto_pendiente').value = "0,00";
}

function selListadoCajaChica(codorganismo, codpersona) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_ap.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=selListadoCajaChica&codpersona="+codpersona+"&codorganismo="+codorganismo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var datos = ajax.responseText.split("||");
			opener.document.getElementById("codbeneficiario").value = codpersona;
			opener.document.getElementById("nombeneficiario").value = datos[0];
			opener.document.getElementById("codanombre").value = codpersona;
			opener.document.getElementById("nomanombre").value = datos[0];
			opener.document.getElementById("monto_autorizado").value = datos[1];
			
			var monto_autorizado = new Number(setNumero(datos[1]));
			if (monto_autorizado <= 0) {
				opener.document.getElementById("divMsj").style.display = "block";
				opener.document.getElementById("bt_submit").disabled = true;
			} else {
				opener.document.getElementById("divMsj").style.display = "none";
				opener.document.getElementById("bt_submit").disabled = false;
			}
			window.close();
		}
	}
}

function reporte_movimientos_de_almacen_pdf() {
	window.open('reporte_movimientos_de_almacen_pdf.php?codalmacen='+document.getElementById('falmacen').value+'&desde='+document.getElementById('fdesde').value+'&hasta='+document.getElementById('fhasta').value+'&buscar='+document.getElementById('fbuscar').value+'&codsubfamilia='+document.getElementById('fcodsubfamilia').value, '', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');
	return false;
}