<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();

$filtro=strtr($filtro, "*", "'");
global $NroPresupuesto;
global $EjercicioPpto;
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf,$Organismo,$CodPresupuesto,$EjercicioPpto2) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode('Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5,utf8_decode('Dirección de Administración y Presupuesto'), 0, 1, 'L');	
	$sql = "SELECT 
				Sector,
				Programa,
				SubPrograma,
				Proyecto,
				Actividad,
				Organismo,
				CodPresupuesto,
				UnidadEjecutora,
				CodPresupuesto 
			FROM 
				pv_presupuesto 
		   WHERE 
				Organismo='$Organismo' and 
				CodPresupuesto='$CodPresupuesto' and 
				EjercicioPpto='$EjercicioPpto2'";
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
	$pdf->Cell(140, 10, utf8_decode('Presupuesto Disminuciones'), 0, 1, 'C');	
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$pdf->Cell(100, 3, $fieldSector['descripcion'], 0, 0, 'L');
	      $pdf->Cell(10, 3, 'FECHA:', 0, 0, 'L');$pdf->Cell(15, 3, date("d-m-Y H:i:s"), 0, 1, 'L');
	$pdf->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$pdf->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$pdf->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(1, 1);
	$pdf->Cell(25, 3.5, 'Par Ge Es Se', 1, 0, 'C', 1);
	$pdf->Cell(100, 3.5, utf8_decode('Denominación'), 1, 0, 'C', 1);
	$pdf->Cell(25, 3.5, utf8_decode('Asignación Anual'), 1, 0, 'C', 1);
	$pdf->Cell(25, 3.5, utf8_decode('Disminuciones'), 1, 1, 'C', 1);
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
//	Creación del objeto de la clase heredada
/*$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);*/
//	Cuerpo
if(($fPeriodoEjec=='')and($NroProyecto=='')) $EjercicioPpto=" and EjercicioPpto='".date("Y")."'";
else $EjercicioPpto="";

   $sql = "SELECT 
				* 
			FROM 
				pv_presupuesto 
		   WHERE 
				Organismo<>'' $filtro $EjercicioPpto";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);

$Organismo = $field['Organismo'];
$CodPresupuesto = $field['CodPresupuesto'];
$EjercicioPpto2 = $field['EjercicioPpto'];

$pdf=new PDF_MC_Table();
$pdf->Open(); Cabecera($pdf,$Organismo,$CodPresupuesto,$EjercicioPpto2);
/// CONSULTO PV_ANTEPRESUPUESTODET PARA VERIFICAR SI EL ANTEPROYECTO TIENE PARTIDAS ASIGNADAS ///
$sqlDet="SELECT cod_partida,MontoAprobado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto 
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
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida 
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
	/// - Consulta de partida incrementada o con ajuste positivo
	$s_consulta01 = "select 
	                       MontoAjuste 
					  from 
					       pv_ajustepresupuestariodet 
				      where 
					       CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   cod_partida='".$fdet['cod_partida']."'";
	$q_consulta01 = mysql_query($s_consulta01) or die ($s_consulta01.mysql_error());
	$f_consulta01 = mysql_fetch_array($q_consulta01);
	
	$montoConsulta01 = $montoConsulta01 + $f_consulta01['MontoAjuste'];
	
	
   }
    $montoPar=number_format($montoP,2,',','.');
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	/// Monto Incrementado
	$montoInc = number_format($montoConsulta01,2,',','.');
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 25, 25));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','L','R','R','C','L'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPar,$montoInc));
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
	  $montoG=0; $cont2=0; $montoConsulta02=0;
	  $sqldet2="SELECT MontoAprobado, cod_partida 
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
	   /// - Consulta de partida incrementada o con ajuste positivo
	   $s_consulta02 = "select 
	                       MontoAjuste 
					  from 
					       pv_ajustepresupuestariodet 
				      where 
					       CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   cod_partida='".$fdet2['cod_partida']."'";
	  $q_consulta02 = mysql_query($s_consulta02) or die ($s_consulta02.mysql_error());
	  $f_consulta02 = mysql_fetch_array($q_consulta02);
	
	  $montoConsulta02 = $montoConsulta02 + $f_consulta02['MontoAjuste'];
	  }
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 8);
	  $pdf->SetWidths(array(25, 100, 25, 25));
	  $pdf->Cell(1,5);
	  $pdf->SetAligns(array('C','L','R','R','C','L'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,$montoInc2));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
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
	 /// Monto Incrementado
	 $s_consulta03 = "select 
	                       MontoAjuste 
					  from 
					       pv_ajustepresupuestariodet 
				      where 
					       CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   cod_partida='".$fieldet['cod_partida']."'";
	  $q_consulta03 = mysql_query($s_consulta03) or die ($s_consulta03.mysql_error());
	  $f_consulta03 = mysql_fetch_array($q_consulta03);
	
	  $montoConsulta03 = $f_consulta03['MontoAjuste'];
	  $montoInc3 = $montoInc3 + $montoConsulta03;
	  $montoConsulta03 = number_format($montoConsulta03,2,',','.');
	  $montoIncTotal = number_format($montoInc3,2,',','.');
	 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 8);
	 $pdf->SetWidths(array(25, 100, 25, 25));
	 $pdf->Cell(1,5);
	 $pdf->SetAligns(array('C','L','R','R','C','L'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$monto,$montoConsulta03));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	$pdf->SetWidths(array(25, 100, 25, 25));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','R','R','R','C','L'));
	$pdf->Row(array('' ,'Total:',$montoTotal,$montoIncTotal));
	/////
//---------------------------------------------------
$pdf->Output();
?>  
