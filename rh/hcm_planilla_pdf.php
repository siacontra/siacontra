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
function Cabecera($pdf,$solicitud,$fecha) {
	$pdf->AddPage();
	
	$pdf->Image('../imagenes/logos/contraloria.jpg', 15, 20, 22, 20);
	$pdf->Image('../imagenes/logos/LOGOSNCF.jpg', 170, 20, 20, 20);		
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetXY(5, 20); $pdf->Cell(190, 5, 'Contraloría del Estado Monagas', 0, 1, 'C');
	$pdf->SetXY(5, 25); $pdf->Cell(190, 5, 'Dirección de Recursos Humanos', 0, 1, 'C');
	$pdf->SetXY(5, 30); $pdf->Cell(190, 5, 'Area de Asistencia Medica', 0, 1, 'C');
	$pdf->SetXY(5, 35); $pdf->Cell(190, 5, 'Solicitud de Reembolso / Ayudas Medicas', 0, 1, 'C');	
	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(160, 45); $pdf->Cell(40, 5, 'Solicitud', 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(160, 49); $pdf->Cell(40, 5, 'CEM-RSM-02-02-'.$solicitud.'-2014', 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(160, 54); $pdf->Cell(40, 5, 'Fecha Solicitud', 0, 1, 'C');
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(160, 59); $pdf->Cell(40, 5,formatFechaDMA( $fecha), 0, 1, 'C');
	//$pdf->Cell(190, 10, 'Planilla de Solicitud', 0, 1, 'C');	
	/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(170, 5, 'DESCRIPCION', 1, 1, 'C', 1);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 170,20));
	$pdf->SetAligns(array('C', 'L'));
	$pdf->Row(array($field[0], $field[1]));*/
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();

//	Cuerpo

$query ="
SELECT
rh_solicitudHcm.CodSolicitud,
rh_solicitudHcm.CodPersona,
rh_solicitudHcm.fechaSolicitud,
mastpersonas.NomCompleto,
mastpersonas.Apellido1,
mastpersonas.Apellido2,
mastpersonas.Ndocumento,
mastpersonas.Fnacimiento
FROM
rh_solicitudHcm
INNER JOIN mastpersonas ON rh_solicitudHcm.CodPersona = mastpersonas.CodPersona

WHERE
rh_solicitudHcm.CodSolicitud='$codigo'


";

$query=mysql_query($query) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	
	//print_r ($field);
	
Cabecera($pdf,$field['CodSolicitud'] ,$field['fechaSolicitud']);


    $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 12);
//	$pdf->SetWidths(array(190));
//	$pdf->SetAligns(array('L'));

  //   $pdf->MultiCell(190,5,'Dstadtoda obligación.',1,1,'L');   
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 6, 'I.- DATOS DEL TITULAR:', 1, 1, 'L', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(100,5,'Apellidos y Nombres'     , 1, 0 ,'L', 0);
	$pdf->Cell(30, 5, 'Cedula '    , 1, 0, 'L', 0);
	$pdf->Cell(30, 5, 'Edad'    , 1, 0, 'L', 0);
	$pdf->Cell(30, 5, 'Fecha  Nac.'    , 1, 1, 'L', 0);
     $arrayEdad =getEdad(formatFechaDMA($field['Fnacimiento']));
	//$pdf->SetAligns(array('R'));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(100,15, utf8_decode( $field['NomCompleto'] )  , 1, 0 ,'L', 0);
	$pdf->Cell(30, 15, $field['Ndocumento']     , 1, 0, 'C', 0);
	$pdf->Cell(30, 15,  $arrayEdad [0]    , 1, 0, 'C', 0);
	$pdf->Cell(30, 15, formatFechaDMA($field['Fnacimiento'])     , 1, 1, 'C', 0);
	       
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(190, 6, 'II.- DATOS DEL BENEFICIARIO:', 1, 1, 'L', 1);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	
	
	$pdf->Cell(100,5,'Apellidos y Nombres'     , 1, 0 ,'L', 0);
	$pdf->Cell(30, 5, 'Cedula '    , 1, 0, 'L', 0);
	$pdf->Cell(30, 5, 'Edad'    , 1, 0, 'L', 0);
	$pdf->Cell(30, 5, 'Parentesco'    , 1, 1, 'L', 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(100,15,''     , 1, 0 ,'L', 0);
	$pdf->Cell(30, 15, ''    , 1, 0, 'L', 0);
	$pdf->Cell(30, 15, ''    , 1, 0, 'L', 0);
	$pdf->Cell(30, 15, ''    , 1, 1, 'L', 0);
	
		$pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(100,5,'Motivo o causa de la atencion medica/cita'     , 1, 0 ,'L', 0);
	$pdf->Cell(60, 5, 'Fecha del Evento'    , 1, 0, 'L', 0);
//	$pdf->Cell(30, 15, ''    , 1, 0, 'L', 0);
	$pdf->Cell(30, 5, 'Telefono Titular'    , 1, 1, 'L', 0);
	
		$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(100,15,''     , 1, 0 ,'L', 0);
	$pdf->Cell(60, 15, ''    , 1, 0, 'L', 0);
//	$pdf->Cell(30, 15, ''    , 1, 0, 'L', 0);
	$pdf->Cell(30, 15, ''    , 1, 1, 'L', 0);

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(190, 6, 'III.- REQUISITOS ENTREGADOS:', 1, 1, 'L', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(190, 15, '', 1, 1, 'L', 1);
	
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(190, 6, 'IV.- EXAMENES ENTREGADOS:', 1, 1, 'L', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(190, 15, '', 1, 1, 'L', 1);
	
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(190, 6, 'V.- OBSERVACIONES:', 1, 1, 'L', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(190, 15, '', 1, 1, 'L', 1);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(190, 6, 'VI.- DECLARACIÓN: Para ser llenado única y exclusivamente por el Titular:', 1, 1, 'L', 1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	//$pdf->Cell(190, 60, 'Declaro que las informaciones suministradas a la Dirección de Recursos Humanos de la Contraloría del estado  Monagas y las facturas adjuntas, emanadas de servicios médicos, cumplen con los requerimientos exigidos por la Providencia Administrativa Nº 0071 emanada del Servicio Nacional Integrado de Administración Aduanera y Tributaria SENIAT. Igualmente autorizo al personal médico, paramédico y a todas las clínicas o instituciones hospitalarias a suministrar sin reservas la información requerida en esta planilla, así como cualquier información adicional que solicite la Contraloría del estado Monagas incluyendo copias exactas de sus archivos. Toda información falsa u omisión respecto a mi o a cualquiera de las personas de mi carga familiar, liberará a la Contraloría del estado Monagas de toda obligación.', 1, 1, 'L', 1);
    $pdf->MultiCell(190,5,'Declaro que las informaciones suministradas a la Dirección de Recursos Humanos de la Contraloría del estado  Monagas y las facturas adjuntas, emanadas de servicios médicos, cumplen con los requerimientos exigidos por la Providencia Administrativa Nº 0071 emanada del Servicio Nacional Integrado de Administración Aduanera y Tributaria SENIAT. Igualmente autorizo al personal médico, paramédico y a todas las clínicas o instituciones hospitalarias a suministrar sin reservas la información requerida en esta planilla, así como cualquier información adicional que solicite la Contraloría del estado Monagas incluyendo copias exactas de sus archivos. Toda información falsa u omisión respecto a mi o a cualquiera de las personas de mi carga familiar, liberará a la Contraloría del estado Monagas de toda obligación.',1,1,'L');   
//---------------------------------------------------
      
    $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array( 190));
	$pdf->SetAligns(array( 'C'));
	$pdf->Row(array(''));
	$pdf->Row(array(''));
	$pdf->Row(array('______________________________'));
	$pdf->Row(array('Firma del Titular'));
	
	$pdf->Ln();$pdf->Ln();$pdf->Ln();
	$pdf->Row(array('Lugar: _______________________________ Fecha: _____ de _____________ del año _________'));
  }    
$pdf->Output();
?>
