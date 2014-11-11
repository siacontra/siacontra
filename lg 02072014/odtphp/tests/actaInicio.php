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
	
	$odf = new odf("../odtphp/tests/actainicio.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Ábril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	$sql1 ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,

			pu. DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where p.CodPersona='".$codAsistenteActaInicio."'";    

    $resultado1 = $objConexion->consultar($sql1,'fila');			
	
	$sql2 ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,

			pu. DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where p.CodPersona='".$codDirectorActaInicio."'";    
	
	$resultado2 = $objConexion->consultar($sql2,'fila');	

	if(isset($casoLlamado) && ($casoLlamado == 0))
	{
		
		
		/*$vectorCodRequerimiento = array();
		$condicionRequerimiento = array();
		$condSecuenReque = array();
		
		for($i = 0; $i < count($vectorCodRequerimientoSecue); $i++)
		{
			$vectorCodRequerimiento[$i] = split("[.]", $vectorCodRequerimientoSecue[$i]);
			
			
		}
		
		for($i = 0; $i < count($vectorCodRequerimientoSecue); $i++)
		{
			
			$condicionRequerimiento[$i] = "'". $vectorCodRequerimiento[$i][1]."'";
			$condSecuenReque[$i] = $vectorCodRequerimiento[$i][2];
			
		}
		
		$cadenaCondicionReque = implode(',',$condicionRequerimiento);
		$cadenaCondicionSecue = implode(',',$condSecuenReque);*/
		$cadenaCondicionReque = $codRequeGlobal;
		$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
	}
//	echo 	$cadenaCondicionReque ;
//echo '-'.		$cadenaCondicionSecue ;return;
	//	consulto los datos de la cotizacion
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,mp.FlagSNC

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				GROUP BY p.NomCompleto";
				


	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	
	/*if ($tipoReque == 'stock')
	{*/
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
	
	//echo $resp[0]['NomProveedor'];
	
	$dia = $diaLetras[$diaActa-1];		
	$diaNumero = $diaActa;
	$mes = $mesLetra[$mesActa-1];
	$anio = $anioActa;
	
	$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);

	$odf->setVars('numActa','');//$CodActaInicio);
	
	$odf->setVars('nombreDirector',utf8_decode($resultado2['Nombres'])." ".utf8_decode($resultado2['Apellido1'])." ".utf8_decode($resultado2['Apellido2']));
	$odf->setVars('cedulaDirector',$resultado2['Ndocumento']);	
	
	$odf->setVars('nombreAnalista',utf8_decode($resultado1['Nombres'])." ".utf8_decode($resultado1['Apellido1'])." ".utf8_decode($resultado1['Apellido2']));
	$odf->setVars('cedulaAnalista',$resultado1['Ndocumento']);	
	$odf->setVars('cargoAnalista',utf8_decode($resultado1['DescripCargo']));
	
	$proveedor = "";
	
	for ($i = 0; $i < count($resp); $i++) {
		
		if($resp[$i]['FlagSNC'] == 'S')
		{
			$descripcionSNC = ' (Inscrito en el SNC)';
			
		} else {
			
			$descripcionSNC = ' (No inscrito en el SNC)';
			
		}
			$proveedor .= "-".utf8_decode($resp[$i]['NomProveedor'].$descripcionSNC)."
";
	}
		
	$odf->setVars('proveedores',$proveedor);
	
	$requerimiento = "";
	
	for ($i = 0; $i < count($resp2); $i++) {
		
			$requerimiento .= str_replace(".00","",$resp2[$i]['Cantidad'])." ".$resp2[$i]['Descripcion'].", ";
	}
		
	$odf->setVars('requerimiento',utf8_decode($requerimiento));
	$odf->setVars('nroActa',utf8_decode($numeroVisualActa));
	

	$odf->saveToDisk("../odtphp/tests/inicioCompra".$CodActaInicio.".odt");

 
?>
