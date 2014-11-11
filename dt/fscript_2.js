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

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarPagina(form, pagina) {
	form.method="POST";
	form.action=pagina;
	form.submit();
}

//	FUNCION PARA CARGAR UNA NUEVA PAGINA 
function cargarOpcion(form, seleccion, pagina, target, param) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		if (target=="SELF") cargarPagina(form, pagina+"?registro="+seleccion);
		else { pagina=pagina+"?limit=0&accion=VER&registro="+seleccion; cargarVentana(form, pagina, param); }
	}
}

//	FUNCION PARA ELIMINAR UN REGISTRO DE LA BASE DE DATOS
function eliminarRegistro(form, seleccion, modulo) {
	if (seleccion == "") alert("¡DEBE SELECCIONAR UN REGISTRO!");
	else {
		//	PREGUNTO SI DESEA ELIMINAR
		var eliminar = confirm("¡Esta seguro de eliminar este registro?");
		if (eliminar) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_2.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo="+modulo+"&accion=ELIMINAR&seleccion="+seleccion);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var error=ajax.responseText;
					if (error!=0) alert ("¡"+error+"!");
					else cargarPagina(form, form.action);
				}
			}
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

//	------------------------------------------------------------------------------------------
//	--
//	------------------------------------------------------------------------------------------

//	FUNCION PARA ABRIR CONSTANCIA
function abrirConstancia(form, pag, param) {
	var registros = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.type == "checkbox" && n.name == "sel" && n.checked) {
			registros += n.value + ";";
		}
	}
	var len = registros.length; len--;
	registros = registros.substr(0, len);
	
	window.open("pdf_constancias.php?registros="+registros, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes");
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
//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroPDFConstancias(form, limit) {
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
			var pagina="reportes_constancias.php?filtro="+filtro+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}

//	EMPLEADOS (IMPUESTO SOBRE LA RENTA) - empleados_impuesto.php
function validarEmpleadoImpuesto(form, codpersona, accion) {
	var anio = document.getElementById("anio").value; anio = anio.trim();
	var desde = document.getElementById("desde").value; desde = desde.trim();
	var hasta = document.getElementById("hasta").value; hasta = hasta.trim();
	var porcentaje = document.getElementById("porcentaje").value; porcentaje = porcentaje.trim();
	
	if (anio == "" || porcentaje == "" || desde == "" || hasta == "") alert("¡Todos los campos son obligatorios!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADO-IMPUESTO&accion="+accion+"&codpersona="+codpersona+"&anio="+anio+"&desde="+desde+"&hasta="+hasta+"&porcentaje="+porcentaje);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var respuesta = ajax.responseText;
				if (respuesta != "") alert (respuesta);
				else form.submit();
			}
		}
	}
	return false;
}
function editarEmpleadoImpuesto(form, codpersona, anio) {
	if (anio == "") alert("¡Debe seleccionar un registro!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADO-IMPUESTO&accion=EDITAR&codpersona="+codpersona+"&anio="+anio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var respuesta = ajax.responseText;
				var datos = respuesta.split("|")
				if (datos[0] != "") alert (respuesta);
				else {
					document.getElementById("anio").value = anio;
					document.getElementById("desde").value = datos[1];
					document.getElementById("hasta").value = datos[2];
					document.getElementById("porcentaje").value = datos[3];
					
					document.getElementById("btEditar").disabled = true;
					document.getElementById("btBorrar").disabled = true;
					document.getElementById("anio").disabled = true;
					document.getElementById("accion").value = "MODIFICAR";
				}
			}
		}
	}
}
function borrarEmpleadoImpuesto(form, codpersona, anio) {
	if (anio == "") alert("¡Debe seleccionar un registro!");
	else if (confirm("¿Desea eliminar este registro?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EMPLEADO-IMPUESTO&accion=BORRAR&codpersona="+codpersona+"&anio="+anio);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var respuesta = ajax.responseText;
				if (respuesta != "") alert (respuesta);
				else form.submit();
			}
		}
	}
}
//	------------------------------------------------------------

//	MAESTRO DE SUELDOS MINIMOS (sueldos_minimos.php)
function verificarSueldoMinimo(form, accion) {
	var secuencia = document.getElementById("secuencia").value;	
	var periodo = document.getElementById("periodo").value;	periodo = periodo.trim();
	var monto = document.getElementById("monto").value;	 monto = monto.trim();
	
	if (periodo == "" || monto == "") alert("¡Debe ingresar los campos obligatorios!");
	else if (isNaN(monto)) alert("¡Debe ingresar un monto correcto!");
	else {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=SUELDOS-MINIMOS&accion="+accion+"&secuencia="+secuencia+"&periodo="+periodo+"&monto="+monto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var respuesta = ajax.responseText;
				if (respuesta.trim() != "") alert (respuesta);
				else form.submit();
			}
		}
	}
	return false;
}
//	------------------------------------------------------------

// EVALUACION DE DESEMPEÑO
function verificarEvaluacionDesempenioEmpleado(form, accion, finalizar) {
	document.getElementById("btGuardar").disabled = true;
	
	var error_tab2 = false;
	var error_tab2_8 = false;
	var error_tab4 = false;
	var error_tab5 = false;
	var error_tab5_1050 = false;
	var error_tab6 = false;
	var error_tab7 = false;
	//	Formulario principal.....
	var forganismo = new String (form.forganismo.value); forganismo=forganismo.trim();
	var fpevaluacion = new String (form.fpevaluacion.value); fpevaluacion=fpevaluacion.trim();
	var chkempleado = new String (form.chkempleado.value); chkempleado=chkempleado.trim();
	var fempleado = new String (form.fempleado.value); fempleado=fempleado.trim();
	var fnomempleado = new String (form.fnomempleado.value); fnomempleado=fnomempleado.trim();
	var chkresponsable = new String (form.chkresponsable.value); chkresponsable=chkresponsable.trim();
	var fresponsable = new String (form.fresponsable.value); fresponsable=fresponsable.trim();
	var fnomresponsable = new String (form.fnomresponsable.value); fnomresponsable=fnomresponsable.trim();
	var filtro = new String (form.filtro.value); filtro=filtro.trim();
	var limit = new String (form.limit.value); limit=limit.trim();
	var secuencia = new String (form.secuencia.value); secuencia=secuencia.trim();
	var periodo = new String (form.periodo.value); periodo=periodo.trim();
	var persona = new String (form.persona.value); persona=persona.trim();
	var comempleado = new String (form.comempleado.value); comempleado=comempleado.trim();
	var codevaluador = new String (form.codevaluador.value); codevaluador=codevaluador.trim();
	var comevaluador = new String (form.comevaluador.value); comevaluador=comevaluador.trim();
	var codsupervisor = new String (form.codsupervisor.value); codsupervisor=codsupervisor.trim();
	var comsupervisor = new String (form.comsupervisor.value); comsupervisor=comsupervisor.trim();
	var status = new String (form.status.value); status=status.trim();
	
	var desempenio = new Number(setNumero(form.desempenio.value));
	var funciones = new Number(setNumero(form.funciones.value));
	var metas = new Number(setNumero(form.metas.value));
	var total = new Number(setNumero(form.total.value));
	
	var fecha_evaluacion = new String (form.fecha_evaluacion.value); fecha_evaluacion=fecha_evaluacion.trim();
	var esF=esFecha(fecha_evaluacion);
	//	---------------------------
	
	//	Competencia (Tab2)...
	var detalles_tab2 = "";
	var peso_tab2 = new Number(0.00);
	var frmdetalles_tab2 = document.getElementById("frmdetalles_tab2");
	var cantdetalles_tab2 = new Number(document.getElementById("cantdetalles_tab2").value);
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.name == "txtplantilla_tab2") detalles_tab2 += n.value + "|";
		if (n.name == "txtcompetencia_tab2") detalles_tab2 += n.value + "|";
		if (n.name == "txtcalificacion_tab2") {
			var calificacion= new Number(setNumero(n.value.trim()));
			if (finalizar == "FINALIZAR" && calificacion == 0) { error_tab2 = true; break; }
			else detalles_tab2 += setNumero(n.value) + "|";
		}
		if (n.name == "txtpeso_tab2") {
			if (n.value.trim() != "") {
				var peso = new Number(setNumero(n.value));
				if (peso >= 1 && peso <= 4) {
					detalles_tab2 += peso + ";"; 
					peso_tab2 += peso; 
				} else { error_tab2_8 = true; break; }
			} else { error_tab2 = true; break; }
		}
	}
	var len = detalles_tab2.length; len--;
	detalles_tab2 = detalles_tab2.substr(0, len);
	//	---------------------------
	
	//	Fortalezas y Debilidades (Tab4)...
	var detalles_tab4 = "";
	var peso_tab4 = new Number(0.00);
	var frmdetalles_tab4 = document.getElementById("frmdetalles_tab4");
	var cantdetalles_tab4 = new Number(document.getElementById("cantdetalles_tab4").value);
	for(i=0; n=frmdetalles_tab4.elements[i]; i++) {
		if (n.name == "slttipo_tab4") {
			if (n.value.trim() != "") detalles_tab4 += n.value + "|";
			else { error_tab4 = true; break; }
		}
		if (n.name == "txtdescripcion_tab4") {
			if (n.value.trim() != "") detalles_tab4 += n.value + ";";
			else { error_tab4 = true; break; }
		}
	}
	var len = detalles_tab4.length; len--;
	detalles_tab4 = detalles_tab4.substr(0, len);
	//	---------------------------
	
	//	Objetivos y Metas (Tab5)...
	var detalles_tab5 = "";
	var peso_tab5 = new Number(0.00);
	var frmdetalles_tab5 = document.getElementById("frmdetalles_tab5");
	var cantdetalles_tab5 = new Number(document.getElementById("cantdetalles_tab5").value);
	for(i=0; n=frmdetalles_tab5.elements[i]; i++) {
		if (n.name == "txtdescripcion_tab5") {
			if (n.value.trim() != "") detalles_tab5 += n.value + "|";
			else { error_tab5 = true; break; }
		}
		if (n.name == "txtperiodo_tab5") {
			if (n.value.trim() != "") detalles_tab5 += n.value + "|";
			else { error_tab5 = true; break; }
		}
		if (n.name == "txtcomentarios_tab5") detalles_tab5 += n.value + "|";
		if (n.name == "txtfdesde_tab5") {
			if (n.value.trim() != "") detalles_tab5 += n.value + "|";
			else { error_tab5 = true; break; }
		}
		if (n.name == "txtfhasta_tab5") {
			if (n.value.trim() != "") detalles_tab5 += n.value + "|";
			else { error_tab5 = true; break; }
		}
		if (n.name == "txtcalificacion_tab5") {
			var calificacion= new Number(setNumero(n.value.trim()));
			if (finalizar == "FINALIZAR" && calificacion == 0) { error_tab5 = true; break; }
			else detalles_tab5 += setNumero(n.value) + "|";
		}
		if (n.name == "txtpeso_tab5") {
			if (n.value.trim() != "") {
				var peso = new Number(setNumero(n.value));
				if (peso >= 5 && peso <= 25) {
					detalles_tab5 += peso + ";"; 
					peso_tab5 += peso;
				} else { error_tab5_1050 = true; break; }
			} else { error_tab5 = true; break; }
		}
	}
	var len = detalles_tab5.length; len--;
	detalles_tab5 = detalles_tab5.substr(0, len);
	//	---------------------------
	
	//	Necesidad de Capacitación (Tab6)...
	var detalles_tab6 = "";
	var peso_tab6 = new Number(0.00);
	var frmdetalles_tab6 = document.getElementById("frmdetalles_tab6");
	var cantdetalles_tab6 = new Number(document.getElementById("cantdetalles_tab6").value);
	for(i=0; n=frmdetalles_tab6.elements[i]; i++) {
		if (n.name == "txtnecesidad_tab6") {
			if (n.value.trim() != "") detalles_tab6 += n.value + "|";
			else { error_tab6 = true; break; }
		}
		else if (n.name == "sltprioridad_tab6") {
			if (n.value.trim() != "") detalles_tab6 += n.value + "|";
			else { error_tab6 = true; break; }
		}
		else if (n.name == "txtcodcurso_tab6") {
			if (n.value.trim() != "") detalles_tab6 += n.value + "|";
			else { error_tab6 = true; break; }
		}
		else if (n.name == "txtobjetivo_tab6") detalles_tab6 += n.value + ";";
	}
	var len = detalles_tab6.length; len--;
	detalles_tab6 = detalles_tab6.substr(0, len);
	//	---------------------------
	
	//	Revision de Metas (Tab7)...
	var detalles_tab7 = "";
	var peso_tab7 = new Number(0.00);
	var frmdetalles_tab7 = document.getElementById("frmdetalles_tab7");
	var cantdetalles_tab7 = new Number(document.getElementById("cantdetalles_tab7").value);
	for(i=0; n=frmdetalles_tab7.elements[i]; i++) {
		if (n.name == "txtfecha1_tab7") detalles_tab7 += n.value + "|";
		if (n.name == "txtporcentaje1_tab7") {
			var porcentaje = new Number(setNumero(n.value));
			if (isNaN(porcentaje)) { error_tab7 = true; break; }
			else detalles_tab7 += n.value + "|";
		}
		if (n.name == "txtobservacion1_tab7") detalles_tab7 += n.value + "|";
		if (n.name == "txtfecha2_tab7") detalles_tab7 += n.value + "|";
		if (n.name == "txtporcentaje2_tab7") {
			var porcentaje = new Number(setNumero(n.value));
			if (isNaN(porcentaje)) { error_tab7 = true; break; }
			else detalles_tab7 += n.value + "|";
		}
		if (n.name == "txtobservacion2_tab7") detalles_tab7 += n.value + "|";
		if (n.name == "txtfecha3_tab7") detalles_tab7 += n.value + "|";
		if (n.name == "txtporcentaje3_tab7") {
			var porcentaje = new Number(setNumero(n.value));
			if (isNaN(porcentaje)) { error_tab7 = true; break; }
			else detalles_tab7 += n.value + "|";
		}
		if (n.name == "txtobservacion3_tab7") detalles_tab7 += n.value + ";";
	}
	var len = detalles_tab7.length; len--;
	detalles_tab7 = detalles_tab7.substr(0, len);
	//	---------------------------
	//	VERIFICO QUE LOS CAMPOS OBLIGATORIOS NO ESTEN VACIOS
	if (persona == "" || fecha_evaluacion == "") alert("¡Los campos marcados con (*) son obligatorios!");
	else if (!esF) alert("¡Formato de fecha incorrecta!");
	else if (error_tab4) alert("¡En la ficha de Fortalezas y Debilidades dejó campos obligatorios vacios!");
	else if (error_tab6) alert("¡En la ficha de Necesidad de Capacitación dejó campos obligatorios vacios!");
	else if (error_tab2_8) alert("¡En la ficha Competencia el peso debe ser un valor entre 1 y 4!");
	else if (cantdetalles_tab5 < 3 || cantdetalles_tab5 > 5 || detalles_tab5 == "") alert("¡Debe ingresar no menos de tres y no mas de cinco objetivos y metas!");
	else if (error_tab5_1050) alert("¡En la ficha Objetivos y Metas el peso debe ser un valor entre 5 y 25!");
	else if (error_tab7) alert("¡En la ficha Revisión de Metas el porcentaje ingresado es incorrecto!");
	else if (error_tab2) alert("¡ERROR: En la ficha competencias no pueder haber totales en cero!");
	else if (error_tab5) alert("¡ERROR: En la ficha Objetivos y Metas no pueder haber totales en cero!");
	else if (peso_tab2 != 50) alert("¡En la ficha de Competencia la suma del peso es ("+peso_tab2+") y no puede ser diferente de 50!");
	else if (peso_tab5 != 50) alert("¡En la ficha de Objetivos y Metas la suma del peso es ("+peso_tab5+") y no puede ser diferente de 50!");
	else {
		if (accion == "FINALIZAR" && detalles_tab4 == "") {
			var continuar_tab4 = confirm("¡No se encontraron datos en la ficha Fortalezas y Debilidades!\n¿Está seguro de finalizar la evaluación?");
		} else var continuar_tab4 = true;
		
		if (accion == "FINALIZAR" && continuar_tab4 && detalles_tab6 == "") {
			var continuar_tab6 = confirm("¡No se encontraron datos en la ficha Necesidades de Capacitación!\n¿Está seguro de finalizar la evaluación?");
		} else var continuar_tab6 = true;
		
		if (continuar_tab4 && continuar_tab6) {
			//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
			var ajax=nuevoAjax();
			ajax.open("POST", "fphp_ajax_2.php", true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("modulo=EVALUACIONDESEMPENIO&accion="+accion+"&forganismo="+forganismo+"&fpevaluacion="+fpevaluacion+"&secuencia="+secuencia+"&periodo="+periodo+"&persona="+persona+"&desempenio="+desempenio+"&funciones="+funciones+"&funciones="+funciones+"&metas="+metas+"&comempleado="+comempleado+"&codevaluador="+codevaluador+"&comevaluador="+comevaluador+"&codsupervisor="+codsupervisor+"&comsupervisor="+comsupervisor+"&fecha_evaluacion="+fecha_evaluacion+"&chkempleado="+chkempleado+"&fempleado="+fempleado+"&fnomempleado="+fnomempleado+"&chkresponsable="+chkresponsable+"&fresponsable="+fresponsable+"&fnomresponsable="+fnomresponsable+"&filtro="+filtro+"&limit="+limit+"&status="+status+"&fecha_evaluacion="+fecha_evaluacion+"&detalles_tab2="+detalles_tab2+"&detalles_tab4="+detalles_tab4+"&detalles_tab5="+detalles_tab5+"&detalles_tab6="+detalles_tab6+"&detalles_tab7="+detalles_tab7+"&finalizar="+finalizar);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4)	{
					var resp=ajax.responseText.split("|:|");
					if (resp[0]!=0) alert ("¡"+resp[0]+"!");
					else {
						var pagina="evaluacion_desempenio.php";
						cargarPagina(form, pagina);
					}
				}
			}
		}
	}
	document.getElementById("btGuardar").disabled = false;
	return false;
}
//	------------------------------------------------------------

//	inserta linea a la grilla
function insertarLineaDetalle(form, accion, tab) {
	var nrodetalles = new Number(document.getElementById("nrodetalles_"+tab).value); nrodetalles++;
	var cantdetalles = new Number(document.getElementById("cantdetalles_"+tab).value); cantdetalles++;
	var codigo = tab + "_" + nrodetalles;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("&accion="+accion+"&nrodetalles="+nrodetalles+"&cantdetalles="+cantdetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				document.getElementById("nrodetalles_"+tab).value = nrodetalles;
				document.getElementById("cantdetalles_"+tab).value = cantdetalles;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle_"+tab+"');");
				newTr.id = codigo;
				document.getElementById("listaDetalles_"+tab).appendChild(newTr);
				document.getElementById(codigo).innerHTML = document.getElementById(codigo).innerHTML + datos[1];
			}
		}
	}
}
//	------------------------------------------------------------

//	inserta linea a la grilla
function insertarLineaDetalleMetas(form, accion, tab) {
	var nrodetalles = new Number(document.getElementById("nrodetalles_"+tab).value); nrodetalles++;
	var cantdetalles = new Number(document.getElementById("cantdetalles_"+tab).value); cantdetalles++;
	var codigo = tab + "_" + nrodetalles;
	
	//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("&accion="+accion+"&nrodetalles="+nrodetalles+"&cantdetalles="+cantdetalles);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				document.getElementById("nrodetalles_"+tab).value = nrodetalles;
				document.getElementById("cantdetalles_"+tab).value = cantdetalles;
				
				var newTr = document.createElement("tr");
				newTr.className = "trListaBody";
				newTr.setAttribute("onclick", "mClk(this, 'seldetalle_"+tab+"');");
				newTr.id = codigo;
				document.getElementById("listaDetalles_"+tab).appendChild(newTr);
				document.getElementById(codigo).innerHTML = document.getElementById(codigo).innerHTML + datos[1];
								
				var codigo7 = "tab7_" + nrodetalles; 
				var newTr7 = document.createElement("tr");
				newTr7.className = "trListaBody";
				newTr7.id = codigo7;
				document.getElementById("listaDetalles_tab7").appendChild(newTr7);
				document.getElementById(codigo7).innerHTML = document.getElementById(codigo7).innerHTML + datos[2];
			}
		}
	}
}
//	------------------------------------------------------------

//	abre selector desde lista
function selector(form, iddetalle, pag, param) {
	var detalle = document.getElementById(iddetalle).value;
	
	if (detalle == "") alert("¡Debe seleccionar un detalle!");
	else {
		cargarVentana(form, pag+"&detalle="+detalle, param);
	}
}
//	------------------------------------------------------------

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

//	------------------------------------------------------------

//	selector de lista para una grilla
function listaEvaluacionObjetivosMetas(descripcion, cod, nom) {
	var codigo = document.getElementById("registro").value;
	var id = document.getElementById("detalle").value;	
	var idcod = cod+"_"+id;
	var idnom = nom+"_"+id;
	
	opener.document.getElementById(idcod).value = codigo;
	opener.document.getElementById(idcod).title = descripcion;
	opener.document.getElementById(idnom).value = descripcion;
	
	//	setTotalesCalificacionPeso(id)...
	linea = id.split("_");
	var calificacion = new Number(setNumero(opener.document.getElementById("txtnomcalificativo_tab5_"+linea[1]).value));
	var peso = new Number(setNumero(opener.document.getElementById("txtpeso_tab5_"+linea[1]).value));
	var total = calificacion * peso;
	opener.document.getElementById("total_tab5_"+linea[1]).innerHTML = setNumeroFormato(total, 2, ".", ",");
	
	var tab5_total = new Number(0.00);
	var frmdetalles_tab5 = opener.document.getElementById("frmdetalles_tab5");
	for(i=0; n=frmdetalles_tab5.elements[i]; i++) {
		if (n.name == "txtcalificacion_tab5") {
			var calificacion = new Number(setNumero(n.value));
		}
		else if (n.name == "txtpeso_tab5") {
			var peso = new Number(setNumero(n.value));
			tab5_total += (calificacion * peso);
		}
	}
	opener.document.getElementById("tab5_total").innerHTML = setNumeroFormato(tab5_total, 2, ".", ",");
	
	var desempenio = new Number(setNumero(opener.document.getElementById("desempenio").value));
	var total_general = tab5_total + desempenio;	
	opener.document.getElementById("metas").value = setNumeroFormato(tab5_total, 2, ".", ",");
	opener.document.getElementById("total").value = setNumeroFormato(total_general, 2, ".", ",");
		
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=mostrarEscalaCuantitativa&valor="+total_general);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText.trim();
			opener.document.getElementById("escala").innerHTML = resp;
			window.close();
		}
	}
}

//	------------------------------------------------------------

//	selector de lista para una grilla
function listaEvaluacionDesempeno(descripcion, cod, nom) {
	var codigo = document.getElementById("registro").value;
	var id = document.getElementById("detalle").value;
	var idcod = cod+"_"+id;
	var idnom = nom+"_"+id;
	
	opener.document.getElementById(idcod).value = codigo;
	opener.document.getElementById(idcod).title = descripcion;
	opener.document.getElementById(idnom).value = descripcion;
	
	var sub_total = new Number(0.00);
	var calificacion_sub_total = new Number(0.00);
	var clase = opener.document.getElementById(idnom).className; 
	var idcali = clase.split("_"); var clase_peso = "peso_" + idcali[1] + "_" + idcali[2]; var clase_sub = "subtotal_" + idcali[1] + "_" + idcali[2];
	var frmdetalles_tab2 = opener.document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.className == clase) {
			var calificacion_monto = new Number(setNumero(n.value));
			calificacion_sub_total += calificacion_monto;
		}
		if (n.className == clase_peso) {
			var peso_monto = new Number(setNumero(n.value));
			sub_total += (peso_monto * calificacion_monto);
		}
	}
	opener.document.getElementById(clase).value = setNumeroFormato(calificacion_sub_total, 2, ".", ",");
	opener.document.getElementById(clase_sub).value = setNumeroFormato(sub_total, 2, ".", ",");
	
	var calificacion_total = new Number(0.00);
	var frmdetalles_tab2 = opener.document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.name == "calificacion_sub_total_tab2") {
			var calificacion_monto = new Number(setNumero(n.value));
			calificacion_total += calificacion_monto;
		}
	}
	opener.document.getElementById("calificacion_total_tab2").value = setNumeroFormato(calificacion_total, 2, ".", ",");
	
	
	var calificacion = new Number(setNumero(opener.document.getElementById("txtnomcalificativo_"+id).value));
	var peso = new Number(setNumero(opener.document.getElementById("txtpeso_"+id).value));
	var total = calificacion * peso;
	opener.document.getElementById("total_"+id).innerHTML = setNumeroFormato(total, 2, ".", ",");
	
	var total = new Number(0.00);
	var frmdetalles_tab2 = opener.document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.name == "subtotal_tab2") {
			var monto = new Number(setNumero(n.value));
			total += monto;
		}
	}
	opener.document.getElementById("total_tab2").value = setNumeroFormato(total, 2, ".", ",");
	
	var minimo = new Number(setNumero(opener.document.getElementById("min_tab2").value));
	var maximo = new Number(setNumero(opener.document.getElementById("max_tab2").value));	
	var partes_id = id.split("_");
	var l = new Number(partes_id[1]);
	
	for (i=minimo; i<=maximo; i++) {
		var td="P_"+i+"_"+l;
		opener.document.getElementById(td).style.background="";	
	}
	
	for (i=minimo; i<=calificacion; i++) {
		var td="P_"+i+"_"+l;
		opener.document.getElementById(td).style.background="#0099CC";	
	}
	
	var metas = new Number(setNumero(opener.document.getElementById("metas").value));
	var total_general = total + metas;
	opener.document.getElementById("desempenio").value = setNumeroFormato(total, 2, ".", ",");
	opener.document.getElementById("total").value = setNumeroFormato(total_general, 2, ".", ",");
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=mostrarEscalaCuantitativa&valor="+total_general);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText.trim();
			opener.document.getElementById("escala").innerHTML = resp;
			window.close();
		}
	}
}

//	------------------------------------------------------------

//	FUNCION PARA SELECCIONAR UN CENTRO DE ESTUDIO DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selCalificativo(busqueda) {
	var registro=document.getElementById("registro").value;
	opener.document.frmentrada.calificacion.value=busqueda;
	window.close();
}
//	------------------------------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalLista(rows) {
	var numreg = document.getElementById("rows");
	numreg.innerHTML="Registros: "+rows;
}
//	------------------------------------------------------------

//	FUNCION PARA CALCULAR EL PESO POR LA CALIFICACION
function setTotalesCalificacionPeso(id) {
	var calificacion = new Number(setNumero(document.getElementById("txtnomcalificativo_tab5_"+id).value));
	var peso = new Number(setNumero(document.getElementById("txtpeso_tab5_"+id).value));
	var total = calificacion * peso;
	document.getElementById("total_tab5_"+id).innerHTML = setNumeroFormato(total, 2, ".", ",");
	
	var metas = new Number(0.00);
	var tab5_total_peso = new Number(0.00);
	var frmdetalles_tab5 = document.getElementById("frmdetalles_tab5");
	for(i=0; n=frmdetalles_tab5.elements[i]; i++) {
		if (n.name == "txtcalificacion_tab5") {
			var calificacion = new Number(setNumero(n.value));
		}
		else if (n.name == "txtpeso_tab5") {
			var peso = new Number(setNumero(n.value));
			tab5_total_peso += peso;
			metas += (calificacion * peso)
		}
	}
	
	var desempenio = new Number(setNumero(document.getElementById("desempenio").value));
	var total_general = desempenio + metas;
	
	document.getElementById("metas").value = setNumeroFormato(metas, 2, ".", ",");
	document.getElementById("total").value = setNumeroFormato(total_general, 2, ".", ",");
	
	document.getElementById("tab5_total_peso").innerHTML = setNumeroFormato(tab5_total_peso, 2, ".", ",");
	document.getElementById("tab5_total").innerHTML = setNumeroFormato(metas, 2, ".", ",");
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=mostrarEscalaCuantitativa&valor="+total_general);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText.trim();
			document.getElementById("escala").innerHTML = resp;
		}
	}
}
//	------------------------------------------------------------

//	FUNCION PARA CALCULAR EL PESO POR LA CALIFICACION
function setTotalesCalificacionPesoDesempeno(id) {
	var calificacion = new Number(setNumero(document.getElementById("txtnomcalificativo_tab2_"+id).value));
	var peso = new Number(setNumero(document.getElementById("txtpeso_tab2_"+id).value));
	var total = calificacion * peso;
	document.getElementById("total_tab2_"+id).innerHTML = setNumeroFormato(total, 2, ".", ",");
	
	var sub_total = new Number(0.00);
	var peso_sub_total = new Number(0.00);
	var clase = document.getElementById("txtpeso_tab2_"+id).className;
	var idpeso = clase.split("_"); var clase_cali = "calificacion_" + idpeso[1] + "_" + idpeso[2]; var clase_sub = "subtotal_" + idpeso[1] + "_" + idpeso[2];
	var frmdetalles_tab2 = document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.className == clase_cali) {
			var calificacion_monto = new Number(setNumero(n.value));
		}
		if (n.className == clase) {
			var peso_monto = new Number(setNumero(n.value));
			peso_sub_total += peso_monto;
			sub_total += (peso_monto * calificacion_monto);
		}
	}
	document.getElementById(clase).value = setNumeroFormato(peso_sub_total, 2, ".", ",");
	document.getElementById(clase_sub).value = setNumeroFormato(sub_total, 2, ".", ",");
	
	var peso_total = new Number(0.00);
	var frmdetalles_tab2 = document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.name == "peso_sub_total_tab2") {
			var peso_monto = new Number(setNumero(n.value));
			peso_total += peso_monto;
		}
	}
	document.getElementById("peso_total_tab2").value = setNumeroFormato(peso_total, 2, ".", ",");
	
	var total = new Number(0.00);
	var frmdetalles_tab2 = document.getElementById("frmdetalles_tab2");
	for(i=0; n=frmdetalles_tab2.elements[i]; i++) {
		if (n.name == "subtotal_tab2") {
			var monto = new Number(setNumero(n.value));
			total += monto;
		}
	}
	document.getElementById("total_tab2").value = setNumeroFormato(total, 2, ".", ",");
	
	var metas = new Number(setNumero(document.getElementById("metas").value));
	var total_general = total + metas;	
	document.getElementById("desempenio").value = setNumeroFormato(total, 2, ".", ",");
	document.getElementById("total").value = setNumeroFormato(total_general, 2, ".", ",");
		
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=mostrarEscalaCuantitativa&valor="+total_general);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText.trim();
			document.getElementById("escala").innerHTML = resp;
		}
	}
}
//	------------------------------------------------------------

//	funcion para quitar un item de la lista de ordenes
function quitarDetalleListaTab(coddetalle, tab) {
	if (confirm("¿Desea quitar este detalle de la lista?")) {
		var cantdetalles = new Number(document.getElementById("cantdetalles_"+tab).value); cantdetalles--;
		var listaDetalles = document.getElementById("listaDetalles_"+tab); 
		var trDetalle = document.getElementById(coddetalle); 
		listaDetalles.removeChild(trDetalle);
		document.getElementById("seldetalle_"+tab).value = "";
		document.getElementById("cantdetalles_"+tab).value = cantdetalles;
	}
}
//	------------------------------------------------------------

//	FUNCION PARA SELECCIONAR UNA PERSONA DE UNA LISTA Y COLOCARLO EN OTRA VENTANA
function selListadoEvaluacion(busqueda, cargo, cod, nom, id, idvalor, foto) {
	var registro=document.getElementById("registro").value;
	opener.document.getElementById(cod).value = registro;
	opener.document.getElementById(nom).value = busqueda;
	opener.document.getElementById(id).value = idvalor;
	opener.document.getElementById("nomcargo").value = cargo;
	
	var path = opener.document.getElementById("path").value;
	if (foto == "") var src = path + "blank.png"; else var src = path + foto;
	opener.document.getElementById("img_foto").src = src;
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "fphp_funciones.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=mostrarIncidentesCriticos&codpersona="+registro);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var resp = ajax.responseText;
			var datos = resp.split("||");
			if (datos[0].trim() != "") alert(resp);
			else {
				opener.document.getElementById("listaDetalles_tab1").innerHTML = datos[1];
				opener.document.getElementById("listaDetalles_tab3").innerHTML = datos[2];
				opener.document.getElementById("listaDetalles_tab2").innerHTML = datos[3];
				opener.document.getElementById("listaDetalles_tab2_grafico").innerHTML = datos[4];
				window.close();
			}
		}
	}
}
//	------------------------------------------------------------

//	FUNCION PARA ENVIAR LOS ELEMENTOS DEL FILTRO DE EMPLEADOS
function filtroListadoEmpleadosEvaluacion(form, limit) {
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
			var pagina="listado_empleados_evaluacion.php?filtro="+filtro+"&ordenar="+ordenar+"&limit=0";
			cargarPagina(form, pagina);
		}
	} else {
		form.ffingresod.value="";
		form.ffingresoh.value="";
		msjError(1110);
	}
}
//	------------------------------------------------------------

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
//	------------------------------------------------------------

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
//	------------------------------------------------------------

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
//	------------------------------------------------------------

//	Colocar la descripcion de la meta en el tab7
function setDescripcionMeta(valor, nrodetalles) {
	var span = "meta_tab7_" + nrodetalles;
	document.getElementById(span).innerHTML = valor;
}
//	------------------------------------------------------------

//
function finalizarEvaluacionDesempenio(form, codpersona) {
	if (confirm("¿Está seguro de finalizar la evaluación del empleado?")) {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "fphp_ajax_2.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=EVALUACIONDESEMPENIO&accion=finalizarEvaluacionDesempenio&codpersona="+codpersona);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else form.submit();
			}
		}
	}
}
//	------------------------------------------------------------