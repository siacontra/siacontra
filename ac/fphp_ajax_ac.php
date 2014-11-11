<?php
include("fphp_ac.php");
connect();
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////

//	SISTEMAS FUENTE
if ($modulo == "SISTEMA-FUENTE") {
	//	insertar
	if ($accion == "INSERTAR") {
		//	valido
		$sql = "SELECT * FROM ac_sistemafuente WHERE CodSistemaFuente = '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡ERROR: Código ya ingresado!");
		
		//	inserto
		$sql = "INSERT INTO ac_sistemafuente (
							CodSistemaFuente,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".utf8_decode($descripcion)."',
							'".$estado."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	modificar
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE ac_sistemafuente
				SET
					Descripcion = '".utf8_decode($descripcion)."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodSistemaFuente = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	eliminar
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_sistemafuente WHERE CodSistemaFuente = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	MODELO VOUCHER
elseif ($modulo == "MODELO-VOUCHER") {
	//	insertar
	if ($accion == "INSERTAR") {
		//	valido
		$sql = "SELECT * FROM ac_modelovoucher WHERE CodModeloVoucher = '".$codigo."'";
		$query_select = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_select) != 0) die("¡ERROR: Código ya ingresado!");
		
		//	inserto
		$sql = "INSERT INTO ac_modelovoucher (
							CodModeloVoucher,
							Descripcion,
							Distribucion,
							CodDependencia,
							CodLibroCont,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".utf8_decode($descripcion)."',
							'".$distribucion."',
							'".$dependencia."',
							'".$libro."',
							'".$estado."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."')";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		// inserto las lineas
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($cuenta, $ccosto, $porcentaje, $monto, $persona, $nrodocumento) = SPLIT( '[|]', $registro);				
				$sql = "INSERT INTO ac_modelovoucherdetalle (
								CodModeloVoucher,
								Secuencia,
								CodCuenta,
								CodCentroCosto,
								Porcentaje,
								Monto,
								CodPersona,
								NroDocumento,
								UltimoUsuario,
								UltimaFecha
						) VALUES (
								'".$codigo."',
								'".$secuencia."',
								'".$cuenta."',
								'".$ccosto."',
								'".$porcentaje."',
								'".$monto."',
								'".$persona."',
								'".$nrodocumento."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	modificar
	elseif ($accion == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE ac_modelovoucher
				SET 
					Descripcion = '".utf8_decode($descripcion)."',
					Distribucion = '".$distribucion."',
					CodDependencia = '".$dependencia."',
					CodLibroCont = '".$libro."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodModeloVoucher = '".$codigo."'";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		// inserto las lineas
		$sql = "DELETE FROM ac_modelovoucherdetalle WHERE CodModeloVoucher = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($cuenta, $ccosto, $porcentaje, $monto, $persona, $nrodocumento) = SPLIT( '[|]', $registro);				
				$sql = "INSERT INTO ac_modelovoucherdetalle (
								CodModeloVoucher,
								Secuencia,
								CodCuenta,
								CodCentroCosto,
								Porcentaje,
								Monto,
								CodPersona,
								NroDocumento,
								UltimoUsuario,
								UltimaFecha
						) VALUES (
								'".$codigo."',
								'".$secuencia."',
								'".$cuenta."',
								'".$ccosto."',
								'".$porcentaje."',
								'".$monto."',
								'".$persona."',
								'".$nrodocumento."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	eliminar
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ac_modelovoucherdetalle WHERE CodModeloVoucher = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM ac_modelovoucher WHERE CodModeloVoucher = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}
?>