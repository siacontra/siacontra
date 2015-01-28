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
	
	
	
	$odf = new odf("../odtphp/procesoCompra/pliego.odt");

	$diaLetras = array('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once',
							'doce','trece','catorce','quince','disciseis','diecisiete','dieciocho','diecinueve','veinte',
							'veintiuno','veintidos','veintitres','venticuatro','venticinco','veintiseies','veintisiete','veintiocho',
							'veintinueve','treinta','treinta y uno');
							
	$mesLetra = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Séptiembre","Octubre","Noviembre","Diciembre",);						
	
	$diaSLetra=array("domingo","lunes","martes","miércoles","jueves","viernes","sábado");

	
		$cadenaCondicionSecue = explode('/',$codRequeGlobal);
		
		$cod_cot=$cadenaCondicionSecue[0];
		$reque=$cadenaCondicionSecue[1];
		$sec=$cadenaCondicionSecue[2];
		
		
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,mp.FlagSNC, mp.condicionRNC,rd.Descripcion,r.Comentarios,ac.*

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_requerimientos r ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_actainicio ac ON (ac.CodActaInicio =c.NroSolicitudCotizacion)
				
			WHERE
				rd.CodRequerimiento in (".$reque.") AND
				c.Secuencia in (".$sec.")
				GROUP BY p.NomCompleto";
				


	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	
	
	
	 
    $d=date('d', strtotime($resp[0]['FechaFin']));
	$m=date('m', strtotime($resp[0]['FechaFin']));
	$anio=date('Y', strtotime($resp[0]['FechaFin']));
		
	$fecha=$d.'/'.$m.'/'.$anio;
	
	$dato=date("w",strtotime($resp[0]['FechaLimite']));
	$dA=date('d', strtotime($resp[0]['FechaLimite']));
	$mA=date('m', strtotime($resp[0]['FechaLimite']));
	$anioA=date('Y', strtotime($resp[0]['FechaLimite']));
	
	$fechaA=$dA.'/'.$mA.'/'.$anioA;
	$diaA = $diaSLetra[$dato];
	$dato=date("w",strtotime($resp[0]['FechaFin']));
	$dia = $diaSLetra[$dato];
	
	$sql1 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp[0]['CodPersonaAsistente']."'";    

    $resultado1 = $objConexion->consultar($sql1,'fila');			
	
	$sql2 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp[0]['CodPersonaAsistente2']."'";    
	
	$resultado2 = $objConexion->consultar($sql2,'fila');	

	$sql3 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp[0]['CodPersonaDirector']."'";    
	
	$resultado3 = $objConexion->consultar($sql3,'fila');	
	
	$odf->setVars('nombreDirector',$resultado3['NomCompleto'].'
	'.$resultado3['DescripCargo']);
	//$odf->setVars('c',$resultado3['DescripCargo']);
	$odf->setVars('nombreAnalista1',$resultado1['NomCompleto']);
	$odf->setVars('cargoAnalista1',$resultado1['DescripCargo']);
	if($resultado2['NomCompleto']!='')
	{
	$odf->setVars('nombreAnalista2',$resultado2['NomCompleto']);
	$odf->setVars('cargoAnalista2',$resultado2['DescripCargo']);
	}
	else 
	{
	$odf->setVars('nombreAnalista2'," ");
	$odf->setVars('cargoAnalista2'," ");
	}

	$odf->setVars('fecha',$diaA.' ('.$fechaA.')');	
	$odf->setVars('diaA',$dia);
	$odf->setVars('fechaA',$fecha);	
			$requerimiento = $resp[0]['Comentarios'];
			
		$cod='CEM-PC-02-01-'.rellenarConCero($resp[0]['NroSolicitudCotizacion'],4).'-'.$anio;
	
	$odf->setVars('requerimiento',$requerimiento);
	$odf->setVars('nroActa',$cod);
	
	$odf->saveToDisk("../odtphp/documentos/pliego".$cod_cot.".odt");

 
?>
