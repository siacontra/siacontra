<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
connect();
//---------------------------------------------------
list($anio, $codorganismo, $nroorden) = split('[.]', $registro);
$hoja_anexa = false;
//---------------------------------------------------
//	consulto los datos de la orden
 $sql = "SELECT 
			oc.*,
			mp.DocFiscal AS DocFiscalProveedor,
			mp.Direccion,
			mp.Telefono1 AS TelProveedor,
			fp.Descripcion AS NomFormaPago,
			i.FactorPorcentaje,
			o.Organismo,
			o.DocFiscal AS DocFiscalOrganismo,
			o.Telefono1 AS TelOrganismo,
			o.Fax1,
			o.Direccion AS DireccionOrganismo,
			pr.NroInscripcionSNC
		FROM
			lg_ordencompra oc
			INNER JOIN mastpersonas mp ON (oc.CodProveedor = mp.CodPersona)
			INNER JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
			INNER JOIN mastorganismos o ON (oc.CodOrganismo = o.CodOrganismo)
			LEFT JOIN mastformapago fp ON (pr.CodFormaPago = fp.CodFormaPago)
			LEFT JOIN masttiposervicioimpuesto tsi ON (oc.CodTipoServicio = tsi.CodTipoServicio)
			LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = tsi.CodImpuesto)
		WHERE
			oc.Anio = '".$anio."' AND
			oc.CodOrganismo = '".$codorganismo."' AND
			oc.NroOrden = '".$nroorden."'";
$query_mast = mysql_query($sql) or die (mysql_error());
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field_mast;
		global $hoja_anexa;
		
		//	imprimo la cabecera
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 15, 11, 11, 12);
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(25, 10); $this->Cell(195, 5, utf8_decode($field_mast['Organismo']), 0, 0, 'L');
		$this->SetXY(25, 13); 
		$this->Cell(30, 5, ('R.I.F. '.$field_mast['DocFiscalOrganismo']), 0, 0, 'L');
		$this->Cell(35, 5, ('Telefono: '.$field_mast['TelOrganismo']), 0, 0, 'L'); 
		$this->Cell(35, 5, ('Fax: '.$field_mast['Fax1']), 0, 0, 'L');		
		$this->SetXY(25, 16); $this->Cell(195, 5, utf8_decode($field_mast['DireccionOrganismo']), 0, 0, 'L');		
		$this->SetXY(25, 19); $this->Cell(195, 5, ('DIRECCION DE ADMINISTRACION Y PRESUPUESTOS'), 0, 0, 'L');
		//------
		$this->SetFont('Arial', '', 9);
		$this->SetXY(165, 11); $this->Cell(20, 5, ('Nro. Orden: '), 0, 0, 'R');
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(20, 5, $field_mast['NroInterno']);
		$this->SetFont('Arial', '', 9);
		$this->SetXY(165, 15); $this->Cell(20, 5, ('Fecha: '), 0, 0, 'R'); 
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(20, 5, formatFechaDMA($field_mast['FechaAprobacion']));
		$this->SetFont('Arial', '', 9);
		$this->SetXY(165, 19); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(20, 5, $this->PageNo().' de {nb}');
		//------		
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY(10, 30); 
		if (!$hoja_anexa) $this->Cell(195, 10, 'ORDEN DE COMPRA', 0, 1, 'C');
		else $this->Cell(195, 10, 'LISTA DE PARTIDAS', 0, 1, 'C');
		//------
		//	imprimo los datos del proveedor (solamente en la primera hoja)
		if ($this->PageNo() == 1) {
			$this->Ln(5);
			$this->SetFillColor(245, 245, 245);
			$this->SetFont('Arial', '', 8); 
			$this->Cell(30, 5, 'Proveedor: ', 0, 0, 'L', 1);
			$this->SetFont('Arial', 'B', 8); 
			$this->Cell(95, 5, utf8_decode($field_mast['NomProveedor']), 0, 0, 'L');
			$this->SetFont('Arial', '', 8); 
			$this->Cell(30, 5, 'R.I.F: ', 0, 0, 'L', 1);
			$this->Cell(95, 5, $field_mast['DocFiscalProveedor'], 0, 0, 'L');
			$this->Ln(6);
			
			$this->Cell(30, 5, utf8_decode('Dirección: '), 0, 0, 'L', 1);
			//$this->Cell(95, 5, utf8_decode($field_mast['Direccion']), 0, 0, 'L');
			$this->MultiCell(195, 3, utf8_decode($field_mast['Direccion'].""), 0, 'J');
			$this->SetY($this->GetY());
			$this->Ln(6);
			
			$this->Cell(30, 5, ('S.N.C.: '), 0, 0, 'L', 1);
			$this->Cell(95, 5, $field_mast['NroInscripcionSNC'], 0, 0, 'L');
			$this->Ln(6);
			$this->Cell(30, 5, ('Lugar de Entrega: '), 0, 0, 'L', 1);
			$this->Cell(95, 5, utf8_decode($field_mast['Entregaren']), 0, 0, 'L');
			$this->Cell(30, 5, utf8_decode('Teléfono: '), 0, 0, 'L', 1);
			$this->Cell(95, 5, $field_mast['TelProveedor'], 0, 0, 'L');
			$this->Ln(6);
			$this->Cell(30, 5, ('Email: '), 0, 0, 'L', 1);
			$this->Cell(95, 5, utf8_decode($field_mast['Email']), 0, 0, 'L');
			$this->Cell(30, 5, ('Modalidad de Pago: '), 0, 0, 'L', 1);
			$this->Cell(95, 5, utf8_decode($field_mast['NomFormaPago']), 0, 0, 'L');
			$this->Ln(6);
			$this->Cell(195, 5, ('Especificaciones de la orden de compra: '), 0, 1, 'L', 1);
			$this->MultiCell(195, 4, utf8_decode($field_mast['Observaciones']), 0, 'J');
			$this->SetY($this->GetY());
		} else $this->Ln(5);
		//------
		if (!$hoja_anexa) {
			$this->SetDrawColor(0, 0, 0);
			$this->SetFillColor(255, 255, 255);
			$this->SetFont('Arial', '', 8);
			$this->SetWidths(array(10, 100, 15, 20, 25, 25));
			$this->SetAligns(array('C', 'L', 'C', 'R', 'R', 'R'));
			$this->Row(array('Item', 
							 utf8_decode('Descripción'), 
							 'Uni.', 
							 'Cant.', 
							 'P. Unitario', 
							 'Total'));
		}
		$this->Ln(1);
	}
	//	Pie de página.
	function Footer() {
		global $field_mast;
		//---------------------------------------------------
		/*//	obtengo la firma de aprobado por
		$sql = "SELECT
					mp.Busqueda AS Nombre,
					mp.Sexo,
					p1.DescripCargo AS Cargo,
					p2.DescripCargo AS CargoTemporal
				FROM
					mastpersonas mp
					INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
					LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
				WHERE mp.CodPersona = '".$field_mast['AprobadaPor']."'";
		$query_firma1 = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_firma1) != 0) $field_firma1 = mysql_fetch_array($query_firma1);
		##
		if ($field_firma1['CargoTemporal'] != "") { 
			$cargo_firma1 = $field_firma1['CargoTemporal'];
			$cargo_firma1 = str_replace("(A)", "(E)", $cargo_firma1);
		}
		else {
			$cargo_firma1 = $field_firma1['Cargo'];
			$cargo_firma1 = str_replace("(A)", "", $cargo_firma1); 
		}
		if ($field_firma1['Sexo'] == "F") {
			$cargo_firma1 = str_replace("JEFE", "JEFA", $cargo_firma1);
			$cargo_firma1 = str_replace("DIRECTOR", "DIRECTORA", $cargo_firma1);
		}
		//---------------------------------------------------
		//	obtengo la firma de conformado por
		$sql = "SELECT
					mp.Busqueda AS Nombre,
					mp.Sexo,
					p1.DescripCargo AS Cargo,
					p2.DescripCargo AS CargoTemporal
				FROM
					mastpersonas mp
					INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					INNER JOIN rh_puestos p1 ON (me.CodCargo = p1.CodCargo)
					LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
				WHERE mp.CodPersona = '".$field_mast['RevisadaPor']."'";
		$query_firma2 = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_firma2) != 0) $field_firma2 = mysql_fetch_array($query_firma2);
		##
		if ($field_firma2['CargoTemporal'] != "") { 
			$cargo_firma2 = $field_firma2['CargoTemporal'];
			$cargo_firma2 = str_replace("(A)", "(E)", $cargo_firma2);
		}
		else {
			$cargo_firma2 = $field_firma2['Cargo'];
			$cargo_firma2 = str_replace("(A)", "", $cargo_firma2); 
		}
		if ($field_firma2['Sexo'] == "F") {
			$cargo_firma2 = str_replace("JEFE", "JEFA", $cargo_firma2);
			$cargo_firma2 = str_replace("DIRECTOR", "DIRECTORA", $cargo_firma2);
		}*/
		//---------------------------------------------------
		//	obtengo las firmas
		list($_REVISADO['Nombre'], $_REVISADO['Cargo']) = getFirma($field_mast['RevisadaPor']);
		list($_APROBADO['Nombre'], $_APROBADO['Cargo']) = getFirma($field_mast['AprobadaPor']);
		//---------------------------------------------------
		$this->SetXY(10, -40);		
		$y = $this->GetY();
		$this->SetXY(10, $y);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->Rect(10, $y, 195, 30, "DF");		
		$this->Rect(75, $y, 0.1, 30, "DF");		
		$this->Rect(145, $y, 0.1, 30, "DF");
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(10, 240); $this->Cell(65, 5, 'APROBADO POR', 0, 0, 'L'); 
		$this->SetXY(75, 240); $this->Cell(65, 5, 'CONFORMADO POR', 0, 0, 'L'); 
		$this->SetXY(145, 240); $this->Cell(65, 5, 'PROVEEDOR', 0, 0, 'L');
		$this->SetXY(10, 244); $this->MultiCell(65, 5, substr($_APROBADO['Nombre'], 0, 35), 0, 'L'); 
		$this->SetXY(75, 244); $this->MultiCell(65, 5, substr($_REVISADO['Nombre'], 0, 35), 0, 'L'); 
		$this->SetXY(145, 244); $this->Cell(65, 5, '', 0, 0, 'L');
		$this->SetXY(10, 252); $this->MultiCell(65, 5, substr($_APROBADO['Cargo'], 0, 35), 0, 'C');
		$this->SetXY(75, 252); $this->MultiCell(65, 5, substr($_REVISADO['Cargo'], 0, 35), 0, 'C'); 
		$this->SetXY(145, 252); $this->Cell(65, 5, '', 0, 0, 'L');
		$this->SetFont('Arial', 'B', 6);
		$this->SetXY(10, 265); $this->Cell(65, 5, 'Fecha:             /                 /', 0, 0, 'L'); 
		$this->SetXY(75, 265); $this->Cell(65, 5, 'Fecha:             /                 /', 0, 0, 'L'); 
		$this->SetXY(145, 265); $this->Cell(65, 5, 'Fecha:            /                 /', 0, 0, 'L'); 
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 38);
//----

//----
//	imprimo los detalles de la orden
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$sql = "SELECT *
		FROM lg_ordencompradetalle
		WHERE
			CodOrganismo = '".$codorganismo."' AND
			NroOrden = '".$nroorden."'";
$query_detalle = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field_detalle = mysql_fetch_array($query_detalle)) {	$i++;
	$pdf->Row(array($i, 
					utf8_decode($field_detalle['Descripcion']), 
					$field_detalle['CodUnidad'], 
					number_format($field_detalle['CantidadPedida'], 2, ',', '.'), 
					number_format($field_detalle['PrecioUnit'], 2, ',', '.'), 
					number_format($field_detalle['PrecioCantidad'], 2, ',', '.')));
}

//	imprimo totales
if ($pdf->GetY() < 200) $y = 200;
else {
	$pdf->AddPage();
	$y = $pdf->GetY();
}
$pdf->SetXY(10, $y);
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
$pdf->Rect(10, $y, 195, 0.1, "DF");
//----
$tipo_impuesto = "(".$field_mast['CodTipoServicio']." ".number_format($field_mast['FactorPorcentaje'], 2, ',', '.')." %): ";
$monto_total_en_letras = convertir_a_letras($field_mast['MontoTotal'], "moneda");

$pdf->SetFillColor(245, 245, 245);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, $y+2); $pdf->Cell(120, 5, ('Monto total en letras: '), 0, 1, 'L', 1);
$pdf->SetXY(10, $y+7); $pdf->MultiCell(120, 4, utf8_decode((strtoupper($monto_total_en_letras))), 0, 'J');

$pdf->SetXY(135, $y+2); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(43, 5, 'Monto Afecto: ', 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($field_mast['MontoAfecto'], 2, ',', '.'), 0, 0, 'R');

$pdf->SetXY(135, $y+8);
$pdf->Cell(43, 5, 'Monto No Afecto: ', 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($field_mast['MontoNoAfecto'], 2, ',', '.'), 0, 0, 'R');

$pdf->SetXY(135, $y+14);
$pdf->Cell(43, 5, $tipo_impuesto, 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($field_mast['MontoIGV'], 2, ',', '.'), 0, 0, 'R');

$pdf->SetXY(135, $y+20);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(43, 5, 'Monto Total: ', 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($field_mast['MontoTotal'], 2, ',', '.'), 0, 0, 'R');
//----

//----
//	consulto las partidas de la orden
$_PARAMETRO = PARAMETROS('IVADEFAULT');
$sql = "(SELECT 
			cod_partida, 
			denominacion,
			'".$field_mast['MontoIGV']."' AS MontoPartida
		FROM pv_partida
		WHERE cod_partida = '".$_PARAMETRO['IVADEFAULT']."'
		GROUP BY cod_partida)
		
		UNION
		
		(SELECT 
			ocd.cod_partida, 
			p.denominacion,
			SUM(ocd.PrecioCantidad) AS MontoPartida
		FROM 
			lg_ordencompradetalle ocd
			INNER JOIN pv_partida p ON (ocd.cod_partida = p.cod_partida)
		WHERE
			CodOrganismo = '".$codorganismo."' AND
			NroOrden = '".$nroorden."'
		GROUP BY cod_partida)
		
		ORDER BY cod_partida";
$query_partida = mysql_query($sql) or die ($sql.mysql_error());	$i=0; $total_partida = 0;
$rows_partida = mysql_num_rows($query_partida);
//	imprimo hoja anexa
$hoja_anexa = true; 
$pdf->AddPage(); 
$y = $pdf->GetY();

$pdf->SetXY(10, $y);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(20, 150, 25));
$pdf->SetAligns(array('C', 'L', 'R'));
$pdf->Row(array('Partida', 
				utf8_decode('Descripción'), 
				'Monto'));
$pdf->SetDrawColor(255, 255, 255);
$pdf->Ln(1);
//----
//	imprimo las partidas de la orden
while ($field_partida = mysql_fetch_array($query_partida)) {	$i++;
	$total_partida += $field_partida['MontoPartida'];
	
	$pdf->Row(array($field_partida['cod_partida'],
					utf8_decode($field_partida['denominacion']),
					number_format($field_partida['MontoPartida'], 2, ',', '.')));
}
//----
$y = $pdf->GetY(); //	$y+=38;
$pdf->SetXY(10, $y);
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
$pdf->Rect(10, $y, 195, 0.1, "DF");
//----
$pdf->SetXY(135, $y+1);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(43, 5, 'Total: ', 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($total_partida, 2, ',', '.'), 0, 0, 'R');
//----

//----
//	consulto las cuentas contables de la orden
$_PARAMETRO = PARAMETROS('IVACTADEF');
$sql = "(SELECT 
			CodCuenta, 
			Descripcion,
			'".$field_mast['MontoIGV']."' AS Monto
		FROM ac_mastplancuenta
		WHERE CodCuenta = '".$_PARAMETRO['IVACTADEF']."'
		GROUP BY CodCuenta)
		
		UNION
		
		(SELECT 
			ocd.CodCuenta, 
			c.Descripcion,
			SUM(ocd.PrecioCantidad) AS Monto
		FROM 
			lg_ordencompradetalle ocd
			INNER JOIN ac_mastplancuenta c ON (ocd.CodCuenta = c.CodCuenta)
		WHERE
			ocd.CodOrganismo = '".$codorganismo."' AND
			ocd.NroOrden = '".$nroorden."'
		GROUP BY CodCuenta)
		
		ORDER BY CodCuenta";
$query_cuenta = mysql_query($sql) or die ($sql.mysql_error());
$rows_cuenta = mysql_num_rows($query_cuenta);
//	imprimo hoja anexa
$y = $pdf->GetY() + 20;

$pdf->SetXY(10, $y);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(195, 20, 'LISTA DE CUENTAS CONTABLES', 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(20, 150, 25));
$pdf->SetAligns(array('C', 'L', 'R'));
$pdf->Row(array('Cuenta', 
				utf8_decode('Descripción'), 
				'Monto'));
$pdf->SetDrawColor(255, 255, 255);
$pdf->Ln(1);
//----
//	imprimo las partidas de la orden
while ($field_cuenta = mysql_fetch_array($query_cuenta)) {	$i++;
	$total_cuenta += $field_cuenta['Monto'];
	
	$pdf->Row(array($field_cuenta['CodCuenta'],
					utf8_decode($field_cuenta['Descripcion']),
					number_format($field_cuenta['Monto'], 2, ',', '.')));
}
//----
$y = $pdf->GetY();
$pdf->SetXY(10, $y);
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
$pdf->Rect(10, $y, 195, 0.1, "DF");
//----
$pdf->SetXY(135, $y+1);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(43, 5, 'Total: ', 0, 0, 'R', 1);
$pdf->Cell(27, 5, number_format($total_cuenta, 2, ',', '.'), 0, 0, 'R');
//----
//---------------------------------------------------

$pdf->Output();
?> 
