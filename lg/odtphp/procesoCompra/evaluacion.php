<?php

	include('../odtphp/library/odf.php');
	
	
	
	$odf = new odf("../odtphp/procesoCompra/evaluacion.odt");

	

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	$ofertaProveedor = array("","A","B","C","D","F","G","H","I","J","K","L","M","Ñ","O","P","Q","R","S","T","U","V");

	$sqlAsistente ="select p.NomCompleto, p.CodPersona, pu. DescripCargo,p.Ndocumento
	from lg_evaluacion as A
	JOIN  mastempleado me ON me.CodEmpleado = (select CodPersonaAsistente from lg_evaluacion where CodEvaluacion=".$codEvaluacion.") and A.CodEvaluacion=".$codEvaluacion
	." join mastpersonas as p on p.CodPersona=me.CodPersona
	join rh_puestos as pu on me.CodCargo=pu.CodCargo";
	
	$asistente = $objConexion->consultar($sqlAsistente ,'fila');
	
	$sqlDirector="select p.NomCompleto, p.CodPersona, pu. DescripCargo,p.Ndocumento
	from lg_evaluacion as A
	JOIN mastempleado me ON me.CodEmpleado in 
	(select CodPersonaDirector from lg_evaluacion where CodEvaluacion=".$codEvaluacion.")
	and A.CodEvaluacion=".$codEvaluacion." join mastpersonas as p on p.CodPersona=me.CodPersona
	join rh_puestos as pu on me.CodCargo=pu.CodCargo";
	
	$director= $objConexion->consultar($sqlDirector ,'fila');
	
	$sqlAsistente2 ="select p.NomCompleto, p.CodPersona, pu. DescripCargo,p.Ndocumento
	from lg_evaluacion as A
	JOIN  mastempleado me ON me.CodEmpleado = (select CodPersonaAsistente2 from lg_evaluacion where CodEvaluacion=".$codEvaluacion.") and A.CodEvaluacion=".$codEvaluacion
	." join mastpersonas as p on p.CodPersona=me.CodPersona
	join rh_puestos as pu on me.CodCargo=pu.CodCargo";
	
	$asistente2 = $objConexion->consultar($sqlAsistente2 ,'fila'); 

		$sql6 = "SELECT A.CodEvaluacion, p.NomCompleto AS NomProveedor, p.CodPersona AS CodProveedor, C.CodActaInicio,
				B.PuntajeRenglonOf,B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, 
			 	A.ObjetoEvaluacion, A.CriterioCualitativo, A.CriterioCuantitativo, A.Conclusion, A.Recomendacion
				FROM lg_evaluacion AS A
				JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
				JOIN lg_actainicio AS C ON C.CodActaInicio = A.CodActainicio
				JOIN lg_requedetalleacta AS D ON D.CodActaInicio = A.CodActaInicio
				JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
				JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
				WHERE A.CodEvaluacion =".$codEvaluacion."
				GROUP BY A.CodEvaluacion, NomProveedor, CodProveedor, C.CodActaInicio, B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, A.ObjetoEvaluacion, A.CriterioCualitativo, A.CriterioCuantitativo, A.Conclusion, A.Recomendacion";

		$resultado6 = $objConexion->consultar($sql6,'matriz');

		$sql7 = "SELECT C.CodActaInicio
					FROM lg_evaluacion AS A
					JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
					JOIN lg_actainicio AS C ON C.CodActaInicio = A.CodActainicio
					JOIN lg_requedetalleacta AS D ON D.CodActaInicio = A.CodActaInicio
					JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
					JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
					WHERE A.CodEvaluacion =".$codEvaluacion."
					GROUP BY C.CodActaInicio";
					
		$resultado7 = $objConexion->consultar($sql7,'matriz');
		
		$sql4 = "select CodRequerimiento, Secuencia
					from lg_requedetalleacta where CodActaInicio=".$resultado6[0]['CodActaInicio'];
				
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
		$CodActaInicio = $codActa;
		

						
 $sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				rd.Descripcion

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
	
 	 $sql2 = "SELECT  c.*, p.NomCompleto as NomProveedor
FROM lg_cualitativacuantitativa c
inner join mastpersonas p on (c.CodProveedor=p.CodPersona )
inner join mastproveedores pr on (c.CodProveedor=pr.CodProveedor )
where c.CodEvaluacion = ".$codEvaluacion." and c.ProvRecRenglon='S' ";
 	 
 	 
 	 /*"SELECT c.*, p.NomCompleto AS NomProveedor, rd.Descripcion, B.ProvRecRenglon FROM lg_cotizacion c
	 INNER JOIN lg_requerimientosdet rd ON 
	 (c.CodOrganismo=rd.CodOrganismo AND c.CodRequerimiento=rd.CodRequerimiento AND c.Secuencia = rd.Secuencia)
	  INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona) 
	  INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)  
	  JOIN lg_cualitativacuantitativa AS B ON (B.CodEvaluacion = ".$codEvaluacion." and B.ProvRecRenglon='S' and B.CodProveedor = p.CodPersona)
	   WHERE rd.CodRequerimiento in (".$cadenaCondicionReque.") AND c.Secuencia in (".$cadenaCondicionSecue.")  order by B.CodProveedor";
*/
/*SELECT 
				c.*,
				p.NomCompleto AS NomProveedor, rd.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
				JOIN lg_cualitativacuantitativa AS B ON B.CodEvaluacion = ".$codEvaluacion." and B.ProvRecRenglon='S'
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") group by d.Descripcion*/


	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir

	
	
	
	$menor = 999999999999999;
	$sumaTotalProveedor = array();		
		
				for($i = 0; $i <count($resp); $i++)
				{
					$sql5 = "SELECT sum(Total) as total
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
						$sql5 = "SELECT sum(Total) as total
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
			
						
							
	$diaNumero = $diaEvaluacion;
	$mes = $mesLetra[$mesEvaluacion-1];
	$anio = $anioEvaluacion;
	//$dia = $diaLetras[$diaEvaluacion-1];		
	list($anioDocumento,$mesDocumento,$diaDocumento) = split('-',$resp[0]['FechaDocumento']);
	$odf->setVars('FechaDocumento',$diaDocumento.'-'.$mesDocumento.'-'.$anioDocumento);
		
	$anio2=date('Y', strtotime($resp[0]['FechaInvitacion']));
	$cod='CEM-PC-02-01-'.rellenarConCero($resp[0]['NroSolicitudCotizacion'],4).'-'.$anio2;
	$odf->setVars('nroProc',$cod);
	
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);
	
	
	//$odf->setVars('nroSolicitud',utf8_decode($resp[0]['NroSolicitudCotizacion']));
	$odf->setVars('nroEvaluacion',$numeroVisualEvaluacion);
	
		

	$cadenaProveedor = '';
	
	$listaProveedor = array();
	
	$evaluacionCuantitativa = array();
	
	$evaluacionTotal = array(); 
	
	for ($i = 0; $i < count($resp); $i++) 
	{
		
		$cadenaProveedor .= "- Oferta \"".$ofertaProveedor[$i+1]."\": ".$resp[$i]['NomProveedor']."
";

	 	$listaProveedor[$i] = 
			array('titreArticle' => "Oferta \"".$ofertaProveedor[$i+1]."\"",
					'requeTec'=>$resultado6[$i]['PuntajeRenglonOf'],
					'TiempoEntrega'=>$resultado6[$i]['PuntajeTiempoEntrega'],
					'condiPago'=>$resultado6[$i]['PuntajeCondicionPago'],
					'texteArticle'=>($resultado6[$i]['TotalPuntajeCuali']),
					'reque'=>$resultado6[$i]['PuntajeRequeTec'],
					
				);
				
		$totalPP = ($menor/$sumaTotalProveedor [$i])*40;
		
		
		$evaluacionCuantitativa[$i] = 
			array('titre' => "\"".$ofertaProveedor[$i+1]."\": ".$resp[$i]['NomProveedor'],
					'proveedor' => 'Monto oferta '."\"".$ofertaProveedor[$i+1]."\"",
					'precioProveedor' => $sumaTotalProveedor [$i],
					'texte' => number_format($totalPP,2,',','.'),
		
			);
			
			
			$evaluacionTotal[$i] = 
			array('titre' => "\"".$ofertaProveedor[$i+1]."\"",
					'textoProveedor' => $resp[$i]['NomProveedor'],
					'cuali' => $resultado6[$i]['TotalPuntajeCuali'],
					'cuanti' => number_format($totalPP,2,',','.'),
					'texte' =>  $totalPP+$resultado6[$i]['TotalPuntajeCuali'],
		
			);
	}
	
	
	for ($i = 0; $i < count($resp2); $i++) 
	{
		$sqln="select * from lg_requerimientosdet rd
		inner join lg_cotizacion cot on (cot.CodRequerimiento in (".$cadenaCondicionReque.") and cot.Secuencia =rd.Secuencia)
		where rd.CodRequerimiento in (".$cadenaCondicionReque.") and rd.Secuencia=".$resp2[$i]['Secuencia']; 
		$respn = $objConexion->consultar($sqln,'fila');
				$itemR[$i] = 
			array('titre' => $i+1,
					'proveedor' => $resp2[$i]['NomProveedor'],
					'descripcion' => $respn['Descripcion'],
					'texte' => number_format($respn['Cantidad'],2,',','.'),
		
			);
	}
	//------------------------------------------FILAS DE LA EVALUACION CUALITATIVA-------------------------------------//
	$article = $odf->setSegment('articles');
	
	foreach($listaProveedor AS $element) {
		
		$article->titreArticle($element['titreArticle']);
		$article->setVars('renglonOf',$element['requeTec']);
		$article->setVars('tiempoEntrega',$element['TiempoEntrega']);
		$article->setVars('condiPago',$element['condiPago']);
		$article->setVars('reque',$element['reque']);
		$article->texteArticle($element['texteArticle']);//total puntaje
		$article->merge();
		
	}
	
	$odf->mergeSegment($article);
	

	
	$item = $odf->setSegment('itemR');
		
	foreach($itemR AS $element) {
		
		$item->titreArticle($element['titre']);
		$item->setVars('Proveedor',$element['proveedor']);
		$item->setVars('desc',$element['descripcion']);
		$item->texteArticle($element['texte']);//total puntaje
		$item->merge();
		
	}
	
	$odf->mergeSegment($item);
	//--------------------------------------FILAS DE LA EVALUACION CUANTITATIVA----------------------------------------// 

	/*$a = $odf->setSegment('fila');
	
	foreach($evaluacionCuantitativa AS $element) {
		$a->titreArticle($element['titre']);
		$totalPMO_POE = ($menor/$element['precioProveedor']);
		
		if($menor==999999999999999) $menor=0;
		
		$a->setVars('textoCondicionProveedor','Oferta mas Economica

'.utf8_decode($element['proveedor']));
		$a->setVars('precioEconomicoProveedor',number_format($menor,2,',','.').'


'.number_format($element['precioProveedor'],2,',','.'));

		$a->setVars('formulaPMO_POE',number_format ($totalPMO_POE,2,',','.'));
		$a->setVars('formulaPMP','40');
		$a->texteArticle($element['texte']);
		$a->merge();
	}
	
	$odf->mergeSegment($a);*/
		
	
	
//---------------------------------------------------------------------------------------------------------------------------//

//-----------------------------------------------TOTAL PUNTUACION--------------------------------------//
	$b = $odf->setSegment('total');
	
	foreach($evaluacionTotal AS $element) {
		$b->titreArticle($element['titre']);
		$b->setVars('textoProveedor',$element['textoProveedor']);
		$b->setVars('cuali',$element['cuali']);
		$b->setVars('cuanti',$element['cuanti']);
		$b->texteArticle(number_format($element['texte'],2,',','.'));
		$b->merge();
	}
	
	$odf->mergeSegment($b);
	
	$bs = $odf->setSegment('tota');
	
	foreach($evaluacionTotal AS $element) {
		$bs->titreArticle('La empresa ');
		$bs->setVars('texto',$element['textoProveedor']);
		$bs->setVars('t',' obtuvo ');
		$bs->texteArticle(number_format($element['texte'],2,',','.'));
		$bs->merge();
	}
	
	$odf->mergeSegment($bs);
	
	
//-----------------------------------------------TOTAL PUNTUACION--------------------------------------//		

	
	$odf->setVars('objeto',$resultado6[0]['ObjetoEvaluacion']);
	$odf->setVars('proveedores',$cadenaProveedor);
	
	
	$analistas=$director['NomCompleto'].", titular de la cédula de identidad N° ".number_format($director['Ndocumento'],0,'','.').", en su condición de ".$director['DescripCargo'];
	
	if($asistente2['NomCompleto']!=''){
	$analistas.=", ".$asistente['NomCompleto'].", titular de la cédula de identidad N° ".number_format($asistente['Ndocumento'],0,'','.').", en su condición de ".$asistente['DescripCargo'];
	$analistas.=" y ".$asistente2['NomCompleto'].", titular de la cédula de identidad N° ".number_format($asistente2['Ndocumento'],0,'','.').", en su condición de ".$asistente2['DescripCargo'];
	}
	else {
	$analistas.=" y ".$asistente['NomCompleto'].", titular de la cédula de identidad N° ".number_format($asistente['Ndocumento'],0,'','.').", en su condición de ".$asistente2['DescripCargo'];
	}
	$odf->setVars('analistas',$analistas);
	
	$odf->setVars('nombreDirector',$director['NomCompleto']);
	$odf->setVars('cargoDirector',$director['DescripCargo']."
	
	
	
	
							".$asistente2['NomCompleto']."
							".$asistente2['DescripCargo']);
	
	$odf->setVars('nombreAnalista',$asistente['NomCompleto']);
	$odf->setVars('cargoAnalista',$asistente['DescripCargo']);

	/*if($asistente2['NomCompleto']!=''){
	$odf->setVars('nombreAnalist',utf8_decode($asistente2['NomCompleto']));
	//$odf->setVars('cargoAnalist',utf8_decode($asistente2['DescripCargo']));	
	}
	else{
	$odf->setVars('nombreAnalist',' ');
	//$odf->setVars('cargoAnalist',' ');	
	}*/
	
	$odf->setVars('conclusion',$resultado6[0]['Conclusion']);
	$odf->setVars('recomendacion',$resultado6[0]['Recomendacion']);
	
	$odf->saveToDisk("../odtphp/documentos/evaluacionCualitativaCuantitativa".$codEvaluacion.".odt");
	//open('odtphp/procesoCompra/evaluacion_anexo.php?codEvaluacion='.$codEvaluacion);
?>
