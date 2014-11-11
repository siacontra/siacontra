<?php



define('FPDF_FONTPATH','../nomina/font/');
require('../nomina/mc_table3.php');
require('../nomina/fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
global $Periodo;



/// -------------------------------------------------
//---------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends PDF_MC_Table
{
//Page header
function Header(){

	$this->SetFont('Times','',11);
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetWidths(array(10,160,10));
	$this->SetAligns(array('L','C','R'));
	$this->Row(array('',utf8_decode('REPORTE LISTADO DE USUARIOS Y ACCESOS') ,''));

	$this->Ln();

	$this->SetFont('Times','B',11);
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
	$this->SetWidths(array(15,70,20,20,20));
	$this->SetAligns(array('C','L','C','C','C'));
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(154,8);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' Pagina:'.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');


$pdf->AliasNbPages();
$pdf->AddPage();
//$pdf->AddPage();
////////////////////////////////////////////////////
// Parametros recibidos desde el HTML
$DEPENDENCIA= $_POST["fdependencia"];

	$pdf->SetFont('Times','',10);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(40,100));
	$pdf->SetAligns(array('L','L'));

	//////////////////////////	
	$sql_dependencia= "SELECT  mastdependencias.CodDependencia, mastdependencias.Dependencia
	FROM  mastdependencias  where  mastdependencias.CodDependencia='".$DEPENDENCIA."'";
			

	$sql_dependencia = mysql_query($sql_dependencia) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_dependencia));
	$field_dependencia = mysql_fetch_array($sql_dependencia);
		


	
	$sql= "SELECT
	u.Usuario,
	u.Clave,
	mastdependencias.Dependencia,
	mastpersonas.Ndocumento,
	mastpersonas.NomCompleto
	FROM
	mastempleado
	INNER JOIN usuarios AS u ON mastempleado.CodPersona = u.CodPersona
	INNER JOIN mastdependencias ON mastempleado.CodDependencia = mastdependencias.CodDependencia
	INNER JOIN mastpersonas ON u.CodPersona = mastpersonas.CodPersona
	where
	mastempleado.Estado='A' AND
	mastpersonas.Estado='A' AND

	mastempleado.CodDependencia='".$DEPENDENCIA."'";


	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);

	$y=40;
	//DATOS DE USUARIO

while ($field_lista = mysql_fetch_array($query_lista)) {
		

///////////////////////////////////////////////////////////////////		
if( $rows_lista >0)
{
		if( $pdf->GetY() >200) {

		$pdf->AddPage();
		}
		
		$pdf->SetFont('Times','',10);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetWidths(array(30,140));
		$pdf->SetAligns(array('L','L'));
		$pdf->SetFont('Times','B',10);
		$pdf->Row(array(utf8_decode('DEPENDENCIA:'),utf8_decode($field_lista['Dependencia']) ));
		$pdf->SetFont('Times','',10);
		$pdf->Row(array(utf8_decode('NOMBRE:'),utf8_decode($field_lista['NomCompleto'] )));
		$pdf->Row(array(utf8_decode('CEDULA:'),utf8_decode($field_lista['Ndocumento'] )));
		$pdf->Row(array(utf8_decode('USUARIO:'),utf8_decode($field_lista['Usuario'] )));
		
	//	$pdf->Row(array(utf8_decode('CLAVE:'),utf8_decode($field_lista['Clave'] )));
		
	    $pdf->Ln(5);
	
	
}// condifcional para que sean cero.

////////////////////////////////////////////////////////////////
}// 

$pdf->Output();
?>  
