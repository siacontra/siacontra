<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
if ($coditem != "") $filtro .= "AND (i.CodItem = '".$fcoditem."') ";
if ($buscar != "") $filtro .= "AND (i.CodItem LIKE '".$fbuscar."' || i.CodInterno LIKE '".$fbuscar."' || i.Descripcion LIKE '".$fbuscar."' || i.CodUnidad LIKE '".$fbuscar."' || p.Descripcion LIKE '".$fbuscar."') ";
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
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(195, 5, utf8_decode('Inventario Valorizado'), 0, 1, 'C', 0);
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(20, 15, 70, 10, 30, 15, 15, 15, 15));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'C', 'R', 'R', 'R', 'R'));
		$this->Row(array('Item',
						 'Cod. Interno',
						 'Descripcion',
						 'Und.',
						 'Procedencia',
						 'Stock Inicial',
						 'Ingresos',
						 'Egresos',
						 'Stock Final'));
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
//	obtengo los movimientos
$sql = "SELECT
			i.CodItem,
			i.CodInterno,
			i.Descripcion,
			i.CodUnidad,
			t.Periodo,
			p.Descripcion AS NomProcedencia,
			SUM(td.CantidadRecibida) AS Ingresos,
			(SELECT SUM(td2.CantidadRecibida)
			 FROM
				lg_itemmast i2
				INNER JOIN lg_transacciondetalle td2 ON (i2.CodItem = td2.CodItem)
				INNER JOIN lg_transaccion t2 ON (td2.CodOrganismo = t2.CodOrganismo AND
												 td2.CodDocumento = t2.CodDocumento AND
												 td2.NroDocumento = t2.NroDocumento)
				INNER JOIN lg_tipotransaccion tt2 ON (t2.CodTransaccion = tt2.CodTransaccion)
			 WHERE
			 	i2.CodItem = i.CodItem AND
			 	t2.CodAlmacen = '".$falmacen."' AND
				t2.Periodo = '".$fdesde."' AND
				tt2.TipoMovimiento = 'E'
			) AS Egresos,
			(SELECT SUM(td2.CantidadRecibida)
			 FROM
				lg_itemmast i2
				INNER JOIN lg_transacciondetalle td2 ON (i2.CodItem = td2.CodItem)
				INNER JOIN lg_transaccion t2 ON (td2.CodOrganismo = t2.CodOrganismo AND
												 td2.CodDocumento = t2.CodDocumento AND
												 td2.NroDocumento = t2.NroDocumento)
				INNER JOIN lg_tipotransaccion tt2 ON (t2.CodTransaccion = tt2.CodTransaccion)
			 WHERE
			 	i2.CodItem = i.CodItem AND
			 	t2.CodAlmacen = '".$falmacen."' AND
				t2.Periodo < '".$fdesde."' AND
				tt2.TipoMovimiento = 'I'
			) AS IngresosAnterior,
			(SELECT SUM(td2.CantidadRecibida)
			 FROM
				lg_itemmast i2
				INNER JOIN lg_transacciondetalle td2 ON (i2.CodItem = td2.CodItem)
				INNER JOIN lg_transaccion t2 ON (td2.CodOrganismo = t2.CodOrganismo AND
												 td2.CodDocumento = t2.CodDocumento AND
												 td2.NroDocumento = t2.NroDocumento)
				INNER JOIN lg_tipotransaccion tt2 ON (t2.CodTransaccion = tt2.CodTransaccion)
			 WHERE
			 	i2.CodItem = i.CodItem AND
			 	t2.CodAlmacen = '".$falmacen."' AND
				t2.Periodo < '".$fdesde."' AND
				tt2.TipoMovimiento = 'E'
			) AS EgresosAnterior
		FROM
			lg_itemmast i
			INNER JOIN lg_transacciondetalle td ON (i.CodItem = td.CodItem)
			INNER JOIN lg_transaccion t ON (td.CodOrganismo = t.CodOrganismo AND
											td.CodDocumento = t.CodDocumento AND
											td.NroDocumento = t.NroDocumento)
			INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			LEFT JOIN lg_procedencias p ON (i.CodProcedencia = p.CodProcedencia)
		WHERE
			t.CodAlmacen = '".$falmacen."' AND
			t.Periodo = '".$fdesde."' AND
			tt.TipoMovimiento = 'I'
			$filtro
		GROUP BY CodItem
		ORDER BY $forden";
$query = mysql_query($sql) or die($sql.mysql_error());
while($field = mysql_fetch_array($query)) {
	$stock_inicial = $field['IngresosAnterior'] - $field['EgresosAnterior'];
	$stock_final = $stock_inicial + $field['Ingresos'] - $field['Egresos'];
	
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['CodItem'],
					$field['CodInterno'],
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					utf8_decode($field['NomProcedencia']),
					number_format($stock_inicial, 2, ',', '.'),
					number_format($field['Ingresos'], 2, ',', '.'),
					number_format($field['Egresos'], 2, ',', '.'),
					number_format($stock_final, 2, ',', '.')));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
