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
	
	
	$odf = new odf("../odtphp/procesoCompra/informeAdjudicacion.odt");


	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre",);						
	
	
	$sql7 ="select A.*,B.*, B.FechaCreacion as FechaCreacionRecomendacion,C.SecuenciaRecomendacion 
			from lg_informeadjudicacion as A
			join lg_informerecomendacion as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
			join lg_proveedorrecomendado as C on C.CodInformeRecomendacion=B.CodInformeRecomendacion and C.CodProveedorRecomendado=A.CodProveedor
			where A.CodInformeRecomendacion = (select CodInformeRecomendacion from lg_informeadjudicacion as IAD where IAD.CodAdjudicacion=".$CodAdjudicacion.") order by C.SecuenciaRecomendacion";
				
	$resultado7 = $objConexion->consultar($sql7,'matriz');

	$cadenaProveedor = '';
	$secuenciaRecomendacion = '';
	
	for($y = 0; $y < count($resultado7);$y++)
	{
		$sql4 ="select A.CodRequerimiento, A.Secuencia
				from lg_adjudicaciondetalle as A
				join lg_informeadjudicacion as B on A.CodAdjudicacion=B.CodAdjudicacion
				where A.CodAdjudicacion=".$resultado7[$y]['CodAdjudicacion'];
					
		$resultado4 = $objConexion->consultar($sql4,'matriz');
		
		$condicionRequerimiento = array();
		$condSecuenReque = array();
		
		for($i= 0; $i < count($resultado4); $i++)
		{
			
			$condicionRequerimiento[$i] = $resultado4[$i]['CodRequerimiento'];
			$condSecuenReque[$i] = $resultado4[$i]['Secuencia'];
		}
		
		$cadenaCondicionReque = implode(',',$condicionRequerimiento);
		$cadenaCondicionSecue = implode(',',$condSecuenReque);
		
		
		$sql3 ="select A.*, C.NomCompleto as NomProveedor, C.Ndocumento, C.Direccion, C.Lnacimiento,E.CodProveedor
				from lg_adjudicaciondetalle as A
				join lg_informeadjudicacion as E on A.CodAdjudicacion=E.CodAdjudicacion
				JOIN mastpersonas C ON (E.CodProveedor= C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = C.CodPersona)
				where E.CodAdjudicacion=".$resultado7[$y]['CodAdjudicacion'];
	
		$resultado3 = $objConexion->consultar($sql3,'fila');//
		
	//	echo $resultado3['CodProveedor'];
		
		$sql5 = "SELECT sum(Cantidad*(PrecioUnitIva)) as total
								FROM lg_cotizacion c
								INNER JOIN lg_requerimientosdet rd ON ( c.CodOrganismo = rd.CodOrganismo
								AND c.CodRequerimiento = rd.CodRequerimiento
								AND c.Secuencia = rd.Secuencia )
								INNER JOIN mastpersonas p ON ( c.CodProveedor = p.CodPersona )
								INNER JOIN mastproveedores mp ON ( c.CodProveedor = mp.CodProveedor )
								INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
								WHERE rd.CodRequerimiento
								IN ( ".$cadenaCondicionReque." )
								AND c.Secuencia
								IN (".$cadenaCondicionSecue.")
								AND c.CodProveedor = '".$resultado3['CodProveedor']."'";
					
		$resp5 = $objConexion->consultar($sql5,'fila');
		
		if ($resp5['total'] == NULL)
		{
			$sql5 = "SELECT sum(Cantidad*(PrecioUnitIva)) as total
				FROM lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON ( c.CodOrganismo = rd.CodOrganismo
				AND c.CodRequerimiento = rd.CodRequerimiento
				AND c.Secuencia = rd.Secuencia )
				INNER JOIN mastpersonas p ON ( c.CodProveedor = p.CodPersona )
				INNER JOIN mastproveedores mp ON ( c.CodProveedor = mp.CodProveedor )
				INNER JOIN lg_commoditysub AS d 
				WHERE rd.CodRequerimiento
				IN ( ".$cadenaCondicionReque." )
				AND c.Secuencia
				IN (".$cadenaCondicionSecue.") and (rd.CommoditySub = d.Codigo)  
				AND c.CodProveedor = '".$resultado3['CodProveedor']."'";
				
			$resp5 = $objConexion->consultar($sql5,'fila');
	
			
		}
		
		/*$odf->setVars('proveedor',utf8_decode($resultado3['NomProveedor']));
		$odf->setVars('rifproveedor',$resultado3['Ndocumento']);
		$odf->setVars('montoCotizado',number_format($resp5['total'],2,',','.'));
		//$odf->setVars('montoCotizado',$resp5['total']);
		$odf->setVars('direccion',utf8_decode($resultado3['Direccion']));
		$odf->setVars('Lnacimiento',utf8_decode($resultado3['Lnacimiento']));*/
		
		$cadenaProveedor .= "- ".$resultado3['NomProveedor'].", RIF: ".$resultado3['Ndocumento'].", por un monto de Bs. ".number_format($resp5['total'],2,',','.').", ubicada en ".$resultado3['Direccion'].", ".$resultado3['Lnacimiento']."

";
		
		
		$secuenciaRecomendacion .= $resultado7[$y]['SecuenciaRecomendacion'].', ';
			
		
	}	
	//---------------------------FECHA RECOMENDACION------------------//
	list($anioRecomendacion,$mesRecomendacion,$diaRecomendacion) = split('-',$resultado7[0]['FechaCreacionRecomendacion']);
	
	$dia = $diaLetras[$diaRecomendacion-1];		
	$diaNumero = $diaRecomendacion;
	$mes = $mesLetra[$mesRecomendacion-1];
	$anio = $anioRecomendacion;
	
	
	$odf->setVars('diaRecomen',$diaNumero);
	$odf->setVars('mesRecomen',$mes);
	$odf->setVars('anioRecomen',$anio);
	//$odf->setVars('nroRecomendacion',$numeroVisualRecomendacion);
	//-----------------------------------------------------------------//
	
	if(count($resultado7) > 1)
	{
		$odf->setVars('tipoAdjudicacionSecuencia','DECIDO OTORGAR LA ADJUDICACIÓN PARCIAL por medio del presente Informe a las empresas signadas con los Nros. '.$secuenciaRecomendacion.':');
	
	} else {
		
		$odf->setVars('tipoAdjudicacionSecuencia','DECIDO OTORGAR LA ADJUDICACIÓN por medio del presente Informe la empresa signada con el Nro. '.$secuenciaRecomendacion.':');
	}	
	

	
	
	$dia = $diaLetras[date('j')-1];		
	$diaNumero = date('d');
	$mes = $mesLetra[date("n")-1];
	$anio = date('Y');
	
	$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);
	$odf->setVars('nroVisual',rellenarConCero($resultado7[0]['NroVisualAdjudicacion'],3).'-'.$resultado7[0]['AnioAdjudicacion']);

	$odf->setVars('objetoConsulta',$resultado7[0]['ObjetoConsulta']);
	
	

	
	/*$cadenaProveedor = '';
	
	for ($i = 0; $i < count($proveedor); $i++) 
	{
		
		$cadenaProveedor .= "- ".$proveedor[$i]."
";
	}*/
	
	$odf->setVars('proveedores',$cadenaProveedor);	
	
	
	
	$sql3 = "select RepresentLegal, DocRepreLeg from mastorganismos
			where CodOrganismo='".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."'";	 			
	
	$resultadoOrganismo = $objConexion->consultar($sql3,'fila');//
	
	$odf->setVars('contralor',$resultadoOrganismo['RepresentLegal']);
	$odf->setVars('cedula',$resultadoOrganismo['DocRepreLeg']);
	
	
	
	
	
	$odf->saveToDisk("../odtphp/procesoCompra/informeAdjudicacion".$CodAdjudicacion.".odt");

 
?>
