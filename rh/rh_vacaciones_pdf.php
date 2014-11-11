<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
list($Anio, $CodSolicitud) = split("[.]", $registro);

$sql = "SELECT
			vs.*,
			p1.Ndocumento AS DocCreadoPor,
			p1.Apellido1 AS ApeCreadoPor1,
			p1.Apellido2 AS ApeCreadoPor2,
			p1.Nombres AS NomCreadoPor,
			e1.Fingreso AS FingCreadoPor,
			pt1.DescripCargo AS CargoCreadoPor,
			d1.Dependencia AS DepCreadoPor,
			md1.Descripcion AS CatCreadoPor,
			o.Organismo
		FROM
			rh_vacacionsolicitud vs
			INNER JOIN mastpersonas p1 ON (p1.CodPersona = vs.CodPersona)
			INNER JOIN mastempleado e1 ON (e1.CodPersona = p1.CodPersona)
			INNER JOIN rh_puestos pt1 ON (pt1.CodCargo = e1.CodCargo)
			LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pt1.CategoriaCargo AND
												 md1.CodMaestro = 'CATCARGO')
			INNER JOIN mastdependencias d1 ON (d1.CodDependencia = vs.CodDependencia)
			INNER JOIN mastorganismos o ON (o.CodOrganismo = vs.CodOrganismo)
		WHERE
			vs.Anio = '".$Anio."' AND
			vs.CodSolicitud = '".$CodSolicitud."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);


//---------------------------------------------------
//	obtengo las firmas
list($_CREADO['Nombre'], $_CREADO['Cargo'], $_CREADO['Nivel']) = getFirma($field['CodPersona']);
list($_REVISADO['Nombre'], $_REVISADO['Cargo'], $_REVISADO['Nivel']) = getFirma($field['RevisadoPor']);
list($_CONFORMADO['Nombre'], $_CONFORMADO['Cargo'], $_CONFORMADO['Nivel']) = getFirma($field['ConformadoPor']);
list($_APROBADO['Nombre'], $_APROBADO['Cargo'], $_APROBADO['Nivel']) = getFirma($field['AprobadoPor']);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $_GET;
		global $field;
		global $field_detalles;
		extract($_POST);
		extract($_GET);
		##	
		//	membrete
		##	obtengo los valores
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);		
		##	colores
		$this->SetDrawColor(0, 0, 0);
		$this->SetTextColor(0, 0, 0);
		##	cuadros y lineas
		$this->Rect(10, 10, 195, 36, 'D');
		$this->Rect(45, 10, 0.1, 36, 'D');
		$this->Rect(170, 10, 0.1, 36, 'D');
		$this->Rect(45, 35, 160, 0.1, 'D');
		$this->Rect(170, 20, 35, 0.1, 'D');
		$this->Rect(170, 25, 35, 0.1, 'D');
		//$this->Rect(170, 30, 35, 0.1, 'D');
		$this->Rect(187.5, 25, 0.1, 0, 'D');
		##	imprimo membrete
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 18, 12, 18, 18);
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(10, 32); $this->MultiCell(35, 4, utf8_decode($field['Organismo']), 0, 'C');
		##
		$this->SetFont('Arial', 'B', 11);
		$this->SetXY(40, 18); $this->MultiCell(125, 5, utf8_decode($NomDependencia), 0, 'C');
		##
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(40, 36);
		$this->Cell(125, 5, 'FORMATO', 0, 2, 'C');
		$this->Cell(125, 5, utf8_decode('PARTICIPACIÓN DE DISFRUTE DE VACACIONES'), 0, 0, 'C');
		##
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(170, 10); $this->Cell(35, 5, utf8_decode('CODIGO:'), 0, 1, 'C');
		$this->SetXY(170, 15); $this->Cell(35, 5, utf8_decode('FOR-DRRHH-014'), 0, 1, 'C');
		$this->SetXY(170, 20); $this->Cell(35, 5, utf8_decode('REVISIÓN:'), 0, 1, 'C');
		//$this->SetXY(170, 25); $this->Cell(17.5, 5, utf8_decode('N°:'), 0, 1, 'C');
		$this->SetXY(180, 25); $this->Cell(17.5, 5, utf8_decode('FECHA:'), 0, 1, 'C');
		//$this->SetXY(170, 30); $this->Cell(17.5, 5, utf8_decode('0'), 0, 1, 'C');
		$this->SetXY(180, 30); $this->Cell(17.5, 5, date('d-m-Y'), 0, 1, 'C');
		$this->SetXY(170, 37); $this->Cell(35, 5, utf8_decode('PAGINA'), 0, 1, 'C');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(170, 41); $this->Cell(35, 5, $this->PageNo().' DE {nb}', 0, 1, 'C');
		//	cuerpo
		##	cuadro
		$this->Rect(10, 55, 195, 210, 'D');
		##	funcionario o trabajador
		list($Nombre) = split("[ ]", $field['NomCreadoPor']);
		if ($field['ApeCreadoPor1'] != "") $Apellido = $field['ApeCreadoPor1']; else $Apellido = $field['ApeCreadoPor2'];
		$NomCompleto = "$Apellido $Nombre";
		##
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY(10, 55); 
		$this->Cell(165, 10, utf8_decode('DISFRUTE DE VACACIONES'), 1, 0, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Cell(30, 5, utf8_decode('FECHA:'), 1, 2, 'C');
		$this->Cell(30, 5, formatFechaDMA(substr($field['FechaAprobacion'], 0, 10)), 1, 0, 'C');
		##
		$this->Ln();
		$this->SetFillColor(200, 200, 200);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(195, 5, utf8_decode('FUNCIONARIO O TRABAJADOR'), 1, 0, 'C', 1);
		##
		$this->Ln();
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', '', 10);
		$this->Cell(30, 5, utf8_decode($field['CatCreadoPor']), 0, 0, 'C');
		$this->SetWidths(array(120, 45));
		$this->SetAligns(array('C', 'C'));
		$this->SetFont('Arial', 'B', 10);
		$this->Row(array(utf8_decode('APELLIDOS Y NOMBRES'),
						 utf8_decode('CEDULA DE IDENTIDAD')));
		$this->SetX(40);
		$this->SetFont('Arial', '', 10);
		$this->Row(array(utf8_decode($NomCompleto),
						 utf8_decode($field['DocCreadoPor'])));
		##
		$this->SetFont('Arial', 'B', 10);
		$this->SetWidths(array(97.5, 97.5));
		$this->SetAligns(array('C', 'C'));
		$this->Row(array(utf8_decode('CARGO'),
						 utf8_decode('DIRECCIÓN O DIVISIÓN')));
		$this->SetFont('Arial', '', 10);
		$this->Row(array(utf8_decode($field['CargoCreadoPor']),
						 utf8_decode($field['DepCreadoPor'])));
		##
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(65, 5, utf8_decode('FECHA DE INGRESO'), 1, 0, 'C');
		$this->Cell(130, 5, utf8_decode('PERIODO VENCIDO'), 1, 0, 'C');
		$this->Ln();
		$this->SetFont('Arial', '', 10);
		$this->Cell(65, 5, formatFechaDMA($field['FingCreadoPor']), 1, 0, 'C');
		$this->Cell(65, 5, $field_detalles['AnioVencido'], 1, 0, 'C');
		$this->Cell(65, 5, $field_detalles['AnioVencido']+1, 1, 0, 'C');
		$this->Ln();
	}
	
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(5, 5);
//---------------------------------------------------
//	consulto
$sql = "SELECT
			vsd.*,
			vp.Anio AS AnioVencido,
			vp.Derecho,
			vp.DiasGozados,
			vp.DiasInterrumpidos,
			vp.TotalUtilizados,
			vp.Pendientes,
			vs.Observaciones as obs
		FROM
			rh_vacacionsolicituddetalle vsd
			INNER JOIN rh_vacacionperiodo vp ON (vp.CodPersona = vsd.CodPersona AND 
												 vp.NroPeriodo = vsd.NroPeriodo)
		    LEFT JOIN rh_vacacionsolicitud  as vs ON vs.CodSolicitud = vsd.CodSolicitud
		WHERE
			vsd.Anio = '".$Anio."' AND
			vsd.CodSolicitud = '".$CodSolicitud."'
		ORDER BY Secuencia,Pendientes Asc "; //  
$query_detalles = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_detalles) == 0) $pdf->AddPage();
while ($field_detalles = mysql_fetch_array($query_detalles)) {
	$pdf->AddPage();
	##
	list($AnioDesde, $MesDesde, $DiaDesde) = split("[./-]", $field_detalles['FechaInicio']);
	list($AnioHasta, $MesHasta, $DiaHasta) = split("[./-]", $field_detalles['FechaFin']);
	list($AnioIncorporacion, $MesIncorporacion, $DiaIncorporacion) = split("[./-]", $field_detalles['FechaIncorporacion']);
	##
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->SetTextColor(0, 0, 0);
	##
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('DETALLES DEL DISFRUTE VACACIONAL'), 1, 1, 'C', 1);
	$pdf->Cell(138, 5, utf8_decode('DIAS A DISFRUTAR'), 1, 0, 'C');
	$pdf->Cell(57, 5, utf8_decode('REINCORPORARSE'), 1, 1, 'C');
	$pdf->Cell(57, 5, utf8_decode('DESDE:'), 1, 0, 'C');
	$pdf->Cell(57, 5, utf8_decode('HASTA:'), 1, 0, 'C');
	$pdf->Cell(24, 5, utf8_decode('TOTAL DIAS'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('DIA'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('MES'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('AÑO'), 1, 1, 'C');
	$pdf->Cell(19, 5, utf8_decode('DIA'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('MES'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('AÑO'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('DIA'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('MES'), 1, 0, 'C');
	$pdf->Cell(19, 5, utf8_decode('AÑO'), 1, 0, 'C');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(24, 10, $field_detalles['NroDias'], 1, 0, 'C');
	$pdf->Cell(19, 10, $DiaIncorporacion, 1, 0, 'C');
	$pdf->Cell(19, 10, $MesIncorporacion, 1, 0, 'C');
	$pdf->Cell(19, 10, $AnioIncorporacion, 1, 1, 'C');
	##
	$pdf->SetY($pdf->GetY()-5);
	$pdf->Cell(19, 5, $DiaDesde, 1, 0, 'C');
	$pdf->Cell(19, 5, $MesDesde, 1, 0, 'C');
	$pdf->Cell(19, 5, $AnioDesde, 1, 0, 'C');
	$pdf->Cell(19, 5, $DiaHasta, 1, 0, 'C');
	$pdf->Cell(19, 5, $MesHasta, 1, 0, 'C');
	$pdf->Cell(19, 5, $AnioHasta, 1, 1, 'C');
	##
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('OBSERVACIONES:'), 1, 1, 'L');
	$pdf->SetFont('Arial', '', 10);
	$pdf->MultiCell(195, 5, utf8_decode($field_detalles['Observaciones']), 0, 'J');
	##
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(10, 180);
	$pdf->Cell(110, 5, utf8_decode('TOTAL PERIODO VACACIONAL '.$field_detalles['AnioVencido'].' - '.($field_detalles['AnioVencido']+1).':'), 0, 0, 'L');
	$pdf->Cell(110, 5, utf8_decode(intval($field_detalles['Derecho']).' DIAS HÁBILES '), 0, 0, 'L');
	$pdf->Ln();
	$pdf->Cell(110, 5, utf8_decode('CANTIDAD DE DIAS DISFRUTADOS (ANTERIOR Y ACTUAL):'), 0, 0, 'L');
	$pdf->Cell(110, 5, utf8_decode(intval($field_detalles['DiasGozados']-$field_detalles['DiasInterrumpidos']).' DIAS HÁBILES '), 0, 0, 'L');
	$pdf->Ln();
	$pdf->Cell(110, 5, utf8_decode('CANTIDAD DE DIAS PENDIENTES:'), 0, 0, 'L');
	$pdf->Cell(110, 5, utf8_decode(intval($field_detalles['Pendientes']).' DIAS HÁBILES '), 0, 0, 'L');
	##
	$pdf->Ln();
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(195, 5, utf8_decode('CONFORMACION Y APROBACION'), 1, 0, 'C', 1);
	$pdf->Ln();
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetXY(10, 200); 
	$pdf->Cell(97.5, 5, utf8_decode('FUNCIONARIO O TRABAJADOR'), 1, 0, 'C');
	$pdf->Cell(97.5, 5, utf8_decode('REVISADO POR'), 1, 0, 'C');
	$pdf->SetXY(10, 230);
	$pdf->Cell(97.5, 5, utf8_decode('CONFORMADO POR'), 1, 0, 'C');
	$pdf->Cell(97.5, 5, utf8_decode('APROBADO POR'), 1, 0, 'C');
	$pdf->Rect(107.5, 200, 0.1, 65, 'D');
	##
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetWidths(array(95.5));
	$pdf->SetAligns(array('C'));
	$pdf->SetXY(11, 206);
	$pdf->Row(array(utf8_decode($_CREADO['Nivel'].' '.$_CREADO['Nombre'])));
	$pdf->SetX(11);
	$pdf->Row(array(utf8_decode($_CREADO['Cargo'])));
	$pdf->SetXY(108.5, 206);
	$pdf->Row(array(utf8_decode($_REVISADO['Nivel'].' '.$_REVISADO['Nombre'])));
	$pdf->SetX(108.5);
	$pdf->Row(array(utf8_decode($_REVISADO['Cargo'])));
	$pdf->SetXY(11, 236);
	$pdf->Row(array(utf8_decode($_CONFORMADO['Nivel'].' '.$_CONFORMADO['Nombre'])));
	$pdf->SetX(11);
	$pdf->Row(array(utf8_decode($_CONFORMADO['Cargo'])));
	$pdf->SetXY(108.5, 236);
	$pdf->Row(array(utf8_decode($_APROBADO['Nivel'].' '.$_APROBADO['Nombre'])));
	$pdf->SetX(108.5);
	$pdf->Row(array(utf8_decode($_APROBADO['Cargo'])));
	##
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(11, 225);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->Cell(20, 5, utf8_decode('Fecha: '.formatFechaDMA(substr($field['FechaCreacion'], 0, 10))), 0, 0, 'L');
	$pdf->SetXY(108.5, 225);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->Cell(20, 5, utf8_decode('Fecha: '.formatFechaDMA(substr($field['FechaRevision'], 0, 10))), 0, 0, 'L');
	$pdf->SetXY(11, 260);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->Cell(20, 5, utf8_decode('Fecha: '.formatFechaDMA(substr($field['FechaConformacion'], 0, 10))), 0, 0, 'L');
	$pdf->SetXY(108.5, 260);
	$pdf->Cell(68, 5, utf8_decode('Firma: '), 0, 0, 'L');
	$pdf->Cell(20, 5, utf8_decode('Fecha: '.formatFechaDMA(substr($field['FechaAprobacion'], 0, 10))), 0, 0, 'L');
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
