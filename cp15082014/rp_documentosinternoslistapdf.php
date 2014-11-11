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
	$this->Cell(250, 5, utf8_decode('Reporte Lista Documentos Internos'), 0, 1, 'C');
	////
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		//$this->Cell(5, 5);
		$this->Cell(19, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(7, 5,utf8_decode('AÑO'), 1, 0, 'C', 1);
		$this->Cell(18, 5,utf8_decode('T. DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(38, 5,utf8_decode('REMITENTE'), 1, 0, 'C', 1);
		$this->Cell(60, 5,utf8_decode('ASUNTO'), 1, 0, 'C', 1);
		$this->Cell(60, 5,utf8_decode('COMENTARIO'), 1, 0, 'C', 1);
		$this->Cell(18, 5,utf8_decode('F. DOCUMENTO'), 1, 0, 'C', 1);
		$this->Cell(15, 5,utf8_decode('PLAZO ATEN.'), 1, 0, 'C', 1);
		$this->Cell(13, 5,utf8_decode('ESTADO'), 1, 1, 'C', 1);
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
			   cpint.Cod_DocumentoCompleto,
			   cpint.Cod_TipoDocumento,
			   cpint.FechaRegistro,
			   cpint.Asunto,
			   cpint.Descripcion as Descp,
			   cpint.Estado,
			   cpint.PlazoAtencion,
			   cpint.Periodo,
			   cpint.CodOrganismo,
			   cptc.Descripcion,
			   mtdep.Dependencia
		from
			   cp_documentointerno cpint
			   inner join cp_tipocorrespondencia cptc on (cptc.Cod_TipoDocumento = cpint.Cod_TipoDocumento)
			   inner join mastdependencias mtdep on (mtdep.CodDependencia = cpint.Cod_Dependencia)
		where
			   cpint.CodOrganismo<>'' $filtro"; //echo $s_consulta;
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);
for($i=0; $i<$rcon; $i++){
  $fcon = mysql_fetch_array($qcon);
  	  /// ---------------------------------------------
	     if($fcon['Estado']=='PP') $estado='Preparado'; 
		 else if($fcon['Estado']=='RE') $estado='Recibido'; 
		 else if($fcon['Estado']=='AN') $estado='Anulado'; 
		 else if($fcon['Estado']=='PR') $estado='Preparación'; 
		 else if($fcon['Estado']=='EV') $estado='Enviado'; 
	 /// ---------------------------------------------
		  $contador = $contador + 1;
		  list($ano,$mes,$dia)=SPLIT('[-]', $fcon['FechaRegistro']);	
		  $fechaRegistro = $dia.'-'.$mes.'-'.$ano;
		  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		  $pdf->SetFont('Arial', '', 6);
		  $pdf->SetWidths(array(19,7,18,38,60,60,18,15,13));
		  $pdf->SetAligns(array('C','C','C','L','L','L','C','C','C'));
		  //$pdf->Cell(5,5); 
		  $pdf->Row(array($fcon['Cod_DocumentoCompleto'],$fcon['Periodo'],$fcon['Descripcion'],$fcon['Dependencia'],$fcon['Asunto'],$fcon['Descp'],$fechaRegistro,$fcon['PlazoAtencion'],utf8_decode($estado)));
  /// --------------------------------------------------------------
}
  /// --------------------------------------------------------------
  $pdf->SetFont('Arial', '', 6);
  $pdf->Cell(27,8,'Cantidad de Documentos = ');$pdf->Cell(10,8,$contador);
  /// --------------------------------------------------------------
$pdf->Output();
?>  
