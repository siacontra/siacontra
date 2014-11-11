<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);	
		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');
		
		$this->SetXY(230, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(230, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(270, 5, utf8_decode('Consumo por Dependencia'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(20, 100, 20, 20, 20, 90));
		$this->SetAligns(array('C', 'L', 'C', 'R', 'R', 'L'));
		$this->Row(array('Item',
						 utf8_decode('Descripción'),
						 utf8_decode('Transacción'),
						 'Cant.',
						 'Monto',
						 'Recibido Por'));
		$this->Ln(1);
						
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->Rect(5, 185, 270, 25, 'DF');
		$this->Rect(91.5, 185, 0.1, 25, 'DF');
		$this->Rect(178, 185, 0.1, 25, 'DF');
		$this->Rect(5, 190, 270, 0.1, 'DF');
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(5, 185);
		$this->Cell(76.5, 5, utf8_decode('Preparado Por'), 0, 1, 'L', 0);
		$this->SetXY(91.5, 185);
		$this->Cell(76.5, 5, utf8_decode('Revisado Por'), 0, 1, 'L', 0);
		$this->SetXY(178, 185);
		$this->Cell(76, 5, utf8_decode('Conformado Por'), 0, 1, 'L', 0);
		
		$this->SetXY(5, 190);
		$this->Cell(76.5, 5, utf8_decode('T.S.U. Mariana Salazar'), 0, 1, 'L', 0);
		$this->SetXY(91.5, 190);
		$this->Cell(76.5, 5, utf8_decode('Licda. Yosmar Greham'), 0, 1, 'L', 0);
		$this->SetXY(178, 190);
		$this->Cell(76, 5, utf8_decode('Licda. Rosis Requena'), 0, 1, 'L', 0);
		
		$this->SetXY(5, 195);
		$this->Cell(76.5, 5, utf8_decode('ASISTENTE DE PRESUPUESTO I'), 0, 1, 'C', 0);
		$this->SetXY(91.5, 195);
		$this->Cell(76.5, 5, utf8_decode('DIRECTORA ADMINISTRACION Y SERVICIOS'), 0, 1, 'C', 0);
		$this->SetXY(178, 195);
		$this->Cell(76, 5, utf8_decode('DIRECTORA GENERAL'), 0, 1, 'C', 0);
	}
}

function total_centro_costo($pdf, $cantidad, $total) {
	$y = $pdf->GetY() - 1;
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect(146, $y, 38, 0.1, 'DF');
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(140, 4, 'Total Centro de Costo: ', 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($cantidad, 2, ',', '.'), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($total, 2, ',', '.'), 0, 1, 'R');
	$pdf->Ln(2);
}

function total_dependencia($pdf, $cantidad, $total) {
	$y = $pdf->GetY() - 1;
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect(146, $y, 38, 0.1, 'DF');
	$pdf->Rect(146, $y+0.5, 38, 0.1, 'DF');
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(140, 4, 'Total Dependencia: ', 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($cantidad, 2, ',', '.'), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($total, 2, ',', '.'), 0, 1, 'R');
	$pdf->Ln(2);
}

function total_general($pdf, $cantidad, $total) {
	$y = $pdf->GetY() - 1;
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect(146, $y, 38, 0.1, 'DF');
	$pdf->Rect(146, $y+0.5, 38, 0.1, 'DF');
	$pdf->Rect(146, $y+1, 38, 0.1, 'DF');
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(140, 4, 'Total General: ', 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($cantidad, 2, ',', '.'), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($total, 2, ',', '.'), 0, 1, 'R');
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 35);
$pdf->AddPage();
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro.=" AND (t.CodOrganismo = '".$forganismo."')";
if ($fdependencia != "") $filtro.=" AND (t.CodDependencia = '".$fdependencia."')";
if ($fccosto != "") $filtro.=" AND (t.CodCentroCosto = '".$fccosto."')";
if ($fdesde != "") $filtro.=" AND (t.FechaDocumento >= '".formatFechaAMD($fdesde)."')";
if ($fhasta != "") $filtro.=" AND (t.FechaDocumento <= '".formatFechaAMD($fhasta)."')";
//---------------------------------------------------
$sql = "SELECT
			t.CodOrganismo,
			t.CodDocumento,
			t.NroDocumento,
			t.CodTransaccion,
			t.FechaDocumento,
			t.Periodo,
			t.CodCentroCosto,
			t.CodDependencia,
			td.CodItem,
			td.CodUnidad,
			td.CantidadRecibida,
			td.Total,
			rd.Descripcion,
			d.Dependencia,
			cc.Descripcion AS NomCentroCosto,
			cc.Abreviatura,
			mp.NomCompleto AS NomRecibidoPor
		FROM
			lg_transaccion t
			INNER JOIN lg_transacciondetalle td ON (t.CodOrganismo = td.CodOrganismo AND
													  t.CodDocumento = td.CodDocumento AND
													  t.NroDocumento = td.NroDocumento)
			INNER JOIN lg_requerimientosdet rd ON (td.CodOrganismo = rd.CodOrganismo AND
												   td.ReferenciaNroDocumento = rd.CodRequerimiento AND
												   td.ReferenciaSecuencia = rd.Secuencia)
			INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion AND tt.TipoMovimiento = 'E')
			INNER JOIN mastdependencias d ON (t.CodDependencia = d.CodDependencia)
			INNER JOIN ac_mastcentrocosto cc ON (t.CodCentroCosto = cc.CodCentroCosto)
			INNER JOIN mastpersonas mp ON (t.RecibidoPor = mp.CodPersona)
		WHERE 1 $filtro
		ORDER BY CodOrganismo, CodDependencia, CodCentroCosto";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	$i++;
	$pdf->SetTextColor(0, 0, 0);
	
	if ($grupo != $field['CodDependencia']) {
		if ($i>1) total_dependencia($pdf, $subcantidad_d, $subtotal_d);
		
		$grupo = $field['CodDependencia'];
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'BU', 6);
		$pdf->Cell(20, 4, 'Dependencia: ', 0, 0, 'L');
		$pdf->Cell(240, 4, utf8_decode($field['Dependencia']), 0, 1, 'L');
		
		$subcantidad_d = 0;
		$subtotal_d = 0;
	}
	
	if ($grupo2 != $field['CodCentroCosto']) {
		if ($i>1) total_centro_costo($pdf, $subcantidad_cc, $subtotal_cc);
		
		$grupo2 = $field['CodCentroCosto'];
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 4, 'Centro de Costo: ', 0, 0, 'L');
		$pdf->Cell(240, 4, utf8_decode($field['Abreviatura'].' '.$field['NomCentroCosto']), 0, 1, 'L');
		
		$subcantidad_cc = 0;
		$subtotal_cc = 0;
	}
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['CodItem'],
					utf8_decode($field['Descripcion']),
					$field['CodDocumento'].'-'.$field['NroDocumento'],
					number_format($field['CantidadRecibida'], 2, ',', '.'),
					number_format($field['Total'], 2, ',', '.'),
					$field['NomRecibidoPor']));
	$pdf->Ln(1);
	
	$subcantidad_cc += $field['CantidadRecibida'];
	$subtotal_cc += $field['Total'];
	$subcantidad_d += $field['CantidadRecibida'];
	$subtotal_d += $field['Total'];
	$cantidad += $field['CantidadRecibida'];
	$total += $field['Total'];
}
total_centro_costo($pdf, $subcantidad_cc, $subtotal_cc);
total_dependencia($pdf, $subcantidad_d, $subtotal_d);
total_general($pdf, $cantidad, $total);
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
