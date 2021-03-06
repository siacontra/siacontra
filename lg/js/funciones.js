// JavaScript Document


//	calculos los montos de la orden a partir de los detalles
function setMontosOrdenCompra(frm_detalles) {
	//	datos generales
	var FactorImpuesto = $("#FactorImpuesto").val();
	
	//	detalles
	var Impuesto = new Number(0.0000);
	var Total = new Number(0.0000);
	var MontoDescuentoPorcentaje = new Number(0.0000);
	var MontoDescuentoFijo = new Number(0.0000);
	var MontoAfecto = new Number(0.0000);
	var MontoNoAfecto = new Number(0.0000);
	var MontoIGV = new Number(0.0000);
	var MontoTotal = new Number(0.0000);
	var MontoBruto= new Number(0.0000);
	var PrecioUnitTotal= new Number(0.0000);
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CantidadPedida") var CantidadPedida = new Number(n.value);
		else if (n.name == "PrecioUnit") var PrecioUnit = new Number(n.value);
		else if (n.name == "DescuentoPorcentaje") var DescuentoPorcentaje = new Number(n.value);
		else if (n.name == "DescuentoFijo") var DescuentoFijo = new Number(n.value);
		else if (n.name == "FlagExonerado") {
			if (n.checked) var FlagExonerado = "S"; else var FlagExonerado = "N";
		}
		else if (n.name == "PrecioUnitTotal") {
			MontoDescuentoPorcentaje = PrecioUnit * DescuentoPorcentaje / 100;
			MontoDescuentoFijo = DescuentoFijo;
			PrecioUnitTotal = PrecioUnit - MontoDescuentoPorcentaje - MontoDescuentoFijo;
			if (FlagExonerado == "S") {
				Impuesto = new Number(0.0000);
				MontoNoAfecto += (PrecioUnitTotal  * CantidadPedida);
			}
			else {
				Impuesto = new Number(PrecioUnitTotal * FactorImpuesto / 100);
				MontoAfecto += (PrecioUnitTotal * CantidadPedida);
			}
			PrecioUnitTotal = PrecioUnitTotal + Impuesto;
			n.value = setNumeroFormato(PrecioUnitTotal, 4, '', '.');
		}
		else if (n.name == "Total") {
			Total = new Number(PrecioUnitTotal * CantidadPedida);
			n.value = setNumeroFormato(Total, 4, '', '.');
		}
	}
	MontoIGV = MontoAfecto * FactorImpuesto / 100;
	MontoBruto = new Number(MontoAfecto + MontoNoAfecto);
	MontoTotal = MontoBruto + MontoIGV;
	
	//	totales
	$("#MontoAfecto").val(setNumeroFormato(MontoAfecto, 4, '', '.'));
	$("#MontoNoAfecto").val(setNumeroFormato(MontoNoAfecto, 4, '', '.'));
	$("#MontoBruto").val(setNumeroFormato(MontoBruto, 4, '', '.'));
	$("#MontoIGV").val(setNumeroFormato(MontoIGV, 4, '', '.'));
	$("#MontoTotal").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	$("#MontoPendiente").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	mostrarTabDistribucionOrden();
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaOrdenCompra(boton, detalle, form) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") cajaModal("Debe seleccionar una linea", "error", 400);
	else {
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		document.getElementById(sel).value = "";
		setMontosOrdenCompra(form);
		$("#TipoClasificacion").val("");
	}
	boton.disabled = false;
}
//	--------------------------------------

//	calculos los montos de la orden a partir de los detalles
function setMontosOrdenServicio(frm_detalles) {
	//	datos generales
	var FactorImpuesto = $("#FactorImpuesto").val();
	
	//	detalles
	var Impuesto = new Number(0.0000);
	var Total = new Number(0.0000);
	var MontoOriginal = new Number(0.0000);
	var MontoAfecto = new Number(0.0000);
	var MontoNoAfecto = new Number(0.0000);
	var MontoIva = new Number(0.0000);
	var TotalMontoIva = new Number(0.0000);
	var MontoBruto= new Number(0.0000);
	var PrecioUnitTotal= new Number(0.0000);
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		var PrecioUnitTotal = new Number(0.0000);
		if (n.name == "CantidadPedida") var CantidadPedida = new Number(n.value);
		else if (n.name == "PrecioUnit") var PrecioUnit = new Number(n.value);
		else if (n.name == "FlagExonerado") {
			if (n.checked) var FlagExonerado = "S"; else var FlagExonerado = "N";
		}
		else if (n.name == "Total") {
			if (FlagExonerado == "N") {
				Impuesto = PrecioUnit * FactorImpuesto / 100; 
				MontoOriginal += (PrecioUnit * CantidadPedida)
			} else {
				var Impuesto = 0;
				MontoNoAfecto += (PrecioUnit * CantidadPedida)
			}
			PrecioUnitTotal = PrecioUnit + Impuesto;
			Total = new Number(PrecioUnitTotal * CantidadPedida);
			n.value = setNumeroFormato(Total, 4, '', '.');
		}
	}
	MontoIva = MontoOriginal * FactorImpuesto / 100;
	MontoBruto = new Number(MontoOriginal + MontoNoAfecto);
	TotalMontoIva = MontoBruto + MontoIva;
	
	//	totales
	$("#MontoOriginal").val(setNumeroFormato(MontoOriginal, 4, '', '.'));
	$("#MontoNoAfecto").val(setNumeroFormato(MontoNoAfecto, 4, '', '.'));
	$("#MontoIva").val(setNumeroFormato(MontoIva, 4, '', '.'));
	$("#TotalMontoIva").val(setNumeroFormato(TotalMontoIva, 4, '', '.'));
	$("#MontoPendiente").val(setNumeroFormato(TotalMontoIva, 4, '', '.'));
	mostrarTabDistribucionOrden();
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaOrdenServicio(boton, detalle, form) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") cajaModal("Debe seleccionar una linea", "error", 400);
	else {
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		document.getElementById(sel).value = "";
		setMontosOrdenServicio(form);
	}
	boton.disabled = false;
}
//	--------------------------------------

//	
function verDisponibilidadPresupuestaria() {
	var CodOrganismo = $("#CodOrganismo").val();
	var CodProveedor = $("#CodProveedor").val();
	var Anio = $("#Anio").val();
	var NroOrden = $("#NroOrden").val();
	var Estado = $("#Estado").val();
	var FactorImpuesto = $("#FactorImpuesto").val();
	
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat((n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) CantidadPedida = 0;
			detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") {
			var PrecioUnit = parseFloat((n.value));
			if (isNaN(PrecioUnit) || PrecioUnit <= 0) PrecioUnit = 0;
			detalles += PrecioUnit + ";char:td;";
		}
		else if (n.name == "FlagExonerado") {
			if (n.checked) detalles += "S;char:td;"; else detalles += "N;char:td;";
		}
		else if (n.name == "cod_partida") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	detalles distribucion
	var detalles_partida = "";
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "cod_partida") {
			var _cod_partida = n.value;
			detalles_partida += n.value + ";char:td;";
		}
		else if (n.name == "CodCuenta") detalles_partida += n.value + ";char:td;";
		else if (n.name == "Monto") detalles_partida += n.value + ";char:td;";
		else if (n.name == "MontoDisponible") detalles_partida += n.value + ";char:td;";
		else if (n.name == "MontoPendiente") detalles_partida += n.value + ";char:tr;";
	}
	var len = detalles_partida.length; len-=9;
	detalles_partida = detalles_partida.substr(0, len);
	
	window.open("gehen.php?anz=lg_orden_disponibilidad_presupuestaria&detalles="+detalles+"&detalles_partida="+detalles_partida+"&CodOrganismo="+CodOrganismo+"&CodProveedor="+CodProveedor+"&Anio="+Anio+"&NroOrden="+NroOrden+"&FactorImpuesto="+FactorImpuesto+"&Estado="+Estado, "lg_orden_disponibilidad_presupuestaria", "toolbar=no, menubar=no, location=no, scrollbars=yes, ");
}
//	--------------------------------------

//	FUNCION PARA CARGAR UNA NUEVA PAGINA mmm
function generarRequerimientoPendiente(frm_detalle) {
	//	detalles documento
	var error = "";
	var detalles = "";
	var sel = false;
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "Secuencia" && n.checked) {
			sel = true;
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CodItem" && sel) {
			detalles += n.value + ";char:td;";
			var CodItem = n.value;
		}
		else if (n.name == "CodInterno" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "StockActual" && sel) {
			var StockActual = new Number(n.value);
			if (StockActual == 0) { error = "No puede Despachar Items con <strong>Stock en Cero</strong>"; break; }
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "EnTransito" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "FlagCompraAlmacen" && sel) {
			detalles += n.value + ";char:tr;";
			sel = false;
		}
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	if (detalles == "") cajaModal("Debe seleccionar por lo menos un registro", "error", 400);
	else if (error != "") {
		cajaModal(error, "error", 400);
	}
	else {
		$("#registro").val(detalles);
		$("#frmentrada").submit();
	}
}
//	--------------------------------------

//	FUNCION QUE IMPRIME EL NUMERO DE REGISTROS Y BLOQUEA/DESBLOQUEA LAS OPCIONES
function totalRegistrosGenerarOrden(rows_oc, rows_os, admin, insert, update, del) {
	//	
	var numreg_oc = document.getElementById("rows_oc");
	numreg_oc.innerHTML = "Registros: "+rows_oc;
	
	var numreg_os = document.getElementById("rows_os");
	numreg_os.innerHTML = "Registros: "+rows_os;
	//	
	if (insert == "N" || update == "N" || rows_oc == '0') {
		$("#btGenerarOC").attr("disabled","disabled");
	}
	if (insert == "N" || update == "N" || rows_os == '0') {
		$("#btGenerarOS").attr("disabled","disabled");
	}
}
//	--------------------------------------

//	calculos los montos an transaccion en almacen a partir de los detalles
function setMontosAlmacen(frm_detalles) {
	//	detalles
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CantidadRecibida") var CantidadRecibida = new Number((n.value));
		else if (n.name == "PrecioUnit") var PrecioUnit = new Number((n.value));
		else if (n.name == "Total") {
			var Total = new Number(PrecioUnit * CantidadRecibida);
			n.value = setNumeroFormato(Total, 4, '', '.');
		}
	}
}
//	--------------------------------------

//	bloqueo/desbloqueo preio unitario en transaccion en almacen
function setFlagManualAlmacen(boo) {
	if (boo) $(".cell.PrecioUnit").removeAttr("disabled");
	else $(".cell.PrecioUnit").attr("disabled", "disabled");
	$(".cell.PrecioUnit").val("0,0000");
	setMontosAlmacen(document.getElementById("frm_detalles"));
}
//	--------------------------------------

//	si al conformar el requerimiento tildo para caja chica selecciono compras/almacen
function setDirigidoACC(boo) {
	if (boo) $("#FlagCompras").attr("checked", "checked");
	else {
		var TipoClasificacion = $("#TipoClasificacion").val();
		if (TipoClasificacion == "C") $("#FlagCompras").attr("checked", "checked");
		else $("#FlagAlmacen").attr("checked", "checked");
	}
}
//	--------------------------------------

//	funcion para abrir el reporte de transaccion de almacen (recepcion/despacho)
function imprimir_transaccion_almacen(form) {
	//alert('por aqui');
	var registro = $('#registro').val();
	var partes = registro.split(".");
	if (partes[3] == "I") var pdf = "lg_transaccion_almacen_recepcion_pdf.php?";
	else if (partes[3] == "T") var pdf = "lg_transaccion_almacen_recepcion_pdf.php?";
	else if (partes[3] == "E") var pdf = "lg_transaccion_almacen_despacho_pdf.php?";
	cargarOpcion2(form, pdf, 'BLANK', 'height=800, width=750, left=200, top=200, resizable=no', $('#registro').val());
}
//	--------------------------------------

//	calculo los valores generales si modifico el iva manualmente solamente
function setNuevoMontoIvaOC() {
	var MontoAfecto = new Number(parseFloat(($("#MontoAfecto").val())));
	var MontoNoAfecto = new Number(parseFloat(($("#MontoNoAfecto").val())));
	var MontoBruto = new Number(parseFloat(($("#MontoBruto").val())));
	var MontoIGV = new Number(parseFloat(($("#MontoIGV").val())));
	var MontoTotal = MontoBruto + MontoIGV;
	$("#MontoTotal").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	$("#MontoPendiente").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	mostrarTabDistribucionOrden()
}
//	--------------------------------------
//	calculo los valores generales si modifico el iva manualmente solamente
function setNuevoMontoIvaOS() {
	var MontoAfecto = new Number(parseFloat(($("#MontoOriginal").val())));
	var MontoNoAfecto = new Number(parseFloat(($("#MontoNoAfecto").val())));
	var MontoIGV = new Number(parseFloat(($("#MontoIva").val())));
	var MontoTotal = MontoAfecto + MontoNoAfecto + MontoIGV;
	$("#TotalMontoIva").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	$("#MontoPendiente").val(setNumeroFormato(MontoTotal, 4, '', '.'));
	mostrarTabDistribucionOrden()
}
//	--------------------------------------

function limitarCaracteres(id,limite)
{
	var elemento= xGetElementById(id);
	
    var totalMensaje = elemento.value.length;
	
    if (totalMensaje > limite) 
	{
        elemento.value = elemento.value.substring(0, limite);
        totalMensaje = elemento.value.length;
    }
   
}
