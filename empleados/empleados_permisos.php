<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "A";
	$fdOrderBy = "CodPermiso";
	$fFechaDesde = "01-01-$AnioActual";
	$fFechaHasta = "31-12-$AnioActual";
	$CodPersona = $registro;
}
$filtro = "";
if ($fTipoPermiso != "") { $cTipoPermiso = "checked"; $filtro.=" AND (p.TipoPermiso = '".$fTipoPermiso."')"; } else $dTipoPermiso = "disabled";
if ($fTipoFalta != "") { $cTipoFalta = "checked"; $filtro.=" AND (p.TipoFalta = '".$fTipoFalta."')"; } else $dTipoFalta = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (p.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fFechaDesde != "" || $fFingresoH != "") {
	$cFecha = "checked";
	if ($fFechaDesde != "") $filtro.=" AND (p.FechaDesde >= '".formatFechaAMD($fFechaDesde)."')";
	if ($fFechaHasta != "") $filtro.=" AND (p.FechaHasta <= '".formatFechaAMD($fFechaHasta)."')";
} else $dFecha = "disabled";
if ($fdBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (p.CodPermiso LIKE '%".$fdBuscar."%' OR
					  md1.Descripcion LIKE '%".$fdBuscar."%' OR
					  md2.Descripcion LIKE '%".$fdBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Permisos del Empleado</td>
		<td align="right"><a class="cerrar" href="#" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_lista'); document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_permisos" method="post" autocomplete="off">
<?=filtroEmpleados()?>
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$field_empleado['CodPersona']?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Empleado</td>
    </tr>
	<tr>
		<td align="right" width="125">Empleado:</td>
		<td>
        	<input type="text" id="CodEmpleado" style="width:60px;" class="codigo" value="<?=$field_empleado['CodEmpleado']?>" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Nombre Completo:</td>
		<td>
        	<input type="text" id="NomCompleto" style="width:500px;" class="codigo" value="<?=$field_empleado['NomCompleto']?>" disabled />
		</td>
	</tr>
</table>
</div><br />

<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
    	<td colspan="4" class="divFormCaption">Filtro</td>
    </tr>
	<tr>
		<td align="right">Motivo de Ausencia: </td>
		<td>
        	<input type="checkbox" <?=$cTipoPermiso?> onclick="chkFiltro(this.checked, 'fTipoPermiso');" />
			<select name="fTipoPermiso" id="fTipoPermiso" style="width:255px;" <?=$dTipoPermiso?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($fTipoPermiso, "PERMISOS", 0)?>
			</select>
		</td>
		<td align="right">Fecha: </td>
		<td>
			<input type="checkbox" <?=$cFecha?> onclick="chkFiltro_2(this.checked, 'fFechaDesde', 'fFechaHasta');" />
			<input type="text" name="fFechaDesde" id="fFechaDesde" value="<?=$fFechaDesde?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFechaHasta" id="fFechaHasta" value="<?=$fFechaHasta?>" <?=$dFecha?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Tipo de Evento: </td>
		<td>
        	<input type="checkbox" <?=$cTipoFalta?> onclick="chkFiltro(this.checked, 'fTipoFalta');" />
			<select name="fTipoFalta" id="fTipoFalta" style="width:255px;" <?=$dTipoFalta?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($fTipoFalta, "TIPOFALTAS", 0)?>
			</select>
		</td>
		<td align="right">Estado: </td>
		<td>
        	<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:145px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO-PERMISOS", $fEstado, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fdBuscar');" />
			<input type="text" name="fdBuscar" id="fdBuscar" value="<?=$fdBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right" class="gallery clearfix">
            <a href="pagina.php?iframe=true" rel="prettyPhoto[iframe1]" style="display:none;" id="a_imprimir"></a>
            <input type="button" value="Imprimir" style="width:75px;" onclick="empleados_permisos_imprimir(this.form);" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="75" onclick="order('CodPermiso', '', 'fdOrderBy')"><a href="javascript:">Permiso</a></th>
        <th scope="col" align="left" onclick="order('MotivoAusencia', '', 'fdOrderBy')"><a href="javascript:">Motivo de Ausencia</a></th>
        <th scope="col" width="125" onclick="order('TipoEvento', '', 'fdOrderBy')"><a href="javascript:">Tipo de Evento</a></th>
        <th scope="col" colspan="2" onclick="order('Desde', '', 'fdOrderBy')"><a href="javascript:">Desde</a></th>
        <th scope="col" colspan="2" onclick="order('Hasta', '', 'fdOrderBy')"><a href="javascript:">Hasta</a></th>
        <th scope="col" width="75" onclick="order('Estado', '', 'fdOrderBy')"><a href="javascript:">Estado</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				p.CodPermiso,
				p.FechaDesde,
				p.FechaHasta,
				p.HoraDesde,
				p.HoraHasta,
				p.Estado,
				CONCAT(p.FechaDesde, ' ', p.HoraDesde) AS Desde,
				CONCAT(p.FechaHasta, ' ', p.HoraHasta) AS Hasta,
				md1.Descripcion AS MotivoAusencia,
				md2.Descripcion AS TipoEvento
            FROM
				rh_permisos p
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = p.TipoPermiso AND
													 md1.CodMaestro = 'PERMISOS' AND
													 md1.CodAplicacion = 'RH')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = p.TipoFalta AND
													 md2.CodMaestro = 'TIPOFALTAS' AND
													 md2.CodAplicacion = 'RH')
            WHERE CodPersona = '".$CodPersona."' $filtro
            ORDER BY $fdOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        ?>
        <tr class="trListaBody">
            <td align="center"><?=$field['CodPermiso']?></td>
            <td><?=htmlentities($field['MotivoAusencia'])?></td>
            <td align="center"><?=htmlentities($field['TipoEvento'])?></td>
            <td align="center" width="70"><?=formatFechaDMA($field['FechaDesde'])?></td>
            <td align="center" width="60"><?=formatHora12($field['HoraDesde'])?></td>
            <td align="center" width="70"><?=formatFechaDMA($field['FechaHasta'])?></td>
            <td align="center" width="60"><?=formatHora12($field['HoraHasta'])?></td>
            <td align="center"><?=printValoresGeneral("ESTADO-PERMISOS", $field['Estado'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>