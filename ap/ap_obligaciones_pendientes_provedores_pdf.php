<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
connect();
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		//	imprimo cabecera
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y SERVICIOS'), 0, 0, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(175, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R');
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(175, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(200, 5, utf8_decode('Obligaciones Pendientes'), 0, 0, 'C');
		$this->Ln(5);
		//	imprimo datos generales
		if ($fvencimientod != "") { $desde = "Desde: ".$fvencimientod;}
		if ($fvencimientoh != "") { $hasta = "Hasta: ".$fvencimientoh;}
		$this->SetFont('Arial', 'B', 6);
		$this->Cell(200, 5, 'Fecha de Vencimiento: '.$desde.' '.$hasta, 0, 0, 'L');
		$this->Ln(5);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(30, 15, 75, 20, 20, 20, 20));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'C', 'C', 'R'));
		$this->Row(array('Nro. Documento',
						 'Nro. Reg.',
						 'Detalle',
						 'Fecha Doc.',
						 utf8_decode('Fecha Recep.'),
						 'Fecha Prog.',
						 'Monto'));
	}
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 6);
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro .= " AND o.CodOrganismo = '".$forganismo."'";
if ($fproveedor != "") $filtro .= " AND o.CodProveedor = '".$fproveedor."'";
if ($fvencimientod != "" || $fvencimientoh != "") {
	if ($fvencimientod != "") $filtro .= " AND o.FechaVencimiento >= '".$fvencimientod."'";
	if ($fvencimientoh != "") $filtro .= " AND o.FechaVencimiento <= '".$fvencimientoh."'";
}
if ($fcodccosto != "") $filtro .= " AND o.CodCentroCosto = '".$ftdoc."'";
//---------------------------------------------------
$total_registros = 0;
$total_contabilizado = 0;
$total_proveedor = 0;
//	consulto la obligaciones
$sql = "SELECT
			o.*,
			p.NomCompleto AS NomProveedor,
			p.Ndocumento,
			p.Telefono1,
			p.Direccion
		FROM
			ap_obligaciones o
			INNER JOIN mastpersonas p ON (o.CodProveedor = p.CodPersona)
		WHERE
			o.Estado <> 'PA' AND
			o.Estado <> 'AN' $filtro
		ORDER BY CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$total_registros++;
	if ($field['FlagContabilizacionPendiente'] == "N") $total_contabilizado += $field['MontoObligacion'];
	$total_proveedor += $field['MontoObligacion'];
	//	si cambia de proveedor
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];		
		//	imprimo sub-total		
		if ($total_registros > 1) {
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Cell(180, 5, 'Total Contabilizado: ', 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($sub_total_contabilizado, 2, ',', '.'), 0, 0, 'R');
			$pdf->Ln(5);
			$pdf->Cell(180, 5, 'Total Proveedor: ', 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($sub_total_proveedor, 2, ',', '.'), 0, 0, 'R');
			$pdf->Ln(5);
		}
		//	imprimo proveedor
		$pdf->Ln(2);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 6);		
		$pdf->SetWidths(array(200));
		$pdf->SetAligns(array('L'));
		$pdf->Row(array($field['CodProveedor'].' '.utf8_decode($field['NomProveedor'])));
		$sub_total_registros = 0;
		$sub_total_contabilizado = 0;
		$sub_total_proveedor = 0;
	}
	$sub_total_registros++;
	if ($field['FlagContabilizacionPendiente'] == "N") $sub_total_contabilizado += $field['MontoObligacion'];
	$sub_total_proveedor += $field['MontoObligacion'];
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(30, 15, 75, 20, 20, 20, 20));
	$pdf->SetAligns(array('C', 'C', 'L', 'C', 'C', 'C', 'R'));
	$pdf->Row(array($field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					$field['NroRegistro'],
					utf8_decode($field['Comentarios']),
					formatFechaDMA($field['FechaDocumento']),
					formatFechaDMA($field['FechaRecepcion']),
					formatFechaDMA($field['FechaProgramada']),
					number_format($field['MontoObligacion'], 2, ',', '.')));
}
//	imprimo sub-total
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(180, 5, 'Total Contabilizado: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($sub_total_contabilizado, 2, ',', '.'), 0, 0, 'R');
$pdf->Ln(5);
$pdf->Cell(180, 5, 'Total Proveedor: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($sub_total_proveedor, 2, ',', '.'), 0, 0, 'R');
$pdf->Ln(10);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(190, $y, 20, 0.1, "FD");
$y = $pdf->GetY() + 10;
$pdf->Rect(190, $y, 20, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(180, 5, 'Total Contabilizado: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_contabilizado, 2, ',', '.'), 0, 0, 'R');
$pdf->Ln(5);
$pdf->Cell(180, 5, 'Total Proveedor: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_proveedor, 2, ',', '.'), 0, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>