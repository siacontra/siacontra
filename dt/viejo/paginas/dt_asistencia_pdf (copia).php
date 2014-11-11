<?php
require('../fpdf/fpdf.php');
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include ("fphp.php");
connect();
//list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------


list($organismo, $nroOrden, $secuencia, $nrosecuencia)=SPLIT('[-]',$_GET['registro']);

 
$pdf = new FPDF('P','cm','Letter');
$pdf->AddPage();

//CABECERA
$pdf->SetFont('Arial','B',10);
$pdf->Cell(2,1.2,'LOGO',1,'C');
$pdf->Cell(5.4,1.2,'Dirección Técnica',1,'C'); 

$pdf->SetFont('Arial','B',12);
$pdf->Cell(12.2,1.2,'COMPROBANTE DE ASESORIA',1,'','C');$pdf->Ln();

//DATOS DE SOLICITUD
$pdf->SetFont('Arial','B',6);
$pdf->Cell(19.6,0.4,'DATOS DE LA SOLICITUD',1,'L');$pdf->Ln();

$pdf->SetFont('Arial','B',6);
$pdf->Cell(7.4,0.4,'Unidad Organizativa',1,'L');
$pdf->Cell(7.4,0.4,'Funcionario (Nombres y Apellidos / C.I.):',1,'L');
$pdf->Cell(4.8,0.4,'Fecha de Solicitud:',1,'L');$pdf->Ln();

$pdf->SetFont('Arial','B',10);
$pdf->Cell(7.4,1,'',1,'L');
$pdf->Cell(7.4,1,'',1,'L');
$pdf->Cell(4.8,1,'',1,'L');$pdf->Ln();


//ASESORIA PRESTADA
$pdf->SetFont('Arial','B',6);
$pdf->Cell(14.8,0.4,'ASESORIA PRESTADA Y/O ASUNTO ATENDIDO',1,'L');
$pdf->Cell(4.8,0.4,'MODALIDAD',1,'L');$pdf->Ln();

$pdf->Cell(14.8,1.2,'',1,'L');
$pdf->Cell(4.8,1.2,'__ Presencial __ Telefonica',1,'','C');$pdf->Ln();


//FECHA - HORA _ EVALUACION

$pdf->SetFont('Arial','B',6);
$pdf->Cell(3.7,0.4,'FECHA DE EJECUCION',1,'','C');
$pdf->Cell(3.7,0.4,'HORA INICIO:',1,'','C');
$pdf->Cell(3.7,0.4,'HORA CULMINACION',1,'','C');
$pdf->Cell(3.7,0.4,'TIEMPO TOTAL',1,'','C');
$pdf->Cell(4.8,0.4,'EVALUACION DE LA ASESORIA PRESTADA',1,'C');$pdf->Ln();


//ANALISTA Y FUNCIONARIOS - SELLO Y FIRMA

// -----ANALISTAS
$pdf->SetFont('Arial','B',6);
$pdf->Cell(7.4,0.4,'ANALISTAS ASESORES (NOMBRES / C.I.)',1,'','C');

// -----ASESORES
$pdf->SetFont('Arial','B',6);
$pdf->Cell(7.4,0.4,'FUNCIONARIOS RECEPTORES DEL SERVICIO (NOMBRES / C.I.)',1,'','C');


// ------SELLO 
$pdf->Cell(4.8,0.4,'CERTIFICACION DEL SERVICIO',1,'C');$pdf->Ln();

//OBSERVACIONES
$pdf->Output();

?>
