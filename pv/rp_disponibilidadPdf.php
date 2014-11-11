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



$query5="SELECT * FROM pv_partida 

				   WHERE cod_partida='".$codigo."'";

				   

		

				   

	   

	   	 $objConexion->ejecutarQuery($query5);

$resp5 = $objConexion->getMatrizCompleta();







$asignado     		= '0.00';

$incrementado 		= '0.00';

$descrementado		= '0.00';

$creditoAdicional 	= '0.00';

$disminucion 		= '0.00';

$actualizado 		= '0.00';

$preconprometido 	= '0.00';

$comprometido 		= '0.00';

$causado 			= '0.00';

$pagado 			= '0.00';

$disponible 		= '0.00';





$esta='ninguna';



/////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////

//echo $resp5[0]['partida1'];

if($resp5[0]['partida1']!='00' && $resp5[0]['generica']=='00' ) //301.00.00.00

{

	$sql1="SELECT SUM(MontoAprobado) AS ASIGNADO, SUM(MontoCompromiso) AS COMPROMISO, SUM(MontoCausado) AS CAUSADO, SUM(MontoPagado) AS PAGADO FROM pv_presupuestodet 

		   WHERE partida='".$resp5[0]['partida1']."' AND

			     tipocuenta='".$resp5[0]['cod_tipocuenta']."' AND

				 CodPresupuesto='0001'";

				 

	 $objConexion->ejecutarQuery($sql1);

	 $resp1 = $objConexion->getMatrizCompleta();

	 

	 $esta='partida';

	 

	 

	 

////////////////////////////CREDITO ADICIONAL/////////////////////////////////////

	 $sqlCredito="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL

					FROM `pv_item_credito_adicional`

					WHERE LEFT( `cod_partida` , 3 )

					IN (					

					SELECT CONCAT( B.tipocuenta, B.partida )

					FROM pv_partida AS A, pv_presupuestodet AS B

					WHERE B.partida = '".$resp5[0]['partida1']."'

					AND B.tipocuenta = '".$resp5[0]['cod_tipocuenta']."'

					AND A.cod_partida = B.cod_partida

					AND B.CodPresupuesto = '0001'

					)";

					

	 $objConexion->ejecutarQuery($sqlCredito);

	 $datoCredito = $objConexion->getMatrizCompleta();

////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////

//////////////////////COMPROMISO////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////





	$sqlComp="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompradetalle

			  WHERE LEFT( cod_partida, 3 ) = '".$resp5[0]['cod_tipocuenta'].$resp5[0]['partida1']."' AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp);

	 $respComp = $objConexion->getMatrizCompleta();

	 

	 

	 

	 

	 $sqlComp2="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompra

			  WHERE LEFT( cod_partida, 3 ) = '".$resp5[0]['cod_tipocuenta'].$resp5[0]['partida1']."' AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp2);

	 $respComp2 = $objConexion->getMatrizCompleta();

	 

	 

	 

	 

	 

	 //LEFT

////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////

	

	

	$asignado     		= $resp1[0]['ASIGNADO'];

	//$incrementado 		= 0.00;

	//$descrementado		= 0.00;

	$creditoAdicional 	= $datoCredito[0]['CREDITO_ADICIONAL'];

	//$disminucion 		= 0.00;

	$actualizado 		= $asignado+$creditoAdicional;

	//$preconprometido 	= 0.00;

	$comprometido 		= $resp1[0]['COMPROMISO'];//$respComp[0]['PRECIO']+$respComp2[0]['PRECIO'];//$resp1[0]['MontoCompromiso'];

	$causado 			= $resp1[0]['MontoCausado'];

	$pagado 			= $resp1[0]['MontoPagado'];

	$disponible 		= $actualizado-$comprometido;	

}

else if($resp5[0]['generica']!='00' && $resp5[0]['especifica']=='00') //301.01.00.00

{

	 $sql2="SELECT  SUM(MontoAprobado) AS ASIGNADO, SUM(MontoCompromiso) AS COMPROMISO, SUM(MontoCausado) AS CAUSADO, SUM(MontoPagado) AS PAGADO FROM pv_presupuestodet 

						WHERE partida='".$resp5[0]['partida1']."' AND 

							  generica='".$resp5[0]['generica']."' AND 

							  tipocuenta='".$resp5[0]['cod_tipocuenta']."' AND

							  CodPresupuesto='0001'";

				 

	 $objConexion->ejecutarQuery($sql2);

	 $resp2 = $objConexion->getMatrizCompleta();

	 

	  $esta='generica';

	 

////////////////////////////CREDITO ADICIONAL/////////////////////////////////////

	$sqlCredito="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL

					FROM `pv_item_credito_adicional`

					WHERE LEFT( `cod_partida` , 3 )

					IN (

					

					SELECT CONCAT( B.tipocuenta, B.partida )

					FROM pv_partida AS A, pv_presupuestodet AS B

					WHERE B.partida = '".$resp5[0]['partida1']."'

					AND B.generica = '".$resp5[0]['generica']."'

					AND B.tipocuenta = '".$resp5[0]['cod_tipocuenta']."'					

					AND B.CodPresupuesto = '0001'

					)";

					

	 $objConexion->ejecutarQuery($sqlCredito);

	 $datoCredito = $objConexion->getMatrizCompleta();

////////////////////////////////////////////////////////////////////////////////////









////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////

//////////////////////COMPROMISO////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////





	$sqlComp="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompradetalle

			  WHERE LEFT( cod_partida, 6 ) = '".$resp5[0]['cod_tipocuenta'].$resp5[0]['partida1']."'.'".$resp5[0]['generica']."'  AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp);

	 $respComp = $objConexion->getMatrizCompleta();

	 

	 

	 

	 

	 $sqlComp2="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompra

			  WHERE LEFT( cod_partida, 6 ) = '".$resp5[0]['cod_tipocuenta'].$resp5[0]['partida1']."'.'".$resp5[0]['generica']."' AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp2);

	 $respComp2 = $objConexion->getMatrizCompleta();

	 

	 

	 

	 

	 

	 //LEFT

////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////



	

	$asignado     		= $resp2[0]['ASIGNADO'];

	//$incrementado 		= 0.00;

	//$descrementado		= 0.00;

	$creditoAdicional 	= $datoCredito[0]['CREDITO_ADICIONAL'];

	//$disminucion 		= 0.00;

	$actualizado 		= $asignado+$creditoAdicional;

	//$preconprometido 	= 0.00;

	$comprometido 		= $resp2[0]['COMPROMISO'];//$respComp[0]['PRECIO']+$respComp2[0]['PRECIO'];

	$causado 			= $resp2[0]['CAUSADO'];

	$pagado 			= $resp2[0]['PAGADO'];

	$disponible 		= $actualizado-$comprometido;	

}



else if($resp5[0]['partida1']!='00' && $resp5[0]['generica']!='00')

{

	 $sql2="SELECT  * FROM pv_presupuestodet 

						WHERE cod_partida='".$codigo."'  AND

							  CodPresupuesto='0001'";

				 

	 $objConexion->ejecutarQuery($sql2);

	 $resp2 = $objConexion->getMatrizCompleta();

	 

	  $esta='especifica';

	 

////////////////////////////CREDITO ADICIONAL/////////////////////////////////////

	 $sqlCredito="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL

					FROM `pv_item_credito_adicional`

					WHERE `cod_partida`='".$codigo."'";

					

	 $objConexion->ejecutarQuery($sqlCredito);

	 $datoCredito = $objConexion->getMatrizCompleta();

////////////////////////////////////////////////////////////////////////////////////

	

	

	

////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////

//////////////////////COMPROMISO////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////





	$sqlComp="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompradetalle

			  WHERE  cod_partida = '".$codigo."'  AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp);

	 $respComp = $objConexion->getMatrizCompleta();

	 

	 	 	 

	 $sqlComp2="SELECT SUM( PrecioCantidad ) AS PRECIO

			  FROM lg_ordencompra

			  WHERE cod_partida= '".$codigo."' AND (Estado ='RE' OR Estado= 'CE' OR Estado='CO')";

				 

	 $objConexion->ejecutarQuery($sqlComp2);

	 $respComp2 = $objConexion->getMatrizCompleta();

	 	 	 	 

	 

	 //LEFT

////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////



	

	

	$asignado     		= $resp2[0]['MontoAprobado'];

	//$incrementado 		= 0.00;

	//$descrementado		= 0.00;

	$creditoAdicional 	= $datoCredito[0]['CREDITO_ADICIONAL'];

	//$disminucion 		= 0.00;

	$actualizado 		= $asignado+$creditoAdicional;

	//$preconprometido 	= 0.00;

	$comprometido 		= $resp2[0]['MontoCompromiso'];//$respComp[0]['PRECIO']+$respComp2[0]['PRECIO'];

	$causado 			= $resp2[0]['MontoCausado'];

	$pagado 			= $resp2[0]['MontoPagado'];

	$disponible 		= $actualizado-$comprometido;	

}











/////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////



$pdf=new PDF_MC_Table();

$pdf->Open();



//---------------------------------------------------

//	Imprime la cabedera del documento



	$pdf->AddPage();

	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	

	$pdf->SetFont('Arial', 'B', 12);

	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');

	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Administración'), 0, 1, 'L');	

	$pdf->SetFont('Arial', 'B', 10);

	$pdf->Cell(190, 10, 'DISPONIBILIDAD', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', 'B', 6);

	$pdf->SetXY(20, 30); $pdf->Cell(170, 5, utf8_decode('PARTIDA : '.$resp5[0]['cod_partida']), 1, 0, 'L', 1);// AÑO

	$pdf->SetXY(20, 35); $pdf->Cell(170, 5, utf8_decode('DESCRIPCIÓN : '.$resp5[0]['denominacion']), 1, 0, 'L', 1);	// FECHA APROBADO

	

	

	$pdf->SetFont('Arial', 'B', 8);

	$pdf->SetXY(20, 40); $pdf->Cell(30, 5, utf8_decode('Asignado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 40); $pdf->Cell(30, 5, number_format($asignado,2,',','.') , 1, 0, 'R', 0);

	

	$pdf->SetXY(20, 45); $pdf->Cell(30, 5, utf8_decode('Incrementado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 45); $pdf->Cell(30, 5, number_format($incrementado,2,',','.') , 1, 0, 'R', 0);

	

	$pdf->SetXY(20, 50); $pdf->Cell(30, 5, utf8_decode('Descrementado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 50); $pdf->Cell(30, 5, number_format($descrementado,2,',','.'), 1, 0, 'R', 0);

	

	$pdf->SetXY(20, 55); $pdf->Cell(30, 5, utf8_decode('Crédito Adicional : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 55); $pdf->Cell(30, 5, number_format($creditoAdicional,2,',','.'), 1, 0, 'R', 0);

		

	$pdf->SetXY(20, 60); $pdf->Cell(30, 5, utf8_decode('Disminución : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 60); $pdf->Cell(30, 5, number_format($disminucion,2,',','.'), 1, 0, 'R', 0);	

	

	$pdf->SetXY(20, 65); $pdf->Cell(30, 5, utf8_decode('Monto Actualozado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 65); $pdf->Cell(30, 5, number_format($actualizado,2,',','.'), 1, 0, 'R', 0);	

	

	$pdf->SetXY(20, 70); $pdf->Cell(30, 5, utf8_decode('Pre Comprometido : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 70); $pdf->Cell(30, 5, number_format($preconprometido,2,',','.'), 1, 0, 'R', 0);	

		

	$pdf->SetXY(20, 75); $pdf->Cell(30, 5, utf8_decode('Comprometido : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 75); $pdf->Cell(30, 5, number_format($comprometido,2,',','.'), 1, 0, 'R', 0);		



	$pdf->SetXY(20, 80); $pdf->Cell(30, 5, utf8_decode('Causado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 80); $pdf->Cell(30, 5, number_format($causado,2,',','.'), 1, 0, 'R', 0);

	

	$pdf->SetXY(20, 85); $pdf->Cell(30, 5, utf8_decode('Pagado : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 85); $pdf->Cell(30, 5, number_format($pagado,2,',','.'), 1, 0, 'R', 0);		

	

	$pdf->SetXY(20, 90); $pdf->Cell(30, 5, utf8_decode('Disponible : '), 1, 0, 'L', 0);

	$pdf->SetXY(50, 90); $pdf->Cell(30, 5,number_format($disponible,2,',','.'), 1, 0, 'R', 0);		

						



//---------------------------------------------------













$pdf->Output();

//$pdf->Output();



?> 
