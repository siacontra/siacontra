// JavaScript Document

//	funcion para obtener la fecha de termino y la fecha siguiente a partir de una fecha inicial y un nro de dias
function obtenerFechaTerminoVacacion() {
	//	campos
	FechaSalida = $("#FechaSalida");
	NroDias = $("#NroDias");
	FechaTermino = $("#FechaTermino");
	FechaIncorporacion = $("#FechaIncorporacion");
	$("#lista_detalles").append("");
	$("#TotalPendientes").val("0,00");
	$("#TotalNroDias").val("0,00");
	
	//	valido valores
	if (!valFecha(FechaSalida.val())) {
		FechaSalida.val("");
		FechaTermino.val("");
		FechaIncorporacion.val("");
		$("#bti_detalles").attr("disabled", "disabled");
		$("#btb_detalles").attr("disabled", "disabled");
	}
	else if (isNaN(setNumero(NroDias.val()))) {
		NroDias.val("0");
		FechaTermino.val("");
		FechaIncorporacion.val("");
		$("#bti_detalles").attr("disabled", "disabled");
		$("#btb_detalles").attr("disabled", "disabled");
	}
	else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=obtenerFechaTerminoVacacion&FechaSalida="+FechaSalida.val()+"&NroDias="+NroDias.val(),
			async: false,
			success: function(resp){
				var fechas = resp.split("|");
				$("#bti_detalles").removeAttr("disabled");
				$("#btb_detalles").removeAttr("disabled");
				FechaTermino.val(fechas[0]);
				FechaIncorporacion.val(fechas[1]);
			}
		});
	}
}
//	-------------------------------

//	funcion para obtener la fecha de termino y la fecha siguiente a partir de una fecha inicial y un nro de dias
function obtenerFechaTerminoVacacionDetalle(detalle) {
	//	campos
	NroDiasGeneral = $("#NroDias");
	NroDias = $("#NroDias_"+detalle);
	FechaInicio = $("#FechaInicio_"+detalle);
	FechaFin = $("#FechaFin_"+detalle);
	Pendientes = $("#Pendientes_"+detalle);
	
	//	valido valores
	if (!valFecha(FechaInicio.val()) || FechaInicio.val().trim() == "") { FechaInicio.val(""); FechaFin.val(""); }
	else if (isNaN(setNumero(NroDias.val())) || NroDias.val().trim() == "") { NroDias.val("0"); FechaFin.val(""); }
	else if (parseInt(setNumero(NroDias.val())) > parseInt(setNumero(Pendientes.val()))) {
		cajaModal("El Nro. de Dias NO puede ser mayor que " + Pendientes.val(), "error", 400);
		if (parseInt(setNumero(NroDiasGeneral.val())) > parseInt(setNumero(Pendientes.val()))) {
			NroDias.val(Pendientes.val());
		} else {
			NroDias.val(NroDiasGeneral.val());
		}
		totalizarPeriodoVacaciones1();
	}
	else if (parseInt(setNumero(NroDiasGeneral.val())) > parseInt(setNumero(NroDias.val())) && parseInt(setNumero(NroDias.val())) != parseInt(setNumero(Pendientes.val()))) {
		cajaModal("El Nro. de Dias NO puede ser distinto de " + Pendientes.val(), "error", 400);
		NroDias.val(Pendientes.val());
		totalizarPeriodoVacaciones1();
	}
	else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=obtenerFechaTerminoVacacion&FechaSalida="+FechaInicio.val()+"&NroDias="+NroDias.val(),
			async: false,
			success: function(resp){
				var fechas = resp.split("|");
				FechaFin.val(fechas[0]);
				totalizarPeriodoVacaciones1();
			}
		});
	}
}
//	-------------------------------

//	ESTABLEZCO EL TIPO DE CONTRATO
function setTipoContrato(TipoContrato, FechaDesde, FechaHasta) {
	if (TipoContrato == "") {
		FechaDesde.val("").attr("disabled", "disabled");
		FechaHasta.val("").attr("disabled", "disabled");
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=setTipoContrato&TipoContrato="+TipoContrato,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				FechaDesde.val("").removeAttr("disabled");
				if (datos[0] == "S") FechaHasta.val("").removeAttr("disabled");
				else FechaHasta.val("").attr("disabled", "disabled");
			}
		});
	}
}
//	------------------------------------

//	INSERTAR LOS PERIODOS VACACIONALES VENCIDOS 
function vacaciones_periodos_insertar() {
	var CodPersona = $("#CodPersona").val();
	var NroDias = parseFloat(setNumero($("#NroDias").val()));
	var FechaSalida = $("#FechaSalida").val();
	
	if (isNaN(NroDias) || NroDias <= 0 || !valFecha(FechaSalida)) $("#lista_detalles").html("");
	else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=vacaciones_periodos_insertar&CodPersona="+CodPersona+"&NroDias="+NroDias+"&FechaSalida="+FechaSalida,
			async: true,
			success: function(resp) {
				$("#lista_detalles").html(resp);
				totalizarPeriodoVacaciones1();
			}
		});
	}
}
//	--------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaPostulante(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	if (document.getElementById(sel).value == "") cajaModalParent("Debe seleccionar una linea", "error", 300);
	else {
		//	ajax
		var CodOrganismo = $("#CodOrganismo").val();
		var Requerimiento = $("#Requerimiento").val();
		var seldetalle = document.getElementById(sel).value;
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=requerimientos&accion=borrar-candidato&CodOrganismo="+CodOrganismo+"&Requerimiento="+Requerimiento+"&seldetalle="+seldetalle,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModalParent(resp, "error", 300);
				else {
					var candetalle = new Number(document.getElementById(can).value); candetalle--;
					document.getElementById(can).value = candetalle;
					var listaDetalles = document.getElementById(lista);
					var tr = document.getElementById(seldetalle);
					listaDetalles.removeChild(tr);
					document.getElementById(sel).value = "";
				}
			}
		});
	}
	boton.disabled = false;
}
//	--------------------------------

//	
function desarrollo_carreras_evaluacion_sel() {
	var CodPersona = $("#CodPersona").val();
	var CodOrganismo = $("#CodOrganismo").val();
	var Secuencia = $("#Secuencia").val();
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=desarrollo_carreras_evaluacion_sel&CodPersona="+CodPersona+"&CodOrganismo="+CodOrganismo+"&Secuencia="+Secuencia,
		async: false,
		success: function(resp) {
			var datos = resp.split("|");
			$("#lista_academico").html(datos[1]);
			$("#lista_cursos_area").html(datos[2]);
			$("#lista_cursos_general").html(datos[3]);
			$("#lista_competencias_adquiridas").html(datos[4]);
			$("#lista_competencias_adquirir").html(datos[5]);
			$("#lista_habilidades_adquiridas").html(datos[6]);
			$("#lista_habilidades_adquirir").html(datos[7]);
			$("#lista_capacitacion_conductual").html(datos[8]);
			$("#lista_experiencia").html(datos[9]);
		}
	});
}
//	--------------------------------

//	
function bono_periodos_empleados_importar() {
	var CodOrganismo = $("#CodOrganismo").val();
	var CodTipoNom = $("#CodTipoNom").val();
	if (CodTipoNom == "") {
		cajaModal("Debe seleccionar el Tipo de N&oacute;mina", "error", 400);
	} else {
		$("#cajaModal").dialog({
			buttons: {
				"Aceptar": function() {
					$(this).dialog("close");
					$.ajax({
						type: "POST",
						url: "../lib/fphp_funciones_ajax.php",
						data: "accion=bono_periodos_empleados_insertar&nro_detalles=1&EdoReg=A&SitTra=A&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom,
						async: false,
						success: function(resp) {
							$("#lista_empleados").html(resp);
						}
					});
				},
				"Cancelar": function() {
					$(this).dialog("close");
				}
			}
		});
		cajaModalConfirm("Está seguro de realizar esta acción", 400);
	}
}
//	--------------------------------

//	
function getDiasBonoPeriodo() {
	var FechaInicio = $("#FechaInicio").val();
	var FechaFin = $("#FechaFin").val();
	var ValorDia = $("#ValorDia").val();
	if (!valFecha(FechaInicio) || !valFecha(FechaFin) || formatFechaAMD(FechaInicio) > formatFechaAMD(FechaFin)) {
		cajaModal("Periodo de Fechas Incorrecto", "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=getDiasBonoPeriodo&FechaInicio="+FechaInicio+"&FechaFin="+FechaFin+"&ValorDia="+ValorDia,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				$("#TotalDiasPeriodo").val(datos[0]);
				$("#TotalFeriados").val(datos[1]);
				$("#TotalDiasPago").val(datos[2]);
				$("#ValorMes").val(datos[3]);
			}
		});
	}
}
//	--------------------------------

//	FUNCION PARA INSERTAR UNA LINEA EN UNA LISTA (TR EN TABLE)
function bono_periodos_registrar_eventos_insertar(boton, secuencia, FechaInicio, FechaFin) {
	boton.disabled = true;
	var detalle = "eventos";
	var nro = "nro_" + detalle + "_" + secuencia;
	var can = "can_" + detalle + "_" + secuencia;
	var sel = "sel_" + detalle;
	var lista = "lista_" + detalle + "_" + secuencia;
	var nrodetalle = parseInt($("#"+nro).val()); nrodetalle++;
	var candetalle = parseInt($("#"+can).val()); candetalle++;
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=bono_periodos_registrar_eventos_insertar&nrodetalle="+nrodetalle+"&candetalle="+candetalle+"&FechaInicio="+FechaInicio+"&FechaFin="+FechaFin+"&secuencia="+secuencia,
		async: true,
		success: function(resp) {
			$("#"+nro).val(nrodetalle);
			$("#"+can).val(candetalle);
			$("#"+lista).append(resp);
			boton.disabled = false;
		}
	});
}
//	--------------------------------

//	FUNCION PARA INSERTAR UNA LINEA EN UNA LISTA (TR EN TABLE) ----ASIGNACION DIAS
function bono_periodos_registrar_laborados_insertar(boton, secuencia, FechaInicio, FechaFin) {
	boton.disabled = true;
	var detalle = "laborados";
	var nro = "nro_" + detalle + "_" + secuencia;
	var can = "can_" + detalle + "_" + secuencia;
	var sel = "sel_" + detalle;
	var lista = "lista_" + detalle + "_" + secuencia;
	var nrodetalle = parseInt($("#"+nro).val()); nrodetalle++;
	var candetalle = parseInt($("#"+can).val()); candetalle++;
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=bono_periodos_registrar_laborados_insertar&nrodetalle="+nrodetalle+"&candetalle="+candetalle+"&FechaInicio="+FechaInicio+"&FechaFin="+FechaFin+"&secuencia="+secuencia,
		async: true,
		success: function(resp) {
			$("#"+nro).val(nrodetalle);
			$("#"+can).val(candetalle);
			$("#"+lista).append(resp);
			boton.disabled = false;
		}
	});
}


//	--------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE) -- ASIGNACION DIAS
function bono_periodos_registrar_laborados_quitar(boton, secuencia) {
	boton.disabled = true;
	var detalle = "laborados";
	var can = "can_" + detalle + "_" + secuencia;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle + "_" + secuencia;
	if ($("#"+sel).val() == "") cajaModal("Debe seleccionar un dia laborado", "error", 400);
	else {
		var candetalle = new Number($("#"+can).val()); candetalle--;
		$("#"+can).val(candetalle);
		var seldetalle = $("#"+sel).val();
		var tr = $("#"+seldetalle);
		tr.remove();
		$("#"+sel).val("");
	}
	boton.disabled = false;
}
//	--------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function bono_periodos_registrar_eventos_quitar(boton, secuencia) {
	boton.disabled = true;
	var detalle = "eventos";
	var can = "can_" + detalle + "_" + secuencia;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle + "_" + secuencia;
	if ($("#"+sel).val() == "") cajaModal("Debe seleccionar un evento", "error", 400);
	else {
		var candetalle = new Number($("#"+can).val()); candetalle--;
		$("#"+can).val(candetalle);
		var seldetalle = $("#"+sel).val();
		var tr = $("#"+seldetalle);
		tr.remove();
		$("#"+sel).val("");
	}
	boton.disabled = false;
}
//	--------------------------------

//	obtener la ddiferencia entre dos horas
function getDiffHoraEventos(Secuencia) {
	var CodPersona = $("#CodPersona").val();
	var CodHorario = $("#CodHorario").val();
	var Fecha = $("#Fecha_"+Secuencia).val().trim();
	var Desde = $("#HoraSalida_"+Secuencia).val().trim();
	var Hasta = $("#HoraEntrada_"+Secuencia).val().trim();
	var Total = $("#TotalHoras_"+Secuencia);
	
	if (valHora(Desde) && valHora(Hasta) && valFecha(Fecha)) {
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=getDiffHoraEventos&Desde="+formatHora(Desde)+"&Hasta="+formatHora(Hasta)+"&CodPersona="+CodPersona+"&CodHorario="+CodHorario+"&Fecha="+Fecha,
			async: true,
			success: function(resp) {
				Total.val(resp);
			}
		});
	} else Total.val("");
}
//	--------------------------------
