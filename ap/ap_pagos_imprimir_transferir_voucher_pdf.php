<?php
require('fpdf.php');
require('fphp_ap.php');
connect();
//---------------------------------------------------
extract($_POST);
extract($_GET);
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
$_CONFORMESE = getParametro("FIRMAOP1");
$_PAGUESE = getParametro("FIRMAOP2");
$_REVISADO = getParametro("FIRMAOP3");
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
		WHERE mp.CodPersona = '".$_CONFORMESE."'";
$query_conforme = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_conforme) != 0) $field_conforme = mysql_fetch_array($query_conforme);
//---------------------------------------------------
//	obtengo la firma de paguese
$sql = "SELECT
			mp.Busqueda AS NomPaguese,
			mp.Sexo,
			p.DescripCargo AS CargoPaguese,
			p2.DescripCargo AS CargoPagueseEncargado
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos p ON (me.CodCargo = p.CodCargo)
			LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
		WHERE mp.CodPersona = '".$_PAGUESE."'";
$query_paguese = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_paguese) != 0) $field_paguese = mysql_fetch_array($query_paguese);
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
		WHERE mp.CodPersona = '".$_REVISADO."'";
$query_revisado = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_revisado) != 0) $field_revisado = mysql_fetch_array($query_revisado);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;
		global $field;
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 10, 5, 10, 10);		
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
		global $field_conforme;
		global $field_revisado;
		global $field_paguese;
		global $field_orden;
		##
		if ($field_conforme['CargoConformeEncargado'] != "") { 
			$cargo_conforme = $field_conforme['CargoConformeEncargado'];
			$cargo_conforme = str_replace("(A)", "(E)", $cargo_conforme);
		}
		else {
			$cargo_conforme = $field_conforme['CargoConforme'];
			$cargo_conforme = str_replace("(A)", "", $cargo_conforme); 
		}
		if ($field_conforme['Sexo'] == "F") {
			$cargo_conforme = str_replace("JEFE", "JEFA", $cargo_conforme);
			$cargo_conforme = str_replace("DIRECTOR", "DIRECTORA", $cargo_conforme);
		}
		##
		if ($field_revisado['CargoRevisadoEncargado'] != "") { 
			$cargo_revisado = $field_revisado['CargoRevisadoEncargado'];
			$cargo_revisado = str_replace("(A)", "(E)", $cargo_revisado);
		}
		else {
			$cargo_revisado = $field_revisado['CargoRevisado'];
			$cargo_revisado = str_replace("(A)", "", $cargo_revisado); 
		}
		if ($field_revisado['Sexo'] == "F") {
			$cargo_revisado = str_replace("JEFE", "JEFA", $cargo_revisado);
			$cargo_revisado = str_replace("DIRECTOR", "DIRECTORA", $cargo_revisado);
		}
		##
		if ($field_paguese['CargoPagueseEncargado'] != "") { 
			$cargo_paguese = $field_paguese['CargoPagueseEncargado'];
			$cargo_paguese = str_replace("(A)", "(E)", $cargo_paguese);
		}
		else {
			$cargo_paguese = $field_paguese['CargoPaguese'];
			$cargo_paguese = str_replace("(A)", "", $cargo_paguese); 
		}
		if ($field_paguese['Sexo'] == "F") {
			$cargo_paguese = str_replace("JEFE", "JEFA", $cargo_paguese);
			$cargo_paguese = str_replace("DIRECTOR", "DIRECTORA", $cargo_paguese);
		}
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetXY(10, 237);
		$this->Rect(10, 237, 65, 35, "D"); 
		$this->Rect(75, 237, 65, 35, "D");
		$this->Rect(140, 237, 65, 35, "D");
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(65, 5, 'EMITIDO POR', 1, 0, 'L');
		$this->Cell(65, 5, 'CONFORMADO POR', 1, 0, 'L');
		$this->Cell(65, 5, 'REVISADO POR', 1, 0, 'L');
		##
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(10, 243); $this->MultiCell(65, 3, utf8_decode('LCDA. AMARILIS GONZALEZ'), 0, 'L');
		$this->SetXY(75, 243); $this->MultiCell(65, 3, utf8_decode($field_conforme['NomConforme']), 0, 'L');
		$this->SetXY(140, 243); $this->MultiCell(65, 3, utf8_decode($field_revisado['NomRevisado']), 0, 'L');
		##
		$this->SetXY(10, 250); $this->MultiCell(65, 3, utf8_decode('ANALISTA CONTABLE I'), 0, 'C');
		$this->SetXY(75, 250); $this->MultiCell(65, 3, utf8_decode($cargo_conforme), 0, 'C');
		$this->SetXY(140, 250); $this->MultiCell(65, 3, utf8_decode($cargo_revisado), 0, 'C');
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
