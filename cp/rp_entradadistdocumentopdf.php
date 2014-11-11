<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); //echo $_SESSION["MYSQL_BD"];
/// -------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
/*$filtro1=strtr($filtro1, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");
$partida = $Partida;
$fdesde = $fd;
$fhasta = $fh;
$cont_partidas= '';
$con_veces = 0;*/
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
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
	$this->Cell(250, 5, utf8_decode('Reporte Distribución por Documento'), 0, 1, 'C');
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
$sql="SELECT 
             cpdocent.*,
			 cptco.Descripcion as DescpTipoCorresp 
	   FROM  
	         cp_documentoextentrada cpdocent
			 inner join cp_tipocorrespondencia cptco on (cptco.Cod_TipoDocumento = cpdocent.Cod_TipoDocumento)
	  WHERE  
	         CodOrganismo <>'' $filtro 
   ORDER BY 
             NumeroRegistroInt"; 
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows = mysql_num_rows($query);

if($rows!=0){
  for($i=0;$i<$rows;$i++){
	  $field = mysql_fetch_array($query);
	  
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
list($C,$E,$F)=SPLIT('[-]',$field['FechaDocumentoExt']);$fechaDocumento = $F.'-'.$E.'-'.$C;

    //$pdf->SetFont('Arial', '', 8);
 //if($field['FlagEsParticular']=='N'){
	 
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(5,5,'',0,1,'');
	$pdf->Cell(5, 5);
	$pdf->Cell(30, 5, utf8_decode('Documento Nro:'), 0, 0, 'L'); $pdf->Cell(20, 5,$field['NumeroDocumentoExt'], 0, 0, 'L');		
	$pdf->Cell(30, 5, utf8_decode('Registro Interno:'), 0, 0, 'L'); $pdf->Cell(20,5,$field['NumeroRegistroInt']);
	$pdf->Cell(30, 5, utf8_decode('Tipo Documento:'), 0, 0, 'L'); $pdf->Cell(25, 5, utf8_decode($field['DescpTipoCorresp']), 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(115, 5,$field['Remitente'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Cargo:'), 0, 0, 'L');$pdf->Cell(200, 5,$field['Cargo'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Asunto:'), 0, 0, 'L'); $pdf->Cell(200, 5,$field['Asunto'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(35, 5, utf8_decode('Fecha Documento:'), 0, 0, 'C');$pdf->Cell(20, 5, $fechaDocumento, 0, 0, 'C');$pdf->Cell(35, 5, utf8_decode('Fecha Entrada:'), 0, 0, 'C');$pdf->Cell(15, 5, $fechaIngreso, 0, 1, 'C');
	
	
	// Consulta para obtener la distribucion del documento 
	$s_consulta = "select 
	                      cpdist.*,
						  mtdep.Dependencia,
						  mtper.NomCompleto,
						  rhp.DescripCargo
				     from 
					     cp_documentodistribucion cpdist
						 inner join mastdependencias mtdep on (mtdep.CodPersona = cpdist.CodPersona)
						 inner join mastpersonas mtper on (mtper.CodPersona = cpdist.CodPersona)
						 inner join rh_puestos rhp on (rhp.CodCargo = cpdist.CodCargo)
					where 
				         cpdist.Cod_Documento = '".$field['NumeroRegistroInt']."'";
	$q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error());
	$r_consulta = mysql_num_rows($q_consulta);
	if($r_consulta!=0){
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(5, 5);
		$pdf->Cell(13, 5,utf8_decode('N° REG.INT.'), 1, 0, 'C', 1);
		$pdf->Cell(18, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
		$pdf->Cell(14, 5,utf8_decode('F. ENVIO'), 1, 0, 'C', 1);
		$pdf->Cell(70, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
		$pdf->Cell(70, 5, 'REPRESENTANTE/EMPLEADO', 1, 0, 'C', 1);
		$pdf->Cell(70, 5, 'CARGO', 1, 1, 'C', 1);
		for($a=0; $a<$r_consulta; $a++){
            $f_consulta = mysql_fetch_array($q_consulta);
			list($ano,$mes,$dia)=SPLIT('[-]',$f_consulta['FechaEnvio']);	
			$fechaEnvio = $dia.'-'.$mes.'-'.$ano;
			
		    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetWidths(array(13,18,14,70,70,70));
			$pdf->SetAligns(array('C','C','C','L', 'L','L'));
			$pdf->Cell(5,5); $pdf->Row(array($field['NumeroDocumentoExt'],$f_consulta['Cod_Documento'],$fechaEnvio,$f_consulta['Dependencia'],$f_consulta['NomCompleto'], $f_consulta['DescripCargo']));
		   	
		}
	}
 }
  }
 /*}else{
	 
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(5, 7);
	$pdf->Cell(30, 10, utf8_decode('Documento Nro:'), 0, 0, 'L'); $pdf->Cell(20, 10,$field['NumeroDocumentoExt'], 0, 0, 'L');		
	$pdf->Cell(30, 10, utf8_decode('Registro Interno:'), 0, 0, 'L'); $pdf->Cell(20,10,$field['NumeroRegistroInt']);
	$pdf->Cell(30, 10, utf8_decode('Tipo Documento:'), 0, 0, 'L'); $pdf->Cell(25, 10, utf8_decode($field['DescpTipoCorresp']), 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(115, 5,$field['Remitente'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(15, 5, utf8_decode('Cargo:'), 0, 0, 'L');$pdf->Cell(200, 5,$field['Cargo'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(20, 5, utf8_decode('Asunto:'), 0, 0, 'L'); $pdf->Cell(200, 5,$field['Asunto'], 0, 1, 'L');
	$pdf->Cell(5,5);
	$pdf->Cell(35, 5, utf8_decode('Fecha Documento:'), 0, 0, 'C');$pdf->Cell(20, 5, $fechaDocumento, 0, 0, 'C');$pdf->Cell(35, 5, utf8_decode('Fecha Entrada:'), 0, 0, 'C');$pdf->Cell(15, 5, $fechaIngreso, 0, 1, 'C');
   //$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','', 6);
	$pdf->SetWidths(array(13,18,14,60,60,60,50));
	$pdf->SetAligns(array('C','C','C','L','L','L','L'));
	$pdf->Cell(2, 5); $pdf->Row(array($field['Cod_Documento'],$field['NumeroDocumentoExt'],$fechaIngreso,'PARTICULAR','PARTICULAR', $f_con[0], $field['Cargo']));
 }
  }
}


/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(22,30); $pdf->Cell(45, 6,utf8_decode(''), 0, 0, 'L');$pdf->Cell(10, 6,'', 0, 0, 'L'); 
	                    $pdf->Cell(24, 5,utf8_decode(''), 0, 0, 'L');$pdf->Cell(15, 5, $f_tdocumento['Descripcion'], 0, 0, 'L');
						$pdf->Cell(26, 5, '', 0, 0, 'L'); $pdf->Cell(17, 5, $fechaDocumento, 0, 0, 'L');
						$pdf->Cell(27, 5, 'Fecha Reporte:', 0, 0, 'L'); $pdf->Cell(15, 5, date("d-m-Y H:i:s"), 0, 1, 'L');*/
						
	/*$pdf->SetXY(20,34); $pdf->Cell(25, 5,utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(50, 5, $f_con['Remitente'], 0, 1, 'L');	
	$pdf->SetXY(20,38); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_con['Cargo'], 0, 1, 'L');
	$pdf->SetXY(20,42); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_tdocumento['Organismo'], 0, 1, 'L');
	$pdf->SetXY(20,46); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, '', 0, 1, 'L');*/
	
	/*$pdf->SetFont('Arial', '', 8); 
	$pdf->SetXY(20,50); $pdf->Cell(25, 5, utf8_decode('Distribución:'), 0, 0, 'L'); ;$pdf->Cell(50, 5, '', 0, 1, 'L');*/
	
	/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(2, 5);
	$pdf->Cell(13, 5,utf8_decode('N° REG.INT.'), 1, 0, 'C', 1);
	$pdf->Cell(18, 5,utf8_decode('N° DOCUMENTO'), 1, 0, 'C', 1);
	$pdf->Cell(14, 5,utf8_decode('F. INGRESO'), 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'ORGANISMO/PARTICULAR', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'REPRESENTANTE/PARTICULAR', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'CARGO', 1, 1, 'C', 1);*/

/*while ($field=mysql_fetch_array($query)) {

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
}*/
 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
 $pdf->SetFont('Arial','', 8);
 $pdf->Cell(40, 8,utf8_decode('Cantidad de Documentos:'), 0, 0, 'L');$pdf->Cell(10, 8,$rows, 0, 0, 'L');
 
//---------------------------------------------------
/// --------------------------------------------------
//---------------------------------------------------
//	Imprime la cabedera del documento
/*function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Reporte Distribución por Documento'), 0, 1, 'C');	
	
	//// CONSULTA PARA MUESTRA DE INFORMACION
	$s_con = "select * from cp_documentoextentrada where NumeroRegistroInt = '".$_GET['registro']."'";
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
	$f_tdocumento = mysql_fetch_array($q_tdocumento);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20,30); $pdf->Cell(24, 6,utf8_decode('Nro. Documento:'), 0, 0, 'L');$pdf->Cell(10, 6, $f_con['Cod_Documento'], 0, 0, 'L'); 
	                    $pdf->Cell(24, 5,utf8_decode('Tipo Documento:'), 0, 0, 'L');$pdf->Cell(15, 5, $f_tdocumento['Descripcion'], 0, 0, 'L');
						$pdf->Cell(26, 5, 'Fecha Documento:', 0, 0, 'L'); $pdf->Cell(17, 5, $fechaDocumento, 0, 0, 'L');
						$pdf->Cell(23, 5, 'Fecha Registro:', 0, 0, 'L'); $pdf->Cell(15, 5, $fechaRegistro, 0, 1, 'L');
						
	$pdf->SetXY(20,34); $pdf->Cell(25, 5,utf8_decode('Remitente:'), 0, 0, 'L'); $pdf->Cell(50, 5, $f_con['Remitente'], 0, 1, 'L');	
	$pdf->SetXY(20,38); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_con['Cargo'], 0, 1, 'L');
	$pdf->SetXY(20,42); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, $f_tdocumento['Organismo'], 0, 1, 'L');
	$pdf->SetXY(20,46); $pdf->Cell(25, 5, '', 0, 0, 'L');$pdf->Cell(50, 5, '', 0, 1, 'L');
	
	$pdf->SetFont('Arial', 'B', 8); 
	$pdf->SetXY(20,50); $pdf->Cell(25, 5, utf8_decode('Distribución:'), 0, 0, 'L'); ;$pdf->Cell(50, 5, '', 0, 1, 'L');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5);
	$pdf->Cell(50, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'REPRESENTANTE/EMPLEADO', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'CARGO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="select
            mtp.NomCompleto,
			mtdep.Dependencia,
			rhp.DescripCargo			
		from  
		    cp_documentodistribucion cpdist
			inner join mastpersonas mtp on (cpdist.CodPersona = mtp.CodPersona)
			inner join mastempleado mtemp on (cpdist.CodPersona = mtemp.CodPersona)
			inner join mastdependencias mtdep on (mtemp.CodDependencia = mtdep.CodDependencia)
			inner join rh_puestos rhp on (rhp.CodCargo = cpdist.CodCargo)
	   where
	        cpdist.Cod_Documento = '".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(50, 50, 50));
	$pdf->SetAligns(array('L', 'L', 'L'));
	$pdf->Cell(20, 5); $pdf->Row(array($field[1], $field[0], $field[2]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}*/
//---------------------------------------------------

$pdf->Output();
?>  
