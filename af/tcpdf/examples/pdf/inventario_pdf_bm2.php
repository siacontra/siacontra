<?php

//include('remplace1.php');
/*
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
$tabla1=$_POST['tabla'];

 
 $tabla=nl2br($tabla);
$tabla=remplace($tabla1);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);


// add a page
$pdf->AddPage();
*/
// ----------------------------------------------------------------------------
/*   
$tbl = <<<EOD

<table  border="1" width="99%">
		<tr>
		<td width="98" align="left"><br /><img src="../images/logo_nuevo1.jpg" /></td>
		<td width="67%"><div align="center"><b><br /><br />RELACI&Oacute;N DEL MOVIMIENTO DE BIENES MUEBLES</b></div></td>
		<td width="120" align="center"><b><br /><br />FORMULARIO MB-2</b></td>
		</tr>
</table>




EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
*/
// -----------------------------------------------------------------------------



// -----------------------------------------------------------------------------

// Table with rowspans and THEAD
/*$tbl = $inventario;
//<<<EOD


EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
*/
// -----------------------------------------------------------------------------

// NON-BREAKING TABLE (nobr="true")

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_04.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
