<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
extract($_POST);
extract($_GET);
//---------------------------------------------------
list($Anio, $CodProveedor, $DocumentoClasificacion, $DocumentoReferencia, $NroConfirmacion) = split("[.]", $registro);
//---------------------------------------------------
//	consulto
$sql = "SELECT
			dc.Anio,
			dc.CodProveedor,
			dc.DocumentoClasificacion,
			dc.DocumentoReferencia,
			dc.TransaccionNroDocumento AS NroConfirmacion,
			dd.Cantidad AS CantidadRecibida,
			(dc.MontoAfecto + dc.MontoNoAfecto) AS MontoServicio,
			dc.MontoImpuestos,
			dc.MontoTotal,
			dc.Comentarios AS Descripcion,
			osd.FechaConfirmacion,
			os.FechaDocumento,
			os.Estado,
			os.NomProveedor,
			mp.DocFiscal,
			mp.Direccion,
			mp.Telefono1,
			fp.Descripcion AS NomFormaPago,
			d.Dependencia,
			o.Organismo,
			o.DocFiscal,
			o.Telefono1,
			o.Fax1,
			o.Direccion,
			pr.NroInscripcionSNC,
			cc.Abreviatura As NomCentroCosto,
			mp2.NomCompleto As NomConfirmadoPor
		FROM
			ap_documentos dc
			INNER JOIN ap_documentosdetalle dd ON (dd.Anio = dc.Anio AND
												   dd.CodProveedor = dc.CodProveedor AND
												   dd.DocumentoClasificacion = dc.DocumentoClasificacion AND
												   dd.DocumentoReferencia = dc.DocumentoReferencia)
			INNER JOIN lg_ordenservicio os ON (os.Anio = dc.Anio AND
											   os.CodOrganismo = dc.CodOrganismo AND
											   os.CodProveedor = dc.CodProveedor AND
											   os.NroOrden = dc.ReferenciaNroDocumento)
			INNER JOIN lg_ordenserviciodetalle osd ON (osd.Anio = os.Anio AND
											   		   osd.CodOrganismo = os.CodOrganismo AND
													   osd.NroOrden = os.NroOrden)
			INNER JOIN mastpersonas mp ON (os.CodProveedor = mp.CodPersona)
			INNER JOIN mastproveedores pr ON (mp.CodPersona = pr.CodProveedor)
			INNER JOIN mastorganismos o ON (os.CodOrganismo = o.CodOrganismo)
			INNER JOIN mastdependencias d ON (os.CodDependencia = d.CodDependencia)
			INNER JOIN mastformapago fp ON (pr.CodFormaPago = fp.CodFormaPago)
			INNER JOIN ac_mastcentrocosto cc ON (osd.CodCentroCosto = cc.CodCentroCosto)
			INNER JOIN mastpersonas mp2 ON (osd.ConfirmadoPor = mp2.CodPersona)
		WHERE
			dc.Anio = '".$Anio."' AND
			dc.CodProveedor = '".$CodProveedor."' AND
			dc.DocumentoClasificacion = '".$DocumentoClasificacion."' AND
			dc.DocumentoReferencia = '".$DocumentoReferencia."'";
$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_documento) != 0) $field_documento = mysql_fetch_array($query_documento);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field_documento;
		//	imprimo la cabecera
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 15, 11, 11, 12);	
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(25, 10); $this->Cell(195, 5, $field_documento['Organismo'], 0, 0, 'L');
		$this->SetXY(25, 13); 
		$this->Cell(30, 5, utf8_decode('R.I.F. '.$field_documento['DocFiscal']), 0, 0, 'L');
		$this->Cell(35, 5, utf8_decode('Telefono: '.$field_documento['Telefono1']), 0, 0, 'L'); 
		$this->Cell(35, 5, utf8_decode('Fax: '.$field_documento['Fax1']), 0, 0, 'L');		
		$this->SetXY(25, 16); $this->Cell(195, 5, utf8_decode($field_documento['Direccion']), 0, 0, 'L');		
		$this->SetXY(25, 19); $this->Cell(195, 5, utf8_decode(getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO['DEPLOGCXP'])), 0, 0, 'L');
		//------
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY(10, 30); 
		$this->Cell(195, 10, utf8_decode('CONFIRMACIÓN DE SERVICIOS Nº '.$field_documento['NroConfirmacion']), 0, 1, 'C');		
		//------
		$this->Ln(10);
		$this->SetFillColor(255, 255, 255);
		$this->SetX(20); $this->SetFont('Arial', 'B', 12); $this->Cell(15, 8, 'De: ', 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 12); $this->Cell(15, 8, $field_documento['NomCentroCosto'], 0, 1, 'L', 1);
		$this->SetX(20); $this->SetFont('Arial', 'B', 12); $this->Cell(15, 8, 'Para: ', 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 12); $this->Cell(15, 8, utf8_decode($field_documento['Dependencia']), 0, 1, 'L', 1);
		$this->SetX(20); $this->SetFont('Arial', 'B', 12); $this->Cell(15, 8, 'Fecha: ', 0, 0, 'L', 1);
		$this->SetFont('Arial', '', 12); $this->Cell(15, 8, formatFechaDMA($field_documento['FechaDocumento']), 0, 1, 'L', 1);
		$this->SetX(20); $this->Cell(15, 8, 'Estado: ', 0, 0, 'L', 1);
		$this->Cell(15, 8, printValoresGeneral("ESTADO-SERVICIO", $field_documento['Estado']), 0, 1, 'L', 1);			
		$this->Ln(10);			
		$this->SetX(20); 
		$this->MultiCell(175, 8, utf8_decode('Conste por el presente documento, que la '.$field_documento['Organismo'].' ha recibido los servicios de '.$field_documento['NomProveedor'].', correspondiente a '), 0, 'L', 1);			
		$this->Ln(5);			
		$this->SetDrawColor(255, 255, 255);
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', 'B', 12);
		$this->SetWidths(array(20, 5, 170));
		$this->SetAligns(array('L', 'C', 'L'));
		$this->SetX(20);
		$this->Row(array(number_format($field_documento['CantidadRecibida'], 2, ',', '.'),
						 '', 					
						 utf8_decode($field_documento['Descripcion'])));
		$this->Ln(10);
		$this->SetX(20); $this->Cell(195, 8, utf8_decode('Según Contrato de Locación de Servicios O/S '.$NroOrden), 0, 1, 'L', 1);
		$this->SetX(20); $this->Cell(195, 8, utf8_decode('Por lo tanto, solicito se proceda con el pago respectivo.'), 0, 1, 'L', 1);
		$this->Ln(10);
		$this->SetX(20); $this->Cell(60, 8, 'Monto Servicio: ', 0, 0, 'L', 1); $this->Cell(10, 8, 'Bs.', 0, 0, 'L', 1);
		$this->Cell(30, 8, number_format($field_documento['MontoServicio'], 2, ',', '.'), 0, 1, 'R', 1);
		$this->SetX(20); $this->Cell(60, 8, 'I.V.A: ', 0, 0, 'L', 1); $this->Cell(10, 8, 'Bs.', 0, 0, 'L', 1);
		$this->Cell(30, 8, number_format($field_documento['MontoImpuestos'], 2, ',', '.'), 0, 1, 'R', 1);
		$this->SetX(20); $this->Cell(60, 8, 'Monto Total a Pagar: ', 0, 0, 'L', 1); $this->Cell(10, 8, 'Bs.', 0, 0, 'L', 1);
		$this->Cell(30, 8, number_format($field_documento['MontoTotal'], 2, ',', '.'), 0, 1, 'R', 1);
	}
	//	Pie de página.
	function Footer() {
		global $field_documento;
		//------
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->Rect(115, 239, 85, 0.1, "DF");
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(125, 240); $this->Cell(65, 5, 'El presente documento fue confirmado por', 0, 0, 'C');
		$this->SetXY(125, 245); $this->Cell(65, 5, utf8_decode($field_documento['NomConfirmadoPor']), 0, 0, 'C');
		$this->SetXY(125, 250); $this->Cell(65, 5, formatFechaDMA($field_documento['FechaConfirmacion']), 0, 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 1);
//---------------------------------------------------

//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>