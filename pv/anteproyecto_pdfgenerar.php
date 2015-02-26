<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190,5, 'Contraloría del Estado Monagas', 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190,5,'División de Administración', 0, 1, 'L');	
	$qry=mysql_query("SELECT Sector,Programa,SubPrograma,Proyecto,Actividad,Organismo,CodAnteproyecto FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_GET['registro']."'") or die ($sql.mysql_error());
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
	$pdf->Cell(140, 10, 'Descripción de Anteproyecto', 0, 1, 'C');	
	$pdf->SetFont('Arial', '', 5);
	$pdf->Cell(20, 3, 'SECTOR:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$pdf->Cell(20, 3, 'PROGRAMA:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$pdf->Cell(20, 3, 'Actividad:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$pdf->Cell(20, 3, 'PROYECTO:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$pdf->Cell(20, 3, 'Sub-Programa:', 0, 0, 'L');$pdf->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$pdf->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$pdf->Cell(20, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$pdf->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 4);
	$pdf->Cell(1, 1);
	$pdf->Cell(15, 3.5, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$pdf->Cell(90, 3.5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Cell(13, 3.5, 'PPTO. ESTIMADO', 1, 0, 'C', 1);
	$pdf->Cell(13, 3.5, 'PPTO. APROBADO', 1, 1, 'C', 1);
	/*$pdf->Cell(11, 3.5, 'DISMINUCION', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'PPTO.AJUST', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'COMPROMISOS', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'CAUSADO', 1, 0, 'C', 1);
	$pdf->Cell(11, 3.5, 'PAGADO', 1, 0, 'C', 1);
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
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
/// CONSULTO PV_ANTEPRESUPUESTODET PARA VERIFICAR SI EL ANTEPROYECTO TIENE PARTIDAS ASIGNADAS ///
$query=mysql_query("SELECT cod_partida,MontoAsignado,partida,generica,especifica,subespecifica FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_GET['registro']."' ORDER BY cod_partida") or die ($sql.mysql_error());
if(mysql_num_rows($query)!=0){
  while($fieldAntp=mysql_fetch_array($query)){
   $sqlPartida="SELECT cod_partida,partida1,generica,especifica,subespecifica,denominacion,tipo FROM pv_partida WHERE cod_partida='".$fieldAntp['cod_partida']."'";
   $qryPartida=mysql_query($sqlPartida) or die ($sqlPartida.mysql_error());
   $fieldPartida=mysql_fetch_array($qryPartida);
   if(($fieldAntp[1]!=0) and ($fieldAntp[2]==00) and ($fieldPartida[6]=='T')){
	  $pdf->SetFont('Arial', 'B', 4);
	  $pdf->SetWidths(array(15, 90, 13, 13));
	  $pdf->Cell(1,5);
	  $pdf->SetAligns(array('C','L','C','C','C','L'));
	  $pdf->Row(array($fieldPartida[0],$fieldPartida[5],$fieldAntp[1],''));
	}else{
	   if($fieldPartida[6]=='D'){
	     $monto=$fieldAntp[1] + $monto;
		 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	     $pdf->SetFont('Arial', '', 4);
	     $pdf->SetWidths(array(15, 90, 13, 13));
	     $pdf->Cell(1,5);
	     $pdf->SetAligns(array('C','L','C','C','C','L'));
	     $pdf->Row(array($fieldPartida[0],$fieldPartida[5],$fieldAntp[1],''));
	   }
	}
   }
	///// *** Mostrar *** /////
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 4);
	$pdf->SetWidths(array(15, 90, 13, 13));
	$pdf->Cell(1,5);
	$pdf->SetAligns(array('C','R','C','C','C','L'));
	$pdf->Row(array('' ,'Total:',$monto,''));
	/////
}
//---------------------------------------------------

$pdf->Output();
?>  
