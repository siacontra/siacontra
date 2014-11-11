<?php
	
	session_start();
	set_time_limit(-1);
	ini_set('memory_limit','128M');
	include ("../funciones.php");

    include_once ("../../clases/MySQL.php");	
    include_once("../../comunes/objConexion.php");
    include_once("../include/funciones_php.php");
     	
    $c=0;
	$sumCert=0;
	$sumCert2=0;
	$sumCert22=1;
	$ultCert=1222;
	$certificados_automaticos;
	$certificados_automaticos2;

     
	foreach($_POST as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}


	/*foreach($_POST as $nombre_campo => $valor){
	$asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
	eval($asignacion);
	}*/


	switch($caso)
	{
            
		case 'LISTA_PERSONA__BuscarListado':				
			$sql = "select * from mastpersonas where (Ndocumento like '%$CadenaBuscarpersona%' or NomCompleto like '%$CadenaBuscarpersona%') and TipoPersona='N' and CodPersona<>'".$ponente."' and CodPersona<>'".$ponente22."'"; 				
			$resp = $objConexion->consultar($sql,'matriz');
		break;

		case 'CargarSelectEventos':				
			$sql = "select co_id_evento, tx_nombre_evento from ev_evento_capacitacion;"; 				
			$resp = $objConexion->consultar($sql,'matriz');
		break;



		case 'LISTA_PARTICIPANTES__BuscarListado':					
			$sql = "select * from mastpersonas where (Ndocumento like '%$CadenaBuscarparticipantes%' or NomCompleto like '%$CadenaBuscarparticipantes%') and TipoPersona='N' and CodPersona<>'".$ponente."' and CodPersona<>'".$ponente2."'";				
			$resp = $objConexion->consultar($sql,'matriz');
			
		break;
		
		
		case 'LISTA_PARTICIPANTES_DEPENDENCIA__BuscarListado':				
			$sql = "select A.CodPersona,A.Ndocumento,A.NomCompleto,B.CodEmpleado,C.CodDependencia,C.Dependencia from mastpersonas as A, mastempleado as B, mastdependencias as C where A.CodPersona=B.CodPersona and B.CodDependencia=C.CodDependencia and A.TipoPersona='N' and B.Estado='A' and C.Dependencia like '%$CadenaBuscarparticipantes%' and A.CodPersona<>'".$ponente."'"; 				
			$resp = $objConexion->consultar($sql,'matriz');
		break;
		
		
		case 'BuscarSegundoPonente':	
		$sql = "select A.CodPersona, A.tx_nu_certificado, B.NomCompleto, B.Ndocumento from ev_persona_evento as A, mastpersonas as B where co_id_evento=".$CodEvento." and bo_ponente=0 and bo_ponente_1=1 and A.CodPersona=B.CodPersona;";    	
		$resp = $objConexion->consultar($sql,'matriz');	
		break;


		case 'cargarEventos':							
			$sql="select A.co_id_evento,A.hh_fecha1,A.hh_fecha2,A.co_lugar,A.co_id,A.tx_nombre_evento,
			A.tx_descripcion_evento,A.hh_hora1,A.hh_hora2,A.bo_certificado, A.bo_certificado_ponente, A.eliminado,A.nu_horas, B.CodPersona,B.bo_culmino_evento,
			B.bo_recibio_certificado,B.bo_ponente,B.tx_nu_certificado, C.CodPersona, C.NomCompleto,C.Ndocumento,
			D.nombre_lugar 
									
			from ev_evento_capacitacion as A, ev_persona_evento as B, mastpersonas as C, 
			ev_lugares_evento as D 
			
						
			where D.co_lugares=A.co_lugar and A.co_id_evento=B.co_id_evento and B.CodPersona=C.CodPersona and 
			A.tx_nombre_evento LIKE '%$CadenaBuscar%' and A.eliminado=false and bo_ponente=1 /*and bo_recibio_certificado=1*/ order by A.co_id_evento;";
			$resp = $objConexion->consultar($sql,'matriz');	
		break;



		case 'cargarParticipantesEvento':
			$sql= "select A.co_id_persona_evento, A.CodPersona, A.bo_culmino_evento, A.bo_recibio_certificado, A.bo_ponente, A.tx_nu_certificado, A.co_id_evento, A.eliminado, B.NomCompleto, B.Ndocumento from ev_persona_evento as A, mastpersonas as B  where A.CodPersona=B.CodPersona and A.bo_ponente=0 and bo_ponente_1=0 and A.eliminado=false and A.co_id_evento= ".$CodEvento." order by tx_nu_certificado";
			$resp = $objConexion->consultar($sql,'matriz');	
		break;


		case 'TiposEventosBuscarListado':
		$sql="SELECT * FROM ev_tipo_capacitacion WHERE (eliminado=false AND (tx_nombre_cap LIKE '%".$CadenaBuscar."%')) OR co_id='CadenaBuscarTE' ORDER BY co_id";
			$resp = $objConexion->consultar($sql,'matriz');
		break;
		
		
		case 'cargarTemasporEvento':
		$sql="select A.co_id_evento, E.co_tema, E.tx_tema  from ev_evento_capacitacion as A, ev_temas as E, ev_evento_temas as F where F.co_id_evento=A.co_id_evento and F.co_tema=E.co_tema and A.co_id_evento=".$eventoSeleccionado.";";
		$resp = $objConexion->consultar($sql,'matriz');
		break;
						
		case 'LUGARES__BuscarListadoModificar':			
		$sql="SELECT * FROM ev_lugares_evento WHERE (eliminado=false AND (nombre_lugar LIKE '%".$CadenaBuscar."%' )) OR co_lugares='".$Modificar."' ORDER BY co_lugares";
			$resp = $objConexion->consultar($sql,'matriz');
		break;
		
		

		case 'EventosExiste':	
		$sql = "select count(*) as num from ev_evento_capacitacion where eliminado=false and tx_nombre_evento like '".$NombreEvento."' and co_lugar =".$LugarEvento." and hh_fecha1='".$FechaInicio."' and hh_hora1='".$hora_inicio.":".$minuto_inicio."-".$turno_inicio."'";    	
			$resp = $objConexion->consultar($sql,'matriz');	
		break;
		
		
		case 'LUGARES__BuscarListado':	
		$sql = "select * from ev_lugares_evento where nombre_lugar LIKE '%".$CadenaBuscar."%' and eliminado=false order by co_lugares;";    	
		$resp = $objConexion->consultar($sql,'matriz');	
		break;
		
		case 'TEMAS__BuscarListado':	
		$sql = "select * from ev_temas where tx_tema LIKE '%".$CadenaBuscar."%' and eliminado=false order by co_tema;";    	
		$resp = $objConexion->consultar($sql,'matriz');	
		break;
		
		
		case 'LUGAR__Existe':	
		$sql = "SELECT count(*) as num2 FROM ev_lugares_evento WHERE eliminado=false AND nombre_lugar LIKE '".$nombre_lugar."'";    	
			$resp = $objConexion->consultar($sql,'matriz');	
		break;
		
		case 'TEMA__Existe':	
		$sql = "SELECT count(*) as num3 FROM ev_temas WHERE eliminado=false AND tx_tema LIKE '".$nombre_tema."'";    	
		$resp = $objConexion->consultar($sql,'matriz');	
		break;
		
		
		case 'lugar__Eliminar':	
		$sql = "select count(*) as cantidad from ev_evento_capacitacion where eliminado=false and co_lugar='".$co_lugar."'";
		
		$ultvalor = $objConexion->consultar($sql,'fila');
		$cantidad = $ultvalor['cantidad'];
				
			if ($cantidad>=1)
			{
		
			$resp=0;
			}
			elseif ($cantidad==0)
			{	if ($objConexion->modificar(array(				
				"eliminado=true","co_lugares=".$co_lugar),'ev_lugares_evento') == true)	
				$resp=1;
				
			}
		break;
		
		
		
		
		
		case 'TEMA__Eliminar':	
			
				if ($objConexion->modificar(array("eliminado=true","co_tema=".$co_tema),'ev_temas') == true)	
				$resp=1;
				
				else 
				{
					$resp=0;
				}				
				
		break;
		
		
		
		case 'LUGARES__Guardar':
			if($objConexion->ingresar(array("nombre_lugar","'".$nombre_lugar."'"),'ev_lugares_evento')== true)
			{
			$resp=1;
			}
				
			else
			{
			$resp=0;
			}
		break;	
		
		
		case 'TEMAS__Guardar':
			if($objConexion->ingresar(array("tx_tema","'".$nombre_tema."'"),'ev_temas')== true)
			{
			$resp=1;
			}
				
			else
			{
			$resp=0;
			}
		break;	
		
			
		case 'LUGARES__Modificar':
				if ($objConexion->modificar(array(				
				"nombre_lugar='".$nombre_lugar."'","co_lugares=".$co_lugar),'ev_lugares_evento') == true)
				{
				$resp=1;
				}
				
				else
				{
				$resp=0;
				}
			
		break;
		
		
			case 'TEMAS__Modificar':
				if ($objConexion->modificar(array(				
				"tx_tema='".$nombre_tema."'","co_tema=".$co_tema),'ev_temas') == true)
				{
				$resp=1;
				}
				
				else
				{
				$resp=0;
				}
			
		break;
		

		case 'EventosGuardar':

		if($objConexion->ingresar(array("hh_fecha1,hh_fecha2,co_lugar,co_id,tx_nombre_evento,tx_descripcion_evento,hh_hora1,hh_hora2,bo_certificado,bo_certificado_ponente,nu_horas","'".$fecha_inicio."','".$fecha_culminacion."',".$lugar_evento.",'".$tipo_evento."','".$nombre_evento."','".$descripcion_evento."','".$hora_inicio.":".$minuto_inicio."-".$turno_inicio."','".$hora_culmi.":".$minuto_culmi."-".$turno_culmi."','".$certificado."','".$CertificadoPonente."',".$horas_evento.""),'ev_evento_capacitacion')== true)
		{


			$sql="select MAX(co_id_evento) as maximoevento from ev_evento_capacitacion";
	 		$ultvalor = $objConexion->consultar($sql,'fila');
			$maxevento = $ultvalor['maximoevento'];
			
			
			$sql="select MAX(tx_nu_certificado) as ultimocertificado from ev_persona_evento";
			$ultvalorw = $objConexion->consultar($sql,'fila');
			$maxcertificado = $ultvalorw['ultimocertificado'];
			
			if ($maxcertificado==0 || $maxcertificado==null || $maxcertificado=="")
			$maxcertificado=$ultCert;
			
			if($CertificadoPonente=="1")
			{
			$sumCert=$sumCert+1;
			$certificados_automaticos=$maxcertificado+$sumCert;			
		
			$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente."',1,1,1,0,".$certificados_automaticos.",".$maxevento.""),'ev_persona_evento');
			
				if($cod_ponente2==null || $cod_ponente2=="")
				{
					
				}
				
				if($cod_ponente2!=null || $cod_ponente2!="")
				{
					$sumCert=$sumCert+1;
					$certificados_automaticos=$maxcertificado+$sumCert;
					
					$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente2."',1,1,0,1,".$certificados_automaticos.",".$maxevento.""),'ev_persona_evento');	
				}
				
			
			}
			
			if($CertificadoPonente=="0")
			{
				$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente."',1,1,1,0,'0',".$maxevento.""),'ev_persona_evento');
			
					if(cod_ponente2!='')
					{
						$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente2."',1,1,0,1,'0',".$maxevento.""),'ev_persona_evento');
					}				
			}



			if($certificado=="1")
			{
											

			$ARREGLO_PARTICIPANTES=explode(",",$Arreglo);
			
					for($i=0;$i<$tam_arreglo*6;$i=$i+6)
					{
		
					$sumCert=$sumCert+1;
					$certificados_automaticos=$maxcertificado+$sumCert;
					
					
					$cod_participante=$ARREGLO_PARTICIPANTES[$i];
					$cedula_participante=$ARREGLO_PARTICIPANTES[$i+1];
					$nombre_participante=$ARREGLO_PARTICIPANTES[$i+2];
		
					$culmino_participante=$ARREGLO_PARTICIPANTES[$i+3];
						if($culmino_participante=='S' || $culmino_participante=='s'){
						$culmino="1";}
						else{
						$culmino="0";}
		
					$certificado_participante=$ARREGLO_PARTICIPANTES[$i+4];
						if($certificado_participante=='S' || $culmino_participante=='s'){
						$certificado="1";}
						else{
						$certificado="0";}
		
					$n_certificado_participante=$ARREGLO_PARTICIPANTES[$i+5];
					
					$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,tx_nu_certificado,co_id_evento","'".$cod_participante."','".$culmino."','".$certificado."',0,".$certificados_automaticos.",".$maxevento.""),'ev_persona_evento');
							
					}
			
			}

			if($certificado=="0")
			{
								
				$ARREGLO_PARTICIPANTES=explode(",",$Arreglo);
			
					for($i=0;$i<$tam_arreglo*6;$i=$i+6)
					{
		
								
					$cod_participante=$ARREGLO_PARTICIPANTES[$i];
					$cedula_participante=$ARREGLO_PARTICIPANTES[$i+1];
					$nombre_participante=$ARREGLO_PARTICIPANTES[$i+2];
		
					$culmino_participante=$ARREGLO_PARTICIPANTES[$i+3];
						if($culmino_participante=='S' || $culmino_participante=='s'){
						$culmino="1";}
						else{
						$culmino="0";}
		
					$certificado_participante=$ARREGLO_PARTICIPANTES[$i+4];
						if($certificado_participante=='S' || $culmino_participante=='s'){
						$certificado="1";}
						else{
						$certificado="0";}
		
					$n_certificado_participante=$ARREGLO_PARTICIPANTES[$i+5];
					
					$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,tx_nu_certificado,co_id_evento","'".$cod_participante."','".$culmino."','".$certificado."',0,0,".$maxevento.""),'ev_persona_evento');
							
			}
			
			}	
							
			$ARREGLO_TEMAS=explode(",",$Lista);
			$tam_EP=count($ARREGLO_TEMAS);
						
					for($u=0;$u<$tam_EP;$u++)
					{
					
					$co_tema=$ARREGLO_TEMAS[$u];
					$objConexion->ingresar(array("co_tema,co_id_evento","".$co_tema.",".$maxevento.""),'ev_evento_temas');	
					}
			
					$resp=1;							
			}
				
			else
			{
					$resp=0;
			}


		break;



		case 'EventosModificar':

		if ($objConexion->modificar(array(
				"hh_fecha1='".$fecha_inicio."',
				hh_fecha2='".$fecha_culminacion."',
				co_lugar=".$lugar_evento.",
				co_id='".$tipo_evento."',
				tx_nombre_evento='".$nombre_evento."',
				tx_descripcion_evento='".$descripcion_evento."',
				hh_hora1='".$hora_inicio.":".$minuto_inicio."-".$turno_inicio."',
				hh_hora2='".$hora_culmi.":".$minuto_culmi."-".$turno_culmi."',
				nu_horas=".$horas_evento.",
				bo_certificado_ponente='".$CertificadoPonente."',
				bo_certificado='".$certificado."'","co_id_evento=".$co_id_evento),'ev_evento_capacitacion') == true)
				{
					
					
				$sql="select MAX(tx_nu_certificado) as ultimocertificado from ev_persona_evento";
				$ultvalorw = $objConexion->consultar($sql,'fila');
				$maxcertificado = $ultvalorw['ultimocertificado'];
				
				if ($maxcertificado==0 || $maxcertificado==null || $maxcertificado=="")
				$maxcertificado=$ultCert;
										

			if($objConexion->eliminar("co_id_evento=".$co_id_evento,"ev_persona_evento") == true)
			{
						
					if($CertificadoPonente=="1")
					{
						$n_certificado_ponente=$Certificado_Ponente;
						
							if($n_certificado_ponente!=0)	
							{
								$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente."',1,1,1,0,".$n_certificado_ponente.",".$co_id_evento.""),'ev_persona_evento');
							
							}
							
							
							if($n_certificado_ponente==0)	
							{
																	
							$maxcertificado=$maxcertificado+1;
							$n_certificado_ponente=$maxcertificado;	
							
							$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente."',1,1,1,0,".$n_certificado_ponente.",".$co_id_evento.""),'ev_persona_evento');								
							}
													
													
						$n_certificado_ponente2=$Certificado_Ponente2;
						
						
						if($cod_ponente2!=null || $cod_ponente2!="")
						{
							
							if($n_certificado_ponente2!=0)	
							{
								$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente2."',1,1,0,1,".$n_certificado_ponente2.",".$co_id_evento.""),'ev_persona_evento');
							
							}
							
							if($n_certificado_ponente2==0 || $n_certificado_ponente2=="" || $n_certificado_ponente2==null)	
							{
																	
							$maxcertificado=$maxcertificado+1;
							$n_certificado_ponente2=$maxcertificado;	
							
							$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente2."',1,1,0,1,".$n_certificado_ponente2.",".$co_id_evento.""),'ev_persona_evento');								
							}					
							
						}
										
						
					}	


					if($CertificadoPonente=="0")
					{					
					$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente."',1,1,1,0,'0',".$co_id_evento.""),'ev_persona_evento');
					
						if($cod_ponente2!=null || $cod_ponente2!="")
						{					
							$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,bo_ponente_1,tx_nu_certificado,co_id_evento","'".$cod_ponente2."',1,1,0,1,'0',".$co_id_evento.""),'ev_persona_evento');
						}
					}


															
						
			if($certificado=="1")
			{	
						
					$ARREGLO_PARTICIPANTES=explode(",",$Arreglo);		

			
								for($i=0;$i<($tam_arreglo*6);$i=$i+6)
								{								
								
								/*$sql="select MAX(tx_nu_certificado) as ultimocertificado from ev_persona_evento";
								$ultvalorw = $objConexion->consultar($sql,'fila');
								$maxcertificado = $ultvalorw['ultimocertificado'];*/
									
								$n_certificado_participante=$ARREGLO_PARTICIPANTES[$i+5];
										
									if($n_certificado_participante=='' || $n_certificado_participante==0)	
									{									
									$maxcertificado=$maxcertificado+1;
									$n_certificado_participante=$maxcertificado;									
									}		

								$cod_participante=$ARREGLO_PARTICIPANTES[$i];
								$cedula_participante=$ARREGLO_PARTICIPANTES[$i+1];
								$nombre_participante=$ARREGLO_PARTICIPANTES[$i+2];
			
								$culmino_participante=$ARREGLO_PARTICIPANTES[$i+3];
									if($culmino_participante=='S' || $culmino_participante=='s'){
									$culmino="1";}
									else{
									$culmino="0";}
			
								$certificado_participante=$ARREGLO_PARTICIPANTES[$i+4];
									if($certificado_participante=='S' || $culmino_participante=='s'){
									$certificado="1";}
									else{
									$certificado="0";}									
			
								$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,tx_nu_certificado,co_id_evento","'".$cod_participante."','".$culmino."','".$certificado."',0,".$n_certificado_participante.",".$co_id_evento.""),'ev_persona_evento');
							
								}
					}

					if($certificado=="0")
					{
						$ARREGLO_PARTICIPANTES=explode(",",$Arreglo);		

			
								for($i=0;$i<($tam_arreglo*6);$i=$i+6)
								{								
								$n_certificado_participante=$ARREGLO_PARTICIPANTES[$i+5];
										
									if($n_certificado_participante=='' || $n_certificado_participante>0)	
									{										
									$n_certificado_participante=0;									
									}		

								$cod_participante=$ARREGLO_PARTICIPANTES[$i];
								$cedula_participante=$ARREGLO_PARTICIPANTES[$i+1];
								$nombre_participante=$ARREGLO_PARTICIPANTES[$i+2];
			
								$culmino_participante=$ARREGLO_PARTICIPANTES[$i+3];
									if($culmino_participante=='S' || $culmino_participante=='s'){
									$culmino="1";}
									else{
									$culmino="0";}
			
								$certificado_participante=$ARREGLO_PARTICIPANTES[$i+4];
									if($certificado_participante=='S' || $culmino_participante=='s'){
									$certificado="1";}
									else{
									$certificado="0";}									
			
								$objConexion->ingresar(array("CodPersona,bo_culmino_evento,bo_recibio_certificado, bo_ponente,tx_nu_certificado,co_id_evento","'".$cod_participante."','".$culmino."','".$certificado."',0,".$n_certificado_participante.",".$co_id_evento.""),'ev_persona_evento');
							
								}				
						
					}
					}

					if($objConexion->eliminar("co_id_evento=".$co_id_evento,"ev_evento_temas") == true)
					{
					
					 	$ARREGLO_TEMAS2=explode(",",$Lista);
						$tam_EP2=count($ARREGLO_TEMAS2);
						
							for($u=0;$u<$tam_EP2;$u++)
							{			
								$co_tema=$ARREGLO_TEMAS2[$u];
								$objConexion->ingresar(array("co_tema, co_id_evento","".$co_tema.",".$co_id_evento.""),'ev_evento_temas');	
							}
					 
					$resp=1;
					
					}			
					

					//$resp=$CertificadoPonente;
				} 

				else
				{
					$resp=0;
				}
				
				
			break;

		case 'EventosEliminar':

		if ($objConexion->modificar(array(
				"eliminado=true","co_id_evento=".$co_id_evento),'ev_evento_capacitacion') == true)
				{

					$objConexion->modificar(array(
					"eliminado=true","co_id_evento=".$co_id_evento),'ev_persona_evento');
			
					$resp=1;
				} 
				
				else
				{
					$resp=0;
				}

		break;

            default:
		$resp = "Error acción no encontrada";
	}

$datos=json_encode($resp);
echo $datos;
?>
        
