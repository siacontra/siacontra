<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	--------------------------
if ($accion == "desarrollo_carreras_evaluacion_sel") {
	echo "|";
	//	nivel academico
	$sql = "SELECT
				ei.Secuencia,
				ei.FechaGraduacion,
				ei.CodGradoInstruccion,
				ei.Area,
				ei.CodProfesion,
				ei.Nivel,
				ei.CodCentroEstudio,
				gi.Descripcion AS NomGradoInstruccion,
				md.Descripcion AS NomArea,
				p.Descripcion AS NomProfesion
			FROM
				rh_empleado_instruccion ei
				INNER JOIN rh_gradoinstruccion gi ON (ei.CodGradoInstruccion = gi.CodGradoInstruccion)
				LEFT JOIN mastmiscelaneosdet md ON (ei.Area = md.CodDetalle AND CodMaestro = 'AREA')
				LEFT JOIN rh_profesiones p ON (ei.CodProfesion = p.CodProfesion)
			WHERE ei.CodPersona = '".$CodPersona."'
			ORDER BY FechaGraduacion DESC, Secuencia";
	$query_nivel = mysql_query($sql) or die($sql.mysql_error());
	while ($field_nivel = mysql_fetch_array($query_nivel)) {
		$nronivel++;
		if ($field_nivel['CodProfesion'] != "") $Profesion = $field_nivel['NomProfesion'];
		else $Profesion = $field_nivel['NomGradoInstruccion']." EN ".$field_nivel['NomArea'];
		?>
		<tr class="trListaBody">
			<th>
				<?=$nronivel?>
			</th>
			<td>
				<?=$Profesion?>
			</td>
			<td align="center">
				<?=formatFechaDMA($field_nivel['FechaGraduacion'])?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	cursos realizados en el area
	$sql = "SELECT
				ec.*,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_cursos ec
				INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
			WHERE
				ec.CodPersona = '".$CodPersona."' AND
				ec.FlagArea = 'S'
			ORDER BY FechaCulminacion DESC, Secuencia";
	$query_cursosa = mysql_query($sql) or die($sql.mysql_error());
	while ($field_cursosa = mysql_fetch_array($query_cursosa)) {
		$nrocursosa++;
		if ($field_cursosa['CodProfesion'] != "") $Profesion = $field_cursosa['NomProfesion'];
		else $Profesion = $field_cursosa['NomGradoInstruccion']." EN ".$field_cursosa['NomArea'];
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrocursosa?>
			</th>
			<td>
				<?=$field_cursosa['NomCurso']?>
			</td>
			<td align="center">
				<?=($field_cursosa['FechaCulminacion'])?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	cursos realizados en formacion general
	$sql = "SELECT
				ec.*,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_cursos ec
				INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
			WHERE
				ec.CodPersona = '".$CodPersona."' AND
				ec.FlagArea = 'N'
			ORDER BY FechaCulminacion DESC, Secuencia";
	$query_cursosfg = mysql_query($sql) or die($sql.mysql_error());
	while ($field_cursosfg = mysql_fetch_array($query_cursosfg)) {
		$nrocursosfg++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrocursosfg?>
			</th>
			<td>
				<?=$field_cursosfg['NomCurso']?>
			</td>
			<td align="center">
				<?=($field_cursosfg['FechaCulminacion'])?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	competencias conductuales adquiridas
	$sql = "SELECT
				ef.Competencia,
				ef.Descripcion,
				ef.ValorRequerido,
				ef.ValorMinimo,
				ef.Estado,
				ee.Calificacion,
				fv.Explicacion,
				fv.Explicacion2
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
				INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
														 ee.Periodo = eve.Periodo AND
														 ee.Secuencia = eve.Secuencia AND
														 ee.CodPersona = eve.CodPersona AND
														 ee.Evaluador = eve.Evaluador)
				LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
												ee.Calificacion = fv.Grado)
			WHERE
				ef.Estado = 'A' AND
				ee.CodPersona = '".$CodPersona."' AND
				ee.Calificacion >= ef.ValorMinimo AND
				eve.Estado = 'EV'
			ORDER BY Competencia";
	$query_competenciasca = mysql_query($sql) or die($sql.mysql_error());
	while ($field_competenciasca = mysql_fetch_array($query_competenciasca)) {
		$nrocompetenciasca++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrocompetenciasca?>
			</th>
			<td>
				<strong><?=$field_competenciasca['Descripcion']?></strong><br />
                <?=$field_competenciasca['Explicacion']?><br />
                <?=$field_competenciasca['Explicacion2']?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	competencias conductuales por adquirir
	$sql = "SELECT
				ef.Competencia,
				ef.Descripcion,
				ef.ValorRequerido,
				ef.ValorMinimo,
				ef.Estado,
				ee.Calificacion,
				fv.Explicacion,
				fv.Explicacion2
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
				INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
														 ee.Periodo = eve.Periodo AND
														 ee.Secuencia = eve.Secuencia AND
														 ee.CodPersona = eve.CodPersona AND
														 ee.Evaluador = eve.Evaluador)
				LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
												ee.Calificacion = fv.Grado)
			WHERE
				ef.Estado = 'A' AND
				ee.CodPersona = '".$CodPersona."' AND
				ee.Calificacion < ef.ValorMinimo AND
				eve.Estado = 'EV'
			ORDER BY Competencia";
	$query_competenciascgpa = mysql_query($sql) or die($sql.mysql_error());
	while ($field_competenciascgpa = mysql_fetch_array($query_competenciascgpa)) {
		$nrocompetenciascgpa++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrocompetenciascgpa?>
			</th>
			<td>
				<strong><?=$field_competenciascgpa['Descripcion']?></strong><br />
                <?=$field_competenciascgpa['Explicacion']?><br />
                <?=$field_competenciascgpa['Explicacion2']?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	habllidades y destrezas tecnicas adquiridas
	$sql = "SELECT
				ed.Descripcion
			FROM
				rh_empleado_desempenio ed
				INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
														ed.Periodo = ee.Periodo AND
														ed.Secuencia = ee.Secuencia AND
														ed.CodPersona = ee.CodPersona AND
														ed.Evaluador = ee.Evaluador)
			WHERE
				ed.CodPersona = '".$CodPersona."' AND
				ed.Tipo = 'F' AND
				ee.Estado = 'EV'
			ORDER BY ed.SecuenciaDesempenio";
	$query_fortalezasa = mysql_query($sql) or die($sql.mysql_error());
	while ($field_fortalezasa = mysql_fetch_array($query_fortalezasa)) {
		$nrofortalezasa++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrofortalezasa?>
			</th>
			<td>
				<?=$field_fortalezasa['Descripcion']?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	habllidades y destrezas tecnicas por adquirir
	$sql = "SELECT
				ed.Descripcion
			FROM
				rh_empleado_desempenio ed
				INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
														ed.Periodo = ee.Periodo AND
														ed.Secuencia = ee.Secuencia AND
														ed.CodPersona = ee.CodPersona AND
														ed.Evaluador = ee.Evaluador)
			WHERE
				ed.CodPersona = '".$CodPersona."' AND
				ed.Tipo = 'D' AND
				ee.Estado = 'EV'
			ORDER BY ed.SecuenciaDesempenio";
	$query_fortalezaspa = mysql_query($sql) or die($sql.mysql_error());
	while ($field_fortalezaspa = mysql_fetch_array($query_fortalezaspa)) {
		$nrofortalezaspa++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrofortalezaspa?>
			</th>
			<td>
				<?=$field_fortalezaspa['Descripcion']?>
			</td>
		</tr>
		<?
	}
	echo "|";
	//	capacitacion requeridas para desarrollar competencias conductuales
	$sql = "SELECT
				en.Necesidad,
				en.Objetivo,
				en.Prioridad,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_necesidad en
				INNER JOIN rh_cursos c ON (en.CodCurso = c.CodCurso)
				INNER JOIN rh_evaluacionempleado ee ON (en.CodOrganismo = ee.CodOrganismo AND
														en.Periodo = ee.Periodo AND
														en.Secuencia = ee.Secuencia AND
														en.CodPersona = ee.CodPersona AND
														en.Evaluador = ee.Evaluador)
			WHERE
				en.CodPersona = '".$CodPersona."' AND
				ee.Estado = 'EV'
			ORDER BY en.SecuenciaDesempenio";
	$query_capacitacioncc = mysql_query($sql) or die($sql.mysql_error());
	while ($field_capacitacioncc = mysql_fetch_array($query_capacitacioncc)) {
		$nrocapacitacioncc++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nrocapacitacioncc?>
            </th>
            <td>
                <strong><?=$field_capacitacioncc['Necesidad']?></strong><br />
                <?=$field_capacitacioncc['Objetivo']?>
            </td>
            <td>
                <?=$field_capacitacioncc['NomCurso']?>
            </td>
            <td align="center">
                <?=strtoupper(printValoresGeneral("PRIORIDAD", $field_capacitacioncc['Prioridad']))?>
            </td>
		</tr>
		<?
	}
	echo "|";
	//	consulto experiencia
    $sql = "SELECT
    			enh.Secuencia,
    			enh.Fecha,
    			enh.Cargo,
    			enh.TipoAccion,
    			en.CodCargo,
    			en.FechaHasta
			FROM
				rh_empleadonivelacionhistorial enh
				INNER JOIN rh_empleadonivelacion en ON (en.CodPersona = enh.CodPersona AND en.Secuencia = enh.Secuencia)
			WHERE enh.CodPersona = '".$CodPersona."'
			ORDER BY Secuencia";
	$query_experiencia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_experiencia=0;
	while($field_experiencia = mysql_fetch_array($query_experiencia)) {
		$_DESDE = formatFechaDMA($field_experiencia['Fecha']);
		if ($field_experiencia['FechaHasta'] == '0000-00-00') $_HASTA = $FechaActual; else $_HASTA = formatFechaDMA($field_experiencia['FechaHasta']);
		list($A, $M, $D) = getTiempo($_DESDE, $_HASTA);
		?>
        <tr class="trListaBody">
    		<th>
				<?=++$nro_experiencia?>
        	</th>
        	<td align="center">
				<?=$_DESDE?>
    	    </td>
        	<td>
				<?=htmlentities($field_experiencia['Cargo'])?>
    	    </td>
        	<td>
				<?=htmlentities($field_experiencia['TipoAccion'])?>
    	    </td>
        	<td align="center">
				<?=$A?>
    	    </td>
        	<td align="center">
				<?=$M?>
    	    </td>
        	<td align="center">
				<?=$D?>
    	    </td>
	    </tr>
        <?
	}
}
//	--------------------------

//	--------------------------
elseif ($accion == "desarrollo_carreras_capacitacion_tecnica_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_captecnica');" id="captecnica_<?=$nrodetalle?>">
    	<th>
			<?=$nrodetalle?>
        </th>
        <td>
        	<textarea name="Descripcion" class="cell" style="height:30px;"></textarea>
        </td>
    </tr>
    <?
}
//	--------------------------

//	--------------------------
elseif ($accion == "desarrollo_carreras_habilidad_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_habilidad');" id="habilidad_<?=$nrodetalle?>">
    	<th>
			<?=$nrodetalle?>
        </th>
        <td>
        	<select name="Tipo" class="cell">
            	<?=loadSelectValores("TIPO-HABILIDAD", "H", 0)?>
            </select>
        </td>
        <td>
        	<textarea name="Descripcion" class="cell" style="height:30px;"></textarea>
        </td>
    </tr>
    <?
}
//	--------------------------

//	--------------------------
elseif ($accion == "desarrollo_carreras_evaluacion_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_evaluacion');" id="evaluacion_<?=$nrodetalle?>">
    	<th>
			<?=$nrodetalle?>
        </th>
        <td>
        	<textarea name="Descripcion" class="cell" style="height:30px;"></textarea>
        </td>
    </tr>
    <?
}
//	--------------------------

//	--------------------------
elseif ($accion == "desarrollo_carreras_metas_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_metas');" id="metas_<?=$nrodetalle?>">
    	<th>
			<?=$nrodetalle?>
        </th>
        <td>
        	<textarea name="Descripcion" class="cell" style="height:30px;"></textarea>
        </td>
    </tr>
    <?
}
//	--------------------------

//	--------------------------
elseif ($accion == "obtenerFechaTerminoVacacion") {
	$FechaTermino = getFechaFinHabiles($FechaSalida, $NroDias);
	$FechaIncorporacion = getFechaFinHabiles($FechaSalida, $NroDias+1);
	echo "$FechaTermino|$FechaIncorporacion";
}
//	--------------------------

//	consulto si se puede modificar una solicitud de vacaciones
elseif ($accion == "vacaciones_modificar") {
	list($Anio, $CodSolicitud) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_vacacionsolicitud
			WHERE
				Anio = '".$Anio."' AND
				CodSolicitud = '".$CodSolicitud."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("No se puede modificar esta solicitud");
		elseif ($field['Estado'] == "AP") die("No se puede modificar esta solicitud");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar una solicitud de vacaciones
elseif ($accion == "vacaciones_anular") {
	list($Anio, $CodSolicitud) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_vacacionsolicitud
			WHERE
				Anio = '".$Anio."' AND
				CodSolicitud = '".$CodSolicitud."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("Solicitud ya se encuentra anulada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar una solicitud de vacaciones
elseif ($accion == "setTipoContrato") {
	$sql = "SELECT FlagVencimiento
			FROM rh_tipocontrato
			WHERE TipoContrato = '".$TipoContrato."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "$field[FlagVencimiento]|";
}
//	--------------------------

//	consulto si se puede modificar una solicitud de vacaciones
elseif ($accion == "requerimientos_modificar") {
	list($CodOrganismo, $Requerimiento) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_requerimiento
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				Requerimiento = '".$Requerimiento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PE") die("No se puede modificar este requerimiento");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	muestro los periodos disponibles
elseif ($accion == "vacaciones_periodos_insertar") {
	//	empleado
	$sql = "SELECT Fingreso, CodTipoNom
			FROM mastempleado
			WHERE CodPersona = '".$CodPersona."'";
	$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
	
	//	obtengo los valores almacenados del empleado para el periodo
	$sql = "SELECT
				NroPeriodo,
				Anio,
				Mes,
				Derecho,
				PendientePeriodo,
				DiasGozados,
				DiasTrabajados,
				DiasInterrumpidos,
				DiasNoGozados,
				TotalUtilizados,
				Pendientes
			FROM rh_vacacionperiodo
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodTipoNom = '".$field_empleado['CodTipoNom']."'";
	$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	
	$i=0;
	
	while ($field_periodo = mysql_fetch_array($query_periodo)) {
		$NroPeriodo[$i] = $field_periodo['NroPeriodo'];
		$Anio[$i] = $field_periodo['Anio'];
		$Mes[$i] = $field_periodo['Mes'];
		$Derecho[$i] = $field_periodo['Derecho'];
		$PendientePeriodo[$i] = $field_periodo['PendientePeriodo'];
		$DiasGozados[$i] = $field_periodo['DiasGozados'];
		$DiasTrabajados[$i] = $field_periodo['DiasTrabajados'];
		$DiasInterrumpidos[$i] = $field_periodo['DiasInterrumpidos'];
		$DiasNoGozados[$i] = $field_periodo['DiasNoGozados'];
		$TotalUtilizados[$i] = $field_periodo['TotalUtilizados'];
		$Pendientes[$i] = $field_periodo['Pendientes'];
		$i++;
	}
	
	//	tiempo de servicio
	$_FechaInicial = obtenerFechaFin(formatFechaDMA($field_empleado['Fingreso']), -$_PARAMETRO['VACVENDIAS']);
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	list($AnioIngreso, $MesIngreso, $DiaIngreso) = split("[/.-]", $field_empleado['Fingreso']);
	list($Anios, $Meses, $Dias) = getTiempo($_FechaInicial, "$DiaActual-$MesActual-$AnioActual");
	$NroPeriodos = $Anios;
	
	//	recorro los periodos y almaceno
	$FechaInicio = $FechaSalida;
	$Distribucion = $NroDias;
	$Quinquenios = 0;
	$Pendiente = 0;
	$Seleccionable = false;
	
	

	for($i=0; $i<$NroPeriodos; $i++) {
	
		
		$Anio[$i] = $AnioIngreso + $i;
		
		if ($NroPeriodo[$i] == "") {
				
			$NroPeriodo[$i] = $i + 1;
			$Mes[$i] = $MesIngreso;
			##	obtengo los dias de derecho
			if ($i > 0 && $i % 5 == 0) ++$Quinquenios;
			$Derecho[$i] = $_PARAMETRO['DERECHO'] + $i + $Quinquenios;
			$PendientePeriodo[$i] += $Pendientes[$i-1];
			$DiasGozados[$i] = 0;
			$DiasTrabajados[$i] = 0;
			$DiasInterrumpidos[$i] = 0;
			$TotalUtilizados[$i] = 0;
			
		}
		
		$Pendientes[$i] = $Derecho[$i] - $TotalUtilizados[$i] + $DiasInterrumpidos[$i];
	
		if ($Pendientes[$i] > 0 && $Distribucion > 0) {
			if ($Pendientes[$i] > $Distribucion) $Dias = $Distribucion; else $Dias = $Pendientes[$i];
			$Distribucion -= $Dias;
			$FechaFin = getFechaFinHabiles($FechaInicio, $Dias);
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="<?=$NroPeriodo[$i]?>">
				<th>
				   <input type="text" name="NroPeriodo" id="NroPeriodo_<?=$i?>" class="cell2" style="text-align:center;" value="<?=$NroPeriodo[$i]?>" readonly />
				</th>
				<td align="center">
				   <input type="checkbox" name="FlagUtlizarPeriodo" checked="checked" disabled="disabled" />
				</td>
				<td align="center"><?=$Anio[$i]?> - <?=$Anio[$i]+1?></td>
				<td>
				   <input type="text" name="NroDias" id="NroDias_<?=$i?>" class="cell" style="text-align:right;" value="<?=number_format($Dias, 2, ',', '.')?>" onchange="obtenerFechaTerminoVacacionDetalle('<?=$i?>');" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" disabled="disabled" />
				</td>
				<td>
				   <input type="text" name="FechaInicio" id="FechaInicio_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" onkeyup="setFechaDMA(this);" value="<?=$FechaInicio?>" onchange="obtenerFechaTerminoVacacionDetalle('<?=$i?>');" disabled="disabled" />
				</td>
				<td>
				   <input type="text" name="FechaFin" id="FechaFin_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" onkeyup="setFechaDMA(this);" value="<?=$FechaFin?>" disabled="disabled" />
				</td>
				<td>
				   <input type="text" name="Derecho" id="Derecho_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($Derecho[$i], 2, ',', '.')?>" readonly />
				</td>
				<td>
				   <input type="text" name="TotalUtilizados" id="TotalUtilizados_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($v, 2, ',', '.')?>" readonly />
				</td>
				<td>
				   <input type="text" name="Pendientes" id="Pendientes_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($Pendientes[$i], 2, ',', '.')?>" readonly />
				</td>
				<td>
					<textarea name="Observaciones" class="cell" style="height:20px;" disabled="disabled"></textarea>
					<input type="hidden" name="Secuencia" />
				</td>
			</tr>
			<?
			$FechaInicio = getFechaFinHabiles($FechaFin, 2);
		}
	}
}
//	--------------------------

//	inserto linea
elseif ($accion == "capacitaciones_horario_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_horario');" id="horario_<?=$nro_detalle?>">
    	<th width="20">
        	<?=$nro_detalle?>
        </th>
    	<td>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                	<td width="75" style="border:none;">Estado:</td>
                    <td style="border:none;" width="100">
                        <select name="Estado" style="width:100px;">
                            <?=loadSelectGeneral("ESTADO", "A", 0)?>
                        </select>
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	L <input type="checkbox" name="Lunes" onclick="chkCampos(this.checked, 'HoraInicioLunes_<?=$nro_detalle?>', 'HoraFinLunes_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	M <input type="checkbox" name="Martes" onclick="chkCampos(this.checked, 'HoraInicioMartes_<?=$nro_detalle?>', 'HoraFinMartes_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	M <input type="checkbox" name="Miercoles" onclick="chkCampos(this.checked, 'HoraInicioMiercoles_<?=$nro_detalle?>', 'HoraFinMiercoles_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	J <input type="checkbox" name="Jueves" onclick="chkCampos(this.checked, 'HoraInicioJueves_<?=$nro_detalle?>', 'HoraFinJueves_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	V <input type="checkbox" name="Viernes" onclick="chkCampos(this.checked, 'HoraInicioViernes_<?=$nro_detalle?>', 'HoraFinViernes_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	S <input type="checkbox" name="Sabado" onclick="chkCampos(this.checked, 'HoraInicioSabado_<?=$nro_detalle?>', 'HoraFinSabado_<?=$nro_detalle?>');" />
                    </td>
                    <td width="50" style="border:none;" align="center">
                    	D <input type="checkbox" name="Domingo" onclick="chkCampos(this.checked, 'HoraInicioDomingo_<?=$nro_detalle?>', 'HoraFinDomingo_<?=$nro_detalle?>');" />
                    </td>
                    <td width="150" style="border:none;">
                    	Total
                    </td>
                </tr>
                <tr>
                	<td style="border:none;">Desde:</td>
                	<td style="border:none;">
                    	<input type="text" name="FechaDesde" id="FechaDesde_<?=$nro_detalle?>" maxlength="10" class="datepicker" style="width:95px;" onkeyup="setFechaDMA(this);" onchange="obtenerFechaFin($('#FechaDesde_<?=$nro_detalle?>'), $('#FechaHasta_<?=$nro_detalle?>'), 7);" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioLunes" id="HoraInicioLunes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioMartes" id="HoraInicioMartes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioMiercoles" id="HoraInicioMiercoles_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioJueves" id="HoraInicioJueves_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioViernes" id="HoraInicioViernes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioSabado" id="HoraInicioSabado_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraInicioDomingo" id="HoraInicioDomingo_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="TotalDias" maxlength="5" style="width:100px;" /> <i>dias</i>
                    </td>
                </tr>
                <tr>
                	<td style="border:none;">Hasta:</td>
                	<td style="border:none;">
                    	<input type="text" name="FechaHasta" id="FechaHasta_<?=$nro_detalle?>" maxlength="10" class="datepicker" style="width:95px;" onkeyup="setFechaDMA(this);" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinLunes" id="HoraFinLunes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinMartes" id="HoraFinMartes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinMiercoles" id="HoraFinMiercoles_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinJueves" id="HoraFinJueves_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinViernes" id="HoraFinViernes_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinSabado" id="HoraFinSabado_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="HoraFinDomingo" id="HoraFinDomingo_<?=$nro_detalle?>" maxlength="11" style="width:50px; text-align:center;" disabled="disabled" />
                    </td>
                	<td style="border:none;">
                    	<input type="text" name="TotalHoras" maxlength="5" style="width:100px;" /> <i>horas</i>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	<?
}
//	--------------------------

//	consulto si se puede modificar un registro
elseif ($accion == "capacitaciones_iniciar") {
	list($Anio, $CodOrganismo, $Capacitacion) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_capacitacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				Capacitacion = '".$Capacitacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "IN") die("Capacitaci&oacute;n ya iniciada");
		elseif ($field['Estado'] == "TE") die("Capacitaci&oacute;n ya terminada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar un registro
elseif ($accion == "capacitaciones_terminar") {
	list($Anio, $CodOrganismo, $Capacitacion) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_capacitacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				Capacitacion = '".$Capacitacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "TE") die("Capacitaci&oacute;n ya terminada");
		elseif ($field['Estado'] != "IN") die("Capacitaci&oacute;n no ha sido iniciada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	inserto linea
elseif ($accion == "capacitaciones_gastos_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_gastos');" id="gastos_<?=$nro_detalle?>">
    	<th>
        	<?=$nro_detalle?>
        </th>
        <td>
            <input type="text" name="Numero" class="cell" maxlength="15" />
        </td>
        <td>
            <input type="text" name="Fecha" class="cell datepicker" style="text-align:center;" maxlength="10" onkeyup="setFechaDMA(this);" />
        </td>
        <td>
            <input type="text" name="SubTotal" id="SubTotal_<?=$nro_detalle?>" class="cell" style="text-align:right;" value="0,00" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="capacitaciones_gastos_total('<?=$nro_detalle?>');" />
        </td>
        <td>
            <input type="text" name="Impuestos" id="Impuestos_<?=$nro_detalle?>" class="cell" style="text-align:right;" value="0,00" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="capacitaciones_gastos_total('<?=$nro_detalle?>');" />
        </td>
        <td>
            <input type="text" name="Total" id="Total_<?=$nro_detalle?>" class="cell2" style="text-align:right;" value="0,00" readonly="readonly" />
        </td>
    </tr>
	<?
}
//	--------------------------v

//	inserto linea
elseif ($accion == "evaluacion_tipo_grados_insertar") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_grados');" id="gastos_<?=$nro_detalle?>">
    	<th>
        	<?=$nro_detalle?>
        </th>
        <td>
            <input type="text" name="Grado" class="cell" style="text-align:center;" maxlength="4" />
        </td>
        <td>
            <input type="text" name="Descripcion" class="cell" maxlength="50" />
        </td>
        <td>
            <input type="text" name="PuntajeMin" class="cell" style="text-align:center;" maxlength="4" />
        </td>
        <td>
            <input type="text" name="PuntajeMax" class="cell" style="text-align:center;" maxlength="4" />
        </td>
        <td>
            <select name="Estado" class="cell">
                <?=loadSelectGeneral("ESTADO", "A", 0)?>
            </select>
        </td>
    </tr>
	<?
}
//	--------------------------

//	consulto si se puede modificar un registro
elseif ($accion == "capacitaciones_iniciar") {
	list($Anio, $CodOrganismo, $Capacitacion) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM rh_capacitacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				Capacitacion = '".$Capacitacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "IN") die("Capacitaci&oacute;n ya iniciada");
		elseif ($field['Estado'] == "TE") die("Capacitaci&oacute;n ya terminada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	muestro lineas
elseif ($accion == "evaluacion_items_grados") {
	//	consulto datos generales
	$sql = "SELECT
				gc.Grado,
				gc.Descripcion,
				gc.PuntajeMin,
				gc.PuntajeMax
			FROM
				rh_gradoscompetencia gc
				INNER JOIN rh_evaluacion e ON (e.TipoEvaluacion = gc.TipoEvaluacion)
			WHERE e.Evaluacion = '".$Evaluacion."'
			ORDER BY Grado";
	$query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_grados = mysql_fetch_array($query_grados)) {
		?>
		<tr class="trListaBody">
			<th align="center">
				<?=++$nro_grados?>
			</th>
			<td align="center">
				<?=$field_grados['Grado']?>
			</td>
			<td>
				<?=htmlentities($field_grados['Descripcion'])?>
			</td>
			<td align="center">
				<?=$field_grados['PuntajeMin']?>
			</td>
			<td align="center">
				<?=$field_grados['PuntajeMax']?>
			</td>
		</tr>
		<?
	}
}
//	--------------------------

//	--------------------------
elseif ($accion == "getDiasBonoPeriodo") {

	$ValorDia = str_replace(',', '.',$ValorDia);
	
	$TotalDiasPeriodo = getFechaDias($FechaInicio, $FechaFin) + 1;
	$TotalFeriados = getDiasFeriados($FechaInicio, $FechaFin); //0;//
	
	//SE MODIFICO ESTA PARTE PARA PAGAR LOS DIAS QUE TENGA EL MES
	//$TotalDiasPago = getDiasHabiles($FechaInicio, $FechaFin);
	$TotalDiasPago = $TotalDiasPeriodo;
	$ValorMes = $ValorDia * $TotalDiasPago;
//echo $ValorDia;
	echo "$TotalDiasPeriodo|$TotalFeriados|$TotalDiasPago|".number_format($ValorMes, 2, ',', '.');
}
//	--------------------------

//	--------------------------
elseif ($accion == "bono_periodos_registrar_eventos_insertar") {
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	$FechaActual = "$DiaActual-$MesActual-$AnioActual";
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_eventos');" id="eventos_<?=$secuencia?>_<?=$nrodetalle?>">
    	<th>
			<?=$nrodetalle?>
        </th>
        <td>
            <select name="Fecha" id="Fecha_<?=$secuencia?>_<?=$nrodetalle?>" class="cell" onChange="getDiffHoraEventos('<?=$secuencia?>_<?=$nrodetalle?>');">
                <option value="">&nbsp;</option>
                <?=getFechaEventos($FechaInicio, $FechaFin, "", 0)?>
            </select>
        </td>
        <td>
            <input type="text" name="HoraSalida" id="HoraSalida_<?=$secuencia?>_<?=$nrodetalle?>" class="cell" style="text-align:center;" maxlength="11" onChange="getDiffHoraEventos('<?=$secuencia?>_<?=$nrodetalle?>');" />
        </td>
        <td>
            <input type="text" name="HoraEntrada" id="HoraEntrada_<?=$secuencia?>_<?=$nrodetalle?>" class="cell" style="text-align:center;" maxlength="11" onChange="getDiffHoraEventos('<?=$secuencia?>_<?=$nrodetalle?>');" />
        </td>
        <td>
            <input type="text" name="TotalHoras" id="TotalHoras_<?=$secuencia?>_<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="0:0" readonly />
        </td>
        <td>
            <select name="Motivo" class="cell">
                <option value="">&nbsp;</option>
                <?=getMiscelaneos('', "PERMISOS", 0)?>
            </select>
        </td>
        <td>
            <select name="TipoEvento" class="cell">
                <option value="">&nbsp;</option>
                <?=getMiscelaneos('', "TIPOFALTAS", 0)?>
            </select>
        </td>
        <td>
            <textarea name="Observaciones" class="cell" style="height:15px;"></textarea>
        </td>
    </tr>
    <?
}
//	--------------------------

//	--------------------------
elseif ($accion == "bono_periodos_registrar_eventos_control") {
	list($CodEvento, $CodPersona) = split("[_]", $registro);
	//	consulto el empleado
	$sql = "SELECT CodHorario FROM mastempleado WHERE CodPersona = '".$CodPersona."'";
	$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
	
	//	consulto el evento
	$sql = "SELECT *
			FROM rh_controlasistencia
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodEvento = '".$CodEvento."'";
	$query_evento = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_evento = mysql_fetch_array($query_evento)) {
		if (trim($field_evento['Event_Puerta']) == "Interior") {
			$HoraEntrada = formatHora12($field_evento['HoraFormat'], true);
			$Hasta = $field_evento['HoraFormat'];
			$HoraSalida = "";
			$Desde = "";
		} else {
			$HoraSalida = formatHora12($field_evento['HoraFormat'], true);
			$Desde = $field_evento['HoraFormat'];
			##	consulto para ingresar la hora de entrada si tiene
			$sql = "SELECT *
					FROM rh_controlasistencia
					WHERE
						CodPersona = '".$CodPersona."' AND
						FechaFormat = '".$field_evento['FechaFormat']."' AND
						HoraFormat > '".$field_evento['HoraFormat']."' AND
						Event_Puerta = 'Interior'
					ORDER BY HoraFormat
					LIMIT 0, 1";
			$query_entrada = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_entrada) != 0) {
				$field_entrada = mysql_fetch_array($query_entrada);
				$HoraEntrada = formatHora12($field_entrada['HoraFormat'], true);
				$Hasta = $field_entrada['HoraFormat'];
			} else {
				$HoraEntrada = "";
				$Hasta = "";
			}
		}
		$TotalHoras = getDiffHoraEventos($field_empleado['CodHorario'], formatFechaDMA($field_evento['FechaFormat']), $Desde, $Hasta);
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_eventos');" id="eventos_<?=$secuencia?>_<?=$nrodetalle?>">
			<th>
				<?=$nrodetalle?>
			</th>
			<td>
				<select name="Fecha" id="Fecha_<?=$nrodetalle?>" class="cell" onChange="getDiffHoraEventos('<?=$nrodetalle?>');">
					<option value="">&nbsp;</option>
					<?=getFechaEventos($FechaInicio, $FechaFin, formatFechaDMA($field_evento['FechaFormat']), 0)?>
				</select>
			</td>
			<td>
				<input type="text" name="HoraSalida" id="HoraSalida_<?=$nrodetalle?>" class="cell" style="text-align:center;" value="<?=$HoraSalida?>" maxlength="11" onChange="getDiffHoraEventos('<?=$nrodetalle?>');" />
			</td>
			<td>
				<input type="text" name="HoraEntrada" id="HoraEntrada_<?=$nrodetalle?>" class="cell" style="text-align:center;" value="<?=$HoraEntrada?>" maxlength="11" onChange="getDiffHoraEventos('<?=$nrodetalle?>');" />
			</td>
			<td>
				<input type="text" name="TotalHoras" id="TotalHoras_<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$TotalHoras?>" readonly />
			</td>
			<td>
				<select name="TipoEvento" class="cell">
					<option value="">&nbsp;</option>
					<?=getMiscelaneos('', "PERMISOS", 0)?>
				</select>
			</td>
			<td>
				<select name="Motivo" class="cell">
					<option value="">&nbsp;</option>
					<?=getMiscelaneos('', "TIPOFALTAS", 0)?>
				</select>
			</td>
			<td>
				<textarea name="Observaciones" class="cell" style="height:15px;"></textarea>
			</td>
		</tr>
		<?
	}
}
//	--------------------------

//	--------------------------
elseif ($accion == "getDiffHoraEventos") {
	echo getDiffHoraEventos($CodHorario, $Fecha, $Desde, $Hasta);
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "bono_periodos_modificar") {
	list($Anio, $CodOrganismo, $CodBonoAlim) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM rh_bonoalimentacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				CodBonoAlim = '".$CodBonoAlim."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "A") die("No se puede modificar este Periodo");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "bono_periodos_cerrar") {
	list($Anio, $CodOrganismo, $CodBonoAlim) = split("[_]", $codigo);
	$sql = "SELECT Estado
			FROM rh_bonoalimentacion
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				CodBonoAlim = '".$CodBonoAlim."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "A") die("No se puede cerrar este Periodo");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	seleccionar empleado (jubilaciones)
elseif ($accion == "jubilaciones_empleados_sel") {
	$sep = "|char:sep|";
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	$FechaActual = "$AnioActual-$MesActual-$DiaActual";
	
	echo "$sep";
	//	datos generales
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				p.Sexo,
				p.Fnacimiento,
				e.CodEmpleado,
				e.CodOrganismo,
				e.CodDependencia,
				e.CodCargo,
				e.Fingreso,
				pt.NivelSalarial
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
			WHERE p.CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		list($EdadA, $EdadM, $EdadD) = getEdad(formatFechaDMA($field['Fnacimiento']), formatFechaDMA($FechaActual));
		echo $field['CodPersona'].$sep.$field['CodEmpleado'].$sep.$field['NomCompleto'].$sep.$field['CodOrganismo'].$sep.$field['CodDependencia'].$sep.$field['CodCargo'].$sep.number_format($field['NivelSalarial'], 2, ',', '.').$sep.$field['Ndocumento'].$sep.$field['Sexo'].$sep.formatFechaDMA($field['Fnacimiento']).$sep.$EdadA.$sep.formatFechaDMA($field['Fingreso']);
	}
	
	echo "$sep";
	//	listado de antecedentes
	$sql = "SELECT
				Empresa,
				FechaDesde,
				FechaHasta
			FROM rh_empleado_experiencia
			WHERE
				CodPersona = '".$CodPersona."' AND
				TipoEnte = '02'";
	$query_antecedentes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_antecedentes=0;
	while ($field_antecedentes = mysql_fetch_array($query_antecedentes)) {
		list($TiempoA, $TiempoM, $TiempoD) = getEdad(formatFechaDMA($field_antecedentes['FechaDesde']), formatFechaDMA($field_antecedentes['FechaHasta']));
		$TotalTiempoD += $TiempoD;
		$TotalTiempoM += $TiempoM;
		$TotalTiempoA += $TiempoA;
		?>
		<tr class="trListaBody">
			<th>
				<?=++$nro_antecedentes?>
			</th>
			<td>
            	<input type="text" name="Organismo" class="cell2" value="<?=htmlentities($field_antecedentes['Empresa'])?>" />
			</td>
			<td>
            	<input type="text" name="FIngreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FechaDesde'])?>" />
			</td>
			<td>
            	<input type="text" name="FEgreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FechaHasta'])?>" />
			</td>
			<td>
            	<input type="text" name="Anios" class="cell2" style="text-align:center;" value="<?=$TiempoA?>" />
			</td>
			<td>
            	<input type="text" name="Meses" class="cell2" style="text-align:center;" value="<?=$TiempoM?>" />
			</td>
			<td>
            	<input type="text" name="Dias" class="cell2" style="text-align:center;" value="<?=$TiempoD?>" />
			</td>
		</tr>
		<?
	}
	
	echo "$sep";
	//	totales antecedentes
	list($_Anios, $_Meses, $_Dias) = totalTiempo($TotalTiempoA, $TotalTiempoM, $TotalTiempoD);
	echo $_Anios.$sep.$_Meses.$sep.$_Dias;
	
	echo "$sep";
	//	totales servicio
	list($ActualA, $ActualM, $ActualD) = getEdad(formatFechaDMA($field['Fingreso']), formatFechaDMA($FechaActual));
	list($_AniosTotal, $_MesesTotal, $_DiasTotal) = totalTiempo($TotalTiempoA+$ActualA, $TotalTiempoM+$ActualM, $TotalTiempoD+$ActualD);
	if ($_MesesTotal >= 8) { $_AniosTotal++; $_MesesTotal = 0; $_DiasTotal = 0; }
	echo $ActualA.$sep.$ActualM.$sep.$ActualD.$sep.$_AniosTotal.$sep.$_MesesTotal.$sep.$_DiasTotal;
	
	echo "$sep";
	$Secuencia = 0;
	$TotalSueldo = 0;
	$TotalPrimas = 0;
	$NroCotizaciones = 0;
	//	consulto los conceptos disponibles para el calculo de jubilacion
	$sql = "SELECT
				CodConcepto,
				Descripcion
			FROM pr_concepto
			WHERE
				FlagJubilacion = 'S' AND
				Tipo = 'I'
			ORDER BY CodConcepto";
	$query_conceptos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$row_conceptos = mysql_num_rows($query_conceptos);
	while($field_conceptos = mysql_fetch_array($query_conceptos)) {
		?>
		<div style="width:796px;" class="divFormCaption"><?=htmlentities($field_conceptos['Descripcion'])?></div>
		<div style="overflow:scroll; width:800px; height:235px;">
		<div style="float:left; width:33%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$nro_sueldos = 0;
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
	 				 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 0, 8";
			$query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		
		<div style="float:left; width:33%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
	 				 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 8, 8";
			$query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		
		<div style="float:left; width:34%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
	 				 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 16, 8";
			$query_sueldos = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		</div>
		<?
	}
	
	echo "$sep";
	//	calculos
	##	variables
	$_Cumple = false;
	$_CumpleExceso = false;
	$Sexo = $field['Sexo'];
	$Edad = $EdadA;
	$AniosServicio = $_AniosTotal;
	$AniosServicioFinal = $_AniosTotal;
	$_AniosServicioExceso = 0;
	##	valido si cumple
	if ($Sexo == "M" && $Edad >= $_PARAMETRO['EDADJUBM'] && $AniosServicio >= $_PARAMETRO['MINSERVSI']) $_Cumple = true;
	elseif ($Sexo == "F" && $Edad >= $_PARAMETRO['EDADJUBF'] && $AniosServicio >= $_PARAMETRO['MINSERVSI']) $_Cumple = true;
	elseif ($AniosServicio >= $_PARAMETRO['MINSERVNO']) $_Cumple = true;
	else {
		if ($AniosServicio > $_PARAMETRO['MINSERVSI']) {
			if ($Sexo == "M") $EdadResta = $_PARAMETRO['EDADJUBM'] - $Edad;
			elseif ($Sexo == "M") $EdadResta = $_PARAMETRO['EDADJUBF'] - $Edad;
			if (($AniosServicio - $_PARAMETRO['MINSERVSI']) >= $EdadResta) {
				$_CumpleExceso = true;
				$AniosServicioFinal -= $EdadResta;
				$_AniosServicioExceso = $EdadResta;
			}
		}
	}
	if ($_Cumple || $_CumpleExceso) echo "S"; else echo "N";
	##
	echo "$sep";
	if ($_CumpleExceso) echo "S"; else echo "N";
	echo "$sep";
	echo $_AniosServicioExceso;
	
	echo "$sep";
	//	montos
	if ($_Cumple || $_CumpleExceso) {
		$Total = $TotalSueldo + $TotalPrimas;
		$SueldoBase = $Total / 24;
		$Porcentaje = $AniosServicioFinal * $_PARAMETRO['COEPORCJUB'];
		$Coeficiente = $Porcentaje / 100;
		$MontoJubilacion = $SueldoBase * $Coeficiente;
	} else {
		$Coeficiente = 0;
		$TotalSueldo = 0;
		$TotalPrimas = 0;
		$Total = 0;
		$MontoJubilacion = 0;
	}
	//PORCLIMITJUB
	echo number_format($Porcentaje, 2, ',', '.').$sep.number_format($TotalSueldo, 2, ',', '.').$sep.number_format($TotalPrimas, 2, ',', '.').$sep.number_format($Total, 2, ',', '.').$sep.number_format($MontoJubilacion, 2, ',', '.');
	
	echo "$sep".number_format($SueldoBase, 2, ',', '.');
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "jubilaciones_modificar") {
	$sql = "SELECT Estado FROM rh_proceso_jubilacion WHERE CodProceso = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar este Registro");
	} else die("$sql No se encuentra el registro");
}
//	--------------------------

//	seleccionar empleado (pensiones x invalidez)
elseif ($accion == "pensiones_invalidez_empleados_sel") {
	$sep = "|char:sep|";
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	$FechaActual = "$AnioActual-$MesActual-$DiaActual";
	
	echo "$sep";
	//	datos generales
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				p.Sexo,
				p.Fnacimiento,
				e.CodEmpleado,
				e.CodOrganismo,
				e.CodDependencia,
				e.CodCargo,
				e.Fingreso,
				pt.NivelSalarial
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
			WHERE p.CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		list($EdadA, $EdadM, $EdadD) = getEdad(formatFechaDMA($field['Fnacimiento']), formatFechaDMA($FechaActual));
		echo $field['CodPersona'].$sep.$field['CodEmpleado'].$sep.$field['NomCompleto'].$sep.$field['CodOrganismo'].$sep.$field['CodDependencia'].$sep.$field['CodCargo'].$sep.number_format($field['NivelSalarial'], 2, ',', '.').$sep.$field['Ndocumento'].$sep.$field['Sexo'].$sep.formatFechaDMA($field['Fnacimiento']).$sep.$EdadA.$sep.formatFechaDMA($field['Fingreso']);
	}
	
	echo "$sep";
	//	totales servicio
	list($ActualA, $ActualM, $ActualD) = getEdad(formatFechaDMA($field['Fingreso']), formatFechaDMA($FechaActual));
	echo $ActualA;
	
	echo "$sep";
	##	valido si cumple
	if ($ActualA >= $_PARAMETRO['PENSERVMIN']) echo "S"; else echo "N";
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "pensiones_invalidez_modificar") {
	$sql = "SELECT Estado FROM rh_proceso_pension WHERE CodProceso = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar este Registro");
	} else die("$sql No se encuentra el registro");
}
//	--------------------------

//	seleccionar empleado (pensiones x sobreviviente)
elseif ($accion == "pensiones_sobreviviente_empleados_sel") {
	$sep = "|char:sep|";
	list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
	$FechaActual = "$AnioActual-$MesActual-$DiaActual";
	
	echo "$sep";
	//	datos generales
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.Ndocumento,
				p.Sexo,
				p.Fnacimiento,
				e.CodEmpleado,
				e.CodOrganismo,
				e.CodDependencia,
				e.CodCargo,
				e.Fingreso,
				pt.NivelSalarial
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
			WHERE p.CodPersona = '".$CodPersona."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		list($EdadA, $EdadM, $EdadD) = getEdad(formatFechaDMA($field['Fnacimiento']), formatFechaDMA($FechaActual));
		echo $field['CodPersona'].$sep.$field['CodEmpleado'].$sep.$field['NomCompleto'].$sep.$field['CodOrganismo'].$sep.$field['CodDependencia'].$sep.$field['CodCargo'].$sep.number_format($field['NivelSalarial'], 2, ',', '.').$sep.$field['Ndocumento'].$sep.$field['Sexo'].$sep.formatFechaDMA($field['Fnacimiento']).$sep.$EdadA.$sep.formatFechaDMA($field['Fingreso']);
	}
	
	echo "$sep";
	//	listado de antecedentes
	$sql = "SELECT
				Empresa,
				FechaDesde,
				FechaHasta
			FROM rh_empleado_experiencia
			WHERE
				CodPersona = '".$CodPersona."' AND
				TipoEnte = '02'";
	$query_antecedentes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_antecedentes=0;
	while ($field_antecedentes = mysql_fetch_array($query_antecedentes)) {
		list($TiempoA, $TiempoM, $TiempoD) = getEdad(formatFechaDMA($field_antecedentes['FechaDesde']), formatFechaDMA($field_antecedentes['FechaHasta']));
		$TotalTiempoD += $TiempoD;
		$TotalTiempoM += $TiempoM;
		$TotalTiempoA += $TiempoA;
		?>
		<tr class="trListaBody">
			<th>
				<?=++$nro_antecedentes?>
			</th>
			<td>
            	<input type="text" name="Organismo" class="cell2" value="<?=htmlentities($field_antecedentes['Empresa'])?>" />
			</td>
			<td>
            	<input type="text" name="FIngreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FechaDesde'])?>" />
			</td>
			<td>
            	<input type="text" name="FEgreso" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_antecedentes['FechaHasta'])?>" />
			</td>
			<td>
            	<input type="text" name="Anios" class="cell2" style="text-align:center;" value="<?=$TiempoA?>" />
			</td>
			<td>
            	<input type="text" name="Meses" class="cell2" style="text-align:center;" value="<?=$TiempoM?>" />
			</td>
			<td>
            	<input type="text" name="Dias" class="cell2" style="text-align:center;" value="<?=$TiempoD?>" />
			</td>
		</tr>
		<?
	}
	
	echo "$sep";
	//	totales antecedentes
	list($_Anios, $_Meses, $_Dias) = totalTiempo($TotalTiempoA, $TotalTiempoM, $TotalTiempoD);
	echo $_Anios.$sep.$_Meses.$sep.$_Dias;
	
	echo "$sep";
	//	totales servicio
	list($ActualA, $ActualM, $ActualD) = getEdad(formatFechaDMA($field['Fingreso']), formatFechaDMA($FechaActual));
	list($_AniosTotal, $_MesesTotal, $_DiasTotal) = totalTiempo($TotalTiempoA+$ActualA, $TotalTiempoM+$ActualM, $TotalTiempoD+$ActualD);
	if ($_MesesTotal >= 8) { $_AniosTotal++; $_MesesTotal = 0; $_DiasTotal = 0; }
	echo $ActualA.$sep.$ActualM.$sep.$ActualD.$sep.$_AniosTotal.$sep.$_MesesTotal.$sep.$_DiasTotal;
	
	echo "$sep";
	$Secuencia = 0;
	$TotalSueldo = 0;
	$TotalPrimas = 0;
	$NroCotizaciones = 0;
	//	consulto los conceptos disponibles para el calculo de jubilacion
	$sql = "SELECT
				CodConcepto,
				Descripcion
			FROM pr_concepto
			WHERE
				FlagJubilacion = 'S' AND
				Tipo = 'I'
			ORDER BY CodConcepto";
	$query_conceptos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$row_conceptos = mysql_num_rows($query_conceptos);
	while($field_conceptos = mysql_fetch_array($query_conceptos)) {
		?>
		<div style="width:796px;" class="divFormCaption"><?=htmlentities($field_conceptos['Descripcion'])?></div>
		<div style="overflow:scroll; width:800px; height:235px;">
		<div style="float:left; width:33%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$nro_sueldos = 0;
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 0, 8";
			$query_sueldos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		
		<div style="float:left; width:33%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 8, 8";
			$query_sueldos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		
		<div style="float:left; width:34%">
		<table width="100%" class="tblLista">
			<thead>
			<tr>
				<th scope="col" width="15">#</th>
				<th scope="col" width="75">Periodo</th>
				<th scope="col" align="right">Monto</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = "(SELECT
						CodConcepto,
						Periodo,
						Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso = 'FIN' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					)
					UNION
					(SELECT
						CodConcepto,
						Periodo,
						SUM(Monto) AS Monto
					 FROM pr_tiponominaempleadoconcepto
					 WHERE
						CodPersona = '".$CodPersona."' AND
						CodConcepto = '".$field_conceptos['CodConcepto']."' AND
						CodTipoProceso <> 'FIN' AND CodTipoProceso <> 'ADE' AND
						Periodo < SUBSTRING(NOW(), 1, 7)
					 GROUP BY CodPersona, CodConcepto
					)
					ORDER BY Periodo DESC
					LIMIT 16, 8";
			$query_sueldos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_sueldos = mysql_fetch_array($query_sueldos)) {
				if ($field_conceptos['CodConcepto'] == $_PARAMETRO['CODSUELDOBAS']) $TotalSueldo += $field_sueldos['Monto'];
				else $TotalPrimas += $field_sueldos['Monto'];
				?>
				<tr class="trListaBody">
					<th>
						<input type="hidden" name="Secuencia" value="<?=++$Secuencia?>" />
						<?=++$nro_sueldos?>
					</th>
					<td>
						<input type="text" name="Periodo" class="cell2" style="text-align:center;" value="<?=$field_sueldos['Periodo']?>" />
					</td>
					<td>
                    	<input type="hidden" name="CodConcepto" value="<?=$field_sueldos['CodConcepto']?>" />
						<input type="text" name="Monto" class="cell2" style="text-align:right;" value="<?=number_format($field_sueldos['Monto'], 2, ',', '.')?>" />
					</td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		</div>
		</div>
		<?
	}
	
	echo "$sep";
	//	calculos
	##	variables
	$_Cumple = false;
	$_CumpleExceso = false;
	$Sexo = $field['Sexo'];
	$Edad = $EdadA;
	$AniosServicio = $_AniosTotal;
	$AniosServicioFinal = $_AniosTotal;
	$_AniosServicioExceso = 0;
	##	valido si cumple
	if ($Sexo == "M" && $Edad >= $_PARAMETRO['EDADJUBM'] && $AniosServicio >= $_PARAMETRO['MINSERVSI']) $_Cumple = true;
	elseif ($Sexo == "F" && $Edad >= $_PARAMETRO['EDADJUBF'] && $AniosServicio >= $_PARAMETRO['MINSERVSI']) $_Cumple = true;
	elseif ($AniosServicio >= $_PARAMETRO['MINSERVNO']) $_Cumple = true;
	else {
		if ($AniosServicio > $_PARAMETRO['MINSERVSI']) {
			if ($Sexo == "M") $EdadResta = $_PARAMETRO['EDADJUBM'] - $Edad;
			elseif ($Sexo == "M") $EdadResta = $_PARAMETRO['EDADJUBF'] - $Edad;
			if (($AniosServicio - $_PARAMETRO['MINSERVSI']) >= $EdadResta) {
				$_CumpleExceso = true;
				$AniosServicioFinal -= $EdadResta;
				$_AniosServicioExceso = $EdadResta;
			}
		}
	}
	if ($_Cumple || $_CumpleExceso) echo "S"; else echo "N";
	##
	echo "$sep";
	if ($_CumpleExceso) echo "S"; else echo "N";
	echo "$sep";
	echo $_AniosServicioExceso;
	
	echo "$sep";
	//	montos
	if ($_Cumple || $_CumpleExceso) {
		$Total = $TotalSueldo + $TotalPrimas;
		$SueldoBase = $Total / 24;
		$Porcentaje = $AniosServicioFinal * $_PARAMETRO['COEPORCJUB'];
		$Coeficiente = $Porcentaje / 100;
		$MontoJubilacion = $SueldoBase * $Coeficiente;
	} else {
		$Coeficiente = 0;
		$TotalSueldo = 0;
		$TotalPrimas = 0;
		$Total = 0;
		$MontoJubilacion = 0;
	}
	$MontoPension = $MontoJubilacion * $_PARAMETRO['PENPORMAX'] / 100;
	//	OJO:PORCLIMITJUB
	echo number_format($Porcentaje, 2, ',', '.').$sep.number_format($TotalSueldo, 2, ',', '.').$sep.number_format($TotalPrimas, 2, ',', '.').$sep.number_format($Total, 2, ',', '.').$sep.number_format($MontoJubilacion, 2, ',', '.').$sep.number_format($MontoPension, 2, ',', '.');
	
	echo "$sep".number_format($SueldoBase, 2, ',', '.');
	
	echo "$sep";
	//	beneficiarios
	$FlagCumple = false;
	$sql = "SELECT
				cf.Ndocumento,
				CONCAT(cf.NombresCarga, ' ', cf.ApellidosCarga) AS NombreCompleto,
				cf.Parentesco,
				cf.FechaNacimiento,
				cf.Sexo,
				cf.FlagDiscapacidad,
				cf.FlagEstudia
			FROM rh_cargafamiliar cf
			WHERE cf.CodPersona = '".$CodPersona."'";
	$query_beneficiarios = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_beneficiarios=0;
	while($field_beneficiarios = mysql_fetch_array($query_beneficiarios)) {
		list($EdadBeneficiario) = getEdad(formatFechaDMA($field_beneficiarios['FechaNacimiento']), formatFechaDMA($FechaActual));
		//	valido si cumple
		if ($field_beneficiarios['Parentesco'] == "ES" || $field_beneficiarios['Parentesco'] == "CB") $FlagCumple = true;
		elseif ($field_beneficiarios['Parentesco'] == "HI" && $EdadBeneficiario <= 14) $FlagCumple = true;
		elseif ($field_beneficiarios['Parentesco'] == "HI" && $field_beneficiarios['FlagEstudia'] == "S" && $EdadBeneficiario <= 18) $FlagCumple = true;
		elseif ($field_beneficiarios['FlagDiscapacidad'] == "S") $FlagCumple = true;
		
		if ($FlagCumple) {
			?>
			<tr class="trListaBody" onclick="clk($(this), 'beneficiarios', '<?=$nro_beneficiarios?>');">
				<th>
					<?=++$nro_beneficiarios?>
				</th>
				<td>
					<input type="text" name="NroDocumento" class="cell2" style="text-align:right;" value="<?=$field_beneficiarios['Ndocumento']?>" readonly="readonly" />
				</td>
				<td>
					<input type="text" name="NombreCompleto" class="cell2" value="<?=htmlentities($field_beneficiarios['NombreCompleto'])?>" readonly="readonly" />
				</td>
                <td align="center">
                    <input type="checkbox" name="FlagPrincipal" />
                </td>
				<td align="center">
                	<select name="Parentesco" class="cell2">
						<?=getMiscelaneos($field_beneficiarios['Parentesco'], 'PARENT', 1)?>
                    </select>
				</td>
				<td>
					<input type="text" name="FechaNacimiento" class="cell2" style="text-align:center;" value="<?=formatFechaDMA($field_beneficiarios['FechaNacimiento'])?>" readonly="readonly" />
				</td>
				<td>
                	<select name="Sexo" class="cell2">
						<?=loadSelectGeneral("SEXO", $field_beneficiarios['Sexo'], 1)?>
                    </select>
				</td>
				<td align="center">
                    <input type="hidden" name="FlagIncapacitados" value="<?=$field_beneficiarios['FlagDiscapacidad']?>" />
                    <input type="hidden" name="FlagEstudia" value="<?=$field_beneficiarios['FlagEstudia']?>" />
					<?=$EdadBeneficiario?>
				</td>
			</tr>
			<?
		}
	}
}
//	--------------------------

//	consulto si se puede modificar
elseif ($accion == "pensiones_sobreviviente_modificar") {
	$sql = "SELECT Estado FROM rh_proceso_pension WHERE CodProceso = '".$codigo."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar este Registro");
	} else die("$sql No se encuentra el registro");
}
//	--------------------------

//	insertar linea
elseif ($accion == "pensiones_sobreviviente_beneficiarios_insertar") {
	?>
	<tr class="trListaBody" onclick="clk($(this), 'beneficiarios', '<?=$nro_detalle?>');">
		<th>
			<?=$nro_detalle?>
		</th>
		<td>
			<input type="text" name="NroDocumento" class="cell" style="text-align:right;" max="20" />
		</td>
		<td>
			<input type="text" name="NombreCompleto" class="cell" maxlength="100" />
		</td>
		<td align="center">
			<input type="checkbox" name="FlagPrincipal" />
		</td>
		<td align="center">
			<select name="Parentesco" class="cell2">
				<?=getMiscelaneos("", 'PARENT', 0)?>
			</select>
		</td>
		<td>
			<input type="text" name="FechaNacimiento" class="datepicker cell" style="text-align:center;" maxlength="10" onkeyup="setFechaDMA(this);" />
		</td>
		<td>
			<select name="Sexo" class="cell">
				<?=loadSelectGeneral("SEXO", "", 0)?>
			</select>
		</td>
		<td align="center">
        	<input type="hidden" name="FlagIncapacitados" value="N" />
        	<input type="hidden" name="FlagEstudia" value="N" />
			<?=$EdadBeneficiario?>
		</td>
	</tr>
	<?
}
//	--------------------------
?>
