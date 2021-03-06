<?php
if ($filtrar == "default") {
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (d.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY d.CodOrganismo, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (d.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (d.CodDependencia LIKE '%".$fbuscar."%' OR
					d.Dependencia LIKE '%".$fbuscar."%' OR
					d.CodOrganismo LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Dependencias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=dependencias_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
        <td>
            <input type="checkbox" <?=$corganismo?> onclick="chkFiltro(this.checked, 'forganismo');" />
            <select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $forganismo, 0)?>
            </select>
		</td>
		<td align="right" width="100">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:295px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=dependencias_form&opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=dependencias_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'dependencias', 'eliminar');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=dependencias_form&opcion=ver', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div class="divScroll" style="width:1000px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="60">Dependencia</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				d.*,
				o.Organismo
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o ON (d.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);



	//	consulto lista
	/*$sql = "SELECT
					d.*,
				o.Organismo
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o ON (d.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro
			ORDER BY d.CodInterno
			LIMIT ".intval($limit).", $maxlimit";*/

	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodOrganismo']) {
			$grupo = $field['CodOrganismo'];
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=$field['Organismo']?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodDependencia']?>">
			<td align="right"><?=$field['CodDependencia']?></td>
			<td><?=($field['Dependencia'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1000">
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
