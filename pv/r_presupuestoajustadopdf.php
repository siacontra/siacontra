<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();
/// -------------------------------------------------
$filtro=strtr($filtro, "*", "'");
global $NroProyecto;
global $fPeriodoEjec;
/// -------------------------------------------------
if($valor==""){
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf,$Organismo,$CodPresupuesto,$EjercicioPpto) {
	//global $NroProyecto;
    //global $fPeriodoEjec;

	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190,5,utf8_decode('Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190,5,utf8_decode('Dirección de Administración y Presupuesto'), 0, 1, 'L');	
	$sql = "SELECT Sector,
	                         Programa,
	                         SubPrograma,
							 Proyecto,
							 Actividad,
							 Organismo,
							 CodPresupuesto,
							 UnidadEjecutora,
							 EjercicioPpto,
							 MontoAjustado,
							 MontoAprobado,
							 FechaPresupuesto
	                    FROM 
						     pv_presupuesto 
					   WHERE 
					        Organismo='$Organismo' and 
							CodPresupuesto='$CodPresupuesto' and 
							EjercicioPpto='$EjercicioPpto'"; //echo $sql;
	$qry = mysql_query($sql) or die ($sql.mysql_error()); 						
	$field=mysql_fetch_array($qry);
	list($a, $m, $d)=SPLIT( '[/.-]', $field[11]); $fechaGenerado=$d.'-'.$m.'-'.$a;
	$m_presupuestado=number_format($field['10'],2,',','.');
	
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
	$pdf->Cell(140, 10, utf8_decode('Descripción de Presupuesto Ajustado'), 0, 1, 'C');	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSector['descripcion'], 0, 0, 'L');
	$pdf->Cell(55, 3, 'EJERCICIO:', 0, 0, 'R');$pdf->Cell(3, 3, $field[8], 0, 1, 'L');
	
	$pdf->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldPrograma[0], 0, 0, 'L');
	$pdf->Cell(55, 3, 'FECHA:', 0, 0, 'R');$pdf->Cell(3, 3, $fechaGenerado, 0, 1, 'L');
	
	$pdf->Cell(27, 3, 'Actividad:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSubprog[0], 0, 0, 'L');
	$pdf->Cell(55, 3, 'MONTO:', 0, 0, 'R');$pdf->Cell(3, 3, $m_presupuestado, 0, 1, 'L');
	
	$pdf->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'Sub-Programa:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$pdf->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(1, 1);
	$pdf->Cell(25, 3.5, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$pdf->Cell(100, 3.5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(35, 3.5, 'MTO.PRESUPUESTADO', 1, 0, 'C', 1);
	$pdf->Cell(30, 3.5, 'MTO.AJUSTADO', 1, 1, 'C', 1);
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
//	Creación del objeto de la clase heredada
/*$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);*/
if(($fPeriodoEjec=='')and($NroProyecto=='')) $EjercicioPpto=" and EjercicioPpto='2012'";
else $EjercicioPpto="";

$sql = "SELECT Sector,
			 Programa,
			 SubPrograma,
			 Proyecto,
			 Actividad,
			 Organismo,
			 CodPresupuesto,
			 UnidadEjecutora,
			 EjercicioPpto,
			 MontoAjustado
		FROM 
			 pv_presupuesto 
	   WHERE 
			Organismo<>'' $filtro $EjercicioPpto";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);
$field=mysql_fetch_array($qry);

$Organismo = $field['Organismo'];
$CodPresupuesto = $field['CodPresupuesto'];
$EjercicioPpto = $field['EjercicioPpto'];

if($row!=0){$pdf=new PDF_MC_Table();
$pdf->Open(); Cabecera($pdf,$Organismo,$CodPresupuesto,$EjercicioPpto);
//	Cuerpo
/// CONSULTO PV_ANTEPRESUPUESTODET PARA VERIFICAR SI EL ANTEPROYECTO TIENE PARTIDAS ASIGNADAS ///
$sqlDet="SELECT cod_partida,MontoAjustado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto,MontoAprobado 
		   FROM pv_presupuestodet 
		  WHERE CodPresupuesto='".$field['CodPresupuesto']."' 
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
   $montoP=0; $montoG=0; $cont1=0;
   $sqldet="SELECT MontoAjustado,MontoAprobado 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   for($a=0; $a<$rwdet; $a++){/*$pdf->Cell(5, 3.5,$rwdet);$pdf->Cell(5, 3.5,$a);*/
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
    $montoP = $montoP + $fdet['MontoAjustado'];
	$montoG = $montoG + $fdet['MontoAprobado'];
   }
    $montoPre=number_format($montoP,2,',','.');
	$montoGen=number_format($montoG,2,',','.');
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 35, 30));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','L','R','R','C','L'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPre,$montoGen));
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
	  $montoG2=0; $montoP2=0; $cont2=0;
	  $sqldet2="SELECT MontoAjustado, MontoAprobado 
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
	   $montoP2 = $montoP2 + $fdet2['MontoAjustado'];
	   $montoG2 = $montoG2 + $fdet2['MontoAprobado'];
	   //$pdf->Cell(5,3.5,$montoG);
	  }
	  $montoGen2=number_format($montoG2,2,',','.');
	  $montoPre2=number_format($montoP2,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 8);
	  $pdf->SetWidths(array(25, 100, 35, 30));
	  $pdf->Cell(1,5);
	  $pdf->SetAligns(array('C','L','R','R','C','L'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoPre2,$montoGen2));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 
	 $montoP3=$fieldet['MontoAjustado'];
	 $montoG3=$fieldet['MontoAprobado'];
	 
	 $montoT_pre=$montoT_pre + $montoP3; //// montos finales 
	 $montoT_gen=$montoT_gen + $montoG3;
	 
	 $montoPre3=number_format($montoP3,2,',','.'); //// cambio de formato de montos a mostrar
	 $montoGen3=number_format($montoG3,2,',','.');
	 
	 $montoTotal_pre=number_format($montoT_pre,2,',','.'); //// cambio de formato montos finales
	 $montoTotal_gen=number_format($montoT_gen,2,',','.');
	 
	 $montoDet=number_format($fieldet['MontoAjustado']);
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 8);
	 $pdf->SetWidths(array(25, 100, 35, 30));
	 $pdf->Cell(1,5);
	 $pdf->SetAligns(array('C','L','R','R','C','L'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoPre3,$montoGen3));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 35, 30));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','R','R','R','C','L'));
	$pdf->Row(array('' ,'Total:',$montoTotal_pre,$montoTotal_gen));
	/////

//---------------------------------------------------
$pdf->Output();
}}
?>  
