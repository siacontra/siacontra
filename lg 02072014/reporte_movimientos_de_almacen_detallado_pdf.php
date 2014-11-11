<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
if ($coditem != "") $filtro .= "AND (i.CodItem = '".$fcoditem."') ";
if ($buscar != "") $filtro .= "AND (i.CodItem LIKE '".$fbuscar."' || i.CodInterno LIKE '".$fbuscar."' || i.Descripcion LIKE '".$fbuscar."') ";
if ($codlinea != "") $filtro .= "AND (i.CodLinea = '".$fcodlinea."' AND i.CodFamilia = '".$fcodfamilia."' AND i.CodSubFamilia = '".$fcodsubfamilia."') ";
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
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Transacciones por Item'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(30, 70, 30, 30, 20));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'R'));
		$this->Row(array('Fecha',
						 utf8_decode('Transacción'),
						 'Documento',
						 'Referencia',
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
$pdf->SetMargins(15, 5, 1);
$pdf->SetAutoPageBreak(1, 20);
$pdf->AddPage();
//---------------------------------------------------
//	obtengo los movimientos
$sql = "(SELECT
			i.CodItem,
			i.Descripcion,
			t.FechaDocumento,
			t.CodTransaccion,
			t.CodDocumento,
			t.NroDocumento,
			tt.Descripcion AS NomTransaccion,
			td.ReferenciaCodDocumento,
			td.ReferenciaNroDocumento,
			td.CantidadRecibida,
			'Egresos' AS Tipo
		 FROM
			lg_transaccion t
			INNER JOIN lg_transacciondetalle td ON (t.CodOrganismo = td.CodOrganismo AND
													t.CodDocumento = td.CodDocumento AND
													t.NroDocumento = td.NroDocumento)
			INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			INNER JOIN lg_itemmast i ON (td.CodItem = i.CodItem)
		 WHERE
			t.CodAlmacen = '".$falmacen."' AND
			t.Periodo >= '".$fdesde."' AND
			t.Periodo <= '".$fhasta."' AND
			tt.TipoMovimiento = 'E'
			$filtro)
		UNION
		(SELECT
			i.CodItem,
			i.Descripcion,
			t.FechaDocumento,
			t.CodTransaccion,
			t.CodDocumento,
			t.NroDocumento,
			tt.Descripcion AS NomTransaccion,
			td.ReferenciaCodDocumento,
			td.ReferenciaNroDocumento,
			td.CantidadRecibida,
			'Ingresos' AS Tipo
		 FROM
			lg_transaccion t
			INNER JOIN lg_transacciondetalle td ON (t.CodOrganismo = td.CodOrganismo AND
													t.CodDocumento = td.CodDocumento AND
													t.NroDocumento = td.NroDocumento)
			INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			INNER JOIN lg_itemmast i ON (td.CodItem = i.CodItem)
		 WHERE
			t.CodAlmacen = '".$falmacen."' AND
			t.Periodo >= '".$fdesde."' AND
			t.Periodo <= '".$fhasta."' AND
			tt.TipoMovimiento = 'I'
			$filtro)
		ORDER BY CodItem, Tipo, FechaDocumento";
$query = mysql_query($sql) or die($sql.mysql_error());	$i=0;
while($field = mysql_fetch_array($query)) {	$i++;
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	
	if ($grupo != $field['CodItem']) {
		if ($i > 1) {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(160, 5, 'Total de '.$tipo, 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 0, 1, 'R');
			$pdf->Ln(10);
			$total = 0;
		}	
		$grupo = $field['CodItem'];
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(30, 5, $field['CodItem'], 0, 0, 'L');
		$pdf->Cell(150, 5, utf8_decode($field['Descripcion']), 0, 1, 'L');		
		$tipo = "";
	}
	
	if ($tipo != $field['Tipo']) {
		if ($i > 1 && $tipo != "") {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(160, 5, 'Total de '.$tipo, 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 0, 1, 'R');
			$total = 0;
		}
		$tipo = $field['Tipo'];
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(180, 5, $field['Tipo'], 0, 1, 'L');
	}
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(formatFechaDMA($field['FechaDocumento']),
					utf8_decode($field['NomTransaccion']),
					$field['CodDocumento'].' '.$field['NroDocumento'],
					$field['ReferenciaCodDocumento'].' '.$field['ReferenciaNroDocumento'],
					number_format($field['CantidadRecibida'], 2, ',', '.')));
	$pdf->Ln(1);
	
	$total += $field['CantidadRecibida'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(160, 5, 'Total de '.$tipo, 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 0, 1, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
