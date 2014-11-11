<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	horario laboral
if ($modulo == "horario_laboral") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$CodHorario = getCodigo("rh_horariolaboral", "CodHorario", 3);
		
		//	inserto
		$sql = "INSERT INTO rh_horariolaboral
				SET
					CodHorario = '".$CodHorario."',
					Descripcion = '".changeUrl($Descripcion)."',
					FlagCorrido = '".$FlagCorrido."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	horario
		$_Secuencia = 0;
		$horario = split(";char:tr;", $detalles_horario);
		foreach ($horario as $_detalle) {
			list($_FlagLaborable, $_Dia, $_Entrada1, $_Salida1, $_Entrada2, $_Salida2) = split(";char:td;", $_detalle);
			//	inserto
			$sql = "INSERT INTO rh_horariolaboraldet
					SET
						CodHorario = '".$CodHorario."',
						Secuencia = '".++$_Secuencia."',
						FlagLaborable = '".$_FlagLaborable."',
						Dia = '".$_Dia."',
						Entrada1 = '".$_Entrada1."',
						Salida1 = '".$_Salida1."',
						Entrada2 = '".$_Entrada2."',
						Salida2 = '".$_Salida2."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_horariolaboral
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					FlagCorrido = '".$FlagCorrido."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodHorario = '".$CodHorario."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	horario
		$sql = "DELETE FROM rh_horariolaboraldet WHERE CodHorario = '".$CodHorario."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$horario = split(";char:tr;", $detalles_horario);
		foreach ($horario as $_detalle) {
			list($_FlagLaborable, $_Dia, $_Entrada1, $_Salida1, $_Entrada2, $_Salida2) = split(";char:td;", $_detalle);
			//	inserto
			$sql = "INSERT INTO rh_horariolaboraldet
					SET
						CodHorario = '".$CodHorario."',
						Secuencia = '".++$_Secuencia."',
						FlagLaborable = '".$_FlagLaborable."',
						Dia = '".$_Dia."',
						Entrada1 = '".$_Entrada1."',
						Salida1 = '".$_Salida1."',
						Entrada2 = '".$_Entrada2."',
						Salida2 = '".$_Salida2."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM rh_horariolaboral WHERE CodHorario = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	tipo de nomina
elseif ($modulo == "tipo_nomina") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO tiponomina
				SET
					CodTipoNom = '".$CodTipoNom."',
					Nomina = '".changeUrl($Nomina)."',
					TituloBoleta = '".changeUrl($TituloBoleta)."',
					FlagPagoMensual = '".$FlagPagoMensual."',
					CodPerfilConcepto = '".$CodPerfilConcepto."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	periodos
		$periodos = split(";char:tr;", $detalles_periodos);
		foreach ($periodos as $_detalle) {
			list($_Periodo, $_Mes, $_Secuencia) = split(";char:td;", $_detalle);
			//	inserto
			$sql = "INSERT INTO pr_tiponominaperiodo
					SET
						CodTipoNom = '".$CodTipoNom."',
						Periodo = '".$_Periodo."',
						Mes = '".$_Mes."',
						Secuencia = '".$_Secuencia."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	procesos
		if ($detalles_procesos != "") {
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_detalle) {
				list($_CodTipoProceso, $_CodTipoDocumento) = split(";char:td;", $_detalle);
				//	inserto
				$sql = "INSERT INTO pr_tiponominaproceso
						SET
							CodTipoNom = '".$CodTipoNom."',
							CodTipoProceso = '".$_CodTipoProceso."',
							CodTipoDocumento = '".$_CodTipoDocumento."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE tiponomina
				SET
					Nomina = '".changeUrl($Nomina)."',
					TituloBoleta = '".changeUrl($TituloBoleta)."',
					FlagPagoMensual = '".$FlagPagoMensual."',
					CodPerfilConcepto = '".$CodPerfilConcepto."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoNom = '".$CodTipoNom."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	periodos
		$sql = "DELETE FROM pr_tiponominaperiodo WHERE CodTipoNom = '".$CodTipoNom."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$periodos = split(";char:tr;", $detalles_periodos);
		foreach ($periodos as $_detalle) {
			list($_Periodo, $_Mes, $_Secuencia) = split(";char:td;", $_detalle);
			//	inserto
			$sql = "INSERT INTO pr_tiponominaperiodo
					SET
						CodTipoNom = '".$CodTipoNom."',
						Periodo = '".$_Periodo."',
						Mes = '".$_Mes."',
						Secuencia = '".$_Secuencia."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	procesos
		if ($detalles_procesos != "") {
			$sql = "DELETE FROM pr_tiponominaproceso WHERE CodTipoNom = '".$CodTipoNom."'";	fwrite($__archivo, $sql.";\n\n");
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$procesos = split(";char:tr;", $detalles_procesos);
			foreach ($procesos as $_detalle) {
				list($_CodTipoProceso, $_CodTipoDocumento) = split(";char:td;", $_detalle);
				//	inserto
				$sql = "INSERT INTO pr_tiponominaproceso
						SET
							CodTipoNom = '".$CodTipoNom."',
							CodTipoProceso = '".$_CodTipoProceso."',
							CodTipoDocumento = '".$_CodTipoDocumento."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-----------------
		mysql_query("COMMIT");
	}
}
?>