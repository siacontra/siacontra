<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;
			
		case "ESTADO-DOCUMENTOS":
			$c[0] = "E"; $v[0] = "Entregado";
			$c[1] = "D"; $v[1] = "Devuelto";
			$c[2] = "P"; $v[2] = "Perdido";
			break;
			
		case "ESTADO-DOCUMENTOS1":
			$c[0] = "E"; $v[0] = "Entregado";
			break;
			
		case "ESTADO-DOCUMENTOS2":
			$c[0] = "D"; $v[0] = "Devuelto";
			$c[1] = "P"; $v[1] = "Perdido";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;
			
		case "ESTADO-DOCUMENTOS":
			$c[0] = "E"; $v[0] = "Entregado";
			$c[1] = "D"; $v[1] = "Devuelto";
			$c[2] = "P"; $v[2] = "Perdido";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function ctaPrincipal($CodPersona, $CodSecuencia=NULL) {
	if ($CodSecuencia) $filtro = "AND CodSecuencia <> '".$CodSecuencia."'";
	$sql = "SELECT FlagPrincipal
			FROM bancopersona
			WHERE
				FlagPrincipal = 'S' AND
				CodPersona = '".$CodPersona."'
				$filtro";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) return true; else return false;
}

//	hidden que tomo del filtro de la lista de empleados para mantener los valores del filtro
function filtroEmpleados() {
	global $_APLICACION;
	global $maxlimit;
	global $fCodOrganismo;
	global $fCodDependencia;
	global $fCodCentroCosto;
	global $fCodTipoNom;
	global $fCodTipoTrabajador;
	global $fEdoReg;
	global $fSitTra;
	global $fFingresoD;
	global $fFingresoH;
	global $fBuscar;
	global $fOrderBy;
	?>
    <input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
    <input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
    <input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
    <input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
    <input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
    <input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
    <input type="hidden" name="fCodTipoNom" id="fCodTipoNom" value="<?=$fCodTipoNom?>" />
    <input type="hidden" name="fCodTipoTrabajador" id="fCodTipoTrabajador" value="<?=$fCodTipoTrabajador?>" />
    <input type="hidden" name="fEdoReg" id="fEdoReg" value="<?=$fEdoReg?>" />
    <input type="hidden" name="fSitTra" id="fSitTra" value="<?=$fSitTra?>" />
    <input type="hidden" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" />
    <input type="hidden" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" />
    <input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
    <input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
    <?
}

function totalPatrimonio($CodPersona) {
	//	inmuebles
	$sql = "SELECT SUM(Valor) AS Total
			FROM rh_patrimonio_inmueble
			WHERE CodPersona = '".$CodPersona."'
			GROUP BY CodPersona";
	$query_inmuebles = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_inmuebles) != 0) $field_inmuebles = mysql_fetch_array($query_inmuebles);
	
	//	inversiones
	$sql = "SELECT SUM(Valor) AS Total
			FROM rh_patrimonio_inversion
			WHERE CodPersona = '".$CodPersona."'
			GROUP BY CodPersona";
	$query_inversiones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_inversiones) != 0) $field_inversiones = mysql_fetch_array($query_inversiones);
	
	//	vehiculos
	$sql = "SELECT SUM(Valor) AS Total
			FROM rh_patrimonio_vehiculo
			WHERE CodPersona = '".$CodPersona."'
			GROUP BY CodPersona";
	$query_vehiculos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_vehiculos) != 0) $field_vehiculos = mysql_fetch_array($query_vehiculos);
	
	//	cuentas
	$sql = "SELECT SUM(Valor) AS Total
			FROM rh_patrimonio_cuenta
			WHERE CodPersona = '".$CodPersona."'
			GROUP BY CodPersona";
	$query_cuentas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_cuentas) != 0) $field_cuentas = mysql_fetch_array($query_cuentas);
	
	//	otros
	$sql = "SELECT SUM(Valor) AS Total
			FROM rh_patrimonio_otro
			WHERE CodPersona = '".$CodPersona."'
			GROUP BY CodPersona";
	$query_otros = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_otros) != 0) $field_otros = mysql_fetch_array($query_otros);
	
	$Total = $field_inmuebles['Total'] + $field_inversiones['Total'] + $field_vehiculos['Total'] + $field_cuentas['Total'] + $field_otros['Total'];
	
	return array($field_inmuebles['Total'], $field_inversiones['Total'], $field_vehiculos['Total'], $field_cuentas['Total'], $field_otros['Total'], $Total);
}
?>