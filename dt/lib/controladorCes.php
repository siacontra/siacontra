<?php
/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SAICOM
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: PHP
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre,
*******************************************************************************************/
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("../funciones.php");
	include("JSON.php");

   	include_once ("../../clases/MySQL.php");
	
	include_once("../../comunes/objConexion.php");
	
	
	
     
	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}



	switch($caso)
	{
            //CASOS 
            			
            case 'buscarMaestroUtiles':

				if($numMaestroUtiles != '')
				{
					$condicion = " WHERE 
					numutilesayuda like '%".$numMaestroUtiles."%'";
				}
				
				$sql = "SELECT * 
				FROM rh_utilesayuda".$condicion;
	
				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
				
           	break;
			
			case 'guardarMaestroUtiles':
		
				$sql = "SELECT numutilesayuda 
				FROM rh_utilesayuda WHERE numutilesayuda=".$numBeneficio;
				
				$resp = $objConexion->consultar($sql,'fila');
				
				if (count($resp) > 0)
				{
					echo '2';
				
				} else {
					
					$montoUtiles = str_replace('.','',$montoUtiles);
					$montoUtiles = str_replace(',','.',$montoUtiles);
						
					if($objConexion->ingresar(array('numutilesayuda,descripcionutiles,periodoutiles,montoasignado,ultimousuario,ultimafecha',
					"'".$numBeneficio."','".$descripcionUtiles."','".$periodoUtiles."',".$montoUtiles.",'".$_SESSION["USUARIO_ACTUAL"]."',NOW()"),'rh_utilesayuda') == true)
					{
						
						echo '1';
						
								
					} else {
						
						echo '0';
					}
				}
			break;
			
			case 'modificarMaestroUtiles':
			
				$montoUtiles = str_replace('.','',$montoUtiles);
				$montoUtiles = str_replace(',','.',$montoUtiles);
					
				if ($objConexion->modificar(array(
					"numutilesayuda='".$numBeneficio."',
					descripcionutiles='".$descripcionUtiles."',
					periodoutiles='".$periodoUtiles."',
					montoasignado=".$montoUtiles.",
					ultimousuario='".$_SESSION['USUARIO_ACTUAL']."',
					ultimafecha=NOW()","codutilesayuda=".$codUtilesAyuda),'rh_utilesayuda') == true)
				{
						
					echo '1';
					
							
				} else {
					
					echo '0';
				}
			break;
			
			case 'eliminarMaestroUtiles':
				
				if($objConexion->eliminar('codutilesayuda='.$codUtilesAyuda,'rh_utilesayuda') == true)
				{
							
					echo '1';
					
							
				} else {
					
					echo '0';
				}

			break;
			
			//CASOS PARA CREAR Y LISTAR PROCESOS DE UTILES 
			case 'CARGAHIJOS':						
			
				$sql = "SELECT * FROM rh_cargafamiliar WHERE CodPersona='".$_POST['codigo']."' and Parentesco ='HI' ";
				$objConexion->ejecutarQuery($sql);
				$resp = $objConexion->getMatrizCompleta();	
				
				$json=new Services_JSON();
				$resp = $json->encode($resp);			
				
				echo $resp;
			
			break;
			case 'GUARDAR':
			
				//$periodo = date("Y-m");		
				$sql_ayuda	=	'SELECT codutilesayuda FROM rh_utilesayuda where periodoutiles ="'.$periodo.'"';
				$objConexion->ejecutarQuery($sql_ayuda);
				$arreg_ayuda = $objConexion->getMatrizCompleta();
				
				
				//echo $arreg_ayuda[0]['codutilesayuda'].'sadfsdfsdfsdf';
				$totalMonto='';
				$arraCod=explode(',',$arrayCodigo);
				$arraNom=explode(',',$arraynombre);
				$arraMon=explode(' ,',$arrayMonto);
				$j= count($arraCod);
				$monto='';
				if($j==1)
				{
					$monto = str_replace('.','',$arraMon[0]);
					$totalMonto = str_replace(',','.',$monto);				
				}
				else{
					for($i=0; $i<count($arraCod); $i++)
					{
						$monto='';
						$montoa='';
						$arraMon[$i] = str_replace(' ','',$arraMon[$i]);
						if(strlen($arraMon[$i])>7)
						{
							$monto = str_replace('.','',$arraMon[$i]);
							$montoa = str_replace(',','.',$monto);
							$montoa = str_replace(' ','',$montoa);
							$totalMonto=$totalMonto+$montoa;					
						}
						else{
							$montob = str_replace(',','.',$arraMon[$i]);
							$montob = str_replace(' ','',$montob);
							
							$totalMonto=$totalMonto+$montob;
							//echo $monto.'-';						
						}
						//echo $totalMonto.'-';
					}	
					
				}			
				
				$ahora=date("Y-m-d H:m:s");			
				$sql="INSERT INTO  rh_beneficiarioutiles (nroBeneficioUtiles,codutilesayuda,codpersonabeneficiario,CodProveedor,observacionutiles,montoutiles, ultimousuario, ultimafecha)
						   VALUES ('".$nroOrden."','".$arreg_ayuda[0]['codutilesayuda']."','".$codPersona."' ,'". $proveedor."','','".$totalMonto."','".$ultimaMod."','".$fechaUltimaMod."')";
				$resp = $objConexion->ejecutarQuery($sql);
				
				//SELECT MAX(id_tabla) AS id FROM tabla
				$sql_ultimo	=	'SELECT MAX(codbeneficiarioutiles) as codbeneficiarioutiles FROM rh_beneficiarioutiles';
				$objConexion->ejecutarQuery($sql_ultimo);
				$arreg_ultimo = $objConexion->getMatrizCompleta();
															 			
				if($resp!=0)
				{					
						for($i=0; $i<count($arraCod);$i++)
						{		
						
							$monto = str_replace('.','',$arraMon[$i]);
							$monto = str_replace(',','.',$monto);
												
					 		$sql2= "INSERT INTO  rh_familarutilesbeneficio (codbeneficiarioutiles,codsecuenciafamiliar,montoutilesfamiliar,ultimousuario,ultimafecha)
									 VALUES('".$arreg_ultimo[0]['codbeneficiarioutiles']."', '".$arraCod[$i]."' , '".$monto."', '".$ultimaMod."','".$fcreacion."')";							
							$resp3 = $objConexion->ejecutarQuery($sql2);
						}
						if($resp3=='1'){$respFinal='1';}else{$respFinal='0';}				
				}						
				echo $respFinal;	
									
			break;
			
			
			case 'BUSCARPERIODOUTILES':		
				$sql ="select periodoutiles from rh_utilesayuda";
				
				$objConexion->ejecutarQuery($sql);
				$arreg_ultimo = $objConexion->getMatrizCompleta();
				
				$json=new Services_JSON();
				$resp = $json->encode($resp);			
				
				echo $resp;
			break;
			
			case 'BUSCARBENEFICIO':
				$sql = "SELECT a.*, b.periodoutiles, b.montoasignado
					FROM rh_beneficiarioutiles as a , rh_utilesayuda as b where a.codbeneficiarioutiles = $codigo and a.codutilesayuda = b.codutilesayuda";
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
			
			case 'BUSCARITEMBENEFICIO':
				
				$sql = "SELECT a.*, b.NombresCarga
					FROM  rh_familarutilesbeneficio as a, rh_cargafamiliar as b
					where a.codbeneficiarioutiles = $codigo and
					a.codsecuenciafamiliar = b.CodSecuencia and
					b.CodPersona='".$persona."'";
					
				$objConexion->ejecutarQuery($sql);
				$resp = $objConexion->getMatrizCompleta();	
				
				$json=new Services_JSON();
				$resp = $json->encode($resp);						
				echo $resp;						
			break;
			
			
			case 'EDITAR':
				$ahora=date("Y-m-d H:m:s");		
				$totalMonto = 0;
				$arraCod=explode(',',$arrayCodigo);
				$arraNom=explode(',',$arraynombre);
				$arraMon=explode(' ,',$arrayMonto);
				
				/*echo strlen($arraMon[0]);
				break;
				*/
				
				$j=count($arraCod);			
				if($j==1)
				{
					$monto = str_replace('.','',$arraMon[0]);
					$totalMonto = str_replace(',','.',$monto);				
				}
				else{
					for($i=0; $i<count($arraCod); $i++)
					{
						$monto='';
						$montoa='';
						$arraMon[$i] = str_replace(' ','',$arraMon[$i]);
						if(strlen($arraMon[$i])>7)
						{
							$monto = str_replace('.','',$arraMon[$i]);
							$montoa = str_replace(',','.',$monto);
							$montoa = str_replace(' ','',$montoa);
							$totalMonto=$totalMonto+$montoa;					
						}
						else{
							$montob = str_replace(',','.',$arraMon[$i]);
							$montob = str_replace(' ','',$montob);
							
							$totalMonto=$totalMonto+$montob;
							//echo $monto.'-';						
						}
						//echo $totalMonto.'-';
					}					
				}			
												
				$sql = "UPDATE rh_beneficiarioutiles
						SET 						 						 
							 observacionutiles	=	'',
							 CodProveedor		=	'".$proveedor."',
							 montoutiles 		= 	$totalMonto,
							
							 ultimousuario 		=	'".$ultimaMod."', 
							 ultimafecha 		=	NOW()					  																			
						WHERE codbeneficiarioutiles = $codBeneficio";
							
				$resp = $objConexion->ejecutarQuery($sql);	
						
				if($resp=='1')
				{																
					//$rs= "SELECT MAX(codBeneficio) AS id FROM rh_beneficio";
					$rs= "DELETE FROM rh_familarutilesbeneficio WHERE codbeneficiarioutiles =".$codBeneficio;
					$objConexion->ejecutarQuery($rs);
					$resp2 = $objConexion->getMatrizCompleta();	
							
					//$id = $resp2[0]['id'];
					
					
					for($i=0; $i<count($arraCod);$i++)
					{				
						/*$montoItem='';		
						$montoItem = str_replace(',','.',$array_monto[$i]);	*/
											
						$monto 		= str_replace('.','',$arraMon[$i]);
						$montoItem  = str_replace(',','.',$monto);
												
					 		$sql2= "INSERT INTO  rh_familarutilesbeneficio (codbeneficiarioutiles,codsecuenciafamiliar,montoutilesfamiliar,ultimousuario,ultimafecha)
									 VALUES('".$codBeneficio."', '".$arraCod[$i]."' , '".$montoItem."', '".$ultimaMod."','".$fcreacion."')";							
							$resp3 = $objConexion->ejecutarQuery($sql2);												
					}
					if($resp3=='1'){$respFinal='1';}else{$respFinal='0';}							
																							
				}
				else {echo 'ERROR AL MODIFICAR';}
				
				
				echo $respFinal;
							
			break;	
			
           	////FIN CASOS
            
            default://para pruebas
                 
      	}


?>
