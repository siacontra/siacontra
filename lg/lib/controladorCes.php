<?php
/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SIACEDA
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez   |  | 27/09/2012 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* |________________________________________________________|_____________________________________________________________________________
* TIPO: PHP
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A f
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("../funciones.php");
	include_once ("../../clases/MySQL.php");
	include_once("../../comunes/objConexion.php");
	
	
	
	
	/*function __autoload ($nombreClase)
	{
		$archivo = '../clases/'.$nombreClase.'.php';
		
		if (file_exists ($archivo))
		{
			  include $archivo;
		}
	}*/


 


     
	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}

//echo $_PARAMETRO["HABILITARALERTAREQ"];

	switch($caso)
	{
            //CASOS 
            case 'buscarItemStockMinimo':
				
				/*$sql = "select A.CodItem, A.Descripcion, B.StockActual, A.StockMin, A.StockMax 
							from lg_itemmast as A 
							join lg_itemalmaceninv as B
							where A.CodItem = B.CodItem and B.StockActual<=A.StockMin";*/
							
				/*$sql = "SELECT A.CodItem, A.Descripcion, B.StockActual, C.StockMin, C.StockMax
						FROM lg_itemmast AS A
						JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
						JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
						AND B.CodAlmacen = C.CodAlmacen
						WHERE B.StockActual <= C.StockMin";*/
					
					
				//funcion modificada para la visialización de del stock minimo
				// Guidmar Espinoza 06/12/2014	
				/*codigo anterior
				 * $sql = "SELECT A.CodItem, A.Descripcion, B.StockActual, C.StockMin, C.StockMax
						FROM lg_itemmast AS A
						JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
						JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
						AND B.CodAlmacen = C.CodAlmacen
						JOIN lg_requerimientosdet AS D ON D.CodItem = A.CodItem
						WHERE B.StockActual <= C.StockMin
						AND D.Estado NOT
						IN ('AN', 'RE', 'CE', 'CO')";
				 * 
				 * */	
				$sql = "SELECT A.CodItem, A.Descripcion, B.StockActual, A.StockMin, A.StockMax
						FROM lg_itemmast AS A
						JOIN lg_itemalmaceninv AS B ON A.CodItem = B.CodItem
						JOIN lg_itemalmacen AS C ON B.CodItem = C.CodItem
						AND B.CodAlmacen = C.CodAlmacen
						WHERE B.StockActual <= A.StockMin";
				
				$resp = $objConexion->consultar($sql,'matriz');
				
				//echo $_SESSION["PERMISOS_ACTUAL"]; exit;
				
				if ((count($resp) == 0) || ((strpos($_SESSION["PERMISOS_ACTUAL"],'05-0003,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0001,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0002,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0006,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0004,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0005,S') == false) && (strpos($_SESSION["PERMISOS_ACTUAL"],'05-0007,S') == false)))
				{
						echo '0';
						
				} else {
					
				$contenidoCapaVentana = '<table width="100%" height="auto" >
													<tbody><tr class="trListaHead">
														<th scope="col" width="100">&Iacute;tem</th>
														<th scope="col">Descripci&oacute;n</th>
														<th scope="col" width="75">Stock Actual</th>
														<th scope="col" width="90">Stock M&iacute;nimo</th>
												   		<th scope="col" width="90">Stock M&aacute;ximo</th>
													</tr>';
																
					for ($i = 0; $i < count($resp); $i++)
					{
						
						
						$contenidoCapaVentana.='<tr class="trListaBody" style="cursor:default;background-color:#FFFF00;"><td align="center">'.$resp[$i]['CodItem'].'</td><td align="center">'
															.$resp[$i]['Descripcion'].'</td><td align="right">'
															.$resp[$i]['StockActual'].'</td><td align="right">'
															.$resp[$i]['StockMin'].'</td><td align="right">'
															.$resp[$i]['StockMax'].'</td></tr>';
						
						
					}
					
					$contenidoCapaVentana.='</tbody></table>';
												
					echo $contenidoCapaVentana;
				}
            break;
//-----------------------			
            case 'buscarRequerimientoPendiente':

				$sql = "SELECT * 
				FROM mastparametros 
				WHERE 
					CodAplicacion = 'GE' and Estado = 'A' and ParametroClave='HABILITARALERTAREQ'";
	
				$parametro = $objConexion->consultar($sql,'matriz');
		
				if (((strpos($_SESSION["PERMISOS_ACTUAL"],'01-0002,S') != false) || (strpos($_SESSION["PERMISOS_ACTUAL"],'01-0003,S') != false) || (strpos($_SESSION["PERMISOS_ACTUAL"],'01-0004,S') != false)) && ($parametro[0]['ValorParam'] == 1))
				{
					$sql = "select count(*) as cantidad from lg_requerimientos as A where A.Estado='PR'";
					
					$resp = $objConexion->consultar($sql,'fila');
					
					
					if ($resp['cantidad'] == 0)
					{
							echo '0';
							
					} else {
						
					$contenidoCapaVentana = '<table width="100%" height="auto">
														<tbody><tr class="trListaHead" >
															<th scope="col" width="100" style="color:#FF0000;align:center;background-color:#FFFF00;">Usted tiene '.$resp['cantidad'].' requerimientos por Revisar.</th>
														</tr>';
																	
						$contenidoCapaVentana.='</tbody></table>';
													
						echo $contenidoCapaVentana;
						
					}
					
            	} else {
            		
            		echo '0';
            	} 
            break;
			
//-----------------------			
            case 'buscarRequerimientoRevisado':
			
				$sql = "SELECT * 
				FROM mastparametros 
				WHERE 
					CodAplicacion = 'GE' and Estado = 'A' and ParametroClave='HABILITARALERTAREQ'";
	
				$parametro = $objConexion->consultar($sql,'matriz');
				
				if ((strpos($_SESSION["PERMISOS_ACTUAL"],'01-0018,S') != false) && ($parametro[0]['ValorParam'] == 1))
				{
					$sql = "select count(*) as cantidad from lg_requerimientos as A where A.Estado='RV'";
					
					$resp = $objConexion->consultar($sql,'fila');
					

					if ($resp['cantidad'] == 0)
					{
							echo '0';
							
					} else {
						
					$contenidoCapaVentana = '<table width="100%" height="auto">
														<tbody><tr class="trListaHead">
															<th scope="col" width="100" style="color:#FF0000;align:center;background-color:#FFFF00;">Usted tiene '.$resp['cantidad'].' requerimientos por Conformar.</th>
														</tr>';
																	
					
						$contenidoCapaVentana.='</tbody></table>';
													
						echo $contenidoCapaVentana;
						
					}
					
            	} else {
            		
            		echo '0';
            	} 
            break;
			
//-----------------------			
            case 'buscarRequerimientoConformado':
			
				$sql = "SELECT * 
				FROM mastparametros 
				WHERE 
					CodAplicacion = 'GE' and Estado = 'A' and ParametroClave='HABILITARALERTAREQ'";
	
				$parametro = $objConexion->consultar($sql,'matriz');
				
				if ((strpos($_SESSION["PERMISOS_ACTUAL"],'01-0005,S')!= false) && ($parametro[0]['ValorParam'] == 1))
				{
					$sql = "select count(*) as cantidad from lg_requerimientos as A where A.Estado='CN'";
					
					$resp = $objConexion->consultar($sql,'fila');
					

					if ($resp['cantidad'] == 0)
					{
							echo '0';
							
					} else {
						
					$contenidoCapaVentana = '<table width="100%" height="auto">
														<tbody><tr class="trListaHead">
															<th scope="col" width="100" style="color:#FF0000;align:center;background-color:#FFFF00;">Usted tiene '.$resp['cantidad'].' requerimientos por Aprobar.</th>
														</tr>';
																	
					
						$contenidoCapaVentana.='</tbody></table>';
													
						echo $contenidoCapaVentana;
						
					}
					
            	} else {
            		
            		echo '0';
            	} 
            break;	
			
			
			case 'marcarDisponiPresuCompra':
			
				/***********REGISTRO LA VARIABLE EN LA SESION PARA PODER CAPTURARLA EN EL ARCHIVO controladorCes.php en el caso marcarDisponiPresuCompra*******/
				//$_SESSION['nroOrdenVerificarPresu'] = $NroOrden; 
				/************************************************************************************************************************************/
				if($proceso == 'nuevo')
				{
					echo '1';
					//$nroOrden = $_SESSION['nroOrdenVerificarPresu']; 
									
				}
				
				if($objConexion->ingresar(array("Anio,CodOrganismo,NroOrden,CodPersona,UltimoUsuario,UltimaFechaModif","'".$anio."','".$fCodOrganismo."','".$nroOrden."','".$_SESSION['CODPERSONA_ACTUAL']."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_verificarpresuordencom') == true)

				  {
	
	
						echo '1';
	
						
	
				  } else {
	
	
						echo '0';
	
				  }
			
			break;
			case 'marcarImpuCompra':
			
				if($objConexion->ingresar(array("Anio,CodOrganismo,NroOrden,CodPersona,UltimoUsuario,UltimaFechaModif","'".$anio."','".$fCodOrganismo."','".$nroOrden."','".$_SESSION['CODPERSONA_ACTUAL']."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_verificarimpuordencom') == true)

				  {
	
	
						echo '1';
	
						
	
				  } else {
	
	
						echo '0';
	
				  }
			
			break;
			case 'marcarDisponiPresuServicio':
			
				if($proceso == 'nuevo')
				{
					echo '1';
					//$nroOrden = $_SESSION['nroOrdenVerificarPresu']; 
									
				}
				
				if($objConexion->ingresar(array("Anio,CodOrganismo,NroOrden,CodPersona,UltimoUsuario,UltimaFechaModif","'".$anio."','".$fCodOrganismo."','".$nroOrden."','".$_SESSION['CODPERSONA_ACTUAL']."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_verificarpresuordenser') == true)

				  {
	
	
						echo '1';
	
						
	
				  } else {
	
	
						echo '0';
	
				  }
			
			break;
			case 'marcarImpuServicio':
			
				if($objConexion->ingresar(array("Anio,CodOrganismo,NroOrden,CodPersona,UltimoUsuario,UltimaFechaModif","'".$anio."','".$fCodOrganismo."','".$nroOrden."','".$_SESSION['CODPERSONA_ACTUAL']."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_verificarimpuordenser') == true)

				  {
	
	
						echo '1';
	
						
	
				  } else {
	
	
						echo '0';
	
				  }
			
			break;
//----------------------GUARDADO Y GENERACION DEL ACTA DE INICIO			
			case 'guardarGenerarActaInicio':
				
				$casoLlamado = 0;
				
				$sql = "select max(CodActaInicio) as maximo from lg_actainicio";
                $resultado = $objConexion->consultar($sql,'fila');
                $CodActaInicio = $resultado['maximo']+1;
				
				$sql = "select max(NroVisualActaInicio) as maximoVisual from lg_actainicio where AnioActa='".date('Y')."'";
				
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroActa = $resultado['maximoVisual']+1;
				
				
				$numeroVisualActa = '0004-CPAI-'.rellenarConCero($numeroActa, 3).'-'.date('Y');
				//echo $CodActaInicio;
				//$vectorCodRequerimientoSecue = array();
				
				$sql = "select Secuencia from lg_requerimientosdet where CodRequerimiento='".$codRequeGlobal."'";
                $resultado = $objConexion->consultar($sql,'matriz');
				
                list($anioActa,$mesActa,$diaActa) = split('-',date('Y-m-d'));
				
				//$vectorCodRequerimientoSecue = explode(',', $codRequerimientoSecuenciaDetalle);
				
				list($d,$m,$a) = split('-',$fechaReunion);
				$fechaReunion=$a.'-'.$m.'-'.$d;
				
				list($d1,$m1,$a1) = split('-',$fechaIP);
				$fechaIP=$a1.'-'.$m1.'-'.$d1;
				
				list($d2,$m2,$a2) = split('-',$fechaFP);
				$fechaFP=$a2.'-'.$m2.'-'.$d2;
				
				if($objConexion->ingresar(array("CodActaInicio,CodPersonaAsistente,CodPersonaAsistente2,CodPersonaDirector,AnioActa, FechaCreacion, NroVisualActaInicio, UltimoUsuario,UltimaFechaModif,FechaReunion,HoraReunion,PresupuestoBase,FechaInicio,FechaFin",
				"".$CodActaInicio.",'".$codAsistenteActaInicio."','".$codAsistenteActaInicio2."','".$codDirectorActaInicio."','".date('Y')."','".date('Y-m-d')."',".$numeroActa.",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),'".$fechaReunion."','".$horaReunion."','".$PresupuestoBase."','".$fechaIP."','".$fechaFP."'"),'lg_actainicio') == true)
				{
					$bandera = 1;
					
					for($i = 0; $i < count($resultado); $i++)
					{
						
						$vectorCondicionSecuencia[$i] = $resultado[$i]['Secuencia'];
						
						
						if($objConexion->ingresar(array("CodRequerimiento,Secuencia,CodActaInicio,UltimoUsuario,UltimaFechaModif","'".$codRequeGlobal."',". $resultado[$i]['Secuencia'].",".$CodActaInicio.",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_requedetalleacta') != true)
						{
				
									$bandera = 0;
									break;
									
				
						}
						
					}
				
					if ($bandera == 1)
					{
						
						
						include ("../odtphp/procesoCompra/actaInicio.php");
	
							echo $CodActaInicio;
							
					} else {
			
							echo '0';
					}
	
				} else {
				
				
					echo '0';
				
				}
			break;
			case 'guardarPliego':
				$cadenaCondicionSecue = explode('/',$_POST['codRequeGlobal']);
				$sql = "select CodActaInicio from lg_actainicio where CodActaInicio=".$cadenaCondicionSecue[0];
                $resultado = $objConexion->consultar($sql,'fila');
                $CodActaInicio = $resultado['CodActaInicio'];
						
						include ("../odtphp/procesoCompra/pliego.php");
	
							echo $resultado['CodActaInicio'];
							
				
			break;
			case 'buscarActaInicio':
				
				$sql = "select C.CodActaInicio, A.Descripcion,C.NroVisualActaInicio,C.AnioActa
							from lg_requerimientosdet as A
							join lg_requedetalleacta as B on A.CodRequerimiento=B.CodRequerimiento
							and A.Secuencia=B.Secuencia
							join lg_actainicio as C on C.CodActaInicio=B.CodActaInicio
							where C.NroVisualActaInicio like '%".$variableBusqueda."%' order by C.CodActaInicio";
				
				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
					
			break;
			
					
//******************   funcion para anular el procedimiento de consulta de precio			
			case 'anular_proc_CP':
				$sql_cons="select * from lg_actainicio where CodActaInicio='".$variableBusqueda."' and AnioActa='".date('Y')."'";
				$res_cons = $objConexion->consultar($sql_cons,'fila');
				if($res_cons['Estado']!='PR')
				{ $v=0; echo $v;}
				else
				{
				echo $sql3 = "UPDATE lg_actainicio 
					SET Estado='AN', MotivoAnulacion='".$an."' , UltimaFechaModif='".date("Y-m-d H:i:s")."',  UltimoUsuario='".$_SESSION['CODEMPLEADO_ACTUAL']."'
					WHERE CodActaInicio='".$variableBusqueda."' and AnioActa='".date('Y')."'";
				
				$resultado3 = $objConexion->consultar($sql3,'fila');	
				$v=1; echo $v;
				}
				
			break;
			
//************************************************************************************			
			
			
			case 'generarActaInicio':
				
				$casoLlamado = 1;	
					
				$sql3 = "select *
					from lg_actainicio where CodActaInicio=".$variableBusqueda;
				
				$resultado3 = $objConexion->consultar($sql3,'fila');	
				
				list($anioActa,$mesActa,$diaActa) = split('-',$resultado3['FechaCreacion']);
				
				
				$sql = "select (max(NroVisualActaInicio)+1) as maximoVisual from lg_actainicio where AnioActa='".date('Y')."'";
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroActa = $resultado['maximoVisual'];
				
				
				//$numeroVisualEvaluacion = 'DA-CPECC-'.rellenarConCero($CodActaInicio, 3).'-'.date('Y');
				$codAsistenteActaInicio2 = $resultado3['CodPersonaAsistente2'];
				$codAsistenteActaInicio = $resultado3['CodPersonaAsistente'];
				$codDirectorActaInicio = $resultado3['CodPersonaDirector'];
				
				$numeroVisualActa = '0004-CPAI-'.rellenarConCero($resultado3['NroVisualActaInicio'], 3).'-'.$resultado3['AnioActa'];
				
				$sql4 = "select CodRequerimiento, Secuencia
					from lg_requedetalleacta where CodActaInicio=".$variableBusqueda;
				
				$resultado4 = $objConexion->consultar($sql4,'matriz');
				
				
				/*$condicionRequerimiento = array();
				$condSecuenReque = array();*/
				
				for($i= 0; $i < count($resultado4); $i++)
				{
					$vectorCondicionSecuencia[$i] = $resultado4[$i]['Secuencia'];
					
					/*$condicionRequerimiento[$i] = $resultado4[$i]['CodRequerimiento'];
					$condSecuenReque[$i] = $resultado4[$i]['Secuencia'];*/
				}
						
					$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
					$cadenaCondicionReque = $resultado4[0]['CodRequerimiento'];
					
					/*$cadenaCondicionReque = implode(',',$condicionRequerimiento);
					$cadenaCondicionSecue = implode(',',$condSecuenReque);*/
					$CodActaInicio = $variableBusqueda;

				
				include ("../odtphp/procesoCompra/actaInicio.php");
				echo $variableBusqueda;
			break;
			case 'modificarGenerarActaInicio':
				
				$casoLlamado = 1;
				
				$sql4 = "select CodRequerimiento, Secuencia
					from lg_requedetalleacta where CodActaInicio=".$codActa;
				
				$resultado4 = $objConexion->consultar($sql4,'matriz');
				
				///////////////////////
				//SELEC DIRECTOR (CAMBIAR EL CODIGO.. MASTPERSONAS ( CODEMPLEADO , CODPERSONA);
			$sql_codDirector = "SELECT 
			
				mastempleado.CodEmpleado, 
				
				mastempleado.CodPersona 
				FROM 
				mastempleado 
 
				where
				 mastempleado.CodEmpleado = '".$codDirectorActaInicio."'";

				$resultado_director = $objConexion->consultar($sql_codDirector,'matriz');
			echo	$codDirectorActaInicio =$resultado_director['CodPersona'];
				
				//////////////////////
				
				$sql8 = "select CodPersonaAsistente, CodPersonaDirector, AnioActa, FechaCreacion, NroVisualActaInicio
					from lg_actainicio where CodActaInicio=".$codActa;
				
				$resultado8 = $objConexion->consultar($sql8,'fila');	
				list($anioActa,$mesActa,$diaActa) = split('-',$resultado8['FechaCreacion']);
				
				$numeroVisualActa = '0004-CPAI-'.rellenarConCero($resultado8['NroVisualActaInicio'], 3).'-'.$resultado8['AnioActa'];
				
				/*
				$condicionRequerimiento = array();
				$condSecuenReque = array();
				*/
				/*if($objConexion->eliminar("CodActaInicio=".$codActa,"lg_requedetalleacta") == true)
				{*/
				
					if($objConexion->modificar(array("CodPersonaAsistente='".$codAsistenteActaInicio."',
					CodPersonaDirector='".$codDirectorActaInicio."',UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',UltimaFechaModif=NOW()","CodActaInicio=".$codActa),
					"lg_actainicio") == true)
					{
				
						$bandera = 1;
						
						for($i= 0; $i < count($resultado4); $i++)
						{
							$vectorCondicionSecuencia[$i] = $resultado4[$i]['Secuencia'];
							
							/*if($objConexion->ingresar(array("CodRequerimiento,Secuencia,CodActaInicio,UltimoUsuario,UltimaFechaModif","'".$resultado4[$i]['CodRequerimiento']."',".$resultado4[$i]['Secuencia'].",".$codActa.",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_requedetalleacta') != true)
							{
					
										$bandera = 0;
										break;
					
							}
							
							$condicionRequerimiento[$i] = $resultado4[$i]['CodRequerimiento'];
							$condSecuenReque[$i] = $resultado4[$i]['Secuencia'];*/
						}
					}
					
				    if ($bandera == 1)
					{
						/*$cadenaCondicionReque = implode(',',$condicionRequerimiento);
						$cadenaCondicionSecue = implode(',',$condSecuenReque);*/
						
						$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
						$cadenaCondicionReque = $resultado4[0]['CodRequerimiento'];
						
						$CodActaInicio = $codActa;
						
						include ("../odtphp/procesoCompra/actaInicio.php");
						echo $CodActaInicio;
							
					} else {
			
							echo '0';
					}
	
				/*} else {
				
				
					echo '0';
				
				}*/
				
				
			break;
			case 'buscarActaInicioEvaluacion':
				
				$sql = "select C.CodActaInicio, A.Descripcion, C.NroVisualActaInicio,C.AnioActa
							from lg_requerimientosdet as A
							join lg_requedetalleacta as B on A.CodRequerimiento=B.CodRequerimiento
							and A.Secuencia=B.Secuencia
							join lg_actainicio as C on C.CodActaInicio=B.CodActaInicio
							where C.NroVisualActaInicio like '%".$variableBusqueda."%' 
							and C.CodActaInicio not in (select T.CodActaInicio from lg_evaluacion as T) 
							order by C.CodActaInicio";
				
				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
					
			break;
			case 'guardarEvaluacionProveedor':
				$casoLlamado = 0;
				
				$sql = "select max(CodEvaluacion) as maximo from lg_evaluacion";
                $resultado = $objConexion->consultar($sql,'fila');
                $CodEvaluacion= $resultado['maximo']+1;
				
				$sql = "select max(NroVisualEvaluacion) as maximoVisual from lg_evaluacion  where AnioEvaluacion='".date('Y')."'";
				
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroEvaluacion = $resultado['maximoVisual']+1;
                
				
				if($objConexion->ingresar(array(" CodEvaluacion , CodActaInicio , ObjetoEvaluacion , CriterioCualitativo , CriterioCuantitativo , Conclusion , Recomendacion , CodPersonaAsistente ,CodPersonaAsistente2 , CodPersonaDirector,UltimoUsuario,UltimaFechaModif, AnioEvaluacion, NroVisualEvaluacion, FechaCreacion 
				","".$CodEvaluacion.",".$codActaInicio.",'".$objetoEvaluacion."','".$criterioEvaluacionCualitativa."','".$criterioEvaluacionCuantitativo."','".$conclusionEvaluacion."','".$recomendacionEvaluacion.
				"','".$asistenteEvaluacion."','".$asistenteEvaluacion2."','".$directorEvaluacion."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),'".date('Y')."',".$numeroEvaluacion.",'".date('Y-m-d')."'"),'lg_evaluacion') == true)
				{
					$bandera = 1;
					
					for($i = 0; $i < count($codProveedor); $i++)
					{
						for($r = 1; $r <= $items; $r++)
						{
					
						$sql = "select max(CodCualiCuanti) as maximo from lg_cualitativacuantitativa";
		                $resultado = $objConexion->consultar($sql,'fila');
		                $CodCualiCuanti= $resultado['maximo']+1;
				//$prueba=$CodCualiCuanti.",".$CodEvaluacion.",'".$codProveedor[$i]."','".$secuencia[$r.$i]."',".$requeTec[$i].",".$tiempoEntregaUno[$i].",".$condicionPagoUno[$i].",".$puntajeCualiTotal[$i].",".$PMO_POE[$r.$i].",".$PP[$r.$i].",'".$_SESSION['CODPERSONA_ACTUAL'];
						
						if($objConexion->ingresar(array(" CodCualiCuanti , CodEvaluacion , CodProveedor , Secuencia, ProvRecRenglon, PuntajeRenglonOf , PuntajeRequeTec , PuntajeTiempoEntrega , PuntajeCondicionPago , TotalPuntajeCuali , PMO_POE , PP , UltimoUsuario , UltimaFechaModif",
						"".$CodCualiCuanti.",".$CodEvaluacion.",'".$codProveedor[$i]."','".$secuencia[$r]."','".$pRec[$r.$i]."',".$requeRenglonOf[$i].",".$requeTec[$i].",".$tiempoEntregaUno[$i].",".$condicionPagoUno[$i].",".$puntajeCualiTotal[$i].",".$PMO_POE[$r.$i].",".$PP[$r.$i].",'"
						.$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_cualitativacuantitativa') != true)
						{
				
									$bandera = 0;
									break;
									
				
						}
						}
					}
				
					if ($bandera == 1)
					{
						//include ("../odtphp/procesoCompra/evaluacionCualiCuanti.php");
	
						echo $CodEvaluacion;
							
					} else {
			
							echo '0';
					}
	
				} else {
				
				
					echo '0';
				
				}
			break;
			case 'buscarEvaluacionModificar':
				
				if($criterioBusqueda == 'todos')
				{
					$sql = "select A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion
						from lg_evaluacion as A
						join lg_cualitativacuantitativa as B on A.CodEvaluacion=B.CodEvaluacion
						JOIN mastpersonas p ON (B.CodProveedor = p.CodPersona)
						JOIN mastproveedores mp ON (B.CodProveedor = mp.CodProveedor) order by A.CodEvaluacion";
						
				} else {
					
						$sql = "select A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion
						from lg_evaluacion as A
						join lg_cualitativacuantitativa as B on A.CodEvaluacion=B.CodEvaluacion
						JOIN mastpersonas p ON (B.CodProveedor = p.CodPersona)
						JOIN mastproveedores mp ON (B.CodProveedor = mp.CodProveedor)
						where A.NroVisualEvaluacion like '%".$variableBusqueda."%' order by A.CodEvaluacion";
					
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
					
			break;
			case 'modificarEvaluacionProveedor':
				$casoLlamado = 0;
				
				if ($objConexion->modificar(array("ObjetoEvaluacion='".$objetoEvaluacion."',
				CriterioCualitativo='".$criterioEvaluacionCualitativa."',
				CriterioCuantitativo='".$criterioEvaluacionCuantitativo."',
				Conclusion='".$conclusionEvaluacion."',
				Recomendacion='".$recomendacionEvaluacion."',
				CodPersonaAsistente='".$asistenteEvaluacion."',
				CodPersonaDirector='".$directorEvaluacion."',
				UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
				UltimaFechaModif=NOW()","CodEvaluacion=".$CodEvaluacion),'lg_evaluacion') == true)
				{
				
						if($objConexion->eliminar("CodEvaluacion=".$CodEvaluacion,"lg_cualitativacuantitativa") == true)
						{
						
							/*$objConexion->modificar(array("CodPersonaAsistente='".$codAsistenteActaInicio."',
							CodPersonaDirector='".$codDirectorActaInicio."',UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',UltimaFechaModif=NOW()","CodActaInicio=".$codActa),
							"lg_actainicio");*/
							
							$bandera = 1;
							
							for($i = 0; $i < count($codProveedor); $i++)
							{
							
								$sql = "select max(CodCualiCuanti) as maximo from lg_cualitativacuantitativa";
				                $resultado = $objConexion->consultar($sql,'fila');
				                $CodCualiCuanti= $resultado['maximo']+1;
						
								if($objConexion->ingresar(array(" CodCualiCuanti , CodEvaluacion , CodProveedor , PuntajeRequeTec , PuntajeTiempoEntrega , PuntajeCondicionPago , TotalPuntajeCuali , PMO_POE , PP , UltimoUsuario , UltimaFechaModif",
								"".$CodCualiCuanti.",".$CodEvaluacion.",'".$codProveedor[$i]."',".$requeTec[$i].",".$tiempoEntregaUno[$i].",".$condicionPagoUno[$i].",".$puntajeCualiTotal[$i].",".$PMO_POE[$i].",".$PP[$i].",'"
								.$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_cualitativacuantitativa') != true)
								{
						
											$bandera = 0;
											break;
											
						
								}
								
							}
						
							if ($bandera == 1)
							{
								//include ("../odtphp/procesoCompra/evaluacionCualiCuanti.php");
			
								echo $CodEvaluacion;
									
							} else {
					
									echo '0';
							}
			
						} else {
						
						
							echo '0';
						
						}

					} else {
					
					
						echo '0';
					
					}
			break;
			case 'buscarOrdenCompra':
				
				/*$sql = "select distinct A.NroOrden, C.NomCompleto as NomProveedor, B.FechaPrometida, A.CodProveedor, B.CodItem 
							from lg_ordencompra as A 
							join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo
							INNER JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona)
							INNER JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor)
							where A.NroOrden like '%".$variableBusqueda."%' and A.NroOrden not in (select NroOrden from lg_controlperceptivo)";
				 */
				 $sql = "select distinct A.NroOrden, C.NomCompleto as NomProveedor, A.CodProveedor 
                            from lg_ordencompra as A  
                            join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo 
                            INNER JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona) 
                            INNER JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor) 
                            where A.Estado in ('AP','CE','CO') and A.NroOrden like '%".$variableBusqueda."%' and A.NroOrden not in (select NroOrden from lg_controlperceptivo)"; 

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			
			case 'guardarGenerarControlPerceptivo':
				
				$casoLlamado = 1;
				
				$sql = "select max(CodControlPerceptivo) as maximo from lg_controlperceptivo";
                $resultado = $objConexion->consultar($sql,'fila');
                $CodControlPerceptivo = $resultado['maximo']+1;
				//".$persona[4]."
				if($objConexion->ingresar(array("CodControlPerceptivo,NroOrden,CodPersonaConforme1,CodPersonaConforme2,CodPersonaConforme3,
				CodPersonaConforme4,CodPersonaConforme5,FechaRegistro,UltimoUsuario,UltimaFechaModif",
				"".$CodControlPerceptivo.",'".$nroOrden."','".$persona[0]."','".$persona[1]."','".$persona[2]."','".$persona[3]."','','".date("Y-m-d")."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_controlperceptivo') == true)
				{
					
					$sql2 = "SELECT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia
								FROM lg_ordencompradetalle AS A
								JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
								JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
								WHERE C.CodControlPerceptivo =".$CodControlPerceptivo;
					
					$resp2 = $objConexion->consultar($sql2,'matriz');
					
					for($i = 0; $i < count ($resp2); $i++)
					{
						$sql4 = "select max(CodControlPerceptivoDetalle) as maximo from lg_controlperceptivodetalle";
		                $resultado4 = $objConexion->consultar($sql4,'fila');
		                $CodControlPerceptivoDetalle = $resultado4['maximo']+1;
						$resp3= 1;
						if($objConexion->ingresar(array("CodControlPerceptivoDetalle,CodControlPerceptivo,CodItem,Secuencia,Recibido,CantidadRecibida,UltimoUsuario,UltimaFechaModif",
						"".$CodControlPerceptivoDetalle.",".$CodControlPerceptivo.",'".$resp2[$i]['CodItem']."',".$resp2[$i]['Secuencia'].",".$resp3.",".$resp2[$i]['CantidadPedida'].",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_controlperceptivodetalle') != true)
						{
							echo '0';
							break;
						}
						
					}
					
					
						include ("../odtphp/procesoCompra/actaControlPerceptivo.php");
	
						echo $CodControlPerceptivo;
							
				} else {
					
					echo '0';
				}
			break;
			case 'buscarControlPerceptivo':
				
				if($criterioBusqueda == 'todos')
				{
					$sql = "SELECT A.CodControlPerceptivo, B.NroOrden, C.NomCompleto AS NomProveedor, A.FechaRegistro
							FROM lg_controlperceptivo AS A
							JOIN lg_ordencompra AS B ON A.NroOrden = B.NroOrden
							JOIN mastpersonas C ON ( B.CodProveedor = C.CodPersona )
							JOIN mastproveedores D ON ( D.CodProveedor = B.CodProveedor )";
					
				} else {
					
					$sql = "SELECT A.CodControlPerceptivo, B.NroOrden, C.NomCompleto AS NomProveedor, A.FechaRegistro
							FROM lg_controlperceptivo AS A
							JOIN lg_ordencompra AS B ON A.NroOrden = B.NroOrden
							JOIN mastpersonas C ON ( B.CodProveedor = C.CodPersona )
							JOIN mastproveedores D ON ( D.CodProveedor = B.CodProveedor )
							where A.CodControlPerceptivo like '%".$variableBusqueda."%'";
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			
			case 'generarControlPerceptivo':
					
					$CodControlPerceptivo = $variableBusqueda;
				
					$sql = "select Estado from lg_controlperceptivo where CodControlPerceptivo=".$CodControlPerceptivo."";
					$resp = $objConexion->consultar($sql,'fila');
					
					if($resp['Estado'] == 1)
					{
						$casoLlamado = 1;
							
					} else {
							
						$casoLlamado = 0;
					} 
					
					
					
					include ("../odtphp/procesoCompra/actaControlPerceptivo.php");
					echo $CodControlPerceptivo;
			
			break;
			case 'modificarGenerarControlPerceptivo':
				$casoLlamado = 0;
				//return;
				
				if ($banderaCerrar == 'false')
				{
					$banderaCerrar = 1;
					
				} else {
						
					$banderaCerrar = 0;
				}
				//CodPersonaConforme5='".$persona[4]."',
				if ($objConexion->modificar(array(
				"CodPersonaConforme1='".$persona[0]."',
				CodPersonaConforme2='".$persona[1]."',
				CodPersonaConforme3='".$persona[2]."',
				CodPersonaConforme4='".$persona[3]."',
				
				Estado=".$banderaCerrar.",
				UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
				UltimaFechaModif=NOW()","CodControlPerceptivo=".$CodControlPerceptivo),'lg_controlperceptivo') == true)
				
				/*if($objConexion->modificar(array("CodControlPerceptivo,NroOrden,CodPersonaConforme1,CodPersonaConforme2,CodPersonaConforme3,
				CodPersonaConforme4,CodPersonaConforme5,FechaRegistro,UltimoUsuario,UltimaFechaModif",
				"".$CodControlPerceptivo.",'".$nroOrden."','".$persona[0]."','".$persona[1]."','".$persona[2]."','".$persona[3]."','".$persona[4]."','".date("Y-m-d")."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_controlperceptivo') == true)*/
				{
					
					$sql2 = "SELECT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia
								FROM lg_ordencompradetalle AS A
								JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
								JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
								WHERE C.CodControlPerceptivo =".$CodControlPerceptivo." ORDER BY A.Secuencia";
					
					$resp2 = $objConexion->consultar($sql2,'matriz');
					
					if($objConexion->eliminar("CodControlPerceptivo=".$CodControlPerceptivo,"lg_controlperceptivodetalle") != true)
					{
						echo '0';
					}
					
					for($i = 0; $i < count ($resp2); $i++)
					{
				
                                $sql4 = "select max(CodControlPerceptivoDetalle) as maximo from lg_controlperceptivodetalle";
		                $resultado4 = $objConexion->consultar($sql4,'fila');
		                $CodControlPerceptivoDetalle = $resultado4['maximo']+1;
				
if($objConexion->ingresar(array("CodControlPerceptivoDetalle,
                                CodControlPerceptivo,
				CodItem,Secuencia,
				Recibido,ObservacionItem,
				CantidadRecibida,
				UltimoUsuario,
				UltimaFechaModif",

				"".$CodControlPerceptivoDetalle.",
				".$CodControlPerceptivo.",
				'".$resp2[$i]['CodItem']."',
				".$resp2[$i]['Secuencia'].",
				".$item[$i].",
				'".$observacionRecibido[$i]."',
				".$cantidadRecibido[$i].",
				'".$_SESSION['CODPERSONA_ACTUAL']."',
                                 NOW()"),
                              'lg_controlperceptivodetalle') != true)
						{
							echo '0';
							break;
						}
						
					}
					
					
						include ("../odtphp/procesoCompra/actaControlPerceptivo.php");
	
						echo $CodControlPerceptivo;
							
				} else {
					
					echo '0';
				}
			break;
			case 'guardarModificarInformeRecomendacion':
			
				if($codInformeRecomendacion == '0')
				{
					$casoLlamado = 1;
				
					$sql = "select max(CodInformeRecomendacion) as maximo from lg_informerecomendacion";
	                $resultado = $objConexion->consultar($sql,'fila');
	                $codInformeRecomendacion = $resultado['maximo']+1;
					
					$sql = "select max(NroVisualRecomendacion) as maximoVisual from lg_informerecomendacion  where AnioRecomendacion='".date('Y')."'";
				
	                $resultado = $objConexion->consultar($sql,'fila');
	                $numeroRecomendacion = $resultado['maximoVisual']+1;
					
					$numeroVisualRecomendacion = '0004-CPIR-'.rellenarConCero($numeroRecomendacion, 3).'-'.date('Y');
					list($anioRecomendacion,$mesRecomendacion,$diaRecomendacion) = split('-',date('Y-m-d'));
		
					if($objConexion->ingresar(array("CodInformeRecomendacion,CodEvaluacion,Asunto,ObjetoConsulta,Conclusiones,Recomendacion,Asistente,Asistente2,Director,
					UltimoUsuario, UltimaFechaModif, AnioRecomendacion, NroVisualRecomendacion, FechaCreacion,TipoAdjudicacion, Numeral",
					"".$codInformeRecomendacion.",".$codEvaluacion.",'".$asunto."','".$objeto."','".$conclusion."','".$recomendacion."','".$persona[3]."','".$persona[4]."','".$persona[4]."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),'".date('Y')."',".$numeroRecomendacion.",'".date('Y-m-d')."','".valorListaTipoAdjudicacion."','".$numeral."'"),'lg_informerecomendacion') == true)
					{
						
						/*include ("../odtphp/procesoCompra/informeRecomendacion.php");
						echo $codInformeRecomendacion;*/
						
						for($g = 0; $g < count($codProveedor); $g++)
						{
							
							$sql = "select max(CodInformeProveedor) as maximo from lg_proveedorrecomendado";
			                $resultado = $objConexion->consultar($sql,'fila');
			                $CodInformeProveedor = $resultado['maximo']+1;
							
							if($objConexion->ingresar(array("CodInformeProveedor,CodInformeRecomendacion,CodProveedorRecomendado,UltimoUsuario,UltimaFechaModif,SecuenciaRecomendacion",
							"".$CodInformeProveedor .",".$codInformeRecomendacion.",'".$codProveedor[$g]."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),".($g+1)),'lg_proveedorrecomendado') == true)
							{
								
								
							} else {
									
								echo '0';
								return;
							}
						}
						
						include ("../odtphp/procesoCompra/informeRecomendacion.php");
						echo $codInformeRecomendacion;
						
					} else {
							
						echo '0';
					}
					
				} else {
					
						$casoLlamado = 0;
					
					//valorListaTipoAdjudicacion
					if ($objConexion->modificar(array(
						"Asunto='".$asunto."',
						ObjetoConsulta='".$objeto."',
						Conclusiones='".$conclusion."',
						Recomendacion='".$recomendacion."',
						TipoAdjudicacion='".$valorListaTipoAdjudicacion."',
						Asistente='".$persona[3]."',
                                                Asistente2='".$persona[4]."',
						Director='".$persona[5]."',
						Numeral='".$numeral."',
						UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
						UltimaFechaModif=NOW()","CodInformeRecomendacion=".$codInformeRecomendacion),'lg_informerecomendacion') == true)
						{
						
						/*include ("../odtphp/procesoCompra/informeRecomendacion.php");
						echo $codInformeRecomendacion;*/
						$objConexion->eliminar('CodInformeRecomendacion='.$codInformeRecomendacion,'lg_proveedorrecomendado');
						
							for($g = 0; $g < count($codProveedor); $g++)
							{
								
								$sql = "select max(CodInformeProveedor) as maximo from lg_proveedorrecomendado";
				                $resultado = $objConexion->consultar($sql,'fila');
				                $CodInformeProveedor = $resultado['maximo']+1;
								
								if($objConexion->ingresar(array("CodInformeProveedor,CodInformeRecomendacion,CodProveedorRecomendado,UltimoUsuario,UltimaFechaModif,SecuenciaRecomendacion",
								"".$CodInformeProveedor.",".$codInformeRecomendacion.",'".$codProveedor[$g]."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),".($g+1)),'lg_proveedorrecomendado') == true)
								{
									
									
								} else {
										
									echo '0';
									return;
								}
							}
							
							$sql8 = "select A.*
									from lg_informerecomendacion as A
									where A.CodInformeRecomendacion=".$codInformeRecomendacion;
									
							$resultado8 = $objConexion->consultar($sql8,'fila');//datos recomendacion
							$numeroVisualRecomendacion = '0004-CPIR-'.rellenarConCero($resultado8['NroVisualRecomendacion'], 3).'-'.$resultado8['AnioRecomendacion'];
							list($anioRecomendacion,$mesRecomendacion,$diaRecomendacion) = split('-',$resultado8['FechaCreacion']);
	
						
							include ("../odtphp/procesoCompra/informeRecomendacion.php");
							echo $codInformeRecomendacion;
							
						} else {
								
							echo '0';
						}
				}
				
			break;
			case 'buscarInformeRecomendacion':
			
				if($criterioBusqueda == 'todos')
				{
					$sql = "SELECT distinct A.CodInformeRecomendacion, A.Asunto, A.ObjetoConsulta, B.CodproveedorRecomendado,C.NomCompleto AS NomProveedor, A.NroVisualRecomendacion, A.AnioRecomendacion
								FROM lg_informerecomendacion AS A
								join lg_proveedorrecomendado as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
								JOIN mastproveedores D ON ( B.CodProveedorRecomendado = D.CodProveedor )
								JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona )
								where B.SecuenciaRecomendacion=1 and A.Estado<>'AP' and  A.CodInformeRecomendacion 
								not in (select CodInformeRecomendacion from lg_declarar_desierto)";
					
				} else {
					
					$sql = "SELECT distinct A.CodInformeRecomendacion, A.Asunto, A.ObjetoConsulta, B.CodproveedorRecomendado,C.NomCompleto AS NomProveedor, A.NroVisualRecomendacion, A.AnioRecomendacion
								FROM lg_informerecomendacion AS A
								join lg_proveedorrecomendado as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
								JOIN mastproveedores D ON ( B.CodProveedorRecomendado = D.CodProveedor )
								JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona )
								where B.SecuenciaRecomendacion=1 and A.Estado<>'AP' and A.NroVisualRecomendacion like '%".$variableBusqueda."%' and A.CodInformeRecomendacion  
								not in (select CodInformeRecomendacion from lg_declarar_desierto)";
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			case 'guardarGenerarInformeAdjudicacion':
				
				$sql = "select max(CodAdjudicacion) as maximo from lg_informeadjudicacion";
				$resultado = $objConexion->consultar($sql,'fila');
				$CodAdjudicacion = $resultado['maximo']+1;	
				
				$sql8="select NroVisualAdjudicacion, AnioAdjudicacion from lg_informeadjudicacion where CodInformeRecomendacion=".$codRecomendacion;
				$resultado8 = $objConexion->consultar($sql8,'fila');
				$nroVisualAux = $resultado8['NroVisualAdjudicacion'];
				
				if($nroVisualAux != '')
				{
						$numeroAdjudicacion = $nroVisualAux;
						
				} else {
						
					$sql = "select max(NroVisualAdjudicacion) as maximoVisual from lg_informeadjudicacion  where AnioAdjudicacion='".date('Y')."'";
	                $resultado = $objConexion->consultar($sql,'fila');
	                $numeroAdjudicacion = $resultado['maximoVisual']+1;
				}
				
				
				/*$sql = "select max(NroVisualAdjudicacion) as maximoVisual from lg_informeadjudicacion  where AnioAdjudicacion='".date('Y')."'";
			
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroAdjudicacion = $resultado['maximoVisual']+1;*/
	                	
				if($objConexion->ingresar(array("CodAdjudicacion,CodInformeRecomendacion,TipoAdjudicacion,FechaCreacion,
				UltimoUsuario,UltimaFechaModif,Codproveedor,NroVisualAdjudicacion,AnioAdjudicacion",
				$CodAdjudicacion.",".$codRecomendacion.",'DT','".date('Y-m-d')."','".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),'".$codProveedor."',".$numeroAdjudicacion.",'".date('Y')."'"),'lg_informeadjudicacion') == true)
				{
					$bande = 0;
					
					for($i = 0; $i< count($requeSecueAdjudicaion); $i++)
					{
						$a = explode('-',$requeSecueAdjudicaion[$i]);
						
						if($objConexion->ingresar(array("CodAdjudicacion,CodRequerimiento,Secuencia,
						UltimoUsuario,UltimaFechaModif",
						"".$CodAdjudicacion.",'".$a[0]."',".$a[1].",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_adjudicaciondetalle') == true)
						{
							$bande = 1;
						}
					}
					
					if($bande == 1)
					{
						
						include ("../odtphp/procesoCompra/informeAdjudicacion.php");
						echo $CodAdjudicacion;
					
					} else {
						
						echo '0';
					}
					
				} else {
							
					echo '0';
				}
						
						
			break;
			case 'guardarPliego':
				
				$sql = "select max(CodActaInicio) as maximo from lg_actainicio";
                $resultado = $objConexion->consultar($sql,'fila');
                $CodActaInicio = $resultado['maximo']+1;
						
						include ("../odtphp/procesoCompra/pliego.php");
	
							echo $CodActaInicio;
							
				
			break;
			case 'buscarInformeAdjudicacion':
				
				if($criterioBusqueda == 'todos')
				{
					$sql = "SELECT distinct B.CodAdjudicacion, B.CodInformeRecomendacion, B.FechaCreacion, B.Codproveedor,C.NomCompleto AS NomProveedor,G.NroVisualRecomendacion, G.AnioRecomendacion,B.NroVisualAdjudicacion,B.AnioAdjudicacion
							FROM lg_informeadjudicacion AS B
							join lg_informerecomendacion as G on G.CodInformeRecomendacion=B.CodInformeRecomendacion
							JOIN mastproveedores D ON ( B.CodProveedor= D.CodProveedor )
							JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona ) 
							where B.CodInformeRecomendacion not in (select E.CodInformeRecomendacion from lg_declarar_desierto as E)
							order by B.CodADjudicacion";
					
				} else {
					
					$sql = "SELECT distinct B.CodAdjudicacion, B.CodInformeRecomendacion, B.FechaCreacion, B.Codproveedor,C.NomCompleto AS NomProveedor,G.NroVisualRecomendacion, G.AnioRecomendacion, B.NroVisualAdjudicacion,B.AnioAdjudicacion
							FROM lg_informeadjudicacion AS B
							join lg_informerecomendacion as G on G.CodInformeRecomendacion=B.CodInformeRecomendacion
							JOIN mastproveedores D ON ( B.CodProveedor= D.CodProveedor )
							JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona )
							where B.NroVisualAdjudicacion like '%".$variableBusqueda."%' 
							and B.CodInformeRecomendacion not in (select E.CodInformeRecomendacion from lg_declarar_desierto as E)
							order by B.CodADjudicacion";
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
				
			break;
			case 'generarAdjudicacion':
				include ("../odtphp/procesoCompra/informeAdjudicacion.php");
				echo $CodAdjudicacion;
			break;
			case 'modificarGenerarInformeAdjudicacion':
			//return;
				if ($objConexion->modificar(array(
				"TipoAdjudicacion='DT',
				UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
				UltimaFechaModif=NOW(),
				CodProveedor='".$codProveedor."'","CodAdjudicacion=".$CodAdjudicacion),'lg_informeadjudicacion') == true)
				{
					$bande = 0;
					
					
					$objConexion->eliminar('CodAdjudicacion='.$CodAdjudicacion,'lg_adjudicaciondetalle');
					
					if(isset($requeSecueAdjudicaion))
					{
						for($i = 0; $i< count($requeSecueAdjudicaion); $i++)
						{
							$a = explode('-',$requeSecueAdjudicaion[$i]);
							
							if($objConexion->ingresar(array("CodAdjudicacion,CodRequerimiento,Secuencia,
							UltimoUsuario,UltimaFechaModif",
							"".$CodAdjudicacion.",'".$a[0]."',".$a[1].",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW()"),'lg_adjudicaciondetalle') == true)
							{
								$bande = 1;
							}
						}
						
						if($bande == 1)
						{
							
							include ("../odtphp/procesoCompra/informeAdjudicacion.php");
							echo $CodAdjudicacion;
						
						} else {
							
							echo '0';
						}
						
					} else {
						
						$objConexion->eliminar('CodAdjudicacion='.$CodAdjudicacion,'lg_informeadjudicacion');
						echo '-1';
						
					}
					
				} else {
						
					echo '0';
				}
				
				
			break;
			case 'guardarDeclararDesierto':
				
				$sql = "select max(CodDesierto) as maximo from lg_declarar_desierto";
				$resultado = $objConexion->consultar($sql,'fila');
				$CodDesierto = $resultado['maximo']+1;	
				
				$sql = "select max(NroVisualDesierto) as maximoVisual from lg_declarar_desierto  where AnioDesierto='".date('Y')."'";
			
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroDesierto = $resultado['maximoVisual']+1;
	                	
				$sql2 = "SELECT D.*
						from lg_informerecomendacion AS B 
						JOIN lg_evaluacion AS C ON C.CodEvaluacion = B.CodEvaluacion
						JOIN lg_actainicio AS D ON D.CodActaInicio = C.CodActaInicio
						WHERE B.CodInformeRecomendacion =".$CodRecomendacion;
						
				$resultado2 = $objConexion->consultar($sql2,'fila');
		
				if($objConexion->ingresar(array("CodDesierto,CodInformeRecomendacion,UltimoUsuario,UltimaFechaModif,NroVisualDesierto,AnioDesierto","".$CodDesierto.",".$CodRecomendacion.",'".$_SESSION['CODPERSONA_ACTUAL']."',NOW(),".$numeroDesierto.",'".date('Y')."'"),'lg_declarar_desierto') == true)
				{
					if ($objConexion->modificar(array(
					"Estado='DS',
					UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
					UltimaFechaModif=NOW()","CodActaInicio=".$resultado2['CodActaInicio']),'lg_actainicio') == true)
					{
						
								
						include ("../odtphp/procesoCompra/informeDesierto.php");
						echo $CodRecomendacion;
						
					} else {
								
							echo '0';
						
					}
					
				} else {
				
				
					echo '0';
				
				}
				
			break;
			case 'buscarInformeDesierto':
				
				if($criterioBusqueda == 'todos')
				{
					$sql = "select distinct B.*,C.*
					from lg_informerecomendacion as B 
					join lg_declarar_desierto as C on C.CodInformeRecomendacion=B.CodInformeRecomendacion
					order by C.UltimaFechaModif desc";
					
				} else {
					
					$sql = "select distinct B.*,C.*
					from lg_informerecomendacion as B 
					join lg_declarar_desierto as C on C.CodInformeRecomendacion=B.CodInformeRecomendacion
					where C.CodDesierto like '%".$variableBusqueda."%' 
					order by C.UltimaFechaModif desc";
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
				
			break;
			case 'generarInformeDesierto':
				include ("../odtphp/procesoCompra/informeDesierto.php");
				echo $CodRecomendacion;
			break;
			case 'generarEvaluacionActa':
				
				$sql = "select * from lg_evaluacion where CodEvaluacion=".$_POST['codEvaluacion'];
				$resp = $objConexion->consultar($sql,'fila');
				
				$numeroVisualEvaluacion = '0004-CPECC-'.rellenarConCero($resp['NroVisualEvaluacion'], 3).'-'.$resp['AnioEvaluacion'];
				
				list($anioEvaluacion,$mesEvaluacion,$diaEvaluacion) = split('-',$resp['FechaCreacion']);
				
				include ("../odtphp/procesoCompra/evaluacion.php");
				echo $_POST['codEvaluacion'];
			break;
			
			case 'buscarRecomendacion':
				
				if($criterioBusqueda == 'todos')
				{
					$sql = "select A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
						from lg_evaluacion as A
						join lg_informerecomendacion as ir on ir.CodEvaluacion=A.CodEvaluacion
						join lg_cualitativacuantitativa as B on A.CodEvaluacion=B.CodEvaluacion
						JOIN mastpersonas p ON (B.CodProveedor = p.CodPersona)
						JOIN mastproveedores mp ON (B.CodProveedor = mp.CodProveedor) 
						where ir.Estado='PR' and ir.CodInformeRecomendacion  
						not in (select CodInformeRecomendacion from lg_declarar_desierto) order by A.CodEvaluacion desc";
						
				} else {
					
						$sql = "SELECT A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
								FROM lg_evaluacion AS A
								JOIN lg_informerecomendacion AS ir ON ir.CodEvaluacion = A.CodEvaluacion
								JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
								JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
								JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
								WHERE ir.Estado = 'PR' and ir.CodInformeRecomendacion  
								not in (select CodInformeRecomendacion from lg_declarar_desierto)
								AND ir.NroVisualRecomendacion LIKE'%".$variableBusqueda."%' order by A.CodEvaluacion desc";
					
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
					
			break;
			case 'generarInformeRecomendacion':
			
				$codInformeRecomendacion = $CodRecomendacion;
				$casoLlamado = 0;
				
				$sql1 = "SELECT *
						FROM lg_proveedorrecomendado AS A
						WHERE A.CodInformeRecomendacion =".$codInformeRecomendacion." AND A.SecuenciaRecomendacion = 
						(SELECT min( B.SecuenciaRecomendacion )
						FROM lg_proveedorrecomendado AS B
						WHERE B.CodInformeRecomendacion =".$codInformeRecomendacion.")";
						
				$resultado1 = $objConexion->consultar($sql1,'fila');//datos recomendacion
				
				$codProveedor[0] = $resultado1['CodProveedorRecomendado'];
				 	
				$persona = array();
				$sql8 = "select A.*
									from lg_informerecomendacion as A
									where A.CodInformeRecomendacion=".$codInformeRecomendacion;
				
											
				$resultado8 = $objConexion->consultar($sql8,'fila');//datos recomendacion
					
				$asunto = $resultado8['Asunto'];
				$objeto= $resultado8['ObjetoConsulta'];
				$conclusion = $resultado8['Conclusiones'];
				$recomendacion = $resultado8['Recomendacion'];
				$persona[3] = $resultado8['Asistente'];
				$persona[4] = $resultado8['Director'];
				
					$numeroVisualRecomendacion = '0004-CPIR-'.rellenarConCero($resultado8['NroVisualRecomendacion'], 3).'-'.$resultado8['AnioRecomendacion'];
					list($anioRecomendacion,$mesRecomendacion,$diaRecomendacion) = split('-',$resultado8['FechaCreacion']);

				
					include ("../odtphp/procesoCompra/informeRecomendacion.php");
					echo $codInformeRecomendacion;
			break;
			case 'generarActaInicioRecomendacion':
				
				$sql5 = "select A.CodActaInicio from lg_actainicio as A 
				join lg_evaluacion as B on A.CodActaInicio = B.CodActaInicio
				join lg_informerecomendacion as C on C.CodEvaluacion=B.CodEvaluacion
				where C.CodInformeRecomendacion=".$CodRecomendacion;
				
				$resultado5 = $objConexion->consultar($sql5,'fila');
                
				$variableBusqueda = $resultado5['CodActaInicio'];
				
				$casoLlamado = 1;	
					
				$sql3 = "select CodPersonaAsistente, CodPersonaDirector, AnioActa, FechaCreacion, NroVisualActaInicio
					from lg_actainicio where CodActaInicio=".$variableBusqueda;
				
				$resultado3 = $objConexion->consultar($sql3,'fila');	
				
				list($anioActa,$mesActa,$diaActa) = split('-',$resultado3['FechaCreacion']);
				
				
				$sql = "select (max(NroVisualActaInicio)+1) as maximoVisual from lg_actainicio where AnioActa='".date('Y')."'";
                $resultado = $objConexion->consultar($sql,'fila');
                $numeroActa = $resultado['maximoVisual'];
				
				
				//$numeroVisualEvaluacion = 'DA-CPECC-'.rellenarConCero($CodActaInicio, 3).'-'.date('Y');
				
				$codAsistenteActaInicio = $resultado3['CodPersonaAsistente'];
				$codDirectorActaInicio = $resultado3['CodPersonaDirector'];
				
				$numeroVisualActa = '0004-CPAI-'.rellenarConCero($resultado3['NroVisualActaInicio'], 3).'-'.$resultado3['AnioActa'];
				
				$sql4 = "select CodRequerimiento, Secuencia
					from lg_requedetalleacta where CodActaInicio=".$variableBusqueda;
				
				$resultado4 = $objConexion->consultar($sql4,'matriz');
				

				for($i= 0; $i < count($resultado4); $i++)
				{
					$vectorCondicionSecuencia[$i] = $resultado4[$i]['Secuencia'];
					
				}
						
					$cadenaCondicionSecue = implode(',',$vectorCondicionSecuencia);
					$cadenaCondicionReque = $resultado4[0]['CodRequerimiento'];
					
					
					$CodActaInicio = $variableBusqueda;

				
				include ("../odtphp/procesoCompra/actaInicio.php");
				echo $variableBusqueda;
			break;
			case 'guardarRevisionRecomendacion':
				
				if ($objConexion->modificar(array(
				"Estado='RV',
				RevisadoPor='".$_SESSION['CODPERSONA_ACTUAL']."',
				FechaRevisado=NOW(),
				UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
				UltimaFechaModif=NOW()","CodInformeRecomendacion=".$codRecomendacion),'lg_informerecomendacion') == true)
				{
					
					echo '1';
					
				} else {
					
					echo '0';
				}

			break;
			case 'buscarRecomendacionRevisada':
			
				if($criterioBusqueda == 'todos')
				{
					$sql = "select A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
						from lg_evaluacion as A
						join lg_informerecomendacion as ir on ir.CodEvaluacion=A.CodEvaluacion
						join lg_cualitativacuantitativa as B on A.CodEvaluacion=B.CodEvaluacion
						JOIN mastpersonas p ON (B.CodProveedor = p.CodPersona)
						JOIN mastproveedores mp ON (B.CodProveedor = mp.CodProveedor) 
						where ir.Estado='RV' and ir.CodInformeRecomendacion  
						not in (select CodInformeRecomendacion from lg_declarar_desierto) order by A.CodEvaluacion desc";
						
				} else {
					
						$sql = "SELECT A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
								FROM lg_evaluacion AS A
								JOIN lg_informerecomendacion AS ir ON ir.CodEvaluacion = A.CodEvaluacion
								JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
								JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
								JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
								WHERE ir.Estado = 'RV' and ir.CodInformeRecomendacion  
								not in (select CodInformeRecomendacion from lg_declarar_desierto)
								AND ir.NroVisualRecomendacion LIKE'%".$variableBusqueda."%' order by A.CodEvaluacion desc";
					
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			case 'guardarAprobacionRecomendacion':
				
				if ($objConexion->modificar(array(
				"Estado='AP',
				AprobadoPor='".$_SESSION['CODPERSONA_ACTUAL']."',
				FechaAprobado=NOW(),
				UltimoUsuario='".$_SESSION['CODPERSONA_ACTUAL']."',
				UltimaFechaModif=NOW()","CodInformeRecomendacion=".$codRecomendacion),'lg_informerecomendacion') == true)
				{
					
					echo '1';
					
				} else {
					
					echo '0';
				}

			break;
			case 'buscarInformeRecomendacionParaAdjudicacion':
			
				if($criterioBusqueda == 'todos')
				{
					$sql = "SELECT distinct A.CodInformeRecomendacion, A.Asunto, A.ObjetoConsulta, B.CodproveedorRecomendado,C.NomCompleto AS NomProveedor, A.NroVisualRecomendacion, A.AnioRecomendacion
								FROM lg_informerecomendacion AS A
								join lg_proveedorrecomendado as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
								JOIN mastproveedores D ON ( B.CodProveedorRecomendado = D.CodProveedor )
								JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona )
								where B.SecuenciaRecomendacion=1 and A.Estado='AP' and  A.CodInformeRecomendacion 
								not in (select CodInformeRecomendacion from lg_declarar_desierto)";
					
				} else {
					
					$sql = "SELECT distinct A.CodInformeRecomendacion, A.Asunto, A.ObjetoConsulta, B.CodproveedorRecomendado,C.NomCompleto AS NomProveedor, A.NroVisualRecomendacion, A.AnioRecomendacion
								FROM lg_informerecomendacion AS A
								join lg_proveedorrecomendado as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
								JOIN mastproveedores D ON ( B.CodProveedorRecomendado = D.CodProveedor )
								JOIN mastpersonas C ON ( D.CodProveedor = C.CodPersona )
								where B.SecuenciaRecomendacion=1 and A.Estado='AP' and A.NroVisualRecomendacion like '%".$variableBusqueda."%' and A.CodInformeRecomendacion  
								not in (select CodInformeRecomendacion from lg_declarar_desierto)";
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			case 'buscarRecomendacionRealizada':
			
				if($criterioBusqueda == 'todos')
				{
					$sql = "select A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
						from lg_evaluacion as A
						join lg_informerecomendacion as ir on ir.CodEvaluacion=A.CodEvaluacion
						join lg_cualitativacuantitativa as B on A.CodEvaluacion=B.CodEvaluacion
						JOIN mastpersonas p ON (B.CodProveedor = p.CodPersona)
						JOIN mastproveedores mp ON (B.CodProveedor = mp.CodProveedor) 
						order by A.CodEvaluacion desc";
						
				} else {
					
						$sql = "SELECT A.CodEvaluacion, p.NomCompleto, A.NroVisualEvaluacion, A.AnioEvaluacion,ir.NroVisualRecomendacion, ir.AnioRecomendacion, ir.CodInformeRecomendacion
								FROM lg_evaluacion AS A
								JOIN lg_informerecomendacion AS ir ON ir.CodEvaluacion = A.CodEvaluacion
								JOIN lg_cualitativacuantitativa AS B ON A.CodEvaluacion = B.CodEvaluacion
								JOIN mastpersonas p ON ( B.CodProveedor = p.CodPersona )
								JOIN mastproveedores mp ON ( B.CodProveedor = mp.CodProveedor )
								WHERE ir.NroVisualRecomendacion LIKE'%".$variableBusqueda."%' order by A.CodEvaluacion desc";
					
				}

				$resp = $objConexion->consultar($sql,'xml');
				echo $resp;
			break;
			
            ////FIN CASOS
            
            default://para pruebas
                 
      }

function rellenarConCero($cadena, $cantidadRelleno)
{
	$cantidadCadena = strlen($cadena);
	
	for($i = 0; $i < ($cantidadRelleno-$cantidadCadena); $i++)
	{
			$cadena = '0'.$cadena;
		
	}			
	
	return $cadena;
}

?>
