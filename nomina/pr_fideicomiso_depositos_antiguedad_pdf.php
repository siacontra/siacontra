<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro.=" AND (tnec.CodOrganismo = '".$forganismo."')";
if ($fnomina != "") $filtro.=" AND (tnec.CodTipoNom = '".$fnomina."')";
$filtro.=" AND (tnec.Periodo = '".$fperiodo."')";
if ($inactivos != "S") $filtro.=" AND me.Estado = 'A'";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $_GET;
		global $field;
		extract($_GET);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $forganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $forganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(235, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(235, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('ACUMULADO DE ANTIGUEDAD'), 0, 1, 'C', 0);
		$this->SetFont('Arial', 'BI', 10);
		$this->SetXY(10, 25); $this->Cell(260, 5, utf8_decode('Periodo '.$fperiodo), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(18, 85, 20, 15, 13, 13, 13, 13, 13, 10, 17, 17, 17));
		$this->SetAligns(array('R', 'L', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'C', 'R', 'R', 'R'));
		$this->Row(array('Documento',
						  'Nombre Completo',
						  'F.Ingreso',
						  'Sueldo Mensual',
						  'Bonos',
						  'Diario',
						  'Ali. Vac.',
						  'Ali. Fin.',
						  'Sueldo + Alic.',
						  'Dias',
						  'Prest. Antig.',
						  'Prest. Compl.',
						  'Total'));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT
			me.CodPersona,
			me.CodEmpleado,
			me.Fingreso,
			mp.NomCompleto,
			bp.Ncuenta,
			mp.Ndocumento,
			tnec.Monto,
			tnec.Cantidad,
			tnec.CodOrganismo,
			tnec.CodTipoNom,
			tnec.CodTipoProceso,
			tnec.Periodo,
			me.Fingreso,
			(SELECT COUNT(*)
			 FROM pr_acumuladofideicomisodetalle afd
			 WHERE
				afd.CodOrganismo = '".$forganismo."' AND
				afd.Periodo = '".$fperiodo."'  AND
				afd.CodPersona = tnec.CodPersona) AS PeriodoGenerado,
			pp.FechaDesde,
			pp.FechaHasta
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto tnec ON (mp.CodPersona = tnec.CodPersona)
			LEFT JOIN bancopersona bp ON (mp.CodPersona = bp.CodPersona AND bp.Aportes = 'FI')
			LEFT JOIN pr_acumuladofideicomisodetalle afd ON (tnec.CodPersona = afd.CodPersona AND tnec.Periodo = afd.Periodo)
			INNER JOIN pr_procesoperiodo pp ON (pp.CodOrganismo = tnec.CodOrganismo AND
												pp.CodTipoNom = tnec.CodTipoNom AND
												pp.Periodo = tnec.Periodo AND
												pp.CodTipoProceso = tnec.CodTipoProceso)
		WHERE
			(tnec.CodTipoProceso = 'FIN' OR tnec.CodTipoProceso = 'PPA') AND
			tnec.CodConcepto = '".$_PARAMETRO["PROVISION"]."'
			$filtro
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	//	obtengo dias adicionales
	$cantidad = getDiasAdicionalesTrimestral($field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']);
	//	si tiene dias adicionales calculo el complemento por los dias adicionales
	if ($cantidad > 0) $complemento = calculo_antiguedad_complemento_trimestral($field['CodPersona'], $field['Fingreso'], $field['FechaDesde'], $field['FechaHasta']); else $complemento = 0;
	//	sueldo mensual
	$sql = "SELECT TotalIngresos, (TotalIngresos / 30) AS SueldoDiario
			 FROM pr_tiponominaempleado
			 WHERE
				CodPersona = '".$field['CodPersona']."' AND
				CodOrganismo = '".$field['CodOrganismo']."' AND
				CodTipoProceso = 'FIN' AND
				CodTipoNom = '".$field['CodTipoNom']."' AND
				Periodo < '".$field['Periodo']."'
			 ORDER BY Periodo DESC
			 LIMIT 0, 1";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
	//	sueldo mensual
	$sql = "SELECT Monto
			 FROM pr_tiponominaempleadoconcepto
			 WHERE
				CodPersona = '".$field['CodPersona']."' AND
				CodOrganismo = '".$field['CodOrganismo']."' AND
				CodTipoProceso = 'FIN' AND
				CodTipoNom = '".$field['CodTipoNom']."' AND
				Periodo < '".$field['Periodo']."' AND
				CodConcepto = '0046'
			 ORDER BY Periodo DESC
			 LIMIT 0, 1";
	$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_alivac) != 0) $field_alivac = mysql_fetch_array($query_alivac);
	//	sueldo mensual
	$sql = "SELECT Monto
			 FROM pr_tiponominaempleadoconcepto
			 WHERE
				CodPersona = '".$field['CodPersona']."' AND
				CodOrganismo = '".$field['CodOrganismo']."' AND
				CodTipoProceso = 'FIN' AND
				CodTipoNom = '".$field['CodTipoNom']."' AND
				Periodo < '".$field['Periodo']."' AND
				CodConcepto = '0047'
			 ORDER BY Periodo DESC
			 LIMIT 0, 1";
	$query_fin = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_fin) != 0) $field_fin = mysql_fetch_array($query_fin);
	//	bonos
	$sql = "SELECT SUM(Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (c.CodConcepto = tnec.CodConcepto)
			WHERE
				tnec.Periodo = '".$field['Periodo']."' AND
				tnec.CodPersona = '".$field['CodPersona']."' AND
				c.FlagBonoRemuneracion = 'S'";
	$query_bono = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_bono) != 0) $field_bono = mysql_fetch_array($query_bono);
	##
	$SueldoDiario = round($field_sueldo['SueldoDiario'] + ($field_bono['Monto'] / 30), 2);
	$SueldoAlicuotas = $SueldoDiario + $field_alivac['Monto'] + $field_fin['Monto'];
	$Total = $field['Monto'] + $complemento;
	$SumaMonto += $field['Monto'];
	$SumaComplemento += $complemento;
	$SumaTotal += $Total;
	$BonoDiario = $field_bono['Monto'] / 30;
	$BonoAlicuotas = $BonoDiario + $field_alivac['Monto'] + $field_fin['Monto'];
	##
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	if (++$i % 2 == 0) $pdf->SetFillColor(245, 245, 245); else $pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'),
					 utf8_decode($field['NomCompleto']),
					 formatFechaDMA($field['Fingreso']),
					 number_format($field_sueldo['TotalIngresos'], 2, ',', '.'),
					 number_format($field_bono['Monto'], 2, ',', '.'),
					 number_format($SueldoDiario, 2, ',', '.'),
					 number_format($field_alivac['Monto'], 2, ',', '.'),
					 number_format($field_fin['Monto'], 2, ',', '.'),
					 number_format($SueldoAlicuotas, 2, ',', '.'),
					 number_format($field['Cantidad'], 2, ',', '.'),
					 number_format($field['Monto'], 2, ',', '.'),
					 number_format($complemento, 2, ',', '.'),
					 number_format($Total, 2, ',', '.')));
}
$pdf->SetFillColor(235, 235, 235);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('',
				 '',
				 '',
				 '',
				 '',
				 '',
				 '',
				 '',
				 'Total',
				 '',
				 number_format($SumaMonto, 2, ',', '.'),
				 number_format($SumaComplemento, 2, ',', '.'),
				 number_format($SumaTotal, 2, ',', '.')));
##
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, 193); $pdf->MultiCell(65, 4, 'PREPARADO POR', 0, 'C');
$pdf->SetXY(105, 193); $pdf->MultiCell(65, 4, 'REVISADO POR', 0, 'C');
$pdf->SetXY(210, 193); $pdf->MultiCell(65, 4, 'CONFORMADO POR', 0, 'C');
$pdf->SetXY(10, 197); $pdf->MultiCell(65, 4, 'LICDA. ANDREINA ZAPATA', 0, 'C');
$pdf->SetXY(105, 197); $pdf->MultiCell(65, 4, 'LICDA. CARMEN ALFONZO', 0, 'C');
$pdf->SetXY(210, 197); $pdf->MultiCell(65, 4, 'ABOG. MARLYN ABREU', 0, 'C');
$pdf->SetXY(10, 201); $pdf->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS I', 0, 'C');
$pdf->SetXY(105, 201); $pdf->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS II', 0, 'C');
$pdf->SetXY(210, 201); $pdf->MultiCell(65, 4, 'DIRECTORA DE RECURSOS HUMANOS', 0, 'C');
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
