<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,('Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5,('Direccion de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetXY(20, 20); $pdf->Cell(190, 5,('Relación de Nómina - '.$nomina), 0, 1, 'L');
	$pdf->SetXY(20, 25); $pdf->Cell(190, 5,($periodo.' '.utf8_decode($proceso)), 0, 1, 'L');
	$pdf->SetXY(20, 30); $pdf->Cell(190, 5, 'Pagina: '.$pdf->PageNo().'/{nb}', 0, 1, 'R');
	$pdf->Ln(5);
	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(8, 20, 60, 20, 20,20,40));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C','C', 'C'));
	$pdf->Row(array('N°','Cédula', 'Apellidos y Nombres','Fecha de Ingreso', 'Años de Servicio CEM','Años de Servicio Adm Pub', 'Salario Básico Mensual'));
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(10, 15, 10);
$pdf->AliasNbPages();
$h = 1;

//	Tipo de Nomina
$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$ftiponom."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);

//	Tipo de Proceso
$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$ftproceso."'";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);

//	Periodo
$periodo = getPeriodoLetras($fperiodo);

Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);

//	Cuerpo
 $sql = "SELECT 	mp.CodPersona,
			mp.Ndocumento, 
			mp.NomCompleto AS Busqueda,
			ptne.TotalNeto,
			rbp.CodBeneficiario,
			rbp.NroDocumento,
			rbp.NombreCompleto,
			bp.Ncuenta,
                        me.SueldoActual,
			me.Fingreso
		FROM
			mastpersonas mp
                        INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
                        INNER JOIN bancopersona bp ON (mp.CodPersona = bp.CodPersona)
			LEFT JOIN rh_beneficiariopension rbp ON (mp.CodPersona = rbp.CodPersona)
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
                        ORDER BY length(mp.Ndocumento), mp.Ndocumento";
//echo $sql;

$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	if ($field['CodBeneficiario'] != "") {
		$ndocumento = $field['NroDocumento'];
		$nombre = $field['Busqueda']." (".$field['NombreCompleto'].")";
	} else {
		$ndocumento = $field['Ndocumento'];
		$nombre = $field['Busqueda'];
	}
	$sum_total += $field['SueldoActual'];
	
	if ($pdf->GetY() > 220) 
	{

    Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);
    }  $annioP=0;
	list($y,$m,$d)=split('-',$field['Fingreso']);
	$fechaC=$d.'-'.$m.'-'.$y;
	$annioC=date("Y")-$y;
	
	if($m>date("m")) $annioC=$annioC-1;
	else if($d>date("d") and $m==date("m")) $annioC=$annioC-1;
	
	$sql_a2="select FechaDesde, FechaHasta from rh_empleado_experiencia where CodPersona='".$field['CodPersona']."' and TipoEnte='02'";
	$query_a2 = mysql_query($sql_a2) or die ($sql_a2.mysql_error());
	while ($field_a2 = mysql_fetch_array($query_a2)) {
	list($yd,$md,$dd)=split('-',$field_a2['FechaDesde']);
	$fechaD=$dd.'-'.$md.'-'.$yd;
	list($yh,$mh,$dh)=split('-',$field_a2['FechaHasta']);
	$fechaH=$dh.'-'.$mh.'-'.$yh;
	$annioP=$yh-$yd;
	if($md>$mh) $annioP=$annioP-1;
	else if($dd>$dh and $md==$mh) $annioP=$annioP-1;
	}

	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(8, 20, 60, 20, 20,20, 40));
	$pdf->SetAligns(array('C', 'C', 'L', 'C', 'C','C', 'C'));
	$pdf->Row(array(''.$h.'', number_format($ndocumento, 0, '', '.'), utf8_decode($nombre), $fechaC,$annioC,$annioP, number_format($field['SueldoActual'], 2, ',', '.')));
	
	$h++;
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('','', '', '','',  'TOTAL', number_format($sum_total, 2, ',', '.')));
//---------------------------------------------------
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
$pdf->Ln(8);
$y = $pdf->GetY();


/*
$pdf->Rect(10, $y, 70, 0.1, "DF");
$pdf->Rect(120, $y, 70, 0.1, "DF");*/
/*
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, $y);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');
$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');
$pdf->Cell(110, 4, ($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, ($caraprobado), 0, 1, 'L');*/



$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(array(10,100, 100));
$pdf->SetAligns(array('','L', 'L'));
$pdf->Row(array('','ELABORADO POR:','REVISADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','Luis Gonzalez','Nhysette Reyes'));
$pdf->Row(array('','ANALISTA DE RECURSOS HUMANOS I','ANALISTA JEFE DE ADMINISTRACIÓN DE PERSONAL'));

$pdf->Ln(10);
$pdf->Row(array('','CONFORMADO POR: ','AUTORIZADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','karla Azocar','Freddy Cudjoe'));
$pdf->Row(array('','DIRECTORA DE RECURSOS HUMANOS ','CONTRALOR DEL ESTADO MONAGAS (P)'));
/*
$pdf->SetFont('Arial', '', 8); 

$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('ELABORADO POR:                                                                             REVISADO POR:'), 0, 0, 'L'); 
$pdf->Ln(9);

$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('_____________                                                                                    _________________'), 0, 0, 'L'); $pdf->Ln();
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('Luis Gonzalez                                                                                       Yusmily Campos    '), 0, 0, 'L'); $pdf->Ln();
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('ANALISTA DE RECURSOS HUMANOS I                                            DIRECTORA DE RECURSOS HUMANOS  '), 0, 0, 'L'); $pdf->Ln();
*/

/*
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('CONFORMADO POR:                                                                           APROBADO POR:'), 0, 0, 'L');
 $pdf->Ln(8);
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('_____________                                                                                     _________________'), 0, 0, 'L'); $pdf->Ln();
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('Cesar Granado                                                                                       Freddy Cudjoe'), 0, 0, 'L'); $pdf->Ln();
$pdf->Cell(10, 4);$pdf->Cell(220, 4, ('DIRECTOR GENERAL                                                                           CONTRALOR DEL ESTADO MONAGAS (P)'), 0, 0, 'L'); $pdf->Ln();
	*/	
//---------------------------------------------------
$pdf->Output();
?>  
