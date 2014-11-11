// JavaScript Document

//	actualizar acumulados
function actualizarAcumulados(form) {
	cargando("block");
	//	formulario
	var organismo = document.getElementById("forganismo").value.trim();
	var nomina = document.getElementById("fnomina").value.trim();
	var periodo = document.getElementById("fperiodo").value.trim();
	
	//	detalle
	var error = "";
	var detalles = "";
	for(i=0; n=form.elements[i]; i++) {
		if (n.name == "persona") {
			var sel = n.checked;
			if (sel) detalles += n.value + "|";
		} 
		else if (n.name == "mes_monto" && sel) detalles += n.value + "|";
		else if (n.name == "mes_dias" && sel) detalles += n.value + "|";
		else if (n.name == "complemento_monto" && sel) detalles += n.value + "|";
		else if (n.name == "complemento_dias" && sel) detalles += n.value + ";";
	}
	var len = detalles.length; len--;
	detalles = detalles.substr(0, len);
	
	//	busco errores
	if (detalles == "") error = "¡ERROR: Debe seleccionar por lo menos un trabajador!";
	
	//	valido
	if (error != "") { alert(error); cargando("none"); }
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/pr_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=fideicomiso&accion=actualizarAcumulados&organismo="+organismo+"&nomina="+nomina+"&periodo="+periodo+"&detalles="+detalles);
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