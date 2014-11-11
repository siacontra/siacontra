<?php
include ("fphp_nomina.php");
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	TIPOS DE PROCESO
if ($_POST['modulo']=="TIPOPROCESO") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion']=="GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM pr_tipoproceso WHERE CodTipoProceso='$codigo' OR Descripcion='$descripcion'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0) $error="REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql="INSERT INTO pr_tipoproceso VALUES ('$codigo', '".($descripcion)."', '$flag', '$status', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql="SELECT * FROM pr_tipoproceso WHERE Descripcion='".($descripcion)."' AND CodTipoProceso<>'$codigo'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows!=0)	$error="REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql="UPDATE pr_tipoproceso SET Descripcion='".($descripcion)."', FlagAdelanto='$flag', Estado='$status', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoProceso='$codigo'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$sql="DELETE FROM pr_tipoproceso WHERE CodTipoProceso='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
}

//	CONCEPTOS
elseif ($_POST['modulo']=="CONCEPTOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion'] == "GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql = "SELECT * FROM pr_concepto WHERE CodConcepto = '$codigo' OR (Descripcion = '$descripcion' AND Tipo = '$tipo')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $error = "REGISTRO EXISTENTE";
		else {
			$codigo = getCodigo("pr_concepto", "CodConcepto", 4);
			//	INSERTO EL NUEVO REGISTRO
			$sql = "INSERT INTO pr_concepto (
								CodConcepto,
								Descripcion,
								Tipo,
								TextoImpresion,
								PlanillaOrden,
								Abreviatura,
								Formula,
								FlagFormula,
								Estado,
								FlagAutomatico,
								FlagBono,
								FlagObligacion,
								FlagRetencion,
								CodPersona,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'$codigo', 
								'".($descripcion)."', 
								'$tipo', '".($impresion)."', 
								'$orden', '".($abreviatura)."', 
								'".($formula)."', 
								'".$flagformula."', 
								'$status', 
								'$flagautomatica', 
								'$flagbono', 
								'$flagobligacion', 
								'$flagretencion', 
								'$codpersona', 
								'".$_SESSION['USUARIO_ACTUAL']."', 
								'$ahora'
					)";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql = "SELECT * FROM pr_concepto WHERE Descripcion = '".($descripcion)."' AND Tipo = '$tipo' AND CodConcepto <> '$codigo'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0)	$error = "REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql = "UPDATE pr_concepto
					SET
						Descripcion = '".($descripcion)."', 
						Tipo = '".$tipo."', 
						TextoImpresion = '".($impresion)."', 
						PlanillaOrden = '$orden', 
						Abreviatura = '".($abreviatura)."', 
						Formula = '".($formula)."', 
						FlagFormula = '".$flagformula."', 
						Estado = '$status', 
						FlagAutomatico = '$flagautomatica', 
						FlagBono = '$flagbono', 
						FlagObligacion = '$flagobligacion', 
						FlagRetencion = '$flagretencion', 
						CodPersona = '$codpersona', 
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
						UltimaFecha = '$ahora'
					WHERE CodConcepto = '$codigo'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion'] == "ELIMINAR") {
		$sql = "DELETE FROM pr_concepto WHERE CodConcepto='".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion'] == "TIPOSNOMINA") {
		if ($sub == "BORRAR") {
			$sql = "DELETE FROM pr_conceptotiponomina WHERE CodConcepto = '".$concepto."' AND CodTipoNom = '".$tiponomina."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion'] == "PROCESOS") {
		if ($sub == "BORRAR") {
			$sql = "DELETE FROM pr_conceptoproceso WHERE CodConcepto = '".$concepto."' AND CodTipoProceso = '".$proceso."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	TIPOS DE NOMINA
elseif ($_POST['modulo'] == "TIPOSNOMINA") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion'] == "GUARDAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql = "SELECT * FROM tiponomina WHERE Nomina = '$descripcion'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $error = "REGISTRO EXISTENTE";
		else {
			$codigo = getCodigo("tiponomina", "CodTipoNom", 2);
			//	INSERTO EL NUEVO REGISTRO
			$sql = "INSERT INTO tiponomina (
								CodTipoNom, 
								Nomina, 
								FlagPagoMensual, 
								TituloBoleta, 
								CodPerfilConcepto,
								Estado, 
								UltimoUsuario, 
								UltimaFecha
					) VALUES (
								'$codigo', 
								'".($descripcion)."', 
								'$flag', 
								'".($titulo)."', 
								'$perfil', 
								'$status', 
								'".$_SESSION['USUARIO_ACTUAL']."', 
								'$ahora'
					)";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error.":".$codigo;
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql = "SELECT * FROM tiponomina WHERE Nomina = '".($descripcion)."' AND CodTipoNom <> '$codigo'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $error = "REGISTRO EXISTENTE";
		else {
			//	ACTUALIZO REGISTRO
			$sql = "UPDATE tiponomina
					SET 
						Nomina = '".($descripcion)."', 
						TituloBoleta = '".($titulo)."', 
						CodPerfilConcepto = '$perfil', 
						FlagPagoMensual = '$flag', 
						Estado = '$status', 
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
						UltimaFecha = '$ahora'
					WHERE CodTipoNom = '$codigo'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		$sql="DELETE FROM tiponomina WHERE CodTipoNom='".$codigo."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	elseif ($_POST['accion']=="PROCESO") {
		if ($sub=="NUEVO") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM pr_tiponominaproceso WHERE CodTipoProceso='$proceso' AND CodTipoNom='$tiponomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO EXISTENTE";
			else {
				//	INSERTO EL NUEVO REGISTRO
				$sql="INSERT INTO pr_tiponominaproceso (CodTipoNom, CodTipoProceso, UltimoUsuario, UltimaFecha) VALUES ('$tiponomina', '$proceso', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($sub=="ACTUALIZAR") {
			list($codproceso)=SPLIT( '[:.:]', $codigo);
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM pr_tiponominaproceso WHERE CodTipoProceso='$proceso' AND (CodTipoProceso<>'$codproceso')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="REGISTRO EXISTENTE";
			else {
				//	INSERTO EL NUEVO REGISTRO
				$sql="UPDATE pr_tiponominaproceso SET CodTipoProceso='$proceso', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoProceso='$codproceso' AND CodTipoNom='$tiponomina'";
				$query=mysql_query($sql) or die ($sql.mysql_error());
			}
			echo $error;
		}
		elseif ($sub=="BORRAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="DELETE FROM pr_tiponominaproceso WHERE CodTipoProceso='$proceso' AND CodTipoNom='$tiponomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
	}
	elseif ($_POST['accion']=="PERIODO") {
		list($anio_contable, $mes_contable)=SPLIT( '[-.-]', $_SESSION['PERIODO_CONTABLE_ACTUAL']);
		//	-------------------------------
		if ($sub=="NUEVO") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT * FROM pr_tiponominaperiodo WHERE (CodTipoNom='$tiponomina' AND Periodo='$anio' AND Mes='$mes') OR (Periodo='$anio' AND Secuencia='$secuencia')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) $error="PERIODO O SECUENCIA YA INGRESADA";
			else {
				//if ($anio>$anio_contable || ($anio==$anio_contable && $mes>$mes_contable)) $error="NO PUEDE INSERTAR UN PERIODO MAYOR AL PERIODO CONTABLE";
				//else {
					//	INSERTO EL NUEVO REGISTRO
					$sql="INSERT INTO pr_tiponominaperiodo (CodTipoNom, Periodo, Mes, Secuencia, UltimoUsuario, UltimaFecha) VALUES ('$tiponomina', '$anio', '$mes', '$secuencia', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
					$query=mysql_query($sql) or die ($sql.mysql_error());
				//}
			}
			echo $error;
		}
		elseif ($sub=="GENERAR") {
			//	CONSULTO SI EL NUEVO REGISTRO EXISTE
			$sql="SELECT MAX(tnp.Periodo) AS Periodo FROM pr_tiponominaperiodo tnp WHERE tnp.CodTipoNom='$tiponomina' GROUP BY tnp.CodTipoNom";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			$rows=mysql_num_rows($query);
			if ($rows!=0) {
				$field=mysql_fetch_array($query);
				$p=(int) $field['Periodo']; 
				
				$sql="SELECT MAX(tnp.Mes) AS Mes FROM pr_tiponominaperiodo tnp WHERE tnp.CodTipoNom='$tiponomina' AND tnp.Periodo = '".$field['Periodo']."' GROUP BY tnp.CodTipoNom";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				$rows=mysql_num_rows($query);
				if ($rows!=0) $field=mysql_fetch_array($query);
				
				$m=(int) $field['Mes'];
				$m=(int) $m+1;
				if ($m==13) { $p=(int) $p+1; $m=1; }
				$periodo=$p;
				if ($m<10) $mes="0$m"; else $mes=$m;
			} else {
				$periodo=date("Y");
				$mes=date("m");
			}	//die("$periodo $mes");
			//if ($periodo>$anio_contable || ($periodo==$anio_contable && $mes>$mes_contable)) $error="EL PERIODO CONTABLE NO PUEDE SER MENOR AL PERIODO ACTUAL";
			//else {
				$sql="INSERT INTO pr_tiponominaperiodo (CodTipoNom, Periodo, Mes, Secuencia, UltimoUsuario, UltimaFecha) VALUES ('$tiponomina', '$periodo', '$mes', '$mes', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
				$query_insert=mysql_query($sql) or die ($sql.mysql_error());
			//}
			echo $error;
		}
		elseif ($sub=="ACTUALIZAR") {
			list($codanio, $codmes, $codsecuencia)=SPLIT( '[:.:]', $codigo);
			//if ($anio>$anio_contable || ($anio==$anio_contable && $mes>$mes_contable)) $error="NO PUEDE INSERTAR UN PERIODO MAYOR AL PERIODO CONTABLE";
			//else {
				//	ACTUALIZO EL REGISTRO
				$sql="UPDATE pr_tiponominaperiodo SET Periodo='$anio', Mes='$mes', Secuencia='$secuencia', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodTipoNom='$tiponomina' AND Periodo='$codanio' AND Mes='$codmes'";
				$query=mysql_query($sql) or die ("REGISTRO EXISTENTE");
			//}
			echo $error;
		}
		elseif ($sub=="BORRAR") {
			//	ELIMINO EL REGISTRO
			$sql="DELETE FROM pr_tiponominaperiodo WHERE Periodo='$anio' AND Mes='$mes' AND CodTipoNom='$tiponomina'";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			echo $error;
		}
	}
}

//	CONCEPTOS DEL EMPLEADO
elseif ($_POST['modulo']=="EMPLEADOS-CONCEPTOS") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	//
	if ($_POST['accion']=="INSERTAR") {
		$sql="SELECT * FROM pr_empleadoconcepto WHERE CodPersona='".$codpersona."' AND CodConcepto='".$codconcepto."' AND PeriodoDesde='".$pdesde."' AND PeriodoHasta='".$phasta."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query)!=0) $error="CONCEPTO YA INGRESADO PARA ESTE EMPLEADO";
		else {
			if ($pdesde!="" && $phasta!="") $tipo="T"; else $tipo="P";
			//	------------------------
			$secuencia=getSecuencia("Secuencia", "CodPersona", "pr_empleadoconcepto", $codpersona);
			$sql="INSERT INTO pr_empleadoconcepto (CodPersona, CodConcepto, Secuencia, TipoAplicacion, PeriodoDesde, PeriodoHasta, FlagTipoProceso, Procesos, Monto, Cantidad, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$codconcepto."', '".$secuencia."', '".$tipo."', '".$pdesde."', '".$phasta."', '".$flagproceso."', '".$codproceso."', '".$monto."', '".$cantidad."', '".$status."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	elseif ($_POST['accion']=="EDITAR") {
		list($codconcepto, $secuencia)=SPLIT( '[-.-]', $elemento);
		//	------------------------
		$sql="SELECT pec.CodConcepto, pec.Secuencia, pc.Descripcion AS NomConcepto, pec.PeriodoDesde, pec.PeriodoHasta, pec.Monto, pec.Cantidad, pec.Estado, pec.FlagTipoProceso, pec.Procesos FROM pr_empleadoconcepto pec INNER JOIN pr_concepto pc ON (pec.CodConcepto=pc.CodConcepto) WHERE pec.CodPersona='".$codpersona."' AND pec.CodConcepto='".$codconcepto."' AND pec.Secuencia='".$secuencia."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query)!=0) {
			$field=mysql_fetch_array($query);
			$monto=number_format($field['Monto'], 2, ',', '');
			$cantidad=number_format($field['Cantidad'], 0, ',', '.');
			echo "0|:|".$field['CodConcepto']."|:|".$field['NomConcepto']."|:|".$field['Secuencia']."|:|".$field['PeriodoDesde']."|:|".$field['PeriodoHasta']."|:|".$monto."|:|".$cantidad."|:|".$field['Estado']."|:|".$field['FlagTipoProceso']."|:|".$field['Procesos'];
		}
	}
	elseif ($_POST['accion']=="ACTUALIZAR") {
		if ($phasta=="") $tipo="P"; else $tipo="T";
		//	------------------------
		$sql="UPDATE pr_empleadoconcepto SET TipoAplicacion='".$tipo."', PeriodoDesde='".$pdesde."', PeriodoHasta='".$phasta."', FlagTipoProceso='".$flagproceso."', Procesos='".$codproceso."', Monto='".$monto."', Cantidad='".$cantidad."', Estado='".$status."', UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha='$ahora' WHERE CodPersona='".$codpersona."' AND CodConcepto='".$codconcepto."' AND Secuencia='".$secuencia."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//	------------------------
		echo $error;
	}
	elseif ($_POST['accion']=="ELIMINAR") {
		list($codconcepto, $secuencia)=SPLIT( '[-.-]', $elemento);
		//	-----------------------------
		$sql="DELETE FROM pr_empleadoconcepto WHERE CodPersona='".$codpersona."' AND CodConcepto='".$codconcepto."' AND Secuencia='".$secuencia."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
}

//	CONTROL DE PROCESOS
elseif ($_POST['modulo'] == "PROCESOS-CONTROL") {
	connect();
	$error=0;
	$ahora=date("Y-m-d H:i:s");
	list($d, $m, $a)=SPLIT( '[/.-]', $fdesde); $fdesde=$a.$m.$d;
	list($d, $m, $a)=SPLIT( '[/.-]', $fhasta); $fhasta=$a.$m.$d;
	list($d, $m, $a)=SPLIT( '[/.-]', $fprocesado); $fprocesado=$a.$m.$d;
	list($d, $m, $a)=SPLIT( '[/.-]', $fpago); $fpago=$a.$m.$d;
	//
	if ($_POST['accion'] == "INICIAR") {
		//	CONSULTO SI EL NUEVO REGISTRO EXISTE
		$sql = "SELECT *
				FROM pr_procesoperiodo
				WHERE
					CodOrganismo = '".$organismo."' AND 
					CodTipoNom = '".$tiponom."' AND 
					Periodo = '".$periodo."' AND 
					CodTipoProceso = '".$proceso."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows = mysql_num_rows($query);
		if ($rows != 0) $error = "REGISTRO EXISTENTE";
		else {
			//	INSERTO EL NUEVO REGISTRO
			$sql = "INSERT INTO pr_procesoperiodo (
								CodOrganismo, 
								CodTipoNom, 
								Periodo, 
								CodTipoProceso, 
								FechaDesde, 
								FechaHasta, 
								FlagMensual, 
								Estado, 
								CreadoPor, 
								FechaCreado, 
								FechaProceso, 
								FechaPago, 
								FlagAprobado, 
								FlagProcesado, 
								FlagPagado, 
								UltimoUsuario, 
								UltimaFecha) 
							VALUES (
								'".$organismo."', 
								'".$tiponom."', 
								'".$periodo."', 
								'".$proceso."', 
								'".$fdesde."', 
								'".$fhasta."', 
								'".$flagmensual."', 
								'".$status."', 
								'".$_SESSION["CODPERSONA_ACTUAL"]."', 
								'".$ahora."', 
								'".$fprocesado."', 
								'".$fpago."', 
								'N', 
								'N', 
								'N', 
								'".$_SESSION['USUARIO_ACTUAL']."', 
								'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		//	ACTUALIZO REGISTRO
		$sql = "UPDATE pr_procesoperiodo
				SET
					FechaDesde = '".$fdesde."',
					FechaHasta = '".$fhasta."',
					FlagMensual = '".$flagmensual."',
					Estado = '".$status."',
					FechaProceso = '".$fprocesado."',
					FechaPago = '".$fpago."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '$ahora'
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$tiponom."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	elseif ($_POST['accion'] == "DESACTIVAR") {
		list($organismo, $tiponom, $periodo, $proceso) = SPLIT( '[:.:]', $registro);
		$sql = "SELECT Estado
				FROM pr_procesoperiodo
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$tiponom."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";
		$query_status = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_status) != 0) {
			$field_status = mysql_fetch_array($query_status);
			if ($field_status['Estado'] == "A") $status = "I"; else $status = "A";
			//	-----------------------
			$sql = "UPDATE pr_procesoperiodo
					SET
						Estado = '".$status."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = '$ahora'
					WHERE
						CodOrganismo = '".$organismo."' AND
						CodTipoNom = '".$tiponom."' AND
						Periodo = '".$periodo."' AND
						CodTipoProceso = '".$proceso."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	elseif ($_POST['accion'] == "CERRAR") {
		list($organismo, $tiponom, $periodo, $proceso) = SPLIT( '[:.:]', $registro);
		$sql = "SELECT FlagProcesado
				FROM pr_procesoperiodo
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$tiponom."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";
		$query_status = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_status) != 0) {
			$field_status = mysql_fetch_array($query_status);
			if ($field_status['FlagProcesado'] == "S") $error = "EL PERIODO YA SE ENCUENTRA CERRADO";
			else {
				$sql = "UPDATE pr_procesoperiodo 
						SET 
							FlagProcesado = 'S', 
							FlagPagado = 'S', 
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
							UltimaFecha = '$ahora' 
						WHERE 
							CodOrganismo = '".$organismo."' AND 
							CodTipoNom = '".$tiponom."' AND 
							Periodo = '".$periodo."' AND 
							CodTipoProceso = '".$proceso."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	elseif ($_POST['accion'] == "APROBAR") {
		$sql = "SELECT FlagAprobado
				FROM pr_procesoperiodo
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoNom = '".$tiponom."' AND
					Periodo = '".$periodo."' AND
					CodTipoProceso = '".$proceso."'";
		$query_status = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_status) != 0) {
			$field_status = mysql_fetch_array($query_status);
			if ($field_status['FlagAprobado'] == "N") $flag = "S"; else $flag = "N";
			//	-----------------------
			$sql = "UPDATE pr_procesoperiodo
					SET
						AprobadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
						FechaAprobado = '$ahora',
						FlagAprobado = '".$flag."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = '$ahora'
					WHERE
						CodOrganismo = '".$organismo."' AND
						CodTipoNom = '".$tiponom."' AND
						Periodo = '".$periodo."' AND
						CodTipoProceso = '".$proceso."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo $error;
}

//	EJECUCION DE PROCESOS
elseif ($_POST['modulo'] == "EJECUCION-PROCESOS") {
	connect();
	$error = 0;
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($_POST['accion'] == "PROCESAR-NOMINA") {
		include ("funciones_globales_nomina.php");
		$_PARAMETROS = PARAMETROS();
		$TOTAL_ASIGNACIONES = 0;
		$TOTAL_DEDUCCIONES = 0;
		$TOTAL_PATRONALES = 0;
		$TOTAL_PROVISIONES = 0;
		$ALIVAC = 0;
		$empleado = explode("|:|", $aprobados);
		
		//	Variables usadas en la formula....
		$_ARGS['NOMINA'] = $tiponom;
		$_ARGS['PERIODO'] = $periodo;
		$_ARGS['PROCESO'] = $proceso;
		$_PROCESO = $proceso;
		$_ARGS['ORGANISMO'] = $organismo;
		list($_ARGS['DESDE'], $_ARGS['HASTA']) = FECHA_PROCESO($_ARGS);
		$_ARGS['DIAS_PROCESO'] = DIAS_PROCESO($_ARGS);
		if ($_ARGS['DIAS_PROCESO'] == 28 || $_ARGS['DIAS_PROCESO'] == 29) { 
			$_ARGS['DIAS_PROCESO'] = 30; 
			list($a, $m, $d) = split("[-]", $_ARGS['HASTA']);
			$_ARGS['HASTA'] = "$a-$m-$_PARAMETROS[MAXDIASMES]";
		}		
		list($_ANO_PROCESO, $_MES_PROCESO, $_DIA_PROCESO) = split("-", $_ARGS['HASTA']);
		$_NOMINA = $tiponom;
		
		//	Actualizo el proceso
		$sql = "UPDATE pr_procesoperiodo
				SET
					ProcesadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '$ahora'
				WHERE
					CodTipoNom = '".$tiponom."' AND 
					Periodo = '".$periodo."' AND 
					CodOrganismo = '".$organismo."' AND 
					CodTipoProceso = '".$_ARGS['PROCESO']."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	Recorro cada empleado seleccionado
		foreach ($empleado as $codpersona) {
			//	Variables usadas en la formula....
			$_ARGS['TRABAJADOR'] = $codpersona;
			$_ARGS['FECHA_INGRESO'] = FECHA_INGRESO($_ARGS);
			$_FECHA_INGRESO = $_ARGS['FECHA_INGRESO'];
			list($_ANO_INGRESO, $_MES_INGRESO, $_DIA_INGRESO) = split("-", $_ARGS['FECHA_INGRESO']);
			$_DIAS_SUELDO_BASICO = DIAS_SUELDO_BASICO($_ARGS);
			$_SUELDO_BASICO = SUELDO_BASICO($_ARGS);
			$_SUELDO_BASICO_DIARIO = $_SUELDO_DIARIO / 30; $_SUELDO_BASICO_DIARIO = REDONDEO($_SUELDO_BASICO_DIARIO, 2);
			$_SUELDO_NORMAL_COMPLETO = TOTAL_ASIGNACIONES($_ARGS);
			$_SUELDO_NORMAL = 0;
			$_SUELDO_NORMAL_DIARIO = 0;
			$_ARGS['ASIGNACIONES'] = 0;
			$_ARGS['DEDUCCIONES'] = 0;
			$_ARGS['PATRONALES'] = 0;
			$_ARGS['PROVISIONES'] = 0;
			
			
			
			//	Elimino los datos del empleado si tiene...
			$sql = "DELETE FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = '".$codpersona."' AND 
						CodTipoNom = '".$tiponom."' AND 
						Periodo = '".$periodo."' AND 
						CodOrganismo = '".$organismo."' AND 
						CodTipoProceso = '".$_ARGS['PROCESO']."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------
			$sql = "DELETE FROM pr_tiponominaempleado
					WHERE
						CodPersona = '".$codpersona."' AND 
						CodTipoNom = '".$tiponom."' AND 
						Periodo = '".$periodo."' AND 
						CodOrganismo = '".$organismo."' AND 
						CodTipoProceso = '".$_ARGS['PROCESO']."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------
			
			
			/***********************************************************************************
			 * OBTENGO EL MONTO DE LA ULTIMA PRIMA POR AÑOS DE SERVICIOS QUE SE LE PAGO AL EMPLEADO
			 * 
			***********************************************************************************/
			/*$sqlPrima = "SELECT * FROM pr_tiponominaempleadoconcepto  
			where CodPersona='".$codpersona."' and CodConcepto ='0023' 
			and Periodo = (select max(Periodo) FROM pr_tiponominaempleadoconcepto)";*/
			
			$sqlPrima = "SELECT * FROM pr_tiponominaempleadoconcepto  
			where CodPersona='".$codpersona."' and CodConcepto ='0023' 
			and Periodo < (select max(Periodo) FROM pr_tiponominaempleadoconcepto)
			and Periodo < '".$periodo."' 
			order by Periodo desc limit 1";
			
//echo $sqlPrima;

			$query_prima = mysql_query($sqlPrima) or die ($sqlPrima.mysql_error());
			$rows_prima = mysql_num_rows($query_prima);
			
			$field_prima = mysql_fetch_array($query_prima); 
			
			/**********************************************************************************/
			
			//	Obtengo los conceptos del empleado
			$sql = "(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'1' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'I') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'1' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'I' AND pc.FlagAutomatico = 'S' AND 
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'I') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'2' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'D') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'2' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'D' AND pc.FlagAutomatico = 'S' AND 
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'D') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'3' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'A') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'3' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'A' AND pc.FlagAutomatico = 'S' AND 
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'A') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							pec.Monto,
							pec.Cantidad,
							'4' AS Orden,
pec.PeriodoDesde
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pec.Estado = 'A' AND pc.Estado = 'A') AND
							(pc.Tipo = 'P') AND	(pec.CodPersona = '".$codpersona."') AND
							(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."'))
						GROUP BY CodConcepto)
					UNION
					(SELECT
							pc.CodConcepto,
							pc.Descripcion,
							pc.PlanillaOrden,
							pc.FlagAutomatico,
							pc.Formula,
							pc.Tipo,
							pc.FlagBono,
							'' AS Monto,
							'' AS Cantidad,
							'4' AS Orden,
'' AS PeriodoDesde
						FROM
							pr_concepto pc
							INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = '".$_ARGS['PROCESO']."')
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
						WHERE
							(pc.Estado = 'A') AND
							pc.Tipo = 'P' AND pc.FlagAutomatico = 'S' AND 
							pc.CodConcepto NOT IN (
								SELECT
									pc.CodConcepto
								FROM
									pr_empleadoconcepto pec
									INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
									INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$tiponom."' )
								WHERE
									(pec.Estado = 'A' AND pc.Estado = 'A') AND
									(pc.Tipo = 'P') AND	(pec.CodPersona = '".$codpersona."') AND
									(pec.Procesos = '[TODOS]' OR pec.Procesos LIKE '%".$_ARGS['PROCESO']."%') AND
									((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$periodo."' AND pec.PeriodoDesde <= '".$periodo."') OR 
									 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$periodo."')))
						GROUP BY CodConcepto)
					ORDER BY Orden, PlanillaOrden";
//echo $sql;					
			$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
			$rows_conceptos = mysql_num_rows($query_concepto);
			while ($field_concepto = mysql_fetch_array($query_concepto)) {
				$_ARGS['CONCEPTO'] = $field_concepto['CodConcepto'];
				$_CODCONCEPTO = $field_concepto['CodConcepto'];
				if (trim($field_concepto['Formula']) == "") {
					$_ARGS['FORMULA'] = "";
					
					$_ARGS['MONTO'] = $field_concepto['Monto'];
					
					/***********************************CALCULO PARA LA PRIMA DE AÑOS DE SERVICIOS*************************************************************/
					if($field_concepto['CodConcepto'] == '0023')
					{
						/*if($rows_prima > 0)
						{
							
							if(($_MES_INGRESO == date('m')) && ($_ANO_INGRESO < date('Y')) && ($field_prima['Periodo'] == $periodo))
							{
								//$_ARGS['MONTO'] = ;
								
								//$calculoMonto = $field_concepto['Monto'] + ($_SUELDO_BASICO*(2/100));
								
								$_ARGS['MONTO'] = $field_prima['Monto'];
								
								//$sqlAux = "update pr_empleadoconcepto set Monto=".$calculoMonto." where CodPersona='".$codpersona."' and PeriodoDesde='".$periodo."'";
								
								//$query_insert = mysql_query($sqlAux) or die ($sqlAux.mysql_error());
						
							} else {
								
								$_ARGS['MONTO'] = $field_concepto['Monto'];
								
							}
		
						} else {*/
							
							list($anioPeriodo,$mesPeriodo) = split('-',$periodo);
							
							//if(($_MES_INGRESO == date('m')) && ($_ANO_INGRESO < date('Y')) && ($rows_prima > 0))
							if(($_MES_INGRESO == $mesPeriodo) && ($_ANO_INGRESO < $anioPeriodo) && ($rows_prima > 0))
							{
								//$_ARGS['MONTO'] = ;
								//echo 'si';
								
								if ($periodo == $field_concepto['PeriodoDesde']) {
									
									$_ARGS['MONTO'] = $field_concepto['Monto'];
								
								} else {
								
									$calculoMonto = $field_prima['Monto'] + ($_SUELDO_BASICO*(2/100));
									
									$_ARGS['MONTO'] = $calculoMonto;
								}
								
								/*if($periodo !=  $field_concepto['Periodo'])
								{
									
								
									$sqlAux = "update pr_empleadoconcepto set Monto=".$calculoMonto." where CodPersona='".$codpersona."' and PeriodoDesde='".$field_concepto['Periodo']."'";
									
									$query_insert = mysql_query($sqlAux) or die ($sqlAux.mysql_error());
								}*/
						
							} else {
								
								if ($periodo == $field_concepto['PeriodoDesde']) {
									
									$_ARGS['MONTO'] = $field_concepto['Monto'];
								
								} else if (isset($field_prima['Monto']))
								{
									
								 	$_ARGS['MONTO'] = $field_prima['Monto'];
									
								} 
								
								
								
							}
						//}
					}
					
					/***********************************************************************************************/
					
					$_ARGS['CANTIDAD'] = $field_concepto['Cantidad'];
				} else {
					//	Variables usadas en la formula....
					$_ARGS['FORMULA'] = $field_concepto['Formula'];
					$_ARGS['FLAGBONO'] = $field_concepto['FlagBono'];
					//if ($_ARGS['FLAGBONO'] == "S") $_ARGS['ASIGNACIONES'] = TOTAL_ASIGNACIONES($_ARGS);
					$_SUELDO_NORMAL = $_ARGS['ASIGNACIONES'];
					$_SUELDO_NORMAL_DIARIO = $_SUELDO_NORMAL / 30; $_SUELDO_NORMAL_DIARIO = REDONDEO($_SUELDO_NORMAL_DIARIO, 2);
					
					$_MONTO = 0;
					$_CANTIDAD = 0;
					//	Ejecuto la formula del concepto...
					eval($_ARGS['FORMULA']);
					$_ARGS['MONTO'] = REDONDEO($_MONTO, 2);
					$_ARGS['CANTIDAD'] = REDONDEO($_CANTIDAD, 2);
					$_CONCEPTO[$_CODCONCEPTO] = $_ARGS['MONTO'];
				}
				
				if ($_ARGS['MONTO'] != 0) {
					//	Dependendiendo del tipo de concepto (I:INGRESOS; D:DESCUENTOS; A:PATRONALES) voy sumando
					if ($field_concepto['Tipo'] == "I") $TOTAL_ASIGNACIONES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "D") $TOTAL_DEDUCCIONES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "A") $TOTAL_PATRONALES += $_ARGS['MONTO'];
					elseif ($field_concepto['Tipo'] == "P") $TOTAL_PROVISIONES += $_ARGS['MONTO'];
					
					//	Variables usadas en la formula....
					$_ARGS['ASIGNACIONES'] = $TOTAL_ASIGNACIONES;
					$_ARGS['DEDUCCIONES'] = $TOTAL_DEDUCCIONES;
					$_ARGS['PATRONALES'] = $TOTAL_PATRONALES;
					$_ARGS['PROVISIONES'] = $TOTAL_PROVISIONES;
										
					// Inserto los valores del concepto del trabajador....
					$sql = "INSERT INTO pr_tiponominaempleadoconcepto (
										CodTipoNom,
										Periodo,
										CodPersona,
										CodOrganismo,
										CodConcepto,
										CodTipoProceso,
										Monto,
										Cantidad,
										UltimoUsuario,
										UltimaFecha)
							VALUES (
										'".$_ARGS['NOMINA']."',
										'".$_ARGS['PERIODO']."',
										'".$_ARGS['TRABAJADOR']."',
										'".$_ARGS['ORGANISMO']."',
										'".$_ARGS['CONCEPTO']."',
										'".$_ARGS['PROCESO']."',
										'".$_ARGS['MONTO']."',
										'".$_ARGS['CANTIDAD']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'$ahora')";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}
			}
			
			//	Si se le genero algun concepto entonces...
			if (($_ARGS['ASIGNACIONES'] != 0 || $_ARGS['PROVISIONES'] != 0) && $rows_conceptos != 0) {
				//	Consulto la informacion bancaria del trabajador...
				$sql = "SELECT
							me.SueldoActual, 
							me.CodTipoPago, 
							rp.NivelSalarial, 
							bp.Monto, 
							bp.Ncuenta, 
							bp.CodBanco, 
							bp.TipoCuenta 
						FROM 
							mastempleado me 
							INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo) 
							INNER JOIN masttipopago mtp ON (me.CodTipoPago = mtp.CodTipoPago) 
							LEFT JOIN bancopersona bp ON (me.CodPersona = bp.CodPersona AND FlagPrincipal = 'S') 
						WHERE me.CodPersona = '".$_ARGS['TRABAJADOR']."'";
				$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_nomina) != 0) {
					$field_nomina = mysql_fetch_array($query_nomina);
					
					// Inserto los totales generales del trabajador
					$TOTAL_NETO = $TOTAL_ASIGNACIONES - $TOTAL_DEDUCCIONES;
					$sql = "INSERT INTO pr_tiponominaempleado (
										CodTipoNom, 
										Periodo, 
										CodPersona, 
										CodOrganismo, 
										CodTipoProceso, 
										SueldoBasico, 
										TotalIngresos, 
										TotalEgresos, 
										TotalPatronales, 
										TotalProvisiones, 
										TotalNeto, 
										CodBanco, 
										TipoCuenta, 
										Ncuenta, 
										CodTipoPago, 
										GeneradoPor, 
										FechaGeneracion, 
										UltimoUsuario, 
										UltimaFecha) 
							VALUES (
										'".$_ARGS['NOMINA']."', 															   
										'".$_ARGS['PERIODO']."', 
										'".$_ARGS['TRABAJADOR']."', 
										'".$_ARGS['ORGANISMO']."', 
										'".$_ARGS['PROCESO']."', 
										'".$_SUELDO_BASICO."', 
										'".$_ARGS['ASIGNACIONES']."', 
										'".$_ARGS['DEDUCCIONES']."', 
										'".$_ARGS['PATRONALES']."', 
										'".$_ARGS['PROVISIONES']."', 
										'".$TOTAL_NETO."', 
										'".$field_nomina['CodBanco']."', 
										'".$field_nomina['TipoCuenta']."', 
										'".$field_nomina['Ncuenta']."', 
										'".$field_nomina['CodTipoPago']."', 
										'".$_SESSION['USUARIO_ACTUAL']."', 
										'$ahora', 
										'".$_SESSION['USUARIO_ACTUAL']."', 
										'$ahora')";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					
					if ($_ARGS['PROCESO'] == "FIN") {
						//	Elimino cualquier calculo anterior
						$sql = "DELETE FROM rh_sueldos 
								WHERE
									CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
									Periodo = '".$_ARGS['PERIODO']."'";
						$query_delete = mysql_query($sql) or die ($sql.mysql_error());
						//	-----------------------------
						// Inserto en la tabla de sueldos
						$secuencia = getSecuencia("Secuencia", "CodPersona", "rh_sueldos", $_ARGS['TRABAJADOR']);
						$sql = "INSERT INTO rh_sueldos (
											CodPersona,
											Secuencia,
											Periodo,
											Sueldo,
											SueldoNormal,
											UltimoUsuario,
											UltimaFecha)
								VALUES (
											'".$_ARGS['TRABAJADOR']."',
											'".$secuencia."',
											'".$_ARGS['PERIODO']."',
											'".$_SUELDO_BASICO."',
											'".$_SUELDO_NORMAL."',
											'".$_SESSION['USUARIO_ACTUAL']."', 
											'$ahora')";
						$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					}
				}
			}
			$TOTAL_ASIGNACIONES = 0;
			$TOTAL_DEDUCCIONES = 0;
			$TOTAL_PATRONALES = 0;
			$TOTAL_PROVISIONES = 0;
			$ALIVAC = 0;
			$_ARGS['MONTO'] = 0;
			$_ARGS['CANTIDAD'] = 0;
		}
	}
	
	elseif ($_POST['accion'] == "QUITAR-SEL-NOMINA") {
		$seleccion = explode("|;|", $seleccionados);
		foreach ($seleccion as $registro) {
			list($codpersona)=SPLIT( '[|:|]', $registro);			
			//	Elimino los datos del empleado si tiene...
			$sql = "DELETE FROM pr_tiponominaempleadoconcepto 
					WHERE 
						CodPersona = '".$codpersona."' AND 
						CodTipoNom = '".$tiponom."' AND 
						Periodo = '".$periodo."' AND 
						CodOrganismo = '".$organismo."' AND 
						CodTipoProceso = '".$proceso."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------
			$sql = "DELETE FROM pr_tiponominaempleado 
					WHERE 
						CodPersona = '".$codpersona."' AND 
						CodTipoNom='".$tiponom."' AND 
						Periodo='".$periodo."' AND 
						CodOrganismo='".$organismo."' AND 
						CodTipoProceso = '".$proceso."'";
			$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}

//	EJECUCION DE PROCESOS
elseif ($_POST['modulo'] == "FIDEICOMISO") {
	connect();
	$error = 0;
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($_POST['accion'] == "GUARDAR") {
		$sql = "SELECT o.CodOrganismo FROM mastorganismos o INNER JOIN mastempleado e ON (o.CodOrganismo = e.CodOrganismo AND e.CodPersona = '".$codpersona."')";
		$query_organismo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_organismo) != 0) $field_organismo = mysql_fetch_array($query_organismo);
		
		$sql = "SELECT * FROM pr_acumuladofideicomiso WHERE CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "EMPLEADO YA INGRESADO";
		else {
			$sql = "INSERT INTO pr_acumuladofideicomiso (CodPersona,
														CodOrganismo,
														AcumuladoInicialDias,
														AcumuladoInicialProv,
														AcumuladoInicialFide,
														AcumuladoDiasAdicionalInicial,
														PeriodoInicial,
														UltimoUsuario,
														UltimaFecha) 
												VALUES ('".$codpersona."',
														'".$field_organismo['CodOrganismo']."',
														'".$acumuladoinicialdias."',
														'".$acumuladoinicialprov."',
														'".$acumuladoinicialfide."',
														'".$acumuladoinicialdiasadicional."',
														'".$periodoinicial."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	-----------------------
	elseif ($_POST['accion'] == "ACTUALIZAR") {
		$sql = "UPDATE pr_acumuladofideicomiso SET AcumuladoInicialDias = '".$acumuladoinicialdias."',
													AcumuladoInicialProv = '".$acumuladoinicialprov."',
													AcumuladoInicialFide = '".$acumuladoinicialfide."',
													AcumuladoDiasAdicionalInicial = '".$acumuladoinicialdiasadicional."',
													PeriodoInicial = '".$periodoinicial."',
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													UltimaFecha = '$ahora'
												WHERE
													CodPersona = '".$codpersona."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	-----------------------
	elseif ($_POST['accion'] == "ACTUALIZAR-ANTIGUEDAD") {
		list($anio, $mes) = SPLIT( '[-./]', $periodo);
		$anio_ant = (int) $anio;
		$mes_ant = (int) $mes;
		$mes_ant--; if ($mes_ant == 0) { $mes_ant = 12; $anio_ant--; }
		if ($mes_ant < 10) $mes_ant = "0$mes_ant";
		$periodo_ant = "$anio_ant-$mes_ant";
		//	----------
		$sql = "SELECT
					me.CodPersona,
					mp.NomCompleto,
					bp.Ncuenta,
					mp.Ndocumento,
					tnec.Monto,
					tnec.Cantidad,
					(SELECT AnteriorProv 
						FROM pr_acumuladofideicomisodetalle
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."' AND
								Periodo = '".$periodo_ant."'
					) AS AnteriorProv,
					(SELECT AnteriorFide
						FROM pr_acumuladofideicomisodetalle
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."' AND
								Periodo = '".$periodo_ant."'
					) AS AnteriorFide,
					(SELECT AcumuladoProvDias
						FROM pr_acumuladofideicomiso
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."'
					) AS AcumuladoProvDias,
					(SELECT AcumuladoInicialProv
						FROM pr_acumuladofideicomiso
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."'
					) AS AcumuladoInicialProv,
					(SELECT AcumuladoInicialFide
						FROM pr_acumuladofideicomiso
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."'
					) AS AcumuladoInicialFide,
					(SELECT AcumuladoProv
						FROM pr_acumuladofideicomiso
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."'
					) AS AcumuladoProv,
					(SELECT AcumuladoFide
						FROM pr_acumuladofideicomiso
							WHERE
								CodPersona = me.CodPersona AND
								CodOrganismo = '".$organismo."'
					) AS AcumuladoFide
				FROM
					mastpersonas mp
					INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
					INNER JOIN pr_tiponominaempleadoconcepto tnec ON (mp.CodPersona = tnec.CodPersona)
					LEFT JOIN bancopersona bp ON (mp.CodPersona = bp.CodPersona AND bp.Aportes = 'FI')
				WHERE
					tnec.CodOrganismo = '".$organismo."' AND
					tnec.Periodo = '".$periodo."' AND
					tnec.CodTipoNom = '".$nomina."' AND
					tnec.CodTipoProceso = 'FIN' AND
					tnec.CodConcepto = '0045'";
		$query_listado = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_listado = mysql_fetch_array($query_listado)) {
			$sql = "SELECT * FROM pr_acumuladofideicomisodetalle WHERE CodPersona = '".$field_listado['CodPersona']."' AND Periodo = '".$periodo."'";
			$query_inserto = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_inserto) != 0) {
				$field_inserto = mysql_fetch_array($query_inserto);
				
				$acumuladoprovdias = $field_listado['AcumuladoProvDias'] + $field_listado['Cantidad'] - $field_inserto['Dias'];
				$acumuladoprov = $field_listado['AcumuladoProv'] + $field_listado['Monto'] - $field_inserto['Transaccion'];
				//	---------------------------
				$tasa = tasaInteres($periodo);
				$prestacion_antiguedad = $field_listado['AcumuladoInicialProv'] + $acumuladoprov;
				list($anio, $mes) = split("[-]", $periodo); $dias_mes = getDiasMes(intval($anio), intval($mes));
				$interes_mensual = ($prestacion_antiguedad * $tasa / 100) * $dias_mes / 365;
				$acumuladofide = $interes_mensual + $field_listado['AnteriorFide'];
				//	---------------------------
				//$acumuladofide = $field_listado['AcumuladoFide'] + $field_listado['Monto'] - $field_inserto['Transaccion'];
				
				//	Actualizo
				$sql = "UPDATE pr_acumuladofideicomisodetalle SET AnteriorProv = '".$field_listado['AnteriorProv']."',
																	AnteriorFide = '".$field_listado['AnteriorFide']."',
																	Transaccion = '".$field_listado['Monto']."',
																	Dias = '".$field_listado['Cantidad']."',
																	UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
																	UltimaFecha = '$ahora'
																WHERE 
																	CodPersona = '".$field_listado['CodPersona']."' AND
																	Periodo = '".$periodo."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				// Actualizo
				$sql = "UPDATE pr_acumuladofideicomiso SET AcumuladoProvDias = '".$acumuladoprovdias."',
															AcumuladoProv = '".$acumuladoprov."',
															AcumuladoFide = '".$acumuladofide."'
														WHERE
															CodPersona = '".$field_listado['CodPersona']."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	Inserto
				$sql = "INSERT INTO pr_acumuladofideicomisodetalle (CodPersona,
																	CodOrganismo,
																	Periodo,
																	AnteriorProv,
																	AnteriorFide,
																	Transaccion,
																	Dias,
																	UltimoUsuario,
																	UltimaFecha) 
															VALUES ('".$field_listado['CodPersona']."',
																	'".$organismo."',
																	'".$periodo."',
																	'".($field_listado['AcumuladoInicialProv']+$field_listado['AcumuladoProv'])."',
																	'".($field_listado['AcumuladoInicialFide']+$field_listado['AcumuladoFide'])."',
																	'".$field_listado['Monto']."',
																	'".$field_listado['Cantidad']."',
																	'".$_SESSION['USUARIO_ACTUAL']."',
																	'$ahora')";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				// Actualizo o Inserto
				$acumuladoprovdias = $field_listado['AcumuladoProvDias'] + $field_listado['Cantidad'];
				$acumuladoprov = $field_listado['AcumuladoProv'] + $field_listado['Monto'];
				//	---------------------------
				$tasa = tasaInteres($periodo);
				$prestacion_antiguedad = $field_listado['AcumuladoInicialProv'] + $acumuladoprov;
				list($anio, $mes) = split("[-]", $periodo); $dias_mes = getDiasMes(intval($anio), intval($mes));
				$interes_mensual = ($prestacion_antiguedad * $tasa / 100) * $dias_mes / 365;
				$acumuladofide = $interes_mensual + $field_listado['AcumuladoFide'];
				//	---------------------------
				//$acumuladofide = $field_listado['AcumuladoFide'] + $field_listado['Monto'];
				
				$sql = "SELECT * FROM pr_acumuladofideicomiso WHERE CodPersona = '".$field_listado['CodPersona']."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query) != 0) {
					//
					$sql = "UPDATE pr_acumuladofideicomiso SET AcumuladoProvDias = '".$acumuladoprovdias."',
																AcumuladoProv = '".$acumuladoprov."',
																AcumuladoFide = '".$acumuladofide."'
															WHERE
																CodPersona = '".$field_listado['CodPersona']."'";
				} else {
					//
					$sql = "INSERT INTO  pr_acumuladofideicomiso (CodPersona, 
																	CodOrganismo,
																	AcumuladoProvDias,
																	AcumuladoProv,
																	AcumuladoFide,
																	UltimoUsuario,
																	UltimaFecha)
															VALUES ('".$field_listado['CodPersona']."',
																	'".$organismo."',
																	'".$acumuladoprovdias."',
																	'".$acumuladoprov."',
																	'".$acumuladofide."',
																	'".$_SESSION['USUARIO_ACTUAL']."',
																	'$ahora')";
				}
				$query = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo $error;
	}
}

//	MODIFICAR PAYRRLL
elseif ($modulo == "PAYROLL") {
	connect();
	$error = 0;
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($accion == "MODIFICAR") {
		//	Obtengo las asignaciones
		$valores = explode("|", $asignaciones);		
		//	Por cada asignacion
		foreach ($valores as $valor) {
			$asignacion = explode(":", $valor);
			//	si el monto es mayor a cero
			if ($asignacion[1] > 0) {
				$sql = "UPDATE pr_tiponominaempleadoconcepto
						SET Monto = '".$asignacion[1]."'
						WHERE
							CodConcepto = '".$asignacion[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				##
				$total_asignaciones += $asignacion[1];
			} else {
				//	elimino el concepto
				$sql = "DELETE FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = '".$asignacion[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Obtengo las deducciones
		$valores = explode("|", $deducciones);
		//	Por cada asignacion
		foreach ($valores as $valor) {
			$deduccion = explode(":", $valor);
			//	si el monto es mayor a cero
			if ($deduccion[1] > 0) {
				$sql = "UPDATE pr_tiponominaempleadoconcepto
						SET Monto = '".$deduccion[1]."'
						WHERE
							CodConcepto = '".$deduccion[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."' ";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				##
				$total_deducciones += $deduccion[1];
			} else {
				//	elimino el concepto
				$sql = "DELETE FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = '".$deduccion[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Obtengo los aportes
		$valores = explode("|", $patronales);
		//	Por cada aportes
		foreach ($valores as $valor) {
			$aporte = explode(":", $valor);
			//	si el monto es mayor a cero
			if ($aporte[1] > 0) {
				$sql = "UPDATE pr_tiponominaempleadoconcepto
						SET Monto = '".$aporte[1]."'
						WHERE
							CodConcepto = '".$aporte[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				##
				$total_aportes += $aporte[1];
			} else {
				//	elimino el concepto
				$sql = "DELETE FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = '".$aporte[0]."' AND
							CodTipoNom = '".$nomina."' AND
							Periodo = '".$periodo."' AND
							CodPersona = '".$persona."' AND
							CodOrganismo = '".$organismo."' AND
							CodTipoProceso = '".$proceso."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		$total_neto = $total_asignaciones - $total_deducciones;
		//	Actualizo los totales
		$sql = "UPDATE pr_tiponominaempleado
				SET
					TotalIngresos = '".$total_asignaciones."',
					TotalEgresos = '".$total_deducciones."',
					TotalNeto = '".$total_neto."'
				WHERE
					CodTipoNom = '".$nomina."' AND
					Periodo = '".$periodo."' AND
					CodPersona = '".$persona."' AND
					CodOrganismo = '".$organismo."' AND
					CodTipoProceso = '".$proceso."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}	
	elseif ($accion == "INSERTAR-CONCEPTO") {
		$sql="SELECT 
					* 
				FROM 
					pr_empleadoconcepto 
				WHERE 
					CodPersona = '".$codpersona."' AND 
					CodConcepto = '".$codconcepto."' AND 
					PeriodoDesde = '".$pdesde."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query)!=0) $error="CONCEPTO YA INGRESADO PARA ESTE EMPLEADO";
		else {
			if ($pdesde!="" && $phasta!="") $tipo="T"; else $tipo="P";
			//	------------------------
			$secuencia=getSecuencia("Secuencia", "CodPersona", "pr_empleadoconcepto", $codpersona);
			$sql = "INSERT INTO pr_empleadoconcepto (CodPersona, 
													CodConcepto, 
													Secuencia, 
													TipoAplicacion, 
													PeriodoDesde, 
													PeriodoHasta, 
													FlagTipoProceso, 
													Procesos, 
													Monto, 
													Cantidad, 
													Estado, 
													UltimoUsuario, 
													UltimaFecha) 
											VALUES ('".$codpersona."', 
													'".$codconcepto."', 
													'".$secuencia."', 
													'".$tipo."', 
													'".$pdesde."', 
													'".$phasta."', 
													'".$flagproceso."', 
													'".$codproceso."', 
													'".$monto."', 
													'".$cantidad."', 
													'".$status."', 
													'".$_SESSION['USUARIO_ACTUAL']."', 
													'$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
			
			$sql = "INSERT INTO pr_tiponominaempleadoconcepto (CodTipoNom, 	
																Periodo, 
																CodPersona, 
																CodOrganismo, 
																CodConcepto, 
																CodTipoProceso, 
																Monto, 
																Cantidad, 
																UltimoUsuario, 
																UltimaFecha) 
														VALUES ('".$nomina."', 
																'".$periodo."', 
																'".$codpersona."', 
																'".$organismo."', 
																'".$codconcepto."', 
																'".$codproceso."', 
																'".$monto."', 
																'".$cantidad."', 
																'".$_SESSION['USUARIO_ACTUAL']."', 
																'$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
}

//	TASAS DE INTERES
elseif ($modulo == "TASA-INTERESES") {
	connect();
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM masttasainteres WHERE Periodo = '".$periodo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die ("Periodo ya ingresado");
		else {
			$sql = "INSERT INTO masttasainteres (Periodo,
												Porcentaje,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$periodo."',
												'".$porcentaje."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."', 
												'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	-----------------------
	elseif ($accion == "MODIFICAR") {
		$sql = "UPDATE masttasainteres SET Porcentaje = '".$porcentaje."',
											Estado = '".$estado."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
											UltimaFecha = '$ahora'
										WHERE
											Periodo = '".$periodo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	-----------------------
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM masttasainteres WHERE Periodo = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	AJUSTE SALARIAL POR GRADO
elseif ($modulo == "AJUSTE-SALARIAL-POR-GRADO") {
	connect();
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($accion == "GUARDAR") {
		$datos = explode(";", $valores);	
		foreach ($datos as $nivel) {
			list($grado, $porcentaje, $monto)=SPLIT( '[|]', $nivel);
			
			$sql = "SELECT * FROM rh_nivelsalarial WHERE CategoriaCargo = '".$categoria."' AND Grado = '".$grado."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($porcentaje != "") {
					$nuevo = ($field['SueldoPromedio'] * $porcentaje / 100) + $field['SueldoPromedio'];
				} else {
					$nuevo = $field['SueldoPromedio'] + $monto;
				}
				
				//	Inserto en el ajuste salarial
				$secuencia=getSecuencia("Secuencia", "CodNivel", "rh_nivelsalarialajustes", $field['CodNivel']); $secuencia = (int) $secuencia;
				$sql = "INSERT INTO rh_nivelsalarialajustes (CodNivel,
																Secuencia,
																Descripcion,
																SueldoPromedio,
																Estado,
																UltimoUsuario,
																UltimaFecha) 
														VALUES ('".$field['CodNivel']."',
																'".$secuencia."',
																'".$field['Descripcion']."',
																'".$nuevo."',
																'A',
																'".$_SESSION['USUARIO_ACTUAL']."', 
																'$ahora')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	Actualizo los niveles salariales
				$sql = "UPDATE rh_nivelsalarial SET SueldoMinimo = '".$nuevo."', 
													SueldoMaximo = '".$nuevo."', 
													SueldoPromedio = '".$nuevo."',
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
													UltimaFecha = '$ahora'
												WHERE
													Grado = '".$grado."' AND
													CategoriaCargo = '".$categoria."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				
				//	Actualizo en cargos
				$sql = "UPDATE rh_puestos SET NivelSalarial = '".$nuevo."' WHERE CategoriaCargo = '".$categoria."' AND Grado = '".$grado."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				
				//	Actualizo el sueldo de los empleados
				$sql = "SELECT CodCargo FROM rh_puestos WHERE CategoriaCargo = '".$categoria."' AND Grado = '".$grado."'";
				$query_cargos = mysql_query($sql) or die ($sql.mysql_error());
				while ($field_cargos = mysql_fetch_array($query_cargos)) {
					$sql = "UPDATE mastempleado SET SueldoAnterior = SueldoActual, SueldoActual = '".$nuevo."' WHERE CodCargo = '".$field_cargos['CodCargo']."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				}
			}
		}
	}
}

//	PERFIL DE CONCEPTOS
elseif ($modulo == "CONCEPTO-PERFIL") {
	connect();
	$ahora = date("Y-m-d H:i:s");
	//	-----------------------
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM pr_conceptoperfil WHERE Descripcion = '".($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die ("Perfil ya ingresado");
		else {
			$codigo = getCodigo("pr_conceptoperfil", "CodPerfilConcepto", 4);
			$sql = "INSERT INTO pr_conceptoperfil
						(CodPerfilConcepto,
						 Descripcion,
						 Estado,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$codigo."',
						 '".($descripcion)."',
						 '".$status."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 '$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	-----------------------
	elseif ($accion == "MODIFICAR") {
		$sql = "UPDATE pr_conceptoperfil
				SET 
					Descripcion = '".($descripcion)."',
					Estado = '".$status."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '$ahora'
				WHERE CodPerfilConcepto = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	-----------------------
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM pr_conceptoperfil WHERE CodPerfilConcepto = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	-----------------------
	elseif ($accion == "DETALLES") {
		$sql = "DELETE FROM pr_conceptoperfildetalle WHERE CodPerfilConcepto = '".$codperfil."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		//	---------------------------------------------------
		$procesos = split(";", $detalles);
		foreach ($procesos as $detprocesos) {
			list($codigo, $partida, $debe, $ccdebe, $haber, $cchaber)=SPLIT( '[|]', $detprocesos);
			list($proceso, $concepto)=SPLIT( '[_]', $codigo);
			
			//if ($partida != "") {
				if ($ccdebe == "true") $ccdebe = "S"; else $ccdebe = "N";
				if ($cchaber == "true") $cchaber = "S"; else $cchaber = "N";
				
				$sql = "INSERT INTO pr_conceptoperfildetalle
							(CodPerfilConcepto,
							 CodTipoProceso,
							 CodConcepto,
							 cod_partida,
							 CuentaDebe,
							 CuentaHaber,
							 FlagDebeCC,
							 FlagHaberCC,
							 UltimoUsuario,
							 UltimaFecha)
						VALUES
							('".$codperfil."',
							 '".$proceso."',
							 '".$concepto."',
							 '".$partida."',
							 '".$debe."',
							 '".$haber."',
							 '".$ccdebe."',
							 '".$cchaber."',
							 '".$_SESSION['USUARIO_ACTUAL']."',
							 '$ahora')";
				$query = mysql_query($sql) or die ($sql.mysql_error());
			//}
		}
	}
}
?>
