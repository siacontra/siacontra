<?php
include("fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	GRADOS DE CALIFICACION GENERAL
if ($modulo == "grados_calificacion_general_form") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si existe...
		$sql = "SELECT *
				FROM rh_gradoscalificacion
				WHERE
					('".$minimo."' >= PuntajeMin AND '".$minimo."' <= PuntajeMax) OR
					('".$maximo."' >= PuntajeMin AND '".$maximo."' <= PuntajeMax)";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: El puntaje ingresado no puede estar en un intervalo ya ingresado!");
		
		//	inserto
		$codigo = getCodigo("rh_gradoscalificacion", "Grado", 4);
		$sql = "INSERT INTO rh_gradoscalificacion (
							Grado,
							PuntajeMin,
							PuntajeMax,
							Descripcion,
							Definicion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".$minimo."',
							'".$maximo."',
							'".($descripcion)."',
							'".($definicion)."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							'".date("Y-m-d H:i:s")."'
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	consulto si existe...
		$sql = "SELECT *
				FROM rh_gradoscalificacion
				WHERE
					Grado <> '".$codigo."' AND
					(('".$minimo."' >= PuntajeMin AND '".$minimo."' <= PuntajeMax) OR
					 ('".$maximo."' >= PuntajeMin AND '".$maximo."' <= PuntajeMax))";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: El puntaje ingresado no puede estar en un intervalo ya ingresado!");
		
		//	actualizo
		$sql = "UPDATE rh_gradoscalificacion 
				SET
					Descripcion = '".($descripcion)."',
					Definicion = '".($definicion)."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE Grado = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		//	elimino
		$sql = "DELETE FROM rh_gradoscalificacion WHERE Grado = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}
?>