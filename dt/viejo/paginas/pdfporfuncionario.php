<?php
$dependencia=$_REQUEST[dependencia];
$cedula=$_REQUEST[cedula];
$nombres=$_REQUEST[nombres];
$apellidos=$_REQUEST[apellidos];
$cargo=$_REQUEST[cargo];

require_once('class.ezpdf.php');
$pdf =& new Cezpdf('letter','landscape');
$pdf->selectFont('../fonts/Courier-Bold.afm');
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

$pdf->addText(295,18,10,'Listado de Permisos por Funcionarios');

$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect("192.168.1.9", "siaces", "s1m0nd14z");
mysql_select_db("permisos", $conexion);

$queEmp = "SELECT * FROM permiso INNER JOIN personal ON permiso.CI = personal.cedula WHERE personal.dependencia='$dependencia' ORDER BY permiso.fechainic";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}


$titles = array(
				
				'num'=>'<b>Nro</b>',
				'tipo'=>'<b>Tipo de Permiso</b>',
				'motivo'=>'<b>Motivo</b>',
				'fechainic'=>'<b>Desde</b>',
				'fechaculm'=>'<b>Hasta</b>',
				'nroreposo'=>'<b>Nro de Reposo</b>',
				'institucion' =>'<b>Institucion</b>'
								
			);
			
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>680
			);
$txttit =" <b>LISTADO DE PERMISOS POR FUNCIONARIO</b>\n";
$txtt="CEDULA DE IDENTIDAD:".$cedula."          FUNCIONARIO: ".$nombres."  ".$apellidos." \n";
$txtt.="DEPENDENCIA: ".$dependencia."          CARGO: ".$cargo." \n ";
$pdf->ezText($txttit, 12,array('justification'=>'center'));
$pdf->ezText($txtt, 9,array('justification'=>'left'));
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>