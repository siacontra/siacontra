<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
list($CodOrganismo, $CodDocumento, $NroDocumento, $TipoMovimiento) = split("[.]", $registro);
//---------------------------------------------------
//	consulto la transaccion
$sql = "SELECT
			oc.CodOrganismo,
			oc.NroInterno,
			oc.NomProveedor,
			t.NroDocumento
		FROM
			lg_commoditytransaccion t
			INNER JOIN lg_ordencompra oc ON (t.CodOrganismo = oc.CodOrganismo AND 
											 t.ReferenciaNroDocumento = oc.NroOrden AND
											 t.Anio = oc.Anio)
		WHERE
			t.CodOrganismo = '".$CodOrganismo."' AND
			t.CodDocumento = '".$CodDocumento."' AND
			t.NroDocumento = '".$NroDocumento."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $field;
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $_GET;
		extract($_POST);
		extract($_GET);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('Ingreso de Activo Fijo N° '.$field['NroDocumento']), 0, 1, 'C', 0);
		$this->Ln(10);
		##	primera pagina solamente
		$this->SetFont('Arial', '', 8); $this->Cell(20, 5, utf8_decode('NroOrden: '), 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8); $this->Cell(75, 5, $field['NroInterno'], 0, 0, 'L');
		$this->Ln(6);
		
		$this->SetFont('Arial', '', 8);
		$this->Cell(20, 5, 'Proveedor: ', 0, 0, 'L', 1);
		$this->Cell(75, 5, utf8_decode($field['NomProveedor']), 0, 0, 'L');
		$this->Ln(6);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(10, 20, 80, 20, 30, 30, 20, 50));
		$this->SetAligns(array('C', 'C', 'L', 'C', 'C', 'C', 'C', 'L'));
		$this->Row(array('#',
						 'Commodity',
						 'Descripcion',
						 'Nro. Serie',
						 'Modelo',
						 'Marca',
						 'Color',
						 utf8_decode('Ubicación')));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		$this->Rect(20, 188, 65, 0.1, "DF");	
		$this->SetXY(20, 190); $this->Cell(65, 4, 'ALMACEN', 0, 0, 'C');
		$this->Rect(195, 188, 65, 0.1, "DF");
		$this->SetXY(195, 190); $this->Cell(65, 4, 'AUTORIZA', 0, 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 5);
$pdf->SetAutoPageBreak(5, 40);
$pdf->AddPage();
//---------------------------------------------------
$i = 0;
//	consulto los detalles
$sql = "SELECT
			af.CommoditySub,
			af.Descripcion,
			af.CodBarra,
			af.NroSerie,
			af.Modelo,
			m.Descripcion AS NomMarca,
			md.Descripcion AS NomColor
		FROM
			lg_commoditytransaccion t
			INNER JOIN lg_ordencompra oc ON (t.CodOrganismo = oc.CodOrganismo AND 
											 t.ReferenciaNroDocumento = oc.NroOrden AND
											 t.Anio = oc.Anio)
			INNER JOIN lg_activofijo af ON (af.CodOrganismo = oc.CodOrganismo AND
											af.Anio = oc.Anio AND
											af.NroOrden = oc.NroOrden AND
											af.CodDocumento = t.CodDocumento AND
											af.NroDocumento = t.NroDocumento)
			LEFT JOIN lg_marcas m ON (af.CodMarca = m.CodMarca)
			LEFT JOIN mastmiscelaneosdet md ON (af.Color = md.CodDetalle AND md.CodMaestro = 'COLOR')
		WHERE
			t.CodOrganismo = '".$CodOrganismo."' AND
			t.CodDocumento = '".$CodDocumento."' AND
			t.NroDocumento = '".$NroDocumento."'";
$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field_detalle = mysql_fetch_array($query_detalle)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(++$i,
					$field_detalle['CommoditySub'],
					utf8_decode($field_detalle['Descripcion']),
					$field_detalle['NroSerie'],
					utf8_decode($field_detalle['Modelo']),
					utf8_decode($field_detalle['NomMarca']),
					utf8_decode($field_detalle['NomColor']),
					utf8_decode($field_detalle['Ubicacion'])));
	$pdf->Ln(1);
}
##	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 260, 0.1, 'DF');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 5, utf8_decode('Nro. Activos. '.$i), 0, 0, 'L', 0);
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
