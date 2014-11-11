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
		global $fdesde;
		global $fhasta;

		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);	
		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
		
		$this->SetXY(230, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(230, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Consumo por Dependencia'), 0, 1, 'C');
                $this->SetXY(20, 20); $this->Cell(130, 20, utf8_decode('Desde: ').$fdesde, 0, 0, 'C');
                $this->SetXY(20, 20); $this->Cell(200, 20, utf8_decode('Hasta: ').$fhasta, 0, 1, 'C');

		$this->Ln(8);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(5, 100, 25,  25, 25, 25));
		$this->SetAligns(array('C', 'C',  'C', 'C', 'C','C'));
		$this->Row(array('N',
						'Dependencia',
						utf8_decode('# Requerimientos'),
						
						'Estado',
                                                utf8_decode('F. Preparación'),
                                                utf8_decode('F. Aprobación'), ));
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
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(10, 10);
$pdf->AddPage();
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro.=" AND (lrd.CodOrganismo = '".$forganismo."')";
if ($fdependencia != "") $filtro.=" AND (lr.CodDependencia = '".$fdependencia."')";
if ($fccosto != "") $filtro.=" AND (lrd.CodCentroCosto = '".$fccosto."')";
if ($fclasificacion != "") $filtro.=" AND (lr.Clasificacion = '".$fclasificacion."')";
if ($fedoreg != "") $filtro.=" AND (lr.Estado = '".$fedoreg."')";
if ($fdesde != "" and $fhasta != "" ) 
{
list($dd, $md, $ad)=SPLIT('-', $fdesde);
list($dh, $mh, $ah)=SPLIT('-', $fhasta);	
$filtro.=" and  (lr.FechaPreparacion>='".$ad."-".$md."-".$dd."' AND lr.FechaPreparacion<='".$ah."-".$mh."-".$dh."') ";}

if ($fdirigido != "") $filtro.=" AND (lrd.FlagCompraAlmacen = '".$fdirigido."')";
//if ($fperiodo != "")	$filtro.=" AND (lrd.Periodo = '".$fperiodo."')";
if ($fbuscar != "") {
	list($sbuscar1, $sbuscar2)=SPLIT('[|]', $sltbuscar);	
	if ($sbuscar2 == "" && $sltbuscar == "") $filtro.=" AND (lrd.CodRequerimiento LIKE '%".$fbuscar."%' OR lrd.CodItem LIKE '%".utf8_decode($fbuscar)."%' OR lrd.CommodityMast LIKE '%".utf8_decode($fbuscar)."%' OR lrd.Descripcion LIKE '%".utf8_decode($fbuscar)."%' OR lr.CodCentroCosto LIKE '%".utf8_decode($fbuscar)."%')";	elseif ($sbuscar2 == "" && $sltbuscar != "") $filtro.=" AND $sbuscar1 LIKE '%".$fbuscar."%'";
	elseif ($sbuscar2 != "") $filtro.="AND ($sbuscar1 LIKE '%".$fbuscar."%' OR $sbuscar2 LIKE '%".$fbuscar."%')";
}

//---------------------------------------------------
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
				lr.CodInterno,
				lrd.CommoditySub,
				lrd.Descripcion,
				lrd.CodUnidad,
				lrd.CantidadPedida,
				lrd.CantidadRecibida,
				(lrd.CantidadPedida - lrd.CantidadRecibida) As CantidadPendiente,
				lrd.CantidadOrdenCompra,
				lrd.DocReferencia,
				lrd.FlagCompraAlmacen,
				lr.Estado AS EstadoDet,
				lc.Descripcion AS NomClasificacion,
				la.Descripcion AS NomAlmacen,
				cc.Abreviatura,
				dep.Dependencia,
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
				INNER JOIN mastdependencias dep ON (lr.CodDependencia=dep.CodDependencia) 
			WHERE 1 $filtro
			ORDER BY lr.CodRequerimiento";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	
	if ($field['CodItem'] != "") $codigo = $field['CodItem']; else $codigo = $field['CommoditySub'];
	if ($field['StockActualItem'] != "") $stock = $field['StockActualItem']; else $stock = $field['StockActualComm'];
	$edodet = printValores("ESTADO-REQUERIMIENTO-DET", $field['EstadoDet']);
	$edomast = printValores("ESTADO-REQUERIMIENTO", $field['EstadoMast']);
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	 
	if ($grupo != $field['CodRequerimiento']) {
		$i++;
		$grupo = $field['CodRequerimiento'];
		//$pdf->Ln(3);
		$pdf->SetFont('Arial', '', 6);
		$pdf->Cell(5, 4, $i, 0, 0, 'L');
		$pdf->Cell(100, 4, utf8_decode($field['Dependencia']), 0, 0, 'C');		
		/*$pdf->Cell(12, 4, utf8_decode('Almacén: '), 0, 0, 'L');   $field['Abreviatura']
		$pdf->Cell(50, 4, $field['NomAlmacen'], 0, 0, 'L');		
		$pdf->Cell(16, 4, utf8_decode('Clasificación: '), 0, 0, 'L');
		$pdf->Cell(30, 4, $field['NomClasificacion'], 0, 1, 'L');*/
		
		//$pdf->Cell(20, 4, '# Requerimiento: ', 0, 0, 'L');
		$pdf->Cell(25, 4, $field['CodInterno'], 0, 0, 'C');
		/*$pdf->Cell(16, 4, 'Estado: ', 0, 0, 'L');*/
		$pdf->Cell(25, 4, $edomast, 0, 0, 'C');
		
		//$pdf->Cell(20, 4, utf8_decode(''), 0, 0, 'L');
		$pdf->Cell(25, 4, formatFechaDMA($field['FechaPreparacion']), 0, 0, 'C');
		//$pdf->Cell(16, 4, utf8_decode(''), 0, 0, 'L');
		$pdf->Cell(25, 4, formatFechaDMA($field['FechaAprobacion']), 0, 1, 'C');
		$pdf->Ln(1);
	}
	
	/*$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($i,
					$codigo,
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					number_format($field['CantidadPedida'], 2, ',', '.'),
					number_format($field['CantidadRecibida'], 2, ',', '.'),
					number_format($field['CantidadPendiente'], 2, ',', '.'),
					$edodet,
					number_format($stock, 2, ',', '.')));*/
	
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
