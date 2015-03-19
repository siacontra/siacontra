<?php
require('fpdf.php');
require('fphp_lg.php');
connect();
//---------------------------------------------------
$_PATHLOGO = getParametro("PATHLOGO");
//---------------------------------------------------
if ($fcoditem != "") $filtro .= "AND (i.CodItem = '".$fcoditem."') ";
if ($fbuscar != "") $filtro .= "AND (i.CodItem LIKE '%".$fbuscar."%' || i.CodInterno LIKE '%".$fbuscar."%' || i.Descripcion LIKE '%".$fbuscar."%' || i.CodUnidad LIKE '%".$fbuscar."%' || p.Descripcion LIKE '%".$fbuscar."%') ";
if ($fcodlinea != "") $filtro .= "AND (i.CodLinea = '".$fcodlinea."' AND i.CodFamilia = '".$fcodfamilia."' AND i.CodSubFamilia = '".$fcodsubfamilia."') ";
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PATHLOGO;		
		$this->Image($_PATHLOGO.'encabezadopdf.jpg', 5, 5, 12, 12);	
		
	    $this->SetFont('Arial', '', 6);
		$this->SetXY(15, 5); $this->Cell(100, 5, "   ".$_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(15, 8); $this->Cell(100, 5, utf8_decode('   DIRECCIÓN DE ADMINISTRACIÓN'), 0, 1, 'L');
		$this->SetXY(15, 11); $this->Cell(100, 5, utf8_decode('   DIVISIÓN DE COMPRAS'), 0, 1, 'L');
		
		$this->SetXY(170, 5); $this->Cell(12, 5, utf8_decode('Fecha: '), 0, 0, 'L'); 
		$this->Cell(60, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetXY(170, 10); $this->Cell(12, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(60, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(5, 20); 
		$this->Cell(195, 5, utf8_decode('Listado de Items'), 0, 1, 'C', 0);
		$this->Ln(5);		
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 12, 50, 10, 31, 18, 13, 13, 13, 15, 15));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->Row(array('Item',
						 '# Interno',
						 'Descripcion',
						 'Und.',
						 'Tipo',
						 'Procedencia',
						 'Linea',
						 'Familia',
						 'S.Familia',
						 'Partida',
						 'Cta. Gasto'));
		$this->Ln(1);						
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
		$this->Rect(9, 258, 197, 0.1, 'DF');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 1, 1);
$pdf->SetAutoPageBreak(1, 20);
$pdf->AddPage();
//---------------------------------------------------
//	consulto el listado
$sql = "SELECT
			i.CodItem,
			i.CodInterno,
			i.Descripcion,
			i.CodUnidad,
			i.CodLinea,
			i.CodFamilia,
			i.CodSubFamilia,
			i.CtaGasto,
			i.PartidaPresupuestal,
			ti.Descripcion AS NomTipoItem,
			p.Descripcion AS NomProcedencia
		FROM
			lg_itemmast i
			INNER JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
			LEFT JOIN lg_procedencias p ON (i.CodProcedencia = p.CodProcedencia)
		WHERE 1 $filtro
		ORDER BY $forden";
$query = mysql_query($sql) or die($sql.mysql_error());
while($field = mysql_fetch_array($query)) {
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['CodItem'],
					$field['CodInterno'],
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					utf8_decode($field['NomTipoItem']),
					utf8_decode($field['NomProcedencia']),
					$field['CodLinea'],
					$field['CodFamilia'],
					$field['CodSubFamilia'],
					$field['PartidaPresupuestal'],
					$field['CtaGasto']));
	$pdf->Ln(1);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
