<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();


//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf,$cod_documento,$periodo,$CodOrganismo) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Reporte Distribución por Fecha de Registro de Documento'), 0, 1, 'C');	
	
	//// CONSULTA PARA MUESTRA DE INFORMACION
	
	$s_con = "select * from cp_documentoextentrada where  Cod_Documento = '$cod_documento' and Periodo = '$periodo' and CodOrganismo = '$CodOrganismo'";
    $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
	$r_con = mysql_num_rows($q_con);
	
	 
	
	
	//$pdf->Cell(100 , 6, $r_con, 0, 0 , 'C');
	for($i=0;$i<$r_con;$i++){ 
	    $f_con = mysql_fetch_array($q_con);
		
		list($a,$m,$d)=SPLIT('[-]', $f_con['FechaRegistro']); $fechaRegistro = $d.'-'.$m.'-'.$a;
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
		
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(20, 5);
		$pdf->Cell(50, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
		$pdf->Cell(50, 5, 'REPRESENTANTE/EMPLEADO', 1, 0, 'C', 1);
		$pdf->Cell(50, 5, 'CARGO', 1, 1, 'C', 1);
	
	
}}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
//Cabecera($pdf);
//// CONSULTA PARA MUESTRA DE INFORMACION
	list($a,$b,$c)= SPLIT('[-]', $_GET['fecha1']); $fecha1 = $c.'-'.$b.'-'.$a;
	list($d,$e,$f)= SPLIT('[-]', $_GET['fecha2']); $fecha2 = $f.'-'.$e.'-'.$d;
	
	$s_con = "select * from cp_documentoextentrada where (FechaRegistro >= '$fecha1') and (FechaRegistro <= '$fecha2')";
    $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
	$r_con = mysql_num_rows($q_con);
	//$pdf->Cell(100 , 6, $r_con, 0, 0 , 'C');
	for($i=0;$i<$r_con;$i++){ 
	    $f_con = mysql_fetch_array($q_con);
	   Cabecera($pdf,$f_con['Cod_Documento'],$f_con['Periodo'],$f_con['CodOrganismo']);
	
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
					cpdist.Cod_Documento = '".$f_con['NumeroRegistroInt']."'";
			$qry = mysql_query($sql) or die ($sql.mysql_error());
			$row = mysql_num_rows($qry);
			//$pdf->Cell(100 , 6, $row, 0, 0 , 'C');
			
		while ($field=mysql_fetch_array($qry)) {
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetWidths(array(50, 50, 50));
			$pdf->SetAligns(array('L', 'L', 'L'));
			$pdf->Cell(20, 5); $pdf->Row(array($field[1], $field[0], $field[2]));
			$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
		}
	}
	

$pdf->Output();
?>  
