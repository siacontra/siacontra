<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$_HOJA = "ORDEN DE PAGO";
$h = 4;
$y = 30;
//---------------------------------------------------
list($Anio, $CodOrganismo, $NroOrden) = split("[.]", $registro);
//	obtengo la informacion general de la orden de pago
$sql = "SELECT
			op.*,
			o.MontoAfecto,
			o.MontoNoAfecto,
			(o.MontoAfecto + o.MontoNoAfecto) AS MontoBruto,
			o.MontoImpuesto,
			o.MontoImpuestoOtros,
			o.MontoObligacion,
			o.ConformadoPor,
			o.FechaConformado,
			o.MontoPagoParcial,
			o.MontoAdelanto,
			mp.DocFiscal,
			b.Banco
		FROM
			ap_ordenpago op
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN mastpersonas mp ON (op.CodProveedorPagar = mp.CodPersona)
			LEFT JOIN ap_ctabancaria cb ON (op.NroCuenta = cb.NroCuenta)
			LEFT JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
		WHERE
			op.Anio = '".$Anio."' AND
			op.CodOrganismo = '".$CodOrganismo."' AND
			op.NroOrden = '".$NroOrden."'";	//echo $sql;
$query_orden = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
//---------------------------------------------------
//	obtengo las retenciones
$sql = "(SELECT
			oi.CodImpuesto AS Codigo,
			oi.FactorPorcentaje,
			oi.MontoImpuesto,
			oi.Linea,
			i.Descripcion
		 FROM
			ap_ordenpago op
			INNER JOIN ap_obligacionesimpuesto oi ON (op.CodProveedor = oi.CodProveedor AND
													  op.CodTipoDocumento = oi.CodTipoDocumento AND
													  op.NroDocumento = oi.NroDocumento)
			INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
		 WHERE
			op.Anio = '".$Anio."' AND
			op.CodOrganismo = '".$CodOrganismo."' AND
			op.NroOrden = '".$NroOrden."')
		UNION
		(SELECT
			oi.CodConcepto AS Codigo,
			oi.FactorPorcentaje,
			oi.MontoImpuesto,
			oi.Linea,
			c.Descripcion
		 FROM
			ap_ordenpago op
			INNER JOIN ap_obligacionesimpuesto oi ON (op.CodProveedor = oi.CodProveedor AND
													  op.CodTipoDocumento = oi.CodTipoDocumento AND
													  op.NroDocumento = oi.NroDocumento)
			INNER JOIN pr_concepto c ON (oi.CodConcepto = c.CodConcepto)
		 WHERE
			op.Anio = '".$Anio."' AND
			op.CodOrganismo = '".$CodOrganismo."' AND
			op.NroOrden = '".$NroOrden."')
		ORDER BY Linea";
$query_retencion = mysql_query($sql) or die($sql.mysql_error());
$rows_retencion = mysql_num_rows($query_retencion);
//---------------------------------------------------
//	obtengo la distribucion presupuestaria
$sql = "SELECT
			do.cod_partida,
			do.Monto,
			p.denominacion
		FROM
			ap_ordenpago op
			INNER JOIN ap_distribucionobligacion do ON (op.CodProveedor = do.CodProveedor AND
													  	op.CodTipoDocumento = do.CodTipoDocumento AND
													  	op.NroDocumento = do.NroDocumento)
			INNER JOIN pv_partida p ON (do.cod_partida = p.cod_partida)
		WHERE
			op.Anio = '".$Anio."' AND
			op.CodOrganismo = '".$CodOrganismo."' AND
			op.NroOrden = '".$NroOrden."'
		ORDER BY cod_partida";
$query_presupuesto = mysql_query($sql) or die($sql.mysql_error());
$rows_presupuesto = mysql_num_rows($query_presupuesto);
//---------------------------------------------------
//	obtengo la distribucion financiera
$sql = "SELECT
			opc.CodCuenta,
			opc.Monto,
			pc.Descripcion,
			pc.TipoSaldo
		FROM
			ap_ordenpagocontabilidad opc
			INNER JOIN ac_mastplancuenta pc ON (pc.CodCuenta = opc.CodCuenta)
		WHERE
			opc.Anio = '".$Anio."' AND
			opc.CodOrganismo = '".$CodOrganismo."' AND
			opc.NroOrden = '".$NroOrden."'";
$query_cuenta = mysql_query($sql) or die($sql.mysql_error());
$rows_cuenta = mysql_num_rows($query_cuenta);
//---------------------------------------------------
//	obtengo las firmas
list($_REVISADO['Nombre'], $_REVISADO['Cargo']) = getFirma($field_orden['ConformadoPor']);
list($_APROBADO['Nombre'], $_APROBADO['Cargo']) = getFirma($field_orden['AprobadoPor']);

list($_CONFORMADO['Nombre'], $_CONFORMADO['Cargo']) = getFirma($field_orden['ConformadoPor']);

$_CONFORMADO['FechaConformado'] = $field_orden['FechaConformado'];
$_REVISADO['FechaConformado'] = $field_orden['FechaConformado'];
$_APROBADO['FechaAprobado'] = $field_orden['FechaOrdenPago'];


	
//---------------------------------------------------

//---------------------------------------------------
function hoja_retencion($pdf, $query_retencion, $ln, $imprimir) {
	global $h;
	global $y;	
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(195, $h, 'RETENCIONES', 1, 0, 'C');
	$y+=$h;
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, $h, 'Cod.', 1, 0, 'C');
	$pdf->Cell(105, $h, utf8_decode('Denominación'), 1, 0, 'L');
	$pdf->Cell(30, $h, 'Partida', 1, 0, 'C');
	$pdf->Cell(15, $h, 'Tasa(%)', 1, 0, 'R');
	$pdf->Cell(30, $h, 'Monto', 1, 0, 'R');
	$y+=$h;
	$pdf->Rect(10, $y, 195, $h*$ln, "D");		
	if ($imprimir) {
		while($field_retencion = mysql_fetch_array($query_retencion)) {
			$pdf->SetXY(10, $y);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(15, $h, $field_retencion['Codigo'], 0, 0, 'C');
			$pdf->Cell(105, $h, strtoupper(substr(utf8_decode($field_retencion['Descripcion']), 0, 60)), 0, 0, 'L');
			$pdf->Cell(30, $h, $field_retencion['PartidaPresupuestal'], 0, 0, 'C');
			$pdf->Cell(15, $h, number_format($field_retencion['FactorPorcentaje'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(30, $h, number_format($field_retencion['MontoImpuesto'], 2, ',', '.'), 0, 0, 'R');
			$y+=$h;
		}

		

	} else {
		$y+=($h*2);
		$pdf->SetXY(10, $y);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(195, $h, 'VER RELACION ANEXA', 0, 0, 'C');
	}
}

function hoja_presupuesto($pdf, $query_presupuesto, $ln, $imprimir) {
	global $h;
	global $y;	
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(195, $h, 'CONTABILIDAD PRESUPUESTARIA', 1, 0, 'C');
	$y+=$h;	
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(20, $h, 'Partida', 1, 0, 'C');
	$pdf->Cell(145, $h, utf8_decode('Denominación'), 1, 0, 'L');
	$pdf->Cell(30, $h, 'Monto', 1, 0, 'R');
	$y+=$h;
	$pdf->Rect(10, $y, 195, $h*$ln, "D");		
	if ($imprimir) {
		while($field_presupuesto = mysql_fetch_array($query_presupuesto)) {
			$total_monto += $field_presupuesto['Monto'];
			$pdf->SetXY(10, $y);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(20, $h, $field_presupuesto['cod_partida'], 0, 0, 'C');
			$pdf->Cell(145, $h, strtoupper(substr($field_presupuesto['denominacion'], 0, 60)), 0, 0, 'L');
			$pdf->Cell(30, $h, number_format($field_presupuesto['Monto'], 2, ',', '.'), 0, 0, 'R');
			$y+=$h;
		}
		$pdf->SetXY(10, $y+1);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(30, $h, '', 0, 0, 'C');
		$pdf->Cell(135, $h, '', 0, 0, 'L');
		$pdf->Cell(30, $h, number_format($total_monto, 2, ',', '.'), 0, 0, 'R');
		$pdf->SetXY(10, $y+1);
		$pdf->Cell(90, $h+10, utf8_decode('MANUEL MAITA  ________________'), 0, 0, 'L');
		$pdf->Cell(10, $h+10, '__/__/____', 0, 0, 'L');
		
		$y+=$h;
	} else {
		$y+=($h*3);
		$pdf->SetXY(10, $y);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(195, $h, 'VER RELACION ANEXA', 0, 0, 'C');
	}
}

function hoja_cuenta($pdf, $query_cuenta, $ln, $imprimir) {
	global $h;
	global $y;	
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(195, $h, 'CONTABILIDAD FINANCIERA', 1, 0, 'C');
	$y+=$h;	
	$pdf->SetXY(10, $y);
	$pdf->SetXY(10, $y);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(20, $h, 'Cuenta', 1, 0, 'C');
	$pdf->Cell(115, $h, utf8_decode('Denominación'), 1, 0, 'L');
	$pdf->Cell(30, $h, 'Debe', 1, 0, 'R');
	$pdf->Cell(30, $h, 'Haber', 1, 0, 'R');
	$y+=$h;
	$pdf->Rect(10, $y, 195, $h*$ln, "D");		
	if ($imprimir) {
		while($field_cuenta = mysql_fetch_array($query_cuenta)) {
			if ($field_cuenta['Monto'] >= 0) { $debe = $field_cuenta['Monto']; $haber = 0.00; }
			else  { $haber = $field_cuenta['Monto']; $debe = 0.00; }
			
			$total_debe += $debe;
			$total_haber += $haber;
			
			$pdf->SetXY(10, $y);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(20, $h, $field_cuenta['CodCuenta'], 0, 0, 'C');
			$pdf->Cell(115, $h, strtoupper(substr(utf8_decode($field_cuenta['Descripcion']), 0, 57)), 0, 0, 'L');
			$pdf->Cell(30, $h, number_format($debe, 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(30, $h, number_format($haber, 2, ',', '.'), 0, 0, 'R');
			$y+=$h;
		}
		$pdf->SetXY(10, $y+1);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(30, $h, '', 0, 0, 'C');
		$pdf->Cell(105, $h, '', 0, 0, 'L');
		$pdf->Cell(30, $h, number_format($total_debe, 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell(30, $h, number_format($total_haber, 2, ',', '.'), 0, 0, 'R');

		$pdf->SetXY(10, $y);
		// 

		//if($_APROBADO['FechaAprobado']<='2013-07-16')
			//$nombreResponsable='CLELIMAR JOSÉ GONZÁLEZ';
		$nombreResponsable='ROSSELIS GUEVARA';

		$pdf->Cell(90, $h+10, utf8_decode($nombreResponsable.' ________________'), 0, 0, 'L');
		$pdf->Cell(10, $h+10, ' __/__/____', 0, 0, 'L');		

		$y+=$h;
	} else {
		$y+=($h*3);
		$pdf->SetXY(10, $y);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(195, $h, 'VER RELACION ANEXA', 0, 0, 'C');
	}
}

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field_orden;
		global $_HOJA;
		
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 10);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 0, 'L');		
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(165, 5); $this->Cell(20, 5, utf8_decode('Nro. Orden: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $field_orden['NroOrden'], 0, 1, 'L');		
		$this->SetFont('Arial', '', 10);
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA($field_orden['FechaOrdenPago']), 0, 1, 'L');
		$this->SetXY(165, 15); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 14);		
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode($_HOJA), 0, 1, 'C', 0);
	}
	
	//	Pie de página.
	function Footer() {
		global $_HOJA;
		global $_REVISADO;
		global $_APROBADO;
		global $_CONFORMADO;
		##
		if ($_HOJA == "ORDEN DE PAGO") {
			
			$this->SetXY(10, 232);			
			$nombreResponsable='REVISADO POR: SORIELMA SALMERON';
			$this->Cell(110,  1, utf8_decode($nombreResponsable.' ________________  '), 0, 0, 'L');
			$this->Cell(10, 1, '__/__/____', 0, 0, 'L');
			
			$this->SetTextColor(0, 0, 0);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFillColor(255, 255, 255);
			$this->SetXY(10, 237);
			$this->Rect(10, 237, 65, 35, "D"); 
			$this->Rect(75, 237, 65, 35, "D");
			$this->Rect(140, 237, 65, 35, "D");
			$this->SetFont('Arial', 'B', 9);
			$this->Cell(65, 5, 'PAGUESE', 1, 0, 'L');
			$this->Cell(65, 5, 'CONFORME', 1, 0, 'L');
			$this->Cell(65, 5, 'BENEFICIARIO', 1, 1, 'L');
			##
			$this->SetFont('Arial', 'B', 8);
			$this->SetXY(10, 242); $this->MultiCell(65, 4, 	utf8_decode($_APROBADO['Nombre']), 0, 'L');
			$this->SetXY(75, 242); $this->MultiCell(65, 4, 	utf8_decode($_CONFORMADO['Nombre']), 0, 'L');
			$this->SetXY(140, 242); $this->MultiCell(65, 4, '', 0, 'L');			
			$this->SetXY(10, 246); $this->MultiCell(65, 4, 	utf8_decode($_APROBADO['Cargo']), 0, 'C');
			$this->SetXY(75, 246); $this->MultiCell(65, 4, 	utf8_decode($_CONFORMADO['Cargo']), 0, 'C');
			$this->SetXY(140, 246); $this->MultiCell(65, 4, '', 0, 'C');
			##
			$this->SetXY(10, 263);
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(65, 4, 'FECHA: '.formatFechaDMA($_APROBADO['FechaAprobado']), 0, 0, 'L');
			$this->Cell(65, 4, 'FECHA: '.formatFechaDMA($_CONFORMADO['FechaConformado']), 0, 0, 'L');
			##
			$this->SetXY(10, 268);
			$this->SetFont('Arial', 'B', 9);
			$this->Cell(65, 4, 'FIRMA Y SELLO', 1, 0, 'C');
			$this->Cell(65, 4, 'FIRMA Y SELLO', 1, 0, 'C');
			$this->Cell(65, 4, 'FIRMA', 1, 1, 'C');
		}
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$int = (int) $field_orden['MontoTotal'];
$dec = strstr($field_orden['MontoTotal'], '.');
$int_letras = strtoupper(convertir_a_letras($int, "entero"));
$monto_letras = "$int_letras BOLIVARES CON $dec/100";
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

//	imprimo la primera parte
$pdf->SetXY(10, $y);
$pdf->Rect(10, $y, 165, $h, "D");
$pdf->Rect(175, $y, 30, $h, "D");
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(165, $h, 'BENEFICIARIO', 0, 0, 'L');
$pdf->Cell(30, $h, 'CEDULA/R.I.F.', 0, 0, 'C');
$y+=$h;

$pdf->SetXY(10, $y);
$pdf->Rect(10, $y, 165, $h, "D");
$pdf->Rect(175, $y, 30, $h, "D");
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(165, $h, utf8_decode($field_orden['NomProveedorPagar']), 0, 0, 'L');
$pdf->Cell(30, $h, $field_orden['DocFiscal'], 0, 0, 'C');
$y+=$h;

$pdf->SetXY(10, $y);
$pdf->Rect(10, $y, 195, $h*2, "D");
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(40, $h, 'POR LA CANTIDAD DE:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(155, $h, $monto_letras, 0, 'L');
$y+=$h*2;

$pdf->SetXY(10, $y);
//$pdf->Rect(10, $y, 195, $h*5, "D");
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(195, $h, 'POR CONCEPTO DE: '.utf8_decode($field_orden['Concepto']), 'LTRB', 'J');
//$y+=$h*5;
$y=$pdf->GetY();

$pdf->SetXY(10, $y);
$pdf->Rect(10, $y, 110, $h, "D");
$pdf->Rect(120, $y, 85, $h, "D");
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(15, $h, 'BANCO:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(95, $h, $field_orden['Banco'], 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(25, $h, ' NRO. CUENTA:', 0, 0, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(63, $h, $field_orden['NroCuenta'], 0, 0, 'L');
$y+=$h;

$pdf->SetXY(10, $y);
$pdf->Rect(10, $y, 195, $h+3, "D");
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(150, 8, 'MONTO BRUTO (Bs.): ', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(45, 8, number_format($field_orden['MontoBruto'], 2, ',', '.'), 0, 0, 'R');
$y+=$h+3;

//	imprimo retenciones
if ($rows_retencion > 10) {
	hoja_retencion($pdf, $query_retencion, 14, false);
	$y+=($h*11);
} else {
	hoja_retencion($pdf, $query_retencion, 14, true);
	$y+=($h*(11-$rows_retencion));
}

$pdf->SetXY(10, $y);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(165, $h, 'MONTO RETENCIONES (Bs.): ', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, $h, number_format($field_orden['MontoImpuestoOtros'], 2, ',', '.'), 0, 0, 'R');
$y+=$h;
$pdf->SetXY(10, $y);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(165, $h, 'AMORTIZACION ANTICIPO (Bs.): ', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, $h, number_format($field_orden['MontoAdelanto'], 2, ',', '.'), 0, 0, 'R');
$y+=$h;
$pdf->SetXY(10, $y);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(165, $h, 'MONTO NETO (Bs.): ', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, $h, number_format($field_orden['MontoObligacion'], 2, ',', '.'), 0, 0, 'R');
$y+=$h;

//	imprimo distribucion presupuestaria
if ($rows_presupuesto > 8) {
	hoja_presupuesto($pdf, $query_presupuesto, 11, false);
	$y+=($h*8);
} else {
	hoja_presupuesto($pdf, $query_presupuesto, 11, true);
	$y+=($h*(10-$rows_presupuesto));
}

//	imprimo contabilidad financiera
if ($rows_cuenta > 2) {
	hoja_cuenta($pdf, $query_cuenta, 9, false);
	$y+=($h*6);
} else {
	hoja_cuenta($pdf, $query_cuenta, 9, true);
	$y+=($h*(8-$rows_cuenta));
}

//	imprimo las hojas anexas
$y = 30;
if ($rows_retencion > 10) {
	$pdf->AddPage();
	$_HOJA = "RELACIÓN ANEXA";
	hoja_retencion($pdf, $query_retencion, 57, true);
}

$y = 30;
if ($rows_presupuesto > 8) {
	$pdf->AddPage();
	$_HOJA = "RELACIÓN ANEXA";
	hoja_presupuesto($pdf, $query_presupuesto, 57, true);
}

$y = 30;
if ($rows_cuenta > 2) {
	$pdf->AddPage();
	$_HOJA = "RELACIÓN ANEXA";
	hoja_cuenta($pdf, $query_cuenta, 57, true);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
