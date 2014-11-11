<?php
session_start();
require('fpdf.php');
require('fphp_pf.php');
connect();
//---------------------------------------------------
//	variables globales
$cabecera = "";
$grupo_cabecera = "";

//	consulto los datos de la orden
if ($codigo != "") $filtro = "AND af.CodActuacion = '".$codigo."'";

$sql = "SELECT
			af.*,
			o.Organismo,
			d.Dependencia,
			oe.Organismo AS NomOrganismoExt,
			de.Dependencia AS NomDependenciaExt,
			taf.Descripcion AS NomTipoActuacion,
			p.Descripcion AS NomProceso
		FROM
			pf_actuacionfiscal af
			INNER JOIN pf_tipoactuacionfiscal taf ON (af.CodTipoActuacion = taf.CodTipoActuacion)
			INNER JOIN pf_procesos p ON (af.CodProceso = p.CodProceso)
			INNER JOIN mastorganismos o ON (af.CodOrganismo = o.CodOrganismo)
			LEFT JOIN mastdependencias d ON (af.CodDependencia = d.CodDependencia)
			INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
			LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
		WHERE 1 $filtro";
$query_mast = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	var $widths;
	var $aligns;
	
	//	ancho de las celdas
	function SetWidths($w) {
		$this->widths = $w;
	}
	
	//	alineacion de las celdas
	function SetAligns($a) {
		$this->aligns = $a;
	}
	
	//	dibujar celdas
	function Row($data) {
		// calculo el alto de la celda
		$nb = 0;
		for($i=0;$i<count($data);$i++)
			$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h = 5 * $nb;		
		//	genero un salto de pagina si es necesario
		$this->CheckPageBreak($h);		
		//	dibujo las celdas de las filas
		for($i=0;$i<count($data);$i++) {
			$w = $this->widths[$i];
			$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';			
			//	posicion actual
			$x = $this->GetX();
			$y = $this->GetY();			
			//	dibujo el borde
			$this->Rect($x, $y, $w, $h, "DF");			
			//	imprimo el texto
			$this->MultiCell($w, 5, $data[$i], 0, $a);			
			//	coloco la posicion a la derecha de la celda
			$this->SetXY($x+$w, $y);
		}		
		//	siguiente linea
		$this->Ln($h);
	}
	
	//	valido salto de pagina
	function CheckPageBreak($h) {
		//	si la altura se desborda añado pagina
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	//	calcula el numero de lineas de un MultiCell de acuerdo al ancho y el texto	
	function NbLines($w, $txt) {
		$cw = &$this->CurrentFont['cw'];
		if($w == 0) $w = $this->w-$this->rMargin-$this->x;
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if($nb > 0 and $s[$nb-1] == "\n")
		$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while($i<$nb) {
			$c = $s[$i];
			if($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if($c == ' ')
				$sep = $i;
			$l += $cw[$c];
			if($l > $wmax) {
				if($sep == -1) {
					if($i == $j)
						$i++;
				} else $i=$sep+1;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else $i++;
		}
		return $nl;
	}
	
	//	Cabecera
	function Header() {
		global $field_mast;
		global $cabecera;
		global $grupo_cabecera;
		//------
		$this->SetFillColor(255, 255, 255);
		$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 15, 13);
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(25, 10); $this->Cell(195, 5, $field_mast['Organismo'], 0, 0, 'L');
		$this->SetXY(25, 17); $this->Cell(195, 5, $field_mast['Dependencia'], 0, 0, 'L');
		//------
		$this->SetFont('Arial', '', 9);
		$this->SetXY(165, 11); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(20, 5, date("d-m-Y"));
		$this->SetFont('Arial', '', 9);
		$this->SetXY(165, 15); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(20, 5, $this->PageNo().' de {nb}');
		//------
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY(10, 30);
		$this->Cell(195, 10, utf8_decode('ACTUACIÓN FISCAL ('.$field_mast['CodActuacion'].')'), 0, 1, 'C');
		$this->Ln(5);
		//------
		//	en la primera hoja imprimo la informacion general
		if ($this->PageNo() == 1) {
			$this->SetFont('Arial', 'BU', 9); 
			$this->Cell(30, 5, utf8_decode('INFORMACIÓN GENERAL.'), 0, 0, 'L', 1);	
			$this->Ln(10);
			$this->SetFont('Arial', '', 8);
			
			$this->SetFillColor(245, 245, 245);
			$this->Cell(30, 5, utf8_decode('Tipo de Actuación: '), 0, 0, 'L', 1);
			$this->Cell(80, 5, $field_mast['NomTipoActuacion'], 0, 0, 'L');
			$this->Cell(20, 5, 'Proceso: ', 0, 0, 'L', 1);
			$this->Cell(70, 5, $field_mast['NomProceso'], 0, 1, 'L');
			$this->Ln(1);
			$this->Cell(30, 5, 'Organismo: ', 0, 0, 'L', 1);
			$this->Cell(165, 5, $field_mast['NomOrganismoExt'], 0, 1, 'L');
			$this->Ln(1);
			
			if ($field_mast['NomDependenciaExt'] != "") {
				$this->Cell(30, 5, 'Dependencia: ', 0, 0, 'L', 1);
				$this->Cell(165, 5, $field_mast['NomDependenciaExt'], 0, 1, 'L');
			$this->Ln(1);
			}
			$this->Ln(10);
		} else {
			$this->SetFillColor(245, 245, 245);
			$this->SetFont('Arial', 'B', 8);
			$this->SetWidths(array(7, 86, 46, 46));
			$this->SetAligns(array('C', 'L', 'C', 'C'));
			$this->Row(array('',
							 '',
							 'Planificado',
							 'Real'));
			$this->SetWidths(array(7, 86, 10, 18, 18, 10, 18, 18, 10));
			$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
			$this->Row(array('Ej.',
							 'Actividad',
							 'Dias',
							 'Inicio',
							 utf8_decode('Término'),
							 'Prr.',
							 'Inicio',
							 utf8_decode('Término'),
							 'Ret.'));
			if ($grupo_cabecera == "FASE") {
				$this->SetWidths(array(195));
				$this->SetAligns(array('L'));
			} else {
				$this->SetWidths(array(7, 86, 10, 18, 18, 10, 18, 18, 10));
				$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
			}
		}
	}
	
	//	Pie
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 25);
//----

//	imprimo las actividades
$cabecera = "ACTIVIDADES";
//$pdf->AddPage();
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'BU', 9); 
$pdf->Cell(30, 5, utf8_decode('ACTIVIDADES.'), 0, 0, 'L', 1);	
$pdf->Ln(10);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(7, 96, 46, 46));
$pdf->SetAligns(array('C', 'L', 'C', 'C'));
$pdf->Row(array('',
				'',
				'Planificado',
				'Real'));
$pdf->SetWidths(array(7, 86, 10, 18, 18, 10, 18, 18, 10));
$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
$pdf->Row(array('Ej.',
			    'Actividad',
			    'Dias',
			    'Inicio',
			    utf8_decode('Término'),
				'Prr.',
			    'Inicio',
			    utf8_decode('Término'),
				'Ret.'));
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

$sql = "SELECT
			afd.*,
			DATEDIFF(afd.FechaTerminoCierre, afd.FechaRegistroCierre) AS DiasRetardoCierre,
			DATEDIFF(afd.FechaTerminoReal, NOW()) AS DiasRetardo,
			a.CodFase,
			a.Descripcion,
			f.Descripcion AS NomFase
		FROM
			pf_actuacionfiscaldetalle afd
			INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
			INNER JOIN pf_fases f On (f.CodFase = a.CodFase)
			INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
		WHERE
			afd.CodActuacion = '".$field_mast['CodActuacion']."' AND
			(afd.Estado = 'TE' OR afd.Estado = 'EJ')
			
		ORDER BY CodFase, CodActividad";
$query_actividades = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field_actividades = mysql_fetch_array($query_actividades)) {	$i++;
	$dias = $field_actividades['Duracion'] + $field_actividades['Prorroga'];
	if ($field_actividades['Estado'] == "TE") $diasret = $field_actividades['DiasRetardoCierre'];
	elseif ($field_actividades['Estado'] == "EJ") $diasret = $field_actividades['DiasRetardo'];
	
	if ($grupo != $field_actividades['CodFase']) {
		if ($i>1)  {
			$grupo_cabecera = "ACTIVIDAD";
			$y = $pdf->GetY();
			$fase_duracion = 0;
			$fase_duracion_prorroga = 0;
		}
		$grupo_cabecera = "FASE";
		$grupo = $field_actividades['CodFase'];
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->SetWidths(array(195));
		$pdf->SetAligns(array('L'));
		$pdf->Row(array($field_actividades['CodFase'].' - '.$field_actividades['NomFase']));
	}
	
	$grupo_cabecera = "ACTIVIDAD";
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(7, 86, 10, 18, 18, 10, 18, 18, 10));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$y = $pdf->GetY();
	$pdf->Row(array('',
					$field_actividades['Descripcion'],
					$field_actividades['Duracion'],
					formatFechaDMA($field_actividades['FechaInicio']),
					formatFechaDMA($field_actividades['FechaTermino']),
					$field_actividades['Prorroga'],
					formatFechaDMA($field_actividades['FechaInicioReal']),
					formatFechaDMA($field_actividades['FechaTerminoReal']),
					$diasret));
					
	if ($field_actividades['Estado'] == "TE") {
		$pdf->Image('../imagenes/checked.jpg', 11.5, $y+0.5, 4, 4);
	}
	$fase_duracion += $field_actividades['Duracion'];
	$total_duracion += $field_actividades['Duracion'];
	$fase_duracion_prorroga += $field_actividades['Prorroga'];
	$total_duracion_prorroga += $field_actividades['Prorroga'];
}
//---------------------------------------------------
$pdf->Output();
?> 
