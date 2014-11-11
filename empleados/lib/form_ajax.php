<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	empleados
if ($modulo == "empleados") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$CodPersona = getCodigo("mastpersonas", "CodPersona", 6);
		$CodEmpleado = getCodigo("mastempleado", "CodEmpleado", 6);
		
		//	inserto persona
		$sql = "INSERT INTO mastpersonas
				SET
					CodPersona = '".$CodPersona."',
					Apellido1 = '".changeUrl($Apellido1)."',
					Apellido2 = '".changeUrl($Apellido2)."',
					Nombres = '".changeUrl($Nombres)."',
					Busqueda = '".changeUrl($Busqueda)."',
					Nacionalidad = '".$Nacionalidad."',
					NomCompleto = '".changeUrl($NomCompleto)."',
					EstadoCivil = '".$EstadoCivil."',
					Sexo = '".$Sexo."',
					Fnacimiento = '".formatFechaAMD($Fnacimiento)."',
					CiudadNacimiento = '".$CiudadNacimiento."',
					FedoCivil = '".formatFechaAMD($FedoCivil)."',
					Direccion = '".changeUrl($Direccion)."',
					Telefono1 = '".$Telefono1."',
					Telefono2 = '".$Telefono2."',
					CiudadDomicilio = '".$CiudadDomicilio."',
					Fax = '".changeUrl($Fax)."',
					Lnacimiento = '".$Lnacimiento."',
					NomEmerg1 = '".changeUrl($NomEmerg1)."',
					DirecEmerg1 = '".changeUrl($DirecEmerg1)."',
					TelefEmerg1 = '".changeUrl($TelefEmerg1)."',
					DocFiscal = '".changeUrl($DocFiscal)."',
					TipoPersona = 'N',
					Estado = '".$EdoReg."',
					Ndocumento = '".$Ndocumento."',
					EsCliente = 'N',
					CelEmerg1 = '".changeUrl($CelEmerg1)."',
					EsProveedor = 'N',
					ParentEmerg1 = '".changeUrl($ParentEmerg1)."',
					NomEmerg2 = '".changeUrl($NomEmerg2)."',
					EsEmpleado = 'S',
					EsOtros = 'N',
					DirecEmerg2 = '".changeUrl($DirecEmerg2)."',
					TelefEmerg2 = '".changeUrl($TelefEmerg2)."',
					CelEmerg2 = '".changeUrl($CelEmerg2)."',
					SituacionDomicilio = '".$SituacionDomicilio."',
					ParentEmerg2 = '".changeUrl($ParentEmerg2)."',
					TipoDocumento = '".$TipoDocumento."',
					Email = '".changeUrl($Email)."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					Observacion = '".changeUrl($Observacion)."',
					TipoLicencia = '".$TipoLicencia."',
					Nlicencia = '".changeUrl($Nlicencia)."',
					ExpiraLicencia = '".formatFechaAMD($ExpiraLicencia)."',
					SiAuto = '".$SiAuto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto empleado
		$sql = "INSERT INTO mastempleado
				SET
					CodEmpleado = '".$CodEmpleado."',
					CodPersona = '".$CodPersona."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					CodMotivoCes = '".$CodMotivoCes."',
					CodTipoPago = '".$CodTipoPago."',
					CodPerfil = '".$CodPerfil."',
					CodCargo = '".$CodCargo."',
					CodDependencia = '".$CodDependencia."',
					CodOrganismo = '".$CodOrganismo."',
					Fingreso = '".formatFechaAMD($Fingreso)."',
					SueldoActual = '".setNumero($SueldoActual)."',
					CodTipoNom = '".$CodTipoNom."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					Observacion = '".changeUrl($Observacion)."',
					Estado = '".$SitTra."',
					ObsCese = '".changeUrl($ObsCese)."',
					CodCarnetProv = '".changeUrl($CodCarnetProv)."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodHorario = '".$CodHorario."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en contratos
		$Secuencia = getCodigo_2("rh_contratos", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_contratos
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					CodOrganismo = '".$CodOrganismo."',
					FlagFirma = 'N',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion
		$Secuencia = getCodigo_2("rh_empleadonivelacion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_empleadonivelacion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Fecha = '".formatFechaAMD($Fingreso)."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCargo = '".$CodCargo."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$SitTra."',
					FechaHasta = '0000-00-00',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion historial
		$sql = "INSERT INTO rh_empleadonivelacionhistorial (
							CodPersona,
							Secuencia,
							Fecha,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							Estado,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							en.CodPersona,
							en.Secuencia,
							en.Fecha,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							en.Estado,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							rh_empleadonivelacion en
							INNER JOIN mastorganismos o ON (o.CodOrganismo = en.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = en.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = en.CodTipoNom)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = en.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
						WHERE
							en.CodPersona = '".$CodPersona."' AND
							en.Secuencia = '".$Secuencia."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en historial
		$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_historial (
							CodPersona,
							Secuencia,
							Periodo,
							Fingreso,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							TipoPago,
							Estado,
							MotivoCese,
							Fegreso,
							ObsCese,
							TipoTrabajador,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							e.CodPersona,
							'$Secuencia' AS Secuencia,
							NOW() AS Periodo,
							e.Fingreso,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							tp.TipoPago,
							e.Estado,
							mc.MotivoCese,
							e.Fegreso,
							e.ObsCese,
							tt.TipoTrabajador,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							mastempleado e
							INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
							INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
							INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
							LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
						WHERE e.CodPersona = '".$CodPersona."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo requerimientos
		if ($opcion == "contratar") {
			//	actualizo requerimiento
			$sql = "UPDATE rh_requerimiento
					SET NumeroPendiente = NumeroPendiente - 1
					WHERE
						CodOrganismo = '".$CodOrganismoReq."' AND
						Requerimiento = '".$Requerimiento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo requerimiento postulante
			$sql = "UPDATE rh_requerimientopost
					SET Estado = 'C'
					WHERE
						CodOrganismo = '".$CodOrganismoReq."' AND
						Requerimiento = '".$Requerimiento."' AND
						TipoPostulante = '".$TipoPostulante."' AND
						Postulante = '".$Postulante."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo postulante
			$sql = "UPDATE rh_postulantes SET Estado = 'C' WHERE Postulante = '".$Postulante."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$CodEmpleado|$CodPersona";
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	modifico persona
		$sql = "UPDATE mastpersonas
				SET
					Apellido1 = '".changeUrl($Apellido1)."',
					Apellido2 = '".changeUrl($Apellido2)."',
					Nombres = '".changeUrl($Nombres)."',
					Busqueda = '".changeUrl($Busqueda)."',
					Nacionalidad = '".$Nacionalidad."',
					NomCompleto = '".changeUrl($NomCompleto)."',
					EstadoCivil = '".$EstadoCivil."',
					Sexo = '".$Sexo."',
					Fnacimiento = '".formatFechaAMD($Fnacimiento)."',
					CiudadNacimiento = '".$CiudadNacimiento."',
					FedoCivil = '".formatFechaAMD($FedoCivil)."',
					Direccion = '".changeUrl($Direccion)."',
					Telefono1 = '".$Telefono1."',
					Telefono2 = '".$Telefono2."',
					CiudadDomicilio = '".$CiudadDomicilio."',
					Fax = '".changeUrl($Fax)."',
					Lnacimiento = '".$Lnacimiento."',
					NomEmerg1 = '".changeUrl($NomEmerg1)."',
					DirecEmerg1 = '".changeUrl($DirecEmerg1)."',
					TelefEmerg1 = '".changeUrl($TelefEmerg1)."',
					DocFiscal = '".changeUrl($DocFiscal)."',
					Estado = '".$EdoReg."',
					Ndocumento = '".$Ndocumento."',
					CelEmerg1 = '".changeUrl($CelEmerg1)."',
					ParentEmerg1 = '".changeUrl($ParentEmerg1)."',
					NomEmerg2 = '".changeUrl($NomEmerg2)."',
					DirecEmerg2 = '".changeUrl($DirecEmerg2)."',
					TelefEmerg2 = '".changeUrl($TelefEmerg2)."',
					CelEmerg2 = '".changeUrl($CelEmerg2)."',
					SituacionDomicilio = '".$SituacionDomicilio."',
					ParentEmerg2 = '".changeUrl($ParentEmerg2)."',
					TipoDocumento = '".$TipoDocumento."',
					Email = '".changeUrl($Email)."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					Observacion = '".changeUrl($Observacion)."',
					TipoLicencia = '".$TipoLicencia."',
					Nlicencia = '".changeUrl($Nlicencia)."',
					ExpiraLicencia = '".formatFechaAMD($ExpiraLicencia)."',
					SiAuto = '".$SiAuto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPersona = '".$CodPersona."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico empleado
		$sql = "UPDATE mastempleado
				SET
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					CodMotivoCes = '".$CodMotivoCes."',
					CodTipoPago = '".$CodTipoPago."',
					CodPerfil = '".$CodPerfil."',
					Fingreso = '".formatFechaAMD($Fingreso)."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					Observacion = '".changeUrl($Observacion)."',
					Estado = '".$SitTra."',
					ObsCese = '".changeUrl($ObsCese)."',
					CodCarnetProv = '".changeUrl($CodCarnetProv)."',
					CodCentroCosto = '".$CodCentroCosto."',
					CodHorario = '".$CodHorario."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodEmpleado = '".$CodEmpleado."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en historial
		if ($SitTra != $HSitTra || $CodTipoPago != $HCodTipoPago || $CodTipoTrabajador != $HCodTipoTrabajador) {
			$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
			$Secuencia = intval($Secuencia);
			$sql = "INSERT INTO rh_historial (
								CodPersona,
								Secuencia,
								Periodo,
								Fingreso,
								Organismo,
								Dependencia,
								Cargo,
								NivelSalarial,
								CategoriaCargo,
								TipoNomina,
								TipoPago,
								Estado,
								MotivoCese,
								Fegreso,
								ObsCese,
								TipoTrabajador,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								e.CodPersona,
								'$Secuencia' AS Secuencia,
								NOW() AS Periodo,
								e.Fingreso,
								o.Organismo,
								d.Dependencia,
								pt.DescripCargo AS Cargo,
								pt.NivelSalarial,
								md.Descripcion AS CategoriaCargo,
								tn.Nomina AS TipoNomina,
								tp.TipoPago,
								e.Estado,
								mc.MotivoCese,
								e.Fegreso,
								e.ObsCese,
								tt.TipoTrabajador,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM
								mastempleado e
								INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
								INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
								INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
								INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
								INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
								INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
								LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																	md.CodMaestro = 'CATCARGO')
								LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
							WHERE e.CodPersona = '".$CodPersona."'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	--------------------
		mysql_query("COMMIT");
		echo "|$CodEmpleado|$CodPersona";
	}
}

//	empleados (nivelaciones)
elseif ($modulo == "empleados_nivelaciones") {
	mysql_query("BEGIN");
	//	--------------------
	//	valido
	if ($opcion == "contratar") {
		$sql = "SELECT NumeroPendiente
				FROM rh_requerimiento
				WHERE
					CodOrganismo = '".$CodOrganismoReq."' AND
					Requerimiento = '".$Requerimiento."' AND
					NumeroPendiente = 0";
		$query_valido = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_valido) != 0) die("No existen Vacantes Pendientes para este Requerimiento");
	}
	//	--------------------
	//	obtengo el dia anterior de la fecha de nivelacion
	$sql = "SELECT DATE_ADD('".formatFechaAMD($Fecha)."', INTERVAL -1 DAY) AS Fecha";
	$query_fecha = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_fecha) != 0) $field_fecha = mysql_fetch_array($query_fecha);
	
	//	actualizo nivelaciones anteriores y empleado
	if ($TipoAccion == "ET") {
		//	actualizo nivelacion
		$sql = "UPDATE rh_empleadonivelacion
				SET FechaHasta = '".$field_fecha['Fecha']."'
				WHERE
					CodPersona = '".$CodPersona."' AND
					TipoAccion = 'ET' AND
					FechaHasta = '0000-00-00'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo empleado
		if ($CodDependenciaAnt != $CodDependencia) $CodDependenciaTemp = $CodDependencia;
		if ($CodCargoAnt != $CodCargo) $CodCargoTemp = $CodCargo;
		$sql = "UPDATE mastempleado
				SET
					CodDependenciaTemp = '".$CodDependenciaTemp."',
					CodCargoTemp = '".$CodCargoTemp."'
				WHERE CodPersona = '".$CodPersona."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	} else {
		//	actualizo nivelacion
		$sql = "UPDATE rh_empleadonivelacion
				SET FechaHasta = '".$field_fecha['Fecha']."'
				WHERE
					CodPersona = '".$CodPersona."' AND
					FechaHasta = '0000-00-00'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo empleado 
		$sql = "UPDATE mastempleado
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCargo = '".$CodCargo."',
					CodTipoNom = '".$CodTipoNom."',
					SueldoActual = '".setNumero($SueldoActual)."'
				WHERE CodPersona = '".$CodPersona."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
	}
	
	//	inserto en nivelacion
	$Secuencia = getCodigo_2("rh_empleadonivelacion", "Secuencia", "CodPersona", $CodPersona, 6);
	$Secuencia = intval($Secuencia);
	$sql = "INSERT INTO rh_empleadonivelacion
			SET
				CodPersona = '".$CodPersona."',
				Secuencia = '".$Secuencia."',
				Fecha = '".formatFechaAMD($Fecha)."',
				TipoAccion = '".$TipoAccion."',
				Responsable = '".$Responsable."',
				Documento = '".changeUrl($Documento)."',
				Motivo = '".changeUrl($Motivo)."',
				CodOrganismo = '".$CodOrganismo."',
				CodDependencia = '".$CodDependencia."',
				CodCargo = '".$CodCargo."',
				CodTipoNom = '".$CodTipoNom."',
				Estado = '".$Estado."',
				FechaHasta = '0000-00-00',
				UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
				UltimaFecha = NOW()";
	$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	inserto en nivelacion historial
	$SecuenciaH = getCodigo_2("rh_empleadonivelacionhistorial", "Secuencia", "CodPersona", $CodPersona, 6);
	$SecuenciaH = intval($SecuenciaH);
	$sql = "INSERT INTO rh_empleadonivelacionhistorial (
						CodPersona,
						Secuencia,
						Fecha,
						Organismo,
						Dependencia,
						Cargo,
						NivelSalarial,
						CategoriaCargo,
						TipoNomina,
						Estado,
						TipoAccion,
						UltimoUsuario,
						UltimaFecha
			)
					SELECT
						en.CodPersona,
						'$SecuenciaH' AS Secuencia,
						en.Fecha,
						o.Organismo,
						d.Dependencia,
						pt.DescripCargo AS Cargo,
						pt.NivelSalarial,
						md.Descripcion AS CategoriaCargo,
						tn.Nomina AS TipoNomina,
						en.Estado,
						md2.Descripcion AS TipoAccion,
						'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
						NOW() AS UltimaFecha
					FROM
						rh_empleadonivelacion en
						INNER JOIN mastorganismos o ON (o.CodOrganismo = en.CodOrganismo)
						INNER JOIN mastdependencias d ON (d.CodDependencia = en.CodDependencia)
						INNER JOIN tiponomina tn ON (tn.CodTipoNom = en.CodTipoNom)
						INNER JOIN rh_puestos pt ON (pt.CodCargo = en.CodCargo)
						LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
															md.CodMaestro = 'CATCARGO')
						LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = en.TipoAccion AND
															 md2.CodMaestro = 'TIPOACCION')
					WHERE
						en.CodPersona = '".$CodPersona."' AND
						en.Secuencia = '".$Secuencia."'";
	$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	inserto en historial
	$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
	$Secuencia = intval($Secuencia);
	$sql = "INSERT INTO rh_historial (
						CodPersona,
						Secuencia,
						Periodo,
						Fingreso,
						Organismo,
						Dependencia,
						Cargo,
						NivelSalarial,
						CategoriaCargo,
						TipoNomina,
						TipoPago,
						Estado,
						MotivoCese,
						Fegreso,
						ObsCese,
						TipoTrabajador,
						UltimoUsuario,
						UltimaFecha
			)
					SELECT
						e.CodPersona,
						'$Secuencia' AS Secuencia,
						NOW() AS Periodo,
						e.Fingreso,
						o.Organismo,
						d.Dependencia,
						pt.DescripCargo AS Cargo,
						pt.NivelSalarial,
						md.Descripcion AS CategoriaCargo,
						tn.Nomina AS TipoNomina,
						tp.TipoPago,
						e.Estado,
						mc.MotivoCese,
						e.Fegreso,
						e.ObsCese,
						tt.TipoTrabajador,
						'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
						NOW() AS UltimaFecha
					FROM
						mastempleado e
						INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
						INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
						INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
						INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
						INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
						INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
						LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
															md.CodMaestro = 'CATCARGO')
						LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
					WHERE e.CodPersona = '".$CodPersona."'";
	$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	actualizo requerimientos
	if ($opcion == "contratar") {
		//	actualizo requerimiento
		$sql = "UPDATE rh_requerimiento
				SET NumeroPendiente = NumeroPendiente - 1
				WHERE
					CodOrganismo = '".$CodOrganismoReq."' AND
					Requerimiento = '".$Requerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo requerimiento postulante
		$sql = "UPDATE rh_requerimientopost
				SET Estado = 'C'
				WHERE
					CodOrganismo = '".$CodOrganismoReq."' AND
					Requerimiento = '".$Requerimiento."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	//	--------------------
	mysql_query("COMMIT");
}

//	empleados (vacaciones)
elseif ($modulo == "empleado_vacaciones") {
	//	nuevo registro
	if ($accion== "actualizar") {
		mysql_query("BEGIN");
		//	--------------------
		//	consulto empleado
		$sql = "SELECT pt.Grado, e.CodTipoNom
				FROM
					mastempleado e
					INNER JOIN rh_puestos pt ON (pt.CodCargo = pt.CodCargo)
				WHERE e.CodPersona = '".$CodPersona."'";
		$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
		
		//	elimino antes de insertar
		$sql = "DELETE FROM rh_vacacionperiodo WHERE CodPersona = '".$CodPersona."' AND CodTipoNom = '".$field_empleado['CodTipoNom']."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	actualizo periodos vacacionales
		$periodos = split(";char:tr;", $detalles_periodos);
		foreach ($periodos as $linea) {
			list($_NroPeriodo, $_Anio, $_Mes, $_Derecho, $_PendientePeriodo, $_DiasGozados, $_DiasTrabajados, $_DiasInterrumpidos, $_TotalUtilizados, $_Pendientes, $_PagosRealizados, $_PendientePago) = split(";char:td;", $linea);
			
			//	actualizo
			$sql = "INSERT INTO rh_vacacionperiodo
					SET
						CodPersona = '".$CodPersona."',
						NroPeriodo = '".$_NroPeriodo."',
						CodTipoNom = '".$field_empleado['CodTipoNom']."',
						Anio = '".$_Anio."',
						Mes = '".$_Mes."',
						Derecho = '".$_Derecho."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto utilizacion
		if ($NroPeriodo_utilizacion != "") {
			//	elimino antes de insertar
			$sql = "DELETE FROM rh_vacacionutilizacion
					WHERE
						CodPersona = '".$CodPersona."' AND
						NroPeriodo = '".$NroPeriodo_utilizacion."' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		if ($detalles_utilizacion != "") {
			$utilizacion = split(";char:tr;", $detalles_utilizacion);
			foreach ($utilizacion as $linea) {
				list($_NroPeriodo, $_TipoUtilizacion, $_DiasUtiles, $_FechaInicio, $_FechaFin) = split(";char:td;", $linea);
				$_Secuencia = getCodigo_3("rh_vacacionutilizacion", "Secuencia", "CodPersona", "NroPeriodo", $CodPersona, $_NroPeriodo, 2);
				$_Secuencia = intval($_Secuencia);
				//	inserto
				$sql = "INSERT INTO rh_vacacionutilizacion
						SET
							CodPersona = '".$CodPersona."',
							NroPeriodo = '".$_NroPeriodo."',
							CodTipoNom = '".$field_empleado['CodTipoNom']."',
							Secuencia = '".$_Secuencia."',
							TipoUtilizacion = '".$_TipoUtilizacion."',
							FechaInicio = '".$_FechaInicio."',
							FechaFin = '".$_FechaFin."',
							DiasUtiles = '".$_DiasUtiles."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	actualizo periodos vacacionales
		$sql = "SELECT *
				FROM rh_vacacionperiodo
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodTipoNom = '".$field_empleado['CodTipoNom']."'
				ORDER BY NroPeriodo";
		$query_periodos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field_periodos = mysql_fetch_array($query_periodos)) {
			//	consulto pendientes anteriores
			$sql = "SELECT Pendientes
					FROM rh_vacacionperiodo
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".($field_periodos['NroPeriodo']-1)."' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_pendientes_ant = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_pendientes_ant) != 0) $field_pendientes_ant = mysql_fetch_array($query_pendientes_ant);
			//	consulto gozados
			$sql = "SELECT SUM(DiasUtiles) AS DiasGozados
					FROM rh_vacacionutilizacion
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
						TipoUtilizacion = 'G' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_gozados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_gozados) != 0) $field_gozados = mysql_fetch_array($query_gozados);
			//	consulto interrumpidos
			$sql = "SELECT SUM(DiasUtiles) AS DiasInterrumpidos
					FROM rh_vacacionutilizacion
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
						TipoUtilizacion = 'I' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_interrumpidos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_interrumpidos) != 0) $field_interrumpidos = mysql_fetch_array($query_interrumpidos);
			//	consulto pendientes anteriores
			$sql = "SELECT PendientePago
					FROM rh_vacacionperiodo
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".($field_periodos['NroPeriodo']-1)."' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_pendientes_pago_ant = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_pendientes_pago_ant) != 0) $field_pendientes_pago_ant = mysql_fetch_array($query_pendientes_pago_ant);
			//	consulto pagados
			$sql = "SELECT SUM(DiasPago) AS PagosRealizados
					FROM rh_vacacionpago
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_pagados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_pagados) != 0) $field_pagados = mysql_fetch_array($query_pagados);
			##	calculo dias
			$Pendientes = $field_periodos['Derecho'] + $field_pendientes_ant['Pendientes'] - $field_gozados['DiasGozados'] + $field_interrumpidos['DiasInterrumpidos'];
			if ($field_empleado['Grado'] == "99") $DiasPago = $_PARAMETRO["PAGOFINDC"]; else $DiasPago = $_PARAMETRO["PAGOVACA"];
			$PendientePago = $DiasPago + $field_pendientes_pago_ant['PendientePago'] - $field_pagados['PagosRealizados'];
			//	actualizo
			$sql = "UPDATE rh_vacacionperiodo
					SET
						PendientePeriodo = '".$field_pendientes_ant['Pendientes']."',
						DiasGozados = '".$field_gozados['DiasGozados']."',
						DiasInterrumpidos = '".$field_interrumpidos['DiasInterrumpidos']."',
						TotalUtilizados = '".$field_gozados['DiasGozados']."',
						Pendientes = '".$Pendientes."',
						PagosRealizados = '".$field_pagados['PagosRealizados']."',
						PendientePago = '".$PendientePago."'
					WHERE
						CodPersona = '".$field_periodos['CodPersona']."' AND
						NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
						CodTipoNom = '".$field_empleado['CodTipoNom']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (carga familiar)
elseif ($modulo == "empleados_carga_familiar") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$CodSecuencia = getCodigo_2("rh_cargafamiliar", "CodSecuencia", "CodPersona", $CodPersona, 4);
		$CodSecuencia = intval($CodSecuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_cargafamiliar
				SET
					CodSecuencia = '".$CodSecuencia."',
					CodPersona = '".$CodPersona."',
					Parentesco = '".$Parentesco."',
					ApellidosCarga = '".changeUrl($ApellidosCarga)."',
					NombresCarga = '".changeUrl($NombresCarga)."',
					DireccionFam = '".changeUrl($DireccionFam)."',
					FechaNacimiento = '".formatFechaAMD($FechaNacimiento)."',
					TipoDocumento = '".$TipoDocumento."',
					Ndocumento = '".changeUrl($Ndocumento)."',
					Telefono = '".changeUrl($Telefono)."',
					Celular = '".changeUrl($Celular)."',
					Sexo = '".$Sexo."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					Afiliado = '".$Afiliado."',
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Estado = '".$Estado."',
					MotivoBaja = '".$MotivoBaja."',
					FechaBaja = '".formatFechaAMD($FechaBaja)."',
					TipoEducacion = '".$TipoEducacion."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FlagTrabaja = '".$FlagTrabaja."',
					Empresa = '".changeUrl($Empresa)."',
					DireccionEmpresa = '".changeUrl($DireccionEmpresa)."',
					TiempoServicio = '".setNumero($TiempoServicio)."',
					SueldoMensual = '".setNumero($SueldoMensual)."',
					Comentarios = '".changeUrl($Comentarios)."',
					FlagEstudia = '".$FlagEstudia."',
					EstadoCivil = '".$EstadoCivil."',
					FlagDiscapacidad = '".$FlagDiscapacidad."',
					Foto='".$numAleatorioFoto.$CodPersona.".jpg',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_cargafamiliar
				SET
					Parentesco = '".$Parentesco."',
					ApellidosCarga = '".changeUrl($ApellidosCarga)."',
					NombresCarga = '".changeUrl($NombresCarga)."',
					DireccionFam = '".changeUrl($DireccionFam)."',
					FechaNacimiento = '".formatFechaAMD($FechaNacimiento)."',
					TipoDocumento = '".$TipoDocumento."',
					Ndocumento = '".changeUrl($Ndocumento)."',
					Telefono = '".changeUrl($Telefono)."',
					Celular = '".changeUrl($Celular)."',
					Sexo = '".$Sexo."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					Afiliado = '".$Afiliado."',
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Estado = '".$Estado."',
					MotivoBaja = '".$MotivoBaja."',
					FechaBaja = '".formatFechaAMD($FechaBaja)."',
					TipoEducacion = '".$TipoEducacion."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FlagTrabaja = '".$FlagTrabaja."',
					Empresa = '".changeUrl($Empresa)."',
					DireccionEmpresa = '".changeUrl($DireccionEmpresa)."',
					TiempoServicio = '".setNumero($TiempoServicio)."',
					SueldoMensual = '".setNumero($SueldoMensual)."',
					Comentarios = '".changeUrl($Comentarios)."',
					FlagEstudia = '".$FlagEstudia."',
					EstadoCivil = '".$EstadoCivil."',
					FlagDiscapacidad = '".$FlagDiscapacidad."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodSecuencia = '".$CodSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $CodSecuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_cargafamiliar
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodSecuencia = '".$CodSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (instruccion - carreras)
elseif ($modulo == "empleados_instruccion_carreras") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_empleado_instruccion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_instruccion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Area = '".$Area."',
					CodProfesion = '".$CodProfesion."',
					Nivel = '".$Nivel."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Colegiatura = '".$Colegiatura."',
					NroColegiatura = '".$NroColegiatura."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaGraduacion = '".formatFechaAMD($FechaGraduacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_instruccion
				SET
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Area = '".$Area."',
					CodProfesion = '".$CodProfesion."',
					Nivel = '".$Nivel."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Colegiatura = '".$Colegiatura."',
					NroColegiatura = '".$NroColegiatura."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaGraduacion = '".formatFechaAMD($FechaGraduacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_instruccion
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (instruccion - idiomas)
elseif ($modulo == "empleados_instruccion_idiomas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	inserto
		$sql = "INSERT INTO rh_empleado_idioma
				SET
					CodPersona = '".$CodPersona."',
					CodIdioma = '".$CodIdioma."',
					NivelLectura = '".$NivelLectura."',
					NivelOral = '".$NivelOral."',
					NivelEscritura = '".$NivelEscritura."',
					NivelGeneral = '".$NivelGeneral."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_idioma
				SET
					NivelLectura = '".$NivelLectura."',
					NivelOral = '".$NivelOral."',
					NivelEscritura = '".$NivelEscritura."',
					NivelGeneral = '".$NivelGeneral."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodIdioma = '".$CodIdioma."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $CodIdioma) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_idioma
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodIdioma = '".$CodIdioma."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (instruccion - cursos)
elseif ($modulo == "empleados_instruccion_cursos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_empleado_cursos", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_cursos
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					CodCurso = '".$CodCurso."',
					TipoCurso = '".$TipoCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					FechaCulminacion = '".$FechaCulminacion."',
					TotalHoras = '".$TotalHoras."',
					AniosVigencia = '".$AniosVigencia."',
					Observaciones = '".changeUrl($Observaciones)."',
					FlagInstitucional = '".$FlagInstitucional."',
					FlagPago = '".$FlagPago."',
					FlagArea = '".$FlagArea."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_cursos
				SET
					CodCurso = '".$CodCurso."',
					TipoCurso = '".$TipoCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					FechaCulminacion = '".$FechaCulminacion."',
					TotalHoras = '".$TotalHoras."',
					AniosVigencia = '".$AniosVigencia."',
					Observaciones = '".changeUrl($Observaciones)."',
					FlagInstitucional = '".$FlagInstitucional."',
					FlagPago = '".$FlagPago."',
					FlagArea = '".$FlagArea."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_cursos
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (informacion bancaria)
elseif ($modulo == "empleados_bancaria") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	valido lo de cuenta principal
		if (ctaPrincipal($CodPersona)) die("El empleado ya tiene una cuenta principal");
		
		//	genero codigo
		$CodSecuencia = getCodigo_2("bancopersona", "CodSecuencia", "CodPersona", $CodPersona, 6);
		
		//	inserto
		$sql = "INSERT INTO bancopersona
				SET
					CodPersona = '".$CodPersona."',
					CodSecuencia = '".$CodSecuencia."',
					CodBanco = '".$CodBanco."',
					TipoCuenta = '".$TipoCuenta."',
					Aportes = '".$Aportes."',
					Ncuenta = '".changeUrl($Ncuenta)."',
					Monto = '".setNumero($Monto)."',
					FlagPrincipal = '".$FlagPrincipal."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	valido lo de cuenta principal
		if (ctaPrincipal($CodPersona, $CodSecuencia)) die("El empleado ya tiene una cuenta principal");
		
		//	actualizo
		$sql = "UPDATE bancopersona
				SET
					CodBanco = '".$CodBanco."',
					TipoCuenta = '".$TipoCuenta."',
					Aportes = '".$Aportes."',
					Ncuenta = '".changeUrl($Ncuenta)."',
					Monto = '".setNumero($Monto)."',
					FlagPrincipal = '".$FlagPrincipal."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodSecuencia = '".$CodSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $CodSecuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM bancopersona
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodSecuencia = '".$CodSecuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (islr)
elseif ($modulo == "empleados_islr") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	inserto
		$sql = "INSERT INTO pr_impuestorenta
				SET
					CodPersona = '".$CodPersona."',
					Anio = '".$Anio."',
					Desde = '".$Desde."',
					Hasta = '".$Hasta."',
					Porcentaje = '".setNumero($Porcentaje)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	valido lo de cuenta principal
		if (ctaPrincipal($CodPersona, $CodSecuencia)) die("El empleado ya tiene una cuenta principal");
		
		//	actualizo
		$sql = "UPDATE pr_impuestorenta
				SET
					Desde = '".$Desde."',
					Hasta = '".$Hasta."',
					Porcentaje = '".setNumero($Porcentaje)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Anio = '".$Anio."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Anio) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM pr_impuestorenta
				WHERE
					CodPersona = '".$CodPersona."' AND
					Anio = '".$Anio."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (experiencia laboral)
elseif ($modulo == "empleados_experiencia_laboral") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_empleado_experiencia", "Secuencia", "CodPersona", $CodPersona, 6);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_experiencia
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Empresa = '".changeUrl($Empresa)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					MotivoCese = '".$MotivoCese."',
					Sueldo = '".setNumero($Sueldo)."',
					AreaExperiencia = '".$AreaExperiencia."',
					TipoEnte = '".$TipoEnte."',
					CargoOcupado = '".changeUrl($CargoOcupado)."',
					Funciones = '".changeUrl($Funciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	valido lo de cuenta principal
		if (ctaPrincipal($CodPersona, $CodSecuencia)) die("El empleado ya tiene una cuenta principal");
		
		//	actualizo
		$sql = "UPDATE rh_empleado_experiencia
				SET
					Empresa = '".changeUrl($Empresa)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					MotivoCese = '".$MotivoCese."',
					Sueldo = '".setNumero($Sueldo)."',
					AreaExperiencia = '".$AreaExperiencia."',
					TipoEnte = '".$TipoEnte."',
					CargoOcupado = '".changeUrl($CargoOcupado)."',
					Funciones = '".changeUrl($Funciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (referencias - laborales)
elseif ($modulo == "empleados_referencias_laborales") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_empleado_referencias", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_referencias
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Nombre = '".changeUrl($Nombre)."',
					Empresa = '".changeUrl($Empresa)."',
					Direccion = '".changeUrl($Direccion)."',
					Cargo = '".changeUrl($Cargo)."',
					Telefono = '".$Telefono."',
					Tipo = '".$Tipo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_referencias
				SET
					Nombre = '".changeUrl($Nombre)."',
					Empresa = '".changeUrl($Empresa)."',
					Direccion = '".changeUrl($Direccion)."',
					Cargo = '".changeUrl($Cargo)."',
					Telefono = '".$Telefono."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_referencias
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (referencias - personales)
elseif ($modulo == "empleados_referencias_personales") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_empleado_referencias", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_referencias
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Nombre = '".changeUrl($Nombre)."',
					Empresa = '".changeUrl($Empresa)."',
					Direccion = '".changeUrl($Direccion)."',
					Telefono = '".$Telefono."',
					Tipo = '".$Tipo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_referencias
				SET
					Nombre = '".changeUrl($Nombre)."',
					Empresa = '".changeUrl($Empresa)."',
					Direccion = '".changeUrl($Direccion)."',
					Telefono = '".$Telefono."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_referencias
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (meritos)
elseif ($modulo == "empleados_meritos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_meritosfaltas", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_meritosfaltas
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Documento = '".changeUrl($Documento)."',
					FechaDoc = '".formatFechaAMD($FechaDoc)."',
					Observacion = '".changeUrl($Observacion)."',
					Clasificacion = '".$Clasificacion."',
					Tipo = '".$Tipo."',
					Responsable = '".$Responsable."',
					FlagExterno = '".$FlagExterno."',
					Externo = '".changeUrl($Externo)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_meritosfaltas
				SET
					Documento = '".changeUrl($Documento)."',
					FechaDoc = '".formatFechaAMD($FechaDoc)."',
					Observacion = '".changeUrl($Observacion)."',
					Clasificacion = '".$Clasificacion."',
					Responsable = '".$Responsable."',
					FlagExterno = '".$FlagExterno."',
					Externo = '".changeUrl($Externo)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_meritosfaltas
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (demeritos)
elseif ($modulo == "empleados_demeritos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_meritosfaltas", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_meritosfaltas
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Documento = '".changeUrl($Documento)."',
					FechaDoc = '".formatFechaAMD($FechaDoc)."',
					FechaIni = '".formatFechaAMD($FechaIni)."',
					FechaFin = '".formatFechaAMD($FechaFin)."',
					Observacion = '".changeUrl($Observacion)."',
					Clasificacion = '".$Clasificacion."',
					Tipo = '".$Tipo."',
					Responsable = '".$Responsable."',
					FlagExterno = '".$FlagExterno."',
					Externo = '".changeUrl($Externo)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_meritosfaltas
				SET
					Documento = '".changeUrl($Documento)."',
					FechaDoc = '".formatFechaAMD($FechaDoc)."',
					FechaIni = '".formatFechaAMD($FechaIni)."',
					FechaFin = '".formatFechaAMD($FechaFin)."',
					Observacion = '".changeUrl($Observacion)."',
					Clasificacion = '".$Clasificacion."',
					Responsable = '".$Responsable."',
					FlagExterno = '".$FlagExterno."',
					Externo = '".changeUrl($Externo)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_meritosfaltas
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (patrimonio - inmuebles)
elseif ($modulo == "empleados_patrimonio_inmuebles") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_patrimonio_inmueble", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_patrimonio_inmueble
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Descripcion = '".changeUrl($Descripcion)."',
					Ubicacion = '".changeUrl($Ubicacion)."',
					Uso = '".changeUrl($Uso)."',
					Valor = '".setNumero($Valor)."',
					FlagHipotecado = '".$FlagHipotecado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_patrimonio_inmueble
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Ubicacion = '".changeUrl($Ubicacion)."',
					Uso = '".changeUrl($Uso)."',
					Valor = '".setNumero($Valor)."',
					FlagHipotecado = '".$FlagHipotecado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_patrimonio_inmueble
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (patrimonio - inversiones)
elseif ($modulo == "empleados_patrimonio_inversiones") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_patrimonio_inversion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_patrimonio_inversion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Titular = '".changeUrl($Titular)."',
					EmpresaRemitente = '".changeUrl($EmpresaRemitente)."',
					NroCertificado = '".changeUrl($NroCertificado)."',
					Cantidad = '".setNumero($Cantidad)."',
					ValorNominal = '".setNumero($ValorNominal)."',
					Valor = '".setNumero($Valor)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_patrimonio_inversion
				SET
					Titular = '".changeUrl($Titular)."',
					EmpresaRemitente = '".changeUrl($EmpresaRemitente)."',
					NroCertificado = '".changeUrl($NroCertificado)."',
					Cantidad = '".setNumero($Cantidad)."',
					ValorNominal = '".setNumero($ValorNominal)."',
					Valor = '".setNumero($Valor)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_patrimonio_inversion
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (patrimonio - vehiculos)
elseif ($modulo == "empleados_patrimonio_vehiculos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_patrimonio_vehiculo", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_patrimonio_vehiculo
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Marca = '".changeUrl($Marca)."',
					Modelo = '".changeUrl($Modelo)."',
					Color = '".changeUrl($Color)."',
					Placa = '".changeUrl($Placa)."',
					Anio = '".setNumero($Anio)."',
					Valor = '".setNumero($Valor)."',
					FlagPrendado = '".$FlagPrendado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_patrimonio_vehiculo
				SET
					Marca = '".changeUrl($Marca)."',
					Modelo = '".changeUrl($Modelo)."',
					Color = '".changeUrl($Color)."',
					Placa = '".changeUrl($Placa)."',
					Anio = '".setNumero($Anio)."',
					Valor = '".setNumero($Valor)."',
					FlagPrendado = '".$FlagPrendado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_patrimonio_vehiculo
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (patrimonio - cuentas)
elseif ($modulo == "empleados_patrimonio_cuentas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_patrimonio_cuenta", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_patrimonio_cuenta
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					TipoCuenta = '".changeUrl($TipoCuenta)."',
					NroCuenta = '".changeUrl($NroCuenta)."',
					Institucion = '".changeUrl($Institucion)."',
					Valor = '".setNumero($Valor)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_patrimonio_cuenta
				SET
					TipoCuenta = '".changeUrl($TipoCuenta)."',
					NroCuenta = '".changeUrl($NroCuenta)."',
					Institucion = '".changeUrl($Institucion)."',
					Valor = '".setNumero($Valor)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_patrimonio_cuenta
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (patrimonio - otros)
elseif ($modulo == "empleados_patrimonio_otros") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_2("rh_patrimonio_otro", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_patrimonio_otro
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Descripcion = '".changeUrl($Descripcion)."',
					Valor = '".setNumero($Valor)."',
					ValorCompra = '".setNumero($ValorCompra)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_patrimonio_otro
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Valor = '".setNumero($Valor)."',
					ValorCompra = '".setNumero($ValorCompra)."',
					FlagGarantia = '".$FlagGarantia."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_patrimonio_otro
				WHERE
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	total
		list($TotalInmuebles, $TotalInversiones, $TotalVehiculos, $TotalCuentas, $TotalOtros, $Total) = totalPatrimonio($CodPersona);
		echo "|".number_format($TotalInmuebles, 2, ',', '.')."|".number_format($TotalInversiones, 2, ',', '.')."|".number_format($TotalVehiculos, 2, ',', '.')."|".number_format($TotalCuentas, 2, ',', '.')."|".number_format($TotalOtros, 2, ',', '.')."|".number_format($Total, 2, ',', '.');
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (documentos - entregados)
elseif ($modulo == "empleados_documentos_entregados") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$CodDocumento = getCodigo_2("rh_empleado_documentos", "CodDocumento", "CodPersona", $CodPersona, 2);
		
		//	inserto
		$sql = "INSERT INTO rh_empleado_documentos
				SET
					CodPersona = '".$CodPersona."',
					CodDocumento = '".$CodDocumento."',
					FlagPresento = '".$FlagPresento."',
					FlagCarga = '".$FlagCarga."',
					Documento = '".$Documento."',
					CargaFamiliar = '".$CargaFamiliar."',
					FechaPresento = '".formatFechaAMD($FechaPresento)."',
					FechaVence = '".formatFechaAMD($FechaVence)."',
					Observaciones = '".changeUrl($Observaciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$CodDocumento";
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_empleado_documentos
				SET
					FlagPresento = '".$FlagPresento."',
					FlagCarga = '".$FlagCarga."',
					Documento = '".$Documento."',
					CargaFamiliar = '".$CargaFamiliar."',
					FechaPresento = '".formatFechaAMD($FechaPresento)."',
					FechaVence = '".formatFechaAMD($FechaVence)."',
					Observaciones = '".changeUrl($Observaciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodDocumento = '".$CodDocumento."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$CodDocumento";
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $CodDocumento) = split("[_]", $registro);
		
		//	consulto ruta de la imagen para eliminarla
		$sql = "SELECT Ruta
				FROM rh_empleado_documentos
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodDocumento = '".$CodDocumento."'";
		$query_ruta = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_ruta) != 0) $field_ruta = mysql_fetch_array($query_ruta);
		if ($field_ruta['Ruta'] != "") unlink("../".$_PARAMETRO["PATHIMGDOC"].$field_ruta['Ruta']);
	
		//	actualizo
		$sql = "DELETE FROM rh_empleado_documentos
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodDocumento = '".$CodDocumento."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

//	empleados (documentos - movimientos)
elseif ($modulo == "empleados_documentos_movimientos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	--------------------
		//	genero codigo
		$Secuencia = getCodigo_3("rh_documentos_historia", "Secuencia", "CodPersona", "CodDocumento", $CodPersona, $CodDocumento, 3);
		
		//	inserto
		$sql = "INSERT INTO rh_documentos_historia
				SET
					CodPersona = '".$CodPersona."',
					CodDocumento = '".$CodDocumento."',
					Secuencia = '".$Secuencia."',
					Responsable = '".$Responsable."',
					FechaEntrega = '".formatFechaAMD($FechaEntrega)."',
					Estado = '".$Estado."',
					ObsEntrega = '".changeUrl($ObsEntrega)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	--------------------
		//	actualizo
		$sql = "UPDATE rh_documentos_historia
				SET
					FechaDevuelto = '".formatFechaAMD($FechaDevuelto)."',
					Estado = '".$Estado."',
					ObsDevuelto = '".changeUrl($ObsDevuelto)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodDocumento = '".$CodDocumento."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		mysql_query("BEGIN");
		//	--------------------
		list($CodPersona, $CodDocumento, $Secuencia) = split("[_]", $registro);
	
		//	actualizo
		$sql = "DELETE FROM rh_documentos_historia
				WHERE
					CodPersona = '".$CodPersona."' AND
					CodDocumento = '".$CodDocumento."' AND
					Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	--------------------
		mysql_query("COMMIT");
	}
}

?>