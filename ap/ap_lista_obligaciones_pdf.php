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
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN'), 0, 0, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(260, 5, utf8_decode('Lista de Obligaciones'), 0, 0, 'C');
		$this->Ln(5);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(30, 15, 15, 15, 10, 20, 20, 20, 20, 20, 75));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'C', 'L'));
		$this->Row(array('Nro. Documento',
						 'Nro. Reg.',
						 'Fecha Doc.',
						 'Fecha Pago',
						 'Est.',
						 'Monto Obligacion',
						 '(-Adelantos)',
						 '(-Pago Parcial)',
						 'Total',
						 'Voucher',
						 'Concepto'));
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
if ($ftdoc != "") $filtro .= " AND o.CodTipoDocumento = '".$ftdoc."'";
if ($fcodingresado != "") $filtro .= " AND o.CodProveedor = '".$fcodingresado."'";
if ($fdocumentod != "" || $fdocumentoh != "") {
	if ($fdocumentod != "") $filtro .= " AND o.FechaDocumento >= '".formatFechaAMD($fdocumentod)."'";
	if ($fdocumentoh != "") $filtro .= " AND o.FechaDocumento <= '".formatFechaAMD($fdocumentoh)."'";
}
if ($fedoreg != "") $filtro .= " AND o.Estado = '".$fedoreg."'";
if ($fpagod != "" || $fpagoh != "") {
	if ($fpagod != "") $filtro .= " AND o.FechaPago >= '".formatFechaAMD($fpagod)."'";
	if ($fpagoh != "") $filtro .= " AND o.FechaPago <= '".formatFechaAMD($fpagoh)."'";
}
if ($fperiodo != "") $filtro .= " AND o.Periodo = '".$fperiodo."'";
if ($faprobaciond != "" || $faprobacionh != "") {
	if ($faprobaciond != "") $filtro .= " AND o.FechaAprobado >= '".formatFechaAMD($faprobaciond)."'";
	if ($faprobacionh != "") $filtro .= " AND o.FechaAprobado <= '".formatFechaAMD($faprobacionh)."'";
}
//---------------------------------------------------
$total_registros = 0;
$sub_total_registros = 0;
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
		WHERE 1 $filtro
		ORDER BY CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	list($anio, $mes) = split("[-]", $field['Periodo']);
	list($codvoucher, $nrovocuher) = split("[-]", $field['Voucher']);
	$voucher = "$anio$mes-$codvoucher$nrovocuher";
	$total_registros++;
	$monto_total = $field['MontoObligacion'] - $field['MontoAdelanto'] - $field['MontoPagoParcial'];
	$total += $monto_total;
	//	si cambia de proveedor
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];		
		//	imprimo sub-total		
		if ($flagvertotales == "S" && $total_registros > 1) {
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Cell(70, 5, 'Nro. Registros: '.$sub_total_registros, 0, 0, 'L');
			$pdf->Cell(75, 5, 'Sub-Total: ', 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($sub_total, 2, ',', '.'), 0, 0, 'R');
			$pdf->Ln(5);
		}
		//	imprimo proveedor
		$pdf->Ln(2);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array(80, 30, 30, 120));
		$pdf->SetAligns(array('L', 'L', 'L', 'L'));
		$pdf->Row(array(utf8_decode($field['NomProveedor']),
						'Documento '.$field['Ndocumento'],
						'Telf. '.$field['Telefono1'],
						utf8_decode('Dirección ').utf8_decode($field['Direccion'])));
		$sub_total_registros = 0;
		$sub_total = 0;
	}
	$sub_total_registros++;
	$sub_total += $monto_total;
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(30, 15, 15, 15, 10, 20, 20, 20, 20, 20, 75));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'C', 'L'));
	$pdf->Row(array($field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					$field['NroRegistro'],
					formatFechaDMA($field['FechaDocumento']),
					formatFechaDMA($field['FechaPago']),
					$field['Estado'],
					number_format($field['MontoObligacion'], 2, ',', '.'),
					number_format($field['MontoAdelanto'], 2, ',', '.'),
					number_format($field['MontoPagoParcial'], 2, ',', '.'),
					number_format($monto_total, 2, ',', '.'),
					$voucher,
					utf8_decode($field['Comentarios'])));
}
//	imprimo sub-total
if ($flagvertotales == "S") {
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(70, 5, 'Nro. Registros: '.$sub_total_registros, 0, 0, 'L');
	$pdf->Cell(75, 5, 'Sub-Total: ', 0, 0, 'R');
	$pdf->Cell(20, 5, number_format($sub_total, 2, ',', '.'), 0, 0, 'R');
}
$pdf->Ln(10);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(155, $y, 20, 0.1, "FD");
$y = $pdf->GetY() + 5;
$pdf->Rect(155, $y, 20, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(70, 5, 'Nro. Registros: '.$total_registros, 0, 0, 'L');
$pdf->Cell(75, 5, 'Total: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 0, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
