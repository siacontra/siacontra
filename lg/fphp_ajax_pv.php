<?php
include("fphp_lg.php");
connect();
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
switch ($modulo) {

//	TIPOS DE CUENTA PRESUPUESTARIA...
case "TIPO-CUENTA":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta = '".$codigo."' OR descp_tipocuenta = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "INSERT INTO pv_tipocuenta (cod_tipocuenta,
												descp_tipocuenta,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".utf8_decode($descripcion)."',
												'".$status."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM pv_tipocuenta WHERE cod_tipocuenta <> '".$codigo."' AND descp_tipocuenta = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡REGISTRO YA INGRESADO!");
		else {
			$sql = "UPDATE pv_tipocuenta SET descp_tipocuenta = '".utf8_decode($descripcion)."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												cod_tipocuenta = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM pv_tipocuenta WHERE cod_tipocuenta = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}	
	break;

//	CLASIFICADOR PRESUPUESTARIA...
case "CLASIFICADOR-PRESUPUESTARIO":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		//	-------------------------------
		if ($par != "00" && $gen != "00" && $esp != "00" && $subesp != "00") {
			$sql = "SELECT * FROM pv_partida WHERE cod_tipocuenta = '".$cuenta."' AND partida1 = '".$par."' AND generica = '".$gen."' AND especifica = '".$esp."'";
			$nivel = 5;
		}
		elseif ($par != "00" && $gen != "00" && $esp != "00" && $subesp == "00") {
			$sql = "SELECT * FROM pv_partida WHERE cod_tipocuenta = '".$cuenta."' AND partida1 = '".$par."' AND generica = '".$gen."'";
			$nivel = 4;
		}
		elseif ($par != "00" && $gen != "00" && $esp == "00" && $subesp == "00") {
			$sql = "SELECT * FROM pv_partida WHERE cod_tipocuenta = '".$cuenta."' AND partida1 = '".$par."'";
			$nivel = 3;
		}
		elseif ($par != "00" && $gen == "00" && $esp == "00" && $subesp == "00") $nivel = 2;
		
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) == 0) die("¡NO SE ENCONTRO NINGUN CLASIFICADOR PRESUPUESTARIO PARA RELACIONAR ESTA PARTIDA!");
		//	-------------------------------
		
		//	-------------------------------
		$codigo = $cuenta."$par.$gen.$esp.$subesp";
		$sql = "SELECT * FROM pv_partida WHERE cod_partida = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("¡REGISTRO YA INGRESADO!");
		//	-------------------------------
		
		//	-------------------------------
		$sql = "INSERT INTO pv_partida (cod_partida,
										partida1,
										generica,
										especifica,
										subespecifica,
										denominacion,
										cod_tipocuenta,
										tipo,
										nivel,
										Estado,
										CodCuenta,
										UltimoUsuario,
										UltimaFecha)
								VALUES ('".$codigo."',
										'".$par."',
										'".$gen."',
										'".$esp."',
										'".$subesp."',
										'".utf8_decode($descripcion)."',
										'".$cuenta."',
										'".$tipo."',
										'".$nivel."',
										'".$status."',
										'".$codcuenta."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		//	-------------------------------
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE pv_partida SET denominacion = '".utf8_decode($descripcion)."',
										tipo = '".$tipo."',
										Estado = '".$status."',
										CodCuenta = '".$codcuenta."',
										UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										UltimaFecha = '".date("Y-m-d H:i:s")."'
									WHERE
										cod_partida = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM pv_partida WHERE cod_partida = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	break;
}
?>