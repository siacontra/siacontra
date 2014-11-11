<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$sql = "SELECT NomCompleto FROM mastpersonas WHERE CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $field;
		global $field_empleado;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $_SESSION["ORGANISMO_ACTUAL"]);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $_SESSION["ORGANISMO_ACTUAL"]);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('HISTORIAL DE NIVELACIONES'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->Cell(280, 5, 'Empleado: '.utf8_decode($field_empleado['NomCompleto']), 0, 1, 'L');
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(20, 30, 60, 55, 20, 15, 30, 50));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->Row(array('Fecha', 'Tipo', 'Dependencia', 'Cargo', utf8_decode('Categoría'), 'Sueldo', utf8_decode('Nómina'), 'Responsable'));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT * FROM rh_empleadonivelacionhistorial WHERE CodPersona='".$CodPersona."' ORDER BY Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'L', 'R', 'L', 'L'));
	$pdf->Row(array(formatFechaDMA($field['Fecha']), 
					utf8_decode($field['TipoAccion']), 
					utf8_decode($field['Dependencia']), 
					utf8_decode($field['Cargo']), 
					utf8_decode($field['CategoriaCargo']), 
					number_format($field['NivelSalarial'], 2, ',', '.'), 
					utf8_decode($field['TipoNomina']), 
					utf8_decode($field['Responsable'])));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
