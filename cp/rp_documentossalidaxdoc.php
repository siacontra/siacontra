<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();


//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Reporte Distribución por Documento'), 0, 1, 'C');	
	
	//// CONSULTA PARA MUESTRA DE INFORMACION
	list($cod_dcompleto, $cod_documento, $cod_tdocumento, $periodo)= SPLIT('[|]',$_GET['registro']);
 
	$s_con = "select * from cp_documentoextsalida where Cod_DocumentoCompleto = '$cod_dcompleto'";
    $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
    $f_con = mysql_fetch_array($q_con);
	
	//// CONSULTA PARA OBTENER EL TIPO DE DOCUMENTO
	/*$s_tdocumento = "select * from cp_tipocorrespondencia where Cod_TipoDocumento='".$f_con['Cod_TipoDocumento']."'";
	$q_tdocumento = mysql_query($s_tdocumento) or die ($s_tdocumento.mysql_error());
	$f_tdocumento = mysql_fetch_array($q_tdocumento);*/
	 
	$s_tdocumento = "select
	                       tp.Descripcion as Descripcion,
						   org.Organismo as Organismo,
						   mp.NomCompleto as Remitente,
						   rp.DescripCargo as DescripCargo
	                   from 
					       cp_tipocorrespondencia as tp,
						   pf_organismosexternos as org,
						   mastpersonas as mp,
						   rh_puestos rp
					  where 
					       tp.Cod_TipoDocumento='".$f_con['Cod_TipoDocumento']."' and
						   org.CodOrganismo = '".$f_con['CodOrganismo']."' and
						   mp.CodPersona = '".$f_con['Remitente']."' and 
						   rp.CodCargo = '".$f_con['Cargo']."'"; 
	$q_tdocumento = mysql_query($s_tdocumento) or die ($s_tdocumento.mysql_error());
	$f_tdocumento = mysql_fetch_array($q_tdocumento);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20,30); $pdf->Cell(25, 6,utf8_decode('Nro. Documento:'), 0, 0, 'L');$pdf->Cell(25, 6, $f_con['Cod_DocumentoCompleto'], 0, 0, 'L'); 
	                    $pdf->Cell(25, 5,utf8_decode('Tipo Documento:'), 0, 0, 'L');$pdf->Cell(15, 5, $f_tdocumento['Descripcion'], 0, 1, 'L');
	$pdf->SetXY(20,34); $pdf->Cell(25, 5,utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(50, 5, $f_tdocumento['Remitente'], 0, 1, 'L');	
	$pdf->SetXY(20,38); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_tdocumento['DescripCargo'], 0, 1, 'L');
	//$pdf->SetXY(20,42); $pdf->Cell(25, 5,utf8_decode('Destinatario:'), 0, 0, 'L');$pdf->Cell(50, 5, $f_tdocumento['Organismo'], 0, 1, 'L');
	//$pdf->SetXY(20,46); $pdf->Cell(25, 5, '', 0, 0, 'L');
	$pdf->Cell(50, 5, '', 0, 1, 'L');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5);
	$pdf->Cell(50, 5, 'ORGANISMO/DEPENDENCIA/PARTICULAR', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'REPRESENTANTE/EMPLEADO/PERSONA', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'CARGO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
   $s_tdocumento = "select
	                       tp.Descripcion as Descripcion,
						   org.Organismo as Organismo,
						   mp.NomCompleto as Remitente,
						   rp.DescripCargo as DescripCargo
	                   from 
					       cp_tipocorrespondencia as tp,
						   pf_organismosexternos as org,
						   mastpersonas as mp,
						   rh_puestos rp
					  where 
					       tp.Cod_TipoDocumento='".$f_con['Cod_TipoDocumento']."' and
						   org.CodOrganismo = '".$f_con['CodOrganismo']."' and
						   mp.CodPersona = '".$f_con['Remitente']."' and 
						   rp.CodCargo = '".$f_con['Cargo']."'"; 
	$q_tdocumento = mysql_query($s_tdocumento) or die ($s_tdocumento.mysql_error());
	$r_tdocumento = mysql_num_rows($q_tdocumento); 
	$f_tdocumento = mysql_fetch_array($q_tdocumento);
	
 list($cod_dcompleto, $cod_documento, $cod_tdocumento, $periodo)= SPLIT('[|]',$_GET['registro']);
 
 /// Consulta 
 $s_condist = "select * from  
                            cp_documentodistribucionext 
					   where 
					        Cod_Documento = '$cod_documento' and 
			                Periodo = '$periodo' and 
			                Cod_TipoDocumento = '$cod_tdocumento'";
 $q_condist = mysql_query($s_condist) or die ($s_condist.mysql_error());
 $r_condist = mysql_num_rows($q_condist);
 
 for($i=0;$i<$r_condist;$i++){
  $f_condist = mysql_fetch_array($q_condist);
 
 
 
 
 /// Condiciones
 if(($f_condist['FlagEsParticular']=='N')and($f_condist['Cod_Dependencia']=='')){
    $sql="select
	           DescripComp		
		    from  
			   pf_organismosexternos
	       where
	          CodOrganismo = '".$f_condist['Cod_Organismos']."'";
}else{
 if(($f_condist['FlagEsParticular']=='N')and($f_condist['Cod_Dependencia']!='')){
    $sql="select
            orgExt.DescripComp,
			depExt.Dependencia			
		from  
			pf_organismosexternos orgExt,
			pf_dependenciasexternas depExt
	   where
	        orgExt.CodOrganismo = '".$f_condist['Cod_Organismos']."' and 
			depExt.CodDependencia = '".$f_condist['Cod_Dependencia']."'";
 }else{
   if($f_condist['FlagEsParticular']=='S'){
     $sql = "select 
	              Nombre, Cargo
			  from
			      cp_particular 
			 where
			      CodParticular = '".$f_condist['Cod_Organismos']."'";
   }
 }
}


$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(50, 50, 50));
	
	if($f_condist['FlagEsParticular']=='S'){
		$pdf->SetAligns(array('C', 'L', 'L'));
		$pdf->Cell(20, 5); $pdf->Row(array('PARTICULAR', $f_condist['Representante'], $f_condist['Cargo']));
	}else{
		$pdf->SetAligns(array('L', 'L', 'L'));
	    $pdf->Cell(20, 5); $pdf->Row(array($field[0], $f_condist['Representante'], $f_condist['Cargo']));
	}
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
}
//---------------------------------------------------

$pdf->Output();
?>  
