<?php

/**
 * Tutoriel file
 * Description : Imbricating several segments
 * You need PHP 5.2 at least
 * You need Zip Extension or PclZip library
 *
 * @copyright  GPL License 2008 - Julien Pauli - Cyril PIERRE de GEYER - Anaska (http://www.anaska.com)
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL License
 * @version 1.3
 */


// Make sure you have Zip extension or PclZip library loaded
// First : include the librairy
	require_once('../odtphp/library/odf.php');
	
	//unlink("../odtphp/procesoCompra/inicioCompra".$CodActaInicio.".odt");
	
	$odf = new odf("../odtphp/procesoCompra/pliego.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	
		$cadenaCondicionSecue = explode('/',$codRequeGlobal);
		
		$cod_cot=$cadenaCondicionSecue[0];
		$reque=$cadenaCondicionSecue[1];
		$sec=$cadenaCondicionSecue[2];
	
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,mp.FlagSNC, mp.condicionRNC,rd.Descripcion

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				
				
			WHERE
				rd.CodRequerimiento in (".$reque.") AND
				c.Secuencia in (".$sec.")
				GROUP BY p.NomCompleto";
				


	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	 
	/*if ($tipoReque == 'stock')
	{
		$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
			
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") group by d.Descripcion";
				
	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
	
	//} else if ($tipoReque == 'commodity') {
			
	if (count($resp2) <= 0)
	{		
		$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_commoditysub AS d 
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				and (rd.CommoditySub = d.Codigo) group by d.Descripcion";
				
		$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
				
	}
//	LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
//LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')

	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
	*/
	//echo $resp[0]['NomProveedor'];
	$d=date('d', strtotime($resp[0]['FechaLimite']));
	$m=date('m', strtotime($resp[0]['FechaLimite']));
	$dato=date("w",strtotime($resp[0]['FechaLimite']));
switch ($dato) {
  case 0: $dia= "domingo";
          break;
  case 1: $dia= "lunes";
          break;
  case 2: $dia= "martes";
          break;
  case 3: $dia= "miércoles";
          break;
  case 4: $dia= "jueves";
          break;
  case 5: $dia= "viernes";
          break;
  case 6: $dia= "sábado";
          break;
	}
	
	//$dia = $diaLetras[$d-1] ;			
	$anio=date('Y', strtotime($resp[0]['FechaLimite']));
	
	$fecha=$d.'/'.$m.'/'.$anio;
	$odf->setVars('dia',$dia);
	$odf->setVars('fecha',$fecha);
	/*$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);*/

	//$odf->setVars('nroSolicitud',utf8_decode($resp[0]['NroSolicitudCotizacion']));
	
	$odf->setVars('nombreDirector',utf8_decode('Lcda. ROXAIDA ESTRADA'));
	//$odf->setVars('nombreDirector',utf8_decode($resultado2['Nombres'])." ".utf8_decode($resultado2['Apellido1'])." ".utf8_decode($resultado2['Apellido2']));
	//$odf->setVars('cedulaDirector',$resultado2['Ndocumento']);	
	
		
			
	
	$requerimiento = "";
	
	//for ($i = 0; $i < count($resp); $i++) {
		
			$requerimiento .= "- ".str_replace(".00","",$resp[0]['Cantidad'])." ".$resp[0]['Descripcion']."";
		
	//}
		$cod=utf8_decode('CEM-PC-02-01-'.rellenarConCero($resp[0]['NroSolicitudCotizacion'],4).'-'.$anio);
		//$cod_visual='CEM-PC-02-01-'.$cod_cot;
	$odf->setVars('requerimiento',utf8_decode($requerimiento));
	$odf->setVars('nroActa',utf8_decode($cod));
	

	$odf->saveToDisk("../odtphp/procesoCompra/pliego".$cod_cot.".odt");

 
?>
