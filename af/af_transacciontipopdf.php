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
	$this->SetXY(20, 14); $this->Cell(70, 5,utf8_decode('Contraloría del Estado Sucre'), 0, 1, 'L'); 
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
	$this->Cell(200, 3, utf8_decode('Maestro Tipo Transacción Activo'), 0, 1, 'C');$this->Ln(4);
	
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 8);
	$this->Cell(25, 5, utf8_decode('Tipo Transacción'), 1, 0, 'C', 1);
	$this->Cell(20, 5, utf8_decode('Categoría'), 1, 0, 'C', 1);
	$this->Cell(25, 5, utf8_decode('Contabilidad'), 1, 0, 'C', 1);
	$this->Cell(15, 5, utf8_decode('Secuencia'), 1, 0, 'C', 1);
	$this->Cell(60, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
	$this->Cell(25, 5, utf8_decode('Cuenta Contable'), 1, 0, 'C', 1);
	$this->Cell(20, 5, utf8_decode('Monto'), 1, 1, 'C', 1);$this->Ln(2);
	
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

 
$s_trans = "select * from af_tipotransaccion where TipoTransaccion='".$registro."'";
$q_trans = mysql_query($s_trans) or die ($s_trans.mysql_error());
$r_trans = mysql_num_rows($q_trans);

if($r_trans!=0){
  for($i=0; $i<$r_trans; $i++){
    $f_trans = mysql_fetch_array($q_trans);
    
	if($f_trans['FlagAltaBaja']=='A') $accion = 'Alta'; else $accion = 'Baja';
	$pdf->SetFont('Arial','B','9'); $pdf->Cell(25, 4, utf8_decode('Transacción:'), 0, 0, 'L');
									$pdf->Cell(60, 4, $f_trans['TipoTransaccion'].' - '.utf8_decode($f_trans['Descripcion']), 0, 0, 'L');
									$pdf->Cell(50, 4, utf8_decode('Acción: ').$accion, 0, 1, 'C');
									
	$sql = "select 
	               a.TipoTransaccion,
				   a.Categoria,
				   a.Secuencia,
				   a.Descripcion,
				   a.CuentaContable,
				   a.MontoLocal,
				   b.Descripcion as DescpContabilidad 
			  from 
			       af_tipotranscuenta a
				   inner join ac_contabilidades b on (b.CodContabilidad=a.Contabilidad)
		     where 
		           a.TipoTransaccion='".$f_trans['TipoTransaccion']."'"; //echo $sql;
    $qry = mysql_query($sql) or die ($sql.mysql_error());
    $row = mysql_num_rows($qry);
	
	if($row!=0){
	  for($a=0; $a<$row; $a++){
		  $field = mysql_fetch_array($qry);
	    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont('Arial', '', 8);
	    $pdf->SetWidths(array(25, 20, 25, 15, 60, 25, 20));
	    $pdf->SetAligns(array('C', 'C', 'L', 'C', 'L', 'C', 'R'));
	    $pdf->Row(array($field['TipoTransaccion'], $field['Categoria'], $field['DescpContabilidad'], 
		                $field['Secuencia'], $field['Descripcion'], $field['CuentaContable'], 
						number_format($field['MontoLocal'],2,',','.')));
	  }
	}
  }


}

//---------------------------------------------------

$pdf->Output();
?>  
