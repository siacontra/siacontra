<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();
$dia_actual=date("d");
$mes_actual=date("m"); 
$anio_actual=date("Y");
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 40, 11, 20, 18);	
	$pdf->SetFont('Times', 'B', 12);
	$pdf->SetTextColor(50, 50, 50);
	$pdf->SetXY(20, 12); $pdf->Cell(180, 5, utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'C');
	$pdf->SetXY(20, 17); $pdf->Cell(180, 5, utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'C');
	$pdf->SetXY(20, 22); $pdf->Cell(180, 5, utf8_decode('Despacho del Contralor'), 0, 1, 'C');
	
	$pdf->SetFont('Times', 'B', 16);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(25, 40); $pdf->Cell(170, 10, 'CONSTANCIA', 0, 1, 'C');
	$pdf->Ln(10);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
$pdf->Open();
$pdf->SetMargins(25, 10, 30);
$pdf->SetAutoPageBreak(1, 2);

$trabajadores = split(";", $registros);
foreach ($trabajadores as $persona) {
	$sql = "SELECT
				mp.Nacionalidad,
				mp.Ndocumento,
				mp.NomCompleto,
				mp.Sexo,
				me.Fingreso,
				c.NivelSalarial,
				c.DescripCargo,
				ptne.*
			FROM
				pr_tiponominaempleado ptne
				INNER JOIN mastpersonas mp ON (ptne.CodPersona = mp.CodPersona)
				INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
				INNER JOIN rh_puestos c ON (me.CodCargo = c.CodCargo)
			WHERE
				ptne.CodPersona = '".$persona."' AND 
				ptne.CodTipoProceso = 'FIN' AND
				ptne.Periodo = (SELECT
									MAX(Periodo)
								FROM
									pr_tiponominaempleado
								WHERE
									CodPersona = '".$persona."' AND
									CodTipoProceso = 'FIN')";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		Cabecera($pdf);
		
		$sueldo = number_format($field['NivelSalarial'], 2, ',', '.');
		$sueldo = "(Bs. $sueldo)";
		$sueldo_letras = convertir_a_letras($field['NivelSalarial']);
		$sueldo_basico = utf8_decode(strtoupper("$sueldo_letras $sueldo"));
		
		$p = $field['TotalIngresos'] - $field['NivelSalarial'];
		$primas_letras = convertir_a_letras($p);
		$primas = utf8_decode(strtoupper("$primas_letras (Bs. ".number_format($p, 2, ',', '.').")"));
		
		$sueldo_normal = number_format($field['TotalIngresos'], 2, ',', '.');
		$sueldo_normal_letras = convertir_a_letras($total_ingresos);
		$SueldoNormal = utf8_decode(strtoupper("$sueldo_normal_letras (Bs. $sueldo_normal)"));
		
		if ($field['Sexo']=="F") $funcionario="la funcionaria"; else $funcionario="el funcionario";
		$dia_letras = entero_a_letras($dia_actual);
		$dia_actual = utf8_decode("$dia_letras ($dia_actual)");
		$anio_letras = entero_a_letras($anio_actual);
		$anio_actual = utf8_decode("$anio_letras ($anio_actual)");
		$m = (int) $mes_actual; 
		$mes_actual = getNombreMes($m);
		
		$parrafo1 = utf8_decode("Quien suscribe, Licdo. FREDDY CUDJOE, titular de la cédula de identidad V-10.870.192, en mi condición de Contralor del Estado Monagas Provisional, hago constar que el funcionario ").strtoupper($field['NomCompleto']).utf8_decode(", titular de la cédula de identidad número: ").$field['Nacionalidad']."-".number_format($field['Ndocumento'], 0, '', '.').utf8_decode(", labora en este organismo desde la fecha ").formatFechaDMA($field['Fingreso']).utf8_decode(", y actualmente ocupa el cargo de ").strtoupper($field['DescripCargo']).utf8_decode(", devengando una remuneración salarial básica mensual de ").$sueldo_basico.utf8_decode(", más primas por la cantidad de ").$primas.utf8_decode("; totalizando una remuneración normal de ").$SueldoNormal.".";
		
		$parrafo2 = utf8_decode("Constancia que se expide a petición de la parte interesada. En la Ciudad de Maturín, Estado Monagas, a los ".$dia_actual." días del mes de ".$mes_actual." de ".$anio_actual.".");
		
		$pie1 = utf8_decode("Licdo. FREDDY CUDJOE");
		$pie2 = utf8_decode("CONTRALOR DEL ESTADO MONAGAS (P)");
		$pie3 = utf8_decode("Designado mediante Resolución Nº. 01-00-000159 de  Fecha 18-09-2013,");
		$pie4 = utf8_decode("Emanada del Despacho de la Contralora General de la República,");
		$pie5 = utf8_decode("publicada en G.O.Nº 40.254 de fecha 19-09-2013");
		
		$pdf->SetFont('Times', '', 14);
		$pdf->MultiCell(170, 8, $parrafo1, 0, 'J');
		$pdf->Ln(5);
		$pdf->MultiCell(170, 8, $parrafo2, 0, 'J');
		
		$pdf->SetFont('Times', 'B', 14);
		$pdf->SetXY(25, 200); $pdf->Cell(170, 10, $pie1, 0, 1, 'C');
		$pdf->SetXY(25, 205); $pdf->Cell(170, 10, $pie2, 0, 1, 'C');
		
		$pdf->SetFont('Times', '', 8);
		$pdf->SetXY(25, 210); $pdf->Cell(170, 10, $pie3, 0, 1, 'C');
		$pdf->SetXY(25, 214); $pdf->Cell(170, 10, $pie4, 0, 1, 'C');
		$pdf->SetXY(25, 218); $pdf->Cell(170, 10, $pie5, 0, 1, 'C');
		
		$pie6 = utf8_decode("Hacia la Transparencia, Fortalecimiento y Consolidación del Sistema Nacional de Control Fiscal.");
		$pie7 = utf8_decode("Calle Sucre con calle Monagas, Edificio Contraloría del estado Monagas / Teléfono (0291) 641.04.41;");
		$pie8 = utf8_decode("Maturín Edo Monagas.");
		
		$pdf->SetFont('Times', 'I', 8);
		$pdf->SetTextColor(100, 100, 100);
		$pdf->SetXY(25, 252); $pdf->Cell(170, 10, $pie6, 0, 1, 'C');
		$pdf->SetXY(25, 256); $pdf->Cell(170, 10, $pie7, 0, 1, 'C');
		$pdf->SetXY(25, 260); $pdf->Cell(170, 10, $pie8, 0, 1, 'C');
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
