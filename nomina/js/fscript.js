// JavaScript Document

// 	funcion para seleccionar de una lista un registro y colocar su valor en la ventana que lo llamo
function fideicomiso_calculo_listado(form) {
	//	valido
	var error = "";
	
	
	
	if ($("#Periodo").val().trim() == "" || isNaN($("#Periodo").val().trim())) error = "Debe ingresar un periodo valido";
	else if ($("#CodPersona").val().trim() == "") error = "Debe seleccionar un empleado";
	
	//	formulario
	var post = "";
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "hidden" || n.type == "text" || n.type == "password" || n.type == "select-one" || n.type == "textarea") {
			post += n.id + "=" + changeUrl(n.value.trim()) + "&";
		} else {
			if (n.type == "checkbox") {
				if (n.checked) post += n.id + "=S" + "&"; else post += n.id + "=N" + "&";
			}
			else if (n.type == "radio" && n.checked) {
				post += n.name + "=" + n.value.trim() + "&";
			}
		}
	}
	
	//	ajax
	$.ajax({
		type: "POST",
		url: "lib/fphp_funciones_ajax.php",
		data: "accion=fideicomiso_calculo_listado&"+post,
		async: false,
		success: function(resp) {
			$("#listaCalculo").html(resp);
		}
	});
}
//	-------------------------------

//	FUNCION SELECT DEPENDIENTE
function loadSelectPeriodosNomina(opt) {
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	//
	var option = "<option value=''>&nbsp;</option>";
	$("#fPeriodo").empty().append(option);
	$("#fCodTipoProceso").empty().append(option);
	//
	if (CodOrganismo != "" && CodTipoNom != "") {
		$.ajax({
			type: "POST",
			url: "../lib/fphp_selects.php",
			data: "tabla=loadSelectPeriodosNomina&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&opt="+opt,
			async: true,
			success: function(resp) {
				$("#fPeriodo").empty().append(resp);
			}
		});
	}
}
//	-------------------------------

//	FUNCION SELECT DEPENDIENTE
function loadSelectPeriodosNominaProcesos(opt) {
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodTipoNom = $("#fCodTipoNom").val();
	var Periodo = $("#fPeriodo").val();
	//
	var option = "<option value=''>&nbsp;</option>";
	$("#fCodTipoProceso").empty().append(option);
	//
	if (CodOrganismo != "" && CodTipoNom != "" && Periodo != "") {
		$.ajax({
			type: "POST",
			url: "../lib/fphp_selects.php",
			data: "tabla=loadSelectPeriodosNominaProcesos&CodOrganismo="+CodOrganismo+"&CodTipoNom="+CodTipoNom+"&Periodo="+Periodo+"&opt="+opt,
			async: true,
			success: function(resp) {
				$("#fCodTipoProceso").empty().append(resp);
			}
		});
	}
}
//	-------------------------------
