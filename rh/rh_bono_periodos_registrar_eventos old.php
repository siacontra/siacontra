<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
//	------------------------------------
list($Anio, $CodOrganismo, $CodBonoAlim, $CodPersona) = split("[_]", $dregistro);
//	empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado,
			e.CodHorario
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
//	consulto los dias
$sql = "SELECT
			ba.FechaInicio,
			ba.FechaFin,
			bad.DiasPeriodo
		FROM
			rh_bonoalimentacion ba
			INNER JOIN rh_bonoalimentaciondet bad ON (bad.Anio = ba.Anio AND
													  bad.CodOrganismo = ba.CodOrganismo AND
													  bad.CodBonoAlim = ba.CodBonoAlim)
		WHERE
			ba.Anio = '".$Anio."' AND
			ba.CodOrganismo = '".$CodOrganismo."' AND
			ba.CodBonoAlim = '".$CodBonoAlim."' AND
			bad.CodPersona = '".$CodPersona."'";
$query_dias = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_dias) != 0) $field_dias = mysql_fetch_array($query_dias);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Registro de Eventos</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_bono_periodos_registrar_lista" method="post" autocomplete="off" onsubmit="return bono_periodos_registrar_eventos_procesar(this);">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />

<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />

<input type="hidden" name="dregistro" id="dregistro" value="<?=$dregistro?>" />
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="fdCodDependencia" id="fdCodDependencia" value="<?=$fdCodDependencia?>" />
<input type="hidden" name="fdCodPersona" id="fdCodPersona" value="<?=$fdCodPersona?>" />
<input type="hidden" name="fdCodEmpleado" id="fdCodEmpleado" value="<?=$fdCodEmpleado?>" />
<input type="hidden" name="fdNomPersona" id="fdNomPersona" value="<?=$fdNomPersona?>" />
<input type="hidden" name="fdCodCentroCosto" id="fdCodCentroCosto" value="<?=$fdCodCentroCosto?>" />
<input type="hidden" name="fdBuscar" id="fdBuscar" value="<?=$fdBuscar?>" />

<input type="hidden" id="Anio" value="<?=$Anio?>" />
<input type="hidden" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" id="CodBonoAlim" value="<?=$CodBonoAlim?>" />
<input type="hidden" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="CodHorario" value="<?=$field_empleado['CodHorario']?>" />
<input type="hidden" id="FechaInicio" value="<?=$field_dias['FechaInicio']?>" />

<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:40px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:300px;" class="codigo" value="<?=htmlentities($field_empleado['NomCompleto'])?>" disabled />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Procesar" /></center>
</form>
<br />
<table width="950" align="center" cellpadding="0" cellspacing="0">
    <tr>
    	<td>
            <div class="header">
            <ul id="tab">
            <?php
			//	obtengo el nro de semanas
			$swSemana = true;
			$ns = 0;
			$nro_semanas = 0;
            $fi = formatFechaDMA($field_dias['FechaInicio']);
            while($swSemana) {
				++$ns;
				++$nro_semanas;
				$dsemana = getWeekDay($fi);
				$dias_semana = 7 - $dsemana + 1;
				$ff = obtenerFechaFin($fi, $dias_semana);
                if (formatFechaAMD($FechaActual) >= formatFechaAMD($fi) && formatFechaAMD($FechaActual) <= formatFechaAMD($ff)) { 
					$current[$ns] = "current";
					$nro_tab = $ns;
				} else $current[$ns] = "";
				if (formatFechaAMD($ff) >= $field_dias['FechaFin']) { $ff = formatFechaDMA($field_dias['FechaFin']); $swSemana = false; }
				$ttl[$ns] = "$fi AL $ff";
				$fechai[$ns] = $fi;
				$fechaf[$ns] = $ff;
				$fi = obtenerFechaFin($ff, 2);
			}
			
            //	tabs
			for($ns=1;$ns<=$nro_semanas;$ns++) {
                ?>
                <li id="li<?=$ns?>" onclick="currentTab('tab', this);" class="<?=$current[$ns]?>">
                    <a href="#" onclick="mostrarTab('tab', <?=$ns?>, <?=$nro_semanas?>);"><?=$ttl[$ns]?></a>
                </li>
                <?
            }
            ?>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<form name="frm_eventos" id="frm_eventos">
<input type="hidden" name="sel_eventos" id="sel_eventos" />
<?php
//	tabs
if ($nro_tab == "") $nro_tab = 1;
for($ns=1;$ns<=$nro_semanas;$ns++) {
	if ($nro_tab == $ns) $display = "block"; else $display = "none";
	?>
    <div id="tab<?=$ns?>" style="display:<?=$display?>;">
    <table width="950" class="tblBotones">
        <tr class="gallery clearfix">
        	<td>
	            <a id="a_eventos_<?=$ns?>" href="../lib/listas/listado_eventos_asistencia.php?CodPersona=<?=$CodPersona?>&FechaInicio=<?=$fechai[$ns]?>&FechaFin=<?=$fechaf[$ns]?>&secuencia=<?=$ns?>&ventana=bono_periodos_registrar_eventos&iframe=true&width=500&height=400" rel="prettyPhoto[iframe1<?=$ns?>]" style="display:none;"></a>
	            <a id="a_horario_<?=$ns?>" href="../lib/listas/listado_horario.php?CodPersona=<?=$CodPersona?>&iframe=true&width=500&height=340" rel="prettyPhoto[iframe2<?=$ns?>]" style="display:none;"></a>
                
	            <a id="a_permisos_<?=$ns?>" href="../lib/listas/listado_permisos.php?CodPersona=<?=$CodPersona?>&FechaInicio=<?=$fechai[$ns]?>&FechaFin=<?=$fechaf[$ns]?>&iframe=true&width=700&height=340" rel="prettyPhoto[iframe3<?=$ns?>]" style="display:none;"></a>
                
                <input type="button" style="width:90px;" value="Insertar Evento" onclick="document.getElementById('a_eventos_<?=$ns?>').click();" /> |
            	<input type="button" style="width:90px;" value="Ver Horario" onclick="document.getElementById('a_horario_<?=$ns?>').click();" />
            	<input type="button" style="width:90px;" value="Ver Permisos" onclick="document.getElementById('a_permisos_<?=$ns?>').click();" />
            </td>
            <td align="right">
                <input type="button" class="btLista" value="Insertar" onclick="bono_periodos_registrar_eventos_insertar(this, '<?=$ns?>', '<?=$fechai[$ns]?>', '<?=$fechaf[$ns]?>');" />
                <input type="button" class="btLista" value="Borrar" onclick="bono_periodos_registrar_eventos_quitar(this, '<?=$ns?>');" />
            </td>
        </tr>
    </table>
    <div style="overflow:scroll; width:950px; height:250px;">
    <table width="100%" class="tblLista">
        <thead>
        <tr>
            <th scope="col" width="15">&nbsp;</th>
            <th scope="col" width="80">Fecha</th>
            <th scope="col" width="60">Salida</th>
            <th scope="col" width="60">Entrada</th>
            <th scope="col" width="60">Horas</th>
            <th scope="col" width="125">Motivo de Ausencia</th>
            <th scope="col" width="125">Tipo de Evento</th>
            <th scope="col">Observaciones</th>
        </tr>
        </thead>
        
        <tbody id="lista_eventos_<?=$ns?>">
		<?php
        //	consulto
        $sql = "SELECT *
                FROM rh_bonoalimentacioneventos
                WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."' AND
					CodPersona = '".$CodPersona."' AND
					Fecha >= '".formatFechaAMD($fechai[$ns])."' AND
					Fecha <= '".formatFechaAMD($fechaf[$ns])."'";
        $query_eventos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_eventos=0;
        while($field_eventos = mysql_fetch_array($query_eventos)) {
            ?>
            <tr class="trListaBody" onclick="mClk(this, 'sel_eventos');" id="eventos_<?=$ns?>_<?=$nro_eventos?>">
                <th>
                    <?=++$nro_eventos?>
                </th>
                <td>
                    <select name="Fecha" id="Fecha_<?=$ns?>_<?=$nro_eventos?>" class="cell" onChange="getDiffHoraEventos('<?=$ns?>_<?=$nro_eventos?>');">
                        <?=getFechaEventos($fechai[$ns], $fechaf[$ns], formatFechaDMA($field_eventos['Fecha']), 0)?>
                    </select>
                </td>
                <td>
                	<input type="text" name="HoraSalida" id="HoraSalida_<?=$ns?>_<?=$nro_eventos?>" class="cell" style="text-align:center;" value="<?=formatHora12($field_eventos['HoraSalida'], true)?>" maxlength="11" onChange="getDiffHoraEventos('<?=$ns?>_<?=$nro_eventos?>');" />
                </td>
                <td>
                	<input type="text" name="HoraEntrada" id="HoraEntrada_<?=$ns?>_<?=$nro_eventos?>" class="cell" style="text-align:center;" value="<?=formatHora12($field_eventos['HoraEntrada'], true)?>" maxlength="11" onChange="getDiffHoraEventos('<?=$ns?>_<?=$nro_eventos?>');" />
                </td>
                <td>
                	<input type="text" name="TotalHoras" id="TotalHoras_<?=$ns?>_<?=$nro_eventos?>" class="cell2" style="text-align:center;" value="<?=$field_eventos['TotalHoras']?>" readonly />
                </td>
                <td>
                    <select name="Motivo" class="cell">
                    	<option value="">&nbsp;</option>
                        <?=getMiscelaneos($field_eventos['Motivo'], "PERMISOS", 0)?>
                    </select>
                </td>
                <td>
                    <select name="TipoEvento" class="cell">
                    	<option value="">&nbsp;</option>
                        <?=getMiscelaneos($field_eventos['TipoEvento'], "TIPOFALTAS", 0)?>
                    </select>
                </td>
                <td>
                    <textarea name="Observaciones" class="cell" style="height:15px;"><?=$field_eventos['Observaciones']?></textarea>
                </td>
            </tr>
            <?
        }
        ?>
        </tbody>
    </table>
    <input type="hidden" id="nro_eventos_<?=$ns?>" value="<?=$nro_eventos?>" />
    <input type="hidden" id="can_eventos_<?=$ns?>" value="<?=$nro_eventos?>" />
    </div>
    </div>
	<?
}
?>
</form>
</center>