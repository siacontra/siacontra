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
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Ordenes de Servicios'), 0, 1, 'C');
		$this->Ln(5);		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(10, 20, 135, 20, 20));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'C'));
		$this->Row(array('#',
						 utf8_decode('Commodity'),
						 utf8_decode('Descripción'),
						 'C.Costo',
						 'Confirmado'));
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
if ($forganismo != "") $filtro.=" AND (os.CodOrganismo = '".$forganismo."')";
if ($fcodccosto != "") $filtro.=" AND (osd.CodCentroCosto = '".$fcodccosto."')";
if ($fproveedor != "") $filtro.=" AND (os.CodProveedor = '".$fproveedor."')";
if ($fedoreg != "") $filtro.=" AND (os.Estado = '".$fedoreg."')";
if ($fpreparaciond != "") $filtro.=" AND (os.FechaPreparacion >= '".formatFechaAMD($fpreparaciond)."')";
if ($fpreparacionh != "") $filtro.=" AND (os.FechaPreparacion <= '".formatFechaAMD($fpreparacionh)."')";
if ($fmontod != "") $filtro.=" AND (os.TotalMontoIva >= ".setNumero($fmontod).")";
if ($fmontoh != "") $filtro.=" AND (os.TotalMontoIva <= ".setNumero($fmontoh).")";
if ($faprobaciond != "") $filtro.=" AND (os.FechaAprobacion >= '".formatFechaAMD($faprobaciond)."')";
if ($faprobacionh != "") $filtro.=" AND (os.FechaAprobacion <= '".formatFechaAMD($faprobacionh)."')";
if ($fedodet != "") $filtro.=" AND (osd.Estado = '".$fedodet."')";
if ($fcodcommodity != "") $filtro.=" AND (osd.CommoditySub = '".$fcodcommodity."')";
//---------------------------------------------------
$sql = "SELECT
			os.CodOrganismo,
			os.NroOrden,
			os.CodProveedor,
			os.NomProveedor,
			os.FechaDocumento,
			os.FechaPreparacion,
			os.FechaAprobacion,
			os.TotalMontoIva AS TotalOrden,
			(SELECT SUM(os1.TotalMontoIva)
			 FROM lg_ordenservicio os1
			 WHERE os1.CodProveedor = os.CodProveedor
			 GROUP BY CodProveedor) AS TotalProveedor,
			DATEDIFF(NOW(), os.FechaEntrega) AS DiasAtrasados,
			osd.CommoditySub,
			osd.Descripcion,
			osd.CodCentroCosto,
			osd.CantidadPedida,
			osd.CantidadRecibida,
			osd.FechaEsperadaTermino,
			osd.FlagTerminado
		FROM
			lg_ordenservicio os
			INNER JOIN lg_ordenserviciodetalle osd ON (os.CodOrganismo = osd.CodOrganismo AND os.NroOrden = osd.NroOrden)
		WHERE 1 $filtro
		ORDER BY CodOrganismo, CodProveedor, NroOrden";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;	$j=0;
while ($field = mysql_fetch_array($query)) {	$i++;
	if ($field['FlagTerminado'] == "S") $flagterminado = "Si"; else $flagterminado = "No";

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
		$pdf->Cell(140, 8, $field['NomProveedor'], 0, 0, 'L', 1);
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
		$pdf->Cell(40, 8, 'F.Aprobado: '.formatFechaDMA($field['FechaAprobacion']), 0, 0, 'C');
		$pdf->Cell(65, 8, $estado, 0, 0, 'L');
		$pdf->Cell(13, 8, 'Total Orden: ', 0, 0, 'R');
		$pdf->Cell(25, 8, number_format($field['TotalOrden'], 2, ',', '.'), 0, 1, 'R');
		$j=0;
	}
	
	if ($chkverdet == "S") {	$j++;
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);	
		$pdf->Row(array($j,
						$field['CommoditySub'],
						$field['Descripcion'],
						$field['CodCentroCosto'],
						$flagterminado));
		$pdf->Ln(1);
	}
	
	$total += $field['Total'];
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
