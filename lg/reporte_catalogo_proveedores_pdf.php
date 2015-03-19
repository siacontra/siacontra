<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;
		
		$this->Image($_PATHLOGO.'encabezadopdf.jpg', 5, 5, 12, 12);	
		
		$this->SetFont('Arial', '', 6);
		$this->SetXY(15, 5); $this->Cell(100, 5, "   ".$_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 8); $this->Cell(100, 5, utf8_decode('   DIRECCIÓN DE ADMINISTRACIÓN'), 0, 1, 'L');
		$this->SetXY(15, 11); $this->Cell(100, 5, utf8_decode('   DIVISIÓN DE COMPRAS'), 0, 1, 'L');
		
		$this->SetXY(230, 5); $this->Cell(12, 5, utf8_decode('Fecha: '), 0, 0, 'L'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(230, 10); $this->Cell(12, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); $this->Cell(260, 5, utf8_decode('Catálogo de Proveedores'), 0, 1, 'C');
		$this->Ln(5);
		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 60, 20, 60, 35, 45, 35));
		$this->SetAligns(array('C', 'L', 'L', 'L', 'L', 'L', 'L'));
		$this->Row(array('Cod.',
						utf8_decode('Razón Social'),
						'RIF / NIT',
						utf8_decode('Dirección'),						
						utf8_decode('Teléfono/Fax'),
						'Contacto/Vendedor',
						'e-Mail'));
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
if ($fnacionalidad != "") $filtro .= " AND p.Nacionalidad = '".$fnacionalidad."'";
if ($ftiposervicio != "") $filtro .= " AND p.CodTipoServicio = '".$ftiposervicio."'";
if ($fingresado != ""){ 
    list ($dia,$mes,$year)=explode("-",$fingresado);
    $fingresado=$year."-".$mes."-".$dia;
	$filtro .= " AND p.FechaConstitucion = '".$fingresado."'";
}
if ($festado != "") $filtro .= " AND mp.Estado = '".$festado."'";
if (trim($fbuscar) != "") $filtro .= " AND (mp.CodPersona LIKE '%".trim($fbuscar)."%' OR mp.NomCompleto LIKE '%".trim($fbuscar)."%' OR mp.Direccion LIKE '%".trim($fbuscar)."%' OR mp.DocFiscal LIKE '%".trim($fbuscar)."%' OR mp.Telefono1 LIKE '%".trim($fbuscar)."%' OR mp.Telefono2 LIKE '%".trim($fbuscar)."%' OR mp.Fax LIKE '%".trim($fbuscar)."%' OR mp.Email LIKE '%".trim($fbuscar)."%')";

//---------------------------------------------------
//	Obtengo los datos generales de la cabecera
$sql = "SELECT
			p.*,
			mp.NomCompleto,
			mp.Direccion,
			mp.DocFiscal,
			mp.Nacionalidad,
			mp.Telefono1,
			mp.Telefono2,
			mp.Email
		FROM
			mastproveedores p
			INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
		WHERE 1 $filtro
		ORDER BY $fordenadopor";
$query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
while ($field_proveedores = mysql_fetch_array($query_proveedores)) {
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field_proveedores['CodProveedor'],
					utf8_decode($field_proveedores['NomCompleto']),
					$field_proveedores['DocFiscal'],
					utf8_decode($field_proveedores['Direccion']),
					$field_proveedores['Telefono1'].' / '.$field_proveedores['Telefono2'].' / '.$field_proveedores['Fax'],
					$field_proveedores['ContactoVendedor'],
					$field_proveedores['Email']));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
