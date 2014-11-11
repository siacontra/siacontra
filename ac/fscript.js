// CREO ESTE METODO PARA PODER ELIMINAR LOS ESPACIOS EN BLANCOS AL PRINCIPIO Y AL FINAL DE CADA CAMPO 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }

var MAXLIMIT=30;

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

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL HACER CLICK EL PUNTERO DEL MOUSE SOBRE ELLA (MULTI SELECCION)
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
//	--------------------------------------

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
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosNEEP(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btEliminar=document.getElementById("btEliminar");
	var btPDF=document.getElementById("btPDF");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) btEditar.disabled=true;
	if (del=="N" || !rows) btEliminar.disabled=true;
	if (!rows) {
		btPDF.disabled=true;
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosAgregar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btAgregar=document.getElementById("btAgregar");
	//
	if (update=="N" || !rows) btAgregar.disabled=true;
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
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosA(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btAprobar = document.getElementById("btAprobar");
	var btPDF=document.getElementById("btPDF");
	//
	if (update == "N" || !rows) { btAprobar.disabled = true; btPDF.disabled=true; }
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosP(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btProcesar = document.getElementById("btProcesar");
	var btPDF=document.getElementById("btPDF");
	//
	if (update == "N" || !rows) { btProcesar.disabled = true; btPDF.disabled=true; }
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalUsuarios(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btEliminar=document.getElementById("btEliminar");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) btEditar.disabled=true;
	if (del=="N" || !rows) btEliminar.disabled=true;
	if (!rows) btVer.disabled=true;
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalAutorizacion(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	//
	if (update=="N" || !rows) btEditar.disabled=true;
	if (!rows) btVer.disabled=true;
}
//	FUNCION QUE BLOQUEA/DESBLOQUEA LAS OPCIONES DEL MENU DE EMPLEADOS
function totalEmpleados(rows) {
	if (rows) {
		var btVacaciones=document.getElementById("btVacaciones"); btVacaciones.disabled=false;
		var btPatrimonio=document.getElementById("btPatrimonio"); btPatrimonio.disabled=false;
		var btPermisos=document.getElementById("btPermisos"); btPermisos.disabled=false;
		var btInstruccion=document.getElementById("btInstruccion"); btInstruccion.disabled=false;
		var btReferencia=document.getElementById("btReferencia"); btReferencia.disabled=false;
		var btExperiencia=document.getElementById("btExperiencia"); btExperiencia.disabled=false;
		var btMerito=document.getElementById("btMerito"); btMerito.disabled=false;
		var btBancaria=document.getElementById("btBancaria"); btBancaria.disabled=false;
		var btFamiliar=document.getElementById("btFamiliar"); btFamiliar.disabled=false;
		var btHistoria=document.getElementById("btHistoria"); btHistoria.disabled=false;
		var btNivelacion=document.getElementById("btNivelacion"); btNivelacion.disabled=false;
		var btDocumentos=document.getElementById("btDocumentos"); btDocumentos.disabled=false;
	} else {		
		var btVacaciones=document.getElementById("btVacaciones"); btVacaciones.disabled=true;
		var btPatrimonio=document.getElementById("btPatrimonio"); btPatrimonio.disabled=true;
		var btPermisos=document.getElementById("btPermisos"); btPermisos.disabled=true;
		var btInstruccion=document.getElementById("btInstruccion"); btInstruccion.disabled=true;
		var btReferencia=document.getElementById("btReferencia"); btReferencia.disabled=true;
		var btExperiencia=document.getElementById("btExperiencia"); btExperiencia.disabled=true;
		var btMerito=document.getElementById("btMerito"); btMerito.disabled=true;
		var btBancaria=document.getElementById("btBancaria"); btBancaria.disabled=true;
		var btFamiliar=document.getElementById("btFamiliar"); btFamiliar.disabled=true;
		var btHistoria=document.getElementById("btHistoria"); btHistoria.disabled=true;
		var btNivelacion=document.getElementById("btNivelacion"); btNivelacion.disabled=true;
		var btDocumentos=document.getElementById("btDocumentos"); btDocumentos.disabled=true;
	}
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
function totalGuardar(rows) {
	if (rows) {
		var btGuardar=document.getElementById("btGuardar"); btGuardar.disabled=false;
	} else {		
		var btGuardar=document.getElementById("btGuardar"); btGuardar.disabled=true;
	}
}
//	FUNCION QUE BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalCompetencias(rows) {
	//
	if (rows) {
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false;
	} else {
		var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true;
	}
}
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
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalContratos(tab, rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	var btNuevo=document.getElementById("btNuevo");
	var btAbrir=document.getElementById("btAbrir");
	var btImprimir=document.getElementById("btImprimir");
	var btVer=document.getElementById("btVer");
	var btEditar=document.getElementById("btEditar");
	var btEliminar=document.getElementById("btEliminar");
	//
	if (admin=="S") {
		switch (tab) {
			case 1:
				numreg.innerHTML="Contratos Vigentes: "+rows;
				if (rows) { btAbrir.disabled=false; btImprimir.disabled=false; btEditar.disabled=false; btVer.disabled=false; btEliminar.disabled=false; } 
				else { btAbrir.disabled=true; btImprimir.disabled=true; btEditar.disabled=true; btVer.disabled=true; btEliminar.disabled=true; } 
				btNuevo.disabled=true;
				break;
			case 2:
				numreg.innerHTML="Contratos Vencidos: "+rows;
				if (rows) { btNuevo.disabled=false; btVer.disabled=false; } else { btNuevo.disabled=true; btVer.disabled=true; }
				btAbrir.disabled=true; 
				btImprimir.disabled=true; 
				btEditar.disabled=true;
				btEliminar.disabled=true;
				break;
			case 3:
				numreg.innerHTML="Contratos Por Vencer: "+rows;
				if (rows) btVer.disabled=false; else btVer.disabled=true;
				btAbrir.disabled=true; 
				btImprimir.disabled=true; 
				btEditar.disabled=true;
				btEliminar.disabled=true;
				btNuevo.disabled=true;
				break;
			case 4:
				numreg.innerHTML="Personas sin Contrato: "+rows;
				if (rows) btNuevo.disabled=false; else btNuevo.disabled=true;
				btAbrir.disabled=true;
				btImprimir.disabled=true;
				btEditar.disabled=true;
				btVer.disabled=true;
				btEliminar.disabled=true;
				break;
		}
	} else {
		if (insert=="N") btNuevo.disabled=true;
		if (update=="N") btEditar.disabled=true;
		if (del=="N") btEliminar.disabled=true;
		if (!rows) { btVer.disabled=true; btAbrir.disabled=true; btImprimir.disabled=true; }
		switch (tab) {
			case 1:
				numreg.innerHTML="Contratos Vigentes: "+rows;
				break;
			case 2:
				numreg.innerHTML="Contratos Vencidos: "+rows;
				break;
			case 3:
				numreg.innerHTML="Contratos Por Vencer: "+rows;
				break;
			case 4:
				numreg.innerHTML="Personas sin Contrato: "+rows;
				break;
		}
	}
}
//	FUNCION QUE BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalPuestos(rows) {
	//
	if (rows) { var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=false; }
	else { var btBorrar=document.getElementById("btBorrar"); btBorrar.disabled=true; }
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalPermisos(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	//
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btPDF=document.getElementById("btPDF");
	var btImprimir=document.getElementById("btImprimir");
	var btAnular=document.getElementById("btAnular");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N") { btEditar.disabled=true; btAnular.disabled=true; }
	if (!rows) { btVer.disabled=true; btImprimir.disabled=true; btPDF.disabled=true; }
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalPermisosLista(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btVer=document.getElementById("btVer");
	var btRechazar=document.getElementById("btRechazar");
	var btAprobar=document.getElementById("btAprobar");
	//
	if (update=="N") { btRechazar.disabled=true; btAprobar.disabled=true; }
	if (!rows) btVer.disabled=true;
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalPermisosEmpleado(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalPostulantes(rows) {
	if (rows) {
		var btCarta=document.getElementById("btCarta"); btCarta.disabled=false;
	} else {
		var btCarta=document.getElementById("btCarta"); btCarta.disabled=true;
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRequerimientos(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btPDF=document.getElementById("btPDF");
	var btAsignar=document.getElementById("btAsignar");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) { btEditar.disabled=true; btAsignar.disabled=true; }
	if (!rows) {
		btVer.disabled=true;
		btPDF.disabled=true;
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRequerimientosAprobar(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btVer=document.getElementById("btVer");
	var btPDF=document.getElementById("btPDF");
	var btAprobar=document.getElementById("btAprobar");
	//
	if (update=="N" || !rows) btAprobar.disabled=true;
	if (!rows) {
		btVer.disabled=true;
		btPDF.disabled=true;
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalCapacitaciones(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btNuevo=document.getElementById("btNuevo");
	var btEditar=document.getElementById("btEditar");
	var btVer=document.getElementById("btVer");
	var btPDF=document.getElementById("btPDF");
	var btAprobar=document.getElementById("btAprobar");
	var btIniciar=document.getElementById("btIniciar");
	var btTerminar=document.getElementById("btTerminar");
	//
	if (insert=="N") btNuevo.disabled=true;
	if (update=="N" || !rows) { btEditar.disabled=true; btAprobar.disabled=true; btIniciar.disabled=true; btTerminar.disabled=true; }
	if (!rows) {
		btVer.disabled=true;
		btPDF.disabled=true;
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalConstancias(rows, admin, insert, update, del) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	var btAbrir=document.getElementById("btAbrir");
	//
	if (!rows) {
		btAbrir.disabled=true;
	}
}

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

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion2(form, pagina, target, param) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		if (target=="SELF") cargarPagina(form, pagina);
		else { pagina=pagina+"&registro="+codigo; cargarVentana(form, pagina, param); }
	}
}

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
				ajax.open("POST", "fphp_ajax.php", true);
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

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarDato(form, pagina, foraneo, modulo) {
	var filas = form.registro.length;
	var filtro = form.filtro.value;
	var limit = form.limit.value;
	var codigo = "";
	if (filas>1) { for (i=0; i<filas; i++) if (form.registro[i].checked==true) codigo=form.registro[i].value;	} 
	else if (form.registro.checked==true) codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			if (foraneo) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo="+modulo+"&accion=ELIMINAR&codigo="+codigo);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var error=ajax.responseText;
						if (error!=0) alert ("¡"+error+"!");
						else {
							form.method="POST";
							form.action=pagina+"&filtro="+filtro+"&limit=0";
							form.submit();
						}
					}
				}
			} else cargarPagina(form, pagina);
		}
	}
}

//	FUNCION QUE FORZA UN CAMPO A ESCRIBIR LO QUE UNO QUIERA
function forzarFecha(form, campo) {
	checkOK = "0123456789-/";
	linea = "";
	if (campo.value.length!=0) {
		for (i = 0; i < campo.value.length; i++) { 
			ch = campo.value.charAt(i); 
			for (j = 0; j < checkOK.length; j++) 
				if (ch == checkOK.charAt(j)) linea = linea + ch; 
		}
		campo.value=linea; 
	}
}
function forzarReal(form, campo) {
	checkOK = "0123456789,";
	linea = "";
	if (campo.value.length!=0) {
		for (i = 0; i < campo.value.length; i++) { 
			ch = campo.value.charAt(i); 
			for (j = 0; j < checkOK.length; j++) 
				if (ch == checkOK.charAt(j)) linea = linea + ch; 
		}
		campo.value=linea; 
	}
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

//	FUNCION QUE DEVUELVE SI DOS INTERVALOS DE FECHA SON CORRECTOS
function esVFecha(fechad, fechah) {
	var dma = fechad.split("-");
	if (typeof dma[0]=="undefined") dma[0]="";
	if (typeof dma[1]=="undefined") dma[1]="";
	if (typeof dma[2]=="undefined") dma[2]="";	
	var d = new String (dma[0]); d=d.trim();
	var m = new String (dma[1]); m=m.trim();
	var a = new String (dma[2]); a=a.trim();
	if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	if (!esFecha) {
		var dma = fechad.split("/");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";	
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	}
	var diad=d; var mesd=m; var anniod=a;
	//
	var dma = fechah.split("-");
	if (typeof dma[0]=="undefined") dma[0]="";
	if (typeof dma[1]=="undefined") dma[1]="";
	if (typeof dma[2]=="undefined") dma[2]="";	
	var d = new String (dma[0]); d=d.trim();
	var m = new String (dma[1]); m=m.trim();
	var a = new String (dma[2]); a=a.trim();
	if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	if (!esFecha) {
		var dma = fechah.split("/");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";	
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	}
	var diah=d; var mesh=m; var annioh=a;
	//
	if ((anniod>annioh) || (annioh==anniod && mesd>mesh) || (annioh==anniod && mesh==mesd && diad>diah)) return false; else return true;
}

//	FUNCION QUE DEVUELVE SI DOS INTERVALOS DE FECHA SON CORRECTOS COMPARADO CON LA FECHA ACTUAL
function esAFecha(fechad) {
	var dma = fechad.split("-");
	if (typeof dma[0]=="undefined") dma[0]="";
	if (typeof dma[1]=="undefined") dma[1]="";
	if (typeof dma[2]=="undefined") dma[2]="";	
	var d = new String (dma[0]); d=d.trim();
	var m = new String (dma[1]); m=m.trim();
	var a = new String (dma[2]); a=a.trim();
	if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	if (!esFecha) {
		var dma = fechad.split("/");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";	
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) var esFecha=false; else var esFecha=true;
	}	
	var diad = new Number (d);
	var mesd = new Number (m);
	var anniod = new Number (a);
	//
	fechaActual = new Date();
	var diah=fechaActual.getDate();
	var mesh=1+(fechaActual.getMonth());
	var annioh= fechaActual.getFullYear();
	//
	if ((anniod>annioh) || (annioh==anniod && mesd>mesh) || (annioh==anniod && mesh==mesd && diad>diah)) return false; else return true;
}

//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getEdad(form, fecha) {
	var error=0;
	var listo=0;
	if (fecha.length<10) error=1;
	else {
		fechaActual = new Date();
		var diaActual = fechaActual.getDate();
		var mesActual = 1+(fechaActual.getMonth());
		var annioActual = fechaActual.getFullYear();
		//
		var dma = fecha.split("-");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) {
			var dma = fecha.split("/");
			if (typeof dma[0]=="undefined") dma[0]="";
			if (typeof dma[1]=="undefined") dma[1]="";
			if (typeof dma[2]=="undefined") dma[2]="";	
			var d = new String (dma[0]); d=d.trim();
			var m = new String (dma[1]); m=m.trim();
			var a = new String (dma[2]); a=a.trim();
		}
		var dia = new Number (d);
		var mes = new Number (m);
		var annio = new Number (a);
		//
		var dias = new Number (0);
		var meses = new Number (0);
		var annios = new Number (0);
		//
		var fechaCorrecta=esFecha(fecha);
		if (!fechaCorrecta || annio>annioActual || (annio==annioActual && mes>mesActual) || (annio==annioActual && mes==mesActual && dia>diaActual)) error=2;
		else {
			var diasMes = new Array(13);
			diasMes[1]=31; diasMes[3]=31; diasMes[4]=30; diasMes[5]=31; diasMes[6]=30; diasMes[7]=31;
			diasMes[8]=31; diasMes[9]=30; diasMes[10]=31; diasMes[11]=30; diasMes[12]=31;
			if (annioActual%4==0) diasMes[2]=29; else diasMes[2]=28;			
			while (listo==0) {
				if (annio==annioActual && mes==mesActual) {
					if (diaActual>=dia) dias=diaActual-dia;
					else {
						if ((mesActual-1)==0) dias=(31-dia)+diaActual;
						else dias=(diasMes[mesActual-1]-dia)+diaActual;
						meses--;
					}
					if (meses==12) {annios++; meses=0;}
					listo=1;
				} else {							
					if (mes==12) {
						mes=0; annio++;
					}
					if (meses==12) {
						meses=0; annios++;
					}
					mes++; meses++;
				}
			}
			form.anac.value=annios;
			form.mnac.value=meses;
			form.dnac.value=dias;
		}
	}
	if (error!=0) {
		form.anac.value="";
		form.mnac.value="";
		form.dnac.value="";
	}
}

//	FUNCION PARA OBTENER LA EDAD DE UNA FECHA INGRESADA
function getEdadAMD(form, fecha, fechaActual) {
	var error=0;
	var listo=0;
	if (fecha.length < 10 || fechaActual.length < 10) error=1;
	else {
		var actual = fechaActual.split("-");
		var diaActual = actual[0];
		var mesActual = actual[1];
		var annioActual = actual[2];
		//
		var dma = fecha.split("-");
		if (typeof dma[0]=="undefined") dma[0]="";
		if (typeof dma[1]=="undefined") dma[1]="";
		if (typeof dma[2]=="undefined") dma[2]="";
		var d = new String (dma[0]); d=d.trim();
		var m = new String (dma[1]); m=m.trim();
		var a = new String (dma[2]); a=a.trim();	
		if (d=="" || m=="" || a=="" || d.length<2 || m.length<2 || a.length<4) {
			var dma = fecha.split("/");
			if (typeof dma[0]=="undefined") dma[0]="";
			if (typeof dma[1]=="undefined") dma[1]="";
			if (typeof dma[2]=="undefined") dma[2]="";	
			var d = new String (dma[0]); d=d.trim();
			var m = new String (dma[1]); m=m.trim();
			var a = new String (dma[2]); a=a.trim();
		}
		var dia = new Number (d);
		var mes = new Number (m);
		var annio = new Number (a);
		//
		var dias = new Number (0);
		var meses = new Number (0);
		var annios = new Number (0);
		//
		var fechaCorrecta=esFecha(fecha);
		if (!fechaCorrecta || annio>annioActual || (annio==annioActual && mes>mesActual) || (annio==annioActual && mes==mesActual && dia>diaActual)) error=2;
		else {
			var diasMes = new Array(13);
			diasMes[1]=31; diasMes[3]=31; diasMes[4]=30; diasMes[5]=31; diasMes[6]=30; diasMes[7]=31;
			diasMes[8]=31; diasMes[9]=30; diasMes[10]=31; diasMes[11]=30; diasMes[12]=31;
			if (annioActual%4==0) diasMes[2]=29; else diasMes[2]=28;			
			while (listo==0) {
				if (annio==annioActual && mes==mesActual) {
					if (diaActual>=dia) dias=diaActual-dia;
					else {
						if ((mesActual-1)==0) dias=(31-dia)+diaActual;
						else dias=(diasMes[mesActual-1]-dia)+diaActual;
						meses--;
					}
					if (meses==12) {annios++; meses=0;}
					listo=1;
				} else {							
					if (mes==12) {
						mes=0; annio++;
					}
					if (meses==12) {
						meses=0; annios++;
					}
					mes++; meses++;
				}
			}
			document.getElementById("anios").value = annios;
			document.getElementById("meses").value = meses;
			document.getElementById("dias").value = dias;
		}
	}
	if (error!=0) {
		document.getElementById("anios").value = "";
		document.getElementById("meses").value = "";
		document.getElementById("dias").value = "";
	}
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

function esHora(hora) {
	var esHora=true;
	var hm=hora.split(":");
	var h=new Number(hm[0]);
	var m=new Number(hm[1]);
	var s=new Number(hm[2]);
	if (hm.length!=3) esHora=false;
	if (h<1 || h>12 || m<0 || m>59 || s<0 || s>59) esHora=false;
	return esHora;
}

function esDateTime(desde, hasta) {
	var esDateTime=true;
	var fhmdesde=desde.split(" ");
	var fhmhasta=hasta.split(" ");
	var esFechaDesde=esFecha(fhmdesde[0]);
	var esFechaHasta=esFecha(fhmhasta[0]);
	var esHoraDesde=esHora(fhmdesde[1]);
	var esHoraHasta=esHora(fhmhasta[1]);
	var esVigenciaFecha=esVFecha(fhmdesde[0], fhmhasta[0]);	
	var horadesde = fhmdesde[1].split(":");	
	var horahasta = fhmhasta[1].split(":");
	var hd = horadesde[0] - 0;
	var md = horadesde[1] - 0;
	var hh = horahasta[0] - 0;
	var mh = horahasta[1] - 0;
	if (fhmdesde[2] == "PM" && hd != 12) hd = hd + 12;
	if (fhmdesde[2] == "AM" && hd == 12) hd = 0;
	if (fhmhasta[2] == "PM" && hh != 12) hh = hh + 12; 
	if (fhmhasta[2] == "AM" && hh == 12) hh = 0;
	if (!esFechaDesde || !esFechaHasta || !esHoraDesde || !esHoraHasta || !esVigenciaFecha) esDateTime=false;
	if ((fhmdesde[0] == fhmhasta[0]) && (hd == hh) && (md > mh)) esDateTime = false;
	if ((fhmdesde[0] == fhmhasta[0]) && (hd > hh)) esDateTime = false;
	return esDateTime;
}

// FUNCION PARA BLOQUEAR/DESBLOQUEAR CAMPOS DE TIPOS DE VALOR DE PARAMETROS
function chkTipoParam(form, valor) {
	if (valor=="T") { form.texto.disabled=false; form.numero.disabled=true; form.fecha.disabled=true; form.texto.focus(); }
	else if (valor=="N") { form.texto.disabled=true; form.numero.disabled=false; form.fecha.disabled=true; form.numero.focus(); }
	else if (valor=="F") { form.texto.disabled=true; form.numero.disabled=true; form.fecha.disabled=false; form.fecha.focus(); }
}

//	FUNCION PARA MOSTRAR MENSAJE DE LAS OPCIONES
function imgEmpleado(form, msj) {
	switch (msj) {
		case 0:
			form.imgMenu.src="imagenes/blank.jpg";
			break;
		case 1:
			form.imgMenu.src="imagenes/vacaciones.jpg";
			break;
		case 2:
			form.imgMenu.src="imagenes/patrimonio.jpg";
			break;
		case 3:
			form.imgMenu.src="imagenes/permisos.jpg";
			break;
		case 4:
			form.imgMenu.src="imagenes/instruccion.jpg";
			break;
		case 5:
			form.imgMenu.src="imagenes/referencias.jpg";
			break;
		case 6:
			form.imgMenu.src="imagenes/documentos.jpg";
			break;
		case 7:
			form.imgMenu.src="imagenes/experiencia.jpg";
			break;
		case 8:
			form.imgMenu.src="imagenes/meritos.jpg";
			break;
		case 9:
			form.imgMenu.src="imagenes/bancaria.jpg";
			break;
		case 10:
			form.imgMenu.src="imagenes/familiar.jpg";
			break;
		case 11:
			form.imgMenu.src="imagenes/historial.jpg";
			break;
		case 12:
			form.imgMenu.src="imagenes/nivelaciones.jpg";
			break;
		case 13:
			form.imgMenu.src="imagenes/conceptos.jpg";
			break;
	}	
}

//	MUESTRA EL LOGO
function setLogo() {	
	var logo=document.getElementById("logo").value; logo=logo.trim();
	var path=document.getElementById("path").value;
	if (logo=="") var src=path+"blank.png"; else var src=path+logo;
	document.getElementById("img_logo").src=src;
}

//	MUESTRA LA FOTO
function setFoto() {	
	var foto=document.getElementById("foto").value; foto=foto.trim();
	var path=document.getElementById("path").value;
	if (foto=="") var src=path+"blank.png"; else var src=path+foto;
	document.getElementById("img_foto").src=src;
}

//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledOrganismo(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; 
	else { form.forganismo.disabled=true; form.fdependencia.disabled=true; form.chkdependencia.checked=false; form.forganismo.value=""; form.fdependencia.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledOrganismo2(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; 
	else form.forganismo.disabled=true;
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledEdoReg(form) {
	if (form.chkedoreg.checked) form.fedoreg.disabled=false; 
	else { form.fedoreg.disabled=true; form.fedoreg.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTTrabajador(form) {
	if (form.chkttrabajador.checked) form.fttrabajador.disabled=false; 
	else { form.fttrabajador.disabled=true; form.fttrabajador.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledMes(form) {
	if (form.chkmes.checked) form.fmes.disabled=false; 
	else { form.fmes.disabled=true; form.fmes.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledDependencia(form) {
	if (form.chkorganismo.checked && form.chkdependencia.checked) form.fdependencia.disabled=false;
	else { form.fdependencia.disabled=true; form.fdependencia.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledSitTra(form) {
	if (form.chksittra.checked) form.fsittra.disabled=false; 
	else { form.fsittra.disabled=true; form.fsittra.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTipoNom(form) {
	if (form.chktiponom.checked) form.ftiponom.disabled=false; 
	else { form.ftiponom.disabled=true; form.ftiponom.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledBuscar(form) {
	if (form.chkbuscar.checked) { form.fbuscar.disabled=false; form.sltbuscar.disabled=false; } 
	else { form.fbuscar.disabled=true; form.sltbuscar.disabled=true; form.fbuscar.value=""; form.sltbuscar.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTipoTra(form) {
	if (form.chktipotra.checked) form.ftipotra.disabled=false; 
	else { form.ftipotra.disabled=true; form.ftipotra.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledPersona(form) {
	if (form.chkpersona.checked) { form.fpersona.disabled=false; form.sltpersona.disabled=false; } 
	else { form.fpersona.disabled=true; form.fpersona.value=""; form.sltpersona.disabled=true; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledEdad(form) {
	if (form.chkedad.checked) { form.fedad.disabled=false; form.sltedad.disabled=false; } 
	else { form.fedad.disabled=true; form.sltedad.disabled=true; form.fedad.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCargaEdad(form) {
	if (form.chkedad.checked) { form.fedadd.disabled=false; form.fedadh.disabled=false; } 
	else { form.fedadd.disabled=true; form.fedadh.disabled=true; form.fedadd.value=""; form.fedadh.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFIngreso(form) {
	if (form.chkfingreso.checked) { form.ffingresod.disabled=false; form.ffingresoh.disabled=false; } 
	else { form.ffingresod.disabled=true; form.ffingresoh.disabled=true; form.ffingresod.value=""; form.ffingresoh.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTipoCon(form) {
	if (form.chktcon.checked) form.ftcon.disabled=false; 
	else {form.ftcon.disabled=true; form.ftcon.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledParentesco(form) {
	if (form.chkparentesco.checked) form.fparentesco.disabled=false; 
	else { form.fparentesco.disabled=true; form.fparentesco.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledVContrato(form) {
	if (form.chkvcontrato.checked) { form.fvcontratod.disabled=false; form.fvcontratoh.disabled=false; } 
	else { form.fvcontratod.disabled=true; form.fvcontratoh.disabled=true; form.fvcontratod.value=""; form.fvcontratoh.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFirma(form) {
	if (form.chkfirma.checked) { form.ffirmad.disabled=false; form.ffirmah.disabled=false; } 
	else { form.ffirmad.disabled=true; form.ffirmah.disabled=true; form.ffirmad.value=""; form.ffirmah.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFirmaContrato(form) {
	if (form.flagfirma.checked) form.firma.disabled=false; else form.firma.disabled=true;
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledOrdenarPersona(form) {
	if (form.chkordenar.checked) form.fordenar.disabled=false; 
	else { form.fordenar.disabled=true;  form.fordenar.value="";  }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledActualizarPersona(form) {
	if (form.chkactualizar.checked) form.factualizar.disabled=false; 
	else { form.factualizar.disabled=true;  form.factualizar.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCPersona(form) {
	if (form.chkcpersona.checked) form.fcpersona.disabled=false; 
	else { form.fcpersona.disabled=true;  form.fcpersona.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTPersona(form) {
	if (form.chktpersona.checked) form.ftpersona.disabled=false; 
	else { form.ftpersona.disabled=true;  form.ftpersona.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledBPersona(form) {
	if (form.chkbpersona.checked) form.fbpersona.disabled=false; 
	else { form.fbpersona.disabled=true;  form.fbpersona.value="";  }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledNDoc(form) {
	if (form.chkndoc.checked) form.fndoc.disabled=false; 
	else { form.fndoc.disabled=true;  form.fndoc.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledPerfilnomina(form) {
	if (form.chkperfilnom.checked) form.fperfilnom.disabled=false; 
	else { form.fperfilnom.disabled=true;  form.fperfilnom.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledPermiso(form) {
	if (form.chkpermiso.checked) form.fpermiso.disabled=false; 
	else { form.fpermiso.disabled=true;  form.fpermiso.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledEmpleado(form) {
	if (form.chkempleado.checked) form.fempleado.disabled=false; 
	else { form.fempleado.disabled=true;  form.fempleado.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledListadoEmpleado(form) {
	if (form.chkempleado.checked) form.btEmpleado.disabled=false;
	else { form.fempleado.value=""; form.fnomempleado=""; form.btEmpleado.disabled=true; } 
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledListadoResponsable(form) {
	if (form.chkresponsable.checked) form.btResponsable.disabled=false;
	else { form.fresponsable.value=""; form.fnomresponsable=""; form.btResponsable.disabled=true; } 
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledNomEmpleado(form) {
	if (form.chkempleado.checked) form.bt_examinar.disabled=false;
	else { form.bt_examinar.disabled=true; form.nomempleado.value=""; form.codempleado.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledStatus(form) {
	if (form.chkstatus.checked) form.fstatus.disabled=false; 
	else { form.fstatus.disabled=true;  form.fstatus.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFInicio(form) {
	if (form.chkinicio.checked) form.finicio.disabled=false; 
	else { form.finicio.disabled=true; form.finicio.value=""; } 
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFFin(form) {
	if (form.chkfin.checked) form.ffin.disabled=false; 
	else { form.ffin.disabled=true;  form.ffin.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFExpediente(form) {
	if (form.chkexpediente.checked) form.fexpediente.disabled=false; 
	else { form.fexpediente.disabled=true;  form.fexpediente.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFEmpleado(form) {
	if (form.chkempleado.checked) form.fempleado.disabled=false; 
	else { form.fempleado.disabled=true;  form.fempleado.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledSexo(form) {
	if (form.chksexo.checked) form.fsexo.disabled=false; 
	else { form.fsexo.disabled=true; form.fsexo.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledFSexo(form) {
	if (form.chkfsexo.checked) form.ffsexo.disabled=false; 
	else { form.ffsexo.disabled=true; form.ffsexo.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledGInstruccion(form) {
	if (form.chkginstruccion.checked) form.fginstruccion.disabled=false; 
	else {
		form.fginstruccion.disabled=true; 
		form.fprofesion.disabled=true;
		form.fprofesion.value="";
		form.chkprofesion.checked=false;
	}
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCargo(form) {
	if (form.chkcargo.checked) form.fcargo.disabled=false; 
	else { form.fcargo.disabled=true; form.fcargo.value="";}
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledEdadD(form) {
	if (form.chkedadd.checked) form.fedadd.disabled=false; 
	else { form.fedadd.disabled=true;  form.fedadd.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledEdadH(form) {
	if (form.chkedadh.checked) form.fedadh.disabled=false; 
	else { form.fedadh.disabled=true;  form.fedadh.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledArea(form) {
	if (form.chkarea.checked) form.farea.disabled=false; 
	else {
		form.farea.disabled=true; 
		form.fprofesion.disabled=true;
		form.fprofesion.value="";
		form.chkprofesion.checked=false;
	}
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledIdioma(form) {
	if (form.chkidioma.checked) form.fidioma.disabled=false; 
	else { form.fidioma.disabled=true;  form.fidioma.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledApellido(form) {
	if (form.chkapellido.checked) form.fapellido.disabled=false; 
	else { form.fapellido.disabled=true;  form.fapellido.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledProfesion(form) {
	if (form.chkprofesion.checked && form.chkginstruccion.checked && form.chkarea.checked) {
		form.fprofesion.disabled=false;
		getFProfesiones(form);
	}
	else form.fprofesion.disabled=true; 
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCursos(form) {
	if (form.chkcursos.checked) form.fcursos.disabled=false; 
	else { form.fcursos.disabled=true;  form.fcursos.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledTCursos(form) {
	if (form.chktcursos.checked) form.ftcursos.disabled=false; 
	else { form.ftcursos.disabled=true;  form.ftcursos.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCentros(form) {
	if (form.chkcentros.checked) form.fcentros.disabled=false; 
	else { form.fcentros.disabled=true;  form.fcentros.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledAnios(form) {
	if (form.chkanios.checked) form.fanios.disabled=false; 
	else { form.fanios.disabled=true;  form.fanios.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledBeneficas(form) {
	if (form.flagbeneficas.checked) form.beneficas.disabled=false; 
	else { form.beneficas.disabled=true;  form.beneficas.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledLaborales(form) {
	if (form.flaglaborales.checked) form.laborales.disabled=false; 
	else { form.laborales.disabled=true;  form.laborales.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCulturales(form) {
	if (form.flagculturales.checked) form.culturales.disabled=false; 
	else { form.culturales.disabled=true;  form.culturales.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledDeportivas(form) {
	if (form.flagdeportivas.checked) form.deportivas.disabled=false; 
	else { form.deportivas.disabled=true;  form.deportivas.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledReligiosas(form) {
	if (form.flagreligiosas.checked) form.religiosas.disabled=false; 
	else { form.religiosas.disabled=true;  form.religiosas.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledSociales(form) {
	if (form.flagsociales.checked) form.sociales.disabled=false; 
	else { form.sociales.disabled=true;  form.sociales.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledSOrganismo(form) {
	if (form.chkorganismo.checked) form.forganismo.disabled=false; else { form.forganismo.disabled=true; form.forganismo.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledPeriodo(form) {
	if (form.chkperiodo.checked) form.fperiodo.disabled=false; else { form.fperiodo.disabled=true; form.fperiodo.value=""; }
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR
function enabledCapacitacion(form) {
	if (form.chkcapacitacion.checked) form.fcapacitacion.disabled=false; else { form.fcapacitacion.disabled=true; form.fcapacitacion.value="";}
}

//	FUNCION PARA FORZAR A MANTENER CHEQUEADO
function forzarCheck(id) {
	document.getElementById(id).checked=true;
}

function enabledSNCProveedor(check) {
	document.getElementById("nrosnc").value = "";
	document.getElementById("nrosnc").disabled = !check;
	document.getElementById("femision").value = "";
	document.getElementById("femision").disabled = !check;
	document.getElementById("fvalidacion").value = "";
	document.getElementById("fvalidacion").disabled = !check;
}

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
// FUNCION QUE CALCULA LA FECHA DE UNA EDAD
function setFiltroEdad(edad) {
	fechaActual = new Date();
	var diaActual = fechaActual.getDate();
	var mesActual = 1+(fechaActual.getMonth());
	var annioActual = fechaActual.getFullYear();
	var diasMes = new Array(13);
	diasMes[1]=31; diasMes[3]=31; diasMes[4]=30; diasMes[5]=31; diasMes[6]=30; diasMes[7]=31;
	diasMes[8]=31; diasMes[9]=30; diasMes[10]=31; diasMes[11]=30; diasMes[12]=31;
	if (annioActual%4==0) diasMes[2]=29; else diasMes[2]=28;		
	var dd=diaActual+1; var md=mesActual; var ad=annioActual-edad-1;
	var dh=diaActual; var mh=mesActual; var ah=annioActual-edad;
	if (dd>diasMes[md]) {
		dd=1;
		if (md==12) { md=1; ad=aa-edad; }
		else md=md+1;
	}
	var desde=ad+"-"+md+"-"+dd;
	var hasta=ah+"-"+mh+"-"+dh;
	var fecha=desde+":"+hasta;
	return fecha;
}

//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selListado(busqueda, cod, nom, id, idvalor) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	opener.document.getElementById(id).value=idvalor;
	window.close();
}
function selEmpleadoFideicomiso(codpersona, codempleado, nomempleado, fdoc, anos, meses, dias, fingreso) {
	opener.document.getElementById("codpersona").value = codpersona;
	opener.document.getElementById("codempleado").value = codempleado;
	opener.document.getElementById("nomempleado").value = nomempleado;
	opener.document.getElementById("fdoc").value = fdoc;
	opener.document.getElementById("anos").value = anos;
	opener.document.getElementById("meses").value = meses;
	opener.document.getElementById("dias").value = dias;
	opener.document.getElementById("fingreso").value = fingreso;
	window.close();
}
function selListadoEmpleadoCargo(busqueda, cod, nom, id, idvalor, cargo) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value=registro;
	opener.document.getElementById(nom).value=busqueda;
	opener.document.getElementById(id).value=idvalor;
	opener.document.getElementById("cargo").value=cargo;
	window.close();
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selEmpleado(busqueda, campo) {
	var registro=document.getElementById("registro").value;
	if (campo==1) {
		opener.document.frmentrada.codempleado.value=registro;
		opener.document.frmentrada.nomempleado.value=busqueda;
	}
	else if (campo == 2) {
		opener.document.frmentrada.codaprueba.value=registro;
		opener.document.frmentrada.nomaprueba.value=busqueda;
	}
	else if (campo == 3) {
		opener.document.frmentrada.codpersona.value=registro;
		opener.document.frmentrada.nompersona.value=busqueda;
	}
	window.close();
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selPersonaOtros(busqueda, campo) {
	var registro=document.getElementById("registro").value;
	if (campo==1) {
		opener.document.frmentrada.coddemandante.value=registro;
		opener.document.frmentrada.nomdemandante.value=busqueda;
	}
	window.close();
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selPersona(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.codpersona.value=registro;
	opener.document.frmentrada.persona.value=busqueda;
	window.close();
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selPersona(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.codpersona.value=registro;
	opener.document.frmentrada.persona.value=busqueda;
	window.close();
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selConceptoRetencion(nomconcepto) {
	var codconcepto = document.getElementById("registro").value;
	var form = opener.document.getElementById("frmdetalles");
	var detalles = "";
	
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "chkdetalles") detalles += n.title + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=selConceptoRetencion&codconcepto="+codconcepto+"&nomconcepto="+nomconcepto);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				if (opener.document.getElementById(codconcepto)) alert("¡ERROR: Concepto ya ingresado!");
				else {
					var newTr = document.createElement("tr");
					newTr.className = "trListaBody";
					newTr.setAttribute("onclick", "mClk(this, 'seldetalle');");
					newTr.id = codconcepto;
					opener.document.getElementById("listaDetalles").appendChild(newTr);
					opener.document.getElementById(codconcepto).innerHTML = datos[1];
					window.close();
				}
			}
		}
	}
}
//	funcion para quitar un item de la lista de ordenes
function quitarDetalleConceptoRetencion(coddetalle) {
	if (confirm("¿Desea quitar este detalle de la lista?")) {
		var listaDetalles = document.getElementById("listaDetalles"); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle").value = "";
	}
}
//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selFamiliar(nombre, parentesco) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.familiar.value=registro;
	opener.document.frmentrada.nomfamiliar.value=nombre;
	opener.document.frmentrada.parentesco.value=parentesco;
	window.close();
}
//	FUNCION PARA SELECCIONAR UN CENTRO DE ESTUDIO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selCentro(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.codcentro.value=registro;
	opener.document.frmentrada.nomcentro.value=busqueda;
	window.close();
}
//	FUNCION PARA SELECCIONAR UN CURSO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selCurso(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.codcurso.value=registro;
	opener.document.frmentrada.nomcurso.value=busqueda;
	window.close();
}
//	FUNCION PARA SELECCIONAR UN CENTRO DE ESTUDIO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selCompetencia(busqueda) {
	var registro=document.getElementById("registro").value;
	var accion=document.getElementById("accion").value;
	if (accion=="INSERTAR") {
		opener.document.frmentrada.action=opener.document.frmentrada.action+"?accion=INSERTAR&competencia="+registro;
		opener.document.frmentrada.submit();
	} else {
		opener.document.frmentrada.codcompetencia.value=registro;
		opener.document.frmentrada.nomcompetencia.value=busqueda;
	}
	window.close();
}
//
function selCompetenciaEvaluacionDesempenio() {
	//	Competencias seleccionadas
	var detalles = "";
	var frmlista = document.getElementById("frmlista");
	for(i=0; n=frmlista.elements[i]; i++) {
		if (n.name == "competencia" && n.checked) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	//	---------------------------
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=selCompetenciaEvaluacionDesempenio&detalles="+detalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==1) {}
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split("||");
			opener.document.getElementById("nrodetalles_tab2").value = datos[0];
			opener.document.getElementById("cantdetalles_tab2").value = datos[0];
			opener.document.getElementById("listaDetalles_tab2").innerHTML = datos[1];
			opener.document.getElementById("listaDetalles_tab2_grafico").innerHTML = datos[2];
			window.close();
		}
	}
}
//------------------------------------------------------------//
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
//	FUNCION PARA ABRIR
function abrirContrato(form, pag, param) {
	var filas = form.sel.length;
	var codigo = "";
	if (filas>1) { 
		for (i=0; i<filas; i++) {
			if (form.sel[i].checked==true) {
				codigo=form.sel[i].value;
				pagina=pag+"?registro="+codigo;
				window.open(pagina, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
			}
		}
	} else if (form.sel.checked==true) {
		codigo=form.sel.value;
		pagina=pagina+"?registro="+codigo;
		window.open(pagina, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, "+param);
	}	
	if (codigo=="") msjError(1000);
}

//------------------------------------------------------------//

//------------------------------------------------------------//
//	MAESTRO DE APLICACIONES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarAplicacion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var voucher = new String (form.voucher.value); voucher=voucher.trim();
	var voucher_pd = new String (form.voucher_pd.value); voucher_pd=voucher_pd.trim();
	var voucher_pa = new String (form.voucher_pa.value); voucher_pa=voucher_pa.trim();
	var voucher_lp = new String (form.voucher_lp.value); voucher_lp=voucher_lp.trim();
	var voucher_tb = new String (form.voucher_tb.value); voucher_tb=voucher_tb.trim();
	var fuente = new String (form.fuente.value); fuente=fuente.trim();
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	var esPC=esPContable(periodo);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo=="" || descripcion=="") msjError(1010); 
	else if (periodo!="" && !esPC) msjError(1030);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=APLICACIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&periodo="+periodo+"&voucher="+voucher+"&voucher_pd="+voucher_pd+"&voucher_pa="+voucher_pa+"&voucher_lp="+voucher_lp+"&voucher_tb="+voucher_tb+"&fuente="+fuente+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="aplicaciones.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE PAISES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPais(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var obs = new String (form.obs.value); obs=obs.trim(); form.obs.value=obs;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PAISES&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&obs="+obs+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="paises.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE ESTADOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarEstado(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.pais.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ESTADOS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status+"&pais="+form.pais.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="estados.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE MUNICIPIOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarMunicipio(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.estado.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MUNICIPIOS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status+"&estado="+form.estado.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="municipios.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE CIUDADES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCiudad(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var postal = new String (form.postal.value); postal=postal.trim(); form.postal.value=postal;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.municipio.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CIUDADES&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&municipio="+form.municipio.value+"&postal="+postal);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("!"+error+"!");
				else location.href="ciudades.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE PAGO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoPago(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSPAGO&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tipospago.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE ORGANISMOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarOrganismo(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var codpersona = new String (form.codpersona.value); codpersona=codpersona.trim(); form.codpersona.value=codpersona;
	var descripcionc = new String (form.descripcionc.value); descripcionc=descripcionc.trim(); form.descripcionc.value=descripcionc;
	var rep = new String (form.rep.value); rep=rep.trim(); form.rep.value=rep;
	var docr = new String (form.docr.value); docr=docr.trim(); form.docr.value=docr;
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
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	var checkFecha=esFecha(fecha);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || descripcionc=="" || form.ciudad.value=="") msjError(1010);
	else if (fecha!="" && !checkFecha) msjError(1040);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ORGANISMOS&accion="+accion+"&codigo="+form.codigo.value+"&codpersona="+codpersona+"&descripcion="+descripcion+"&descripcionc="+descripcionc+"&rep="+rep+"&docr="+docr+"&www="+www+"&docf="+docf+"&fecha="+fecha+"&dir="+dir+"&ciudad="+form.ciudad.value+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&fax1="+fax1+"&fax2="+fax2+"&logo="+logo+"&status="+status+"&nreg="+nreg+"&treg="+treg);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="organismos.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE DEPENDENCIAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarDependencia(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.organismo.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DEPENDENCIAS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&organismo="+form.organismo.value+"&tel1="+form.tel1.value+"&tel2="+form.tel2.value+"&ext1="+form.ext1.value+"&ext2="+form.ext2.value+"&codpersona="+form.codpersona.value+"&codinterno="+form.codinterno.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="dependencias.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE DIVISIONES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarDivision(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.dependencia.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DIVISIONES&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&organismo="+form.organismo.value+"&dependencia="+form.dependencia.value+"&ext="+form.ext.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="divisiones.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE GRUPO OCUPACIONAL
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarGrupoOcupacional(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo == "" || descripcion == "") msjError(1010);
	else if (codigo.length != 4) alert("¡EL CODIGO DEBE TENER 4 DIGITOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GRUPOOCUPACIONAL&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="grupoocupacional.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE SERIE OCUPACIONAL
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarSerieOcupacional(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo == "" || descripcion=="" || form.grupo.value=="") msjError(1010);
	else if (codigo.length != 4) alert("¡EL CODIGO DEBE TENER 4 DIGITOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SERIEOCUPACIONAL&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&grupo="+form.grupo.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="serieocupacional.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE CARGO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoCargo(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSCARGO&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&definicion="+form.definicion.value+"&funcion="+form.funcion.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tiposcargo.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarRiesgo(form, accion) {
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var idDescripcion=document.getElementById("descripcion");	var descripcion = new String (idDescripcion.value);
	var idFuncion=document.getElementById("funcion");	var funcion = new String (idFuncion.value);
	var idDefinicion=document.getElementById("definicion");	var definicion = new String (idDefinicion.value);
	var riesgo = new String (form.riesgo.value); riesgo=riesgo.trim(); form.riesgo.value=riesgo;
	var causa = new String (form.causa.value); causa=causa.trim(); form.causa.value=causa;
	var consecuencia = new String (form.consecuencia.value); consecuencia=consecuencia.trim(); form.consecuencia.value=consecuencia;
	var prevencion = new String (form.prevencion.value); prevencion=prevencion.trim(); form.prevencion.value=prevencion;
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim(); form.secuencia.value=secuencia;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (riesgo=="") msjError(1050);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSCARGO&accion="+accion+"&codigo="+codigo+"&secuencia="+secuencia+"&riesgo="+riesgo+"&causa="+causa+"&consecuencia="+consecuencia+"&prevencion="+prevencion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					if (secuencia=="") location.href="tiposcargo_editar.php?filtro="+filtro+"&registro="+codigo;
					else location.href="tiposcargo_editar.php?filtro="+filtro+"&registro="+codigo+"&secuencia="+secuencia;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN RIESGO
function editarRiesgo(form) {
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSCARGO&accion=EDITAR&codigo="+codigo+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var datos=ajax.responseText;
				var erccp = datos.split("riesgo="); 
				var error=erccp[0]; 
				var riesgo=new String (erccp[1]);
				var causa=new String (erccp[2]);
				var consecuencia=new String (erccp[3]);
				var prevencion=new String (erccp[4]);				
				if (error!=0) alert ("¡"+error+"!");
				else {
					var idSecuencia=document.getElementById("secuencia");
					var idRiesgo=document.getElementById("riesgo");
					var idCausa=document.getElementById("causa");
					var idConsecuencia=document.getElementById("consecuencia");
					var idPrevencion=document.getElementById("prevencion");					
					idSecuencia.value=secuencia;
					idRiesgo.value=riesgo;
					idCausa.value=causa;
					idConsecuencia.value=consecuencia;
					idPrevencion.value=prevencion;
					idSecuencia.focus();
				}
			}
		}
	}
}
//	FUNCION PARA ELIMINAR UN RIESGO
function eliminarRiesgo(form) {
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) cargarPagina(form, "tiposcargo_editar.php?accion=ELIMINAR&filtro="+filtro+"&registro="+codigo);
	}
}

//	MAESTRO DE NIVELES DE TIPO DE CARGO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarNivel(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || form.tipocargo.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=NIVELESCARGO&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&tipocargo="+form.tipocargo.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="nivelcargo.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE CARGOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargo(form, accion) {
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var codigo = new String (form.codigo.value);
	var grupo = new String (form.grupo.value);
	var serie = new String (form.serie.value);
	var tipocargo = new String (form.tipocargo.value);
	var nivelcargo = new String (form.nivelcargo.value);	
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;		
	var descripcion_generica = new String (form.descripcion_generica.value); descripcion_generica=descripcion_generica.trim(); form.descripcion_generica.value=descripcion_generica;	
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.trim(); sueldo=sueldo.replace(",", ".");
	var ttra = new String (form.ttra.value);
	var gcargo = new String (form.gcargo.value);
	var plantilla_competencias = new String (form.plantilla_competencias.value);
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codcargo =="" || descripcion=="" || serie=="" || nivelcargo=="" || ttra=="" || descripcion_generica=="") msjError(1010);
	else if (isNaN(sueldo)) msjError(1190);
	else if (codcargo.length != 4) ("¡EL CODIGO DEBE TENER 4 DIGITOS!")
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		alert
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion="+accion+"&codigo="+codigo+"&grupo="+grupo+"&serie="+serie+"&tipocargo="+tipocargo+"&nivelcargo="+nivelcargo+"&descripcion="+descripcion+"&tipocargo="+tipocargo+"&status="+status+"&ttra="+ttra+"&sueldo="+sueldo+"&gcargo="+gcargo+"&plantilla_competencias="+plantilla_competencias+"&descripcion_generica="+descripcion_generica+"&codcargo="+codcargo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var error=ajax.responseText;
				var ec = error.split(":"); error = ec[0]; var codigo = new String (ec[1]);
				if (error!=0) alert ("¡"+error+"!");
				else {
					if (accion=="GUARDAR") {
						var cargos=confirm("¿Desea llenar la información faltante del cargo?");
						if (cargos) location.href="cargos_editar.php?filtro="+form.filtro.value+"&registro="+codigo;
						else location.href="cargos.php?filtro="+form.filtro.value;
					} else location.href="cargos.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA ESCRIBIR EL NOMBRE DEL CARGO
function setCargo(form) {
	var serie = new String (form.serie.value);
	var tipocargo = new String (form.tipocargo.value);
	var nivelcargo = new String (form.nivelcargo.value);
	if (serie!="" && tipocargo!="" && nivelcargo!="") {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=DESCRIPCION&serie="+serie+"&tipocargo="+tipocargo+"&nivelcargo="+nivelcargo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var descripcion=ajax.responseText;
				form.descripcion.value=descripcion;
			}
		}		
	}	else form.descripcion.value="";
}
//	FUNCION PARA OBTENER LAS PROFESIONES A PARTIR DE EL GRADO Y EL AREA
function getGradosCargo(form, ttra) {
	var gcargo = document.getElementById("gcargo");
	var sueldo = document.getElementById("sueldo");
	sueldo.value = "";
	//--
	if (ttra == "") { gcargo.disabled = true; gcargo.value = ""; }
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla=grados_cargo&ttra="+ttra);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				gcargo.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				gcargo.appendChild(nuevaOpcion);
				gcargo.disabled=true;
			}
			if (ajax.readyState==4)	{
				gcargo.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}
//	FUNCION PARA OBTENER EL SUELDO DEL GRADO
function getSueldoCargo(form, gcargo) {
	var sueldo = document.getElementById("sueldo");
	var ttra = document.getElementById("ttra");
	if (gcargo == "") sueldo.value = "";
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGO-SUELDO&gcargo="+gcargo+"&ttra="+ttra.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				sueldo.value = ajax.responseText;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoRelaciones(form, cargo) {
	var tipo=form.tipo.value;
	var ente = new String (form.ente.value); ente=ente.trim(); form.ente.value=ente;
	if (tipo=="" || ente=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGORELACION&cargo="+cargo+"&tipo="+tipo+"&ente="+ente);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_relaciones.php?registro="+cargo;
			}
		}
	}
	return false;
}
//xxxx
function verificarCargoFunciones(form) {
	var codigo = new String (form.codigo.value);
	var codcargo = new String (form.codcargo.value);
	var funciones = new String (form.funciones.value);
	var comentarios = new String (form.comentarios.value); comentarios=comentarios.trim();
	var status = new String (form.status.value);
	var inserto = new String (form.inserto.value);
	if (funciones == "" || comentarios == "") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOFUNCION&secuencia="+codigo+"&codcargo="+codcargo+"&funciones="+funciones+"&comentarios="+comentarios+"&status="+status+"&sub="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optCargosFunciones(form, accion) {
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var secuencia = form.sec.value;
	if (secuencia == "") msjError(1000);
	else {
		if (accion == "ELIMINAR") {
			var eliminar = confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax = nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=CARGOS&accion=CARGOFUNCION&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value = "INSERTAR";
							document.getElementById("btEditar").disabled = true;
							document.getElementById("btBorrar").disabled = true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOFUNCION&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value = "ACTUALIZAR";
						document.getElementById("codigo").value = secuencia;
						document.getElementById("funciones").value = resp[1];
						document.getElementById("comentarios").value = resp[2];
						document.getElementById("status").value = resp[3];
						document.getElementById("btEditar").disabled = true;
						document.getElementById("btBorrar").disabled = true;
					}
				}
			}
		}
	}
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoFormacion(form, cargo) {
	var grado=form.grado.value;
	var area=form.area.value;
	var profesion=form.profesion.value;
	if (grado=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOFORMACION&cargo="+cargo+"&grado="+grado+"&area="+area+"&profesion="+profesion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_formacion.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoIdioma(form, cargo) {
	var idioma=form.idioma.value;
	var lectura=form.lectura.value;
	var oral=form.oral.value;
	var escritura=form.escritura.value;
	var general=form.general.value;
	if (idioma=="" || lectura=="" || oral=="" || escritura=="" || general=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOIDIOMA&cargo="+cargo+"&idioma="+idioma+"&lectura="+lectura+"&oral="+oral+"&escritura="+escritura+"&general="+general);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_idioma.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoInformat(form, cargo) {
	var curso=form.curso.value;
	var nivel=form.nivel.value;
	if (curso=="" || nivel=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOINFORMAT&cargo="+cargo+"&curso="+curso+"&nivel="+nivel);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_informatica.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoExperiencia(form, cargo) {
	var area=form.area.value;
	var cargo_experiencia=form.cargo_experiencia.value;
	var meses = new String (form.meses.value); meses=meses.trim(); form.meses.value=meses;
	if (form.flag.checked) var flag="S"; else var flag="N";
	
	if (cargo_experiencia=="" && area=="") alert("¡ERROR: Debe seleccionar el área o el cargo de experiencia!");
	else if (cargo_experiencia!="" && area!="") alert("¡ERROR: No puede seleccionar un area y un cargo de experiencia juntos!");
	else if (meses=="") alert("¡ERROR: Debe ingresar el tiempo de experiencia en el área o el cargo!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOEXPERIENCIA&cargo="+cargo+"&area="+area+"&meses="+meses+"&flag="+flag+"&cargo_experiencia="+cargo_experiencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_experiencia.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoRiesgo(form, cargo) {
	var triesgo=form.triesgo.value;
	var riesgo = new String (form.riesgo.value); riesgo=riesgo.trim(); form.riesgo.value=riesgo;
	if (triesgo=="" || riesgo=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGORIESGO&cargo="+cargo+"&triesgo="+triesgo+"&riesgo="+riesgo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_riesgos.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoSub(form, cargo) {
	var cargos=form.cargos.value;
	var cantidad = new String (form.cantidad.value); cantidad=cantidad.trim();
	if (cargos=="" || cantidad=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOSUB&cargo="+cargo+"&cargos="+cargos+"&cantidad="+cantidad);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_sub.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoReporta(form, cargo) {
	var cargosup=form.cargosup.value;
	if (cargosup=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOREPORTA&cargo="+cargo+"&cargosup="+cargosup);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_reporta.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoCurso(form, cargo) {
	var cursos=form.codcurso.value;
	var horas = new String (form.horas.value); horas=horas.trim();
	var anios = new String (form.anios.value); anios=anios.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	if (cursos=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOCURSO&cargo="+cargo+"&cursos="+cursos+"&horas="+horas+"&anios="+anios+"&observaciones="+observaciones);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_cursos.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCargoMeta(form, cargo) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var factor = new String (form.factor.value); factor=factor.trim();
	if (descripcion=="" || factor=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOMETA&cargo="+cargo+"&descripcion="+descripcion+"&factor="+factor);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cargos_metas.php?registro="+cargo;
			}
		}
	}
	return false;
}
//	FUNCION PARA ELIMINAR REGISTROS DE LOS TABS DE CARGOS
function eliminarSubCargo(form, pagina) {
	var secuencia = form.det.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) cargarPagina(form, pagina);
	}
}
//	FUNCION PARA OBTENER LAS PROFESIONES A PARTIR DE EL GRADO Y EL AREA
function getProfesiones(form) {
	var grado=document.getElementById("grado");
	var area=document.getElementById("area");
	var profesion=document.getElementById("profesion");
	//--
	if (grado.value=="") profesion.disabled=true;
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla=profesiones&grado="+grado.value+"&area="+area.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				profesion.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				profesion.appendChild(nuevaOpcion);
				profesion.disabled=true;
			}
			if (ajax.readyState==4)	{
				profesion.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	MAESTRO DE TIPOS DE TRABAJADOR
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoTrabajador(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSTRABAJADOR&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tipostrabajador.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE NOMINA
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoNomina(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSNOMINA&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tiposnomina.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE PERFILES DE NOMINA
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoPerfil(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSPERFIL&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tiposperfil.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE MOTIVOS DE CESE
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCese(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.grave.checked) var falta="S"; else var falta="N";
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MOTIVOSCESE&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&falta="+falta+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="motivoscese.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE COOPERATIVAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCooperativa(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var objeto = new String (form.objeto.value); objeto=objeto.trim(); form.objeto.value=objeto;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COOPERATIVAS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&objeto="+objeto+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cooperativas.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE SINDICATOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarSindicato(form, accion) {
	var organismo=form.organismo.value;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var descripcion2 = new String (form.descripcion2.value); descripcion2=descripcion2.trim(); form.descripcion2.value=descripcion2;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || organismo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SINDICATOS&accion="+accion+"&codigo="+form.codigo.value+"&organismo="+organismo+"&descripcion="+descripcion+"&descripcion2="+descripcion2+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="sindicatos.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE SEGURO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoSeguro(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSSEGURO&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tiposseguro.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE PLANES DE SEGURO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPlanSeguro(form, accion) {
	var tiposeguro=form.tiposeguro.value;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var monto = new String (form.monto.value); monto=monto.trim(); monto=monto.replace(",", ".");
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || tiposeguro=="") msjError(1010);
	else if (isNaN(monto)) msjError(1060);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANESSEGURO&accion="+accion+"&codigo="+form.codigo.value+"&tiposeguro="+tiposeguro+"&descripcion="+descripcion+"&monto="+monto+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="planesseguro.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE TIPOS DE CONTRATO
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarTipoContrato(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.nomina[0].checked) var nomina=form.nomina[0].value; else var nomina=form.nomina[1].value;
	if (form.vence[0].checked) var vence=form.vence[0].value; else var vence=form.vence[1].value;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo=="" || descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TIPOSCONTRATO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&nomina="+nomina+"&vence="+vence+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="tiposcontrato.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE FORMATOS DE CONTRATOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarFormContrato(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var ruta = new String (form.ruta.value); ruta=ruta.trim(); form.ruta.value=ruta;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo=="" || descripcion=="" || form.tipocontrato.value=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FORMCONTRATO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&ruta="+ruta+"&tipocontrato="+form.tipocontrato.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="formatoscontrato.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE MISCELANEOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarMiscelaneo(form, accion) {
	var aplicacion = form.aplicacion.value;
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || codigo=="" || aplicacion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MISCELANEOS&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&aplicacion="+aplicacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (accion=="GUARDAR") {
					if (error!=0) alert ("¡"+error+"!");
					else {
						var registro=codigo+"-"+aplicacion;
						var elemento=confirm("¡Desea ingresar los elementos del maestro?");
						if (elemento) location.href="miscelaneos_editar.php?filtro="+form.filtro.value+"&registro="+registro;
						else location.href="miscelaneos.php?filtro="+form.filtro.value;
					}
				} else {
					if (error!=0) alert ("¡"+error+"!");
					else location.href="miscelaneos.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarElemento(form, accion) {
	var idAplicacion=document.getElementById("aplicacion");	var aplicacion = new String (idAplicacion.value);
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var elemento = new String (form.elemento.value); elemento=elemento.trim(); form.elemento.value=elemento;
	var helemento = new String (form.helemento.value); helemento=helemento.trim(); form.helemento.value=helemento;
	var detalle = new String (form.detalle.value); detalle=detalle.trim(); form.detalle.value=detalle;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (elemento=="" || detalle=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MISCELANEOS&accion="+accion+"&codigo="+codigo+"&aplicacion="+aplicacion+"&elemento="+elemento+"&helemento="+helemento+"&detalle="+detalle);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var registro=codigo+"-"+aplicacion;
					location.href="miscelaneos_editar.php?filtro="+filtro+"&registro="+registro;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN RIESGO
function editarElemento(form) {
	var idAplicacion=document.getElementById("aplicacion");	var aplicacion = new String (idAplicacion.value);
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var elemento = form.det.value;
	if (elemento=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MISCELANEOS&accion=EDITAR&codigo="+codigo+"&aplicacion="+aplicacion+"&elemento="+elemento);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var datos=ajax.responseText;
				var ed = datos.split("elemento="); 
				var error=ed[0]; 
				var elemento=new String (ed[1]);
				var detalle=new String (ed[2]);
				if (error!=0) alert ("¡"+error+"!");
				else {
					var idHElemento=document.getElementById("helemento");
					var idElemento=document.getElementById("elemento");
					var idDetalle=document.getElementById("detalle");					
					idHElemento.value=elemento;
					idElemento.value=elemento;
					idDetalle.value=detalle;
					idDetalle.focus();
				}
			}
		}
	}
}
//	FUNCION PARA ELIMINAR UN RIESGO
function eliminarElemento(form, pagina, foraneo, modulo) {
	var idAplicacion=document.getElementById("aplicacion");	var aplicacion = new String (idAplicacion.value);
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var elemento = form.det.value;
	if (elemento=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");		
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo="+modulo+"&accion=ELIMINARELEMENTO&codigo="+idCodigo.value+"&elemento="+elemento);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, "miscelaneos_editar.php?accion=ELIMINAR&foco=ELIMINAR&filtro="+filtro+"&registro="+idCodigo.value+"-"+idAplicacion.value+"&elemento="+elemento);
				}
			}		
		}
	}
}
//
function resetMiscelaneos(form) {
	form.helemento.value="";
	form.elemento.value="";
	form.detalle.value="";
	form.ult_usuario.value="";
	form.ult_fecha.value="";
}

//	MAESTRO DE PARAMETROS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarParametro(form, accion) {
	var aplicacion = form.aplicacion.value;
	var organismo = form.organismo.value;	
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var explicacion = new String (form.explicacion.value); explicacion=explicacion.trim(); form.explicacion.value=explicacion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (form.tipo[0].checked) {
		var tipo="T";
		var valor=form.texto.value;
		valor=valor.trim();
	}
	else if (form.tipo[1].checked) {
		var tipo="N";
		var valor=form.numero.value; 
		valor=valor.trim();
		valor=valor.replace(",", ".");
	}
	else {
		var tipo="F";
		var valor=form.fech.value;
		valor=valor.trim();
	}
	
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (aplicacion=="" || organismo=="" || codigo=="" || descripcion=="" || valor=="") msjError(1010);
	else if (tipo=="N" && isNaN(valor)) msjError(1070);
	else if (tipo=="F" && !esFecha(valor)) msjError(1040);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PARAMETROS&accion="+accion+"&aplicacion="+aplicacion+"&organismo="+organismo+"&codigo="+codigo+"&descripcion="+descripcion+"&explicacion="+explicacion+"&tipo="+tipo+"&status="+status+"&valor="+valor);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="parametros.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE EMPLEADOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarEmpleado(form, accion) {//contratarCandidatoEvaluacion
	var ccosto = new String (form.ccosto.value);
	var persona = new String (form.persona.value);
	var empleado = new String (form.empleado.value);
	var apellido1 = new String (form.apellido1.value); apellido1=apellido1.trim();
	var apellido2 = new String (form.apellido2.value); apellido2=apellido2.trim();
	var nombres = new String (form.nombres.value); nombres=nombres.trim();
	var busqueda = new String (form.busqueda.value); busqueda=busqueda.trim();
	var sexo = new String (form.sexo.value); sexo=sexo.trim();
	var ciudad1 = new String (form.ciudad1.value);
	var fnac = new String (form.fnac.value); fnac=fnac.trim();
	var lnac = new String (form.lnac.value);
	var dir = new String (form.dir.value); dir=dir.trim();
	var ciudad2 = new String (form.ciudad2.value);
	var tel1 = new String (form.tel1.value); tel1=tel1.trim();
	var tel2 = new String (form.tel2.value); tel2=tel2.trim();
	var tel3 = new String (form.tel3.value); tel3=tel3.trim();
	var tdoc = new String (form.tdoc.value);
	var ndoc = new String (form.ndoc.value); ndoc=ndoc.trim();
	var nac = new String (form.nac.value);
	var rif = new String (form.rif.value); rif=rif.trim();
	var email = new String (form.email.value); email=email.trim();
	var foto = new String (form.foto.value); foto=foto.trim();
	if (form.statusreg[0].checked) var statusreg=form.statusreg[0].value; else var statusreg=form.statusreg[1].value;
	
	var gsan = new String (form.gsan.value);
	var sitdom= new String (form.sitdom.value);
	var edocivil = new String (form.edocivil.value);
	var fedocivil = new String (form.fedocivil.value); fedocivil=fedocivil.trim();
	var nomcon1 = new String (form.nomcon1.value); nomcon1=nomcon1.trim();
	var nomcon2 = new String (form.nomcon2.value); nomcon2=nomcon2.trim();
	var dircon1 = new String (form.dircon1.value); dircon1=dircon1.trim();
	var dircon2 = new String (form.dircon2.value); dircon2=dircon2.trim();
	var telcon1 = new String (form.telcon1.value); telcon1=telcon1.trim();
	var telcon2 = new String (form.telcon2.value); telcon2=telcon2.trim();
	var celcon1 = new String (form.celcon1.value); celcon1=celcon1.trim();
	var celcon2 = new String (form.celcon2.value); celcon2=celcon2.trim();
	var parent1 = new String (form.parent1.value);
	var parent2 = new String (form.parent2.value);
	var tlic = new String (form.tlic.value);
	var nlic = new String (form.nlic.value); nlic=nlic.trim();
	var flic = new String (form.flic.value); flic=flic.trim();
	var auto = new String (form.auto.value);
	var obs = new String (form.obs.value); obs=obs.trim();
	
	var organismo = new String (form.organismo.value);
	var horganismo = new String (form.horganismo.value);
	var dependencia = new String (form.dependencia.value);
	var hdependencia = new String (form.hdependencia.value);
	var tnom = new String (form.tnom.value);
	var pnom = new String (form.pnom.value);
	var tpago = new String (form.tpago.value);
	var htpago = new String (form.htpago.value);
	var ttra = new String (form.ttra.value);
	var httra = new String (form.httra.value);
	var codcarnet = new String (form.codcarnet.value); codcarnet=codcarnet.trim();
	
	var fingreso = new String (form.fingreso.value); fingreso=fingreso.trim();
	if (form.sittra[0].checked) var sittra=form.sittra[0].value; else var sittra=form.sittra[1].value;
	var hsittra = new String (form.hsittra.value);
	var tcese = new String (form.tcese.value);
	var fcese = new String (form.fcese.value); fcese=fcese.trim();
	var explicacion = new String (form.explicacion.value); explicacion=explicacion.trim();
	var grupo = new String (form.grupo.value);
	var serie = new String (form.serie_empleado.value);
	var cargo = new String (form.cargo_empleado.value);
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.replace(".", ""); sueldo=sueldo.replace(",", ".");
	if (sittra=="A") { tcese=""; fcese=""; explicacion=""; }
	
	var filtro = new String (form.filtro.value);
	var ordenar = new String (form.ordenar.value);
	
	//	 || nomcon1=="" || dircon1=="" || telcon1=="" || parent1==""
	if (apellido1=="" || nombres=="" || busqueda=="" || ciudad1=="" || dir=="" || ciudad2=="" || tdoc=="" || ndoc=="" || nac=="" || edocivil=="" || organismo=="" || dependencia=="" || tnom=="" || pnom=="" || tpago=="" || cargo=="") msjError(1010);
	else if ((tcese=="" || fcese=="") && sittra=="I") msjError(1010);
	else if (!esFecha(fnac)) msjError(1080);
	else if (fedocivil!="" && !esFecha(fedocivil)) msjError(1090);
	else if (!esFecha(flic) && flic!="") msjError(1100);
	else if (!esFecha(fingreso)) msjError(1110);
	else if (!esFecha(fcese) && sittra=="I") msjError(1120);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADOS&accion="+accion+"&persona="+persona+"&empleado="+empleado+"&apellido1="+apellido1+"&apellido2="+apellido2+"&nombres="+nombres+"&busqueda="+busqueda+"&sexo="+sexo+"&ciudad1="+ciudad1+"&fnac="+fnac+"&lnac="+lnac+"&dir="+dir+"&ciudad2="+ciudad2+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&tdoc="+tdoc+"&ndoc="+ndoc+"&nac="+nac+"&rif="+rif+"&email="+email+"&foto="+foto+"&statusreg="+statusreg+"&gsan="+gsan+"&sitdom="+sitdom+"&edocivil="+edocivil+"&fedocivil="+fedocivil+"&nomcon1="+nomcon1+"&nomcon2="+nomcon2+"&dircon1="+dircon1+"&dircon2="+dircon2+"&telcon1="+telcon1+"&telcon2="+telcon2+"&celcon1="+celcon1+"&celcon2="+celcon2+"&parent1="+parent1+"&parent2="+parent2+"&tlic="+tlic+"&nlic="+nlic+"&flic="+flic+"&auto="+auto+"&obs="+obs+"&organismo="+organismo+"&dependencia="+dependencia+"&tnom="+tnom+"&pnom="+pnom+"&tpago="+tpago+"&fingreso="+fingreso+"&sittra="+sittra+"&tcese="+tcese+"&fcese="+fcese+"&explicacion="+explicacion+"&grupo="+grupo+"&serie="+serie+"&cargo="+cargo+"&ttra="+ttra+"&sueldo="+sueldo+"&htpago="+htpago+"&hsittra="+hsittra+"&httra="+httra+"&horganismo="+horganismo+"&hdependencia="+hdependencia+"&codcarnet="+codcarnet+"&ccosto="+ccosto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else cargarPagina(form, "empleados.php?filtro="+filtro+"&ordenar="+ordenar+"&limit=0");
			}
		}
	}
	return false;
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR LOS CAMPOS DEL CESE
function setCese(form) {
	if (form.sittra[0].checked) {
		form.tcese.disabled=true;
		form.fcese.disabled=true;
		form.explicacion.disabled=true;
	}	else {
		form.tcese.disabled=false;
		form.fcese.disabled=false;
		form.explicacion.disabled=false;
	}
}
//	FUNCION PARA ESCRIBIR EL LUGAR DE NACIMIENTO
function setLNAC(form) {
	var ciudad = new String (form.ciudad1.value);
	if (form.ciudad1.disabled==true || form.ciudad1.value=="") form.lnac.value="";
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADOS&accion=LNAC&ciudad="+ciudad);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				var lnac = error.split("lnac="); error=lnac[0]; var ciudad=lnac[1]; var estado=lnac[2];
				if (error!=0) alert ("¡"+error+"!");
				else form.lnac.value=ciudad+" ESTADO "+estado;
			}
		}	
	}
}
//	FUNCION PARA ESCRIBIR EL NOMBRE DE BUSQUEDA
function setBusqueda(form) {
	form.busqueda.value=form.apellido1.value+" "+form.apellido2.value+", "+form.nombres.value;
}
//	FUNCION PARA ESCRIBIR EL NOMBRE DE BUSQUEDA
function setBusquedaPersona(form) {
	form.busqueda.value=form.apellido1.value+" "+form.apellido2.value+", "+form.nombres.value;
	form.nom_completo.value=form.apellido1.value+" "+form.apellido2.value+", "+form.nombres.value;
}
//	FUNCION PARA ESCRIBIR EL NOMBRE DE BUSQUEDA
function setBusquedaPersona2(form) {
	form.busqueda.value=form.nom_completo.value;
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroEmpleados(form, limit) {
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
			var pagina="empleados.php?filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroEmpleadosAntecedentes(form, limit) {
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
			var pagina="antecedentes_listado.php?accion=FILTRAR&filtrar="+filtro+"&filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroEmpleadosJubilacion(form, limit) {
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
			var pagina="jubilacion_proceso_listado.php?accion=FILTRAR&filtrar="+filtro+"&filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroAprobarJubilacion(form, limit) {
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
			var pagina="jubilacion_aprobar_listado.php?accion=FILTRAR&filtrar="+filtro+"&filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroPDFEmpleados(form, limit) {
	var edad=new String(form.fedad.value); edad=edad.trim();
	var filtro=""; var ordenar="";
	if (form.chkedoreg.checked) filtro+=" AND mp.Estado LIKE *;"+form.fedoreg.value+";*";
	if (form.chkorganismo.checked) filtro+=" AND me.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND me.CodDependencia LIKE *;"+form.fdependencia.value+";*";
	if (form.chksittra.checked) filtro+=" AND me.Estado LIKE *;"+form.fsittra.value+";*";
	if (form.chktiponom.checked) filtro+=" AND me.CodTipoNom LIKE *;"+form.ftiponom.value+";*";
	if (form.chkbuscar.checked) filtro+=" AND mp."+form.sltbuscar.value+" LIKE *;"+form.fbuscar.value+";*";
	if (form.chktipotra.checked) filtro+=" AND me.CodTipoTrabajador LIKE *;"+form.ftipotra.value+";*";
	if (form.chkndoc.checked) filtro+=" AND mp.Ndocumento LIKE *;"+form.fndoc.value+";*";
	if (form.chkordenar.checked) ordenar=form.fordenar.value;	
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
			var pagina="pdf_empleados.php?filtro="+filtro+"&ordenar="+ordenar;
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroPDFEventosControl(form, limit) {
	var filtro=""; var ordenar="ORDER BY me.CodEmpleado";
	if (form.chkedoreg.checked) filtro+=" AND mp.Estado LIKE *;"+form.fedoreg.value+";*";
	if (form.chkorganismo.checked) filtro+=" AND me.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND me.CodDependencia LIKE *;"+form.fdependencia.value+";*";
	if (form.chksittra.checked) filtro+=" AND me.Estado LIKE *;"+form.fsittra.value+";*";
	if (form.chktiponom.checked) filtro+=" AND me.CodTipoNom LIKE *;"+form.ftiponom.value+";*";
	if (form.chkbuscar.checked) filtro+=" AND mp."+form.sltbuscar.value+" LIKE *;"+form.fbuscar.value+";*";
	if (form.chkperfilnom.checked) filtro+=" AND me.CodPerfil LIKE *;"+form.fperfilnom.value+";*";
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
	if (form.chkfingreso.checked) {
		var error = false;
		var esFIngD=esFecha(form.ffingresod.value);
		var esFIngH=esFecha(form.ffingresoh.value);
		var fechad = new String (form.ffingresod.value); fechad=fechad.trim();
		var fechah = new String (form.ffingresoh.value); fechah=fechah.trim();		
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");
		var filtro_evento = " AND (ce.FechaFormat>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND ce.FechaFormat<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
		var filtro_permiso = " AND (rp.FechaDesde>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND rp.FechaHasta<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFIngD=true; esFIngH=true; var error = true; }
	if (esFIngD && esFIngH) {
		if (form.chkbuscar.checked && form.sltbuscar.value=="") msjError(1130);
		else {
			if (error) alert("¡DEBE SELECCIONAR EL PERIODO DEL REPORTE!");
			else {
				var pagina_asistencias="pdf_eventos_control.php?filtro="+filtro+"&filtro_evento="+filtro_evento+"&filtro_permiso="+filtro_permiso+"&fingresod="+form.ffingresod.value+"&fingresoh="+form.ffingresoh.value;
				
				form.target = "iAsistencias";				
				cargarPagina(form, pagina_asistencias);
				
				var pagina_permisos="pdf_eventos_permisos.php?filtro="+filtro+"&filtro_permiso="+filtro_permiso+"&filtro_permiso="+filtro_permiso+"&fingresod="+form.ffingresod.value+"&fingresoh="+form.ffingresoh.value;
				
				form.target = "iPermisos";				
				cargarPagina(form, pagina_permisos);
			}
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroControlAsistencias(form, limit) {
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
			var pagina="control_asistencias.php?filtro="+filtro;
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroListadoEmpleados(form, limit) {
	var edad=new String(form.fedad.value); edad=edad.trim();
	var filtro="AND mp.CodPersona<>**"; var ordenar="ORDER BY mp.CodPersona";
	if (form.chkedoreg.checked) filtro+=" AND mp.Estado LIKE *;"+form.fedoreg.value+";*";
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
		filtro+=" AND mp.CodPersona"+rel+"*"+form.fpersona.value+"*";
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
			var pagina="listado_empleados.php?filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}

//	MAESTRO DE PERSONAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPersona(form, accion) {
	var tabproveedor = document.getElementById("proveedor").value;
	var datos_proveedor = "";
	if (tabproveedor == "S") {
		var actualizo = document.getElementById("actualizo").value;
		var docproveedor = document.getElementById("docproveedor").value;
		var docpago = document.getElementById("docpago").value;
		var formapago = document.getElementById("formapago").value;
		var servicio = document.getElementById("servicio").value;
		var dias = document.getElementById("dias").value;
		var registro = document.getElementById("registro").value;
		var licencia = document.getElementById("licencia").value;
		var fconstitucion = document.getElementById("fconstitucion").value; var esfconstitucion = esFecha(fconstitucion);
		var representante = document.getElementById("representante").value;
		var contacto = document.getElementById("contacto").value;
		if (document.getElementById("flagsnc").checked) var flagsnc = "S"; else var flagsnc = "N";
		var nrosnc = document.getElementById("nrosnc").value;
		var femision = document.getElementById("femision").value; var esfemision = esFecha(femision);
		var fvalidacion = document.getElementById("fvalidacion").value; var esfvalidacion = esFecha(fvalidacion);
		if (document.getElementById("nacional").checked) var flagnacionalidad = "N"; else var flagnacionalidad = "E";
		
		if (docproveedor == "" || docpago == "" || servicio == "") var error = true; else var error = false;
		
		var datos_proveedor = "docproveedor="+docproveedor+"&docpago="+docpago+"&servicio="+servicio+"&dias="+dias+"&registro="+registro+"&licencia="+licencia+"&fconstitucion="+fconstitucion+"&representante="+representante+"&contacto="+contacto+"&flagsnc="+flagsnc+"&nrosnc="+nrosnc+"&femision="+femision+"&fvalidacion="+fvalidacion+"&actualizo="+actualizo+"&formapago="+formapago+"&flagnacionalidad="+flagnacionalidad;
	}
	
	var persona = new String (form.persona.value);
	var filtro = new String (form.filtro.value);
	var ordenar = new String (form.ordenar.value);
	var busqueda = new String (form.busqueda.value); busqueda=busqueda.trim();
	var nom_completo = new String (form.nom_completo.value); nom_completo=nom_completo.trim();
	var cpersona = new String (form.cpersona.value);
	if (form.escliente.checked) var escliente="S"; else var escliente="N";
	if (form.esproveedor.checked) var esproveedor="S"; else var esproveedor="N";
	if (form.esempleado.checked) var esempleado="S"; else var esempleado="N";
	if (form.esotro.checked) var esotro="S"; else var esotro="N";
	var apellido1 = new String (form.apellido1.value); apellido1=apellido1.trim();
	var apellido2 = new String (form.apellido2.value); apellido2=apellido2.trim();
	var nombres = new String (form.nombres.value); nombres=nombres.trim();
	var sexo = new String (form.sexo.value); sexo=sexo.trim();
	var fnac = new String (form.fnac.value); fnac=fnac.trim();
	var edocivil = new String (form.edocivil.value);
	var dir = new String (form.dir.value); dir=dir.trim();
	var ciudad2 = new String (form.ciudad2.value);
	var email = new String (form.email.value); email=email.trim();
	var nomcon1 = new String (form.nomcon1.value); nomcon1=nomcon1.trim();
	var dircon1 = new String (form.dircon1.value); dircon1=dircon1.trim();
	var tel1 = new String (form.tel1.value); tel1=tel1.trim();
	var tel2 = new String (form.tel2.value); tel2=tel2.trim();
	var tel3 = new String (form.tel3.value); tel3=tel3.trim();
	var tdoc = new String (form.tdoc.value);
	var ndoc = new String (form.ndoc.value); ndoc=ndoc.trim();
	var rif = new String (form.rif.value); rif=rif.trim();
	var banco = new String (form.banco.value);
	var ncta = new String (form.ncta.value); ncta=ncta.trim();
	var tcta = new String (form.tcta.value);
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (cpersona=="N" && (apellido1=="" || apellido2=="" || nombres=="" || busqueda=="" || nom_completo=="" || dir=="" || ciudad2=="" || tdoc=="" || ndoc=="" || rif=="")) msjError(1010);
	else if (cpersona=="J" && (busqueda=="" || nom_completo=="" || dir=="" || ciudad2=="" || tdoc=="" || ndoc=="" || rif=="")) msjError(1010);
	else if (cpersona=="N" && !esFecha(fnac)) msjError(1080);
	else if (cpersona=="J" && !esFecha(fnac)) alert("¡FECHA DE CONSTITUCION INCORRECTA!");
	//else if (tabproveedor == "S" && error) alert("¡DEBE INGRESAR LOS CAMPOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PERSONAS&accion="+accion+"&persona="+persona+"&apellido1="+apellido1+"&apellido2="+apellido2+"&nombres="+nombres+"&busqueda="+busqueda+"&sexo="+sexo+"&fnac="+fnac+"&dir="+dir+"&ciudad2="+ciudad2+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&tdoc="+tdoc+"&ndoc="+ndoc+"&rif="+rif+"&email="+email+"&status="+status+"&edocivil="+edocivil+"&nomcon1="+nomcon1+"&dircon1="+dircon1+"&cpersona="+cpersona+"&escliente="+escliente+"&esproveedor="+esproveedor+"&esempleado="+esempleado+"&esotro="+esotro+"&status="+status+"&banco="+banco+"&ncta="+ncta+"&tcta="+tcta+"&nom_completo="+nom_completo+"&tabproveedor="+tabproveedor+"&"+datos_proveedor);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else cargarPagina(form, "personas.php?filtro="+filtro+"&limit=0&ordenar="+ordenar);
			}
		}
	}
	return false;
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE PERSONAS
function filtroPersonas(form, limit) {
	var filtro="CodPersona<>**"; var ordenar="";
	if (form.chkordenar.checked) ordenar=" ORDER BY "+form.fordenar.value+" ASC";
	if (form.chkedoreg.checked) filtro+=" AND Estado = *"+form.fedoreg.value+"*";
	if (form.chkcpersona.checked) filtro+=" AND TipoPersona = *"+form.fcpersona.value+"*";
	if (form.chktpersona.checked) {
		if (form.ftpersona.value=="Cliente") filtro+=" AND EsCliente = *S*";
		else if (form.ftpersona.value=="Proveedor") filtro+=" AND EsProveedor= *S*";
		else if (form.ftpersona.value=="Empleado") filtro+=" AND EsEmpleado= *S*";
		else if (form.ftpersona.value=="Otro") filtro+=" AND EsOtros= *S*";
	}
	if (form.chkbpersona.checked) filtro+=" AND (Busqueda LIKE *;"+form.fbpersona.value+";* OR CodPersona LIKE *;"+form.fbpersona.value+";* OR Ndocumento LIKE *;"+form.fbpersona.value+";* OR DocFiscal LIKE *;"+form.fbpersona.value+";*)";
	
	var pagina="personas.php?filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
	cargarPagina(form, pagina);
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE PERSONAS
function filtroPostulantes(form, limit) {
	var filtro="";
	var edadD=new String(form.fedadd.value); edadD=edadD.trim();
	var edadH=new String(form.fedadh.value); edadH=edadH.trim();
	var anios=new String(form.fanios.value); anios=anios.trim();
	if (form.chksexo.checked) filtro+=" AND rh_postulantes.Sexo = *"+form.fsexo.value+"*";
	if (form.chkedadd.checked && edadD!="") {
		var fechaEdad=setFiltroEdad(edadD);
		var fecha = fechaEdad.split(":");
		var fechaDesde = fecha[0].split("-");
		var fechaHasta = fecha[1].split("-");
		if (fechaDesde[1]<10) fechaDesde[1]="0"+fechaDesde[1];
		if (fechaDesde[2]<10) fechaDesde[2]="0"+fechaDesde[2];
		if (fechaHasta[1]<10) fechaHasta[1]="0"+fechaHasta[1];
		if (fechaHasta[2]<10) fechaHasta[2]="0"+fechaHasta[2];
		var desde=fechaDesde[0]+"-"+fechaDesde[1]+"-"+fechaDesde[2];
		var hasta=fechaHasta[0]+"-"+fechaHasta[1]+"-"+fechaHasta[2];		
		filtro+=" AND (rh_postulantes.Fnacimiento<=*"+desde+"*)";
	}
	if (form.chkedadh.checked && edadH!="") {
		var fechaEdad=setFiltroEdad(edadH);
		var fecha = fechaEdad.split(":");
		var fechaDesde = fecha[0].split("-");
		var fechaHasta = fecha[1].split("-");
		if (fechaDesde[1]<10) fechaDesde[1]="0"+fechaDesde[1];
		if (fechaDesde[2]<10) fechaDesde[2]="0"+fechaDesde[2];
		if (fechaHasta[1]<10) fechaHasta[1]="0"+fechaHasta[1];
		if (fechaHasta[2]<10) fechaHasta[2]="0"+fechaHasta[2];
		var desde=fechaDesde[0]+"-"+fechaDesde[1]+"-"+fechaDesde[2];
		var hasta=fechaHasta[0]+"-"+fechaHasta[1]+"-"+fechaHasta[2];		
		filtro+=" AND (rh_postulantes.Fnacimiento>=*"+hasta+"*)";
	}
	if (form.chkapellido.checked) filtro+=" AND (rh_postulantes.Apellido1 LIKE *;"+form.fapellido.value+";* OR rh_postulantes.Apellido2 LIKE *;"+form.fapellido.value+";*)";
	if (form.chkstatus.checked) filtro+=" AND rh_postulantes.Estado = *"+form.fstatus.value+"*";
	if (form.chkginstruccion.checked) filtro+=" AND rh_postulantes_instruccion.CodGradoInstruccion = *"+form.fginstruccion.value+"*";
	if (form.chkarea.checked) filtro+=" AND rh_postulantes_instruccion.Area = *"+form.farea.value+"*";
	if (form.chkprofesion.checked) filtro+=" AND rh_postulantes_instruccion.CodProfesion = *"+form.fprofesion.value+"*";
	if (form.chkcentros.checked) filtro+=" AND rh_postulantes_instruccion.CodCentroEstudio = *"+form.fcentros.value+"*";
	if (form.chkcargo.checked) filtro+=" AND rh_postulantes_cargos.CodCargo = *"+form.fcargo.value+"*";
	if (form.chkidioma.checked) filtro+=" AND rh_postulantes_idioma.CodIdioma = *"+form.fidioma.value+"*";
	if (form.chkcursos.checked) filtro+=" AND rh_postulantes_cursos.CodCurso = *"+form.fcursos.value+"*";	
	if (form.chkanios.checked && anios!="") {
		var fechaEdad=setFiltroEdad(anios);
		var fecha = fechaEdad.split(":");
		var fechaDesde = fecha[0].split("-");
		var fechaHasta = fecha[1].split("-");
		if (fechaDesde[1]<10) fechaDesde[1]="0"+fechaDesde[1];
		if (fechaDesde[2]<10) fechaDesde[2]="0"+fechaDesde[2];
		if (fechaHasta[1]<10) fechaHasta[1]="0"+fechaHasta[1];
		if (fechaHasta[2]<10) fechaHasta[2]="0"+fechaHasta[2];
		var desde=fechaDesde[0]+"-"+fechaDesde[1]+"-"+fechaDesde[2];
		var hasta=fechaHasta[0]+"-"+fechaHasta[1]+"-"+fechaHasta[2];		
		filtro+=" AND (rh_postulantes_experiencia.FechaDesde<=*"+hasta+"*)";
	}
	var pagina="postulantes.php?filtro="+filtro+"&limit=0";
	cargarPagina(form, pagina);
}

//	FUNCION PARA CAMBIAR DE NATURAL A JURIDICA
function setClase(form, clase) {
	var tag1=document.getElementById("clase");
	var tag2=document.getElementById("fclase");
	if (clase=="N") {
		tag1.innerHTML="Datos Persona Natural";
		tag2.innerHTML="Fecha de Nac.:";
		form.nom_completo.disabled=true;
		form.nom_completo.value="";
		form.busqueda.value="";
		form.apellido1.disabled=false;
		form.apellido2.disabled=false;
		form.nombres.disabled=false;
		form.sexo.disabled=false;
		form.edocivil.disabled=false;
		form.nomcon1.disabled=false;
		form.dircon1.disabled=false;
		
	} else {
		tag1.innerHTML="Datos Persona Juridica";
		tag2.innerHTML="Fecha de Const.:";
		form.nom_completo.disabled=false;
		form.apellido1.disabled=true;
		form.apellido2.disabled=true;
		form.nombres.disabled=true;
		form.sexo.disabled=true;
		form.edocivil.disabled=true;
		form.nomcon1.disabled=true;
		form.dircon1.disabled=true;
		form.apellido1.value="";
		form.apellido2.value="";
		form.nombres.value="";
		form.sexo.value="";
		form.edocivil.value="";
		form.nomcon1.value="";
		form.dircon1.value="";
	}
}

//	MAESTRO DE BANCOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarBanco(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var codpersona = new String (form.codpersona.value); codpersona=codpersona.trim();
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010); 
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=BANCOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&persona="+codpersona);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="bancos.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarBancaria(form, accion) {
	var idPersona=document.getElementById("persona");	var persona = new String (idPersona.value);
	var secuencia = new String (form.secuencia.value);
	var banco = new String (form.banco.value);
	var tcta = new String (form.tcta.value);
	var tapo = new String (form.tapo.value);
	var cuenta = new String (form.cuenta.value); cuenta=cuenta.trim();
	var monto = new String (form.monto.value); monto=monto.trim(); monto=monto.replace(",", ".");
	if (form.principal.checked) var principal="S"; else var principal="N";
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (banco=="" || tcta=="" || tapo=="" || cuenta=="" || monto=="") msjError(1010);
	else if (isNaN(monto)) msjError(1060);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=BANCARIA&accion="+accion+"&persona="+persona+"&secuencia="+secuencia+"&banco="+banco+"&tcta="+tcta+"&tapo="+tapo+"&cuenta="+cuenta+"&monto="+monto+"&principal="+principal);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="empleados_bancaria.php?registro="+persona;
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN RIESGO
function optBancaria(form, accion) {
	var idPersona=document.getElementById("persona");	var persona = new String (idPersona.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="empleados_bancaria.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
		} else location.href="empleados_bancaria.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
	}
}
//
function resetBancaria(form) {
	form.secuencia.value="";
	form.banco.value="";
	form.tcta.value="";
	form.cuenta.value="";
	form.tapo.value="";
	form.monto.value="";
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarDocumento(form, accion) {
	var secuencia = new String (form.secuencia.value);
	var registro = new String (form.registro.value);
	var doc = new String (form.doc.value);
	if (form.flagpresento.checked) var flagpresento="S"; else var flagpresento="N";
	if (form.flagfamiliar.checked) var flagfamiliar="S"; else var flagfamiliar="N";
	var fentrega = new String (form.fentrega.value); fentrega=fentrega.trim();
	var fvence = new String (form.fvence.value); fvence=fvence.trim();
	var familiar = new String (form.familiar.value);
	var observacion = new String (form.observacion.value); observacion=observacion.trim();
	var archivo = new String (form.archivo.value); archivo=archivo.trim();
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (doc=="") msjError(1010);
	else if (flagpresento=="S" && (fentrega=="" || !esFecha(fentrega))) msjError(1200);
	else if (fvence!="" && !esFecha(fvence)) msjError(1240);
	else if (flagfamiliar=="S" && familiar=="") msjError(1210);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=DOCUMENTO&accion="+accion+"&secuencia="+secuencia+"&registro="+registro+"&doc="+doc+"&flagpresento="+flagpresento+"&flagfamiliar="+flagfamiliar+"&fentrega="+fentrega+"&fvence="+fvence+"&familiar="+familiar+"&observacion="+observacion+"&archivo="+archivo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="documentos.php?registro="+registro;
			}
		}
	}
	return false;
}
//	FUNCION QUE ACTIVA LOS CAMPOS OBLIGATORIOS
function setFlagPresento(form) {
	if (form.flagpresento.checked) { form.fentrega.disabled=false; form.fvence.disabled=false; }
	else { form.fentrega.disabled=true; form.fvence.disabled=true; }
}
//	FUNCION QUE ACTIVA LOS CAMPOS OBLIGATORIOS
function setFlagFamiliar(form) {
	if (form.flagfamiliar.checked) form.bt_examinar.disabled=false; else form.bt_examinar.disabled=true;
}
//	FUNCION PARA EDITAR UN DOCUMENTO
function optDocumento(form, accion) {
	var persona=document.getElementById("registro").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) location.href="documentos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
		} else location.href="documentos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
	}
}
//
function resetDocumento(form) {
	form.secuencia.value="";
	form.doc.value="";
	form.fentrega.value="";
	form.fvence.value="";
	form.familiar.value="";
	form.nomfamiliar.value="";
	form.parentesco.value="";
	form.observacion.value="";
	form.archivo.value="";	
	form.flagpresento.checked=false;	
	form.flagfamiliar.checked=false;	
}
//	FUNCION PARA CAMBIAR DE TAB EN EL MODULO DE DOCUMENTOS DEL EMPLEADO
function tabMovimiento() {	
	var registro=document.getElementById("persona").value;
	var documento=iDocumentos.document.frmtabla.sec.value;
	if (documento=="") msjError(1220);
	else {
		document.getElementById('iMovimientos').src="movimientos.php?registro="+registro+"&documento="+documento;
		document.getElementById("tab1").style.display="none";
		document.getElementById("tab2").style.display="block";
	}
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarMovimiento(form, accion) {
	var secuencia = new String (form.secuencia.value);
	var registro = new String (form.registro.value);
	var documento = new String (form.documento.value);
	var fentrega = new String (form.fentrega.value); fentrega=fentrega.trim();
	var fstatus = new String (form.fstatus.value); fstatus=fstatus.trim();
	var codpersona = new String (form.codpersona.value);
	var status = new String (form.status.value);
	var obsestado = new String (form.obsestado.value); obsestado=obsestado.trim();
	var obsentrega = new String (form.obsentrega.value); obsentrega=obsentrega.trim();
	var esIntervalo=esVFecha(fentrega, fstatus);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (status=="E" && codpersona=="") msjError(1010);
	else if (status=="E" && !esFecha(fentrega)) msjError(1200);
	else if (status!="E" && !esFecha(fstatus)) msjError(1230);
	else if (status!="E" && !esIntervalo) msjError(1250);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MOVIMIENTO&accion="+accion+"&secuencia="+secuencia+"&documento="+documento+"&registro="+registro+"&fentrega="+fentrega+"&fstatus="+fstatus+"&codpersona="+codpersona+"&status="+status+"&obsestado="+obsestado+"&obsentrega="+obsentrega);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="movimientos.php?registro="+registro+"&documento="+documento;
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN MOVIMIENTO
function optMovimiento(form, accion) {
	var persona=document.getElementById("registro").value;
	var documento=document.getElementById("documento").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) location.href="movimientos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia+"&documento="+documento;
		} else location.href="movimientos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia+"&documento="+documento;
	}
}

//	MAESTRO DE CONTRATOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarContrato(form, accion) {
	var organismo = new String (form.organismo.value);
	var tcon = new String (form.tcon.value);
	var vcontratod = new String (form.vcontratod.value); vcontratod=vcontratod.trim(); esVCD=esFecha(vcontratod);
	var vcontratoh = new String (form.vcontratoh.value); vcontratoh=vcontratoh.trim(); esVCH=esFecha(vcontratoh);
	var esVigencia=esVFecha(vcontratod, vcontratoh);
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	var fcon = new String (form.fcon.value);
	if (form.flagfirma.checked) var flagfirma="S"; else var flagfirma="N";
	var firma = new String (form.firma.value); firma=firma.trim(); esFirma=esFecha(firma);
	var esActual=esAFecha(firma);
	var coment = new String (form.coment.value); coment=coment.trim();
	var persona = new String (form.persona.value);
	var filtro = new String (form.filtro.value);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo=="" || tcon=="" || vcontratod=="" || fcon=="") msjError(1010);
	else if ((vcontratod!="" && !esVCD) || (vcontratoh!="" && !esVCH)) msjError(1140);
	else if (flagfirma=="S" && (!esFirma || !esActual)) msjError(1150);
	else if (vcontratod!="" && vcontratoh!="" && !esVigencia) msjError(1140);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CONTRATOS&accion="+accion+"&persona="+persona+"&organismo="+organismo+"&tcon="+tcon+"&fcon="+fcon+"&vcontratod="+vcontratod+"&vcontratoh="+vcontratoh+"&status="+status+"&flagfirma="+flagfirma+"&firma="+firma+"&coment="+coment);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				if (accion=="NUEVO") var pagina="contratos_sin.php?limit=0&filtro="+filtro;
				else if (accion=="ACTUALIZAR") var pagina="contratos_vigentes.php?limit=0&filtro="+filtro;
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					form.action=pagina;
					form.target="itab";
					form.submit();
					window.close();
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE CONTRATOS
function filtroContratos(form) {
	var filtro="";
	if (form.chkorganismo.checked) filtro+=" AND me.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND me.CodDependencia LIKE *;"+form.fdependencia.value+";*";	
	if (form.chktcon.checked) filtro+=" AND rc.TipoContrato LIKE *;"+form.ftcon.value+";*";
	if (form.chkvcontrato.checked) {
		var fechad = new String (form.fvcontratod.value); fechad=fechad.trim();
		var fechah = new String (form.fvcontratoh.value); fechah=fechah.trim();	
		var esFIngD=esFecha(fechad);
		var esFIngH=esFecha(fechah);
		var esVFIng=esVFecha(fechad, fechah);
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");		
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");		
		filtro+=" AND (rc.FechaDesde>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND rc.FechaDesde<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*) AND (rc.FechaHasta>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND rc.FechaHasta<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFIngD=true; esFIngH=true; esVFIng=true; }
	if (form.chkpersona.checked) {
		var rel="";
		if (form.sltpersona.value=="1") rel="=";
		else if (form.sltpersona.value=="2") rel="<";
		else if (form.sltpersona.value=="3") rel=">";
		else if (form.sltpersona.value=="4") rel="<=";
		else if (form.sltpersona.value=="5") rel=">=";
		else if (form.sltpersona.value=="6") rel="<>";
		filtro+=" AND mp.CodPersona"+rel+"*"+form.fpersona.value+"*";
	}
	if (form.chkfirma.checked) {
		var fechad = new String (form.ffirmad.value); fechad=fechad.trim();
		var fechah = new String (form.ffirmah.value); fechah=fechah.trim();		
		var esFirmaD=esFecha(fechad);
		var esFirmaH=esFecha(fechah);
		var esVFirma=esVFecha(fechad, fechah);
		var esActualD=esAFecha(fechad);
		var esActualH=esAFecha(fechah);
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");		
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");		
		filtro+=" AND (rc.FechaFirma>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND rc.FechaFirma<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFirmaD=true; esFirmaH=true; esVFirma=true; esActualD=true; esActualH=true; }
	if (esFIngD && esFIngH && esFirmaD && esFirmaH && esVFIng && esVFirma && esActualD && esActualH) {
		var pagina=form.action+"&filtro="+filtro;
		form.action=pagina;
		form.submit();
	} else {
		if (!esFIngD || !esFIngD || !esVFIng) msjError(1140);
		if (!esFirmaD || !esFirmaH || !esVFirma || !esActualD || !esActualH) msjError(1150);
		if (!esFIngD || !esFIngH) {
			form.fvcontratod.value="";
			form.fvcontratoh.value="";
		}
		if (!esFirmaD || !esFirmaH) {
			form.ffirmad.value="";
			form.ffirmah.value="";
		}
	}
}
//
function setTipoContrato(tipo) {
	var vcontratod=document.getElementById("vcontratod");
	var vcontratoh=document.getElementById("vcontratoh");
	var fcon=document.getElementById("fcon");
	var nfcon=document.getElementById("nfcon");
	if (tipo=="") {
		vcontratod.value="";
		vcontratoh.value="";
		vcontratod.disabled=true;
		vcontratoh.disabled=true;
		fcon.value="";
		nfcon.value="";
	} else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("tipo="+tipo+"&accion=SET-TIPO-CONTRATOS");
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				var valores=resp.split("|:|");
				if (valores[1]=="S") vcontratoh.disabled=false;
				else vcontratoh.disabled=true;
				
				vcontratod.disabled=false;
				vcontratod.value="";
				vcontratoh.value="";
				fcon.value=valores[2];
				nfcon.value=valores[3];
			}
		}
	}
}

//	CARGA FAMILIAR
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarFamiliar(form, accion) {
	var persona = new String (form.persona.value);
	var registro = new String (form.registro.value);
	var parent = new String (form.parent.value);
	var sexo = new String (form.sexo.value);
	var apellidos = new String (form.apellidos.value); apellidos=apellidos.trim();
	var nombres = new String (form.nombres.value); nombres=nombres.trim();
	var fnac = new String (form.fnac.value); fnac=fnac.trim(); esFNac=esFecha(fnac);
	var anac = new String (form.anac.value);
	var mnac = new String (form.mnac.value);
	var dnac = new String (form.dnac.value);
	var tdoc = new String (form.tdoc.value);
	var ndoc = new String (form.ndoc.value); ndoc=ndoc.trim();
	var dirfam = new String (form.dirfam.value); dirfam=dirfam.trim();
	var gsan = new String (form.gsan.value);
	if (form.afiliado.checked) var afiliado="S"; else var afiliado="N";
	var tel = new String (form.tel.value);
	var cel = new String (form.cel.value);
	var mbaja = new String (form.mbaja.value);
	var fbaja = new String (form.fbaja.value); fbaja=fbaja.trim(); esFBaja=esFecha(fbaja);
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	var instruccion = new String (form.instruccion.value);
	var educacion = new String (form.educacion.value);
	var centroeduc = new String (form.centroeduc.value);
	if (form.trabaja.checked) var trabaja="S"; else var trabaja="N";
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var diremp = new String (form.diremp.value); diremp=diremp.trim();
	var tservicio = new String (form.tservicio.value); tservicio=tservicio.trim();
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.trim();
	var comentarios = new String (form.comentarios.value); comentarios=comentarios.trim();
	
	var anios = document.getElementById("anac").value; anios = parseInt(anios);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (parent=="" || apellidos=="" || nombres=="" || fnac=="") msjError(1010);
	else if (anios > 9 && (tdoc == "" || ndoc == "")) alert("¡SI EL NIÑO ES MAYOR A NUEVE AÑOS SE REQUIERE SU DOCUMENTO DE IDENTIDAD!"); 
	else if (!esFNac) msjError(1080);
	else if (!esFBaja && mbaja!="") msjError(1160);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FAMILIARES&accion="+accion+"&registro="+registro+"&persona="+persona+"&parent="+parent+"&sexo="+sexo+"&apellidos="+apellidos+"&nombres="+nombres+"&fnac="+fnac+"&anac="+anac+"&mnac="+mnac+"&dnac="+dnac+"&tdoc="+tdoc+"&ndoc="+ndoc+"&dirfam="+dirfam+"&gsan="+gsan+"&afiliado="+afiliado+"&tel="+tel+"&cel="+cel+"&mbaja="+mbaja+"&fbaja="+fbaja+"&status="+status+"&instruccion="+instruccion+"&educacion="+educacion+"&centroeduc="+centroeduc+"&trabaja="+trabaja+"&empresa="+empresa+"&diremp="+diremp+"&tservicio="+tservicio+"&sueldo="+sueldo+"&comentarios="+comentarios);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else cargarPagina(form, "empleados_familiares.php");
			}
		}
	}
	return false;
}
//	FUNCION PARA BLOQUEAR/DESBLOQUEAR LA OPCION DE FECHA DE BAJA
function enabledBaja(form, motivo) {
	if (motivo!="") form.fbaja.disabled=false; else form.fbaja.disabled=true;
}

//	MAESTRO DE GRADO DE INSTRUCCION
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarGInstruccion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || codigo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GINSTRUCCION&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (accion=="GUARDAR") {
					if (error!=0) alert ("¡"+error+"!");
					else {
						var registro=codigo;
						var elemento=confirm("¡Desea ingresar los niveles del grado de instrucción?");
						if (elemento) location.href="ginstruccion_editar.php?filtro="+form.filtro.value+"&registro="+registro;
						else location.href="ginstruccion.php?filtro="+form.filtro.value;
					}
				} else {
					if (error!=0) alert ("¡"+error+"!");
					else location.href="ginstruccion.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarNInstruccion(form, accion) {
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var elemento = new String (form.elemento.value); elemento=elemento.trim(); form.elemento.value=elemento;
	var helemento = new String (form.helemento.value); helemento=helemento.trim(); form.helemento.value=helemento;
	var detalle = new String (form.detalle.value); detalle=detalle.trim(); form.detalle.value=detalle;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (elemento=="" || detalle=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GINSTRUCCION&accion="+accion+"&codigo="+codigo+"&elemento="+elemento+"&helemento="+helemento+"&detalle="+detalle);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var registro=codigo;
					location.href="ginstruccion_editar.php?filtro="+filtro+"&registro="+registro;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN RIESGO
function editarNInstruccion(form) {
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var elemento = form.det.value;
	if (elemento=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GINSTRUCCION&accion=EDITAR&codigo="+codigo+"&elemento="+elemento);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var datos=ajax.responseText;
				var ed = datos.split("elemento="); 
				var error=ed[0]; 
				var elemento=new String (ed[1]);
				var detalle=new String (ed[2]);
				if (error!=0) alert ("¡"+error+"!");
				else {
					var idHElemento=document.getElementById("helemento");
					var idElemento=document.getElementById("elemento");
					var idDetalle=document.getElementById("detalle");					
					idHElemento.value=elemento;
					idElemento.value=elemento;
					idDetalle.value=detalle;
					idDetalle.focus();
				}
			}
		}
	}
}
//	FUNCION PARA ELIMINAR UN RIESGO
function eliminarNInstruccion(form, pagina, foraneo, modulo) {
	var idCodigo=document.getElementById("codigo");	var codigo = new String (idCodigo.value);
	var idFiltro=document.getElementById("filtro");	var filtro = new String (idFiltro.value);
	var elemento = form.det.value;
	if (elemento=="") msjError(1000);
	else {
		var registro=codigo;
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo="+modulo+"&accion=ELIMINARELEMENTO&codigo="+registro+"&elemento="+elemento);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, "ginstruccion_editar.php?accion=ELIMINAR&foco=ELIMINAR&filtro="+filtro+"&registro="+registro+"&elemento="+elemento);
				}
			}		
		}
	}
}
//
function resetNInstruccion(form) {
	form.helemento.value="";
	form.elemento.value="";
	form.detalle.value="";
	form.ult_usuario.value="";
	form.ult_fecha.value="";
}

//	MAESTRO DE CENTRO DE ESTUDIOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCEstudio(form, accion) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var ubicacion = new String (form.ubicacion.value); ubicacion=ubicacion.trim(); form.ubicacion.value=ubicacion;
	if (document.getElementById("estudio").checked) var estudio = "S"; else var estudio = "N";
	if (document.getElementById("curso").checked) var curso = "S"; else var curso = "N";
		
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CESTUDIO&accion="+accion+"&codigo="+form.codigo.value+"&descripcion="+descripcion+"&ubicacion="+ubicacion+"&estudio="+estudio+"&curso="+curso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="centroestudio.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE IDIOMAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarIdioma(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcionl = new String (form.descripcionl.value); descripcionl=descripcionl.trim(); form.descripcionl.value=descripcionl;
	var descripcione = new String (form.descripcione.value); descripcione=descripcione.trim(); form.descripcione.value=descripcione;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codigo=="" || descripcionl=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=IDIOMAS&accion="+accion+"&codigo="+codigo+"&descripcionl="+descripcionl+"&descripcione="+descripcione);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="idiomas.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE PROFESIONES
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarProfesion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var grado = new String (form.grado.value);
	var area = new String (form.area.value);
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || grado=="" || area=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROFESIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&grado="+grado+"&area="+area+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="profesiones.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarExperiencia(form, accion) {
	var idPersona=document.getElementById("persona");	var persona = new String (idPersona.value);
	var secuencia = new String (form.secuencia.value);
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var mcese = new String (form.mcese.value);
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var area = new String (form.area.value);
	var ente = new String (form.ente.value);
	var cargo = new String (form.cargo.value); cargo=cargo.trim();
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.trim(); sueldo=sueldo.replace(",", ".");
	var funciones = new String (form.funciones.value); funciones=funciones.trim();
	var VFecha=esVFecha(fdesde, fhasta);
	var DFecha=esAFecha(fdesde);
	var HFecha=esAFecha(fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (empresa=="") msjError(1010);
	else if (isNaN(sueldo)) msjError(1060);
	else if (!esFecha(fdesde) || !esFecha(fhasta) || !VFecha || !DFecha || !HFecha) msjError(1260);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EXPERIENCIA&accion="+accion+"&persona="+persona+"&secuencia="+secuencia+"&empresa="+empresa+"&mcese="+mcese+"&fdesde="+fdesde+"&fhasta="+fhasta+"&area="+area+"&ente="+ente+"&cargo="+cargo+"&sueldo="+sueldo+"&funciones="+funciones);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="empleados_experiencia.php?registro="+persona;
			}
		}
	}
	return false;
}
//	FUNCION PARA EDITAR UN RIESGO
function optExperiencia(form, accion) {
	var idPersona=document.getElementById("persona");	var persona = new String (idPersona.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="empleados_experiencia.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
		} else location.href="empleados_experiencia.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
	}
}
//
function resetExperiencia(form) {
	form.secuencia.value="";
	form.empresa.value="";
	form.mcese.value="";
	form.fdesde.value="";
	form.fhasta.value="";
	form.area.value="";
	form.ente.value="";
	form.cargo.value="";
	form.sueldos.value="";
	form.funciones.value="";
	form.banco.value="";
	form.banco.value="";
	form.banco.value="";
}

//FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarEstudio(form, accion) {
	var idPersona=document.getElementById("registro");	var persona = new String (idPersona.value);
	var secuencia = new String (form.secuencia.value);
	var grado = new String (form.grado.value);
	var nivel = new String (form.nivel.value);
	var area = new String (form.area.value);
	var profesion = new String (form.profesion.value);
	var codcentro = new String (form.codcentro.value); codcentro=codcentro.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var fgraduacion = new String (form.fgraduacion.value); fgraduacion=fgraduacion.trim();
	var colegiatura = new String (form.colegiatura.value);
	var ncolegiatura = new String (form.ncolegiatura.value); ncolegiatura=ncolegiatura.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	var esIntervalo=esVFecha(fdesde, fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (grado=="" || codcentro=="") msjError(1010);
	//else if (!esFecha(fgraduacion)) msjError(1260);
	else {
		if (secuencia=="") accion="GUARDAR"; else accion="ACTUALIZAR"
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ESTUDIO&accion="+accion+"&persona="+persona+"&secuencia="+secuencia+"&grado="+grado+"&nivel="+nivel+"&area="+area+"&profesion="+profesion+"&codcentro="+codcentro+"&fdesde="+fdesde+"&fhasta="+fhasta+"&colegiatura="+colegiatura+"&ncolegiatura="+ncolegiatura+"&observaciones="+observaciones+"&fgraduacion="+fgraduacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="instruccion_estudios.php?registro="+persona;
			}
		}
	}
	return false;
}
function optEstudio(form, accion) {
	var idPersona=document.getElementById("registro");	var persona = new String (idPersona.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="instruccion_estudios.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
		} else location.href="instruccion_estudios.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
	}
}
function resetEstudio(form) {
	form.secuencia.value="";
	form.nivel.value="";
	form.area.value="";
	form.profesion.value="";
	form.codcentro.value="";
	form.nomcentro.value="";
	form.fdesde.value="";
	form.fhasta.value="";
	form.colegiatura.value="";
	form.ncolegiatura.value="";
	form.observaciones.value="";
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarInstruccionIdioma(form, persona) {
	var idioma=form.idioma.value;
	var lectura=form.lectura.value;
	var oral=form.oral.value;
	var escritura=form.escritura.value;
	var general=form.general.value;
	if (idioma=="" || lectura=="" || oral=="" || escritura=="" || general=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ESTUDIO&accion=IDIOMA&persona="+persona+"&idioma="+idioma+"&lectura="+lectura+"&oral="+oral+"&escritura="+escritura+"&general="+general);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="instruccion_idiomas.php?registro="+persona;
			}
		}
	}
	return false;
}

//FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarInstruccionCurso(form, accion) {
	var idPersona=document.getElementById("registro");	var persona = new String (idPersona.value);
	var secuencia = new String (form.secuencia.value);
	var codcurso = new String (form.codcurso.value);
	var tcurso = new String (form.tcurso.value);
	var codcentro = new String (form.codcentro.value); codcentro=codcentro.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var fculminacion = new String (form.fculminacion.value); fculminacion=fculminacion.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	var horas = new String (form.horas.value); horas=horas.trim();
	var anios = new String (form.anios.value); anios=anios.trim();
	if (form.flag.checked) var flag="S"; else var flag="N";
	if (form.pago.checked) var flagpago="S"; else var flagpago="N";
	var esIntervalo=esVFecha(fdesde, fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codcurso=="" || codcentro=="" || fculminacion == "") msjError(1010);
	else if (!esPContable(fculminacion)) alert("¡PERIODO DE CULMINACION INCORRECTO!");
	else if (isNaN(horas)) msjError(1270);
	else if (isNaN(anios)) msjError(1280);
	
	else {
		if (secuencia=="") accion="GUARDAR"; else accion="ACTUALIZAR"
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=INSTRUCCION_CURSOS&accion="+accion+"&persona="+persona+"&secuencia="+secuencia+"&codcurso="+codcurso+"&codcentro="+codcentro+"&fdesde="+fdesde+"&fhasta="+fhasta+"&observaciones="+observaciones+"&horas="+horas+"&anios="+anios+"&tcurso="+tcurso+"&flag="+flag+"&fculminacion="+fculminacion+"&flagpago="+flagpago);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="instruccion_cursos.php?registro="+persona;
			}
		}
	}
	return false;
}
function optICurso(form, accion) {
	var idPersona=document.getElementById("registro");	var persona = new String (idPersona.value);
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="instruccion_cursos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
		} else location.href="instruccion_cursos.php?accion="+accion+"&registro="+persona+"&secuencia="+secuencia;
	}
}
function resetICurso(form) {
	form.secuencia.value="";
	form.codcurso.value="";
	form.nomcurso.value="";
	form.codcentro.value="";
	form.nomcentro.value="";
	form.fdesde.value="";
	form.fhasta.value="";
	form.horas.value="";
	form.anios.value="";
	form.observaciones.value="";
}

//	MAESTRO DE CURSOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarCurso(form, accion) {
	var codigo = new String (form.codigo.value);
	var area = new String (form.area.value);
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="" || area=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CURSOS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&area="+area);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="cursos.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarMeritos(form, persona) {
	var doc = new String (form.doc.value); doc=doc.trim();
	var fdoc = new String (form.fdoc.value); fdoc=fdoc.trim();
	var merito=form.merito.value;
	var responsable=form.codpersona.value;
	var tipo=form.tipo.value;
	var obs = new String (form.obs.value); obs=obs.trim();
	var externo = new String (form.externo.value); externo=externo.trim();
	if (form.flagexterno.checked) var flagexterno = "S"; else var flagexterno = "N";
	esFDoc=esFecha(fdoc);
	if (doc=="" || fdoc=="" || merito=="") msjError(1010);
	else if (responsable=="" && externo == "") alert("ERROR: Se requiere un responsable");
	else if (!esFDoc) msjError(1290);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MERITOS&accion=MERITOS&doc="+doc+"&fdoc="+fdoc+"&merito="+merito+"&responsable="+responsable+"&obs="+obs+"&tipo="+tipo+"&persona="+persona+"&externo="+externo+"&flagexterno="+flagexterno);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="meritos.php?registro="+persona;
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarDemeritos(form, persona) {
	var doc = new String (form.doc.value); doc=doc.trim();
	var fdoc = new String (form.fdoc.value); fdoc=fdoc.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var merito=form.merito.value;
	var responsable=form.codpersona.value;
	var tipo=form.tipo.value;
	var obs = new String (form.obs.value); obs=obs.trim();
	var externo = new String (form.externo.value); externo=externo.trim();
	if (form.flagexterno.checked) var flagexterno = "S"; else var flagexterno = "N";
	esFDoc=esFecha(fdoc);
	esFDesde=esFecha(fdesde);
	esFHasta=esFecha(fhasta);
	esPeriodo=esVFecha(fdesde, fhasta);
	if (doc=="" || fdoc=="" || merito=="") msjError(1010);
	else if (responsable=="" && externo == "") alert("ERROR: Se requiere un responsable");
	else if (!esFDoc) msjError(1290);
	else if (merito=="04" && (!esFDesde || !esFHasta || !esPeriodo)) msjError(1300);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=MERITOS&accion=DEMERITOS&doc="+doc+"&fdoc="+fdoc+"&merito="+merito+"&responsable="+responsable+"&obs="+obs+"&tipo="+tipo+"&persona="+persona+"&fdesde="+fdesde+"&fhasta="+fhasta+"&externo="+externo+"&flagexterno="+flagexterno);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="demeritos.php?registro="+persona;
			}
		}
	}
	return false;
}
//	FUNCION PARA ELIMINAR REGISTROS DE LOS TABS DE MERITOS
function eliminarMerito(form, pagina) {
	var secuencia = form.det.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) cargarPagina(form, pagina);
	}
}
//	FUNCION PARA DESBLOQUAR FECHA DE SUSPENSION
function enabledSuspension() {
	var merito=document.getElementById("merito").value;
	if (merito=="04") {
		document.getElementById("fdesde").disabled=false;
		document.getElementById("fhasta").disabled=false;
	} else {
		document.getElementById("fdesde").disabled=true;
		document.getElementById("fhasta").disabled=true;
		document.getElementById("fdesde").value="";
		document.getElementById("fhasta").value="";
	}
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarReferencias(form, persona) {
	var pagina=form.action;
	var nombre = new String (form.nombre.value); nombre=nombre.trim();
	var tel = new String (form.tel.value); tel=tel.trim();
	var dir = new String (form.dir.value); dir=dir.trim();
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var cargo = new String (form.cargo.value); cargo=cargo.trim();
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	if (nombre=="" || empresa=="" || (tipo=="L" && cargo=="")) msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REFERENCIAS&accion=PERSONALES&nombre="+nombre+"&tel="+tel+"&dir="+dir+"&empresa="+empresa+"&cargo="+cargo+"&persona="+persona+"&tipo="+tipo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href=pagina+"?registro="+persona;
			}
		}
	}
	return false;
}
//	FUNCION PARA ELIMINAR REGISTROS DE LOS TABS DE MERITOS
function eliminarReferencia(form, pagina) {
	var secuencia = form.det.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) cargarPagina(form, pagina);
	}
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPermisos(form, accion) {
	var tfecha = new String (form.tfecha.value);
	var ttiempo = new String (form.ttiempo.value);
	var dias = new String (form.dias.value);
	var horas = new String (form.horas.value);
	var minutos = new String (form.minutos.value);
	var filtro = new String (form.filtro.value);
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var status = new String (form.status.value); status=status.trim();
	var codempleado = new String (form.codempleado.value); codempleado=codempleado.trim();
	var codaprueba = new String (form.codaprueba.value); codaprueba=codaprueba.trim();
	var tpermiso = new String (form.tpermiso.value); tpermiso=tpermiso.trim();
	var tfalta = new String (form.tfalta.value); tfalta=tfalta.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	if (form.flagremunerado.checked) var remunerado="S"; else var remunerado="N"; 
	if (form.flagjustificativo.checked) var justificativo="S"; else var justificativo="N"; 
	if (form.flagexento.checked) var flagexento="S"; else var flagexento="N"; 
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var turnodesde = new String (form.turnodesde.value); turnodesde=turnodesde.trim();
	var turnohasta = new String (form.turnohasta.value); turnohasta=turnohasta.trim();
	var hdesde = new String (form.hdesde.value); hdesde=hdesde.trim();
	var hhasta = new String (form.hhasta.value); hhasta=hhasta.trim();	
	var desde=fdesde+" "+hdesde+":00 "+turnodesde;
	var hasta=fhasta+" "+hhasta+":00 "+turnohasta;
	var esFechaCorrecta=esDateTime(desde, hasta);
	var esPC=esPContable(periodo);
	if (codempleado=="" || codaprueba=="" || tpermiso=="" || tfalta=="" || periodo=="" || fdesde=="" || fhasta=="") msjError(1010);
	else if (!esPC) msjError(1030);
	else if (!esFechaCorrecta) msjError(1310);
	else if ((minutos == "" || minutos == 0) && (horas == "" || horas == 0)) alert("¡EL TIEMPO DE PERMISO NO PUEDE SER CERO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PERMISOS&accion="+accion+"&codempleado="+codempleado+"&codaprueba="+codaprueba+"&tpermiso="+tpermiso+"&tfalta="+tfalta+"&periodo="+periodo+"&fdesde="+fdesde+"&fhasta="+fhasta+"&hdesde="+hdesde+"&hhasta="+hhasta+"&observaciones="+observaciones+"&remunerado="+remunerado+"&justificativo="+justificativo+"&codigo="+codigo+"&status="+status+"&turnodesde="+turnodesde+"&turnohasta="+turnohasta+"&dias="+dias+"&horas="+horas+"&minutos="+minutos+"&flagexento="+flagexento+"&tfecha="+tfecha+"&ttiempo="+ttiempo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					if (filtro == "")
						cargarPagina(form, "framemain.php");
					else
						cargarPagina(form, "permisos.php?filtro="+filtro+"&limit=0");
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarAprobarPermisos(form, accion, btAprobar) {
	var filtro = new String (form.filtro.value); filtro=filtro.trim();
	var limit = new String (form.limit.value); limit=limit.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var codaprueba = new String (form.codaprueba.value); codaprueba=codaprueba.trim();
	var obsaprobado = new String (form.obsaprobado.value); obsaprobado=obsaprobado.trim();
	var status = new String (form.status.value); status=status.trim();
	if (form.flagremunerado.checked) var remunerado="S"; else var remunerado="N"; 
	if (form.flagjustificativo.checked) var justificativo="S"; else var justificativo="N"; 
	if (form.flagexento.checked) var flagexento="S"; else var flagexento="N"; 
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=PERMISOS&accion="+accion+"&obsaprobado="+obsaprobado+"&remunerado="+remunerado+"&justificativo="+justificativo+"&codigo="+codigo+"&status="+status+"&codaprueba="+codaprueba+"&flagexento="+flagexento);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else {
				cargarPagina(form, "permisos_lista.php?filtro="+filtro+"&limit=0");	
				if (accion=="APROBAR") window.open("permisos_abrir.php?registro="+codigo, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
			}
		}
	}
	return false;
}
function anularPermiso(form) {
	var codigo=form.registro.value;
	var filtro=form.filtro.value;
	var limit=form.limit.value;
	if (codigo=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ANULAR
		var anular=confirm("¡Esta seguro de anular este permiso?");
		if (anular) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PERMISOS&accion=ANULAR&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, "permisos.php?filtro="+filtro+"&limit="+limit);
				}
			}
		}	
	}
}
function abrirPermiso(form) {
	var codigo=form.registro.value;
	if (codigo=="") msjError(1000);
	else {
		window.open("permisos_abrir.php?registro="+codigo, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
	}
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE PERMISOS
function filtroPermisos(form, limit, pagina) {
	var desde=new String(form.ffingresod.value); desde=desde.trim();
	var hasta=new String(form.ffingresoh.value); hasta=hasta.trim();
	var permiso=new String(form.fpermiso.value); permiso=permiso.trim();
	var empleado=new String(form.fempleado.value); empleado=empleado.trim();	
	var filtro="mastpersonas.CodPersona<>**";	
	if (form.chkorganismo.checked) filtro+=" AND mastempleado.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND mastempleado.CodDependencia LIKE *;"+form.fdependencia.value+";*";
	if (form.chkempleado.checked) filtro+=" AND mastempleado.CodEmpleado LIKE *"+form.fempleado.value+"*";
	if (form.chkpermiso.checked) filtro+=" AND rh_permisos.CodPermiso LIKE *"+form.fpermiso.value+"*";
	if (form.chkstatus.checked) filtro+=" AND rh_permisos.Estado = *"+form.fstatus.value+"*";
	if (form.chkfingreso.checked) {
		var esFIngD=esFecha(form.ffingresod.value);
		var esFIngH=esFecha(form.ffingresoh.value);
		var fechad = new String (form.ffingresod.value); fechad=fechad.trim();
		var fechah = new String (form.ffingresoh.value); fechah=fechah.trim();		
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");		
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");		
		filtro+=" AND (rh_permisos.FechaIngreso>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND rh_permisos.FechaIngreso<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFIngD=true; esFIngH=true; }
	if (esFIngD && esFIngH) {
		//document.getElementById("filtro").value=filtro;
		//document.getElementById("limit").value=0;
		var pagina=pagina+"?filtro="+filtro+"&limit=0";
		cargarPagina(form, pagina);
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
function getTotalDiasPermisos() {
	var fdesde = document.getElementById("fdesde").value;
	var fhasta = document.getElementById("fhasta").value;
	var hdesde = document.getElementById("hdesde").value;
	var hhasta = document.getElementById("hhasta").value;
	var turnodesde = document.getElementById("turnodesde").value;
	var turnohasta = document.getElementById("turnohasta").value;
	var desde = fdesde + " " + hdesde + ":00 " + turnodesde;
	var hasta = fhasta + " " + hhasta + ":00 " + turnohasta;
	var esFechaCorrecta = esDateTime(desde, hasta);
	
	if (esFechaCorrecta) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=GET-TOTAL-DIAS-PERMISOS&fdesde="+fdesde+"&fhasta="+fhasta+"&hdesde="+hdesde+"&hhasta="+hhasta+"&turnodesde="+turnodesde+"&turnohasta="+turnohasta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|:|");
				document.getElementById("dias").value = datos[0];
				document.getElementById("horas").value = datos[1];
				document.getElementById("minutos").value = datos[2];
				document.getElementById("tfecha").value = datos[3];
				document.getElementById("ttiempo").value = datos[4];
			}
		}
	} else {
		document.getElementById("dias").value = "";
		document.getElementById("horas").value = "";
		document.getElementById("minutos").value = "";
		document.getElementById("tfecha").value = "";
		document.getElementById("ttiempo").value = "";
	}
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPostulante(form, accion) {
	var filtro = new String (form.filtro.value); filtro=filtro.trim();
	var postulante = new String (form.postulante.value); postulante=postulante.trim();
	var status = new String (form.status.value); status=status.trim();
	var apellido1 = new String (form.apellido1.value); apellido1=apellido1.trim();
	var apellido2 = new String (form.apellido2.value); apellido2=apellido2.trim();
	var nombres = new String (form.nombres.value); nombres=nombres.trim();
	var sexo = new String (form.sexo.value); sexo=sexo.trim();
	var resumen = new String (form.resumen.value); resumen=resumen.trim();
	var ciudad = new String (form.ciudad.value); ciudad=ciudad.trim();
	var fnac = new String (form.fnac.value); fnac=fnac.trim();
	var dir = new String (form.dir.value); dir=dir.trim();
	var referencia = new String (form.referencia.value); referencia=referencia.trim();
	var ciudad2 = new String (form.ciudad2.value); ciudad2=ciudad2.trim();
	var tel = new String (form.tel.value); tel=tel.trim();
	var email = new String (form.email.value); email=email.trim();
	var tdoc = new String (form.tdoc.value); tdoc=tdoc.trim();
	var ndoc = new String (form.ndoc.value); ndoc=ndoc.trim();
	var gsan = new String (form.gsan.value); gsan=gsan.trim();
	var sitdom = new String (form.sitdom.value); sitdom=sitdom.trim();
	var edocivil = new String (form.edocivil.value); edocivil=edocivil.trim();
	var fedocivil = new String (form.fedocivil.value); fedocivil=fedocivil.trim();
	var obs = new String (form.obs.value); obs=obs.trim();
	var beneficas = new String (form.beneficas.value); beneficas=beneficas.trim();
	var culturales = new String (form.culturales.value); culturales=culturales.trim();
	var religiosas = new String (form.religiosas.value); religiosas=religiosas.trim();
	var laborales = new String (form.laborales.value); laborales=laborales.trim();
	var deportivas = new String (form.deportivas.value); deportivas=deportivas.trim();
	var sociales = new String (form.sociales.value); sociales=sociales.trim();
	if (form.flagbeneficas.checked) var fbeneficas="S"; else var fbeneficas="N";
	if (form.flagculturales.checked) var fculturales="S"; else var fculturales="N";
	if (form.flagreligiosas.checked) var freligiosas="S"; else var freligiosas="N";
	if (form.flaglaborales.checked) var flaborales="S"; else var flaborales="N";
	if (form.flagdeportivas.checked) var fdeportivas="S"; else var fdeportivas="N";
	if (form.flagsociales.checked) var fsociales="S"; else var fsociales="N";
	var esFNac=esFecha(fnac);
	var esFEdoCivil=esFecha(fedocivil);
	
	if (apellido2=="" || nombres=="" || ciudad=="" || fnac=="" || dir=="" || ciudad2=="" || tel=="" || tdoc=="" || ndoc=="") msjError(1010);
	else if (!esFNac) msjError(1080);
	else if (!esFEdoCivil) msjError(1090);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES&accion="+accion+"&postulante="+postulante+"&status="+status+"&apellido1="+apellido1+"&apellido2="+apellido2+"&nombres="+nombres+"&sexo="+sexo+"&resumen="+resumen+"&ciudad="+ciudad+"&fnac="+fnac+"&dir="+dir+"&ciudad2="+ciudad2+"&tel="+tel+"&email="+email+"&tdoc="+tdoc+"&ndoc="+ndoc+"&gsan="+gsan+"&sitdom="+sitdom+"&edocivil="+edocivil+"&fedocivil="+fedocivil+"&beneficas="+beneficas+"&culturales="+culturales+"&religiosas="+religiosas+"&laborales="+laborales+"&deportivas="+deportivas+"&sociales="+sociales+"&fbeneficas="+fbeneficas+"&fculturales="+fculturales+"&freligiosas="+freligiosas+"&flaborales="+flaborales+"&fdeportivas="+fdeportivas+"&fsociales="+fsociales+"&referencia="+referencia+"&obs="+obs);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var response=ajax.responseText;
				var ep=response.split("|.|");
				if (ep[0]!=0) alert ("¡"+ep[0]+"!");
				else {
					if (accion=="GUARDAR") {						
						respuesta=confirm("¿Desea ingresar la información faltante del postulante?");
						if (respuesta) cargarPagina(form, "postulantes_editar.php?registro="+ep[1]+"&filtro="+filtro+"&limit=0");
						else cargarPagina(form, "postulantes.php?filtro="+filtro+"&limit=0");
					} else cargarPagina(form, "postulantes.php?filtro="+filtro+"&limit=0");
				}
			}
		}
	}
	return false;
}
function verificarPostulanteInstruccion(form, accion) {
	var postulante=document.getElementById("registro").value;
	var secuencia = new String (form.secuencia.value);
	var grado = new String (form.grado.value);
	var nivel = new String (form.nivel.value);
	var area = new String (form.area.value);
	var profesion = new String (form.profesion.value);
	var codcentro = new String (form.codcentro.value); codcentro=codcentro.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var colegiatura = new String (form.colegiatura.value);
	var ncolegiatura = new String (form.ncolegiatura.value); ncolegiatura=ncolegiatura.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	var esIntervalo=esVFecha(fdesde, fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (grado=="" || codcentro=="") msjError(1010);
	else if (!esFecha(fdesde) || !esFecha(fhasta) || !esIntervalo || !esAFecha(fdesde) || !esAFecha(fhasta)) msjError(1260);
	else {
		if (secuencia=="") accion="GUARDAR"; else accion="ACTUALIZAR"
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES_INSTRUCCION&accion="+accion+"&postulante="+postulante+"&secuencia="+secuencia+"&grado="+grado+"&nivel="+nivel+"&area="+area+"&profesion="+profesion+"&codcentro="+codcentro+"&fdesde="+fdesde+"&fhasta="+fhasta+"&colegiatura="+colegiatura+"&ncolegiatura="+ncolegiatura+"&observaciones="+observaciones);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_instruccion.php?registro="+postulante;
			}
		}
	}
	return false;
}
function optPostulante(form, accion) {
	var postulante=document.getElementById("registro").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="postulantes_instruccion.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
		} else location.href="postulantes_instruccion.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
	}
}
function verificarPostulanteIdioma(form, postulante) {
	var idioma=form.idioma.value;
	var lectura=form.lectura.value;
	var oral=form.oral.value;
	var escritura=form.escritura.value;
	var general=form.general.value;
	if (idioma=="" || lectura=="" || oral=="" || escritura=="" || general=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES&accion=IDIOMA&postulante="+postulante+"&idioma="+idioma+"&lectura="+lectura+"&oral="+oral+"&escritura="+escritura+"&general="+general);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_idioma.php?registro="+postulante;
			}
		}
	}
	return false;
}
function verificarPostulanteInformat(form, postulante) {
	var curso=form.curso.value;
	var nivel=form.nivel.value;
	if (curso=="" || nivel=="") msjError(1180);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES&accion=INFORMATICA&postulante="+postulante+"&curso="+curso+"&nivel="+nivel);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_informatica.php?registro="+postulante;
			}
		}
	}
	return false;
}
function verificarPostulanteCurso(form, accion) {
	var postulante=document.getElementById("registro").value;
	var secuencia = new String (form.secuencia.value);
	var codcurso = new String (form.codcurso.value);
	var tcurso = new String (form.tcurso.value);
	var codcentro = new String (form.codcentro.value); codcentro=codcentro.trim();
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var observaciones = new String (form.observaciones.value); observaciones=observaciones.trim();
	var horas = new String (form.horas.value); horas=horas.trim();
	var anios = new String (form.anios.value); anios=anios.trim();
	if (form.flag.checked) var flag="S"; else var flag="N";
	var esIntervalo=esVFecha(fdesde, fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (codcurso=="" || codcentro=="") msjError(1010);
	else if (!esFecha(fdesde) || !esFecha(fhasta) || !esIntervalo || !esAFecha(fdesde) || !esAFecha(fhasta)) msjError(1260);
	else if (isNaN(horas)) msjError(1270);
	else if (isNaN(anios)) msjError(1280);
	
	else {
		if (secuencia=="") accion="GUARDAR"; else accion="ACTUALIZAR"
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES_CURSOS&accion="+accion+"&postulante="+postulante+"&secuencia="+secuencia+"&codcurso="+codcurso+"&codcentro="+codcentro+"&fdesde="+fdesde+"&fhasta="+fhasta+"&observaciones="+observaciones+"&horas="+horas+"&anios="+anios+"&tcurso="+tcurso+"&flag="+flag);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_cursos.php?registro="+postulante;
			}
		}
	}
	return false;
}
function optPostCurso(form, accion) {
	var postulante=document.getElementById("registro").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="postulantes_cursos.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
		} else location.href="postulantes_cursos.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
	}
}
function verificarPostExperiencia(form, accion) {
	var postulante=document.getElementById("postulante").value;
	var secuencia = new String (form.secuencia.value);
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var mcese = new String (form.mcese.value);
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim();
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim();
	var area = new String (form.area.value);
	var ente = new String (form.ente.value);
	var cargo = new String (form.cargo.value); cargo=cargo.trim();
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.trim(); sueldo=sueldo.replace(",", ".");
	var funciones = new String (form.funciones.value); funciones=funciones.trim();
	var VFecha=esVFecha(fdesde, fhasta);
	var DFecha=esAFecha(fdesde);
	var HFecha=esAFecha(fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (empresa=="") msjError(1010);
	else if (isNaN(sueldo)) msjError(1060);
	else if (!esFecha(fdesde) || !esFecha(fhasta) || !VFecha || !DFecha || !HFecha) msjError(1260);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTE_EXPERIENCIA&accion="+accion+"&postulante="+postulante+"&secuencia="+secuencia+"&empresa="+empresa+"&mcese="+mcese+"&fdesde="+fdesde+"&fhasta="+fhasta+"&area="+area+"&ente="+ente+"&cargo="+cargo+"&sueldo="+sueldo+"&funciones="+funciones);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_experiencia.php?registro="+postulante;
			}
		}
	}
	return false;
}
function optPostExperiencia(form, accion) {
	var postulante=document.getElementById("postulante").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) location.href="postulantes_experiencia.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
		} else location.href="postulantes_experiencia.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
	}
}
function verificarPostReferencias(form, postulante) {
	var pagina=form.action;
	var nombre = new String (form.nombre.value); nombre=nombre.trim();
	var tel = new String (form.tel.value); tel=tel.trim();
	var dir = new String (form.dir.value); dir=dir.trim();
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var cargo = new String (form.cargo.value); cargo=cargo.trim();
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	if (nombre=="" || empresa=="" || (tipo=="L" && cargo=="")) msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES_REFERENCIAS&nombre="+nombre+"&tel="+tel+"&dir="+dir+"&empresa="+empresa+"&cargo="+cargo+"&postulante="+postulante+"&tipo="+tipo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href=pagina+"?registro="+postulante;
			}
		}
	}
	return false;
}
function verificarPostDocumento(form, accion) {
	var secuencia = new String (form.secuencia.value);
	var registro = new String (form.registro.value);
	var doc = new String (form.doc.value);
	if (form.flagpresento.checked) var flagpresento="S"; else var flagpresento="N";
	var observacion = new String (form.observacion.value); observacion=observacion.trim();
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (doc=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES_DOCUMENTOS&accion="+accion+"&secuencia="+secuencia+"&registro="+registro+"&doc="+doc+"&flagpresento="+flagpresento+"&observacion="+observacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_documentos.php?registro="+registro;
			}
		}
	}
	return false;
}
function resetPostDocumento(form) {
	form.secuencia.value="";
	form.doc.value="";
	form.observacion.value="";
	form.flagpresento.checked=false;
}
function optPostDocumento(form, accion) {
	var postulante=document.getElementById("registro").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) location.href="postulantes_documentos.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
		} else location.href="postulantes_documentos.php?accion="+accion+"&registro="+postulante+"&secuencia="+secuencia;
	}
}
function verificarPostulanteCargo(form, postulante) {
	var organismo=form.organismo.value;
	var cargo=form.cargo.value;
	var comentario=form.comentario.value; comentario=comentario.trim();
	if (organismo=="" || cargo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=POSTULANTES&accion=CARGOS&postulante="+postulante+"&organismo="+organismo+"&cargo="+cargo+"&comentario="+comentario);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="postulantes_cargos.php?registro="+postulante;
			}
		}
	}
	return false;
}
//	FUNCION PARA OBTENER LAS PROFESIONES A PARTIR DE EL GRADO Y EL AREA
function getFProfesiones(form) {
	var grado=document.getElementById("fginstruccion");
	var area=document.getElementById("farea");
	var profesion=document.getElementById("fprofesion");
	var chkprofesion=document.getElementById("chkprofesion");
	//--
	if (chkprofesion.checked) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_selects.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=getOptions_2&tabla=fprofesiones&grado="+grado.value+"&area="+area.value);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==1) {
				// Mientras carga elimino la opcion "" y pongo una que dice "Cargando..."
				profesion.length=0;
				var nuevaOpcion=document.createElement("option");
				nuevaOpcion.value="";
				nuevaOpcion.innerHTML="Cargando...";
				profesion.appendChild(nuevaOpcion);
				profesion.disabled=true;
			}
			if (ajax.readyState==4)	{
				profesion.parentNode.innerHTML=ajax.responseText;
			}
		}
	}
}

//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPatrimonioInmueble(form, persona) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();	
	var ubicacion = new String (form.ubicacion.value); ubicacion=ubicacion.trim();	
	var uso = new String (form.uso.value); uso=uso.trim();	
	var valor = new String (form.valor.value); valor=valor.trim();  valor=valor.replace(",", ".");
	var fhipoteca = document.getElementById("fhipoteca").checked;
	if (fhipoteca) var flaghipoteca="S"; else var flaghipoteca="N";
	if (descripcion=="" || ubicacion=="" || uso=="") msjError(1010);	
	else if (isNaN(valor)) msjError(1320);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PATRIMONIO&accion=INMUEBLE&persona="+persona+"&descripcion="+descripcion+"&ubicacion="+ubicacion+"&uso="+uso+"&valor="+valor+"&flaghipoteca="+flaghipoteca);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					actualizarPatrimonio('inmueble', valor, persona);
					cargarPagina(form, "patrimonio_inmueble.php?registro="+persona);
				}
			}
		}
	}
	return false;
}
function verificarPatrimonioInversion(form, persona) {
	var titular = new String (form.titular.value); titular=titular.trim();
	var empresa = new String (form.empresa.value); empresa=empresa.trim();
	var certificado = new String (form.certificado.value); certificado=certificado.trim();	
	var cant = new String (form.cant.value); cant=cant.trim();
	var valorn = new String (form.valorn.value); valorn=valorn.trim();  valorn=valorn.replace(",", ".");
	var valor = new String (form.valor.value); valor=valor.trim();  valor=valor.replace(",", ".");
	var fgarantia = document.getElementById("fgarantia").checked;
	if (fgarantia) var flaggarantia="S"; else var flaggarantia="N";
	if (titular=="" || empresa=="" || certificado=="" || cant=="") msjError(1010);
	else if (isNaN(cant)) msjError(1340);
	else if (isNaN(valorn)) msjError(1330);
	else if (isNaN(valor)) msjError(1320);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PATRIMONIO&accion=INVERSION&persona="+persona+"&titular="+titular+"&empresa="+empresa+"&certificado="+certificado+"&cant="+cant+"&valorn="+valorn+"&valor="+valor+"&flaggarantia="+flaggarantia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					actualizarPatrimonio('inversion', valor, persona);
					cargarPagina(form, "patrimonio_inversion.php?registro="+persona);
				}
			}
		}
	}
	return false;
}
function verificarPatrimonioVehiculo(form, persona) {
	var marca = new String (form.marca.value); marca=marca.trim();
	var modelo = new String (form.modelo.value); modelo=modelo.trim();
	var anio = new String (form.anio.value); anio=anio.trim();
	var anio = new String (form.anio.value); anio=anio.trim();
	var color = new String (form.color.value); color=color.trim();
	var placa = new String (form.placa.value); placa=placa.trim();
	var valor = new String (form.valor.value); valor=valor.trim();  valor=valor.replace(",", ".");
	var fprendado = document.getElementById("fprendado").checked;
	if (fprendado) var flagprendado="S"; else var flagprendado="N";
	
	if (marca=="" || modelo=="" || anio=="" || color=="" || placa=="") msjError(1010);
	else if (isNaN(anio)) msjError(1350);
	else if (isNaN(valor)) msjError(1320);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PATRIMONIO&accion=VEHICULO&persona="+persona+"&marca="+marca+"&modelo="+modelo+"&anio="+anio+"&color="+color+"&placa="+placa+"&valor="+valor+"&flagprendado="+flagprendado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					actualizarPatrimonio('vehiculo', valor, persona);
					cargarPagina(form, "patrimonio_vehiculo.php?registro="+persona);
				}
			}
		}
	}
	return false;
}
function verificarPatrimonioCuenta(form, persona) {
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	var institucion = new String (form.institucion.value); institucion=institucion.trim();
	var cta = new String (form.cta.value); cta=cta.trim();
	var valor = new String (form.valor.value); valor=valor.trim();  valor=valor.replace(",", ".");
	var fgarantia = document.getElementById("fgarantia").checked;
	if (fgarantia) var flaggarantia="S"; else var flaggarantia="N";
	if (tipo=="" || institucion=="" || cta=="") msjError(1010);
	else if (isNaN(valor)) msjError(1320);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PATRIMONIO&accion=CUENTA&persona="+persona+"&tipo="+tipo+"&institucion="+institucion+"&cta="+cta+"&valor="+valor+"&flaggarantia="+flaggarantia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					actualizarPatrimonio('cuenta', valor, persona);
					cargarPagina(form, "patrimonio_cuenta.php?registro="+persona);
				}
			}
		}
	}
	return false;
}
function verificarPatrimonioOtro(form, persona) {
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var valorc = new String (form.valorc.value); valorc=valorc.trim();  valorc=valorc.replace(",", ".");
	var valor = new String (form.valor.value); valor=valor.trim();  valor=valor.replace(",", ".");
	var fgarantia = document.getElementById("fgarantia").checked;
	if (fgarantia) var flaggarantia="S"; else var flaggarantia="N";
	if (descripcion=="") msjError(1010);
	else if (isNaN(valor)) msjError(1320);
	else if (isNaN(valorc)) msjError(1360);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PATRIMONIO&accion=OTRO&persona="+persona+"&descripcion="+descripcion+"&valorc="+valorc+"&valor="+valor+"&flaggarantia="+flaggarantia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					actualizarPatrimonio('otro', valor, persona);
					cargarPagina(form, "patrimonio_otro.php?registro="+persona);
				}
			}
		}
	}
	return false;
}
function actualizarPatrimonio(tab, valor, persona) {
	var form = document.getElementById("frmentrada");
	form.action="patrimonio_totales.php?registro="+persona;
	form.target="iTotales";
	form.submit();
	form.target="";
}
function eliminarPatrimonio(form, pagina, persona) {
	var secuencia = form.det.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			cargarPagina(form, pagina);
			//actualizarPatrimonio('', '', persona);
		}
	}
}

//	MAESTRO DE PREGUNTAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPregunta(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var minimo = new String (form.minimo.value); minimo=minimo.trim(); minimo=parseInt(minimo);
	var maximo = new String (form.maximo.value); maximo=maximo.trim(); maximo=parseInt(maximo);
	var area = new String (form.area.value); area=area.trim();
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	if (descripcion=="" || minimo=="" || maximo=="" || area=="") msjError(1010);
	else if (isNaN(minimo) || isNaN(maximo) || minimo>maximo) msjError(1320);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PREGUNTAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&minimo="+minimo+"&maximo="+maximo+"&status="+status+"&area="+area);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else location.href="preguntas.php?filtro="+form.filtro.value;
			}
		}
	}
	return false;
}

//	MAESTRO DE PLANTILLAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarPlantilla(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PLANTILLAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var response=ajax.responseText;
				var datos=response.split("|:|");
				if (datos[0]!=0) alert ("¡"+error+"!");
				else {
					if (accion=="GUARDAR") {
						respuesta=confirm("¿Desea ingresar las preguntas de la plantilla?");
						if (respuesta) location.href="plantillas_editar.php?registro="+datos[1];
						else location.href="plantillas.php?filtro="+form.filtro.value;
					} else location.href="plantillas.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
//	FUNCION PARA SELECCIONAR UNA/S PREGUNTA DE UNA LISTA Y GUARDARLO EN 
function cargarPreguntas(pagina, target) {
	var form=document.getElementById("frmentrada");
	form.action=pagina;
	form.target=target;
	form.submit();
	window.close();
}
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE ENCUESTAS
function filtroEncuesta(form, limit, pagina) {
	filtro="";
	if (form.chkorganismo.checked) filtro+=" AND CodOrganismo = *"+form.forganismo.value+"*";
	if (form.chkperiodo.checked) {
		var esPeriodo=esPContable(form.fperiodo.value);
		if (esPeriodo) filtro+=" AND PeriodoContable = *"+form.fperiodo.value+"*";
	} else esPeriodo=true;
	
	if (esPeriodo) {
		document.getElementById("filtro").value=filtro;
		document.getElementById("limit").value=0;
		cargarPagina(form, pagina);
	} else {
		form.fperiodo.value="";
		msjError(1030);
	}
}

//	MAESTRO DE ENCUESTAS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarEncuesta(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var titulo = new String (form.titulo.value); titulo=titulo.trim();
	var fecha = new String (form.fecha.value); fecha=fecha.trim();
	var muestra = new String (form.muestra.value); muestra=muestra.trim();
	var obs = new String (form.obs.value); obs=obs.trim();
	var esPeriodo=esPContable(periodo);
	var esFechaCorrecta=esFecha(fecha);
	if (organismo=="" || periodo=="" || titulo=="") msjError(1010);
	else if (!esPeriodo) msjError(1030);
	else if (fecha!="" && !esFechaCorrecta) msjError(1260);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=ENCUESTAS&accion="+accion+"&codigo="+codigo+"&organismo="+organismo+"&periodo="+periodo+"&titulo="+titulo+"&fecha="+fecha+"&muestra="+muestra+"&obs="+obs);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var response=ajax.responseText;
				var datos=response.split("|:|");
				if (datos[0]!=0) alert ("¡"+error+"!");
				else {
					if (accion=="GUARDAR") {
						respuesta=confirm("¿Desea ingresar las preguntas de la encuesta?");
						if (respuesta) cargarPagina(form, "encuestas_editar.php?registro="+datos[1]);
						else cargarPagina(form, "encuestas.php?limit=0");
					} else cargarPagina(form, "encuestas.php?limit=0");
				}
			}
		}
	}
	return false;
}
function totalEncuestaPreguntas(rows) {
	if (rows) {
		var btEliminarPregunta=document.getElementById("btEliminarPregunta"); btEliminarPregunta.disabled=false;
	} else {
		var btEliminarPregunta=document.getElementById("btEliminarPregunta"); btEliminarPregunta.disabled=true;
	}
}
function cargarPlantilla(registro) {
	var det=document.getElementById("det").value;
	var form=document.getElementById("frmentrada");
	form.action="encuestas_preguntas.php?accion=AGREGAR-PLANTILLA&secuencia="+det+"&registro="+registro;
	form.target="iPreguntas";
	form.submit();
	window.close();
}

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalMuestras(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Muestras: "+rows;
	//
	if (rows) {
		var btEliminar=document.getElementById("btEliminar"); btEliminar.disabled=false;
		var btGuardar=document.getElementById("btGuardar"); btGuardar.disabled=false;
	} else {
		var btEliminar=document.getElementById("btEliminar"); btEliminar.disabled=true;
		var btGuardar=document.getElementById("btGuardar"); btGuardar.disabled=true;
	}
}
function eliminarSujeto(form, pagina) {
	var secuencia = form.detalle.value;
	if (secuencia=="") msjError(1000);
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			cargarPagina(form, pagina);
			//actualizarPatrimonio('', '', persona);
		}
	}
}

//	FORMULARIO AGREGAR REQUERIMIENTOS
//	FUNCION PARA VERIFICAR QUE SE INGRESARON LOS DATOS OBLIGATORIOS Y QUE EL REGISTRO NO EXISTA EN LA BASE DE DATOS
function verificarRequerimiento(form, accion) {
	var filtro = new String (form.filtro.value); filtro=filtro.trim(); form.filtro.value=filtro;
	var regresar = new String (form.regresar.value); regresar=regresar.trim(); form.regresar.value=regresar;
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var status = new String (form.status.value); status=status.trim(); form.status.value=status;
	var vacantes = new String (form.vacantes.value); vacantes=vacantes.trim(); form.vacantes.value=vacantes;
	var fsolicitud = new String (form.fsolicitud.value); fsolicitud=fsolicitud.trim(); form.fsolicitud.value=fsolicitud;
	var pendientes = new String (form.pendientes.value); pendientes=pendientes.trim(); form.pendientes.value=pendientes;
	var ftermino = new String (form.ftermino.value); ftermino=ftermino.trim(); form.ftermino.value=ftermino;
	var tiempo = new String (form.tiempo.value); tiempo=tiempo.trim(); form.tiempo.value=tiempo;
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;
	var dependencia = new String (form.dependencia.value); dependencia=dependencia.trim(); form.dependencia.value=dependencia;
	var modalidad = new String (form.modalidad.value); modalidad=modalidad.trim(); form.modalidad.value=modalidad;
	var cargo = new String (form.cargo.value); cargo=cargo.trim(); form.cargo.value=cargo;
	var codempleado = new String (form.codempleado.value); codempleado=codempleado.trim(); form.codempleado.value=codempleado;
	var vdesde = new String (form.vdesde.value); vdesde=vdesde.trim(); form.vdesde.value=vdesde;
	var vhasta = new String (form.vhasta.value); vhasta=vhasta.trim(); form.vhasta.value=vhasta;
	var contrato = new String (form.contrato.value); contrato=contrato.trim(); form.contrato.value=contrato;
	var motivo = new String (form.motivo.value); motivo=motivo.trim(); form.motivo.value=motivo;
	var ddesde = new String (form.ddesde.value); ddesde=ddesde.trim(); form.ddesde.value=ddesde;
	var dhasta = new String (form.dhasta.value); dhasta=dhasta.trim(); form.dhasta.value=dhasta;
	var esFSolicitud=esFecha(fsolicitud);
	var esFASolicitud=esAFecha(fsolicitud);
	var esFDdesde=esFecha(ddesde);
	var esFVigencia=esVFecha(vdesde, vhasta);
	var esFContrato=esVFecha(ddesde, dhasta); var esDFContrato=esFecha(ddesde); var esHFContrato=esFecha(dhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (status=="" || vacantes=="" || fsolicitud=="" || organismo=="" || dependencia=="" || modalidad=="" || cargo=="" || codempleado=="" || vdesde=="" || vhasta=="" || contrato=="" || motivo=="" || ddesde=="" || (form.dhasta.disabled==false && dhasta=="")) msjError(1010);
	else if (!esFSolicitud || !esFASolicitud) msjError(1380);
	else if (!esFVigencia) msjError(1370);
	else if ((form.dhasta.disabled!=true && !esFContrato) || (!esDFContrato)) alert("¡FECHA DE CONTRATO INCORRECTA!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&codigo="+codigo+"&status="+status+"&vacantes="+vacantes+"&fsolicitud="+fsolicitud+"&pendientes="+pendientes+"&ftermino="+ftermino+"&tiempo="+tiempo+"&organismo="+organismo+"&dependencia="+dependencia+"&modalidad="+modalidad+"&cargo="+cargo+"&codempleado="+codempleado+"&vdesde="+vdesde+"&vhasta="+vhasta+"&cargo="+cargo+"&contrato="+contrato+"&motivo="+motivo+"&ddesde="+ddesde+"&dhasta="+dhasta);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				var ec = error.split(":"); error = ec[0]; var codigo = new String (ec[1]);
				if (error!=0) alert ("¡"+error+"!");
				else {
					var registro=codigo+"-"+organismo;
					var pagina=document.getElementById("frmentrada").action;
					if (accion=="GUARDAR") {
						alert("¡LOS DATOS SE REGISTRARON EXITOSAMENTE!");
						cargarPagina(form, "requerimientos_editar.php?registro="+registro+"&tab1=1&tab2=1&tab3=0&tab4=0&tab5=0");
					} else cargarPagina(form, pagina+"?limit=0");
				}
			}
		}
	}
	return false;
}
function setVigenciaContrato(tipo) {
	var ddesde = document.getElementById("ddesde");
	var dhasta = document.getElementById("dhasta");
	if (tipo=="") { ddesde.disabled=true; dhasta.disabled=true; ddesde.value=""; dhasta.value=""; }
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion=VIGENCIA-CONTRATO&tipo="+tipo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText.split(":");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					if (resp[1]=="S") { ddesde.disabled=false; dhasta.disabled=true; ddesde.value=""; dhasta.value=""; }
					else { ddesde.disabled=false; dhasta.disabled=false; ddesde.value=""; dhasta.value=""; }
					
				}
			}
		}
	}
}
function filtroRequerimientos(form, limit, pagina) {
	var desde=new String(form.ffingresod.value); desde=desde.trim();
	var hasta=new String(form.ffingresoh.value); hasta=hasta.trim();
	var filtro="r.Requerimiento<>**";
	if (form.chkorganismo.checked) filtro+=" AND r.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkdependencia.checked) filtro+=" AND r.CodDependencia LIKE *;"+form.fdependencia.value+";*";
	if (form.chkempleado.checked) filtro+=" AND r.Requerimiento LIKE *"+form.fempleado.value+"*";
	if (form.chkcargo.checked) filtro+=" AND r.CodCargo LIKE *"+form.fcargo.value+"*";
	if (form.chkstatus.checked) { 
		if (form.fstatus.value=="A+E") filtro+=" AND (r.Estado = *A* OR r.Estado = *E* )";
		else filtro+=" AND r.Estado = *"+form.fstatus.value+"*";
	}
	if (form.chkfingreso.checked) {
		var esFIngD=esFecha(form.ffingresod.value);
		var esFIngH=esFecha(form.ffingresoh.value);
		var esFDH=esVFecha(form.ffingresod.value, form.ffingresoh.value);
		var fechad = new String (form.ffingresod.value); fechad=fechad.trim();
		var fechah = new String (form.ffingresoh.value); fechah=fechah.trim();		
		var dmad = fechad.split("-");
		if (dmad[0].length!=2 || dmad[1].length!=2 || dmad[2].length!=4) var dmad = fechad.split("/");		
		var dmah = fechah.split("-");
		if (dmah[0].length!=2 || dmah[1].length!=2 || dmah[2].length!=4) var dmah = fechah.split("/");		
		filtro+=" AND (r.FechaSolicitud>=*"+dmad[2]+"-"+dmad[1]+"-"+dmad[0]+"* AND r.FechaSolicitud<=*"+dmah[2]+"-"+dmah[1]+"-"+dmah[0]+"*)";
	} else { esFIngD=true; esFIngH=true; esFDH=true; }
	if (esFIngD && esFIngH && esFDH) {
		document.getElementById("filtro").value=filtro;
		document.getElementById("limit").value=0;
		cargarPagina(form, pagina);
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1380);
	}
}
function verificarRequerimientoEvaluacion(form, requerimiento) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;
	var evaluacion = new String (form.evaluacion.value); evaluacion=evaluacion.trim(); form.evaluacion.value=evaluacion;
	var etapa = new String (form.etapa.value); etapa=etapa.trim(); form.etapa.value=etapa;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (evaluacion=="" || etapa=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion=EVALUACION&requerimiento="+requerimiento+"&organismo="+organismo+"&evaluacion="+evaluacion+"&etapa="+etapa);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else cargarPagina(form, "requerimientos_evaluacion.php");
			}
		}
	}
	return false;
}
function aprobarRequerimiento(form, registro) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion=APROBAR&registro="+registro);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) { alert ("¡"+error+"!"); return false; }
			else {
				cargarPagina(form, "requerimientos_aprobar.php?limit=0");
			}
		}
	}
	return false;
}
function mClkPostulante(src, registro) {
	var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	var row=document.getElementById(src.id);
	row.className="trListaBodySel";
	//	------------------
	var registro=document.getElementById(registro);
	registro.value=src.id;
	//	------------------
	var postulante=document.getElementById("det").value;
	var organismo=document.getElementById("organismo").value;
	var requerimiento=document.getElementById("registro").value;
	var cargo=document.getElementById("cargo").value;
	var form=document.getElementById("frmentrada");
	form.action="requerimientos_resultados.php?postulante="+postulante+"&organismo="+organismo+"&registro="+requerimiento+"&cargo="+cargo;
	form.target="iResultados";
	form.submit();
	form.action="requerimientos_competencias.php";
	form.target="iCompetencias";
	form.submit();
	form.action="requerimientos_grafico.php";
	form.target="iGrafico";
	form.submit();
}
function mClkCompetencias(src, registro) {
	var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	var row=document.getElementById(src.id);
	row.className="trListaBodySel";
	//	------------------
	var registro=document.getElementById(registro);
	registro.value=src.id;
	//	------------------
	var postulante=document.getElementById("postulante").value;
	var organismo=document.getElementById("organismo").value;
	var requerimiento=document.getElementById("registro").value;
	var evaluacion=document.getElementById("det").value;
	var cargo=document.getElementById("cargo").value;
	var form=document.getElementById("frmentrada");
	form.action="requerimientos_competencias.php?postulante="+postulante+"&organismo="+organismo+"&registro="+requerimiento+"&evaluacion="+evaluacion+"&cargo="+cargo;
	form.target="iCompetencias";
	form.submit();
	form.action="requerimientos_grafico.php?postulante="+postulante+"&organismo="+organismo+"&registro="+requerimiento+"&evaluacion="+evaluacion+"&cargo="+cargo;
	form.target="iGrafico";
	form.submit();
}
function setRequerimientoEval(cargo, requerimiento, organismo) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion=EVALREQUERIMIENTO&requerimiento="+requerimiento+"&cargo="+cargo+"&organismo="+organismo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else {
				document.getElementById('iEvaluacion').src="requerimientos_evaluacion.php?registro="+requerimiento+"&organismo="+organismo;
				document.getElementById('iPerfil').src="requerimientos_perfil.php?cargo="+cargo;
			}
		}
	}
	return false;
}
function verificarTerminarRequerimiento(form) {
	var organismo = document.getElementById("organismo").value;
	var requerimiento = document.getElementById("codigo").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion=TERMINAR-REQUERIMIENTO&organismo="+organismo+"&requerimiento="+requerimiento);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else {
				cargarPagina(form, "requerimientos_lista_terminar.php?limit=0");
			}
		}
	}
	return false;
}

//	CAPACITACIONES
function verificarCapacitacion(form, accion) {
	var filtro = new String (form.filtro.value); filtro=filtro.trim(); form.filtro.value=filtro;
	var regresar = new String (form.regresar.value); regresar=regresar.trim(); form.regresar.value=regresar;
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;
	var codempleado = new String (form.codempleado.value); codempleado=codempleado.trim(); form.codempleado.value=codempleado;
	var status = new String (form.status.value); status=status.trim(); form.status.value=status;
	var codcurso = new String (form.codcurso.value); codcurso=codcurso.trim(); form.codcurso.value=codcurso;
	var tcurso = new String (form.tcurso.value); tcurso=tcurso.trim(); form.tcurso.value=tcurso;
	var codcentro = new String (form.codcentro.value); codcentro=codcentro.trim(); form.codcentro.value=codcentro;
	var ciudad = new String (form.ciudad.value); ciudad=ciudad.trim(); form.ciudad.value=ciudad;
	var tel = new String (form.tel.value); tel=tel.trim(); form.tel.value=tel;
	var aula = new String (form.aula.value); aula=aula.trim(); form.aula.value=aula;
	var expositor = new String (form.expositor.value); expositor=expositor.trim(); form.expositor.value=expositor;
	var tcapacitacion = new String (form.tcapacitacion.value); tcapacitacion=tcapacitacion.trim(); form.tcapacitacion.value=tcapacitacion;
	var modalidad = new String (form.modalidad.value); modalidad=modalidad.trim(); form.modalidad.value=modalidad;
	var vacantes = new String (form.vacantes.value); vacantes=vacantes.trim(); form.vacantes.value=vacantes;
	var participantes = new String (form.participantes.value); participantes=participantes.trim(); form.participantes.value=participantes;
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim(); form.fdesde.value=fdesde;
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim(); form.fhasta.value=fhasta;
	var costo = new String (form.costo.value); costo=costo.trim(); form.costo.value=costo; costo=costo.replace(",", ".");
	var asumido = new String (form.asumido.value); asumido=asumido.trim(); form.asumido.value=asumido; asumido=asumido.replace(",", ".");
	var utilizado = new String (form.utilizado.value); utilizado=utilizado.trim(); form.utilizado.value=utilizado; utilizado=utilizado.replace(",", ".");
	var saldo = new String (form.saldo.value); saldo=saldo.trim(); form.saldo.value=saldo;
	var funda1 = new String (form.funda1.value); funda1=funda1.trim(); form.funda1.value=funda1;
	var funda2 = new String (form.funda2.value); funda2=funda2.trim(); form.funda2.value=funda2;
	var funda3 = new String (form.funda3.value); funda3=funda3.trim(); form.funda3.value=funda3;
	var funda4 = new String (form.funda4.value); funda4=funda4.trim(); form.funda4.value=funda4;
	var funda5 = new String (form.funda5.value); funda5=funda5.trim(); form.funda5.value=funda5;
	var funda6 = new String (form.funda6.value); funda6=funda6.trim(); form.funda6.value=funda6;
	
	var esFechaCorrecta=esVFecha(fdesde, fhasta);
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo=="" || codempleado=="" || codcentro=="" || codcurso=="" || ciudad=="" || tcapacitacion=="" || modalidad=="" || vacantes=="") msjError(1010);
	else if (isNaN(vacantes)) msjError(1430);
	else if (!esFechaCorrecta) msjError(1390);
	else if (costo!="" && isNaN(costo)) msjError(1400);
	else if (asumido!="" && isNaN(asumido)) msjError(1410);
	else if (utilizado!="" && isNaN(utilizado)) msjError(1420);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CAPACITACION&accion="+accion+"&codigo="+codigo+"&status="+status+"&fdesde="+fdesde+"&fhasta="+fhasta+"&organismo="+organismo+"&codempleado="+codempleado+"&status="+status+"&codcurso="+codcurso+"&codcentro="+codcentro+"&ciudad="+ciudad+"&tel="+tel+"&aula="+aula+"&expositor="+expositor+"&tcapacitacion="+tcapacitacion+"&modalidad="+modalidad+"&vacantes="+vacantes+"&participantes="+participantes+"&funda1="+funda1+"&funda2="+funda2+"&funda3="+funda3+"&funda4="+funda4+"&funda5="+funda5+"&funda6="+funda6+"&tcurso="+tcurso+"&costo="+costo+"&asumido="+asumido+"&utilizado="+utilizado+"&saldo="+saldo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				var ec = error.split(":"); error = ec[0]; var codigo = new String (ec[1]);
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					var registro=codigo+"-"+organismo;
					if (accion=="GUARDAR") {
						alert("¡LOS DATOS SE GUARDARON EXITOSAMENTE!");
						cargarPagina(form, "capacitacion_editar.php?registro="+registro+"&tab1=1&tab2=1&tab3=1&tab4=0&tab5=0&tab6=0&tab7=0&regresar=capacitacion_lista.php");
					} else cargarPagina(form, pagina+"?limit=0");
				}
			}
		}
	}
	return false;		
}
function filtroCapacitaciones(form, limit, pagina) {
	var filtro="r.Capacitacion<>**";
	if (form.chkorganismo.checked) filtro+=" AND r.CodOrganismo LIKE *;"+form.forganismo.value+";*";
	if (form.chkcapacitacion.checked) filtro+=" AND r.Capacitacion LIKE *"+form.fcapacitacion.value+"*";
	if (form.chkcursos.checked) filtro+=" AND ru.CodCurso LIKE *"+form.fcursos.value+"*";
	if (form.chktcursos.checked) filtro+=" AND r.TipoCurso LIKE *"+form.ftcursos.value+"*";
	if (form.chkstatus.checked) filtro+=" AND r.Estado = *"+form.fstatus.value+"*";
	
	document.getElementById("filtro").value=filtro;
	document.getElementById("limit").value=0;
	cargarPagina(form, pagina);
}

//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selParticipante(capacitacion, organismo, codpersona, dependencia, division) {
	var form = document.getElementById("frmlista");
	form.action="capacitacion_participantes.php?accion=AGREGAR&capacitacion="+capacitacion+"&organismo="+organismo+"&codpersona="+codpersona+"&dependencia="+dependencia+"&division="+division;
	form.target="iParticipante";
	form.submit();
	form.target="";
	window.close();
}
function aprobarCapacitacion(form) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=CAPACITACION&accion=APROBAR&capacitacion="+codigo+"&organismo="+organismo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else {
				var pagina=document.getElementById("frmentrada").action;
				cargarPagina(form, pagina+"?limit=0");
			}
		}
	}
	return false;
}
function verificarCapacitacionHorarios(form, accion) {
	var sec = new String (form.sec.value); sec=sec.trim(); form.sec.value=sec;	
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;	
	var capacitacion = new String (form.capacitacion.value); capacitacion=capacitacion.trim(); form.capacitacion.value=capacitacion;	
	var status = new String (form.status.value); status=status.trim(); form.status.value=status;
	var fdesde = new String (form.fdesde.value); fdesde=fdesde.trim(); form.fdesde.value=fdesde;
	var fhasta = new String (form.fhasta.value); fhasta=fhasta.trim(); form.fhasta.value=fhasta;
	var dlunes = new String (form.dlunes.value); dlunes=dlunes.trim(); form.dlunes.value=dlunes;	
	var hlunes = new String (form.hlunes.value); hlunes=hlunes.trim(); form.hlunes.value=hlunes;	
	var dmartes = new String (form.dmartes.value); dmartes=dmartes.trim(); form.dmartes.value=dmartes;	
	var hmartes = new String (form.hmartes.value); hmartes=hmartes.trim(); form.hmartes.value=hmartes;	
	var dmiercoles = new String (form.dmiercoles.value); dmiercoles=dmiercoles.trim(); form.dmiercoles.value=dmiercoles;	
	var hmiercoles = new String (form.hmiercoles.value); hmiercoles=hmiercoles.trim(); form.hmiercoles.value=hmiercoles;	
	var djueves = new String (form.djueves.value); djueves=djueves.trim(); form.djueves.value=djueves;	
	var hjueves = new String (form.hjueves.value); hjueves=hjueves.trim(); form.hjueves.value=hjueves;	
	var dviernes = new String (form.dviernes.value); dviernes=dviernes.trim(); form.dviernes.value=dviernes;	
	var hviernes = new String (form.hviernes.value); hviernes=hviernes.trim(); form.hviernes.value=hviernes;	
	var dsabado = new String (form.dsabado.value); dsabado=dsabado.trim(); form.dsabado.value=dsabado;	
	var hsabado = new String (form.hsabado.value); hsabado=hsabado.trim(); form.hsabado.value=hsabado;	
	var ddomingo = new String (form.ddomingo.value); ddomingo=ddomingo.trim(); form.ddomingo.value=ddomingo;	
	var hdomingo = new String (form.hdomingo.value); hdomingo=hdomingo.trim(); form.hdomingo.value=hdomingo;
	if (form.flunes.checked) var flunes="S"; else var flunes="N";
	if (form.fmartes.checked) var fmartes="S"; else var fmartes="N";
	if (form.fmiercoles.checked) var fmiercoles="S"; else var fmiercoles="N";
	if (form.fjueves.checked) var fjueves="S"; else var fjueves="N";
	if (form.fviernes.checked) var fviernes="S"; else var fviernes="N";
	if (form.fsabado.checked) var fsabado="S"; else var fsabado="N";
	if (form.fdomingo.checked) var fdomingo="S"; else var fdomingo="N";
	var esFechaCorrecta=esVFecha(fdesde, fhasta);
	if ((flunes=="S" && (dlunes=="" || hlunes=="")) || (fmartes=="S" && (dmartes=="" || hmartes=="")) || (fmiercoles=="S" && (dmiercoles=="" || hmiercoles=="")) || (fjueves=="S" && (djueves=="" || hjueves=="")) || (fviernes=="S" && (dviernes=="" || hviernes=="")) || (fsabado=="S" && (dsabado=="" || hsabado=="")) || (fdomingo=="S" && (ddomingo=="" || hdomingo==""))) msjError(1450);
	else if (flunes=="N" && fmartes=="N" && fmiercoles=="N" && fjueves=="N" && fviernes=="N" && fsabado=="N" && fdomingo=="N") msjError(1460);
	else if (!esFechaCorrecta || fdesde=="" || fhasta=="") msjError(1440);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CAPACITACION&accion=HORARIOS&capacitacion="+capacitacion+"&organismo="+organismo+"&flunes="+flunes+"&fmartes="+fmartes+"&fmiercoles="+fmiercoles+"&fjueves="+fjueves+"&fviernes="+fviernes+"&fsabado="+fsabado+"&fdomingo="+fdomingo+"&dlunes="+dlunes+"&dmartes="+dmartes+"&dmiercoles="+dmiercoles+"&djueves="+djueves+"&dviernes="+dviernes+"&dsabado="+dsabado+"&ddomingo="+ddomingo+"&hlunes="+hlunes+"&hmartes="+hmartes+"&hmiercoles="+hmiercoles+"&hjueves="+hjueves+"&hviernes="+hviernes+"&hsabado="+hsabado+"&hdomingo="+hdomingo+"&fdesde="+fdesde+"&fhasta="+fhasta+"&status="+status+"&sec="+sec);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function verificarCapacitacionGastos(form, accion) {
	var sec = new String (form.sec.value); sec=sec.trim(); form.sec.value=sec;
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;
	var capacitacion = new String (form.capacitacion.value); capacitacion=capacitacion.trim(); form.capacitacion.value=capacitacion;
	var fecha = new String (form.fecha.value); fecha=fecha.trim(); form.fecha.value=fecha;
	var subtotal = new String (form.subtotal.value); subtotal=subtotal.trim(); form.subtotal.value=subtotal;
	var impuesto = new String (form.impuesto.value); impuesto=impuesto.trim(); form.impuesto.value=impuesto;
	var total = new String (form.total.value); total=total.trim(); form.total.value=total;
	var numero = new String (form.numero.value); numero=numero.trim(); form.numero.value=numero;
	var esFechaCorrecta=esFecha(fecha);
	if (numero=="" || fecha=="" || subtotal=="" || impuesto=="") msjError(1010);
	else if (isNaN(subtotal) || isNaN(impuesto)) msjError(1470);
	else if (!esFechaCorrecta) msjError(1260);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CAPACITACION&accion=GASTOS&capacitacion="+capacitacion+"&organismo="+organismo+"&sec="+sec+"&fecha="+fecha+"&subtotal="+subtotal+"&impuesto="+impuesto+"&total="+total+"&numero="+numero);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function setHorasCapacitacion(check, desde, hasta) {	
	if (document.getElementById(check).checked) {
		document.getElementById(desde).disabled=false;
		document.getElementById(hasta).disabled=false;
		document.getElementById(desde).value="";
		document.getElementById(hasta).value="";
	} else {
		document.getElementById(desde).disabled=true;
		document.getElementById(hasta).disabled=true;
		document.getElementById(desde).value="";
		document.getElementById(hasta).value="";
	}
}
function optCapacitacionHorario(form, accion) {
	var secuencia = form.sec.value;
	var organismo = form.organismo.value;
	var capacitacion = form.capacitacion.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) {
				var pagina=document.getElementById("frmentrada").action;
				cargarPagina(form, pagina+"?accion=ELIMINAR");
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CAPACITACION&accion=HORARIOS_EDITAR&capacitacion="+capacitacion+"&organismo="+organismo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+error+"!");
					else {
						var fdesde = resp[2].split("-");
						var fhasta = resp[3].split("-");
						document.getElementById('status').value=resp[1];
						document.getElementById('fdesde').value=fdesde[2]+"-"+fdesde[1]+"-"+fdesde[0];
						document.getElementById('fhasta').value=fhasta[2]+"-"+fhasta[1]+"-"+fhasta[0];
						document.getElementById('dias').value=resp[25];
						document.getElementById('horas').value=resp[26];
						if (resp[4]=="S") {
							document.getElementById('flunes').checked=true;
							document.getElementById('dlunes').value=resp[5];
							document.getElementById('hlunes').value=resp[6];
							document.getElementById('dlunes').disabled=false;
							document.getElementById('hlunes').disabled=false;
						} else {
							document.getElementById('flunes').checked=false;
							document.getElementById('dlunes').value="";
							document.getElementById('hlunes').value="";
							document.getElementById('dlunes').disabled=true;
							document.getElementById('hlunes').disabled=true;
						}
						if (resp[7]=="S") {
							document.getElementById('fmartes').checked=true;
							document.getElementById('dmartes').value=resp[8];
							document.getElementById('hmartes').value=resp[9];
							document.getElementById('dmartes').disabled=false;
							document.getElementById('hmartes').disabled=false;
						} else {
							document.getElementById('fmartes').checked=false;
							document.getElementById('dmartes').value="";
							document.getElementById('hmartes').value="";
							document.getElementById('dmartes').disabled=true;
							document.getElementById('hmartes').disabled=true;
						}
						if (resp[10]=="S") {
							document.getElementById('fmiercoles').checked=true;
							document.getElementById('dmiercoles').value=resp[11];
							document.getElementById('hmiercoles').value=resp[12];
							document.getElementById('dmiercoles').disabled=false;
							document.getElementById('hmiercoles').disabled=false;
						} else {
							document.getElementById('fmiercoles').checked=false;
							document.getElementById('dmiercoles').value="";
							document.getElementById('hmiercoles').value="";
							document.getElementById('dmiercoles').disabled=true;
							document.getElementById('hmiercoles').disabled=true;
						}
						if (resp[13]=="S") {
							document.getElementById('fjueves').checked=true;
							document.getElementById('djueves').value=resp[14];
							document.getElementById('hjueves').value=resp[15];
							document.getElementById('djueves').disabled=false;
							document.getElementById('hjueves').disabled=false;
						} else {
							document.getElementById('fjueves').checked=false;
							document.getElementById('djueves').value="";
							document.getElementById('hjueves').value="";
							document.getElementById('djueves').disabled=true;
							document.getElementById('hjueves').disabled=true;
						}
						if (resp[16]=="S") {
							document.getElementById('fviernes').checked=true;
							document.getElementById('dviernes').value=resp[17];
							document.getElementById('hviernes').value=resp[18];
							document.getElementById('dviernes').disabled=false;
							document.getElementById('hviernes').disabled=false;
						} else {
							document.getElementById('fviernes').checked=false;
							document.getElementById('dviernes').value="";
							document.getElementById('hviernes').value="";
							document.getElementById('dviernes').disabled=true;
							document.getElementById('hviernes').disabled=true;
						}
						if (resp[19]=="S") {
							document.getElementById('fsabado').checked=true;
							document.getElementById('dsabado').value=resp[20];
							document.getElementById('hsabado').value=resp[21];
							document.getElementById('dsabado').disabled=false;
							document.getElementById('hsabado').disabled=false;
						} else {
							document.getElementById('fsabado').checked=false;
							document.getElementById('dsabado').value="";
							document.getElementById('hsabado').value="";
							document.getElementById('dsabado').disabled=true;
							document.getElementById('hsabado').disabled=true;
						}
						if (resp[22]=="S") {
							document.getElementById('fdomingo').checked=true;
							document.getElementById('ddomingo').value=resp[23];
							document.getElementById('hdomingo').value=resp[24];
							document.getElementById('ddomingo').disabled=false;
							document.getElementById('hdomingo').disabled=false;
						} else {
							document.getElementById('fdomingo').checked=false;
							document.getElementById('ddomingo').value="";
							document.getElementById('hdomingo').value="";
							document.getElementById('ddomingo').disabled=true;
							document.getElementById('hdomingo').disabled=true;
						}
					}
				}
			}
		}
	}
}
//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalCapacitacionAprobar(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
	//
	if (rows) {
		var btAprobar=document.getElementById("btAprobar"); btAprobar.disabled=false;
	} else {
		var btAprobar=document.getElementById("btAprobar"); btAprobar.disabled=true;
	}
}
function limpiarCapacitacionHorario() {
	document.getElementById("sec").value="";
	document.getElementById("fdesde").value="";
	document.getElementById("fhasta").value="";
	document.getElementById("dlunes").value="";
	document.getElementById("hlunes").value="";
	document.getElementById("dmartes").value="";
	document.getElementById("hmartes").value="";
	document.getElementById("dmiercoles").value="";
	document.getElementById("hmiercoles").value="";
	document.getElementById("djueves").value="";
	document.getElementById("hjueves").value="";
	document.getElementById("dviernes").value="";
	document.getElementById("hviernes").value="";
	document.getElementById("dsabado").value="";
	document.getElementById("hsabado").value="";
	document.getElementById("ddomingo").value="";
	document.getElementById("hdomingo").value="";
	
	document.getElementById("dlunes").disabled=true;
	document.getElementById("hlunes").disabled=true;
	document.getElementById("dmartes").disabled=true;
	document.getElementById("hmartes").disabled=true;
	document.getElementById("dmiercoles").disabled=true;
	document.getElementById("hmiercoles").disabled=true;
	document.getElementById("djueves").disabled=true;
	document.getElementById("hjueves").disabled=true;
	document.getElementById("dviernes").disabled=true;
	document.getElementById("hviernes").disabled=true;
	document.getElementById("dsabado").disabled=true;
	document.getElementById("hsabado").disabled=true;
	document.getElementById("ddomingo").disabled=true;
	document.getElementById("hdomingo").disabled=true;
	
	document.getElementById("flunes").checked=false;
	document.getElementById("fmartes").checked=false;
	document.getElementById("fmiercoles").checked=false;
	document.getElementById("fjueves").checked=false;
	document.getElementById("fviernes").checked=false;
	document.getElementById("fsabado").checked=false;
	document.getElementById("fdomingo").checked=false;
	document.getElementById("status").value="A";	
	document.getElementById("dias").value="";
	document.getElementById("horas").value="";
}
function optCapacitacionGasto(form, accion) {
	var secuencia = form.sec.value;
	var organismo = form.organismo.value;
	var capacitacion = form.capacitacion.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¡Esta seguro de eliminar este registro?");
			if (eliminar) {
				var pagina=document.getElementById("frmentrada").action;
				cargarPagina(form, pagina+"?accion=ELIMINAR");
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CAPACITACION&accion=GASTOS_EDITAR&capacitacion="+capacitacion+"&organismo="+organismo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						var fecha = resp[2].split("-");
						document.getElementById('numero').value=resp[1];
						document.getElementById('fecha').value=fecha[2]+"-"+fecha[1]+"-"+fecha[0];
						document.getElementById('subtotal').value=resp[3];
						document.getElementById('impuesto').value=resp[4];
						document.getElementById('total').value=resp[5];
					}
				}
			}
		}
	}
}
function limpiarCapacitacionGastos() {
	document.getElementById("sec").value="";
	document.getElementById("numero").value="";
	document.getElementById("subtotal").value="";
	document.getElementById("impuesto").value="";
	document.getElementById("total").value="";
	document.getElementById("fecha").value="";
}
function iniciarCapacitacion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var organismo = new String (form.organismo.value); organismo=organismo.trim(); form.organismo.value=organismo;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=CAPACITACION&accion=INICIAR&capacitacion="+codigo+"&organismo="+organismo+"&estado="+accion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var error=ajax.responseText;
			if (error!=0) alert ("¡"+error+"!");
			else {
				var pagina=document.getElementById("frmentrada").action;
				cargarPagina(form, pagina+"?limit=0");
			}
		}
	}
	return false;
}

//	MAESTRO DE EVALUACIONES
function verificarEvaluacion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var pmin = new String (form.pmin.value); pmin=pmin.trim(); form.pmin.value=pmin;
	var pmax = new String (form.pmax.value); pmax=pmax.trim(); form.pmax.value=pmax;
	var plantilla = new String (form.plantilla.value); plantilla=plantilla.trim(); form.plantilla.value=plantilla;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (descripcion=="") msjError(1010);
	else if (isNaN(pmin) || isNaN(pmax)) alert("¡PUNTAJE DEBE SER UN VALOR NUMERICO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONES&accion="+accion+"&codigo="+codigo+"&pmin="+pmin+"&pmax="+pmax+"&descripcion="+descripcion+"&plantilla="+plantilla+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}

//	TIPO DE EVALUACIONES
function verificarTEvaluacion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (codigo=="" || descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=TEVALUACIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}

//	GRUPOS DE COMPETENCIAS
function verificarAEvaluacion(form, accion) {
	var tipo = new String (form.tipo.value); tipo=tipo.trim(); form.tipo.value=tipo;
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (tipo=="" || codigo=="" || descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=AEVALUACIONES&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&tipo="+tipo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}

//	GRADO DE CALIFICACIONES
function verificarGCalificacion(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var pmin = new String (form.pmin.value); pmin=pmin.trim(); form.pmin.value=pmin;
	var pmax = new String (form.pmax.value); pmax=pmax.trim(); form.pmax.value=pmax;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (descripcion=="" || pmin=="" || pmax=="") msjError(1010);
	else if (isNaN(pmin) || isNaN(pmax)) alert("¡PUNTAJE DEBE SER UN VALOR NUMERICO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GCALIFICACIONES&accion="+accion+"&codigo="+codigo+"&pmin="+pmin+"&pmax="+pmax+"&descripcion="+descripcion+"&status="+status);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}

//	COLOREAR LOS TD DE LA TABLA DE PUNTAJE
function setRequerido(minimo, maximo, requerido) {
	for (i=minimo; i<=maximo; i++) {
		var td="R_"+i;
		document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=requerido; i++) {
		var td="R_"+i;
		document.getElementById(td).style.background="#000";	
	}
}

//	COLOREAR LOS TD DE LA TABLA DE PUNTAJE
function setMinimo(minimo, maximo, requerido) {
	for (i=minimo; i<=maximo; i++) {
		var td="M_"+i;
		document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=requerido; i++) {
		var td="M_"+i;
		document.getElementById(td).style.background="#990000";	
	}
}

//	COLOREAR LOS TD DE LA TABLA DE PUNTAJE
function setRequerido2(minimo, maximo, requerido, l) {
	for (i=minimo; i<=maximo; i++) {
		var td="R_"+i+"_"+l;
		document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=requerido; i++) {
		var td="R_"+i+"_"+l;
		document.getElementById(td).style.background="#000";	
	}
}

//	COLOREAR LOS TD DE LA TABLA DE PUNTAJE
function setMinimo2(minimo, maximo, requerido, l) {
	for (i=minimo; i<=maximo; i++) {
		var td="M_"+i+"_"+l;
		document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=requerido; i++) {
		var td="M_"+i+"_"+l;
		document.getElementById(td).style.background="#990000";	
	}
}

//	COLOREAR LOS TD DE LA TABLA DE PUNTAJE
function setPuntaje(minimo, maximo, requerido, l) {
	for (i=minimo; i<=maximo; i++) {
		var td="P_"+i+"_"+l;
		document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=requerido; i++) {
		var td="P_"+i+"_"+l;
		document.getElementById(td).style.background="#0099CC";	
	}
}

//	MAESTRO DE COMPETENCIAS
function verificarCompetencia(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var explicacion = new String (form.explicacion.value); explicacion=explicacion.trim(); form.explicacion.value=explicacion;
	var grupo = new String (form.grupo.value); grupo=grupo.trim(); form.grupo.value=grupo;
	var nivel = new String (form.nivel.value); nivel=nivel.trim(); form.nivel.value=nivel;
	var calificacion = new String (form.calificacion.value); calificacion=calificacion.trim(); form.calificacion.value=calificacion;
	var tipo = new String (form.tipo.value); tipo=tipo.trim(); form.tipo.value=tipo;
	var requerido = new String (form.requerido.value); requerido=requerido.trim(); form.requerido.value=requerido;
	var minimo = new String (form.minimo.value); minimo=minimo.trim(); form.minimo.value=minimo;
	if (form.fplantilla.checked) var fplantilla="S"; else var fplantilla="N";
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (descripcion=="" || grupo=="" || nivel=="" || calificacion=="" || tipo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=COMPETENCIAS&accion="+accion+"&codigo="+codigo+"&explicacion="+explicacion+"&grupo="+grupo+"&descripcion="+descripcion+"&status="+status+"&nivel="+nivel+"&calificacion="+calificacion+"&tipo="+tipo+"&fplantilla="+fplantilla+"&requerido="+requerido+"&minimo="+minimo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split(":");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina+"?accion="+accion+"&competencia="+resp[1]);
				}
			}
		}
	}
	return false;
}

//	PLANTILLA DE COMPETENCIAS
function verificarPCompetencia(form, accion) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var tipo = new String (form.tipo.value); tipo=tipo.trim(); form.tipo.value=tipo;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	if (form.ftipo[0].checked) var ftipo=form.ftipo[0].value; else var ftipo=form.ftipo[1].value;
	
	if (descripcion=="" || tipo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PCOMPETENCIAS&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&tipo="+tipo+"&ftipo="+ftipo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					if (accion=="GUARDAR") var pagina="pcompetencias_editar.php?registro="+resp[1];
					else var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function verificarPlantillaCompetencia(form) {
	var codcompetencia = new String (form.codcompetencia.value); codcompetencia=codcompetencia.trim(); form.codcompetencia.value=codcompetencia;
	var peso = new String (form.peso.value); peso=peso.trim(); form.peso.value=peso; peso=peso.replace(",", ".");
	var orden = new String (form.orden.value); orden=orden.trim(); form.orden.value=orden; orden=orden.replace(",", ".");
	var plantilla = new String (form.plantilla.value); plantilla=plantilla.trim(); form.plantilla.value=plantilla;
	var inserto = new String (form.inserto.value); inserto=inserto.trim(); form.inserto.value=inserto;
	if (form.potencial.checked) var potencial="S"; else var potencial="N";
	if (form.competencia.checked) var competencia="S"; else var competencia="N";
	if (form.conceptual.checked) var conceptual="S"; else var conceptual="N";
	
	if (codcompetencia=="" || peso=="" || orden=="") msjError(1010);
	else if (isNaN(peso)) alert ("¡EL PESO DEBE SER UN VALOR NUMERICO!");
	else if (isNaN(orden)) alert ("¡EL ORDEN FACTOR DEBE SER UN VALOR NUMERICO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PCOMPETENCIAS&accion=INSERTAR&codcompetencia="+codcompetencia+"&peso="+peso+"&orden="+orden+"&potencial="+potencial+"&competencia="+competencia+"&conceptual="+conceptual+"&plantilla="+plantilla+"&inserto="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="NUEVO";
					document.getElementById("btBrowse").disabled=false;
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina+"?accion=EDITAR");
				}
			}
		}
	}
	return false;
}
function optCompetencia(form, accion) {
	var plantilla=document.getElementById("plantilla").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=PCOMPETENCIAS&accion=INSERTAR&codcompetencia="+secuencia+"&plantilla="+plantilla+"&inserto=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="NUEVO";
							document.getElementById("btBrowse").disabled=false;
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina+"?accion=EDITAR");
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=PCOMPETENCIAS&accion=INSERTAR&codcompetencia="+secuencia+"&plantilla="+plantilla+"&inserto=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codcompetencia").value=resp[1];
						document.getElementById("nomcompetencia").value=resp[2];
						document.getElementById("peso").value=resp[3];
						document.getElementById("orden").value=resp[4];
						if (resp[5]=="S") document.getElementById("potencial").checked=true;
						if (resp[6]=="S") document.getElementById("competencia").checked=true;
						if (resp[7]=="S") document.getElementById("conceptual").checked=true;
						document.getElementById("btBrowse").disabled=true;
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}

//	VACACIONES DEL EMPLEADO
function verificarVacacionesUtilizacion(form) {
	var secuencia = new String (form.secuencia.value);
	var persona = new String (form.persona.value);
	var nro_periodo = new String (form.nro_periodo.value);
	var fingreso = new String (form.fingreso.value);
	var mes_programado = new String (form.mes_programado.value);
	var sub_accion = new String (form.sub_accion.value);
	var vac_pendientes = new String (form.vac_pendientes.value);
	var derecho = new String (form.derecho.value);
	var finicio = new String (form.finicio.value); finicio=finicio.trim();
	var ffin = new String (form.ffin.value); ffin=ffin.trim();
	var utilizacion = new String (form.utilizacion.value); utilizacion=utilizacion.trim();
	var dias = new String (form.dias.value); dias=dias.trim(); dias=dias.replace(",", "."); dias=parseInt(dias);
	var esFechaCorrecta=esVFecha(finicio, ffin);
	if (finicio=="" || ffin=="" || utilizacion=="" || dias=="" || dias==0) msjError(1010);
	else if (!esFechaCorrecta) alert("¡FECHAS INCORRECTAS!");
	else if (isNaN(dias)) alert("¡DEBE INGRESAR LOS DIAS DE UTILIZACION!");
	else if (utilizacion=="G" && dias>vac_pendientes) alert("¡LOS DIAS DE UTILIZACION "+dias+" NO PUEDE SER MAYOR A LOS DIAS DE DERECHO! "+vac_pendientes);
	else if (utilizacion=="I" && (dias>derecho-vac_pendientes)) alert("¡LOS DIAS DE INTERRUPCION NO PUEDE SER MAYOR A LOS DIAS GOZADOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=VACACIONES&accion=UTILIZACION&sub="+sub_accion+"&persona="+persona+"&nro_periodo="+nro_periodo+"&finicio="+finicio+"&ffin="+ffin+"&utilizacion="+utilizacion+"&fingreso="+fingreso+"&derecho="+derecho+"&mes_programado="+mes_programado+"&dias="+dias+"&vac_pendientes="+vac_pendientes+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("vac_pendientes").value=vac_pendientes-dias;
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function verificarVacacionesPagos(form) {
	var persona = new String (form.persona.value);
	var nro_periodo = new String (form.nro_periodo.value);
	var fingreso = new String (form.fingreso.value);
	var mes_programado = new String (form.mes_programado.value);
	var sub_accion = new String (form.sub_accion.value);
	var derecho = new String (form.derecho.value);
	var finicio = new String (form.finicio.value); finicio=finicio.trim();
	var ffin = new String (form.ffin.value); ffin=ffin.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var concepto = new String (form.concepto.value); concepto=concepto.trim();
	var dias = new String (form.dias.value); dias=dias.trim(); dias=dias.replace(",", "."); dias=parseInt(dias);
	var esFechaCorrecta=esVFecha(finicio, ffin);
	if (finicio=="" || ffin=="" || periodo=="" || dias=="" || dias==0) msjError(1010);
	else if (!esFechaCorrecta) alert("¡FECHAS INCORRECTAS!");
	else if (isNaN(dias)) alert("¡DEBE INGRESAR LOS DIAS DE PAGOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=VACACIONES&accion=PAGOS&sub="+sub_accion+"&persona="+persona+"&nro_periodo="+nro_periodo+"&finicio="+finicio+"&ffin="+ffin+"&periodo="+periodo+"&fingreso="+fingreso+"&derecho="+derecho+"&mes_programado="+mes_programado+"&dias="+dias+"&concepto="+concepto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function eliminarSubElemento(form, modulo, accion, subaccion, foraneos, codigo) {
	if (codigo=="") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo="+modulo+"&accion="+accion+"&sub="+subaccion+"&foraneos="+foraneos+"&codigo="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
}
function editarSubElemento(form) {
	var persona = form.persona.value;
	var nro_periodo = form.nro_periodo.value;
	var secuencia = form.elemento.value;
	
	if (secuencia == "") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=VACACIONES&accion=UTILIZACION&sub=EDITAR&persona="+persona+"&nro_periodo="+nro_periodo+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var partes = resp.split("|");
				if (partes[0] != "") alert (partes[0]);
				else {
					document.getElementById("secuencia").value = secuencia;
					document.getElementById("utilizacion").value = partes[1];
					document.getElementById("dias").value = partes[2];
					document.getElementById("finicio").value = partes[3];
					document.getElementById("ffin").value = partes[4];
					document.getElementById("sub_accion").value = "MODIFICAR";
					document.getElementById("utilizacion").disabled = true;
					document.getElementById("dias").disabled = true;
					document.getElementById("btBorrar").disabled = true;
					document.getElementById("btEditar").disabled = true;
				}
			}
		}
	}
}
function clkVacaciones(nro_periodo, persona, fingreso) {
	var derecho=document.getElementById("derecho_"+nro_periodo).value; derecho=parseInt(derecho);
	var mes_programado=document.getElementById("mes_programado_"+nro_periodo).value; mes_programado=parseInt(mes_programado);
	var form=document.getElementById("frmentrada");
	form.method="post";
	form.action="vacaciones_utilizacion.php?persona="+persona+"&nro_periodo="+nro_periodo+"&derecho="+derecho+"&fingreso="+fingreso+"&mes_programado="+mes_programado+"&disabled=";
	form.target="iUtilizacion";
	form.submit();
	
	form.action="vacaciones_pagos.php?persona="+persona+"&nro_periodo="+nro_periodo+"&derecho="+derecho+"&fingreso="+fingreso+"&mes_programado="+mes_programado+"&disabled=";
	form.target="iPagos";
	form.submit();
	
	form.action="vacaciones_periodo.php?persona="+persona+"&nro_periodo="+nro_periodo+"&derecho="+derecho+"&fingreso="+fingreso+"&mes_programado="+mes_programado;
	form.target=""
}
function getFechaFin(fecha, dias) {
	fecha=fecha.trim();
	dias=dias.trim();
	var esFechaCorrecta=esFecha(fecha);
	if (esFechaCorrecta && !isNaN(dias)) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_funciones.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("fecha="+fecha+"&dias="+dias+"&accion=FECHA_FIN");
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText;
				document.getElementById("ffin").value=resp;
			}
		}
	} else document.getElementById("ffin").value="";
}

//	USUARIOS
function verificarUsuario(form, accion) {
	var codempleado = new String (form.codempleado.value); codempleado=codempleado.trim();
	var usuario = new String (form.usuario.value); usuario=usuario.trim();
	var clave = new String (form.clave.value); clave=clave.trim();
	var confirmar = new String (form.confirmar.value); confirmar=confirmar.trim();
	var fexpira = new String (form.fexpira.value); fexpira=fexpira.trim();
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	if (form.flag.checked) var flag="S"; else var flag="N";
	var esFechaCorrecta=esFecha(fexpira);
	if (usuario=="" || clave=="" || codempleado=="") msjError(1010);
	else if (clave!=confirmar) alert("¡CONFIRMACION DE LA CONTRASEÑA INCORRECTA!");
	else if (flag=="S" && (fexpira=="" || !esFechaCorrecta)) alert("¡FECHA DE EXPIRACION INCORRECTA!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=USUARIOS&accion="+accion+"&codempleado="+codempleado+"&usuario="+usuario+"&clave="+clave+"&status="+status+"&flag="+flag+"&fexpira="+fexpira);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}

function eliminarDatos(form, modulo, foraneos, codigo) {
	if (codigo=="") msjError(1000);
	else {
		x=confirm("¿Esta seguro de eliminar este registro?");
		if (x) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo="+modulo+"&accion=ELIMINAR&foraneos="+foraneos+"&codigo="+codigo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp=ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else {
						var pagina=document.getElementById("frmentrada").action;
						cargarPagina(form, pagina);
					}
				}
			}
		}
	}
}

function forzarFechaExpira(activado) {
	if (activado) document.getElementById("fexpira").disabled=false;
	else document.getElementById("fexpira").disabled=true;
}

function clkPermisos(id){
	var concepto=document.getElementById(id);
	if (concepto.checked) concepto.checked=false; else concepto.checked=true;
	checkPermisos(id, concepto.checked);
}

function checkPermisos(id, check) {
	var nuevo="N_"+id;
	var modificar="M_"+id;
	var eliminar="E_"+id;
	if (document.getElementById(id).checked) {
		document.getElementById(nuevo).disabled=false;
		document.getElementById(modificar).disabled=false;
		document.getElementById(eliminar).disabled=false;
	} else {
		document.getElementById(nuevo).disabled=true;
		document.getElementById(modificar).disabled=true;
		document.getElementById(eliminar).disabled=true;
		document.getElementById(nuevo).checked=false;
		document.getElementById(modificar).checked=false;
		document.getElementById(eliminar).checked=false;
	}
}

function clkSetAdministrador(form, check) {
	if (check) {
		for(i=0; n=form.elements[i]; i++) {
			if (n.type=="checkbox" && i!=4) n.disabled=true;
			if (n.type=="checkbox" && i!=4) n.checked=false;
		}
	} else {
		for(i=0; n=form.elements[i]; i++) {
			if (n.type=="checkbox" && i%4==1) n.disabled=false;
		}
	}
}

function selTodos(form, sel) {
	if (sel) form.elements[4].checked=false;
	for(i=0; n=form.elements[i]; i++){
		if (sel) { if (n.type=="checkbox" && n.id!="admin") { n.disabled=false; n.checked=true; } }
		else { 
			if (n.type=="checkbox" && n.id!="admin") { 
				n.checked=false; 
				if (n.type=="checkbox" && i%4!=1) n.disabled=true;
			} 
		}
	}
}

// CONCEPTOS DE SEGURIDAD ALTERNO
function verificarSeguridadGrupo(form, accion) {
	var aplicacion = new String (form.aplicacion.value); aplicacion=aplicacion.trim(); form.aplicacion.value=aplicacion;
	var codigo = new String (form.codigo.value); codigo=codigo.trim(); form.codigo.value=codigo;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SEGURIDADGRUPO&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&status="+status+"&aplicacion="+aplicacion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					if (accion=="GUARDAR") location.href="seguridadgrupo_editar.php?filtro="+form.filtro.value+"&registro="+codigo;
					else location.href="seguridadgrupo.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
function verificarSeguridadGrupoConcepto(form) {
	var grupo = new String (form.grupo.value); grupo=grupo.trim(); form.grupo.value=grupo;
	var concepto = new String (form.concepto.value); concepto=concepto.trim(); form.concepto.value=concepto;
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim(); form.descripcion.value=descripcion;
	var status = new String (form.status.value); status=status.trim(); form.status.value=status;
	var inserto = new String (form.inserto.value); inserto=inserto.trim(); form.inserto.value=inserto;
	if (concepto=="" || descripcion=="" || status=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SEGURIDADGRUPO&accion=INSERTAR&grupo="+grupo+"&concepto="+concepto+"&descripcion="+descripcion+"&status="+status+"&inserto="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
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
function optSeguridadConcepto(form, accion) {
	var grupo=document.getElementById("grupo").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=SEGURIDADGRUPO&accion=INSERTAR&concepto="+secuencia+"&grupo="+grupo+"&inserto=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
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
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=SEGURIDADGRUPO&accion=INSERTAR&concepto="+secuencia+"&grupo="+grupo+"&inserto=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("concepto").value=resp[1];
						document.getElementById("descripcion").value=resp[2];
						document.getElementById("status").value=resp[3];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
						document.getElementById("concepto").disabled=true;
					}
				}
			}
		}
	}
}

function seleccionarTodos(form, sel) {
	for(i=0; n=form.elements[i]; i++){ 
		if (n.type=="checkbox" && n.id!="admin") {
			if (n.type=="checkbox") n.checked=sel;
		} 
	}
}

function clkPermisosAlterno(id){
	var concepto=document.getElementById(id);
	if (concepto.checked) concepto.checked=false; else concepto.checked=true;
}

//	FUNCION PARA BLOQUEAR EL BOTON DE GUARDAR, APROBAR, ETC DE LOS FORMULARIOS SEGUN LOS PERMISOS DEL USUARIO
function permisoBoton(bt, admin, insert, update, del) {
	var button = document.getElementById(bt);
	if (insert=="N") button.disabled=true;
}

//	FUNCION PARA ELIMINAR UNA COMPETENCIA DEL CARGO
function optCargoCompetencia(form, accion) {
	var codcargo=document.getElementById("codcargo").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		var eliminar=confirm("¿Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOCOMPETENCIA&competencia="+secuencia+"&codcargo="+codcargo);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else {
						var pagina=document.getElementById("frmentrada").action;
						cargarPagina(form, pagina);
					}
				}
			}	
		}
	}
}

//	CARGOS (EVALUACION)
function verificarCargoEvaluacion(form) {
	var etapa = new String (form.etapa.value); etapa=etapa.trim();
	var evaluacion = new String (form.evaluacion.value); evaluacion=evaluacion.trim();
	var factor = new String (form.factor.value); factor=factor.trim();
	var status = new String (form.status.value); status=status.trim();
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	if (etapa=="" || evaluacion=="" || factor=="" || status=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOEVALUACION&etapa="+etapa+"&evaluacion="+evaluacion+"&factor="+factor+"&status="+status+"&inserto="+inserto+"&codcargo="+codcargo+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="NUEVO";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optCargoEvaluacion(form, accion) {
	var codcargo=document.getElementById("codcargo").value;
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=CARGOS&accion=CARGOEVALUACION&secuencia="+secuencia+"&codcargo="+codcargo+"&inserto=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
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
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOEVALUACION&secuencia="+secuencia+"&codcargo="+codcargo+"&inserto=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("etapa").value=resp[1];
						document.getElementById("evaluacion").value=resp[2];
						document.getElementById("factor").value=resp[3];
						document.getElementById("status").value=resp[4];
						document.getElementById("secuencia").value=resp[5];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
						document.getElementById("etapa").disabled=true;
					}
				}
			}
		}
	}
}

//	
function clkPuntajeCompetencia(form, id, value, competencia, rows) {
	for(i=1; i<=rows; i++) {
		var check=i+":"+competencia;
		var n = document.getElementById(check);
		if (check!=id) n.checked=false;
	}
}

//
function guardarPuntajeCompetencias() {
	var checks = new String();
	var frmcompetencias = iCompetencias.document.getElementById("frmentrada");
	var frmcandidatos= iCandidatos.document.getElementById("frmentrada");
	var frmresultados = iResultados.document.getElementById("frmentrada");
	var frmgrafico = iGrafico.document.getElementById("frmentrada");
	
	var cargo = frmcompetencias.cargo.value;
	var evaluacion = frmcompetencias.evaluacion.value
	var registro = frmcompetencias.registro.value;
	var organismo = frmcompetencias.organismo.value;
	var postulante = frmcompetencias.postulante.value;
	
	for(i=0; n=frmcompetencias.elements[i]; i++) 
		if (n.type=="checkbox" && n.checked==true) checks+=n.id+";";
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=REQUERIMIENTOS&accion=COMPETENCIAS&cargo="+cargo+"&evaluacion="+evaluacion+"&registro="+registro+"&organismo="+organismo+"&postulante="+postulante+"&checks="+checks);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			if (resp!=0) alert ("¡"+resp+"!");
			else {
				frmcandidatos.action="requerimientos_candidatos.php";
				frmcandidatos.target="iCandidatos";
				frmcandidatos.submit();
				frmresultados.action="requerimientos_resultados.php";
				frmresultados.target="iResultados";
				frmresultados.submit();
				frmgrafico.action="requerimientos_grafico.php";
				frmgrafico.target="iGrafico";
				frmgrafico.submit();
			}
		}
	}
}
function eliminarCandidatoEvaluacion(requerimiento, organismo) {
	var frmcandidatos= iCandidatos.document.getElementById("frmentrada");
	var frmresultados = iResultados.document.getElementById("frmentrada");
	var frmgrafico = iGrafico.document.getElementById("frmentrada");
	var postulante = iCandidatos.document.getElementById("det").value;
	
	if (postulante=="") alert("¡SELECCIONE UN CANDIDATO!");
	else {
		var eliminar=confirm("¿Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=REQUERIMIENTOS&accion=ELIMINAR-CANDIDATOS&requerimiento="+requerimiento+"&organismo="+organismo+"&postulante="+postulante);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp!=0) alert ("¡"+resp+"!");
					else {
						frmcandidatos.action="requerimientos_candidatos.php";
						frmcandidatos.target="iCandidatos";
						frmcandidatos.submit();
						frmresultados.action="requerimientos_resultados.php";
						frmresultados.target="iResultados";
						frmresultados.submit();
						frmgrafico.action="requerimientos_grafico.php";
						frmgrafico.target="iGrafico";
						frmgrafico.submit();
					}
				}
			}
		}
	}
}
function aprobarCandidatoEvaluacion(accion, requerimiento, organismo) {
	var frmcandidatos= iCandidatos.document.getElementById("frmentrada");
	var postulante = iCandidatos.document.getElementById("det").value;
	
	if (postulante=="") alert("¡SELECCIONE UN CANDIDATO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&requerimiento="+requerimiento+"&organismo="+organismo+"&postulante="+postulante);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					frmcandidatos.action="requerimientos_candidatos.php";
					frmcandidatos.target="iCandidatos";
					frmcandidatos.submit();
				}
			}
		}
	}
}
function contratarCandidatoEvaluacion(requerimiento, organismo, cargo) {
	var frmcandidatos= iCandidatos.document.getElementById("frmentrada");
	var postulante = iCandidatos.document.getElementById("det").value;
	
	if (postulante=="") alert("¡SELECCIONE UN CANDIDATO!");
	else {
		window.open("requerimientos_contratar_postulante.php?requerimiento="+requerimiento+"&organismo="+organismo+"&postulante="+postulante+"&cargo="+cargo, "wPrincipal", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=625, width=1000, left=0, top=0, resizable=yes");
	}
}
function verificarCandidatoEvaluacion(form, tipo, requerimiento, postulante, org) {
	var persona = new String (form.persona.value);
	var empleado = new String (form.empleado.value);
	var apellido1 = new String (form.apellido1.value); apellido1=apellido1.trim();
	var apellido2 = new String (form.apellido2.value); apellido2=apellido2.trim();
	var nombres = new String (form.nombres.value); nombres=nombres.trim();
	var busqueda = new String (form.busqueda.value); busqueda=busqueda.trim();
	var sexo = new String (form.sexo.value); sexo=sexo.trim();
	var ciudad1 = new String (form.ciudad1.value);
	var fnac = new String (form.fnac.value); fnac=fnac.trim();
	var lnac = new String (form.lnac.value);
	var dir = new String (form.dir.value); dir=dir.trim();
	var ciudad2 = new String (form.ciudad2.value);
	var tel1 = new String (form.tel1.value); tel1=tel1.trim();
	var tel2 = new String (form.tel2.value); tel2=tel2.trim();
	var tel3 = new String (form.tel3.value); tel3=tel3.trim();
	var tdoc = new String (form.tdoc.value);
	var ndoc = new String (form.ndoc.value); ndoc=ndoc.trim();
	var nac = new String (form.nac.value);
	var rif = new String (form.rif.value); rif=rif.trim();
	var email = new String (form.email.value); email=email.trim();
	var foto = new String (form.foto.value); foto=foto.trim();
	if (form.statusreg[0].checked) var statusreg=form.statusreg[0].value; else var statusreg=form.statusreg[1].value;
	
	var gsan = new String (form.gsan.value);
	var sitdom= new String (form.sitdom.value);
	var edocivil = new String (form.edocivil.value);
	var fedocivil = new String (form.fedocivil.value); fedocivil=fedocivil.trim();
	var nomcon1 = new String (form.nomcon1.value); nomcon1=nomcon1.trim();
	var nomcon2 = new String (form.nomcon2.value); nomcon2=nomcon2.trim();
	var dircon1 = new String (form.dircon1.value); dircon1=dircon1.trim();
	var dircon2 = new String (form.dircon2.value); dircon2=dircon2.trim();
	var telcon1 = new String (form.telcon1.value); telcon1=telcon1.trim();
	var telcon2 = new String (form.telcon2.value); telcon2=telcon2.trim();
	var celcon1 = new String (form.celcon1.value); celcon1=celcon1.trim();
	var celcon2 = new String (form.celcon2.value); celcon2=celcon2.trim();
	var parent1 = new String (form.parent1.value);
	var parent2 = new String (form.parent2.value);
	var tlic = new String (form.tlic.value);
	var nlic = new String (form.nlic.value); nlic=nlic.trim();
	var flic = new String (form.flic.value); flic=flic.trim();
	var auto = new String (form.auto.value);
	var obs = new String (form.obs.value); obs=obs.trim();
	
	var organismo = new String (form.organismo.value);
	var dependencia = new String (form.dependencia.value);
	var tnom = new String (form.tnom.value);
	var pnom = new String (form.pnom.value);
	var tpago = new String (form.tpago.value);
	
	var fingreso = new String (form.fingreso.value); fingreso=fingreso.trim();
	if (form.sittra[0].checked) var sittra=form.sittra[0].value; else var sittra=form.sittra[1].value;
	var tcese = new String (form.tcese.value);
	var fcese = new String (form.fcese.value); fcese=fcese.trim();
	var explicacion = new String (form.explicacion.value); explicacion=explicacion.trim();
	var grupo = new String (form.grupo.value);
	var serie = new String (form.serie.value);
	var cargo = new String (form.cargo.value);
	
	if (apellido2=="" || nombres=="" || busqueda=="" || ciudad1=="" || dir=="" || ciudad2=="" || tdoc=="" || ndoc=="" || nac=="" || edocivil=="" || nomcon1=="" || dircon1=="" || telcon1=="" || parent1=="" || organismo=="" || dependencia=="" || tnom=="" || pnom=="" || tpago=="" || cargo=="") msjError(1010);
	else if ((tcese=="" || fcese=="") && sittra=="I") msjError(1010);
	else if (!esFecha(fnac)) msjError(1080);
	else if (!esFecha(fedocivil)) msjError(1090);
	else if (!esFecha(flic) && flic!="") msjError(1100);
	else if (!esFecha(fingreso)) msjError(1110);
	else if (!esFecha(fcese) && sittra=="I") msjError(1120);
	else {
		if (tipo=="I") var accion = "CONTRATAR-EMPLEADOS";
		else if (tipo=="E") var accion = "CONTRATAR-CANDIDATOS"; 
		alert(accion);
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=REQUERIMIENTOS&accion="+accion+"&persona="+persona+"&empleado="+empleado+"&apellido1="+apellido1+"&apellido2="+apellido2+"&nombres="+nombres+"&busqueda="+busqueda+"&sexo="+sexo+"&ciudad1="+ciudad1+"&fnac="+fnac+"&lnac="+lnac+"&dir="+dir+"&ciudad2="+ciudad2+"&tel1="+tel1+"&tel2="+tel2+"&tel3="+tel3+"&tdoc="+tdoc+"&ndoc="+ndoc+"&nac="+nac+"&rif="+rif+"&email="+email+"&foto="+foto+"&statusreg="+statusreg+"&gsan="+gsan+"&sitdom="+sitdom+"&edocivil="+edocivil+"&fedocivil="+fedocivil+"&nomcon1="+nomcon1+"&nomcon2="+nomcon2+"&dircon1="+dircon1+"&dircon2="+dircon2+"&telcon1="+telcon1+"&telcon2="+telcon2+"&celcon1="+celcon1+"&celcon2="+celcon2+"&parent1="+parent1+"&parent2="+parent2+"&tlic="+tlic+"&nlic="+nlic+"&flic="+flic+"&auto="+auto+"&obs="+obs+"&organismo="+organismo+"&dependencia="+dependencia+"&tnom="+tnom+"&pnom="+pnom+"&tpago="+tpago+"&fingreso="+fingreso+"&sittra="+sittra+"&tcese="+tcese+"&fcese="+fcese+"&explicacion="+explicacion+"&grupo="+grupo+"&serie="+serie+"&cargo="+cargo+"&requerimiento="+requerimiento+"&postulante="+postulante+"&org="+org);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					var frmcandidato = opener.iCandidatos.document.getElementById("frmentrada"); 
					frmcandidato.action="requerimientos_candidatos.php";
					frmcandidato.target="iCandidatos";
					frmcandidato.submit();
					window.close();
				}
			}
		}
	}
	return false;
}

// PERIODO DE EVALUACION
function verificarEvaluacionDesempenio(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	var status = new String (form.status.value); status=status.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var anterior = new String (form.anterior.value); anterior=anterior.trim();
	var finicio = new String (form.finicio.value); finicio=finicio.trim();
	var ffin = new String (form.ffin.value); ffin=ffin.trim();
	var fcierre = new String (form.fcierre.value); fcierre=fcierre.trim();
	if (form.incidentes.checked) var incidentes="S"; else var incidentes="N";
	if (form.metas.checked) var metas="S"; else var metas="N";
	if (form.necesidad.checked) var necesidad="S"; else var necesidad="N";
	if (form.funciones.checked) var funciones="S"; else var funciones="N";
	if (form.revision.checked) var revision="S"; else var revision="N";
	if (form.fortaleza.checked) var fortaleza="S"; else var fortaleza="N";
	if (form.desempenio.checked) var desempenio="S"; else var desempenio="N";
	var esPC=esPContable(periodo);
	var esFV=esVFecha(finicio, ffin);
	var esFC=esFecha(fcierre); 
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (organismo=="" || descripcion=="" || tipo=="" || status=="" || periodo=="" || finicio=="" || ffin=="") msjError(1010);
	else if (!esFV) alert ("¡Las fechas de inicio y fin son incorrectas!");
	else if (!esPC) alert ("¡El periodo ingresado es incorrecto!");
	else if (!esFC) alert ("¡La fecha de cierre es incorrecta!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONPERIODO&accion="+accion+"&organismo="+organismo+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo="+tipo+"&status="+status+"&periodo="+periodo+"&anterior="+anterior+"&finicio="+finicio+"&ffin="+ffin+"&incidentes="+incidentes+"&metas="+metas+"&necesidad="+necesidad+"&funciones="+funciones+"&revision="+revision+"&fortaleza="+fortaleza+"&desempenio="+desempenio+"&fcierre="+fcierre);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp=ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					if (accion=="GUARDAR") {
						alert("¡LOS DATOS SE ALMACENARON EXITOSAMENTE!");
						location.href="evaluacion_periodo_editar.php?filtro="+form.filtro.value+"&registro="+resp[1];
					}
					else location.href="evaluacion_periodo.php?filtro="+form.filtro.value;
				}
			}
		}
	}
	return false;
}
function verificarEvaluacionDesempenioValor(form) {
	var rango = new String (form.rango.value); rango=rango.trim();
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var rango = new String (form.sec.value); rango=rango.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var explicacion = new String (form.explicacion.value); explicacion=explicacion.trim();
	var valor = new String (form.valor.value); valor=valor.trim(); valor=valor.replace(",", ".");
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	
	if (descripcion=="" || valor=="") msjError(1010);
	else if (isNaN(valor)) alert ("¡DEBE INGRESAR UN VALOR NUMERICO CORRECTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONPERIODO&accion=VALORES&rango="+rango+"&organismo="+organismo+"&sub="+inserto+"&descripcion="+descripcion+"&explicacion="+explicacion+"&valor="+valor+"&secuencia="+secuencia+"&periodo="+periodo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina+"?accion=EDITAR");
				}
			}
		}
	}
	return false;
}
function optEvaluacionDesempenio(form, accion) {
	var organismo=document.getElementById("organismo").value;
	var periodo=document.getElementById("periodo").value;
	var secuencia=document.getElementById("secuencia").value;
	var rango = form.sec.value;
	if (rango=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONPERIODO&accion=VALORES&rango="+rango+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina+"?accion=EDITAR");
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONPERIODO&accion=VALORES&rango="+rango+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("rango").value=rango;
						document.getElementById("descripcion").value=resp[1];
						document.getElementById("explicacion").value=resp[2];
						document.getElementById("valor").value=resp[3];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}

//------AQUI
function verificarEvaluacionFortalezas(form) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	
	if (descripcion=="" || tipo=="0") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FORTALEZAS&codigo="+codigo+"&organismo="+organismo+"&sub="+inserto+"&descripcion="+descripcion+"&persona="+persona+"&secuencia="+secuencia+"&periodo="+periodo+"&tipo="+tipo+"&evaluador="+evaluador);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optEvaluacionFortalezas(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var secuencia_desempenio = form.sec.value;
	if (secuencia_desempenio=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FORTALEZAS&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FORTALEZAS&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia_desempenio;

						document.getElementById("descripcion").value=resp[1];
						document.getElementById("tipo").value=resp[2];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarEvaluacionMetas(form) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var comentarios = new String (form.comentarios.value); comentarios=comentarios.trim();
	var calificacion = new String (form.calificacion.value); calificacion=calificacion.trim(); calificacion=calificacion.replace(",", ".");
	var peso = new String (form.peso.value); peso=peso.trim(); peso=peso.replace(",", ".");
	var periodo_metas = new String (form.periodo_metas.value); periodo_metas=periodo_metas.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	var esPC=esPContable(periodo_metas);
	
	if (descripcion=="" || periodo=="" || peso=="" || calificacion=="" || peso==0 || calificacion==0) msjError(1010);
	else if (!esPC) alert("¡PERIODO CONTABLE INCORRECTO!");
	else if (isNaN(calificacion)) alert("¡CALIFICACION INCORRECTA!");
	else if (isNaN(peso)) alert("¡PESO INCORRECTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=METAS&codigo="+codigo+"&organismo="+organismo+"&sub="+inserto+"&descripcion="+descripcion+"&persona="+persona+"&secuencia="+secuencia+"&periodo="+periodo+"&comentarios="+comentarios+"&evaluador="+evaluador+"&calificacion="+calificacion+"&peso="+peso+"&periodo_metas="+periodo_metas);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optEvaluacionMetas(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var secuencia_desempenio = form.sec.value;
	if (secuencia_desempenio=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONDESEMPENIO&accion=METAS&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion=METAS&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia_desempenio;
						document.getElementById("descripcion").value=resp[1];
						document.getElementById("periodo_metas").value=resp[2];
						document.getElementById("comentarios").value=resp[3];
						document.getElementById("calificacion").value=resp[4];
						document.getElementById("peso").value=resp[5];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarEvaluacionRevision(form) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var primera = new String (form.primera.value); primera=primera.trim();
	var segunda = new String (form.segunda.value); segunda=segunda.trim();
	var tercera = new String (form.tercera.value); tercera=tercera.trim();
	var porc1 = new String (form.porc1.value); porc1=porc1.trim(); porc1=porc1.replace(",", ".");
	var porc2 = new String (form.porc2.value); porc2=porc2.trim(); porc2=porc2.replace(",", ".");
	var porc3 = new String (form.porc3.value); porc3=porc3.trim(); porc3=porc3.replace(",", ".");
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	
	if (primera=="" || segunda=="" || tercera=="") msjError(1010);
	else if (isNaN(porc1) || isNaN(porc2) || isNaN(porc3)) alert("¡PORCENTAJE INCORRECTO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=REVISION&codigo="+codigo+"&organismo="+organismo+"&sub="+inserto+"&primera="+primera+"&persona="+persona+"&secuencia="+secuencia+"&periodo="+periodo+"&segunda="+segunda+"&evaluador="+evaluador+"&tercera="+tercera+"&porc1="+porc1+"&porc2="+porc2+"&porc3="+porc3);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optEvaluacionRevision(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var secuencia_desempenio = form.sec.value;
	if (secuencia_desempenio=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONDESEMPENIO&accion=REVISION&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion=REVISION&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia_desempenio;
						document.getElementById("primera").value=resp[1];
						document.getElementById("segunda").value=resp[2];
						document.getElementById("tercera").value=resp[3];
						document.getElementById("porc1").value=resp[4];
						document.getElementById("porc2").value=resp[5];
						document.getElementById("porc3").value=resp[6];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarEvaluacionNecesidad(form) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var necesidad = new String (form.necesidad.value); necesidad=necesidad.trim();
	var prioridad = new String (form.prioridad.value); prioridad=prioridad.trim();
	var codcurso = new String (form.codcurso.value); codcurso=codcurso.trim();
	var objetivo = new String (form.objetivo.value); objetivo=objetivo.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	
	if (necesidad=="" || prioridad=="" || codcurso=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=NECESIDAD&codigo="+codigo+"&organismo="+organismo+"&sub="+inserto+"&necesidad="+necesidad+"&persona="+persona+"&secuencia="+secuencia+"&periodo="+periodo+"&prioridad="+prioridad+"&evaluador="+evaluador+"&codcurso="+codcurso+"&objetivo="+objetivo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optEvaluacionNecesidad(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var secuencia_desempenio = form.sec.value;
	if (secuencia_desempenio=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONDESEMPENIO&accion=NECESIDAD&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion=NECESIDAD&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia_desempenio;
						document.getElementById("necesidad").value=resp[1];
						document.getElementById("prioridad").value=resp[2];
						document.getElementById("codcurso").value=resp[3];
						document.getElementById("nomcurso").value=resp[4];
						document.getElementById("objetivo").value=resp[5];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarEvaluacionFunciones(form) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var funcion = new String (form.funcion.value); funcion=funcion.trim();
	var calificacion = new String (form.calificacion.value); calificacion=calificacion.trim(); calificacion=calificacion.replace(",", ".");
	var peso = new String (form.peso.value); peso=peso.trim(); peso=peso.replace(",", ".");
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	
	if (funcion=="" || calificacion=="" || peso=="") msjError(1010);
	else if (isNaN(peso)) alert("¡DEBE INGRESAR UN VALOR NUMERICO PARA EL PESO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FUNCIONES&codigo="+codigo+"&organismo="+organismo+"&sub="+inserto+"&funcion="+funcion+"&persona="+persona+"&secuencia="+secuencia+"&periodo="+periodo+"&calificacion="+calificacion+"&evaluador="+evaluador+"&peso="+peso);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText.split("|:|");
				if (resp[0]!=0) alert ("¡"+resp[0]+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optEvaluacionFunciones(form, accion) {
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var evaluador = new String (form.evaluador.value); evaluador=evaluador.trim();
	var secuencia_desempenio = form.sec.value;
	if (secuencia_desempenio=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FUNCIONES&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=BORRAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText.split("|:|");
						if (resp[0]!=0) alert ("¡"+resp[0]+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion=FUNCIONES&secuencia_desempenio="+secuencia_desempenio+"&organismo="+organismo+"&sub=EDITAR&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia_desempenio;
						document.getElementById("funcion").value=resp[1];
						document.getElementById("calificacion").value=resp[2];
						document.getElementById("peso").value=resp[3];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}

//
function setCalificacionEvaluacionCompetencia() {
	var frmcompetencias = iCompetencia.document.getElementById("frmentrada"); frmcompetencias.submit();
	//	-------------------------
	var organismo = iCompetencia.document.getElementById("organismo").value;
	var periodo = iCompetencia.document.getElementById("periodo").value;
	var secuencia = iCompetencia.document.getElementById("secuencia").value;
	var persona = iCompetencia.document.getElementById("persona").value;
	var evaluador = iCompetencia.document.getElementById("evaluador").value;
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("modulo=EVALUACIONDESEMPENIO&accion=CALIFICACIONDESEMPENIO&organismo="+organismo+"&periodo="+periodo+"&secuencia="+secuencia+"&persona="+persona+"&evaluador="+evaluador);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.split(":");
			if (resp[0]!=0) alert ("¡"+resp[0]+"!");
			else document.getElementById("desempenio").value=resp[1];
		}
	}
	//	-------------------------
	var frmgrafico = iGrafico.document.getElementById("frmentrada"); frmgrafico.submit();
}
//------------------------------------------------------------//

//	CARGOS
function verificarCargosAmbiente(form) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var ambiente = new String (form.ambiente.value); ambiente=ambiente.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	if (ambiente=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOAMBIENTE&secuencia="+codigo+"&codcargo="+codcargo+"&ambiente="+ambiente+"&sub="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optCargosAmbiente(form, accion) {
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=CARGOS&accion=CARGOAMBIENTE&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOAMBIENTE&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia;
						document.getElementById("ambiente").value=resp[1];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarCargosEsfuerzo(form) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var esfuerzo = new String (form.esfuerzo.value); esfuerzo=esfuerzo.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	if (esfuerzo=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOESFUERZO&secuencia="+codigo+"&codcargo="+codcargo+"&esfuerzo="+esfuerzo+"&sub="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optCargosEsfuerzo(form, accion) {
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=CARGOS&accion=CARGOESFUERZO&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOESFUERZO&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia;
						document.getElementById("esfuerzo").value=resp[1];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}
function verificarCargosHabilidades(form) {
	var codigo = new String (form.codigo.value); codigo=codigo.trim();
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var tipo = new String (form.tipo.value); tipo=tipo.trim();
	var descripcion = new String (form.descripcion.value); descripcion=descripcion.trim();
	var inserto = new String (form.inserto.value); inserto=inserto.trim();
	if (tipo=="" || descripcion=="") msjError(1010);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=CARGOS&accion=CARGOHABILIDAD&secuencia="+codigo+"&codcargo="+codcargo+"&tipo="+tipo+"&descripcion="+descripcion+"&sub="+inserto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp!=0) alert ("¡"+resp+"!");
				else {
					document.getElementById("inserto").value="INSERTAR";
					var pagina=document.getElementById("frmentrada").action;
					cargarPagina(form, pagina);
				}
			}
		}
	}
	return false;
}
function optCargosHabilidades(form, accion) {
	var codcargo = new String (form.codcargo.value); codcargo=codcargo.trim();
	var secuencia = form.sec.value;
	if (secuencia=="") msjError(1000);
	else {
		if (accion=="ELIMINAR") {
			var eliminar=confirm("¿Esta seguro de eliminar este registro?");
			if (eliminar) {
				//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
				var ajax=nuevoAjax();
				ajax.open("POST", "fphp_ajax.php", true);
				ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				ajax.send("modulo=CARGOS&accion=CARGOHABILIDAD&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=BORRAR");
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4)	{
						var resp = ajax.responseText;
						if (resp!=0) alert ("¡"+resp+"!");
						else {
							document.getElementById("inserto").value="INSERTAR";
							document.getElementById("btEditar").disabled=true;
							document.getElementById("btBorrar").disabled=true;
							var pagina=document.getElementById("frmentrada").action;
							cargarPagina(form, pagina);
						}
					}
				}	
			}
		} else {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=CARGOS&accion=CARGOHABILIDAD&secuencia="+secuencia+"&codcargo="+codcargo+"&sub=EDITAR");
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						document.getElementById("inserto").value="ACTUALIZAR";
						document.getElementById("codigo").value=secuencia;
						document.getElementById("tipo").value=resp[1];
						document.getElementById("descripcion").value=resp[2];
						document.getElementById("btEditar").disabled=true;
						document.getElementById("btBorrar").disabled=true;
					}
				}
			}
		}
	}
}

//	CONTROL DE NIVELACIONES
function verificarNivelaciones(form, accion) {
	var categoria_ant = document.getElementById("categoria_ant").value;
	var categoria = document.getElementById("categoria").value;
	var codpersona = new String (form.registro.value); codpersona=codpersona.trim();
	var organismo = new String (form.organismo.value); organismo=organismo.trim();
	var dependencia = new String (form.dependencia.value); dependencia=dependencia.trim();
	var cargo = new String (form.cargo.value); cargo=cargo.trim();
	var nomina = new String (form.nomina.value); nomina=nomina.trim();
	var fecha = new String (form.fecha.value); fecha=fecha.trim(); var esF=esFecha(fecha);
	var fingreso = new String (form.fingreso.value);
	var tipo_accion = new String (form.tipo_accion.value); tipo_accion=tipo_accion.trim();
	var responsable = new String (form.responsable.value); responsable=responsable.trim();
	var documento = new String (form.documento.value); documento=documento.trim();
	var motivo = new String (form.motivo.value); motivo=motivo.trim();
	var sueldo_ant = new String (form.sueldo_ant.value); sueldo_ant=sueldo_ant.replace(".", ""); sueldo_ant=sueldo_ant.replace(",", ".");
	var sueldo = new String (form.sueldo.value); sueldo=sueldo.replace(".", ""); sueldo=sueldo.replace(",", ".");
	//if (form.status[0].checked) var status=form.status[0].value; else var status=form.status[1].value;
	
	if (fecha=="" || tipo_accion=="" || codpersona=="" || documento=="" || motivo=="") msjError(1010);
	else if (!esFecha) alert("¡FORMATO DE FECHA INCORRECTA!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=NIVELACIONES&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&cargo="+cargo+"&nomina="+nomina+"&fecha="+fecha+"&tipo_accion="+tipo_accion+"&responsable="+responsable+"&documento="+documento+"&motivo="+motivo+"&codpersona="+codpersona+"&sueldo_ant="+sueldo_ant+"&sueldo="+sueldo+"&fingreso="+fingreso+"&categoria="+categoria+"&categoria_ant="+categoria_ant);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					alert("¡LOS DATOS SE ALMACENARON EXITOSAMENTE!");
					form.submit();
				}
			}
		}
	}
	return false;
}
function mostrarCategoriaSueldo(codcargo) {
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=MOSTRAR-CATEGORIA-SUELDO&codcargo="+codcargo);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.split("|:|");
			if (resp[0]!=0) alert ("¡"+resp[0]+"!");
			else {
				document.getElementById("categoria").value=resp[1];
				document.getElementById("sueldo").value=resp[2];
			}
		}
	}
}

//	Antecedentes del Empleado
function verificarAntecedenteEmpleado(form, accion) {
	var codpersona = document.getElementById("registro").value;
	var secuencia = document.getElementById("secuencia").value;
	var organismo = document.getElementById("organismo").value; organismo=organismo.trim();
	var fingreso = document.getElementById("fingreso").value; fingreso=fingreso.trim(); var esFingreso = esFecha(fingreso);
	var fegreso = document.getElementById("fegreso").value; fegreso=fegreso.trim(); var esFegreso = esFecha(fegreso);
	var esIntervalo=esVFecha(fingreso, fegreso);

	if (organismo == "" || fingreso == "" || fegreso == "") msjError(1010);
	else if (!esFingreso || !esFegreso) alert("¡FORMATOS DE FECHA INCORRECTO!");
	else if (!esIntervalo) alert("¡FECHA DE INGRESO NO PUEDE SER MAYOR A LA FECHA EGRESO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADO-ANTECEDENTES&accion="+accion+"&organismo="+organismo+"&fingreso="+fingreso+"&fegreso="+fegreso+"&codpersona="+codpersona+"&secuencia="+secuencia);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var error=ajax.responseText;
				if (error!=0) alert ("¡"+error+"!");
				else {
					form.submit();
				}
			}
		}
	}
	return false;
}
function editarEmpleadoAntecedente(form) {
	var codpersona = document.getElementById("registro").value;
	var sec = document.getElementById("sec").value;
	
	if (sec == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADO-ANTECEDENTES&accion=EDITAR&secuencia="+sec+"&codpersona="+codpersona);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				var datos = resp.split("|:|");
	
				if (datos[0] != 0) alert ("¡"+datos[0]+"!");
				else {
					document.getElementById("accion").value = "ACTUALIZAR";
					document.getElementById("organismo").value = datos[1];
					document.getElementById("fingreso").value = datos[2];
					document.getElementById("fegreso").value = datos[3];
					document.getElementById("secuencia").value = datos[4];
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btEliminar").disabled = true;
					document.getElementById("btPDF").disabled = true;
				}
			}
		}
	}
}
function eliminarEmpleadoAntecedente(form) {
	var codpersona = document.getElementById("registro").value;
	var sec = document.getElementById("sec").value;
	
	if (sec == "") alert ("!DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar=confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EMPLEADO-ANTECEDENTES&accion=ELIMINAR&secuencia="+sec+"&codpersona="+codpersona);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp = ajax.responseText;
					if (resp != 0) alert ("¡"+resp+"!");
					else {
						form.submit();
					}
				}
			}
		}
	}
}
function limpiarFormEmpleadoAntecedente(form) {
	document.getElementById("organismo").value = "";
	document.getElementById("fingreso").value = "";
	document.getElementById("fegreso").value = "";
	document.getElementById("anios").value = "";
	document.getElementById("meses").value = "";
	document.getElementById("dias").value = "";
	document.getElementById("btEditar").disabled = false;
	document.getElementById("btEliminar").disabled = false;
	document.getElementById("btPDF").disabled = false;
	document.getElementById("accion").value = "GUARDAR";
}

//	RETENCIONES JUDICIALES
function verificarRetencionJudicial(form, accion) {
	var codretencion = document.getElementById("codretencion").value;
	var organismo = document.getElementById("organismo").value;
	var fresolucion = document.getElementById("fresolucion").value; fresolucion = fresolucion.trim();
	var expediente = document.getElementById("expediente").value; expediente = expediente.trim();
	var tipo = document.getElementById("tipo").value;
	var tipo_pago = document.getElementById("tipo_pago").value;
	var juzgado = document.getElementById("juzgado").value; juzgado = juzgado.trim();
	var codempleado = document.getElementById("codempleado").value;
	var coddemandante = document.getElementById("coddemandante").value;
	var comentarios = document.getElementById("comentarios").value; comentarios = comentarios.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	var detalles = "";
	
	var frmdetalles = document.getElementById("frmdetalles");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "txtcodconcepto") detalles += n.value + "|";
		if (n.name == "txtporcentaje") detalles += setNumero(n.value) + "|";
		if (n.name == "txtmonto") detalles += setNumero(n.value) + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (organismo == "" || fresolucion == "" || expediente == "" || tipo == "" || juzgado == "" || codempleado == "" || tipo_pago == "") msjError(1010);
	else if (!esFecha(fresolucion)) alert("¡FORMATO FECHA DE RESOLUCION INCORRECTA!");
	else if (detalles == "") alert("¡Debe ingresar por lo menos un concepto a retener!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=RETENCIONES-JUDICIALES&accion="+accion+"&codretencion="+codretencion+"&organismo="+organismo+"&fresolucion="+fresolucion+"&expediente="+expediente+"&tipo="+tipo+"&juzgado="+juzgado+"&codempleado="+codempleado+"&comentarios="+comentarios+"&tipo_pago="+tipo_pago+"&coddemandante="+coddemandante+"&codretencion="+codretencion+"&estado="+estado+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert("¡"+resp+"!");
				else cargarPagina(document.getElementById("frmentrada"), "rjudiciales.php");
			}
		}
	}
	return false;
}

//	PROCESO DE JUBILACION
function cargarOpcionJubilacion(form, pagina, accion) {
	var codigo = form.registro.value;
	if (codigo == "") msjError(1000);
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCESO-JUBILACION&accion="+accion+"&codpersona="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else cargarPagina(form, pagina);
			}
		}
	}
}
function verificarProcesoJubilacion(form, accion) {
	var organismo = document.getElementById("organismo").value;
	var codpersona = document.getElementById("codpersona").value;
	var anios_servicio = document.getElementById("anios_servicio").value;
	var edad = document.getElementById("edad_empleado").value;
	var procesadopor = document.getElementById("procesadopor").value;
	var obsprocesado = document.getElementById("obsprocesado").value; obsprocesado = obsprocesado.trim();
	var aprobadopor = document.getElementById("aprobadopor").value;
	var obsaprobado = document.getElementById("obsaprobado").value; obsaprobado = obsaprobado.trim();
	var nomina = document.getElementById("nomina").value;
	var tipo_trabajador = document.getElementById("tipo_trabajador").value;
	if (document.getElementById("activo").checked) var status = "A"; else var status = "I";
	var tcese = document.getElementById("tcese").value;
	var fcese = document.getElementById("fcese").value; fcese = fcese.trim();
	var explicacion = document.getElementById("explicacion").value; explicacion = explicacion.trim();
	var monto_jubilacion = document.getElementById("monto_jubilacion").value;
	var coeficiente = document.getElementById("coeficiente").value;
	
	if (status == "I" && (tcese == "" || fcese == "" || !esFecha(fcese) || explicacion == "")) alert("¡LOS DATOS DEL CESE SON OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=PROCESO-JUBILACION&accion="+accion+"&codpersona="+codpersona+"&organismo="+organismo+"&anios_servicio="+anios_servicio+"&edad="+edad+"&obsprocesado="+obsprocesado+"&obsaprobado="+obsaprobado+"&procesadopor="+procesadopor+"&aprobadopor="+aprobadopor+"&nomina="+nomina+"&tipo_trabajador="+tipo_trabajador+"&status="+status+"&tcese="+tcese+"&fcese="+fcese+"&explicacion="+explicacion+"&monto_jubilacion="+monto_jubilacion+"&coeficiente="+coeficiente);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else cargarPagina(form, "jubilacion_proceso_listado.php?limit=0");
			}
		}
	}
	return false;
}
function pdfJubilacion() {
	var organismo = document.getElementById("organismo").value;
	var codpersona = document.getElementById("codpersona").value;
	var anios_servicio = document.getElementById("anios_servicio").value;
	var edad_empleado = document.getElementById("edad_empleado").value;
	var procesadopor = document.getElementById("procesadopor").value;
	var obsprocesado = document.getElementById("obsprocesado").value; obsprocesado = obsprocesado.trim();
	var aprobadopor = document.getElementById("aprobadopor").value;
	var obsaprobado = document.getElementById("obsaprobado").value; obsaprobado = obsaprobado.trim();
	var monto_jubilacion = document.getElementById("monto_jubilacion").value;
	var coeficiente = document.getElementById("coeficiente").value;
	
	var get = "?codpersona="+codpersona+"&anios_servicio="+anios_servicio+"&edad_empleado="+edad_empleado+"&procesadopor="+procesadopor+"&obsprocesado="+obsprocesado+"&aprobadopor="+aprobadopor+"&obsaprobado="+obsaprobado+"&monto_jubilacion="+monto_jubilacion+"&coeficiente="+coeficiente;
	var pagina = "pdf_jubilacion.php"+get;
	
	window.open(pagina, "wPDF", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}
function pdfJubilacionSel() {
	var codpersona = document.getElementById("registro").value;
	if (codpersona == "") alert("¡DEBE SELECCIONAR UN EMPLEADO!");
	else {
		var pagina = "pdf_jubilacion.php?codpersona="+codpersona; 
		window.open(pagina, "wPDF", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
	}
}

//	LISTADO DE ANTECEDENTES
function pdfAntecedentes() {
	var codpersona = document.getElementById("registro").value;
	var pagina = "pdf_antecedentes.php?codpersona="+codpersona;
	
	window.open(pagina, "wPDF", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
}

//	MAESTRO DE FERIADOS
function verificarFeriados(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var anio = document.getElementById("anio").value;
	var feriado = document.getElementById("feriado").value; var feriado = feriado.trim();
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	if (document.getElementById("flagvariable").checked) var flagvariable = "S"; else var flagvariable = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (feriado == "" || descripcion == "") alert("¡DEBE INGRESAR LOS DATOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=FERIADOS&accion="+accion+"&codigo="+codigo+"&anio="+anio+"&feriado="+feriado+"&descripcion="+descripcion+"&flagvariable="+flagvariable+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else cargarPagina(form, "feriados.php?limit=0");
			}
		}
	}
	return false;
}

//	MAESTRO DE GRADO SALARIAL
function verificarGradoSalarial(form, accion) {
	var codigo = document.getElementById("codigo").value;
	var categoria = document.getElementById("categoria").value;
	var descripcion = document.getElementById("descripcion").value; descripcion = descripcion.trim();
	var grado = document.getElementById("grado").value; grado = grado.trim();
	var sueldo_minimo = document.getElementById("sueldo_minimo").value; sueldo_minimo = sueldo_minimo.trim(); sueldo_minimo = sueldo_minimo.replace(",", ".");
	var sueldo_maximo = document.getElementById("sueldo_maximo").value; sueldo_maximo = sueldo_maximo.trim(); sueldo_maximo = sueldo_maximo.replace(",", ".");
	var sueldo_promedio = document.getElementById("sueldo_promedio").value; sueldo_promedio = sueldo_promedio.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	
	if (descripcion == "" || sueldo_promedio == "" || sueldo_promedio == 0) alert("¡DEBE INGRESAR LOS DATOS OBLIGATORIOS!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=GRADO-SALARIAL&accion="+accion+"&categoria="+categoria+"&grado="+grado+"&codigo="+codigo+"&descripcion="+descripcion+"&sueldo_minimo="+sueldo_minimo+"&sueldo_maximo="+sueldo_maximo+"&sueldo_promedio="+sueldo_promedio+"&estado="+estado);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp != 0) alert ("¡"+resp+"!");
				else cargarPagina(form, "grado_salarial.php?limit=0");
			}
		}
	}
	return false;
}

//	FUNCION PARA IMPRIMIR PROMEDIO EN UN CAMPO
function getSueldoPromedio(minimo, maximo) {
	minimo = minimo.replace(",", "."); minimo = parseFloat(minimo);
	maximo = maximo.replace(",", "."); maximo = parseFloat(maximo);
	var prom = (maximo + minimo) / 2;
	if (isNaN(prom)) document.getElementById('sueldo_promedio').value = "";
	else document.getElementById('sueldo_promedio').value = prom.toFixed(2);
}

function enabledCargando(display) {
	document.getElementById("bloqueo").style.display = display;
	document.getElementById("cargando").style.display = display;
}

//	FUNCIONES NUMERICAS

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

//	selector de lista para una grilla
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