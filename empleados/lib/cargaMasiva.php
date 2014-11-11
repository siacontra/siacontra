<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
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
	

?>
