<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
extract($_POST);
extract($_GET);
//---------------------------------------------------
list($NroProceso, $Secuencia, $CodTipoPago) = split("[.]", $registro);
//---------------------------------------------------
//	consulto
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
			p.GeneradoPor,
			p.ConformadoPor,
			p.AprobadoPor,
			mp.NomCompleto AS NomProveedor,
			op.Concepto,
			op.CodCentroCosto,
			op.CodTipoDocumento,
			op.NroDocumento,
			o.CodCuenta AS CodCuentaPago,
			o.Comentarios,
			td.CodVoucher,
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
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------
//	obtengo las firmas
//list($_GENERADO['Nombre'], $_GENERADO['Cargo']) = getFirma($field['GeneradoPor']);
list($_GENERADO['Nombre'], $_GENERADO['Cargo']) = getFirma( $GLOBALS['CODPERSONA_ACTUAL']);
list($_REVISADO['Nombre'], $_REVISADO['Cargo']) = getFirma($_PARAMETRO['FIRMAOP3']);
list($_CONFORMADO['Nombre'], $_CONFORMADO['Cargo']) = getFirma($field['ConformadoPor']);
list($_APROBADO['Nombre'], $_APROBADO['Cargo']) = getFirma($field['AprobadoPor']);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field;
		
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y SERVICIOS'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(165, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R');
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(10);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Beneficiario: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(175, 5, utf8_decode($field['NomProveedorPagar']), 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Nro. Cuenta: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(105, 5, $field['NroCuenta'], 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Nro. Pago: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, $field['NroPago'], 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Voucher: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(105, 5, $field['VoucherPago'], 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, formatFechaDMA($field['FechaPago']), 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Banco: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(105, 5, utf8_decode($field['Banco']), 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Monto: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(50, 5, number_format($field['MontoPago'], 2, ',', '.'), 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(20, 5, utf8_decode('Glosa: '), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->MultiCell(175, 5, utf8_decode($field['Comentarios']), 0, 'L');
		$this->Ln(3);
	}
	
	//	Pie de página.
	function Footer() {
		global $_GENERADO;
		global $_CONFORMADO;
		global $_REVISADO;
		global $_APROBADO;
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetXY(10, 237);
		$this->Rect(10, 237, 48.75, 35, "D"); 
		$this->Rect(58.75, 237, 48.75, 35, "D");
		$this->Rect(107.5, 237, 48.75, 35, "D");
		$this->Rect(156.25, 237, 48.75, 35, "D");
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(48.75, 5, 'EMITIDO POR', 1, 0, 'L');
		$this->Cell(48.75, 5, 'REVISADO POR', 1, 0, 'L');
		$this->Cell(48.75, 5, 'CONFORMADO POR', 1, 0, 'L');
		$this->Cell(48.75, 5, 'APROBADO POR', 1, 0, 'L');
		##
		$this->SetFont('Arial', 'B', 7);
		$this->SetXY(10, 233); $this->MultiCell(48.75, 3, utf8_decode($_GENERADO['Nombre']), 0, 'L');
		$this->SetXY(58.75, 233); $this->MultiCell(48.75, 3, utf8_decode($_REVISADO['Nombre']), 0, 'L');
		$this->SetXY(107.5, 233); $this->MultiCell(48.75, 3, utf8_decode( $_CONFORMADO['Nombre']), 0, 'L');
		$this->SetXY(156.25, 233); $this->MultiCell(48.75, 3, utf8_decode($_APROBADO['Nombre']), 0, 'L');
		##
		$this->SetXY(10, 240); $this->MultiCell(48.75, 3, utf8_decode($_GENERADO['Cargo']), 0, 'C');
		$this->SetXY(58.75, 240); $this->MultiCell(48.75, 3, utf8_decode($_REVISADO['Cargo']), 0, 'C');
		$this->SetXY(107.5, 240); $this->MultiCell(48.75, 3, utf8_decode($_CONFORMADO['Cargo']), 0, 'C');
		$this->SetXY(156.25, 240); $this->MultiCell(48.75, 3, utf8_decode($_APROBADO['Cargo']), 0, 'C');
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
