<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); //echo $_SESSION["MYSQL_BD"];
/// -------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
//---------------------------------------------------
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
	$this->Cell(250, 5, utf8_decode('Reporte Distribución por Documento Salida'), 0, 1, 'C');
	//// 
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
//$pdf->SetFont('Times','',12);
/// --------------------------------------------------
//	Cuerpo
$scon = "select
		   cpsal.Cod_Documento,
		   cpsal.Cod_DocumentoCompleto,
		   cpsal.Cod_TipoDocumento,
		   cpsal.FechaRegistro,
		   cpsal.Asunto,
		   cpsal.Descripcion as Descp,
		   cpsal.Estado,
		   cpsal.PlazoAtencion,
		   cpsal.Periodo,
		   cpsal.CodOrganismo,
		   cpsal.FechaDocumento,
		   cptc.Descripcion,
		   cpsal.Cargo,
		   mtdep.Dependencia,
		   mtp.NomCompleto,
		   rhp.DescripCargo
        from
          cp_documentoextsalida cpsal
          inner join cp_tipocorrespondencia cptc on (cptc.Cod_TipoDocumento = cpsal.Cod_TipoDocumento)
          inner join mastdependencias mtdep on (mtdep.CodDependencia = cpsal.Cod_Dependencia)
		  inner join mastpersonas mtp on (mtp.CodPersona = cpsal.Remitente)
		  inner join rh_puestos rhp on (rhp.CodCargo = cpsal.Cargo)
       where
          cpsal.CodOrganismo<>'' $filtro 
	order by 
	      Cod_Dependencia";
$qcon = mysql_query($scon) or die ($scon.mysql_error());
$rcon = mysql_num_rows($qcon);

if($rcon!=0){
  for($i=0;$i<$rcon;$i++){
	  $fcon = mysql_fetch_array($qcon);
  
     list($A,$M,$D)=SPLIT('[-]',$fcon['FechaRegistro']);$fechaIngreso = $D.'-'.$M.'-'.$A;
     list($C,$E,$F)=SPLIT('[-]',$fcon['FechaDocumento']);$fechaDocumento = $F.'-'.$E.'-'.$C;

    //$pdf->SetFont('Arial', '', 8);
 //if($field['FlagEsParticular']=='N'){
	 
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(5,5,'',0,1,'');
	$pdf->Cell(5, 5);
	$pdf->Cell(26, 5, utf8_decode('Documento Nro:'), 0, 0, 'L'); $pdf->Cell(15, 5,$fcon['Cod_Documento'], 0, 0, 'L');		
	$pdf->Cell(27, 5, utf8_decode('Registro Interno:'), 0, 0, 'L'); $pdf->Cell(30,5,$fcon['Cod_DocumentoCompleto']);
	$pdf->Cell(27, 5, utf8_decode('Tipo Documento:'), 0, 0, 'L'); $pdf->Cell(25, 5, utf8_decode($fcon['Descripcion']), 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(115, 5,$fcon['NomCompleto'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Cargo:'), 0, 0, 'L');$pdf->Cell(200, 5,$fcon['DescripCargo'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Asunto:'), 0, 0, 'L'); $pdf->Cell(200, 5,$fcon['Asunto'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(29, 5, utf8_decode('Fecha Documento:'), 0, 0, 'L');$pdf->Cell(20, 5, $fechaDocumento, 0, 0, 'C');$pdf->Cell(28, 5, utf8_decode('Fecha Entrada:'), 0, 0, 'C');$pdf->Cell(15, 5, $fechaIngreso, 0, 1, 'C');
	
	
	// Consulta para obtener la distribucion del documento 
	$scon2 = "select 
				 *
			 from 
				 cp_documentodistribucionext 
			where 
				 Cod_Documento = '".$fcon['Cod_Documento']."' and 
				 Cod_TipoDocumento = '".$fcon['Cod_TipoDocumento']."' and
				 CodOrganismo = '".$fcon['CodOrganismo']."' and 
				 Periodo = '".$fcon['Periodo']."'";
	$qcon2 = mysql_query($scon2) or die ($scon2.mysql_error());
	$rcon2 = mysql_num_rows($qcon2);
	if($rcon2!=0){
	  $fcon2 = mysql_fetch_array($qcon2);	
		//// Condiciones para especificar datos de particulares y organismos
		if(($fcon2['FlagEsParticular']=='N')and($fcon2['Cod_Dependencia']=='')){
		   $scon3 = "select * from pf_organismosexternos where CodOrganismo = '".$fcon2['Cod_Organismos']."'";
		   $qcon3 = mysql_query($scon3) or die ($scon3.mysql_error()); 
		}else{
		  if(($fcon2['FlagEsParticular']=='N')and($fcon2['Cod_Dependencia']!='')){
		    $scon3 = "select 
			                 pforg.Organismo,
							 pfdep.Representante,
							 pfdep.Dependencia
					    from 
					         pf_organismosexternos pforg, 
					         pf_dependenciasexternas pfdep
					    where 
						     pforg.CodOrganismo = '".$fcon2['Cod_Organismos']."' and 
							 pfdep.CodDependencia = '".$fcon2['Cod_Dependencia']."'";
		    $qcon3 = mysql_query($scon3) or die ($scon3.mysql_error()); 
		  }else{
		    $scon3 = "select * from cp_particular where CodParticular = '".$fcon2['Cod_Organismos']."'";
			$qcon3 = mysql_query($scon3) or die ($scon3.mysql_error()); 
		  }
		}
		$rcon3 = mysql_num_rows($qcon3);
		//// ---------------------------------------------------------------
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(5, 5);
		$pdf->Cell(18, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'ORGANISMO', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'REPRESENTANTE', 1, 0, 'C', 1);
		$pdf->Cell(60, 5, 'CARGO', 1, 1, 'C', 1);
		for($a=0; $a<$rcon3; $a++){
            
		  $fcon3 = mysql_fetch_array($qcon3);
		  list($ano,$mes,$dia)=SPLIT('[-]',$fcon['FechaRegistro']);	
		  $fechaRegistro = $dia.'-'.$mes.'-'.$ano;
		  if($fcon2['FlagEsParticular']=='N'){
		    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		    $pdf->SetFont('Arial', '', 6);
		    $pdf->SetWidths(array(18,60,60,60,60));
		    $pdf->SetAligns(array('C','C','C','L', 'L','L'));
		    $pdf->Cell(5,5); 
		    $pdf->Row(array($fcon['Cod_Documento'],$fcon3['Organismo'],$fcon3['Dependencia'],utf8_decode($fcon2['Representante']),$fcon2['Cargo']));
		  }else{
		    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		    $pdf->SetFont('Arial', '', 6);
		    $pdf->SetWidths(array(18,60,60,60,60));
		    $pdf->SetAligns(array('C','C','C','L', 'L','L'));
		    $pdf->Cell(5,5); 
		    $pdf->Row(array($fcon['Cod_Documento'],'Particular','Particular',utf8_decode($fcon2['Representante']),$fcon2['Cargo']));
		  }
		}
	}
 }
  }
 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial','', 8);
 $pdf->Cell(50, 8,utf8_decode('Cantidad de Documentos:'), 0, 0, 'R');$pdf->Cell(10, 8,$rcon, 0, 0, 'L');
 
//---------------------------------------------------
/// -------------------------------------------------
//---------------------------------------------------
//---------------------------------------------------

$pdf->Output();
?>  
