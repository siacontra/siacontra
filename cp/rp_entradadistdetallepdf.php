<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect();
/// -------------------------------------------------
$filtro=strtr($filtro, "*", "'");
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
class PDF extends FPDF{
//Page header
function Header(){
    
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y H:i:s'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Área de Mensajería y Correspondencia'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(20, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(5,5,date('Y'),0,1,'L');
						   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(250, 5, utf8_decode('Reporte Distribución Documento'), 0, 1, 'C');
	////
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		//$this->Cell(5, 5);
		$this->Cell(19, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(23, 5,utf8_decode('N° DOCUMENTO INT'), 1, 0, 'C', 1);
		$this->Cell(14, 5,utf8_decode('F. ENVIO'), 1, 0, 'C', 1);
		$this->Cell(68, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
		$this->Cell(68, 5, 'REPRESENTANTE/EMPLEADO', 1, 0, 'C', 1);
		$this->Cell(68, 5, 'CARGO', 1, 1, 'C', 1); 
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(200,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//---------------------------------------------------
//Instanciation of inherited class
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
//---------------------------------------------------
//	Cuerpo
$scon = "select
			  cpdist.Cod_Documento,
			  cpdist.CodPersona,
			  cpdist.FechaEnvio,
			  cpdist.Estado,
			  mtper.NomCompleto,
			  rhp.DescripCargo,
			  cpext.NumeroDocumentoExt
		  from
			  cp_documentodistribucion cpdist
			  inner join mastpersonas mtper on (mtper.CodPersona = cpdist.CodPersona)
			  inner join rh_puestos rhp on (rhp.CodCargo = cpdist.CodCargo)
			  inner join cp_documentoextentrada cpext on (cpext.NumeroRegistroInt = cpdist.Cod_Documento)
		  where
			  cpdist.Procedencia='EXT' $filtro
		 order by
			  cpdist.Cod_Documento"; //echo $s_consulta;
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);

for($i=0; $i<$rcon; $i++){
  $fcon = mysql_fetch_array($qcon);
  $scon02 = "select 
				   max(Secuencia)
			   from 
				   rh_empleadonivelacion 
			  where 
				   CodPersona = '".$fcon['CodPersona']."'"; //echo $s_consulta02;
   $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());	
   $rcon02 = mysql_num_rows($qcon02); //echo $r_consulta02;
   $fcon02 = mysql_fetch_array($qcon02); 
   /// ---------------------------------------------------------------
   $scon03 = "select 
				   mastdep.CodDependencia,
				   mastdep.Dependencia
			   from 
				   rh_empleadonivelacion rhnivel
				   inner join mastdependencias mastdep on (mastdep.CodDependencia = rhnivel.CodDependencia)
			  where 
				   rhnivel.CodPersona = '".$fcon['CodPersona']."' and 
				   rhnivel.Secuencia = '".$fcon02['0']."'"; //echo $s_consulta02;
	$qcon03 = mysql_query($scon03) or die ($scon03.mysql_error());
	$rcon03 = mysql_num_rows($qcon03);
	$fcon03 = mysql_fetch_array($qcon03);
   /// --------------------------------------------------------------
  if($CodDependencia!=''){
	if($CodDependencia==$fcon03['CodDependencia']){
	  
	  list($ano,$mes,$dia)=SPLIT('[-]', $fcon['FechaEnvio']);	
	  $fechaEnvio = $dia.'-'.$mes.'-'.$ano;
	  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	  $pdf->SetFont('Arial', '', 6);
	  $pdf->SetWidths(array(19,23,14,68,68,68));
	  $pdf->SetAligns(array('C','C','C','L', 'L','L'));
	  //$pdf->Cell(5,5); 
	  $pdf->Row(array($fcon['NumeroDocumentoExt'],$fcon['Cod_Documento'],$fechaEnvio,$fcon03['Dependencia'],$fcon['NomCompleto'],$fcon['DescripCargo']));
	}
  }else{
	  list($ano,$mes,$dia)=SPLIT('[-]', $fcon['FechaEnvio']);	
	  $fechaEnvio = $dia.'-'.$mes.'-'.$ano;
	  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	  $pdf->SetFont('Arial', '', 6);
	  $pdf->SetWidths(array(19,23,14,68,68,68));
	  $pdf->SetAligns(array('C','C','C','L', 'L','L'));
	  //$pdf->Cell(5,5); 
	  $pdf->Row(array($fcon['NumeroDocumentoExt'],$fcon['Cod_Documento'],$fechaEnvio,$fcon03['Dependencia'],$fcon['NomCompleto'],$fcon['DescripCargo']));
   }
}
$pdf->Output();
?>  
