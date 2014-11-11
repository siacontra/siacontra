<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

?>

<?php

define('FPDF_FONTPATH','font/');



$codigo=$_REQUEST['registro'];



require('mc_table.php');

require('fphp.php');

include("../clases/MySQL.php");



include("../comunes/objConexion.php");

ob_end_clean();











$query="SELECT a.`co_credito_adicional`,a.`CodOrganismo`,a.`nu_oficio`,a.`ff_oficio`,

a.`nu_decreto`,a.`ff_decreto`,a.`tx_motivo`,a.`ff_ejecucion`,a.`ff_creacion`,

a.`tx_estatus`,a.`tx_preparado`,a.`tx_aprobado`,a.`tx_ultima_modificacion`,

a.`ff_ultima_modoficacion`,a.`mm_monto_total`



FROM `pv_credito_adicional` as a, `pv_item_credito_adicional` as b 



WHERE 

       a.`co_credito_adicional`=b.`co_credito_adicional` AND

	   a.`co_credito_adicional`=".$codigo;

	   

	   		$objConexion->ejecutarQuery($query);

$resp = $objConexion->getMatrizCompleta();



//---------------------------------------------------

//	Imprime la cabedera del documento

function Cabecera($pdf) {

	$pdf->AddPage();

	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	

	$pdf->SetFont('Arial', 'B', 8);

	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');

	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Administración'), 0, 1, 'L');	

	$pdf->SetFont('Arial', 'B', 10);

	$pdf->Cell(190, 10, 'CREDITO ADICIOINAL', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', 'B', 6);

	$pdf->Cell(10, 5, utf8_decode('AÑO'), 1, 0, 'C', 1);// AÑO

	$pdf->Cell(20, 5, utf8_decode('FECHA APROBADO'), 1, 0, 'C', 1);	// FECHA APROBADO

	$pdf->Cell(15, 5, utf8_decode('Nro OFICIO'), 1, 0, 'C', 1);//

	$pdf->Cell(15, 5, utf8_decode('Nro DECRETO	'), 1, 0, 'C', 1);//Nro DECRETO	

	$pdf->Cell(20, 5, utf8_decode('MONTO'), 1, 0, 'R', 1);//MONTO

	$pdf->Cell(15, 5, utf8_decode('ESTATUS'), 1, 0, 'C', 1);//ESTATUS	

	$pdf->Cell(100, 5, utf8_decode('MOTIVO DEL PROCESO'), 1, 0, 'C', 1);//MOTIVO DEL PROCESO

	

	

}



//---------------------------------------------------







function Cuerpo($pdf,$sec,$pro,$spro,$proy,$act) {

	//$pdf->AddPage();

	

	$pdf->SetFont('Arial', 'B', 6);

	//$pdf->SetXY(20, 100); $pdf->Cell(190, 5,utf8_decode( ''), 0, 1, 'L');

	//$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode(''), 0, 1, 'L');	

	//$pdf->SetFont('Arial', 'B', 6);


	$pdf->SetXY(10, 50);$pdf->Cell(20, 5, utf8_decode('SECTOR:'), 0, 0, 'L', 0);// AÑO
	$pdf->SetXY(10, 53);$pdf->Cell(20, 5, utf8_decode('PROGRAMA:'), 0, 0, 'L', 0);	// FECHA APROBADO
	$pdf->SetXY(10, 56);$pdf->Cell(20, 5, utf8_decode('SUBPROGRAMA: '), 0, 0, 'L', 0);// AÑO
	$pdf->SetXY(10, 59);$pdf->Cell(20, 5, utf8_decode('PROYECTO:'), 0, 0, 'L', 0);	// FECHA APROBADO
	$pdf->SetXY(10, 62);$pdf->Cell(20, 5, utf8_decode('ACTIVIDAD:'), 0, 0, 'L', 0);	// FECHA APROBADO
	
	
	$pdf->SetFont('Arial', '', 6);
	
	
	$pdf->SetXY(30, 50);$pdf->Cell(20, 5, utf8_decode($sec), 0, 0, 'L', 0);// AÑO
	$pdf->SetXY(30, 53);$pdf->Cell(20, 5, utf8_decode($pro), 0, 0, 'L', 0);	// FECHA APROBADO
	$pdf->SetXY(30, 56);$pdf->Cell(20, 5, utf8_decode($spro), 0, 0, 'L', 0);// AÑO
	$pdf->SetXY(30, 59);$pdf->Cell(20, 5, utf8_decode($proy), 0, 0, 'L', 0);	// FECHA APROBADO
	$pdf->SetXY(30, 62);$pdf->Cell(20, 5, utf8_decode($act), 0, 0, 'L', 0);	// FECHA APROBADO
	







	/*SECTOR:
01
01
PROGRAMA:
01
01
SUBPROGRAMA:
01
00
PROYECTO:
01
00
ACTIVIDAD:
01
052
UNIDAD EJECUTORA:
CONTRALORIA DEL ESTADO SUCRE
*/


	$pdf->Cell(100, 5, '', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

$pdf->SetWidths(array(25,50, 25));	

	$pdf->Cell(25, 5, utf8_decode('Partida'), 1, 0, 'l', 1);// AÑO
	$pdf->Cell(50, 5, utf8_decode('Descripción'), 1, 0, 'l', 1);// AÑO
	$pdf->Cell(25, 5, utf8_decode('Monto'), 1, 0, 'R', 1);	// FECHA APROBADO				

}



//---------------------------------------------------

//	Creación del objeto de la clase heredada

$pdf=new PDF_MC_Table();

$pdf->Open();

Cabecera($pdf);









//	Cuerpo









$query="SELECT a.`co_credito_adicional`,a.`CodOrganismo`,a.`nu_oficio`,a.`ff_oficio`,

a.`nu_decreto`,a.`ff_decreto`,a.`tx_motivo`,a.`ff_ejecucion`,a.`ff_creacion`,

a.`tx_estatus`,a.`tx_preparado`,a.`tx_aprobado`,a.`tx_ultima_modificacion`,

a.`ff_ultima_modoficacion`,a.`mm_monto_total`



FROM `pv_credito_adicional` as a, `pv_item_credito_adicional` as b 



WHERE 

       a.`co_credito_adicional`=b.`co_credito_adicional` AND

	   a.`co_credito_adicional`=".$codigo;

	   

	   		$objConexion->ejecutarQuery($query);

$resp = $objConexion->getMatrizCompleta();

	  

	$pdf->Row(array('','','','','','',''));// para que no se salga de la tabla

	







	

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', '', 6);

$pdf->SetWidths(array(10,20,15,15,20,15,100));

$pdf->SetAligns(array('C', 'C','C', 'C','R', 'C', 'L'));

$pdf->Row(array($resp[0]['ff_ejecucion'], $resp[0]['ff_decreto'], $resp[0]['nu_oficio'], $resp[0]['nu_decreto'], number_format($resp[0]['mm_monto_total'],2,',','.'), 'APROBADO', $resp[0]['tx_motivo']));

$y=$pdf->GetY();

if ($y==270) Cabecera($pdf);

	











	   

$query2="SELECT b.co_item_credito_adicional, b.co_credito_adicional, b.cod_partida, b.mm_monto, b.cod_partida, c.descripcion, d.descp_programa, e.descp_subprog, f.descp_proyecto, g.descp_actividad, h.denominacion
FROM pv_credito_adicional AS a, pv_item_credito_adicional AS b, pv_sector AS c, pv_programa1 AS d, pv_subprog1 AS e, pv_proyecto1 AS f, pv_actividad1 AS g, pv_partida AS h
WHERE a.co_credito_adicional = b.co_credito_adicional
AND h.cod_partida = b.cod_partida
AND b.`co_credito_adicional` =$codigo";





		$objConexion->ejecutarQuery($query2);

$resp2 = $objConexion->getMatrizCompleta();

Cuerpo($pdf,$resp2[0]['descripcion'],$resp2[0]['descp_programa'],$resp2[0]['descp_subprog'],$resp2[0]['descp_proyecto'],$resp2[0]['descp_actividad']);



	// Cuerpo($pdf2);

	$pdf->Cell(100, 5, '', 0, 1, 'C');
	/*$pdf->Cell(10, 5, 'Sector:', 0, 0, 'C');*/


for($i=0; $i<count($resp2);$i++) {

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', '', 6);

	$pdf->SetWidths(array(25,50, 25));

	$pdf->SetAligns(array('L', 'L', 'R'));

	$pdf->Row(array($resp2[$i]['cod_partida'],$resp2[$i]['denominacion'], number_format($resp2[$i]['mm_monto'],2,',','.')));

	/*$y=$pdf->GetY();

	if ($y==270){;}*/

}

//---------------------------------------------------





	$pdf->Cell(60, 5, utf8_decode(''), 0, 0, 'C', 0);// AÑO

	$pdf->Cell(40, 5, 'Total Montos: '. number_format($resp[0]['mm_monto_total'],2,',','.'), 0, 0, 'R', 0);	// FECHA APROBADO	

//Cuerpo($pdf);



//Cuerpo($pdf);









//$pdf->Output();



$pdf->Output();

//$pdf->Output();



?> 

