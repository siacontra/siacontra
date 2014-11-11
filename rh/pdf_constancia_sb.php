<?php  /// -----------------por probar 17/01/2014 Guidmar
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
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/logo oficio.jpg', 22, 12, 30, 33);
	$pdf->Image('../imagenes/logos/LOGOSNCF.jpg', 170, 18, 25, 25);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(50, 50, 50);
	
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(25, 50); $pdf->Cell(170, 10, 'CONSTANCIA', 0, 1, 'C');
	$pdf->Ln(10);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
$pdf->Open();
$pdf->SetMargins(25, 10, 30);
$pdf->SetAutoPageBreak(1, 2);

$dia_letras = convertir_a_letras($dia_actual, "entero");
$dia_actual = ("$dia_letras ($dia_actual)");
$anio_letras = convertir_a_letras($anio_actual, "entero");
$anio_actual = ("$anio_letras ($anio_actual)");
$m = (int) $mes_actual; 
$mes_actual = getNombreMes($m);

$trabajadores = split(";", $registros);
foreach ($trabajadores as $persona) {
		$sql = "SELECT
				mp.Nacionalidad,
				mp.Ndocumento,
				mp.NomCompleto,
				mp.Direccion,
				mp.Telefono1,
				mp.DocFiscal,
				mp.Sexo,
				me.Fingreso,
				me.CodTipoNom,
				c.NivelSalarial,
				c.DescripCargo,
				me.CodDependencia,
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
		
		$sql_dep="select a.CodDependencia, a.Dependencia, 
		(Select b.Dependencia from mastdependencias b where b.CodDependencia=a.EntidadPadre) as Direccion
		from mastdependencias a where a.CodDependencia='".$field['CodDependencia']."'";
		$query_dep = mysql_query($sql_dep) or die ($sql_dep.mysql_error());
		$field_dep = mysql_fetch_array($query_dep);
		if($field_dep['Direccion']!='')
			{	if($field_dep['Direccion']=='DESPACHO DEL CONTRALOR')
				$textoAD=" a la ". utf8_decode($field_dep['Dependencia']). " perteneciente al ".utf8_decode($field_dep['Direccion']);
				else $textoAD=" a la ". utf8_decode($field_dep['Dependencia']). " perteneciente a la ".utf8_decode($field_dep['Direccion']);}
		else
			$textoAD=" a la ". utf8_decode($field_dep['Dependencia']);
		if($field_dep['Dependencia']=='DESPACHO DEL CONTRALOR')
			$textoAD=" al ". utf8_decode($field_dep['Dependencia']);
		
		$sueldo = number_format($field['NivelSalarial'], 2, ',', '.');
		$sueldo = "(Bs. $sueldo)";
		$sueldo_letras = convertir_a_letras($field['NivelSalarial'], "moneda");
		$sueldo_basico = (strtoupper("$sueldo_letras")." $sueldo");
		
		$p = $field['TotalIngresos'] - $field['SueldoBasico'];
		$p = number_format($p, 2, '.', '');
		$primas_letras = convertir_a_letras($p, "moneda");
		$primas = (strtoupper("$primas_letras (")."Bs. ".number_format($p, 2, ',', '.').")");
		$sn_prueba=$field['NivelSalarial']+$p;
		//$sueldo_normal = number_format($field['TotalIngresos'], 2, ',', '.');
		$sueldo_normal = number_format($sn_prueba, 2, ',', '.');
		//$sueldo_normal_letras = convertir_a_letras($field['TotalIngresos'], "moneda");
		$sueldo_normal_letras = convertir_a_letras($sn_prueba, "moneda");
		
		$SueldoNormal = (strtoupper("$sueldo_normal_letras")." (Bs. $sueldo_normal)");
		
		if ($field['Sexo']=="F") 
			{ if ($field['CodTipoNom'] == "02")  $funcionario = "la trabajadora";  else $funcionario="la funcionaria"; $adscrito=", adscrita ";}
		else 
			{ if ($field['CodTipoNom'] == "02") $funcionario = "el trabajador"; else $funcionario="el funcionario"; $adscrito=", adscrito ";}
		
		$parrafo1 = ("Quien suscribe, Abogada. KARLA AZOCAR, titular de la cédula de identidad V-14.011.460, en su carácter de Directora de Recursos Humanos de la Contraloría del estado Monagas, hace constar que ".$funcionario." ").strtoupper(trim($field['NomCompleto'])).(", titular de la cédula de identidad número: ").$field['Nacionalidad']."-".number_format($field['Ndocumento'], 0, '', '.').(", labora en este Órgano de Control Fiscal desde la fecha ").formatFechaDMA($field['Fingreso']).(", y actualmente ocupa el cargo de ").strtoupper($field['DescripCargo']).$adscrito.$textoAD.(", devengando una remuneración Salarial Básica Mensual de ").$sueldo_basico.".";;
		
		$parrafo2 = ("Constancia que se expide a petición de la parte interesada. En la Ciudad de Maturín, estado Monagas, a los ".$dia_actual." día(s) del mes de ".$mes_actual." de ".$anio_actual.".");
		
		//$parrafo3 = ("Válida por tres (3) meses.");
		//$parrafo3 = ("Dirección: ".$field['Direccion']);
		//$parrafo4 = ("Teléfono: ".$field['Telefono1']);
		//$parrafo5 = ("Rif: ".$field['DocFiscal']);
		
		$pie1 = ("Abg. Karla Azocar");
		$pie2 = ("Directora de Recursos Humanos");
		$pie3 = ("Contraloría del estado Monagas");
		
		$pdf->SetFont('Arial', '', 12);
		$pdf->MultiCell(170, 6, utf8_decode($parrafo1), 0, 'J');
		$pdf->Ln(5);
		$pdf->MultiCell(170, 6, utf8_decode($parrafo2), 0, 'J');
		$pdf->Ln(5);
		$pdf->MultiCell(170, 6, utf8_decode($parrafo3), 0, 'J');
		$pdf->Ln(5);
		$pdf->SetFont('Arial', '', 12);
		//$pdf->MultiCell(170, 6, utf8_decode($parrafo3), 0, 'J');
		//$pdf->MultiCell(170, 6, utf8_decode($parrafo4), 0, 'J');
		//$pdf->MultiCell(170, 6, utf8_decode($parrafo5), 0, 'J');
		$pdf->Ln(15);
		$pdf->MultiCell(170, 6, 'Atentamente,', 0, 'C');
		
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->SetXY(25, 195); $pdf->Cell(170, 10, utf8_decode($pie1), 0, 1, 'C');
		
		
		$pdf->SetFont('Arial', '',12);
		$pdf->SetXY(25, 200); $pdf->Cell(170, 10, utf8_decode($pie2), 0, 1, 'C');
		$pdf->SetXY(25, 205); $pdf->Cell(170, 10, utf8_decode($pie3), 0, 1, 'C');
		$pdf->SetXY(25, 210); $pdf->Cell(170, 10, utf8_decode($pie4), 0, 1, 'C');
		$pdf->SetXY(25, 215); $pdf->Cell(170, 10, utf8_decode($pie5), 0, 1, 'C');
		
		$pdf->SetFont('Arial', 'B', 10);
		//$pdf->SetXY(25, 190); $pdf->Cell(170, 10, utf8_decode($field_param['ValorParam']), 0, 1, 'L');
		
		$pie6 = "Hacia la Transparencia, Fortalecimiento y Consolidación del Sistema Nacional de Control Fiscal. ";
		$pie7 = "Dirección: Calle Sucre con calle Monagas, Edificio Contraloría del estado Monagas, Maturín. Teléfono (0291) 6410441 - 6432713";
		$pie8 = "Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve / Página web: www.contraloriamonagas.gob.ve / Twitter: @CEMonagas01";
		
		$pdf->SetFont('Arial', 'I', 6);
		$pdf->SetTextColor(100, 100, 100);
		$pdf->SetXY(25, 252); $pdf->Cell(170, 10, utf8_decode($pie6), 0, 1, 'C');
		$pdf->SetFont('Arial', '', 6);
		$pdf->SetXY(25, 253); $pdf->Cell(170, 10, '_____________________________________________________________________________________________________________________', 0, 1, 'C');
		$pdf->SetXY(25, 256); $pdf->Cell(170, 10, utf8_decode($pie7), 0, 1, 'C');
		$pdf->SetXY(25, 259); $pdf->Cell(170, 10, utf8_decode($pie8), 0, 1, 'C');
		$pdf->SetXY(25, 262); $pdf->Cell(170, 10, 'RIF: G-20001397-4', 0, 1, 'C');
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
