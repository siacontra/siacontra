<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
//	consulto resumen
$sql = "SELECT
			*,
			(TotalDiasPeriodo - TotalDiasPago - TotalFeriados) AS Inactivos
		FROM rh_bonoalimentacion
		WHERE
			CodOrganismo = '".$fCodOrganismo."' AND
			CodTipoNom = '".$fCodTipoNom."' AND
			CodBonoAlim = '".$fPeriodo."'";
$query_resumen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_resumen) != 0) $field_resumen = mysql_fetch_array($query_resumen);
$FechaInicio = formatFechaDMA($field_resumen['FechaInicio']);
$FechaFin = formatFechaDMA($field_resumen['FechaFin']);
$TotalDias = getFechaDias($FechaInicio, $FechaFin) + 1;
$TotalDiasPago = getDiasHabiles($FechaInicio, $FechaFin);
$TotalFeriados = getDiasFeriados($FechaInicio, $FechaFin);
$Inactivos = $TotalDias - $TotalDiasPago - $TotalFeriados;
$HorasSemanal = $TotalDiasPago * $field_resumen['HorasDiaria'];
$ValorSemanal = $field_resumen['ValorDia'] * $TotalDias;
$DiffDias = getFechaDias(formatFechaDMA($field_resumen['FechaInicio']), $FechaInicio) + 1;
//---------------------------------------------------
//list($FechaInicio, $FechaFin) = split("[|]", $fSemana);
$filtro = "";
$tituloNomina=""; 
if ($fCodOrganismo != "") $filtro .= " AND (bad.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (e.CodDependencia = '".$fCodDependencia."')";
if ($fCodCentroCosto != "") $filtro .= " AND (e.CodCentroCosto = '".$fCodCentroCosto."')";
if ($fCodTipoNom != "") {
	$filtro .= " AND (ba.CodTipoNom = '".$fCodTipoNom."')"; 
//	consulto tipo de nomina ( Se puede optimizar)
$sql_nomina = "SELECT
tiponomina.CodTipoNom,
tiponomina.Nomina,
tiponomina.TituloBoleta
FROM
tiponomina

WHERE 
CodTipoNom = '".$fCodTipoNom."'";
$query_tiponomina = mysql_query($sql_nomina) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_nomina));


if (mysql_num_rows($query_tiponomina) != 0) $field_tiponomina = mysql_fetch_array($query_tiponomina);
$tituloNomina =  " ".$field_tiponomina['TituloBoleta'];
	
}
if ($fCodPerfil != "") $filtro .= " AND (e.CodPerfil = '".$fCodPerfil."')";
if ($fEdoReg != "") $filtro .= " AND (p.Estado = '".$fEdoReg."')";
if ($fSitTra != "") $filtro .= " AND (e.Estado = '".$fSitTra."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
									  p.NomCompleto LIKE '%".$fBuscar."%' OR
									  p.Ndocumento LIKE '%".$fBuscar."%')";
if ($fCodCargo != "") {
	$inner_cargo = "INNER JOIN rh_cargoreporta cr ON (cr.CodCargo = e.CodCargo AND
													  cr.CargoReporta = '".$fCodCargo."')";
}
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $tituloNomina;
		global $_PARAMETRO;
		global $Ahora;
		global $field_resumen;
		global $FechaInicio;
		global $FechaFin;
		global $_POST;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(180, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(180, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Resumen Mensual: '.$tituloNomina), 0, 1, 'C', 0);
		$this->SetFont('Arial', 'BI', 8);
		$this->SetXY(10, 24); $this->Cell(190, 5, utf8_decode('Periodo del '.formatFechaDMA($field_resumen['FechaInicio']).' al '.formatFechaDMA($field_resumen['FechaFin'])), 0, 1, 'C', 0);
		$this->Ln(5);
		##
	}
	
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
##	imprimo resumen
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(200, 200, 200);
$pdf->SetWidths(array(28, 28, 30, 30, 30, 30, 30));
$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HORAS DIARIAS',
				'HORAS MENSUALES',
				'DIAS HABILES',
				'DIAS FERIADOS',
				'DIAS INACTIVOS',
				'VALOR X DIA',
				'VALOR MENSUAL'));
$pdf->SetFillColor(255, 255, 255);
$pdf->Row(array($field_resumen['HorasDiaria'],
				$HorasSemanal,
				$TotalDiasPago,
				$TotalFeriados,
				$Inactivos,
				number_format($field_resumen['ValorDia'], 2, ',', '.'),
				number_format($ValorSemanal, 2, ',', '.')));

//	consulto
$sql = "SELECT
			bad.Anio,
			bad.CodOrganismo,
			bad.CodBonoAlim,
			bad.CodPersona,
			bad.DiasPeriodo,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			rh_bonoalimentaciondet bad
			INNER JOIN rh_bonoalimentacion ba ON (ba.Anio = bad.Anio AND
												  ba.CodOrganismo = bad.CodOrganismo AND
												  ba.CodBonoAlim = bad.CodBonoAlim)
			INNER JOIN mastpersonas p ON (p.CodPersona = bad.CodPersona)
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			$inner_cargo
		WHERE
			bad.Anio = '".$field_resumen['Anio']."' AND
			bad.CodOrganismo = '".$field_resumen['CodOrganismo']."' AND
			bad.CodBonoAlim = '".$field_resumen['CodBonoAlim']."' $filtro
		GROUP BY CodPersona
		ORDER BY CodEmpleado";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$sw=0;
while ($field = mysql_fetch_array($query)) {	$sw++;
	##	imprimo empleado
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Ln(5);
	$pdf->Cell(12, 5, $field['CodEmpleado'], 0, 0, 'L', 0);
	$pdf->Cell(170, 5, utf8_decode($field['NomCompleto']), 0, 0, 'L', 0);
	$pdf->Ln(5);
	##
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(240, 240, 240);
	$pdf->SetWidths(array(25, 25, 25, 25, 25, 25, 28, 28));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array('HORAS FALTANTES',
					'HORAS TRABAJADAS',
					'DIAS FERIADOS',
					'DIAS INACTIVOS',
					'DIAS DESCUENTO',
					'DIAS A PAGAR',
					'VALOR A DESCONTAR',
					'VALOR A PAGAR'));
	##	
	$TotalH = 0;
	$TotalM = 0;
	$TotalDiasFeriados = 0;
	$TotalDiasInactivos = 0;
	$TotalDiasDescuento = 0;
	$TotalDiasPago = 0;
	$TotalValorDescuento = 0;
	$TotalTotalPagar = 0;
	$_DiffDias = $DiffDias;
	//	obtengo el nro de semanas
	$swSemana = true;
	$ns = 0;
	$nro_semanas = 0;
	$fi = formatFechaDMA($field_resumen['FechaInicio']);
	while($swSemana) {
		++$ns;
		++$nro_semanas;
		$dsemana = getWeekDay($fi);
		$dias_semana[$ns] = 7 - $dsemana + 1;
		$ff = obtenerFechaFin($fi, $dias_semana[$ns]);
		if (formatFechaAMD($ff) >= $field_resumen['FechaFin']) { $ff = formatFechaDMA($field_resumen['FechaFin']); $swSemana = false; }
		$fechai[$ns] = $fi;
		$fechaf[$ns] = $ff;
		//	
		$_dias_semanas_habiles[$ns] = getDiasHabiles($fi, $ff);
		//	
		$fi = obtenerFechaFin($ff, 2);
	}
	//	imprimo las semanas
	for($ns=1;$ns<=$nro_semanas;$ns++) {
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->Cell(206, 5, 'Semana del '.$fechai[$ns].' al '.$fechaf[$ns], 0, 1, 'C', 1);
		##	consulto eventos
		$SumH = 0;
		$SumM = 0;
		$sql = "SELECT
					Fecha,
					HoraSalida,
					HoraEntrada,
					TotalHoras
				FROM rh_bonoalimentacioneventos
				WHERE
					Anio = '".$field['Anio']."' AND
					CodOrganismo = '".$field['CodOrganismo']."' AND
					CodBonoAlim = '".$field['CodBonoAlim']."' AND
					CodPersona = '".$field['CodPersona']."' AND
					Fecha >= '".formatFechaAMD($fechai[$ns])."' AND
					Fecha <= '".formatFechaAMD($fechaf[$ns])."'";
		$query_eventos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_eventos = mysql_fetch_array($query_eventos)) {
			list($_H, $_M) = split("[:]", $field_eventos['TotalHoras']);
			if ($_H < 10) $H = "0$_H"; else $H = "$_H";
			if ($_M < 10) $M = "0$_M"; else $M = "$_M";
			$TotalHoras = "$H:$M";
			$SumH += $_H;
			$SumM += $_M;
			$TotalH += $_H;
			$TotalM += $_M;
		}
		##	totalizo
		if ($SumM > 60) {
			$Horas = $SumH + intval($SumM / 60);
			$Minutos = ($Horas * 60) - $SumM;
		} else {
			$Horas = $SumH;
			$Minutos = $SumM;
		}
		if ($Horas < 10) $HF = "0$Horas"; else $HF = "$Horas";
		if ($Minutos < 10) $MF = "0$Minutos"; else $MF = "$Minutos";
		$_HorasSemanal = $field_resumen['HorasDiaria'] * $_dias_semanas_habiles[$ns];
		$HT = $_HorasSemanal - $Horas;
		if ($Minutos > 0) $MT = 60 - $Minutos; else $MT = 0;
		if ($HT < 10) $HT = "0$HT"; else $HT = "$HT";
		if ($MT < 10) $MT = "0$MT"; else $MT = "$MT";
		$HorasFaltantes = "$HF:$MF";
		$HorasTrabajadas = "$HT:$MT";
		$DiasPago = 0;
		$DiasDescuento = 0;
		$DiasFeriados = 0;
		$DiasInactivos = 0;
		
		$TotalDiasSemana = getFechaDias($fechai[$ns], $fechaf[$ns]) + 1;
		for($i=$_DiffDias;$i<($_DiffDias+$TotalDiasSemana);$i++) {
			$sql = "SELECT Dia".$i." AS Dia
					FROM rh_bonoalimentaciondet
					WHERE
						Anio = '".$field['Anio']."' AND
						CodOrganismo = '".$field['CodOrganismo']."' AND
						CodBonoAlim = '".$field['CodBonoAlim']."' AND
						CodPersona = '".$field['CodPersona']."'";
			$query_total = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_total) != 0) $field_total = mysql_fetch_array($query_total);
			
			//if ($field_total['Dia'] == "X") ++$DiasPago;
			if (($field_total['Dia'] == "X") || ($field_total['Dia'] == "I") || ($field_total['Dia'] == "F")) ++$DiasPago;
			elseif ($field_total['Dia'] == "D") ++$DiasDescuento;
			
			if ($field_total['Dia'] == "F") ++$DiasFeriados;
			
			if ($field_total['Dia'] == "I") ++$DiasInactivos;
		}
		$_DiffDias += $TotalDiasSemana;
		$ValorDescuento = $field_resumen['ValorDia'] * $DiasDescuento;
		$TotalPagar = $field_resumen['ValorDia'] * $DiasPago;
		##
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Row(array($HorasFaltantes,
						$HorasTrabajadas,
						$DiasFeriados,
						$DiasInactivos,
						$DiasDescuento,
						$DiasPago,
						number_format($ValorDescuento, 2, ',', '.'),
						number_format($TotalPagar, 2, ',', '.')));
		##
		$TotalDiasFeriados += $DiasFeriados;
		$TotalDiasInactivos += $DiasInactivos;
		$TotalDiasDescuento += $DiasDescuento;
		$TotalDiasPago += $DiasPago;
		$TotalValorDescuento += $ValorDescuento;
		$TotalTotalPagar += $TotalPagar;
	}
	##
	$pdf->Ln(5);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array($TotalHorasFaltantes,
					$TotalHorasTrabajadas,
					$TotalDiasFeriados,
					$TotalDiasInactivos,
					$TotalDiasDescuento,
					$TotalDiasPago,
					number_format($TotalValorDescuento, 2, ',', '.'),
					number_format($TotalTotalPagar, 2, ',', '.')));
	$pdf->Ln(5);
	if ($sw % 2 == 0) $pdf->AddPage();
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
