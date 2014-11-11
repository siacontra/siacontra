<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
extract($_POST);
extract($_GET);
$_PAGINA = "PRIMERA";
//---------------------------------------------------
//	consulto
$sql = "SELECT
			r.*,
			me1.CodEmpleado AS CodPreparado,
			mp1.NomCompleto AS NomPreparado,
			me2.CodEmpleado AS CodRevisado,
			mp2.NomCompleto AS NomRevisado,
			me3.CodEmpleado AS CodAprobado,
			mp3.NomCompleto AS NomAprobado,
			me4.CodEmpleado AS CodConformada,
			mp4.NomCompleto AS NomConformada,
			lc.TipoRequerimiento,
			cc.Descripcion AS NomCentroCosto,
			o.Organismo,
			d.Dependencia,
			c.Descripcion AS NomClasificacion
		FROM
			lg_requerimientos r
			INNER JOIN mastpersonas mp1 ON (r.PreparadaPor = mp1.CodPersona)
			INNER JOIN mastempleado me1 ON (mp1.CodPersona = me1.CodPersona)
			LEFT JOIN mastpersonas mp2 ON (r.RevisadaPor = mp2.CodPersona)
			LEFT JOIN mastempleado me2 ON (mp2.CodPersona = me2.CodPersona)
			LEFT JOIN mastpersonas mp3 ON (r.AprobadaPor = mp3.CodPersona)
			LEFT JOIN mastempleado me3 ON (mp3.CodPersona = me3.CodPersona)
			LEFT JOIN mastpersonas mp4 ON (r.ConformadaPor = mp4.CodPersona)
			LEFT JOIN mastempleado me4 ON (mp4.CodPersona = me4.CodPersona)
			INNER JOIN lg_clasificacion lc ON (r.Clasificacion = lc.Clasificacion)
			INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
			INNER JOIN mastorganismos o ON (r.CodOrganismo = o.CodOrganismo)
			INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
			INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
		WHERE r.CodRequerimiento = '".$registro."'";

$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field;
		global $_PAGINA;
		##
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 5, 5, 15, 12);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $field['Organismo'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode(getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"])), 0, 0, 'L');
		$this->Ln(10);
		##
		$this->SetFillColor(250, 250, 250);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(200, 5, utf8_decode('Requerimiento Nº '.$field['CodInterno']), 0, 0, 'C');
		$this->Ln(10);
		##
		if ($_PAGINA == "PRIMERA") {
			$_PAGINA = "OTRA";
			$this->SetFont('Arial', '', 8);
			$this->Cell(25, 5, 'Dependencia: ', 0, 0, 'L', 1);
			$this->Cell(175, 5, utf8_decode($field['Dependencia']), 0, 0, 'L');
			$this->Ln(6);	
			$this->Cell(25, 5, 'Centro de Costos: ', 0, 0, 'L', 1);
			$this->Cell(70, 5, utf8_decode($field['NomCentroCosto']), 0, 0, 'L');
			$this->Cell(25, 5, utf8_decode('Clasificación: '), 0, 0, 'L', 1);
			$this->Cell(75, 5, utf8_decode($field['NomClasificacion']), 0, 0, 'L');
			$this->Ln(6);
			$this->Cell(25, 5, 'Dirigido A: ', 0, 0, 'L', 1);
			$this->Cell(70, 5, utf8_decode(printValores("DIRIGIDO", $field['TipoClasificacion'])), 0, 0, 'L');
			$this->Cell(25, 5, 'Prioridad: ', 0, 0, 'L', 1);
			$this->Cell(75, 5, utf8_decode(printValores("PRIORIDAD", $field['Prioridad'])), 0, 0, 'L');
			$this->Ln(6);
			$this->Cell(25, 5, 'Fecha Requerida: ', 0, 0, 'L', 1);
			$this->Cell(70, 5, formatFechaDMA($field['FechaRequerida']), 0, 0, 'L');
			$this->Cell(25, 5, 'Estado: ', 0, 0, 'L', 1);
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(75, 5, utf8_decode(printValores("ESTADO-REQUERIMIENTO", $field['Estado'])), 0, 0, 'L');
			$this->SetFont('Arial', '', 8);
			$this->Ln(6);
			$this->Cell(25, 5, 'Comentarios: ', 0, 0, 'L', 1);
			$this->MultiCell(175, 4, utf8_decode($field['Comentarios']), 0, 'J');
			$this->Ln(5);
		}
		##
		if ($_PAGINA != "ULTIMA") {
			$this->SetDrawColor(0, 0, 0); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
			$this->SetFont('Arial', 'B', 8);
			$this->SetWidths(array(10, 20, 20, 120, 15, 15));
			$this->SetAligns(array('R', 'C', 'C', 'L', 'C', 'R'));
			$this->Row(array('#', 'CODIGO', 'ITEM', 'DESCRIPCION', 'UNI.', 'CANT.'));
			$this->Ln(1);
		}
	}
	
	//	Pie de página.
	function Footer() {
		global $field;
		global $_PAGINA;
		##
		if ($_PAGINA == "ULTIMA") {
			$this->SetY(225);
			$y=$this->GetY();
			$this->Rect(20, $y-2, 65, 0.1, "DF");	
			$this->SetXY(20, 225); $this->Cell(20, 4, 'Solicitado Por:', 0, 0, 'R'); $this->Cell(80, 4, utf8_decode($field['NomPreparado']), 0, 1, 'L');
			$this->SetXY(20, 229); $this->Cell(20, 4, 'Fecha:', 0, 0, 'R'); $this->Cell(80, 4, formatFechaDMA($field['FechaPreparacion']), 0, 1, 'L');
			##
			$this->SetY(225);
			$y=$this->GetY();
			$this->Rect(130, $y-2, 65, 0.1, "DF");
			$this->SetXY(130, 225); $this->Cell(20, 4, 'Revisado Por:', 0, 0, 'R'); $this->Cell(80, 4, utf8_decode($field['NomRevisado']), 0, 1, 'L');
			$this->SetXY(130, 229); $this->Cell(20, 4, 'Fecha:', 0, 0, 'R'); $this->Cell(80, 4, formatFechaDMA($field['FechaRevision']), 0, 1, 'L');
			##
			$this->SetY(250);
			$y=$this->GetY();
			$this->Rect(20, $y-2, 65, 0.1, "DF");	
			$this->SetXY(20, 250); $this->Cell(20, 4, 'Conformado Por:', 0, 0, 'R'); $this->Cell(80, 4, utf8_decode($field['NomConformada']), 0, 1, 'L');
			$this->SetXY(20, 254); $this->Cell(20, 4, 'Fecha:', 0, 0, 'R'); $this->Cell(80, 4, formatFechaDMA($field['FechaConformacion']), 0, 1, 'L');
			##
			$this->SetY(250);
			$y=$this->GetY();
			$this->Rect(130, $y-2, 65, 0.1, "DF");
			$this->SetXY(130, 250); $this->Cell(20, 4, 'Aprobado Por:', 0, 0, 'R'); $this->Cell(80, 4, utf8_decode($field['NomAprobado']), 0, 1, 'L');
			$this->SetXY(130, 254); $this->Cell(20, 4, 'Fecha:', 0, 0, 'R'); $this->Cell(80, 4, formatFechaDMA($field['FechaAprobacion']), 0, 1, 'L');
		}
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(1, 25);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
//	Detalles
$sql = "SELECT 
			rd.*,
			i.CodInterno
		FROM 
			lg_requerimientosdet rd
			LEFT JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
		WHERE rd.CodRequerimiento = '".$registro."'
		ORDER BY Secuencia";
$query_det = mysql_query($sql) or die ($sql.mysql_error()); $i=0;
while ($field_det = mysql_fetch_array($query_det)) { $i++;
	if ($field_det['CodItem'] != "") $codigo = $field_det['CodItem']; else $codigo = $field_det['CommoditySub'];
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(10, 20, 20, 120, 15, 15));
	$pdf->SetAligns(array('R', 'C', 'C', 'L', 'C', 'R'));
	$pdf->Row(array($i, 
					$field_det['CodInterno'], 
					$codigo, 
					utf8_decode($field_det['Descripcion']), 
					$field_det['CodUnidad'], 
					$field_det['CantidadPedida']));
	
	if($i==22 || $i==44)
	{
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(255, 255, 255);
			$y=$pdf->GetY();
			$pdf->Rect(10, $y, 200, 0.1, "DF");
			$pdf->Ln(2);
		$_PAGINA='PRIMERA';
		$pdf->AddPage();

		
	}

}
##
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
$y=$pdf->GetY();
$pdf->Rect(10, $y, 200, 0.1, "DF");
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(200, 5, utf8_decode('Total de Items: '.$i), 0, 0, 'L');
$_PAGINA = "ULTIMA";
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
