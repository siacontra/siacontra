<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro.=" AND (op.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodProveedor != "") $filtro.=" AND (op.CodProveedor = '".$fCodProveedor."')";
if ($fCodSistemaFuente != "") $filtro.=" AND (op.CodSistemaFuente = '".$fCodSistemaFuente."')";
if ($fCodTipoDocumento != "") $filtro.=" AND (op.CodTipoDocumento = '".$fCodTipoDocumento."')";
if ($fNroDocumento != "") $filtro.=" AND (op.NroDocumento LIKE '%".$fNroDocumento."%')";
if ($fFechaVencimientod != "" || $fFechaVencimientoh != "") {
	if ($fFechaVencimientod != "") $filtro.=" AND (op.FechaVencimiento >= '".formatFechaAMD($fFechaVencimientod)."')";
	if ($fFechaVencimientoh != "") $filtro.=" AND (op.FechaVencimiento <= '".formatFechaAMD($fFechaVencimientoh)."')";
}
if ($fCodBanco != "") {
	//$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro.= " AND (op.NroCuenta = '".$fNroCuenta."')";
}
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_sub;
		global $_POST;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(165, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);		
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('ORDENES DE PAGO PENDIENTES'), 0, 1, 'C', 0);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(5, 30, 15, 20, 20, 25, 85));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'R', 'L', 'L'));
		$this->Row(array('#',
						 'Nro. Documento',
						 'Fecha Venc.',
						 'Nro. Cuenta',
						 'Monto',
						 'Tipo de Pago',
						 'Comentarios'));
		if ($_sub == 1) {
			$this->SetWidths(array(70, 20, 25, 65));
			$this->SetAligns(array('L', 'R', 'L', 'L'));
		}
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
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
//---------------------------------------------------
//	consulto
$sql = "SELECT
			op.Anio,
			op.CodOrganismo,
			op.NroOrden,
			op.FechaVencimiento,
			op.NroPago,
			op.NomProveedorPagar,
			op.MontoTotal,
			op.CodProveedor,
			op.CodSistemaFuente,
			op.NroCuenta,
			op.CodTipoDocumento,
			op.NroDocumento,
			o.Comentarios,
			p.DocFiscal,
			tp.TipoPago,
			(SELECT SUM(op2.MontoTotal)
			 FROM ap_ordenpago op2
			 WHERE
			 	op2.CodProveedor = op.CodProveedor AND
				op2.Estado = op.Estado) AS MontoProveedor
		FROM
			ap_ordenpago op
			INNER JOIN ap_obligaciones o ON (o.CodProveedor = op.CodProveedor AND
											 o.CodTipoDocumento = op.CodTipoDocumento AND
											 o.NroDocumento = op.NroDocumento)
			INNER JOIN mastpersonas p ON (p.CodPersona = op.CodProveedor)
			INNER JOIN masttipopago tp ON (tp.CodTipoPago = op.CodTipoPago)
		WHERE 
			op.Estado = 'PE' $filtro
		ORDER BY NomProveedorPagar, CodProveedor, CodTipoDocumento, NroDocumento";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	##	imprimo proveedor
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];
		$_sub = 1;
		$i = 0;
		$pdf->Ln(3);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array(70, 20, 25, 65));
		$pdf->SetAligns(array('L', 'R', 'L', 'L'));
		$pdf->Row(array($field['NomProveedorPagar'],
						number_format($field['MontoProveedor'], 2, ',', '.'),
						'',
						'Documento. '.$field['DocFiscal']));
	}
	##	imprimo documento
	$_sub = 2;
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(5, 30, 15, 20, 20, 25, 85));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'R', 'L', 'L'));
	$pdf->Row(array(++$i,
					$field['CodSistemaFuente'].' '.$field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					formatFechaDMA($field['FechaVencimiento']),
					$field['NroCuenta'],
					number_format($field['MontoTotal'], 2, ',', '.'),
					$field['TipoPago'],
					$field['Comentarios']));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
