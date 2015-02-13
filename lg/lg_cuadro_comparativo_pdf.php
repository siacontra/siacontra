<?php
extract($_POST);
extract($_GET);
//$registro="0001.0000000016.1.3;0001.0000000016.2.3;0001.0000000020.1.3";
//die($registro);
//---------------------------------------------------

require_once('../tcpdf/config/lang/spa.php');

require_once('../tcpdf/tcpdf.php');

require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
connect();

//---------------------------------------------------
$_PROVEEDORES = array();
$_GENERAL = array();
//---------------------------------------------------
//	Obtengo los proveedores de los requerimientos seleccionados.
//if ($origen == "listarq") {
	list($codorganismo, $codrequerimiento) = split( '[.]', $registro);
	$filtro_proveedores = "(c.CodOrganismo = '".$codorganismo."' AND
							 c.CodRequerimiento = '".$codrequerimiento."')";
	$filtro_items .= "(rd.CodOrganismo = '".$codorganismo."' AND
					   rd.CodRequerimiento = '".$codrequerimiento."')";
/*} else {
	$detalle = split(";", $registro);	$i=0;
	foreach ($detalle as $registro) {	$i++;
		list($codorganismo, $codrequerimiento, $secuencia, $numero) = split( '[.]', $registro);
		if ($i == 1) {
			$filtro_proveedores .= "(c.CodOrganismo = '".$codorganismo."' AND
									 c.CodRequerimiento = '".$codrequerimiento."' AND
									 c.Secuencia = '".$secuencia."')";
			$filtro_items .= "(rd.CodOrganismo = '".$codorganismo."' AND
							   rd.CodRequerimiento = '".$codrequerimiento."' AND
							   rd.Secuencia = '".$secuencia."')";
		} else {
			$filtro_proveedores .= "OR (c.CodOrganismo = '".$codorganismo."' AND
										c.CodRequerimiento = '".$codrequerimiento."' AND
										c.Secuencia = '".$secuencia."')";
			$filtro_items .= "OR (rd.CodOrganismo = '".$codorganismo."' AND
								  rd.CodRequerimiento = '".$codrequerimiento."' AND
								  rd.Secuencia = '".$secuencia."')";
		}
	}
}*/

//proveedores
$sql = "SELECT
			c.CotizacionNumero,
			c.NroCotizacionProv,
			c.CodProveedor,
			c.FlagAsignado,
			mp.NomCompleto AS NomProveedor,
			c.NroSolicitudCotizacion
		FROM
			lg_cotizacion c
			INNER JOIN mastpersonas mp ON (c.CodProveedor = mp.CodPersona)
		WHERE $filtro_proveedores
		GROUP BY CodProveedor
		ORDER BY CodProveedor
		-- LIMIT 0, 3";

		
$query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
//$_ROWS_PROVEEDORES = 3;


$_ROWS_PROVEEDORES_REAL = mysql_num_rows($query_proveedores);

//$_SUMAX = (3 - $_ROWS_PROVEEDORES) * 62;
	$j=0;

while ($field_proveedores = mysql_fetch_array($query_proveedores)) {
	
	$_PROVEEDORES[] = $field_proveedores;
	$proveedor[$j] = $field_proveedores['NomProveedor'];
	$numCotizacion = $field_proveedores['NroSolicitudCotizacion'];
	$j++;
}
//---------------------------------------------------
//	Obtengo los datos generales de la cabecera
$sql = "SELECT
			c.Numero,
			c.FechaInvitacion,
			o.Organismo
		FROM
			lg_cotizacion c
			INNER JOIN mastorganismos o ON (c.CodOrganismo = o.CodOrganismo)
		WHERE $filtro_proveedores
		GROUP BY Numero
		ORDER BY CodProveedor
		-- LIMIT 0, 3";
		
$query_general = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_general) != 0) $_GENERAL = mysql_fetch_array($query_general);

//	Obtengo los items
$sql = "SELECT 
			rd.CodItem, 
			rd.CommoditySub, 
			rd.Descripcion, 
			rd.CodUnidad,
			c.CodProveedor,
			c.Cantidad,
			c.PrecioUnit,
			c.PrecioUnitIva,
			c.ValidezOferta,
			c.DiasEntrega,
			c.Total,
			c.PrecioCantidad,
			fp.Descripcion AS NomFormaPago,
			c.FlagExonerado
		FROM
			lg_requerimientosdet rd
			INNER JOIN lg_cotizacion c ON (rd.CodOrganismo = c.CodOrganismo AND
										   rd.CodRequerimiento = c.CodRequerimiento AND
										   rd.Secuencia = c.Secuencia)
			INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor)
			LEFT JOIN mastformapago fp ON (p.CodFormaPago = fp.CodFormaPago)
		WHERE $filtro_items
		ORDER BY CodItem, CommoditySub, Descripcion, c.CodRequerimiento, c.Secuencia, CodProveedor";
//echo $sql;		
$query_items = mysql_query($sql) or die ($sql.mysql_error());

/*while ($field_items = mysql_fetch_array($query_items)) {
	
	$proveedor[$j] = $field_proveedores['NomProveedor'];
	$numCotizacion = $field_proveedores['NroSolicitudCotizacion'];

}*/

//$r = mysql_num_rows($query_items) / $_ROWS_PROVEEDORES_REAL;

	


// extend TCPF with custom functions

class MYPDF extends TCPDF {



	// Load table data from file	

	public function Header() {

		// Logo
		$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 15, 10, 'JPG', '', '', true, 150, 'L', false, false, 0, false, false, false);				
		$this->Cell(18, 16, '', 0, 1, 'L', 0, '', 0, false);
		$this->Cell(0, 0, utf8_encode('CONTRALOR�A DEL ESTADO MONAGAS'), 0, 1, 'L', 0, '', 0);
		$this->Cell(0, 0, utf8_encode('DIVISI�N DE ADMINISTRACI�N Y PRESUPUESTO'), 0, 0, 'L', 0, '', 0);
		//$this->Cell(18, 16, '', 0, 1, 'L', 0, '', 0, false);
	}

}

	

// create new PDF document

$pdf = new MYPDF('L','mm','LEGAL', true, 'UTF-8', false);

// set document information

$pdf->SetCreator('SAICOM');

$pdf->SetAuthor('SAICOM');

$pdf->SetTitle('Cuadro Comparativo Proveedores');

//$pdf->SetSubject('TCPDF Tutorial');

//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');



// set default header data

//$pdf->SetHeaderData(barra_me2.png, PDF_HEADER_LOGO_WIDTH,'Planilla de Inscripci�n',"Periodo Escolar: 2011_2012\nhhhh");



// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



//set margins

//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetMargins(15, 35, 25,15);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


//set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



//set some language-dependent strings

$pdf->setLanguageArray($l);



// ---------------------------------------------------------

$pdf->AddPage();

$pdf->SetFont('times', '', 11);


		$fecha = date("d-m-Y");//." ".date("j")." de ".$mesLetra[date("n")-1]." de ".date("Y").".";

$pdf->Cell(0, 0, 'Fecha: '.$fecha, 0, 1, 'R', 0, '', 0);

$pdf->Cell(0, 0, '', 0, 1, 'R', 0, '', 0);

// ---------------------------------------------------------------------------------------------------------------------------
$strHTML = '';

$j=1;

//$strHTML = '<table width="570" height="63" border="1"><tr><td>&nbsp;</td>';
	
	//$strHTML ='<tr>';//<td rowspan="2" width="250">&nbsp;</td><td rowspan="2"></td>';
	
	$strHTML .='<tr><td  width="200" rowspan="2" ><strong ><br /><br />Item/Commodity</strong></td><td width="40" rowspan="2" ><strong><br /><br />Unid.</strong></td>';
	
	
	
	for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
	{
		  
			$strHTML .='
			<td width="240" height="" colspan="3"><strong>Cotizaci&oacute;n N&deg;: '.$numCotizacion.'</strong><br />'.$proveedor[$i].'</td>';
		
	}
	
	$strHTML .='</tr><tr>';
	
	for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
	{
		  
			$strHTML .='<td><strong>Cant.</strong></td><td><strong>Precio Unit.</strong></td><td><strong>Total</strong></td>';
		
	}
	
	$strHTML .='</tr>';
	$t = 0;
	
	$sumaTotalProveedor = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$sumaImpuestoProveedor = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$total = array(0,0,0,0,0,0,0,0,0,0,0,0);
	
		while($field_items = mysql_fetch_array($query_items)) 
		{
			$strHTML .= '<tr><td>'.$field_items['Descripcion'].'</td><td>'.$field_items['CodUnidad'].'</td>';
			
			for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
			{	
				
				$strHTML .= '<td>'.number_format($field_items['Cantidad'], 4, ',', '.').'</td><td >'.number_format($field_items['PrecioUnit'], 4, ',', '.').'</td><td>'.number_format($field_items['Cantidad']*$field_items['PrecioUnit'], 4, ',', '.').'</td>';

				$sumaTotalProveedor[$i] += $field_items['Cantidad']*$field_items['PrecioUnit'];

				if ($field_items['FlagExonerado'] == 'N')
				{
					$sumaImpuestoProveedor[$i] += ($field_items['Cantidad']*$field_items['PrecioUnit'])*0.12;
					
				} else {
				
					$sumaImpuestoProveedor[$i] += 0;
				}
				
				$total [$i] = $sumaTotalProveedor[$i] + $sumaImpuestoProveedor[$i];
				
				if(($i+1) != $_ROWS_PROVEEDORES_REAL)
				{
					$field_items = mysql_fetch_array($query_items);
				}
				
				
				
	
			}
			
			$t++;
			
			
			
			$strHTML .= '</tr>';
			
		}
		
		$strHTML .= '<tr><td colspan="2" align="left"><strong>Sub-Total</strong></td>';
		
		for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
		{	
		
			$strHTML .= '<td colspan="3" align="rigth">'.number_format($sumaTotalProveedor[$i], 2, ',', '.').'&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		}
		
		$strHTML .= '</tr><tr><td colspan="2" align="left"><strong>Impuesto</strong></td>';
		
		for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
		{	
		
			$strHTML .= '<td colspan="3" align="rigth">'.number_format($sumaImpuestoProveedor[$i], 4, ',', '.').'&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		}
		
		$strHTML .= '</tr><tr><td colspan="2" align="left"><strong>Total</strong></td>';
		
		for($i = 0; $i < $_ROWS_PROVEEDORES_REAL; $i++)
		{	
		
			$strHTML .= '<td colspan="3" align="rigth">'.number_format($total[$i], 2, ',', '.').'&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		}
		
		$strHTML .= '</tr>';
		
		
/*c.CodProveedor,
			c.Cantidad,
			c.PrecioUnit,
			c.PrecioUnitIva,
			c.ValidezOferta,
			c.DiasEntrega,
			c.Total,
			c.PrecioCantidad,
			fp.Descripcion AS NomFormaPago*/
			
//	$strHTML .= '<tr><td>Cant.</td><td>Precio Unit.</td><td>Total</td></tr>';
//		<td>Cant.</td><td>Precio Unit.</td><td>Total</td></tr>


//set auto page breaks

$sql1 ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where p.CodPersona='".$_SESSION["CODPERSONA_ACTUAL"]."'";    

    $query_anlista = mysql_query($sql1) or die ($sql1.mysql_error());
	
	$fetchAnalista = mysql_fetch_array($query_anlista);	
	
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


$tb1 = <<<EOD
<br><br><br><table align="center" width="100%" height="63" border="1">

	   $strHTML
	 
	</table>

	
EOD;


$tb12 = '

<table width="650" height="120" border="0">
	  <tr>
		<td width="424"><table width="100%" border="0">
		  <tr>
			<td width="414" align="center">Elaborado Por: </td>
		  </tr>
		  <tr>
			<td align="center">'.$_SESSION["NOMBRE_USUARIO_ACTUAL"].'</td>
		  </tr>
		  <tr>
			<td align="center">'.$fetchAnalista['DescripCargo'].'</td>
		  </tr>
		  
		</table></td>
		<td colspan="3">&nbsp;</td>
		<td width="286"><table width="100%" border="0">
		  <tr>
			<td align="center">Revisado por: </td>
		  </tr>
		  <tr>
			<td align="center">ROXAIDA ESTRADA</td>
		  </tr>
		  <tr>
			<td align="center">DIRECTORA DE ADMINISTRACI&Oacute;N Y PRESUPUESTO (E)</td>
		  </tr>
		  
		</table></td>
	  </tr>
	</table>';
//-------------------------------------------------------------------------------------

//$pdf->SetFillColor(1, 1, 1);

$pdf->SetFillColor(255, 255, 255);

	

// print a blox of text using multicell()



//-------------------------------------------------------------------------------------

// set font

$pdf->SetFont('times', 'B', 12);



$pdf->Write(0, "CUADRO COMPARATIVO DE OFERTAS", '', 0, 'C', true, 0, false, false, 0);


$pdf->SetFont('times', '', 10);

$pdf->writeHTML($tb1, true, false, false, false, 'C');

$pdf->SetFont('times', 'B', 10);

$pdf->writeHTML($tb12, true, false, false, false, 'C');





//----------------------------------------------------------------------------------------------

// output the HTML content

//$pdf->writeHTML($html, true, false, true, false, '');

//$pdf->MultiCell(0, 0, $txt."\n".$txt2, 0, 'C', 1, 2, 30, 240, true);

//$pdf->MultiCell(80, 0, $txt2, 0, 'L', 1, 2, 130, 210, true);



//$pdf->MultiCell(0, 0, $txt."\n", 0, 'J', 1, 1, '' ,'', true);

// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output('comparativo.pdf', 'I');

//============================================================+

// END OF FILE                                                

//============================================================+
