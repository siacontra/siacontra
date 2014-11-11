<?php
if ($filtrar == "default") {
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (cc.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (cc.CodCentroCosto LIKE '%".$fbuscar."%' OR 
					cc.Descripcion LIKE '%".$fbuscar."%' OR 
					cc.Abreviatura LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($fgrupocc != "") { $cgrupocc = "checked"; $filtro.=" AND (cc.CodGrupoCentroCosto = '".$fgrupocc."')"; } else $dgrupocc = "disabled";
if ($fsubgrupocc != "") { $csubgrupocc = "checked"; $filtro.=" AND (cc.CodSubGrupoCentroCosto = '".$fsubgrupocc."')"; } else $dsubgrupocc = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Centro de Costos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=centro_costos_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="125">Grupo C.C:</td>
        <td>
            <input type="checkbox" <?=$cgrupocc?> onclick="chkFiltro(this.checked, 'fgrupocc');" />
            <select name="fgrupocc" id="fgrupocc" style="width:200px;" onchange="getOptionsSelect(this.value, 'subgrupocc', 'fsubgrupocc', 200, 1);" <?=$dgrupocc?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("ac_grupocentrocosto", "CodGrupoCentroCosto", "Descripcion", $fgrupocc, 0)?>
            </select>
		</td>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Sub-Grupo C.C:</td>
        <td>
            <input type="checkbox" <?=$csubgrupocc?> onclick="chkFiltro(this.checked, 'fsubgrupocc');" />
            <select name="fsubgrupocc" id="fsubgrupocc" style="width:200px;" <?=$dsubgrupocc?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", $fsubgrupocc, $fgrupocc, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=centro_costos_form&opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=centro_costos_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'centro_costos', 'eliminar');" />            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=centro_costos_form&opcion=ver', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="50">C.Costo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="100">Sub-Grupo</th>
		<th scope="col" width="60">Abreviatura</th>
		<th scope="col" width="50">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				cc.*,
				gcc.Descripcion AS NomGrupoCentroCosto,
				sgcc.Descripcion AS NomSubGrupoCentroCosto
			FROM
				ac_mastcentrocosto cc
				INNER JOIN ac_grupocentrocosto gcc ON (cc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
				INNER JOIN ac_subgrupocentrocosto sgcc ON (cc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto AND
														   cc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				cc.*,
				gcc.Descripcion AS NomGrupoCentroCosto,
				sgcc.Descripcion AS NomSubGrupoCentroCosto
			FROM
				ac_mastcentrocosto cc
				INNER JOIN ac_grupocentrocosto gcc ON (cc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
				INNER JOIN ac_subgrupocentrocosto sgcc ON (cc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto AND
														   cc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto)
			WHERE 1 $filtro
			ORDER BY CodCentroCosto
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodGrupoCentroCosto']) {
			$grupo = $field['CodGrupoCentroCosto'];
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=$field['NomGrupoCentroCosto']?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodCentroCosto']?>">
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td><?=$field['NomSubGrupoCentroCosto']?></td>
			<td><?=$field['Abreviatura']?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="800">
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