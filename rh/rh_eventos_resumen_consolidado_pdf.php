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
$ValorSemanal = $field_resumen['ValorDia'] * $TotalDiasPago;
$DiffDias = getFechaDias(formatFechaDMA($field_resumen['FechaInicio']), $FechaInicio) + 1;
//	obtengo el nro de semanas
$swSemana = true;
$ns = 0;
$nro_semanas = 0;
$fi = formatFechaDMA($field_resumen['FechaInicio']);
while($swSemana) {
	++$ns;
	++$nro_semanas;
	$dsemana = getWeekDay($fi);
	$dias_semana = 7 - $dsemana + 1;
	$ff = obtenerFechaFin($fi, $dias_semana);
	if (formatFechaAMD($ff) >= $field_resumen['FechaFin']) { $ff = formatFechaDMA($field_resumen['FechaFin']); $swSemana = false; }
	$fechai[$ns] = $fi;
	$fechaf[$ns] = $ff;
	$fi = obtenerFechaFin($ff, 2);
}
//---------------------------------------------------
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
		global $HorasSemanal;
		global $TotalDiasPago;
		global $TotalFeriados;
		global $Inactivos;
		global $nro_semanas;
		global $_POST;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		//$ValorMensual = ($TotalDiasPago+$TotalFeriados+$Inactivos) * $field_resumen['ValorDia'];
		$ValorMensual = ($TotalDiasPago) * $field_resumen['ValorDia'];
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(260, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(260, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(5, 20); $this->Cell(286, 5, utf8_decode('Consolidado Mensual:'.$tituloNomina), 0, 1, 'C', 0);
		$this->SetFont('Arial', 'BI', 8);
		$this->SetXY(5, 24); $this->Cell(286, 5, utf8_decode('Periodo del '.formatFechaDMA($field_resumen['FechaInicio']).' al '.formatFechaDMA($field_resumen['FechaFin'])), 0, 1, 'C', 0);
		$this->Ln(5);
		##	imprimo resumen
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(200, 200, 200);
		$this->SetWidths(array(28, 28, 30, 30, 30, 30, 30));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(40, 5);
		/*$this->Row(array('HORAS DIARIAS',
						 'HORAS MENSUALES',
						 'DIAS HABILES',
						 'DIAS FERIADOS',
						 'DIAS INACTIVOS',
						 'VALOR X DIA',
						 'VALOR MENSUAL'));
		$this->SetFillColor(255, 255, 255);
		$this->Cell(40, 5);
		$this->Row(array($field_resumen['HorasDiaria'],
						 $HorasSemanal,
						 $TotalDiasPago,
						 $TotalFeriados,
						 $Inactivos,
						 number_format($field_resumen['ValorDia'], 2, ',', '.'),
						 number_format($ValorMensual, 2, ',', '.')));
		$this->Ln();
		*/
		//	
		$w1 = 110 - (7.5 * $nro_semanas);
		$w2 = 141 - (7.5 * $nro_semanas);
		$SetWidths[0] = 6; $SetWidths[1] = $w1; $SetWidths[2] = 18; $SetWidths[3] = $w2;
		$SetAligns[0] = "C";$SetAligns[1] = "L"; $SetAligns[2] = "R"; $SetAligns[3] = "L";
		$Row[0] = utf8_decode("N"); $Row[1] = "Empleado"; $Row[2] = "Documento"; $Row[3] = "Cargo";
		$j = 0;
		for($i=4;$i<$nro_semanas+4;$i++) {
			++$j;
			$SetWidths[$i] = 15;
			$SetAligns[$i] = "R";
			$Row[$i] = "Sem. ".$j;
		}
		$SetWidths[$i] = 15;
		$SetAligns[$i] = "R";
		$Row[$i] = "Total";
		##
		$this->SetDrawColor(0, 0, 0, 0);
		$this->SetFillColor(230,230, 230, 230);
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths($SetWidths);
		$this->SetAligns($SetAligns);
		$this->SetWidths(array(6,70,20,60,15,15,15,15,15,15));
		$this->SetX(20);$this->Row($Row);$this->SetX(20);
	}
	
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------

$pdf->SetTextColor(0, 0, 0);
	
//	--
$w1 = 110 - (7.5 * $nro_semanas);
$w2 = 120 - (7.5 * $nro_semanas);
$SetWidths[0] = 6; $SetWidths[1] = $w1; $SetWidths[2] = 18; $SetWidths[3] = $w2;
$SetAligns[0] = "C"; $SetAligns[1] = "L"; $SetAligns[2] = "R"; $SetAligns[3] = "L";
$j = 1;
for($i=4;$i<$nro_semanas+4;$i++) {
	++$j;
	$SetWidths[$i] = 15;
	$SetAligns[$i] = "R";
	$Row[$i] = "Sem. ".$j;
}
$SetWidths[$i] = 15;
$SetAligns[$i] = "R";
//	consulto
$sql = "SELECT
			bad.*,
			p.NomCompleto,
			p.Ndocumento,
			e.CodEmpleado,
			pt.DescripCargo
		FROM
			rh_bonoalimentaciondet bad
			INNER JOIN rh_bonoalimentacion ba ON (ba.Anio = bad.Anio AND
												  ba.CodOrganismo = bad.CodOrganismo AND
												  ba.CodBonoAlim = bad.CodBonoAlim)
			INNER JOIN mastpersonas p ON (p.CodPersona = bad.CodPersona)
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
			$inner_cargo
		WHERE
			bad.Anio = '".$field_resumen['Anio']."' AND
			bad.CodOrganismo = '".$field_resumen['CodOrganismo']."' AND
			bad.CodBonoAlim = '".$field_resumen['CodBonoAlim']."' $filtro
		GROUP BY CodPersona
		ORDER BY LENGTH(Ndocumento), Ndocumento";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));

$nu = 1;

while ($field = mysql_fetch_array($query)) {
	$Row[0] = $nu;
	$Row[1] = utf8_decode($field['NomCompleto']);
	$Row[2] = $field['Ndocumento'];
	$Row[3] = utf8_decode($field['DescripCargo']);
	
	
	
	##
	$_DiffDias = $DiffDias;
	$j = 3;
	$totalMesPago = 0;
	for($ns=1;$ns<=$nro_semanas;$ns++) {
		++$j;
		##
		$DiasPago = 0;
		$DiasDescuento = 0;
		$DiasFeriados = 0;
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
			//if (($field_total['Dia'] == "X") || ($field_total['Dia'] == "I") || ($field_total['Dia'] == "F"))
			
					if (($field_total['Dia'] == "X") || ($field_total['Dia'] == "I") || ($field_total['Dia'] == "F")) ++$DiasPago;
			elseif ($field_total['Dia'] == "D") ++$DiasDescuento;
			
			if ($field_total['Dia'] == "F") ++$DiasFeriados;


		}
	//	if ($field['CodPersona'] == '000025') echo "--".$DiasPago;
	//if ($field['CodPersona'] == '000025') echo "--".getDiasHabiles($fechai[$ns], $fechaf[$ns]);
		
		//echo $TotalDiasSemana." ";
	////	$fi = obtenerFechaFin($ff, 2);
		//echo "INICIO: ".$fi. " --- ".$ff;
		
		$_DiffDias += $TotalDiasSemana;
		$ValorDescuento = $field_resumen['ValorDia'] * $DiasDescuento;
		//$TotalPagar = $field_resumen['ValorDia'] * (getDiasHabiles($fechai[$ns], $fechaf[$ns]) - $DiasDescuento) ;
		
		$TotalPagar = $field_resumen['ValorDia'] * ($DiasPago) ;
		##
		
		//echo "  ".getDiasHabiles($fechai[$ns], $fechaf[$ns]);
	/*	echo " DESCUENTO: ".$DiasDescuento."  "."
		DIAS FERIDOS: ".$DiasFeriados. " DIAS SEMANA: ".($TotalDiasSemana)."
		 FINES DE SEMANA:". getDiasHabiles($fechai[$ns], $fechaf[$ns]) ."<br>"; 
	*/	 
		 
		
		//echo $DiasPago."-";
		$Row[$j] = number_format($TotalPagar, 2, ',', '.');
		$totalMesPago += $TotalPagar;
		
	}

	
	$Row[++$j] = number_format($totalMesPago, 2, ',', '.');
	$SumaTotal += $totalMesPago;
	##
	$pdf->SetWidths(array(6,70,20,60,15,15,15,15,15,15));
	
	$pdf->SetDrawColor(0,0, 0, 0);
	$pdf->SetFillColor(255,255, 255, 255);
	$pdf->SetFont('Arial', '', 9);
	##
		if ($pdf->GetY() > 150) { $pdf->addPage(); }
		
		$pdf->SetX(20);$pdf->Row($Row);$pdf->SetX(20);
	$nu++;
}

$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 8);

$ancho =171;
for($i=4;$i<$nro_semanas+4;$i++) $ancho = $ancho  + 15;

$pdf->SetWidths(array($ancho));
$pdf->SetAligns(array('R'));
$pdf->Row(array(number_format($SumaTotal, 2, ',', '.')));
		$pdf->SetX(20);$pdf->SetWidths(array(50,70,20,60,15,15,15,15,15,15));

//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
