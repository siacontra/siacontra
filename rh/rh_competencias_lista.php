<?php
if ($filtrar == "default") {
	$fEstado = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fTipoEvaluacion != "") { $cTipoEvaluacion = "checked"; $filtro.=" AND (ea.TipoEvaluacion = '".$fTipoEvaluacion."')"; } else $dTipoEvaluacion = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (ea.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (ea.Competencia LIKE '%".$fBuscar."%' OR
					  ea.Descripcion LIKE '%".$fBuscar."%' OR
					  te.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Competencias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_competencias_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right" width="125">Tipo de Evaluaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cTipoEvaluacion?> onclick="chkFiltro(this.checked, 'fTipoEvaluacion');" />
            <select name="fTipoEvaluacion" id="fTipoEvaluacion" style="width:300px;" <?=$dTipoEvaluacion?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("rh_tipoevaluacion", "TipoEvaluacion", "Descripcion", $fTipoEvaluacion, 0)?>
            </select>
		</td>
		<td align="right" width="125">Estado:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fEstado, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px;" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_competencias_form&opcion=nuevo');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_competencias_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_competencias_form&opcion=ver', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btEliminar" value="Eliminar" style="width:75px;" onclick="opcionRegistro2(this.form, $('#registro').val(), 'competencias', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="40">Cod.</th>
		<th scope="col" align="left">Denominaci&oacute;n</th>
		<th scope="col" width="100">Nivel</th>
		<th scope="col" width="100">Tipo</th>
		<th scope="col" width="60">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT ef.Competencia
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_evaluacionarea ea ON (ea.Area = ef.Area)
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = ea.TipoEvaluacion)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ef.Nivel AND
													 md1.CodMaestro = 'NIVELCOMPE')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ef.TipoCompetencia AND
													 md2.CodMaestro = 'TIPOCOMPE')
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				ef.Competencia,
				ef.Descripcion,
				ef.Estado,
				te.TipoEvaluacion,
				te.Descripcion AS NomTipoEvaluacion,
				md1.Descripcion AS NomNivel,
				md2.Descripcion As NomTipoCompetencia
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_evaluacionarea ea ON (ea.Area = ef.Area)
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = ea.TipoEvaluacion)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ef.Nivel AND
													 md1.CodMaestro = 'NIVELCOMPE')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ef.TipoCompetencia AND
													 md2.CodMaestro = 'TIPOCOMPE')
			WHERE 1 $filtro
			ORDER BY TipoEvaluacion, Competencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Competencia]";
		if ($Grupo != $field['TipoEvaluacion']) {
			$Grupo = $field['TipoEvaluacion'];
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field['TipoEvaluacion']?></td>
				<td colspan="3"><?=htmlentities($field['NomTipoEvaluacion'])?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Competencia']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td><?=htmlentities($field['NomNivel'])?></td>
			<td><?=htmlentities($field['NomTipoCompetencia'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>