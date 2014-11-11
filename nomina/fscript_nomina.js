// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

var MAXLIMIT=30;

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

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL COLOCAR EL PUNTERO DEL MOUSE SOBRE ELLA
function mOvr(src) {
	/*
	if (!src.contains(event.FROMElement)) {
		  src.style.cursor = 'hand';
	}
	*/
	//	CON INTERNETEXPLORER VIEJO DA ERROR.... COMPROBAR QUE CON LAS DEMAS NO LO DA
}

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL QUITAR EL PUNTERO DEL MOUSE SOBRE ELLA
function mOut(src) {
	/*
	if (!src.contains(event.toElement)) {
	  src.style.cursor = 'default';
	}
	*/
	//	CON INTERNETEXPLORER VIEJO DA ERROR.... COMPROBAR QUE CON LAS DEMAS NO LO DA
}

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
	}
	if (del == "N" || !rows) {
		if (document.getElementById("btEliminar")) document.getElementById("btEliminar").disabled = true;
	}
	if (!rows) {
		if (document.getElementById("btVer")) document.getElementById("btVer").disabled = true;
		if (document.getElementById("btPDF")) document.getElementById("btPDF").disabled = true;
	}
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosProcesosControl(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btInsertar=document.getElementById("btInsertar");
	var btEditar=document.getElementById("btEditar");
	var btActivar=document.getElementById("btActivar");
	var btCerrar=document.getElementById("btCerrar");
	//
	if (insert=="N") btInsertar.disabled=true;
	if (update=="N" || !rows) { btEditar.disabled=true; btActivar.disabled=true; btCerrar.disabled=true; }
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosProcesosControlAprobar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btAprobar=document.getElementById("btAprobar");
	//
	if (update=="N" || !rows) btAprobar.disabled=true;
}

//	FUNCION QUE MUESTRA EL NUMERO DE REGISTROS
function numRegistros(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}

//	FUNCION QUE BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalElementos(rows) {
	//
	if (rows) {
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=false;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false;
	} else {		
		var btEditar=document.getElementById("btEditar"); btEditar.disabled=true;
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true;
	}
}

//	FUNCION QUE BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalBotones2(rows) {
	//
	if (rows) {
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false;
	} else {
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true;
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

//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarVentana(form, pagina, param) {
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION PARA CARGAR UNA NUEVA VENTANA
function cargarPDF(form, pag, param) {
	pagina=pag+"?persona="+form.persona.value;
	window.open(pagina, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION QUE MUESTRA MENSAJES DE ERROR
function msjError(error) {
	switch (error) {
		case 1000: alert ("¡SELECCIONE UN REGISTRO!"); break;
		case 1010: alert ("¡LOS CAMPOS MARCADOS CON (*) SON OBLIGATORIOS!"); break;
		case 1020: alert ("¡REGISTRO EXISTENTE!"); break;
		case 1030: alert ("¡PERIODO CONTABLE INCORRECTO!"); break;
		case 1040: alert ("¡FECHA FUNDACION INCORRECTA!"); break;
		case 1050: alert ("¡DEBE LLENAR EL CAMPO RIESGO!"); break;
		case 1060: alert ("¡MONTO INCORRECTO!"); break;
		case 1070: alert ("¡NUMERO INCORRECTO!"); break;
		case 1080: alert ("¡FECHA DE NACIMIENTO INCORRECTA!"); break;
		case 1090: alert ("¡FECHA DE ESTADO CIVIL INCORRECTA!"); break;
		case 1100: alert ("¡FECHA DE EXPIRACION DE LICENCIA INCORRECTA!"); break;
		case 1110: alert ("¡FECHA DE INGRESO INCORRECTA!"); break;
		case 1120: alert ("¡FECHA DE CESE INCORRECTA!"); break;
		case 1130: alert ("¡DEBE SELECCIONAR UN VALOR EN EL FILTRO BUSCAR!"); break;
		case 1140: alert ("¡FECHA DE VIGENCIA DE CONTRATO INCORRECTA!"); break;
		case 1150: alert ("¡FECHA DE FIRMA INCORRECTA!"); break;
		case 1160: alert ("¡FECHA DE BAJA INCORRECTA!"); break;
		case 1170: alert ("¡DEBE SELECCIONAR UN CARGO!"); break;
		case 1180: alert ("¡DEBE LLENAR TODOS LOS CAMPOS!"); break;
		case 1190: alert ("¡SUELDO INCORRECTO!"); break;
		case 1200: alert ("FECHA DE ENTREGA INCORRECTA!"); break;
		case 1210: alert ("¡DEBE SELECCIONAR LA PERSONA RELACIONADA!"); break;
		case 1220: alert ("¡SELECCIONE UN DOCUMENTO!"); break;
		case 1230: alert ("¡FECHA DE ESTADO INCORRECTA!"); break;
		case 1240: alert ("¡FECHA DE VENCIMIENTO INCORRECTA!"); break;
		case 1250: alert ("¡LA FECHA DE ESTADO NO PUEDE SER MENOR QUE LA FECHA DE ENTREGA!"); break;
		case 1260: alert ("¡FECHA INCORECTA!"); break;
		case 1270: alert ("¡MONTO DE HORAS INCORRECTO!"); break;
		case 1280: alert ("¡MONTO DE AÑOS INCORRECTO!"); break;
		case 1290: alert ("¡FECHA DE DOCUMENTO INCORECTA!"); break;
		case 1300: alert ("¡PERIODO DE SUSPENSION INCORECTA!"); break;
		case 1310: alert ("¡INTERVALOS DE FECHAS Y HORAS INCORRECTA!"); break;
		case 1320: alert ("¡VALOR INCORRECTO!"); break;
		case 1330: alert ("¡VALOR NOMINAL INCORRECTO!"); break;
		case 1340: alert ("¡CANTIDAD INCORRECTA!"); break;
		case 1350: alert ("¡AÑO DEL VEHICULO INCORRECTO!"); break;
		case 1360: alert ("¡VALOR DE COMPRA INCORRECTO!"); break;
		case 1370: alert ("¡FECHA DE VIGENCIA DEL REQUERIMIENTO INCORRECTA!"); break;
		case 1380: alert ("¡FECHA DE SOLICITUD INCORRECTA!"); break;
		case 1390: alert ("¡FECHA DE CAPACITACION INCORRECTA!"); break;
		case 1400: alert ("¡MONTO DE COSTO INCORRECTO!"); break;
		case 1410: alert ("¡MONTO ASUMIDO INCORRECTO!"); break;
		case 1420: alert ("¡MONTO DE COSTO INCORRECTO!"); break;
		case 1430: alert ("¡NUMERO DE VACANTES INCORRECTO!"); break;
		case 1440: alert ("¡FECHA DE VIGENCIA INCORRECTA!"); break;
		case 1450: alert ("¡DEBE INGRESAR LA HORA DE INICIO Y FIN DEL DIA SELECCIONADO!"); break;
		case 1460: alert ("¡DEBE INGRESAR EL HORARIO DE LA CAPACITACION!"); break;
		case 1470: alert ("¡MONTO INCORRECTO!"); break;
	}
}

//	FUNCION PARA CERRAR UNA VENTANA
function cerrar() {	window.close(); }

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, pagina, foraneo, modulo) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_nomina.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion=ELIMINAR&codigo="+codigo);
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
//------------------------------------------------------------//

//------------------------------------------------------------//
// FUNCION QUE MUESTRA EL LOTE CORREPONDIENTE
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
	var pagina=form.action+"&limit="+limit+"&ordenar="+ordenar;
	cargarPagina(form, pagina);
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION QUE BLOQUEA/DESBLOQUEA LOS BOTONES DE LOTES
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
//------------------------------------------------------------//

//------------------------------------------------------------//
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
//------------------------------------------------------------//

//------------------------------------------------------------//
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
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledOrganismo(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; 
	else { form.forganismo.disabled=true; form.fdependencia.disabled=true; form.chkdependencia.checked=false; form.forganismo.value=""; form.fdependencia.value=""; }
}
function enabledDependencia(form) {
	if (form.chkorganismo.checked && form.chkdependencia.checked) form.fdependencia.disabled=false;
	else { form.fdependencia.disabled=true; form.fdependencia.value=""; }
}
function enabledTipoNom(form) {
	if (form.chktiponom.checked) form.ftiponom.disabled=false; 
	else form.ftiponom.disabled=true;
}
function enabledPeriodo(form) {
	if (form.chkperiodo.checked) form.fperiodo.disabled=false; 
	else form.fperiodo.disabled=true;
}
function enabledSitTra(form) {
	if (form.chksittra.checked) form.fsittra.disabled=false; 
	else form.fsittra.disabled=true;
}
//------------------------------------------------------------//

//------------------------------------------------------------//
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
function getFOptions_PeriodoPreNomina(idSelectOrigen, idSelectDestino, idChkDestino, codorganismo) {
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
		ajax.send("accion=getOptions_2PreNomina&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&codorganismo="+codorganismo+"&ventana=PRENOMINA");
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
//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getFOptions_ProcesoPreNomina(idSelectOrigen, idSelectDestino, idChkDestino, nomina, codorganismo) {
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
		ajax.send("accion=getOptions_2&tabla="+idSelectDestino+"&opcion="+optSelectOrigen+"&nomina="+nomina+"&codorganismo="+codorganismo+"&ventana=PRENOMINA");
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
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
			if (ajax.readyState==4)	{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//------------------------------------------------------------//

//------------------------------------------------------------//
//	TIPOS DE PROCESO
function verificarTipoProceso(form, accion) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	if (document.getElementById("activo").checked) var status="A"; else var status="I";
	if (document.getElementById("flag").checked) var flag="S"; else var flag="N";
	if (codigo=="" || descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOPROCESO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&flag="+flag);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else location.href="tiposproceso.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	CONCEPTOS
function verificarConcepto(form, accion) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var tipo=document.getElementById("tipo").value; tipo=tipo.trim();
	var impresion=document.getElementById("impresion").value; impresion=impresion.trim();
	var orden=document.getElementById("orden").value; orden=orden.trim();
	var abreviatura=document.getElementById("abreviatura").value; abreviatura=abreviatura.trim();
	var formula=document.getElementById("formula").value; formula=formula.trim(); formula=encodeURIComponent(formula);
	if (document.getElementById("activo").checked) var status="A"; else var status="I";
	if (document.getElementById("flagautomatica").checked) var flagautomatica="S"; else var flagautomatica="N";
	if (document.getElementById("flagbono").checked) var flagbono="S"; else var flagbono="N";
	if (document.getElementById("flagobligacion").checked) var flagobligacion="S"; else var flagobligacion="N";
	if (document.getElementById("flagretencion").checked) var flagretencion="S"; else var flagretencion="N";
	var codpersona=document.getElementById("codpersona").value;
	if (formula != "") var flagformula = "S"; else var flagformula = "N";
	
	if (descripcion=="" || tipo=="" || impresion=="") msjError(1010);
	else if (isNaN(orden)) alert("¡DEBE INGRESAR UN VALOR NUMERICO EN EL ORDEN!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CONCEPTOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&impresion="+impresion+"&tipo="+tipo+"&orden="+orden+"&formula="+formula+"&flagformula="+flagformula+"&flagautomatica="+flagautomatica+"&abreviatura="+abreviatura+"&flagbono="+flagbono+"&flagobligacion="+flagobligacion+"&codpersona="+codpersona+"&flagretencion="+flagretencion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				datos=resp.split(":");
				if (datos[0]!=0) alert ("¡"+resp+"!");
				else {
					location.href="conceptos.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
function optConceptoTipoNomina(form, accion) {
	var concepto=document.getElementById("concepto").value;
	var tiponomina = form.tiponomina.value;
	if (tiponomina=="") msjError(1000);
	else {
		var eliminar=confirm("¿Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CONCEPTOS&accion=TIPOSNOMINA&concepto="+concepto+"&tiponomina="+tiponomina+"&sub=BORRAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else {
						document.getElementById("btBorrar").disabled=true;
						var pagina=document.getElementById("frmentrada").action;
						cargarPagina(form, pagina+"?accion=EDITAR");
					}
				}
			}	
		}
	}
}
function optConceptoProceso(form, accion) {
	var concepto=document.getElementById("concepto").value;
	var proceso = form.proceso.value;
	if (proceso=="") msjError(1000);
	else {
		var eliminar=confirm("¿Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CONCEPTOS&accion=PROCESOS&concepto="+concepto+"&proceso="+proceso+"&sub=BORRAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else {
						document.getElementById("btBorrar").disabled=true;
						var pagina=document.getElementById("frmentrada").action;
						cargarPagina(form, pagina+"?accion=EDITAR");
					}
				}
			}	
		}
	}
}
function tipoConcepto(tipo) {
	document.getElementById("flagautomatica").checked = false;
	document.getElementById("flagbono").checked = false;
	document.getElementById("flagretencion").checked = false;
	document.getElementById("formula").value = "";
	document.getElementById("flagobligacion").checked = false;
	document.getElementById("codpersona").value = "";
	document.getElementById("nompersona").value = "";
	document.getElementById("btpersona").disabled = true;
	if (tipo == "T") {
		document.getElementById("flagautomatica").disabled = true;
		document.getElementById("flagbono").disabled = true;
		document.getElementById("flagretencion").disabled = true;
		document.getElementById("flagobligacion").disabled = true;
	} else {
		document.getElementById("flagautomatica").disabled = false;
		document.getElementById("flagbono").disabled = false;
		document.getElementById("flagretencion").disabled = false;
		document.getElementById("flagobligacion").disabled = false;
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	TIPOS DE NOMINA
function verificarTipoNomina(form, accion) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var descripcion=document.getElementById("descripcion").value; descripcion=descripcion.trim();
	var titulo=document.getElementById("titulo").value; titulo=titulo.trim();
	var perfil=document.getElementById("perfil").value;
	if (document.getElementById("activo").checked) var status="A"; else var status="I";
	if (document.getElementById("flag").checked) var flag="S"; else var flag="N";
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSNOMINA&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&titulo="+titulo+"&flag="+flag+"&perfil="+perfil);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				datos=resp.split(":");
				if (datos[0]!=0) alert ("¡"+resp+"!");
				else {
					if (accion=="GUARDAR") location.href="tiposnomina_editar.php?filtro="+form.filtro.value+"&registro="+datos[1];
					else location.href="tiposnomina.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
function verificarTipoNominaProceso(form) {
	var codigo=document.getElementById("codigo").value; codigo=codigo.trim();
	var tiponomina=document.getElementById("tiponomina").value; tiponomina=tiponomina.trim();
	var proceso=document.getElementById("proceso").value; proceso=proceso.trim();
	var inserto=document.getElementById("inserto").value; inserto=inserto.trim();
	if (proceso=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSNOMINA&accion=PROCESO&codigo="+codigo+"&proceso="+proceso+"&tiponomina="+tiponomina+"&sub="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="NUEVO";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina+"?accion=EDITAR");
				}
			}
		}
	}
	return false;
}
function optTipoNominaProceso(form, accion) {
	var tiponomina=document.getElementById("tiponomina").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		var datos=secuencia.split(":");
		var proceso=datos[0];
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_nomina.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=TIPOSNOMINA&accion=PROCESO&proceso="+proceso+"&tiponomina="+tiponomina+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value="NUEVO";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina+"?accion=EDITAR");
						}
					}
				}	
			}
		} else {
			document.getElementById("inserto").value="ACTUALIZAR";
			document.getElementById("codigo").value=secuencia;
			document.getElementById("proceso").value=proceso;
			document.getElementById("btEditar").disabled=true;
			document.getElementById("btBorrar").disabled=true;
			document.getElementById("proceso").focus();
		}
	}
}
function verificarTipoNominaPeriodo(form) {
	var tiponomina=document.getElementById("tiponomina").value;
	var codigo=document.getElementById("codigo").value;
	var anio=document.getElementById("anio").value; anio=anio.trim();
	var secuencia=document.getElementById("secuencia").value; secuencia=secuencia.trim();
	var mes=document.getElementById("mes").value; mes=mes.trim();
	var inserto=document.getElementById("inserto").value; inserto=inserto.trim();
	if ((anio=="" && (secuencia!="" || mes!="")) || (secuencia=="" && (anio!="" || mes!="")) || (mes=="" && (anio!="" || secuencia!=""))) alert("¡DEBE COMPLETAR LOS CAMPOS VACIOS!");
	else if (anio=="" && secuencia=="" && mes=="" && inserto=="ACTUALIZAR") alert("¡DEBE COMPLETAR LOS CAMPOS VACIOS!");
	else {
		if (anio=="" && secuencia=="" && mes=="" && inserto=="NUEVO") inserto="GENERAR";
		else if (anio!="" && secuencia!="" && mes!="" && inserto=="NUEVO") inserto="NUEVO";
		else if (anio!="" && secuencia!="" && mes!="" && inserto=="ACTUALIZAR") inserto="ACTUALIZAR";
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSNOMINA&accion=PERIODO&codigo="+codigo+"&anio="+anio+"&mes="+mes+"&tiponomina="+tiponomina+"&sub="+inserto+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="NUEVO";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina+"?accion=EDITAR");
				}
			}
		}
	}
	return false;
}
function optTipoNominaPeriodo(form, accion) {
	var tiponomina=document.getElementById("tiponomina").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		var datos=secuencia.split(":");
		var anio=datos[0]; var mes=datos[1]; var periodo_sec=datos[2];
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax_nomina.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=TIPOSNOMINA&accion=PERIODO&anio="+anio+"&mes="+mes+"&tiponomina="+tiponomina+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value="NUEVO";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina+"?accion=EDITAR");
						}
					}
				}	
			}
		} else {
			document.getElementById("inserto").value="ACTUALIZAR";
			document.getElementById("codigo").value=secuencia;
			document.getElementById("anio").value=anio;
			document.getElementById("mes").value=mes;
			document.getElementById("secuencia").value=periodo_sec;
			document.getElementById("btEditar").disabled=true;
			document.getElementById("btBorrar").disabled=true;
			document.getElementById("anio").focus();
		}
	}
}

function escribirFormula(valor) {
	var campo=document.getElementById("formula");
	campo.value=campo.value+valor;
	campo.focus();
}

function escribirFormulaRetorno(valor) {
	var campo=document.getElementById("formula");
	campo.value=campo.value+valor+"\r";
	campo.focus();
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	CONCEPTOS DEL EMPLEADO
function verificarEmpleadoConceptos(form) {
	var secuencia=document.getElementById("secuencia").value;
	var registro=document.getElementById("registro").value;
	var accion=document.getElementById("accion").value;
	var codconcepto=document.getElementById("codconcepto").value; codconcepto=codconcepto.trim();
	var pdesde=document.getElementById("pdesde").value; pdesde=pdesde.trim(); var esPdesde=esPContable(pdesde);
	var phasta=document.getElementById("phasta").value; phasta=phasta.trim(); var esPhasta=esPContable(phasta);
	var codproceso=document.getElementById("codproceso").value; codproceso=codproceso.trim();
	var monto=document.getElementById("monto").value; monto=monto.trim(); monto=monto.replace(",", ".");
	var cantidad=document.getElementById("cantidad").value; cantidad=cantidad.trim(); cantidad=cantidad.replace(",", ".");
	var status=document.getElementById("status").value; status=status.trim();
	if (document.getElementById("flagproceso").checked) var flagproceso="S"; else var flagproceso="N";
	
	if (codconcepto=="" || status=="" || codproceso=="" || pdesde=="") msjError(1010);
	else if (isNaN(monto)) alert("¡MONTO INCORRECTO!");
	else if (isNaN(cantidad)) alert("¡CANTIDAD INCORRECTA!");
	else if (pdesde!="" && !esPdesde) alert("¡PERIODO INCORRECTO!");
	else if (phasta!="" && !esPhasta) alert("¡PERIODO INCORRECTO!");
	else if (pdesde!="" && phasta!="" && (!esPdesde || !esPhasta || (pdesde>phasta))) alert("¡PERIODO INCORRECTO!");
	//else if (monto == 0) alert("¡NO SE PUEDE ASIGNAR UN CONCEPTO CON MONTO EN CERO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADOS-CONCEPTOS&accion="+accion+"&codpersona="+registro+"&secuencia="+secuencia+"&codconcepto="+codconcepto+"&pdesde="+pdesde+"&phasta="+phasta+"&codproceso="+codproceso+"&monto="+monto+"&cantidad="+cantidad+"&status="+status+"&flagproceso="+flagproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else cargarPagina(form, "empleados_conceptos.php");
			}
		}
	}
	return false;
}
function editarEmpleadoConceptos(form) {
	elemento=document.getElementById("elemento").value;
	registro=document.getElementById("registro").value;
	if (elemento=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADOS-CONCEPTOS&accion=EDITAR&codpersona="+registro+"&elemento="+elemento);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				var valores=resp.split("|:|");
				if (valores[0]!=0) alert ("¡"+valores[0]+"!");
				else {
					document.getElementById("btConcepto").disabled=true;
					document.getElementById("codconcepto").value=valores[1];
					document.getElementById("nomconcepto").value=valores[2];
					document.getElementById("secuencia").value=valores[3];
					document.getElementById("pdesde").value=valores[4];
					document.getElementById("phasta").value=valores[5];
					document.getElementById("monto").value=valores[6];
					document.getElementById("cantidad").value=valores[7];
					document.getElementById("status").value=valores[8];
					if (valores[9]=="S") {
						document.getElementById("flagproceso").checked=true;
						document.getElementById("codproceso").value=valores[10];
						document.getElementById("nomproceso").value=valores[10];
						document.getElementById("btProceso").disabled=false;
					} else {
						document.getElementById("flagproceso").checked=false;
						document.getElementById("codproceso").value="[TODOS]";
						document.getElementById("nomproceso").value="[TODOS]";
						document.getElementById("btProceso").disabled=true;
					}
					document.getElementById("btEditar").disabled=true;
					document.getElementById("btEliminar").disabled=true;
					document.getElementById("accion").value="ACTUALIZAR";
				}
			}
		}
	}
}
function eliminarEmpleadoConceptos(form) {
	elemento=document.getElementById("elemento").value;
	registro=document.getElementById("registro").value;
	if (elemento=="") msjError(1000);
	else {
		var x=confirm("¿Realmente desde eliminar este registro?");
		if (x) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EMPLEADOS-CONCEPTOS&accion=ELIMINAR&codpersona="+registro+"&elemento="+elemento);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp=ajax.responseText;
					if (resp!=0) alert("¡"+resp+"!");
					else cargarPagina(form, "empleados_conceptos.php");
				}
			}
		}
	}
}
function limpiarEmpleadoConceptos(form) {
	document.getElementById("secuencia").value="";
	document.getElementById("codconcepto").value="";
	document.getElementById("nomconcepto").value="";
	document.getElementById("pdesde").value="";
	document.getElementById("phasta").value="";
	document.getElementById("codproceso").value="";
	document.getElementById("nomproceso").value="";
	document.getElementById("monto").value="";
	document.getElementById("cantidad").value="";
	document.getElementById("status").value="";
	document.getElementById("flagproceso").checked=false;
	document.getElementById("accion").value="INSERTAR";
	document.getElementById("btConcepto").disabled=false;
	document.getElementById("btProceso").disabled=true;
	document.getElementById("btEditar").disabled=false;
	document.getElementById("btEliminar").disabled=false;
}
function setTipoProcesoTodos(check) {
	if (check) {
		document.getElementById("codproceso").value="";
		document.getElementById("nomproceso").value="";
		document.getElementById("btProceso").disabled=false;
	} else {
		document.getElementById("codproceso").value="[TODOS]";
		document.getElementById("nomproceso").value="[TODOS]";
		document.getElementById("btProceso").disabled=true;
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
function guardarConceptosTemporales(form) {
	form.action="conceptos_temporales.php?accion=GUARDAR-TODO";
	form.submit();
}
function guardarConceptosPermanentes(form) {
	form.action="conceptos_permanentes.php?accion=GUARDAR-TODO";
	form.submit();
}
function validarConceptos(form) {
	if (document.getElementById('forganismo').value=="") { alert("¡DEBE SELECCIONAR EL ORGANISMO!"); return false; }
	else if (document.getElementById('ftiponom').value=="") { alert("¡DEBE SELECCIONAR EL TIPO DE NOMINA!"); return false; }
	else if (document.getElementById('fperiodo').value=="") { alert("¡DEBE SELECCIONAR EL PERIODO!"); return false; }
	else if (document.getElementById('codconcepto').value=="") { alert("¡DEBE SELECCIONAR EL CONCEPTO!"); return false; }
	else return true;
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FUNCION PARA SELECCIONAR UN REGISTRO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selConcepto(busqueda) {
	var codpersona = document.getElementById("codpersona").value;
	var registro = document.getElementById("registro").value;
	var periodo = opener.document.frmentrada.pdesde.value;
	var nomina = opener.document.frmentrada.nomina.value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=EJECUTAR-FORMULA-CONCEPTO&_CODPERSONA="+codpersona+"&_CODCONCEPTO="+registro+"&periodo="+periodo+"&nomina="+nomina);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			var dato = resp.split("|");
			opener.document.frmentrada.monto.value="";
			opener.document.frmentrada.cantidad.value="";
			
			if (dato[0] == "NO") {
				opener.document.frmentrada.monto.disabled=false;
				opener.document.frmentrada.cantidad.disabled=false;
			} else {
				opener.document.frmentrada.monto.disabled=true;
				opener.document.frmentrada.cantidad.disabled=true;
				opener.document.frmentrada.monto.value = dato[1];
				opener.document.frmentrada.cantidad.value = dato[2];
			}
			opener.document.frmentrada.codconcepto.value=registro;
			opener.document.frmentrada.nomconcepto.value=busqueda;
			window.close();
		}
	}
}
function selConceptoModificacion(busqueda) {
	var codpersona = document.getElementById("codpersona").value;
	var registro = document.getElementById("registro").value;
	var periodo = opener.document.frmentrada.pdesde.value;
	var nomina = opener.document.frmentrada.nomina.value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=EJECUTAR-FORMULA-CONCEPTO&_CODPERSONA="+codpersona+"&_CODCONCEPTO="+registro+"&periodo="+periodo+"&nomina="+nomina);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();
			var dato = resp.split("|");
			if (dato[1] != "0.00") {
				opener.document.frmentrada.monto.value=dato[0];
			} else {
				opener.document.frmentrada.monto.value="";
			}
			if (dato[2] != "0.00") {
				opener.document.frmentrada.cantidad.value=dato[1];
			} else {
				opener.document.frmentrada.cantidad.value="";
			}
			opener.document.frmentrada.codconcepto.value=registro;
			opener.document.frmentrada.nomconcepto.value=busqueda;
			window.close();
		}
	}
}
function selConcepto_2(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.codconcepto.value=registro;
	opener.document.frmentrada.nomconcepto.value=busqueda;
	window.close();
}
function selTipoProceso(form) {
	var registro=""; var busqueda="";
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="proceso" && n.checked==true) { registro+=n.id+" "; busqueda+=n.id+" "; }
	}
	
	opener.document.frmentrada.codproceso.value=registro;
	opener.document.frmentrada.nomproceso.value=busqueda;
	window.close();
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	CONTROL DE PROCESOS
function verificarProcesoControlIniciar(form, accion) {
	var organismo=document.getElementById("organismo").value; organismo=organismo.trim();
	var tiponom=document.getElementById("tiponom").value; tiponom=tiponom.trim();
	var periodo=document.getElementById("periodo").value; periodo=periodo.trim();
	var proceso=document.getElementById("proceso").value; proceso=proceso.trim();
	var fdesde=document.getElementById("fdesde").value; fdesde=fdesde.trim(); esFdesde=esFecha(fdesde);
	var fhasta=document.getElementById("fhasta").value; fhasta=fhasta.trim(); esFhasta=esFecha(fhasta);
	var fprocesado=document.getElementById("fprocesado").value; fprocesado=fprocesado.trim(); esFprocesado=esFecha(fprocesado);
	var fpago=document.getElementById("fpago").value; fpago=fpago.trim(); esFpago=esFecha(fpago);
	if (document.getElementById("activo").checked) var status="A"; else var status="I";
	if (document.getElementById("flagmensual").checked) var flagmensual="S"; else var flagmensual="N";
	if (organismo=="" || tiponom=="" || periodo=="" || proceso=="" || fdesde=="" || fhasta=="" || fprocesado=="" || fpago=="") msjError(1010);
	else if (!esFdesde || !esFhasta) alert("¡FECHA INCORRECTA!");
	else if (!esFprocesado) alert("¡FECHA DE PROCESADO INCORRECTA!");
	else if (!esFpago) alert("¡FECHA DE PAGO INCORRECTA!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCESOS-CONTROL&accion="+accion+"&organismo="+organismo+"&tiponom="+tiponom+"&periodo="+periodo+"&proceso="+proceso+"&fdesde="+fdesde+"&fhasta="+fhasta+"&fprocesado="+fprocesado+"&fpago="+fpago+"&status="+status+"&flagmensual="+flagmensual);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else cargarPagina(form, form.action);
			}
		}
	}
	return false;
}
//	CONTROL DE PROCESOS (APROBACION)
function verificarProcesoControlAprobar(form, accion) {
	var organismo=document.getElementById("organismo").value; organismo=organismo.trim();
	var tiponom=document.getElementById("tiponom").value; tiponom=tiponom.trim();
	var periodo=document.getElementById("periodo").value; periodo=periodo.trim();
	var proceso=document.getElementById("proceso").value; proceso=proceso.trim();
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=PROCESOS-CONTROL&accion="+accion+"&organismo="+organismo+"&tiponom="+tiponom+"&periodo="+periodo+"&proceso="+proceso);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp=ajax.responseText;
			if (resp!=0) alert ("¡"+resp+"!");
			else cargarPagina(form, "procesos_control_aprobacion.php");
		}
	}
	return false;
}
//
function desactivarProcesoControl(form) {
	var registro=document.getElementById("registro").value;
	if (registro=="") msjError(1000);
	else {
		var resp=confirm("¿Realmente desea modificar este periodo?");
		if (resp) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PROCESOS-CONTROL&accion=DESACTIVAR&registro="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp=ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else cargarPagina(form, "procesos_control.php");
				}
			}
		}
	}
}
//
function cerrarPeriodoProcesoControl(form) {
	var registro=document.getElementById("registro").value;
	if (registro=="") msjError(1000);
	else {
		var resp=confirm("¿Realmente desea cerrar este periodo?");
		if (resp) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_nomina.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PROCESOS-CONTROL&accion=CERRAR&registro="+registro);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp=ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else cargarPagina(form, "procesos_control.php");
				}
			}
		}
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	Valido filtro Ejecucion de Procesos
function cargarDisponiblesProcesar(form) {
	var frm = document.getElementById("frmentrada");
	var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	
	if (ftiponom=="" || fperiodo=="") { alert("¡DEBE SELECCIONAR EL TIPO DE NOMINA Y PERIODO!"); return false; }
	else if (ftproceso=="") { alert("¡DEBE SELECCIONAR EL TIPO DE PROCESO!"); return false; }
	else return true;
}

//	Eliminar fila de tabla Origen y Agregarla a tabla de Destino...
function switchDblTR(trOrigen, tblDest, idTrDestino, tblOrigen, idTrOrigen, idChkDestino, nmChkDestino, idChkOrigen, nmChkOrigen, valores) {
	var tblDestino = document.getElementById(tblDest);
	var valor = valores.split("|:|");	
	
	//	Creo los elementos para agregarlos a la tabla destino...
	idTrDest = idTrDestino + valor[0];
	idChkDest = idChkDestino + valor[0];
	var trDestino = document.createElement("tr"); 
	trDestino.className = "trListaBody";
	trDestino.id = idTrDest;
	trDestino.setAttribute("ondblclick", "switchDblTR(this, '"+tblOrigen+"', '"+idTrOrigen+"', '"+tblDest+"', '"+idTrDestino+"', '"+idChkOrigen+"', '"+nmChkOrigen+"', '"+idChkDestino+"', '"+nmChkDestino+"', '"+valores+"');");
	var tdDestino1 = document.createElement("td");
	tdDestino1.align = "center";
	var tdDestino2 = document.createElement("td");
	tdDestino2.align = "center";
	var tdDestino3 = document.createElement("td");
	var tdDestino4 = document.createElement("td");
	var tdDestino5 = document.createElement("td");
	var tdDestino6 = document.createElement("td");
	tdDestino6.align = "center";
	var chkDestino1 = document.createElement("input");
	chkDestino1.type = "checkbox";
	chkDestino1.id = idChkDest; 
	chkDestino1.name = nmChkDestino; 
	chkDestino1.value = valores;
	var txtDestino2 = document.createTextNode(valor[0]);
	var txtDestino3 = document.createTextNode(valor[1]);
	var txtDestino4 = document.createTextNode(valor[2]);
	var txtDestino5 = document.createTextNode(valor[3]);
	var txtDestino6 = document.createTextNode(valor[4]);
	
	//	Agrego los elementos creados a la tabla destino...
	tblDestino.appendChild(trDestino);
	trDestino.appendChild(tdDestino1);
	trDestino.appendChild(tdDestino2);
	trDestino.appendChild(tdDestino3);
	trDestino.appendChild(tdDestino4);
	trDestino.appendChild(tdDestino5);
	trDestino.appendChild(tdDestino6);
	tdDestino1.appendChild(chkDestino1);
	tdDestino2.appendChild(txtDestino2);
	tdDestino3.appendChild(txtDestino3);
	tdDestino4.appendChild(txtDestino4);
	tdDestino5.appendChild(txtDestino5);
	tdDestino6.appendChild(txtDestino6);
	
	//	Elimino la fila del seleccionado en la tabla origen...
	var tblO = trOrigen.parentNode;
	tblO.removeChild(trOrigen);
}

//	Eliminar fila de tabla Origen y Agregarla a tabla de Destino...
function switchSelTR(form, tblDest, idTrDestino, tblOrigen, idTrOrigen, idChkDestino, nmChkDestino, idChkOrigen, nmChkOrigen) {
	var tblDestino = document.getElementById(tblDest);
	var num = 0;
	var seleccionados = "";
	var valores = "";
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name==nmChkOrigen && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
		} 
	}
	
	if (seleccionados == "") alert("¡DEBE SELECCIONAR UN EMPLEADO!")
	else {
		//	------------------------------
		var forganismo = document.getElementById("forganismo").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EJECUCION-PROCESOS&accion=QUITAR-SEL-NOMINA&seleccionados="+seleccionados+"&organismo="+forganismo+"&tiponom="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
			  var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
			}
		}
		//	------------------------------
		
		var valores = seleccionados.split("|;|");
		
		for (i=0; i<valores.length; i++) {
			var valor = valores[i].split("|:|");
			
			//	Creo los elementos para agregarlos a la tabla destino...
			idTrDest = idTrDestino + valor[0];
			idChkDest = idChkDestino + valor[0];
			var trDestino = document.createElement("tr"); 
			trDestino.className = "trListaBody";
			trDestino.id = idTrDest;
			trDestino.setAttribute("ondblclick", "switchDblTR(this, '"+tblOrigen+"', '"+idTrOrigen+"', '"+tblDest+"', '"+idTrDestino+"', '"+idChkOrigen+"', '"+nmChkOrigen+"', '"+idChkDestino+"', '"+nmChkDestino+"', '"+valores[i]+"');");
			var tdDestino1 = document.createElement("td");
			tdDestino1.align = "center";
			var tdDestino2 = document.createElement("td");
			tdDestino2.align = "center";
			var tdDestino3 = document.createElement("td");
			var tdDestino4 = document.createElement("td");
			var tdDestino5 = document.createElement("td");
			var tdDestino6 = document.createElement("td");
			tdDestino6.align = "center";
			var chkDestino1 = document.createElement("input");
			chkDestino1.type = "checkbox";
			chkDestino1.id = idChkDest; 
			chkDestino1.name = nmChkDestino; 
			chkDestino1.value = valores[i];
			var txtDestino2 = document.createTextNode(valor[0]);
			var txtDestino3 = document.createTextNode(valor[1]);
			var txtDestino4 = document.createTextNode(valor[2]);
			var txtDestino5 = document.createTextNode(valor[3]);
			var txtDestino6 = document.createTextNode(valor[4]);
			
			//	Agrego los elementos creados a la tabla destino...
			tblDestino.appendChild(trDestino);
			trDestino.appendChild(tdDestino1);
			trDestino.appendChild(tdDestino2);
			trDestino.appendChild(tdDestino3);
			trDestino.appendChild(tdDestino4);
			trDestino.appendChild(tdDestino5);
			trDestino.appendChild(tdDestino6);
			tdDestino1.appendChild(chkDestino1);
			tdDestino2.appendChild(txtDestino2);
			tdDestino3.appendChild(txtDestino3);
			tdDestino4.appendChild(txtDestino4);
			tdDestino5.appendChild(txtDestino5);
			tdDestino6.appendChild(txtDestino6);
			
			//	Elimino la fila del seleccionado en la tabla origen...
			var idTrO = idTrOrigen + valor[0];
			var trOrigen = document.getElementById(idTrO);
			var tblO = trOrigen.parentNode;		
			tblO.removeChild(trOrigen);
			
		}
	}
}

//	FUNCION PARA SELECCIOAR TODOS LOS CHECK
function selChkTodos(form, chkNombre, boo) {
	for(i=0; n=form.elements[i]; i++) 
		if (n.type == "checkbox" && n.name == chkNombre) n.checked = boo;
}

//	FUNCION PARA EJECUTAR EL PROCESO DE CALCULO A TRAVES DE UN AJAX
function procesarNomina(form) {
	if (confirm("¿Dese generar la nomina para los trabajadores seleccionados?")) {
		document.getElementById("bloqueo").style.display = "block";
		document.getElementById("cargando").style.display = "block";
		var forganismo = document.getElementById("forganismo").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		var num = 0;
		var aprobados = "";
		
		//	Obtengo los valores de los empleados a procesar...
		for(i=0; n=form.elements[i]; i++) {
			if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
				num++;
				var valor = n.value.split("|:|");
				if (num == 1) aprobados = valor[0]; else aprobados += "|:|" + valor[0];
			} 
		}
		
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EJECUCION-PROCESOS&accion=PROCESAR-NOMINA&aprobados="+aprobados+"&organismo="+forganismo+"&tiponom="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp.trim() != "") alert(resp); else alert("¡Se generó el calculo exitosamente!");			
				document.getElementById("bloqueo").style.display = "none";		
				document.getElementById("cargando").style.display = "none";
			}
		}
	}
}
function procesarPreNomina(form) {
	if (confirm("¿Dese generar la nomina para los trabajadores seleccionados?")) {
		document.getElementById("bloqueo").style.display = "block";
		document.getElementById("cargando").style.display = "block";
		var forganismo = document.getElementById("forganismo").value;
		var ftiponom = document.getElementById("ftiponom").value;
		var fperiodo = document.getElementById("fperiodo").value;
		var ftproceso = document.getElementById("ftproceso").value;
		var num = 0;
		var aprobados = "";
		
		//	Obtengo los valores de los empleados a procesar...
		for(i=0; n=form.elements[i]; i++) {
			if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
				num++;
				var valor = n.value.split("|:|");
				if (num == 1) aprobados = valor[0]; else aprobados += "|:|" + valor[0];
			} 
		}
		
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EJECUCION-PROCESOS&accion=PROCESAR-PRENOMINA&aprobados="+aprobados+"&organismo="+forganismo+"&tiponom="+ftiponom+"&periodo="+fperiodo+"&proceso="+ftproceso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!"); else alert("¡Se generó el calculo exitosamente!");			
				document.getElementById("bloqueo").style.display = "none";		
				document.getElementById("cargando").style.display = "none";
			}
		}
	}
}

function verPayRoll(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
			var variables = n.value.split("|:|");
			if (num == 1) empleados = variables[0]; else empleados += "|:|" + variables[0];
		}
	}
	var pagina = "payroll_pdf.php?codproceso=" + codproceso + "&nomproceso=" + nomproceso + "&periodo=" + periodo + "&organismo=" + organismo + "&nomina=" + nomina + "&empleados=" + empleados;
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}

function verNomina(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
			var variables = n.value.split("|:|");
			if (num == 1) empleados = variables[0]; else empleados += "|:|" + variables[0];
		}
	}
	var pagina = "nomina_pdf.php?codproceso=" + codproceso + "&nomproceso=" + nomproceso + "&periodo=" + periodo + "&organismo=" + organismo + "&nomina=" + nomina + "&empleados=" + empleados;
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}

function verPayRollPreNomina(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
			var variables = n.value.split("|:|");
			if (num == 1) empleados = variables[0]; else empleados += "|:|" + variables[0];
		}
	}
	var pagina = "payroll_prenomina_pdf.php?codproceso=" + codproceso + "&nomproceso=" + nomproceso + "&periodo=" + periodo + "&organismo=" + organismo + "&nomina=" + nomina + "&empleados=" + empleados;
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}

function verNominaPreNomina(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	//	Obtengo los valores de las filas seleccionadas...
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="chkAprobados" && n.checked==true) {
			num++;
			if (num == 1) seleccionados = n.value; else seleccionados += "|;|" + n.value;
			var variables = n.value.split("|:|");
			if (num == 1) empleados = variables[0]; else empleados += "|:|" + variables[0];
		}
	}
	var pagina = "nomina_prenomina_pdf.php?codproceso=" + codproceso + "&nomproceso=" + nomproceso + "&periodo=" + periodo + "&organismo=" + organismo + "&nomina=" + nomina + "&empleados=" + empleados;
	window.open(pagina, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}

function txtNomina(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	//var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var nombre_archivo = document.getElementById("archivo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	if (organismo == "" || periodo == "" || codproceso == "" || nombre_archivo == "") alert("¡Debe ingresar todos los valores del filtro!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "txt_nomina_venezuela.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codproceso="+codproceso+"&nomproceso="+nomproceso+"&periodo="+periodo+"&organismo="+organismo+"&nombre_archivo="+nombre_archivo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				window.open("descarga_txt.php?nombre_archivo="+nombre_archivo, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
			}
		}
	}
	return false;
}


function enabledArchivo(id) {
	if (id == "pdf") {
		document.getElementById("archivo").disabled = true;
		document.getElementById("archivo").value = "";
	} else {
		document.getElementById("archivo").disabled = false;
		document.getElementById("archivo").value = "";
	}
}

function validarReporteJubilacionPension(form) {
	if (document.getElementById("pdf").checked) return true;
	else {
		var archivo = document.getElementById("archivo").value; archivo = archivo.trim();
		var forganismo = document.getElementById("forganismo").value; forganismo = forganismo.trim();
		var ftiponom = document.getElementById("ftiponom").value; ftiponom = ftiponom.trim();
		var fperiodo = document.getElementById("fperiodo").value; fperiodo = fperiodo.trim();
		var ftproceso = document.getElementById("ftproceso").value; ftproceso = ftproceso.trim();
		if (archivo == "") alert("¡Debe ingresar el nombre del archivo!");
		else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "txt_nomina_jubilacion_pension.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("ftproceso="+ftproceso+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&forganismo="+forganismo+"&nombre_archivo="+archivo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					window.open("descarga_txt.php?nombre_archivo="+archivo, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
				}
			}
		}
		return false;
	}
}

function validarReporteViviendaHabitat(form) {
	if (document.getElementById("pdf").checked) return true;
	else {
		var archivo = document.getElementById("archivo").value; archivo = archivo.trim();
		var forganismo = document.getElementById("forganismo").value; forganismo = forganismo.trim();
		var ftiponom = document.getElementById("ftiponom").value; ftiponom = ftiponom.trim();
		var fperiodo = document.getElementById("fperiodo").value; fperiodo = fperiodo.trim();
		var ftproceso = document.getElementById("ftproceso").value; ftproceso = ftproceso.trim();
		if (archivo == "") alert("¡Debe ingresar el nombre del archivo!");
		else {
			form.action = "excel_relacion_vivienda_habitat.php";
			form.submit();
		}
		return false;
	}
}
//------------------------------------------------------------//

//------------------------------------------------------------//
//	FIDEICOMISO
function verificarFideicomiso(form, accion) {
	var codpersona = document.getElementById("codpersona").value;
	var acumuladoinicialdias = document.getElementById("acumuladoinicialdias").value; acumuladoinicialdias = acumuladoinicialdias.trim();
	var acumuladoinicialprov = document.getElementById("acumuladoinicialprov").value; acumuladoinicialprov = acumuladoinicialprov.trim();
	var acumuladoinicialfide = document.getElementById("acumuladoinicialfide").value; acumuladoinicialfide = acumuladoinicialfide.trim();
	var acumuladoinicialdiasadicional = document.getElementById("acumuladoinicialdiasadicional").value.trim();
	var periodoinicial = document.getElementById("periodoinicial").value.trim();
	acumuladoinicialdias = acumuladoinicialdias.replace(",", ".");
	acumuladoinicialprov = acumuladoinicialprov.replace(",", ".");
	acumuladoinicialfide = acumuladoinicialfide.replace(",", ".");
	
	if (codpersona == "" || acumuladoinicialdias == "" || acumuladoinicialprov == "" || acumuladoinicialfide == "" || acumuladoinicialdiasadicional == "" || periodoinicial == "") msjError(1010);
	else if (isNaN(acumuladoinicialdias) || isNaN(acumuladoinicialprov) || isNaN(acumuladoinicialfide) || isNaN(acumuladoinicialdiasadicional)) alert("Monto Incorrecto");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FIDEICOMISO&accion="+accion+"&codpersona="+codpersona+"&acumuladoinicialdias="+acumuladoinicialdias+"&acumuladoinicialprov="+acumuladoinicialprov+"&acumuladoinicialfide="+acumuladoinicialfide+"&acumuladoinicialdiasadicional="+acumuladoinicialdiasadicional+"&periodoinicial="+periodoinicial);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else cargarPagina(form, "fideicomiso.php?limit=0&filtro="+document.getElementById("filtro").value);
			}
		}
	}
	return false;
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroFideicomiso(form, limit) {
	var edad=new String(form.fedad.value); edad=edad.trim();
	var filtro=""; var ordenar="ORDER BY me.CodEmpleado";
	if (form.chkedoreg.checked) filtro+=" AND mp.Estado LIKE *;"+form.fedoreg.value+";*";
	if (form.chkorganismo.checked) filtro+=" AND me.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND me.CodDependencia LIKE *;"+form.fdependencia.value+";*";
	if (form.chksittra.checked) filtro+=" AND me.Estado LIKE *;"+form.fsittra.value+";*";
	if (form.chktiponom.checked) filtro+=" AND me.CodTipoNom LIKE *;"+form.ftiponom.value+";*";
	if (form.chkbuscar.checked) filtro+=" AND mp."+form.sltbuscar.value+" LIKE *;"+form.fbuscar.value+";*";
	if (form.chktipotra.checked) filtro+=" AND me.CodTipoTrabajador LIKE *;"+form.ftipotra.value+";*";
	if (form.chkndoc.checked) filtro+=" AND mp.Ndocumento LIKE *;"+form.fndoc.value+";*";
	if (form.chkordenar.checked) ordenar=" ORDER BY "+form.fordenar.value;	
	if (form.chkpersona.checked) {
		var rel="";
		if (form.sltpersona.value=="1") rel="=";
		else if (form.sltpersona.value=="2") rel="<";
		else if (form.sltpersona.value=="3") rel=">";
		else if (form.sltpersona.value=="4") rel="<=";
		else if (form.sltpersona.value=="5") rel=">=";
		else if (form.sltpersona.value=="6") rel="<>";
		filtro+=" AND me.CodEmpleado"+rel+"*"+form.fpersona.value+"*";
	}
	if (form.chkedad.checked && edad!="") {
		var fechaEdad=setFiltroEdad(edad);
		var fecha = fechaEdad.split(":");
		var fechaDesde = fecha[0].split("-");
		var fechaHasta = fecha[1].split("-");
		if (fechaDesde[1]<10) fechaDesde[1]="0"+fechaDesde[1];
		if (fechaDesde[2]<10) fechaDesde[2]="0"+fechaDesde[2];
		if (fechaHasta[1]<10) fechaHasta[1]="0"+fechaHasta[1];
		if (fechaHasta[2]<10) fechaHasta[2]="0"+fechaHasta[2];
		var desde=fechaDesde[0]+"-"+fechaDesde[1]+"-"+fechaDesde[2];
		var hasta=fechaHasta[0]+"-"+fechaHasta[1]+"-"+fechaHasta[2];		
		if (form.sltedad.value=="1") filtro+=" AND (mp.Fnacimiento>=*"+desde+"* AND mp.Fnacimiento<=*"+hasta+"*)";
		else if (form.sltedad.value=="2") filtro+=" AND (mp.Fnacimiento>*"+hasta+"*)";
		else if (form.sltedad.value=="3") filtro+=" AND (mp.Fnacimiento<*"+desde+"*)";
		else if (form.sltedad.value=="4") filtro+=" AND (mp.Fnacimiento>=*"+desde+"*)";
		else if (form.sltedad.value=="5") filtro+=" AND (mp.Fnacimiento<=*"+hasta+"*)";
		else if (form.sltedad.value=="6") filtro+=" AND (mp.Fnacimiento<*"+desde+"* OR mp.Fnacimiento>*"+hasta+"*)";
	}
	if (form.chkfingreso.checked) {
		var esFIngD=esFecha(form.ffingresod.value);
		var esFIngH=esFecha(form.ffingresoh.value);
		var fechad = new String (form.ffingresod.value); fechad=fechad.trim();
		var fechah = new String (form.ffingresoh.value); fechah=fechah.trim();		
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");		
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");		
		filtro+=" AND (me.Fingreso>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND me.Fingreso<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFIngD=true; esFIngH=true; }
	if (esFIngD && esFIngH) {
		if (form.chkbuscar.checked && form.sltbuscar.value=="") msjError(1130);
		else {
			var pagina="fideicomiso.php?accion=FILTRAR&filtrar="+filtro+"&filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosFideicomiso(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btAgregar = document.getElementById("btAgregar");
	var btEditar = document.getElementById("btEditar");
	var btDepositos = document.getElementById("btDepositos");
	//
	if (insert == "N") btAgregar.disabled = true;
	if (update == "N" || !rows) btEditar.disabled = true;
	if (!rows) btDepositos.disabled = true;
}
//------------------------------------------------------------//

//	FUNCION PARA VALIDAR EL FILTRO
function validarFiltroDepositosFideicomiso(form) {
	var periodo = document.getElementById("fperiodo").value;
	if (periodo == "") { alert("¡DEBE SELECCIONAR EL PERIODO!"); return false; }
	else return true;
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosFideicomisoDepositos(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML = "Registros: "+rows;
	var btTXT = document.getElementById("btTXT");
	var btPDF = document.getElementById("btPDF");
	var btActualizar = document.getElementById("btActualizar");
	//	------------
	if (insert == "N" || update == "N" || !rows) {
		btTXT.disabled = true;
		btPDF.disabled = true;
		btActualizar.disabled = true;
	}
}
//	
function actualizarPrestacionAntiguedad() {
	document.getElementById("bloqueo").style.display = "block";
	document.getElementById("cargando").style.display = "block";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=FIDEICOMISO&accion=ACTUALIZAR-ANTIGUEDAD&organismo="+organismo+"&nomina="+nomina+"&periodo="+periodo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp=ajax.responseText;
			if (resp!=0) alert ("¡"+resp+"!");
			else alert("¡SE ACTUALIZARON LAS PROVISIONES!");
			document.getElementById("bloqueo").style.display = "none";
			document.getElementById("cargando").style.display = "none";
		}
	}
}
//
function descargarTxt(pagina) {
	stamp = new Date();
	var y = stamp.getDate();
	var m = 1+(stamp.getMonth());
	var d = stamp.getFullYear();
	var h = stamp.getHours();
	var i = stamp.getMinutes();
	//var archivo = y + "_" + m + "_" + d + "_" +  
	
	
	document.getElementById("bloqueo").style.display = "block";
	document.getElementById("cargando").style.display = "block";
	var organismo = document.getElementById("forganismo").value;
	var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fideicomiso_depositos_txt.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("archivo=&organismo="+organismo+"&nomina="+nomina+"&periodo="+periodo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			location.href = "descarga_txt.php";
			document.getElementById("bloqueo").style.display = "none";
			document.getElementById("cargando").style.display = "none";
		}
	}
}
//------------------------------------------------------------//


//------------------------------------------------------------//
function insertarConceptoPayRoll(form) {
	var nomina = document.getElementById("nomina").value;
	var organismo = document.getElementById("organismo").value;
	var periodo = document.getElementById("periodo").value;
	var proceso = document.getElementById("proceso").value;
	var persona = document.getElementById("persona").value;
	var pdesde = document.getElementById("pdesde").value;
	var phasta = document.getElementById("phasta").value;
	var codconcepto = document.getElementById("codconcepto").value;
	var status = document.getElementById("status").value;
	var monto = document.getElementById("monto").value.replace(".", ""); monto = monto.replace(",", ".");
	var cantidad = document.getElementById("cantidad").value.replace(".", ""); cantidad = cantidad.replace(",", ".");
	
	if (status == "" || monto == "" || pdesde == "" || codconcepto == "") alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PAYROLL&accion=INSERTAR-CONCEPTO&organismo="+organismo+"&nomina="+nomina+"&periodo="+periodo+"&codproceso="+proceso+"&codpersona="+persona+"&phasta="+phasta+"&pdesde="+pdesde+"&phasta="+phasta+"&codconcepto="+codconcepto+"&monto="+monto+"&cantidad="+cantidad+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else location.href = "modificar_payroll_editar.php?organismo="+organismo+"&nomina="+nomina+"&periodo="+periodo+"&proceso="+proceso+"&persona="+persona;
			}
		}
	}
	return false;
}

function calcularTotalesConceptos() {
	var frmasignaciones = document.getElementById("frmasignaciones");
	var asignaciones = 0;
	for (i=0; i<frmasignaciones.elements.length; i++) {
		var valor = frmasignaciones.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		valor = new Number(valor);
		asignaciones += valor;
	}
	monto_asignaciones = new Number(asignaciones);	
	document.getElementById("asignaciones").value = setNumberFormat(asignaciones, 2, ",", ".");
	
	
	var frmdeducciones = document.getElementById("frmdeducciones");
	var deducciones = 0;
	for (i=0; i<frmdeducciones.elements.length; i++) {
		var valor = frmdeducciones.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		valor = new Number(valor);
		deducciones += valor;
	}
	monto_deducciones = new Number(deducciones);
	document.getElementById("deducciones").value = setNumberFormat(deducciones, 2, ",", ".");
	
	
	var frmpatronales = document.getElementById("frmpatronales");
	var patronales = 0;
	for (i=0; i<frmpatronales.elements.length; i++) {
		var valor = frmpatronales.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		valor = new Number(valor);
		patronales += valor;
	}
	monto_patronales = new Number(patronales);
	document.getElementById("patronales").value = setNumberFormat(patronales, 2, ",", ".");
	
	var neto = new Number(monto_asignaciones - monto_deducciones);
	document.getElementById("neto").value = setNumberFormat(neto, 2, ",", ".");
	
}

function modificarPayRoll() {
	var nomina = document.getElementById("nomina").value;
	var organismo = document.getElementById("organismo").value;
	var periodo = document.getElementById("periodo").value;
	var proceso = document.getElementById("proceso").value;
	var persona = document.getElementById("persona").value;
	
	var frmasignaciones = document.getElementById("frmasignaciones");
	var asignaciones = "";
	for (i=0; i<frmasignaciones.elements.length; i++) {
		var id = frmasignaciones.elements[i].id;
		var valor = frmasignaciones.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		if (i == 0) asignaciones += id + ":" + valor;
		else asignaciones += "|" + id + ":" + valor;
		
	}
	
	var frmdeducciones = document.getElementById("frmdeducciones");
	var deducciones = "";
	for (i=0; i<frmdeducciones.elements.length; i++) {
		var id = frmdeducciones.elements[i].id;
		var valor = frmdeducciones.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		if (i == 0) deducciones += id + ":" + valor;
		else deducciones += "|" + id + ":" + valor;
		
	}
	
	var frmpatronales = document.getElementById("frmpatronales");
	var patronales = "";
	for (i=0; i<frmpatronales.elements.length; i++) {
		var id = frmpatronales.elements[i].id;
		var valor = frmpatronales.elements[i].value.replace(".", ""); valor = valor.replace(",", ".");
		if (i == 0) patronales += id + ":" + valor;
		else patronales += "|" + id + ":" + valor;
		
	}
		
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=PAYROLL&accion=MODIFICAR&asignaciones="+asignaciones+"&deducciones="+deducciones+"&patronales="+patronales+"&nomina="+nomina+"&organismo="+organismo+"&periodo="+periodo+"&proceso="+proceso+"&persona="+persona);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp != 0) alert ("¡"+resp+"!");
			else window.close();
		}
	}
}

function validarDetalleConcepto(form) {
	var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	var archivo = document.getElementById("archivo").value.trim();
	if (document.getElementById("asignaciones").checked) var chkasignacion = "I"; else var chkasignacion = "";
	if (document.getElementById("deducciones").checked) var chkdeduccion = "D"; else var chkdeduccion = "";
	if (document.getElementById("aporte").checked) var chkaporte = "A"; else var chkaporte = "";

	if (chkasignacion == "" && chkdeduccion == "" && chkaporte == "") {
		alert("¡DEBE SELECCIONAR EL TIPO DE CONCEPTO A FILTRAR!");
	}
	else {
		if (document.getElementById("pdf").checked) {
			window.open("pdf_detallado_conceptos.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso+"&chkdeduccion="+chkdeduccion+"&chkasignacion="+chkasignacion+"&chkaporte="+chkaporte, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
		} else {
			if (archivo == "") {
				alert("¡DEBE INGRESAR EL NOMBRE DEL ARCHIVO A EXPORTAR!");
			} else {
				form.action = "excel_detallado_conceptos.php";
				form.submit();
			}
		}
	}
	return false;
}

function validarResumenConceptosProceso(form) {
	var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	var archivo = document.getElementById("archivo").value.trim();	
	
	if (document.getElementById("pdf").checked) {
		window.open("pdf_resumen_conceptos_proceso.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
	} else {
		if (archivo == "") {
			alert("¡DEBE INGRESAR EL NOMBRE DEL ARCHIVO A EXPORTAR!");
		} else {
			form.action = "excel_resumen_conceptos_proceso.php";
			form.submit();
		}
	}
	return false;
}

function validarResumenConceptosProcesoPartida(form) {
	var forganismo = document.getElementById("forganismo").value;
	var ftiponom = document.getElementById("ftiponom").value;
	var fperiodo = document.getElementById("fperiodo").value;
	var ftproceso = document.getElementById("ftproceso").value;
	var archivo = document.getElementById("archivo").value.trim();	
	
	if (document.getElementById("pdf").checked) {
		window.open("pdf_resumen_conceptos_proceso_partidas.php?forganismo="+forganismo+"&ftiponom="+ftiponom+"&fperiodo="+fperiodo+"&ftproceso="+ftproceso, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
	} else {
		if (archivo == "") {
			alert("¡DEBE INGRESAR EL NOMBRE DEL ARCHIVO A EXPORTAR!");
		} else {
			form.action = "excel_resumen_conceptos_proceso_partidas.php";
			form.submit();
		}
	}
	return false;
}
//------------------------------------------------------------//

//	Funcion para formatear un numero
function setNumberFormat(numero, decimales, sep_decimales, sep_millares) {
	var monto = new String(numero);
	var partes = monto.split(".");
	var parte_entera = new String(partes[0]);
	if (partes[1] == undefined) var parte_decimal = new String(""); else var parte_decimal = new String(partes[1]);
	var parte_derecha = new String("");
	var parte_izquierda = new String("");
	var ceros = new String("");
	
	if (partes.length <= 2) {
		//	Agrego la separacion de los millares...
		var con = 0;
		for (i = parte_entera.length-1; i>=0; i--) {
			con++;
			if (con % 3 == 0 && i != 0) parte_derecha = sep_millares + parte_entera.charAt(i) + parte_derecha;
			else parte_derecha = parte_entera.charAt(i) + parte_derecha;
		}
		
		//	Obtengo los decimales...
		if (decimales == parte_decimal.length) parte_izquierda = sep_decimales + parte_decimal; 
		else if (decimales < parte_decimal.length) {
			var redondear_1 = parte_decimal.substr(0, decimales);
			var redondear_2 = parte_decimal.substr(decimales, 1);			
			if (redondear_2 >= 5) redondear_1++;
			var aredondear = new String(redondear_1);
			
			if (decimales > aredondear.length) {
				var num_ceros = decimales - aredondear.length;
				for (i=0; i<num_ceros; i++) ceros += "0";
				
				parte_izquierda = sep_decimales + ceros + aredondear;
				
			} else parte_izquierda = sep_decimales + redondear_1;
		}
		else if (decimales > parte_decimal.length) {
			var num_ceros = decimales - parte_decimal.length;
			for (i=0; i<num_ceros; i++) ceros += "0";
			
			parte_izquierda = sep_decimales + parte_decimal + ceros;
			
		}
	}
	
	return parte_derecha + parte_izquierda;
}
//------------------------------------------------------------//

//	Funcion para enebled/disabled un input por un check
function enabledInput(boo, input) {
	document.getElementById(input).disabled = !boo;
}
//------------------------------------------------------------//

//	Funcion para enebled/disabled un input por un check
function enabledInputBrowse(boo, input1, input2, boton) {
	document.getElementById(input1).value = "";
	document.getElementById(input2).value = "";
	document.getElementById(boton).disabled = !boo;
}
//------------------------------------------------------------//


//------------------------------------------------------------//
function valPeriodo(periodo) {
	var nAno = parseInt(periodo.substr(0, 4), 10);
	var nMes = parseInt(periodo.substr(5, 2), 10);
	
	if (isNaN(nAno) || isNaN(nMes) || (nMes < 1 || nMes > 12) || nAno < 1900) return false;
	else return true;
}
//------------------------------------------------------------//


//------------------------------------------------------------//
function verificarTasaInteres(form, accion) {
	var periodo = document.getElementById("periodo").value; periodo = periodo.trim();
	var porcentaje = document.getElementById("porcentaje").value; porcentaje = porcentaje.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else estado = "I";
	
	if (periodo == "" || porcentaje == "") msjError(1010);
	else if (!valPeriodo) alert("¡Periodo Incorrecto!");
	else if (isNaN(porcentaje)) alert("¡Porcentaje Incorrecto!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TASA-INTERESES&accion="+accion+"&periodo="+periodo+"&porcentaje="+porcentaje+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert ("¡"+resp+"!");
				else cargarPagina(form, "tasa_intereses.php");
			}
		}
	}
	return false;
}
//------------------------------------------------------------//


//------------------------------------------------------------//
//	AJUSTES SALARIALES
function enabledAjusteSalarial(idgrado, boo) {
	document.getElementById("P_"+idgrado).disabled = !boo;
	document.getElementById("P_"+idgrado).value = "";
	document.getElementById("M_"+idgrado).disabled = !boo;
	document.getElementById("M_"+idgrado).value = "";
	
	document.getElementById("P_"+idgrado).focus();
}

function setAjusteSalarial(tipo, grado, actual) {
	if (tipo == "P") {
		var valor = document.getElementById("P_"+grado).value; valor = parseFloat(valor);
		var nuevo = (actual * valor / 100) + actual;
		document.getElementById("M_"+grado).value = "";
	} else {
		var valor = document.getElementById("M_"+grado).value; valor = parseFloat(valor);
		var nuevo = actual + valor;
		document.getElementById("P_"+grado).value = "";
	}
	
	if (nuevo == 0 || isNaN(nuevo)) 	
		document.getElementById("N_"+grado).value = "";
	else	
		document.getElementById("N_"+grado).value = setNumberFormat(nuevo, 2, ",", ".");
}


function validarAjusteSalarial(form){
	var valores = "";
	var error = false;
	var categoria = document.getElementById("fcategoria").value;
	var periodo = document.getElementById("periodo").value; periodo = periodo.trim();
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.type=="checkbox" && n.name=="grados" && n.checked==true) {
			if (document.getElementById("N_"+n.id).value == "") {
				alert("¡El grado " + n.id + " no puede tener un monto total vacio!");
				document.getElementById("P_"+n.id).focus()
				error = true;
				break;
			} else {
				if (valores=="") valores = n.id + "|" + document.getElementById("P_"+n.id).value + "|" + document.getElementById("M_"+n.id).value;
				else valores += ";" + n.id + "|" + document.getElementById("P_"+n.id).value + "|" + document.getElementById("M_"+n.id).value;
			}
		}
	}
	
	if (periodo == "") alert("¡Debe ingresar un periodo!");
	else if (!error) {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=AJUSTE-SALARIAL-POR-GRADO&accion=GUARDAR&periodo="+periodo+"&categoria="+categoria+"&valores="+valores);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp.trim() != "") alert ("¡"+resp+"!");
				else cargarPagina(form, "ajuste_salarial_por_grado.php?filtrar=DEFAULT");
			}
		}
	} 
}

//------------------------------------------------------------//

//------------------------------------------------------------//
//	PERFIL DE CONCEPTOS
function verificarConceptoPerfil(form, accion) {
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	
	if (descripcion == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CONCEPTO-PERFIL&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else location.href="conceptos_perfil.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

function mostrarListaConceptoProcesoPerfil(proceso) {
	var selproceso = document.getElementById("selproceso").value;
	document.getElementById("selproceso").value = proceso;
	
	document.getElementById("listaDetalles_"+proceso).style.display = "block";
	document.getElementById("listaDetalles_"+selproceso).style.display = "none";
	document.getElementById("selconcepto").value = "";
}

function listaPartidaConceptoPerfil(form) {
	var selconcepto = document.getElementById("selconcepto").value;	
	if (selconcepto == "") alert("¡Debe seleccionar un detalle!");
	else {
		window.open("lista_clasificador_presupuestario.php?destino=selPartidaConceptoPerfil&cod="+selconcepto, "wLista", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1100, left=50, top=50, resizable=yes");
	}
}

function listaCuentasContables(form, columna) {
	var selconcepto = document.getElementById("selconcepto").value;	
	if (selconcepto == "") alert("¡Debe seleccionar un detalle!");
	else {
		window.open("listado_cuentas_contables.php?destino=selCuentaContablePerfil&cod="+selconcepto+"&columna="+columna, "wLista", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1100, left=50, top=50, resizable=yes");
	}
}

function guardarDetallesConceptosPerfil() {	
	var codperfil = document.getElementById("codperfil").value;
	
	var detalles = "";
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "txtconcepto") detalles += n.value + "|";
		if (n.name == "partida") detalles += n.value + "|";
		if (n.name == "debe") detalles += n.value + "|";
		if (n.name == "debecc") detalles += n.checked + "|";
		if (n.name == "haber") detalles += n.value + "|";
		if (n.name == "habercc") detalles += n.checked + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
		
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax_nomina.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=CONCEPTO-PERFIL&accion=DETALLES&codperfil="+codperfil+"&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp=ajax.responseText;
			if (resp!=0) alert ("¡"+resp+"!");
			else { window.close(); }
		}
	}
}

function setFlagDebe(concepto) {
	var haber = document.getElementById("haber_"+concepto).value;
	if (haber != "") {
		document.getElementById("debe_"+concepto).value = haber;
		document.getElementById("haber_"+concepto).value = "";
	}
}

function setFlagHaber(concepto) {
	var debe = document.getElementById("debe_"+concepto).value;
	if (debe != "") {
		document.getElementById("haber_"+concepto).value = debe;
		document.getElementById("debe_"+concepto).value = "";
	}
}
//------------------------------------------------------------//

//	VOUCHER DE NOMINA
function abrirVoucherNomina(form) {
	var registro = document.getElementById("registro").value;
	var fecha = document.getElementById("fecha").value;
	
	window.open("voucher_nomina_provision.php?registro="+registro+"&fecha="+fecha, "wProvision", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=600, width=1100, left=50, top=50, resizable=yes");
	return false;
}

function generarVoucher(form) {	
	var organismo = document.getElementById("organismo").value.trim();
	var proceso = document.getElementById("proceso").value.trim();
	var nomina = document.getElementById("nomina").value.trim();
	var periodo = document.getElementById("periodo").value.trim();	
	var descripcion = document.getElementById("descripcion").value.trim();
	var fecha = document.getElementById("fecha").value.trim();
	var codingresado = document.getElementById("codingresado").value.trim();
	var codvoucher = document.getElementById("codvoucher").value.trim();
	var nrovoucher = document.getElementById("nrovoucher").value.trim();
	var codaprobado = document.getElementById("codaprobado").value.trim();
	var codsistemafuente = document.getElementById("codsistemafuente").value.trim();
	
	// distribucion
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "_codcuenta") detalles += n.value + "|";
		if (n.name == "_monto") detalles += n.value + "|";
		if (n.name == "_comentarios") detalles += n.value + "|";
		if (n.name == "_tiposaldo") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (!valFecha(fecha)) alert("¡ERROR: Formato de fecha incorrecta!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_nomina_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=VOUCHER&organismo="+organismo+"&proceso="+proceso+"&nomina="+nomina+"&periodo="+periodo+"&descripcion="+descripcion+"&fecha="+fecha+"&codingresado="+codingresado+"&codvoucher="+codvoucher+"&nrovoucher="+nrovoucher+"&codaprobado="+codaprobado+"&codsistemafuente="+codsistemafuente+"&detalles="+detalles);
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
//------------------------------------------------------------//


//--------------------------------FUNCIONES PARA REALIZAR EL CHECKSUM DEL ARCHIVO A BANCO--------------------------//
function checkSumTxtNomina(form) {
	var num = 0;
	var seleccionados = "";
	var empleados = "";
	var organismo = document.getElementById("forganismo").value;
	//var nomina = document.getElementById("ftiponom").value;
	var periodo = document.getElementById("fperiodo").value;
	var nombre_archivo = document.getElementById("archivo").value;
	var proceso = document.getElementById("ftproceso");
	var codproceso = proceso.value;
	var nomproceso = proceso.options[proceso.selectedIndex].text;
	
	if (organismo == "" || periodo == "" || codproceso == "" || nombre_archivo == "") alert("¡Debe ingresar todos los valores del filtro!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "checksum_txt_nomina_venezuela.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codproceso="+codproceso+"&nomproceso="+nomproceso+"&periodo="+periodo+"&organismo="+organismo+"&nombre_archivo="+nombre_archivo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				if(ajax.responseText == "0")
				{
					document.getElementById('capaRespuesta').innerHTML = "<span style=\"color:#FF0000;font-size:16px;\">Los archivos son diferentes</span>";
					
				} else if(ajax.responseText == "1") {
				
					document.getElementById('capaRespuesta').innerHTML = "<span style=\"color:#009900;font-size:16px;\">Los archivos son iguales</span>";
				}
				//window.open("descarga_txt.php?nombre_archivo="+nombre_archivo, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
			}
		}
	}
	return false;
}

function uploadFile( file ){

	var nombreArchivo = 'archivoAVerificar';
	
	//5MB
	var limit = 1048576*8,xhr;


	console.log( limit  );

	if( file ){
		if( file.size < limit ){
			//if( !confirm('Cargar archivo?') ){return;}

			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e){
				alert('Archivo cargado!');
				
			}, false);

			xhr.upload.addEventListener('error',function(e){
				alert('Ha habido un error :/');
			}, false);

			xhr.open('POST','checksum_txt_nomina_venezuela.php?nombreArchivo='+nombreArchivo+'&caso=cargarArchivoCheck&');

			    xhr.setRequestHeader("Cache-Control", "no-cache");
			    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			    xhr.setRequestHeader("X-File-Name", file.name);

			    xhr.send(file);

		} else {
			
			alert('El archivo es mayor que 2MB!');
			return;
		}
	}
	

}

