<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {	
		case "ESTADO-CARRERAS":
			$c[0] = "AB"; $v[0] = "Abierto";
			$c[1] = "TE"; $v[1] = "Terminado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;

		case "ORDENAR-EVALUACION":
			$c[0] = "e1.CodEmpleado"; $v[0] = "Empleado";
			$c[1] = "p1.NomCompleto"; $v[1] = "Nombre";
			$c[2] = "pu1.DescripCargo"; $v[2] = "Cargo";
			$c[3] = "ee.Periodo"; $v[3] = "Periodo";
			$c[4] = "ee.Estado"; $v[4] = "Estado";
			break;

		case "ESTADO-EVALUACION":
			$c[0] = "EE"; $v[0] = "En Evaluación";
			$c[1] = "EV"; $v[1] = "Evaluado";
			break;

		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;

		case "ESTADO-VACACIONES":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CO"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			break;

		case "MODALIDAD-REQUERIMIENTO":
			$c[0] = "E"; $v[0] = "Externo";
			$c[1] = "I"; $v[1] = "Interno";
			$c[2] = "A"; $v[2] = "Ambos";
			break;

		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "EE"; $v[2] = "En Evaluación";
			$c[3] = "TE"; $v[3] = "Terminado";
			break;

		case "ESTADO-REQUERIMIENTO2":
			$c[0] = "AP"; $v[0] = "Aprobado";
			$c[1] = "EE"; $v[1] = "En Evaluación";
			$c[2] = "AP|EE"; $v[2] = "Aprobado/En Evaluación";
			break;

		case "ESTADO-POSTULANTE":
			$c[0] = "P"; $v[0] = "Postulante";
			$c[1] = "A"; $v[1] = "Aceptado";
			$c[2] = "C"; $v[2] = "Contratado";
			$c[3] = "D"; $v[3] = "Descalificado";
			break;

		case "ESTADO-CAPACITACION":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "IN"; $v[2] = "Iniciado";
			$c[3] = "TE"; $v[3] = "Terminado";
			break;

		case "ESTADO-CAPACITACION2":
			$c[0] = "AP"; $v[0] = "Aprobado";
			$c[1] = "IN"; $v[1] = "Iniciado";
			break;

		case "TIPO-CAPACITACION":
			$c[0] = "E"; $v[0] = "Externo";
			$c[1] = "I"; $v[1] = "Interno";
			break;

		case "TIPO-HABILIDAD":
			$c[0] = "H"; $v[0] = "Habilidad";
			$c[1] = "D"; $v[1] = "Destreza";
			$c[2] = "C"; $v[2] = "Capacidad";
			break;

		case "ESTADO-BONO":
			$c[0] = "A"; $v[0] = "Abierto";
			$c[1] = "C"; $v[1] = "Cerrado";
			break;

		case "ESTADO-JUBILACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "CN"; $v[1] = "Conformado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;

		case "ESTADO-PENSION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "CN"; $v[1] = "Conformado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;

		case "MOTIVO-PENSION":
			$c[0] = "I"; $v[0] = "Invalidez";
			$c[1] = "N"; $v[1] = "Incapacidad";
			$c[2] = "F"; $v[2] = "Fallecimiento";
			break;

		case "MOTIVO-PENSION2":
			$c[0] = "I"; $v[0] = "Invalidez";
			$c[1] = "N"; $v[1] = "Incapacidad";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO-EVALUACION":
			$c[0] = "EE"; $v[0] = "En Evaluación";
			$c[1] = "EV"; $v[1] = "Evaluado";
			break;

		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;

		case "ESTADO-VACACIONES":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CO"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			break;

		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "EE"; $v[2] = "En Evaluación";
			$c[3] = "TE"; $v[3] = "Terminado";
			break;

		case "ESTADO-POSTULANTE":
			$c[0] = "P"; $v[0] = "Postulante";
			$c[1] = "A"; $v[1] = "Aceptado";
			$c[2] = "C"; $v[2] = "Contratado";
			$c[3] = "D"; $v[3] = "Descalificado";
			break;

		case "ESTADO-CAPACITACION":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "IN"; $v[2] = "Iniciado";
			$c[3] = "TE"; $v[3] = "Terminado";
			break;
		
		case "ESTADO-CARRERAS":
			$c[0] = "AB"; $v[0] = "Abierto";
			$c[1] = "TE"; $v[1] = "Terminado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;

		case "TIPO-HABILIDAD":
			$c[0] = "H"; $v[0] = "Habilidad";
			$c[1] = "D"; $v[1] = "Destreza";
			$c[2] = "C"; $v[2] = "Capacidad";
			break;

		case "ESTADO-BONO":
			$c[0] = "A"; $v[0] = "Abierto";
			$c[1] = "C"; $v[1] = "Cerrado";
			break;

		case "ESTADO-JUBILACION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "CN"; $v[1] = "Conformado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;

		case "ESTADO-PENSION":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "CN"; $v[1] = "Conformado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;

		case "MOTIVO-PENSION":
			$c[0] = "I"; $v[0] = "Invalidez";
			$c[1] = "N"; $v[1] = "Incapacidad";
			$c[2] = "F"; $v[2] = "Fallecimiento";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectEvaluacion($Evaluacion) {
	$i=0;
	$sql = "SELECT
				e.Evaluacion,
				e.Descripcion,
				e.TipoEvaluacion,
				te.Descripcion As NomTipoEvaluacion
			FROM
				rh_evaluacion e
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = e.TipoEvaluacion)
			WHERE e.Estado = 'A'
			ORDER BY TipoEvaluacion, Descripcion, Evaluacion";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {	++$i;
		if ($Grupo != $field['TipoEvaluacion']) {
			$Grupo = $field['TipoEvaluacion'];
			if ($i>1) { ?></optgroup><? }
			?><optgroup label="<?=htmlentities($field['TipoEvaluacion']." ".$field['NomTipoEvaluacion'])?>"><?
		}
		if ($field[0] == $Evaluacion) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
		else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectGrupoCompetencia($Area, $TipoEvaluacion) {
	$i=0;
	if ($TipoEvaluacion != "") $filtro = " AND te.TipoEvaluacion = '".$TipoEvaluacion."'";
	$sql = "SELECT
				ea.Area,
				ea.Descripcion,
				ea.TipoEvaluacion,
				te.Descripcion As NomTipoEvaluacion
			FROM
				rh_evaluacionarea ea
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = ea.TipoEvaluacion)
			WHERE ea.Estado = 'A' $filtro
			ORDER BY TipoEvaluacion, Descripcion, Area";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {	++$i;
		if ($Grupo != $field['TipoEvaluacion']) {
			$Grupo = $field['TipoEvaluacion'];
			if ($i>1) { ?></optgroup><? }
			?><optgroup label="<?=htmlentities($field['TipoEvaluacion']." ".$field['NomTipoEvaluacion'])?>"><?
		}
		if ($field[0] == $Area) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
		else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectValoresGrado($TipoEvaluacion, $Puntaje) {
	$sql = "SELECT
				MIN(gc.PuntajeMin) AS Min,
				(SELECT MAX(PuntajeMax) FROM rh_gradoscompetencia WHERE TipoEvaluacion = gc.TipoEvaluacion) AS Max
			FROM rh_gradoscompetencia gc
			WHERE TipoEvaluacion = '".$TipoEvaluacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$field = mysql_fetch_array($query);
	for($i=$field['Min']; $i<=$field['Max']; $i++) {
        if ($Puntaje == $i) { ?><option value="<?=$i?>" selected="selected"><?=$i?></option><? }
		else { ?><option value="<?=$i?>"><?=$i?></option><? }
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectEvaluaciones($CodOrganismo, $Secuencia) {
	$sql = "SELECT Secuencia, Descripcion
			FROM rh_evaluacionperiodo
			WHERE CodOrganismo = '".$CodOrganismo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		if ($field[0] == $Secuencia) { ?><option value="<?=$field[0]?>" selected="selected"><?=htmlentities($field[1])?></option><? }
		else { ?><option value="<?=$field[0]?>"><?=htmlentities($field[1])?></option><? }
	}
}

//	
function getDiffHoraEventos($CodHorario, $Fecha, $Desde, $Hasta) {
	if ($CodHorario != "") {
		//	consulto el horario 
		$DiaSemana = getWeekDay($Fecha);
		$sql = "SELECT *
				FROM rh_horariolaboraldet
				WHERE
					CodHorario = '".$CodHorario."' AND
					Dia = '".$DiaSemana."' AND
					FlagLaborable = 'S'";
		$query_horario = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_horario) != 0) $field_horario = mysql_fetch_array($query_horario);
		
		//	si tiene salida y no entrada
		if ($Desde && !$Hasta) {
			//	si la salida es en el primer turno
			if ($Desde >= $field_horario['Entrada1'] && $Desde < $field_horario['Salida1']) {
				$turno1 = getDiffHora($Desde, $field_horario['Salida1']);
				$turno2 = getDiffHora($field_horario['Entrada2'], $field_horario['Salida2']);
			}
			//	si la salida es en el segundo turno
			elseif ($Desde >= $field_horario['Entrada2'] && $Desde < $field_horario['Salida2']) {
				$turno1 = "00:00:00";
				$turno2 = getDiffHora($Desde, $field_horario['Salida2']);
				
			}
		}
		//	si tiene salida y entrada
		elseif ($Desde && $Hasta) {
			//	si la salida y la entrada estan en el mismo turno
			if (($Desde >= $field_horario['Entrada1'] && $Desde < $field_horario['Salida1'] && $Hasta >= $field_horario['Entrada1'] && $Hasta <= $field_horario['Salida1']) || ($Desde >= $field_horario['Entrada2'] && $Desde < $field_horario['Salida2'] && $Hasta >= $field_horario['Entrada2'] && $Hasta <= $field_horario['Salida2'])) {
				$turno1 = getDiffHora($Desde, $Hasta);
				$turno2 = "00:00:00";
			}
			//	si la salida es en el turno de la mañana y la entrada en el turno de la tarde
			elseif ($Desde >= $field_horario['Entrada1'] && $Desde < $field_horario['Salida1'] && $Hasta > $field_horario['Entrada2'] && $Hasta <= $field_horario['Salida2']) {
				$turno1 = getDiffHora($Desde, $field_horario['Salida1']);
				$turno2 = getDiffHora($field_horario['Entrada2'], $Hasta);
			}
		}
		//	si no tiene salida y si entrada
		elseif (!$Desde && $Hasta) {
			//	si la salida es en el primer turno
			if ($Hasta > $field_horario['Entrada1'] && $Hasta < $field_horario['Salida1']) {
				$turno1 = getDiffHora($field_horario['Entrada1'], $Hasta);
				$turno2 = "00:00:00";
			}
			//	si la salida es en el segundo turno
			elseif ($Hasta > $field_horario['Entrada2'] && $Hasta < $field_horario['Salida2']) {
				$turno1 = "00:00:00";
				$turno1 = getDiffHora($field_horario['Entrada2'], $Hasta);
			}
		}
		
		$total = sumarHoras($turno1, $turno2);
		return "$total";
	} else {
		return getDiffHora($Desde, $Hasta);
	}
}
?>