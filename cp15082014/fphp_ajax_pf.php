<?php
include("fphp_pf.php");

///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////

//	ORGANISMOS EXTERNOS
if ($_POST['modulo'] == "ORGANISMOS-EXTERNOS") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_organismosexternos WHERE Organismo = '".utf8_decode($descripcion)."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Organismo ya ingresado!");

		//	inserto
		$codigo = getCodigo("pf_organismosexternos", "CodOrganismo", 4);
		$sql = "INSERT INTO pf_organismosexternos (
						CodOrganismo,
						Organismo,
						DescripComp,
						RepresentLegal,
						DocRepreLeg,
						Cargo,
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
						Control,
						FlagSocial,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".utf8_decode($descripcionc)."',
						'".utf8_decode($rep)."',
						'".$docr."',
						'".utf8_decode($cargo)."',
						'".$www."',
						'".$docf."',
						'".formatFechaAMD($fecha)."',
						'".utf8_decode($dir)."',
						'".$ciudad."',
						'".$tel1."',
						'".$tel2."',
						'".$tel3."',
						'".$fax1."',
						'".$fax2."',
						'".$logo."',
						'".$status."',
						'".$nreg."',
						'".$treg."',
						'".$control."',
						'".$orgsocial."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_organismosexternos WHERE Organismo = '".utf8_decode($descripcion)."' AND CodOrganismo <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Organismo ya ingresado!");

		//	actualizo
		$sql = "UPDATE pf_organismosexternos
				SET
					Organismo = '".utf8_decode($descripcion)."',
					DescripComp = '".utf8_decode($descripcionc)."',
					RepresentLegal = '".utf8_decode($rep)."',
					DocRepreLeg = '".$docr."',
					Cargo = '".utf8_decode($cargo)."',
					PaginaWeb = '".$www."',
					DocFiscal = '".$docf."',
					FechaFundac = '".formatFechaAMD($fecha)."',
					Direccion = '".utf8_decode($dir)."',
					CodCiudad = '".$ciudad."',
					Telefono1 = '".$tel1."',
					Telefono2 = '".$tel1."',
					Telefono3 = '".$tel1."',
					Fax1 = '".$fax1."',
					Fax2 = '".$fax1."',
					Logo = '".$logo."',
					Estado = '".$status."',
					NumReg = '".$nreg."',
					TomoReg = '".$treg."',
					Control = '".$control."',
					FlagSocial = '".$orgsocial."',
					UltimoUsuario = '".date("Y-m-d H:i:s")."',
					UltimaFecha = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodOrganismo = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_organismosexternos WHERE CodOrganismo = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	DEPENDENCIAS EXTERNAS
elseif ($_POST['modulo'] == "DEPENDENCIAS-EXTERNAS") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_dependenciasexternas WHERE Dependencia = '".utf8_decode($descripcion)."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Dependencia ya ingresada!");

		//	inserto
		$codigo = getCodigo("pf_dependenciasexternas", "CodDependencia", 4);
		$sql = "INSERT INTO pf_dependenciasexternas (
						CodDependencia,
						Dependencia,
						CodOrganismo,
						Representante,
						Cargo,
						Telefono1,
						Telefono2,
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".$codorganismo."',
						'".utf8_decode($representante)."',
						'".utf8_decode($cargo)."',
						'".$tel1."',
						'".$tel2."',
						'".$status."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_dependenciasexternas WHERE Dependencia = '".utf8_decode($descripcion)."' AND CodDependencia <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Dependencia ya ingresada!");

		//	actualizo
		$sql = "UPDATE pf_dependenciasexternas
				SET
					Dependencia = '".utf8_decode($descripcion)."',
					CodOrganismo = '".$codorganismo."',
					Representante = '".utf8_decode($representante)."',
					Cargo = '".utf8_decode($cargo)."',
					Telefono1 = '".$tel1."',
					Telefono2 = '".$tel2."',
					Estado = '".$status."',
					UltimoUsuario = '".date("Y-m-d H:i:s")."',
					UltimaFecha = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodDependencia = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_dependenciasexternas WHERE CodDependencia = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	PROCESOS
elseif ($_POST['modulo'] == "PROCESOS") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_procesos WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Proceso ya ingresado!");

		//	inserto
		$codigo = getCodigo("pf_procesos", "CodProceso", 2);
		$sql = "INSERT INTO pf_procesos (
						CodProceso,
						Descripcion,
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".$status."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_procesos WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodProceso <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Proceso ya ingresado!");

		//	actualizo
		$sql = "UPDATE pf_procesos
				SET
					Descripcion = '".utf8_decode($descripcion)."',
					Estado = '".$status."',
					UltimaFecha = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodProceso = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_procesos WHERE CodProceso = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	FASES
elseif ($_POST['modulo'] == "FASES") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_fases WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodProceso = '".$proceso."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Fase ya ingresada!");

		//	inserto
		$codfase = getCodigo_2("pf_fases", "Codigo", "CodProceso", $proceso, 2);
		$codigo = "$proceso$codfase";
		$sql = "INSERT INTO pf_fases (
						CodProceso,
						Codigo,
						CodFase,
						Descripcion,
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$proceso."',
						'".$codfase."',
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".$status."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_fases WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodProceso = '".$proceso."' AND CodFase <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Fase ya ingresada!");

		//	actualizo
		$sql = "UPDATE pf_fases
				SET
					Descripcion = '".utf8_decode($descripcion)."',
					Estado = '".$status."',
					UltimaFecha = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodFase = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_fases WHERE CodFase = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	ACTIVIDADES
elseif ($_POST['modulo'] == "ACTIVIDADES") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_actividades WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodFase = '".$fase."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Actividad ya ingresada!");

		//	inserto
		$codactividad = getCodigo_2("pf_actividades", "Codigo", "CodFase", $fase, 2);
		$codigo = "$fase$codactividad";
		$sql = "INSERT INTO pf_actividades (
						CodFase,
						Codigo,
						CodActividad,
						Descripcion,
						Duracion,
						FlagAutoArchivo,
						FlagNoAfectoPlan,
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$fase."',
						'".$codactividad."',
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".$duracion."',
						'".$flagauto."',
						'".$flagnoafecto."',
						'".$status."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_actividades WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodFase = '".$fase."' AND CodActividad <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Actividad ya ingresada!");

		//	actualizo
		$sql = "UPDATE pf_actividades
				SET
					Descripcion = '".utf8_decode($descripcion)."',
					Duracion = '".$duracion."',
					FlagAutoArchivo = '".$flagauto."',
					FlagNoAfectoPlan = '".$flagnoafecto."',
					Estado = '".$status."',
					UltimaFecha = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodActividad = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_actividades WHERE CodActividad = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	TIPO DE ACTUACION FISCAL
elseif ($_POST['modulo'] == "TIPO-ACTUACION-FISCAL") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		$sql = "SELECT * FROM pf_tipoactuacionfiscal WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Tipo de Actuación ya ingresado!");

		//	inserto
		$codigo = getCodigo("pf_tipoactuacionfiscal", "CodTipoActuacion", 2);
		$sql = "INSERT INTO pf_tipoactuacionfiscal (
						CodTipoActuacion,
						Descripcion,
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$codigo."',
						'".utf8_decode($descripcion)."',
						'".$status."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "SELECT * FROM pf_tipoactuacionfiscal WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodTipoActuacion <> '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡Tipo de Actuación ya ingresado!");

		//	actualizo
		$sql = "UPDATE pf_tipoactuacionfiscal
				SET
					Descripcion = '".utf8_decode($descripcion)."',
					Estado = '".$status."',
					UltimaFecha = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."'
				WHERE CodTipoActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pf_tipoactuacionfiscal WHERE CodTipoActuacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	PLANIFICACION
elseif ($_POST['modulo'] == "PLANIFICACION") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		//	inserto
		$correlativo = getCodigo_3("pf_actuacionfiscal", "Secuencia", "Anio", "CodDependencia", date("Y"), $dependencia, 4);
		$secuencia = (int) $correlativo;
		$codinterno_dependencia = getCodInternoDependencia($dependencia);
		$codigo = "$codinterno_dependencia-$correlativo-".date("Y");
		
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
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$codigo."',
						'".date("Y")."',
						'".$secuencia."',
						'".$organismo."',
						'".$dependencia."',
						'".$organismoext."',
						'".$dependenciaext."',
						'".$tipo_actuacion."',
						'".$codccosto."',
						'".$proceso."',
						'".utf8_decode($objetivo_general)."',
						'".utf8_decode($alcance)."',
						'".utf8_decode($observaciones)."',
						'".formatFechaAMD($fregistro)."',
						'".formatFechaAMD($finicio)."',
						'".formatFechaAMD($ftermino)."',
						'".formatFechaAMD($ftermino)."',
						'".$duracion."',
						'".$_SESSION["CODPERSONA_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."',
						'PR',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_actuacionfiscalauditores (
							CodActuacion,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);			
			$sql = "INSERT INTO pf_actuacionfiscaldetalle (
							CodActuacion,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					CodTipoActuacion = '".$tipo_actuacion."',
					CodCentroCosto = '".$codccosto."',
					ObjetivoGeneral = '".utf8_decode($objetivo_general)."',
					Alcance = '".utf8_decode($alcance)."',
					Observacion = '".utf8_decode($observaciones)."',
					FechaInicio = '".formatFechaAMD($finicio)."',
					FechaTermino = '".formatFechaAMD($ftermino)."',
					FechaTerminoReal = '".formatFechaAMD($ftermino)."',
					Duracion = '".$duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$sql = "DELETE FROM pf_actuacionfiscalauditores WHERE CodActuacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_actuacionfiscalauditores (
							CodActuacion,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_actuacionfiscaldetalle WHERE CodActuacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);			
			$sql = "INSERT INTO pf_actuacionfiscaldetalle (
							CodActuacion,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	consulto la cantidad de detalles
		$sql = "SELECT *
				FROM pf_actuacionfiscaldetalle
				WHERE CodActuacion = '".$codigo."'";
		$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
		$rows_detalles = mysql_num_rows($query_detalles);
		
		//	consulto los detalles para cambiar el estado a ejecucion
		$listo = false;
		$secuencia = 1;
		do {
			//	consulto si afecta planificacion
			$sql = "SELECT a.FlagNoAfectoPlan
					FROM
						pf_actividades a
						INNER JOIN pf_actuacionfiscaldetalle dd ON (a.CodActividad = dd.CodActividad)
					WHERE
						dd.CodActuacion = '".$codigo."' AND
						dd.Secuencia = '".$secuencia."' AND
						a.FlagNoAfectoPlan = 'N'";
			$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulta) != 0) $listo = true;
			
			//	actualizo
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET
						Estado = 'EJ',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodActuacion = '".$codigo."' AND
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($secuencia >= $rows_detalles) $listo = true; else $secuencia++;
		} while (!$listo);
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {}
	
	//	TERMINAR ACTIVIDAD
	elseif ($_POST['accion'] == "TERMINAR") {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodActuacion = '".$actuacion."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$actuacion."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
		
		//	consulto si quedan actividades pendientes
		$sql = "SELECT Estado
				FROM pf_actuacionfiscaldetalle
				WHERE
					CodActuacion = '".$actuacion."' AND
					Estado = 'PE'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) {
			$secuencia_next = $secuencia + 1;
			$listo = false;
			do {
				//	consulto si la siguiente afecta planificacion
				$sql = "SELECT 
							afd.*,
							a.FlagNoAfectoPlan
						FROM
							pf_actuacionfiscaldetalle afd
							INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
						WHERE 
							afd.CodActuacion = '".$actuacion."' AND 
							afd.Secuencia = '".$secuencia_next."' AND 
							a.FlagNoAfectoPlan = 'N'";
				$query_consulta_flag = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_consulta_flag) != 0) {
					$field_consulta_flag = mysql_fetch_array($query_consulta_flag);
					if ($field_consulta_flag['Estado'] == "PE") {
						//	actualizo
						$sql = "UPDATE pf_actuacionfiscaldetalle
								SET
									Estado = 'EJ',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE
									CodActuacion = '".$actuacion."' AND
									Secuencia = '".$secuencia_next."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
						$listo = true;
					}
					elseif ($field_consulta_flag['Estado'] == "EJ") $listo = true;
				} else {
					//	actualizo
					$sql = "UPDATE pf_actuacionfiscaldetalle
							SET
								Estado = 'EJ',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = '".date("Y-m-d H:i:s")."'
							WHERE
								CodActuacion = '".$actuacion."' AND
								Secuencia = '".$secuencia_next."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si quedan actividades pendientes
					$sql = "SELECT Estado
							FROM pf_actuacionfiscaldetalle
							WHERE
								CodActuacion = '".$actuacion."' AND
								Estado = 'PE'";
					$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_consulta) == 0) {
						$listo = true;
						//	actualizo
						$sql = "UPDATE pf_actuacionfiscal
								SET
									Estado = 'CO',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE CodActuacion = '".$actuacion."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else $secuencia_next++;
				}
			} while(!$listo);
		} else {
			//	actualizo
			$sql = "UPDATE pf_actuacionfiscal
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE CodActuacion = '".$actuacion."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	verifico si termino
		$sql = "SELECT * FROM pf_actuacionfiscaldetalle WHERE Estado = 'EJ'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) == 0) {
			//	termino la planificacion
			$sql = "SELECT * FROM pf_actuacionfiscal WHERE Estado = 'TE'";
			$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	AUTO DE ARCHIVO DE LA ACTUACION
	elseif ($_POST['accion'] == "AUTO-ARCHIVO") {
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodActuacion = '".$actuacion."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodActuacion = '".$actuacion."' AND
					Secuencia >= '".$secuencia."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$actuacion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$actuacion."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
	}
}

//	PRORROGA
elseif ($_POST['modulo'] == "PRORROGA") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			if ($estado == "EJ") {
				$secuencia = getCodigo_2("pf_prorroga", "Secuencia", "CodActuacion", $actuacion, 2);
				$codigo = $actuacion.$secuencia;
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
								Estado,
								UltimoUsuario,
								UltimaFecha
						) VALUES (
								'".$codigo."',
								'".$secuencia."',
								'".$actuacion."',
								'".$organismo."',
								'".$actividad."',
								'".$prorroga."',
								'".utf8_decode($motivo)."',
								'".formatFechaAMD($fregistro)."',
								'".$_SESSION["CODPERSONA_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			if ($estado == "EJ") {
				$sql = "UPDATE pf_prorroga
						SET
							Prorroga = '".$prorroga."',
							Motivo = '".utf8_decode($motivo)."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_prorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodProrroga = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			$sql = "UPDATE pf_actuacionfiscaldetalle
					SET
						FechaInicioReal = '".$finicio_real."',
						FechaTerminoReal = '".$ftermino_real."',
						Prorroga = '".($acumulado+$prorroga)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodActuacion = '".$actuacion."' AND
						CodActividad = '".$actividad."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($estado == "EJ") {
				//	actualizo
				$sql = "UPDATE pf_prorroga
						SET
							Estado = 'AP',
							AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
							FechaAprobacion = '".date("Y-m-d H:i:s")."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			if ($flagnoafectoplan == "N") { 
				$total_prorroga += ($acumulado+$prorroga);
				$ftermino_real_afecto = $ftermino_real;
			}
		}
		
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Prorroga = '".$total_prorroga."',
					FechaTerminoReal = '".$ftermino_real_afecto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$actuacion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {
		/*if ($estado == "AP") { $estado_mast = "RV"; $estado_det = "PR"; }
		if ($estado == "RV") { $estado_mast = "PR"; $estado_det = "PR"; }
		if ($estado == "PR") { $estado_mast = "AN"; $estado_det = "PR"; }
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscal
				SET
					Estado = '".$estado_mast."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_actuacionfiscaldetalle
				SET
					Estado = '".$estado_det."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodActuacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		die("¡En construcción!");
	}
}

//	POTESTAD INVESTIGATIVA
elseif ($_POST['modulo'] == "POTESTAD-INVESTIGATIVA") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "GENERAR") {
		//	inserto
		$correlativo = getCodigo_3("pf_potestad", "Secuencia", "Anio", "CodDependencia", date("Y"), $dependencia, 4);
		$secuencia = (int) $correlativo;
		$codinterno_dependencia = getCodInternoDependencia($dependencia);
		$codigo = "$codinterno_dependencia-$correlativo-".date("Y");
		
		$sql = "INSERT INTO pf_potestad (
						CodActuacion,
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
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$actuacion."',
						'".$codigo."',
						'".date("Y")."',
						'".$secuencia."',
						'".$organismo."',
						'".$dependencia."',
						'".$organismoext."',
						'".$dependenciaext."',
						'".$tipo_actuacion."',
						'".$codccosto."',
						'".$proceso."',
						'".utf8_decode($objetivo_general)."',
						'".utf8_decode($alcance)."',
						'".utf8_decode($observaciones)."',
						'".formatFechaAMD($fregistro)."',
						'".formatFechaAMD($finicio)."',
						'".formatFechaAMD($ftermino)."',
						'".formatFechaAMD($ftermino)."',
						'".$duracion."',
						'".$_SESSION["CODPERSONA_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."',
						'PR',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_potestadauditores (
							CodPotestad,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);
			
			$sql = "INSERT INTO pf_potestaddetalle (
							CodPotestad,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					CodTipoActuacion = '".$tipo_actuacion."',
					CodCentroCosto = '".$codccosto."',
					ObjetivoGeneral = '".utf8_decode($objetivo_general)."',
					Alcance = '".utf8_decode($alcance)."',
					Observacion = '".utf8_decode($observaciones)."',
					FechaInicio = '".formatFechaAMD($finicio)."',
					FechaTermino = '".formatFechaAMD($ftermino)."',
					FechaTerminoReal = '".formatFechaAMD($ftermino)."',
					Duracion = '".$duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodPotestad = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$sql = "DELETE FROM pf_potestadauditores WHERE CodPotestad = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_potestadauditores (
							CodPotestad,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_potestaddetalle WHERE CodPotestad = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);			
			$sql = "INSERT INTO pf_potestaddetalle (
							CodPotestad,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodPotestad = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodPotestad = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_potestaddetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodPotestad = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	consulto la cantidad de detalles
		$sql = "SELECT *
				FROM pf_potestaddetalle
				WHERE CodPotestad = '".$codigo."'";
		$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
		$rows_detalles = mysql_num_rows($query_detalles);
		
		//	consulto los detalles para cambiar el estado a ejecucion
		$listo = false;
		$secuencia = 1;
		do {
			//	consulto si afecta planificacion
			$sql = "SELECT a.FlagNoAfectoPlan
					FROM
						pf_actividades a
						INNER JOIN pf_potestaddetalle dd ON (a.CodActividad = dd.CodActividad)
					WHERE
						dd.CodPotestad = '".$codigo."' AND
						dd.Secuencia = '".$secuencia."' AND
						a.FlagNoAfectoPlan = 'N'";
			$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulta) != 0) $listo = true;
			
			//	actualizo
			$sql = "UPDATE pf_potestaddetalle
					SET
						Estado = 'EJ',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodPotestad = '".$codigo."' AND
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($secuencia >= $rows_detalles) $listo = true; else $secuencia++;
		} while (!$listo);
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {}
	
	//	TERMINAR ACTIVIDAD
	elseif ($_POST['accion'] == "TERMINAR") {
		//	actualizo
		$sql = "UPDATE pf_potestaddetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodPotestad = '".$potestad."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$potestad."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
		
		//	consulto si quedan actividades pendientes
		$sql = "SELECT Estado
				FROM pf_potestaddetalle
				WHERE
					CodPotestad = '".$potestad."' AND
					Estado = 'PE'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		$rows_pendientes = mysql_fetch_array($query_consulta);
		if (mysql_num_rows($query_consulta) != 0) {
			$secuencia_next = $secuencia + 1;
			$listo = false;
			do {
				//	consulto si la siguiente afecta planificacion
				$sql = "SELECT 
							afd.*,
							a.FlagNoAfectoPlan
						FROM
							pf_potestaddetalle afd
							INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
						WHERE 
							afd.CodPotestad = '".$potestad."' AND 
							afd.Secuencia = '".$secuencia_next."' AND 
							a.FlagNoAfectoPlan = 'N'";
				$query_consulta_flag = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_consulta_flag) != 0) {
					$field_consulta_flag = mysql_fetch_array($query_consulta_flag);
					if ($field_consulta_flag['Estado'] == "PE") {
						//	actualizo
						$sql = "UPDATE pf_potestaddetalle
								SET
									Estado = 'EJ',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE
									CodPotestad = '".$potestad."' AND
									Secuencia = '".$secuencia_next."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
						$listo = true;
					}
					elseif ($field_consulta_flag['Estado'] == "EJ") $listo = true;
				} else {
					//	actualizo
					$sql = "UPDATE pf_potestaddetalle
							SET
								Estado = 'EJ',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = '".date("Y-m-d H:i:s")."'
							WHERE
								CodPotestad = '".$potestad."' AND
								Secuencia = '".$secuencia_next."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si quedan actividades pendientes
					$sql = "SELECT Estado
							FROM pf_potestaddetalle
							WHERE
								CodPotestad = '".$potestad."' AND
								Estado = 'PE'";
					$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_consulta) == 0) {
						$listo = true;
						//	actualizo
						$sql = "UPDATE pf_potestad
								SET
									Estado = 'CO',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE CodPotestad = '".$potestad."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else $secuencia_next++;
				}
			} while(!$listo);
		} else {
			//	actualizo
			$sql = "UPDATE pf_potestad
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE CodPotestad = '".$potestad."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	verifico si termino
		$sql = "SELECT * FROM pf_potestaddetalle WHERE Estado = 'EJ'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) == 0) {
			//	termino la planificacion
			$sql = "SELECT * FROM pf_potestad WHERE Estado = 'TE'";
			$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	AUTO DE ARCHIVO DE LA ACTUACION
	elseif ($_POST['accion'] == "AUTO-ARCHIVO") {
		//	actualizo
		$sql = "UPDATE pf_potestaddetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Documento = '".utf8_decode($documento)."',
					NroDocumento = '".utf8_decode($nrodocumento)."',
					FechaDocumento = '".formatFechaAMD($fdocumento)."',
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodPotestad = '".$potestad."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_potestaddetalle
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodPotestad = '".$potestad."' AND
					Secuencia >= '".$secuencia."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_potestad
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodPotestad = '".$potestad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$potestad."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
	}
}

//	POTESTAD PRORROGA
elseif ($_POST['modulo'] == "POTESTAD-PRORROGA") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			if ($estado == "EJ") {
				$secuencia = getCodigo_2("pf_potestadprorroga", "Secuencia", "CodPotestad", $potestad, 2);
				$codigo = $potestad.$secuencia;
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
								Estado,
								UltimoUsuario,
								UltimaFecha
						) VALUES (
								'".$codigo."',
								'".$secuencia."',
								'".$potestad."',
								'".$organismo."',
								'".$actividad."',
								'".$prorroga."',
								'".utf8_decode($motivo)."',
								'".formatFechaAMD($fregistro)."',
								'".$_SESSION["CODPERSONA_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			if ($estado == "EJ") {
				$sql = "UPDATE pf_potestadprorroga
						SET
							Prorroga = '".$prorroga."',
							Motivo = '".utf8_decode($motivo)."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_potestadprorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodProrroga = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			$sql = "UPDATE pf_potestaddetalle
					SET
						FechaInicioReal = '".$finicio_real."',
						FechaTerminoReal = '".$ftermino_real."',
						Prorroga = '".($acumulado+$prorroga)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodPotestad = '".$potestad."' AND
						CodActividad = '".$actividad."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($estado == "EJ") {
				//	actualizo
				$sql = "UPDATE pf_potestadprorroga
						SET
							Estado = 'AP',
							AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
							FechaAprobacion = '".date("Y-m-d H:i:s")."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			if ($flagnoafectoplan == "N") { 
				$total_prorroga += ($acumulado+$prorroga);
				$ftermino_real_afecto = $ftermino_real;
			}
		}
		
		$sql = "UPDATE pf_potestad
				SET
					Prorroga = '".$total_prorroga."',
					FechaTerminoReal = '".$ftermino_real_afecto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodPotestad = '".$potestad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {}
}

//	DETERMINACION RESPONSABILIDADES
elseif ($_POST['modulo'] == "DETERMINACION-RESPONSABILIDADES") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "GENERAR") {
		//	inserto
		//	inserto
		$correlativo = getCodigo_3("pf_determinacion", "Secuencia", "Anio", "CodDependencia", date("Y"), $dependencia, 4);
		$secuencia = (int) $correlativo;
		$codinterno_dependencia = getCodInternoDependencia($dependencia);
		$codigo = "$codinterno_dependencia-$correlativo-".date("Y");
		
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
						Estado,
						UltimoUsuario,
						UltimaFecha
				) VALUES (
						'".$potestad."',
						'".$codigo."',
						'".date("Y")."',
						'".$secuencia."',
						'".$organismo."',
						'".$dependencia."',
						'".$organismoext."',
						'".$dependenciaext."',
						'".$tipo_actuacion."',
						'".$codccosto."',
						'".$proceso."',
						'".utf8_decode($objetivo_general)."',
						'".utf8_decode($alcance)."',
						'".utf8_decode($observaciones)."',
						'".formatFechaAMD($fregistro)."',
						'".formatFechaAMD($finicio)."',
						'".formatFechaAMD($ftermino)."',
						'".formatFechaAMD($ftermino)."',
						'".$duracion."',
						'".$_SESSION["CODPERSONA_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."',
						'PR',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_determinacionauditores (
							CodDeterminacion,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);			
			$sql = "INSERT INTO pf_determinaciondetalle (
							CodDeterminacion,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					CodTipoActuacion = '".$tipo_actuacion."',
					CodCentroCosto = '".$codccosto."',
					ObjetivoGeneral = '".utf8_decode($objetivo_general)."',
					Alcance = '".utf8_decode($alcance)."',
					Observacion = '".utf8_decode($observaciones)."',
					FechaInicio = '".formatFechaAMD($finicio)."',
					FechaTermino = '".formatFechaAMD($ftermino)."',
					FechaTerminoReal = '".formatFechaAMD($ftermino)."',
					Duracion = '".$duracion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodDeterminacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	auditores
		$sql = "DELETE FROM pf_determinacionauditores WHERE CodDeterminacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_auditores = split(";", $detalles_auditores);	$i=0;
		foreach ($lineas_auditores as $linea) {	$i++;
			list($flag, $persona) = split("[|]", $linea);
			if ($flag == "true") $flagcoordinador = "S"; else $flagcoordinador = "N";			
			$sql = "INSERT INTO pf_determinacionauditores (
							CodDeterminacion,
							CodPersona,
							CodOrganismo,
							FlagCoordinador,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$persona."',
							'".$organismo."',
							'".$flagcoordinador."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actividades
		$sql = "DELETE FROM pf_determinaciondetalle WHERE CodDeterminacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());		
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($actividad, $duracion, $finicio, $ftermino, $prorroga, $finicioreal, $fterminoreal) = split("[|]", $linea);			
			$sql = "INSERT INTO pf_determinaciondetalle (
							CodDeterminacion,
							Secuencia,
							CodOrganismo,
							CodActividad,
							CodCentroCosto,
							FechaInicio,
							FechaTermino,
							FechaInicioReal,
							FechaTerminoReal,
							Duracion,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$codigo."',
							'".$i."',
							'".$organismo."',
							'".$actividad."',
							'".$codccosto."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".formatFechaAMD($finicio)."',
							'".formatFechaAMD($ftermino)."',
							'".$duracion."',
							'PR',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodDeterminacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodDeterminacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodDeterminacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	consulto la cantidad de detalles
		$sql = "SELECT *
				FROM pf_determinaciondetalle
				WHERE CodDeterminacion = '".$codigo."'";
		$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
		$rows_detalles = mysql_num_rows($query_detalles);
		
		//	consulto los detalles para cambiar el estado a ejecucion
		$listo = false;
		$secuencia = 1;
		do {
			//	consulto si afecta planificacion
			$sql = "SELECT a.FlagNoAfectoPlan
					FROM
						pf_actividades a
						INNER JOIN pf_determinaciondetalle dd ON (a.CodActividad = dd.CodActividad)
					WHERE
						dd.CodDeterminacion = '".$codigo."' AND
						dd.Secuencia = '".$secuencia."' AND
						a.FlagNoAfectoPlan = 'N'";
			$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulta) != 0) $listo = true;
			
			//	actualizo
			$sql = "UPDATE pf_determinaciondetalle
					SET
						Estado = 'EJ',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodDeterminacion = '".$codigo."' AND
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($secuencia >= $rows_detalles) $listo = true; else $secuencia++;
		} while (!$listo);
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {}
	
	//	TERMINAR ACTIVIDAD
	elseif ($_POST['accion'] == "TERMINAR") {
		//	actualizo
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodDeterminacion = '".$determinacion."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$determinacion."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
		
		//	consulto si quedan actividades pendientes
		$sql = "SELECT Estado
				FROM pf_determinaciondetalle
				WHERE
					CodDeterminacion = '".$determinacion."' AND
					Estado = 'PE'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) {
			$secuencia_next = $secuencia + 1;
			$listo = false;
			do {
				//	consulto si la siguiente afecta planificacion
				$sql = "SELECT 
							afd.*,
							a.FlagNoAfectoPlan
						FROM
							pf_determinaciondetalle afd
							INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
						WHERE 
							afd.CodDeterminacion = '".$determinacion."' AND 
							afd.Secuencia = '".$secuencia_next."' AND 
							a.FlagNoAfectoPlan = 'N'";
				$query_consulta_flag = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_consulta_flag) != 0) {
					$field_consulta_flag = mysql_fetch_array($query_consulta_flag);
					if ($field_consulta_flag['Estado'] == "PE") {
						//	actualizo
						$sql = "UPDATE pf_determinaciondetalle
								SET
									Estado = 'EJ',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE
									CodDeterminacion = '".$determinacion."' AND
									Secuencia = '".$secuencia_next."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
						$listo = true;
					}
					elseif ($field_consulta_flag['Estado'] == "EJ") $listo = true;
				} else {
					//	actualizo
					$sql = "UPDATE pf_determinaciondetalle
							SET
								Estado = 'EJ',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = '".date("Y-m-d H:i:s")."'
							WHERE
								CodDeterminacion = '".$determinacion."' AND
								Secuencia = '".$secuencia_next."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si quedan actividades pendientes
					$sql = "SELECT Estado
							FROM pf_determinaciondetalle
							WHERE
								CodDeterminacion = '".$determinacion."' AND
								Estado = 'PE'";
					$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_consulta) == 0) {
						$listo = true;
						//	actualizo
						$sql = "UPDATE pf_determinacion
								SET
									Estado = 'CO',
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = '".date("Y-m-d H:i:s")."'
								WHERE CodDeterminacion = '".$determinacion."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else $secuencia_next++;
				}
			} while(!$listo);
		} else {
			//	actualizo
			$sql = "UPDATE pf_determinacion
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE CodDeterminacion = '".$determinacion."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	verifico si termino
		$sql = "SELECT * FROM pf_determinaciondetalle WHERE Estado = 'EJ'";
		$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_consulta) == 0) {
			//	termino la planificacion
			$sql = "SELECT * FROM pf_determinacion WHERE Estado = 'TE'";
			$query_consulta = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	AUTO DE ARCHIVO DE LA ACTUACION
	elseif ($_POST['accion'] == "AUTO-ARCHIVO") {
		//	actualizo
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Observaciones = '".utf8_decode($observaciones)."',
					FechaRegistroCierre = '".formatFechaAMD($fregistro_cierre)."',
					FechaTerminoCierre = '".formatFechaAMD($ftermino_cierre)."',
					DiasCierre = '".$duracion_cierre."',
					Documento = '".utf8_decode($documento)."',
					NroDocumento = '".utf8_decode($nrodocumento)."',
					FechaDocumento = '".formatFechaAMD($fdocumento)."',
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodDeterminacion = '".$determinacion."' AND
					CodActividad = '".$actividad."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_determinaciondetalle
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodDeterminacion = '".$determinacion."' AND
					Secuencia >= '".$secuencia."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE pf_determinacion
				SET
					Estado = 'AU',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodDeterminacion = '".$determinacion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los documentos del cierre
		if ($detalles != "") {
			$documentos = split(";", $detalles);	$linea=0;
			foreach ($documentos as $documento_cierre) {	$linea++;
				list($documento, $nrodocumento, $fdocumento)=SPLIT( '[|]', $documento_cierre);			
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
									'".$determinacion."',
									'".$secuencia."',
									'".$linea."',
									'".$documento."',
									'".$nrodocumento."',
									'".formatFechaAMD($fdocumento)."',
									'".$_SESSION["UltimoUsuario"]."',
									'".date("Y-m-d H:i:s")."'
						)";
				$query_insert = mysql_query($sql.mysql_error());
			}
		}
	}
}

//	DETERMINACION PRORROGA
elseif ($_POST['modulo'] == "DETERMINACION-PRORROGA") {
	connect();
	//	INSERTAR
	if ($_POST['accion'] == "INSERTAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			if ($estado == "EJ") {
				$secuencia = getCodigo_2("pf_determinacionprorroga", "Secuencia", "CodDeterminacion", $determinacion, 2);
				$codigo = $determinacion.$secuencia;
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
								Estado,
								UltimoUsuario,
								UltimaFecha
						) VALUES (
								'".$codigo."',
								'".$secuencia."',
								'".$determinacion."',
								'".$organismo."',
								'".$actividad."',
								'".$prorroga."',
								'".utf8_decode($motivo)."',
								'".formatFechaAMD($fregistro)."',
								'".$_SESSION["CODPERSONA_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."',
								'PR',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ACTUALIZAR
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			if ($estado == "EJ") {
				$sql = "UPDATE pf_determinacionprorroga
						SET
							Prorroga = '".$prorroga."',
							Motivo = '".utf8_decode($motivo)."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	REVISAR
	elseif ($_POST['accion'] == "REVISAR") {
		//	actualizo
		$sql = "UPDATE pf_determinacionprorroga
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d H:i:s")."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodProrroga = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($_POST['accion'] == "APROBAR") {
		//	actividades
		$lineas_actividades = split(";", $detalles_actividades);	$i=0;
		foreach ($lineas_actividades as $linea) {	$i++;
			list($estado, $fase, $nomfase, $actividad, $nomactividad, $flagautoarchivo, $flagnoafectoplan, $duracion, $finicio, $ftermino, $acumulado, $prorroga, $finicio_real, $ftermino_real) = split("[|]", $linea);
			
			$sql = "UPDATE pf_determinaciondetalle
					SET
						FechaInicioReal = '".$finicio_real."',
						FechaTerminoReal = '".$ftermino_real."',
						Prorroga = '".($acumulado+$prorroga)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodDeterminacion = '".$determinacion."' AND
						CodActividad = '".$actividad."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($estado == "EJ") {
				//	actualizo
				$sql = "UPDATE pf_determinacionprorroga
						SET
							Estado = 'AP',
							AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
							FechaAprobacion = '".date("Y-m-d H:i:s")."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE CodProrroga = '".$codigo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			if ($flagnoafectoplan == "N") { 
				$total_prorroga += ($acumulado+$prorroga);
				$ftermino_real_afecto = $ftermino_real;
			}
		}
		
		$sql = "UPDATE pf_determinacion
				SET
					Prorroga = '".$total_prorroga."',
					FechaTerminoReal = '".$ftermino_real_afecto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE CodDeterminacion = '".$determinacion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ANULAR
	elseif ($_POST['accion'] == "ANULAR") {}
}

?>
