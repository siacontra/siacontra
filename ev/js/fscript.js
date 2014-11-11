// JavaScript Document



//	FUNCION PARA SETEAR VALORS POR DEFECTO AL SELECCIONAR A QUIEN VA DIRIGIDO (ALMACEN/COMPRA) EN REQUERIMIENTOS
function setDirigidoA(Clasificacion) {
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=setDirigidoA&Clasificacion="+Clasificacion,
		async: false,
		success: function(resp) {
			var datos = resp.split("|");
			//	--
			if (datos[1].trim() == "A") {
				$("#FlagCompras").removeAttr("checked");
				$("#FlagAlmacen").attr("checked", "checked");
			} else {
				$("#FlagAlmacen").removeAttr("checked");
				$("#FlagCompras").attr("checked", "checked");
			}
			//	--
			if (datos[5] == "S") {
				$("#btCommodity").removeAttr("disabled");
				$("#btItem").attr("disabled", "disabled");
			} else {
				$("#btItem").removeAttr("disabled");
				$("#btCommodity").attr("disabled", "disabled");
			}
			//	--
			$("#TipoRequerimiento").val(datos[2]);
			$("#CodAlmacen").val(datos[3]);
			$("#FlagCommodity").val(datos[5]);
			//	--
			$("#FlagCajaChica").removeAttr("checked");
			$("#lista_detalles").html("");
			$("#nro_detalles").val("0");
			$("#can_detalles").val("0");
			//	--
			$("#CodAlmacen").html(datos[6]);
		}
	});
	//	--
	if (Clasificacion == "STO") {
		$("#ClasificacionOC").attr("disabled", "disabled").val("");
		$("#ProveedorSugerido").val("");
		$("#NomProveedorSugerido").val("");
		$("#btProveedorSugerido").css("visibility", "hidden");
	} else {
		$("#ClasificacionOC").removeAttr("disabled").val("");
		$("#btProveedorSugerido").css("visibility", "visible");
	}
}

//	FUNCION PARA SETEAR VALORS POR DEFECTO AL SELECCIONAR ALMACEN
function setFlagCommodity(CodAlmacen) {
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=setFlagCommodity&CodAlmacen="+CodAlmacen,
		async: false,
		success: function(resp) {
			$("#FlagCommodity").val(resp);
			if (resp.trim() == "S") {
				$("#btCommodity").removeAttr("disabled");
				$("#btItem").attr("disabled", "disabled");
			} else {
				$("#btItem").removeAttr("disabled");
				$("#btCommodity").attr("disabled", "disabled");
			}
			$("#FlagCajaChica").removeAttr("checked");
			$("#lista_detalles").html("");
			$("#nro_detalles").val("0");
			$("#can_detalles").val("0");
		}
	});
}

function abrirRequerimientoListadoCommodities() {
	var href = $("#aCommodity").attr("href");
	var Clasificacion = $("#Clasificacion").val();
	href = "../lib/listas/listado_commodities.php?filtrar=default&ventana=requerimiento_detalles_insertar&PorClasificacion=S&fClasificacion="+Clasificacion+"&iframe=true&width=950&height=525";
	$("#aCommodity").attr("href", href);
	document.getElementById('aCommodity').click();
}

//	
function mostrarTabDistribucionRequerimiento(boo) {
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat(setNumero(n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) CantidadPedida = 0;
			detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "cod_partida") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	if (detalles != "") {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=mostrarTabDistribucionRequerimiento&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				$("#lista_cuentas").html(partes[0]);
				$("#lista_partidas").html(partes[1]);
				if (boo) mostrarTab("tab", 5, 5);
			}
		});
	} else {
		$("#lista_cuentas").html("");
		$("#lista_partidas").html("");
		mostrarTab("tab", 5, 5);
	}
}

//	
function mostrarTabDistribucionOrden() {
	var CodOrganismo = $("#CodOrganismo").val();
	var CodProveedor = $("#CodProveedor").val();
	var Anio = $("#Anio").val();
	var NroOrden = $("#NroOrden").val();
	var Estado = $("#Estado").val();
	var FactorImpuesto = $("#FactorImpuesto").val();
	if (document.getElementById('MontoIGV')) var MontoIGV = setNumero($("#MontoIGV").val());
	else if (document.getElementById('MontoIva')) var MontoIGV = setNumero($("#MontoIva").val());
	
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat(setNumero(n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) CantidadPedida = 0;
			detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") {
			var PrecioUnit = parseFloat(setNumero(n.value));
			if (isNaN(PrecioUnit) || PrecioUnit <= 0) PrecioUnit = 0;
			detalles += PrecioUnit + ";char:td;";
		}
		else if (n.name == "FlagExonerado") {
			if (n.checked) detalles += "S;char:td;"; else detalles += "N;char:td;";
		}
		else if (n.name == "cod_partida") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	if (detalles != "") {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=mostrarTabDistribucionOrden&detalles="+detalles+"&CodOrganismo="+CodOrganismo+"&CodProveedor="+CodProveedor+"&Anio="+Anio+"&NroOrden="+NroOrden+"&FactorImpuesto="+FactorImpuesto+"&Estado="+Estado+"&MontoIGV="+MontoIGV,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				$("#lista_cuentas").html(partes[0]);
				$("#lista_partidas").html(partes[1]);
			}
		});
	} else {
		$("#lista_partidas").html("");
		$("#lista_cuentas").html("");
	}
}

//	cargo ventana despacho de almacen
function cargarOpcionDespachoAlmacen(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_almacen_despacho_form");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------

//	cargo ventana recepcion de almacen
function cargarOpcionRecepcionAlmacen(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
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
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "StockActual" && sel) detalles += n.value + ";char:td;";		
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadRecibida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "PrecioUnit" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "FlagExonerado" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) {
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_almacen_recepcion_form");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------

//	cargo ventana recepcion de almacen
function cargarOpcionRecepcionCommodity(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
	//	detalles documento
	var error = "";
	var detalles = "";
	var sel = false;
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "Secuencia" && n.checked) {
			sel = true;
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CommoditySub" && sel) {
			detalles += n.value + ";char:td;";
			var CodItem = n.value;
		}
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";	
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadRecibida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "PrecioUnit" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "FlagExonerado" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodClasificacion" && sel) {
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_transaccion_commodity_recepcion_form");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------

//	cargo ventana recepcion de almacen
function cargarOpcionDespachoCommodity(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
	//	detalles documento
	var error = "";
	var detalles = "";
	var sel = false;
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "Secuencia" && n.checked) {
			sel = true;
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CommoditySub" && sel) {
			detalles += n.value + ";char:td;";
			var CodItem = n.value;
		}
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";	
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "StockActual" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodInterno" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodRequerimiento" && sel) {
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_transaccion_commodity_despacho_form");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------

//	cargo ventana recepcion de almacen
function cargarOpcionRequerimientoPendiente(frm_detalle) {
	//	detalles documento
	var error = "";
	var detalles = "";
	var sel = false;
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "Secuencia" && n.checked) {
			sel = true;
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CodItem" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion" && sel) detalles += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) detalles += n.value + ";char:td;";		
		else if (n.name == "FlagExonerado" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "cod_partida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "Comentarios" && sel) {
			detalles += changeUrl(n.value) + ";char:tr;";
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
		$("#detalles").val(detalles);
		$("#frmentrada").attr("action", "gehen.php?anz=lg_requerimiento_pendiente_form");
		$("#frmentrada").submit();
	}
}
//	--------------------------------------

//	calcular m onto pendiente
function setSaldoTotalConfirmacionServicio() {	
	//	valores
	var CantidadTotal = new Number(setNumero($("#CantidadTotal").val()));
	var PorcentajeTotal = new Number(setNumero($("#PorcentajeTotal").val()));
	var RecibidaTotal = new Number(setNumero($("#RecibidaTotal").val()));
	var RecibidaPorcentaje = new Number(setNumero($("#RecibidaPorcentaje").val()));
	var PorRecibirTotal = new Number(setNumero($("#PorRecibirTotal").val()));
	//	verifico
	if ((PorRecibirTotal + RecibidaTotal) > CantidadTotal) {
		cajaModal("La <strong>Cantidad Recibida</strong> no puede ser mayor que la <strong>Cantidad Total Pedida</strong>", "error", 400);
		PorRecibirTotal = CantidadTotal - RecibidaTotal;
		$("#PorRecibirTotal").val(setNumeroFormato(PorRecibirTotal, 2, '.', ','));
	}
	//	calculo
	var PorRecibirPorcentaje = PorRecibirTotal * 100 / CantidadTotal;
	var SaldoTotal = RecibidaTotal + PorRecibirTotal;
	var SaldoPorcentaje = RecibidaPorcentaje + PorRecibirPorcentaje;
	//	imprimo
	$("#PorRecibirPorcentaje").val(setNumeroFormato(PorRecibirPorcentaje, 2, '.', ','));
	$("#SaldoTotal").val(setNumeroFormato(SaldoTotal, 2, '.', ','));
	$("#SaldoPorcentaje").val(setNumeroFormato(SaldoPorcentaje, 2, '.', ','));
}
//	--------------------------------------

//	cargo lineas para la ficha de activos fijo en recepcion de commodities
function setActivosFijosAsociados(set) {
	//	valores
	var CodUbicacion = $("#CodUbicacion").val();
	var CodCentroCosto = $("#CodCentroCosto").val();
	var CodOrganismo = $("#CodOrganismo").val();
	var FechaDocumento = $("#FechaDocumento").val();
	var ReferenciaNroDocumento = $("#ReferenciaNroDocumento").val();
	
	//	detalles
	var error = "";
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = new Number(n.value);
			detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "CantidadPendiente") {
			var CantidadPendiente = new Number(n.value);
			detalles += CantidadPendiente + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (CantidadRecibida > CantidadPendiente) {
				n.value = CantidadPendiente;
				error = "La Cantidad por Recepcionar no puede ser mayor de <strong>" + CantidadPendiente + "</strong>";
				break;
			}
			else if (isNaN(CantidadRecibida)) {
				n.value = CantidadPendiente;
				error = "Formato Incorrecto en la Cantidad por Recepcionar";
				break;
			}
			else if (CantidadRecibida <= 0) {
				n.value = CantidadPendiente;
				error = "La Cantidad por Recepcionar no puede ser menor o igual a cero";
				break;
			}
			else detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodClasificacion") detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	activos
	var activos = "";
	var frm_activos = document.getElementById("frm_activos");
	for(var i=0; n=frm_activos.elements[i]; i++) {
		if (n.name == "CommoditySub") activos += n.value + ";char:td;";
	}
	var len = activos.length; len-=9;
	activos = activos.substr(0, len);
	
	//	valido
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		if (activos == "" || !set) {
			//	ajax
			$.ajax({
				type: "POST",
				url: "lib/fphp_funciones_ajax.php",
				data: "accion=setActivosFijosAsociados&detalles="+detalles+"&CodCentroCosto="+CodCentroCosto+"&CodUbicacion="+CodUbicacion+"&CodOrganismo="+CodOrganismo+"&FechaDocumento="+FechaDocumento+"&ReferenciaNroDocumento="+ReferenciaNroDocumento,
				async: false,
				success: function(resp) {
					$("#lista_activos").html(resp);
					if (set) mostrarTab('tab', 3, 3);
				}
			});
		} else mostrarTab('tab', 3, 3);
	}
}
//	--------------------------------------

//	cargo ventana recepcion de caja chica
function cargarOpcionRecepcionCajaChica(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
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
		else if (n.name == "CommoditySub" && sel) {
			detalles += n.value + ";char:td;";
			var CommoditySub = n.value;
		}
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";	
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) {
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_transaccion_cajachica_recepcion");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------

//	cargo ventana despacho de caja chica
function cargarOpcionDespachoCajaChica(frm_detalle) {
	//	datos generales
	var CodRequerimiento = $("#registro").val();
	var CodOrganismo = $("#fCodOrganismo").val();
	
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
		else if (n.name == "CommoditySub" && sel) {
			detalles += n.value + ";char:td;";
			var CommoditySub = n.value;
		}
		else if (n.name == "Descripcion" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad" && sel) detalles += n.value + ";char:td;";	
		else if (n.name == "CantidadPedida" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente" && sel) detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto" && sel) {
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
		//	ajax (verifico si el periodo actual esta abierto)
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=periodoAbierto&CodOrganismo="+CodOrganismo,
			async: false,
			success: function(resp) {
				if (resp != "") cajaModal(resp, "error", 400);
				else {
					$("#detalles").val(detalles);
					$("#frmentrada").attr("action", "gehen.php?anz=lg_transaccion_cajachica_despacho");
					$("#frmentrada").submit();
				}
			}
		});
	}
}
//	--------------------------------------