<?php
define('FPDF_FONTPATH','font/');
require('mc_tablerp01.php');
require('fphp.php');
connect();

//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Área de Mensajería y Correspondencia'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Reporte Lista de Documentos Entrada'), 0, 1, 'C');	
	
	
	//// CONSULTA PARA MUESTRA DE INFORMACION
	/*$s_con = "SELECT CodOrganismo, Cod_Documento, Periodo FROM  cp_documentoextentrada WHERE CodOrganismo <>'' $filtro ORDER BY NumeroRegistroInt"; 
    $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
    $f_con = mysql_fetch_array($q_con);
	
	//// CONSULTA PARA OBTENER EL TIPO DE DOCUMENTO
	/*$s_tdocumento = "select * from cp_tipocorrespondencia where Cod_TipoDocumento='".$f_con['Cod_TipoDocumento']."'";
	$q_tdocumento = mysql_query($s_tdocumento) or die ($s_tdocumento.mysql_error());
	$f_tdocumento = mysql_fetch_array($q_tdocumento);*/
	 
	/*list($a,$m,$d)=SPLIT('[-]', $f_con['FechaRegistro']); $fechaRegistro = $d.'-'.$m.'-'.$a;
	list($e,$f,$g)=SPLIT('[-]', $f_con['FechaDocumentoExt']); $fechaDocumento = $g.'-'.$f.'-'.$e;
	$s_tdocumento = "select
	                       tp.Descripcion as Descripcion,
						   org.Organismo as Organismo
	                   from 
					       cp_tipocorrespondencia as tp,
						   pf_organismosexternos as org
					  where 
					       tp.Cod_TipoDocumento='".$f_con['Cod_TipoDocumento']."' and
						   org.CodOrganismo = '".$f_con['Cod_Organismos']."'"; 
	$q_tdocumento = mysql_query($s_tdocumento) or die ($s_tdocumento.mysql_error());
	$f_tdocumento = mysql_fetch_array($q_tdocumento);*/
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT 
             * 
	   FROM  
	         cp_documentoextentrada 
	  WHERE  
	         CodOrganismo <>'' $filtro 
   ORDER BY 
             NumeroRegistroInt"; 
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows = mysql_num_rows($query);

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(20,30); $pdf->Cell(45, 6,utf8_decode(''), 0, 0, 'L');$pdf->Cell(10, 6,'', 0, 0, 'L'); 
	                    $pdf->Cell(24, 5,utf8_decode(''), 0, 0, 'L');$pdf->Cell(15, 5, $f_tdocumento['Descripcion'], 0, 0, 'L');
						$pdf->Cell(26, 5, '', 0, 0, 'L'); $pdf->Cell(17, 5, $fechaDocumento, 0, 0, 'L');
						$pdf->Cell(27, 5, 'Fecha Reporte:', 0, 0, 'L'); $pdf->Cell(15, 5, date("d-m-Y H:i:s"), 0, 1, 'L');
						
	/*$pdf->SetXY(20,34); $pdf->Cell(25, 5,utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(50, 5, $f_con['Remitente'], 0, 1, 'L');	
	$pdf->SetXY(20,38); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_con['Cargo'], 0, 1, 'L');
	$pdf->SetXY(20,42); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_tdocumento['Organismo'], 0, 1, 'L');
	$pdf->SetXY(20,46); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, '', 0, 1, 'L');*/
	
	/*$pdf->SetFont('Arial', '', 8); 
	$pdf->SetXY(20,50); $pdf->Cell(25, 5, utf8_decode('Distribución:'), 0, 0, 'L'); ;$pdf->Cell(50, 5, '', 0, 1, 'L');*/
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(2, 5);
	$pdf->Cell(13, 5,utf8_decode('N° REG.INT.'), 1, 0, 'C', 1);
	$pdf->Cell(18, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
	$pdf->Cell(14, 5,utf8_decode('F. INGRESO'), 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'ORGANISMO/PARTICULAR', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'REPRESENTANTE/PARTICULAR', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'CARGO', 1, 1, 'C', 1);

while ($field=mysql_fetch_array($query)) {

if(($field['Cod_Dependencia']=='')and($field['FlagEsParticular']=='N')){
  $s_con="select
              	 pforg.Organismo as NomOrganismo		
			from  
				cp_documentoextentrada cpent
				inner join pf_organismosexternos pforg on (pforg.CodOrganismo = cpent.Cod_Organismos)
		   where
				cpent.CodOrganismo='".$field['CodOrganismo']."' and 
				cpent.Cod_Documento='".$field['Cod_Documento']."' and 
				cpent.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";  
}else{
  if(($field['Cod_Dependencia']!='')and($field['FlagEsParticular']=='N')){
  $s_con="select
              	 pforg.Organismo as NomOrganismo,
				 pfdep.Dependencia as NomDependencia		
			from  
				cp_documentoextentrada cpent
				inner join pf_organismosexternos pforg on (pforg.CodOrganismo = cpent.Cod_Organismos)
				inner join pf_dependenciasexternas pfdep on (pfdep.CodDependencia = cpent.Cod_Dependencia) 
		   where
				cpent.CodOrganismo='".$field['CodOrganismo']."' and 
				cpent.Cod_Documento='".$field['Cod_Documento']."' and 
				cpent.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
  }else{
    if($field['FlagEsParticular']=='S'){
	    $s_con="select
	              cpart.Nombre as NombParticular		
			from  
				cp_documentoextentrada cpent
				inner join cp_particular cpart on (cpart.CodParticular = cpent.Cod_Organismos)
		   where
				cpent.CodOrganismo='".$field['CodOrganismo']."' and 
				cpent.Cod_Documento='".$field['Cod_Documento']."' and 
				cpent.Cod_TipoDocumento='".$field['Cod_TipoDocumento']."'";
	}
  }
}			
$q_con=mysql_query($s_con) or die ($s_con.mysql_error());
$r_con = mysql_num_rows($q_con);
$f_con=mysql_fetch_array($q_con);

list($A,$M,$D)=SPLIT('[-]',$field['FechaRegistro']);$fechaIngreso = $D.'-'.$M.'-'.$A;
    //$pdf->SetFont('Arial', '', 8);
 if($field['FlagEsParticular']=='N'){
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(13,18,14,60,60,60,50));
	$pdf->SetAligns(array('C','C','C','L', 'L', 'L', 'L'));
	$pdf->Cell(2, 5); $pdf->Row(array($field['Cod_Documento'],$field['NumeroDocumentoExt'],$fechaIngreso,$f_con['0'], $f_con['1'], $field['Remitente'], $field['Cargo']));
	//$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
 }else{
   $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','', 6);
	$pdf->SetWidths(array(13,18,14,60,60,60,50));
	$pdf->SetAligns(array('C','C','C','L','L','L','L'));
	$pdf->Cell(2, 5); $pdf->Row(array($field['Cod_Documento'],$field['NumeroDocumentoExt'],$fechaIngreso,'PARTICULAR','PARTICULAR', $f_con[0], $field['Cargo']));
 }
}
 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial','', 8);
 $pdf->Cell(40, 8,utf8_decode('Cantidad de Documentos:'), 0, 0, 'L');$pdf->Cell(10, 8,$rows, 0, 0, 'L');
 
//---------------------------------------------------
$pdf->Output();
?>  
