<?php
if ($filtrar == "default") {
	$fordenar = "f.CodFase";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY CodProceso, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (f.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (f.CodFase LIKE '%".$fbuscar."%' OR 
					f.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($fproceso != "") { $cproceso = "checked"; $filtro.=" AND (f.CodProceso = '".$fproceso."')"; } else $dproceso = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Fases</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_fases_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:700px;">
<table width="700" class="tblFiltro">
	<tr>
		<td align="right" width="75">Proceso:</td>
		<td>
            <input type="checkbox" <?=$cproceso?> onclick="chkFiltro(this.checked, 'fproceso');" />
            <select name="fproceso" id="fproceso" style="width:250px;" <?=$dproceso?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $fproceso, 0)?>
            </select>
		</td>
		<td align="right" width="75">Estado Reg.:</td>
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
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-FASES", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=pf_fases_form&opcion=nuevo');" />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_fases_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'fases', 'eliminar');" />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_fases_form&opcion=ver', 'BLANK', 'height=300, width=700, left=100, top=0, resizable=no', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:700px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="50">Fase</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="60">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				f.*,
				p.Descripcion AS NomProceso
			FROM
				pf_fases f
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				f.*,
				p.Descripcion AS NomProceso
			FROM
				pf_fases f
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodProceso']) {
			$grupo = $field['CodProceso'];
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=($field['NomProceso'])?></td>
            </tr>
            <?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodFase']?>">
			<td align="center"><?=$field['CodFase']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="700">
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