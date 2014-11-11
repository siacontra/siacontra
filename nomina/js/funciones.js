// JavaScript Document

// 	interfase de cuentas por pagar (abrir check)
function interfase_cuentas_por_pagar_abrir_check(frm) {
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var registro = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		var nro_bancos = 0;
		var nro_cheques = 0;
		var nro_terceros = 0;
		
		//	listado (interfase bancaria)
		if (frm == "bancos") {
			var chk = false;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { registro = n.value; ++nro_bancos; chk = true; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	listado (cheques)
		else if (frm == "cheques") {
			var chk = false;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { registro = n.value; ++nro_cheques; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	listado (terceros)
		else if (frm == "terceros") {
			var chk = false;
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				if (n.name == "terceros" && n.checked) { registro = n.value; ++nro_terceros; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos > 1 || nro_cheques > 1 || nro_terceros > 1) error = "Debe seleccionar solo una Obligaci&oacute;n";
		else if (nro_bancos < 1 && nro_cheques < 1 && nro_terceros < 1) error = "Debe seleccionar una Obligaci&oacute;n";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		var href = "gehen.php?anz=pr_interfase_cuentas_por_pagar_verificar&filtrar=default&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&registro="+registro+"&iframe=true&width=800&height=450";
		$("#a_check").attr("href", href);
		document.getElementById('a_check').click();
	}
}
//	-------------------------------


// 	interfase de cuentas por pagar (abrir check)
/*function interfase_cuentas_por_pagar_abrir_check(frm) {
	//	valores
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	var CodTipoProceso = $("#fCodTipoProceso").val();
	var registro = "";
	
	//	valido
	var error = "";
	if (CodOrganismo == "") error = "Debe seleccionar el Organismo";
	else if (CodTipoNom == "") error = "Debe seleccionar la N&oacute;mina";
	else if (Periodo == "") error = "Debe seleccionar el Periodo";
	else if (CodTipoProceso == "") error = "Debe seleccionar el Proceso";
	
	if (error == "") {
		var nro_bancos = 0;
		var nro_cheques = 0;
		var nro_terceros = 0;
		
		//	listado (interfase bancaria)
		if (frm == "bancos") {
			var chk = false;
			var frm_bancos = document.getElementById("frm_bancos");
			for(var i=0; n=frm_bancos.elements[i]; i++) {
				if (n.name == "bancos" && n.checked) { registro = n.value; ++nro_bancos; chk = true; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	listado (cheques)
		else if (frm == "cheques") {
			var chk = false;
			var frm_cheques = document.getElementById("frm_cheques");
			for(var i=0; n=frm_cheques.elements[i]; i++) {
				if (n.name == "cheques" && n.checked) { registro = n.value; ++nro_cheques; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	listado (terceros)
		else if (frm == "terceros") {
			var chk = false;
			var frm_terceros = document.getElementById("frm_terceros");
			for(var i=0; n=frm_terceros.elements[i]; i++) {
				if (n.name == "terceros" && n.checked) { registro = n.value; ++nro_terceros; }
				else if (n.name == "FlagVerificado") {
					if (chk && n.value == "S") error = "La Obligaci&oacute;n ya se encuentra Verificada";
					chk = false;
				}
			}
		}
		
		//	valido que selecciono algun registro
		if (nro_bancos > 1 || nro_cheques > 1 || nro_terceros > 1) error = "Debe seleccionar solo una Obligaci&oacute;n";
		else if (nro_bancos < 1 && nro_cheques < 1 && nro_terceros < 1) error = "Debe seleccionar una Obligaci&oacute;n";
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 450);
	} else {
		var href = "gehen.php?anz=pr_interfase_cuentas_por_pagar_verificar&filtrar=default&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&CodTipoProceso="+CodTipoProceso+"&registro="+registro+"&iframe=true&width=800&height=450";
		$("#a_check").attr("href", href);
		document.getElementById('a_check').click();
	}
}*/
//	-------------------------------

function setFormula(valor, tipo, descripcion) {
	$(".last").removeClass("last");
	if (tipo == "numero") { 
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#F60;'>"+valor+"</span>";
	}
	else if (tipo == "char") { 
		var span = "<span class='span-selected last' onclick='selSpan($(this));' style='color:#000;'>"+valor+"</span>";
	}
	else if (tipo == "signo") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#000;'> "+valor+" </span> ";
	}
	else if (tipo == "salto") {
		var span = " <span onclick='selSpan($(this));' style='color:#000;'> "+valor+" </span> <br class='span-selected last' />";
	}
	else if (tipo == "var") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#333;'> "+valor+" </span> ";
	}
	else if (tipo == "var-total") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#333; font-weight:bold;'> "+valor+" </span> ";
	}
	else if (tipo == "espacio") {
		var span = "<span class='span-selected last' onclick='selSpan($(this));'>&nbsp;</span>";
	}
	else if (tipo == "enter") {
		var span = "<br class='span-selected last' />";
	}
	else if (tipo == "variable") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#090;' title='"+descripcion+"'> $_"+valor+" </span> ";
	}
	else if (tipo == "parametro") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#330;' title='"+descripcion+"'> $_"+valor+" </span> ";
	}
	else if (tipo == "concepto") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#900;' title='"+descripcion+"'> $_"+valor+" </span> ";
	}
	else if (tipo == "funcion") {
		var span = " <span class='span-selected last' onclick='selSpan($(this));' style='color:#06F;' title='"+descripcion+"'> "+valor+"() </span> ";
	}
	
	if ($("#Formula").text().trim() == "") $("#Formula").append(span);	
	else $(span).insertAfter(".span-selected");
	$(".span-selected").removeClass("span-selected").addClass("span");
	$(".last").addClass("span-selected");
}
//	-------------------------------

//	
function selSpan(span) {
	$(".span-selected").removeClass("span-selected").addClass("span");
	span.addClass("span-selected");
}
//	-------------------------------
