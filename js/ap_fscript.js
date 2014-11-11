// JavaScript Document

//	VALIDO FORMULARIO
function lista_obligaciones_pdf(form) {
	//	formulario
	var fdocumentod = document.getElementById("fdocumentod").value.trim();
	var fdocumentoh = document.getElementById("fdocumentoh").value.trim();
	var fpagod = document.getElementById("fpagod").value.trim();
	var fpagoh = document.getElementById("fpagoh").value.trim();
	var faprobaciond = document.getElementById("faprobaciond").value.trim();
	var faprobacionh = document.getElementById("faprobacionh").value.trim();
	var fperiodo = document.getElementById("fperiodo").value.trim();
	//	valido
	var error = "";
	/*if ((fdocumentod != "" && !valFecha(fdocumentod)) || (fdocumentoh != "" && !valFecha(fdocumentoh)))
		error = "¡ERROR: Fecha del documento incorrecta!";
	else if (fdocumentod != "" && fdocumentoh != "" && formatFechaAMD(fdocumentod) < formatFechaAMD(fdocumentoh))
		error = "¡ERROR: Fecha del documento incorrecta!";
	else if ((fpagod != "" && !valFecha(fpagod)) || (fpagoh != "" && !valFecha(fpagoh)))
		error = "¡ERROR: Fecha de pago incorrecto!";
	else if (fpagod != "" && fpagoh != "" && formatFechaAMD(fpagod) < formatFechaAMD(fpagoh))
		error = "¡ERROR: Fecha de pago incorrecto!";
	else if ((faprobaciond != "" && !valFecha(faprobaciond)) || (faprobacionh != "" && !valFecha(faprobacionh)))
		error = "¡ERROR: Fecha de aprobacion incorrecta!";
	else if (faprobaciond != "" && faprobacionh != "" && formatFechaAMD(faprobaciond) < formatFechaAMD(faprobacionh))
		error = "¡ERROR: Fecha de aprobacion incorrecta!";
	else if (fperiodo != "" && !valPeriodo(fperiodo))
		error = "¡ERROR: Periodo incorrecto!";*/
	//	muestro errores
	if (error != "") { alert(error); return false }
	else return true;
}
//	--------------------------------------

//	VALIDO FORMULARIO
function obligaciones_pendientes_provedores_pdf(form) {
	//	formulario
	var fvencimientod = document.getElementById("fvencimientod").value.trim();
	var fvencimientoh = document.getElementById("fvencimientoh").value.trim();
	//	valido
	var error = "";
	if ((fvencimientod != "" && !valFecha(fvencimientod)) || (fvencimientoh != "" && !valFecha(fvencimientoh)))
		error = "¡ERROR: Fecha de vencimiento incorrecta!";
	else if (fvencimientod != "" && fvencimientoh != "" && formatFechaAMD(fvencimientod) < formatFechaAMD(fvencimientoh))
		error = "¡ERROR: Fecha de vencimiento incorrecta!";
	//	muestro errores
	if (error != "") { alert(error); return false }
	else return true;
}
//	--------------------------------------

//	GENERAR VOUCHERS (provision)
function generar_vouchers_provision(form) {
	cargando("block");
	//	lineas
	var error = "";
	var registro = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "obligacion" && n.checked) registro += n.value + ";";
	}
	var len = registro.length; len--;
	registro = registro.substr(0, len);
	
	if (registro == "") alert("¡Debe seleccionar por lo menos un registro!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/ap_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=generar_vouchers&accion=generar_vouchers_provision&obligaciones="+registro);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
}
//	--------------------------------------

//	GENERAR VOUCHERS (pagos)
function generar_vouchers_pagos(form) {
	cargando("block");
	var organismo = document.getElementById("forganismo").value;
	var sistema_fuente = document.getElementById("sistema_fuente").value;
	var periodo = document.getElementById("fperiodo").value;
	
	//	lineas
	var error = "";
	var registro = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "pago" && n.checked) registro += n.value + ";";
	}
	var len = registro.length; len--;
	registro = registro.substr(0, len);
	
	if (registro == "") alert("¡Debe seleccionar por lo menos un registro!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/ap_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=generar_vouchers&accion=generar_vouchers_pagos&pagos="+registro+"&organismo="+organismo+"&sistema_fuente="+sistema_fuente+"&periodo="+periodo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp.trim());
				else form.submit();
			}
		}
	}
}
//	--------------------------------------

//	VALIDO FORMULARIO
function obligaciones_contable_pdf(form) {
	//	formulario
	var fregistrod = document.getElementById("fregistrod").value.trim();
	var fregistroh = document.getElementById("fregistroh").value.trim();
	//	valido
	var error = "";
	//if ((fregistrod == "" || !valFecha(fregistrod)) || (fregistroh == "" || !valFecha(fregistroh)))
		//error = "¡ERROR: Fecha de registro incorrecta!";
	//else if (fregistrod != "" && fregistroh != "" && formatFechaAMD(fregistrod) < formatFechaAMD(fregistroh))
		//error = "¡ERROR: Fecha de registro incorrecta!";
	//	muestro errores
	if (error != "") { alert(error); return false }
	else return true;
}
//	--------------------------------------

//	VALIDO FORMULARIO
function obligaciones_estado_cuenta_pdf(form, frmaction) {
	//	formulario
	var fproveedor = document.getElementById("fproveedor").value.trim();
	var fhasta = document.getElementById("fhasta").value.trim();
	//	valido
	var error = "";
	//if (fhasta == "" || !valFecha(fhasta)) error = "¡ERROR: Fecha incorrecta!";
	//else 
	if (fproveedor == "") error = "¡ERROR: Debe seleccionar un proveedor!";
	//	muestro errores
	if (error != "") { alert(error); return false }
	else {
		form.action = frmaction;
		form.submit();
	}
}
//	--------------------------------------

//	VALIDO FORMULARIO
function obligaciones_documentos_pdf(form) {
	//	formulario
	var ffechad = document.getElementById("ffechad").value.trim();
	var ffechah = document.getElementById("ffechah").value.trim();
	//	valido
	var error = "";
	//if ((ffechad == "" || !valFecha(ffechad)) || (ffechah == "" || !valFecha(ffechah)))
		//error = "¡ERROR: Fecha de registro incorrecta!";
	//else if (ffechad != "" && ffechah != "" && formatFechaAMD(ffechad) < formatFechaAMD(ffechah))
		//error = "¡ERROR: Fecha de registro incorrecta!";
	//	muestro errores
	if (error != "") { alert(error); return false }
	else {
		form.submit();
	}
}
//	--------------------------------------