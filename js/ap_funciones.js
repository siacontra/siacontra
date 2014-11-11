// JavaScript Document

//	FUNCION PARA CARGAR EN UN SELECT LO SELECCIONADO EN OTRO SELECT (2 SELECTS)
function getCuentaBancariaDefault(idorganismo, idtipo_pago, idctabancaria) {
	var codorganismo = document.getElementById(idorganismo).value;
	var codtipopago = document.getElementById(idtipo_pago).value;
	var ctabancaria = document.getElementById(idctabancaria).value;
	
	//	CREO UN OBJETO AJAX
	var ajax=nuevoAjax();
	ajax.open("POST", "../lib/ap_fphp_funciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("accion=getCuentaBancariaDefault&codorganismo="+codorganismo+"&codtipopago="+codtipopago);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4)	{
			var resp = ajax.responseText.trim();	alert(resp);
			document.getElementById(idctabancaria).value = resp;
		}
	}
}