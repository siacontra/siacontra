<?php
$mes1=$_REQUEST[mes];
$ano1=$_REQUEST[ano];
if ($mes1=='01'){
	$mes='Enero';
	}else if ($mes1=='02'){
	$mes='Febrero';
	}else if ($mes1=='03'){
	$mes='Marzo';
	}else if ($mes1=='04'){
	$mes='Abril';
	}else if ($mes1=='05'){
	$mes='Mayo';
	}else if ($mes1=='06'){
	$mes='Junio';
	}else if ($mes1=='07'){
	$mes='Julio';
	}else if ($mes1=='08'){
	$mes='Agosto';
	}else if ($mes1=='09'){
	$mes='Septiembre';
	}else if ($mes1=='10'){
	$mes='Octubre';
	}else if ($mes1=='11'){
	$mes='Noviembre';
	}elseif ($mes1=='12'){
	$mes='Diciembre';
}
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

$pdf->addText(310,18,10,'Control de Asistencia');

$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$conexion = mysql_connect("192.168.1.9", "siaces", "s1m0nd14z");
mysql_select_db("permisos", $conexion);

$queEmp = "SELECT * FROM controlasis where mes='".$mes1."' and ano='".$ano."'";
$resEmp = mysql_query($queEmp, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;
while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}


$titles = array(
				
				'dependencia'=>'<b>Dependencia</b>',
				'nrofuncionario'=>'<b>Nro. de Funcionarios</b>',
				'nropermiso'=>'<b>Nro. de Permisos</b>',
				'nroreposo'=>'<b>Nro. de Reposos</b>',
				'nrollegadatardia'=>'<b>Nro. de Llegadas Tardias</b>',
				'nrofuncionariotres'=>'<b>Nro. de Func. con mas de 3 Llegadas Tardias</b>',
								
			);
$options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>680
			);
$txttit =" <b>Control de Asistencia de " .$mes." - " .$ano."  </b>\n";
$pdf->ezText($txttit, 14,array('justification'=>'center'));
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezStream();
						
?>