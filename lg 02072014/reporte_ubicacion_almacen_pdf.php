<?php
require('fpdf.php');
require('fphp_lg.php');
connect();

include_once ("../clases/MySQL.php");
include_once("../comunes/objConexion.php");
ob_end_clean();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
if ($falmacen != "") $filtro.=" AND (C.CodAlmacen = '".$falmacen."')";
if ($fcoditem != "") $filtro .= "AND (i.CodItem = '".$fcoditem."') ";
//|| i.CodInterno LIKE '".$fbuscar."' || i.Descripcion LIKE '".$fbuscar."' || i.CodUnidad LIKE '".$fbuscar."' || p.Descripcion LIKE '".$fbuscar."'
if ($fbuscar != "") $filtro .= "AND (A.Ubicacion1 LIKE '".$fbuscar."') ";
if ($fcodlinea != "") $filtro .= "AND (A.CodLinea = '".$fcodlinea."' AND A.CodFamilia = '".$fcodfamilia."' AND A.CodSubFamilia = '".$fcodsubfamilia."') ";
if ($grupoStock != "1") $filtro .= "AND C.StockActual <> 0";
//echo $grupoStock;
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		/*global $_PATHLOGO;		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');		
		$this->SetXY(230, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(230, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); */
		global $_PATHLOGO;
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');
		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); 
		
		$this->Cell(195, 5, utf8_decode('Ubicaciones por Almacén'), 0, 1, 'C', 0);
		$this->Ln(5);		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(40, 25, 25, 70, 20, 18));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
		
		$this->Row(array(utf8_decode('Ubicación'),
						 utf8_decode('Cód. Interno'),
						 utf8_decode('Ítem'),
						 utf8_decode('Descripción'),
						 'Und.',
						 'Stock Actual'));
		//$this->Ln(1);						
	}
	
	//	Pie de página.
	function Footer() {
		/*$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->Rect(9, 258, 197, 0.1, 'DF');*/
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
				i.CodItem,
				i.CodInterno,
				i.Descripcion,
				i.CodUnidad,
				i.CodLinea,
				i.CodFamilia,
				i.CodSubFamilia,
				i.CtaGasto,
				i.PartidaPresupuestal,
				ti.Descripcion AS NomTipoItem,
				p.Descripcion AS NomProcedencia
			FROM
				lg_itemmast i
				INNER JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
				LEFT JOIN lg_procedencias p ON (i.CodProcedencia = p.CodProcedencia)
			WHERE 1 $filtro
			ORDER BY $forden";
			
	$sql ="select A.Descripcion, A.CodUnidad, A.CodInterno, B.CodItem, B.CodAlmacen, B.Ubicacion1, 
			C.StockActual
			from lg_itemmast as A 
			join lg_itemalmacen as B on A.CodItem=B.CodItem
			join lg_itemalmaceninv as C on B.CodItem=C.CodItem
			where 1 $filtro";
	$resp = $objConexion->consultar($sql,'matriz');
		
	$pdf->SetWidths(array(40, 25, 25, 70, 20, 18));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
	
	for($i = 0; $i < count($resp); $i++)
	{
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 6);
		
		
		$pdf->Row(array(utf8_decode($resp[$i]['Ubicacion1']),
						 $resp[$i]['CodInterno'],
						 $resp[$i]['CodItem'],
						 utf8_decode($resp[$i]['Descripcion']),
						 $resp[$i]['CodUnidad'],
						 $resp[$i]['StockActual']));
		//$pdf->Ln(1);			
	}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>