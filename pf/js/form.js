// JavaScript Document

//	organismos externos
function organismos_externos(form, accion) {
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
		if ((n.id == "Organismo" || n.id == "DescripComp" || n.id == "CodCiudad") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (n.id == "CodDependencia" && n.value.trim() == "" && document.getElementById("FlagSujetoControl").checked) {
			error = "Debe selecionar la dependencia interna al que esta sujeto a control";
			break;
		}
		else if (!valFecha(n.value) && n.id == "FechaFundac") { error = "Formato del valor de la fecha es incorrecta"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=organismos_externos&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	dependencias externas
function dependencias_externas(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "Dependencia" || n.id == "Representante" || n.id == "Cargo") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=dependencias_externas&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	procesos
function procesos(form, accion) {
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
			data: "modulo=procesos&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	fases
function fases(form, accion) {
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
			data: "modulo=fases&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	actividades
function actividades(form, accion) {
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
		if ((n.id == "Descripcion" || n.id == "CodFase" || n.id == "Duracion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((n.id == "Duracion") && isNaN(n.value)) { error = "Debe ingresar en la duración un valor numérico"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=actividades&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	tipo de actuacion
function tipo_actuacion(form, accion) {
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
			data: "modulo=tipo_actuacion&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	actuacion fiscal
function actuacion_fiscal(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodTipoActuacion" || n.id == "FechaInicio" || n.id == "CodProceso" || n.id == "ObjetivoGeneral" || n.id == "CodOrganismoExterno") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaInicio") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		else if (!valAlfaNumerico(n.value)) { error = n.value + "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado auditores
	var detalles_auditores = "";
	var frm_auditores = document.getElementById("frm_auditores");
	for(var i=0; n=frm_auditores.elements[i]; i++) {
		if (n.name == "CodPersona") detalles_auditores += n.value + "|";
		else if (n.name == "FlagCoordinador") {
			if (n.checked) detalles_auditores += "S;";
			else detalles_auditores += "N;";
		}
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Duracion") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicio") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTermino") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=actuacion_fiscal&accion="+accion+"&"+post+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	actuacion fiscal (terminar actividad)
function actuacion_fiscal_actividades_terminar(form, accion) {
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
		if ((n.id == "FechaTerminoCierre") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaTerminoCierre") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaRegistroCierre.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha actual"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) < formatFechaAMD(form.FechaInicioReal.value)) { error = "La fecha de termino de la actividad no puede ser menor a la fecha de inicio"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaTerminoReal.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha planificada de termino<br>Debe agregar una prorroga a la actividad"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado documentos
	var detalles_documentos = "";
	var frm_documentos = document.getElementById("frm_documentos");
	for(var i=0; n=frm_documentos.elements[i]; i++) {
		if (n.name == "Documento") detalles_documentos += n.value + "|";
		else if (n.name == "NroDocumento") detalles_documentos += n.value + "|";
		else if (n.name == "Fecha") {
			if (!valFecha(n.value)) { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
			else detalles_documentos += n.value + ";";
		}
	}
	var len = detalles_documentos.length; len--;
	detalles_documentos = detalles_documentos.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	confirmo
		if ($("#Duracion").val() != $("#DiasCierre").val() && $("#FlagNoAfectoPlan").val() == "N") {
		$("#cajaModal").dialog({
			buttons: {
				"Si": function() {
					$(this).dialog("close");
					$.ajax({
						type: "POST",
						url: "lib/form_ajax.php",
						data: "modulo=actuacion_fiscal_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=true",
						async: false,
						success: function(resp) {
							if (resp.trim() != "") cajaModal(resp, "error", 400);
							else form.submit();
						}
					});
				},			
				"No": function() {
					$(this).dialog("close");
					$.ajax({
						type: "POST",
						url: "lib/form_ajax.php",
						data: "modulo=actuacion_fiscal_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
						async: false,
						success: function(resp) {
							if (resp.trim() != "") cajaModal(resp, "error", 400);
							else form.submit();
						}
					});
				}
			}
		});	
		$("#cajaModal").dialog({ 
			title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
			width: 400
		});
		$("#cajaModal").html("¿Desea reprogramar las actividades?");
		$('#cajaModal').dialog('open');
	} else {		
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=actuacion_fiscal_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	}
	return false;
}

//	actuacion fiscal (prorrogas)
function actuacion_fiscal_prorrogas(form, accion) {
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
		if ((n.id == "CodActuacion" || n.id == "Motivo") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((n.id == "Prorroga") && (n.value.trim() == "" || n.value.trim() == "0")) { error = "La prorroga debe ser mayor a cero dias"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Estado") detalles_actividades += n.value + "|";
		else if (n.name == "ProrrogaAcu") detalles_actividades += n.value + "|";
		else if (n.name == "Prorroga") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicioReal") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTerminoReal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=actuacion_fiscal_prorrogas&accion="+accion+"&"+post+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	valoracion juridica
function valoracion_juridica(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodTipoActuacion" || n.id == "FechaInicio" || n.id == "CodProceso" || n.id == "ObjetivoGeneral" || n.id == "CodOrganismoExterno") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaInicio") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado auditores
	var detalles_auditores = "";
	var frm_auditores = document.getElementById("frm_auditores");
	for(var i=0; n=frm_auditores.elements[i]; i++) {
		if (n.name == "CodPersona") detalles_auditores += n.value + "|";
		else if (n.name == "FlagCoordinador") {
			if (n.checked) detalles_auditores += "S;";
			else detalles_auditores += "N;";
		}
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Duracion") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicio") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTermino") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=valoracion_juridica&accion="+accion+"&"+post+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "generar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	valoracion juridica (prorrogas)
function valoracion_juridica_prorrogas(form, accion) {
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
		if ((n.id == "CodValJur" || n.id == "Motivo") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((n.id == "Prorroga") && (n.value.trim() == "" || n.value.trim() == "0")) { error = "La prorroga debe ser mayor a cero dias"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Estado") detalles_actividades += n.value + "|";
		else if (n.name == "ProrrogaAcu") detalles_actividades += n.value + "|";
		else if (n.name == "Prorroga") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicioReal") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTerminoReal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=valoracion_juridica_prorrogas&accion="+accion+"&"+post+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	valoracion juridica (terminar actividad)
function valoracion_juridica_actividades_terminar(form, accion) {
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
		if ((n.id == "FechaTerminoCierre") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaTerminoCierre") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaRegistroCierre.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha actual"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) < formatFechaAMD(form.FechaInicioReal.value)) { error = "La fecha de termino de la actividad no puede ser menor a la fecha de inicio"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaTerminoReal.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha planificada de termino<br>Debe agregar una prorroga a la actividad"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado documentos
	var detalles_documentos = "";
	var frm_documentos = document.getElementById("frm_documentos");
	for(var i=0; n=frm_documentos.elements[i]; i++) {
		if (n.name == "Documento") detalles_documentos += n.value + "|";
		else if (n.name == "NroDocumento") detalles_documentos += n.value + "|";
		else if (n.name == "Fecha") {
			if (!valFecha(n.value)) { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
			else detalles_documentos += n.value + ";";
		}
	}
	var len = detalles_documentos.length; len--;
	detalles_documentos = detalles_documentos.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	confirmo
		if ($("#Duracion").val() != $("#DiasCierre").val() && $("#FlagNoAfectoPlan").val() == "N") {
			$("#cajaModal").dialog({
				buttons: {
					"Si": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=valoracion_juridica_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=true",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					},			
					"No": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=valoracion_juridica_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					}
				}
			});	
			$("#cajaModal").dialog({ 
				title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
				width: 400
			});
			$("#cajaModal").html("¿Desea reprogramar las actividades?");
			$('#cajaModal').dialog('open');
		} else {		
			$.ajax({
				type: "POST",
				url: "lib/form_ajax.php",
				data: "modulo=valoracion_juridica_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
				async: false,
				success: function(resp) {
					if (resp.trim() != "") cajaModal(resp, "error", 400);
					else form.submit();
				}
			});
		}
	}
	return false;
}

//	potestad investigativa
function potestad_investigativa(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodTipoActuacion" || n.id == "FechaInicio" || n.id == "CodProceso" || n.id == "ObjetivoGeneral" || n.id == "CodOrganismoExterno") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaInicio") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado auditores
	var detalles_auditores = "";
	var frm_auditores = document.getElementById("frm_auditores");
	for(var i=0; n=frm_auditores.elements[i]; i++) {
		if (n.name == "CodPersona") detalles_auditores += n.value + "|";
		else if (n.name == "FlagCoordinador") {
			if (n.checked) detalles_auditores += "S;";
			else detalles_auditores += "N;";
		}
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Duracion") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicio") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTermino") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=potestad_investigativa&accion="+accion+"&"+post+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "generar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	potestad investigativa (prorrogas)
function potestad_investigativa_prorrogas(form, accion) {
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
		if ((n.id == "CodValJur" || n.id == "Motivo") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((n.id == "Prorroga") && (n.value.trim() == "" || n.value.trim() == "0")) { error = "La prorroga debe ser mayor a cero dias"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Estado") detalles_actividades += n.value + "|";
		else if (n.name == "ProrrogaAcu") detalles_actividades += n.value + "|";
		else if (n.name == "Prorroga") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicioReal") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTerminoReal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=potestad_investigativa_prorrogas&accion="+accion+"&"+post+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	potestad investigativa (terminar actividad)
function potestad_investigativa_actividades_terminar(form, accion) {
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
		if ((n.id == "FechaTerminoCierre") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaTerminoCierre") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaRegistroCierre.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha actual"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) < formatFechaAMD(form.FechaInicioReal.value)) { error = "La fecha de termino de la actividad no puede ser menor a la fecha de inicio"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaTerminoReal.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha planificada de termino<br>Debe agregar una prorroga a la actividad"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado documentos
	var detalles_documentos = "";
	var frm_documentos = document.getElementById("frm_documentos");
	for(var i=0; n=frm_documentos.elements[i]; i++) {
		if (n.name == "Documento") detalles_documentos += n.value + "|";
		else if (n.name == "NroDocumento") detalles_documentos += n.value + "|";
		else if (n.name == "Fecha") {
			if (!valFecha(n.value)) { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
			else detalles_documentos += n.value + ";";
		}
	}
	var len = detalles_documentos.length; len--;
	detalles_documentos = detalles_documentos.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	confirmo
		if ($("#Duracion").val() != $("#DiasCierre").val() && $("#FlagNoAfectoPlan").val() == "N") {
			$("#cajaModal").dialog({
				buttons: {
					"Si": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=potestad_investigativa_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=true",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					},			
					"No": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=potestad_investigativa_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					}
				}
			});	
			$("#cajaModal").dialog({ 
				title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
				width: 400
			});
			$("#cajaModal").html("¿Desea reprogramar las actividades?");
			$('#cajaModal').dialog('open');
		} else {		
			$.ajax({
				type: "POST",
				url: "lib/form_ajax.php",
				data: "modulo=potestad_investigativa_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
				async: false,
				success: function(resp) {
					if (resp.trim() != "") cajaModal(resp, "error", 400);
					else form.submit();
				}
			});
		}
	}
	return false;
}

//	determinacion de responsabilidad
function determinacion_responsabilidad(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "CodTipoActuacion" || n.id == "FechaInicio" || n.id == "CodProceso" || n.id == "ObjetivoGeneral" || n.id == "CodOrganismoExterno") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaInicio") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado auditores
	var detalles_auditores = "";
	var frm_auditores = document.getElementById("frm_auditores");
	for(var i=0; n=frm_auditores.elements[i]; i++) {
		if (n.name == "CodPersona") detalles_auditores += n.value + "|";
		else if (n.name == "FlagCoordinador") {
			if (n.checked) detalles_auditores += "S;";
			else detalles_auditores += "N;";
		}
	}
	var len = detalles_auditores.length; len--;
	detalles_auditores = detalles_auditores.substr(0, len);
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Duracion") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicio") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTermino") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=determinacion_responsabilidad&accion="+accion+"&"+post+"&detalles_auditores="+detalles_auditores+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "generar") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	determinacion de responsabilidad (prorrogas)
function determinacion_responsabilidad_prorrogas(form, accion) {
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
		if ((n.id == "CodValJur" || n.id == "Motivo") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((n.id == "Prorroga") && (n.value.trim() == "" || n.value.trim() == "0")) { error = "La prorroga debe ser mayor a cero dias"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado actividades
	var detalles_actividades = "";
	var frm_actividades = document.getElementById("frm_actividades");
	for(var i=0; n=frm_actividades.elements[i]; i++) {
		if (n.name == "CodActividad") detalles_actividades += n.value + "|";
		else if (n.name == "Estado") detalles_actividades += n.value + "|";
		else if (n.name == "ProrrogaAcu") detalles_actividades += n.value + "|";
		else if (n.name == "Prorroga") detalles_actividades += n.value + "|";
		else if (n.name == "FechaInicioReal") detalles_actividades += n.value + "|";
		else if (n.name == "FechaTerminoReal") detalles_actividades += n.value + ";";
	}
	var len = detalles_actividades.length; len--;
	detalles_actividades = detalles_actividades.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=determinacion_responsabilidad_prorrogas&accion="+accion+"&"+post+"&detalles_actividades="+detalles_actividades,
			async: false,
			success: function(resp) {
				var partes = resp.split("|.|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") {
						var funct = "document.getElementById('frmentrada').submit();";
						cajaModal(partes[1], "info", 400, funct);
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}

//	determinacion de responsabilidad (terminar actividad)
function determinacion_responsabilidad_actividades_terminar(form, accion) {
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
		if ((n.id == "FechaTerminoCierre") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valFecha(n.value) && n.id == "FechaTerminoCierre") { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
		//else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaRegistroCierre.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha actual"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) < formatFechaAMD(form.FechaInicioReal.value)) { error = "La fecha de termino de la actividad no puede ser menor a la fecha de inicio"; break; }
		else if (formatFechaAMD(form.FechaTerminoCierre.value) > formatFechaAMD(form.FechaTerminoReal.value)) { error = "La fecha de termino de la actividad no puede ser mayor a la fecha planificada de termino<br>Debe agregar una prorroga a la actividad"; break; }
		//else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	listado documentos
	var detalles_documentos = "";
	var frm_documentos = document.getElementById("frm_documentos");
	for(var i=0; n=frm_documentos.elements[i]; i++) {
		if (n.name == "Documento") detalles_documentos += n.value + "|";
		else if (n.name == "NroDocumento") detalles_documentos += n.value + "|";
		else if (n.name == "Fecha") {
			if (!valFecha(n.value)) { error = "Formato de Fecha Incorrecta (dd-mm-aaaa)"; break; }
			else detalles_documentos += n.value + ";";
		}
	}
	var len = detalles_documentos.length; len--;
	detalles_documentos = detalles_documentos.substr(0, len);
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	confirmo
		if ($("#Duracion").val() != $("#DiasCierre").val() && $("#FlagNoAfectoPlan").val() == "N") {
			$("#cajaModal").dialog({
				buttons: {
					"Si": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=determinacion_responsabilidad_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=true",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					},			
					"No": function() {
						$(this).dialog("close");
						$.ajax({
							type: "POST",
							url: "lib/form_ajax.php",
							data: "modulo=determinacion_responsabilidad_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
							async: false,
							success: function(resp) {
								if (resp.trim() != "") cajaModal(resp, "error", 400);
								else form.submit();
							}
						});
					}
				}
			});	
			$("#cajaModal").dialog({ 
				title: "<img src='../imagenes/info.png' width='24' align='absmiddle' />Confirmación", 
				width: 400
			});
			$("#cajaModal").html("¿Desea reprogramar las actividades?");
			$('#cajaModal').dialog('open');
		} else {		
			$.ajax({
				type: "POST",
				url: "lib/form_ajax.php",
				data: "modulo=determinacion_responsabilidad_actividades_terminar&"+post+"&detalles_documentos="+detalles_documentos+"&reprogramar=false",
				async: false,
				success: function(resp) {
					if (resp.trim() != "") cajaModal(resp, "error", 400);
					else form.submit();
				}
			});
		}
	}
	return false;
}