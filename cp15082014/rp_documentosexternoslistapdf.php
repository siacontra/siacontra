<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect();
/// -------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$contador = 0;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
class PDF extends FPDF{
//Page header
function Header(){
    
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y H:i:s'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Área de Mensajería y Correspondencia'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(20, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(5,5,date('Y'),0,1,'L');
						   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(250, 5, utf8_decode('Reporte Lista Documentos Salida'), 0, 1, 'C');
	////
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		//$this->Cell(5, 5);
		$this->Cell(19, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(10, 5,utf8_decode('AÑO'), 1, 0, 'C', 1);
		$this->Cell(18, 5,utf8_decode('T. DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(40, 5,utf8_decode('REMITENTE'), 1, 0, 'C', 1);
		$this->Cell(60, 5,utf8_decode('ASUNTO'), 1, 0, 'C', 1);
		$this->Cell(60, 5,utf8_decode('COMENTARIO'), 1, 0, 'C', 1);
		$this->Cell(18, 5,utf8_decode('F. DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(15, 5,utf8_decode('PLAZO ATEN.'), 1, 0, 'C', 1);
		$this->Cell(10, 5,utf8_decode('ESTADO'), 1, 1, 'C', 1);
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
       cpsal.Cod_Documento,
	   cpsal.Cod_TipoDocumento,
       cpsal.FechaRegistro,
       cpsal.Asunto,
       cpsal.Descripcion as Descp,
       cpsal.Estado,
       cpsal.PlazoAtencion,
       cpsal.Periodo,
	   cpsal.CodOrganismo,
       cptc.Descripcion,
       mtdep.Dependencia
from
       cp_documentoextsalida cpsal
       inner join cp_tipocorrespondencia cptc on (cptc.Cod_TipoDocumento = cpsal.Cod_TipoDocumento)
       inner join mastdependencias mtdep on (mtdep.CodDependencia = cpsal.Cod_Dependencia)
where
       cpsal.CodOrganismo<>'' $filtro"; //echo $s_consulta;
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);
for($i=0; $i<$rcon; $i++){
  $fcon = mysql_fetch_array($qcon);
  /*$scon02 = "select 
				   max(Secuencia)
			   from 
				   rh_empleadonivelacion 
			  where 
				   CodPersona = '".$fcon['CodPersona']."'"; //echo $s_consulta02;
   $qcon02 = mysql_query($scon02) or die ($scon02.mysql_error());	
   $rcon02 = mysql_num_rows($qcon02); //echo $r_consulta02;
   $fcon02 = mysql_fetch_array($qcon02); */
  /// ---------------------------------------------------------------
  /*$scon03 = "select 
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
	$fcon03 = mysql_fetch_array($qcon03);*/
  /// -------------------------------------------------------------- 
  $scon2 = "select 
                   * 
			  from 
			       cp_documentodistribucionext 
			  where 
			       Cod_Documento = '".$fcon['Cod_Documento']."' and 
				   Cod_TipoDocumento = '".$fcon['Cod_TipoDocumento']."' and 
				   Periodo = '".$fcon['Periodo']."' and 
				   CodOrganismo = '".$fcon['CodOrganismo']."' ";
  $qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
  $rcon2 = mysql_num_rows($qcon2);
  /// --------------------------------------------------------------
  if($CodDepExt!=''){ //$pdf->Cell(10,5,$CodDepExt);
	  
    if($rcon2!=0){
      $fcon2 = mysql_fetch_array($qcon2);
	  if($CodDepExt == $fcon2['Cod_Organismos']){
		  $contador = $contador + 1;
		  list($ano,$mes,$dia)=SPLIT('[-]', $fcon['FechaRegistro']);	
		  $fechaRegistro = $dia.'-'.$mes.'-'.$ano;
		  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		  $pdf->SetFont('Arial', '', 6);
		  $pdf->SetWidths(array(19,10,18,40,60,60,18,15,10));
		  $pdf->SetAligns(array('C','C','C','L','L','L','C','C','C'));
		  //$pdf->Cell(5,5); 
		  $pdf->Row(array($fcon['Cod_Documento'],$fcon['Periodo'],$fcon['Descripcion'],$fcon['Dependencia'],$fcon['Asunto'],$fcon['Descp'],$fechaRegistro,$fcon['PlazoAtencion'],$fcon['Estado']));
      }
	}
  }else{
	 $contador = $contador + 1; 
     $fcon2 = mysql_fetch_array($qcon2);
      
	  list($ano,$mes,$dia)=SPLIT('[-]', $fcon['FechaRegistro']);	
	  $fechaRegistro = $dia.'-'.$mes.'-'.$ano;
	  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	  $pdf->SetFont('Arial', '', 6);
	  $pdf->SetWidths(array(19,10,18,40,60,60,18,15,10));
	  $pdf->SetAligns(array('C','C','C','L','L','L','C','C','C'));
	  //$pdf->Cell(5,5); 
	  $pdf->Row(array($fcon['Cod_Documento'],$fcon['Periodo'],$fcon['Descripcion'],$fcon['Dependencia'],$fcon['Asunto'],utf8_decode($fcon['Descp']),$fechaRegistro,$fcon['PlazoAtencion'],$fcon['Estado']));
  
  }
  /// --------------------------------------------------------------
}
  /// --------------------------------------------------------------
  $pdf->SetFont('Arial', '', 6);
  $pdf->Cell(27,8,'Cantidad de Documentos = ');$pdf->Cell(10,8,$contador);
  /// --------------------------------------------------------------
$pdf->Output();
?>  
