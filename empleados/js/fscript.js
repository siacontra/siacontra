// JavaScript Document

//	FUNCION PARA INSERTAR UNA LINEA EN UNA LISTA (TR EN TABLE)
function insertarLineaVacacionesUtilizacion(boton) {
	boton.disabled = true;
	var detalle = "utilizacion";
	var nro = "nro_" + detalle;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;
	var lista = "lista_" + detalle;
	var nrodetalle = new Number($("#"+nro).val()); nrodetalle++;
	var candetalle = new Number($("#"+can).val()); candetalle++;
	var sel_periodos = $("#sel_periodos").val();
	var partes = sel_periodos.split("_");
	var NroPeriodo = partes[1];
	
	//	defino el path
	var php_ajax = "lib/fphp_funciones_ajax.php";
	
	//	ajax
	$.ajax({
		type: "POST",
		url: php_ajax,
		data: "accion=empleado_vacaciones_utilizacion_linea&nrodetalle="+nrodetalle+"&candetalle="+candetalle+"&NroPeriodo="+NroPeriodo,
		async: true,
		success: function(resp) {
			$("#"+nro).val(nrodetalle);
			$("#"+can).val(candetalle);
			$("#"+lista).append(resp);
			boton.disabled = false;
		}
	});
}
//	--------------------------------------

//	FUNCION PARA INSERTAR UNA LINEA EN UNA LISTA (TR EN TABLE)
function empleado_vacaciones_periodo_sel(NroPeriodo) {
	var CodPersona = $("#CodPersona").val();
	$("#sel_utilizacion").val("");
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=empleado_vacaciones_periodo_sel&NroPeriodo="+NroPeriodo+"&CodPersona="+CodPersona,
		async: true,
		success: function(resp) {
			var datos = resp.split("|");
			$("#nro_utilizacion").val(datos[0]);
			$("#can_utilizacion").val(datos[0]);
			$("#NroPeriodo_utilizacion").val(NroPeriodo);
			$("#btu_Insertar").removeAttr("disabled");
			$("#btu_Borrar").removeAttr("disabled");
			$("#lista_utilizacion").html(datos[1]);
		}
	});
}
//	--------------------------------------

//	funcion para obtener la fecha de termino y la fecha siguiente a partir de una fecha inicial y un nro de dias
function obtenerFechaTerminoVacacionUtilizacion(detalle) {
	//	campos
	DiasUtiles = $("#DiasUtiles_"+detalle);
	FechaInicio = $("#FechaInicio_"+detalle);
	FechaFin = $("#FechaFin_"+detalle);
	
	//	valido valores
	var error = false;
	if (!valFecha(FechaInicio.val()) || FechaInicio.val().trim() == "") error = true;
	else if (isNaN(parseFloat(setNumero(DiasUtiles.val()))) || parseFloat(setNumero(DiasUtiles.val())) <= 0) error = true;
	
	if (error) {
		FechaFin.val("");
	}
	else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "lib/fphp_funciones_ajax.php",
			data: "accion=obtenerFechaTerminoVacacion&FechaSalida="+FechaInicio.val()+"&NroDias="+DiasUtiles.val(),
			async: false,
			success: function(resp){
				var fechas = resp.split("|");
				FechaFin.val(fechas[0]);
			}
		});
	}
}
//	--------------------------------------

//	funcion para obtener la fecha de termino y la fecha siguiente a partir de una fecha inicial y un nro de dias
function getSueldoBasico(CodCargo, Categoria, Sueldo) {
	if (CodCargo == "") {
		Categoria.val("");
		Sueldo.val("0,00");
	} else {
		//	ajax
		$.ajax({
			type: "POST",
			url: "../lib/fphp_funciones_ajax.php",
			data: "accion=getSueldoBasico&CodCargo="+CodCargo,
			async: false,
			success: function(resp) {
				var datos = resp.split("|");
				Categoria.val(datos[0]);
				Sueldo.val(datos[1]);
			}
		});
	}
}
//	--------------------------------------

//	FUNCION PARA EJECUTAR OPCION DE MENU DE REGISTROS
function eliminarPatrimonio(form, registro, modulo, accion) {
	//	si selecciono un registro
	if (registro == "") {
		cajaModalParent("Debe seleccionar un registro", "error", 400);
	} else {
		parent.$("#cajaModal").dialog({
			buttons: {
				"Aceptar": function() {
					$.ajax({
						type: "POST",
						url: "lib/form_ajax.php",
						data: "modulo="+modulo+"&accion="+accion+"&registro="+registro,
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
					parent.$(this).dialog("close");
				},
				"Cancelar": function() {
					parent.$(this).dialog("close");
				}
			}
		});
		cajaModalConfirmParent("Está seguro de realizar esta acción", 400);
	}
}
//	-----------------------------