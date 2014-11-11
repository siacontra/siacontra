<?php
session_start(); 

require_once('../funciones/class.ezpdf.php');
require('../funciones/fechas.php');
$pdf =& new Cezpdf('A4','landscape');
$pdf->selectFont('../funciones/fonts/helvetica.afm');
$pdf->ezSetCmMargins(2,2,2.5,2.5);
$pdf->ezStartPageNumbers(760,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addJpegFromFile("../images/membrete.jpg",10,510,300,70);
$pdf->ezSetY(490);
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
$pdf->line(15,29,820,29);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(15,580,820,580);


$pdf->addText(325,18,10,'Listado de Proveedores');

$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');
$conexion = mysql_connect($_SESSION["MYSQL_HOST"], $_SESSION["MYSQL_USER"], $_SESSION["MYSQL_CLAVE"]);
mysql_select_db("automotor", $conexion);

$queEmp = "SELECT * FROM proveedor";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}


$titles = array(
				
				'num'=>'<b>Nro</b>',
				'rif'=>'<b>Rif</b>',
				'nombre'=>'<b>Nombre</b>',
				'direccion'=>'<b>Direccion</b>',
				'telefono'=>'<b>Telefono</b>',
											
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>750
			);
$txttit =  "<b>LISTADO DE PROVEEDORES</b>\n";

$pdf->ezText($txttit,15,array('justification'=>'center'));
$pdf->ezText($txtt, 10);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>
