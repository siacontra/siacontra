<?php 
session_start(); 
/****************************************************************************************
* DEV: 
* MODULO: 
* OPERADORES_________________________________________________________
* | # |   FECHA    |   HORA   | PROGRAMADOR 
* | 1 | 29/11/2012 | 9:22:26 | Ernesto Rivas
* |__________________________________________________________________
* UBICACION: Venezuela- Sucre- Cumana
* VERSION: 1.0 
* SOPORTE: Ernesto José Rivas Marval 
*******************************************************************************************/

// DESCRIPCION: 

include("../../clases/MySQL.php");
include("../../comunes/objConexion.php");

include("JSON.php");



foreach($_REQUEST as $nombreCampo => $valor)
{
   $$nombreCampo = $valor;
} 

switch($opcion)
	{//############################################################################################################################
		case 'CARGAFAMILIAR':						
			
			$sql = "SELECT * FROM rh_cargafamiliar WHERE CodPersona='".$_POST['codigo']."'";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);			
			
			echo $resp;
			
		break;
		
		case 'CARGARSERVICIO':
			$sql = "SELECT a.* , b.NomCompleto
					FROM rh_ayudamedicaespecifica AS a, mastpersonas AS b
					WHERE a.CodPerAprobar = b.CodPersona";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);			
			
			echo $resp;
		break;
		
		case 'CARGARRAMA':
			$sql = "SELECT * FROM rh_ramaservicio";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);			
			
			echo $resp;
		break;
		
		case 'CARGARINSTITUCION':
			$sql = "SELECT * FROM rh_institucionhcm";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);			
			
			echo $resp;
			
			
		break;
		
		
		case 'CARGARMEDICO':
			$sql = "SELECT * FROM rh_medicoshcm";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
						
		break;
		
		case 'GUARDAR':
			$ahora=date("Y-m-d H:m:s");			
			$sql="INSERT INTO  rh_beneficio (nroBeneficio, CodOrganismo,      tipoSolicitud,        codPersona,      codFamiliar,    codAyudaE,    codRamaS,      idInstHcm,          idMedHcm,    anhoEjecucio,    fechaEjecucion,     estadoBeneficio,    montoTotal,         UltimoUsuario,    UltimaFecha,   	preparadoPor,        aprobadoPor,     planillaSolicitud, informeMedico,   facturaMedicina, recipeMedico, otros)
					   VALUES 					   ('".$nroOrden."','".$organismo."' ,'".$tipoSolicitud."','".$codPersona."','".$codFamilia."','".$servicio."','".$rama."','".$institucion."','".$medico."','".$ejercicio."','".$fcreacion."',  '".$estatus."',     '".$totalAgregado."','".$ultimaMod."','".$ahora."', '".$preparado."', '".$aprobado."',   ".$chPlanilla.",   ".$chInforme.", ".$chFactura.", ".$chRecipe.", ". $chOtro.")";
			$resp = $objConexion->ejecutarQuery($sql);
			
			if($resp=='1')
			{
				$array_codigo		=	explode(',',$nu_codigo);
				$array_descripcion	=	explode(',',$descripcion);
				$array_monto		=	explode(' ,',$monto);
				
				
				$rs= "SELECT MAX(codBeneficio) AS id FROM rh_beneficio";
				$objConexion->ejecutarQuery($rs);
				$resp2 = $objConexion->getMatrizCompleta();	
						
				$id = $resp2[0]['id'];
				
				
				for($i=0; $i<count($array_codigo);$i++)
				{										
					$sql2= "INSERT INTO rh_item_beneficio ( codBeneficio , nroFactura ,          descripcionItem,          montoItem)
								 VALUES                           ('$id'         , '$array_codigo[$i]' , '$array_descripcion[$i]', '$array_monto[$i]')";									
					$resp3 = $objConexion->ejecutarQuery($sql2);	
					
					if($resp3=='1'){$respFinal='1';}else{$respFinal='0';}
			
				}
			}
			
			
			echo $respFinal;
						
		break;
		
		
		case 'MODIFICAR':
			$ahora=date("Y-m-d H:m:s");	
			
			
			
			$sql = "UPDATE rh_beneficio
					SET 
						tipoSolicitud = '".$tipoSolicitud."',
						codPersona = '".$codPersona."',
						codFamiliar = '".$codFamilia."',
						codAyudaE = '".$servicio."',
						codRamaS = '".$rama."',
						idInstHcm = '".$institucion."',
						idMedHcm = '".$medico."',
						anhoEjecucio = '".$ejercicio."',
						fechaEjecucion = '".$fcreacion."',
						montoTotal = ".$totalAgregado.",
						UltimoUsuario = '".$ultimaMod."',
						UltimaFecha = '".$ahora."',
						aprobadoPor = '".$aprobado."',						
						planillaSolicitud = ".$chPlanilla.",
						informeMedico = ".$chInforme.",
						facturaMedicina = ".$chFactura.",
						recipeMedico = ".$chRecipe.",
						otros =  ".$chOtro."												
					WHERE codBeneficio = $codigoBen";
						
			/*$sql="INSERT INTO  rh_beneficio (nroBeneficio, CodOrganismo,      tipoSolicitud,        codPersona,      codFamiliar,    codAyudaE,    codRamaS,      idInstHcm,          idMedHcm,    anhoEjecucio,    fechaEjecucion,     estadoBeneficio,    montoTotal,         UltimoUsuario,    UltimaFecha,          aprobadoPor,     planillaSolicitud, informeMedico,   facturaMedicina, recipeMedico, otros)
					   VALUES 					   ('".$nroOrden."','".$organismo."' ,'".$tipoSolicitud."','".$codPersona."','".$codFamilia."','".$servicio."','".$rama."','".$institucion."','".$medico."','".$ejercicio."','".$fcreacion."',  '".$estatus."',     '".$totalAgregado."','".$ultimaMod."','".$ahora."', '".$aprobado."',   ".$chPlanilla.",   ".$chInforme.", ".$chFactura.", ".$chRecipe.", ". $chOtro.")";*/
			$resp = $objConexion->ejecutarQuery($sql);
			
			if($resp=='1')
			{
				$array_codigo		=	explode(',',$nu_codigo);
				$array_descripcion	=	explode(',',$descripcion);
				$array_monto		=	explode(' ,',$monto);
				
				
				
				//$rs= "SELECT MAX(codBeneficio) AS id FROM rh_beneficio";
				$rs= "DELETE FROM rh_item_beneficio WHERE codBeneficio =".$codigoBen;
				$objConexion->ejecutarQuery($rs);
				$resp2 = $objConexion->getMatrizCompleta();	
						
				//$id = $resp2[0]['id'];
				
				
				for($i=0; $i<count($array_codigo);$i++)
				{				
					$montoItem='';		
					$montoItem = str_replace(',','.',$array_monto[$i]);	
										
					$sql2= "INSERT INTO rh_item_beneficio ( codBeneficio , nroFactura ,          descripcionItem,          montoItem)
								 VALUES                   ('$codigoBen'         , '$array_codigo[$i]' , '$array_descripcion[$i]', '$montoItem')";									
					$resp3 = $objConexion->ejecutarQuery($sql2);	
					
					if($resp3=='1'){$respFinal='1';}else{$respFinal='0';}
			
				}
			}
			
			
			echo $respFinal;
						
		break;
		
		case 'BUSCARBENEFICIO':
			$sql = "SELECT *
				FROM rh_beneficio where codBeneficio = $codigo";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);		
							
			echo $resp;
						
		break;
		
		case 'BUSCARITEMBENEFICIO':
			$sql = "SELECT *
				FROM  rh_item_beneficio where codBeneficio = $codigo";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
						
		break;
		
		case 'BUSCARAPROBARBENEFICIO':
			$sql = "SELECT NomCompleto
					FROM mastpersonas 
					WHERE CodPersona = '".$codigo."'";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
						
		break;
		
		case 'CONSUMIDO':
			$sql = "SELECT SUM(montoTotal) AS CONSUMIDO FROM rh_beneficio WHERE codPersona = '".$codigo."'";
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
						
		break;
		
		case 'CONSUMOESPECIFICO':
		
		//echo $sql = "SELECT SUM(montoTotal) AS CONSUMIDO FROM rh_beneficio WHERE codPersona = '".$codigo."'";
		/*echo $sql = "SELECT SUM(a.montoTotal) AS CONSUMIDO, b.limiteAyudaE, c.limiteAyudaG  
				FROM rh_beneficio as a, rh_ayudamedicaespecifica as b, rh_ayudamedicaglobal as c 
				WHERE a.codPersona ='".$codigo."' and 
				a.codAyudaE = $codEspecifico and
				a.codAyudaE = b.codAyudaE and
				c.codAyudaG = b.codAyudaG
				GROUP BY a.montoTotal, b.limiteAyudaE, c.limiteAyudaG";*/
				
			$sql="SELECT b.limiteAyudaE, a.limiteAyudaG
				  FROM rh_ayudamedicaglobal AS a, rh_ayudamedicaespecifica AS b
				  WHERE b.codAyudaE =$codEspecifico
				  AND a.codAyudaG = b.codAyudaG";	
		
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
		
		
		break;
		
		case 'CONSUMOXPERSONAESPECIFICO':
			$sql="SELECT a.montoTotal AS CONSUMIDO
				FROM rh_beneficio as a 
				right join rh_ayudamedicaespecifica as b on b.codAyudaE=a.codAyudaE
				WHERE a.codPersona ='".$codigo."' and
				a.codAyudaE = $codEspecifico ";	
		
			$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;
		break;
		
		case 'REVISARBENEFICIO':		
			$sql = "UPDATE rh_beneficio SET estadoBeneficio = 'RV' WHERE rh_beneficio.codBeneficio =$codigo";					
			$resp = $objConexion->ejecutarQuery($sql);						
			echo $resp;
		break;
		
		case 'APROBARBENEFICIO':		
			$sql = "UPDATE rh_beneficio SET estadoBeneficio = 'AP' WHERE rh_beneficio.codBeneficio =$codigo";					
			$resp = $objConexion->ejecutarQuery($sql);						
			echo $resp;
		break;
		
		
		case 'PREPARADOPOR':		
			$sql = "SELECT A.Usuario, A.CodPersona, B.NomCompleto  FROM usuarios AS A, mastpersonas AS B
					WHERE A.Usuario='$codigo' AND A.CodPersona = B.CodPersona";					
			$objConexion->ejecutarQuery($sql);		
			$resp = $objConexion->getMatrizCompleta();	
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);						
			echo $resp;				
			
		break;
		
		
		
		
		
		
		
		
		
	}###############################################################################################
	  
?>
