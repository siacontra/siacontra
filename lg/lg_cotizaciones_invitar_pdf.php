<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
connect();
//---------------------------------------------------
//	obtengo la firma 1
$sql = "SELECT
			mp.Busqueda AS Nombre,
			mp.Sexo,
			p.DescripCargo AS Cargo,
			p2.DescripCargo AS CargoEncargado
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos p ON (me.CodCargo = p.CodCargo)
			LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
		WHERE me.CodEmpleado = '".$_PARAMETRO["FIRMAOP1"]."'";
$query_firma1 = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_firma1) != 0) $field_firma1 = mysql_fetch_array($query_firma1);
//---------------------------------------------------
//	obtengo la firma 2
$sql = "SELECT
			mp.Busqueda AS Nombre,
			mp.Sexo,
			p.DescripCargo AS Cargo,
			p2.DescripCargo AS CargoEncargado
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN rh_puestos p ON (me.CodCargo = p.CodCargo)
			LEFT JOIN rh_puestos p2 ON (me.CodCargoTemp = p2.CodCargo)
		WHERE me.CodEmpleado = '".$_PARAMETRO["FIRMAOP4"]."'";
$query_firma2 = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_firma2) != 0) $field_firma2 = mysql_fetch_array($query_firma2);
//---------------------------------------------------

if ($origen == "cotizar") {
	$codproveedor = $registro;
	$registro = "";
}
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $numero;
		global $codproveedor;
		global $registro;
		
		//	datos generales
		if ($registro != "") {
		
			$sql = "SELECT
						c.CodProveedor,
						c.NomProveedor,
						c.CotizacionNumero,
						c.FechaInvitacion,
						c.NroCotizacionProv,
						c.Numero,
						mp.DocFiscal AS ProRif,
						mp.Direccion AS ProDireccion,
						mp.Telefono1 AS ProTel,
						mp.Fax AS ProFax,
						p.RepresentanteLegal,
						o.Organismo,
						o.DocFiscal AS OrgRif,
						o.Direccion AS OrgDireccion,
						o.Telefono1 AS OrgTel,
						o.Fax1 AS OrgFax,
						o.PaginaWeb,
						CodInterno,
						c.NroSolicitudCotizacion,
						c.UltimoUsuario
					FROM
						lg_cotizacion c
						join  lg_requerimientos as lgr on (lgr.CodRequerimiento=c.CodRequerimiento)
						INNER JOIN mastpersonas mp ON (c.CodProveedor = mp.CodPersona)
						INNER JOIN mastproveedores p ON (p.CodProveedor = mp.CodPersona)
						INNER JOIN mastorganismos o ON (c.CodOrganismo = o.CodOrganismo)
					WHERE c.NroCotizacionProv = '".$registro."'
					GROUP BY Numero, CodProveedor";
		} 
		else {
			$sql = "SELECT
						c.CodProveedor,
						c.NomProveedor,
						c.CotizacionNumero,
						c.FechaInvitacion,
						c.NroCotizacionProv,
						c.Numero,
						mp.DocFiscal AS ProRif,
						mp.Direccion AS ProDireccion,
						mp.Telefono1 AS ProTel,
						mp.Fax AS ProFax,
						p.RepresentanteLegal,
						o.Organismo,
						o.DocFiscal AS OrgRif,
						o.Direccion AS OrgDireccion,
						o.Telefono1 AS OrgTel,
						o.Fax1 AS OrgFax,
						o.PaginaWeb,
						CodInterno,
						c.NroSolicitudCotizacion,
						c.UltimoUsuario
					FROM
						lg_cotizacion c
						join  lg_requerimientos as lgr on (lgr.CodRequerimiento=c.CodRequerimiento)
						INNER JOIN mastpersonas mp ON (c.CodProveedor = mp.CodPersona)
						INNER JOIN mastproveedores p ON (p.CodProveedor = mp.CodPersona)
						INNER JOIN mastorganismos o ON (c.CodOrganismo = o.CodOrganismo)
					WHERE c.Numero = '".$numero."' AND c.CodProveedor = '".$codproveedor."'
					GROUP BY Numero, CodProveedor";
		}
//		echo $registro;exit;
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);
		
		
		//	imprimo la cabecera
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 15, 10);	
		$this->Image($_PARAMETRO["PATHLOGO"].'LOGOSNCF.jpg', 190, 5, 10, 10);	
		$this->SetFont('Arial', '', 8);
		
		
		
		$this->SetXY(20, 5); $this->Cell(190, 5, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
		$this->SetXY(20, 8); $this->Cell(190, 5, utf8_decode($field_mast['Organismo']), 0, 1, 'C');
		$this->SetXY(20, 11); $this->Cell(190, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y PRESUPUESTO'), 0, 1, 'C');
		$this->SetXY(20, 14); $this->Cell(190, 5, utf8_decode('UNIDAD CONTRATANTE'), 0, 1, 'C');
		
//		$this->SetXY(20, 10); $this->MultiCell(100, 5, utf8_decode($field_mast['OrgDireccion']), 0, 'L');
/*		$this->SetXY(20, 17); $this->Cell(40, 5, 'Telf. '.$field_mast['OrgTel'], 0, 0, 'L');
		$this->SetXY(60, 20); $this->Cell(40, 5, 'Fax: '.$field_mast['OrgFax'], 0, 1, 'L');
		$this->SetXY(20, 23); $this->Cell(60, 5, 'R.I.F. '.$field_mast['OrgRif'], 0, 1, 'L');
		$this->SetXY(60, 26); $this->Cell(40, 5, 'Email: '.$field_mast['PaginaWeb'], 0, 1, 'L');*/
	
		$this->SetXY(175, 17); $this->Cell(15, 5, utf8_decode('Invitación N°: '), 0, 0, 'R'); $this->Cell(60, 5, $field_mast['NroSolicitudCotizacion'], 0, 1, 'L');
		$this->SetXY(175, 22); $this->Cell(15, 5, utf8_decode('# Requerimiento: '), 0, 0, 'R'); $this->Cell(60, 5, $field_mast['CodInterno'], 0, 1, 'L');
		$this->SetXY(175, 26); $this->Cell(15, 5, 'Fecha: ', 0, 0, 'R'); $this->Cell(60, 5, formatFechaDMA($field_mast['FechaInvitacion']), 0, 1, 'L');
		$anio=date('Y', strtotime($field_mast['FechaInvitacion']));
		$ncadena=strlen($field_mast['NroSolicitudCotizacion']);
		for($i=0; $i<(4-$ncadena); $i++)
		{
				$cadena='0'.$cadena;
			}
		$cod=$cadena.$field_mast['NroSolicitudCotizacion'].'-'.$anio;
		
		$this->Ln(10);
		$this->SetXY(10, 30);
		$this->SetFillColor(250, 250, 250);
		$this->SetFont('Arial', '', 8);
		$this->Cell(35, 5, 'Proveedor: ', 0, 0, 'L', 1);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(150, 6, utf8_decode($field_mast['NomProveedor']), 0, 1, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(35, 5, 'R.I.F: ', 0, 0, 'L', 1);
		$this->Cell(50, 6, $field_mast['ProRif'], 0, 1, 'L');
		$this->Cell(35, 5, 'Domicilio: ', 0, 0, 'L', 1);
		$this->MultiCell(150, 6, utf8_decode($field_mast['ProDireccion']), 0, 'L');
		$this->Cell(35, 5, 'Telefono Contacto: ', 0, 0, 'L', 1);
		$this->Cell(35, 6, $field_mast['ProTel'], 0, 0, 'L');
		$this->Cell(15, 5, 'Fax: ', 0, 0, 'L', 1);
		$this->Cell(50, 6, $field_mast['ProFax'], 0, 1, 'L');
		$this->Ln(5);
		
		$this->SetXY(10, 60);
		$this->Cell(50, 6, utf8_decode('Me dirijo a usted(es) en la oportunidad de informarle(s) que ha(n) sido seleccionado(s) para participar en la modalidad de selección de contratistas'), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('Consulta de Precios Nº CEM-PC-02-01-'.$cod), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('Se le agradece manifestar la voluntad de participar o de ofertar en un lapso de tres (03) días hábiles a partir de la presente fecha. Se anexa pliego '), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('de condiciones. Indicando Número de Cotización o Presupuesto, Condiciones de Pago, Plazo de Entrega, Fecha, RIF y Validez de Oferta.'), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('El lapso para solicitar aclaratorias es de 01 día hábil que empieza a correr a partir de la fecha del pliego de condiciones correspondiente a la invitación '), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('dichas aclaratorias serán hechas en la Dirección de Administración y Presupuesto de la Contraloría del estado Monagas. La oferta presentada será '), 0, 1, 'L');
		$this->Cell(50, 6, utf8_decode('sometida a evaluación cualitativa y cuantitativa.'), 0, 1, 'L');
		//$this->Cell(50, 6, utf8_decode('En caso de ser otorgada la adjudicación y el proveedor solicite anticipo deberá presentar una fianza por el monto total del anticipo.'), 0, 1, 'L');
		$this->Ln(3);
		//~ $this->SetFont('Arial', 'B', 8);
		//$this->Cell(50, 6, utf8_decode('IMPORTANTE: Caso contrario no se tramitará la Oferta'), 0, 1, 'L');
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(10, 155, 15, 15));
		$this->SetAligns(array('C', 'L', 'C', 'R'));
		$this->Row(array('Item',
						 'Descripcion',
						 'Uni.',
						 'Cant.'));
	}
	//	Pie de página.
	function Footer() {
		global $numero;
		global $codproveedor;
		global $registro;
		global $field_firma1;
		global $field_firma2;
		
		##
		/*
		if ($field_firma1['CargoEncargado'] != "") { 
			$cargo_firma1 = $field_firma1['CargoEncargado'];
			$cargo_firma1 = str_replace("(A)", "(E)", $cargo_firma1);
		}
		else {
			$cargo_firma1 = $field_firma1['Cargo'];
			$cargo_firma1 = str_replace("(A)", "", $cargo_firma1); 
		}*/
		
		if ($field_firma1['Sexo'] == "F") {
			$cargo_firma1 = str_replace("JEFE", "JEFA", $cargo_firma1);
			$cargo_firma1 = str_replace("DIRECTOR", "DIRECTORA", $cargo_firma1);
		}
		/*
		##
		if ($field_firma2['CargoEncargado'] != "") { 
			$cargo_firma2 = $field_firma2['CargoEncargado'];
			$cargo_firma2 = str_replace("(A)", "(E)", $cargo_firma2);
		}
		else {
			$cargo_firma2 = $field_firma2['Cargo'];
			$cargo_firma2 = str_replace("(A)", "", $cargo_firma2); 
		}
		*/
		if ($field_firma2['Sexo'] == "F") {
			$cargo_firma2 = str_replace("JEFE", "JEFA", $cargo_firma2);
			$cargo_firma2 = str_replace("DIRECTOR", "DIRECTORA", $cargo_firma2);
		}
		##
		
		$sql_c = "SELECT
						c.CodProveedor,
						c.NroCotizacionProv,
						c.Numero,
						c.NroSolicitudCotizacion,
						c.UltimoUsuario
					FROM
						lg_cotizacion c
					WHERE c.NroCotizacionProv = '".$registro."'
					GROUP BY CodProveedor";
		$query_c = mysql_query($sql_c) or die ($sql_c.mysql_error());
		if (mysql_num_rows($query_c) != 0) $field_c = mysql_fetch_array($query_c);
		if($field_c["UltimoUsuario"]=='')
		$sql_us="select us.*, p.CodPersona 
		from usuarios us, mastpersonas p
		where us.Usuario='".$_SESSION["USUARIO_ACTUAL"]."' and us.CodPersona=p.CodPersona";
		else
		$sql_us="select us.*, p.CodPersona 
		from usuarios us, mastpersonas p
		where us.Usuario='".$field_c["UltimoUsuario"]."' and us.CodPersona=p.CodPersona";
		$query_us = mysql_query($sql_us) or die ($sql_us.mysql_error());
		if (mysql_num_rows($query_us) != 0) $field_us = mysql_fetch_array($query_us);
		
		
	 	$sql ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,
	
				pu.DescripCargo
				
				from mastpersonas as p
				
				join mastempleado as me on p.CodPersona=me.CodPersona
				
				join rh_puestos as pu on me.CodCargo=pu.CodCargo
				
				where me.CodEmpleado='".$field_us["CodPersona"]."'";    

		$query_asistente = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_asistente) != 0) $field_firma = mysql_fetch_array($query_asistente);
		
		//$cargo_firma1 = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
		$cargo_firma1 = str_replace("(A)", "(E)", $field_firma['DescripCargo']);
		
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$this->SetXY(10, 210);
		$this->Rect(70, 210, 75, 0.1, "D"); 
//		$this->Rect(120, 210, 75, 0.1, "D");
		$this->SetFont('Arial', 'B', 9);
		$this->SetXY(10, 214); $this->MultiCell(195, 3, utf8_decode($field_firma["Nombres"].' '.$field_firma["Apellido1"].' '.$field_firma["Apellido2"]), 0, 'C');
		//$this->SetXY(108, 214); $this->MultiCell(97, 3, utf8_decode($field_firma1['Nombre']), 0, 'C');
		##
		$this->SetXY(10, 218); $this->MultiCell(195, 3, utf8_decode( $field_firma['DescripCargo']), 0, 'C');
		//$this->SetXY(108, 218); $this->MultiCell(97, 3, utf8_decode($cargo_firma1), 0, 'C');
		
		//	datos generales
		if ($registro != "") {
			$sql = "SELECT Condiciones, Observaciones
					FROM lg_cotizacion
					WHERE NroCotizacionProv = '".$registro."'
					GROUP BY Numero, CodProveedor";
		}
		else {
			$sql = "SELECT Condiciones, Observaciones
					FROM lg_cotizacion
					WHERE Numero = '".$numero."' AND CodProveedor = '".$codproveedor."'
					GROUP BY Numero, CodProveedor";
		}
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);



$codproveedor;
		
		//	imprimo pie
		$this->SetY(230);
		$this->SetDrawColor(0, 0, 0); $this->SetFillColor(0, 0, 0); $this->SetTextColor(0, 0, 0);
		$y=$this->GetY();
		$this->Rect(10, $y, 200, 0.1, "DF");
		$this->Ln(2);
		
		$this->SetFillColor(245, 245, 245);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(98, 5, utf8_decode('Condiciones de Entrega: '), 0, 0, 'L', 1);
		$this->Cell(4, 5);
		$this->Cell(98, 5, utf8_decode('Observaciones: '), 0, 1, 'L', 1);
		$this->Ln(2);
		$this->SetDrawColor(255, 255, 255); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', '', 8);	
		$this->SetXY(10, 240); $this->MultiCell(98, 5, utf8_decode($field_mast['Condiciones']), 0, 'L');
		$this->SetXY(112, 240 ); $this->MultiCell(98, 5, utf8_decode($field_mast['Observaciones']), 0, 'L');
		//$this->SetFont('Arial', '', 8);	
		$this->SetXY(10, 250 ); $this->Cell(110, 5, utf8_decode('Dirección: Calle Sucre con calle Monagas, Edificio Contraloría del estado Monagas Teléfono: (0291) 641.04.41. '), 0, 0, 'L', 1);
		$this->SetXY(10, 255 ); $this->Cell(110, 5, utf8_decode('Correo Electrónico: contraloriamonagas@contraloriamonagas.gob.ve'), 0, 0, 'L', 1);
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(1, 80);
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);
//---------------------------------------------------
//	imprimo cuerpo
//	consulto
if ($registro != "") {
	$sql = "SELECT
				c.CodProveedor,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
													   c.CodOrganismo = rd.CodOrganismo AND
													   c.Secuencia = rd.Secuencia)
			WHERE c.NroCotizacionProv = '".$registro."'
			ORDER BY CodProveedor, CodItem, CommoditySub";
} 
elseif ($numero != "") {
	$sql = "SELECT
				c.CodProveedor,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
													   c.CodOrganismo = rd.CodOrganismo AND
													   c.Secuencia = rd.Secuencia)
			WHERE c.Numero = '".$numero."'
			ORDER BY NroCotizacionProv, CodProveedor, CodItem, CommoditySub";
} else {
	$sql = "SELECT
				c.CodProveedor,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
													   c.CodOrganismo = rd.CodOrganismo AND
													   c.Secuencia = rd.Secuencia)
			WHERE c.Numero = '".$numero."' AND CodProveedor = '".$codproveedor."'
			ORDER BY CodProveedor, CodItem, CommoditySub";
}
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	//	una pagina por proveedor
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];
		$codproveedor = $field['CodProveedor'];
		$i = 0;
		$pdf->AddPage();
	}
	
	$i++;
	$pdf->Ln(2);
	$pdf->Row(array($i,
					utf8_decode($field['Descripcion']),
					$field['CodUnidad'],
					number_format($field['CantidadPedida'], 4, ',', '.')));
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
