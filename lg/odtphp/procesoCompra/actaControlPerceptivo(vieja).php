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
	
	$odf = new odf("../odtphp/procesoCompra/actaControlPerceptivo.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Ábril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	$sql = "select A.NroOrden, C.NomCompleto as NomProveedor, B.FechaPrometida, A.CodProveedor, B.CodItem,B.Descripcion, B.CantidadPedida,B.Secuencia,
				E.CodPersonaConforme1,E.CodPersonaConforme2,E.CodPersonaConforme3,E.CodPersonaConforme4,E.CodPersonaConforme5
				from lg_ordencompra as A 
				join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo
				JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor)
				join lg_controlperceptivo as E on E.NroOrden=A.NroOrden
				where E.CodControlPerceptivo=".$CodControlPerceptivo;

	$resp = $objConexion->consultar($sql,'matriz');

	/*$sql2 = "SELECT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia,  D.Recibido, D.ObservacionItem, D.CantidadRecibida
				FROM lg_ordencompradetalle AS A
				JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
				JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
				JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
				WHERE C.CodControlPErceptivo =".$CodControlPerceptivo;*/
	
	$sql2 = "SELECT DISTINCT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia, D.Recibido, D.ObservacionItem, D.CantidadRecibida, D.CantidadRecibida, A.PrecioUnit, A.Total
			FROM lg_ordencompradetalle AS A
			JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
			JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
			JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
			AND D.Secuencia = A.Secuencia AND A.CodItem = D.CodItem
			WHERE C.CodControlPerceptivo = ".$CodControlPerceptivo;
			
	$resp2 = $objConexion->consultar($sql2,'matriz');
	
		$sql1 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme1']."'";		
	
	 	$resultado1 = $objConexion->consultar($sql1,'fila');
	 	
	 	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme2']."'";		
	
	 	$resultado2 = $objConexion->consultar($sql2,'fila');
	 	
	 		$sql3 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme3']."'";		
	
	 	$resultado3 = $objConexion->consultar($sql3,'fila');
	 	
	 	$sql4 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme4']."'";		
	
	 	$resultado4 = $objConexion->consultar($sql4,'fila');
	 	
	 	/*$sql5 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme5']."'";		
	
	 	$resultado5 = $objConexion->consultar($sql5,'fila');*/
	 			
	
	$dia = $diaLetras[date('j')-1];		
	$diaNumero = date('d');
	$mes = $mesLetra[date("n")-1];
	$anio = date('Y');
	
	$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',utf8_decode($mes));
	$odf->setVars('anio',$anio);

//	$odf->setVars('nroOrden',$nroOrden);
	
	$odf->setVars('persona1',utf8_decode($resultado1['Nombres'])." ".utf8_decode($resultado1['Apellido1'])." ".utf8_decode($resultado1['Apellido2']));
	$odf->setVars('cedula1',$resultado1['Ndocumento']);	
	$odf->setVars('cargo1',utf8_decode($resultado1['DescripCargo']));
	
	$odf->setVars('persona2',utf8_decode($resultado2['Nombres'])." ".utf8_decode($resultado2['Apellido1'])." ".utf8_decode($resultado2['Apellido2']));
	$odf->setVars('cedula2',$resultado2['Ndocumento']);	
	$odf->setVars('cargo2',utf8_decode($resultado2['DescripCargo']));
	
	$odf->setVars('persona3',utf8_decode($resultado3['Nombres']." ".$resultado3['Apellido1']." ".$resultado3['Apellido2']));
	$odf->setVars('cedula3',$resultado3['Ndocumento']);	
	$odf->setVars('cargo3',utf8_decode($resultado3['DescripCargo']));
	
	$odf->setVars('persona4',utf8_decode($resultado4['Nombres']." ".$resultado4['Apellido1']." ".$resultado4['Apellido2']));
	$odf->setVars('cedula4',$resultado4['Ndocumento']);	
	$odf->setVars('cargo4',utf8_decode($resultado4['DescripCargo']));
	
	/*$odf->setVars('persona5',$resultado5['Nombres']." ".$resultado5['Apellido1']." ".$resultado5['Apellido2']);
	$odf->setVars('cedula5',$resultado5['Ndocumento']);	
	$odf->setVars('cargo5',$resultado5['DescripCargo']);*/


	
	$odf->setVars('empresa',$resp[0]['NomProveedor']);
	
	$requerimiento = "";
	
	if ((count($resp2) > 0) && ($casoLlamado == 0))
	{
		for ($i = 0; $i < count($resp); $i++) {
				
				if($resp2[$i]['Recibido'] == 1) 
				{
					$recibido = ". Entregado: SI";
					
				} else {
					
					$recibido = ". Entregado: NO";
				}
				
				
				
			$listaProveedor = array();
			for ($i = 0; $i < count($resp2); $i++) 
			{	
			
			$listaProveedor[$i] = array('CP' => $resp2[$i]['CantidadPedida'],
									   'CR' =>  $resp2[$i]['CantidadRecibida'],
									   'Desc' =>$resp2[$i]['Descripcion'],
									   'OB' =>  $resp2[$i]['ObservacionItem'],
									   'PU' =>  $resp2[$i]['PrecioUnit'],
									   'T' =>  $resp2[$i]['Total'],
									   /*'Recibido' =>  $resp2[$i]['Recibido'],*/
									   
			);
			}
				$article = $odf->setSegment('total');
			
	foreach($listaProveedor AS $element) {
		
		$article->CP($element['CP']);
		$article->CR($element['CR']);
		$article->Desc($element,utf8_decode['Desc']);
		$article->OB($element['OB']);
		$article->PU($element['PU']);
		$article->T($element['T']);
		/*$article->Recibido($element['Recibido']);*/
		
		$article->merge();
		
	}
	
	$odf->mergeSegment($article);
				
				/*$requerimiento .= "- ".str_replace(".00","",$resp2[$i]['CantidadPedida'])." ".$resp2[$i]['Descripcion']." ".$recibido.", Cant. Recib: ".$resp2[$i]['CantidadRecibida'].", Obser: ".$resp2[$i]['ObservacionItem']."
";*/
			}
		
	} else if ($casoLlamado == 1) {
		
		for ($i = 0; $i < count($resp); $i++) {
			
			
			$listaProveedor[$i] = array('CP' => $resp2[$i]['CantidadPedida'],
									   'CR' =>  $resp2[$i]['CantidadRecibida'],
									   'Desc' =>  $resp2[$i]['Descripcion'],
									   'OB' =>  $resp2[$i]['ObservacionItem'],
									   'PU' =>  $resp2[$i]['PrecioUnit'],
									   'T' =>  $resp2[$i]['Total'],
									   /*'Recibido' =>  $resp2[$i]['Recibido'],*/
									   
			);
				
					/*$requerimiento .= "- ".str_replace(".00","",$resp[$i]['CantidadPedida'])." ".$resp[$i]['Descripcion'].". Entregado: SI__  NO__"."
";*/
			}
			$article = $odf->setSegment('total');
			
	foreach($listaProveedor AS $element) {
		
		$article->CP($element['CP']);
		$article->CR($element['CR']);
		$article->utf8_decode(Desc($element['Desc']));
		$article->OB($element['OB']);
		$article->PU($element['PU']);
		$article->T($element['T']);
		/*$article->Recibido($element['Recibido']);*/
		
		$article->merge();
		
	}
	
	$odf->mergeSegment($article);
		
	}

	/*$odf->setVars('items',$requerimiento);*/

	$odf->saveToDisk("../odtphp/procesoCompra/controlPerceptivo".$CodControlPerceptivo.".odt");

 
?>
