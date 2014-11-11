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






$query="SELECT * FROM `pv_ajustepresupuestario` WHERE `CodAjuste`=".$codigo;

	   

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

	$pdf->Cell(190, 10, 'A J U S T E', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', 'B', 6);

	$pdf->Cell(10, 5, utf8_decode('PERIODO'), 1, 0, 'C', 1);// AÑO

	$pdf->Cell(20, 5, utf8_decode('FECHA APROBADO'), 1, 0, 'C', 1);	// FECHA APROBADO

	$pdf->Cell(15, 5, utf8_decode('Nro OFICIO'), 1, 0, 'C', 1);//

	$pdf->Cell(15, 5, utf8_decode('Nro GACETA'), 1, 0, 'C', 1);//Nro DECRETO	

	$pdf->Cell(20, 5, utf8_decode('MONTO'), 1, 0, 'R', 1);//MONTO

	$pdf->Cell(15, 5, utf8_decode('ESTATUS'), 1, 0, 'C', 1);//ESTATUS	

	$pdf->Cell(80, 5, utf8_decode('MOTIVO DEL PROCESO'), 1, 0, 'C', 1);//MOTIVO DEL PROCESO

	

	$pdf->Cell(20, 5, utf8_decode('TIPO MOV.'), 1, 0, 'C', 1);//MOTIVO DEL PROCESO

	

	

}



//---------------------------------------------------







function Cuerpo($pdf) {

	//$pdf->AddPage();

	

	$pdf->SetFont('Arial', 'B', 6);

	//$pdf->SetXY(20, 100); $pdf->Cell(190, 5,utf8_decode( ''), 0, 1, 'L');

	//$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode(''), 0, 1, 'L');	

	//$pdf2->SetFont('Arial', 'B', 10);

	$pdf->Cell(140, 5, '', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

	

	$pdf->Cell(60, 5, utf8_decode('CÓDIGO RECEPTOR'), 1, 0, 'C', 1);// AÑO

	$pdf->Cell(40, 5, utf8_decode('MONTO ASIGNADO'), 1, 0, 'C', 1);// 

	$pdf->Cell(40, 5, utf8_decode('MONTO AJUSTE'), 1, 0, 'C', 1);// 

}



//---------------------------------------------------

//	Creación del objeto de la clase heredada

$pdf=new PDF_MC_Table();

$pdf->Open();

Cabecera($pdf);









//	Cuerpo





/*



$query="SELECT a.`co_credito_adicional`,a.`CodOrganismo`,a.`nu_oficio`,a.`ff_oficio`,

a.`nu_decreto`,a.`ff_decreto`,a.`tx_motivo`,a.`ff_ejecucion`,a.`ff_creacion`,

a.`tx_estatus`,a.`tx_preparado`,a.`tx_aprobado`,a.`tx_ultima_modificacion`,

a.`ff_ultima_modoficacion`,a.`mm_monto_total`



FROM `pv_credito_adicional` as a, `pv_item_credito_adicional` as b 



WHERE 

       a.`co_credito_adicional`=b.`co_credito_adicional` AND

	   a.`co_credito_adicional`=".$codigo;

	   

	   		$objConexion->ejecutarQuery($query);

$resp = $objConexion->getMatrizCompleta();*/

	  

	$pdf->Row(array('','','','','','',''));// para que no se salga de la tabla

	





 if($resp[0]['MotivoAjuste']=='CR'){$mot='CREDITO ADICIONAL';}

 if($resp[0]['MotivoAjuste']=='TR'){$mot='TRASPASO';}

	

$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', '', 6);

$pdf->SetWidths(array(10,20,15,15,20,15,80,20));

$pdf->SetAligns(array('C', 'C','C','C','R','C','L','C'));

$pdf->Row(array($resp[0]['Periodo'], $resp[0]['FechaGaceta'], $resp[0]['NumResolucion'], $resp[0]['NumGaceta'], number_format($resp[0]['TotalAjuste'],2,',','.'), 'APROBADO', $resp[0]['Descripcion'],utf8_decode($mot)));

$y=$pdf->GetY();

if ($y==270) Cabecera($pdf);

	

Cuerpo($pdf);









	   

$query2="SELECT A.`Organismo`,A.`CodPresupuesto`,A.`CodAjuste`,A.`FechaAjuste`,A.`Periodo`,A.`TipoAjuste`,

				A.`NumGaceta`,A.`FechaGaceta`,A.`NumResolucion`,A.`FechaResolucion`,A.`Descripcion`,A.`TotalAjuste`,

				A.`PreparadoPor`,A.`FechaPreparacion`,A.`AprobadoPor`,A.`FechaAprobacion`,A.`Estado`,

				A.`UltimaFechaModif`, A.`UltimoUsuario`, A.`MotivoAjuste`, B.`CodAjuste`,B.`cod_partida`,B.`Estado`,

				B.`MontoDisponible`,B.`MontoAjuste`,B.`UltimoUsuario`,B.`UltimaFechaModif`

				

		FROM `pv_ajustepresupuestario` AS A, 

			 `pv_ajustepresupuestariodet` AS B 

			 

		WHERE A.`CodAjuste`=B.`CodAjuste` AND

			  A.`CodAjuste`=".$codigo;





		$objConexion->ejecutarQuery($query2);

$resp2 = $objConexion->getMatrizCompleta();





	// Cuerpo($pdf2);

	$pdf->Cell(100, 5, '', 0, 1, 'C');

for($i=0; $i<count($resp2);$i++) {

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', '', 6);

	$pdf->SetWidths(array(60, 40, 40));

	$pdf->SetAligns(array('C', 'R', 'R'));

	$pdf->Row(array($resp2[$i]['cod_partida'],number_format($resp2[$i]['MontoDisponible'],2,',','.'),  number_format($resp2[$i]['MontoAjuste'],2,',','.')));

	/*$y=$pdf->GetY();

	if ($y==270){;}*/

}

//---------------------------------------------------





	$pdf->Cell(60, 5, utf8_decode(''), 0, 0, 'C', 0);// AÑO

	$pdf->Cell(40, 5, '', 0, 0, 'R', 0);

	$pdf->Cell(40, 5, 'Total Montos: '.number_format($resp2[0]['TotalAjuste'],2,',','.'), 0, 0, 'R', 0);

//Cuerpo($pdf);



//Cuerpo($pdf);









//$pdf->Output();



$pdf->Output();

//$pdf->Output();



?> 

