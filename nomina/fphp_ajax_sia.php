<?php
include("fphp_sia.php");
connect();
$ahora = date("Y-m-d H:i:s");

///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////


switch ($modulo) {
//	PLAN DE CUENTAS
case "PLAN-CUENTAS":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico nivel...
		if ($nivel > 1) {
			if ($nivel == 2)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."'";
			elseif ($nivel == 3)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."' AND SubGrupo = '".$subgrupo."'";
			elseif ($nivel == 4)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."' AND SubGrupo = '".$subgrupo."' AND Rubro = '".$rubro."'";
			elseif ($nivel == 5)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."' AND SubGrupo = '".$subgrupo."' AND Rubro = '".$rubro."' AND Cuenta = '".$cuenta."'";
			elseif ($nivel == 6)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."' AND SubGrupo = '".$subgrupo."' AND Rubro = '".$rubro."' AND Cuenta = '".$cuenta."' AND SubCuenta1 = '".$subcuenta1."'";
			elseif ($nivel == 7)
				$sql = "SELECT * FROM ac_mastplancuenta WHERE Grupo = '".$grupo."' AND SubGrupo = '".$subgrupo."' AND Rubro = '".$rubro."' AND Cuenta = '".$cuenta."' AND SubCuenta2 = '".$subcuenta2."'";
				
			$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_nivel) == 0) die("¡NO EXISTE EL PLAN DE CUENTA QUE SE LE ESTA ASOCIANDO A ESTE REGISTRO!");
		}
		
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_mastplancuenta WHERE CodCuenta = '".$codigo."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡CODIGO DE CUENTA YA INGRESADO!");
		else {
			$sql = "INSERT INTO ac_mastplancuenta (CodCuenta,
													Grupo,
													SubGrupo,
													Rubro,
													Cuenta,
													SubCuenta1,
													SubCuenta2,
													SubCuenta3,
													Descripcion,
													Nivel,
													FlagTipo,
													TipoCuenta,
													TipoSaldo,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".$grupo."',
													'".$subgrupo."',
													'".$rubro."',
													'".$cuenta."',
													'".$subcuenta1."',
													'".$subcuenta2."',
													'".$subcuenta3."',
													'".($descripcion)."',
													'".$nivel."',
													'".$tipo."',
													'".$tipo_cuenta."',
													'".$naturaleza."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			echo "0|".$codigo;
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE ac_mastplancuenta SET Descripcion = '".($descripcion)."',
												FlagTipo = '".$tipo."',
												TipoCuenta = '".$tipo_cuenta."',
												TipoSaldo = '".$naturaleza."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '$ahora'
											WHERE
												CodCuenta = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo "0";
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_mastplancuenta WHERE CodCuenta = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo "0";
	}
	
	//	INsertar partida...
	elseif ($accion == "INSERTAR-PARTIDA") {
		$sql = "INSERT INTO mastcuentapartida (CodCuenta, cod_partida, UltimoUsuario, UltimaFecha) VALUES ('".$idcuenta."', '".$idpartida."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo "0";
	}
	
	//	Eliminar partida...
	elseif ($accion == "ELIMINAR-PARTIDA") {
		$sql = "DELETE FROM mastcuentapartida WHERE CodCuenta = '".$idcuenta."' AND cod_partida = '".$idpartida."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo "0";
	}
	break;

//	GRUPOS DE CENTROS DE COSTOS
case "GRUPOS-CENTROS-COSTOS":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_grupocentrocosto WHERE CodGrupoCentroCosto = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "INSERT INTO ac_grupocentrocosto (CodGrupoCentroCosto,
														Descripcion,
														Estado,
														UltimoUsuario,
														UltimaFecha)
												VALUES ('".$codigo."',
														'".($descripcion)."',
														'".$status."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_grupocentrocosto WHERE CodGrupoCentroCosto <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "UPDATE ac_grupocentrocosto SET Descripcion = '".($descripcion)."',
													Estado = '".$status."',
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													UltimaFecha = '$ahora'
												WHERE
													CodGrupoCentroCosto = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_grupocentrocosto WHERE CodGrupoCentroCosto = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;

//	SUB-GRUPOS DE CENTROS DE COSTOS
case "SUBGRUPOS-CENTROS-COSTOS":
	//	Nuevo registro...
	if ($accion == "INSERTAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_subgrupocentrocosto WHERE CodGrupoCentroCosto = '".$codgrupo."' AND (CodSubGrupoCentroCosto = '".$codsubgrupo."' OR Descripcion = '".($nomsubgrupo)."')";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "INSERT INTO ac_subgrupocentrocosto (CodGrupoCentroCosto,
														CodSubGrupoCentroCosto,
														Descripcion,
														Estado,
														UltimoUsuario,
														UltimaFecha)
												VALUES ('".$codgrupo."',
														'".$codsubgrupo."',
														'".($nomsubgrupo)."',
														'".$edosubgrupo."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_subgrupocentrocosto WHERE CodGrupoCentroCosto = '".$codgrupo."' AND CodSubGrupoCentroCosto <> '".$codsubgrupo."' AND Descripcion = '".($nomsubgrupo)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "UPDATE ac_subgrupocentrocosto SET Descripcion = '".($nomsubgrupo)."',
														Estado = '".$edosubgrupo."',
														UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
														UltimaFecha = '$ahora'
													WHERE
														CodGrupoCentroCosto = '".$codgrupo."' AND 
														CodSubGrupoCentroCosto = '".$codsubgrupo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
	}
	
	//	Editar registro...
	elseif ($accion == "EDITAR") {
		$sql = "SELECT * FROM ac_subgrupocentrocosto WHERE CodGrupoCentroCosto = '".$codgrupo."' AND CodSubGrupoCentroCosto = '".$codsubgrupo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		
		echo "|.|".$field['CodSubGrupoCentroCosto']."|.|".$field['Descripcion']."|.|".$field['Estado'];
	}
	
	//	Eliminar registro...
	elseif ($accion == "BORRAR") {
		$sql = "DELETE FROM ac_subgrupocentrocosto WHERE CodGrupoCentroCosto = '".$codgrupo."' AND CodSubGrupoCentroCosto = '".$codsubgrupo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	CENTROS DE COSTOS
case "CENTROS-COSTOS":
	$sql = "SELECT CodPersona FROM mastempleado WHERE CodEmpleado = '".$codempleado."'";
	$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
	
	
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡CENTRO DE COSTO YA INGRESADO!");
		else {
			$sql = "INSERT INTO ac_mastcentrocosto (CodCentroCosto,
													Descripcion,
													CodPersona,
													TipoCentroCosto,
													CodDependencia,
													CodGrupoCentroCosto,
													CodSubGrupoCentroCosto,
													FlagAdministrativo,
													FlagVentas,
													FlagFinanciero,
													FlagProduccion,
													FlagCentroIngreso,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".($descripcion)."',
													'".$field_empleado['CodPersona']."',
													'".$tipo_ccosto."',
													'".$dependencia."',
													'".$codgrupo_cc."',
													'".$codsubgrupo_cc."',
													'".$flagadministrativo."',
													'".$flagventas."',
													'".$flagfinanciero."',
													'".$flagproduccion."',
													'".$flagcentroingreso."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_mastcentrocosto WHERE CodCentroCosto <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡CENTRO DE COSTO YA INGRESADO!");
		else {
			$sql = "UPDATE ac_mastcentrocosto SET CodPersona = '".$field_empleado['CodPersona']."',
												  TipoCentroCosto = '".$tipo_ccosto."',
												  CodDependencia = '".$dependencia."',
												  CodGrupoCentroCosto = '".$codgrupo_cc."',
												  CodSubGrupoCentroCosto = '".$codsubgrupo_cc."',
												  FlagAdministrativo = '".$flagadministrativo."',
												  FlagVentas = '".$flagventas."',
												  FlagFinanciero = '".$flagfinanciero."',
												  FlagProduccion = '".$flagproduccion."',
												  FlagCentroIngreso = '".$flagcentroingreso."',
												  Estado = '".$status."',
												  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												  UltimaFecha = '$ahora'
											WHERE
												  CodCentroCosto = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
	
	
//	TIPOS DE VOUCHER
case "TIPOS-VOUCHER":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_voucher WHERE Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE VOUCHER YA INGRESADO!");
		else {
			$codigo = getCodigo("ac_voucher", "CodVoucher", 2);
			$sql = "INSERT INTO ac_voucher (CodVoucher,
											Descripcion,
											FlagManual,
											Estado,
											UltimoUsuario,
											UltimaFecha)
									VALUES ('".$codigo."',
											'".($descripcion)."',
											'".$flagmanual."',
											'".$status."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ac_voucher WHERE CodVoucher <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE VOUCHER YA INGRESADO!");
		else {
			$sql = "UPDATE ac_voucher SET Descripcion = '".($descripcion)."',
										  FlagManual = '".$flagmanual."',
										  Estado = '".$status."',
										  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										  UltimaFecha = '$ahora'
									WHERE
										  CodVoucher = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_voucher WHERE CodVoucher = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	REGIMEN FISCAL
case "REGIMEN-FISCAL":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ap_regimenfiscal WHERE CodRegimenFiscal = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGIMEN FISCAL YA INGRESADO!");
		else {
			$sql = "INSERT INTO ap_regimenfiscal (CodRegimenFiscal,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".($descripcion)."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ap_regimenfiscal WHERE CodRegimenFiscal <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡REGIMEN FISCAL YA INGRESADO!");
		else {
			$sql = "UPDATE ap_regimenfiscal SET Descripcion = '".($descripcion)."',
												  Estado = '".$status."',
												  UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												  UltimaFecha = '$ahora'
											WHERE
												  CodRegimenFiscal = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ap_regimenfiscal WHERE CodRegimenFiscal = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	IMPUESTOS
case "IMPUESTOS":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM mastimpuestos WHERE CodImpuesto = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡IMPUESTO YA INGRESADO!");
		else {
			$sql = "INSERT INTO mastimpuestos (CodImpuesto,
												Descripcion,
												CodRegimenFiscal,
												Signo,
												CodCuenta,
												FactorPorcentaje,
												FlagProvision,
												FlagImponible,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".($descripcion)."',
												'".$regimen."',
												'".$signo."',
												'".$codcuenta."',
												'".$porcentaje."',
												'".$provisionar."',
												'".$imponible."',
												'".$status."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM mastimpuestos WHERE CodImpuesto <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡IMPUESTO YA INGRESADO!");
		else {
			$sql = "UPDATE mastimpuestos  SET Descripcion = '".($descripcion)."',
												CodRegimenFiscal = '".$regimen."',
												Signo = '".$signo."',
												CodCuenta = '".$codcuenta."',
												FactorPorcentaje = '".$porcentaje."',
												FlagProvision = '".$provisionar."',
												FlagImponible = '".$imponible."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '$ahora'
											WHERE 
												CodImpuesto = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM mastimpuestos WHERE CodImpuesto = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	TIPOS DE DOCUMENTOS CTAS. POR PAGAR
case "TIPOS-DOCUMENTOS-CXP":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ap_tipodocumento WHERE CodTipoDocumento = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE DOCUMENTO YA INGRESADO!");
		else {
			$sql = "INSERT INTO ap_tipodocumento (CodTipoDocumento,
													Descripcion,
													Clasificacion,
													CodRegimenFiscal,
													CodVoucher,
													FlagProvision,
													CodCuentaProv,
													FlagAdelanto,
													CodCuentaAde,
													FlagFiscal,
													CodFiscal,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".($descripcion)."',
													'".$clasificacion."',
													'".$regimen."',
													'".$voucher."',
													'".$flagprovision."',
													'".$codcuentaprov."',
													'".$flagadelanto."',
													'".$codcuentaade."',
													'".$flagfiscal."',
													'".$codfiscal."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM ap_tipodocumento WHERE CodTipoDocumento <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE DOCUMENTO YA INGRESADO!");
		else {
			$sql = "UPDATE ap_tipodocumento SET Descripcion = '".($descripcion)."',
												Clasificacion = '".$clasificacion."',
												CodRegimenFiscal = '".$regimen."',
												CodVoucher = '".$voucher."',
												FlagProvision = '".$flagprovision."',
												CodCuentaProv = '".$codcuentaprov."',
												FlagAdelanto = '".$flagadelanto."',
												CodCuentaAde = '".$codcuentaade."',
												FlagFiscal = '".$flagfiscal."',
												CodFiscal = '".$codfiscal."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '$ahora'
											WHERE
												CodTipoDocumento = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ap_tipodocumento WHERE CodTipoDocumento = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	TIPOS DE SERVICIO
case "TIPOS-SERVICIO":
	//	Nuevo registro...
	if ($accion == "GUARDAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM masttiposervicio WHERE CodTipoServicio = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE SERVICIO YA INGRESADO!");
		else {
			$sql = "INSERT INTO masttiposervicio (CodTipoServicio,
													Descripcion,
													CodRegimenFiscal,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".($descripcion)."',
													'".$regimen."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			echo "|".$codigo;
		}
	}
	
	//	Modificar registro...
	elseif ($accion == "ACTUALIZAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM masttiposervicio WHERE CodTipoServicio <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡TIPO DE SERVICIO YA INGRESADO!");
		else {
			$sql = "UPDATE masttiposervicio SET Descripcion = '".($descripcion)."',
												CodRegimenFiscal = '".$regimen."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '$ahora'
											WHERE
												CodTipoServicio = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM masttiposervicio WHERE CodTipoServicio = '".$registro."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
	
//	TIPOS DE SERVICIO (IMPUESTOS)
case "TIPOS-SERVICIO-IMPUESTO":
	//	Nuevo registro...
	if ($accion == "INSERTAR") {
		//	Verifico que no exista el codigo de la cuenta...
		$sql = "SELECT * FROM masttiposervicioimpuesto WHERE CodTipoServicio = '".$codtiposervicio."' AND CodImpuesto = '".$impuesto."'";
		$query_codigo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_codigo) != 0) die("¡IMPUESTO YA INGRESADO!");
		else {
			$sql = "INSERT INTO masttiposervicioimpuesto (CodTipoServicio,
															CodImpuesto,
															UltimoUsuario,
															UltimaFecha)
													VALUES ('".$codtiposervicio."',
															'".$impuesto."',
															'".$_SESSION['USUARIO_ACTUAL']."',
															'$ahora')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			echo "|".$codigo;
		}
	}
	
	//	Eliminar registro...
	elseif ($accion == "BORRAR") {
		$sql = "DELETE FROM masttiposervicioimpuesto WHERE CodTipoServicio = '".$codtiposervicio."' AND CodImpuesto = '".$impuesto."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	break;
}
?>