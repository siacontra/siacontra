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
		$this->Image($_PATHLOGO.'encabezadopdf.jpg', 5, 5, 12, 12);	
		
    	$this->SetFont('Arial', '', 6);
		$this->SetXY(15, 5); $this->Cell(100, 5, "   ".$_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 8); $this->Cell(100, 5, utf8_decode('   DIRECCIÓN DE ADMINISTRACIÓN'), 0, 1, 'L');	
		$this->SetXY(15, 11); $this->Cell(100, 5, utf8_decode('   DIVISIÓN DE COMPRAS'), 0, 1, 'L');	
		$this->SetXY(170, 5); $this->Cell(12, 5, utf8_decode('Fecha: '), 0, 0, 'L'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(170, 10); $this->Cell(12, 5, utf8_decode('Página: '), 0, 0, 'L'); 
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

		$jefe_division=array();
		$director=array();
		// CodCargo=27 director
		// CodCargo=30 jefe area
		// CodCargo=29 jefe division
		// CodDependencia=0006 Administracion
		// CodDependencia=0025 Compras
        $director=getDatos('0006','27','27');      
        $jefe=getDatos('0025','29','30');  
        $this->SetFont('Arial', 'B', 7);
        $this->SetXY(5, 255);
		$this->Cell(68.5, 5, utf8_decode($this->cargoPreparadoPor), 0, 1, 'C', 0);
        
        //JEFE DE DIVISION
		$this->SetXY(73.5, 251);
       //$this->Cell(68.5, 5, utf8_decode($jefe[0]), 0, 1, 'L', 0);
		$this->SetLeftMargin(74);
		$this->SetRightMargin(70);
		$jefe[0]=utf8_decode($jefe[0]);
		$this->Write(3,$jefe[0]);	


		//JEFE DE DIVISION
		$this->SetXY(73.5, 257);
		$this->SetLeftMargin(74);
		$this->SetRightMargin(75);
		//$this->Cell(68.5, 5, utf8_decode($jefe[1]), 0, 1, 'C', 0);
        $jefe[1]=utf8_decode($jefe[1]);
		$this->Write(3,$jefe[1]);	

		//JEFE DE DIVISION
		$this->SetXY(73.5, 264);
		$this->SetLeftMargin(74);
		$this->SetRightMargin(70);
		//$this->Cell(68.5, 5, utf8_decode($jefe[2]), 0, 1, 'C', 0);
        $jefe[2]=utf8_decode($jefe[2]);
		$this->Write(3,$jefe[2]);	

		//DIRECTOR
		$director[0]="asdf dasfsdaf sdasadf sadfsda asd sadfsda fsad fsa fasdfasdf asdfsdfsadf";
		$this->SetXY(142, 250);
		$this->SetLeftMargin(85);
		$this->SetRightMargin(70);
		$director[0]=utf8_decode($director[0]);
		$this->Cell(68, 5,$director[0], 0, 0, 'L', 0);
		//DIRECTOR
		$this->SetXY(142, 253);
		$this->SetLeftMargin(74);
		$this->SetRightMargin(70);
		$this->Cell(68, 5, utf8_decode($director[1]), 0, 0, 'L', 0);
		// DIRECTOR
		$this->SetXY(142, 256);
		$this->SetLeftMargin(74);
		$this->SetRightMargin(70);
		$this->Cell(68, 5, utf8_decode($director[2]), 0, 0, 'C', 0);
		$this->SetFont('Arial', 'B', 8);
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
