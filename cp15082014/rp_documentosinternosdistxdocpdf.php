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
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y H:i:s'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Área de Mensajería y Correspondencia'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(20, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(5,5,date('Y'),0,1,'L');
						   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(250, 5, utf8_decode('Reporte Distribución por Documento'), 0, 1, 'C');
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
			   cpint.Cod_Documento,
			   cpint.Cod_TipoDocumento,
			   cpint.FechaRegistro,
			   cpint.Asunto,
			   cpint.Descripcion as Descp,
			   cpint.Estado,
			   cpint.PlazoAtencion,
			   cpint.Periodo,
			   cpint.CodOrganismo,
			   cptc.Descripcion,
			   mtdep.Dependencia,
			   mtp.NomCompleto,
			   rhp.DescripCargo
		from
			   cp_documentointerno cpint
			   inner join cp_tipocorrespondencia cptc on (cptc.Cod_TipoDocumento = cpint.Cod_TipoDocumento)
			   inner join mastdependencias mtdep on (mtdep.CodDependencia = cpint.Cod_Dependencia)
			   inner join mastpersonas mtp on (mtp.CodPersona = cpint.Cod_Remitente)
			   inner join rh_puestos rhp on (rhp.CodCargo = cpint.Cod_CargoRemitente)
		where
			   cpint.CodOrganismo<>'' $filtro 
		order by 
		       FechaRegistro";
		 //echo $s_consulta;
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);
for($i=0; $i<$rcon; $i++){
  $fcon = mysql_fetch_array($qcon);
  
  /// -------------------------------------------------
    list($A,$M,$D)=SPLIT('[-]',$fcon['FechaRegistro']);$fechaRegistro = $D.'-'.$M.'-'.$A;
    list($C,$E,$F)=SPLIT('[-]',$fcon['FechaDocumento']);$fechaDocumento = $F.'-'.$E.'-'.$C;
	 
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(5,5,'',0,1,'');
	$pdf->Cell(26, 5, utf8_decode('Documento Nro:'), 0, 0, 'L'); $pdf->Cell(15, 5,$fcon['Cod_Documento'], 0, 0, 'L');		
	$pdf->Cell(27, 5, utf8_decode('Registro Interno:'), 0, 0, 'L'); $pdf->Cell(30,5,$fcon['Cod_DocumentoCompleto']);
	$pdf->Cell(27, 5, utf8_decode('Tipo Documento:'), 0, 0, 'L'); $pdf->Cell(25, 5, utf8_decode($fcon['Descripcion']), 0, 1, 'L');
	$pdf->Cell(20, 5, utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(115, 5,$fcon['NomCompleto'], 0, 1, 'L');
	$pdf->Cell(20, 5, utf8_decode('Cargo:'), 0, 0, 'L');$pdf->Cell(200, 5,$fcon['DescripCargo'], 0, 1, 'L');
	$pdf->Cell(20, 5, utf8_decode('Asunto:'), 0, 0, 'L'); $pdf->Cell(200, 5,utf8_decode($fcon['Asunto']), 0, 1, 'L');
	$pdf->Cell(29, 5, utf8_decode('Fecha Registro:'), 0, 0, 'L');$pdf->Cell(20, 5, $fechaRegistro, 0, 1, 'C');
  /// -------------------------------------------------
   $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(19, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$pdf->Cell(7, 5,utf8_decode('AÑO'), 1, 0, 'C', 1);
		$pdf->Cell(38, 5,utf8_decode('DESTINATARIO'), 1, 0, 'C', 1);
		$pdf->Cell(50, 5,utf8_decode('DEPENDENCIA'), 1, 0, 'C', 1);
		$pdf->Cell(50, 5,utf8_decode('CARGO'), 1, 0, 'C', 1);
		$pdf->Cell(60, 5,utf8_decode('COMENTARIO'), 1, 0, 'C', 1);
		$pdf->Cell(18, 5,utf8_decode('F. DISTRIBUCION'), 1, 0, 'C', 1);
		$pdf->Cell(13, 5,utf8_decode('ESTADO'), 1, 1, 'C', 1);
		
  $scon2 = "select 
                  cpd.Cod_Documento,
				  cpd.Periodo,
				  tpc.Descripcion,
				  mtp.NomCompleto,
				  mdp.Dependencia,
				  rhp.DescripCargo,
				  cpd.FechaDistribucion,
				  cpd.Estado			   
			 from 
			     cp_documentodistribucion cpd
				 inner join cp_tipocorrespondencia tpc on (tpc.Cod_TipoDocumento = cpd.Cod_TipoDocumento)
				 inner join mastpersonas mtp on (mtp.CodPersona = cpd.CodPersona)
				 inner join mastdependencias mdp on (mdp.CodDependencia = cpd.CodDependencia) 
				 inner join rh_puestos rhp on (rhp.CodCargo = cpd.CodCargo)  
		    where 
			     cpd.Cod_Documento = '".$fcon['Cod_DocumentoCompleto']."' and 
				 cpd.Cod_TipoDocumento = '".$fcon['Cod_TipoDocumento']."'";
  $qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
  $rcon2 = mysql_num_rows($qcon2); //echo $rcon2;
  for($j=0; $j<$rcon2; $j++){
	  $fcon2 = mysql_fetch_array($qcon2);
     /// ---------------------------------------------
	     if($fcon2['Estado']=='PE') $estado='Pendiente'; 
		 else if($fcon2['Estado']=='RE') $estado='Recibido'; 
		 else if($fcon2['Estado']=='AN') $estado='Anulado'; 
		 else if($fcon2['Estado']=='PR') $estado='Preparación'; 
		 else if($fcon2['Estado']=='EV') $estado='Enviado'; 
	 /// ---------------------------------------------
		  $contador = $contador + 1;
		  list($ano,$mes,$dia)=SPLIT('[-]', $fcon2['FechaDistribucion']);	
		  $fechaDistribucion = $dia.'-'.$mes.'-'.$ano;
		  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		  $pdf->SetFont('Arial', '', 6);
		  $pdf->SetWidths(array(19,7,38,50,50,60,18,13));
		  $pdf->SetAligns(array('C','C','C','L','L','C','C','C'));
		  //$pdf->Cell(5,5); 
		  $pdf->Row(array($fcon2['Cod_Documento'],$fcon2['Periodo'],$fcon2['NomCompleto'],$fcon2['Dependencia'],$fcon2['DescripCargo'],$fcon['Descp'],$fechaDistribucion,utf8_decode($estado)));
  }
}
  /// --------------------------------------------------------------
  $pdf->SetFont('Arial', '', 6);
  $pdf->Cell(27,8,'Cantidad de Documentos = ');$pdf->Cell(10,8,$contador);
  /// --------------------------------------------------------------
$pdf->Output();
?>  
