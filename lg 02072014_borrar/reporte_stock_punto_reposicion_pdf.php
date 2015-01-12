<?php
require('fpdf.php');
require('fphp_lg.php');

include_once ("../clases/MySQL.php");
include_once("../comunes/objConexion.php");
ob_end_clean();
connect();
extract($_POST);
extract($_GET);
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
		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Stock Punto de Reposición'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(30, 62, 27, 27,27,27));
		$this->SetAligns(array('C', 'C', 'C', 'C','C','C'));
		
		$this->Row(array(utf8_decode('Item'),
						 utf8_decode('Descripción'),
						 utf8_decode('Und.'),
						 'Stock Actual',
						 utf8_decode('Pto. Reposición'),
						 'Faltante'));
		//$this->Ln(1);
						
	}
	
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$filtro = "";
/*if ($forganismo != "") $filtro.=" AND (oc.CodOrganismo = '".$forganismo."')";
if ($fclasificacion != "") $filtro.=" AND (oc.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro.=" AND (oc.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (oc.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro.=" AND (oc.FechaAprobacion >= '".formatFechaAMD($fpreparaciond)."')";
if ($fpreparacionh != "") $filtro.=" AND (oc.FechaAprobacion <= '".formatFechaAMD($fpreparacionh)."')";
if ($fmontod != "") $filtro.=" AND (oc.MontoTotal >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (oc.MontoTotal <= ".setNumero($fmontoh).")";*/

if ($falmacen != "") $filtro.=" AND (C.CodAlmacen = '".$falmacen."')";
//if ($fcodcommodity != "") $filtro.=" AND (ocd.CommoditySub = '".$fcodcommodity."')";
//---------------------------------------------------
/*

SELECT A.CodItem, A.Descripcion, B.StockActual, C.StockMin, C.StockMax, fami.Descripcion as DescripcionFami, line.Descripcion as DescripcionLine, (C.StockReorden-B.StockActual)
FROM lg_itemmast AS A
JOIN lg_clasefamilia as fami on fami.CodFamilia= A.CodFamilia
JOIN lg_claselinea as line on line.CodLinea= A.CodLinea
JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
AND B.CodAlmacen = C.CodAlmacen
WHERE B.StockActual < C.StockReorden

SELECT A.CodItem, A.Descripcion, B.StockActual, C.StockMin, C.StockMax, fami.Descripcion as DescripcionFami, line.Descripcion as DescripcionLine, (C.StockReorden-B.StockActual), C.StockReorden
FROM lg_itemmast AS A
JOIN lg_clasefamilia as fami on fami.CodFamilia= A.CodFamilia
JOIN lg_claselinea as line on line.CodLinea= A.CodLinea
JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
AND B.CodAlmacen = C.CodAlmacen
WHERE B.StockActual < C.StockReorden*/

			$sql = "SELECT line.CodLinea, fami.CodFamilia,line.Descripcion as DescripcionLine,fami.Descripcion as DescripcionFami
					
					FROM lg_clasefamilia as fami
					JOIN lg_claselinea as line on fami.CodLinea=line.CodLinea";


			$resp = $objConexion->consultar($sql,'matriz');

			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 7);
			
				
			for($i = 0; $i < count($resp); $i++)
			{
				$sql1 = "SELECT A.CodItem, A.Descripcion, C.StockMin, C.StockMax, fami.Descripcion as DescripcionFami, line.Descripcion as DescripcionLine, (C.StockReorden-B.StockActual) as faltante,A.CodUnidad,C.StockReorden,B.StockActual
					FROM lg_itemmast AS A
					JOIN lg_clasefamilia as fami on fami.CodFamilia= A.CodFamilia
					JOIN lg_claselinea as line on line.CodLinea= A.CodLinea and fami.CodLinea=line.CodLinea
					JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
					JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
					AND B.CodAlmacen = C.CodAlmacen
					WHERE B.StockActual < C.StockReorden and A.CodLinea='".$resp[$i]['CodLinea']."' and A.CodFamilia='".$resp[$i]['CodFamilia']."'
					
				$filtro";
				
				$resp1 = $objConexion->consultar($sql1,'matriz');
				
				
				$pdf->SetWidths(array(200));
				$pdf->SetAligns(array('L'));
				$pdf->Row(array(utf8_decode('Línea-Familia: '.utf8_decode($resp[$i]['DescripcionLine']).'-'.$resp[$i]['DescripcionFami'])));

				for($j = 0; $j < count($resp1); $j++)
				{
					
					$pdf->SetWidths(array(30, 62, 27, 27,27,27));
					$pdf->SetAligns(array('C', 'C', 'C', 'C','C','C','C'));
			
					$pdf->Row(array($resp1[$j]['CodItem'],
						 utf8_decode($resp1[$j]['Descripcion']),
						 utf8_decode($resp1[$j]['CodUnidad']),
						 $resp1[$j]['StockActual'],
						 $resp1[$j]['StockReorden'],
						 $resp1[$j]['faltante']));
							 
				
				}
			}
			

//	Muestro el contenido del pdf.
$pdf->Output();
?>  
