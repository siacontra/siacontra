// JavaScript Document

function cargarOpcionEditarEvaluacion(form, pagina, target, param, accion) {
	var codigo = form.registro.value;
	if (codigo == "") msjError(1000);
	else {
		//	creo un objeto ajax
		var ajax = nuevoAjax();
		ajax.open("POST", "fphp_funciones.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("accion=verificarEstadoEvaluacion&id="+codigo);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (resp == "EV" && (accion == "EDITAR" || accion == "EVALUAR")) {
					alert("¡ERROR: No puede modificar una evaluación en estado 'Evaluado'!");
				} else {
					if (target == "SELF") cargarPagina(form, pagina);
					else {
						pagina = pagina + "?limit=0&accion=VER&registro=" + codigo;
						cargarVentana(form, pagina, param);
					}
				}
			}
		}
	}
}