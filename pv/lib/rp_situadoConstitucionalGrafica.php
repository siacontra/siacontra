<?php // content="text/plain; charset=utf-8"

require('fphp.php');
connect();

require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_bar.php');



 $sqlPresupuestoAprobado="SELECT SUM(B.MontoAjustado) AS MontoAjustado
FROM `pv_presupuesto` AS A , `pv_presupuestodet` AS B
WHERE A.EjercicioPpto = '".$ejercicio."' AND A.CodPresupuesto=B.CodPresupuesto";


$qryPr = mysql_query($sqlPresupuestoAprobado) or die ($sqlPresupuestoAprobado.mysql_error());
$rowPr = mysql_num_rows($qryPr); //echo $rowCon;
$fieldPr = mysql_fetch_array($qryPr);
 
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//COMPROMISO

/*

SELECT sum( Monto ) AS Monto_lg
FROM `lg_distribucioncompromisos`
WHERE Estado = 'CO'
AND Periodo >= '".$periodo."'
AND Periodo <= '".$periodohasta."'

*/

$sqlCompromiso="SELECT sum( Monto ) AS Monto_lg
			FROM `lg_distribucioncompromisos`
			WHERE Estado = 'CO'
			AND Periodo >= '".$periodo."'
			AND Periodo <= '".$periodohasta."'
		UNION
		select
			 sum(a.Monto) as Monto_lg
		from 
			  lg_distribucioncompromisos a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento)and
												  (e.CodOrganismo=a.CodOrganismo) and												 												   
												   e.Estado<>'AN' and 
												   e.FlagPresupuesto='S'
		where 			  
			 (a.Periodo>='".$periodo."' and 
			  a.Periodo<='".$periodohasta."') and 			  
			  a.Estado<>'AN'" ;


$qryCon = mysql_query($sqlCompromiso) or die ($sqlCompromiso.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;

$montoComp=0.00;
for($i=0;$i<$rowCon;$i++){
	$fieldCon = mysql_fetch_array($qryCon);
	$montoComp=$montoComp+$fieldCon['Monto_lg'];
}

$porAprobado=100.00;// EL APROBADO MontoAprobado
$porComp = ($montoComp*$porAprobado);
$porComp = $porComp/$fieldPr['MontoAjustado'];
$porComp=number_format($porComp,2,'.','');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////CAUSADO////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO CAUSADOS
/// ********************************************************************************************** ///	
$sqlCausado = "select
				   sum(a.Monto) as Monto_lg
			from 
				  ap_distribucionobligacion a 
				  inner join ap_obligaciones b on (b.NroDocumento=a.NroDocumento) and
				  								  (b.CodTipoDocumento=a.CodTipoDocumento) and
												  (b.CodProveedor=a.CodProveedor) and
												  (b.CodOrganismo=a.CodOrganismo) and
												  (b.FechaPago>='".$periodo.'-01'."' and 
												   b.FechaPago<='".$periodohasta.'-31'."' and										   
												   b.Estado<>'AN' and 
												   b.FlagPresupuesto='S')
			where 
				  
				 (a.Periodo>='".$periodo."' and 
			  a.Periodo<='".$periodohasta."') and 
				  a.Estado<>'AN'
			UNION
			select
				   sum(a.Monto) as Monto_lg
			from 
				  ap_distribucionobligacion a 
				  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento) and
													  (e.CodOrganismo=a.CodOrganismo) and
													  (e.FechaTransaccion>='".$periodo.'-01'."' and 
													   e.FechaTransaccion<='".$periodohasta.'-31'."' and										  
													   e.Estado<>'AN' and 
													   e.FlagPresupuesto='S')
			where 
				 
				  (a.Periodo>='".$periodo."' and 
			  a.Periodo<='".$periodohasta."') and 
				  a.Estado<>'AN'";
$qryCau = mysql_query($sqlCausado) or die ($sqlCausado.mysql_error());
$rowCau = mysql_num_rows($qryCau); //echo $rowCon;
$montoCaus=0.00;
for($i=0;$i<$rowCau;$i++){
	$fieldCau = mysql_fetch_array($qryCau);
	$montoCaus=$montoCaus+$fieldCau['Monto_lg'];
}

$porAprobado=100.00;// EL APROBADO MontoAprobado
$porCausado = ($montoCaus*$porAprobado);
$porCausado = $porCausado/$fieldPr['MontoAjustado'];
$porCausado = number_format($porCausado,2,'.','');
 	
/// ********************************************************************************************** ///
///                                 CALCULANDO MONTO PAGADOS
/// ********************************************************************************************** ///	
$sqlPagado="select distinct
			  sum(a.Monto) as Monto_lg
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_ordenpago b on (b.NroDocumento=a.NroDocumento) and
			  							   (b.CodTipoDocumento=a.CodTipoDocumento) and
										   (b.CodProveedor=a.CodProveedor) and
										   (b.CodOrganismo=a.CodOrganismo) and
										   (b.Periodo >= '".$periodo."' and b.Periodo <= '".$periodohasta."' and	
										    b.FechaOrdenPago>='".$periodo.'-01'."' and 
											b.FechaOrdenPago<='".$periodohasta.'-31'."' and								
											b.Estado<>'AN')
		where 
			 
			 (a.Periodo>='".$periodo."' and 
			  a.Periodo<='".$periodohasta."') and 
			  a.Estado<>'AN' and 
			  a.cod_partida <> ''
			 
		UNION
		 select
			   sum(a.Monto) as Monto_lg
		from 
			  ap_ordenpagodistribucion a 
			  inner join ap_bancotransaccion e on (e.CodigoReferenciaBanco=a.NroDocumento) and
												  (e.CodOrganismo=a.CodOrganismo) and
												  (e.FechaTransaccion>='".$periodo.'-01'."' and 
												   e.FechaTransaccion<='".$periodohasta.'-31'."' and												  
												   e.Estado<>'AN' and 
												   e.FlagPresupuesto='S')
		where 			 
			  (a.Periodo>='".$periodo."' and 
			  a.Periodo<='".$periodohasta."') and 
			  a.Estado<>'AN' AND 
			  a.cod_partida <> ''";
			  
$qryPagado = mysql_query($sqlPagado) or die ($sqlPagado.mysql_error());
$rowPag = mysql_num_rows($qryPagado); //echo $rowCon;

$montoPagado=0.00;
for($i=0;$i<$rowPag;$i++){
	$fieldPagado = mysql_fetch_array($qryPagado);
	$montoPagado=$montoPagado+$fieldPagado['Monto_lg'];
}






$porAprobado =100.00;// EL APROBADO MontoAprobado es el 100%
$porPagado   = ($montoPagado*$porAprobado);
$porPagado   = $porPagado/$fieldPr['MontoAjustado'];
$porPagado   = number_format($porPagado,2,'.','');     	


$porAprobadoStr = '100.00';






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  
			  
// include "mycsimscript.php"
// Callback function for Y-scale to get 1000 separator on labels
function separator1000($aVal) {
    return number_format($aVal);
}
 
function separator1000_usd($aVal) {
    return '$'.number_format($aVal);
}

//echo porComp;
 
// Some data
$datay=array($porAprobado,$porComp,$porCausado,$porPagado,$porAprobado-$porComp);
 
$pre= $porAprobadoStr;
$com = $porComp;
$cau = $porCausado;
$pag= $porPagado;
$dis = $porAprobado-$porComp;
 
// Create the graph and setup the basic parameters
$graph = new Graph(700,400,'auto');
$graph->img->SetMargin(80,290,20,20);
$graph->SetScale('textint');
$graph->SetShadow();
$graph->SetFrame(false); // No border around the graph





$graph->yaxis->SetTickPositions(array(0,10,20,30,40,50,60,70,80,90,100));
$graph->SetBox(false);
 
// Add some grace to the top so that the scale doesn't
// end exactly at the max value.
// The grace value is the percetage of additional scale
// value we add. Specifying 50 means that we add 50% of the
// max value
//$graph->yaxis->scale->SetGrace(0.2);
$graph->yaxis->SetLabelFormatString('%0.2f %%');

//$graph->yaxis->SetLabelFormatCallback($y);
//$graph->yaxis->SetTickLabels($y);
//$graph->yaxis->SetFont(FF_FONT1);
 
 
 //rp_ejecucionpdf.php
// Setup X-axis labels
//$a = $gDateLocale->GetShortMonth();'
/*$a = array('Presupuesto','Compromiso', 'Causado', 'Pagado', 'Disponibilidad');
$graph->xaxis->SetTickLabels($a);
$graph->xaxis->SetFont(FF_FONT2);*/
 
// Setup graph title ands fonts

$graph->title->Set(''); //TITULO DE LA GRAFICA
$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->xaxis->title->Set('AÑO '.$ejercicio);
$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD);


$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels(array(''));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$data1y=array((float)$pre);
$data2y=array((float)$com);
$data3y=array((float)$cau);
$data4y=array((float)$pag);
$data5y=array((float)$dis);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
$b3plot = new BarPlot($data3y);
$b4plot = new BarPlot($data4y);
$b5plot = new BarPlot($data5y);




$b1plot->SetColor("white");
$b1plot->SetFillColor("#FDE353");
$b1plot->SetLegend("Presupuesto ".number_format($fieldPr['MontoAjustado'],2,',','.').' - '.$pre.'%');
$b1plot->value->SetFormat('%0.2f %%');
//$b1plot->SetShadow("#666666");


$b2plot->SetColor("white");
$b2plot->SetFillColor("#FF8080");
$b2plot->SetLegend("Compromiso  ".number_format($montoComp,2,',','.').' - '.$com.'%');
$b2plot->value->SetFormat('%0.2f %%');
//$b2plot->SetShadow("#666666");

$b3plot->SetColor("white");
$b3plot->SetFillColor("#800080");
$b3plot->SetLegend("Causado     ".number_format($montoCaus,2,',','.').' - '.$porCausado.'%');
$b3plot->value->SetFormat('%0.2f %%');
//$b3plot->SetShadow("#666666");

$b4plot->SetColor("white");
$b4plot->SetFillColor('#00FF80');
$b4plot->SetLegend("Pagado      ".number_format($montoPagado,2,',','.').' - '.$pag.'%');
$b4plot->value->SetFormat('%0.2f %%');
//$b4plot->SetShadow("#666666");

$b5plot->SetColor("white");
$b5plot->SetFillColor('#0080FF');
$b5plot->SetLegend("Disponible  ".number_format($fieldPr['MontoAjustado']-$montoComp,2,',','.').' - '.$dis.'%');
//$b5plot->SetFormat('%01.2f');

$b5plot->value->SetFormat('%0.2f %%');
//$b5plot->SetShadow("#666666");

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(1);
$graph->legend->SetColor('#4E4E4E','#00A78A');

//$graph->Add($gbplot);

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot,$b4plot,$b5plot));
// ...and add it to the graPH
$graph->Add($gbplot);

$gbplot->SetWidth(0.90);


$graph->Stroke();

?>