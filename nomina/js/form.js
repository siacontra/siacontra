// JavaScript Document

// 	calculo de fideicomiso
function fideicomiso_calculo(form, accion) {
	//	valores
	CodPersona = $("#CodPersona").val();
	Periodo = $("#Periodo").val();
	
	//	lista de calculo
	var post = "";
	var detalles = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.name == "Periodo") detalles += n.value + ";char:td;";
		else if (n.name == "SueldoMensual") detalles += n.value + ";char:td;";
		else if (n.name == "Bonificaciones") detalles += n.value + ";char:td;";
		else if (n.name == "AliVac") detalles += n.value + ";char:td;";
		else if (n.name == "AliFin") detalles += n.value + ";char:td;";
		else if (n.name == "SueldoDiario") detalles += n.value + ";char:td;";
		else if (n.name == "SueldoDiarioAli") detalles += n.value + ";char:td;";
		else if (n.name == "Dias") detalles += n.value + ";char:td;";
		else if (n.name == "PrestAntiguedad") detalles += n.value + ";char:td;";
		else if (n.name == "DiasComplemento") detalles += n.value + ";char:td;";
		else if (n.name == "PrestComplemento") detalles += n.value + ";char:td;";
		else if (n.name == "PrestAcumulada") detalles += n.value + ";char:td;";
		else if (n.name == "Tasa") detalles += n.value + ";char:td;";
		else if (n.name == "DiasMes") detalles += n.value + ";char:td;";
		else if (n.name == "InteresMensual") detalles += n.value + ";char:td;";
		else if (n.name == "InteresAcumulado") detalles += n.value + ";char:td;";
		else if (n.name == "Anticipo") detalles += n.value + ";char:tr;";
	}
	var len = detalles.length; len-=9;
	detalles = detalles.substr(0, len);
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/form_ajax.php",
		data: "modulo=fideicomiso_calculo&accion="+accion+"&CodPersona="+CodPersona+"&Periodo="+Periodo+"&detalles="+detalles,
		async: false,
		success: function(resp) {
			if (resp.trim() != "") cajaModal(resp, "error", 400);
			else cajaModal("Se procesaron los datos exitosamente", "exito", 400);
		}
	});
}
//	-------------------------------

// 	interfase de cuentas por pagar (calcular)
function interfase_cuentas_por_pagar_calcular() {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=calcular&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se calcularon las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (generar)
function interfase_cuentas_por_pagar_generar(frm) {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var detalles_bancos = "";
	var detalles_cheques = "";
	var detalles_terceros = "";
	var detalles_judiciales = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		if (frm == "bancos") {
			//	listado (interfase bancaria)
			var chk = false;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { detalles_bancos += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value != "S") {
						error = n.id + "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria";
						break;
					}
					chk = false;
				}
			}
			var len = detalles_bancos.length; len-=1;
			detalles_bancos = detalles_bancos.substr(0, len);
		}
		
		else if (frm == "cheques") {
			//	listado (cheques)
			var chk = false;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { detalles_cheques += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value != "S") {
						error = n.id + "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria";
						break;
					}
					chk = false;
				}
			}
			var len = detalles_cheques.length; len-=1;
			detalles_cheques = detalles_cheques.substr(0, len);
		}
		
		else if (frm == "terceros") {
			//	listado (pago a terceros)
			var chk = false;
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				if (n.name == "terceros" && n.checked) { detalles_terceros += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value != "S") {
						error = n.id + "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria";
						break;
					}
					chk = false;
				}
			}
			var len = detalles_terceros.length; len-=1;
			detalles_terceros = detalles_terceros.substr(0, len);
		}
		
		else if (frm == "judiciales") {
			//	listado (retenciones judiciales)
			var frm_judiciales = document.getElementById("frm_judiciales");
			for(var i=0; n=frm_judiciales.elements[i]; i++) {
				if (n.name == "judiciales" && n.checked) detalles_judiciales += n.value + ";";
			}
			var len = detalles_judiciales.length; len-=1;
			detalles_judiciales = detalles_judiciales.substr(0, len);
		}
		
		//	valido que selecciono algun registro
		if (detalles_bancos == "" && detalles_cheques == "" && detalles_terceros == "" && detalles_judiciales == "") error = "Debe seleccionar por lo menos una Obligaci&oacute;n por Generar";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=generar&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&detalles_bancos="+detalles_bancos+"&detalles_cheques="+detalles_cheques+"&detalles_terceros="+detalles_terceros+"&detalles_judiciales="+detalles_judiciales+"&frm="+frm,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 500);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se generaron las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (consolidar)
function interfase_cuentas_por_pagar_consolidar(frm) {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var detalles_bancos = "";
	var detalles_cheques = "";
	var detalles_terceros = "";
	var detalles_judiciales = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		if (frm == "bancos") {
			//	listado (interfase bancaria)
			var nro_bancos = 0;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { detalles_bancos += n.value + ";"; ++nro_bancos; }
			}
			var len = detalles_bancos.length; len-=1;
			detalles_bancos = detalles_bancos.substr(0, len);
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos < 2) error = "Debe seleccionar mas una Obligaci&oacute;n por Consolidar";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=consolidar&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&detalles_bancos="+detalles_bancos+"&frm="+frm,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se consolidaron las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (verificar)
function interfase_cuentas_por_pagar_verificar(form) {
	$(".div-progressbar").css("display", "block");
	
	var error = "";
	//	partidas
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "Diferencia") {
			var Diferencia = parseFloat(n.value);
			if (Diferencia < 0) { error = "Se encontraron partidas sin Disponibilidad"; break; }
		}
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=verificar&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else {
					var funct = "parent.$.prettyPhoto.close();";
					funct += "parent.document.getElementById('frmentrada').submit();";
					cajaModal("Presupuesto verificado exitosamente", "exito", 400, funct);
				}
			}
		});
	}
	return false;
}
//	-------------------------------

// 	interfase de cuentas por pagar (calcular)
/*function interfase_cuentas_por_pagar_calcular() {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=calcular&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se calcularon las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (generar)
function interfase_cuentas_por_pagar_generar(frm) {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var detalles_bancos = "";
	var detalles_cheques = "";
	var detalles_terceros = "";
	var detalles_judiciales = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		if (frm == "bancos") {
			//	listado (interfase bancaria)
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				var chk = false;
				if (n.name == "bancos" && n.checked) { detalles_bancos += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado" && n.value != "S" && chk) { error = n.id + "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria"; }
			}
			var len = detalles_bancos.length; len-=1;
			detalles_bancos = detalles_bancos.substr(0, len);
		}
		
		else if (frm == "cheques") {
			//	listado (cheques)
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				var chk = false;
				if (n.name == "cheques" && n.checked) { detalles_cheques += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado" && n.value != "S" && chk) { error = "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria"; }
			}
			var len = detalles_cheques.length; len-=1;
			detalles_cheques = detalles_cheques.substr(0, len);
		}
		
		else if (frm == "terceros") {
			//	listado (pago a terceros)
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				var chk = false;
				if (n.name == "terceros" && n.checked) { detalles_terceros += n.value + ";"; chk = true; }
				else if (n.name == "FlagVerificado" && n.value != "S" && chk) { error = "Se encontraron Obligaciones sin Verificaci&oacute;n Presupuestaria"; }
			}
			var len = detalles_terceros.length; len-=1;
			detalles_terceros = detalles_terceros.substr(0, len);
		}
		
		else if (frm == "judiciales") {
			//	listado (retenciones judiciales)
			var frm_judiciales = document.getElementById("frm_judiciales");
			for(var i=0; n=frm_judiciales.elements[i]; i++) {
				if (n.name == "judiciales" && n.checked) detalles_judiciales += n.value + ";";
			}
			var len = detalles_judiciales.length; len-=1;
			detalles_judiciales = detalles_judiciales.substr(0, len);
		}
		
		//	valido que selecciono algun registro
		if (detalles_bancos == "" && detalles_cheques == "" && detalles_terceros == "" && detalles_judiciales == "") error = "Debe seleccionar por lo menos una Obligaci&oacute;n por Generar";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=generar&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&detalles_bancos="+detalles_bancos+"&detalles_cheques="+detalles_cheques+"&detalles_terceros="+detalles_terceros+"&detalles_judiciales="+detalles_judiciales+"&frm="+frm,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 500);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se generaron las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (consolidar)
function interfase_cuentas_por_pagar_consolidar(frm) {
	$(".div-progressbar").css("display", "block");
	
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var detalles_bancos = "";
	var detalles_cheques = "";
	var detalles_terceros = "";
	var detalles_judiciales = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		if (frm == "bancos") {
			//	listado (interfase bancaria)
			var nro_bancos = 0;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { detalles_bancos += n.value + ";"; ++nro_bancos; }
			}
			var len = detalles_bancos.length; len-=1;
			detalles_bancos = detalles_bancos.substr(0, len);
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos < 2) error = "Debe seleccionar mas una Obligaci&oacute;n por Consolidar";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=consolidar&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&detalles_bancos="+detalles_bancos+"&frm="+frm,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 450);
				else {
					var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Se consolidaron las obligaciones exitosamente", "exito", 400, funct);
				}
			}
		});
	}
}
//	-------------------------------

// 	interfase de cuentas por pagar (verificar)
function interfase_cuentas_por_pagar_verificar(form) {
	$(".div-progressbar").css("display", "block");
	
	var error = "";
	//	partidas
	var frm_partidas = document.getElementById("frm_partidas");
	for(var i=0; n=frm_partidas.elements[i]; i++) {
		if (n.name == "Diferencia") {
			var Diferencia = parseFloat(n.value);
			if (Diferencia < 0) { error = "Se encontraron partidas sin Disponibilidad"; break; }
		}
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=interfase_cuentas_por_pagar&accion=verificar&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else {
					var funct = "parent.$.prettyPhoto.close();";
					funct += "parent.document.getElementById('frmentrada').submit();";
					cajaModal("Presupuesto verificado exitosamente", "exito", 400, funct);
				}
			}
		});
	}
	return false;
}*/
//	-------------------------------
