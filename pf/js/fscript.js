// JavaScript Document

//	hamilitar campos
function setBtDependenciaInterna(boo) {
	$("#CodDependencia").val("");
	$("#NomDependencia").val("");
	$("#Estructura").val("");
	if (boo) $("#btDependencias").css("visibility", "visible");
	else $("#btDependencias").css("visibility", "hidden");
}

//	actualizar las fechas de las actividades
function setFechaActividades() {
	var FechaInicio = $("#FechaInicio").val().trim();
	var CodProceso = $("#CodProceso").val();
	
	//	actividades
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frm_actividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "CodFase") detalles += n.value + "|";
		if (n.name == "NomFase") detalles += n.value + "|";
		if (n.name == "CodActividad") detalles += n.value + "|";
		if (n.name == "Descripcion") detalles += n.value + "|";
		if (n.name == "FlagAutoArchivo") detalles += n.value + "|";
		if (n.name == "FlagNoAfectoPlan") detalles += n.value + "|";
		if (n.name == "Duracion") {
			var duracion = new Number(setNumero(n.value));
			if (isNaN(duracion)) { error_detalles = "¡Debe ingresar un valor numérico en la duración!"; n.value = 1; n.focus(); }
			if (duracion < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la duración!"; n.value = 1; n.focus(); }
			detalles += n.value + ";";
		}
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	if (error_detalles != "") cajaModal(error_detalles, "error", 400);
	else {
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=setFechaActividades&CodProceso="+CodProceso+"&FechaInicio="+FechaInicio+"&detalles="+detalles,
			async: false,
			success: function(resp) {
				var datos = resp.split("||");
				if (datos[0].trim() != "") $("#FechaInicio").val(datos[0]);
				$("#FechaInicioReal").val($("#FechaInicio").val());
				$("#lista_actividades").html(datos[1]);
				$("#FechaTermino").val(datos[2]);
				$("#FechaTerminoReal").val(datos[2]);				
				$("#Duracion").val(datos[3]);
				$("#total_duracion").html(datos[3]);
				$("#total_prorroga").html(datos[4]);
				$("#DuracionNo").val(datos[5]);
				var DuracionTotal = new Number(parseInt(datos[3]) + parseInt(datos[4]) + parseInt(datos[5]));
				$("#DuracionTotal").val(DuracionTotal);
			}
		});
	}
}

//	actualizar las fechas de las actividades
function setFechaActividadesProrroga(Prorroga, CodActividad) {
	$("#Prorroga").val(Prorroga);
	$("#CodActividad").val(CodActividad);
	
	//	actividades
	var detalles = "";
	var error_detalles = "";		
	var frmdetalles = document.getElementById("frm_actividades");
	for(i=0; n=frmdetalles.elements[i]; i++) {
		if (n.name == "CodFase") detalles += n.value + "|";
		else if (n.name == "NomFase") detalles += n.value + "|";
		else if (n.name == "CodActividad") detalles += n.value + "|";
		else if (n.name == "Descripcion") detalles += n.value + "|";
		else if (n.name == "FlagAutoArchivo") detalles += n.value + "|";
		else if (n.name == "FlagNoAfectoPlan") detalles += n.value + "|";
		else if (n.name == "Estado") detalles += n.value + "|";
		else if (n.name == "Duracion") detalles += n.value + "|";
		else if (n.name == "FechaInicio") detalles += n.value + "|";
		else if (n.name == "FechaTermino") detalles += n.value + "|";
		else if (n.name == "ProrrogaAcu") detalles += n.value + "|";
		else if (n.name == "Prorroga") {
			var duracion = new Number(setNumero(n.value));
			if (isNaN(duracion)) { error_detalles = "¡Debe ingresar un valor numérico en la prorroga!"; n.value = 1; n.focus(); }
			if (duracion < 0) { error_detalles = "¡Debe ingresar un valor mayor a cero en la prorroga!"; n.value = 1; n.focus(); }
			detalles += n.value + "|";
		}
		else if (n.name == "FechaInicioReal") detalles += n.value + "|";
		else if (n.name == "FechaTerminoReal") detalles += n.value + "|";
		else if (n.name == "DiasCierre") detalles += n.value + "|";
		else if (n.name == "FechaTerminoCierre") detalles += n.value + "|";
		else if (n.name == "FechaRegistroCierre") detalles += n.value + "|";
		else if (n.name == "DiasAdelanto") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	if (error_detalles != "") cajaModal(error_detalles, "error", 400);
	else {
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=setFechaActividadesProrroga&detalles="+detalles,
			async: false,
			success: function(resp) {
				var datos = resp.split("||");
				$("#lista_actividades").html(datos[0]);
				$("#FechaInicioReal").val(datos[1]);
				$("#FechaTerminoReal").val(datos[2]);
				$("#total_duracion").html(datos[3]);
				$("#total_prorroga").html(datos[4]);
			}
		});
	}
}