// JavaScript Document

//	FUNCION PARA INSERTAR UNA LINEA EN UNA LISTA (TR EN TABLE)
function tipo_nomina_periodos_insertar(boton) {
	boton.disabled = true;
	detalle = "periodos";
	
	//	obtengo el valor de la ultima linea insertada
	var Periodo = "";
	var Mes = "";
	var Secuencia = "";
	var frm = document.getElementById("frm_periodos");
	for(var i=0; n=frm.elements[i]; i++) {
		if (n.name == "Periodo") Periodo = n.value;
		else if (n.name == "Mes") Mes = n.value;
		else if (n.name == "Secuencia") Secuencia = n.value;
	}
	
	//	campos
	var nro = "nro_" + detalle;
	var can = "can_" + detalle;
	var sel = "sel_" + detalle;
	var lista = "lista_" + detalle;
	var nro_detalle = new Number($("#"+nro).val()); nro_detalle++;
	var can_detalle = new Number($("#"+can).val()); can_detalle++;
	
	//	defino el path
	var php_ajax = "lib/fphp_funciones_ajax.php";
	
	//	ajax
	$.ajax({
		type: "POST",
		url: php_ajax,
		data: "accion=tipo_nomina_periodos_insertar&nro_detalle="+nro_detalle+"&can_detalle="+can_detalle+"&Periodo="+Periodo+"&Mes="+Mes+"&Secuencia="+Secuencia,
		async: true,
		success: function(resp) {
			$("#"+nro).val(nro_detalle);
			$("#"+can).val(can_detalle);
			$("#"+lista).append(resp);
			boton.disabled = false;
		}
	});
}
//	--------------------------------------