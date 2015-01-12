<?php
extract($_POST);
extract($_GET);
//$registro="0001.0000000016.1.3;0001.0000000016.2.3;0001.0000000020.1.3";
//die($registro);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
connect();
//---------------------------------------------------
$_PROVEEDORES = array();
$_GENERAL = array();
//---------------------------------------------------
//	Obtengo los proveedores de los requerimientos seleccionados.
if ($origen == "listarq") {
	list($codorganismo, $codrequerimiento) = split( '[.]', $registro);
	$filtro_proveedores .= "(c.CodOrganismo = '".$codorganismo."' AND
							 c.CodRequerimiento = '".$codrequerimiento."')";
	$filtro_items .= "(rd.CodOrganismo = '".$codorganismo."' AND
					   rd.CodRequerimiento = '".$codrequerimiento."')";
} else {
	$detalle = split(";", $registro);	$i=0;
	foreach ($detalle as $registro) {	$i++;
		list($codorganismo, $codrequerimiento, $secuencia, $numero) = split( '[.]', $registro);
		if ($i == 1) {
			$filtro_proveedores .= "(c.CodOrganismo = '".$codorganismo."' AND
									 c.CodRequerimiento = '".$codrequerimiento."' AND
									 c.Secuencia = '".$secuencia."')";
			$filtro_items .= "(rd.CodOrganismo = '".$codorganismo."' AND
							   rd.CodRequerimiento = '".$codrequerimiento."' AND
							   rd.Secuencia = '".$secuencia."')";
		} else {
			$filtro_proveedores .= "OR (c.CodOrganismo = '".$codorganismo."' AND
										c.CodRequerimiento = '".$codrequerimiento."' AND
										c.Secuencia = '".$secuencia."')";
			$filtro_items .= "OR (rd.CodOrganismo = '".$codorganismo."' AND
								  rd.CodRequerimiento = '".$codrequerimiento."' AND
								  rd.Secuencia = '".$secuencia."')";
		}
	}
}
$sql = "SELECT
			c.CotizacionNumero,
			c.NroCotizacionProv,
			c.CodProveedor,
			c.FlagAsignado,
			mp.NomCompleto AS NomProveedor,
			c.NroSolicitudCotizacion
		FROM
			lg_cotizacion c
			INNER JOIN mastpersonas mp ON (c.CodProveedor = mp.CodPersona)
		WHERE $filtro_proveedores
		GROUP BY CodProveedor
		ORDER BY CodProveedor
		LIMIT 0, 3";
$query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
$_ROWS_PROVEEDORES = 3;
$_ROWS_PROVEEDORES_REAL = mysql_num_rows($query_proveedores);
$_SUMAX = (3 - $_ROWS_PROVEEDORES) * 62;	$j=0;
while ($field_proveedores = mysql_fetch_array($query_proveedores)) {
	$_PROVEEDORES[] = $field_proveedores;
	$proveedor[$j] = $field_proveedores['NomProveedor'];	$j++;
}
//---------------------------------------------------
//	Obtengo los datos generales de la cabecera
$sql = "SELECT
			c.Numero,
			c.FechaInvitacion,
			o.Organismo
		FROM
			lg_cotizacion c
			INNER JOIN mastorganismos o ON (c.CodOrganismo = o.CodOrganismo)
		WHERE $filtro_proveedores
		GROUP BY Numero
		ORDER BY CodProveedor
		LIMIT 0, 3";
$query_general = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_general) != 0) $_GENERAL = mysql_fetch_array($query_general);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PROVEEDORES;
		$_ROWS_PROVEEDORES = 3;
		global $_SUMAX;
		global $_GENERAL;
		global $_PARAMETRO;
		
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 5, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(15, 5); $this->Cell(100, 5, $_GENERAL['Organismo'], 0, 1, 'L');
		$this->SetXY(15, 10); $this->Cell(100, 5, utf8_decode('DIVISIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');		
		$this->SetXY(230, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(60, 5, formatFechaDMA($_GENERAL['FechaInvitacion']), 0, 1, 'L');
		$this->SetXY(230, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(260, 5, 'Cuadro Comparativo de Ofertas', 0, 1, 'C');
		$this->Ln(5);
		
		$x = 82 + $_SUMAX;
		for($i=1; $i<=3; $i++) {
			$y=30;
			$this->Rect($x, $y, 62, 15, "D");
			$y+=5;
			$x+=62;
		}
		
		$x = 82 + $_SUMAX;
		foreach ($_PROVEEDORES as $_PROVEEDOR) {
			$y=30;
			if ($_PROVEEDOR['FlagAsignado'] == "S") $this->SetFont('Arial', 'B', 8);
			else $this->SetFont('Arial', '', 8);
			$this->SetXY($x, $y); $this->Cell(60, 5, utf8_decode('Cotización: '.$_PROVEEDOR['NroSolicitudCotizacion']), 0, 0, 'L'); $y+=5;
			$this->SetXY($x, $y); $this->MultiCell(60, 5, utf8_decode($_PROVEEDOR['NomProveedor']), 0, 'L'); $x+=62;
		}
		
		$this->SetXY(5, 45);
		$this->Cell(65+$_SUMAX, 5, 'Item / Commodity', 1, 0, 'L');
		$this->Cell(12, 5, 'Uni.', 1, 0, 'C');
		$this->Cell(12, 5, 'Cant.', 1, 0, 'R');
		$this->Cell(25, 5, 'Precio Unit.', 1, 0, 'R');
		$this->Cell(25, 5, 'Total', 1, 0, 'R');
		$this->Cell(12, 5, 'Cant.', 1, 0, 'R');
		$this->Cell(25, 5, 'Precio Unit.', 1, 0, 'R');
		$this->Cell(25, 5, 'Total', 1, 0, 'R');
		$this->Cell(12, 5, 'Cant.', 1, 0, 'R');
		$this->Cell(25, 5, 'Precio Unit.', 1, 0, 'R');
		$this->Cell(25, 5, 'Total', 1, 0, 'R');
		$this->Ln(7);
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetY(200);
		$y=$this->GetY();
		$this->SetXY(20, 194); $this->Cell(80, 4, 'Elaborado Por:', 0, 0, 'C');
		$this->SetXY(20, 200); $this->Cell(80, 4, utf8_decode('YENNIFFER CRISTINA MARÍN MOLINA'), 0, 1, 'C');
		$this->SetXY(20, 204); $this->Cell(80, 4, utf8_decode('ASISTENTE TEC. ADMINISTRATIVO '), 0, 1, 'C');
		##
		$this->SetY(200);
		$y=$this->GetY();
		$this->SetXY(150, 194); $this->Cell(80, 4, 'Revisado Por:', 0, 0, 'C');
		$this->SetXY(150, 200); $this->Cell(80, 4, utf8_decode('ENRIQUE LUIS FIGUEROA LARES'), 0, 1, 'C');
		$this->SetXY(150, 204); $this->Cell(80, 4, utf8_decode('DIRECTOR DE ADMINISTRACION'), 0, 1, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Legal');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
//	Obtengo los items
$sql = "SELECT 
			rd.CodItem, 
			rd.CommoditySub, 
			rd.Descripcion, 
			rd.CodUnidad,
			c.CodProveedor,
			c.Cantidad,
			c.PrecioUnit,
			c.PrecioUnitIva,
			c.ValidezOferta,
			c.DiasEntrega,
			c.Total,
			c.PrecioCantidad,
			fp.Descripcion AS NomFormaPago
		FROM
			lg_requerimientosdet rd
			INNER JOIN lg_cotizacion c ON (rd.CodOrganismo = c.CodOrganismo AND
										   rd.CodRequerimiento = c.CodRequerimiento AND
										   rd.Secuencia = c.Secuencia)
			INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor)
			LEFT JOIN mastformapago fp ON (p.CodFormaPago = fp.CodFormaPago)
		WHERE $filtro_items
		ORDER BY CodItem, CommoditySub, Descripcion, c.CodRequerimiento, c.Secuencia, CodProveedor";
$query_items = mysql_query($sql) or die ($sql.mysql_error());
$r = mysql_num_rows($query_items) / $_ROWS_PROVEEDORES_REAL;
for($i=0; $i<$r; $i++) {
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$field_items = mysql_fetch_array($query_items);
	$descripcion = $field_items['Descripcion'];
	$codunidad = $field_items['CodUnidad'];
	$validez[0] = $field_items['ValidezOferta'];
	$dias[0] = $field_items['DiasEntrega'];
	$pago[0] = utf8_decode($field_items['NomFormaPago']);
	$cantidad[0] = $field_items['Cantidad'];
	$preciounit[0] = $field_items['PrecioUnit'];
	$total[0] = $field_items['Cantidad'] * $field_items['PrecioUnit'];
	$total_iva[0] = $field_items['Total'] - $field_items['PrecioCantidad'];
	if ($_ROWS_PROVEEDORES_REAL == 2) {
		$field_items = mysql_fetch_array($query_items);
		$validez[1] = $field_items['ValidezOferta'];
		$dias[1] = $field_items['DiasEntrega'];
		$pago[1] = utf8_decode($field_items['NomFormaPago']);
		$cantidad[1] = $field_items['Cantidad'];
		$preciounit[1] = $field_items['PrecioUnit'];
		$total[1] = $field_items['Cantidad'] * $field_items['PrecioUnit'];
		$total_iva[1] = $field_items['Total'] - $field_items['PrecioCantidad'];
	}
	elseif ($_ROWS_PROVEEDORES_REAL == 3) {	
		$field_items = mysql_fetch_array($query_items);
		$validez[1] = $field_items['ValidezOferta'];
		$dias[1] = $field_items['DiasEntrega'];
		$pago[1] = utf8_decode($field_items['NomFormaPago']);
		$cantidad[1] = $field_items['Cantidad'];
		$preciounit[1] = $field_items['PrecioUnit'];
		$total[1] = $field_items['Cantidad'] * $field_items['PrecioUnit'];
		$total_iva[1] = $field_items['Total'] - $field_items['PrecioCantidad'];
		##
		$field_items = mysql_fetch_array($query_items);
		$validez[2] = $field_items['ValidezOferta'];
		$dias[2] = $field_items['DiasEntrega'];
		$pago[2] = utf8_decode($field_items['NomFormaPago']);
		$cantidad[2] = $field_items['Cantidad'];
		$preciounit[2] = $field_items['PrecioUnit'];
		$total[2] = $field_items['Cantidad'] * $field_items['PrecioUnit'];
		$total_iva[2] = $field_items['Total'] - $field_items['PrecioCantidad'];
	}
	
	$pdf->SetWidths(array(65, 12, 12, 25, 25, 12, 25, 25, 12, 25, 25));
	$pdf->SetAligns(array('L', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));	
	$pdf->Row(array(utf8_decode($field_items['Descripcion']),
					$field_items['CodUnidad'],
					number_format($cantidad[0], 2, ',', '.'),
					number_format($preciounit[0], 2, ',', '.'),
					number_format($total[0], 2, ',', '.'),
					number_format($cantidad[1], 2, ',', '.'),
					number_format($preciounit[1], 2, ',', '.'),
					number_format($total[1], 2, ',', '.'),
					number_format($cantidad[2], 2, ',', '.'),
					number_format($preciounit[2], 2, ',', '.'),
					number_format($total[2], 2, ',', '.')));	
	$sum_total[0] += $total[0];
	$sum_total[1] += $total[1];
	$sum_total[2] += $total[2];	
	$sum_total_iva[0] += $total_iva[0];
	$sum_total_iva[1] += $total_iva[1];
	$sum_total_iva[2] += $total_iva[2];
}
//---------------------------------------------------
$pdf->SetDrawColor(0, 0, 0); 
$pdf->SetFillColor(0, 0, 0);
$x = 5;
$y = $pdf->GetY() + 5;
$pdf->Rect($x, $y, 263, 0.1, "FD");
$pdf->SetY($y+2);
//---------------------------------------------------
//	Totales
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Sub-Total', 1, 0, 'L');
$pdf->Cell(25, 5, number_format($sum_total[0], 2, ',', '.'), 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, number_format($sum_total[1], 2, ',', '.'), 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, number_format($sum_total[1], 2, ',', '.'), 1, 0, 'R');
	$pdf->Cell(62, 5, number_format($sum_total[2], 2, ',', '.'), 1, 1, 'R');
}
//---------------------------------------------------
//	Impuesto
if ($sum_total_iva[0] > 0) $sum_total_iva[0] = $sum_total[0] * 0.12;
if ($sum_total_iva[1] > 0) $sum_total_iva[1] = $sum_total[1] * 0.12;
if ($sum_total_iva[2] > 0) $sum_total_iva[2] = $sum_total[2] * 0.12;
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Impuesto', 1, 0, 'L');
$pdf->Cell(25, 5, number_format($sum_total_iva[0], 2, ',', '.'), 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, number_format($sum_total_iva[1], 2, ',', '.'), 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, number_format($sum_total_iva[1], 2, ',', '.'), 1, 0, 'R');
	$pdf->Cell(62, 5, number_format($sum_total_iva[2], 2, ',', '.'), 1, 0, 'R');
}
//---------------------------------------------------
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$x = 5;
$y = $pdf->GetY() + 5;
$pdf->Rect($x, $y, 263, 0.1, "FD");
$pdf->SetY($y+2);
//---------------------------------------------------
//	total
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Total', 1, 0, 'L');
$pdf->Cell(25, 5, number_format($sum_total[0]+$sum_total_iva[0], 2, ',', '.'), 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, number_format($sum_total[1]+$sum_total_iva[1], 2, ',', '.'), 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, number_format($sum_total[1]+$sum_total_iva[1], 2, ',', '.'), 1, 0, 'R');
	$pdf->Cell(62, 5, number_format($sum_total[2]+$sum_total_iva[2], 2, ',', '.'), 1, 0, 'R');
}
//---------------------------------------------------
$pdf->SetDrawColor(0, 0, 0);

$pdf->SetFillColor(0, 0, 0);
$x = 5;
$y = $pdf->GetY() + 5;
$pdf->Rect($x, $y, 263, 0.1, "FD");
$pdf->SetY($y+2);
//---------------------------------------------------
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Validez de la Oferta', 1, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 5, $validez[0].' dias', 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, $validez[1].' dias', 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, $validez[1].' dias', 1, 0, 'R');
	$pdf->Cell(62, 5, $validez[2].' dias', 1, 0, 'R');
}
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Plazo de Entrega Ofertado', 1, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 5, $dias[0].' dias', 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, $dias[1].' dias', 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, $dias[1].' dias', 1, 0, 'R');
	$pdf->Cell(62, 5, $dias[2].' dias', 1, 0, 'R');
}
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(65+$_SUMAX+49, 5, 'Forma de Pago', 1, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(25, 5, $pago[0], 1, 0, 'R');
if ($_ROWS_PROVEEDORES == 2) $pdf->Cell(62, 5, $pago[1], 1, 0, 'R');
elseif ($_ROWS_PROVEEDORES == 3) {
	$pdf->Cell(62, 5, $pago[1], 1, 0, 'R');
	$pdf->Cell(62, 5, $pago[2], 1, 0, 'R');
}
//---------------------------------------------------
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$x = 5;
$y = $pdf->GetY() + 5;
$pdf->Rect($x, $y, 263, 0.1, "FD");
$pdf->SetY($y+2);
//---------------------------------------------------
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(265, 5, 'Proveedores que NO cotizaron: ', 1, 1, 'L');
if ($sum_total[0] == 0) $pdf->Cell(265, 5, '     - '.utf8_decode($proveedor[0]), 1, 1, 'L');
if ($sum_total[1] == 0) $pdf->Cell(265, 5, '     - '.utf8_decode($proveedor[1]), 1, 1, 'L');
if ($sum_total[2] == 0) $pdf->Cell(265, 5, '     - '.utf8_decode($proveedor[2]), 1, 1, 'L');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
