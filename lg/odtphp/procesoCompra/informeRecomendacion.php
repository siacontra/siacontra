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
	
	$odf = new odf("../odtphp/procesoCompra/informeRecomendacion.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	$sql4 = "select distinct A.CodRequerimiento, A.Secuencia,B.*, D.FechaCreacion
			from lg_requedetalleacta as A
			join lg_evaluacion as B on B.CodActaInicio=A.CodActainicio
			join lg_informerecomendacion as C on C.CodEvaluacion=B.CodEvaluacion
			join lg_actainicio as D on D.CodActaInicio=A.CodActainicio
			where C.CodInformeRecomendacion=".$codInformeRecomendacion;
				
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
	
	$sql5 = "SELECT sum(Cantidad*(PrecioUnitIva)) as total, FechaInvitacion, NroSolicitudCotizacion, FechaLimite
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
							AND c.CodProveedor = '".$codProveedor[0]."'";
							
	$resp5 = $objConexion->consultar($sql5,'fila');
	
	if ($resp5['total'] == NULL)
	{
		$sql5 = "SELECT sum(Cantidad*(PrecioUnitIva)) as total, FechaInvitacion,  NroSolicitudCotizacion, FechaLimite
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
			AND c.CodProveedor = '".$codProveedor[0]."'";
			
		$resp5 = $objConexion->consultar($sql5,'fila');
		
		
	}
	
	

	$sql1 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado = '".$persona[3]."'";		

 	$resultado1 = $objConexion->consultar($sql1,'fila');//asistente
 	
 	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado  = '".$persona[4]."'";		

 	$resultado2 = $objConexion->consultar($sql2,'fila');//director
	 	
	 	
	$sql3 = "select A.*, C.NomCompleto as NomProveedor, C.Ndocumento
			from lg_informerecomendacion as A
			JOIN lg_proveedorrecomendado as E on A.CodInformeRecomendacion=E.CodInformeRecomendacion
			JOIN mastpersonas C ON (E.CodProveedorRecomendado = C.CodPersona)
			JOIN mastproveedores D ON (D.CodProveedor = C.CodPersona)
			where A.CodInformeRecomendacion=".$codInformeRecomendacion." order by E.SecuenciaRecomendacion";	 			

	$resultado3 = $objConexion->consultar($sql3,'matriz');//proveedor
	
	
	
	/*
	 ************   Funcion para la tabla de puntajes totales
	 * 				tomado del archivo evaluacion.php
	*/
	
	
	 $sql6 = "SELECT A.CodEvaluacion, p.NomCompleto AS NomProveedor, p.CodPersona AS CodProveedor, 
				B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP
			 	FROM lg_evaluacion AS A
				JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
				JOIN lg_actainicio AS C ON C.CodActaInicio = A.CodActainicio
				JOIN lg_requedetalleacta AS D ON D.CodActaInicio = A.CodActaInicio
				JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
				JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
				WHERE A.CodEvaluacion =".$resultado4[0]['CodEvaluacion']."
				GROUP BY A.CodEvaluacion, NomProveedor, CodProveedor, C.CodActaInicio, B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, A.ObjetoEvaluacion, A.CriterioCualitativo, A.CriterioCuantitativo, A.Conclusion, A.Recomendacion
				order by NomCompleto";

		$resultado6 = $objConexion->consultar($sql6,'matriz');
		
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor

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
	
	$evaluacionTotal = array(); 
	
	
	$menor = 999999999999999;
	$sumaTotalProveedor = array();		
		
				for($i = 0; $i <count($resp); $i++)
				{
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
							AND c.CodProveedor = '".$resp[$i]['CodProveedor']."'";
							
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
							AND c.CodProveedor = '".$resp[$i]['CodProveedor']."'";
							
						$resp5 = $objConexion->consultar($sql5,'fila');
						
					}
					
					if (($resp5['total'] < $menor) && ($resp5['total'] > 0))
					{
						$menor = $resp5['total'];
					}
					
					$sumaTotalProveedor [$i] =  $resp5['total'];
					
					
			}
	
	
	
	for ($i = 0; $i < count($resp); $i++) 
	{
		
		$totalPP = ($menor/$sumaTotalProveedor [$i])*50;
			
			$evaluacionTotal[$i] = 
			array(//'titre' => "\"".$ofertaProveedor[$i+1]."\"",
					'textoProveedor' => utf8_decode($resp[$i]['NomProveedor']),
					'cuali' => ($resultado6[$i]['PuntajeTiempoEntrega']+$resultado6[$i]['PuntajeCondicionPago']+$resultado6[$i]['PuntajeRequeTec']),
					'cuanti' => number_format($totalPP,2,',','.'),
					'texte' =>  $totalPP+($resultado6[$i]['PuntajeTiempoEntrega']+$resultado6[$i]['PuntajeCondicionPago']+$resultado6[$i]['PuntajeRequeTec']),
		
			);
	}
	
//---------------------------------------------------------------------------------------------------------------------------//

//-----------------------------------------------TOTAL PUNTUACION--------------------------------------//
	$b = $odf->setSegment('total');
	
	foreach($evaluacionTotal AS $element) {
		//$b->titreArticle($element['titre']);
		$b->setVars('textoProveedor',$element['textoProveedor']);
		$b->setVars('cuali',$element['cuali']);
		$b->setVars('cuanti',$element['cuanti']);
		$b->texteArticle(number_format($element['texte'],2,',','.'));
		$b->merge();
	}

	$odf->mergeSegment($b);	
	
	  /************************************************ hasta aca ***************************/
	 
			
	$dia = $diaLetras[$diaRecomendacion-1];		
	$diaNumero = $diaRecomendacion;
	$mes = $mesLetra[$mesRecomendacion-1];
	$anio = $anioRecomendacion;
	
	
	$anio2=date('Y', strtotime($resp[0]['FechaInvitacion']));
	$cod=utf8_decode('CEM-PC-02-01-'.utf8_decode(rellenarConCero($resp[0]['Numero'],4)).'-'.$anio2);
	$odf->setVars('nroProc',$cod);
	
	//$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);
	$odf->setVars('nroRecomendacion',$numeroVisualRecomendacion);
//	$odf->setVars('nroOrden',$nroOrden);
	
	$odf->setVars('persona1',utf8_decode($resultado1['Nombres'])." ".utf8_decode($resultado1['Apellido1'])." ".utf8_decode($resultado1['Apellido2']));
	$odf->setVars('cedula1',$resultado1['Ndocumento']);	
	$odf->setVars('cargo1',utf8_decode($resultado1['DescripCargo']));
	
	$odf->setVars('persona2',utf8_decode($resultado2['Nombres'])." ".utf8_decode($resultado2['Apellido1'])." ".utf8_decode($resultado2['Apellido2']));
	$odf->setVars('cedula2',$resultado2['Ndocumento']);	
	$odf->setVars('cargo2',utf8_decode($resultado2['DescripCargo']));
	
	$odf->setVars('asunto',utf8_decode($asunto));
	$odf->setVars('objeto',utf8_decode($objeto));
	$odf->setVars('conclusiones',utf8_decode($conclusion));
	$odf->setVars('recomendaciones',utf8_decode($recomendacion));
	
	$odf->setVars('nroVisualEvaluacion',utf8_decode('0004-CPECC-'.rellenarConCero($resultado4[0]['NroVisualEvaluacion'], 3).'-'.$resultado4[0]['AnioEvaluacion']));

	$odf->setVars('nroSolicitudCotizacion',utf8_decode($resp5['NroSolicitudCotizacion']));
	
	list($anioSolicitudLimite, $mesSolicitudLimite, $diaSolicitudLimite) = split('-',$resp5['FechaLimite']);
	$odf->setVars('fechaLimite',utf8_decode($diaSolicitudLimite.'-'.$mesSolicitudLimite.'-'.$anioSolicitudLimite));
	
	
	list($anioActa, $mesActa, $diaActa) = split('-',$resultado4[0]['FechaCreacion']);
	$odf->setVars('fechaActaInicio',utf8_decode($diaActa.'-'.$mesActa.'-'.$anioActa));
	
	list($anioSolicitud, $mesSolicitud, $diaSolicitud) = split('-',$resp5['FechaInvitacion']);
	$odf->setVars('fechaSolicitudCotizacion',utf8_decode($diaSolicitud.'-'.$mesSolicitud.'-'.$anioSolicitud));
	
	
	$cadenaProveedor = '';
	
	for ($i = 0; $i < count($proveedor); $i++) 
	{
		
		$cadenaProveedor .= "- ".$proveedor[$i]."
";

	}

	
	$odf->setVars('proveedores',utf8_decode($cadenaProveedor));
	
	/*$odf->setVars('proveedor',utf8_decode($resultado3[0]['NomProveedor']));
	$odf->setVars('rifproveedor',$resultado3[0]['Ndocumento']);
	$odf->setVars('montoCotizado',$resp5['total']);*/
	
	
	for ($i = 1; $i < count($resultado3); $i++) 
	{
		
		$cadenaProveedorRecomendados .= "- ".utf8_decode($resultado3[$i]['NomProveedor']).", R.I.F. ".$resultado3[$i]['Ndocumento']."
";
	}
	
	//$odf->setVars('otrosproveedores',utf8_decode($cadenaProveedorRecomendados));
	
	$odf->saveToDisk("../odtphp/procesoCompra/informeRecomendacion".$codInformeRecomendacion.".odt");

 
?>
