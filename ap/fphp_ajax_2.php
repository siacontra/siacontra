<?php
include("fphp.php");

///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////

//	EMPLEADOS (IMPUESTO SOBRE LA RENTA)
if ($modulo == "EMPLEADO-IMPUESTO") {
	connect();
	$ahora=date("Y-m-d H:i:s");
	if ($accion == "INSERTAR") {
		list($ad, $md)=SPLIT( '[/.-]', $desde);
		list($ah, $mh)=SPLIT( '[/.-]', $hasta);
		if ($anio != $ad || $anio != $ah) die ("¡Los periodos no se corresponden con el año!");
				
		$sql = "SELECT * FROM pr_impuestorenta WHERE CodPersona = '".$codpersona."' AND Anio = '".$anio."'";
		$query_anio = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_anio) != 0) die ("¡Año ya ingresado!");
		
		$sql = "INSERT INTO pr_impuestorenta (CodPersona, Anio, Desde, Hasta, Porcentaje, UltimoUsuario, UltimaFecha) VALUES ('".$codpersona."', '".$anio."', '".$desde."', '".$hasta."', '".$porcentaje."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	elseif ($accion == "EDITAR") {
		$sql = "SELECT * FROM pr_impuestorenta WHERE CodPersona = '".$codpersona."' AND Anio = '".$anio."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			
			echo "|".$field['Desde']."|".$field['Hasta']."|".$field['Porcentaje'];
		}
	}
	
	elseif ($accion == "MODIFICAR") {
		list($ad, $md)=SPLIT( '[/.-]', $desde);
		list($ah, $mh)=SPLIT( '[/.-]', $hasta);
		if ($anio != $ad || $anio != $ah) die ("¡Los periodos no se corresponden con el año!");
		
		$sql = "UPDATE pr_impuestorenta SET Desde = '".$desde."', Hasta = '".$hasta."', Porcentaje = '".$porcentaje."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '$ahora' WHERE CodPersona = '".$codpersona."' AND Anio = '".$anio."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	elseif ($accion == "BORRAR") {
		$sql = "DELETE FROM pr_impuestorenta WHERE CodPersona = '".$codpersona."' AND Anio = '".$anio."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
}

elseif ($modulo == "SUELDOS-MINIMOS") {
	if ($accion == "GUARDAR") {
		$secuencia = getCodigo("mastsueldosmin", "Secuencia", 4); $secuencia = (int) $secuencia;
		$sql = "INSERT INTO mastsueldosmin (Secuencia, Periodo, Monto, UltimoUsuario, UltimaFecha) VALUES ('".$secuencia."', '".$periodo."', '".$monto."', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	elseif ($accion == "ELIMINAR") {
		connect();
		$sql = "DELETE FROM mastsueldosmin WHERE Secuencia = '".$seleccion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	EVALUACION DE DESEMPEÑO
elseif ($modulo == "EVALUACIONDESEMPENIO") {
	connect();
	$ahora=date("Y-m-d H:i:s");
	list($d, $m, $a)=SPLIT( '[/.-]', $fecha_evaluacion); $fecha_evaluacion="$a-$m-$d";
	//	---------------------------
	if ($accion == "GUARDAR") {
		//	INSERTO EL NUEVO REGISTRO
		$sql = "INSERT INTO rh_evaluacionempleado 
					(CodOrganismo, 
					 Periodo, 
					 Secuencia, 
					 CodPersona, 
					 ComentarioPersona, 
					 Evaluador, 
					 ComentarioEvaluador, 
					 Supervisor, 
					 ComentarioSupervisor, 
					 FechaEvaluacion, 
					 TotalDesempenio, 
					 TotalFuncion, 
					 TotalMetas, 
					 Estado, 
					 UltimoUsuario, 
					 UltimaFecha)
				VALUES
					('$forganismo', 
					 '$periodo', 
					 '$secuencia', 
					 '$persona', 
					 '".($comempleado)."', 
					 '$codevaluador', 
					 '".($comevaluador)."', 
					 '$codsupervisor', 
					 '".($comsupervisor)."', 
					 '$fecha_evaluacion', 
					 '$desempenio', 
					 '$funcion', 
					 '$metas', 
					 '$status', 
					 '".$_SESSION['USUARIO_ACTUAL']."', 
					 '$ahora')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	INSERTO LOS DETALLES
		//	--------------------		
		//	Inserto tab Desempeño (Tab2)
		if (trim($detalles_tab2)) {
			$secuencia_det = 0;
			$detalle_tab2 = split(";", $detalles_tab2);
			foreach ($detalle_tab2 as $linea) {
				list($plantilla_det, $competencia_det, $calificacion_det, $peso_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_evaluacion 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Plantilla, 
							 Competencia, 
							 Calificacion, 
							 Peso, 
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '$plantilla_det', 
							 '$competencia_det', 
							 '$calificacion_det', 
							 '$peso_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
				
		//	Inserto tab Funciones (Tab3)
		if (trim($detalles_tab3) != "") {
			$secuencia_det = 0;
			$detalle_tab3 = split(";", $detalles_tab3);
			foreach ($detalle_tab3 as $linea) {
				list($funcion_det, $calificacion_det, $peso_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_funciones 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Funcion,
							 Calificacion, 
							 Peso, 
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".($funcion_det)."', 
							 '$calificacion_det', 
							 '$peso_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Fortalezas y Debilidades (Tab4)
		if (trim($detalles_tab4)) {
			$secuencia_det = 0;
			$detalle_tab4 = split(";", $detalles_tab4);
			foreach ($detalle_tab4 as $linea) {
				list($tipo_det, $descripcion_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_desempenio 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Descripcion, 
							 Tipo,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".($descripcion_det)."',
							 '$tipo_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Objetivos y Metas (Tab5)
		if (trim($detalles_tab5)) {
			$secuencia_det = 0;
			$detalle_tab5 = split(";", $detalles_tab5);
			foreach ($detalle_tab5 as $linea) {
				list($descripcion_det, $periodo_det, $comentarios_det, $fdesde_det, $fhasta_det, $calificacion_det, $peso_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_metas 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Descripcion, 
							 Comentarios, 
							 PeriodoMetas,  
							 Desde,  
							 Hasta, 
							 Calificacion, 
							 Peso, 
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".($descripcion_det)."', 
							 '".($comentarios_det)."', 
							 '$periodo_det', 
							 '".formatFechaAMD($fdesde_det)."', 
							 '".formatFechaAMD($fhasta_det)."', 
							 '$calificacion_det', 
							 '$peso_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Necesidad de Capacitación (Tab6)
		if (trim($detalles_tab6)) {
			$secuencia_det = 0;
			$detalle_tab6 = split(";", $detalles_tab6);
			foreach ($detalle_tab6 as $linea) {
				list($necesidad_det, $prioridad_det, $codcurso_det, $objetivo_det)=SPLIT( '[|]', $linea);
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_necesidad 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Necesidad, 
							 Prioridad, 
							 CodCurso, 
							 Objetivo,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo',
							 '$periodo',
							 '$secuencia',
							 '$persona',
							 '$codevaluador',
							 '$secuencia_det',
							 '".($necesidad_det)."',
							 '".$prioridad_det."',
							 '".$codcurso_det."',
							 '".($objetivo_det)."', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Revisión de Metas (Tab7)
		if (trim($detalles_tab7)) {
			$secuencia_det = 0;
			$detalle_tab7 = split(";", $detalles_tab7);
			foreach ($detalle_tab7 as $linea) {
				list($fecha1, $porcentaje1, $observacion1, $fecha2, $porcentaje2, $observacion2, $fecha3, $porcentaje3, $observacion3)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_revision
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Fecha1, 
							 Porcentaje1, 
							 Observacion1,
							 Fecha2, 
							 Porcentaje2, 
							 Observacion2,
							 Fecha3, 
							 Porcentaje3, 
							 Observacion3,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".formatFechaAMD($fecha1)."', 
							 '".$porcentaje1."',
							 '".($observacion1)."',  
							 '".formatFechaAMD($fecha2)."', 
							 '".$porcentaje2."',
							 '".($observacion2)."',  
							 '".formatFechaAMD($fecha3)."', 
							 '".$porcentaje3."',
							 '".($observacion3)."',  
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	//	---------------------------
	elseif ($accion == "ACTUALIZAR") {
		//	ACTUALIZO EL REGISTRO
		$sql = "UPDATE rh_evaluacionempleado 
				SET 
					ComentarioPersona = '".($comempleado)."', 
					Evaluador = '$codevaluador', 
					ComentarioEvaluador = '".($comevaluador)."', 
					Supervisor = '$codsupervisor', 
					ComentarioSupervisor = '".($comsupervisor)."', 
					FechaEvaluacion = '$fecha_evaluacion', 
					TotalDesempenio = '$desempenio', 
					TotalFuncion = '$funcion', 
					TotalMetas = '$metas', 
					Estado = '$status', 
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', 
					UltimaFecha = '$ahora' 
				WHERE 
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	INSERTO LOS DETALLES
		//	--------------------		
		//	Inserto tab Desempeño (Tab2)
		$sql = "DELETE FROM rh_empleado_evaluacion
				WHERE
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		//	-------------------------
		if (trim($detalles_tab2)) {
			$secuencia_det = 0;
			$detalle_tab2 = split(";", $detalles_tab2);
			foreach ($detalle_tab2 as $linea) {
				list($plantilla_det, $competencia_det, $calificacion_det, $peso_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_evaluacion 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Plantilla, 
							 Competencia, 
							 Calificacion, 
							 Peso, 
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '$plantilla_det', 
							 '$competencia_det', 
							 '$calificacion_det', 
							 '$peso_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Fortalezas y Debilidades (Tab4)
		$sql = "DELETE FROM rh_empleado_desempenio
				WHERE
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		//	-------------------------
		if (trim($detalles_tab4)) {
			$secuencia_det = 0;
			$detalle_tab4 = split(";", $detalles_tab4);
			foreach ($detalle_tab4 as $linea) {
				list($tipo_det, $descripcion_det)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_desempenio 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Descripcion, 
							 Tipo,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".($descripcion_det)."',
							 '$tipo_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Objetivos y Metas (Tab5)
		$sql = "DELETE FROM rh_empleado_metas
				WHERE
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		//	-------------------------
		if (trim($detalles_tab5)) {
			$secuencia_det = 0;
			$detalle_tab5 = split(";", $detalles_tab5);
			foreach ($detalle_tab5 as $linea) {
				list($descripcion_det, $periodo_det, $comentarios_det, $fdesde_det, $fhasta_det, $calificacion_det, $peso_det)=SPLIT( '[|]', $linea);
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_metas 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Descripcion, 
							 Comentarios, 
							 PeriodoMetas, 
							 Calificacion, 
							 Peso, 
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".($descripcion_det)."', 
							 '".($comentarios_det)."', 
							 '$periodo_det', 
							 '$calificacion_det', 
							 '$peso_det', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Necesidad de Capacitación (Tab6)
		$sql = "DELETE FROM rh_empleado_necesidad
				WHERE
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		//	-------------------------
		if (trim($detalles_tab6)) {
			$secuencia_det = 0;
			$detalle_tab6 = split(";", $detalles_tab6);
			foreach ($detalle_tab6 as $linea) {
				list($necesidad_det, $prioridad_det, $codcurso_det, $objetivo_det)=SPLIT( '[|]', $linea);
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_necesidad 
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Necesidad, 
							 Prioridad, 
							 CodCurso, 
							 Objetivo,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo',
							 '$periodo',
							 '$secuencia',
							 '$persona',
							 '$codevaluador',
							 '$secuencia_det',
							 '".($necesidad_det)."',
							 '".$prioridad_det."',
							 '".$codcurso_det."',
							 '".($objetivo_det)."', 
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	Inserto tab Revisión de Metas (Tab7)
		$sql = "DELETE FROM rh_empleado_revision
				WHERE
					CodOrganismo = '$forganismo' AND 
					Periodo = '$periodo' AND 
					Secuencia = '$secuencia' AND 
					CodPersona = '$persona'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		//	-------------------------
		if (trim($detalles_tab7)) {
			$secuencia_det = 0;
			$detalle_tab7 = split(";", $detalles_tab7);
			foreach ($detalle_tab7 as $linea) {
				list($fecha1, $porcentaje1, $observacion1, $fecha2, $porcentaje2, $observacion2, $fecha3, $porcentaje3, $observacion3)=SPLIT( '[|]', $linea);			
				$secuencia_det++;
				$sql = "INSERT INTO rh_empleado_revision
							(CodOrganismo, 
							 Periodo, 
							 Secuencia, 
							 CodPersona, 
							 Evaluador, 
							 SecuenciaDesempenio, 
							 Fecha1, 
							 Porcentaje1, 
							 Observacion1,
							 Fecha2, 
							 Porcentaje2, 
							 Observacion2,
							 Fecha3, 
							 Porcentaje3, 
							 Observacion3,
							 UltimoUsuario, 
							 UltimaFecha) 
						VALUES 
							('$forganismo', 
							 '$periodo', 
							 '$secuencia', 
							 '$persona', 
							 '$codevaluador', 
							 '$secuencia_det', 
							 '".formatFechaAMD($fecha1)."', 
							 '".$porcentaje1."',
							 '".($observacion1)."',  
							 '".formatFechaAMD($fecha2)."', 
							 '".$porcentaje2."',
							 '".($observacion2)."',  
							 '".formatFechaAMD($fecha3)."', 
							 '".$porcentaje3."',
							 '".($observacion3)."',  
							 '".$_SESSION['USUARIO_ACTUAL']."', 
							 '$ahora')";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	//	---------------------------
}
?>