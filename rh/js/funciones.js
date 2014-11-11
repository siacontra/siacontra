// JavaScript Document

//	funcion para abrir una ventana nueva enviando valores
function abrir_listado_periodos_vacaciones(ventana) {
	var CodPersona = $("#CodPersona").val();
	var href = "../lib/listas/listado_periodos_vacaciones.php?filtrar=default&CodPersona="+CodPersona+"&ventana="+ventana+"&iframe=true&width=800&height=500";
	$("#a_detalles").attr("href", href);
	document.getElementById('a_detalles').click();
}
//	------------------------------------------

//	funcion para totalizar los dias en la ficha de periodos vacacionales
function totalizarPeriodoVacaciones1() {
	var TotalNroDias = new Number(0);
	var TotalPendientes = new Number(0);
	var form = document.getElementById("frm_detalles");
	for(var i=0; n=form.elements[i]; i++) {
		if (n.name == "NroDias") {
			var NroDias = new Number(setNumero(n.value));
			TotalNroDias += NroDias;
		}
		else if (n.name == "Pendientes") {
			var Pendientes = new Number(setNumero(n.value));
			TotalPendientes += Pendientes;
		}
	}
	$("#TotalNroDias").val(setNumeroFormato(TotalNroDias, 2, '.', ','));
	$("#TotalPendientes").val(setNumeroFormato(TotalPendientes, 2, '.', ','));
}
//	------------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaPeriodoVacaciones(boton, detalle) {
	boton.disabled = true;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") alert("¡Debe seleccionar una linea!");
	else {
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		document.getElementById(sel).value = "";
		totalizarPeriodoVacaciones1();
	}
	boton.disabled = false;
}
//	------------------------------------------

//	ACTUALIZO LOS DOS FRAMES AL SELECCIONAR UN CANDIDATO
function actualizar_evaluaciones_competencias() {
	$("#frm_candidato").attr("action", "gehen.php?anz=rh_requerimientos_evaluaciones_lista&FlagMostrar=S");
	$("#frm_candidato").attr("target", "evaluaciones");
	document.getElementById("frm_candidato").submit();
	$("#frm_candidato").attr("action", "gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=N");
	$("#frm_candidato").attr("target", "competencias");
	document.getElementById("frm_candidato").submit();
}
//	------------------------------------------

//	ACTUALIZO LOS DOS FRAMES AL SELECCIONAR UN CANDIDATO
function actualizar_competencias() {
	$("#frm_evaluacion").attr("action", "gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=S");
	$("#frm_evaluacion").attr("target", "competencias");
	document.getElementById("frm_evaluacion").submit();
}
//	------------------------------------------

//	ACTUALIZO LOS DOS FRAMES AL SELECCIONAR UN CANDIDATO
function actualizar_postulantes_evaluaciones() {
	$("#frm_competencias").attr("action", "gehen.php?anz=rh_requerimientos_postulantes_lista&FlagMostrar=S");
	$("#frm_competencias").attr("target", "postulantes");
	document.getElementById("frm_competencias").submit();
	$("#frm_competencias").attr("action", "gehen.php?anz=rh_requerimientos_evaluaciones_lista&FlagMostrar=S");
	$("#frm_competencias").attr("target", "evaluaciones");
	document.getElementById("frm_competencias").submit();
	$("#frm_competencias").attr("action", "gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=S");
	$("#frm_competencias").attr("target", "competencias");
	document.getElementById("frm_competencias").submit();
}
//	------------------------------------------

//	ACTUALIZO LOS DOS FRAMES AL SELECCIONAR UN CANDIDATO
function actualizar_postulantes() {
	$("#frm_candidato").attr("action", "gehen.php?anz=rh_requerimientos_postulantes_lista&FlagMostrar=S");
	$("#frm_candidato").attr("target", "postulantes");
	document.getElementById("frm_candidato").submit();
	$("#frm_candidato").attr("action", "gehen.php?anz=rh_requerimientos_evaluaciones_lista&FlagMostrar=N");
	$("#frm_candidato").attr("target", "evaluaciones");
	document.getElementById("frm_candidato").submit();
	$("#frm_candidato").attr("action", "gehen.php?anz=rh_requerimientos_competencias_lista&FlagMostrar=N");
	$("#frm_candidato").attr("target", "competencias");
	document.getElementById("frm_candidato").submit();
}
//	------------------------------------------

//	
function setFlagCostos(boo) {
	if (boo) {
		$("#CostoEstimado").attr("disabled", "disabled").val("0,00");
		$("#MontoAsumido").attr("disabled", "disabled").val("0,00");
	} else {
		$("#CostoEstimado").removeAttr("disabled").val("0,00");
		$("#MontoAsumido").removeAttr("disabled").val("0,00");
	}
	$("#Saldo").val("0,00");
}
//	------------------------------------------

//	
function setCostoEstimadoTotal() {
	var CostoEstimado = parseFloat(setNumero($("#CostoEstimado").val()));
	var MontoAsumido = parseFloat(setNumero($("#MontoAsumido").val()));
	var Saldo = CostoEstimado - MontoAsumido;
	$("#Saldo").val(setNumeroFormato(Saldo, 2, '.', ','));
}
//	------------------------------------------

//	totales ficha gastos capacitacion
function capacitaciones_gastos_total(id) {
	var SubTotal = parseFloat(setNumero($("#SubTotal_"+id).val()));
	var Impuestos = parseFloat(setNumero($("#Impuestos_"+id).val()));
	var Total = SubTotal + Impuestos;
	Total = setNumeroFormato(Total, 2, '.', ',');
	$("#Total_"+id).val(Total);
}
//	------------------------------------------

//	totales ficha gastos capacitacion
function competencias_sel_puntaje(Puntaje, Div) {
	if (Div == "vr") var c = "#000"; else var c = "#5F160E";
	$("."+Div).each(function (index) {
		var id = Div + "_" + Puntaje;
		if (id >= $(this).attr("id")) $(this).css("background", c);
		else $(this).css("background", "transparent");
	});
}
//	------------------------------------------

//	
function bono_periodos_abrir_lista_empleados() {
	var CodTipoNom = $('#CodTipoNom').val();
	var CodOrganismo = $('#CodOrganismo').val();
	if (CodTipoNom == "") cajaModal("Debe seleccionar el Tipo de Nomina", "error", 400);
	else if (CodOrganismo == "") cajaModal("Debe seleccionar el Organismo", "error", 400);
	else {
		var href = "../lib/listas/listado_empleados.php?filtrar=default&ventana=bono_periodos_empleados_insertar&detalle=empleados&FlagNomina=S&CodOrganismo="+CodOrganismo+"&fCodTipoNom="+CodTipoNom+"&iframe=true&width=100%&height=500";
		$("#a_empleados").attr("href", href);
		document.getElementById('a_empleados').click();
	}
}
//	------------------------------------------

//	
function bono_periodos_registrar_detalle() {
	var registro = $('#dregistro').val();
	if (registro == "") cajaModal("Debe seleccionar una linea", "error", 400);
	else {
		var href = "gehen.php?anz=rh_bono_periodos_registrar_detalles&registro="+registro+"&iframe=true&width=850&height=300";
		$("#a_detalle").attr("href", href);
		document.getElementById('a_detalle').click();
	}
}
//	------------------------------------------

//	
function bono_periodos_registrar_eventos() {
	var registro = $('#dregistro').val();
	if (registro == "") cajaModal("Debe seleccionar una linea", "error", 400);
	else {
		var href = "gehen.php?anz=rh_bono_periodos_registrar_eventos&registro="+registro+"&iframe=true&width=975&height=500";
		$("#a_detalle").attr("href", href);
		document.getElementById('a_detalle').click();
	}
}
//	------------------------------------------


