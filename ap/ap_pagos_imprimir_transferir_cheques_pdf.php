<?php
require('fpdf.php');
require('fphp_ap.php');
connect();
//---------------------------------------------------
extract($_POST);
extract($_GET);
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
$sql = "SELECT
			p.CodOrganismo,
			p.CodProveedor,
			p.NroPago,
			p.CodTipoPago,
			p.MontoPago,
			p.NroOrden,
			p.NomProveedorPagar,
			p.ChequeCargo,
			p.NroCuenta,
			p.Periodo,
			p.VoucherPago,
			p.FechaPago,
			mp.NomCompleto AS NomProveedor,
			op.Concepto,
			op.CodCentroCosto,
			op.CodTipoDocumento,
			op.NroDocumento,
			op.FechaOrdenPago,
			o.CodCuenta AS CodCuentaPago,
			cb.CodCuenta AS CodCuentaBanco,
			td.CodVoucher,
			b.Banco,
			pc1.Descripcion AS NomCuentaPago,
			pc2.Descripcion AS NomCuentaBanco,
			b.Banco,
			(SELECT PrefVoucherPA FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS Voucher,
			(SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodSistemaFuente
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
			INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			LEFT JOIN ac_mastplancuenta pc1 ON (o.CodCuenta = pc1.CodCuenta)
			LEFT JOIN ac_mastplancuenta pc2 ON (cb.CodCuenta = pc2.CodCuenta)
		WHERE
			p.CodProveedor = '".$codproveedor."' AND
			p.NroProceso = '".$nroproceso."' AND
			p.CodTipoPago = '".$codtipopago."' AND
			p.NroCuenta = '".$nrocuenta."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------
//	obtengo la firma de conformidad
$sql = "SELECT
			mp.Busqueda AS NomConforme,
			mp.Sexo,
			p1.DescripCargo AS CargoConforme,
			p2.DescripCargo AS CargoConformeEncargado
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
			LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
		WHERE mp.CodPersona = '".$_PARAMETRO["FIRMAOP1"]."'";
$query_conforme = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_conforme) != 0) $field_conforme = mysql_fetch_array($query_conforme);
//---------------------------------------------------
//	obtengo la firma de revisado
$sql = "SELECT
			mp.Busqueda AS NomRevisado,
			mp.Sexo,
			p.DescripCargo AS CargoRevisado,
			p2.DescripCargo AS CargoRevisadoEncargado
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos p ON (me.CodCargo = p.CodCargo)
			LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
		WHERE mp.CodPersona = '".$_PARAMETRO["FIRMAOP3"]."'";
$query_revisado = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_revisado) != 0) $field_revisado = mysql_fetch_array($query_revisado);
//---------------------------------------------------

//---------------------------------------------------
function chequeVenezuela($pdf) {
	global $field;
	//	----------------
	$m = (int) date("m");
	//	----------------
	list($int, $dec) = split("[.]", $field['MontoPago']);
	$int_letras = strtoupper(convertir_a_letras($int, "entero"));
	$monto_letras = "$int_letras BOLIVARES CON $dec/100";
	//	----------------	
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 12);
	//	----------------
	$pdf->SetXY(138, 7); $pdf->Cell(35, 4, number_format($field['MontoPago'], 2, ',', '.').'*****', 0, 0, 'L');
	$pdf->SetXY(33, 24); $pdf->Cell(160, 4, utf8_decode($field['NomProveedorPagar']), 0, 0, 'L');		
	$pdf->SetXY(14, 30); $pdf->MultiCell(160, 4, '                       '.$monto_letras.' **********', 0, 'L');		
	$pdf->SetXY(14, 42); $pdf->Cell(160, 4, 'Tucupita, '.date("d").' de '.getNombreMes("$m").'                               '.date("Y"), 0, 0, 'L');
}
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {}
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetY(225);
		$this->Cell(64, 5, 'Preparado Por', 1, 0, 'L', 1);
		$this->Cell(66, 5, 'Revisado Por', 1, 0, 'L', 1);
		$this->Cell(64, 5, 'Aprobado Por', 1, 0, 'L', 1);
		$this->Ln(5);
		$this->Cell(64, 15, '', 1, 0, 'L', 1);
		$this->Cell(66, 15, '', 1, 0, 'L', 1);
		$this->Cell(64, 15, '', 1, 0, 'L', 1);
		$this->Ln(15);
		$this->Cell(64, 5, 'Entrega Contra Factura Original', 1, 0, 'L', 1);
		$this->Cell(33, 5, 'Recibido Por', 1, 0, 'L', 1);
		$this->Cell(33, 5, 'C.I. No.', 1, 0, 'L', 1);
		$this->Cell(32, 5, 'Firma', 1, 0, 'L', 1);
		$this->Cell(32, 5, 'Fecha', 1, 0, 'L', 1);
		$this->Ln(5);
		$this->Cell(64, 15, '________ SI ________ NO', 1, 0, 'C', 1);
		$this->Cell(33, 15, '', 1, 0, 'L', 1);
		$this->Cell(33, 15, '', 1, 0, 'L', 1);
		$this->Cell(32, 15, '', 1, 0, 'L', 1);
		$this->Cell(32, 15, '', 1, 0, 'L', 1);
	}
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
//	imprimo cheque
chequeVenezuela($pdf);

//	imprimo cuerpo
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetXY(10, 100);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Beneficiario: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(175, 5, utf8_decode($field['NomProveedorPagar']), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Nro. Cuenta: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(105, 5, $field['NroCuenta'], 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Nro. Pago: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 5, $field['NroPago'], 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Voucher: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(105, 5, $field['VoucherPago'], 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 5, formatFechaDMA($field['FechaPago']), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Banco: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(105, 5, $field['Banco'], 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Monto: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 5, number_format($field['MontoPago'], 2, ',', '.'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Descripción: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(175, 5, utf8_decode($field['Concepto']), 0, 'L');
$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

//	imprimo cuerpo
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(30, 15, 15, 85, 25, 25));
$pdf->SetAligns(array('L', 'C', 'C', 'L', 'R', 'R'));
$pdf->Row(array('Cuenta',
				'Persona',
				'C.C',
				utf8_decode('Descripción'),
				'Debe',
				'Haber'));
$pdf->Ln(1);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);

//	consulto voucher de pago
$sql = "SELECT
			vd.*,
			pc.Descripcion AS NomCuenta
		FROM
			ac_voucherdet vd
			INNER JOIN ac_mastplancuenta pc On (vd.CodCuenta = pc.CodCuenta)
		WHERE
			CodOrganismo = '".$field['CodOrganismo']."' AND
			Periodo = '".$field['Periodo']."' AND
			Voucher = '".$field['VoucherPago']."'
		ORDER BY CodCuenta";
$query_voucher = mysql_query($sql) or die ($sql.mysql_error());
while($field_voucher = mysql_fetch_array($query_voucher)) {
	if ($field_voucher['MontoVoucher'] > 0) { $debe = $field_voucher['MontoVoucher']; $haber = 0.00; $total_debe += $debe; }
	else { $debe = 0.00; $haber = $field_voucher['MontoVoucher']; $total_haber += $haber; }
	$pdf->Row(array($field_voucher['CodCuenta'],
					$field_voucher['CodPersona'],
					$field_voucher['CodCentroCosto'],
					utf8_decode($field_voucher['NomCuenta']),
					number_format($debe, 2, ',', '.'),
					number_format($haber, 2, ',', '.')));
}
//---------------------------------------------------
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 195, 0.1, "FD");
$pdf->SetY($y+2);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('',
				'',
				'',
				'',
				number_format($total_debe, 2, ',', '.'),
				number_format($total_haber, 2, ',', '.')));
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
