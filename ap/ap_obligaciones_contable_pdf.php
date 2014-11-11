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
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R');
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(260, 5, utf8_decode('Obligaciones Vs. Distribución Contable'), 0, 0, 'C');
		$this->Ln(10);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(65, 20, 20, 20, 20, 20, 20, 20, 20, 15, 20));
		$this->SetAligns(array('L', 'L', 'R', 'R', 'R', 'C', 'C', 'C', 'C', 'C', 'R'));
		$this->Row(array(utf8_decode('Razón Social'),
						 'Documento',
						 'Importe',
						 'IVA',
						 'Impuestos',
						 'F. Documento',
						 utf8_decode('F. Recepción'),
						 'F. Pago',
						 'Cta. Contable',
						 'C.Costo',
						 'Monto'));
		$this->Ln(1);
	}
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
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
if ($fregistrod != "" || $fregistroh != "") {
	if ($fregistrod != "") $filtro .= " AND o.FechaRegistro >= '".$fregistrod."'";
	if ($fregistroh != "") $filtro .= " AND o.FechaRegistro <= '".$fregistroh."'";
}
if ($fcodccosto != "") $filtro .= " AND o.CodCentroCosto = '".$fcodccosto."'";
if ($fpagod != "" || $fpagoh != "") {
	if ($fpagod != "") $filtro .= " AND o.FechaPago >= '".$fpagod."'";
	if ($fpagoh != "") $filtro .= " AND o.FechaPago <= '".$fpagoh."'";
}
if ($fcodcuenta != "") $filtro .= " AND o.CodCuenta = '".$fcodcuenta."'";
//---------------------------------------------------
$total_registros = 0;
//	consulto la obligaciones
$sql = "SELECT
			o.CodTipoDocumento,
			o.NroDocumento,
			o.MontoObligacion,
			o.MontoImpuestoOtros,
			o.MontoImpuesto,
			o.FechaRecepcion,
			o.FechaRegistro,
			o.FechaDocumento,
			oc.CodCuenta,
			oc.CodCentroCosto,
			oc.Monto,
			p.NomCompleto AS NomProveedor,
			p.Ndocumento,
			p.Telefono1,
			p.Direccion
		FROM
			ap_obligaciones o
			INNER JOIN mastpersonas p ON (o.CodProveedor = p.CodPersona)
			INNER JOIN ap_obligacionescuenta oc ON (o.CodProveedor = oc.CodProveedor AND
													o.CodTipoDocumento = oc.CodTipoDocumento AND
													o.NroDocumento = oc.NroDocumento)
		WHERE 1 $filtro
		ORDER BY FechaRegistro";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$total_registros++;
	$total_importe += $field['MontoObligacion'];
	$total_iva += $field['MontoImpuesto'];
	$total_impuestos += $field['MontoImpuestoOtros'];
	$monto = $field['MontoObligacion'] + $field['MontoImpuesto'] - $field['MontoImpuestoOtros'];
	$total_monto += $monto;
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Row(array(utf8_decode($field['NomProveedor']),
					$field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					number_format($field['MontoObligacion'], 2, ',', '.'),
					number_format($field['MontoImpuesto'], 2, ',', '.'),
					number_format($field['MontoImpuestoOtros'], 2, ',', '.'),
					formatFechaDMA($field['FechaDocumento']),
					formatFechaDMA($field['FechaRecepcion']),
					formatFechaDMA($field['FechaRegistro']),
					$field['CodCuenta'],
					$field['CodCentroCosto'],
					number_format($monto, 2, ',', '.')));
	$pdf->Ln(2);
}
$pdf->Ln(5);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(75, $y, 195, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(85, 5, 'Importe Total: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_importe, 2, ',', '.'), 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_iva, 2, ',', '.'), 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_impuestos, 2, ',', '.'), 0, 0, 'R');
$pdf->Cell(95, 5, '', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_monto, 2, ',', '.'), 0, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>