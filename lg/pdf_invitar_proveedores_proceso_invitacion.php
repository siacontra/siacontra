<?php
session_start();
//---------------------------------------------------
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
//---------------------------------------------------
function Cabecera($pdf, $PATHLOGO, $field) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 5, 5, 10, 10);	
	
	//$pdf->SetFont('Arial', 'B', 9);
	//$pdf->SetXY(150, 10); $pdf->Cell(60, 5, 'MAIL DE CONTACTO', 0, 0, 'L');
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY(15, 5); $pdf->Cell(100, 5, $field['Organismo'], 0, 1, 'L');
	$pdf->SetXY(15, 10); $pdf->MultiCell(100, 5, $field['OrgDireccion'], 0, 'L');
	$pdf->SetXY(15, 15); $pdf->Cell(40, 5, 'Telf. '.$field['OrgTel'], 0, 0, 'L');
	$pdf->SetXY(60, 15); $pdf->Cell(40, 5, 'Fax: '.$field['OrgFax'], 0, 1, 'L');
	$pdf->SetXY(15, 20); $pdf->Cell(60, 5, 'R.I.F. '.$field['OrgRif'], 0, 1, 'L');
	
	$pdf->SetXY(175, 15); $pdf->Cell(15, 5, utf8_decode('# Cotización: '), 0, 0, 'R'); $pdf->Cell(60, 5, $field['CotizacionNumero'], 0, 1, 'L');
	$pdf->SetXY(175, 20); $pdf->Cell(15, 5, 'Fecha: ', 0, 0, 'R'); $pdf->Cell(60, 5, formatFechaDMA($field['FechaInvitacion']), 0, 1, 'L');
	
	$pdf->Ln(10);
	$pdf->SetXY(10, 30);
	$pdf->SetFillColor(250, 250, 250);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(35, 5, 'Proveedor: ', 0, 0, 'L', 1);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(150, 6, $field['NomProveedor'], 0, 1, 'L');
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(35, 5, 'R.I.F: ', 0, 0, 'L', 1);
	$pdf->Cell(50, 6, $field['ProRif'], 0, 1, 'L');
	$pdf->Cell(35, 5, 'Domicilio: ', 0, 0, 'L', 1);
	$pdf->MultiCell(150, 6, $field['ProDireccion'], 0, 'L');
	$pdf->Cell(35, 5, 'Representante Legal: ', 0, 0, 'L', 1);
	$pdf->Cell(100, 6, $field['RepresentanteLegal'], 0, 1, 'L');
	$pdf->Cell(35, 5, 'Telefono Contacto: ', 0, 0, 'L', 1);
	$pdf->Cell(35, 6, $field['ProTel'], 0, 0, 'L');
	$pdf->Cell(15, 5, 'Fax: ', 0, 0, 'L', 1);
	$pdf->Cell(50, 6, $field['ProFax'], 0, 1, 'L');
	$pdf->Ln(5);
		
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array(10, 95, 15, 15, 30, 30));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('ITEM', 'DESCRIPCION', 'UNIDAD', 'CANT.', 'PRECIO UNITARIO', 'TOTAL'));
	$pdf->Ln(3);
}
//---------------------------------------------------

//---------------------------------------------------
//	Imprime el pie del documento
//---------------------------------------------------
function Pie($pdf, $PATHLOGO, $field, $condiciones, $observaciones) {
	$pdf->SetY(230);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
	
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(98, 5, utf8_decode('Condiciones de Entrega: '), 0, 0, 'L', 1);
	$pdf->Cell(4, 5);
	$pdf->Cell(98, 5, utf8_decode('Observaciones: '), 0, 1, 'L', 1);
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);	
	$pdf->SetXY(10, 238); $pdf->MultiCell(98, 5, utf8_decode($condiciones), 0, 'L');
	$pdf->SetXY(112, 238); $pdf->MultiCell(98, 5, utf8_decode($observaciones), 0, 'L');
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
//---------------------------------------------------
$pdf=new PDF_MC_Table('P','mm','Letter');
$pdf->Open();
$pdf->SetMargins(10, 10, 5);

//	Organismo
$PATHLOGO = getParametro("PATHLOGO");

//	Proveedores
$sql = "SELECT
			c.CodProveedor,
			c.NomProveedor,
			c.Condiciones,
			c.Observaciones,
			c.CotizacionNumero,
			c.FechaInvitacion,			
			mp.DocFiscal AS ProRif,
			mp.Direccion AS ProDireccion,
			mp.Telefono1 AS ProTel,
			mp.Fax AS ProFax,			
			p.RepresentanteLegal,			
			o.Organismo,
			o.DocFiscal AS OrgRif,
			o.Direccion AS OrgDireccion,
			o.Telefono1 AS OrgTel,
			o.Fax1 AS OrgFax,			
			rd.CodItem,
			rd.CommoditySub,
			rd.Descripcion,
			rd.CodUnidad,
			rd.CantidadPedida
		FROM
			lg_cotizacion c
			INNER JOIN mastpersonas mp ON (c.CodProveedor = mp.CodPersona)
			INNER JOIN mastproveedores p ON (p.CodProveedor = mp.CodPersona)
			INNER JOIN mastorganismos o ON (c.CodOrganismo = o.CodOrganismo)
			INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND c.CodOrganismo = rd.CodOrganismo AND c.Secuencia = rd.Secuencia)
		WHERE c.CotizacionNumero = '".$cotizacion_numero."'";
$query = mysql_query($sql) or die ($sql.mysql_error()); $i=1;
while ($field = mysql_fetch_array($query)) {
	$condiciones = $field['Condiciones'];
	$observaciones = $field['Observaciones'];
	
	//	Si cambio de proveedor imprimo un una nueva pagina
	if ($agrupa != $field['CodProveedor']) {
		$agrupa = $field['CodProveedor'];
		Pie($pdf, $PATHLOGO, $field, $condiciones, $observaciones);
		Cabecera($pdf, $PATHLOGO, $field);
		$i=1;
	}
	
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(10, 95, 15, 15, 30, 30));
	$pdf->SetAligns(array('R', 'L', 'C', 'R', 'C', 'C'));
	$pdf->Row(array($i, $field['Descripcion'], $field['CodUnidad'], number_format($field['CantidadPedida'], 2, ',', '.'), '________________', '________________'));
	
	$i++;
}

Pie($pdf, $PATHLOGO, $field, $condiciones, $observaciones);

//---------------------------------------------------
$pdf->Output();
?>  
