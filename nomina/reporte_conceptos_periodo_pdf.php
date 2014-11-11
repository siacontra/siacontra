<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------
/*include_once ("../clases/MySQL.php");
	
		include_once("../comunes/objConexion.php");
ob_end_clean();*/
	
		
	 	/*$resultado1 = $objConexion->consultar($sql1,'fila');*/
//---------------------------------------------------
//	Imprime la cabedera del documento

function Cabecera($pdf,  $nomina, $proceso, $periodo,  $nom_concepto) {
	$pdf->AddPage('L');
        $pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
        $pdf->SetXY(70, 15); $pdf->Cell(200, 5, utf8_decode('Página: '), 0, 1, 'R'); 
	$pdf->SetXY(70, 15); $pdf->Cell(215, 5, $pdf->PageNo().' de {nb}', 0, 1, 'R');
	$pdf->Cell(20, 15); $pdf->Cell(190, 5, utf8_decode ('Tipo de Nomina: '.$nomina), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 10);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
        $pdf->SetFont('Arial', 'B', 8);	
	$pdf->Cell(190, 5, utf8_decode('REPORTE DEL CONCEPTO: '.$nom_concepto), 0, 1, 'L');
	$pdf->Cell(190, 5, utf8_decode('AÑO: '.$periodo), 0, 1, 'L');
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
        
        $pdf->Ln(5);
      	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(15, 30, 25, 35, 17, 12 , 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 15 ));  
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C','C', 'C'));
      
	$pdf->Row(array(utf8_decode('Cedula'), 
                                              'Nombre y Apellido',
                                              'Cargo', 
                                              'Dependencia', 
                                              'S.B. Mensual',
                                              'Ene.',
                                              'Feb.',
                                              'Mar.',
                                              'Abr.', 
                                              'May.',
                                              'Jun.',
                                              'Jul.',
                                              'Ago.',
                                              'Sep.',
                                              'Oct.',
                                              'Nov.',
                                              'Dic.',
                                              'Total'));
	
       
        
           
}
//	Pie de página.
	function Footer() {

		/*$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->Rect(5, 245, 205, 25, 'DF');
		$this->Rect(73.5, 245, 0.1, 25, 'DF');
		$this->Rect(142, 245, 0.1, 25, 'DF');
		$this->Rect(5, 250, 205, 0.1, 'DF');
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(5, 245);
		$this->Cell(68.5, 5, utf8_decode('Preparado Por'), 0, 1, 'L', 0);
		$this->SetXY(73.5, 245);
		$this->Cell(68.5, 5, utf8_decode('Revisado Por'), 0, 1, 'L', 0);
		$this->SetXY(142, 245);
		$this->Cell(68, 5, utf8_decode('Conformado Por'), 0, 1, 'L', 0);
		
		
		
		$this->SetXY(5, 250);
		$this->Cell(68.5, 5, utf8_decode($_SESSION["NOMBRE_USUARIO_ACTUAL"]), 0, 1, 'L', 0);
		$this->SetXY(73.5, 250);
		$this->Cell(68.5, 5, utf8_decode('Rena Salas'), 0, 1, 'L', 0);
		$this->SetXY(142, 250);
		$this->Cell(68, 5, utf8_decode('Milagros Rivas'), 0, 1, 'L', 0);
		
		$this->SetXY(5, 255);
		$this->Cell(68.5, 5, utf8_decode($this->cargoPreparadoPor), 0, 1, 'C', 0);
		$this->SetXY(73.5, 255);
		$this->Cell(68.5, 5, utf8_decode('ANALISTA JEFE DE ADMINISTRACIÓN'), 0, 1, 'C', 0);
		$this->SetXY(142, 255);
		$this->Cell(68, 5, utf8_decode('DIRECTORA DE  ADMINISTRACION Y PRESUPUESTO'), 0, 1, 'C', 0);*/
	}

//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(10, 15, 10);
//$pdf->cargoPreparadoPor = $resultado1['DescripCargo'];
$pdf->AliasNbPages();
//$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(5, 30);



//	Tipo de Nomina
$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$ftiponom."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);
//	Tipo de Proceso
$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$ftproceso."'";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);
//	Concepto
$sql = "SELECT Descripcion FROM pr_concepto WHERE CodConcepto = '".$codconcepto."'";
$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_concepto) != 0) $field_concepto = mysql_fetch_array($query_concepto);
//	Periodo
list($fecha_desde, $fecha_hasta) = getPeriodoProceso($ftproceso, $fperiodo, $ftiponom);
$periodo_fecha = "DESDE: ".formatFechaDMA($fecha_desde)." HASTA: ".formatFechaDMA($fecha_hasta);



Cabecera($pdf, $field_nomina['Nomina'], $field_proceso['Descripcion'], $fperiodo, $field_concepto['Descripcion']);


//	Cuerpo





$sql = "SELECT
                        ptnec.CodPersona,
			mp.Ndocumento,
			mp.NomCompleto AS Busqueda,
                        me.CodDependencia,
                        me.CodCargo, 
                        rp.DescripCargo, 
                        md.Dependencia,
                        ptnec.Periodo,
                        rp.NivelSalarial as SueldoActual,
                        ptnec.Monto,
			SUM(ptnec.Monto) as MontoTotal
		FROM
                        pr_tiponominaempleadoconcepto ptnec
                        INNER JOIN mastpersonas mp ON (ptnec.CodPersona = mp.CodPersona)
                        INNER JOIN mastempleado me ON (ptnec.CodPersona = me.CodPersona)
                        INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) 
                        INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) 
		WHERE
			ptnec.CodOrganismo = '".$forganismo."' AND
			ptnec.CodTipoNom = '".$ftiponom."' AND
			ptnec.CodTipoProceso = '".$ftproceso."' AND
			SUBSTRING(ptnec.Periodo, 1, 4) = '".$fperiodo."' AND
			ptnec.CodConcepto = '".$codconcepto."'
               GROUP BY ptnec.CodPersona
	       ORDER BY length(mp.Ndocumento), mp.Ndocumento, ptnec.Periodo";

$query = mysql_query($sql) or die ($sql.mysql_error());

while ($field = mysql_fetch_array($query)) {

	$enero=0;
        $febrero=0;
        $marzo=0;
	$abril=0;
	$mayo=0;
	$junio=0;
	$julio=0;
	$agosto=0;
	$septiembre=0;
	$octubre=0;
	$noviembre=0;
	$diciembre=0;
$sum_monto += $field['MontoTotal'];
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
        $pdf->SetWidths(array(15, 30, 25, 35, 17, 12 , 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 15 )); 
        $pdf->SetAligns(array('R', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R','R', 'R'));	

 $sql2 = "SELECT
                        ptnec.CodPersona,
			mp.Ndocumento,
			mp.NomCompleto AS Busqueda,
                        me.CodDependencia,
                        me.CodCargo, 
                        rp.DescripCargo, 
                        md.Dependencia,
                        ptnec.Periodo,
                        me.SueldoActual,
                        ptnec.Monto
		FROM
   			pr_tiponominaempleadoconcepto ptnec
                        INNER JOIN mastpersonas mp ON (ptnec.CodPersona = mp.CodPersona)
                        INNER JOIN mastempleado me ON (ptnec.CodPersona = me.CodPersona)
                        INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) 
                        INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) 
		WHERE
	                ptnec.CodOrganismo = '".$forganismo."' AND
			ptnec.CodTipoNom = '".$ftiponom."' AND
			ptnec.CodTipoProceso = '".$ftproceso."' AND
			SUBSTRING(ptnec.Periodo, 1, 4) = '".$fperiodo."' AND
			ptnec.CodConcepto = '".$codconcepto."' AND
                	ptnec.CodPersona='".$field['CodPersona']."'
	       ORDER BY length(mp.Ndocumento), mp.Ndocumento, ptnec.Periodo";

$query2 = mysql_query($sql2) or die ($sql2.mysql_error());
while ($field2 = mysql_fetch_array($query2)) {
	list($annio,$p)=split('-',$field2['Periodo']); 
	if($p=='01') {$enero=$field2['Monto']; $sum_enero+=$enero;}
	if($p=='02') {$febrero=$field2['Monto']; $sum_febrero+=$febrero;}
	if($p=='03') {$marzo=$field2['Monto']; $sum_marzo+=$marzo;}
	if($p=='04') {$abril=$field2['Monto']; $sum_abril+=$abril;}
	if($p=='05') {$mayo=$field2['Monto']; $sum_mayo+=$mayo;}
	if($p=='06') {$junio=$field2['Monto']; $sum_junio+=$junio;}
	if($p=='07') {$julio=$field2['Monto']; $sum_julio+=$julio;}
	if($p=='08') {$agosto=$field2['Monto']; $sum_agosto+=$agosto;}
	if($p=='09') {$septiembre=$field2['Monto']; $sum_septiembre+=$septiembre;}
	if($p=='10') {$octubre=$field2['Monto']; $sum_octubre+=$octubre;}
	if($p=='11') {$noviembre=$field2['Monto']; $sum_noviembre+=$noviembre;}
	if($p=='12') {$diciembre=$field2['Monto']; $sum_diciembre+=$diciembre;}
	}
	$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), utf8_decode($field['Busqueda']),utf8_decode($field['DescripCargo']), utf8_decode($field['Dependencia']), number_format($field['SueldoActual'], 2, ',', '.'), number_format($enero, 2, ',', '.'), number_format($febrero, 2, ',', '.'), number_format($marzo, 2, ',', '.'), number_format($abril, 2, ',', '.'), number_format($mayo, 2, ',', '.'), number_format($junio, 2, ',', '.'), number_format($julio, 2, ',', '.'), number_format($agosto, 2, ',', '.'), number_format($septiembre, 2, ',', '.'), number_format($octubre, 2, ',', '.'), number_format($noviembre, 2, ',', '.'), number_format($diciembre, 2, ',', '.'), number_format($field['MontoTotal'], 2, ',', '.')));

	             
	if ($pdf->GetY() > 150) Cabecera($pdf, $field_nomina['Nomina'], $field_proceso['Descripcion'], $fperiodo, $field_concepto['Descripcion']);
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 6);
$pdf->Row(array('', '','', 'TOTAL', number_format($sum_sueldo, 2, ',', '.'), number_format($sum_enero, 2, ',', '.'), number_format($sum_febrero, 2, ',', '.'), number_format($sum_marzo, 2, ',', '.'), number_format($sum_abril, 2, ',', '.'), number_format($sum_mayo, 2, ',', '.'), number_format($sum_junio, 2, ',', '.'),number_format($sum_julio, 2, ',', '.'),number_format($sum_agosto, 2, ',', '.'),number_format($sum_septiembre, 2, ',', '.'), number_format($sum_octubre, 2, ',', '.'), number_format($sum_noviembre, 2, ',', '.'), number_format($sum_diciembre, 2, ',', '.'), number_format($sum_monto, 2, ',', '.')));

 
//---------------------------------------------------
if ($pdf->GetY() > 150) Cabecera($pdf, $field_nomina['Nomina'], $field_proceso['Descripcion'], $fperiodo, $field_concepto['Descripcion']);

$y = $pdf->GetY() + 6;
//---------------------------------------------------
//---------------------------------------------------
//list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
//list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
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

$pdf->Rect(10, $y+6, 70, 0.1, "DF");
$pdf->Rect(140, $y+6, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, $y+7);
$pdf->Cell(130, 3, ('Elaborado Por:'), 0, 0, 'L');
$pdf->Cell(80, 3, ('Revisado Por:'), 0, 1, 'L');

$pdf->Cell(130, 3, utf8_decode($field_nomina1['NomCompleto']), 0, 0, 'L');//nombre de quien elabora
$pdf->Cell(80, 3, utf8_decode($field_nomina2['NomCompleto']), 0, 1, 'L');//nombre de quien revisa

$pdf->Cell(130, 3, utf8_decode($field_nomina1['DescripCargo']), 0, 0, 'L');//cargo de quien elabora
$pdf->Cell(80, 3, utf8_decode($field_nomina2['DescripCargo']), 0, 1, 'L');//Cargo de quien revisa

$pdf->Rect(10, $y+24, 70, 0.1, "DF");
$pdf->Rect(140, $y+24, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, $y+25);
$pdf->Cell(130, 3, ('Conformado Por:'), 0, 0, 'L');
$pdf->Cell(80, 3, ('Aprobado Por:'), 0, 1, 'L');
$pdf->Cell(130, 3, utf8_decode($field_nomina3['NomCompleto']), 0, 0, 'L');//nombre de quien conforma
$pdf->Cell(80, 3, utf8_decode($field_nomina4['NomCompleto']), 0, 1, 'L');//nombre de quien aprueba

$pdf->Cell(130, 3, utf8_decode($field_nomina3['DescripCargo']), 0, 0, 'L');//cargo de quien conforma
$pdf->Cell(80, 3, utf8_decode($field_nomina4['DescripCargo']), 0, 1, 'L');//Cargo de quien aprueba

//---------------------------------------------------


$pdf->Output();
?>


