// JavaScript Document

//	FUNCION PARA SETEAR VALORS POR DEFECTO AL SELECCIONAR A QUIEN VA DIRIGIDO (ALMACEN/COMPRA) EN REQUERIMIENTOS
function setDirigidoA(clasificacion) {
	/*
	.-clasificacion (valor del campo clasificacion)
	*/
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "../lib/lg_fphp_funciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=setDirigidoA&clasificacion="+clasificacion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText;
			var datos = resp.split(".");
			if (datos[1].trim() == "A") document.getElementById("flagalmacen").checked = true;
			else document.getElementById("flagcompras").checked = true;
			
			if (datos[2] == "01") {
				document.getElementById("btItem").disabled = false;
				document.getElementById("btCommodity").disabled = true;
			} else {
				document.getElementById("btItem").disabled = true;
				document.getElementById("btCommodity").disabled = false;
			}			
			document.getElementById("tiporequerimiento").value = datos[2];
			document.getElementById("almacen").value = datos[1];
			if (datos[3] == "S") document.getElementById("cajachica").style.display = "block";
			else document.getElementById("cajachica").style.display = "none";
			document.getElementById("cajachica").checked = false;			
			document.getElementById("lista_detalle").innerHTML = "";
			document.getElementById("can_detalle").value = "0";
			document.getElementById("nro_detalle").value = "0";
		}
	}
	if (clasificacion == "STO") {
		document.getElementById("codproveedor").value = "";
		document.getElementById("nomproveedor").value = "";
		document.getElementById("btProveedor").disabled = true;
		document.getElementById("codproveedor").disabled = true;
	}
	else {
		document.getElementById("btProveedor").disabled = false;
		document.getElementById("codproveedor").disabled = false;
	}
}

//	FUNCION PARA ABRIR UNA LISTA PARA DESPUES INSERTAR EN LA LISTA QUE LO LLAMO
function abrirListadoInsertarItem(detalle, opcion, accion, php, param, tipo) {
	var clasificacion = document.getElementById("clasificacion").value;
	if (tipo == "item") var lista = "listado_items";
	else if (tipo == "commodity") var lista = "listado_commodities";
	window.open("../lib/"+lista+".php?detalle=detalle&opcion="+opcion+"&accion="+accion+"&php="+php+"&tipo="+tipo+"&clasificacion="+clasificacion, "wlista", "toolbar=no, menubar=no, location=no, scrollbars=yes, resizable=no, " + param);
}
//	--------------------------------------

//	FUNCION PARA ABRIR UNA LISTA PARA DESPUES INSERTAR EN LA LISTA QUE LO LLAMO
function abrirListadoInsertarItemOC(detalle, opcion, accion, php, param, tipo) {
	var clasificacion = document.getElementById("tipoclasificacion").value;
	if (tipo == "item") var lista = "listado_items";
	else if (tipo == "commodity") var lista = "listado_commodities";
	window.open("../lib/"+lista+".php?detalle=detalle&opcion="+opcion+"&accion="+accion+"&php="+php+"&tipo="+tipo+"&clasificacion="+clasificacion, "wlista", "toolbar=no, menubar=no, location=no, scrollbars=yes, resizable=no, " + param);
}
//	--------------------------------------

//	FUNCION PARA ABRIR UNA LISTA PARA DESPUES INSERTAR EN LA LISTA QUE LO LLAMO
function abrirListadoInsertarItemOS(detalle, opcion, accion, php, param, tipo) {
	window.open("../lib/listado_commodities.php?detalle=detalle&clasificacion=SER&opcion="+opcion+"&accion="+accion+"&php="+php, "wlista", "toolbar=no, menubar=no, location=no, scrollbars=yes, resizable=no, " + param);
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosStock(rows, admin, insert, update, del) {
	if (document.getElementById("rows")) {
		var numreg = document.getElementById("rows");
		numreg.innerHTML = "Registros: "+rows;
	}
		
	if (!rows) {
		if (document.getElementById("btVer_stock")) document.getElementById("btVer_stock").disabled = true;
		if (document.getElementById("btCuadro_stock")) document.getElementById("btCuadro_stock").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btInvitar_stock")) document.getElementById("btInvitar_stock").disabled = true;
		if (document.getElementById("btCotizar_stock")) document.getElementById("btCotizar_stock").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosCommodity(rows, admin, insert, update, del) {
	if (document.getElementById("rows")) {
		var numreg = document.getElementById("rows");
		numreg.innerHTML = "Registros: "+rows;
	}
		
	if (!rows) {
		if (document.getElementById("btVer_comm")) document.getElementById("btVer_comm").disabled = true;
		if (document.getElementById("btCuadro_comm")) document.getElementById("btCuadro_comm").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btInvitar_comm")) document.getElementById("btInvitar_comm").disabled = true;
		if (document.getElementById("btCotizar_comm")) document.getElementById("btCotizar_comm").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosCompras(rows, admin, insert, update, del) {
	if (document.getElementById("rows")) {
		var numreg = document.getElementById("rows");
		numreg.innerHTML = "Registros: "+rows;
	}
	
	if (update == "N" || !rows) {
		if (document.getElementById("btGenerar_compra")) document.getElementById("btGenerar_compra").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosServicios(rows, admin, insert, update, del) {
	if (document.getElementById("rows")) {
		var numreg = document.getElementById("rows");
		numreg.innerHTML = "Registros: "+rows;
	}
	
	if (update == "N" || !rows) {
		if (document.getElementById("btGenerar_servicio")) document.getElementById("btGenerar_servicio").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosConfirmarOS(rows, admin, insert, update, del) {
	if (document.getElementById("rows_confirmar")) {
		var numreg = document.getElementById("rows_confirmar");
		numreg.innerHTML = "Registros: "+rows;
	}
		
	if (!rows) {
		if (document.getElementById("btConfirmar")) document.getElementById("btConfirmar").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btConfirmar")) document.getElementById("btConfirmar").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosDesconfirmarOS(rows, admin, insert, update, del) {
	if (document.getElementById("rows_desconfirmar")) {
		var numreg = document.getElementById("rows_desconfirmar");
		numreg.innerHTML = "Registros: "+rows;
	}
		
	if (!rows) {
		if (document.getElementById("btDesconfirmar")) document.getElementById("btDesconfirmar").disabled = true;
	}
	if (update == "N" || !rows) {
		if (document.getElementById("btDesconfirmar")) document.getElementById("btDesconfirmar").disabled = true;
	}
}
//	--------------------------------------

//	FUNCION PARA TOTALIZAR LOS MONTOS DE LAS COTIZACIONES DE LOS PROVEEDORES POR ITEM (lg_cotizaciones_invitar_cotizar)
function setMontosProveedorItem() {
	//	montos
	var minimo = new Number(0.0000);
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codproveedor") var id = n.value;
		else if (n.name == "cant") var cant = new Number(n.value);
		else if (n.name == "pu") var pu = new Number(n.value);
		else if (n.name == "flagexon") {
			if (n.checked) var igv = new Number(0.0000);
			else var igv = new Number(n.value);
		}
		else if (n.name == "pu_igv") {
			var pu_igv = pu + (pu * igv / 100);
			n.value = setNumeroFormato(pu_igv, 4, "", ".");
		}
		else if (n.name == "descp") var descp = new Number(n.value);
		else if (n.name == "descf") var descf = new Number(n.value);
		else if (n.name == "pu_total") {
			if (descp > 0) var pu_descuento = pu - (pu * descp / 100);
			else if (descf > 0) var pu_descuento = pu - descf;
			else var pu_descuento = pu;			
			var pu_total = pu_descuento + (pu_descuento * igv / 100);			
			n.value = setNumeroFormato(pu_total, 4, "", ".");
		}
		else if (n.name == "total") { 
			pu_total2=new Number(setNumeroFormato(pu_total, 4, "", "."));
			var total = pu_total2 * cant;
			n.value = setNumeroFormato(total, 4, "", ".");
		}
		else if (n.name == "comparar") n.value = setNumeroFormato(pu_total, 4, "", ".");
		else if (n.name == "flagmejor") {
			n.checked = false;
			if ((minimo == 0 && pu_total > 0) || (pu_total <= minimo && pu_total > 0)) {
				var idminimo = id;
				minimo = pu_total;
			}
		}
	}
	
	//	asigno la mejor oferta
	var idflagmejor = "flagmejor_" + idminimo;
	var idflagasig = "flagasig_" + idminimo;
	if (document.getElementById(idflagmejor)) document.getElementById(idflagmejor).checked = true;
	if (document.getElementById(idflagasig)) document.getElementById(idflagasig).checked = true;
}
//	--------------------------------------

//	FUNCION PARA TOTALIZAR LOS MONTOS DE LAS COTIZACIONES DE UN PROVEEDOR (lg_cotizaciones_invitaciones_cotizar)
function setMontosItemProveedor() {
	//	montos
	var minimo = new Number(0.0000);
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "cotizacion_secuencia") var id = n.value;
		else if (n.name == "cant") var cant = new Number(n.value);
		else if (n.name == "pu") var pu = new Number(n.value);
		else if (n.name == "flagexon") {
			if (n.checked) var igv = new Number(0.0000);
			else var igv = new Number(n.value);
		}
		else if (n.name == "pu_igv") {
			var pu_igv = pu + (pu * igv / 100);
			n.value = setNumeroFormato(pu_igv, 4, "", ".");
		}
		else if (n.name == "descp") var descp = new Number(n.value);
		else if (n.name == "descf") var descf = new Number(n.value);
		else if (n.name == "pu_desc") {
			if (descp > 0) var pu_descuento = pu - (pu * descp / 100);
			else if (descf > 0) var pu_descuento = pu - descf;
			else var pu_descuento = pu;
			n.value = setNumeroFormato(pu_descuento, 4, "", ".");
		}
		else if (n.name == "pu_total") {
			var pu_total = pu_descuento + (pu_descuento * igv / 100);			
			n.value = setNumeroFormato(pu_total, 4, "", ".");
		}
		else if (n.name == "total") {
			var total = pu_total * cant;
			n.value = setNumeroFormato(total, 4, "", ".");
		}
	}
}
//	--------------------------------------

//	funcion para mostrar la ventana de disponibilidad presupuestaria
function verDisponibilidadItems(tipoorden, form) {
	var anio = document.getElementById("anio").value;
	var nroorden = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var total_impuesto = new Number(setNumero(document.getElementById("monto_impuestos").value));
	
	//	recorro los detalles para obtener los valores de la partida
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "cantidad") {
			var cant = new Number(n.value);
			detalles += cant + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(n.value);
			detalles += pu + "|";
		}
		else if (n.name == "descp") {
			var descp = new Number(n.value);
			detalles += descp + "|";
		}
		else if (n.name == "descf") {
			var descf = new Number(n.value);
			detalles += descf + "|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalles += "S|"; else detalles += "N|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡No se han ingresado partidas todavia!");
	else {
		if (document.getElementById("btCommodity").disabled) var tabla = "item"; else var tabla = "commodity";	
		window.open("lg_disponibilidad_presupuestaria_ordenes.php?detalles="+detalles+"&tabla="+tabla+"&anio="+anio+"&organismo="+organismo+"&total_impuesto="+total_impuesto+"&nroorden="+nroorden+"&tipoorden="+tipoorden+"&codproveedor="+codproveedor, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=550, width=1100, left=50, top=50, resizable=yes");
	}
}
//	--------------------------------------

//	funcion para mostrar la ventana de disponibilidad presupuestaria
function verDisponibilidadServicios(form) {
	var anio = document.getElementById("anio").value;
	var nroorden = document.getElementById("codigo").value;
	var organismo = document.getElementById("organismo").value;
	var total_impuesto = new Number(document.getElementById("monto_impuestos").value);
	
	//	recorro los detalles para obtener los valores de la partida
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "cantidad") {
			var cant = new Number(n.value);
			detalles += cant + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(n.value);
			detalles += pu + "|0|0|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalles += "S|"; else detalles += "N|";
		}
		else if (n.name == "codpartida") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") alert("¡No se han ingresado partidas todavia!");
	else {
		var tabla = "commodity";
		var tipoorden = "OS";
		window.open("lg_disponibilidad_presupuestaria_ordenes.php?detalles="+detalles+"&tabla="+tabla+"&anio="+anio+"&organismo="+organismo+"&total_impuesto="+total_impuesto+"&nroorden="+nroorden+"&tipoorden="+tipoorden, "", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=550, width=1100, left=50, top=50, resizable=yes");
	}
}
//	--------------------------------------

//	FUNCION PARA TOTALIZAR LOS MONTOS DE LAS COMPRAS (lg_compras)
function setMontosCompras() {
	var monto_afecto = new Number(0.0000);
	var monto_noafecto = new Number(0.0000);
	var monto_bruto = new Number(0.0000);
	var monto_impuestos = new Number(0.0000);
	var monto_total = new Number(0.0000);
	
	//	montos
	var minimo = new Number(0.00);
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "cantidad") var cantidad = new Number(n.value);
		else if (n.name == "pu") var pu = new Number(n.value);
		else if (n.name == "descp") var descp = new Number(n.value);
		else if (n.name == "descf") var descf = new Number(n.value);
		else if (n.name == "flagexon") {
			if (n.checked) var igv = new Number(0.0000);
			else var igv = new Number(n.value);
		}
		else if (n.name == "pu_total") {
			if (descp > 0) var pu_descuento = pu - (pu * descp / 100);
			else if (descf > 0) var pu_descuento = pu - descf;
			else var pu_descuento = pu;			
			var monto_igv = pu_descuento * igv / 100;
			var pu_total = pu_descuento + monto_igv;
			n.value = setNumeroFormato(pu_total, 4, "", ".");
		}
		else if (n.name == "total") {
			var total = pu_total * cantidad;
			n.value = setNumeroFormato(total, 4, "", ".");
			//
			if (igv > 0) monto_afecto += (pu_descuento * cantidad);
			else monto_noafecto += (pu_descuento * cantidad);
			monto_impuestos += (monto_igv * cantidad);
		}
	}
	
	monto_bruto = monto_afecto + monto_noafecto;
	monto_total = monto_bruto + monto_impuestos;
	document.getElementById("monto_afecto").value = setNumeroFormato(monto_afecto, 4, "", ".");
	document.getElementById("monto_noafecto").value = setNumeroFormato(monto_noafecto, 4, "", ".");
	document.getElementById("monto_bruto").value = setNumeroFormato(monto_bruto, 4, "", ".");
	document.getElementById("monto_impuestos").value = setNumeroFormato(monto_impuestos, 4, "", ".");
	document.getElementById("monto_total").value = setNumeroFormato(monto_total, 4, "", ".");
	document.getElementById("monto_pendiente").value = setNumeroFormato(monto_total, 4, "", ".");
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaCompra(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var nro = "nro_" + detalle;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") alert("¡Debe seleccionar una linea!");
	else {
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		document.getElementById(sel).value = "";
		
		if (candetalle == 0) {
			document.getElementById(nro).value = "0";
			document.getElementById("tipoclasificacion").value = "";
			document.getElementById("btItem").disabled = false;
			document.getElementById("btCommodity").disabled = false;
			
		}
	}
	boton.disabled = false;
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaServicio(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var nro = "nro_" + detalle;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") alert("¡Debe seleccionar una linea!");
	else {
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		document.getElementById(sel).value = "";
		
		if (candetalle == 0) document.getElementById(nro).value = "0";
	}
	boton.disabled = false;
}
//	--------------------------------------

//	FUNCION PARA TOTALIZAR LOS MONTOS DE LOS SERVICIOS (lg_servicios)
function setMontosServicios() {
	var monto_original = new Number(0.0000);
	var monto_impuestos = new Number(0.0000);
	var monto_total = new Number(0.0000);
	var monto_gastado = new Number(setNumero(document.getElementById("monto_gastado").value));
	var monto_pendiente = new Number(0.0000);
	
	//	montos
	var minimo = new Number(0.0000);
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "cantidad") var cantidad = new Number(n.value);
		else if (n.name == "pu") var pu = new Number(n.value);
		else if (n.name == "flagexon") {
			if (n.checked) var igv = new Number(0.0000);
			else var igv = new Number(n.value);
		}
		else if (n.name == "total") {
			var monto_igv = pu * igv / 100;
			var pu_total = pu + monto_igv;
			var total = pu_total * cantidad;
			n.value = setNumeroFormato(total, 4, "", ".");
			//
			monto_original += (pu * cantidad);
			monto_impuestos += (monto_igv * cantidad);
		}
	}
	
	monto_total = monto_original + monto_impuestos;
	monto_pendiente = monto_total - monto_gastado;
	document.getElementById("monto_original").value = setNumeroFormato(monto_original, 4, "", ".");
	document.getElementById("monto_impuestos").value = setNumeroFormato(monto_impuestos, 4, "", ".");
	document.getElementById("monto_total").value = setNumeroFormato(monto_total, 4, "", ".");
	document.getElementById("monto_pendiente").value = setNumeroFormato(monto_pendiente, 4, "", ".");
}
//	--------------------------------------

//	actualizar cantidades confirmacion de servicios
function setCantidadPorRecibir(porrecibir) {
	var cantidad_recibir = new Number(porrecibir);
	var cantidad_pendiente = new Number(document.getElementById("cantidad_pendiente").value);
	var cantidad_pedida = new Number(document.getElementById("cantidad_pedida").value);
	var cantidad_recibida =  new Number(document.getElementById("cantidad_recibida").value);
	var saldo =  new Number(document.getElementById("saldo").value);
	
	if ((cantidad_recibida + cantidad_recibir) > cantidad_pedida) {
		alert("ERROR: La cantidad por recibir no puede ser mayor a " + cantidad_pendiente);
		document.getElementById("cantidad_recibir").value = setNumeroFormato(cantidad_pendiente, 4, "", ".");
	} 
	else if (cantidad_recibir == 0) {
		alert("ERROR: La cantidad por recibir no puede ser 0");
		document.getElementById("cantidad_recibir").value = setNumeroFormato(cantidad_pendiente, 4, "", ".");
	} 
	else {
		saldo = cantidad_pedida - cantidad_recibida - cantidad_recibir;
		psaldo = saldo * 100 / cantidad_pedida;
		pcantidad_recibir = cantidad_recibir * 100 / cantidad_pedida;
		
		document.getElementById("pcantidad_recibir").value = setNumeroFormato(pcantidad_recibir, 4, "", ".");
		document.getElementById("saldo").value = setNumeroFormato(saldo, 4, "", ".");
		document.getElementById("psaldo").value = setNumeroFormato(psaldo, 4, "", ".");
	}
}
//	--------------------------------------


function quitarEstiloGrupo()
{
	xGetElementById.style= '';
}

//	FUNCION QUE CAMBIA DE COLOR LA FILA DE UNA TABLA AL HACER CLICK EL PUNTERO DEL MOUSE SOBRE ELLA (MULTI SELECCION)
function mClkMultiReque(src,t) {
	//	Obtengo el id para seleccionar el check
	var fila = src.id.split("_");
	var id = fila[1];
	
	//	Obtengo la fila y le cambio el color
	var row = document.getElementById(src.id);
	if (row.className == "trListaBodySel") {
		row.className= ""+estiloGrupo(t)+" trListaBody";
		document.getElementById(id).checked = false;
	} else {
		row.className="trListaBodySel";
		document.getElementById(id).checked = true;
	}
}
//	--------------------------------------

function estiloGrupo(j)
{
	mod = j;
	
	if(mod % 2 == 0)
	{
   		estilo = "estiloGrupo1";
		
	} else {
		
       	estilo = "estiloGrupo2";
	}
	
	return estilo;
} 
