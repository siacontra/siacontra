<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();

//list($codpresupuesto, $organismo, $ejercicioppto ) = SPLIT('[-]', $_GET['registro']);
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	
	list($codpresupuesto, $organismo, $ejercicioppto ) = SPLIT('[-]', $_GET['registro']);
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190,5,'Contralor�a del Estado Monagas', 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190,5,'Divisi�n de Administraci�n', 0, 1, 'L');	
	$sql = "SELECT Sector,Programa,SubPrograma,Proyecto,Actividad,Organismo,CodPresupuesto,UnidadEjecutora 
			  FROM pv_presupuesto
		     WHERE CodPresupuesto='$codpresupuesto' and 
			  	   EjercicioPpto = '$ejercicioppto'"; //echo $sql;
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);
	// Sector
	$sqlSector="SELECT descripcion,cod_sector FROM pv_sector WHERE cod_sector='".$field[0]."'";
	$qrySector=mysql_query($sqlSector) or die ($sqlSector.mysql_error());
	$fieldSector=mysql_fetch_array($qrySector);
	// Programa
	$sqlPrograma="SELECT descp_programa,cod_programa FROM pv_programa1 WHERE id_programa='".$field[1]."'";
	$qryPrograma=mysql_query($sqlPrograma) or die ($sqlPrograma.mysql_error());
	$fieldPrograma=mysql_fetch_array($qryPrograma);
	// SubPrograma
	$sqlSubprog="SELECT descp_subprog,cod_subprog FROM pv_subprog1 WHERE id_sub='".$field[2]."'";
	$qrySubprog=mysql_query($sqlSubprog) or die ($sqlSubprog.mysql_error());
	$fieldSubprog=mysql_fetch_array($qrySubprog);
	// Proyecto
	$sqlProyecto="SELECT descp_proyecto,cod_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$field[3]."'";
	$qryProyecto=mysql_query($sqlProyecto) or die ($sqlProyecto.mysql_error());
	$fieldProyecto=mysql_fetch_array($qryProyecto);
	// Actividad
	$sqlActividad="SELECT descp_actividad,cod_actividad FROM pv_actividad1 WHERE id_actividad='".$field[4]."'";
	$qryActividad=mysql_query($sqlActividad) or die ($sqlActividad.mysql_error());
	$fieldActividad=mysql_fetch_array($qryActividad);
	// Organismo o Unidad Ejecutora
	$sqlOrg="SELECT Organismo FROM mastorganismos WHERE CodOrganismo='".$field[5]."'";
	$qryOrg=mysql_query($sqlOrg) or die ($sqlOrg.mysql_error());
	$fieldOrg=mysql_fetch_array($qryOrg);
	
	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(140, 10, 'Descripci�n de Presupuesto', 0, 1, 'C');	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$pdf->Cell(4, 3, $fieldSector[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$pdf->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$pdf->Cell(4, 3, $fieldPrograma[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$pdf->Cell(4, 3, $fieldSubprog[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	//$pdf->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	//$pdf->Cell(27, 3, 'Sub-Programa:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$pdf->Cell(30, 3, $field[7], 0, 1, 'L');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(1, 1);
	$pdf->Cell(25, 3.5, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$pdf->Cell(100, 3.5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(25, 3.5, 'MTO. APROBADO', 1, 1, 'C', 1);
	//$pdf->Cell(13, 3.5, 'PPTO. APROBADO', 1, 1, 'C', 1);
	$pdf->SetFillColor(255, 255, 255);
	/*$pdf->Cell(11, 3.5, 'DISMINUCION', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'PPTO.AJUST', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'COMPROMISOS', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'CAUSADO', 1, 0, 'C', 1);	$pdf->Cell(11, 3.5, 'PAGADO', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'DISP.PRESU', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'DISP.FINAN', 1, 1, 'C', 1);
	$pdf->Cell(10, 4, 'PAR', 1, 0, 'C', 1);
	$pdf->Cell(10, 4, 'GE', 1, 0, 'C', 1);
	$pdf->Cell(10, 4, 'ESP', 1, 0, 'C', 1);
	$pdf->Cell(10, 4, 'SE', 1, 0, 'C', 1);
	$pdf->Cell(20, 4, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(125, 4, 'DESCRIPCION', 1, 1, 'C', 1);*/
}
//---------------------------------------------------
//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
list($codpresupuesto, $organismo, $ejercicioppto ) = SPLIT('[-]', $_GET['registro']);
//	Cuerpo
/// CONSULTO PV_ANTEPRESUPUESTODET PARA VERIFICAR SI EL ANTEPROYECTO TIENE PARTIDAS ASIGNADAS ///
$sqlDet="SELECT cod_partida,MontoAprobado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto 
		   FROM pv_presupuestodet 
		  WHERE CodPresupuesto='$codpresupuesto' 
		  ORDER BY cod_partida";
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0;
   $sqldet="SELECT MontoAprobado
		      FROM pv_presupuestodet
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   for($a=0; $a<$rwdet; $a++){/*$pdf->Cell(5, 3.5,$rwdet);$pdf->Cell(5, 3.5,$a);*/
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
    $montoP = $montoP + $fdet['MontoAprobado'];
   }
    $montoPar=number_format($montoP,2,',','.');
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 25));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','L','R','C','C','L'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPar,''));
  }
 }
 //// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
    $sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			    FROM pv_partida 
			   WHERE partida1='".$fieldet['partida']."' AND
				     cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				     tipo='T' AND 
					 generica='".$fieldet['generica']."' AND 
					 especifica='00'";
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2);//$pdf->Cell(5,3.5,$rwPar2);
	if($rows2!=0){
	  $fpar2=mysql_fetch_array($qry2);
	  $montoG=0; $cont2=0;
	  $sqldet2="SELECT MontoAprobado 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  for($b=0; $b<$rwdet2; $b++){
	   $fdet2=mysql_fetch_array($qrydet2);
	   $cont2 = $cont2 + 1;
	   $montoG = $montoG + $fdet2['MontoAprobado'];
	   //$pdf->Cell(5,3.5,$montoG);
	  }
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 8);
	  $pdf->SetWidths(array(25, 100, 25));
	  $pdf->Cell(1,5);
	  $pdf->SetAligns(array('C','L','R','C','C','L'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,''));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 � 301-01-01-01
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $monto=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 8);
	 $pdf->SetWidths(array(25, 100, 25));
	 $pdf->Cell(1,5);
	 $pdf->SetAligns(array('C','L','R','C','C','L'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$monto,''));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 25));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','R','R','L','C','L'));
	$pdf->Row(array('' ,'Total:',$montoTotal,''));
	/////

//---------------------------------------------------
$pdf->Output();
?>  
