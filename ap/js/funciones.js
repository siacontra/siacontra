// JavaScript Document

//	ABRIR GENERAR VOUCHERS
function generar_vouchers_abrir(registro, pagina, imprimir) {
	window.open("gehen.php?anz="+pagina+"&registro="+registro+"&imprimir="+imprimir, pagina, "toolbar=no, menubar=no, location=no, scrollbars=yes, width=1000, height=600");
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaObligacionDocumento(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
	boton.disabled = true;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;	
	var lista = "lista_" + detalle;
	var id = document.getElementById(sel).value;
	if (document.getElementById(sel).value == "") alert("¡Debe seleccionar una linea!");
	else {
		//	elimino la linea del documento
		var candetalle = new Number(document.getElementById(can).value); candetalle--;
		document.getElementById(can).value = candetalle;
		var seldetalle = document.getElementById(sel).value;
		var listaDetalles = document.getElementById(lista);
		var tr = document.getElementById(seldetalle);
		listaDetalles.removeChild(tr);
		//	elimino las lineas de la distribucion
		var idsel = document.getElementById(sel).value;
		var partes = idsel.split("_");
		var idTr = ".trListaBody.distribucion_" + partes[1];
		$(idTr).remove();
		document.getElementById(sel).value = "";
	}
	boton.disabled = false;
	if (candetalle == 0) setObligacionPagoDirecto(document.getElementById("FlagDistribucionManual").checked);
	else actualizarMontosObligacion();
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaImpuesto(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
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
	}
	boton.disabled = false;
	//	actualizar montos de la obligacion
	var MontoAfecto = setNumero($("#MontoAfecto").val());
	var MontoNoAfecto = setNumero($("#MontoNoAfecto").val());
	var MontoImpuesto = setNumero($("#MontoImpuesto").val());
	actualizar_afecto_retenciones(MontoAfecto, MontoNoAfecto, MontoImpuesto, document.getElementById("frm_impuesto"));
	var MontoImpuestoOtros = obtener_obligacion_retenciones(document.getElementById("frm_impuesto"));
	var MontoObligacion = MontoAfecto + MontoNoAfecto + MontoImpuesto + MontoImpuestoOtros;
	$("#MontoImpuestoOtros").val(setNumeroFormato(MontoImpuestoOtros, 2, ".", ","));
	$("#MontoObligacion").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoPagar").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoPendiente").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#impuesto_total").val(setNumeroFormato(MontoImpuestoOtros, 2, ".", ","));
}
//	--------------------------------------

//	FUNCION PARA ELIMINAR UNA LINEA DE UNA LISTA (TR EN TABLE)
function quitarLineaDistribucion(boton, detalle) {
	/*
	.- boton	-> referencia del boton (objeto)
	.- detalle	-> sufijo de los campos de la lista
	*/
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
	}
	boton.disabled = false;
	//	actualizar montos de la obligacion
	actualizarMontosObligacion();
}
//	--------------------------------------

//	SI CHEQUEO PAGO DIRECTO EN LAS OBLIGACIONES
function setObligacionPagoDirecto(boo) {
	if ($("#CodProveedor").val().trim() != "") {
		//	limpio las listas
		$("#lista_documento").html("");
		$("#lista_distribucion").html("");
		$("#lista_impuesto").html("");
		//	si selecciono pago directo
		if (boo) {
			$("#btInsertarDocumento").attr("disabled", "disabled");
			$("#btQuitarDocumento").attr("disabled", "disabled");
			$("#btInsertarDistribucion").removeAttr("disabled");
			$("#btQuitarDistribucion").removeAttr("disabled");
			$("#btSelCuenta").removeAttr("disabled");
			$("#btSelCCosto").removeAttr("disabled");
			$("#btSelPersona").removeAttr("disabled");
			$("#btInsertarImpuesto").removeAttr("disabled");
			$("#btQuitarImpuesto").removeAttr("disabled");
			if ($("#FlagPresupuesto").attr("checked") == "checked") $("#btSelPartida").removeAttr("disabled");
		} else {
			$("#btInsertarDocumento").removeAttr("disabled");
			$("#btQuitarDocumento").removeAttr("disabled");
			$("#btInsertarDistribucion").attr("disabled", "disabled");
			$("#btQuitarDistribucion").attr("disabled", "disabled");
			$("#btSelPartida").attr("disabled", "disabled");
			$("#btSelCuenta").attr("disabled", "disabled");
			$("#btSelCCosto").attr("disabled", "disabled");
			$("#btSelPersona").attr("disabled", "disabled");
			$("#btInsertarImpuesto").attr("disabled", "disabled");
			$("#btQuitarImpuesto").attr("disabled", "disabled");
			$("#FlagCompromiso").removeAttr("checked");
			$("#FlagPresupuesto").attr("checked", "checked");
		}
		//	actualizo valores
		actualizarMontosObligacion();
	}
}
//	--------------------------------------

//	
function FlagCompromisoObligacion(boo) {
	if (!document.getElementById("FlagPresupuesto").disabled && !document.getElementById("FlagDistribucionManual").disabled) {
		if (boo) {
			$("#FlagPresupuesto").attr("checked", "checked");
			$("#FlagDistribucionManual").attr("checked", "checked");
			setObligacionPagoDirecto(true);
		}
	}
}
//	--------------------------------------

//				
function FlagPresupuestoObligacion(boo) {
	if (boo) {
		$("#FlagCompromiso").removeAttr("disabled").removeAttr("checked");
		$("#btSelPartida").removeAttr("disabled");
		$(".cell.cod_partida").removeAttr("disabled");
	} else {
		$("#FlagCompromiso").attr("disabled", "disabled").removeAttr("checked", "checked");
		$("#FlagDistribucionManual").attr("checked", "checked");
		$("#btSelPartida").attr("disabled", "disabled");
		$(".cell.cod_partida").attr("disabled", "disabled");
		setObligacionPagoDirecto(true);
	}
}
//	--------------------------------------

//	ACTUALIZO LOS TOTALES
function actualizarMontoImpuestoObligacion() {
	//	impuestos
	var frm_impuesto = document.getElementById("frm_impuesto");
	for(var i=0; n=frm_impuesto.elements[i]; i++) {
		if (n.name == "Signo") var _Signo = n.value;
		else if (n.name == "MontoAfecto") var _MontoAfecto = new Number(setNumero(n.value));
		else if (n.name == "FactorPorcentaje") var _FactorPorcentaje = new Number(setNumero(n.value));
		else if (n.name == "MontoImpuesto") {
			var _MontoImpuesto = new Number(_MontoAfecto * _FactorPorcentaje / 100);
			if (_Signo == "N") _MontoImpuesto *= -1;
			n.value = setNumeroFormato(_MontoImpuesto, 2, ".", ",");
		}
	}
	actualizarMontosObligacion();
}
//	--------------------------------------

//	ACTUALIZO LOS MONTOS DE LA OBLIGACION (IMPUESTOS)
function actualizarMontosObligacionImpuesto() {
	//	distribucion
	var MontoAfecto = new Number(0);
	var MontoNoAfecto = new Number(0);
	var frm_distribucion = document.getElementById("frm_distribucion");
	for(var i=0; n=frm_distribucion.elements[i]; i++) {
		if (n.name == "FlagNoAfectoIGV") {
			if (n.checked) var FlagNoAfectoIGV = "S";
			else var FlagNoAfectoIGV = "N";
		}
		if (n.name == "Monto") {
			var monto = new Number(setNumero(n.value));
			if (FlagNoAfectoIGV == "S") MontoNoAfecto += monto;
			else MontoAfecto += monto;
		}
	}
	
	//	calculo montos
	var FactorImpuesto = new Number($("#FactorImpuesto").val());
	var MontoImpuesto = new Number(MontoAfecto * FactorImpuesto / 100);
	
	//	impuestos
	var frm_impuesto = document.getElementById("frm_impuesto");
	for(var i=0; n=frm_impuesto.elements[i]; i++) {
		if (n.name == "Signo") var _Signo = n.value;
		else if (n.name == "FlagImponible") var _FlagImponible = n.value;
		else if (n.name == "MontoAfecto") {
			if (_FlagImponible == "I") var _MontoAfecto = new Number(MontoImpuesto);
			else if (_FlagImponible == "N") var _MontoAfecto = new Number(MontoAfecto+MontoNoAfecto);
			n.value = setNumeroFormato(_MontoAfecto, 2, ".", ",");
		}
		else if (n.name == "FactorPorcentaje") var _FactorPorcentaje = new Number(setNumero(n.value));
		else if (n.name == "MontoImpuesto") {
			var _MontoImpuesto = new Number(_MontoAfecto * _FactorPorcentaje / 100);
			if (_Signo == "N") _MontoImpuesto *= -1;
			n.value = setNumeroFormato(_MontoImpuesto, 2, ".", ",");
		}
	}
	actualizarMontosObligacion();
}
//	--------------------------------------

//	ACTUALIZO LOS MONTOS DE LA OBLIGACION
function actualizarMontosObligacion() {
	//	impuestos
	var impuesto_total = new Number(0);
	var frm_impuesto = document.getElementById("frm_impuesto");
	for(var i=0; n=frm_impuesto.elements[i]; i++) {
		if (n.name == "MontoImpuesto") {
			var monto = new Number(setNumero(n.value));
			impuesto_total += monto;
		}
	}
	
	//	documentos
	var documento_total = new Number(0);
	var documento_afecto = new Number(0);
	var documento_impuesto = new Number(0);
	var documento_noafecto = new Number(0);
	var frm_documento = document.getElementById("frm_documento");
	for(var i=0; n=frm_documento.elements[i]; i++) {
		if (n.name == "MontoAfecto") {
			var monto = new Number(setNumero(n.value));
			documento_afecto += monto;
		}
		else if (n.name == "MontoNoAfecto") {
			var monto = new Number(setNumero(n.value));
			documento_noafecto += monto;
		}
	}
	
	//	distribucion
	var distribucion_total = new Number(0);
	var MontoAfecto = new Number(0);
	var MontoNoAfecto = new Number(0);
	var frm_distribucion = document.getElementById("frm_distribucion");
	for(var i=0; n=frm_distribucion.elements[i]; i++) {
		if (n.name == "FlagNoAfectoIGV") {
			if (n.checked) var FlagNoAfectoIGV = "S";
			else var FlagNoAfectoIGV = "N";
		}
		if (n.name == "Monto") {
			var monto = new Number(setNumero(n.value));
			distribucion_total += monto;
			if (FlagNoAfectoIGV == "S") MontoNoAfecto += monto;
			else MontoAfecto += monto;
		}
	}
	
	//	calculo montos
	var FactorImpuesto = new Number($("#FactorImpuesto").val());
	var documento_impuesto = new Number(documento_afecto * FactorImpuesto / 100);
	var documento_total = new Number(documento_afecto + documento_noafecto + documento_impuesto);
	var MontoImpuesto = new Number(MontoAfecto * FactorImpuesto / 100);
	var MontoObligacion = new Number(MontoAfecto + MontoNoAfecto + MontoImpuesto + impuesto_total);
	var MontoAdelanto = new Number(0);
	var MontoPagar = new Number(MontoObligacion - MontoAdelanto);
	var MontoPagoParcial = new Number(0);
	var MontoPendiente = new Number(MontoPagar - MontoPagoParcial);
	
	//	asigno montos a la lista
	$("#impuesto_total").val(setNumeroFormato(impuesto_total, 2, ".", ","));
	$("#documento_total").val(setNumeroFormato(documento_total, 2, ".", ","));
	$("#documento_afecto").val(setNumeroFormato(documento_afecto, 2, ".", ","));
	$("#documento_impuesto").val(setNumeroFormato(documento_impuesto, 2, ".", ","));
	$("#documento_noafecto").val(setNumeroFormato(documento_noafecto, 2, ".", ","));	
	$("#distribucion_total").val(setNumeroFormato(distribucion_total, 2, ".", ","));
	
	//	asigno montos generales
	$("#MontoAfecto").val(setNumeroFormato(MontoAfecto, 2, ".", ","));
	$("#MontoNoAfecto").val(setNumeroFormato(MontoNoAfecto, 2, ".", ","));
	$("#MontoImpuesto").val(setNumeroFormato(MontoImpuesto, 2, ".", ","));
	$("#MontoImpuestoOtros").val(setNumeroFormato(impuesto_total, 2, ".", ","));
	$("#MontoObligacion").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoAdelanto").val(setNumeroFormato(MontoAdelanto, 2, ".", ","));
	$("#MontoPagar").val(setNumeroFormato(MontoPagar, 2, ".", ","));
	$("#MontoPagoParcial").val(setNumeroFormato(MontoPagoParcial, 2, ".", ","));
	$("#MontoPendiente").val(setNumeroFormato(MontoPendiente, 2, ".", ","));
}
//	--------------------------------------

//	ACTUALIZO LOS TOTALES DE LA OBLIGACION
function actualizarTotalesObligacion(campo) {
	//	valores actuales
	var FactorImpuesto = new Number($("#FactorImpuesto").val());
	var Afecto = new Number(setNumero($("#MontoAfecto").val()));
	var NoAfecto = new Number(setNumero($("#MontoNoAfecto").val()));
	var Impuesto = new Number(setNumero($("#MontoImpuesto").val()));
	var ImpuestoOtros = new Number(setNumero($("#MontoImpuestoOtros").val()));
	var Obligacion = new Number(setNumero($("#MontoObligacion").val()));
	var MontoAdelanto = new Number(setNumero($("#MontoAdelanto").val()));
	var MontoPagoParcial = new Number(setNumero($("#MontoPagoParcial").val()));	
	//	calculo
	var MontoImpuesto = new Number(Afecto * FactorImpuesto / 100);
	if (campo == "MontoAfecto" || campo == "MontoNoAfecto") {
		MontoAfecto = Afecto;
		MontoNoAfecto = NoAfecto;
		MontoImpuesto = MontoAfecto * FactorImpuesto / 100;
		MontoImpuestoOtros = ImpuestoOtros;
		MontoObligacion = MontoAfecto + MontoNoAfecto + MontoImpuesto - MontoImpuestoOtros;
	}
	else if (campo == "MontoImpuesto") {
		MontoAfecto = Impuesto / FactorImpuesto * 100;
		MontoNoAfecto = NoAfecto;
		MontoImpuesto = Impuesto;
		MontoImpuestoOtros = ImpuestoOtros;
		MontoObligacion = MontoAfecto + MontoNoAfecto + MontoImpuesto - MontoImpuestoOtros;
	}
	//	asigno montos generales
	MontoPagar = MontoObligacion - MontoAdelanto;
	MontoPendiente = MontoPagar - MontoPagoParcial;
	$("#MontoAfecto").val(setNumeroFormato(MontoAfecto, 2, ".", ","));
	$("#MontoNoAfecto").val(setNumeroFormato(MontoNoAfecto, 2, ".", ","));
	$("#MontoImpuesto").val(setNumeroFormato(MontoImpuesto, 2, ".", ","));
	$("#MontoImpuestoOtros").val(setNumeroFormato(MontoImpuestoOtros, 2, ".", ","));
	$("#MontoObligacion").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoPagar").val(setNumeroFormato(MontoPagar, 2, ".", ","));
	$("#MontoPendiente").val(setNumeroFormato(MontoPendiente, 2, ".", ","));
}
//	--------------------------------------

//	cargo documentos para preparar factura
function cargarOpcionPrepararFactura(frm_documento) {
	//	datos generales
	var CodOrganismo = $("#fCodOrganismo").val();
	var CodProveedor = $("#fCodProveedor").val();
	var DocumentoClasificacion = $("#fDocumentoClasificacion").val();
	
	//	detalles documento
	var detalles_documento = "";
	for(var i=0; n=frm_documento.elements[i]; i++) {
		if (n.name == "documento" && n.checked) detalles_documento += n.value + ";";
	}
	var len = detalles_documento.length; len--;
	detalles_documento = detalles_documento.substr(0, len);
	
	$("#registro").val(detalles_documento);
	$("#frmentrada").attr("action", "gehen.php?anz=ap_facturacion_form");
	$("#frmentrada").submit();
}
//	--------------------------------------

//	
function abrirListadoPartidasDisponiblesObligacion() {
	var CodOrganismo = $("#CodOrganismo").val();
	var pag = "../lib/listas/listado_clasificador_presupuestario_disponible.php?filtrar=default&cod=cod_partida&nom=NomPartida&campo3=CodCuenta&campo4=NomCuenta&ventana=selListadoLista&seldetalle=sel_distribucion&CodOrganismo="+CodOrganismo+"&iframe=true&width=1050&height=500";
	$("#aSelPartida").attr("href", pag);
	validarAbrirLista('sel_distribucion', 'aSelPartida');
	
}
//	--------------------------------------

//	
function cambiar_monto_impuesto() {
	var MontoAfecto = parseFloat(setNumero($("#MontoAfecto").val()));
	var MontoNoAfecto = parseFloat(setNumero($("#MontoNoAfecto").val()));
	var MontoImpuesto = parseFloat(setNumero($("#MontoImpuesto").val()));
	actualizar_afecto_retenciones(MontoAfecto, MontoNoAfecto, MontoImpuesto, document.getElementById("frm_impuesto"));
	var MontoImpuestoOtros = obtener_obligacion_retenciones(document.getElementById("frm_impuesto"));
	var MontoObligacion = MontoAfecto + MontoNoAfecto + MontoImpuesto + MontoImpuestoOtros;
	$("#MontoImpuestoOtros").val(setNumeroFormato(MontoImpuestoOtros, 2, ".", ","));
	$("#MontoObligacion").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoPagar").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#MontoPendiente").val(setNumeroFormato(MontoObligacion, 2, ".", ","));
	$("#impuesto_total").val(setNumeroFormato(MontoImpuestoOtros, 2, ".", ","));
}
//	--------------------------------------

//	bloqueo/desbloqueo campos de provision en el maestro de tipo de doc. cxp
function setFlagProvision(chk) {
	$("#CodCuentaProv").val("");
	if (chk) $("#a_CodCuentaProv").css("visibility", "visible");
	else $("#a_CodCuentaProv").css("visibility", "hidden");
}
//	--------------------------------------

//	bloqueo/desbloqueo campos de adelanto en el maestro de tipo de doc. cxp
function setFlagAdelanto(chk) {
	$("#CodCuentaAde").val("");
	if (chk) $("#a_CodCuentaAde").css("visibility", "visible");
	else $("#a_CodCuentaAde").css("visibility", "hidden");
}
//	--------------------------------------


function limitarCaracteres(id,limite)
{
	var elemento= document.getElementById(id);
	
    var totalMensaje = elemento.value.length;
	
    if (totalMensaje > limite) 
	{
        elemento.value = elemento.value.substring(0, limite);
        totalMensaje = elemento.value.length;
    }
   
}
