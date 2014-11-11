<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$sql = "SELECT NomCompleto FROM mastpersonas WHERE CodPersona = '".$registro."'";
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
		$this->SetXY(300, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(300, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(340, 5, utf8_decode('HISTORIAL DEL EMPLEADO'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->Cell(280, 5, 'Empleado: '.utf8_decode($field_empleado['NomCompleto']), 0, 1, 'L');
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 55, 45, 20, 15, 35, 30, 30, 10, 35, 15, 35));
		$this->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L', 'L', 'L', 'C', 'L', 'L', 'L'));
		$this->Row(array('Fecha de Ingreso', 
						 'Dependencia', 
						 'Cargo', 
						 utf8_decode('Categoría'), 
						 'Sueldo', 
						 utf8_decode('Nómina'), 
						 'Pago', 
						 'Tipo Trabajador', 
						 'Estado', 
						 'Motivo de Cese', 
						 'Fecha de Cese', 
						 utf8_decode('Razón de Cese')));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Legal');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT * FROM rh_historial WHERE CodPersona='".$registro."' ORDER BY Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['Fingreso'], 
					utf8_decode($field['Dependencia']), 
					utf8_decode($field['Cargo']), 
					utf8_decode($field['CategoriaCargo']), 
					number_format($field['NivelSalarial'], 2, ',', '.'), 
					utf8_decode($field['TipoNomina']), 
					utf8_decode($field['TipoPago']), 
					utf8_decode($field['TipoTrabajador']), 
					$field['Estado'], 
					utf8_decode($field['MotivoCese']), 
					$field['Fegreso'], 
					utf8_decode($field['ObsCese'])));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
