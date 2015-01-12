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
		global $faprobaciond;
		global $faprobacionh;
		
		$this->Image($_PATHLOGO.'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');
		
		$this->SetXY(175, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Últimas Compras Realizadas (Commodities)'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetFont('Arial', 'B', 7);
		$this->Cell(195, 5, utf8_decode('Fecha de Aprobación entre: '.$faprobaciond.' y '.$faprobacionh), 0, 1, 'L');
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetWidths(array(20, 15, 115, 20, 20, 15));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'R', 'R'));
		$this->Row(array('Fecha',
						 'Proveedor',
						 utf8_decode('Razón Social'),
						 'Nro. Orden',
						 'Precio Unit.',
						 'Cantidad'));
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
if ($faprobaciond != "") $filtro.=" AND (oc.FechaAprobacion >= '".formatFechaAMD($faprobaciond)."')";
if ($faprobacionh != "") $filtro.=" AND (oc.FechaAprobacion <= '".formatFechaAMD($faprobacionh)."')";
//---------------------------------------------------
$sql = "SELECT
			oc.CodOrganismo,
			oc.NroOrden,
			oc.CodProveedor,
			oc.NomProveedor,
			oc.FechaAprobacion,
			DATEDIFF(NOW(), oc.FechaPrometida) AS DiasAtrasados,
			ocd.CommoditySub,
			ocd.Descripcion,
			ocd.CodUnidad,
			ocd.PrecioUnit,
			ocd.CantidadPedida
		FROM
			lg_ordencompra oc
			INNER JOIN lg_ordencompradetalle ocd ON (oc.CodOrganismo = ocd.CodOrganismo AND oc.NroOrden = ocd.NroOrden)
			INNER JOIN lg_commoditysub cs ON (ocd.CommoditySub = cs.Codigo)
		WHERE 1 $filtro
		ORDER BY CommoditySub, CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {			$i++;

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	
	if ($grupo != $field['CommoditySub']) {
		$grupo = $field['CommoditySub'];
		
		if ($i > 1) $pdf->Ln(2);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(20, 5, $field['CommoditySub'], 0, 0, 'C', 1);
		$pdf->Cell(10, 5, $field['CodUnidad'], 0, 0, 'C', 1);
		$pdf->Cell(150, 5, $field['Descripcion'], 0, 0, 'L', 1);
		$pdf->Cell(25, 5, $field['CantidadRecibida'], 0, 0, 'R', 1);
		$pdf->Ln();
	}
	
	if ($chkverdet == "S") {
		$pdf->SetFont('Arial', '', 7);
		$pdf->Row(array(formatFechaDMA($field['FechaAprobacion']),
						$field['CodProveedor'],
						$field['NomProveedor'],
						$field['NroOrden'],
						number_format($field['PrecioUnit'], 2, ',', '.'),
						number_format($field['CantidadPedida'], 2, ',', '.'),
						$field['CodAlmacen']));
		$pdf->Ln(1);
	}
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
