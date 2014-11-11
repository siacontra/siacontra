<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .= " AND (e.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (e.CodDependencia = '".$fCodDependencia."')";
if ($fCodPersona != "") $filtro .= " AND (vp.CodPersona = '".$fCodPersona."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
									  p.NomCompleto LIKE '%".$fBuscar."%')";
if ($fEstado != "") $filtro .= " AND (e.Estado = '".$fEstado."')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field;
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
		$this->SetFont('Arial', '', 7);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 7);
		$this->SetXY(180, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(180, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Resumen de Vacaciones'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetWidths(array(16, 70, 20, 20, 20, 20, 20, 20));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Row(array('Empleado',
						 'Nombre Completo',
						 'Fecha Ingreso',
						 'Derecho',
						 'Dias Solicitud',
						 utf8_decode('Interrupción'),
						 'Total Utilizado',
						 'Pendientes'));
		$this->Ln(1);
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
$pdf->SetFillColor(255, 255, 255);
//	consulto
$sql = "SELECT
			vp.CodPersona,
			vp.NroPeriodo,
			vp.CodTipoNom,
			SUM(vp.Derecho) AS Derecho,
			SUM(vp.DiasGozados) AS DiasGozados,
			SUM(vp.DiasInterrumpidos) AS DiasInterrumpidos,
			(SUM(vp.DiasGozados) - SUM(vp.DiasInterrumpidos)) AS TotalUtilizados,
			SUM(vp.Pendientes) AS Pendientes,
			e.CodEmpleado,
			e.Fingreso,
			p.NomCompleto
		FROM
			rh_vacacionperiodo vp
			INNER JOIN mastempleado e ON (e.CodPersona = vp.CodPersona AND 
										  e.CodTipoNom = vp.CodTipoNom AND
										  e.Estado = 'A')
			INNER JOIN mastpersonas p ON (p.CodPersona = e.CodPersona)
		WHERE 1 $filtro
		GROUP BY CodPersona
		ORDER BY CodEmpleado";	//die($sql);
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array($field['CodEmpleado'],
					utf8_decode($field['NomCompleto']),
					formatFechaDMA($field['Fingreso']),
					number_format($field['Derecho'], 0, ',', '.'),
					number_format($field['DiasGozados'], 0, ',', '.'),
					number_format($field['DiasInterrumpidos'], 0, ',', '.'),
					number_format($field['TotalUtilizados'], 0, ',', '.'),
					number_format($field['Pendientes'], 0, ',', '.')));
	##
	if ($FlagDetalle == "S") {
		$sql = "SELECT
					Anio,
					NroPeriodo,
					Derecho,
					DiasGozados,
					DiasInterrumpidos,
					(DiasGozados - DiasInterrumpidos) AS TotalUtilizados,
					Pendientes
				FROM rh_vacacionperiodo
				WHERE
					CodPersona = '".$field['CodPersona']."' AND
					CodTipoNom = '".$field['CodTipoNom']."'
				ORDER BY NroPeriodo";
		$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_detalle = mysql_fetch_array($query_detalle)) {
			$Periodo = "$field_detalle[Anio] - ".($field_detalle['Anio'] + 1);
			$pdf->SetFont('Arial', 'BI', 8);
			$pdf->Row(array('',
							'',
							$Periodo,
							number_format($field_detalle['Derecho'], 0, ',', '.'),
							number_format($field_detalle['DiasGozados'], 0, ',', '.'),
							number_format($field_detalle['DiasInterrumpidos'], 0, ',', '.'),
							number_format($field_detalle['TotalUtilizados'], 0, ',', '.'),
							number_format($field_detalle['Pendientes'], 0, ',', '.')));
		}
		$pdf->Ln(3);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
