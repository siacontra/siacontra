<?php
include("fphp.php");
extract($_POST);
extract($_GET);

//	----------------
if ($tabla == "dependencia") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", "", $opcion, 0);
}

elseif ($tabla == "dependencia_filtro") {
	?><option value="">&nbsp;</option><?
	getDependencias("", $opcion, 3);
}

elseif ($tabla == "dependencia_fiscal") {
	?><option value="">&nbsp;</option><?
	loadDependenciaFiscal("", $opcion, 0);
}

elseif ($tabla == "periodo") {
	?><option value="">&nbsp;</option><?
	loadSelectNominaPeriodos($opcion1, $opcion2, 0);
}

elseif ($tabla == "estado") {
	?><option value="">&nbsp;</option><?
	loadSelectDependienteEstado("", $opcion, 0);
}

elseif ($tabla == "municipio") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", "", $opcion, 0);
}

elseif ($tabla == "ciudad") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", "", $opcion, 0);
}

elseif ($tabla == "centro_costo") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", "", $opcion, 0);
}

elseif ($tabla == "fases") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", "", $opcion, 0);
}

elseif ($tabla == "subgrupocc") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", "", $opcion, 0);
}

elseif ($tabla == "periodo_evaluacion") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("rh_evaluacionperiodo", "Periodo", "Periodo", "CodOrganismo", "", $opcion, 0);
}

elseif ($tabla == "subgrupocentrocosto") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", "", $opcion, 0);
}

elseif ($tabla == "tipo_servicio_documento") {
	loadSelectTipoServicioDocumento($opcion, 0);
}

elseif ($tabla == "cuentas_bancarias") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", "", $opcion, 0);
}

elseif ($tabla == "centro_costo") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", "", $opcion, 0);
}

elseif ($tabla == "familia") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("lg_clasefamilia", "CodFamilia", "Descripcion", "CodLinea", "", $opcion, 0);
}

elseif ($tabla == "profesiones") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente2("rh_profesiones", "CodProfesion", "Descripcion", "CodGradoInstruccion", "Area", "", $opcion1, $opcion2, 0);
}

elseif ($tabla == "profesion") {
	?><option value="">&nbsp;</option><?
	loadSelectProfesiones("", $CodGradoInstruccion, $Area, 0);
}

elseif ($tabla == "nivel-instruccion") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("rh_nivelgradoinstruccion", "Nivel", "Descripcion", "CodGradoInstruccion", "", $opcion, 0);
}

elseif ($tabla == "serie-ocupacional") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("rh_serieocupacional", "CodSerieOcup", "SerieOcup", "CodGrupOcup", "", $opcion, 0);
}

elseif ($tabla == "cargo") {
	?><option value="">&nbsp;</option><?
	loadSelectDependiente("rh_puestos", "CodCargo", "DescripCargo", "CodSerieOcup", "", $opcion, 0);
}

elseif ($tabla == "periodo-bono") {
	?><option value="">&nbsp;</option><?
	loadSelectPeriodosBono("", $CodOrganismo, $CodTipoNom, 0);
}

elseif ($tabla == "semana-bono") {
	loadSelectSemanasBono($Semana, $Periodo, $CodOrganismo, $CodTipoNom, 0);
}

elseif ($tabla == "loadSelectPeriodosNomina") {
	?><option value="">&nbsp;</option><?
	loadSelectPeriodosNomina("", $CodOrganismo, $CodTipoNom, $opt);
}

elseif ($tabla == "loadSelectPeriodosNominaProcesos") {
	loadSelectPeriodosNominaProcesos("", $Periodo, $CodOrganismo, $CodTipoNom, $opt);
}
?>