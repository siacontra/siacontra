<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
if ($fclasificacion != "") $filtro .= "AND (cc.Clasificacion = '".$fclasificacion."') ";
if ($fcommodity != "") $filtro .= "AND (cm.CommodityMast = '".$fcommodity."') ";
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
		$this->SetXY(5, 20); 
		$this->Cell(195, 5, utf8_decode('Información de Commodities'), 0, 1, 'C', 0);
		$this->Ln(5);		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 130, 10, 15, 15, 20));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));
		$this->Row(array('Sub-Clase',
						 'Descripcion',
						 'Und.',
						 'Partida',
						 'Cta. Gasto',
						 utf8_decode('Clasificación')));						 
		$this->Ln(1);						
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->Rect(9, 258, 197, 0.1, 'DF');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(1, 20);
$pdf->AddPage();
//---------------------------------------------------
//	consulto el listado
$sql = "SELECT			
			cc.Clasificacion,
			cc.Descripcion AS NomClasificacion,
			cm.CommodityMast,
			cm.Descripcion AS NomCommodity,
			cs.Codigo,
			cs.Descripcion,
			cs.CodUnidad,
			cs.cod_partida,
			cs.CodCuenta,
			cs.CodClasificacion
		FROM
			lg_commoditymast cm
			INNER JOIN lg_commoditysub cs ON (cm.CommodityMast = cs.CommodityMast)
			INNER JOIN lg_commodityclasificacion cc ON (cm.Clasificacion = cc.Clasificacion)
			INNER JOIN af_clasificacionactivo ca On (cs.CodClasificacion = ca.CodClasificacion)
		WHERE 1 $filtro
		ORDER BY cc.Clasificacion, cs.Codigo";
$query = mysql_query($sql) or die($sql.mysql_error());
while($field = mysql_fetch_array($query)) {
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	
	if ($grupo1 != $field['Clasificacion']) {
		$grupo1 = $field['Clasificacion'];
		$grupo2 = "";
		$pdf->SetFont('Arial', 'BU', 6);
		$pdf->Cell(2, 5);
		$pdf->Cell(6, 5, $field['Clasificacion'], 0, 0, 'L');
		$pdf->Cell(187, 5, $field['NomClasificacion'], 0, 1, 'L');
	}
	
	if ($grupo2 != $field['CommodityMast']) {
		$grupo2 = $field['CommodityMast'];
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(2, 5);
		$pdf->Cell(6, 5, $field['CommodityMast'], 0, 0, 'L');
		$pdf->Cell(187, 5, $field['NomCommodity'], 0, 1, 'L');
	}
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['Codigo'],
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					$field['cod_partida'],
					$field['CodCuenta'],
					$field['CodClasificacion']));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
