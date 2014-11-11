// JavaScript Document

function clkLaborable(boo, dia) {
	if (boo) {
		$("."+dia).prop("disabled", false).val("");
		if ($("#FlagCorrido").prop("checked")) $("#turno2").prop("disabled", true).val("");
	}
	else $("."+dia).prop("disabled", true).val("");
}

function clkCorrido(boo) {
	if (boo) $(".turno2").prop("disabled", true).val("");
	else $(".turno2").prop("disabled", false).val("");
}