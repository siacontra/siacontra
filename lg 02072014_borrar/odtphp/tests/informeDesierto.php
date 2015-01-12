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
							
	$mesLetra = array("Enero","Febrero","Marzo","Ábril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	
	/*$sql = "select A.NroOrden, C.NomCompleto as NomProveedor, B.FechaPrometida, A.CodProveedor, B.CodItem,B.Descripcion, B.CantidadPedida,B.Secuencia,
				E.CodPersonaConforme1,E.CodPersonaConforme2,E.CodPersonaConforme3,E.CodPersonaConforme4,E.CodPersonaConforme5
				from lg_ordencompra as A 
				join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo
				JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor)
				join lg_controlperceptivo as E on E.NroOrden=A.NroOrden
				where E.CodControlPerceptivo=".$CodControlPerceptivo;

	$resp = $objConexion->consultar($sql,'matriz');

	$sql2 = "SELECT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia,  D.Recibido
				FROM lg_ordencompradetalle AS A
				JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
				JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
				JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
				WHERE C.CodControlPErceptivo =".$CodControlPerceptivo;
	
	$resp2 = $objConexion->consultar($sql2,'matriz');*/
	
	/*$sql4 = "select distinct A.CodRequerimiento, A.Secuencia
			from lg_requedetalleacta as A
			join lg_evaluacion as B on B.CodActaInicio=A.CodActainicio
			join lg_informerecomendacion as C on C.CodEvaluacion=B.CodEvaluacion
			join lg_actainicio as D on D.CodActaInicio=A.CodActainicio
			where C.CodInformeRecomendacion=".$codInformeRecomendacion;*/
	
	$sql7 ="select A.*,B.*
			from lg_informeadjudicacion as A
			join lg_informerecomendacion as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
			where B.CodInformeRecomendacion=".$CodRecomendacion;
				
	$resultado7 = $objConexion->consultar($sql7,'fila');
	
	/*$CodAdjudicacion = $resultado7['CodAdjudicacion'];
				
	$sql4 ="select A.CodRequerimiento, A.Secuencia
			from lg_adjudicaciondetalle as A
			join lg_informeadjudicacion as B on A.CodAdjudicacion=B.CodAdjudicacion
			where A.CodAdjudicacion=".$CodAdjudicacion;*/
		
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
	
	//$cadenaCondicionReque. " ".$cadenaCondicionSecue;
/*	$sql3 = "select A.*, C.NomCompleto as NomProveedor, C.Ndocumento, C.Direccion, C.Lnacimiento
			from lg_informerecomendacion as A
			JOIN lg_proveedorrecomendado as E on A.CodInformeRecomendacion=E.CodInformeRecomendacion
			JOIN mastpersonas C ON (A.CodProveedorRecomendado = C.CodPersona)
			JOIN mastproveedores D ON (D.CodProveedor = C.CodPersona)
			where A.CodInformeRecomendacion=".$codInformeRecomendacion;*/
			
	/*$sql3 ="select A.*, C.NomCompleto as NomProveedor, C.Ndocumento, C.Direccion, C.Lnacimiento,E.CodProveedor
				from lg_adjudicaciondetalle as A
				join lg_informeadjudicacion as E on A.CodAdjudicacion=E.CodAdjudicacion
				JOIN mastpersonas C ON (E.CodProveedor= C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = C.CodPersona)
				where E.CodAdjudicacion=".$CodAdjudicacion;
	
	$resultado3 = $objConexion->consultar($sql3,'fila');//*/
	
//	echo $resultado3['CodProveedor'];
	
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
	
	//print_r($resp5);
	
	/*$sql1 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE p.CodPersona = '".$persona[3]."'";		

 	$resultado1 = $objConexion->consultar($sql1,'fila');//asistente
 	
 	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE p.CodPersona = '".$persona[4]."'";		

 	$resultado2 = $objConexion->consultar($sql2,'fila');//director*/
	 	
	 	
	
	
	
	$dia = $diaLetras[date('j')-1];		
	$diaNumero = date('d');
	$mes = $mesLetra[date("n")-1];
	$anio = date('Y');
	
	$odf->setVars('dia',$dia);
	$odf->setVars('diaNumero',$diaNumero);
	$odf->setVars('mes',$mes);
	$odf->setVars('anio',$anio);

//	$odf->setVars('nroOrden',$nroOrden);
	
	/*$odf->setVars('persona1',$resultado1['Nombres']." ".$resultado1['Apellido1']." ".$resultado1['Apellido2']);
	$odf->setVars('cedula1',$resultado1['Ndocumento']);	
	$odf->setVars('cargo1',$resultado1['DescripCargo']);
	
	$odf->setVars('persona2',$resultado2['Nombres']." ".$resultado2['Apellido1']." ".$resultado2['Apellido2']);
	$odf->setVars('cedula2',$resultado2['Ndocumento']);	
	$odf->setVars('cargo2',$resultado2['DescripCargo']);*/
	
	//$odf->setVars('asunto',$asunto);
	$odf->setVars('objetoConsulta',$resultado7['ObjetoConsulta']);
	/*$odf->setVars('conclusiones',$conclusion);
	$odf->setVars('recomendaciones',$recomendacion);*/
	
	/*$cadenaProveedor = '';
	
	for ($i = 0; $i < count($proveedor); $i++) 
	{
		
		$cadenaProveedor .= "- ".$proveedor[$i]."
";
	}*/
	
	//$odf->setVars('proveedores',$cadenaProveedor);	
	
	//$odf->setVars('proveedor',$resultado3['NomProveedor']);
	//$odf->setVars('rifproveedor',$resultado3['Ndocumento']);
	//$odf->setVars('montoCotizado',number_format($resp5['total'],2,',','.'));
	//$odf->setVars('montoCotizado',$resp5['total']);
	//$odf->setVars('direccion',$resultado3['Direccion']);
	//$odf->setVars('Lnacimiento',$resultado3['Lnacimiento']);
	
	$sql3 = "select RepresentLegal, DocRepreLeg from mastorganismos
			where CodOrganismo='".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."'";	 			
	
	$resultadoOrganismo = $objConexion->consultar($sql3,'fila');//
	
	$odf->setVars('contralor',$resultadoOrganismo['RepresentLegal']);
	$odf->setVars('cedula',$resultadoOrganismo['DocRepreLeg']);
	
	
	
	
	
	$odf->saveToDisk("../odtphp/procesoCompra/declararDesierto".$CodRecomendacion.".odt");

 
?>