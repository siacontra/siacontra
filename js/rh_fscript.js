// JavaScript Document

//	importar registro de compras
function grados_calificacion_general_form(form, accion) {
	//	formulario
	var codigo = document.getElementById("codigo").value.trim();
	var minimo = new Number(setNumero(document.getElementById("minimo").value.trim()));
	var maximo = new Number(setNumero(document.getElementById("maximo").value.trim()));
	var descripcion = document.getElementById("descripcion").value.trim();
	var definicion = document.getElementById("definicion").value.trim();
	if (document.getElementById("activo").checked) var estado = "A"; else var estado = "N";
	
	//	valido
	if (minimo == 0 || maximo == 0 || !valNumericoEntero(minimo) || !valNumericoEntero(maximo)) alert("¡ERROR: Debe ingresar un valor númerico entero mayor a cero para la escala!");
	else if (minimo > maximo) alert("¡ERROR: El puntaje minimo no puede ser mayor al puntaje máximo!");
	else if (descripcion == "") alert("¡ERROR: Los campos marcados con (*) son obligatorios!");
	else if (!valAlfaNumerico(descripcion) || !valAlfaNumerico(definicion)) alert("¡ERROR: No se permiten caracteres especiales en los campos!");
	else {
		cargando("block");
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "../lib/rh_fphp_ajax.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("modulo=grados_calificacion_general_form&accion="+accion+"&codigo="+codigo+"&minimo="+minimo+"&maximo="+maximo+"&descripcion="+descripcion+"&definicion="+definicion+"&estado="+estado);
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