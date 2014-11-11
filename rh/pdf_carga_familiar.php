<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();
//---------------------------------------------------
$filtro="WHERE me.CodOrganismo='".$forganismo."' and me.Estado='A'";  

if ($codempleado!="") $filtro.=" AND m.CodPersona='".$codempleado."'";
if ($fsexo!="") $filtro.=" AND m.Sexo='".$fsexo."'";

if ($fparentesco!="") $filtro.=" AND r.Parentesco='".$fparentesco."'";
if ($ffsexo!="") $filtro.=" AND r.Sexo='".$ffsexo."'";
if ($chkseguro=="1") $filtro.=" AND r.Afiliado='S'";
if ($fedadh!="") {
	$fechas=setEdadFecha($fedadh);
	list($fechad, $fechah)=SPLIT( '[:]', $fechas);
	$filtro.=" AND (r.FechaNacimiento>='".$fechad."')";
}
if ($fedadd!="") {
	$fechas=setEdadFecha($fedadd);
	list($fechad, $fechah)=SPLIT( '[:]', $fechas);
	$filtro.=" AND (r.FechaNacimiento<='".$fechad."')";
}
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, 'Contraloría del estado Monagas', 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, 'Dirección de Recursos Humanos', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Relación de Carga Familiar', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(15,  70,  20,  20,  15,  15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));
	$pdf->Cell(15, 5); $pdf->Row(array('CODIGO', 'TRABAJADOR', 'CEDULA','FECHA NACIMIENTO', 'EDAD', 'SEGURO MED. FAM.'));
	$y=$pdf->GetY();
	$pdf->SetDrawColor(0, 0, 0); $pdf->Rect(27, $y, 155, 0.2);
}
//---------------------------------------------------
$totalCarga=0;
$totalEmpleados=0;
//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo

$sql="SELECT 
me.CodEmpleado,
m.NomCompleto,
r.Ndocumento as NdocCarga,
m.Ndocumento, r.FechaNacimiento, 
CONCAT(r.ApellidosCarga, ' ',
r.NombresCarga) AS NomFamiliar,
r.Afiliado,

md.Descripcion AS Parentesco 

FROM mastpersonas m 

INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona)
INNER JOIN rh_cargafamiliar r ON (m.CodPersona=r.CodPersona)

INNER JOIN mastmiscelaneosdet md ON (md.CodDetalle=r.Parentesco AND md.CodMaestro='PARENT') $filtro ORDER BY me.CodEmpleado";
/*
$sql="SELECT me.CodEmpleado, m.NomCompleto, m.Ndocumento, r.FechaNacimiento, 

CONCAT(r.ApellidosCarga, ' ', r.NombresCarga) AS NomFamiliar, r.Afiliado, md.Descripcion AS Parentesco 

FROM mastpersonas m 

INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) 
INNER JOIN rh_cargafamiliar r ON (m.CodPersona=r.CodPersona)
INNER JOIN mastmiscelaneosdet md ON (md.CodDetalle=r.Parentesco AND md.CodMaestro='PARENT')

 $filtro ORDER BY LENGTH(m.Ndocumento) DESC";*/
 
 


$query=mysql_query($sql) or die ($sql.mysql_error());

while ($field=mysql_fetch_array($query)) {
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaNacimiento']); $Fnacimiento=$d."/".$m."/".$a;
	list($a, $m, $d)=getEdad($Fnacimiento); $edad=$a;
	if ($field['Afiliado']=="S") $Afiliado="Si"; else $Afiliado="No";
	//	----------------------------------
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//	----------------------------------
	$totalCarga = $totalCarga +1;
	if ($cod!=$field['CodEmpleado']) {
		$totalEmpleados= $totalEmpleados + 1;
		$pdf->Ln(2);
		$cod=$field['CodEmpleado'];
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->SetWidths(array(15,  75,  20,  20,  15,  15));
	    $pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C'));
		$pdf->Cell(15, 5); $pdf->Row(array($field['CodEmpleado'], utf8_decode($field['NomCompleto']), $field['Ndocumento'],'','', '', ''));
		$pdf->Ln(2);
	}
	//	----------------------------------
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array( 5,   25,  60,  15, 20,  15,  15));
	$pdf->SetAligns(array('C', 'L', 'L', 'L','C', 'C', 'C'));

	
	
	$pdf->Cell(15, 5); $pdf->Row(array('', $field['Parentesco'], utf8_decode($field['NomFamiliar']),$field['NdocCarga'], $Fnacimiento, $edad, $Afiliado));
	
	if ($pdf->GetY() > 250) Cabecera($pdf);
}
//---------------------------------------------------
$y=$pdf->GetY();
$pdf->SetDrawColor(0, 0, 0); $pdf->Rect(27, $y, 155, 0.2);
$pdf->SetFont('Arial', 'B', 8);


/*
$sql_total= "SELECT 
(
SELECT COUNT(*)

FROM (

SELECT COUNT(DISTINCT me.CodEmpleado) 

FROM  mastpersonas m 

INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) 
INNER JOIN rh_cargafamiliar r ON (m.CodPersona=r.CodPersona)
INNER JOIN mastmiscelaneosdet md ON (md.CodDetalle=r.Parentesco AND md.CodMaestro='PARENT')   

GROUP BY me.CodEmpleado ORDER BY  LENGTH(m.Ndocumento)  DESC  ) as total";

 
$sql_total=mysql_query($sql_total) or die ($sql_total.mysql_error());
*/

$pdf->Cell(122.5, 4);$pdf->Cell(50, 4, 'Total Funcionarios  :    '.$totalEmpleados , 0, 1, 'L');	
$pdf->Cell(122.5, 4);$pdf->Cell(50, 4, 'Total Carga Familiar:    '.$totalCarga, 0, 1, 'L');	
//-----------------------------------------------------
$pdf->Output();
?>  

