// JavaScript Document

//	campos default para naturales o juridicos (personas)
function setLabelTipoPersona(clase) {
	$("#Busqueda").val("");
	if (clase == "N") {
		$("#NomCompleto").attr("disabled", "disabled").val("");
		$("#sTipoPersona1").html("Natural");
		$("#sTipoPersona2").html("Nac.");
		$("#Apellido1").removeAttr("disabled").val("");
		$("#Apellido2").removeAttr("disabled").val("");
		$("#Nombres").removeAttr("disabled").val("");
		$("#Nombres").removeAttr("disabled").val("");
		$("#Nombres").removeAttr("disabled").val("");
		$("#Sexo").removeAttr("disabled").val("M");
		$("#EstadoCivil").removeAttr("disabled").val("01");
	}
	else if (clase == "J") {
		$("#NomCompleto").removeAttr("disabled").attr("value", "");
		$("#sTipoPersona1").html("Jur&iacute;dica");
		$("#sTipoPersona2").html("Const.");
		$("#Apellido1").attr("disabled", "disabled").val("");
		$("#Apellido2").attr("disabled", "disabled").val("");
		$("#Nombres").attr("disabled", "disabled").val("");
		$("#Sexo").attr("disabled", "disabled").val("");
		$("#EstadoCivil").attr("disabled", "disabled").val("");
	}
}

//	
function setTipoValorParametro(booTexto, booNumero, booFecha) {
	document.getElementById("ValorTexto").disabled = booTexto;
	document.getElementById("ValorNumero").disabled = booNumero;
	document.getElementById("ValorFecha").disabled = booFecha;
	document.getElementById("ValorTexto").value = "";
	document.getElementById("ValorNumero").value = "";
	document.getElementById("ValorFecha").value = "";
}

//	selecionar
function selAutorizaciones(concepto) {
	var idmostrar = "FlagMostrar_" + concepto;
	var idagregar = "FlagAgregar_" + concepto;
	var idmodificar = "FlagModificar_" + concepto;
	var ideliminar = "FlagEliminar_" + concepto;
	if (document.getElementById(idmostrar).checked) var boo = false; else var boo = true;
	document.getElementById(idmostrar).checked = boo;
	document.getElementById(idagregar).checked = boo;
	document.getElementById(idmodificar).checked = boo;
	document.getElementById(ideliminar).checked = boo;
	document.getElementById(idagregar).disabled = !boo;
	document.getElementById(idmodificar).disabled = !boo;
	document.getElementById(ideliminar).disabled = !boo;
}

//	selecionar
function selAutorizaciones2(concepto) {
	var idmostrar = "FlagMostrar_" + concepto;
	var idagregar = "FlagAgregar_" + concepto;
	var idmodificar = "FlagModificar_" + concepto;
	var ideliminar = "FlagEliminar_" + concepto;
	if (document.getElementById(idmostrar).checked) var boo = true; else var boo = false;
	document.getElementById(idmostrar).checked = boo;
	document.getElementById(idagregar).checked = boo;
	document.getElementById(idmodificar).checked = boo;
	document.getElementById(ideliminar).checked = boo;
	document.getElementById(idagregar).disabled = !boo;
	document.getElementById(idmodificar).disabled = !boo;
	document.getElementById(ideliminar).disabled = !boo;
}

//	seleccionar
function selChkTodosAutorizaciones(form, boo) {
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "checkbox") {
			n.checked = boo;
			if (n.name != "selTodos" && n.name != "FlagMostrar") n.disabled = !boo;
		}
	}
}

//	seleccionar
function selChkTodosAdministrador(boo) {
	var form = document.getElementById("frmautorizaciones");
	for(var i=0; n=form.elements[i]; i++) {
		if (n.type == "checkbox") {
			if (boo) {
				n.checked = !boo;
				n.disabled = boo;
			} else {
				if (n.name != "selTodos" && n.name != "FlagMostrar") n.disabled = !boo;
				else n.disabled = boo;
			}
		}
	}
}

//	selecionar
function selAlterna(concepto) {
	var idmostrar = "FlagMostrar_" + concepto;
	if (document.getElementById(idmostrar).checked) var boo = false; else var boo = true;
	document.getElementById(idmostrar).checked = boo;
}