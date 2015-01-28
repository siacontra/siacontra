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
	
	
	$odf = new odf("../odtphp/procesoCompra/declararDesierto.odt");


	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	
	
	$sql7 ="select A.*,B.*, B.FechaCreacion as FechaCreacionRecomendacion 
			from lg_evaluacion as A
			JOIN lg_informerecomendacion AS B ON A.CodEvaluacion = B.CodEvaluacion
			where B.CodInformeRecomendacion=".$CodRecomendacion;
				
	$resultado7 = $objConexion->consultar($sql7,'fila');
	
	
		
	$sql4 = "select distinct A.CodRequerimiento, A.Secuencia
			from lg_requedetalleacta as A
			 join lg_actainicio as B on A.CodActaInicio=B.CodActaInicio
			join lg_evaluacion as C on C.CodActaInicio=A.CodActaInicio
			join lg_informerecomendacion as E on E.CodEvaluacion=C.CodEvaluacion
			 where E.CodInformeRecomendacion=".$CodRecomendacion;
			 
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
	

	
	$sql5 = "SELECT sum( PrecioUnitIva * Cantidad ) as total
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
							IN (".$cadenaCondicionSecue.")";
							//-- AND c.CodProveedor = '".$resultado3['CodProveedor']."'";
				
	$resp5 = $objConexion->consultar($sql5,'fila');
	
	
	if ($resp5['total'] == NULL)
	{
		$sql5 = "SELECT sum( PrecioUnitIva * Cantidad ) as total
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
			IN (".$cadenaCondicionSecue.") and (rd.CommoditySub = d.Codigo)";  
			//-- AND c.CodProveedor = '".$resultado3['CodProveedor']."'";
			
		$resp5 = $objConexion->consultar($sql5,'fila');
		
	}
	
	
	//---------------------------FECHA RECOMENDACION------------------//
	list($anioRecomendacion,$mesRecomendacion,$diaRecomendacion) = split('-',$resultado7['FechaCreacionRecomendacion']);
	
	$dia = $diaLetras[$diaRecomendacion-1];		
	$diaNumero = $diaRecomendacion;
	$mes = $mesLetra[$mesRecomendacion-1];
	$anio = $anioRecomendacion;
	
	
	$odf->setVars('diaRecomen',$diaNumero);
	$odf->setVars('mesRecomen',$mes);
	$odf->setVars('anioRecomen',$anio);

	$sql10 = "select *from lg_declarar_desierto where CodInformeRecomendacion=".$CodRecomendacion;
	$resp10 = $objConexion->consultar($sql10,'fila');
	$odf->setVars('nroVisual',rellenarConCero($resp10['NroVisualDesierto'],3).'-'.$resp10['AnioDesierto']);
	//$odf->setVars('nroRecomendacion',$numeroVisualRecomendacion);
	//-----------------------------------------------------------------//
	
	
	$dia = $diaLetras[date('j')-1];		
	$diaNumero = date('d');
	$mes = $mesLetra[date("n")-1];
	$anio = date('Y');
	
	$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);


	
	
	$odf->setVars('objetoConsulta',$resultado7['ObjetoConsulta']);
	
	
	$sql3 = "select RepresentLegal, DocRepreLeg from mastorganismos
			where CodOrganismo='".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."'";	 			
	
	$resultadoOrganismo = $objConexion->consultar($sql3,'fila');//
	
	$odf->setVars('contralor',$resultadoOrganismo['RepresentLegal']);
	$odf->setVars('cedula',$resultadoOrganismo['DocRepreLeg']);
	
	
	
	
	
	$odf->saveToDisk("../odtphp/documentos/declararDesierto".$CodRecomendacion.".odt");

 
?>
