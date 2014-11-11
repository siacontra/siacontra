<?php
$dependencia=$_REQUEST[dependencia];


require_once('class.ezpdf.php');
$pdf =& new Cezpdf('letter','landscape');
$pdf->selectFont('../fonts/courier.afm');
$pdf->ezSetCmMargins(2,2,2.5,2.5);
$pdf->ezStartPageNumbers(760,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addJpegFromFile("../images/encabezado.jpg",135,493,520,89);
$pdf->ezSetY(490);
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
$pdf->line(15,29,755,29);
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(15,580,755,580);
$pdf->addText(650,585,10,'Recursos Humanos');

$pdf->addText(295,18,10,'Listado de Funcionarios de Permisos por Dependencia');

$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect("192.168.1.9", "siaces", "s1m0nd14z");
mysql_select_db("permisos", $conexion);

$queEmp = "SELECT * FROM permiso INNER JOIN personal ON permiso.CI = personal.cedula WHERE personal.dependencia='$dependencia'";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}


$titles = array(
				
				'num'=>'<b>Nro</b>',
				'CI'=>'<b>Cedula</b>',
				'nombres'=>'<b>Nombres</b>',
				'apellidos'=>'<b>Apellidos</b>',
				'cargo'=>'<b>Cargo</b>',
				'dependencia'=>'<b>Dependencia</b>',
				'fechainic'=>'<b>Desde</b>',
				'fechaculm'=>'<b>Hasta</b>'
								
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>680
			);
$txttit =" <b>            Listado de Funcionarios de Permisos de  " .$dependencia."</b>\n";
$pdf->ezText($txttit, 14,$options[bold]);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>