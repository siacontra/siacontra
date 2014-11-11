<?php 
//session_start(); 
/****************************************************************************************
* DEV: 
* MODULO: 
* OPERADORES_________________________________________________________
* | # |   FECHA    |   HORA   | PROGRAMADOR 
* | 1 | 06/09/2011 | 15:40:26 | Ernesto Rivas
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
		case 'getSector':						
			$sql  = "SELECT cod_sector,descripcion FROM pv_sector WHERE cod_sector='$sector' ORDER BY cod_sector";			
			$resp = $objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			//$a    = $objConexion->getCantidadFilasConsulta();
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);			
			
			echo $resp;
			
		break;
		
		case 'getPrograma':
			$sql  = "SELECT id_programa,cod_programa,descp_programa,cod_sector FROM pv_programa1 WHERE cod_sector='".$sector."' AND cod_programa ='".$programa."' ORDER BY cod_sector,cod_programa";
			$resp = $objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			//$a    = $objConexion->getCantidadFilasConsulta();
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);				
			
			echo $resp;
			
		break;
		
		case 'getSubPrograma':
			$sql  = "SELECT id_sub,cod_subprog,descp_subprog,id_programa FROM pv_subprog1 WHERE id_programa='".$programa."' AND descp_subprog='".$subPrograma."'";
			$resp = $objConexion->ejecutarQuery($sql);
			//$a    = $objConexion->getCantidadFilasConsulta();
			$resp = $objConexion->getMatrizCompleta();
			$json=new Services_JSON();
			$resp = $json->encode($resp);				
			
			echo $resp;
			
		
		break;
		
			
		case 'getProyecto':
			$sql="SELECT id_proyecto,cod_proyecto,descp_proyecto,id_sub FROM pv_proyecto1 WHERE id_sub='".$subPrograma."' AND descp_proyecto = '".$proyecto."'";
			$resp = $objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			//$a    = $objConexion->getCantidadFilasConsulta();
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);				
			
			echo $resp;
			
		break;
		
		case 'getActividad':
			$sql="SELECT id_actividad,cod_actividad,descp_actividad,id_proyecto FROM pv_actividad1 WHERE id_proyecto='".$proyecto."' AND  descp_actividad ='".$actividad."' ORDER BY id_proyecto,id_actividad";
			$resp = $objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			//$a    = $objConexion->getCantidadFilasConsulta();
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);				
			
			echo $resp;
		break;
		
		case 'getPartida':
			
			/*$sql="SELECT a . * , b.denominacion
					FROM pv_antepresupuestodet AS a, pv_partida AS b
					WHERE a.cod_partida = '".$partida."'
					AND a.cod_partida = b.cod_partida 
					AND a.CodAnteproyecto = ".$presupuesto;*/
			$sql="SELECT a . * , b.denominacion
					FROM pv_presupuestodet AS a, pv_partida AS b
					WHERE a.cod_partida = '".$partida."'
					AND a.cod_partida = b.cod_partida 
					AND a.CodPresupuesto = ".$presupuesto;

					$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);	
			
			echo $resp;
		break;
		
		case 'guardar':
		
			$ahora=date("Y-m-d H:m:s");			
			$sql="INSERT INTO pv_credito_adicional (CodOrganismo, CodAnteproyecto, nu_oficio , ff_oficio ,     nu_decreto , ff_decreto ,     tx_motivo , ff_ejecucion , ff_creacion , tx_estatus , tx_preparado , tx_aprobado , tx_ultima_modificacion , ff_ultima_modoficacion , mm_monto_total)
					   VALUES ('$organismo','$codPresupuesto', '$resolucion',  '$fechaResolucion', '$decreto',  '$fechaDecreto', '$motivo',  '$ejercicio',  '$fcreacion', '$estatus',  '$preparado', '$aprobado',   '$preparado',            '$ahora',       '$monto');";
			$resp = $objConexion->ejecutarQuery($sql);
			//echo $resp;
			if($resp=='1')
			{
				/*$array_co_sector		=	explode(',',$co_sector);
				$array_co_programa		=	explode(',',$co_programa);
				$array_co_sub_programa	=	explode(',',$co_sub_programa);
				$array_co_proyecto		=	explode(',',$co_proyecto);
				$array_co_actividad		=	explode(',',$co_actividad);*/
				$array_co_partida		=	explode(',',$co_partida);
				$array_monto_item		=	explode(';,',$monto_item);
				
				
				
				$rs= "SELECT MAX(co_credito_adicional) AS id FROM pv_credito_adicional";
				$objConexion->ejecutarQuery($rs);
				$resp2 = $objConexion->getMatrizCompleta();						
				$id = $resp2[0]['id'];
								
				for($i=0; $i<count($array_co_partida);$i++)
				{
					//echo $array_monto_item[$i];
					$montoItem=str_replace(',', '.', $array_monto_item[$i]);
															
					$sql2= "INSERT INTO pv_item_credito_adicional (co_item_credito_adicional , co_credito_adicional , cod_partida , mm_monto )
								 VALUES (NULL , $id, '$array_co_partida[$i]', '$montoItem')";									
					$resp3 = $objConexion->ejecutarQuery($sql2);
					
					if($resp3==1)
					{
						$respFinal=1;
					}else
					{
						$respFinal=0; 
						break;
					}
			
				}			
				if($respFinal==0)
				{
					$respFinal=0;
					 $sql8="DELETE FROM pv_credito_adicional WHERE co_credito_adicional=".$id;
					$objConexion->ejecutarQuery($sql8);
				}
			}
			
			
			
			echo $respFinal;
			
		break;
		
		
		case 'conformar':
		
			$ahora=date("Y-m-d H:m:s");			
			$sql="UPDATE  pv_credito_adicional 
						SET 																												
							tx_estatus	=  'CF',							
							tx_ultima_modificacion = '$ultimaMod',
							ff_ultima_modoficacion = '$ahora',
							tx_conformado_por = '$confimadoPor',
						 	ff_conformada='$ahora'		
						WHERE 
							`co_credito_adicional`=$coCreditoAdicional";
							
			$resp = $objConexion->ejecutarQuery($sql);
			echo $resp;
		
		break;
		
		case 'revisar':
		
			$ahora=date("Y-m-d H:m:s");			
			$sql="UPDATE  pv_credito_adicional 
						SET 																												
							tx_estatus	=  'RV',							
							tx_ultima_modificacion = '$ultimaMod',
							tx_revisado_por ='$revisadoPor',
							ff_revisada = '$ahora',
							ff_ultima_modoficacion = '$ahora'							
						WHERE 
							co_credito_adicional=$coCreditoAdicional";
							
			$resp = $objConexion->ejecutarQuery($sql);
			echo $resp;
		
		break;
		
		case 'aprobar':
		
			$ahora=date("Y-m-d H:m:s");
			
			$sql="UPDATE  pv_credito_adicional 
						SET 																					
							nu_oficio 	= '$resolucion',
							ff_oficio 	= '$fechaResolucion',
							nu_decreto 	= '$decreto',
							ff_decreto 	= '$fechaDecreto',
							tx_estatus	=  'AP',
							ff_aprobada    = '$ahora',
							tx_ultima_modificacion = '$ultimaMod',
							ff_ultima_modoficacion = '$ahora'
							
						WHERE 
							`co_credito_adicional`=$coCreditoAdicional";
							
			$resp = $objConexion->ejecutarQuery($sql);
			
			$array_co_partida		=	explode(',',$co_partida);
			$array_monto_item		=	explode(';,',$monto_item);
			
			
			for($i=0; $i<count($array_co_partida);$i++)
			{
					//echo $array_monto_item[$i];
					
					$montoItem =0.00;
					$montoItem=str_replace(',', '.', $array_monto_item[$i]);
					
					$montoItem=str_replace(';', '', $montoItem);
					
					 $sql_pre ="UPDATE pv_presupuestodet
								SET MontoAjustado = (MontoAjustado + $montoItem ) 
								WHERE
									Organismo = '".$organismo."' AND
									CodPresupuesto = '".$codPresupuesto."' AND
									cod_partida = '".$array_co_partida[$i]."'";
									
									$objConexion->ejecutarQuery($sql_pre);
					//$montoItem =0.00;
			}
			
			echo $resp;
		
		break;
		
		case 'modificar':
		
			$ahora=date("Y-m-d H:m:s");	
			
			$sql="UPDATE  pv_credito_adicional 
						SET 
							CodOrganismo	= '$organismo',
							nu_oficio		= '$resolucion',
							ff_oficio		= '$fechaResolucion',
							nu_decreto		= '$decreto',
							ff_decreto		= '$fechaDecreto',
							
							tx_motivo		= '$motivo',
							
							ff_ejecucion	= '$ejercicio',
							ff_creacion	= '$fcreacion',
						    /*tx_estatus	= '$estatus',*/
							
							tx_aprobado 	= '$aprobado',
							tx_ultima_modificacion = '$ultimaMod',
							ff_ultima_modoficacion = '$ahora',
							mm_monto_total =  '$monto'
						WHERE 
							co_credito_adicional=$coCreditoAdicional";
					
			/*$sql="INSERT INTO pv_credito_adicional (CodOrganismo, nu_oficio , ff_oficio ,     nu_decreto , ff_decreto ,     tx_motivo , ff_ejecucion , ff_creacion , tx_estatus , tx_preparado , tx_aprobado , tx_ultima_modificacion , ff_ultima_modoficacion , mm_monto_total)
					   VALUES 							 ('$organismo', '$resolucion',  '$fechaResolucion', '$decreto',  '$fechaDecreto', '$motivo',  '$ejercicio',  '$fcreacion', '$estatus',  '$preparado', '$aprobado',   '$preparado',            '$ahora',       '$monto');";*/
			$resp = $objConexion->ejecutarQuery($sql);
			
			if($resp=='1')
			{
				/*$array_co_sector		=	explode(',',$co_sector);
				$array_co_programa		=	explode(',',$co_programa);
				$array_co_sub_programa	=	explode(',',$co_sub_programa);
				$array_co_proyecto		=	explode(',',$co_proyecto);
				$array_co_actividad		=	explode(',',$co_actividad);*/
				$array_co_partida		=	explode(',',$co_partida);
				$array_monto_item		=	explode(';,',$monto_item);
				
				$rs= "DELETE FROM `pv_item_credito_adicional` WHERE `pv_item_credito_adicional`.`co_credito_adicional` = $coCreditoAdicional";
				$objConexion->ejecutarQuery($rs);
				
				
				for($i=0; $i<count($array_co_partida);$i++)
				{
					//$montoItem=str_replace(',', '.', $array_monto_item[$i]);
					$montoItem=str_replace(',', '.', $array_monto_item[$i]);										
					$sql2= "INSERT INTO pv_item_credito_adicional (co_item_credito_adicional , co_credito_adicional , cod_partida , mm_monto )
								 VALUES (NULL , '$coCreditoAdicional', '$array_co_partida[$i]', '$montoItem')";								
					$resp3 = $objConexion->ejecutarQuery($sql2);					
					if($resp3=='1'){$respFinal='1';}else{$respFinal='0';}
			
				}
			}
			
			
			echo $respFinal;
			
		break;
		
		
		case 'buscarCreditoAdicional':
			$sql="SELECT 
							a.co_credito_adicional,
							a.CodOrganismo,
							a.nu_oficio,
							a.ff_oficio,
							a.nu_decreto, 
							a.ff_decreto,
							a.tx_motivo,
							a.ff_ejecucion,
							a.tx_revisado_por,
							a.tx_conformado_por,
							a.ff_creacion,
							a.tx_estatus,
							a.tx_preparado,
							a.tx_aprobado,
							a.tx_ultima_modificacion,
							a.ff_ultima_modoficacion,
							a.mm_monto_total,
							a.ff_revisada,
							a.ff_conformada,
 							a.ff_aprobada, 
							b.Usuario,
							b.CodPersona,
							
							c.Apellido1,
							c.Apellido2,
							
							c.NomCompleto,
							c.Busqueda
							
				  FROM pv_credito_adicional AS a, usuarios AS b, mastpersonas AS c
				  WHERE a.co_credito_adicional = $codigo
				  AND b.Usuario = a.tx_preparado
				  AND c.CodPersona = b.CodPersona";
					$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);	
			
			echo $resp;
		break;
		
		case 'buscarAprobarCreditoAdicional':
			$sql="SELECT *
				  FROM `mastpersonas` 
				  WHERE `CodPersona` = $codigo";
					$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);	
			
			echo $resp;
		break;
		
		case 'buscarItemCreditoAdicional':
			
			$sql="SELECT * FROM `pv_item_credito_adicional` as a, `pv_partida` as b
				  WHERE 
						
						a.cod_partida = b.cod_partida and 
						a.co_credito_adicional = $codigo";
					$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			
			
			$json=new Services_JSON();
			$resp = $json->encode($resp);	
			
			echo $resp;
			
		break;
		
		/*
		case 'editPartida':
			$sql="SELECT * FROM pv_partida";
					$objConexion->ejecutarQuery($sql);
			$resp = $objConexion->getMatrizCompleta();
			
			
			
			for($i=0;$i<count($resp);$i++)
			{
				$aregloPartida = explode('.',$resp[$i]['cod_partida']);
				
				
				$partida = substr($resp[$i]['cod_partida'], 0, 3);  
				$general = substr($resp[$i]['cod_partida'], 3, 2);  
				$esp = substr($resp[$i]['cod_partida'], 5, 2);
				$subesp=substr($resp[$i]['cod_partida'], 7, 2);
				$partida=$partida.'.'.$general.'.'.$esp.'.'.$subesp;
				  
				
				
				
				$sql="UPDATE `pv_partida` SET `cod_partida` = '".$partida."' WHERE cod_partida= '".$resp[$i]['cod_partida']."' LIMIT 1 ;";
					$objConexion->ejecutarQuery($sql);
			}
			echo 'listo';
		break;			
*/
    }//############################################################################################################################
	  
?>
