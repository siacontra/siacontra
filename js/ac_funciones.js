// JavaScript Document

//	funcion para colorear los negativos
function setNegativo(campo) {
	var valor = new Number(setNumero(campo.value));
	if (valor < 0) campo.style.color = "#F00";
	else if (valor) campo.style.color = "#000";
}
//	--------------------------------------

//	funcion para calcular el total de los montos en voucher
function sumar_voucher() {
	var total_creditos = new Number(0.00);
	var total_debitos = new Number(0.00);
	var frm_detalle = document.getElementById("frm_detalle");
	for(i=0; n=frm_detalle.elements[i]; i++) {
		if (n.name == "monto") {
			var monto = new Number(setNumero(n.value));
			if (!isNaN(monto)) {
				if (monto < 0) total_creditos += monto;
				else total_debitos += monto;
			}
		}
	}
	document.getElementById("total_creditos").innerHTML = setNumeroFormato(total_creditos, 2, ".", ",");
	document.getElementById("total_debitos").innerHTML = setNumeroFormato(total_debitos, 2, ".", ",");
}
//	--------------------------------------