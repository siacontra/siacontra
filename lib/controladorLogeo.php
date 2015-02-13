<?php
/****************************************************************************************
* DEV: CONTRALORIA DEL ESTADO SUCRE - VENEZUELA
* PROYECTO: SAICOM
* OPERADORES_____________________________________________________________________________________________________________________________
* | # | PROGRAMADOR          |  |   FECHA    |  |   HORA   |   CELULAR  |   VERSION PAG  | DESCRIPCION DEL CAMBIO 
* | 2 | Christian Hernandez  |  | 22/04/2013 |  | 02:16:18 | 04128354891|      0.1.1.A   | creacion del script
* | 3 | Carlos Marcano       |  | 01/02/2014 |  | 02:16:18 | 04267968744|      0.1.1.B   | actualizacion del script 
* |________________________________________________________|_____________________________________________________________________________
* TIPO: PHP
* DESCRIPCION: 
* UBICACION: VENEZUELA, SUCRE, CUMANA
* VERSION: 0.1.1.A 
* SOPORTE: Christian Hernandez 
* CONTACTO: www.cgesucre.gob.ve, @CESucre, contraloria.estado.sucre@cgesucre.gob.ve
*******************************************************************************************/
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("funciones.php");

	include_once ("../clases/MySQL.php");

	include_once("../comunes/objConexion.php");
	
	include("../clases/class.phpmailer.php");
	include("../clases/class.smtp.php");
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


	switch($caso)
	{
		//CASOS PARA VALIDAR EL INICIO DE SESION
		case 'iniciarSesion':
		
			$sql="SELECT u.CodPersona, e.CodOrganismo, o.Organismo,u.Usuario,u.Clave,u.Estado,u.CodPersona,e.CodDependencia FROM usuarios u INNER JOIN mastempleado e ON (u.CodPersona=e.CodPersona) INNER JOIN mastorganismos o ON (e.CodOrganismo=o.CodOrganismo) WHERE u.Usuario='".$accesoUsuario."' and u.Clave='".$accesoClave."'";		
			$resultado = $objConexion->consultar($sql,'fila');
			
			if(count($resultado) > 1)
			{
				if($resultado['Estado'] == "I")
				{
					echo $objConexion->devolverXML(array("resp"=>"0"));
					
				} else {
					
					
					$_SESSION["CADENA_USUARIO"] = $resultado['Usuario'];
					$_SESSION["CADENA_CLAVE"] = $resultado['Clave'];
					$_SESSION["CADENA_ORGANISMO"] = $resultado['CodOrganismo'];
					
					$_SESSION["CODPERSONA_ACTUAL"]=$resultado['CodPersona'];
					$_SESSION["DEPENDENCIA_ACTUAL"]=$resultado['CodDependencia'];
					
					//datos para registrar la sesion del usuario
					$_SESSION["UltimaSesion"]=date('Y-m-d h:i:s');
					$_SESSION["IP"]=$_SERVER['REMOTE_ADDR'];
					$_SESSION["HOSTNAME"]=gethostbyaddr($_SERVER['REMOTE_ADDR']);
					
					// consulta
					$session_array  = $objConexion->compararSesion($_SESSION["CADENA_USUARIO"]);

					//var auxiales
					$session_usuario = $session_array ["Usuario"];
					$session_ip = $session_array ["IP"];
					$session_hostname = $session_array ["HOSTNAME"];
					$session_ultimasesion = $session_array ["UltimaSesion"];
					
					 echo $objConexion->devolverXML(array(array("resp"=>"1")));
					 $objConexion->registrarSesion();
					/*
					if ($session_ip == NULL || $session_ip == '') 
					{
					 echo $objConexion->devolverXML(array(array("resp"=>"1")));
					 $objConexion->registrarSesion();
					}
					else {
					
					
					if ($session_ip != $_SESSION["IP"] || $session_ip != NULL ) 
					{
						echo $objConexion->devolverXML(array(array("resp"=>"3", "ip"=> $session_ip  , "hostname"=>$session_hostname)));
						
					}else
					{
					
					echo $objConexion->devolverXML(array(array("resp"=>"1")));
					 $objConexion->registrarSesion();
				   }
				   
				   }//else null*/
				}
				
			} else {
				
				echo $objConexion->devolverXML(array(array("resp"=>"2")));
			}
		break;
		case 'verificarDatosSesion':
				
			if(isset($_SESSION["CADENA_USUARIO"]) && isset($_SESSION["CADENA_CLAVE"]) && isset($_SESSION["CADENA_ORGANISMO"]))
			{
				echo $objConexion->devolverXML(array(array("resp"=>"1","usuario"=>$_SESSION["CADENA_USUARIO"],"clave"=>$_SESSION["CADENA_CLAVE"],"organismo"=>$_SESSION["CADENA_ORGANISMO"])));
				
			} else {
				
				echo $objConexion->devolverXML(array(array("resp"=>"0")));
			}
		break;	
		case 'cerrarSesion':
			
		//	$objConexion->clearSesion();  
			
			 $_SESSION = array();
		
             
			if (session_destroy())
			{
			        
				echo $objConexion->devolverXML(array(array("resultadoCerrarSesion"=>"1")));
			       
			        
			} else {
			        
				echo $objConexion->devolverXML(array(array("resultadoCerrarSesion"=>"0")));
			}
		break;
		////FIN CASOS
		
		case 'alertaRRHH':
			
			
		       $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$dependenciaCondicion = $_SESSION["DEPENDENCIA_ACTUAL"];	
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$dependenciaCondicion = $banderaListaAlerta;	
				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				}
		
			//APROBAR PERMISOS

			
			  $sql1 = "SELECT * from rh_permisos where Estado='P' and Aprobador='".$personaCondicion."'";
		     
			//$resultado1 = $objConexion->consultar($sql1);
			//echo $objConexion->getCantidadFilasConsulta();
			
			//ITEMS PARA CONFORMAR VACACIONES
	
			$sql2 = "SELECT vs.Anio, vs.CodSolicitud, vs.Tipo, vs.Fecha, vs.Estado, p.NomCompleto, e.CodEmpleado,e.CodDependencia
					FROM rh_vacacionsolicitud vs INNER JOIN mastpersonas p ON (p.CodPersona = vs.CodPersona) 
					INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona) 
					JOIN seguridad_autorizaciones sa 
					ON (sa.Usuario = '".$usuarioCondicion."' AND sa.CodAplicacion = 'RH' and sa.Concepto='01-0028' and (sa.FlagModificar = 'S' or sa.FlagAdministrador = 'S'))
					WHERE (vs.CodOrganismo = '".$dependenciaCondicion."') AND (vs.Estado = 'RV')
					AND e.CodDependencia in 
					(select CodDependencia from seguridad_alterna as seal 
					where seal.Usuario ='".$usuarioCondicion."' AND seal.FlagMostrar='S' AND seal.CodAplicacion='RH')";
			 
			
			//ITEMS APROBAR VACACIONES

			$sql3 = "SELECT vs.Anio, vs.CodSolicitud, vs.Tipo, vs.Fecha, vs.Estado, p.NomCompleto, e.CodEmpleado,e.CodDependencia
					FROM rh_vacacionsolicitud vs INNER JOIN mastpersonas p ON (p.CodPersona = vs.CodPersona) 
					INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona) 
					JOIN seguridad_autorizaciones sa 
					ON (sa.Usuario = '".$usuarioCondicion."' AND sa.CodAplicacion = 'RH' and sa.Concepto='01-0027' and (sa.FlagModificar = 'S' or sa.FlagAdministrador = 'S'))
					WHERE (vs.CodOrganismo = '".$dependenciaCondicion."') AND (vs.Estado = 'CO')
					AND e.CodDependencia in 
					(select CodDependencia from seguridad_alterna as seal 
					where seal.Usuario ='".$usuarioCondicion."' AND seal.FlagMostrar='S' AND seal.CodAplicacion='RH') ";
			//echo $sql3;
		    
			//ITEMS ARPOBAR HCM

			$sql4 = "SELECT a.codBeneficio, a.nroBeneficio, a.tipoSolicitud, a.codPersona, a.codAyudaE, a.estadoBeneficio, a.montoTotal
						FROM rh_beneficio AS a
						JOIN seguridad_autorizaciones sa ON (sa.Usuario = '".$usuarioCondicion."'
						AND sa.CodAplicacion = 'RH'
						AND sa.Concepto = '05-0017'
						AND (sa.FlagModificar = 'S' or sa.FlagAdministrador = 'S'))
						WHERE estadoBeneficio = 'RV'";
			
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql3);
			$cant3 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql4);
			$cant4 = $objConexion->getCantidadFilasConsulta();
			
			
			//echo $_SESSION["ORGANISMO_ACTUAL"];
			$totalConsulta = $cant1+$cant2+$cant3+$cant4;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." permiso(s) por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." vacacion(es) por conformar","total"=>$totalConsulta);
			$cantCadenaAlert[2] = array("cant"=>$cant3,"cadena"=>"Tiene ".$cant3." vacacion(es) por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[3] = array("cant"=>$cant4,"cadena"=>"Tiene ".$cant4." solicitud(es) de HCM por aprobar","total"=>$totalConsulta);
			
			
			/*"cant2"=>$cant2,"cant3"=>$cant3,"cant4"=>$cant4,"total"=>$totalConsulta);
			
			$cadenaGlobo = array(")
			,
			);*/
			
			$t = 4;
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
			
			
		
		break;
		case 'alertaPresupuesto':
		
					
			
		       $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$dependenciaCondicion = $_SESSION["DEPENDENCIA_ACTUAL"];	
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$dependenciaCondicion = $banderaListaAlerta;	
				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				}
		
			
			//CONFORMAR CREDITO ADICIONAL


			$sql1 = "SELECT DISTINCT co_credito_adicional  porConformar
					FROM pv_credito_adicional AS a, seguridad_autorizaciones AS seau
					WHERE a.tx_estatus = 'RV'
					AND seau.CodAplicacion = 'PV'
					AND ((seau.Concepto = '01-0018' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0018' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
			//echo $sql1;
		
			//APROBAR CREDITO ADICIONAL
			
			
		

			$sql2 = "SELECT DISTINCT co_credito_adicional  porAprobar
					FROM pv_credito_adicional AS a, seguridad_autorizaciones AS seau
					WHERE a.tx_estatus = 'CF'
					AND seau.CodAplicacion = 'PV'
					AND ((seau.Concepto = '01-0017' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0017' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
								
			
					
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1+$cant2;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." crédito(s) adicional(es) por conformar","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." crédito(s) adicional(es) por aprobar","total"=>$totalConsulta);
			
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
			
			
		
		break;
		case 'alertaNomina':
			
						
		       $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$dependenciaCondicion = $_SESSION["DEPENDENCIA_ACTUAL"];	
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$dependenciaCondicion = $banderaListaAlerta;	
				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				}
			
			//APROBAR PROCESO NOMINA
			
			$sql1 = "SELECT  Periodo  
					FROM pr_procesoperiodo AS a, seguridad_autorizaciones AS seau
					WHERE a.FlagAprobado = 'N'
					AND seau.CodAplicacion = 'NOMINA'
					AND ((seau.Concepto = '05-0005' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '05-0005' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
			
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." periodo(s) de nómina por aprobar","total"=>$totalConsulta);
						
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
	
		break;
		case 'alertaCuentasporPagar':
		
						$sqlDependencia = "SELECT md . * , usu.Usuario
								FROM mastdependencias AS md
								JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
								WHERE CodDependencia ='".$banderaListaAlerta."'";

			$resp = $objConexion->consultar($sqlDependencia,'fila');
			
			if($banderaListaAlerta == '') 
			{
					$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				
			} else {
				
				$usuarioCondicion = $resp['Usuario'];
			}
				
			//$usuarioCondicion = $_SESSION["CADENA_USUARIO"];

				//REVISAR OBLIGACION
				    $sql1 = "SELECT *
					FROM ap_obligaciones AS a, seguridad_autorizaciones AS seau
					WHERE a.Estado = 'PR'
					AND seau.CodAplicacion = 'AP'
					AND ((seau.Concepto = '01-0002'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0002' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";

				//APROBAR OBLIGACION
				$sql2 = "SELECT  *
					FROM ap_obligaciones AS a, seguridad_autorizaciones AS seau
					WHERE a.Estado = 'RV'
					AND seau.CodAplicacion = 'AP'
					AND ((seau.Concepto = '01-0003'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0003' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
						
		
					
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1+$cant2;
			
			$cantCadenaAlert = array(array());
			
			
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." obligación(es) por revisar","total"=>$totalConsulta);
			/*if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0003') || ($banderaListaAlerta == '0011'))
			{*/
				$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." obligación(es) por aprobar","total"=>$totalConsulta);
			//}
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
			
		break;
		case 'alertaActivoFijo':
			
			
					       $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$dependenciaCondicion = $_SESSION["DEPENDENCIA_ACTUAL"];	
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$dependenciaCondicion = $banderaListaAlerta;	
				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				}
				
				
			//APROBAR ALTA DE ACTIVO
			
	
			$sql1 = "SELECT *
				FROM af_activo AS a
				WHERE a.Estado = 'PE'
				AND (SELECT count( * ) FROM seguridad_autorizaciones AS seau 
					WHERE seau.CodAplicacion = 'AF'
					AND ((seau.Concepto = '02-0001' AND seau.FlagMostrar = 'S')
					OR (seau.FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."') >0";
			//echo $sql1;
		
			//APROBAR MOVIMIENTO
			
			
			$sql2 = "SELECT  * FROM af_movimientos AS a
					WHERE a.Estado = 'PR'
					AND  (SELECT count( * ) FROM seguridad_autorizaciones AS seau 
					WHERE seau.CodAplicacion = 'AF'
					AND ((seau.Concepto = '01-0004' AND seau.FlagMostrar = 'S')
					OR (seau.FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."') >0";
			
			//APROBAR BAJA ACTIVO
			$sql3 = "SELECT  * FROM af_transaccionbaja AS a
					WHERE a.Estado = 'PR'
					AND  (SELECT count( * ) FROM seguridad_autorizaciones AS seau 
					WHERE seau.CodAplicacion = 'AF'
					AND ((seau.Concepto = '02-0003' AND seau.FlagMostrar = 'S')
					OR (seau.FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."') >0";
								
					
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql3);
			$cant3 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1+$cant2+$cant3;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." activo(s) por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." movimiento(s) de activo por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[2] = array("cant"=>$cant3,"cadena"=>"Tiene ".$cant3." activo(s) por dar de baja","total"=>$totalConsulta);
			
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
		
		break;
		
		case 'alertaPlanificacionFiscal':
		
			    $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$dependenciaCondicion = $_SESSION["DEPENDENCIA_ACTUAL"];	
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$dependenciaCondicion = $banderaListaAlerta;	
				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				
				}
		
			 
			//REVISAR PLANIFICACION

			$sql1 = "SELECT af.CodActuacion
					FROM pf_actuacionfiscal as af, seguridad_autorizaciones AS seau
					where af.Estado='PR' -- and af.CodDependencia='".$dependenciaCondicion."'
					AND seau.CodAplicacion = 'PF'
					AND ((seau.Concepto = '01-0003'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0003' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
			//echo $sql1;
		  
			//APROBAR PLANIFICACION
			

			$sql2 = "SELECT af.CodActuacion
					FROM pf_actuacionfiscal as af, seguridad_autorizaciones AS seau
					where af.Estado='RV' -- and af.CodDependencia='".$dependenciaCondicion."'
					AND seau.CodAplicacion = 'PF'
					AND ((seau.Concepto = '01-0004'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0004' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
			
			//REVISAR PRORROGA

			$sql3 = "SELECT prog.CodProrroga
					FROM pf_prorroga AS prog, seguridad_autorizaciones AS seau
					WHERE prog.Estado = 'PR' -- and af.CodDependencia='".$dependenciaCondicion."'
					AND seau.CodAplicacion = 'PF'
					AND ((seau.Concepto = '01-0009'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0009' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
			//echo $sql1;
		  
			//APROBAR PRORROGA
							  if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )
		  {	
			$sql4 = "SELECT prog.CodProrroga
					FROM pf_prorroga AS prog, seguridad_autorizaciones AS seau
					WHERE prog.Estado = 'RV' -- and af.CodDependencia='".$dependenciaCondicion."'
					AND seau.CodAplicacion = 'PF'
					AND ((seau.Concepto = '01-0010'	AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0010' AND FlagAdministrador = 'S'))
					AND seau.Usuario = '".$usuarioCondicion."'";
								
			//echo $sql1;
		}		
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql3);
			$cant3 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql4);
			$cant4 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1+$cant2+$cant3+$cant4;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." planificación(es) por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." planificación(es) por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[2] = array("cant"=>$cant3,"cadena"=>"Tiene ".$cant3." prorroga(s) por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[3] = array("cant"=>$cant4,"cadena"=>"Tiene ".$cant4." prorroga(s) por aprobar","total"=>$totalConsulta);
			
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
		
		break;
		case 'alertaControlDocumento':
			
			   $sqlDependencia = "SELECT md . * , usu.Usuario,usu.CodPersona
				FROM mastdependencias AS md
				JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
				WHERE CodDependencia ='".$banderaListaAlerta."'";

				$resp = $objConexion->consultar($sqlDependencia,'fila');

				if($banderaListaAlerta == '') 
				{
				$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				$personaCondicion =$_SESSION["CODPERSONA_ACTUAL"];

				} else {

				$usuarioCondicion = $resp['Usuario'];
				$personaCondicion = $resp['CodPersona'];
				
				}
				
				
			//DOCUMENTOS PREPARADOS
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )			 
			{		
			$sql1 = "SELECT a.Cod_DocumentoCompleto 
				FROM cp_documentointerno as a , seguridad_autorizaciones AS seau
				where a.Estado='PP' AND a.Cod_Remitente='".$personaCondicion ."'
				AND seau.CodAplicacion = 'CP'
				AND ((seau.Concepto = '01-0011'	AND seau.FlagMostrar = 'S')
				OR (seau.Concepto = '01-0011' AND FlagAdministrador = 'S'))
				AND seau.Usuario = '".$usuarioCondicion."'";
				
				//AND seau.Usuario = '".$_SESSION["CADENA_USUARIO"]."'";
			//echo $sql1;
			}
		
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )			 
			{
			//DOCUMENTOS POR DAR ACUSE
			$sql2 = "SELECT a.Cod_Documento
			FROM cp_documentodistribucion as a ,seguridad_autorizaciones AS seau
			where a.Estado='EV' AND a.CodPersona='".$personaCondicion ."' 
			AND a.Cod_Documento not in (SELECT b.Cod_Documento FROM  cp_documentoacuserecibo as b)
			AND seau.CodAplicacion = 'CP'
			AND ((seau.Concepto = '01-0015' AND seau.FlagMostrar = 'S')
			OR (seau.Concepto = '01-0015' AND FlagAdministrador = 'S'))
			AND seau.Usuario = '".$usuarioCondicion."'";
			}									
			//echo $sql2;
					
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$totalConsulta = $cant1+$cant2;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." documento(s) preparado(s)","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." documento(s) por dar acuse","total"=>$totalConsulta);
			
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
		
		break;
		case 'alertaLogistica':
			
			$sqlDependencia = "SELECT md . * , usu.Usuario
								FROM mastdependencias AS md
								JOIN usuarios AS usu ON usu.CodPersona = md.CodPersona
								WHERE CodDependencia ='".$banderaListaAlerta."'";

			$resp = $objConexion->consultar($sqlDependencia,'fila');
			
			if($banderaListaAlerta == '') 
			{
					$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				
			} else {
				
				$usuarioCondicion = $resp['Usuario'];
			}
				
			//$usuarioCondicion = $_SESSION["CADENA_USUARIO"];
				
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{
				
				//REVISAR REQUERIMIENTO
				$sql1 = "SELECT CodRequerimiento 
					 FROM lg_requerimientos as a, 
     					  seguridad_autorizaciones as seau

					 where a.Estado ='PR'
					 AND seau.CodAplicacion='LG' 
					  AND ((seau.Concepto = '01-0004' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0004' AND FlagAdministrador = 'S'))
					 AND seau.Usuario='".$usuarioCondicion."'";
					
			}	/*else {
				
						$sql1 = "SELECT
						a.CodRequerimiento,
						a.CodDependencia,
						a.Estado
						FROM
						lg_requerimientos AS a
						WHERE
						a.Estado = 'PR'

						AND a.CodDependencia='".$banderaListaAlerta."'"
						;
				
				}*/	
				
			//echo $sql1;
			
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{
				//CONFORMAR REQUERIMIENTOS
				$sql2 = "SELECT CodRequerimiento 
					 FROM lg_requerimientos as a, 
     					  seguridad_autorizaciones as seau

					 where a.Estado ='RV'
					 AND seau.CodAplicacion='LG' 
					 AND ((seau.Concepto = '01-0018' AND seau.FlagMostrar = 'S')
					 OR (seau.Concepto = '01-0018' AND FlagAdministrador = 'S'))
					 AND seau.Usuario='".$usuarioCondicion."'";
			}

			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{		 
				//APROBAR REQUERIMIENTOS
			 	$sql3 = "SELECT CodRequerimiento 
					 FROM lg_requerimientos as a, 
     					  seguridad_autorizaciones as seau

					 where a.Estado ='CN'
					 AND seau.CodAplicacion='LG' 
					
					  AND ((seau.Concepto = '01-0005' AND seau.FlagMostrar = 'S')OR (seau.Concepto = '01-0005' AND FlagAdministrador = 'S'))
					 AND seau.Usuario='".$usuarioCondicion."'";
			}	 
			
			if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0002') || ($banderaListaAlerta == '0008'))
			{
				//REVISAR RECOMENDACION
				$sql4 = "SELECT CodInformeRecomendacion 
					 FROM lg_informerecomendacion as a, 
     					  seguridad_autorizaciones as seau

					 where a.Estado ='PR'
					 AND seau.CodAplicacion='LG' 
					
					  AND ((seau.Concepto = '01-0024' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0024' AND FlagAdministrador = 'S'))
					 AND seau.Usuario='".$usuarioCondicion."'";
			}
			
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{
				//APROBAR RECOMENDACION		 
				$sql5 = "SELECT CodInformeRecomendacion 
					 FROM lg_informerecomendacion as a, 
     					  seguridad_autorizaciones as seau

					 where a.Estado ='RV'
					 AND seau.CodAplicacion='LG' 
					
					  AND ((seau.Concepto = '01-0025' AND seau.FlagMostrar = 'S')
					OR (seau.Concepto = '01-0025' AND FlagAdministrador = 'S'))
					 AND seau.Usuario='".$usuarioCondicion."'";
			}
			
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{
				//ORDEN COMPRA POR GENERAR		 
				$sql6="SELECT c.*, cl.Descripcion AS NomClasificacion, fp.Descripcion AS NomFormaPago, SUM(Total) AS Total, p.Nacionalidad 
				 FROM seguridad_autorizaciones as seau, lg_cotizacion c  
				 INNER JOIN mastformapago fp ON (c.CodFormaPago = fp.CodFormaPago) 
				 INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND c.CodRequerimiento = rd.CodRequerimiento AND c.Secuencia = rd.Secuencia) INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND r.CodRequerimiento = rd.CodRequerimiento) INNER JOIN lg_clasificacion cl ON (r.Clasificacion = cl.Clasificacion) INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor) JOIN lg_informeadjudicacion as iad on iad.CodProveedor=p.CodProveedor JOIN lg_adjudicaciondetalle as ad on iad.CodAdjudicacion=ad.CodAdjudicacion and c.CodRequerimiento=ad.CodRequerimiento and c.Secuencia=ad.Secuencia 
				 WHERE  rd.Estado = 'PE' AND 
				 r.Clasificacion <> 'SER'  				
				 AND seau.CodAplicacion='LG' 
					
				 AND ((seau.Concepto = '01-0008' AND seau.FlagMostrar = 'S')
				 OR (seau.Concepto = '01-0008' AND FlagAdministrador = 'S'))
				 AND seau.Usuario='".$usuarioCondicion."'
				 GROUP BY NroCotizacionProv, Numero ";
			}
			
			if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
			{
				//ORDEN SERVICIO POR GENERAR	 
				$sql7="SELECT
				c.*,
				cl.Descripcion AS NomClasificacion,
				fp.Descripcion AS NomFormaPago,
				SUM(Total) AS Total,
				p.Nacionalidad
			FROM
				seguridad_autorizaciones as seau,
				lg_cotizacion c
				INNER JOIN mastformapago fp ON (c.CodFormaPago = fp.CodFormaPago)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
												   r.CodRequerimiento = rd.CodRequerimiento)
				INNER JOIN lg_clasificacion cl ON (r.Clasificacion = cl.Clasificacion)
				INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor)
				
				JOIN lg_informeadjudicacion as iad on iad.CodProveedor=p.CodProveedor
				JOIN lg_adjudicaciondetalle as ad on iad.CodAdjudicacion=ad.CodAdjudicacion and c.CodRequerimiento=ad.CodRequerimiento 
				and c.Secuencia=ad.Secuencia				
			WHERE 
				rd.Estado = 'PE' AND
				r.Clasificacion = 'SER'
				AND seau.CodAplicacion='LG'
				AND ((seau.Concepto = '01-0008' AND seau.FlagMostrar = 'S')
				OR (seau.Concepto = '01-0008' AND FlagAdministrador = 'S'))
				AND seau.Usuario='".$usuarioCondicion."'
			GROUP BY NroCotizacionProv, Numero";
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta != '') )	
		{
			//ORDEN COMPRA POR REVISAR	
			 $sql8 = "SELECT distinct oc.Anio, oc.CodOrganismo, oc.NroOrden, oc.NroInterno, oc.FechaPreparacion, oc.CodProveedor, oc.NomProveedor, oc.MontoTotal, oc.MontoPendiente, oc.Observaciones, oc.Estado, a1.Descripcion AS NomAlmacen, a2.Descripcion AS NomAlmacenIngreso, fp.Descripcion AS NomFormaPago
			FROM seguridad_autorizaciones as seau, lg_ordencompra oc
			INNER JOIN lg_almacenmast a1 ON ( oc.CodAlmacen = a1.Codalmacen )
			LEFT JOIN lg_almacenmast a2 ON ( oc.CodAlmacenIngreso = a2.Codalmacen )
			LEFT JOIN mastformapago fp ON ( oc.CodFormaPago = fp.CodFormaPago )
			WHERE 1
			AND (
			oc.NroOrden
			IN (
			
			SELECT A.NroOrden
			FROM lg_verificarimpuordencom AS A
			WHERE A.Anio = oc.Anio
			AND A.CodOrganismo = oc.CodOrganismo
			AND A.NroOrden = oc.NroOrden
			)
			AND oc.NroOrden
			IN (
			
			SELECT B.NroOrden
			FROM lg_verificarpresuordencom AS B
			WHERE B.Anio = oc.Anio
			AND B.CodOrganismo = oc.CodOrganismo
			AND B.NroOrden = oc.NroOrden
			)
			)
			AND (
			oc.CodOrganismo = '0001'
			)
			AND (
			oc.Estado = 'PR'
			)
			 AND seau.CodAplicacion='LG'
			AND ((seau.Concepto = '01-0012' AND seau.FlagMostrar = 'S') OR (seau.Concepto ='01-0012' AND FlagAdministrador = 'S'))
			AND seau.Usuario='".$usuarioCondicion."'
			
			ORDER BY NroInterno";
								
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0002') || ($banderaListaAlerta == '0008'))
		{						
			//ORDEN DE SERVICIO POR REVISAR							
			$sql9="SELECT 
					distinct os.Anio, 
					os.CodOrganismo, 
					os.NroOrden, 
					os.Descripcion, 
					os.DescAdicional, 
					os.MotRechazo, 
					os.Observaciones, 
					os.FechaPreparacion, 
					os.NomProveedor, 
					os.TotalMontoIva, 
					os.Estado, 
					os.NroInterno 
				FROM seguridad_autorizaciones as seau,  
				     lg_ordenservicio os 
				WHERE 1 and (os.NroOrden in (select A.NroOrden from lg_verificarimpuordenser as A where A.Anio = os.Anio and A.CodOrganismo=os.CodOrganismo and A.NroOrden=os.NroOrden) and 
				      os.NroOrden in (select B.NroOrden from lg_verificarpresuordenser as B where B.Anio = os.Anio and B.CodOrganismo=os.CodOrganismo and B.NroOrden=os.NroOrden)) AND 
				      (os.CodOrganismo = '0001') AND (os.Estado = 'PR') 
				      AND seau.CodAplicacion='LG' AND
				      ((seau.Concepto = '01-0015' AND seau.FlagMostrar = 'S')					  
				 	  OR (seau.Concepto ='01-0015' AND FlagAdministrador = 'S'))
				 	  AND seau.Usuario='".$usuarioCondicion."' ORDER BY NroInterno";
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0001') || ($banderaListaAlerta == '0008'))
		{		 
		 
			//ORDEN DE COMPRA POR APROBAR	  
			$sql10="SELECT 
					DISTINCT
					oc.Anio, 
					oc.CodOrganismo, 
					oc.NroOrden, 
					oc.NroInterno, 
					oc.FechaPreparacion, 
					oc.CodProveedor, 
					oc.NomProveedor, 
					oc.MontoTotal, 
					oc.MontoPendiente, 
					oc.Observaciones, 
					oc.Estado, 
					a1.Descripcion AS NomAlmacen, 
					a2.Descripcion AS NomAlmacenIngreso, 
					fp.Descripcion AS NomFormaPago 
			FROM seguridad_autorizaciones as seau,lg_ordencompra oc 
			INNER JOIN lg_almacenmast a1 ON (oc.CodAlmacen = a1.Codalmacen) 
			LEFT JOIN lg_almacenmast a2 ON (oc.CodAlmacenIngreso = a2.Codalmacen) 
			LEFT JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago) 
			WHERE 1
			and oc.Anio='".date('Y')."'  
			and (oc.NroOrden in (select A.NroOrden from lg_verificarimpuordencom as A where A.Anio = oc.Anio and A.CodOrganismo=oc.CodOrganismo and A.NroOrden=oc.NroOrden) and 
					oc.NroOrden in (select B.NroOrden from lg_verificarpresuordencom as B where B.Anio = oc.Anio and B.CodOrganismo=oc.CodOrganismo and B.NroOrden=oc.NroOrden)) AND 
					(oc.CodOrganismo = '0001') AND (oc.Estado = 'RV') 
					AND seau.CodAplicacion='LG' AND
				      ((seau.Concepto = '01-0011' AND seau.FlagMostrar = 'S')
				 	  OR (seau.Concepto ='01-0011' AND FlagAdministrador = 'S'))
				 	  AND seau.Usuario='".$usuarioCondicion."'";
	
	
	
	
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0001') || ($banderaListaAlerta == '0008'))
		{			  
			//ORDEN SERVICIO POR APROBAR			  
			$sql11="SELECT DISTINCT os.Anio, os.CodOrganismo, os.NroOrden, os.Descripcion, os.DescAdicional, os.MotRechazo, os.Observaciones, os.FechaPreparacion, os.NomProveedor, os.TotalMontoIva, os.Estado, os.NroInterno
				FROM seguridad_autorizaciones as seau, lg_ordenservicio os
				WHERE 1
						AND (
							os.NroOrden
						IN (

								SELECT A.NroOrden
								FROM lg_verificarimpuordenser AS A
								WHERE A.Anio = os.Anio
								AND A.CodOrganismo = os.CodOrganismo
								AND A.NroOrden = os.NroOrden
								)
								AND os.NroOrden
								IN (
								
								SELECT B.NroOrden
								FROM lg_verificarpresuordenser AS B
								WHERE B.Anio = os.Anio
								AND B.CodOrganismo = os.CodOrganismo
								AND B.NroOrden = os.NroOrden
				)
				)
				AND (os.CodOrganismo = '0001')
				AND (os.Estado = 'RV')
				AND os.Anio='".date('Y')."' 
				AND seau.CodAplicacion='LG' AND   ((seau.Concepto = '01-0016' AND seau.FlagMostrar = 'S')
				 	  OR (seau.Concepto ='01-0016' AND FlagAdministrador = 'S'))
				 	  AND seau.Usuario='".$usuarioCondicion."'
				ORDER BY NroInterno";
		
		
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0002') || ($banderaListaAlerta == '0008'))
		{
			//TRANSACCION POR EJECUTAR
			$sql12="SELECT DISTINCT t.CodOrganismo, t.CodDocumento, t.NroDocumento, t.NroInterno, t.FechaDocumento, t.CodTransaccion, t.CodCentrocosto, t.Periodo, t.Estado, t.CodDocumentoReferencia, t.NroDocumentoReferencia, t.DocumentoReferenciaInterno, a.Descripcion AS NomAlmacen, tt.Descripcion AS NomTransaccion, tt.TipoMovimiento
				FROM seguridad_autorizaciones AS seau, lg_transaccion t
				INNER JOIN lg_almacenmast a ON ( a.CodAlmacen = t.CodAlmacen
				AND a.FlagCommodity = 'N' )
				INNER JOIN lg_tipotransaccion tt ON ( t.CodTransaccion = tt.CodTransaccion )
				WHERE 1
				AND (
				t.CodOrganismo = '0001'
				)
				AND (
				t.Estado = 'PR'
				)
				AND (
				(
				seau.Concepto = '05-0006'
				AND seau.FlagMostrar = 'S'
				)
				OR (
				seau.Concepto = '05-0006'
				AND FlagAdministrador = 'S'
				)
				)
				AND seau.CodAplicacion='LG'
				AND seau.Usuario ='".$usuarioCondicion."'
				ORDER BY CodDocumento, NroDocumento";
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0002') || ($banderaListaAlerta == '0008'))
		{
			//CONTROL PERCEPTIVO PENDIENTE		
			$sql13 ="select distinct A.NroOrden, C.NomCompleto as NomProveedor, A.CodProveedor 
                 from seguridad_autorizaciones AS seau,lg_ordencompra as A  
                 join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo 
                 INNER JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona) 
                 INNER JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor) 
                 where A.Estado in ('AP','CE','CO') and A.NroOrden like '%%' and A.NroOrden not in (select NroOrden from lg_controlperceptivo) 
                 AND seau.CodAplicacion='LG'
                 AND (
				(
				seau.Concepto = '08-0001'
				AND seau.FlagMostrar = 'S'
				)
				OR (
				seau.Concepto = '08-0001'
				AND FlagAdministrador = 'S'
				)
				)
				
				AND seau.Usuario ='".$usuarioCondicion."'";
		}
		
		if(($banderaListaAlerta == '') || ($banderaListaAlerta == '0002') || ($banderaListaAlerta == '0008'))
		{		
			//REQUERIMIENTOS ANULADOS		
		 	$sql14="SELECT a.* FROM seguridad_autorizaciones AS seau ,`lg_requerimientos` as a
				where a.Estado='AN' AND 
				DATE(a.UltimaFecha)='".date('Y-m-d')."' 
				AND seau.CodAplicacion='LG'
                 AND (
				(
				seau.Concepto = '01-0005'
				AND seau.FlagMostrar = 'S'
				)
				OR (
				seau.Concepto = '01-0005'
				AND FlagAdministrador = 'S'
				)
				)
				
				AND seau.Usuario ='".$usuarioCondicion."'";
		}	
			$objConexion->consultar($sql1);
			$cant1 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql2);
			$cant2 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql3);
			$cant3 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql4);
			$cant4 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql5);
			$cant5 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql6);
			$cant6 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql7);
			$cant7 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql8);
			$cant8 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql9);
			$cant9 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql10);
			$cant10 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql11);
			$cant11 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql12);
			$cant12 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql13);
			$cant13 = $objConexion->getCantidadFilasConsulta();
			
			$objConexion->consultar($sql14);
			$cant14 = $objConexion->getCantidadFilasConsulta();
			
			
			$totalConsulta = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13+$cant14;
			
			$cantCadenaAlert = array(array());
			
			$cantCadenaAlert[0] = array("cant"=>$cant1,"cadena"=>"Tiene ".$cant1." requerimiento(s)  por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[1] = array("cant"=>$cant2,"cadena"=>"Tiene ".$cant2." requerimiento(s)  por conformar","total"=>$totalConsulta);
			$cantCadenaAlert[2] = array("cant"=>$cant3,"cadena"=>"Tiene ".$cant3." requerimiento(s)  por probar","total"=>$totalConsulta);
			$cantCadenaAlert[3] = array("cant"=>$cant4,"cadena"=>"Tiene ".$cant4." recomendación(es)  por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[4] = array("cant"=>$cant5,"cadena"=>"Tiene ".$cant5." recomendación(es)  por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[5] = array("cant"=>$cant6,"cadena"=>"Tiene ".$cant6." orden(es) de compra  por generar","total"=>$totalConsulta);
			$cantCadenaAlert[6] = array("cant"=>$cant7,"cadena"=>"Tiene ".$cant7." orden(es) de servicio  por generar","total"=>$totalConsulta);
			$cantCadenaAlert[7] = array("cant"=>$cant8,"cadena"=>"Tiene ".$cant8." orden(es) de compra  por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[8] = array("cant"=>$cant9,"cadena"=>"Tiene ".$cant9." orden(es) de servicio  por revisar","total"=>$totalConsulta);
			$cantCadenaAlert[9] = array("cant"=>$cant10,"cadena"=>"Tiene ".$cant10." orden(es) de compra  por aprobar","total"=>$totalConsulta);
			$cantCadenaAlert[10] = array("cant"=>$cant11,"cadena"=>"Tiene ".$cant11." orden(es) de servicio  por aprobar","total"=>$totalConsulta);			
			$cantCadenaAlert[11] = array("cant"=>$cant12,"cadena"=>"Tiene ".$cant12." transacción por ejecutar","total"=>$totalConsulta);
			$cantCadenaAlert[12] = array("cant"=>$cant13,"cadena"=>"Tiene ".$cant13." control perceptivo pendiente","total"=>$totalConsulta);
			$cantCadenaAlert[13] = array("cant"=>$cant14,"cadena"=>"Se han anulado ".$cant14." requerimiento(s)","total"=>$totalConsulta);
			$aux = array($cantCadenaAlert);
			
			echo $objConexion->devolverXML($cantCadenaAlert);
							
		break;
		case 'enviarCorreoAlerta':
		/*
			$sql1 ="select Email from mastpersonas where CodPersona='".$_SESSION["CODPERSONA_ACTUAL"]."'";
			$resultadoQuery1 = $objConexion->consultar($sql1,'fila');
			//echo $sql1;exit;
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "tls";
			$mail->Host = "re01.paneldehosting.com";
			$mail->Port = 26;
			$mail->Username = "ces.siaces@cgesucre.gob.ve";
			$mail->Password = "514c35c0rr30";
			
		//$mail->Username;//
			$mail->From = $resultadoQuery1['Email'];
			$mail->FromName = "SIACES";
			$mail->Subject = utf8_decode("Información");
			$mail->AltBody = "Favor revise su cuenta del sistema SIACES, tiene avisos pendientes";
			$mail->MsgHTML("<b>Por favor, revise su cuenta del SIACES, tiene avisos pendientes </b><a href=\"http://siaces.cgesucre.gob.ve/\">http://siaces.cgesucre.gob.ve/</a>.");
			
		//	$mail->AddAttachment("files/files.zip");
		//	$mail->AddAttachment("files/img03.jpg";
			
			$mail->AddAddress($resultadoQuery1['Email'], "Destinatario");
			$mail->IsHTML(true);
			
			if(!$mail->Send()) {
		
				echo $objConexion->devolverXML(array(array("resp"=>"Error: " . $mail->ErrorInfo)));
		
			} else {
				
				echo $objConexion->devolverXML(array(array("resp"=>"Mensaje enviado correctamente")));
			}
		*/
		break;
				case 'cargarListaAlerta':
			
			$sql = "SELECT * FROM mastdependencias where CodPersona='".$_SESSION["CODPERSONA_ACTUAL"]."'";
			
			//$sql = "SELECT * FROM mastempleado where CodPersona='".$_SESSION["CODPERSONA_ACTUAL"]."'";
	
			$resp = $objConexion->consultar($sql,'fila');
			
			$listaAlerta = '<label><br /><select id="listaAlerta" onchange="mostrarAlerta();">
					  <option value="-1">Seleccione...</option>';
			
		 	//if($resp['CodPersona'] == '000104')//PERSONA PRUEBA MARCANO
			if($resp['CodDependencia'] == '0001' || $_SESSION["CODPERSONA_ACTUAL"] == '000104')//DESPACHO
			{
					$sql_lista="SELECT
					mdp.CodDependencia,
					mdp.CodPersona,
					mdp.CodInterno,
					mdp.Dependencia,
					mastpersonas.Nombres as responsable,
					rh_puestos.DescripCargo as cargo
					FROM
					mastdependencias AS mdp
					INNER JOIN mastempleado ON mastempleado.CodPersona = mdp.CodPersona
					INNER JOIN mastpersonas ON mastpersonas.CodPersona = mastempleado.CodPersona
					INNER JOIN rh_puestos ON rh_puestos.CodCargo = mastempleado.CodCargo
					WHERE
					mdp.Estado = 'A'";
				$resp_lista =  $objConexion->consultar($sql_lista,'matriz');
                 for ( $i=0; $i< count ($resp_lista) ; $i++)
					{

					$listaAlerta = $listaAlerta . "<option value='".$resp_lista[$i]['CodDependencia']."' >".$resp_lista[$i]['Dependencia']. " - ".$resp_lista[$i]['cargo']. " - ".$resp_lista[$i]['responsable'] ."</option>";
					}
				
				$listaAlerta =  $listaAlerta  .'</select></label>';
				//$rows_lista=mysql_num_rows($query_lista);
                 
			// print_r ($resp_lista);
			 
			/*	$listaAlerta = '<label><br /><select id="listaAlerta" onchange="mostrarAlerta();">
					  <option value="-1">Seleccione...</option>
		              <option value="0001">Contralor</option>
		              <option value="0008">Director General</option>
		              <option value="0002">Director Administraci&oacute;n</option>
		            </select></label>';*/
			} else 	if($resp['CodPersona'] == '000104')//PERSONA PRUEBA MARCANO
			//else 	if($resp['CodDependencia'] == '0008')// DIRECCION GENERAL
			{
				$listaAlerta = '<label><select id="listaAlerta" onchange="mostrarAlerta();">
					  <option value="-1">Seleccione...</option>
					  <option value="0008">Director General</option>
		              <option value="0002">Director Administraci&oacute;n</option>
		            </select></label>';
					
			} else	//if($resp['CodDependencia'] == '0003')// ADMINISTRACION
			{
				$listaAlerta = '';
			
			}
			
			echo $listaAlerta;
		default://para pruebas
	
	}

	/*function rellenarConCero($cadena, $cantidadRelleno)
	{
		$cantidadCadena = strlen($cadena);

		for($i = 0; $i < ($cantidadRelleno-$cantidadCadena); $i++)
		{
			$cadena = '0'.$cadena;

		}			

		return $cadena;
	}
*/
?>
