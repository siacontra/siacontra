// JavaScript Document

//	empleados
function empleados(form, accion) {
	//	valido
	var error = "";
	if ($("#Apellido2").val().trim() == "" || $("#Nombres").val().trim() == "" || $("#Sexo").val().trim() == "" || $("#CiudadNacimiento").val().trim() == "" || $("#Fnacimiento").val().trim() == "" || $("#Direccion").val().trim() == "" || $("#CiudadDomicilio").val().trim() == "" || $("#TipoDocumento").val().trim() == "" || $("#Ndocumento").val().trim() == "" || $("#Nacionalidad").val().trim() == "" || $("#CodOrganismo").val().trim() == "" || $("#CodDependencia").val().trim() == "" || $("#CodTipoNom").val().trim() == "" || $("#CodPerfil").val().trim() == "" || $("#CodTipoPago").val().trim() == "" || $("#CodTipoTrabajador").val().trim() == "" || $("#EstadoCivil").val().trim() == "" || $("#Fingreso").val().trim() == "" || $("#CodCargo").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#Fnacimiento").val())) error = "Formato de Fecha de Nacimiento incorrecta";
	else if (!valFecha($("#FedoCivil").val()) && $("#FedoCivil").val().trim() != "") error = "Formato de Fecha de Edo. Civil incorrecta";
	else if (!valFecha($("#Fingreso").val())) error = "Formato de Fecha de Ingreso incorrecta";
	else if ($("#SitTraI").attr("checked") == "checked" && $("#CodMotivoCes").val() == "" && $("#Fegreso").val() == "") error = "Debe ingresar el Motivo de Cese y la Fecha de Egreso";
	else if (!valFecha($("#Fegreso").val()) && $("#Fegreso").val().trim() != "") error = "Formato de Fecha de Egreso incorrecta";
	
	//	formulario
	var opcion = $("#opcion").val();
	var post = "";
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
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados&accion="+accion+"&"+post+"&opcion="+opcion,
			async: false,
			success: function(resp){
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					form.target = "";
					form.action = "gehen.php?anz=empleados_lista&_CodEmpleado="+partes[1]+"&_CodPersona="+partes[2];
					if (accion == "nuevo") {
						var funct = "";
						if (opcion == "contratar") {
							funct += "var frame = parent.document.getElementById('postulantes');";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_postulantes_lista';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').target = '';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_evaluaciones_lista&FlagMostrar=N';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').target = 'evaluaciones';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=N';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').target = 'competencias';";
							funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
							funct += "parent.$.prettyPhoto.close();";
							cajaModal("Empleado <strong>Nro. "+partes[1]+"</strong> se registr&oacute; exitosamente", "exito", 400, funct);
						}
						else {
							var funct = "document.getElementById('frmentrada').submit();";
							cajaModal("Empleado <strong>Nro. "+partes[1]+"</strong> se registr&oacute; exitosamente", "exito", 400, funct);
						}
					}
					else form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (nivelaciones)
function empleados_nivelaciones(form) {
	//	valido
	var error = "";
	if ($("#Fecha").val().trim() == "" || $("#TipoAccion").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#Fecha").val())) error = "Formato de Fecha incorrecta";
	else if ($("#CodOrganismo").val() == $("#CodOrganismoAnt").val() && $("#CodDependencia").val() == $("#CodDependenciaAnt").val() && $("#CodTipoNom").val() == $("#CodTipoNomAnt").val() && $("#CodCargo").val() == $("#CodCargoAnt").val()) error = "No ha realizado ning&uacute;n movimiento para la nivelaci&oacute;n";
	
	//	formulario
	var opcion = $("#opcion").val();
	var post = "";
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
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_nivelaciones&"+post+"&opcion="+opcion,
			async: false,
			success: function(resp){
				var partes = resp.split("|");
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					var funct = "";
					if (opcion == "contratar") {
						funct += "var frame = parent.document.getElementById('postulantes');";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_postulantes_lista';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').target = '';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_evaluaciones_lista&FlagMostrar=N';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').target = 'evaluaciones';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').action = 'gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=N';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').target = 'competencias';";
						funct += "frame.contentWindow.document.getElementById('frm_candidato').submit();";
						funct += "parent.$.prettyPhoto.close();";
					}
					else var funct = "document.getElementById('frmentrada').submit();";
					cajaModal("Nivelaci&oacute;n realizada con &eacute;xito", "exito", 400, funct);
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (vacaciones)
function empleado_vacaciones() {
	//	valido
	var error = "";
	var CodPersona = $("#CodPersona").val();
	var NroPeriodo_utilizacion = $("#NroPeriodo_utilizacion").val();
	
	//	periodos
	var detalles_periodos = "";
	var frm = document.getElementById("frm_periodos");
	for(var i=0; n=frm.elements[i]; i++) {
		if (n.name == "NroPeriodo") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Anio") detalles_periodos += n.value + ";char:td;";
		else if (n.name == "Mes") {
			var Mes = new Number(parseInt(setNumero(n.value)));
			if (isNaN(Mes) || Mes < 1 || Mes > 12) { error = "Mes Programado Incorrecto"; break; }
			else detalles_periodos += n.value + ";char:td;";
		}
		else if (n.name == "Derecho") {
			var Derecho = new Number(parseFloat(setNumero(n.value)));
			if (isNaN(Derecho) || Derecho <= 0) { error = "Dias x Derecho Incorrecto"; break; }
			else detalles_periodos += Derecho + ";char:td;";
		}
		else if (n.name == "PendientePeriodo") {
			var PendientePeriodo = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += PendientePeriodo + ";char:td;";
		}
		else if (n.name == "DiasGozados") {
			var DiasGozados = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += DiasGozados + ";char:td;";
		}
		else if (n.name == "DiasTrabajados") {
			var DiasTrabajados = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += DiasTrabajados + ";char:td;";
		}
		else if (n.name == "DiasInterrumpidos") {
			var DiasInterrumpidos = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += DiasInterrumpidos + ";char:td;";
		}
		else if (n.name == "TotalUtilizados") {
			var TotalUtilizados = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += TotalUtilizados + ";char:td;";
		}
		else if (n.name == "Pendientes") {
			var Pendientes = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += Pendientes + ";char:td;";
		}
		else if (n.name == "PagosRealizados") {
			var PagosRealizados = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += PagosRealizados + ";char:td;";
		}
		else if (n.name == "PendientePago") {
			var PendientePago = new Number(parseFloat(setNumero(n.value)));
			detalles_periodos += PendientePago + ";char:tr;";
		}
	}
	var len = detalles_periodos.length; len-=9;
	detalles_periodos = detalles_periodos.substr(0, len);
	
	//	utilizacion
	var detalles_utilizacion = "";
	var frm = document.getElementById("frm_utilizacion");
	for(var i=0; n=frm.elements[i]; i++) {
		if (n.name == "NroPeriodo") detalles_utilizacion += n.value + ";char:td;";
		else if (n.name == "TipoUtilizacion") detalles_utilizacion += n.value + ";char:td;";
		else if (n.name == "DiasUtiles") {
			var DiasUtiles = new Number(parseFloat(setNumero(n.value)));
			if (isNaN(DiasUtiles) || DiasUtiles <= 0) { error = "Dias de Utilizaci&oacute;n Incorrecta (Lista de Utilizaci&oacute;n)"; break; }
			else detalles_utilizacion += DiasUtiles + ";char:td;";
		}
		else if (n.name == "FechaInicio") {
			var FechaInicio = formatFechaAMD(n.value);
			if (!valFecha(n.value)) { error = "Fecha de Salida Incorrecta (Lista de Utilizaci&oacute;n)"; break; }
			else detalles_utilizacion += FechaInicio + ";char:td;";
		}
		else if (n.name == "FechaFin") {
			var FechaFin = formatFechaAMD(n.value);
			if (!valFecha(n.value)) { error = "Fecha de T&eacute;rmino Incorrecta (Lista de Utilizaci&oacute;n)"; break; }
			else if (FechaInicio > FechaFin) { error = "Intervalo de Fechas Incorrecta (Lista de Utilizaci&oacute;n)"; break; }
			else detalles_utilizacion += FechaFin + ";char:tr;";
		}
	}
	var len = detalles_utilizacion.length; len-=9;
	detalles_utilizacion = detalles_utilizacion.substr(0, len);
	
	//	pagos
	var detalles_pagos = "";
	/*var frm = document.getElementById("frm_utilizacion");
	for(var i=0; n=frm.elements[i]; i++) {
		if (n.name == "TipoUtilizacion") detalles_utilizacion += n.value + ";char:td;";
		else if (n.name == "DiasUtiles") {
			var DiasUtiles = new Number(parseFloat(setNumero(n.value)));
			if (isNaN(DiasUtiles) || DiasUtiles <= 0) { error = "Dias de Utilizaci&oacute;n Incorrecta"; break; }
			else detalles_utilizacion += n.value + ";char:td;";
		}
		else if (n.name == "FechaInicio") {
			var FechaInicio = formatFechaAMD(n.value);
			if (!valFecha(n.value)) { error = "Fecha de Salida Incorrecta"; break; }
			else detalles_utilizacion += n.value + ";char:td;";
		}
		else if (n.name == "FechaFin") {
			var FechaFin = formatFechaAMD(n.value);
			if (!valFecha(n.value)) { error = "Fecha de T&eacute;rmino Incorrecta"; break; }
			else if (FechaInicio > FechaFin) { error = "Intervalo de Fechas Incorrecta"; break; }
			else detalles_utilizacion += n.value + ";char:tr;";
		}
	}
	var len = detalles_utilizacion.length; len-=9;
	detalles_utilizacion = detalles_utilizacion.substr(0, len);*/
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleado_vacaciones&accion=actualizar&CodPersona="+CodPersona+"&detalles_periodos="+detalles_periodos+"&detalles_utilizacion="+detalles_utilizacion+"&detalles_pagos="+detalles_pagos+"&NroPeriodo_utilizacion="+NroPeriodo_utilizacion,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else document.getElementById("frmentrada").submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (carga familiar)
function empleados_carga_familiar(form, accion) {
	//	valido
	var error = "";
	if ($("#Parentesco").val() == "" || $("#Sexo").val() == "" || $("#ApellidosCarga").val().trim() == "" || $("#NombresCarga").val().trim() == "" || $("#FechaNacimiento").val().trim() == "" || $("#EstadoCivil").val() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#FechaNacimiento").val())) error = "Formato de Fecha de Nacimiento incorrecta";
	else if (!valFecha($("#FechaBaja").val()) && $("#MotivoBaja").val() != "") error = "Formato de Fecha de Baja incorrecta";
	else if (parseInt($("#EdadAnios").val()) >= 10 && ($("#TipoDocumento").val() == "" || $("#Ndocumento").val().trim() == "")) error = "El campo <strong>Tipo de documento y Nro. de Documento</strong> es Obligatorio para personas mayores a <strong>9 años</strong>";
	
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
			data: "modulo=empleados_carga_familiar&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (instruccion - carreras)
function empleados_instruccion_carreras(form, accion) {
	//	valido
	var error = "";
	if ($("#CodGradoInstruccion").val() == "" || $("#CodCentroEstudio").val() == "" || $("#FechaGraduacion").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#FechaGraduacion").val())) error = "Formato de Fecha de Graduaci&oacute;n incorrecta";
	else if (!valFecha($("#FechaDesde").val()) || !valFecha($("#FechaHasta").val()) || formatFechaAMD($("#FechaDesde").val()) > formatFechaAMD($("#FechaHasta").val())) error = "Formato de Fecha del Periodo de Estudio incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_instruccion_carreras&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (instruccion - idiomas)
function empleados_instruccion_idiomas(form, accion) {
	//	valido
	var error = "";
	if ($("#Codidioma").val() == "" || $("#NivelLectura").val() == "" || $("#NivelOral").val() == "" || $("#NivelEscritura").val() == "" || $("#NivelGeneral").val() == "") error = "Debe ingresar los campos obligatorios";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_instruccion_idiomas&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (instruccion - cursos)
function empleados_instruccion_cursos(form, accion) {
	//	valido
	var error = "";
	if ($("#CodCurso").val() == "" || $("#CodCentroEstudio").val() == "" || $("#TipoCurso").val() == "" || $("#FechaCulminacion").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valPeriodo($("#FechaCulminacion").val())) error = "Formato de Periodo de Culminaci&oacute;n incorrecta";
	else if (!valFecha($("#FechaDesde").val()) || !valFecha($("#FechaHasta").val()) || formatFechaAMD($("#FechaDesde").val()) > formatFechaAMD($("#FechaHasta").val())) error = "Formato de Fecha del Periodo de Estudio incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_instruccion_cursos&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (informacion bancaria)
function empleados_bancaria(form, accion) {
	//	valido
	var error = "";
	if ($("#CodBanco").val() == "" || $("#TipoCuenta").val() == "" || $("#Aportes").val() == "" || $("#Ncuenta").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (isNaN(setNumero($("#Monto").val()))) error = "Formato de Monto incorrecto";
	
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
			data: "modulo=empleados_bancaria&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (islr)
function empleados_islr(form, accion) {
	//	valido
	var error = "";
	if ($("#Anio").val().trim() == "" || $("#Desde").val().trim() == "" || $("#Hasta").val().trim() == "" || $("#Porcentaje").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (isNaN(setNumero($("#Porcentaje").val()))) error = "Formato de Porcentaje incorrecto";
	
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
			data: "modulo=empleados_islr&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (experiencia laboral)
function empleados_experiencia_laboral(form, accion) {
	//	valido
	var error = "";
	if ($("#Empresa").val().trim() == "" || $("#TipoEntidad").val() == "" || $("#MotivoCese").val() == "" || $("#FechaDesde").val().trim() == "" || $("#FechaHasta").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#FechaDesde").val()) || !valFecha($("#FechaHasta").val()) || formatFechaAMD($("#FechaDesde").val()) > formatFechaAMD($("#FechaHasta").val())) error = "Formato de Periodo incorrecto";
	else if (isNaN(setNumero($("#Sueldo").val()))) error = "Formato de Sueldo incorrecto";
	
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
			data: "modulo=empleados_experiencia_laboral&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (referencias - laborales)
function empleados_referencias_laborales(form, accion) {
	//	valido
	var error = "";
	if ($("#Nombre").val().trim() == "" || $("#Empresa").val().trim() == "" || $("#Cargo").val().trim() == "" || $("#Telefono").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_referencias_laborales&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (referencias - personales)
function empleados_referencias_personales(form, accion) {
	//	valido
	var error = "";
	if ($("#Nombre").val().trim() == "" || $("#Empresa").val().trim() == "" || $("#Telefono").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_referencias_personales&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (meritos)
function empleados_meritos(form, accion) {
	//	valido
	var error = "";
	if ($("#Clasificacion").val() == "" || $("#Documento").val().trim() == "" || $("#FechaDoc").val().trim() == "" || ($("#Responsable").val() == "" && $("#Externo").val().trim() == "")) error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#FechaDoc").val())) error = "Formato de Fecha Incorrecta";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_meritos&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (demeritos)
function empleados_demeritos(form, accion) {
	//	valido
	var error = "";
	if ($("#Clasificacion").val() == "" || $("#Documento").val().trim() == "" || $("#FechaDoc").val().trim() == "" || ($("#Responsable").val() == "" && $("#Externo").val().trim() == "")) error = "Debe ingresar los campos obligatorios";
	else if (!valFecha($("#FechaDoc").val())) error = "Formato de Fecha Incorrecta";
	else if (!valFecha($("#FechaIni").val()) && $("#FechaIni").val() != "") error = "Periodo de Suspensi&oacute;n Incorrecto";
	else if (!valFecha($("#FechaFin").val()) && $("#FechaFin").val() != "") error = "Periodo de Suspensi&oacute;n Incorrecto";
	else if ($("#FechaIni").val() > $("#FechaFin").val() && $("#FechaIni").val() != "" && $("#FechaFin").val() != "") error = "Periodo de Suspensi&oacute;n Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_demeritos&accion="+accion+"&"+post,
			async: false,
			success: function(resp){
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (patrimonio - inmuebles)
function empleados_patrimonio_inmuebles(form, accion) {
	//	valido
	var error = "";
	if ($("#Descripcion").val().trim() == "" || $("#Ubicacion").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (($("#Valor").val() != "") && isNaN(parseFloat($("#Valor").val())) || parseFloat(setNumero($("#Valor").val())) < 0) error = "Formato de Valor Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_patrimonio_inmuebles&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					parent.$("#TotalInmuebles").val(datos[1]);
					parent.$("#TotalInversion").val(datos[2]);
					parent.$("#TotalVehiculo").val(datos[3]);
					parent.$("#TotalCuentas").val(datos[4]);
					parent.$("#TotalOtros").val(datos[5]);
					parent.$("#Total").val(datos[6]);
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (patrimonio - inversiones)
function empleados_patrimonio_inversiones(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Titular").val().trim() == "" || $("#Cantidad").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (($("#Valor").val() != "") && isNaN(parseFloat($("#Valor").val())) || parseFloat(setNumero($("#Valor").val())) < 0) error = "Formato de Valor Incorrecto";
	else if (($("#ValorNominal").val() != "") && isNaN(parseFloat($("#ValorNominal").val())) || parseFloat(setNumero($("#ValorNominal").val())) < 0) error = "Formato de Valor Nominal Incorrecto";
	else if (isNaN(parseInt($("#Cantidad").val())) || parseInt(setNumero($("#Cantidad").val())) < 0) error = "Formato de Cantidad Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_patrimonio_inversiones&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					parent.$("#TotalInmuebles").val(datos[1]);
					parent.$("#TotalInversion").val(datos[2]);
					parent.$("#TotalVehiculo").val(datos[3]);
					parent.$("#TotalCuentas").val(datos[4]);
					parent.$("#TotalOtros").val(datos[5]);
					parent.$("#Total").val(datos[6]);
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (patrimonio - vehiculos)
function empleados_patrimonio_vehiculos(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Marca").val().trim() == "" || $("#Modelo").val().trim() == "" || $("#Anio").val().trim() == "" || $("#Color").val().trim() == "" || $("#Placa").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (($("#Valor").val() != "") && isNaN(parseFloat($("#Valor").val())) || parseFloat(setNumero($("#Valor").val())) < 0) error = "Formato de Valor Incorrecto";
	else if (isNaN(parseInt($("#Anio").val())) || parseInt(setNumero($("#Anio").val())) < 0) error = "Formato de A&ntilde;o Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_patrimonio_vehiculos&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					parent.$("#TotalInmuebles").val(datos[1]);
					parent.$("#TotalInversion").val(datos[2]);
					parent.$("#TotalVehiculo").val(datos[3]);
					parent.$("#TotalCuentas").val(datos[4]);
					parent.$("#TotalOtros").val(datos[5]);
					parent.$("#Total").val(datos[6]);
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (patrimonio - cuentas)
function empleados_patrimonio_cuentas(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Institucion").val().trim() == "" || $("#TipoCuenta").val().trim() == "" || $("#NroCuenta").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (($("#Valor").val() != "") && isNaN(parseFloat($("#Valor").val())) || parseFloat(setNumero($("#Valor").val())) < 0) error = "Formato de Valor Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_patrimonio_cuentas&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					parent.$("#TotalInmuebles").val(datos[1]);
					parent.$("#TotalInversion").val(datos[2]);
					parent.$("#TotalVehiculo").val(datos[3]);
					parent.$("#TotalCuentas").val(datos[4]);
					parent.$("#TotalOtros").val(datos[5]);
					parent.$("#Total").val(datos[6]);
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (patrimonio - otros)
function empleados_patrimonio_otros(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Descripcion").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (($("#Valor").val() != "") && isNaN(parseFloat($("#Valor").val())) || parseFloat(setNumero($("#Valor").val())) < 0) error = "Formato de Valor Incorrecto";
	else if (($("#ValorCompra").val() != "") && isNaN(parseFloat($("#ValorCompra").val())) || parseFloat(setNumero($("#ValorCompra").val())) < 0) error = "Formato de Valor de Compra Incorrecto";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_patrimonio_otros&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					parent.$("#TotalInmuebles").val(datos[1]);
					parent.$("#TotalInversion").val(datos[2]);
					parent.$("#TotalVehiculo").val(datos[3]);
					parent.$("#TotalCuentas").val(datos[4]);
					parent.$("#TotalOtros").val(datos[5]);
					parent.$("#Total").val(datos[6]);
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (documentos - entregados)
function empleados_documentos_entregados(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Documento").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if ($("#FlagPresento").prop("checked") && $("#FechaPresento").val().trim() == "") error = "Debe ingresar la Fecha de Entrega";
	else if ($("#FlagPresento").prop("checked") && !valFecha($("#FechaPresento").val())) error = "Formato de Fecha de Entrega Incorrecta";
	else if ($("#FechaVence").val().trim() != "" && !valFecha($("#FechaVence").val())) error = "Formato de Fecha de Vencimiento Incorrecto";
	else if ($("#FlagCarga").prop("checked") && $("#CargaFamiliar").val().trim() == "") error = "Debe seleccionar la Persona Relacionada al Documento";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_documentos_entregados&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				if (datos[0].trim() != "") cajaModalParent(datos[0], "error", 400);
				else {
					form.target = "";
					form.action = "gehen.php?anz=empleados_documentos_entregados_lista&_CodDocumento="+datos[1];
					form.submit();
				}
			}
		});
	}
	return false;
}
//	-------------------------------

//	empleados (documentos - movimientos)
function empleados_documentos_movimientos(form, accion) {
	parent.$(".div-progressbar").css("display", "block");
	
	//	valido
	var error = "";
	if ($("#Responsable").val().trim() == "" || $("#FechaEntrega").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (accion == "nuevo" && !valFecha($("#FechaEntrega").val())) error = "Fecha Entrega Incorrecta";
	else if (accion == "modificar" && $("#FechaDevuelto").val().trim() == "") error = "Debe ingresar los campos obligatorios";
	else if (accion == "modificar" && !valFecha($("#FechaDevuelto").val())) error = "Fecha Estado Incorrecta";
	
	//	valido errores
	if (error != "") {
		cajaModalParent(error, "error", 400);
	} else {
		//	formulario
		var post = getForm(form);
		
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=empleados_documentos_movimientos&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModalParent(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------
