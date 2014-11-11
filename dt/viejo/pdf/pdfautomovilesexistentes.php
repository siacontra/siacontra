<?php

require_once('../paginas/class.ezpdf.php');
require('../funciones/fechas.php');
$pdf =& new Cezpdf('letter','landscape');
$pdf->selectFont('../funciones/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2,2,2,2);
$pdf->ezStartPageNumbers(900,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addJpegFromFile("../images/encabezado.jpg",135,493,520,89);
$pdf->ezSetY(490);
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
$pdf->line(15,29,990,29);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(15,580,755,580);


$pdf->addText(450,18,10,'Listado de Automoviles Existentes');

$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect("192.168.1.9", "siaces", "s1m0nd14z");
mysql_select_db("automotor", $conexion);

$queEmp = "SELECT * FROM automovil";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}

$titles = array(
				'num'=>'<b>Nro</b>',
				'cod_veh'=>'<b>Codigo del Vehiculo</b>',
				'placa'=>'<b>Placa</b>',
				'modelo'=>'<b>Modelo</b>',
				'ano'=>utf8_decode('<b>Año</b>'),
				'basico'=>'<b>Basico</b>',
				'color'=>'<b>Color</b>',
				'serialmotor'=>'<b>Serial del Motor</b>',
				'serialcarro'=>'<b>Serial del Vehiculo</b>',
				'nrocarroceria'=>'<b>Serial de la Carroceria</b>',
				
								
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>710
			);
$txttit =  "<b>LISTADO DE VEHICULOS </b>\n";



// $pdf->ezText($txttit,15,array('justification'=>'center'));
// $pdf->ezText($txtt, 10);
// $pdf->ezTable($data, $titles, '', $options);
// $pdf->ezStream();

$pdf->ezText($txttit,15,array('justification'=>'center'));
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>
