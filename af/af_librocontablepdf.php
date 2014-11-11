<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
mysql_query("SET NAMES 'utf8'");
extract ($_POST);
extract ($_GET);
//global $Periodo;
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
//global $filtro;
//$Periodo = $Periodo;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo, $filtro;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 9);
	$this->SetXY(20, 10); $this->Cell(70, 5,utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'L');
	$this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L'); 
	$this->Ln(4);
	
	$this->SetXY(160, 10);$this->Cell(11,5,'Fecha: ',0,0,'L');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(160, 15);$this->Cell(11,5,utf8_decode('Página:'),0,1,'');
	//$this->SetXY(183, 20);$this->Cell(7,5,utf8_decode('Año:'),0,0,'');$this->Cell(6,5,date('Y'),0,1,'L');
						   
	list($fano, $fmes) = SPLIT('[-]',$Periodo);
    switch ($fmes) {
		case "01": $mes = Enero; break;  
		case "02": $mes = Febrero;break; 
		case "03": $mes = Marzo;break;   
		case "04": $mes = Abril;break;   
		case "05": $mes = Mayo;break;    
		case "06": $mes = Junio;break;
		case "07": $mes = Julio; break;
		case "08": $mes = Agosto; break;
		case "09": $mes = Septiembre; break;
		case "10": $mes = Octubre; break;
		case "11": $mes = Noviembre; break;
		case "12": $mes = Diciembre; break;
    }
	
	$this->Ln(6); 
	$this->SetFont('Arial', 'B', 9);
	$this->Cell(180, 3, utf8_decode('Maestro Libros Contables'), 0, 1, 'C');$this->Ln(4);
	
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 9);
	$this->Cell(15, 5);
	$this->Cell(25, 5, 'Libro Contable', 1, 0, 'C', 1);
	$this->Cell(100, 5, utf8_decode('Descripcion'), 1, 0, 'C', 1);
	$this->Cell(25, 5, 'Estado', 1, 1, 'C', 1);
	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(148,13);
    //Arial italic 8
    $this->SetFont('Arial','B',8);
    //Page number
    $this->Cell(0,9,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

 
 $sc = "select * from ac_librocontable "; //echo $sc;
 $qc =  mysql_query($sc) or die ($sc.mysql_error());
 $rc = mysql_num_rows($qc);

if($rc!=0){
  for($i=0; $i<$rc; $i++){
    $fc = mysql_fetch_array($qc);
	
	if($fc['Estado']=='A') $estado="Activo"; else $estado="Inactivo";
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(25, 100, 25));
	$pdf->SetAligns(array('C', 'L', 'C'));
	$pdf->Cell(15, 5); $pdf->Row(array($fc['CodLibroCont'], utf8_decode($fc['Descripcion']), $estado));
  }
}

$pdf->Output();
?>  
