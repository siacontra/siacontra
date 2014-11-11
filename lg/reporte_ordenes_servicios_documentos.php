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
		$this->SetXY(5, 20); $this->Cell(270, 5, utf8_decode('Ordenes de Servicios'), 0, 1, 'C');
		$this->Ln(5);		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(20, 76, 20, 20, 15, 20, 76, 20));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'L', 'C'));
		$this->Row(array('Nro. Orden',
						 'Proveedor',
						 'F.Documento',
						 'F.Aprobado',
						 'C.Costo',
						 'Commodity',
						 utf8_decode('Descripción'),
						 'Dias Atrasados'));
		$this->Ln(1);
						
	}
	
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
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
		GROUP BY CodOrganismo, NroOrden
		ORDER BY CodOrganismo, NroOrden";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 7);	
	$pdf->Row(array($field['NroOrden'],
					utf8_decode($field['NomProveedor']),
					formatFechaDMA($field['FechaDocumento']),
					formatFechaDMA($field['FechaAprobacion']),
					$field['CodCentroCosto'],
					$field['CommoditySub'],
					utf8_decode($field['Descripcion']),
					$field['DiasAtrasados']));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
