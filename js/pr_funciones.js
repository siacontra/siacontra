// JavaScript Document

function fideicomiso_depositos_antiguedad_imprimir() {
	var forganismo = document.getElementById("forganismo").value;
	var fnomina = document.getElementById("fnomina").value;
	var fperiodo = document.getElementById("fperiodo").value;
	if (document.getElementById("inactivos").checked) var inactivos = "S"; else var inactivos = "N";
	window.open("pr_fideicomiso_depositos_antiguedad_pdf.php?forganismo="+forganismo+"&fnomina="+fnomina+"&fperiodo="+fperiodo, "fideicomiso_depositos_antiguedad_pdf", "height=900, width=1000, left=100, top=0, resizable=no, toolbar=no, menubar=no, location=no, scrollbars=yes, ");
}