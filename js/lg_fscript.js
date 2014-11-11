// JavaScript Document

//	clasificaciones
function clasificaciones(form, accion) {
	cargando("block");
	//	formulario
	var codigo = document.getElementById("codigo").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	if (document.getElementById("requisiciones").checked) var disponible = "R"; else var disponible = "O";
	var codalmacen = document.getElementById("codalmacen").value.trim();
	var requerimiento = document.getElementById("requerimiento").value.trim();
	if (document.getElementById("flagrevision").checked) var flagrevision = "S"; else var flagrevision = "N";
	if (document.getElementById("flagrecepcion").checked) var flagrecepcion = "S"; else var flagrecepcion = "N";
	if (document.getElementById("flagcajachica").checked) var flagcajachica = "S"; else var flagcajachica = "N";
	if (document.getElementById("flagtransaccion").checked) var flagtransaccion = "S"; else var flagtransaccion = "N";
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "I";
	if (document.getElementById("almacen").checked) var almacen_compra = "A"; else var almacen_compra = "C";
	
	//	busco errores
	var error = "";
	if (descripcion == "" || codalmacen == "" || requerimiento == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valAlfaNumerico(codigo) || !valAlfaNumerico(descripcion)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=clasificaciones&accion="+accion+"&codigo="+codigo+"&descripcion="+descripcion+"&disponible="+disponible+"&codalmacen="+codalmacen+"&requerimiento="+requerimiento+"&flagrevision="+flagrevision+"&flagrecepcion="+flagrecepcion+"&flagcajachica="+flagcajachica+"&flagtransaccion="+flagtransaccion+"&estado="+estado+"&almacen_compra="+almacen_compra);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else form.submit();
			}
		}
	}
	return false;
}

//	requerimientos
function requerimientos(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var codinterno = document.getElementById("codinterno").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	if (document.getElementById("flagcompras").checked) var flagdirigido = "C"; else var flagdirigido = "A";
	var prioridad = document.getElementById("prioridad").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	var almacen = document.getElementById("almacen").value.trim();
	var frequerida = document.getElementById("frequerida").value.trim();
	var preparadopor = document.getElementById("preparadopor").value.trim();
	var fpreparado = document.getElementById("fpreparado").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var tiporequerimiento = document.getElementById("tiporequerimiento").value.trim();
	if (document.getElementById("flagcajachica").checked) var flagcajachica = "S"; else var flagcajachica = "N";
	var tiporequerimiento = document.getElementById("tiporequerimiento").value.trim();
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var nomproveedor = document.getElementById("nomproveedor").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var docref = document.getElementById("docref").value.trim();
	var error = "";
	
	//	detalles
	var detalles = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "unidad") detalles += n.value + "|";
		else if (n.name == "codccosto") {
			if (n.value.trim() == "") { error = "¡ERROR: El centro de costo en los detalles es obligatorio!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalles += "S|"; else detalles += "N|";
		}
		else if (n.name == "cantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (!valNumericoFloat(cantidad) || cantidad == 0) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += cantidad + "|";
		}
		else if (n.name == "docreferencia") detalles += n.value + "|";
		else if (n.name == "codcuenta") detalles += n.value + "|";
		else if (n.name == "codpartida") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	buscar errores
	if (ccosto == "" || almacen == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";	
	else if (!valAlfaNumerico(comentarios) || !valAlfaNumerico(razon)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	else if (!valFecha(frequerida)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (detalles == "") error = "¡ERROR: Debe ingresar por lo menos un detalle!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=requerimientos&accion="+accion+"&organismo="+organismo+"&dependencia="+dependencia+"&codigo="+codigo+"&codinterno="+codinterno+"&estado="+estado+"&clasificacion="+clasificacion+"&flagdirigido="+flagdirigido+"&prioridad="+prioridad+"&ccosto="+ccosto+"&almacen="+almacen+"&frequerida="+frequerida+"&preparadopor="+preparadopor+"&fpreparado="+fpreparado+"&comentarios="+comentarios+"&razon="+razon+"&tiporequerimiento="+tiporequerimiento+"&flagcajachica="+flagcajachica+"&clasificacion="+clasificacion+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&docref="+docref+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					if (accion == "nuevo" && datos[1]) alert(datos[1]);
					else if (accion == "aprobar") {
						var registro = organismo + "." + codigo;
						window.open("lg_requerimientos_pdf.php?registro="+registro, "requerimientos_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=950, left=100, top=0, resizable=no");
					}
					form.submit();
				}
			}
		}
	}
	return false;
}

//	cotizaciones (invitar)
function cotizaciones_invitar_proveedor(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var flimite = document.getElementById("flimite").value.trim();
	var condiciones = document.getElementById("condiciones").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var error = "";
	
	//	proveedores
	var proveedores = "";
	var frm_proveedor = document.getElementById("frm_proveedor");
	for(var i=0; n=frm_proveedor.elements[i]; i++) {
		if (n.name == "codproveedor") proveedores += n.value + "|";
		else if (n.name == "codformapago") proveedores += n.value + ";";
	}
	var len = proveedores.length; len--;
	proveedores = proveedores.substr(0, len);
	
	//	requerimientos
	var requerimientos = "";
	var frm_requerimiento = document.getElementById("frm_requerimiento");
	for(var i=0; n=frm_requerimiento.elements[i]; i++) {
		if (n.name == "requerimiento") requerimientos += n.value + "|";
		else if (n.name == "cantidad") requerimientos += n.value + "|";
		else if (n.name == "flagexonerado") requerimientos += n.value + ";";
	}
	var len = requerimientos.length; len--;
	requerimientos = requerimientos.substr(0, len);
	
	//	buscar errores
	if (flimite == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valFecha(flimite)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (!valAlfaNumerico(condiciones) || !valAlfaNumerico(observaciones)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	else if (proveedores == "") error = "¡ERROR: Debe seleccionar por lo menos un proveedor!";
	else if (requerimientos == "") error = "¡ERROR: No seleccionó ningún requerimiento!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=cotizaciones&accion="+accion+"&flimite="+flimite+"&condiciones="+condiciones+"&observaciones="+observaciones+"&proveedores="+proveedores+"&requerimientos="+requerimientos);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(resp);
				else {					
					opener.document.getElementById("frmentrada").submit();
					window.close();
					window.open("lg_cotizaciones_invitar_pdf.php?numero="+datos[1], "wPDF", "toolbar=no, menubar=no, location=no, scrollbars=yes, resizable=no, left=0, top=0, width=800, height=900");
				}
			}
		}
	}
	return false;
}


//	cotizaciones (cotizar x item)
function cotizaciones_invitar_cotizar(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var codorganismo = document.getElementById("codorganismo").value.trim();
	var codrequerimiento = document.getElementById("codrequerimiento").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var coditem = document.getElementById("coditem").value.trim();
	var numero = document.getElementById("numero").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var error = "";
	
	//	detalle
	var detalle = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codproveedor") detalle += n.value + "|";
		else if (n.name == "flagasig") {
			if (n.checked) var flagasig = "S"; else flagasig = "N";
			detalle += flagasig + "|";
		}
		else if (n.name == "cant") {
			var cant = new Number(n.value);
			detalle += cant + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(n.value);
			detalle += pu + "|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalle += "S|"; else detalle += "N|";
		}
		else if (n.name == "pu_igv") {
			var pu_igv = new Number(n.value);
			detalle += pu_igv + "|";
		}
		else if (n.name == "descp") {
			var descp = new Number(n.value);
			detalle += descp + "|";
		}
		else if (n.name == "descf") {
			var descf = new Number(n.value);
			detalle += descf + "|";
		}
		else if (n.name == "pu_total") {
			var pu_total = new Number(n.value);
			detalle += pu_total + "|";
		}
		else if (n.name == "total") {
			var total = new Number(n.value);
			detalle += total + "|";
		}
		else if (n.name == "comparar") {
			var comparar = new Number(n.value);
			detalle += comparar + "|";
		}
		else if (n.name == "flagmejor") {
			if (n.checked) var flagmejor = "S"; else flagmejor = "N";
			detalle += flagmejor + "|";			
		}
		else if (n.name == "formapago") detalle += n.value + "|";
		else if (n.name == "finvitacion") detalle += n.value + "|";
		else if (n.name == "flimite") detalle += n.value + "|";
		else if (n.name == "condiciones") detalle += n.value + "|";
		else if (n.name == "observaciones") {
			detalle += n.value + "|";
			if (flagasig == "S" && flagmejor == "N") {
				var prompt_obs = true;
				var observaciones = n.value;
			} else {
				var prompt_obs = false;
				var observaciones = "";
			}
		}
		else if (n.name == "dias") detalle += n.value + "|";
		else if (n.name == "validez") detalle += n.value + "|";
		else if (n.name == "nrocotizacion") detalle += n.value + ";";
	}
	var len = detalle.length; len--;
	detalle = detalle.substr(0, len);
	
	//	buscar errores
	if (detalle == "") error = "¡ERROR: Debe ingresar por lo menos una cotización!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		if (prompt_obs) var obs = prompt('Explique porque no seleccciono el mejor precio', ''); else var obs = observaciones;
		
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=cotizaciones&accion="+accion+"&codorganismo="+codorganismo+"&codrequerimiento="+codrequerimiento+"&secuencia="+secuencia+"&coditem="+coditem+"&numero="+numero+"&detalle="+detalle+"&obs="+obs);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					opener.document.getElementById("frmentrada").submit();
					window.close();
				}
			}
		}
	}
	return false;
}

//	cotizaciones (cotizar x proveedor)
function cotizaciones_invitaciones_cotizar(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var finvitacion = document.getElementById("finvitacion").value.trim();
	var flimite = document.getElementById("flimite").value.trim();
	var fcotizacion = document.getElementById("fcotizacion").value.trim();
	var frecepcion = document.getElementById("frecepcion").value.trim();
	var fapertura = document.getElementById("fapertura").value.trim();
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var descp = new Number(setNumero(document.getElementById("descp").value.trim()));
	var descf = new Number(setNumero(document.getElementById("descf").value.trim()));
	var nrocotizacionprov = document.getElementById("nrocotizacionprov").value.trim();
	var validez = document.getElementById("validez").value.trim();
	var dias = document.getElementById("dias").value.trim();
	var codformapago = document.getElementById("codformapago").value.trim();
	var error = "";
	
	//	detalle
	var detalle = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "cotizacion_secuencia") detalle += n.value + "|";
		else if (n.name == "cant") {
			var cant = new Number(n.value);
			detalle += cant + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(n.value);
			detalle += pu + "|";
		}
		else if (n.name == "flagasig") {
			if (n.checked) detalle += "S|"; else detalle += "N|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalle += "S|"; else detalle += "N|";
		}
		else if (n.name == "pu_igv") {
			var pu_igv = new Number(n.value);
			detalle += pu_igv + "|";
		}
		else if (n.name == "descp") {
			var descp = new Number(n.value);
			detalle += descp + "|";
		}
		else if (n.name == "descf") {
			var descf = new Number(n.value);
			detalle += descf + "|";
		}
		else if (n.name == "pu_desc") {
			var pu_desc = new Number(n.value);
			detalle += pu_desc + "|";
		}
		else if (n.name == "pu_total") {
			var pu_total = new Number(n.value);
			detalle += pu_total + "|";
		}
		else if (n.name == "total") {
			var total = new Number(n.value);
			detalle += total + "|";
		}
		else if (n.name == "observaciones") {
			detalle += n.value + ";";			
		}
	}
	var len = detalle.length; len--;
	detalle = detalle.substr(0, len);
	
	//	buscar errores
	if (!valFecha(fapertura) || !valFecha(frecepcion) || !valFecha(fcotizacion)) error = "¡ERROR: El formato de las fechas es (dd-mm-aaaa)!";
	else if (!valAlfaNumerico(detalle)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=cotizaciones&accion="+accion+"&finvitacion="+finvitacion+"&flimite="+flimite+"&fcotizacion="+fcotizacion+"&frecepcion="+frecepcion+"&fapertura="+fapertura+"&codproveedor="+codproveedor+"&descp="+descp+"&descf="+descf+"&nrocotizacionprov="+nrocotizacionprov+"&validez="+validez+"&dias="+dias+"&detalle="+detalle+"&codformapago="+codformapago);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				if (resp.trim() != "") alert(resp);
				else {
					opener.document.getElementById("frmentrada").submit();
					window.close();
				}
			}
		}
	}
	return false;
}
//	generar ordenes pendientes (compras)
function generar_ordenes_pendientes_compra(form, nrocotizacionprov) {
	cargando("block");
	//	formulario
	var codorganismo = document.getElementById("codorganismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var codformapago = document.getElementById("codformapago").value;
	var codservicio = document.getElementById("codservicio").value;
	var almacen_entrega = document.getElementById("almacen_entrega").value;
	var almacen_ingreso = document.getElementById("almacen_ingreso").value;
	var nomcontacto = document.getElementById("nomcontacto").value.trim();
	var faxcontacto = document.getElementById("faxcontacto").value.trim();
	var entregaren = document.getElementById("entregaren").value.trim();
	var direccion = document.getElementById("direccion").value.trim();
	var instruccion = document.getElementById("instruccion").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var codtipopago = document.getElementById("codtipopago").value.trim();
	var dias_vence = document.getElementById("dias_vence").value.trim();
	var coddependencia = document.getElementById("coddependencia").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	var monto_afecto = new Number (setNumero(document.getElementById("monto_afecto").value.trim()));
	var monto_noafecto = new Number (setNumero(document.getElementById("monto_noafecto").value.trim()));
	var monto_bruto = new Number (setNumero(document.getElementById("monto_bruto").value.trim()));
	var monto_impuestos = new Number (setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number (setNumero(document.getElementById("monto_total").value.trim()));
	var monto_pendiente = new Number (setNumero(document.getElementById("monto_pendiente").value.trim()));
	var monto_otros = new Number (setNumero(document.getElementById("monto_otros").value.trim()));
	
	//	busco errores
	var error = "";
	if (fentrega == "" || nomproveedor == "" || codformapago == "" || almacen_entrega == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valFecha(fentrega)) error = "¡ERROR: Fecha de entrega incorrecta!";
	else if (!valAlfaNumerico(nomproveedor) || !valAlfaNumerico(nomcontacto) || !valAlfaNumerico(faxcontacto) || !valAlfaNumerico(entregaren) || !valAlfaNumerico(direccion) || !valAlfaNumerico(instruccion) || !valAlfaNumerico(observaciones)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else if (confirm("¿Está seguro de generar esta orden?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=generar_ordenes_pendientes&accion=compras&nrocotizacionprov="+nrocotizacionprov+"&codorganismo="+codorganismo+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&fentrega="+fentrega+"&codformapago="+codformapago+"&codservicio="+codservicio+"&almacen_entrega="+almacen_entrega+"&almacen_ingreso="+almacen_ingreso+"&nomcontacto="+nomcontacto+"&faxcontacto="+faxcontacto+"&entregaren="+entregaren+"&direccion="+direccion+"&instruccion="+instruccion+"&observaciones="+observaciones+"&clasificacion="+clasificacion+"&coddependencia="+coddependencia+"&monto_afecto="+monto_afecto+"&monto_noafecto="+monto_noafecto+"&monto_bruto="+monto_bruto+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&monto_pendiente="+monto_pendiente+"&monto_otros="+monto_otros+"&clasificacion="+clasificacion+"&ccosto="+ccosto+"&dias_entrega="+dias_entrega+"&codtipopago="+codtipopago+"&dias_vence="+dias_vence);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");				
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					window.close();
					opener.document.getElementById("frmentrada").submit();
				}
			}
		}
	} else cargando("none");
	return false;
}

//	generar ordenes pendientes (servicios)
function generar_ordenes_pendientes_servicio(form, nrocotizacionprov) {
	cargando("block");
	//	formulario
	var codorganismo = document.getElementById("codorganismo").value;
	var codproveedor = document.getElementById("codproveedor").value;
	var nomproveedor = document.getElementById("nomproveedor").value;
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var codformapago = document.getElementById("codformapago").value;
	var dias_entrega = document.getElementById("dias_entrega").value;
	var codservicio = document.getElementById("codservicio").value;
	var descripcion = document.getElementById("descripcion").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var codtipopago = document.getElementById("codtipopago").value.trim();
	var dias_vence = document.getElementById("dias_vence").value.trim();
	var coddependencia = document.getElementById("coddependencia").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	var monto_afecto = new Number (setNumero(document.getElementById("monto_afecto").value.trim()));
	var monto_impuestos = new Number (setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number (setNumero(document.getElementById("monto_total").value.trim()));
	
	//	busco errores
	var error = "";
	if (codformapago == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valAlfaNumerico(descripcion) || !valAlfaNumerico(observaciones)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else if (confirm("¿Está seguro de generar esta orden?")) {
		//	CREO UN OBJETO AJAX PARA VERIFICAR QUE EL NUEVO REGISTRO NO EXISTA EN LA BASE DE DATOS
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=generar_ordenes_pendientes&accion=servicios&nrocotizacionprov="+nrocotizacionprov+"&codorganismo="+codorganismo+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&codformapago="+codformapago+"&codservicio="+codservicio+"&descripcion="+descripcion+"&observaciones="+observaciones+"&clasificacion="+clasificacion+"&coddependencia="+coddependencia+"&monto_afecto="+monto_afecto+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&clasificacion="+clasificacion+"&ccosto="+ccosto+"&dias_entrega="+dias_entrega+"&codtipopago="+codtipopago+"&dias_vence="+dias_vence);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					window.close();
					opener.document.getElementById("frmentrada").submit();
				}
			}
		}
	} else cargando("none");
	return false;
}

//	compras
function compras(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var anio = document.getElementById("anio").value.trim();
	var mes = document.getElementById("mes").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var clasificacion = document.getElementById("clasificacion").value.trim();
	var impuesto = document.getElementById("impuesto").value.trim();
	var codservicio = document.getElementById("codservicio").value.trim();
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var nomproveedor = document.getElementById("nomproveedor").value.trim();
	var codformapago = document.getElementById("codformapago").value.trim();
	var almacen_entrega = document.getElementById("almacen_entrega").value.trim();
	var almacen_ingreso = document.getElementById("almacen_ingreso").value.trim();
	var nomcontacto = document.getElementById("nomcontacto").value.trim();
	var faxcontacto = document.getElementById("faxcontacto").value.trim();
	var entregaren = document.getElementById("entregaren").value.trim();
	var direccion = document.getElementById("direccion").value.trim();
	var instruccion = document.getElementById("instruccion").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var preparadopor = document.getElementById("preparadopor").value.trim();
	var fpreparado = document.getElementById("fpreparado").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var tipoclasificacion = document.getElementById("tipoclasificacion").value.trim();
	var monto_afecto = new Number(setNumero(document.getElementById("monto_afecto").value.trim()));
	var monto_noafecto = new Number(setNumero(document.getElementById("monto_noafecto").value.trim()));
	var monto_bruto = new Number(setNumero(document.getElementById("monto_bruto").value.trim()));
	var monto_impuestos = new Number(setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number(setNumero(document.getElementById("monto_total").value.trim()));
	var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value.trim()));
	var monto_otros = new Number(setNumero(document.getElementById("monto_otros").value.trim()));
	var error = "";
	
	//	detalles
	var detalles = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "codunidad") detalles += n.value + "|";
		else if (n.name == "cantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (!valNumericoFloat(cantidad) || cantidad == 0) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(setNumero(n.value));
			if (!valNumericoFloat(pu) || pu == 0) { error = "¡ERROR: Se encontraron precios unitarios en el detalle incorrectas!"; break; }
			else detalles += pu + "|";
		}
		else if (n.name == "descp") {
			var descp = new Number(setNumero(n.value));
			if (!valNumericoFloat(descp)) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += descp + "|";
		}
		else if (n.name == "descf") {
			var descf = new Number(setNumero(n.value));
			if (!valNumericoFloat(descf)) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += descf + "|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalles += "S|"; else detalles += "N|";
		}
		else if (n.name == "pu_total") {
			var pu_total = new Number(setNumero(n.value));
			detalles += pu_total + "|";
		}
		else if (n.name == "total") {
			var total = new Number(setNumero(n.value));
			detalles += total + "|";
		}
		else if (n.name == "fentrega") detalles += n.value + "|";
		else if (n.name == "codccosto") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin centro de costo!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "codpartida") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin partida presupuestaria!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "codcuenta") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin cuenta contable!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "observaciones") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	buscar errores
	if (dias_entrega == "" || fentrega == "" || codproveedor == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valFecha(fentrega)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (!valNumericoEntero(dias_entrega)) error = "¡ERROR: Dias de entrega incorrecto!";
	else if (!valAlfaNumerico(comentarios) || !valAlfaNumerico(razon) || !valAlfaNumerico(nomproveedor)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	else if (detalles == "") error = "¡ERROR: Debe ingresar por lo menos un detalle!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=compras&accion="+accion+"&anio="+anio+"&mes="+mes+"&organismo="+organismo+"&codigo="+codigo+"&estado="+estado+"&clasificacion="+clasificacion+"&impuesto="+impuesto+"&codservicio="+codservicio+"&dias_entrega="+dias_entrega+"&fentrega="+fentrega+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&codformapago="+codformapago+"&almacen_entrega="+almacen_entrega+"&almacen_ingreso="+almacen_ingreso+"&nomcontacto="+nomcontacto+"&faxcontacto="+faxcontacto+"&entregaren="+entregaren+"&direccion="+direccion+"&instruccion="+instruccion+"&razon="+razon+"&preparadopor="+preparadopor+"&fpreparado="+fpreparado+"&comentarios="+comentarios+"&razon="+razon+"&tipoclasificacion="+tipoclasificacion+"&monto_afecto="+monto_afecto+"&monto_noafecto="+monto_noafecto+"&monto_bruto="+monto_bruto+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&monto_pendiente="+monto_pendiente+"&monto_otros="+monto_otros+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					if (accion == "nuevo" && datos[1]) alert(datos[1]);
					else if (accion == "aprobar") {
						var registro = anio + "." + organismo + "." + codigo;
						window.open("lg_compras_pdf.php?registro="+registro, "w_compras_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=950, left=100, top=0, resizable=no");
					}
					form.submit();
				}
			}
		}
	}
	return false;
}

//	servicios
function servicios(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var anio = document.getElementById("anio").value.trim();
	var mes = document.getElementById("mes").value.trim();
	var codigo = document.getElementById("codigo").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var nrointerno = document.getElementById("nrointerno").value.trim();	
	var impuesto = document.getElementById("impuesto").value.trim();
	var codservicio = document.getElementById("codservicio").value.trim();	
	var dias_entrega = document.getElementById("dias_entrega").value.trim();
	var fentrega = document.getElementById("fentrega").value.trim();	
	var codproveedor = document.getElementById("codproveedor").value.trim();
	var nomproveedor = document.getElementById("nomproveedor").value.trim();	
	var codtipopago = document.getElementById("codtipopago").value.trim();	
	var codformapago = document.getElementById("codformapago").value.trim();	
	var dias_pagar = document.getElementById("dias_pagar").value.trim();
	var desde = document.getElementById("desde").value.trim();
	var hasta = document.getElementById("hasta").value.trim();	
	var ccosto = document.getElementById("ccosto").value.trim();
	var descripcion = document.getElementById("descripcion").value.trim();
	var descadicional = document.getElementById("descadicional").value.trim();
	var razon = document.getElementById("razon").value.trim();
	var observaciones = document.getElementById("observaciones").value.trim();	
	var preparadopor = document.getElementById("preparadopor").value.trim();
	var fpreparado = document.getElementById("fpreparado").value.trim();
	var monto_original = new Number(setNumero(document.getElementById("monto_original").value.trim()));
	var monto_impuestos = new Number(setNumero(document.getElementById("monto_impuestos").value.trim()));
	var monto_total = new Number(setNumero(document.getElementById("monto_total").value.trim()));
	var monto_gastado = new Number(setNumero(document.getElementById("monto_gastado").value.trim()));
	var monto_pendiente = new Number(setNumero(document.getElementById("monto_pendiente").value.trim()));
	var error = "";
	
	//	detalles
	var detalles = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "cantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (!valNumericoFloat(cantidad) || cantidad == 0) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "pu") {
			var pu = new Number(setNumero(n.value));
			if (!valNumericoFloat(pu) || pu == 0) { error = "¡ERROR: Se encontraron precios unitarios en el detalle incorrectas!"; break; }
			else detalles += pu + "|";
		}
		else if (n.name == "flagexon") {
			if (n.checked) detalles += n.value + "S|";
			else detalles += n.value + "N|";
		}
		else if (n.name == "total") {
			var total = new Number(setNumero(n.value));
			detalles += total + "|";
		}
		else if (n.name == "fesperada") {
			if (!valFecha(n.value)) { error = "¡ERROR: Se encontrarón fechas en el detalle incorrectas!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "freal") {
			if (!valFecha(n.value)) { error = "¡ERROR: Se encontrarón fechas en el detalle incorrectas!"; break; }
			else detalles += n.value + "|";		
		}
		else if (n.name == "codccosto") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin centro de costo!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "codactivo") detalles += n.value + "|";
		else if (n.name == "flagterminado") {
			if (n.checked) detalles += n.value + "S|";
			else detalles += n.value + "N|";
		}
		else if (n.name == "codpartida") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin partida presupuestaria!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "codcuenta") {
			if (n.value == "") { error = "¡ERROR: Se encontrarón líneas en el detalle sin cuenta contable!"; break; }
			else detalles += n.value + "|";
		}
		else if (n.name == "observaciones") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	buscar errores
	if (dias_entrega == "" || fentrega == "" || codproveedor == "" || codtipopago == "" || codformapago == "" || dias_pagar == "" || desde == "" || hasta == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valFecha(fentrega)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (!valNumericoEntero(dias_entrega)) error = "¡ERROR: Dias de entrega incorrecto!";
	else if (!valNumericoEntero(dias_pagar)) error = "¡ERROR: Dias para pagar incorrecto!";
	else if (!valFecha(desde)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (!valFecha(hasta)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (!valAlfaNumerico(nomproveedor) || !valAlfaNumerico(descripcion) || !valAlfaNumerico(descadicional) || !valAlfaNumerico(observaciones) || !valAlfaNumerico(razon)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	else if (detalles == "") error = "¡ERROR: Debe ingresar por lo menos un detalle!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=servicios&accion="+accion+"&anio="+anio+"&mes="+mes+"&organismo="+organismo+"&codigo="+codigo+"&estado="+estado+"&dependencia="+dependencia+"&nrointerno="+nrointerno+"&impuesto="+impuesto+"&codservicio="+codservicio+"&dias_entrega="+dias_entrega+"&fentrega="+fentrega+"&codproveedor="+codproveedor+"&nomproveedor="+nomproveedor+"&codtipopago="+codtipopago+"&codformapago="+codformapago+"&dias_pagar="+dias_pagar+"&desde="+desde+"&hasta="+hasta+"&ccosto="+ccosto+"&descripcion="+descripcion+"&descadicional="+descadicional+"&razon="+razon+"&observaciones="+observaciones+"&preparadopor="+preparadopor+"&fpreparado="+fpreparado+"&monto_original="+monto_original+"&monto_impuestos="+monto_impuestos+"&monto_total="+monto_total+"&monto_gastado="+monto_gastado+"&monto_pendiente="+monto_pendiente+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					if (accion == "nuevo" && datos[1]) alert(datos[1]);
					else if (accion == "aprobar") {
						var registro = anio + "." + organismo + "." + codigo;
						window.open("lg_servicios_pdf.php?registro="+registro, "lg_servicios_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=950, left=100, top=0, resizable=no");
					}
					form.submit();
				}
			}
		}
	}
	return false;
}

//	servicios (confirmar)
function confirmar_servicios(form, accion) {
	cargando("block");
	//	formulario
	//	datos generales
	var anio = document.getElementById("anio").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var nroorden = document.getElementById("nroorden").value.trim();
	var secuencia = document.getElementById("secuencia").value.trim();
	var ftermino = document.getElementById("ftermino").value.trim();
	var cantidad_pendiente = document.getElementById("cantidad_pendiente").value.trim();
	var cantidad_recibida = new Number(setNumero(document.getElementById("cantidad_recibida").value.trim()));
	var cantidad_recibir = new Number(setNumero(document.getElementById("cantidad_recibir").value.trim()));
	var saldo = new Number(setNumero(document.getElementById("saldo").value.trim()));
	var error = "";
	
	//	buscar errores
	if (ftermino == "") error = "¡ERROR: Los campos marcados con (*) son obligatorios!";
	else if (!valFecha(ftermino)) error = "¡ERROR: Formato de fecha incorrecta!";
	else if (isNaN(cantidad_recibir) || cantidad_recibir == 0) error = "¡ERROR: Cantidad por recibir incorrecta!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=servicios&accion="+accion+"&anio="+anio+"&organismo="+organismo+"&nroorden="+nroorden+"&secuencia="+secuencia+"&cantidad_pendiente="+cantidad_pendiente+"&ftermino="+ftermino+"&cantidad_recibir="+cantidad_recibir+"&saldo="+saldo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					var registro = anio + "." + organismo + "." + nroorden + "." + secuencia + "." + datos[1];
					window.open("lg_servicios_confirmar_pdf.php?registro="+registro, "lg_servicios_confirmar_pdf", "toolbar=no, menubar=no, location=no, scrollbars=yes, height=700, width=950, left=100, top=0, resizable=no");
					form.submit();
				}
			}
		}
	}
	return false;
}

//	almacen (despacho de commodities por oc)
function transacciones_especiales_despachar(form) {
	cargando("block");
	//	formulario
	//	datos generales
	var anio = document.getElementById("anio").value.trim();
	var organismo = document.getElementById("organismo").value.trim();
	var coddocumento = document.getElementById("coddocumento").value.trim();
	var dependencia = document.getElementById("dependencia").value.trim();
	var fecha = document.getElementById("fecha").value.trim();
	var periodo = document.getElementById("periodo").value.trim();
	var codtransaccion = document.getElementById("codtransaccion").value.trim();
	var coddocumentoreferencia = document.getElementById("coddocumentoreferencia").value.trim();
	var nrodocumentoreferencia = document.getElementById("nrodocumentoreferencia").value.trim();
	var preparadopor = document.getElementById("preparadopor").value.trim();
	var revisadopor = document.getElementById("revisadopor").value.trim();
	var comentarios = document.getElementById("comentarios").value.trim();
	var coddocumento = document.getElementById("coddocumento").value.trim();
	var estado = document.getElementById("estado").value.trim();
	var almacen = document.getElementById("almacen").value.trim();
	var documentoreferencia = document.getElementById("documentoreferencia").value.trim();
	var ccosto = document.getElementById("ccosto").value.trim();
	var codubicacion = document.getElementById("codubicacion").value.trim();
	var referencianrodocumento = document.getElementById("referencianrodocumento").value.trim();
	if (document.getElementById("flagactivo").checked) var flagactivo = "S"; else var flagactivo = "N";	
	var error = "";
	
	//	detalles
	var detalles = "";
	var frm_detalle = document.getElementById("frm_detalle");
	for(var i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "codigo") detalles += n.value + "|";
		else if (n.name == "descripcion") detalles += n.value + "|";
		else if (n.name == "codunidad") detalles += n.value + "|";
		else if (n.name == "cantidad") {
			var cantidad = new Number(setNumero(n.value));
			if (!valNumericoFloat(cantidad) || cantidad == 0) { error = "¡ERROR: Se encontraron cantidades en el detalle incorrectas!"; break; }
			else detalles += cantidad + "|";
		}
		else if (n.name == "codbarra") detalles += n.value + "|";
		else if (n.name == "codccosto") detalles += n.value + "|";
		else if (n.name == "pu") {
			var pu = new Number(setNumero(n.value));
			if (!valNumericoFloat(pu) || pu == 0) { error = "¡ERROR: Se encontraron precios unitarios en el detalle incorrectas!"; break; }
			else detalles += pu + "|";
		}
		else if (n.name == "total") {
			var total = new Number(setNumero(n.value));
			detalles += total + "|";
		}
		else if (n.name == "codalmacen") detalles += n.value + "|";
		else if (n.name == "coddocumento") detalles += n.value + "|";
		else if (n.name == "nrodocumento") detalles += n.value + "|";
		else if (n.name == "secuencia") detalles += n.value + "|";
		else if (n.name == "clasificacion") detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	buscar errores
	if (!valAlfaNumerico(comentarios)) error = "¡ERROR: No se permiten carácteres especiales en los campos!";
	else if (detalles == "") error = "¡ERROR: Debe ingresar por lo menos un detalle!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/lg_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=transacciones_especiales&accion=despachar&anio="+anio+"&organismo="+organismo+"&dependencia="+dependencia+"&fecha="+fecha+"&periodo="+periodo+"&codtransaccion="+codtransaccion+"&coddocumentoreferencia="+coddocumentoreferencia+"&nrodocumentoreferencia="+nrodocumentoreferencia+"&preparadopor="+preparadopor+"&revisadopor="+revisadopor+"&comentarios="+comentarios+"&coddocumento="+coddocumento+"&estado="+estado+"&almacen="+almacen+"&documentoreferencia="+documentoreferencia+"&ccosto="+ccosto+"&codubicacion="+codubicacion+"&referencianrodocumento="+referencianrodocumento+"&coddocumento="+coddocumento+"&flagactivo="+flagactivo+"&detalles="+detalles);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				cargando("none");
				var resp = ajax.responseText;
				var datos = resp.split("|");
				if (datos[0].trim() != "") alert(datos[0]);
				else {
					form.submit();
					alert(datos[1]);
					window.close();
				}
			}
		}
	}
	return false;
}

