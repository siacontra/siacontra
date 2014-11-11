<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
list($CodOrganismo, $CodDocumento, $NroDocumento) = split("[.]", $registro);
//---------------------------------------------------
//	consulto la transaccion
$sql = "SELECT
			t.CodOrganismo,
			t.CodDocumento,
			t.NroDocumento,
			t.NroInterno,
			t.Comentarios,
			t.FechaDocumento,
			t.CodDocumentoReferencia,
			t.NroDocumentoReferencia,
			t.ReferenciaNroDocumento,
			t.DocumentoReferencia,
			t.CodAlmacen,
			tt.Descripcion AS NomTransaccion,
			d.Dependencia,
			o.Organismo,
			a.Descripcion AS NomAlmacen,
			p.NomCompleto AS NomRecibidoPor
		FROM
			lg_transaccion t
			INNER JOIN lg_tipotransaccion tt ON (t.CodTransaccion = tt.CodTransaccion)
			INNER JOIN mastorganismos o ON (t.CodOrganismo = o.CodOrganismo)
			INNER JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
			INNER JOIN mastdependencias d ON (t.CodDependencia = d.CodDependencia)
			LEFT JOIN mastpersonas p ON (t.RecibidoPor = p.CodPersona)
		WHERE
			t.CodOrganismo = '".$CodOrganismo."' AND
			t.CodDocumento = '".$CodDocumento."' AND
			t.NroDocumento = '".$NroDocumento."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $field;
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(200, 5, utf8_decode('Nota de Salida N° '.$field['NroInterno']), 0, 1, 'C', 0);
		$this->Ln(10);
		##	primera pagina solamente
		$this->SetFont('Arial', '', 8); $this->Cell(20, 5, utf8_decode('Transacción: '), 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8); $this->Cell(75, 5, formatFechaDMA($field['FechaDocumento']).'   '.utf8_decode($field['NomTransaccion']), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(20, 5, 'Almacen', 0, 0, 'L', 1);

		if(utf8_decode($field['CodAlmacen']) == "ALCEDA")
		{
			$nombreAlmacen = "ALCES";
		}

		$this->Cell(75, 5, utf8_decode($nombreAlmacen), 0, 0, 'L');
		$this->Ln(6);
		
		$this->SetFont('Arial', '', 8); $this->Cell(20, 5, 'Documento: ', 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8); $this->Cell(75, 5, formatFechaDMA($field['FechaDocumento']).'   '.$field['CodDocumentoReferencia'].'-'.$field['NroDocumentoReferencia'], 0, 0, 'L');
		$this->SetFont('Arial', '', 8); $this->Cell(20, 5, 'Referencia', 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8); $this->Cell(75, 5, $field['DocumentoReferencia'], 0, 0, 'L');

		$this->Ln(6);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(20, 5, 'Recibido Por', 0, 0, 'L', 1);
		$this->Cell(75, 5, utf8_decode($field['NomRecibidoPor']), 0, 0, 'L');
		$this->SetFont('Arial', '', 8); $this->Cell(20, 5, utf8_decode('Solicitado por '), 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8); $this->Cell(75, 5, utf8_decode($field['Dependencia']), 0, 0, 'L');
		$this->Ln(6);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(20, 5, 'Comentarios: ', 0, 0, 'L', 1);
		$this->MultiCell(180, 5, utf8_decode($field['Comentarios']), 0, 'L');
		$this->Ln(1);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(10, 15, 20, 90, 10, 18, 18, 18));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'C', 'R', 'R', 'R'));
		$this->Row(array('#',
						 'Codigo Interno',
						 'Item',
						 utf8_decode('Descripción'),
						 'Uni.',
						 'Cant. Requerida',
						 'Cant. Salida',
						 'Cant. Pendiente'));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		$this->Rect(20, 253, 65, 0.1, "DF");	
		$this->SetXY(20, 255); $this->Cell(65, 4, 'ALMACEN', 0, 0, 'C');
		$this->Rect(130, 253, 65, 0.1, "DF");
		$this->SetXY(130, 255); $this->Cell(65, 4, 'AUTORIZA', 0, 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 5);
$pdf->SetAutoPageBreak(5, 40);
$pdf->AddPage();
//---------------------------------------------------
$i = 0;
//	consulto los detalles
$sql = "SELECT
			td.CodItem,
			td.Descripcion,
			td.CodUnidad,
			td.CantidadPedida,
			td.CantidadRecibida,
			(td.CantidadPedida - td.CantidadRecibida) AS CantidadPendiente,
			i.CodInterno
		FROM
			lg_transacciondetalle td
			INNER JOIN lg_itemmast i ON (i.CodItem = td.CodItem)
		WHERE
			td.CodOrganismo = '".$CodOrganismo."' AND
			td.CodDocumento = '".$CodDocumento."' AND
			td.NroDocumento = '".$NroDocumento."'";
$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field_detalle = mysql_fetch_array($query_detalle)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(++$i,
					$field_detalle['CodInterno'],
					$field_detalle['CodItem'],
					utf8_decode($field_detalle['Descripcion']),
					$field_detalle['CodUnidad'],
					number_format($field_detalle['CantidadPedida'], 2, ',', '.'),
					number_format($field_detalle['CantidadRecibida'], 2, ',', '.'),
					number_format($field_detalle['CantidadPendiente'], 2, ',', '.')));
	$pdf->Ln(1);
}
##	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 200, 0.1, 'DF');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 5, utf8_decode('Nro. Items. '.$i), 0, 0, 'L', 0);
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
