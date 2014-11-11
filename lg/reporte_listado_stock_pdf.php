<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
//if ($forganismo != "") $filtro .= "AND (t.CodOrganismo = '".$forganismo."') ";
if ($falmacen != "") $filtro .= "AND (iai.CodAlmacen = '".$falmacen."') ";
if ($coditem != "") $filtro .= "AND (iai.CodItem = '".$fcoditem."') ";
if ($ftipoitem != "") $filtro .= "AND (i.CodTipoItem = '".$ftipoitem."') ";
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
		$this->SetXY(180, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(180, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Listado de Stock'), 0, 1, 'C', 0);
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(20, 15, 100, 10, 20, 20, 20));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'R', 'R', 'R'));
		$this->Row(array('Item',
						 'Cod. Interno',
						 'Descripcion',
						 'Und.',
						 'Stock Actual',
						 'Comprometido',
						 'Stock Disponible'));
		$this->Ln(1);
						
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->Rect(5, 245, 205, 25, 'DF');
		$this->Rect(73.5, 245, 0.1, 25, 'DF');
		$this->Rect(142, 245, 0.1, 25, 'DF');
		$this->Rect(5, 250, 205, 0.1, 'DF');
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(5, 245);
		$this->Cell(68.5, 5, utf8_decode('Preparado Por'), 0, 1, 'L', 0);
		$this->SetXY(73.5, 245);
		$this->Cell(68.5, 5, utf8_decode('Revisado Por'), 0, 1, 'L', 0);
		$this->SetXY(142, 245);
		$this->Cell(68, 5, utf8_decode('Conformado Por'), 0, 1, 'L', 0);
		
		$this->SetXY(5, 250);
		$this->Cell(68.5, 5, utf8_decode($_SESSION["NOMBRE_USUARIO_ACTUAL"]), 0, 1, 'L', 0);
		$this->SetXY(73.5, 250);
		$this->Cell(68.5, 5, utf8_decode('Rena Salas'), 0, 1, 'L', 0);
		$this->SetXY(142, 250);
		$this->Cell(68, 5, utf8_decode('Roxaida Estrada'), 0, 1, 'L', 0);
		
		$this->SetXY(5, 255);
		$this->Cell(68.5, 5, utf8_decode($this->cargoPreparadoPor), 0, 1, 'C', 0);
		$this->SetXY(73.5, 255);
		$this->Cell(68.5, 5, utf8_decode('ANALISTA JEFE DE ADMINISTRACIÓN'), 0, 1, 'C', 0);
		$this->SetXY(142, 255);
		$this->Cell(68, 5, utf8_decode('DIRECTOR DE  ADMINISTRACION Y PRESUPUESTO (E)'), 0, 1, 'C', 0);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 35);
$pdf->AddPage();
//---------------------------------------------------
//	obtengo los movimientos		
$sql = "SELECT
			iai.*,
			i.Descripcion,
			i.CodUnidad,
			i.CodInterno
		FROM
			lg_itemalmaceninv iai
			INNER JOIN lg_itemmast i ON (iai.CodItem = i.CodItem)
		WHERE iai.StockActual > 0 $filtro";
$query = mysql_query($sql) or die($sql.mysql_error());
while($field = mysql_fetch_array($query)) {
	$stock_disponible = $field['StockActual'] + $field['StockComprometido'];
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['CodItem'],
					$field['CodInterno'],
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					number_format($field['StockActual'], 2, ',', '.'),
					number_format($field['StockComprometido'], 2, ',', '.'),
					number_format($stock_disponible, 2, ',', '.')));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
