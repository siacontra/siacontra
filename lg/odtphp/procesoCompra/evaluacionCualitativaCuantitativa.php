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
	
	
	
	$odf = new odf("../odtphp/procesoCompra/evaluacionCualitativaCuantitativa.odt");

	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
	//include_once ("../../../clases/MySQL.php");
		
	//include_once("../../../comunes/objConexion.php");
	//**************************************************//

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	/*foreach($_GET as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}

	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}*/
	
	$ofertaProveedor = array("","A","B","C","D","F","G","H","I","J","K","L","M","Ñ","O","P","Q","R","S","T","U","V");

	$sqlAsistente ="select p.Nombres, p.Apellido1,p.Apellido2,p.CodPersona, pu. DescripCargo
	from lg_evaluacion as A
	
	JOIN mastpersonas p ON p.CodPersona = (select CodPersonaAsistente from lg_evaluacion where CodEvaluacion=".$codEvaluacion.") and A.CodEvaluacion=".$codEvaluacion
	." join mastempleado as me on p.CodPersona=me.CodPersona
	join rh_puestos as pu on me.CodCargo=pu.CodCargo";
	
	$asistente = $objConexion->consultar($sqlAsistente ,'fila');
	
	$sqlDirector="select p.Nombres, p.Apellido1,p.Apellido2,p.CodPersona
	from lg_evaluacion as A
	JOIN mastpersonas p ON p.CodPersona in (select CodPersonaDirector from lg_evaluacion where CodEvaluacion=".$codEvaluacion.")and A.CodEvaluacion=".$codEvaluacion;
	
	$director= $objConexion->consultar($sqlDirector ,'fila');

		$sql6 = "SELECT A.CodEvaluacion, p.NomCompleto AS NomProveedor, p.CodPersona AS CodProveedor, C.CodActaInicio,
				B.PuntajeRequeTec, B.PuntajeTiempoEntrega, B.PuntajeCondicionPago, B.TotalPuntajeCuali, B.PMO_POE, B.PP, 
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
			
			for($i = 0; $i <count($resultado6); $i++)
			{
				/*echo 'xGetElementById(\'ofertaEconomica'.$i.'\').value='.$menor.';
				
					if(xGetElementById(\'oferta'.$i.'\').value > 0)
					{
						var PMO_POE = xGetElementById(\'PMO_POE'.$i.'\').value;
						xGetElementById(\'PMO_POE'.$i.'\').value='.$resultado6[$i]['PMO_POE'].';
						var PP = xGetElementById(\'PP'.$i.'\').value;
						xGetElementById(\'PP'.$i.'\').value = '.$resultado6[$i]['PP'].';
						
					} else {
						
						xGetElementById(\'PMO_POE'.$i.'\').value= 0;
						xGetElementById(\'PP'.$i.'\').value = 0;
						
					}
						';*/

						
						
				//(($menor/$resp5['total'])*50)
			}
				//echo '</script>';
				
							
	$diaNumero = $diaEvaluacion;
	$mes = $mesLetra[$mesEvaluacion-1];
	$anio = $anioEvaluacion;
	//$dia = $diaLetras[$diaEvaluacion-1];		
	list($anioDocumento,$mesDocumento,$diaDocumento) = split('-',$resp[0]['FechaDocumento']);
	$odf->setVars('FechaDocumento',$diaDocumento.'-'.$mesDocumento.'-'.$anioDocumento);
	
	
	
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);
	
	$odf->setVars('nroSolicitud',utf8_decode($resp[0]['NroSolicitudCotizacion']));
	$odf->setVars('nroEvaluacion',utf8_decode($numeroVisualEvaluacion));
	
		

	$cadenaProveedor = '';
	
	$listaProveedor = array();
	
	$evaluacionCuantitativa = array();
	
	$evaluacionTotal = array(); 
	
	for ($i = 0; $i < count($resp); $i++) 
	{
		
		$cadenaProveedor .= "- Oferta \"".utf8_decode($ofertaProveedor[$i+1])."\": ".utf8_decode($resp[$i]['NomProveedor'])."
";

	 	$listaProveedor[$i] = 
			array('titreArticle' => "Oferta \"".$ofertaProveedor[$i+1]."\"",
					'requeTec'=>'gg',
					'TiempoEntrega'=>$resultado6[$i]['PuntajeTiempoEntrega'],
					'condiPago'=>$resultado6[$i]['PuntajeCondicionPago'],
					'texteArticle'=>($resultado6[$i]['PuntajeTiempoEntrega']+$resultado6[$i]['PuntajeCondicionPago']+$resultado6[$i]['PuntajeRequeTec']),
					'reque'=>$resultado6[$i]['PuntajeRequeTec'],
					
				);
				
		$totalPP = ($menor/$sumaTotalProveedor [$i])*50;
		
		$evaluacionCuantitativa[$i] = 
			array('titre' => "\"".$ofertaProveedor[$i+1]."\": ".$resp[$i]['NomProveedor'],
					//'textoCondicion'=>'Oferta más Economica',
					//'precioEcnonomico'=>,
					'proveedor' => 'Monto oferta '."\"".$ofertaProveedor[$i+1]."\"",
					'precioProveedor' => $sumaTotalProveedor [$i],
					'texte' => number_format($totalPP,2,',','.'),
		
			);
			
			
			$evaluacionTotal[$i] = 
			array('titre' => "\"".$ofertaProveedor[$i+1]."\"",
					'textoProveedor' => utf8_decode($resp[$i]['NomProveedor']),
					'cuali' => ($resultado6[$i]['PuntajeTiempoEntrega']+$resultado6[$i]['PuntajeCondicionPago']+$resultado6[$i]['PuntajeRequeTec']),
					'cuanti' => number_format($totalPP,2,',','.'),
					'texte' =>  $totalPP+($resultado6[$i]['PuntajeTiempoEntrega']+$resultado6[$i]['PuntajeCondicionPago']+$resultado6[$i]['PuntajeRequeTec']),
		
			);
	}
	
	
		
	//------------------------------------------FILAS DE LA EVALUACION CUALITATIVA-------------------------------------//
	$article = $odf->setSegment('articles');
	
	foreach($listaProveedor AS $element) {
		
		$article->titreArticle($element['titreArticle']);
		//$article->setVars('requeTec',$element['requeTec']);
		$article->setVars('tiempoEntrega',$element['TiempoEntrega']);
		$article->setVars('condiPago',$element['condiPago']);
		$article->setVars('reque',$element['reque']);
		$article->texteArticle($element['texteArticle']);//total puntaje
		$article->merge();
		
	}
	
	$odf->mergeSegment($article);
	


	//--------------------------------------FILAS DE LA EVALUACION CUANTITATIVA----------------------------------------// 
/*$segmento = $odf->setSegment('articles');

foreach($evaluacionCuantitativa AS $elemento) {
	
	$segmento->titreArticle($elemento['titre']);
	//$article->setVars('precioEcnonomico',$menor);
	//$article->setVars('textoCondicion','Oferta más Económica');
	//$article->setVars('proveedor',$element['proveedor']);
	//$article->setVars('precioProveedor',$element['precioProveedor']);
	$segmento->texteArticle($elemento['texte']);//total puntaje
	$segmento->merge();
  
	}
	$odf->mergeSegment($segmento);*/
	
	
	$a = $odf->setSegment('fila');
	
	foreach($evaluacionCuantitativa AS $element) {
		$a->titreArticle($element['titre']);
		$totalPMO_POE = ($menor/$element['precioProveedor']);
		
		
		$a->setVars('textoCondicionProveedor','Oferta mas Economica

'.utf8_decode($element['proveedor']));
		$a->setVars('precioEconomicoProveedor',number_format($menor,2,',','.').'


'.number_format($element['precioProveedor'],2,',','.'));

		$a->setVars('formulaPMO_POE',number_format ($totalPMO_POE,2,',','.'));
		$a->setVars('formulaPMP','50');
		//$a->setVars('formulaPMP',number_format($totalPP));
		$a->texteArticle($element['texte']);
		$a->merge();
	}
	
	$odf->mergeSegment($a);
		
	
	
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
//-----------------------------------------------TOTAL PUNTUACION--------------------------------------//		

	
	$odf->setVars('objeto',utf8_decode($resultado6[0]['ObjetoEvaluacion']));
	$odf->setVars('proveedores',utf8_decode($cadenaProveedor));
	
	$odf->setVars('nombreDirector',utf8_decode($director['Nombres'])." ".utf8_decode($director['Apellido1'])." ".utf8_decode($director['Apellido2']));
	
	$odf->setVars('nombreAnalista',utf8_decode($asistente['Nombres'])." ".utf8_decode($asistente['Apellido1'])." ".utf8_decode($asistente['Apellido2']));
	$odf->setVars('cargoAnalista',$asistente['DescripCargo']);
	
	$odf->setVars('conclusion',utf8_decode($resultado6[0]['Conclusion']));
	$odf->setVars('recomendacion',utf8_decode($resultado6[0]['Recomendacion']));
	//$odf->setVars('fechaCompleta',date('d-m-Y'));
	
	
	
	/*$odf->setVars('direccion',$resultado3['Direccion']);
	$odf->setVars('Lnacimiento',$resultado3['Lnacimiento']);*/
	
	$odf->saveToDisk("../odtphp/documentos/evaluacionCualitativaCuantitativa".$codEvaluacion.".odt");
	
	//$odf->exportAsAttachedFile();
	//echo $codEvaluacion;
 
?>
