<?php
session_start(); 

require('funcfechas.php');
$desdes=$_REQUEST[desde];
$hastas=$_REQUEST[hasta];
$desde=explota($desdes);
$hasta=explota($hastas);

	
	
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('legal','landscape');
$pdf->selectFont('../fonts/courier.afm');
$pdf->ezSetCmMargins(2,2,2.5,2.5);
$pdf->ezStartPageNumbers(900,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addJpegFromFile("../images/membrete.jpg",10,510,300,70);
$pdf->ezSetY(490);
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
$pdf->line(15,29,990,29);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(15,580,990,580);
$pdf->addText(750,585,7,'DIVISION DE SERVICIOS GENERALES');

$pdf->addText(450,18,10,'Listado de Mantenimientos');

$pdf->addText(70,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]);
mysql_select_db("automotor", $conexion);

$queEmp = "SELECT * FROM entrada WHERE fecha BETWEEN '".$desde."' AND '".$hasta."' order by fecha";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}


$titles = array(
				
				'num'=>'<b>Nro</b>',
				'fecha'=>'<b>Fecha</b>',
				'hora'=>'<b>Hora</b>',
				'cod_veh'=>'<b>Codigo Vehiculo</b>',
				'placa'=>'<b>Placa</b>',
				'modelo'=>'<b>Modelo</b>',
				'kilometraje'=>'<b>Kilometraje</b>',
				'observaciones'=>'<b>Observaciones</b>',
								
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>970
			);
$txttit =  " <b>                      Listado de Entradas de Vehiculos entre Fechas:  " .$desdes."  al " .$hastas."</b>\n";
$pdf->ezText($txttit, 20,$options[bold]);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>
