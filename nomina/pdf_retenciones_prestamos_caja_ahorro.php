<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha) {
	$pdf->AddPage();
        $pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, 'Contraloría del estado Monagas', 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, 'Dirección de Recursos Humanos', 0, 1, 'L');
        $pdf->SetXY(20, 10); $pdf->Cell(150, 5, 'Página: ', 0, 1, 'R'); 
	$pdf->SetXY(20, 10); $pdf->Cell(165, 5, $pdf->PageNo().' de {nb}', 0, 1, 'R');
	$pdf->Cell(190, 20, ('REPORTE DE PRESTAMOS DE CAJA DE AHORRO'), 0, 1, 'C');
	//$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');//
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 8);
	//$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');//
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(18, 70, 50,));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', ));
	$pdf->Row(array('CEDULA', 'APELLIDOS Y NOMBRES', 'CUOTA MENSUAL'));
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetMargins(10, 15, 10);
$pdf->SetAutoPageBreak(5, 35);

//	Tipo de Nomina
$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$ftiponom."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);

//	Tipo de Proceso
$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$ftproceso."'";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);

//	Periodo
list($fecha_desde, $fecha_hasta) = getPeriodoProceso($ftproceso, $fperiodo, $ftiponom);
$periodo_fecha = "DESDE: ".formatFechaDMA($fecha_desde)." HASTA: ".formatFechaDMA($fecha_hasta);


Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);

//	Cuerpo
$sql = "SELECT distinct
			mp.CodPersona,
			mp.Ndocumento,
			mp.NomCompleto AS Busqueda,
			ptne.SueldoBasico,
			(SELECT SUM(SueldoBasico) 
				FROM pr_tiponominaempleado 
					WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."'
                                              AND (CodTipoProceso='FIN' OR CodTipoProceso='PRQ')  ) AS SueldoBasicoMes,
			
			(SELECT Monto FROM pr_tiponominaempleadoconcepto WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoproceso = '".$ftproceso."' AND CodConcepto = '0182') AS Monto
		FROM
			mastpersonas mp
			INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (ptne.CodPersona = ptnec.CodPersona AND ptne.CodTipoNom = ptnec.CodTipoNom AND ptne.Periodo = ptnec.Periodo AND ptne.CodTipoproceso = ptnec.CodTipoProceso )
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	if($field['Monto']!=0){
	$sum_ingresos += $field['SueldoBasicoMes'];
	$sum_retenciones += $field['Monto'];
	$sum_aportes += $field['Aporte'];
        $montoenterar=$field['Monto']+$field['Aporte'];
        $sum_totalenterar += $montoenterar;
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(18, 70, 50, 30,));
	$pdf->SetAligns(array('R', 'L', 'R', 'R', ));
	$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), utf8_decode($field['Busqueda']), number_format($field['Monto'], 2, ',', '.')));
	if ($pdf->GetY() > 260) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);
}
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('', 'TOTAL', number_format($sum_retenciones, 2, ',', '.')));
//---------------------------------------------------
//list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
//list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
$pdf->Ln(10);
$y = $pdf->GetY();
$sql1 = "SELECT p.NomCompleto, pu.DescripCargo
	FROM mastpersonas AS p
	JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
	JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
	WHERE p.CodPersona = '".$_SESSION['CODPERSONA_ACTUAL']."'";		
	$query_nomina1 = mysql_query($sql1) or die ($sql1.mysql_error());
	$field_nomina1 = mysql_fetch_array($query_nomina1);//elaborado
$sql2 = "SELECT p.NomCompleto, pu.DescripCargo
	FROM mastpersonas AS p
	JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
	JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
	JOIN mastdependencias AS de ON de.CodDependencia = '0033' and de.CodPersona = me.CodPersona";		
	$query_nomina2 = mysql_query($sql2) or die ($sql2.mysql_error());
	$field_nomina2 = mysql_fetch_array($query_nomina2); //revisado

$sql3 = "SELECT p.NomCompleto, pu.DescripCargo
	FROM mastpersonas AS p
	JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
	JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
	JOIN mastdependencias AS de ON de.CodDependencia = '0010' and de.CodPersona = me.CodPersona";		
	$query_nomina3 = mysql_query($sql3) or die ($sql3.mysql_error());
	$field_nomina3 = mysql_fetch_array($query_nomina3);//conformado

$sql4 = "SELECT p.NomCompleto, pu.DescripCargo
	FROM mastpersonas AS p
	JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
	JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
	JOIN mastdependencias AS de ON de.CodDependencia = '0008' and de.CodPersona = me.CodPersona";		
	$query_nomina4 = mysql_query($sql4) or die ($sql4.mysql_error());
	$field_nomina4 = mysql_fetch_array($query_nomina4);//aprobado

$pdf->Rect(10, $y+6, 65, 0.1, "DF");
$pdf->Rect(110, $y+6, 65, 0.1, "DF");
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, $y+7);
$pdf->Cell(100, 3, ('Elaborado Por:'), 0, 0, 'L');
$pdf->Cell(80, 3, ('Revisado Por:'), 0, 1, 'L');

$pdf->Cell(100, 3, utf8_decode($field_nomina1['NomCompleto']), 0, 0, 'L');//nombre de quien elabora
$pdf->Cell(80, 3, utf8_decode($field_nomina2['NomCompleto']), 0, 1, 'L');//nombre de quien revisa

$pdf->Cell(100, 3, utf8_decode($field_nomina1['DescripCargo']), 0, 0, 'L');//cargo de quien elabora
$pdf->Cell(80, 3, utf8_decode($field_nomina2['DescripCargo']), 0, 1, 'L');//Cargo de quien revisa



$pdf->Rect(10, $y+24, 65, 0.1, "DF");
$pdf->Rect(110, $y+24, 65, 0.1, "DF");
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, $y+25);
$pdf->Cell(100, 3, ('Conformado Por:'), 0, 0, 'L');
$pdf->Cell(80, 3, ('Aprobado Por:'), 0, 1, 'L');
$pdf->Cell(100, 3, utf8_decode($field_nomina3['NomCompleto']), 0, 0, 'L');//nombre de quien conforma
$pdf->Cell(80, 3, utf8_decode($field_nomina4['NomCompleto']), 0, 1, 'L');//nombre de quien aprueba

$pdf->Cell(100, 3, utf8_decode($field_nomina3['DescripCargo']), 0, 0, 'L');//cargo de quien conforma
$pdf->Cell(80, 3, utf8_decode($field_nomina4['DescripCargo']), 0, 1, 'L');//Cargo de quien aprueba

//---------------------------------------------------
$pdf->Output();
?>  
