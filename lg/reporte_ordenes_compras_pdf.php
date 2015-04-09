<?php
require('fpdf.php');
require('fphp_lg.php');
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
		
		$this->Image($_PATHLOGO.'encabezadopdf.jpg', 5, 5, 12, 12);	
		
	    $this->SetFont('Arial', '', 6);
		$this->SetXY(15, 5); $this->Cell(100, 5, "   ".$_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 8); $this->Cell(100, 5, utf8_decode('   DIRECCIÓN DE ADMINISTRACIÓN'), 0, 1, 'L');
		$this->SetXY(15, 11); $this->Cell(100, 5, utf8_decode('   DIVISIÓN DE COMPRAS'), 0, 1, 'L');
		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Ordenes de Compras'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(17, 102, 10, 13, 13, 15, 15, 20));
		$this->SetAligns(array('C', 'L', 'C', 'R', 'R', 'C', 'C', 'C'));
		$this->Row(array(utf8_decode('Código'),
						 utf8_decode('Descripción'),
						 'Uni.',
						 'Cant. Pedida',
						 'Cant. Recibida',
						 'Fecha de Entrega',
						 'Dias Atrasados',
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
if ($forganismo != "") $filtro.=" AND (oc.CodOrganismo = '".$forganismo."')";
if ($fclasificacion != "") $filtro.=" AND (oc.Clasificacion = '".$fclasificacion."')";
if ($fproveedor != "") $filtro.=" AND (oc.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (oc.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro.=" AND (oc.FechaPreparacion >= '".formatFechaAMD($fpreparaciond)."')";
if ($fpreparacionh != "") $filtro.=" AND (oc.FechaPreparacion <= '".formatFechaAMD($fpreparacionh)."')";
if ($fmontod != "") $filtro.=" AND (oc.MontoTotal >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (oc.MontoTotal <= ".setNumero($fmontoh).")";
if ($fatraso != "") $filtro.=" AND (DATEDIFF(NOW(), oc.FechaPrometida) >= '".$fatraso."')";
if ($fedodet != "") $filtro.=" AND (ocd.Estado = '".$fedodet."')";
if ($fcoditem != "") $filtro.=" AND (ocd.CodItem = '".$fcoditem."')";
if ($falmacen != "") $filtro.=" AND (ocd.CodAlmacen = '".$falmacen."')";
if ($fcodcommodity != "") $filtro.=" AND (ocd.CommoditySub = '".$fcodcommodity."')";
//---------------------------------------------------
$sql = "SELECT
			oc.CodOrganismo,
			oc.NroOrden,
			oc.CodProveedor,
			oc.NomProveedor,
			oc.FechaPreparacion,
			oc.MontoTotal AS TotalOrden,
			oc.Estado AS EstadoMast,
			(SELECT SUM(oc1.MontoTotal)
			 FROM lg_ordencompra oc1
			 WHERE oc1.CodProveedor = oc.CodProveedor
			 GROUP BY CodProveedor) AS TotalProveedor,
			DATEDIFF(NOW(), oc.FechaPrometida) AS DiasAtrasados,
			ocd.CodItem,
			ocd.CommoditySub,
			ocd.Descripcion,
			ocd.CodUnidad,
			ocd.CantidadPedida,
			ocd.CantidadRecibida,
			ocd.FechaPrometida,
			ocd.Estado AS EstadoDetalle
		FROM
			lg_ordencompra oc
			INNER JOIN lg_ordencompradetalle ocd ON (oc.CodOrganismo = ocd.CodOrganismo AND oc.NroOrden = ocd.NroOrden)
		WHERE 1 $filtro
		ORDER BY CodOrganismo, CodProveedor, NroOrden";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	$i++;
	$estado = printValores("ESTADO-ORDENES", $field['EstadoMast']);
	$edodet = printValores("ESTADO-ORDENES-DET", $field['EstadoDetalle']);
	if ($field['CodItem'] != "") $codigo = $field['CodItem']; else $codigo = $field['CommoditySub'];

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];
		$grupo2 = "";
		
		$pdf->SetFillColor(245, 245, 245);
		if ($i > 1) $pdf->Ln(2);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(17, 8, 'Proveedor: ', 0, 0, 'R', 1);
		$pdf->Cell(10, 8, $field['CodProveedor'], 0, 0, 'L', 1);
		$pdf->Cell(140, 8, utf8_decode($field['NomProveedor']), 0, 0, 'L', 1);
		$pdf->Cell(13, 8, 'Total Proveedor: ', 0, 0, 'R', 1);
		$pdf->Cell(25, 8, number_format($field['TotalProveedor'], 2, ',', '.'), 0, 1, 'R', 1);
	}
	
	if ($grupo2 != $field['NroOrden']) {
		$grupo2 = $field['NroOrden'];
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(17, 8, 'NroOrden: ', 0, 0, 'R');
		$pdf->Cell(15, 8, $field['NroOrden'], 0, 0, 'L');
		$pdf->Cell(30, 8, formatFechaDMA($field['FechaPreparacion']), 0, 0, 'C');
		$pdf->Cell(105, 8, $estado, 0, 0, 'L');
		$pdf->Cell(13, 8, 'Total Orden: ', 0, 0, 'R');
		$pdf->Cell(25, 8, number_format($field['TotalOrden'], 2, ',', '.'), 0, 1, 'R');
	}
	
	if ($chkverdet == "S") {
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);	
		$pdf->Row(array($codigo,
						utf8_decode($field['Descripcion']),
						$field['CodUnidad'],
						number_format($field['CantidadPedida'], 2, ',', '.'),
						number_format($field['CantidadRecibida'], 2, ',', '.'),
						formatFechaDMA($field['FechaPrometida']),
						$field['DiasAtrasados'],
						$edodet));
		$pdf->Ln(1);
	}
	
	$total += $field['Total'];
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
