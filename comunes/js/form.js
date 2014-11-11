// JavaScript Document
function devolverValorLista(id)
{
	var nodo = document.getElementById(id);
	var valor = nodo.options[nodo.selectedIndex].value;
	return valor;
	
	
}

function restablecerLista(idLista,valorLista)
{
	
    var objLista = document.getElementById(idLista);
    var tam = objLista.getElementsByTagName('option').length;
    for(var i = 0;  i<tam; i++)             
    {   
        var valor = objLista.getElementsByTagName('option')[i];

        if (valor.value == valorLista)
        {
            objLista.getElementsByTagName('option')[i].selected = true;
            break;
        }
            
    }  
}

function habilitarCondicion(id)
{
	var obj = document.getElementById(id);
	
	if(obj.checked == false)
	{
		document.getElementById('condicionRNC').disabled = true;
                document.getElementById('nivel').disabled = true;
                document.getElementById('NroInscripcionSNC').disabled = true;
                document.getElementById('FechaEmisionSNC').disabled = true;
                document.getElementById('FechaValidacionSNC').disabled = true;
                document.getElementById('calificacion').disabled = true;
                document.getElementById('capacidad').disabled = true;
		restablecerLista('condicionRNC',-1);
	
	} else {
		
		document.getElementById('condicionRNC').disabled = false;
                document.getElementById('nivel').disabled = false;
                document.getElementById('NroInscripcionSNC').disabled =false;
                document.getElementById('FechaEmisionSNC').disabled = false;
                document.getElementById('FechaValidacionSNC').disabled = false;
                document.getElementById('calificacion').disabled = false;
                document.getElementById('capacidad').disabled = false;
	}
	
	
}

//	personas
function personas(form, accion) {
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
		if ((n.id == "Busqueda" || n.id == "NomCompleto" || n.id == "TipoPersona" || n.id == "Apellido2" || n.id == "Nombres" || n.id == "Sexo" || n.id == "EstadoCivil" || n.id == "Direccion" || n.id == "CodCiudad" || n.id == "TipoDocumento" || n.id == "Ndocumento" || n.id == "DocFiscal") && $("#TipoPersona").val() == "N" && n.value.trim() == "") { error = "¡Debe llenar los campos obligatorios!"; break; }
		else if ((n.id == "Busqueda" || n.id == "NomCompleto" || n.id == "TipoPersona" || n.id == "Fnacimiento" || n.id == "Direccion" || n.id == "CodCiudad" || n.id == "TipoDocumento" || n.id == "Ndocumento" || n.id == "DocFiscal") && $("#TipoPersona").val() == "J" && n.value.trim() == "") { error = "¡Debe llenar los campos obligatorios!"; break; }
		else if ((n.id == "CodOrganismo" || n.id == "CodDependencia" || n.id == "CodCentroCosto" || n.id == "Usuario") && $("#factualizar").val() == "Empleado" && n.value.trim() == "" && document.getElementById("EsEmpleado").checked) { error = "¡Debe llenar los campos obligatorios!"; break; }
		else if ((n.id == "CodTipoDocumento" || n.id == "CodTipoPago" || n.id == "CodTipoServicio" || n.id == "CodFormaPago") && $("#factualizar").val() == "Proveedor" && n.value.trim() == "" && document.getElementById("EsProveedor").checked) { error = "¡Debe llenar los campos obligatorios!"; break; }
	}
	var Lnacimiento = $('#CodCiudad option:selected').html() + " ESTADO " + $('#CodEstado option:selected').html();
	
	
	/*if ((document.getElementById("factualizar").value == "Proveedor") && (devolverValorLista('condicionRNC') == '-1'))
	{
		error = "Debe seleccionar la condición del RNC";
	}
	*/
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);

	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=personas&accion="+accion+"&"+post+"&Lnacimiento="+Lnacimiento,
			async: false,
			success: function(msg){
				var partes = msg.split("|");				
				if (partes[0].trim() != "") cajaModal(partes[0], "error", 400);
				else {
					if (accion == "nuevo") cajaModalExito("Se generó la persona Nro. " + partes[1], 400, form);
					else form.submit();
				}
			}
		});
	}
	//return false;
}

//	aplicaciones
function aplicaciones(form, accion) {
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
		if ((n.id == "CodAplicacion" || n.id == "Descripcion" || n.id == "PeriodoContable") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valPeriodo(n.value) && n.id == "PeriodoContable") { error = "Periodo Contable Incorrecto"; break; }
		else if (!valCodigo(n.value) && n.id == "CodAplicacion") { error = "Código de Aplicacion Incorrecto"; break; }
		else if (!valCodigo(n.value) && (n.id == "PrefVoucherPD" || n.id == "PrefVoucherPA" || n.id == "PrefVoucherLP" || n.id == "PrefVoucherTB")) { error = "Prefijo de Voucher Incorrecto"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=aplicaciones&accion="+accion+"&"+post,
			async: false,
			success: function(msg){
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	parametros
function parametros(form, accion) {
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
		if ((n.id == "ParametroClave" || n.id == "DescripcionParam") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valCodigo(n.value) && n.id == "ParametroClave") { error = "Código del parámetro incorrecto"; break; }
		else if (!valNumericoFloat(n.value) && n.id == "ValorNumero") { error = "Formato del valor del número es incorrecto"; break; }
		else if (!valFecha(n.value) && n.id == "ValorFecha") { error = "Formato del valor de la fecha es incorrecta"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=parametros&accion="+accion+"&"+post,
			async: false,
			success: function(msg){
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	organismos
function organismos(form, accion) {
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
		else if (!valFecha(n.value) && n.id == "FechaFundac") { error = "Formato del valor de la fecha es incorrecta"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=organismos&accion="+accion+"&"+post,
			async: false,
			success: function(msg){
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	dependencias
function dependencias(form, accion) {
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
		if ((n.id == "CodOrganismo" || n.id == "Dependencia" || n.id == "DescripComp" || n.id == "Nivel") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ((isNaN(n.value) || n.value < 1) && n.id == "Nivel") { error = "El campo nivel debe ser numérico mayor a cero"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=dependencias&accion="+accion+"&"+post,
			async: false,
			success: function(msg){
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	plan de cuentas
function plan_cuentas(form, accion) {
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
		var CodCuenta = document.getElementById("CodCuenta").value.trim();
		if (n.id == "CodCuenta") {
			var Grupo = CodCuenta.substring(0, 1);	post += "Grupo=" + Grupo + "&";
			var SubGrupo = CodCuenta.substring(1, 2);	post += "SubGrupo=" + SubGrupo + "&";
			var Rubro = CodCuenta.substring(2, 3);	post += "Rubro=" + Rubro + "&";
			var Cuenta = CodCuenta.substring(3, 5);	post += "Cuenta=" + Cuenta + "&";
			var SubCuenta1 = CodCuenta.substring(5, 7);	post += "SubCuenta1=" + SubCuenta1 + "&";
			var SubCuenta2 = CodCuenta.substring(7, 9);	post += "SubCuenta2=" + SubCuenta2 + "&";
			var SubCuenta3 = CodCuenta.substring(9, 12);	post += "SubCuenta3=" + SubCuenta3 + "&";
		}
		
		//	errores
		if ((n.id == "Nivel" || n.id == "CodCuenta" || n.id == "Descripcion" || n.id == "TipoCuenta") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (n.id == "Nivel") {					
			if (n.value == 1 && CodCuenta.length != 1) error = "El código de la cuenta debe tener 1 caracter";
			else if (n.value == 2 && CodCuenta.length != 2) error = "El código de la cuenta debe tener 2 caracteres";
			else if (n.value == 3 && CodCuenta.length != 3) error = "El código de la cuenta debe tener 3 caracteres";
			else if (n.value == 4 && CodCuenta.length != 5) error = "El código de la cuenta debe tener 5 caracteres";
			else if (n.value == 5 && CodCuenta.length != 7) error = "El código de la cuenta debe tener 7 caracteres";
			else if (n.value == 6 && CodCuenta.length != 9) error = "El código de la cuenta debe tener 9 caracteres";
			else if (n.value == 7 && CodCuenta.length != 12) error = "El código de la cuenta debe tener 12 caracteres";
		}
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") {
		cajaModal(error, "error", 400);
	} else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=plan_cuentas&accion="+accion+"&"+post,
			async: false,
			success: function(msg){
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	grupo de centro de costos
function grupo_centro_costos(form, accion) {
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
		if ((n.id == "CodGrupoCentroCosto" || n.id == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	subgrupo
	var detalles_sub = "";
	var frm_sub = document.getElementById("frm_sub");
	for(var i=0; n=frm_sub.elements[i]; i++) {
		if (n.name == "CodSubGrupoCentroCosto") detalles_sub += n.value + "|";
		else if (n.name == "Descripcion") detalles_sub += n.value + "|";
		else if (n.name == "Estado") detalles_sub += n.value + ";";
		
		//	errores
		if ((n.name == "CodSubGrupoCentroCosto" || n.name == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	var len = detalles_sub.length; len--;
	detalles_sub = detalles_sub.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=grupo_centro_costos&accion="+accion+"&detalles_sub="+detalles_sub+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	centro de costos
function centro_costos(form, accion) {
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
		if ((n.id == "CodCentroCosto" || n.id == "Descripcion" || n.id == "CodDependencia" || n.id == "CodSubGrupoCentroCosto") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=centro_costos&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	tipo de cuenta
function tipo_cuenta(form, accion) {
	cargando("display");
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
		if ((n.id == "cod_tipocuenta" || n.id == "descp_tipocuenta") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; cargando("none"); break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; cargando("none"); break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=tipo_cuenta&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				cargando("none");
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	clasificador presupuestario
function clasificador_presupuestario(form, accion) {
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
		if ((n.id == "cod_tipocuenta" || n.id == "partida1" || n.id == "generica" || n.id == "especifica" || n.id == "subespecifica" || n.id == "denominacion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=clasificador_presupuestario&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	paises
function paises(form, accion) {
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
		if ((n.id == "Pais") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=paises&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	estados
function estados(form, accion) {
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
		if ((n.id == "Estado" || n.id == "CodPais") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=estados&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	municipios
function municipios(form, accion) {
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
		if ((n.id == "Municipio" || n.id == "CodEstado") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=municipios&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	ciudades
function ciudades(form, accion) {
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
		if ((n.id == "Ciudad" || n.id == "CodMunicipio") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=ciudades&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	tipos de pago
function tipos_pago(form, accion) {
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
		if ((n.id == "TipoPago") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=tipos_pago&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	bancos
function bancos(form, accion) {
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
		if ((n.id == "Banco") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=bancos&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	miscelaneos
function miscelaneos(form, accion) {
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
		if ((n.id == "CodAplicacion" || n.id == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	subgrupo
	var detalles_det = "";
	var frm_det = document.getElementById("frm_det");
	for(var i=0; n=frm_det.elements[i]; i++) {
		if (n.name == "CodDetalle") detalles_det += n.value + "|";
		else if (n.name == "Descripcion") detalles_det += n.value + "|";
		else if (n.name == "Estado") detalles_det += n.value + ";";
		
		//	errores
		if ((n.name == "CodDetalle" || n.name == "Descripcion") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	var len = detalles_det.length; len--;
	detalles_det = detalles_det.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=miscelaneos&accion="+accion+"&detalles_det="+detalles_det+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	usuarios
function usuarios(form, accion) {
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
		if ((n.id == "CodPersona" || n.id == "Usuario" || n.id == "Clave" || n.id == "Confirmar") && n.value.trim() == "") { error = "Debe llenar los campos obligatorios"; break; }
		else if ($("#Clave").val() != $("#Confirmar").val()) { error = "Confirmación de contraseña incorrecta"; break; }
		else if (($("#FechaExpirar").val() == "" || !valFecha($("#FechaExpirar").val())) && document.getElementById("FlagFechaExpirar").checked) { error = "Debe ingresar una fecha de expiración válida"; break; }
		else if (!valAlfaNumerico(n.value)) { error = "No se permiten caractéres especiales en los campos"; break; }
	}
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=usuarios&accion="+accion+"&"+post,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	usuarios (autorizaciones)
function usuarios_autorizaciones(form, accion) {
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
	}
	
	//	autorizaciones
	var autorizaciones = "";
	var error = "";
	var frmautorizaciones = document.getElementById("frmautorizaciones");
	for(var i=0; n=frmautorizaciones.elements[i]; i++) {
		if (n.type == "checkbox") {
			if (n.name == "FlagMostrar") {
				autorizaciones += n.value + "|";
				if (n.checked) autorizaciones += "S|"; else autorizaciones += "N|";
			}
			else if (n.name == "FlagAgregar") {
				if (n.checked) autorizaciones += "S|"; else autorizaciones += "N|";
			}
			else if (n.name == "FlagModificar") {
				if (n.checked) autorizaciones += "S|"; else autorizaciones += "N|";
			}
			else if (n.name == "FlagEliminar") {
				if (n.checked) autorizaciones += "S;"; else autorizaciones += "N;";
			}
		}
	}
	var len = autorizaciones.length; len--;
	autorizaciones = autorizaciones.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=usuarios&accion="+accion+"&"+post+"autorizaciones="+autorizaciones,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	usuarios (alterna)
function usuarios_alterna(form, accion) {
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
	}
	
	//	autorizaciones
	var autorizaciones = "";
	var error = "";
	var frmautorizaciones = document.getElementById("frmautorizaciones");
	for(var i=0; n=frmautorizaciones.elements[i]; i++) {
		if (n.type == "checkbox") {
			if (n.name == "FlagMostrar") {
				autorizaciones += n.value + "|";
				if (n.checked) autorizaciones += "S;"; else autorizaciones += "N;";
			}
		}
	}
	var len = autorizaciones.length; len--;
	autorizaciones = autorizaciones.substr(0, len);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=usuarios&accion="+accion+"&"+post+"autorizaciones="+autorizaciones,
			async: false,
			success: function(msg) {
				if (msg.trim() != "") cajaModal(msg, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}

//	unidad tributaria
function unidad_tributaria(form, accion) {
	//	valido
	var error = "";
	if ($("#Fecha").val().trim() == "" || $("#Valor").val().trim() == "") error = "Debe llenar los campos obligatorios";
	else if (!valFecha($("#Fecha").val())) error = "Formato de Fecha incorrecta";
	else if (isNaN(parseFloat(setNumero($("#Valor").val()))) || parseFloat(setNumero($("#Valor").val())) == 0) error = "Valor U.T. Incorrecta";

	//	formulario
	var post = getForm(form);
	
	//	valido errores
	if (error != "") { cajaModal(error, "error", 400); }
	else {
		$.ajax({
			type: "POST",
			url: "lib/form_ajax.php",
			data: "modulo=unidad_tributaria&accion="+accion+"&"+post,
			async: false,
			success: function(resp) {
				if (resp.trim() != "") cajaModal(resp, "error", 400);
				else form.submit();
			}
		});
	}
	return false;
}
