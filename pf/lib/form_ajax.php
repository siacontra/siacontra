<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	maestro de organismos externos
if ($modulo == "organismos_externos") {
	//	nuevo registro
	if ($accion== "nuevo") {		
		//	genero correlativo
		$CodOrganismo = getCodigo("pf_organismosexternos", "CodOrganismo", 4);
		
		//	inserto parametro
		$sql = "INSERT INTO pf_organismosexternos (
							CodOrganismo,
							Organismo,
							DescripComp,
							RepresentLegal,
							DocRepreLeg,
							PaginaWeb,
							DocFiscal,
							FechaFundac,
							Direccion,
							CodCiudad,
							Telefono1,
							Telefono2,
							Telefono3,
							Fax1,
							Fax2,
							Logo,
							Estado,
							NumReg,
							TomoReg,
							Cargo,
							FlagSujetoControl,
							CodDependencia,
							Mision,
							Vision,
							Gaceta,
							Resolucion,
							Otros,
							FlagSocial,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$Organismo."',
							'".$DescripComp."',
							'".$RepresentLegal."',
							'".$DocRepreLeg."',
							'".$PaginaWeb."',
							'".$DocFiscal."',
							'".formatFechaAMD($FechaFundac)."',
							'".$Direccion."',
							'".$CodCiudad."',
							'".$Telefono1."',
							'".$Telefono2."',
							'".$Telefono3."',
							'".$Fax1."',
							'".$Fax2."',
							'".$Logo."',
							'".$Estado."',
							'".$NumReg."',
							'".$TomoReg."',
							'".$Cargo."',
							'".$FlagSujetoControl."',
							'".$CodDependencia."',
							'".$Mision."',
							'".$Vision."',
							'".$Gaceta."',
							'".$Resolucion."',
							'".$Otros."',
							'".$FlagSocial."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_organismosexternos
				SET
					Organismo = '".$Organismo."',
					DescripComp = '".$DescripComp."',
					RepresentLegal = '".$RepresentLegal."',
					DocRepreLeg = '".$DocRepreLeg."',
					PaginaWeb = '".$PaginaWeb."',
					DocFiscal = '".$DocFiscal."',
					FechaFundac = '".formatFechaAMD($FechaFundac)."',
					Direccion = '".$Direccion."',
					CodCiudad = '".$CodCiudad."',
					Telefono1 = '".$Telefono1."',
					Telefono2 = '".$Telefono2."',
					Telefono3 = '".$Telefono3."',
					Fax1 = '".$Fax1."',
					Fax2 = '".$Fax2."',
					Logo = '".$Logo."',
					Estado = '".$Estado."',
					NumReg = '".$NumReg."',
					TomoReg = '".$TomoReg."',
					Cargo = '".$Cargo."',
					FlagSujetoControl = '".$FlagSujetoControl."',
					CodDependencia = '".$CodDependencia."',
					Mision = '".$Mision."',
					Vision = '".$Vision."',
					Gaceta = '".$Gaceta."',
					Resolucion = '".$Resolucion."',
					Otros = '".$Otros."',
					FlagSocial = '".$FlagSocial."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodOrganismo = '".$CodOrganismo."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_organismosexternos WHERE CodOrganismo = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	maestro de dependencias externas
elseif ($modulo == "dependencias_externas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$CodDependencia = getCodigo("pf_dependenciasexternas", "CodDependencia", 4);
		
		//	inserto
		$sql = "INSERT INTO pf_dependenciasexternas (
							CodDependencia,
							CodOrganismo,
							Dependencia,
							Representante,
							Cargo,
							Direccion,
							Telefono1,
							Telefono2,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodDependencia."',
							'".$CodOrganismo."',
							'".$Dependencia."',
							'".$Representante."',
							'".$Cargo."',
							'".$Direccion."',
							'".$Telefono1."',
							'".$Telefono2."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_dependenciasexternas
				SET
					CodOrganismo = '".$CodOrganismo."',
					Dependencia = '".$Dependencia."',
					Representante = '".$Representante."',
					Cargo = '".$Cargo."',
					Direccion = '".$Direccion."',
					Telefono1 = '".$Telefono1."',
					Telefono2 = '".$Telefono2."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDependencia = '".$CodDependencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_dependenciasexternas WHERE CodDependencia = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	maestro de procesos
elseif ($modulo == "procesos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$CodProceso = getCodigo("pf_procesos", "CodProceso", 2);
		
		//	inserto
		$sql = "INSERT INTO pf_procesos (
							CodProceso,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProceso."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_procesos
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_procesos WHERE CodProceso = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	maestro de fases
elseif ($modulo == "fases") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$Codigo = getCodigo_2("pf_fases", "Codigo", "CodProceso", $CodProceso, 2);
		$CodFase = $CodProceso.$Codigo;
		
		//	inserto
		$sql = "INSERT INTO pf_fases (
							CodProceso,
							Codigo,
							CodFase,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProceso."',
							'".$Codigo."',
							'".$CodFase."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_fases
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodFase = '".$CodFase."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_fases WHERE CodFase = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	maestro de actividades
elseif ($modulo == "actividades") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$Codigo = getCodigo_2("pf_actividades", "Codigo", "CodFase", $CodFase, 2);
		$CodActividad = $CodFase.$Codigo;
		
		//	inserto
		$sql = "INSERT INTO pf_actividades (
							CodFase,
							Codigo,
							CodActividad,
							Descripcion,
							Comentarios,
							Duracion,
							FlagAutoArchivo,
							FlagNoAfectoPlan,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodFase."',
							'".$Codigo."',
							'".$CodActividad."',
							'".$Descripcion."',
							'".$Comentarios."',
							'".$Duracion."',
							'".$FlagAutoArchivo."',
							'".$FlagNoAfectoPlan."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_actividades
				SET
					Descripcion = '".$Descripcion."',
					Comentarios = '".$Comentarios."',
					Duracion = '".$Duracion."',
					FlagAutoArchivo = '".$FlagAutoArchivo."',
					FlagNoAfectoPlan = '".$FlagNoAfectoPlan."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActividad = '".$CodActividad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_actividades WHERE CodActividad = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	maestro de tipo de actuacion
elseif ($modulo == "tipo_actuacion") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$CodTipoActuacion = getCodigo("pf_tipoactuacionfiscal", "CodTipoActuacion", 2);
		
		//	inserto
		$sql = "INSERT INTO pf_tipoactuacionfiscal (
							CodTipoActuacion,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodTipoActuacion."',
							'".$Descripcion."',
							'".$Estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico parametro
		$sql = "UPDATE pf_tipoactuacionfiscal
				SET
					Descripcion = '".$Descripcion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoActuacion = '".$CodTipoActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		//	modifico parametro
		$sql = "DELETE FROM pf_tipoactuacionfiscal WHERE CodTipoActuacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	actuacion fiscal
elseif ($modulo == "actuacion_fiscal") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero correlativo
		$Anio = substr($FechaInicio, 6, 4);
		$correlativo = getCodigo_3("pf_actuacionfiscal", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 4);
		$Secuencia = (int) $correlativo;
		$CodInterno = getCodInternoDependencia($CodDependencia);
		$CodActuacion = "$CodInterno-$correlativo-$Anio";
		
		//	inserto
		$sql = "INSERT INTO pf_actuacionfiscal (
							CodActuacion,
							Anio,
							Secuencia,
							CodOrganismo,
							CodDependencia,
							CodOrganismoExterno,
							CodDependenciaExterna,
							CodTipoActuacion,
							CodCentroCosto,
							CodProceso,
							ObjetivoGeneral,
							Alcance,
							Observacion,
							FechaRegistro,
							FechaInicio,
							FechaTermino,
							FechaTerminoReal,
							Duracion,
							PreparadoPor,
							FechaPreparacion,
							Origen,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodActuacion."',
							'".$Anio."',
							'".$Secuencia."',
							'".$CodOrganismo."',
							'".$CodDependencia."',
							'".$CodOrganismoExterno."',
							'".$CodDependenciaExterna."',
							'".$CodTipoActuacion."',
							'".$CodCentroCosto."',
							'".$CodProceso."',
							'".$ObjetivoGeneral."',
							'".$Alcance."',
							'".$Observacion."',
							NOW(),
							'".formatFechaAMD($FechaInicio)."',
							'".formatFechaAMD($FechaTermino)."',
							'".formatFechaAMD($FechaTerminoReal)."',
							'".$Duracion."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$Origen."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_actuacionfiscalauditores (
								CodActuacion,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodActuacion."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_actuacionfiscaldetalle (
								CodActuacion,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodActuacion."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		echo "|.|Actuación Nro. <strong>$CodActuacion</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_actuacionfiscal
				SET
					CodTipoActuacion = '".$CodTipoActuacion."',
					CodCentroCosto = '".$CodCentroCosto."',
					ObjetivoGeneral = '".$ObjetivoGeneral."',
					Alcance = '".$Alcance."',
					Observacion = '".$Observacion."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaTermino = '".formatFechaAMD($FechaTermino)."',
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					Duracion = '".$Duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$sql = "DELETE FROM pf_actuacionfiscalauditores WHERE CodActuacion = '".$CodActuacion."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_actuacionfiscalauditores (
								CodActuacion,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodActuacion."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_actuacionfiscaldetalle WHERE CodActuacion = '".$CodActuacion."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_actuacionfiscaldetalle (
								CodActuacion,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodActuacion."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	cambio el estado a ejecucion a la primera acividad afecta
		$sql = "SELECT
					afd.CodActuacion,
					afd.Secuencia,
					afd.CodActividad
				FROM
					pf_actuacionfiscaldetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodActuacion = '".$CodActuacion."' AND
					a.FlagNoAfectoPlan = 'N'
				ORDER BY Secuencia
				LIMIT 0, 1";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field = mysql_fetch_array($query)) {
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET Estado = 'EJ'
					WHERE
						CodActuacion = '".$CodActuacion."' AND
						Secuencia <= '".$field['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	cerrar registro
	elseif ($accion== "cerrar") {
		//	modifico
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodActuacion = '".$CodActuacion."' AND
					(Estado = 'PE' OR Estado = 'EJ')";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	actuacion fiscal (terminar actividad)
elseif ($modulo == "actuacion_fiscal_actividades_terminar") {
	if ($reprogramar == "true") $DiasAdelanto = $Duracion - $DiasCierre; else $DiasAdelanto = 0;
	if ($DiasAdelanto > 0) {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					DiasAdelanto = DiasAdelanto + ".intval($DiasAdelanto).",
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
	}
	
	//	actualizo detalle
	$sql = "UPDATE pf_actuacionfiscaldetalle
			SET
				DiasAdelanto = '".$DiasAdelanto."',
				FechaRegistroCierre = NOW(),
				FechaTerminoCierre = '".formatFechaAMD($FechaTerminoCierre)."',
				DiasCierre = '".$DiasCierre."',
				Observaciones = '".$Observaciones."',
				Estado = 'TE',
				UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFecha = NOW()
			WHERE
				CodActuacion = '".$CodActuacion."' AND
				Secuencia = '".$Secuencia."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	documentos
	if ($detalles_documentos != "") {
		$_Linea = 0;
		$detalle_documentos = split(";", $detalles_documentos);
		foreach ($detalle_documentos as $linea) {
			list($_Documento, $_NroDocumento, $_Fecha) = split("[|]", $linea);
			$_Linea++;
			//	inserto
			$sql = "INSERT INTO pf_actuacionfiscaldocumentos (
								CodActuacion,
								Secuencia,
								Linea,
								Documento,
								NroDocumento,
								Fecha,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodActuacion."',
								'".$Secuencia."',
								'".$_Linea."',
								'".$_Documento."',
								'".$_NroDocumento."',
								'".formatFechaAMD($_Fecha)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	consulto si existen actividades afectas pendientes
	$sql = "SELECT
				afd.Secuencia,
				a.FlagNoAfectoPlan
			FROM
				pf_actuacionfiscaldetalle afd
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
			WHERE
				afd.CodActuacion = '".$CodActuacion."' AND
				afd.Secuencia > '".$Secuencia."' AND
				a.FlagNoAfectoPlan = 'N'
			LIMIT 0, 1";
	$query_actividad = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_actividad) != 0) {
		$field_actividad = mysql_fetch_array($query_actividad);
		//	actualizo siguiente actividad afecta
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = 'EJ',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodActuacion = '".$CodActuacion."' AND
					Secuencia > '".$Secuencia."' AND
					Secuencia <= '".$field_actividad['Secuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	} else {
		if ($AutoArchivo == "S") {
			if ($FlagAutoArchivo == "S") $EstadoAuto = "AA"; else $EstadoAuto = "AC";
			//	actualizo actuacion
			$sql = "UPDATE pf_actuacionfiscal
					SET
						FechaCompletada = '".formatFechaAMD($FechaTerminoCierre)."',
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodActuacion = '".$CodActuacion."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actividad
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodActuacion = '".$CodActuacion."' AND
						Secuencia >= '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	consulto si existen actividades pendientes
			$sql = "SELECT afd.Secuencia
					FROM pf_actuacionfiscaldetalle afd
					WHERE
						afd.CodActuacion = '".$CodActuacion."' AND
						afd.Secuencia > '".$Secuencia."'
					LIMIT 0, 1";
			$query_actividad = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query_actividad) != 0) {
				$field_actividad = mysql_fetch_array($query_actividad);
				//	actualizo siguiente actividad
				$sql = "UPDATE pf_actuacionfiscaldetalle
						SET
							Estado = 'EJ',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodActuacion = '".$CodActuacion."' AND
							Secuencia = '".$field_actividad['Secuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				$estado_plan = "TE";
			} else $estado_plan = "CO";
			
			//	actualizo actuacion
			$sql = "UPDATE pf_actuacionfiscal
					SET
						FechaCompletada = '".formatFechaAMD($FechaTerminoCierre)."',
						Estado = '".$estado_plan."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodActuacion = '".$CodActuacion."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	si se reprograma las actividades de la actuacion
	if ($reprogramar == "true") {
		$FechaInicio = $FechaInicioReal;
		$i = 0;
		
		//	consulto actvidades
		$sql = "SELECT
					afd.Secuencia,
					afd.Duracion,
					a.FlagNoAfectoPlan
				FROM
					pf_actuacionfiscaldetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodActuacion = '".$CodActuacion."' AND
					afd.Secuencia >= '".$Secuencia."'
				ORDER BY Secuencia";
		$query_actividad = mysql_query($sql) or die($sql.mysql_error());
		while($field_actividad = mysql_fetch_array($query_actividad)) {
			$i++;
			if ($i == 1) {
				$Duracion = $DiasCierre;
				$FechaTermino = $FechaTerminoCierre;
			}
			else {
				$Duracion = $field_actividad['Duracion'];
				$FechaTermino = getFechaFinHabiles($FechaInicio, $Duracion);
			}
			
			//	actualizo
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET
						FechaInicioReal = '".formatFechaAMD($FechaInicio)."',
						FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodActuacion = '".$CodActuacion."' AND
						Secuencia = '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si no es afecto
			if ($field_actividad['FlagNoAfectoPlan'] == "N") {
				$FechaInicio = getFechaFinHabiles($FechaTermino, 2);
			}
		}
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	actuacion fiscal (prorrogas)
elseif ($modulo == "actuacion_fiscal_prorrogas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	consulto si ya existe una prorroga para la actividad y la actuacion sin aprobar
		$sql = "SELECT Estado
				FROM pf_prorroga
				WHERE
					CodActuacion = '".$CodActuacion."' AND
					CodActividad = '".$CodActividad."' AND
					(Estado = 'PR' OR Estado = 'RV')";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("Ya existe una prorroga sin aprobar para esta actividad");
		
		//	consulto organismo
		$sql = "SELECT CodOrganismo FROM pf_actuacionfiscal WHERE CodActuacion = '".$CodActuacion."'";
		$query_org = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_org) != 0) $field_org = mysql_fetch_array($query_org);
		
		//	genero correlativo
		$correlativo = getCodigo_2("pf_prorroga", "Secuencia", "CodActuacion", $CodActuacion, 2);
		$CodProrroga = "$CodActuacion$correlativo";
		$Secuencia = intval($correlativo);
		
		//	inserto
		$sql = "INSERT INTO pf_prorroga (
							CodProrroga,
							Secuencia,
							CodActuacion,
							CodOrganismo,
							CodActividad,
							Prorroga,
							Motivo,
							FechaRegistro,
							PreparadoPor,
							FechaPreparacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProrroga."',
							'".$Secuencia."',
							'".$CodActuacion."',
							'".$field_org['CodOrganismo']."',
							'".$CodActividad."',
							'".$Prorroga."',
							'".$Motivo."',
							NOW(),
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|.|Prórroga Nro. <strong>$CodProrroga</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_prorroga
				SET
					Prorroga = '".$Prorroga."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_prorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_prorroga
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actividades
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Estado, $_ProrrogaAcu, $_Prorroga, $_FechaInicioReal, $_FechaTerminoReal) = split("[|]", $linea);
			$_Secuencia++;
			if ($_Estado == "TE") $_update = "";
			else $_update = "Prorroga = (Prorroga + ".intval($_Prorroga)."),";
			//	actualizo
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET
						$_update
						FechaInicioReal = '".formatFechaAMD($_FechaInicioReal)."',
						FechaTerminoReal = '".formatFechaAMD($_FechaTerminoReal)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodActuacion = '".$CodActuacion."' AND
						CodActividad = '".$_CodActividad."'";	fwrite($__archivo, $sql.";\n\n");
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Prorroga = (Prorroga + ".intval($Prorroga)."),
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodActuacion = '".$CodActuacion."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	aprobar registro
	elseif ($accion== "anular") {
		if ($Estado == "PR") $Estado = "AN";
		elseif ($Estado == "RV") $Estado = "PR";
		
		//	modifico
		$sql = "UPDATE pf_prorroga
				SET
					Estado = '".$Estado."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	valoracion juridica
elseif ($modulo == "valoracion_juridica") {
	//	nuevo registro
	if ($accion == "generar") {
		//	genero correlativo
		$Anio = substr($FechaInicio, 6, 4);
		$correlativo = getCodigo_3("pf_valoracionjuridica", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 4);
		$Secuencia = (int) $correlativo;
		$CodInterno = getCodInternoDependencia($CodDependencia);
		$CodValJur = "$CodInterno-$correlativo-$Anio";
		
		//	inserto
		$sql = "INSERT INTO pf_valoracionjuridica (
							CodActuacion,
							CodValJur,
							Anio,
							Secuencia,
							CodOrganismo,
							CodDependencia,
							CodOrganismoExterno,
							CodDependenciaExterna,
							CodTipoActuacion,
							CodCentroCosto,
							CodProceso,
							ObjetivoGeneral,
							Alcance,
							Observacion,
							FechaRegistro,
							FechaInicio,
							FechaTermino,
							FechaTerminoReal,
							Duracion,
							PreparadoPor,
							FechaPreparacion,
							Origen,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodActuacion."',
							'".$CodValJur."',
							'".$Anio."',
							'".$Secuencia."',
							'".$CodOrganismo."',
							'".$CodDependencia."',
							'".$CodOrganismoExterno."',
							'".$CodDependenciaExterna."',
							'".$CodTipoActuacion."',
							'".$CodCentroCosto."',
							'".$CodProceso."',
							'".$ObjetivoGeneral."',
							'".$Alcance."',
							'".$Observacion."',
							NOW(),
							'".formatFechaAMD($FechaInicio)."',
							'".formatFechaAMD($FechaTermino)."',
							'".formatFechaAMD($FechaTerminoReal)."',
							'".$Duracion."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$Origen."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_valoracionjuridicaauditores (
								CodValJur,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodValJur."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_valoracionjuridicadetalle (
								CodValJur,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodValJur."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo actuacion fiscal
		$sql = "UPDATE pf_actuacionfiscal
				SET
					EstadoValJur = 'GE',
					CodValJur = '".$CodValJur."'
				WHERE CodActuacion = '".$CodActuacion."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|.|Valoración Nro. <strong>$CodValJur</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridica
				SET
					CodTipoActuacion = '".$CodTipoActuacion."',
					CodCentroCosto = '".$CodCentroCosto."',
					ObjetivoGeneral = '".$ObjetivoGeneral."',
					Alcance = '".$Alcance."',
					Observacion = '".$Observacion."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaTermino = '".formatFechaAMD($FechaTermino)."',
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					Duracion = '".$Duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$sql = "DELETE FROM pf_valoracionjuridicaauditores WHERE CodValJur = '".$CodValJur."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_valoracionjuridicaauditores (
								CodValJur,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodValJur."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_valoracionjuridicadetalle WHERE CodValJur = '".$CodValJur."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_valoracionjuridicadetalle (
								CodValJur,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodValJur."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridica
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridica
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_valoracionjuridicadetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	cambio el estado a ejecucion a la primera acividad afecta
		$sql = "SELECT
					afd.CodValJur,
					afd.Secuencia,
					afd.CodActividad
				FROM
					pf_valoracionjuridicadetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodValJur = '".$CodValJur."' AND
					a.FlagNoAfectoPlan = 'N'
				ORDER BY Secuencia
				LIMIT 0, 1";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field = mysql_fetch_array($query)) {
			$sql = "UPDATE pf_valoracionjuridicadetalle
					SET Estado = 'EJ'
					WHERE
						CodValJur = '".$CodValJur."' AND
						Secuencia <= '".$field['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	cerrar registro
	elseif ($accion== "cerrar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridica
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_valoracionjuridicadetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodValJur = '".$CodValJur."' AND
					(Estado = 'PE' OR Estado = 'EJ')";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	valoracion juridica (prorrogas)
elseif ($modulo == "valoracion_juridica_prorrogas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	consulto si ya existe una prorroga para la actividad y la actuacion sin aprobar
		$sql = "SELECT Estado
				FROM pf_valoracionjuridicaprorroga
				WHERE
					CodValJur = '".$CodValJur."' AND
					CodActividad = '".$CodActividad."' AND
					(Estado = 'PR' OR Estado = 'RV')";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("Ya existe una prorroga sin aprobar para esta actividad");
		
		//	consulto organismo
		$sql = "SELECT CodOrganismo FROM pf_valoracionjuridica WHERE CodValJur = '".$CodValJur."'";
		$query_org = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_org) != 0) $field_org = mysql_fetch_array($query_org);
		
		//	genero correlativo
		$correlativo = getCodigo_2("pf_valoracionjuridicaprorroga", "Secuencia", "CodValJur", $CodValJur, 2);
		$CodProrroga = "$CodValJur$correlativo";
		$Secuencia = intval($correlativo);
		
		//	inserto
		$sql = "INSERT INTO pf_valoracionjuridicaprorroga (
							CodProrroga,
							Secuencia,
							CodValJur,
							CodOrganismo,
							CodActividad,
							Prorroga,
							Motivo,
							FechaRegistro,
							PreparadoPor,
							FechaPreparacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProrroga."',
							'".$Secuencia."',
							'".$CodValJur."',
							'".$field_org['CodOrganismo']."',
							'".$CodActividad."',
							'".$Prorroga."',
							'".$Motivo."',
							NOW(),
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|.|Prórroga Nro. <strong>$CodProrroga</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridicaprorroga
				SET
					Prorroga = '".$Prorroga."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridicaprorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_valoracionjuridicaprorroga
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actividades
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Estado, $_ProrrogaAcu, $_Prorroga, $_FechaInicioReal, $_FechaTerminoReal) = split("[|]", $linea);
			$_Secuencia++;
			if ($_Estado == "EJ") $_update = "Prorroga = (Prorroga + ".intval($_Prorroga)."),";
			else $_update = "";
			//	actualizo
			$sql = "UPDATE pf_valoracionjuridicadetalle
					SET
						$_update
						FechaInicioReal = '".formatFechaAMD($_FechaInicioReal)."',
						FechaTerminoReal = '".formatFechaAMD($_FechaTerminoReal)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodValJur = '".$CodValJur."' AND
						CodActividad = '".$_CodActividad."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE pf_valoracionjuridica
				SET
					Prorroga = (Prorroga + ".intval($Prorroga)."),
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	aprobar registro
	elseif ($accion== "anular") {
		if ($Estado == "PR") $Estado = "AN";
		elseif ($Estado == "RV") $Estado = "PR";
		
		//	modifico
		$sql = "UPDATE pf_valoracionjuridicaprorroga
				SET
					Estado = '".$Estado."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	valoracion juridica (terminar actividad)
elseif ($modulo == "valoracion_juridica_actividades_terminar") {
	if ($reprogramar == "true") $DiasAdelanto = $Duracion - $DiasCierre; else $DiasAdelanto = 0;
	if ($DiasAdelanto > 0) {
		//	actualizo
		$sql = "UPDATE pf_valoracionjuridica
				SET
					DiasAdelanto = DiasAdelanto + ".intval($DiasAdelanto).",
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
	}
	
	//	actualizo detalle
	$sql = "UPDATE pf_valoracionjuridicadetalle
			SET
				DiasAdelanto = '".$DiasAdelanto."',
				FechaRegistroCierre = NOW(),
				FechaTerminoCierre = '".formatFechaAMD($FechaTerminoCierre)."',
				DiasCierre = '".$DiasCierre."',
				Observaciones = '".$Observaciones."',
				Estado = 'TE',
				UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFecha = NOW()
			WHERE
				CodValJur = '".$CodValJur."' AND
				Secuencia = '".$Secuencia."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	documentos
	if ($detalles_documentos != "") {
		$_Linea = 0;
		$detalle_documentos = split(";", $detalles_documentos);
		foreach ($detalle_documentos as $linea) {
			list($_Documento, $_NroDocumento, $_Fecha) = split("[|]", $linea);
			$_Linea++;
			//	inserto
			$sql = "INSERT INTO pf_valoracionjuridicadocumentos (
								CodValJur,
								Secuencia,
								Linea,
								Documento,
								NroDocumento,
								Fecha,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodValJur."',
								'".$Secuencia."',
								'".$_Linea."',
								'".$_Documento."',
								'".$_NroDocumento."',
								'".formatFechaAMD($_Fecha)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	consulto si existen actividades afectas pendientes
	$sql = "SELECT
				afd.Secuencia,
				a.FlagNoAfectoPlan
			FROM
				pf_valoracionjuridicadetalle afd
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
			WHERE
				afd.CodValJur = '".$CodValJur."' AND
				afd.Secuencia > '".$Secuencia."' AND
				a.FlagNoAfectoPlan = 'N'
			LIMIT 0, 1";
	$query_actividad = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_actividad) != 0) {
		$field_actividad = mysql_fetch_array($query_actividad);
		//	actualizo siguiente actividad afecta
		$sql = "UPDATE pf_valoracionjuridicadetalle
				SET
					Estado = 'EJ',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodValJur = '".$CodValJur."' AND
					Secuencia > '".$Secuencia."' AND
					Secuencia <= '".$field_actividad['Secuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	} else {
		if ($AutoArchivo == "S") {
			if ($FlagAutoArchivo == "S") $EstadoAuto = "AA"; else $EstadoAuto = "AC";
			//	actualizo valoracion
			$sql = "UPDATE pf_valoracionjuridica
					SET
						FechaCompletada = '".formatFechaAMD($FechaTerminoCierre)."',
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodValJur = '".$CodValJur."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actividad
			$sql = "UPDATE pf_valoracionjuridicadetalle
					SET
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodValJur = '".$CodValJur."' AND
						Secuencia >= '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actuacion fiscal
			$sql = "UPDATE pf_actuacionfiscal
					SET
						EstadoValJur = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodValJur = '".$CodValJur."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	consulto si existen actividades pendientes
			$sql = "SELECT afd.Secuencia
					FROM pf_valoracionjuridicadetalle afd
					WHERE
						afd.CodValJur = '".$CodValJur."' AND
						afd.Secuencia > '".$Secuencia."'
					LIMIT 0, 1";
			$query_actividad = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query_actividad) != 0) {
				$field_actividad = mysql_fetch_array($query_actividad);
				//	actualizo siguiente actividad
				$sql = "UPDATE pf_valoracionjuridicadetalle
						SET
							Estado = 'EJ',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodValJur = '".$CodValJur."' AND
							Secuencia = '".$field_actividad['Secuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	si se reprograma las actividades de la actuacion
	if ($reprogramar == "true") {
		$FechaInicio = $FechaInicioReal;
		$i = 0;
		
		//	consulto actvidades
		$sql = "SELECT
					afd.Secuencia,
					afd.Duracion,
					a.FlagNoAfectoPlan
				FROM
					pf_valoracionjuridicadetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodValJur = '".$CodValJur."' AND
					afd.Secuencia >= '".$Secuencia."'
				ORDER BY Secuencia";
		$query_actividad = mysql_query($sql) or die($sql.mysql_error());
		while($field_actividad = mysql_fetch_array($query_actividad)) {
			$i++;
			if ($i == 1) {
				$Duracion = $DiasCierre;
				$FechaTermino = $FechaTerminoCierre;
			}
			else {
				$Duracion = $field_actividad['Duracion'];
				$FechaTermino = getFechaFinHabiles($FechaInicio, $Duracion);
			}
			
			//	actualizo
			$sql = "UPDATE pf_valoracionjuridicadetalle
					SET
						FechaInicioReal = '".formatFechaAMD($FechaInicio)."',
						FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodValJur = '".$CodValJur."' AND
						Secuencia = '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si no es afecto
			if ($field_actividad['FlagNoAfectoPlan'] == "N") {
				$FechaInicio = getFechaFinHabiles($FechaTermino, 2);
			}
		}
		
		//	actualizo
		$sql = "UPDATE pf_valoracionjuridica
				SET
					FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	potestad investigativa
elseif ($modulo == "potestad_investigativa") {
	//	nuevo registro
	if ($accion == "generar") {
		//	genero correlativo
		$Anio = substr($FechaInicio, 6, 4);
		$correlativo = getCodigo_3("pf_potestad", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 4);
		$Secuencia = (int) $correlativo;
		$CodInterno = getCodInternoDependencia($CodDependencia);
		$CodPotestad = "$CodInterno-$correlativo-$Anio";
		
		//	inserto
		$sql = "INSERT INTO pf_potestad (
							CodActuacion,
							CodValJur,
							CodPotestad,
							Anio,
							Secuencia,
							CodOrganismo,
							CodDependencia,
							CodOrganismoExterno,
							CodDependenciaExterna,
							CodTipoActuacion,
							CodCentroCosto,
							CodProceso,
							ObjetivoGeneral,
							Alcance,
							Observacion,
							FechaRegistro,
							FechaInicio,
							FechaTermino,
							FechaTerminoReal,
							Duracion,
							PreparadoPor,
							FechaPreparacion,
							Origen,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodActuacion."',
							'".$CodValJur."',
							'".$CodPotestad."',
							'".$Anio."',
							'".$Secuencia."',
							'".$CodOrganismo."',
							'".$CodDependencia."',
							'".$CodOrganismoExterno."',
							'".$CodDependenciaExterna."',
							'".$CodTipoActuacion."',
							'".$CodCentroCosto."',
							'".$CodProceso."',
							'".$ObjetivoGeneral."',
							'".$Alcance."',
							'".$Observacion."',
							NOW(),
							'".formatFechaAMD($FechaInicio)."',
							'".formatFechaAMD($FechaTermino)."',
							'".formatFechaAMD($FechaTerminoReal)."',
							'".$Duracion."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$Origen."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_potestadauditores (
								CodPotestad,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodPotestad."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_potestaddetalle (
								CodPotestad,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodPotestad."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo actuacion fiscal
		$sql = "UPDATE pf_valoracionjuridica
				SET
					EstadoPotestad = 'GE',
					CodPotestad = '".$CodPotestad."'
				WHERE CodValJur = '".$CodValJur."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|.|Potestad Nro. <strong>$CodPotestad</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_potestad
				SET
					CodTipoActuacion = '".$CodTipoActuacion."',
					CodCentroCosto = '".$CodCentroCosto."',
					ObjetivoGeneral = '".$ObjetivoGeneral."',
					Alcance = '".$Alcance."',
					Observacion = '".$Observacion."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaTermino = '".formatFechaAMD($FechaTermino)."',
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					Duracion = '".$Duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$sql = "DELETE FROM pf_potestadauditores WHERE CodPotestad = '".$CodPotestad."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_potestadauditores (
								CodPotestad,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodPotestad."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_potestaddetalle WHERE CodPotestad = '".$CodPotestad."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_potestaddetalle (
								CodPotestad,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodPotestad."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_potestaddetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	cambio el estado a ejecucion a la primera acividad afecta
		$sql = "SELECT
					afd.CodPotestad,
					afd.Secuencia,
					afd.CodActividad
				FROM
					pf_potestaddetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodPotestad = '".$CodPotestad."' AND
					a.FlagNoAfectoPlan = 'N'
				ORDER BY Secuencia
				LIMIT 0, 1";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field = mysql_fetch_array($query)) {
			$sql = "UPDATE pf_potestaddetalle
					SET Estado = 'EJ'
					WHERE
						CodPotestad = '".$CodPotestad."' AND
						Secuencia <= '".$field['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	cerrar registro
	elseif ($accion== "cerrar") {
		//	modifico
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_potestaddetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPotestad = '".$CodPotestad."' AND
					(Estado = 'PE' OR Estado = 'EJ')";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	potestad investigativa (prorrogas)
elseif ($modulo == "potestad_investigativa_prorrogas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	consulto si ya existe una prorroga para la actividad y la actuacion sin aprobar
		$sql = "SELECT Estado
				FROM pf_potestadprorroga
				WHERE
					CodPotestad = '".$CodPotestad."' AND
					CodActividad = '".$CodActividad."' AND
					(Estado = 'PR' OR Estado = 'RV')";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("Ya existe una prorroga sin aprobar para esta actividad");
		
		//	consulto organismo
		$sql = "SELECT CodOrganismo FROM pf_potestad WHERE CodPotestad = '".$CodPotestad."'";
		$query_org = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_org) != 0) $field_org = mysql_fetch_array($query_org);
		
		//	genero correlativo
		$correlativo = getCodigo_2("pf_potestadprorroga", "Secuencia", "CodPotestad", $CodPotestad, 2);
		$CodProrroga = "$CodPotestad$correlativo";
		$Secuencia = intval($correlativo);
		
		//	inserto
		$sql = "INSERT INTO pf_potestadprorroga (
							CodProrroga,
							Secuencia,
							CodPotestad,
							CodOrganismo,
							CodActividad,
							Prorroga,
							Motivo,
							FechaRegistro,
							PreparadoPor,
							FechaPreparacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProrroga."',
							'".$Secuencia."',
							'".$CodPotestad."',
							'".$field_org['CodOrganismo']."',
							'".$CodActividad."',
							'".$Prorroga."',
							'".$Motivo."',
							NOW(),
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|.|Prórroga Nro. <strong>$CodProrroga</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_potestadprorroga
				SET
					Prorroga = '".$Prorroga."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_potestadprorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_potestadprorroga
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actividades
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Estado, $_ProrrogaAcu, $_Prorroga, $_FechaInicioReal, $_FechaTerminoReal) = split("[|]", $linea);
			$_Secuencia++;
			if ($_Estado == "EJ") $_update = "Prorroga = (Prorroga + ".intval($_Prorroga)."),";
			else $_update = "";
			//	actualizo
			$sql = "UPDATE pf_potestaddetalle
					SET
						$_update
						FechaInicioReal = '".formatFechaAMD($_FechaInicioReal)."',
						FechaTerminoReal = '".formatFechaAMD($_FechaTerminoReal)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodPotestad = '".$CodPotestad."' AND
						CodActividad = '".$_CodActividad."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					Prorroga = (Prorroga + ".intval($Prorroga)."),
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	aprobar registro
	elseif ($accion== "anular") {
		if ($Estado == "PR") $Estado = "AN";
		elseif ($Estado == "RV") $Estado = "PR";
		
		//	modifico
		$sql = "UPDATE pf_potestadprorroga
				SET
					Estado = '".$Estado."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	potestad investigativa (terminar actividad)
elseif ($modulo == "potestad_investigativa_actividades_terminar") {
	if ($reprogramar == "true") $DiasAdelanto = $Duracion - $DiasCierre; else $DiasAdelanto = 0;
	if ($DiasAdelanto > 0) {
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					DiasAdelanto = DiasAdelanto + ".intval($DiasAdelanto).",
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
	}
	
	//	actualizo detalle
	$sql = "UPDATE pf_potestaddetalle
			SET
				DiasAdelanto = '".$DiasAdelanto."',
				FechaRegistroCierre = NOW(),
				FechaTerminoCierre = '".formatFechaAMD($FechaTerminoCierre)."',
				DiasCierre = '".$DiasCierre."',
				Observaciones = '".$Observaciones."',
				Estado = 'TE',
				UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFecha = NOW()
			WHERE
				CodPotestad = '".$CodPotestad."' AND
				Secuencia = '".$Secuencia."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	documentos
	if ($detalle_documentos != "") {
		$_Linea = 0;
		$detalle_documentos = split(";", $detalles_documentos);
		foreach ($detalle_documentos as $linea) {
			list($_Documento, $_NroDocumento, $_Fecha) = split("[|]", $linea);
			$_Linea++;
			//	inserto
			$sql = "INSERT INTO pf_potestaddocumentos (
								CodPotestad,
								Secuencia,
								Linea,
								Documento,
								NroDocumento,
								Fecha,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodPotestad."',
								'".$Secuencia."',
								'".$_Linea."',
								'".$_Documento."',
								'".$_NroDocumento."',
								'".formatFechaAMD($_Fecha)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	consulto si existen actividades afectas pendientes
	$sql = "SELECT
				afd.Secuencia,
				a.FlagNoAfectoPlan
			FROM
				pf_potestaddetalle afd
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
			WHERE
				afd.CodPotestad = '".$CodPotestad."' AND
				afd.Secuencia > '".$Secuencia."' AND
				a.FlagNoAfectoPlan = 'N'
			LIMIT 0, 1";
	$query_actividad = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_actividad) != 0) {
		$field_actividad = mysql_fetch_array($query_actividad);
		//	actualizo siguiente actividad afecta
		$sql = "UPDATE pf_potestaddetalle
				SET
					Estado = 'EJ',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodPotestad = '".$CodPotestad."' AND
					Secuencia > '".$Secuencia."' AND
					Secuencia <= '".$field_actividad['Secuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	} else {
		if ($AutoArchivo == "S") {
			if ($FlagAutoArchivo == "S") $EstadoAuto = "AA"; else $EstadoAuto = "AC";
			//	actualizo valoracion
			$sql = "UPDATE pf_potestad
					SET
						FechaCompletada = '".formatFechaAMD($FechaTerminoCierre)."',
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodPotestad = '".$CodPotestad."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actividad
			$sql = "UPDATE pf_potestaddetalle
					SET
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodPotestad = '".$CodPotestad."' AND
						Secuencia >= '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actuacion fiscal
			$sql = "UPDATE pf_valoracionjuridica
					SET
						EstadoPotestad = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodPotestad = '".$CodPotestad."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	consulto si existen actividades pendientes
			$sql = "SELECT afd.Secuencia
					FROM pf_potestaddetalle afd
					WHERE
						afd.CodPotestad = '".$CodPotestad."' AND
						afd.Secuencia > '".$Secuencia."'
					LIMIT 0, 1";
			$query_actividad = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query_actividad) != 0) {
				$field_actividad = mysql_fetch_array($query_actividad);
				//	actualizo siguiente actividad
				$sql = "UPDATE pf_potestaddetalle
						SET
							Estado = 'EJ',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodPotestad = '".$CodPotestad."' AND
							Secuencia = '".$field_actividad['Secuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	si se reprograma las actividades de la actuacion
	if ($reprogramar == "true") {
		$FechaInicio = $FechaInicioReal;
		$i = 0;
		
		//	consulto actvidades
		$sql = "SELECT
					afd.Secuencia,
					afd.Duracion,
					a.FlagNoAfectoPlan
				FROM
					pf_potestaddetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodPotestad = '".$CodPotestad."' AND
					afd.Secuencia >= '".$Secuencia."'
				ORDER BY Secuencia";
		$query_actividad = mysql_query($sql) or die($sql.mysql_error());
		while($field_actividad = mysql_fetch_array($query_actividad)) {
			$i++;
			if ($i == 1) {
				$Duracion = $DiasCierre;
				$FechaTermino = $FechaTerminoCierre;
			}
			else {
				$Duracion = $field_actividad['Duracion'];
				$FechaTermino = getFechaFinHabiles($FechaInicio, $Duracion);
			}
			
			//	actualizo
			$sql = "UPDATE pf_potestaddetalle
					SET
						FechaInicioReal = '".formatFechaAMD($FechaInicio)."',
						FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodPotestad = '".$CodPotestad."' AND
						Secuencia = '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si no es afecto
			if ($field_actividad['FlagNoAfectoPlan'] == "N") {
				$FechaInicio = getFechaFinHabiles($FechaTermino, 2);
			}
		}
		
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	determinacion de responsabilidad
elseif ($modulo == "determinacion_responsabilidad") {
	//	nuevo registro
	if ($accion == "generar") {
		//	genero correlativo
		$Anio = substr($FechaInicio, 6, 4);
		$correlativo = getCodigo_3("pf_determinacion", "Secuencia", "Anio", "CodDependencia", $Anio, $CodDependencia, 4);
		$Secuencia = (int) $correlativo;
		$CodInterno = getCodInternoDependencia($CodDependencia);
		$CodDeterminacion = "$CodInterno-$correlativo-$Anio";
		
		//	inserto
		$sql = "INSERT INTO pf_determinacion (
							CodPotestad,
							CodDeterminacion,
							Anio,
							Secuencia,
							CodOrganismo,
							CodDependencia,
							CodOrganismoExterno,
							CodDependenciaExterna,
							CodTipoActuacion,
							CodCentroCosto,
							CodProceso,
							ObjetivoGeneral,
							Alcance,
							Observacion,
							FechaRegistro,
							FechaInicio,
							FechaTermino,
							FechaTerminoReal,
							Duracion,
							PreparadoPor,
							FechaPreparacion,
							Origen,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodPotestad."',
							'".$CodDeterminacion."',
							'".$Anio."',
							'".$Secuencia."',
							'".$CodOrganismo."',
							'".$CodDependencia."',
							'".$CodOrganismoExterno."',
							'".$CodDependenciaExterna."',
							'".$CodTipoActuacion."',
							'".$CodCentroCosto."',
							'".$CodProceso."',
							'".$ObjetivoGeneral."',
							'".$Alcance."',
							'".$Observacion."',
							NOW(),
							'".formatFechaAMD($FechaInicio)."',
							'".formatFechaAMD($FechaTermino)."',
							'".formatFechaAMD($FechaTerminoReal)."',
							'".$Duracion."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$Origen."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_determinacionauditores (
								CodDeterminacion,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodDeterminacion."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_determinaciondetalle (
								CodDeterminacion,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodDeterminacion."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo actuacion fiscal
		$sql = "UPDATE pf_potestad
				SET
					EstadoDeterminacion = 'GE',
					CodDeterminacion = '".$CodDeterminacion."'
				WHERE CodPotestad = '".$CodPotestad."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|.|Determinacion Nro. <strong>$CodDeterminacion</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_determinacion
				SET
					CodTipoActuacion = '".$CodTipoActuacion."',
					CodCentroCosto = '".$CodCentroCosto."',
					ObjetivoGeneral = '".$ObjetivoGeneral."',
					Alcance = '".$Alcance."',
					Observacion = '".$Observacion."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaTermino = '".formatFechaAMD($FechaTermino)."',
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					Duracion = '".$Duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	auditores
		$sql = "DELETE FROM pf_determinacionauditores WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$secuencia = 0;
		$detalle_auditores = split(";", $detalles_auditores);
		foreach ($detalle_auditores as $linea) {
			list($_CodPersona, $_FlagCoordinador) = split("[|]", $linea);
			//	inserto
			$sql = "INSERT INTO pf_determinacionauditores (
								CodDeterminacion,
								CodPersona,
								CodOrganismo,
								FlagCoordinador,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodDeterminacion."',
								'".$_CodPersona."',
								'".$CodOrganismo."',
								'".$_FlagCoordinador."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_determinaciondetalle WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		##
		$_Secuencia = 0;
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Duracion, $_FechaInicio, $_FechaTermino) = split("[|]", $linea);
			$_Secuencia++;
			
			//	descripcion
			$_Descripcion = getDescripcionActividad($_CodActividad);
			
			//	inserto
			$sql = "INSERT INTO pf_determinaciondetalle (
								CodDeterminacion,
								Secuencia,
								CodOrganismo,
								CodActividad,
								Descripcion,
								CodCentroCosto,
								FechaInicio,
								FechaInicioReal,
								FechaTermino,
								FechaTerminoReal,
								Duracion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodDeterminacion."',
								'".$_Secuencia."',
								'".$CodOrganismo."',
								'".$_CodActividad."',
								'".$_Descripcion."',
								'".$CodCentroCosto."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaInicio)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".formatFechaAMD($_FechaTermino)."',
								'".$_Duracion."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	cambio el estado a ejecucion a la primera acividad afecta
		$sql = "SELECT
					afd.CodDeterminacion,
					afd.Secuencia,
					afd.CodActividad
				FROM
					pf_determinaciondetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodDeterminacion = '".$CodDeterminacion."' AND
					a.FlagNoAfectoPlan = 'N'
				ORDER BY Secuencia
				LIMIT 0, 1";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field = mysql_fetch_array($query)) {
			$sql = "UPDATE pf_determinaciondetalle
					SET Estado = 'EJ'
					WHERE
						CodDeterminacion = '".$CodDeterminacion."' AND
						Secuencia <= '".$field['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	cerrar registro
	elseif ($accion== "cerrar") {
		//	modifico
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		##
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodDeterminacion = '".$CodDeterminacion."' AND
					(Estado = 'PE' OR Estado = 'EJ')";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	determinacion de responsabilidad (prorrogas)
elseif ($modulo == "determinacion_responsabilidad_prorrogas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	consulto si ya existe una prorroga para la actividad y la actuacion sin aprobar
		$sql = "SELECT Estado
				FROM pf_determinacionprorroga
				WHERE
					CodDeterminacion = '".$CodDeterminacion."' AND
					CodActividad = '".$CodActividad."' AND
					(Estado = 'PR' OR Estado = 'RV')";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("Ya existe una prorroga sin aprobar para esta actividad");
		
		//	consulto organismo
		$sql = "SELECT CodOrganismo FROM pf_determinacion WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_org = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_org) != 0) $field_org = mysql_fetch_array($query_org);
		
		//	genero correlativo
		$correlativo = getCodigo_2("pf_determinacionprorroga", "Secuencia", "CodDeterminacion", $CodDeterminacion, 2);
		$CodProrroga = "$CodDeterminacion$correlativo";
		$Secuencia = intval($correlativo);
		
		//	inserto
		$sql = "INSERT INTO pf_determinacionprorroga (
							CodProrroga,
							Secuencia,
							CodDeterminacion,
							CodOrganismo,
							CodActividad,
							Prorroga,
							Motivo,
							FechaRegistro,
							PreparadoPor,
							FechaPreparacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProrroga."',
							'".$Secuencia."',
							'".$CodDeterminacion."',
							'".$field_org['CodOrganismo']."',
							'".$CodActividad."',
							'".$Prorroga."',
							'".$Motivo."',
							NOW(),
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							NOW(),
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|.|Prórroga Nro. <strong>$CodProrroga</strong> Se generó con éxito";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	modifico
		$sql = "UPDATE pf_determinacionprorroga
				SET
					Prorroga = '".$Prorroga."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE pf_determinacionprorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	modifico
		$sql = "UPDATE pf_determinacionprorroga
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actividades
		$detalle_actividades = split(";", $detalles_actividades);
		foreach ($detalle_actividades as $linea) {
			list($_CodActividad, $_Estado, $_ProrrogaAcu, $_Prorroga, $_FechaInicioReal, $_FechaTerminoReal) = split("[|]", $linea);
			$_Secuencia++;
			if ($_Estado == "EJ") $_update = "Prorroga = (Prorroga + ".intval($_Prorroga)."),";
			else $_update = "";
			//	actualizo
			$sql = "UPDATE pf_determinaciondetalle
					SET
						$_update
						FechaInicioReal = '".formatFechaAMD($_FechaInicioReal)."',
						FechaTerminoReal = '".formatFechaAMD($_FechaTerminoReal)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodDeterminacion = '".$CodDeterminacion."' AND
						CodActividad = '".$_CodActividad."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					Prorroga = (Prorroga + ".intval($Prorroga)."),
					FechaTerminoReal = '".formatFechaAMD($FechaTerminoReal)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	aprobar registro
	elseif ($accion== "anular") {
		if ($Estado == "PR") $Estado = "AN";
		elseif ($Estado == "RV") $Estado = "PR";
		
		//	modifico
		$sql = "UPDATE pf_determinacionprorroga
				SET
					Estado = '".$Estado."',
					Motivo = '".$Motivo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProrroga = '".$CodProrroga."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	determinacion de responsabilidad (terminar actividad)
elseif ($modulo == "determinacion_responsabilidad_actividades_terminar") {
	if ($reprogramar == "true") $DiasAdelanto = $Duracion - $DiasCierre; else $DiasAdelanto = 0;
	if ($DiasAdelanto > 0) {
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					DiasAdelanto = DiasAdelanto + ".intval($DiasAdelanto).",
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
	}
	
	//	actualizo detalle
	$sql = "UPDATE pf_determinaciondetalle
			SET
				DiasAdelanto = '".$DiasAdelanto."',
				FechaRegistroCierre = NOW(),
				FechaTerminoCierre = '".formatFechaAMD($FechaTerminoCierre)."',
				DiasCierre = '".$DiasCierre."',
				Observaciones = '".$Observaciones."',
				Estado = 'TE',
				UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
				UltimaFecha = NOW()
			WHERE
				CodDeterminacion = '".$CodDeterminacion."' AND
				Secuencia = '".$Secuencia."'";
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	documentos
	if ($detalle_documentos != "") {
		$_Linea = 0;
		$detalle_documentos = split(";", $detalles_documentos);
		foreach ($detalle_documentos as $linea) {
			list($_Documento, $_NroDocumento, $_Fecha) = split("[|]", $linea);
			$_Linea++;
			//	inserto
			$sql = "INSERT INTO pf_determinaciondocumentos (
								CodDeterminacion,
								Secuencia,
								Linea,
								Documento,
								NroDocumento,
								Fecha,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodDeterminacion."',
								'".$Secuencia."',
								'".$_Linea."',
								'".$_Documento."',
								'".$_NroDocumento."',
								'".formatFechaAMD($_Fecha)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	consulto si existen actividades afectas pendientes
	$sql = "SELECT
				afd.Secuencia,
				a.FlagNoAfectoPlan
			FROM
				pf_determinaciondetalle afd
				INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
			WHERE
				afd.CodDeterminacion = '".$CodDeterminacion."' AND
				afd.Secuencia > '".$Secuencia."' AND
				a.FlagNoAfectoPlan = 'N'
			LIMIT 0, 1";
	$query_actividad = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_actividad) != 0) {
		$field_actividad = mysql_fetch_array($query_actividad);
		//	actualizo siguiente actividad afecta
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Estado = 'EJ',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodDeterminacion = '".$CodDeterminacion."' AND
					Secuencia > '".$Secuencia."' AND
					Secuencia <= '".$field_actividad['Secuencia']."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	} else {
		if ($AutoArchivo == "S") {
			if ($FlagAutoArchivo == "S") $EstadoAuto = "AA"; else $EstadoAuto = "AC";
			//	actualizo valoracion
			$sql = "UPDATE pf_determinacion
					SET
						FechaCompletada = '".formatFechaAMD($FechaTerminoCierre)."',
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodDeterminacion = '".$CodDeterminacion."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actividad
			$sql = "UPDATE pf_determinaciondetalle
					SET
						Estado = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodDeterminacion = '".$CodDeterminacion."' AND
						Secuencia >= '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo actuacion fiscal
			$sql = "UPDATE pf_potestad
					SET
						EstadoDeterminacion = '".$EstadoAuto."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE CodDeterminacion = '".$CodDeterminacion."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	consulto si existen actividades pendientes
			$sql = "SELECT afd.Secuencia
					FROM pf_determinaciondetalle afd
					WHERE
						afd.CodDeterminacion = '".$CodDeterminacion."' AND
						afd.Secuencia > '".$Secuencia."'
					LIMIT 0, 1";
			$query_actividad = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query_actividad) != 0) {
				$field_actividad = mysql_fetch_array($query_actividad);
				//	actualizo siguiente actividad
				$sql = "UPDATE pf_determinaciondetalle
						SET
							Estado = 'EJ',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodDeterminacion = '".$CodDeterminacion."' AND
							Secuencia = '".$field_actividad['Secuencia']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	si se reprograma las actividades de la actuacion
	if ($reprogramar == "true") {
		$FechaInicio = $FechaInicioReal;
		$i = 0;
		
		//	consulto actvidades
		$sql = "SELECT
					afd.Secuencia,
					afd.Duracion,
					a.FlagNoAfectoPlan
				FROM
					pf_determinaciondetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
				WHERE
					afd.CodDeterminacion = '".$CodDeterminacion."' AND
					afd.Secuencia >= '".$Secuencia."'
				ORDER BY Secuencia";
		$query_actividad = mysql_query($sql) or die($sql.mysql_error());
		while($field_actividad = mysql_fetch_array($query_actividad)) {
			$i++;
			if ($i == 1) {
				$Duracion = $DiasCierre;
				$FechaTermino = $FechaTerminoCierre;
			}
			else {
				$Duracion = $field_actividad['Duracion'];
				$FechaTermino = getFechaFinHabiles($FechaInicio, $Duracion);
			}
			
			//	actualizo
			$sql = "UPDATE pf_determinaciondetalle
					SET
						FechaInicioReal = '".formatFechaAMD($FechaInicio)."',
						FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodDeterminacion = '".$CodDeterminacion."' AND
						Secuencia = '".$field_actividad['Secuencia']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	si no es afecto
			if ($field_actividad['FlagNoAfectoPlan'] == "N") {
				$FechaInicio = getFechaFinHabiles($FechaTermino, 2);
			}
		}
		
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					FechaTerminoReal = '".formatFechaAMD($FechaTermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE CodDeterminacion = '".$CodDeterminacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}
?>
