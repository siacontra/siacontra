<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();

$registro=$_REQUEST['registro'];
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
if ($ordenar == "") $orderby = "ORDER BY me.CodEmpleado";
elseif($ordenar == "mp.Ndocumento") $orderby = "ORDER BY LENGTH(mp.Ndocumento), mp.Ndocumento";
else $orderby = "ORDER BY $ordenar";
//---------------------------------------------------



  $sql ="SELECT a.* , b.NomCompleto, b.Ndocumento
FROM rh_beneficio AS a, mastpersonas AS b
WHERE a.codPersona = b.codPersona
AND a.codPersona = '".$registro."'";

$GLOBALS["nombreG"];
$GLOBALS["cedulaG"];
$query=mysql_query($sql) or die ($sql.mysql_error());

while ($field0=mysql_fetch_array($query)) {

	$GLOBALS["nombreG"]=$field0['NomCompleto'];
	$GLOBALS["cedulaG"]=$field0['Ndocumento'];

	//$field=mysql_fetch_array($query);
	$GLOBALS["TRATAMIENTO_PERMANENTE"]=0;
	$GLOBALS["EXTENSION-COBERTURA"]=0;
        $GLOBALS["EXTENSION-COBERTURA-EXTRAORDINARIA"]=0;

	if($field0['codAyudaE']=='20' && $field0['codAyudaE']=='19' && $field0['codAyudaE']=='21'){
		$GLOBALS["TRATAMIENTO_PERMANENTE"]=1;
		$GLOBALS["EXTENSION-COBERTURA"]=1;
		$GLOBALS["EXTENSION-COBERTURA-EXTRAORDINARIA"]=0;
	}
	else if($field0['codAyudaE']=='21'){
		$GLOBALS["EXTENSION-COBERTURA-EXTRAORDINARIA"]=1;
	}
	else if($field0['codAyudaE']=='20'){
		$GLOBALS["TRATAMIENTO_PERMANENTE"]=1;
	}
	else if($field0['codAyudaE']=='19'){
		$GLOBALS["EXTENSION-COBERTURA"]=1;
	}



}

//echo $field['NomCompleto'].'dsfsdfsdf'; 




/*$sql = "SELECT
   a.CodPersona,
   a.Ndocumento as cedula,
   a.NomCompleto as funcionario,
   c.NombresCarga as cargafamiliar,
   b.tipoSolicitud,
   b.fechaEjecucion as fechasolicitud,
   b.estadoBeneficio as estatus,
   b.montoTotal,
   d.NomCompleto as aprobadopor
   b.aprobadoPor
   b.codBeneficio,
   b.nroBeneficio,
   b.tipoSolicitud,
   b.codFamiliar,
   b.codAyudaE,
   b.codRamaS,
   b.anhoEjecucio,
   b.fechaEjecucion,
   b.estadoBeneficio,
   b.montoTotal,
   b.aprobadoPor

FROM
   mastpersonas as a
    JOIN
       rh_beneficio as b on
               a.CodPersona = b.CodPersona
    LEFT JOIN rh_cargafamiliar as c on
               b.codFamiliar = c.codSecuencia
       JOIN mastpersonas as d on
               b.aprobadoPor = d.CodPersona 
 where b.estadoBeneficio='AP'";*/
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$nombre=$nombreG;
	$cedula=$cedulaG;
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'ESTADO DE CUENTA POR BENEFICIO', 0, 1, 'C');	
	
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(5, 30);$pdf->Cell(15, 5, 'Funcionario: ', 0, 0, 'L');
	$pdf->SetXY(20, 30);$pdf->Cell(80, 5, utf8_decode($GLOBALS["nombreG"]), 0, 1, 'L');
	$pdf->SetXY(160, 30);$pdf->Cell(30, 5, 'Fecha: '.date('d-m-Y'), 0, 1, 'L');

	$pdf->SetXY(5, 35);$pdf->Cell(190, 5, 'C.I.: ', 0, 1, 'L');
	$pdf->SetXY(20, 35);$pdf->Cell(190, 5, $GLOBALS["cedulaG"], 0, 1, 'L');

	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(5,15, 40, 40, 15,20, 20,20));
	$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R', 'R','R','R'));
	$pdf->Row(array(utf8_decode('Nº'),'', 'T. Servicio','Limite',utf8_decode('Nº Solicitud'), 'Fecha', 'Monto Solicitado', 'Monto Disponible'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 175, 0.1, "DF");
	$pdf->Ln(2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(5, 5, 5);
Cabecera($pdf);
//	Cuerpo
/*$sql="SELECT a . * , b.NomCompleto, b.Ndocumento, c.codAyudaE AS idAyuda, c.decripcionAyudaE, c.limiteAyudaE, c.limiteAyudaE-a.montoTotal as disponible
FROM rh_beneficio AS a, mastpersonas AS b, rh_ayudamedicaespecifica AS c
WHERE a.codBeneficio =".$registro."
AND a.codPersona = b.codPersona";*/


/*$sql= "SELECT a . * , b.NomCompleto, b.Ndocumento
FROM rh_beneficio AS a, mastpersonas AS b
WHERE a.codBeneficio =$registro
AND a.codPersona = b.codPersona";*/


if($GLOBALS["TRATAMIENTO_PERMANENTE"]==1)
{
	$sql2="SELECT *
	FROM rh_ayudamedicaespecifica
	where Estado='A' and codAyudaE <> 21 AND codAyudaE <> 19";
}

else if($GLOBALS["EXTENSION-COBERTURA"]==1)
{
	$sql2="SELECT *
	FROM rh_ayudamedicaespecifica
	where Estado='A' and codAyudaE <> 20 and codAyudaE <> 21";
}

else if($GLOBALS["EXTENSION-COBERTURA-EXTRAORDINARIA"]==1)
{
	$sql2="SELECT *
	FROM rh_ayudamedicaespecifica
	where Estado='A' and codAyudaE <> 20 and codAyudaE <> 19";
}

else if($GLOBALS["EXTENSION-COBERTURA"]==1  &&  $GLOBALS["TRATAMIENTO_PERMANENTE"]==1 && $GLOBALS["EXTENSION-COBERTURA-EXTRAORDINARIA"]==1 )
{
	$sql2="SELECT *
	FROM rh_ayudamedicaespecifica
	where Estado='A'";
}

else
{
	$sql2="SELECT *
	FROM rh_ayudamedicaespecifica
	where Estado='A' and codAyudaE <> 20 and codAyudaE <> 21 and codAyudaE <> 19";
}

//echo $sql2;
$query2=mysql_query($sql2) or die ($sql2.mysql_error());


	$i=0;
	$totalDisponible = '';

while ($field2=mysql_fetch_array($query2)) {
	//echo $field['codAyudaE'];

	$total=$total+$field2['limiteAyudaE'];
	
	//$nombre='';
	//$numSolicitud='';
	//$monto='';
	$disponible='';
	//$cedula='';
	//$fecha ='';


	//$nombre=$field['NomCompleto'];
	$numSolicitud=$field3['nroBeneficio'];
	$monto=$field3['montoTotal'];
	//$cedula =$field['Ndocumento'];
	$fecha = $field3['fechaEjecucion'];
	//$disponible =$field2['limiteAyudaE']-$monto;

	/*$sql2="SELECT sum( montoTotal ) as cons
		FROM rh_beneficio
			WHERE codPersona=".$field['codPersona']." AND codAyudaE =".$field['codAyudaE'];*/
	

	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(5,15, 40, 40, 15,20, 20,20));
	$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R','R','R','R'));
	//$pdf->Row(array(utf8_decode('Nº'),utf8_decode('Cédula'), 'Nombre', 'Familia', 'T. Servicio',utf8_decode('Nº Solicitud'), 'Fecha', 'Monto Solicitado', 'Monto Disponible'));

	$pdf->Row(array('', '', utf8_decode($field2['decripcionAyudaE']),number_format($field2['limiteAyudaE'],2,',','.'), '', '', '',number_format($disponible,2,',','.')));





	$sql3 ="SELECT a.* , b.NomCompleto, b.Ndocumento
	FROM rh_beneficio AS a, mastpersonas AS b
	WHERE a.codPersona = b.codPersona

	AND a.codPersona = '".$registro."'";


	$query3=mysql_query($sql3) or die ($sql3.mysql_error());
	//$field3=mysql_fetch_array($query3);
	
	$disponible =0;
	$consumido = 0;
	while ($field3=mysql_fetch_array($query3)) {
		
		if($field3['codAyudaE']==$field2['codAyudaE']){
			
				$i++;
				$numSolicitud=$field3['nroBeneficio'];
				$monto=$field3['montoTotal'];
				$consumido=$consumido+$monto;
				//$cedula =$field['Ndocumento'];
				$fecha = $field3['fechaEjecucion'];
				$disponible = $field2['limiteAyudaE']-$consumido;

				$montoConsumido= $montoConsumido+$field3['montoTotal'];
			


				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 6);

				$pdf->SetWidths(array(5,15, 40, 40, 15,20, 20,20));
				$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R','R','R','R'));
				

				$pdf->Row(array($i, '',  '','', $numSolicitud, $fecha, number_format($monto,2,',','.'),number_format($disponible,2,',','.')));
			
		}
						
    }
}

	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(255, 255, 255);
	$y=$pdf->GetY($y+2);
	//$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(1);

	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(5,15, 40, 40, 15,20, 20,20));
	$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R','R','R','R'));	

	$pdf->Row(array('', '',  'Monto Total:',number_format($total,2,',','.'), '', '', number_format($montoConsumido,2,',','.'),number_format($total-$montoConsumido,2,',','.')));


$y=$pdf->GetY($y+2);
$pdf->SetXY(20, $y); $pdf->Cell(190, 5,utf8_decode( 'Realizado por:'), 0, 1, 'L');
$pdf->SetXY(20, $y+5); $pdf->Cell(190, 5, utf8_decode('Nathalie Ordosgoite  ___________'), 0, 1, 'L');
$pdf->SetXY(20, $y+10); $pdf->Cell(190, 5, utf8_decode('ASISTENTE ADMINISTRATIVO V GRADO 09 PASO D'), 0, 1, 'L');


//$y=$pdf->GetY($y+2);
$pdf->SetXY(120, $y); $pdf->Cell(190, 5,utf8_decode( 'Revisado por:'), 0, 1, 'L');
$pdf->SetXY(120, $y+5); $pdf->Cell(190, 5, utf8_decode('Lcda. Rosalba Gómez  ___________'), 0, 1, 'L');
$pdf->SetXY(120, $y+10); $pdf->Cell(190, 5, utf8_decode('Director de RRHH'), 0, 1, 'L');


//---------------------------------------------------

$pdf->Output();
?>  
