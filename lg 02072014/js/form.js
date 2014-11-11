// JavaScript DocumentS


//	item
function items(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodTipoItem" || n.id == "Descripcion" || n.id == "CodUnidad" || n.id == "_CodLinea" || n.id == "CodInterno" || n.id == "CodProcedencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valCodigo(n.value) && n.id == "CodItem") { error = "Formato de Codigo Interno incorrecto"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "StockMax") { error = "Monto de stock maximo incorrecto"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "StockMin") { error = "Monto de stock minimo incorrecto"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=items&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	commodity
function commodity(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "Descripcion" || n.id == "Clasificacion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles
	var detalles = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "Codigo") detalles += n.value + "|";
		else if (n.name == "CommoditySub") detalles += n.value + "|";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value.trim()) + "|";
		else if (n.name == "cod_partida") detalles += n.value + "|";
		else if (n.name == "CodCuenta") detalles += n.value + "|";
		else if (n.name == "CodClasificacion") detalles += n.value + "|";
		else if (n.name == "CodUnidad") detalles += n.value + "|";
		else if (n.name == "Estado") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=commodity&accion="+accion+"&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	linea
function linea(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=linea&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	familia
function familia(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodLinea" || n.id == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=familia&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	sub-familia
function subfamilia(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodLinea" || n.id == "CodFamilia" || n.id == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=subfamilia&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	requerimiento
function requerimiento(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "Clasificacion" || n.id == "CodAlmacen" || n.id == "Prioridad" || n.id == "FechaRequerida") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaRequerida") { error = "<strong>Fecha Requerida</strong> incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value.trim()) + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "FlagExonerado") {
			if (n.checked) detalles += "S;char:td;";
			else detalles += "N;char:td;";
		}
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat(setNumero(n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) { error = "Se encontraron <strong>Cantidades</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "FlagCompraAlmacen") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "cod_partida") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (detalles == "") error = "Debe ingresar por lo menos un articulo en la ficha de <strong>Items/Commodities</strong>";
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=requerimiento&accion="+accion+"&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");				
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "exito", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}
function requerimiento_rechazar(form) {
	var RazonRechazo = $("#RazonRechazo").val();
	if (RazonRechazo.trim() == "") {
		$("#cajaModal").dialog({
			buttons: {
				"Si": function() {
					$(this).dialog("close");
					requerimiento(form, 'rechazar');
				},
				"No": function() {
					$(this).dialog("close");
				}
			}
		});	
		$("#cajaModal").dialog({ 
			title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
			width: 400
		});
		$("#cajaModal").html("El campo <strong>Razón Rechazo</strong> esta vacio.<br />¿Continuar de todas formas?");
		$('#cajaModal').dialog('open');
	} else {
		requerimiento(form, 'rechazar');
	}
}

//	orden de compra
function orden_compra(form, accion) {
	//	formulario
	var Estado = $("#Estado").val();
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodProveedor" || n.id == "CodTipoServicio" || n.id == "CodFormaPago" || n.id == "Clasificacion" || n.id == "CodAlmacen" || n.id == "CodAlmacenIngreso" || n.id == "PlazoEntrega" || n.id == "FechaPrometida") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (isNaN(n.value) && n.id == "PlazoEntrega") { error = "<strong>Plazo de entrega</strong> incorrecto"; break; }
		else if (!valFecha(n.value) && n.id == "FechaPrometida") { error = "<strong>Fecha Prometida</strong> incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value.trim()) + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat(setNumero(n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) { error = "Se encontraron <strong>Cantidades</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") {
			var PrecioUnit = parseFloat(setNumero(n.value));
			if (isNaN(PrecioUnit) || PrecioUnit <= 0) { error = "Se encontraron <strong>Precios</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += PrecioUnit + ";char:td;";
		}
		else if (n.name == "DescuentoPorcentaje") {
			var DescuentoPorcentaje = parseFloat(setNumero(n.value));
			if (isNaN(DescuentoPorcentaje) || DescuentoPorcentaje < 0) { error = "Se encontraron <strong>Porcentajes de Descuentos</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += DescuentoPorcentaje + ";char:td;";
		}
		else if (n.name == "DescuentoFijo") {
			var DescuentoFijo = parseFloat(setNumero(n.value));
			if (isNaN(DescuentoFijo) || DescuentoFijo < 0) { error = "Se encontraron <strong>Descuentos Fijos</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += DescuentoFijo + ";char:td;";
		}
		else if (n.name == "FlagExonerado") {
			if (n.checked) detalles += "S;char:td;";
			else detalles += "N;char:td;";
		}
		else if (n.name == "PrecioUnitTotal") {
			var PrecioUnitTotal = parseFloat(setNumero(n.value));
			if (isNaN(PrecioUnitTotal) || PrecioUnitTotal <= 0) { error = "Se encontraron <strong>Precios Totales</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += PrecioUnitTotal + ";char:td;";
		}
		else if (n.name == "Total") {
			var Total = parseFloat(setNumero(n.value));
			if (isNaN(Total) || Total <= 0) { error = "Se encontraron <strong>Totales</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += Total + ";char:td;";
		}
		else if (n.name == "FechaPrometida") {
			if (!valFecha(n.value)) { error = "Se encontraron <strong>Fechas Prometidas</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "cod_partida") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "Comentarios") detalles += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodRequerimiento") detalles += n.value + ";char:td;";
		else if (n.name == "Secuencia") detalles += n.value + ";char:tr;";
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
		else if (n.name == "Monto") {
			var _Monto = new Number(n.value);
			detalles_partida += n.value + ";char:td;";
		}
		else if (n.name == "MontoDisponible") {
			var _MontoDisponible = new Number(n.value);
			if (_Monto > _MontoDisponible) { error = "Se encontro la partida <strong>"+_cod_partida+"</strong> sin Disponibilidad Presupuestaria"; break; }
			else detalles_partida += n.value + ";char:tr;";
		}
	}
	var len = detalles_partida.length; len-=9;
	detalles_partida = detalles_partida.substr(0, len);
	
	//	valido errores
	if (detalles == "") error = "Debe ingresar por lo menos un articulo en la ficha de <strong>Items/Commodities</strong>";
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=orden_compra&accion="+accion+"&"+post+"&detalles="+detalles+"&detalles_partida="+detalles_partida,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");				
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "revisar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "exito", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}
function orden_compra_rechazar(form) {
	var MotRechazo = $("#MotRechazo").val();
	if (MotRechazo.trim() == "") {
		$("#cajaModal").dialog({
			buttons: {
				"Si": function() {
					$(this).dialog("close");
					orden_compra(form, 'rechazar');
				},
				"No": function() {
					$(this).dialog("close");
				}
			}
		});	
		$("#cajaModal").dialog({ 
			title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
			width: 400
		});
		$("#cajaModal").html("El campo <strong>Razón Rechazo</strong> esta vacio.<br />¿Continuar de todas formas?");
		$('#cajaModal').dialog('open');
	} else {
		orden_compra(form, 'rechazar');
	}
}

//	orden de servicio
function orden_servicio(form, accion) {
	//	formulario
	var Estado = $("#Estado").val();
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodProveedor" || n.id == "CodTipoServicio" || n.id == "CodFormaPago" || n.id == "CodTipoPago" || n.id == "PlazoEntrega" || n.id == "FechaEntrega" || n.id == "DiasPago" || n.id == "FechaValidoDesde" || n.id == "FechaValidoHasta") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (isNaN(n.value) && n.id == "PlazoEntrega") { error = "<strong>Plazo de entrega</strong> incorrecto"; break; }
		else if (isNaN(n.value) && n.id == "DiasPago") { error = "<strong>Dias para pagar</strong> incorrecto"; break; }
		else if (!valFecha(n.value) && n.id == "FechaPrometida") { error = "<strong>Fecha Entrega</strong> incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaValidoDesde") { error = "<strong>Fecha Desde</strong> incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaValidoHasta") { error = "<strong>Fecha Hasta</strong> incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value.trim()) + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = parseFloat(setNumero(n.value));
			if (isNaN(CantidadPedida) || CantidadPedida <= 0) { error = "Se encontraron <strong>Cantidades</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += CantidadPedida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") {
			var PrecioUnit = parseFloat(setNumero(n.value));
			if (isNaN(PrecioUnit) || PrecioUnit <= 0) { error = "Se encontraron <strong>Precios</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += PrecioUnit + ";char:td;";
		}
		else if (n.name == "FlagExonerado") {
			if (n.checked) detalles += "S;char:td;";
			else detalles += "N;char:td;";
		}
		else if (n.name == "Total") {
			var Total = parseFloat(setNumero(n.value));
			if (isNaN(Total) || Total <= 0) { error = "Se encontraron <strong>Totales</strong> en la ficha de <strong>Items/Commodities</strong> incorrectos"; break; }
			else detalles += Total + ";char:td;";
		}
		else if (n.name == "FechaEsperadaTermino") {
			if (!valFecha(n.value)) { error = "Se encontraron <strong>Fecha Plan.</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += n.value + ";char:td;";
		}
		else if (n.name == "FechaTermino") {
			if (!valFecha(n.value)) { error = "Se encontraron <strong>Fecha Real</strong> en la ficha de <strong>Items/Commodities</strong> incorrectas"; break; }
			else detalles += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "NroActivo") detalles += n.value + ";char:td;";
		else if (n.name == "FlagTerminado") {
			if (n.checked) detalles += "S;char:td;";
			else detalles += "N;char:td;";
		}
		else if (n.name == "cod_partida") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "Comentarios") detalles += changeUrl(n.value) + ";char:tr;";
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
		else if (n.name == "Monto") {
			var _Monto = new Number(n.value);
			detalles_partida += n.value + ";char:td;";
		}
		else if (n.name == "MontoDisponible") {
			var _MontoDisponible = new Number(n.value);
			if (_Monto > _MontoDisponible) { error = "Se encontro la partida <strong>"+_cod_partida+"</strong> sin Disponibilidad Presupuestaria"; break; }
			else detalles_partida += n.value + ";char:tr;";
		}
	}
	var len = detalles_partida.length; len-=9;
	detalles_partida = detalles_partida.substr(0, len);
	
	//	valido errores
	if (detalles == "") error = "Debe ingresar por lo menos un articulo en la ficha de <strong>Items/Commodities</strong>";
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=orden_servicio&accion="+accion+"&"+post+"&detalles="+detalles+"&detalles_partida="+detalles_partida,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");				
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "revisar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "exito", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}
function orden_servicio_rechazar(form) {
	var MotRechazo = $("#MotRechazo").val();
	if (MotRechazo.trim() == "") {
		$("#cajaModal").dialog({
			buttons: {
				"Si": function() {
					$(this).dialog("close");
					orden_servicio(form, 'rechazar');
				},
				"No": function() {
					$(this).dialog("close");
				}
			}
		});	
		$("#cajaModal").dialog({ 
			title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
			width: 400
		});
		$("#cajaModal").html("El campo <strong>Razón Rechazo</strong> esta vacio.<br />¿Continuar de todas formas?");
		$('#cajaModal').dialog('open');
	} else {
		orden_servicio(form, 'rechazar');
	}
}

//	orden de servicio (confirmar)
function orden_servicio_confirmar(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "PorRecibirTotal") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((isNaN(setNumero(n.value)) || parseFloat(n.value) == 0) && n.id == "PorRecibirTotal") { error = "<strong>Cantidad</strong> incorrecta"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=orden_servicio&accion=confirmar&"+post,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var registro = partes[2] + "." + partes[1];
					var funct = "";
					funct += "document.getElementById('frmentrada').submit();";
					funct += "window.open('lg_orden_servicio_confirmar_pdf.php?registro="+registro+"', 'lg_orden_servicio_confirmar_pdf', 'toolbar=no, menubar=no, location=no, scrollbars=yes, width=1000, height=600');";
					cajaModal("Se ha generado la confirmación de servicios Nro. <strong>"+partes[1]+"</strong>", "exito", 400, funct);
				}
			}
		});
	}
	return false;
}

//	almacen (despacho)
function almacen_despacho(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "Clasificacion" || n.id == "CodAlmacen" || n.id == "Prioridad" || n.id == "FechaDocumento") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "StockActual") {
			var StockActual = new Number(setNumero(n.value));
			detalles += StockActual + ";char:td;";
		}
		else if (n.name == "CantidadPedida") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente") {
			var CantidadPendiente = new Number(n.value);
			detalles += CantidadPendiente + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > StockActual) { error = "La <strong>Cantidad por Despachar</strong> no puede ser mayor que el <strong>Stock Actual</strong>."; break; }
			else if (CantidadRecibida > CantidadPendiente) { error = "La <strong>Cantidad por Despachar</strong> no puede ser mayor que la <strong>Cantidad Pendiente</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Despachar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=almacen&accion=despacho&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var registro = $("#CodOrganismo").val() + "." + $("#CodDocumento").val() + "." + partes[1];
					var msj = "Se ha generado la Transacci&oacute;n <strong>Nro. " + $("#CodDocumento").val() + "-" + partes[2] + "</strong>";
					
					var funct = "document.getElementById('frmentrada').submit();";
					funct += "cargarOpcion2(document.getElementById('frmentrada'), 'lg_transaccion_almacen_despacho_pdf.php?', 'BLANK', 'height=800, width=750, left=200, top=200, resizable=no', '" + registro +"');";
					
					cajaModal(msj, "info", 400, funct);
				}
			}
		});
	}
	return false;
}

//	almacen (recepcion)
function almacen_recepcion(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "StockActual") {
			var StockActual = new Number(setNumero(n.value));
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadPedida") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente") {
			var CantidadPendiente = new Number(n.value);
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > CantidadPendiente) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser mayor que la <strong>Cantidad Pendiente</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "FlagExonerado") detalles += n.value + ";char:td;";
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=almacen&accion=recepcion&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var registro = $("#CodOrganismo").val() + "." + $("#CodDocumento").val() + "." + partes[1];
					var msj = "Se ha generado la Transacci&oacute;n <strong>Nro. " + $("#CodDocumento").val() + "-" + partes[2] + "</strong>";
					
					var funct = "document.getElementById('frmentrada').submit();";
					funct += "cargarOpcion2(document.getElementById('frmentrada'), 'lg_transaccion_almacen_recepcion_pdf.php?', 'BLANK', 'height=800, width=750, left=200, top=200, resizable=no', '" + registro +"');";
					
					cajaModal(msj, "info", 400, funct);
				}
			}
		});
	}
	return false;
}

//	transaccion (almacen)
function transaccion_almacen(form, accion) {
	//	formulario
	var TipoMovimiento = $("#TipoMovimiento").val();
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "DocumentoReferencia" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "StockActual") {
			var StockActual = new Number(setNumero(n.value));
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) {
				error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>.";
				break;
			}
			else if (CantidadRecibida > StockActual && TipoMovimiento != "I") {
				error = "La <strong>Cantidad</strong> no puede ser mayor que el <strong>Stock Actual</strong>."; 
				break;
			}
			else if (CantidadRecibida <= 0) {
				error = "La <strong>Cantidad</strong> no puede ser menor o igual a cero.";
				break;
			}
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=transaccion_almacen&accion="+accion+"&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "ejecutar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					} else form.submit();
				}
			}
		});
	}
	return false;
}

//	transaccion commodities (recepcion)
function transaccion_commodity_recepcion(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia" || n.id == "DocumentoReferencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente") {
			var CantidadPendiente = new Number(n.value);
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > CantidadPendiente) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser mayor que la <strong>Cantidad Pendiente</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "FlagExonerado") detalles += n.value + ";char:td;";
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodClasificacion") detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	detalles activo
	var activos = "";
	if ($("#FlagActivoFijo").attr("checked") == "checked") {
	var frm_activos = document.getElementById("frm_activos");
	
	for(var i=0; n=frm_activos.elements[i]; i++) {
		
		if (n.name == "Secuencia") activos += n.value + ";char:td;";
		else if (n.name == "NroSecuencia") activos += n.value + ";char:td;";
		else if (n.name == "CommoditySub") activos += n.value + ";char:td;";
		else if (n.name == "Descripcion") activos += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodClasificacion") activos += n.value + ";char:td;";
		else if (n.name == "Monto") activos += n.value + ";char:td;";
		else if (n.name == "NroSerie") activos += n.value + ";char:td;";
		else if (n.name == "FechaIngreso") {
			if (n.value.trim() == "" || !valFecha(n.value)) {
				error = "Se encontraron <strong>Fechas de Ingreso Incorrectas</strong> en la ficha de <strong>Activos Asociados</strong>";
				break;
			}
			else activos += n.value + ";char:td;";
		}
		else if (n.name == "Modelo") activos += n.value + ";char:td;";
		else if (n.name == "CodBarra") activos += n.value + ";char:td;";
		else if (n.name == "CodUbicacion") {
			if (n.value.trim() == "") {
				error = "Se encontraron lineas sin <strong>Ubicaciones</strong> en la ficha de <strong>Activos Asociados</strong>";
				break;
			}
			else activos += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto") {
			if (n.value.trim() == "") {
				error = "Se encontraron lineas sin <strong>Centro de Costo</strong> en la ficha de <strong>Activos Asociados</strong>";
				break;
			}
			else activos += n.value + ";char:td;";
		}
		else if (n.name == "NroPlaca") activos += n.value + ";char:td;";
		else if (n.name == "CodMarca") activos += n.value + ";char:td;";
		else if (n.name == "Color") activos += n.value + ";char:tr;";
	}
	var len = activos.length; len-=9;
	activos = activos.substr(0, len);
}
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=almacen-commodity&accion=recepcion&"+post+"&detalles="+detalles+"&activos="+activos,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					if ($("#FlagActivoFijo").attr("checked") == "checked") {
						var registro = $("#CodOrganismo").val() + "." + $("#CodDocumento").val() + "." + partes[1] + "." + $("#TipoMovimiento").val();
						funct += "cargarOpcion2(document.getElementById('frmentrada'), 'gehen.php?anz=lg_transaccion_commodity_reportes', 'BLANK', 'height=800, width=1050, left=50, top=50, resizable=no', '"+registro+"');";
					}
					cajaModal(partes[2], "exito", 400, funct);
				}
			}
		});
	}
	return false;
}

//	transaccion commodities (despacho)
function transaccion_commodity_despacho(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "CodTransaccion" || n.id == "CodDocumento") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "StockActual") {
			var StockActual = new Number(n.value);
			detalles += StockActual + ";char:td;";
		}
		else if (n.name == "CantidadPedida") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPendiente") {
			var CantidadPendiente = new Number(n.value);
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > CantidadPendiente) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser mayor que la <strong>Cantidad Pendiente</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:td;";
		else if (n.name == "CodRequerimiento") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=almacen-commodity&accion=despacho&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal(partes[1], "info", 400, funct);
				}
			}
		});
	}
	return false;
}

//	transaccion (commodities)
function transaccion_commodity(form, accion) {
	//	formulario
	var TipoMovimiento = $("#TipoMovimiento").val();
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "DocumentoReferencia" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "StockActual") {
			var StockActual = new Number(setNumero(n.value));
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) {
				error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>.";
				break;
			}
			else if (CantidadRecibida > StockActual && TipoMovimiento != "I") {
				error = "La <strong>Cantidad</strong> no puede ser mayor que el <strong>Stock Actual</strong>."; 
				break;
			}
			else if (CantidadRecibida <= 0) {
				error = "La <strong>Cantidad</strong> no puede ser menor o igual a cero.";
				break;
			}
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=transaccion_commodity&accion="+accion+"&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "ejecutar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					} else form.submit();
				}
			}
		});
	}
	return false;
}

//	transaccion caja chica (recepcion)
function transaccion_cajachica_recepcion(form) {
	if (document.getElementById("FlagActivoFijo").checked) var FlagActivoFijo = "S"; else var FlagActivoFijo = "N";
	if (document.getElementById("FlagManual").checked) var FlagManual = "S"; else var FlagManual = "N";
	
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia" || n.id == "DocumentoReferencia" || (n.id == "CodUbicacion" && FlagActivoFijo == "S")) && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = new Number(setNumero(n.value));
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > CantidadPedida) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser mayor que la <strong>Cantidad Pedida</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") {
			var PrecioUnit = new Number(setNumero(n.value));
			if (PrecioUnit <= 0) { error = "El Precio Unitario no puede ser menor o igual a cero. (Valorizaci&oacute;n Manual Activo)"; break; }
			detalles += PrecioUnit + ";char:td;";
		}
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	detalles activo
	var activos = "";
	var frm_activos = document.getElementById("frm_activos");
	for(var i=0; n=frm_activos.elements[i]; i++) {
		if (n.name == "Secuencia") activos += n.value + ";char:td;";
		else if (n.name == "NroSecuencia") activos += n.value + ";char:td;";
		else if (n.name == "CommoditySub") activos += n.value + ";char:td;";
		else if (n.name == "Descripcion") activos += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodClasificacion") activos += n.value + ";char:td;";
		else if (n.name == "Monto") activos += n.value + ";char:td;";
		else if (n.name == "NroSerie") activos += n.value + ";char:td;";
		else if (n.name == "FechaIngreso") {
			if (n.value.trim() == "" || !valFecha(n.value)) {
				error = "Se encontraron <strong>Fechas de Ingreso Incorrectas</strong> en la ficha de <strong>Activos Asociados</strong>";
				break;
			}
			else activos += n.value + ";char:td;";
		}
		else if (n.name == "Modelo") activos += n.value + ";char:td;";
		else if (n.name == "CodBarra") activos += n.value + ";char:td;";
		else if (n.name == "CodUbicacion") activos += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto") activos += n.value + ";char:td;";
		else if (n.name == "NroPlaca") activos += n.value + ";char:td;";
		else if (n.name == "CodMarca") activos += n.value + ";char:td;";
		else if (n.name == "Color") activos += n.value + ";char:tr;";
	}
	var len = activos.length; len-=9;
	activos = activos.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=transaccion-cajachica&accion=recepcion&"+post+"&detalles="+detalles+"&activos="+activos,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal(partes[1], "exito", 400, funct);
				}
			}
		});
	}
	return false;
}

//	transaccion caja chica (despacho)
function transaccion_cajachica_despacho(form) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodAlmacen" || n.id == "FechaDocumento" || n.id == "CodTransaccion" || n.id == "CodDocumento" || n.id == "CodDocumentoReferencia" || n.id == "NroDocumentoReferencia" || n.id == "DocumentoReferencia") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "<strong>Fecha Transacci&oacute;n</strong> incorrecta"; break; }
	}
	
	//	detalles documento
	var detalles = "";
	var frm_detalles = document.getElementById("frm_detalles");
	for(var i=0; n=frm_detalles.elements[i]; i++) {
		if (n.name == "CodItem") detalles += n.value + ";char:td;";
		else if (n.name == "CommoditySub") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += changeUrl(n.value) + ";char:td;";
		else if (n.name == "CodUnidad") detalles += n.value + ";char:td;";
		else if (n.name == "CantidadPedida") {
			var CantidadPedida = new Number(setNumero(n.value));
			detalles += n.value + ";char:td;";
		}
		else if (n.name == "CantidadRecibida") {
			var CantidadRecibida = new Number(setNumero(n.value));
			if (isNaN(CantidadRecibida)) { error = "Se encontraron <strong>Cantidades</strong> incorrectas en la ficha de <strong>Items</strong>."; break; }
			else if (CantidadRecibida > CantidadPedida) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser mayor que la <strong>Cantidad Pedida</strong>."; break; }
			else if (CantidadRecibida <= 0) { error = "La <strong>Cantidad por Recepcionar</strong> no puede ser menor o igual a cero."; break; }
			detalles += CantidadRecibida + ";char:td;";
		}
		else if (n.name == "PrecioUnit") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "Total") detalles += setNumero(n.value) + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaCodDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaSecuencia") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=transaccion-cajachica&accion=despacho&"+post+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal(partes[1], "exito", 400, funct);
				}
			}
		});
	}
	return false;
}
