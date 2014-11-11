<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	--------------------------
if ($accion == "empleado_vacaciones_periodo_sel") {
	//	empleado
	$sql = "SELECT CodTipoNom FROM mastempleado WHERE CodPersona = '".$CodPersona."'";
	$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
	
	//	consulto
	$sql = "SELECT *
			FROM rh_vacacionutilizacion
			WHERE
				CodPersona = '".$CodPersona."' AND
				NroPeriodo = '".$NroPeriodo."' AND
				CodTipoNom = '".$field_empleado['CodTipoNom']."'
			ORDER BY Secuencia";
	$query_utilizacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
	$rows_utilizacion = mysql_num_rows($query_utilizacion);
	echo "$rows_utilizacion|";
	while ($field_utilizacion = mysql_fetch_array($query_utilizacion)) {
		++$i;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_utilizacion');" id="utilizacion_<?=$i?>">
			<th>
				<input type="hidden" name="NroPeriodo" value="<?=$field_utilizacion['NroPeriodo']?>" />
				<?=$i?>
			</th>
			<td>
				<select name="TipoUtilizacion" class="cell">
					<?=loadSelectValores("TIPO-VACACIONES", $field_utilizacion['TipoUtilizacion'], 0)?>
				</select>
			</td>
			<td>
				<input type="text" name="DiasUtiles" id="DiasUtiles_utilizacion_<?=$i?>" style="text-align:right;" class="cell" value="<?=number_format($field_utilizacion['DiasUtiles'], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" onchange="obtenerFechaTerminoVacacionUtilizacion('utilizacion_<?=$i?>');" />
			</td>
			<td>
				<input type="text" name="FechaInicio" id="FechaInicio_utilizacion_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" value="<?=formatFechaDMA($field_utilizacion['FechaInicio'])?>" onchange="obtenerFechaTerminoVacacionUtilizacion('utilizacion_<?=$i?>');" />
			</td>
			<td>
				<input type="text" name="FechaFin" id="FechaFin_utilizacion_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" value="<?=formatFechaDMA($field_utilizacion['FechaFin'])?>" onkeyup="setFechaDMA(this);" />
			</td>
		</tr>
		<?
	}
}
//	--------------------------

//	--------------------------
elseif ($accion == "empleado_vacaciones_utilizacion_linea") {
	?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_utilizacion');" id="utilizacion_<?=$nrodetalle?>">
    	<th>
        	<input type="hidden" name="NroPeriodo" value="<?=$NroPeriodo?>" />
			<?=$nrodetalle?>
        </th>
        <td>
        	<select name="TipoUtilizacion" class="cell">
            	<?=loadSelectValores("TIPO-VACACIONES", "G", 0)?>
            </select>
        </td>
        <td>
        	<input type="text" name="DiasUtiles" id="DiasUtiles_utilizacion_<?=$nrodetalle?>" style="text-align:right;" class="cell" value="0,00" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" onchange="obtenerFechaTerminoVacacionUtilizacion('utilizacion_<?=$nrodetalle?>');" />
        </td>
        <td>
        	<input type="text" name="FechaInicio" id="FechaInicio_utilizacion_<?=$nrodetalle?>" maxlength="10" style="text-align:center;" class="cell datepicker" onchange="obtenerFechaTerminoVacacionUtilizacion('utilizacion_<?=$nrodetalle?>');" />
        </td>
        <td>
        	<input type="text" name="FechaFin" id="FechaFin_utilizacion_<?=$nrodetalle?>" maxlength="10" style="text-align:center;" class="cell datepicker" onkeyup="setFechaDMA(this);" />
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
?>
