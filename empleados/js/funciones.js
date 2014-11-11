// JavaScript Document

//	establezco mensajes del menu de opciones de empleado
function setMsj(msj) {
	switch(msj) {
		case 0:
			$("#msj").html("");
			break;
			
		case 1:
			$("#msj").html("Actualizar Vacaciones del Empleado");
			break;
			
		case 2:
			$("#msj").html("Actualizar Patrimonio del Empleado");
			break;
			
		case 3:
			$("#msj").html("Ver Permisos del Empleado");
			break;
			
		case 4:
			$("#msj").html("Actualizar Instrucci&oacute;n Academica, Idiomas y Otros Estudios del Empleado");
			break;
			
		case 5:
			$("#msj").html("Actualizar Referencias Personales y Laborales del Empleado");
			break;
			
		case 6:
			$("#msj").html("Actualizar Documentos Entregados por el Empleado");
			break;
			
		case 7:
			$("#msj").html("Actualizar Experiencia Laboral del Empleado");
			break;
			
		case 8:
			$("#msj").html("Actualizar M&eacute;ritos y Dem&eacute;ritos del Empleado");
			break;
			
		case 9:
			$("#msj").html("Actualizar Informaci&oacute;n Bancaria del Empleado");
			break;
			
		case 10:
			$("#msj").html("Actualizar Carga Familiar del Empleado");
			break;
			
		case 11:
			$("#msj").html("Actualizar Porcentajes del Impuesto s. la Renta del Empleado");
			break;
			
		case 12:
			$("#msj").html("Control e Historial de Nivelaciones del Empleado");
			break;
			
		case 13:
			$("#msj").html("Ver Historial de Modificaciones del Empleado");
			break;
			
		case 14:
			$("#msj").html("Actualizar Conceptos de N&oacute;mina Asignados al Empleado");
			break;
	}
}
//	-----------------------------

//	bloqueo/desbloqueo opciones del motivo de baja de la carga familiar
function setMotivoBaja(Valor) {
	if (Valor) $("#FechaBaja").prop("disabled", false).val("");
	else $("#FechaBaja").prop("disabled", true).val("");
}
//	-----------------------------

//	bloqueo/desbloqueo opciones del motivo de baja de la carga familiar
function setTrabaja(chk) {
	if (chk) $(".trabaja").prop("disabled", false).val("");
	else $(".trabaja").prop("disabled", true).val("");
}
//	-----------------------------

//	abrir pdf permisos
function empleados_permisos_imprimir(form) {
	var get = getForm(form);
	var href = "empleados_permisos_pdf.php?"+get+"&iframe=true&width=100%&height=100%";
	$("#a_imprimir").attr("href", href);
	document.getElementById("a_imprimir").click();
}
//	-----------------------------

//	
function setFlagInterno(boo) {
	if (boo) {
		$("#Responsable").val("");
		$("#NomResponsable").val("");
		$("#FlagExterno").prop("checked", false);
		$("#Externo").val("").prop("disabled", true);
		$("#a_Responsable").css("visibility", "visible");
	} else $("#FlagInterno").prop("checked", true);
}
//	-----------------------------

//	
function setFlagExterno(boo) {
	if (boo) {
		$("#Responsable").val("");
		$("#NomResponsable").val("");
		$("#FlagInterno").prop("checked", false);
		$("#Externo").val("").prop("disabled", false);
		$("#a_Responsable").css("visibility", "hidden");
	} else $("#FlagExterno").prop("checked", true);
}
//	-----------------------------

//	
function setPeriodoSuspension(valor) {
	$("#FechaIni").val("");
	$("#FechaFin").val("");
	if (valor == "04") $(".periodo").css("visibility", "visible");
	else $(".periodo").css("visibility", "hidden");
}
//	-----------------------------

//	
function setFlagPresento(boo) {
	if (boo) {
		$("#FechaPresento").val("").prop("disabled", false);
		$("#FechaVence").val("").prop("disabled", false);
	} else {
		$("#FechaPresento").val("").prop("disabled", true);
		$("#FechaVence").val("").prop("disabled", true);
	}
}
//	-----------------------------

//	
function setFlagCarga(boo) {
	$("#CargaFamiliar").val("");
	$("#NomCargaFamiliar").val("");
	$("#Parentesco").val("");
	if (boo) $("#a_carga_familiar").css("visibility", "visible");
	else $("#a_carga_familiar").css("visibility", "hidden");
}
//	-----------------------------

//	cargo el tab de movimientos de documentos
function clickTabMovimientos() {
	var registro = $("#documentos").contents().find("body #sel_registros").val();
	var _APLICACION = $("#_APLICACION").val();
	var concepto = $("#concepto").val();
	
	if (registro == "") {
		currentTab('tab', document.getElementById('li1'));
		mostrarTab('tab', 1, 2);
		cajaModal("Debe seleccionar un Documento", "error", 400);
	}
	else movimientos.location.href = "gehen.php?anz=empleados_documentos_movimientos_lista&filtrar=default&registro="+registro;
}
//	-----------------------------

//	habilito/desahabilito opciones de cese en empleado
function setCese(Estado) {
	if (Estado == "I") $(".cese").prop("disabled", false).val("");
	else $(".cese").prop("disabled", true).val("");
}
//	-----------------------------