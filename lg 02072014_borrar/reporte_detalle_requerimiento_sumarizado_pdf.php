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
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Sumarizado por Item'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(18, 18, 18, 46, 15, 15, 15, 25, 15, 20));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'C', 'R', 'R', 'C', 'R', 'C'));
		$this->Row(array('Requerimiento',
						 utf8_decode('F. Preparación'),
						 utf8_decode('F. Aprobación'),
						 utf8_decode('Clasificación'),
						 'C.Costos',
						 'Pedida',
						 'Pendiente',
						 utf8_decode('Almacén'),
						 'Stock Actual',
						 'Estado'));
		$this->Ln(1);	
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
if ($forganismo != "") $filtro.=" AND (lrd.CodOrganismo = '".$forganismo."')";
if ($fdependencia != "") $filtro.=" AND (lr.CodDependencia = '".$fdependencia."')";
if ($fccosto != "") $filtro.=" AND (lrd.CodCentroCosto = '".$fccosto."')";
if ($fclasificacion != "") $filtro.=" AND (lr.Clasificacion = '".$fclasificacion."')";
if ($fedoreg != "") $filtro.=" AND (lrd.Estado = '".$fedoreg."')";
if ($fdirigido != "") $filtro.=" AND (lrd.FlagCompraAlmacen = '".$fdirigido."')";
if ($fbuscar != "") {
	list($sbuscar1, $sbuscar2)=SPLIT('[|]', $sltbuscar);	
	if ($sbuscar2 == "" && $sltbuscar == "") $filtro.=" AND (lrd.CodRequerimiento LIKE '%".$fbuscar."%' OR lrd.CodItem LIKE '%".utf8_decode($fbuscar)."%' OR lrd.CommoditySub LIKE '%".utf8_decode($fbuscar)."%' OR lrd.Descripcion LIKE '%".utf8_decode($fbuscar)."%' OR lr.CodCentroCosto LIKE '%".utf8_decode($fbuscar)."%')";	elseif ($sbuscar2 == "" && $sltbuscar != "") $filtro.=" AND $sbuscar1 LIKE '%".$fbuscar."%'";
	elseif ($sbuscar2 != "") $filtro.="AND ($sbuscar1 LIKE '%".$fbuscar."%' OR $sbuscar2 LIKE '%".$fbuscar."%')";
}
//---------------------------------------------------
$sql = "SELECT
			lrd.CodItem,
			lrd.CommoditySub,
			lrd.Descripcion,
			lrd.CodUnidad,
			SUM(lrd.CantidadPedida) AS TotalCantidadPedida,
			SUM(lrd.CantidadPedida - lrd.CantidadRecibida) AS TotalCantidadPendiente,
			(SELECT SUM(StockActual)
			 FROM lg_itemalmaceninv
			 WHERE CodItem = lrd.CodItem) AS StockActualItem,
			(SELECT SUM(Cantidad)
			 FROM lg_commoditystock
			 WHERE CommoditySub = lrd.CommoditySub) AS StockActualComm
		FROM
			lg_requerimientosdet lrd
			LEFT JOIN lg_itemmast i ON (lrd.CodItem = i.CodItem)
			LEFT JOIN lg_commoditysub cs ON (lrd.CommoditySub = cs.Codigo)
		GROUP BY CodItem, CommoditySub";
$query_item = mysql_query($sql) or die ($sql.mysql_error());
while ($field_item = mysql_fetch_array($query_item)) {
	if ($field_item['CodItem'] != "") $codigo = $field_item['CodItem']; else $codigo = $field_item['CommoditySub'];
	if ($field_item['StockActualItem'] != "") $stock_item = $field_item['StockActualItem']; else $stock_item = $field_item['StockActualComm'];
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(18, 82, 15, 15, 15, 25, 15));
	$pdf->SetAligns(array('C', 'L', 'C', 'R', 'R', 'C', 'R'));
	$pdf->Row(array($codigo,
					$field_item['Descripcion'],
					$field_item['CodUnidad'],
					number_format($field_item['TotalCantidadPedida'], 2, ',', '.'),
					number_format($field_item['TotalCantidadPendiente'], 2, ',', '.'),
					$field_item['CodAlmacen'],
					number_format($stock_item, 2, ',', '.')));
	
	$sql = "SELECT
				lr.CodRequerimiento,
				lr.Clasificacion,
				lr.FechaPreparacion,
				lr.FechaAprobacion,
				lr.Estado AS EstadoMast,
				lr.CodCentroCosto,
				lr.CodAlmacen,
				lrd.Secuencia,
				lrd.CodUnidad,
				lrd.CantidadPedida,
				lrd.CantidadRecibida,
				(lrd.CantidadPedida - lrd.CantidadRecibida) As CantidadPendiente,
				lrd.Estado AS EstadoDet,
				lc.Descripcion AS NomClasificacion,
				la.Descripcion AS NomAlmacen,
				cc.Abreviatura,
				(SELECT StockActual
				 FROM lg_itemalmaceninv
				 WHERE
				 	CodAlmacen = lr.CodAlmacen AND
					CodItem = lrd.CodItem) AS StockActualItem,
				(SELECT Cantidad
				 FROM lg_commoditystock
				 WHERE
				 	CodAlmacen = lr.CodAlmacen AND
					CommoditySub = lrd.CommoditySub) AS StockActualComm
			FROM
				lg_requerimientos lr
				INNER JOIN lg_requerimientosdet lrd ON (lrd.CodOrganismo = lr.CodOrganismo AND lrd.CodRequerimiento = lr.CodRequerimiento)
				INNER JOIN lg_clasificacion lc ON (lr.Clasificacion = lc.Clasificacion)
				INNER JOIN lg_almacenmast la ON (lr.CodAlmacen = la.CodAlmacen)
				INNER JOIN ac_mastcentrocosto cc ON (lr.CodCentroCosto = cc.CodCentroCosto)
			WHERE lrd.CodItem = '".$field_item['CodItem']."' AND lrd.CommoditySub = '".$field_item['CommoditySub']."'
			ORDER BY CodRequerimiento, Secuencia";
	$query_req = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_req = mysql_fetch_array($query_req)) {
		$edodet = printValores("ESTADO-REQUERIMIENTO-DET", $field_req['EstadoDet']);
		
		$pdf->SetFont('Arial', '', 6);
		$pdf->SetWidths(array(18, 18, 18, 46, 15, 15, 15, 25, 15, 20));
		$pdf->SetAligns(array('C', 'C', 'C', 'L', 'C', 'R', 'R', 'C', 'R', 'C'));
		$pdf->Row(array($field_req['CodRequerimiento'],
						formatFechaDMA($field_req['FechaPreparacion']),
						formatFechaDMA($field_req['FechaAprobacion']),
						$field_req['NomClasificacion'],
						$field_req['CodCentroCosto'].'',
						number_format($field_req['CantidadPedida'], 2, ',', '.'),
						number_format($field_req['CantidadPendiente'], 2, ',', '.'),
						$field_req['CodAlmacen'],
						'',
						$edodet));
		$pdf->Ln(1);
	}
}

/*


		$this->SetWidths(array(20, 20, 20, 40, 20, 15, 15, 20, 15, 20));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'C', 'R', 'R', 'C', 'R', 'C'));
		$this->Row(array('Requerimiento',
						 utf8_decode('F. Preparación'),
						 utf8_decode('F. Aprobación'),
						 utf8_decode('Clasificación'),
						 'C.Costos',
						 'Pedida',
						 'Pendiente',
						 utf8_decode('Almacén'),
						 'Stock Actual',
						 'Estado'));
$sql = "SELECT
				lr.CodOrganismo,
				lr.CodRequerimiento,
				lr.Clasificacion,
				lr.FechaPreparacion,
				lr.FechaAprobacion,
				lr.Estado AS EstadoMast,
				lr.CodCentroCosto,
				lr.CodAlmacen,
				lrd.Secuencia,
				lrd.CodItem,
				lrd.CommoditySub,
				lrd.Descripcion,
				lrd.CodUnidad,
				lrd.CantidadPedida,
				lrd.CantidadRecibida,
				(lrd.CantidadPedida - lrd.CantidadRecibida) As CantidadPendiente,
				lrd.CantidadOrdenCompra,
				lrd.DocReferencia,
				lrd.FlagCompraAlmacen,
				lrd.Estado AS EstadoDet,
				lc.Descripcion AS NomClasificacion,
				la.Descripcion AS NomAlmacen,
				cc.Abreviatura,
				(SELECT StockActual
				 FROM lg_itemalmaceninv
				 WHERE
				 	CodAlmacen = lr.CodAlmacen AND
					CodItem = lrd.CodItem) AS StockActualItem,
				(SELECT Cantidad
				 FROM lg_commoditystock
				 WHERE
				 	CodAlmacen = lr.CodAlmacen AND
					CommoditySub = lrd.CommoditySub) AS StockActualComm
			FROM
				lg_requerimientosdet lrd
				INNER JOIN lg_requerimientos lr ON (lrd.CodOrganismo = lr.CodOrganismo AND lrd.CodRequerimiento = lr.CodRequerimiento)
				INNER JOIN lg_clasificacion lc ON (lr.Clasificacion = lc.Clasificacion)
				INNER JOIN lg_almacenmast la ON (lr.CodAlmacen = la.CodAlmacen)
				INNER JOIN ac_mastcentrocosto cc ON (lr.CodCentroCosto = cc.CodCentroCosto)
			WHERE 1 $filtro
			ORDER BY CodItem, CommoditySub, CodRequerimiento";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	$i++;
	if ($field['CodItem'] != "") $codigo = $field['CodItem']; else $codigo = $field['CommoditySub'];
	if ($field['StockActualItem'] != "") $stock = $field['StockActualItem']; else $stock = $field['StockActualComm'];
	$edodet = printValores("ESTADO-REQUERIMIENTO-DET", $field['EstadoDet']);
	$edomast = printValores("ESTADO-REQUERIMIENTO", $field['EstadoMast']);
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	
	if ($grupo != $codigo) {
		$grupo = $codigo;
		$pdf->Ln(3);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 4, $codigo, 0, 0, 'L');
		$pdf->Cell(50, 4, $field['Descripcion'], 0, 0, 'L');
		$pdf->Cell(30, 4, $field['CodUnidad'], 0, 1, 'L');
		$pdf->Cell(50, 4, $field['SubPedida'], 0, 0, 'L');
		$pdf->Cell(30, 4, $field['SubPendiente'], 0, 1, 'L');
		$pdf->Cell(30, 4, $edomast, 0, 1, 'L');
		
		$pdf->Cell(20, 4, utf8_decode('F. Preparación: '), 0, 0, 'L');
		$pdf->Cell(92, 4, formatFechaDMA($field['FechaPreparacion']), 0, 0, 'L');
		$pdf->Cell(16, 4, utf8_decode('F. Aprobación: '), 0, 0, 'L');
		$pdf->Cell(30, 4, formatFechaDMA($field['FechaAprobacion']), 0, 1, 'L');
	}
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($i,
					$codigo,
					$field['Descripcion'],
					$field['CodUnidad'],
					number_format($field['CantidadPedida'], 2, ',', '.'),
					number_format($field['CantidadRecibida'], 2, ',', '.'),
					number_format($field['CantidadPendiente'], 2, ',', '.'),
					$edodet,
					number_format($stock, 2, ',', '.')));
	$pdf->Ln(1);
}
*/
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
