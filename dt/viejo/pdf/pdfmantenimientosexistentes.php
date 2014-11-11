<?php

require_once('../paginas/class.ezpdf.php');
require('../funciones/fechas.php');
$pdf =& new Cezpdf('legal','landscape');
$pdf->selectFont('../funciones/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2,2,2,2);
$pdf->ezStartPageNumbers(900,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addJpegFromFile("../images/encabezado.jpg",135,493,520,89);
$pdf->ezSetY(490);
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
//LINEA DE ARRIBA ENCABEZADO
$pdf->line(50,29,950,29);
$pdf->setStrokeColor(0,0,0,1);
//LINEA DE ABAJO PIE DE PAGINA
$pdf->line(50,580,950,580);

//PIE DE PAGINA
$pdf->addText(440,18,10,'Listado de Mantenimientos');
//FECHA DEL PIE DE PAGINA
$pdf->addText(80,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect("192.168.1.9", "siaces", "s1m0nd14z");
mysql_select_db("automotor", $conexion);

$queEmp = "SELECT * FROM mantenimiento";
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
				'placa'=>'<b>Placa</b>',
				'modelo'=>'<b>Modelo</b>',				
				'amotor'=>'<b>Aceite Motor</b>',
				'ahidraulico'=>'<b>Aceite Hidra.</b>',
				'n_condicion'=>'<b>Cond. Neuma.</b>',
				'n_presion'=>'<b>Presion Neuma.</b>',
				'cambiobu'=>'<b>Bujia</b>',
				'lavadog'=>'<b>Lav. Gamu</b>',
				'cambiofreno'=>'<b>Frenos</b>',
				'grafito'=>'<b>Grafito</b>',
				'alineabalan'=>'<b>Alinea- Balanc.</b>',
				'filtroaceite'=>'<b>Filtro Aceite</b>',
				'filtrogasolina'=>'<b>Filtro Gasoli.</b>',
				'especificar'=>'<b>Otros</b>',
								
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>900
			);
$txttit =  "<b>LISTADO DE MANTENIMIENTOS </b>\n";

$pdf->ezText($txttit,15,array('justification'=>'center'));
$pdf->ezText($txtt, 10);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>