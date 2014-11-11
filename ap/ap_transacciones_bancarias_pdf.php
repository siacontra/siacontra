<?php
require('fpdf.php');
require('fphp_ap.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
//	obtengo la informacion general
list($nrotransaccion, $secuencia, $estado)=SPLIT( '[|]', $registro);
$sql = "SELECT 
			bt.*,
			p.NomCompleto AS NomPreparadoPor,
			btt.Descripcion AS NomTipoTransaccion
		FROM 
			ap_bancotransaccion bt
			INNER JOIN mastpersonas p ON (bt.PreparadoPor = p.CodPersona)
			INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
		WHERE bt.NroTransaccion = '".$nrotransaccion."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $_FIELD = mysql_fetch_array($query);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;
		global $_FIELD;
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 10, 5, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, utf8_decode($_SESSION['NOMBRE_ORGANISMO_ACTUAL']), 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y SERVICIOS'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(165, 5); $this->Cell(20, 5, ('Fecha: '), 0, 0, 'R');
		$this->Cell(30, 5, formatFechaDMA($_FIELD['FechaTransaccion']), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('SUSTENTO DE TRANSACCION'), 0, 1, 'C', 0);
		$this->Ln(10);
		
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(255, 255, 255); $this->SetFillColor(245, 245, 245);
		$this->SetFont('Arial', 'B', 8); $this->Cell(28, 5, utf8_decode('Nro. Transacción:'), 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 8); $this->Cell(45, 5, $_FIELD['NroTransaccion'], 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 8); $this->Cell(28, 5, utf8_decode('Preparado Por:'), 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 8); $this->Cell(94, 5, utf8_decode($_FIELD['NomPreparadoPor']).' '.formatFechaDMA($_FIELD['FechaPreparacion']), 0, 0, 'L', 0);
		$this->Ln(6);
		$this->SetFont('Arial', 'B', 8); $this->Cell(28, 5, ('Fecha:'), 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 8); $this->Cell(45, 5, formatFechaDMA($_FIELD['FechaTransaccion']), 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 8); $this->Cell(28, 5, ('Estado:'), 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 8); $this->Cell(94, 5, printValores('ESTADO-TRANSACCION-BANCARIA', $_FIELD['Estado']), 0, 0, 'L', 0);
		$this->Ln(6);
		$this->SetFont('Arial', 'B', 8); $this->Cell(28, 5, ('Comentarios:'), 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 8); $this->MultiCell(167, 5, utf8_decode($_FIELD['Comentarios']), 0, 'L', 0);
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0); $this->SetFillColor(255, 255, 255);
		$this->SetWidths(array(85, 45, 40, 25));
		$this->SetAligns(array('L', 'C', 'L', 'R'));
		$this->Row(array(utf8_decode('Tipo de Transacción'),
						 ('Cuenta Bancaria'),
						 ('Doc. Referencia'),
						 ('Monto')));
		$this->Ln(2);
	}
	//	Pie de página.
	function Footer() {
		$this->SetTextColor(0, 0, 0); $this->SetDrawColor(0, 0, 0); $this->SetFillColor(0, 0, 0);
		$this->Rect(150, 250, 55, 0.1, 'DF');
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(150, 250); $this->Cell(55, 5, ('Aprobado'), 0, 0, 'C', 0);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(1, 10);
$pdf->AddPage();
//---------------------------------------------------
//	imprimo los detalles
$sql = "SELECT 
			bt.*,
			p.NomCompleto AS NomPreparadoPor,
			btt.Descripcion AS NomTipoTransaccion
		FROM 
			ap_bancotransaccion bt
			INNER JOIN mastpersonas p ON (bt.PreparadoPor = p.CodPersona)
			INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
		WHERE bt.NroTransaccion = '".$nrotransaccion."'";
$query = mysql_query($sql) or die($sql.mysql_error());
while($field = mysql_fetch_array($query)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Row(array(utf8_decode($field['NomTipoTransaccion']),
					$field['NroCuenta'],
					$field['CodigoReferenciaBanco'],
					number_format($field['Monto'], 2, ',', '.')));
	$pdf->Ln(2);
}

//	imprimo los totales
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(170, 5, 'Totales:', 0, 0, 'R', 0);
if ($_FIELD['Monto'] > 0) $pdf->SetTextColor(0, 0, 0); else $pdf->SetTextColor(255, 0, 0);
$pdf->Cell(25, 5, number_format(abs($_FIELD['Monto']), 2, ',', '.'), 0, 0, 'R', 1);

//	partidas
$sql = "SELECT 
			bt.*,
			p.NomCompleto AS NomPreparadoPor,
			btt.Descripcion AS NomTipoTransaccion,
			pv.denominacion As NomPartida
		FROM 
			ap_bancotransaccion bt
			INNER JOIN mastpersonas p ON (bt.PreparadoPor = p.CodPersona)
			INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
			INNER JOIN pv_partida pv On (bt.CodPartida = pv.cod_partida)
		WHERE bt.NroTransaccion = '".$nrotransaccion."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) {
	$pdf->Ln(15);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(195));
	$pdf->SetAligns(array('C'));
	$pdf->Row(array(utf8_decode('Distribución Presupuestaria')));
	$pdf->Ln(2);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(30, 140, 25));
	$pdf->SetAligns(array('C', 'L', 'R'));
	$pdf->Row(array(utf8_decode('Partida'),
					utf8_decode('Denominación'),
					'Monto'));
	$pdf->Ln(2);
}
while($field = mysql_fetch_array($query)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Row(array(utf8_decode($field['CodPartida']),
					utf8_decode($field['NomPartida']),
					number_format($field['Monto'], 2, ',', '.')));
	$pdf->Ln(2);
}



//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
