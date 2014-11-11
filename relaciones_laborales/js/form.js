// JavaScript Document

//	horario laboral
function horario_laboral(form, accion) {
	//	variables
	var FlagCorrido = $("#FlagCorrido").prop("checked");
	
	//	valido
	var error = "";
	if ($("#Descripcion").val().trim() == "") error = "Debe llenar los campos obligatorios";
	
	//	detalles
	if (error == "") {
		var detalles_horario = "";
		var frm = document.getElementById("frm_detalles");
		for(var i=0; n=frm.elements[i]; i++) {
			if (n.name == "FlagLaborable") {
				if (n.checked) var FlagLaborable = "S"; else var FlagLaborable = "N";
				detalles_horario += FlagLaborable + ";char:td;";
			}
			else if (n.name == "Dia") detalles_horario += n.value + ";char:td;";
			else if (n.name == "Entrada1") {
				var Entrada1 = formatHora(n.value);
				if (FlagLaborable == "S") {
					if (!valHora(n.value)) { error = "Se encontraron horas incorrectas (Formato incorrecto)."; break; }
					else if (n.value.trim() == "") { error = "Se encontraron horas incorrectas (Campos en blanco)."; break; }
				}
				detalles_horario += Entrada1 + ";char:td;";
			}
			else if (n.name == "Salida1") {
				var Salida1 = formatHora(n.value);
				if (FlagLaborable == "S") {
					if (!valHora(n.value)) { error = "Se encontraron horas incorrectas (Formato incorrecto)."; break; }
					else if (n.value.trim() == "") { error = "Se encontraron horas incorrectas (Campos en blanco)."; break; }
					else if (Entrada1 > Salida1) { error = "La hora de salida no puede ser menor a la hora de entrada"; break; }
				}
				detalles_horario += Salida1 + ";char:td;";
			}
			else if (n.name == "Entrada2") {
				var Entrada2 = formatHora(n.value);
				if (FlagLaborable == "S" && !FlagCorrido) {
					if (!valHora(n.value)) { error = "Se encontraron horas incorrectas (Formato incorrecto)."; break; }
					else if (n.value.trim() == "") { error = "Se encontraron horas incorrectas (Campos en blanco)."; break; }
					else if (Entrada1 > Entrada2 || Salida1 > Entrada2) { error = "Las horas del 1er turno no pueden ser mayor a la hora de entrada del 2do turno"; break; }
				}
				detalles_horario += Entrada2 + ";char:td;";
			}
			else if (n.name == "Salida2") {
				var Salida2 = formatHora(n.value);
				if (FlagLaborable == "S" && !FlagCorrido) {
					if (!valHora(n.value)) { error = "Se encontraron horas incorrectas (Formato incorrecto)."; break; }
					else if (n.value.trim() == "") { error = "Se encontraron horas incorrectas (Campos en blanco)."; break; }
					else if (Entrada2 > Salida2) { error = "La hora de salida no puede ser menor a la hora de entrada"; break; }
					else if (Entrada1 > Salida2 || Salida1 > Salida2) { error = "Las horas del 1er turno no pueden ser mayor a la hora de entrada del 2do turno"; break; }
				}
				detalles_horario += Salida2 + ";char:tr;";
			}
		}
		var len = detalles_horario.length; len-=9;
		detalles_horario = detalles_horario.substr(0, len);
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
			data: "modulo=horario_laboral&accion="+accion+"&detalles_horario="+detalles_horario+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------

//	tipo de nomina
function tipo_nomina(form, accion) {
	//	valido
	var error = "";
	if ($("#CodTipoNom").val().trim() == "" || $("#Nomina").val().trim() == "" || $("#TituloBoleta").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valCodigo($("#CodTipoNom").val())) error = "No se permiten caracteres especiales para el <strong>C&oacute;digo</strong>";
	
	//	detalles
	if (error == "") {
		//	periodos
		var detalles_periodos = "";
		var frm = document.getElementById("frm_periodos");
		for(var i=0; n=frm.elements[i]; i++) {
			if (n.name == "Periodo") {
				var Periodo = parseInt(n.value);
				if (Periodo == 0 || isNaN(Periodo)) { error = "Se encontraron lineas con <strong>Años</strong> Incorrectos"; break; }
				else detalles_periodos += Periodo + ";char:td;";
			}
			else if (n.name == "Mes") detalles_periodos += n.value + ";char:td;";
			else if (n.name == "Secuencia") {
				var Secuencia = parseInt(n.value);
				if (Secuencia == 0 || isNaN(Secuencia)) { error = "Se encontraron lineas con <strong>Secuencias</strong> Incorrectas"; break; }
				else detalles_periodos += Secuencia + ";char:tr;";
			}
		}
		var len = detalles_periodos.length; len-=9;
		detalles_periodos = detalles_periodos.substr(0, len);
		
		//	procesos
		var detalles_procesos = "";
		var frm = document.getElementById("frm_procesos");
		for(var i=0; n=frm.elements[i]; i++) {
			if (n.name == "CodTipoProceso") {
				if (n.value.trim() == "") { error = "Debe seleccionar los Tipos de Procesos Aplicables"; break; }
				else detalles_procesos += n.value + ";char:td;";
			}
			else if (n.name == "CodTipoDocumento") detalles_procesos += n.value + ";char:tr;";
		}
		var len = detalles_procesos.length; len-=9;
		detalles_procesos = detalles_procesos.substr(0, len);
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
			data: "modulo=tipo_nomina&accion="+accion+"&detalles_periodos="+detalles_periodos+"&detalles_procesos="+detalles_procesos+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
//	-------------------------------