<?php
require('../fpdf/fpdf.php');
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include ("fphp.php");
connect();
//list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------


list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);


/****************************************************************************************/

 include('../paginas/acceso_db.php');
  mysql_query ("SET NAMES 'utf8'");
	 /// -------------------------------------------
     $query = "
						SELECT
						dt_asistencia.co_asistencia,
						dt_asistencia.co_persona,
						dt_asistencia.co_unidad,
						dt_asistencia.co_modalidad,
						dt_asistencia.co_evaluacion,
						dt_asistencia.fe_solicitud,
						dt_asistencia.fe_aprobacion,
						dt_asistencia.fe_ejecucion,
						dt_asistencia.fe_finalizacion,
						dt_asistencia.tx_status,
						dt_asistencia.tx_observacion,
						dt_asistencia.tx_asunto,
						
						DATE_FORMAT(	dt_asistencia.fe_solicitud, '%H:%i:%s') as hr_solicitud,
                        DATE_FORMAT(	dt_asistencia.fe_solicitud, '%d/%m/%y') as fecha_solicitud, 
                        
                        DATE_FORMAT(	dt_asistencia.fe_ejecucion, '%H:%i:%s') as hr_ejecucion,
                        DATE_FORMAT(	dt_asistencia.fe_ejecucion, '%d/%m/%y') as fecha_ejecucion,
                        
                        DATE_FORMAT(	dt_asistencia.fe_finalizacion, '%H:%i:%s') as hr_fin,
                        DATE_FORMAT(	dt_asistencia.fe_finalizacion, '%d/%m/%y') as fecha_fin
                        
						FROM
						dt_asistencia  WHERE co_asistencia = '".$registro."';
      ";
	 $resultado = mysql_query($query) or die ($query.mysql_error());
	 
	$row = mysql_fetch_array($resultado)  ;
		
/****************************************************************************************/
  
$pdf = new FPDF('P','cm','Letter');
$pdf->AddPage();

//CABECERA
$pdf->Image("../../imagenes/CEM.png", 1, 1,1.2,1);$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(1.2,0.3,'',0,'','C');
$pdf->Cell(11.8,0.3,'CONTRALORIA DEL ESTADO MONAGAS',0,'','L');
$pdf->SetFont('Arial','',6);
$pdf->Cell(4.2,0.3,'Fecha de Solicitud',1,'','C');$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(1.2,0.3,'',0,'','C');
$pdf->Cell(11.8,0.3,'DIRECCION TECNICA',0,'','L');
$pdf->SetFont('Arial','',6);
$pdf->Cell(4.2,0.3,$row['fecha_solicitud'],1,'','C');$pdf->Ln();


$pdf->SetFont('Arial','B',6);
$pdf->Cell(1.2,0.3,'',0,'','C');
$pdf->Cell(11.8,0.3,'UNIDAD DE SISTEMAS',0,'','L');
$pdf->SetFont('Arial','',6);
$pdf->Cell(4.2,0.3,'Hora de Solicitud',1,'','C');$pdf->Ln();


$pdf->SetFont('Arial','B',6);
$pdf->Cell(1.2,0.3,'',0,'','C');
$pdf->Cell(11.8,0.3,'',0,'','C');
$pdf->SetFont('Arial','',6);
$pdf->Cell(4.2,0.3,$row['hr_solicitud'],1,'','C');$pdf->Ln();




//DATOS DE SOLICITUD
$pdf->SetFont('Arial','B',6);
$pdf->Cell(17.2,0.4,'CODIGO DE SOLICITUD: '.$registro,'','','L');$pdf->Ln();
$pdf->Cell(17.2,0.6,'Solicitud de: Asistencia __ Asesoria__ Servicio__','','','C');$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(8.6,0.3,'Funcionario Solicitante',1,'C');
$pdf->Cell(8.6,0.3,'Dependencia:',1,'C');$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(8.6,0.4,$row['co_persona'],1,'L');
$pdf->Cell(8.6,0.4,$row['co_unidad'],1,'L');$pdf->Ln();



//DESCRIPCION DEL PROBLEMA
$pdf->SetFont('Arial','B',6);
$pdf->Cell(17.2,0.3,'Descripcion del Problema',1,'C');$pdf->Ln();
$pdf->Cell(17.2,0.4, $row['tx_asunto'] ,1,'C');$pdf->Ln();

// FUNCIONARIO ASIGNADO -CARGO
$pdf->SetFont('Arial','B',6);
$pdf->Cell(8.6,0.3,'Funcionario Asginado',1,'C');
$pdf->Cell(8.6,0.3,'Cargo:',1,'C');$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(8.6,0.4,$row['hr_solicitud'],1,'L');
$pdf->Cell(8.6,0.4,$row['hr_solicitud'],1,'L');$pdf->Ln();


//FECHA - HORA _ EVALUACION

$pdf->SetFont('Arial','B',6);
$pdf->Cell(4.3,0.3,'FECHA DE EJECUCION',1,'','C');
$pdf->Cell(4.3,0.3,'HORA INICIO:',1,'','C');
$pdf->Cell(4.3,0.3,'FECHA CULMINACION',1,'','C');
$pdf->Cell(4.3,0.3,'HORA CULMINACION',1,'','C');$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(4.3,0.4,$row['fecha_ejecucion'],1,'','C');
$pdf->Cell(4.3,0.4,$row['hr_ejecucion'],1,'','C');
$pdf->Cell(4.3,0.4,$row['fecha_fin'],1,'','C');
$pdf->Cell(4.3,0.4,$row['hr_fin'],1,'','C');$pdf->Ln();



//OBERVACIONES

$pdf->SetFont('Arial','B',6);
$pdf->Cell(12.9,0.3,'OBSERVACIONES',1,'','C');
$pdf->Cell(4.3,0.3,'FIRMA Y SELLO CONFORMIDAD',1,'','C');$pdf->Ln();


$pdf->Cell(12.9,1.5,$row['tx_observacion'],1,'','C');
$pdf->Cell(4.3,1.5,'',1,'','C');$pdf->Ln();

//OBSERVACIONES
$pdf->Output();

?>
