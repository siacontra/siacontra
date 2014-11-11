// JavaScript Document

//	organismos externos
function generar_vouchers(form, accion, imprimir) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "CodOrganismo" || n.id == "CodLibroCont" || n.id == "FechaVoucher") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaVoucher") { error = "Formato de fecha de voucher es incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles
	var detalles = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.name == "Linea") detalles += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles += n.value + ";char:td;";
		else if (n.name == "MontoVoucher") {
			var MontoVoucher = new Number(setNumero(n.value));
			detalles += MontoVoucher + ";char:td;";
		}
		else if (n.name == "CodPersona") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaTipoDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles += n.value + ";char:td;";
		else if (n.name == "CodCentroCosto") detalles += n.value + ";char:td;";
		else if (n.name == "FechaVoucher") detalles += n.value + ";char:tr;";
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
			data: "modulo=generar_vouchers&"+post+"&detalles="+detalles+"&accion="+accion,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else {
					window.close();
					if (opener.document.getElementById("frmentrada")) opener.document.getElementById("frmentrada").submit();
					if (imprimir == "imprimir") {
						var NroProceso = $("#NroProceso").val();
						var Secuencia = $("#Secuencia").val();
						var CodTipoPago = $("#CodTipoPago").val();
						var registro = NroProceso+"."+Secuencia+"."+CodTipoPago;
						window.open("gehen.php?anz=ap_orden_pago_transferir_reportes&registro="+registro, "ap_pagos_imprimir_transferir_voucher_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=1050, left=50, top=50, resizable=yes");
					}
				}
			}
		});
	}
	return false;
}

//	obligacion
function obligacion(form, accion) {
	//	formulario
	if (document.getElementById("FlagCompromiso").checked) var FlagCompromiso = "S"; else var FlagCompromiso = "N";
	var MontoAfecto = new Number(setNumero($("#MontoAfecto").val()));
	var MontoNoAfecto = new Number(setNumero($("#MontoNoAfecto").val()));
	var MontoImpuesto = new Number(setNumero($("#MontoImpuesto").val()));
	var MontoImpuestoOtros = new Number(setNumero($("#MontoImpuestoOtros").val()));
	var MontoObligacion = new Number(setNumero($("#MontoObligacion").val()));
	var MontoAdelanto = new Number(setNumero($("#MontoAdelanto").val()));
	var MontoPagar = new Number(setNumero($("#MontoPagar").val()));
	var MontoPagoParcial = new Number(setNumero($("#MontoPagoParcial").val()));
	var MontoPendiente = new Number(setNumero($("#MontoPendiente").val()));
	var documento_afecto = new Number(setNumero($("#documento_afecto").val()));
	var documento_impuesto = new Number(setNumero($("#documento_impuesto").val()));
	var documento_noafecto = new Number(setNumero($("#documento_noafecto").val()));
	var distribucion_total = new Number(setNumero($("#distribucion_total").val()));
	var impuesto_total = new Number(setNumero($("#impuesto_total").val()));
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
		if ((n.id == "CodProveedor" || n.id == "CodProveedorPagar" || n.id == "CodOrganismo" || n.id == "CodCentroCosto" || n.id == "CodTipoDocumento" || n.id == "NroDocumento" || n.id == "NroControl" || n.id == "CodTipoServicio" || n.id == "CodTipoPago" || n.id == "NroCuenta") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valNumericoEntero(n.value) && n.id == "DiasPago") { error = "Formato de fecha de factura es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaFactura") { error = "Formato de fecha de factura es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaRegistro") { error = "Formato de fecha de registro es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaDocumento") { error = "Formato de fecha de documento es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaRecepcion") { error = "Formato de fecha de recepcion es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaVencimiento") { error = "Formato de fecha de vencimiento es incorrecta"; break; }
		else if (!valFecha(n.value) && n.id == "FechaProgramada") { error = "Formato de fecha programada es incorrecta"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoAfecto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoNoAfecto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoImpuesto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoImpuestoOtros") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoObligacion") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoAdelanto") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPagar") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPagoParcial") { error = "Formato de montos incorrectos"; break; }
		else if (isNaN(setNumero(n.value)) && n.id == "MontoPendiente") { error = "Formato de montos incorrectos"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	detalles impuesto
	var detalles_impuesto = "";
	var frm_impuesto = document.getElementById("frm_impuesto");
	for(var i=0; n=frm_impuesto.elements[i]; i++) {
		if (n.name == "CodImpuesto") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "CodConcepto") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "Signo") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "FlagImponible") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "FlagProvision") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "CodCuenta") detalles_impuesto += n.value + ";char:td;";
		else if (n.name == "MontoAfecto") {
			var _MontoAfecto = new Number(setNumero(n.value));
			//if (isNaN(_MontoAfecto) || _MontoAfecto == 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _MontoAfecto + ";char:td;";
		}
		else if (n.name == "FactorPorcentaje") {
			var _FactorPorcentaje = new Number(setNumero(n.value));
			//if (isNaN(_FactorPorcentaje) || _FactorPorcentaje <= 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _FactorPorcentaje + ";char:td;";
		}
		else if (n.name == "MontoImpuesto") {
			var _MontoImpuesto = new Number(setNumero(n.value));
			if (isNaN(_MontoImpuesto) || _MontoImpuesto == 0) error = "Se encontraron lineas en las retenciones con montos incorrectos";
			detalles_impuesto += _MontoImpuesto + ";char:tr;";
		}
	}
	var len = detalles_impuesto.length; len-=9;
	detalles_impuesto = detalles_impuesto.substr(0, len);
	
	//	detalles documento
	var detalles_documento = "";
	var frm_documento = document.getElementById("frm_documento");
	for(var i=0; n=frm_documento.elements[i]; i++) {
		if (n.name == "Porcentaje") detalles_documento += n.value + ";char:td;";
		else if (n.name == "ReferenciaTipoDocumento") detalles_documento += n.value + ";char:td;";
		else if (n.name == "DocumentoClasificacion") detalles_documento += n.value + ";char:td;";
		else if (n.name == "DocumentoReferencia") detalles_documento += n.value + ";char:td;";
		else if (n.name == "Fecha") detalles_documento += n.value + ";char:td;";
		else if (n.name == "ReferenciaNroDocumento") detalles_documento += n.value + ";char:td;";
		else if (n.name == "MontoTotal") {
			var _MontoTotal = new Number(setNumero(n.value));
			if (isNaN(_MontoTotal) || _MontoTotal < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoTotal + ";char:td;";
		}
		else if (n.name == "MontoAfecto") {
			var _MontoAfecto = new Number(setNumero(n.value));
			if (isNaN(_MontoAfecto) || _MontoAfecto < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoAfecto + ";char:td;";
		}
		else if (n.name == "MontoImpuestos") {
			var _MontoImpuestos = new Number(setNumero(n.value));
			if (isNaN(_MontoImpuestos) || _MontoImpuestos < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoImpuestos + ";char:td;";
		}
		else if (n.name == "MontoNoAfecto") {
			var _MontoNoAfecto = new Number(setNumero(n.value));
			if (isNaN(_MontoNoAfecto) || _MontoNoAfecto < 0) { error = "Se encontraron lineas en los documentos con montos incorrectos"; break; }
			detalles_documento += _MontoNoAfecto + ";char:td;";
		}
		else if (n.name == "Comentarios") detalles_documento += changeUrl(n.value.trim()) + ";char:tr;";
	}
	var len = detalles_documento.length; len-=9;
	detalles_documento = detalles_documento.substr(0, len);
	
	//	detalles distribucion
	var _MontoAfecto = new Number(0);
	var _MontoNoAfecto = new Number(0);
	var detalles_distribucion = "";
	var frm_distribucion = document.getElementById("frm_distribucion");
	for(var i=0; n=frm_distribucion.elements[i]; i++) {
		if (n.name == "cod_partida") {
			if (document.getElementById("FlagPresupuesto").checked && n.value == "") error = "Se encontraron lineas en la distribucion sin partidas presupuestarias";
			detalles_distribucion += n.value + ";char:td;";
		}
		else if (n.name == "CodCuenta") {
			if (n.value.trim() == "") error = "Se encontraron lineas en la distribucion sin cuentas contables";
			detalles_distribucion += n.value + ";char:td;";
		}
		else if (n.name == "CodCentroCosto") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "FlagNoAfectoIGV") {
			if (n.checked) { detalles_distribucion += "S" + ";char:td;"; var _FlagNoAfectoIGV = "S"; }
			else { detalles_distribucion += "N" + ";char:td;"; var _FlagNoAfectoIGV = "N"; }
		}
		else if (n.name == "Monto") {
			var Monto = new Number(setNumero(n.value));
			if (isNaN(Monto) || Monto <= 0) error = "Se encontraron lineas en la distribucion con montos incorrectos";
			detalles_distribucion += Monto + ";char:td;";
			if (_FlagNoAfectoIGV == "N") _MontoAfecto += Monto; else _MontoNoAfecto += Monto;
		}
		else if (n.name == "TipoOrden") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "NroOrden") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "Referencia") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "Descripcion") detalles_distribucion += changeUrl(n.value.trim()) + ";char:td;";
		else if (n.name == "CodPersona") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "NroActivo") detalles_distribucion += n.value + ";char:td;";
		else if (n.name == "FlagDiferido") {
			if (n.checked) detalles_distribucion += "S" + ";char:tr;";
			else detalles_distribucion += "N" + ";char:tr;";
		}
	}
	var len = detalles_distribucion.length; len-=9;
	detalles_distribucion = detalles_distribucion.substr(0, len);
	
	//	detalles partidas
	var detalles_partidas = "";
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "cod_partida") {
			var cod_partida = n.value;
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "Monto") {
			var Monto = new Number(n.value);
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "MontoDisponible") {
			var MontoDisponible = new Number(n.value);
			detalles_partidas += n.value + ";char:td;";
		}
		else if (n.name == "MontoPendiente") {
			var MontoPendiente = new Number(n.value);
			if (MontoDisponible < Monto && FlagCompromiso == "S") { error = "Sin disponibilidad presupuestaria la partida <strong>" + cod_partida + "</strong>"; break; }
			else detalles_partidas += n.value + ";char:tr;";
		}
	}
	var len = detalles_partidas.length; len-=9;
	detalles_partidas = detalles_partidas.substr(0, len);
	
	//	errores del documento
	if (detalles_documento != "") {
		/*if ((documento_afecto - MontoAfecto != 0) || (documento_noafecto - MontoNoAfecto != 0) || (documento_impuesto - MontoImpuesto != 0)) {
			error = "Montos del documento no coinciden con los de la obligación";
			$("#MontoAfecto").removeClass("disabled").removeAttr("disabled");
			$("#MontoNoAfecto").removeClass("disabled").removeAttr("disabled");
			$("#MontoImpuesto").removeClass("disabled").removeAttr("disabled");
			$("#MontoObligacion").removeClass("codigo").removeAttr("disabled");
		}*/
	}
	
	//	errores de la distribucion
	if ((distribucion_total - (MontoAfecto + MontoNoAfecto)) != 0) {
		//error = "El total de la distribución debe ser igual al <strong>Monto Afecto + Monto No Afecto</strong>";
	}
	else if (parseFloat(_MontoAfecto) != parseFloat(MontoAfecto)) {
		//error = "El total de la distribución marcada como Afecta ("+_MontoAfecto+") es distinta del <strong>Monto Afecto ("+MontoAfecto+")</strong>";
	}
	else if (parseFloat(_MontoNoAfecto) != parseFloat(MontoNoAfecto)) {
		//error = "El total de la distribución marcada como No Afecta ("+_MontoNoAfecto+") es distinta del <strong>Monto No Afecto ("+MontoNoAfecto+")</strong>";
	}
	
	//	errores de las retenciones
	if ((impuesto_total - MontoImpuestoOtros) != 0) {
		//error = "El total de las retenciones debe ser igual al <strong>Monto Retención de la Obligación</strong>";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		var url = "modulo=obligacion&accion="+accion+"&"+post+"&detalles_impuesto="+detalles_impuesto+"&detalles_documento="+detalles_documento+"&detalles_distribucion="+detalles_distribucion;
		
		if (accion == "anular") {
			var MotivoAnulacion = $("#MotivoAnulacion").val();
			var Estado = $("#Estado").val();
			if (MotivoAnulacion.trim() == "" && Estado == "PR") {
				$("#cajaModal").dialog({
					buttons: {
						"Si": function() {
							$(this).dialog("close");
							obligacion_ajax(form, accion, url);
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
				$("#cajaModal").html("El campo <strong>Razón Anulación</strong> esta vacio.<br />¿Continuar de todas formas?");
				$('#cajaModal').dialog('open');
			} else obligacion_ajax(form, accion, url);
		}
		else if (accion == "aprobar") {
			if ($("#Periodo").val() != $("#PeriodoActual").val()) {
				$("#cajaModal").dialog({
					buttons: {
						"Aceptar": function() { 
							$(this).dialog("close");
							obligacion_ajax(form, accion, url);
						},
						"Cancelar": function() { 
							$(this).dialog("close");
						}
					},
					title: "<img src='../imagenes/warning.png' width='24' align='absmiddle' />Error", 
					width: 450
				});
				$('#cajaModal').html('El periodo de la obligación es distinta al Periodo Actual,<br />¿Continuar de todas formas?');
				$('#cajaModal').dialog('open');
			} obligacion_ajax(form, accion, url);
		} else {
			obligacion_ajax(form, accion, url);
		}
	}
	return false;
}
//	obligacion (ajax)
function obligacion_ajax(form, accion, url) {
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: url,
		async: false,
		success: function(resp) {
			var datos = resp.split("|");
			if (datos[0].trim() != "") cajaModal(datos[0], "error", 400);
			else {
				if (accion == "aprobar") {
					$("#cajaModal").dialog({
						buttons: {
							"Aceptar": function() { 
								if (accion == "aprobar" && $("#FlagProvision").val() == "S") {
									var registro = $("#CodOrganismo").val() + "." + $("#CodProveedor").val() + "." + $("#CodTipoDocumento").val() + "." + $("#NroDocumento").val() + "." + $("#CodVoucher").val();
									generar_vouchers_abrir(registro, 'ap_generar_vouchers_provision_voucher');
									form.submit();
								} else form.submit();
							},
							"Cancelar": function() { 
								$(this).dialog("close");
								form.submit();
							}
						},
						title: "<img src='../imagenes/warning.png' width='24' align='absmiddle' />Error", 
						width: 450
					});
					$('#cajaModal').html('Se ha generado la Orden de Pago Nro. <strong>'+datos[1]+'</strong>');
					$('#cajaModal').dialog('open');
					
				} else form.submit();
			}
		}
	});
}

//	orden de pago
function orden_pago(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "FechaOrdenPago" || n.id == "CodTipoPago" || n.id == "NroCuenta" || n.id == "RevisadoPor" || n.id == "AprobadoPor" || n.id == "Concepto") && n.value.trim() == "" && accion != "anular") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaOrdenPago") { error = "Formato de fecha de orden de pago es incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		if (accion == "anular") {
			var MotivoAnulacion = $("#MotivoAnulacion").val();
			if (MotivoAnulacion.trim() == "") {
				$("#cajaModal").dialog({
					buttons: {
						"Si": function() {
							$(this).dialog("close");
							orden_pago_anular(form, post);
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
				$("#cajaModal").html("El campo <strong>Razón Anulación</strong> esta vacio.<br />¿Continuar de todas formas?");
				$('#cajaModal').dialog('open');
			} else orden_pago_anular(form, post);
		} else {
			$.ajax({
				type: "POST",
				url: "lib/form_ajax.php",
				data: "modulo=orden_pago&"+post+"&accion="+accion,
				async: false,
				success: function(resp){
					if (resp.trim() != "") cajaModal(resp, "error", 400);
					else form.submit();
				}
			});
		}
	}
	return false;
}
function orden_pago_anular(form, post) {
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: "modulo=orden_pago&"+post+"&accion=anular",
		async: false,
		success: function(resp){
			var partes = resp.split("|");
			if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
			else {
				var FlagContabilizacionPendiente = $("#FlagContabilizacionPendiente").val();
				var VoucherDocumento = $("#VoucherDocumento").val();
				if (FlagContabilizacionPendiente == "N" && VoucherDocumento != "") {
					window.open("gehen.php?anz=ap_voucher_form&registro="+partes[1]+"&opcion=ver", "ap_voucher_form", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=575, width=1000, left=0, top=0, resizable=no");
				}
				form.submit();
			}
		}
	});
}

//	orden de pago (pre-pago)
function prepago(form, accion) {
	//	formulario
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
	}
	
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: "modulo=orden_pago&"+post+"&accion="+accion,
		async: false,
		success: function(resp){
			if (resp.trim() != "") cajaModal(resp, "error", 400);
			else form.submit();
		}
	});
	return false;
}

//	orden de pago (imprimir/transferir)
function transferir(form, accion) {
	//	formulario
	var NroProceso = document.getElementById("NroProceso").value;
	var Secuencia = document.getElementById("Secuencia").value;
	var post = "";
	var error = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + n.value.trim() + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
		
		//	errores
		if ((n.id == "FechaPago") && n.value.trim() == "") { error = "La fecha es obligatoria"; break; }
		else if (!valFecha(n.value) && n.id == "FechaPago") { error = "Formato de fecha de pago es incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=orden_pago&"+post+"&accion="+accion,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else {
					var registro = NroProceso + "." + Secuencia;
					generar_vouchers_abrir(registro, 'ap_generar_vouchers_pagos_voucher', 'imprimir');
					form.submit();
				}
			}
		});
	}
	return false;
}

//	pagos (modificacion restringida)
function pago(form, accion) {
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
		if ((n.id == "FechaPago") && n.value.trim() == "") { error = "La fecha es obligatoria"; break; }
		else if (!valFecha(n.value) && n.id == "FechaPago") { error = "Formato de fecha de pago es incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		if (accion == "modificar") {
			$.ajax({
				type: "POST",
				url: "lib/form_ajax.php",
				data: "modulo=pago&"+post+"&accion="+accion,
				async: false,
				success: function(resp){
					if (resp.trim() != "") cajaModal(resp, "error", 400);
					else form.submit();
				}
			});
		} else {
			var MotivoAnulacion = $("#MotivoAnulacion").val();
			if (MotivoAnulacion.trim() == "") {
				$("#cajaModal").dialog({
					buttons: {
						"Si": function() {
							$(this).dialog("close");
							pago_anular(form, post);
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
				$("#cajaModal").html("El campo <strong>Motivo de Anulación</strong> esta vacio.<br />¿Continuar de todas formas?");
				$('#cajaModal').dialog('open');
			} else pago_anular(form, post);
		}
	}
	return false;
}
function pago_anular(form, post) {
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: "modulo=pago&"+post+"&accion=anular",
		async: false,
		success: function(resp){
			var partes = resp.split("|");
			if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
			else {
				window.open("gehen.php?anz=ap_voucher_form&registro="+partes[1]+"&opcion=ver", "ap_voucher_form", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=575, width=1000, left=0, top=0, resizable=no");
				form.submit();
			}
		}
	});
}

//	importar registro de compras
function registro_compra_importar(form, accion) {
	//	errores
	var error = "";
	if ($("#CodOrganismo").val().trim() == "" || $("#Periodo").val().trim() == "") { error = "Debe seleccionar el Organismo y el Periodo"; }
	else if (!valPeriodo($("#Periodo").val())) { error = "Periodo incorrectos"; }
	
	//	valido error
	if (error) {
		cajaModal(error, "error", 400);
	} else {	
		//	formulario
		var post = "";
		var error = "";
		for(var i=0; n=form.elements[i]; i++) {
			if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
				post += n.id + "=" + n.value.trim() + "&";
			} else {
				if (n.type == "checkbox") {
					if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
				}
				else if (n.type == "radio" && n.checked) {
					post += n.name + "=" + n.value.trim() + "&";
				}
			}
		}
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=registro_compra&"+post+"&accion="+accion,
			async: false,
			success: function(resp){
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModal(resp, "error", 400);
				else {
					var divCP = new Number(datos[1]);
					var divCC = new Number(datos[2]);
					var Total = divCP + divCC;
					$("#divCP").html(datos[1]);
					$("#divCC").html(datos[2]);
					cajaModal("Se importaron "+Total+" registros", "exito", 400);
				}
			}
		});
	}
	return false;
}

//	registro de compras
function registro_compra(form, accion) {
	return false;
}

//	tipo de documento cxp
function tipo_documento_cxp(form, accion) {
	//	valido
	var error = "";
	if ($("#CodTipoDocumento").val().trim() == "" || $("#Descripcion").val().trim() == "" || $("#CodVoucher").val() == "" || $("#CodRegimenFiscal").val() == "" || $("#Clasificacion").val() == "") error = "Debe llenar los campos obligatorios";
	else if (!valCodigo($("#CodTipoDocumento").val())) error = "No se permiten caracteres especiales para el <strong>C&oacute;digo</strong>";
	else if ($("#CodCuentaProv").val() == "" && $("#FlagProvision").prop("checked")) error = "Debe seleccionar la <strong>Cta. de Provisi&oacute;n</strong>";
	else if ($("#CodCuentaAde").val() == "" && $("#FlagAdelanto").prop("checked")) error = "Debe seleccionar la <strong>Cta. de Adelanto</strong>";
	else if ($("#CodFiscal").val().trim() == "" && $("#FlagFiscal").prop("checked")) error = "Debe ingresar el <strong>C&oacute;digo Fiscal</strong>";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=tipo_documento_cxp&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	impuestos
function impuestos(form, accion) {
	//	valido
	var error = "";
	if ($("#CodImpuesto").val().trim() == "" || $("#Descripcion").val().trim() == "" || $("#CodRegimenFiscal").val() == "" || $("#FlagProvision").val() == "" || $("#FlagImponible").val() == "" || $("#TipoComprobante").val() == "" || $("#FactorPorcentaje").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (isNaN(setNumero($("#FactorPorcentaje").val()))) error = "Formato del Porcentaje Incorrecto";
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=impuestos&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}