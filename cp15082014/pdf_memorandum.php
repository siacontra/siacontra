<?php
define('FPDF_FONTPATH','font/');
require('mc_table2.php');
require('fphp.php');
connect();
$dia_actual=date("d");
$mes_actual=date("m"); 
$anio_actual=date("Y");
//---------------------------------------------------
$sql = "SELECT ValorParam FROM mastparametros WHERE ParametroClave = 'INICONSTANCIA'";
$query_param = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_param) != 0) $field_param = mysql_fetch_array($query_param);
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf,$valor) {
	
	$sql="select * from cp_documentointerno where Cod_DocumentoCompleto = '$valor'";
    $qry=mysql_query($sql) or die ($sql.mysql_error());
    $field=mysql_fetch_array($qry);
	
    $fecha = split("-", $field['FechaRegistro']);
	$a= $fecha[0]; $m= $fecha[1]; 	$d= $fecha[2];
	if($m==01){ $mes = 'Enero';}if($m==05){ $mes = 'Mayo';}if($m==09){ $mes = 'Septiembre';}
	if($m==02){ $mes = 'Febrero';}if($m==06){ $mes = 'Junio';}if($m==010){ $mes = 'Octubre';} 
	if($m==03){ $mes = 'Marzo';}if($m==07){ $mes = 'Julio';}if($m==11){ $mes = 'Noviembre';}
	if($m==04){ $mes = 'Abril';}if($m==08){ $mes = 'Agosto';}if($m==12){ $mes = 'Diciembre';}
    //$dia='01';
	// --------------------------------------------------------------
	$sdatos = "select 
                  mo.Organismo,
				  md.Dependencia,
				  mp.NomCompleto,
				  rp.DescripCargo
              from 
			      mastorganismos  mo, 
				  mastdependencias md,
				  mastpersonas mp,
				  rh_puestos rp 
			 where 
			     mo.CodOrganismo='".$field['CodOrganismo']."' and
				 md.CodDependencia='".$field['Cod_Dependencia']."' and
				 mp.CodPersona = '".$field['Cod_Remitente']."' and
				 rp.CodCargo = '".$field['Cod_CargoRemitente']."'";
	$qdatos = mysql_query($sdatos) or die ($sdatos.mysql_error());
	$fdatos = mysql_fetch_array($qdatos);
	// --------------------------------------------------------------
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 20, 11, 20, 18);
	$pdf->Image('../imagenes/logos/LOGOSNCF.jpg', 175, 240, 20, 18);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(50, 50, 50);
	$pdf->SetXY(20, 12); $pdf->Cell(180, 5, utf8_decode('REPUBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
	$pdf->SetXY(20, 17); $pdf->Cell(180, 5, utf8_decode('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'C');
	$pdf->SetXY(20, 22); $pdf->Cell(180, 5, utf8_decode($fdatos['1']), 0, 1, 'C');
	$pdf->Ln(2);
	
	
	/*$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(20, 30); $pdf->Cell(240, 5, utf8_decode('Tucupita'), 0, 1, 'C');
	$pdf->SetXY(20, 30); $pdf->Cell(262, 5, utf8_decode($d), 0, 1, 'C');
	$pdf->SetXY(20, 30); $pdf->Cell(272, 5, utf8_decode('de'), 0, 1, 'C');
	$pdf->SetXY(20, 30); $pdf->Cell(290, 5, utf8_decode($mes), 0, 1, 'C');
	$pdf->SetXY(20, 30); $pdf->Cell(307, 5, utf8_decode('de'), 0, 1, 'C');
	$pdf->SetXY(20, 30); $pdf->Cell(323, 5, utf8_decode($a), 0, 1, 'C');
	$pdf->SetXY(20, 35); $pdf->Cell(310, 5, utf8_decode('200° y 151°'), 0, 1, 'C');*/
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(100, 40);$pdf->Cell(170, 10, utf8_decode('MEMORANDUM'), 0, 1, 'L');
	//$pdf->SetXY(25, 40);$pdf->Cell(90, 10, utf8_decode($field['Cod_DocumentoCompleto']), 0, 1, 'C');
	$pdf->Ln(3);
	
	/*$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(20, 3, 'Ciudadano (a):', 0, 0, 'L');$pdf->Cell(3, 3,'PABLO RODRIGUEZ', 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, '', 0, 0, 'L');$pdf->Cell(3, 3, '' , 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, '', 0, 0, 'L');$pdf->Cell(3, 3, '' , 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, '', 0, 0, 'L');$pdf->Cell(3, 3, '' , 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(5);*/
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(20, 3, 'PARA:', 0, 0, 'L');$pdf->Cell(3, 3,'PABLO RODRIGUEZ', 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, 'DE:', 0, 0, 'L');$pdf->Cell(3, 3, 'RECURSOS HUMANOS' , 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, 'FECHA:', 0, 0, 'L');$pdf->Cell(3, 3, $f['FechaDistribucion'], 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(1);
	$pdf->Cell(20, 3, 'ASUNTO:', 0, 0, 'L');$pdf->Cell(3, 3, 'OTROS', 0, 0, 'L');$pdf->Cell(30, 3,'' , 0, 1, 'L'); $pdf->Ln(5);
}
//---------------------------------------------------
//Cabecera($pdf);
//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
$pdf->Open();
$pdf->SetMargins(25, 10, 30);
$pdf->SetAutoPageBreak(1, 2);

$dia_letras = convertir_a_letras($dia_actual, "entero");
$dia_actual = utf8_decode("$dia_letras ($dia_actual)");
$anio_letras = convertir_a_letras($anio_actual, "entero");
$anio_actual = utf8_decode("$anio_letras ($anio_actual)");
$m = (int) $mes_actual; 
$mes_actual = getNombreMes($m);

//$registros='CEDA-01-0001-2011';
$trabajadores = split(";", $registro);
foreach ($trabajadores as $persona) {
	
	$s="select * from cp_documentointerno where Cod_DocumentoCompleto='".$persona."' ";
	$q=mysql_query($s) or die ($s.mysql_error());
	while ($f = mysql_fetch_array($q)) {
	 Cabecera($pdf,$persona);
	
	$parrafo1 = $f['Contenido'];
	
	
	//$parrafo2 = $f['Contenido'];
	
	
	
	
	
	
	
	/*$sql = "SELECT
				mp.Nacionalidad,
				mp.Ndocumento,
				mp.NomCompleto,
				mp.Sexo,
				me.Fingreso,
				me.CodTipoNom,
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
		$sueldo_letras = convertir_a_letras($field['NivelSalarial'], "moneda");
		$sueldo_basico = utf8_decode(strtoupper("$sueldo_letras")." $sueldo");
		
		$p = $field['TotalIngresos'] - $field['NivelSalarial'];
		$p = number_format($p, 2, '.', '');
		$primas_letras = convertir_a_letras($p, "moneda");
		$primas = utf8_decode(strtoupper("$primas_letras (")."Bs. ".number_format($p, 2, ',', '.').")");
		
		$sueldo_normal = number_format($field['TotalIngresos'], 2, ',', '.');
		$sueldo_normal_letras = convertir_a_letras($field['TotalIngresos'], "moneda");
		$SueldoNormal = utf8_decode(strtoupper("$sueldo_normal_letras")." (Bs. $sueldo_normal)");
		
		if ($field['Sexo']=="F") 
			if ($field['CodTipoNom'] == "02") $funcionario = "la trabajadora"; else $funcionario="la funcionaria"; 
		else 
			if ($field['CodTipoNom'] == "02") $funcionario = "el trabajador"; else $funcionario="el funcionario";
		
		$parrafo1 = utf8_decode("Quien suscribe, Licdo. FREDDY CUDJOE, titular de la cédula de identidad V-10.870.192, en mi condición de Contralor Interventor del Estado Delta Amacuro, hago constar que ".$funcionario." ").strtoupper(trim($field['NomCompleto'])).utf8_decode(", titular de la cédula de identidad número: ").$field['Nacionalidad']."-".number_format($field['Ndocumento'], 0, '', '.').utf8_decode(", labora en este Órgano de Control Fiscal desde la fecha ").formatFechaDMA($field['Fingreso']).utf8_decode(", y actualmente ocupa el cargo de ").strtoupper($field['DescripCargo']).utf8_decode(", devengando una remuneración salarial básica mensual de ").$sueldo_basico.utf8_decode(", más primas por la cantidad de ").$primas.utf8_decode("; totalizando una remuneración normal de ").$SueldoNormal.".";
		
		$parrafo2 = utf8_decode("Constancia que se expide a petición de la parte interesada. En la Ciudad de Tucupita, Estado Delta Amacuro, a ".$dia_actual." día(s) del mes de ".$mes_actual." de ".$anio_actual.".");*/
		
		/*$pie1 = utf8_decode("Licdo. FREDDY CUDJOE");
		$pie2 = utf8_decode("CONTRALOR (I) DEL ESTADO DELTA AMACURO");
		$pie3 = utf8_decode("Designado mediante Resolución Nº. 01-00-000002 de  Fecha 05-01-2009,");
		$pie4 = utf8_decode("Emanada del Despacho del Contralor General de la República,");
		$pie5 = utf8_decode("publicada en G.O.Nº 39.092 de fecha 06-01-2009");*/
		
		$pie1 = utf8_decode("Atentamente,");
		$pie2 = utf8_decode("___________________________________");
		$pie3 = utf8_decode($f['NombCompleto']);
		$pie4 = utf8_decode($f['NombDependencia']);
		//$pie5 = utf8_decode("publicada en G.O.Nº 39.092 de fecha 06-01-2009");
		
		
		$pdf->SetFont('Arial', '', 12);
		$pdf->MultiCell(170, 6, $parrafo1, 0, 'J');
		$pdf->Ln(5);
		//$pdf->MultiCell(170, 6, $parrafo2, 0, 'J');
		//$pdf->Ln(5);
		
		$pdf->SetFont('Arial', '', 12);
		$pdf->SetXY(25, 170); $pdf->Cell(170, 10, $pie1, 0, 1, 'C'); $pdf->Ln(5);
		$pdf->SetXY(25, 175); $pdf->Cell(170, 10, $pie2, 0, 1, 'C');
		
		$pdf->SetFont('Arial', '', 6);
		$pdf->SetXY(25, 180); $pdf->Cell(170, 10, $pie3, 0, 1, 'C');
		$pdf->SetXY(25, 184); $pdf->Cell(170, 10, $pie4, 0, 1, 'C');
		$pdf->SetXY(25, 188); $pdf->Cell(170, 10, $pie5, 0, 1, 'C');
		
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetXY(25, 190); //$pdf->Cell(170, 10, $field_param['ValorParam'], 0, 1, 'L');
		
		/*$pie6 = utf8_decode("Hacia la Consolidación y Fortalecimiento del Sistema Nacional de Control Fiscal. ");
		$pie7 = utf8_decode("Calle Centurión - Quinta Paola  N° 36 / Teléfono (0287) 7211344 - Fax (0287) 7211655");
		$pie8 = utf8_decode("Tucupita Edo Delta Amacuro.");*/
		
		$pdf->SetFont('Arial', 'I', 6);
		$pdf->SetTextColor(100, 100, 100);
		$pdf->SetXY(25, 252); $pdf->Cell(170, 10, $pie6, 0, 1, 'C');
		$pdf->SetXY(25, 256); $pdf->Cell(170, 10, $pie7, 0, 1, 'C');
		$pdf->SetXY(25, 260); $pdf->Cell(170, 10, $pie8, 0, 1, 'C');
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
