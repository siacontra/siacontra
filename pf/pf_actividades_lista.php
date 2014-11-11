<?php
if ($filtrar == "default") {
	$fordenar = "a.CodActividad";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY CodProceso, CodFase, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (a.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (a.CodActividad LIKE '%".$fbuscar."%' OR 
					a.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($fproceso != "") { $cproceso = "checked"; $filtro.=" AND (f.CodProceso = '".$fproceso."')"; } else $dproceso = "disabled";
if ($ffase != "") { $cfase = "checked"; $filtro.=" AND (a.CodFase = '".$ffase."')"; } else $dfase = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Actividades</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_actividades_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="100">Proceso:</td>
		<td>
            <input type="checkbox" <?=$cproceso?> onclick="chkFiltro(this.checked, 'fproceso');" />
            <select name="fproceso" id="fproceso" style="width:250px;" onchange="getOptionsSelect(this.value, 'fases', 'ffase', true);" <?=$dproceso?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $fproceso, 0)?>
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
		<td align="right">Fase:</td>
		<td>
            <input type="checkbox" <?=$cfase?> onclick="chkFiltro(this.checked, 'ffase');" />
            <span>
            <select name="ffase" id="ffase" style="width:250px;" <?=$dfase?>>
                <option value="">&nbsp;</option>
                <?=loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", $ffase, $fproceso, 0)?>
            </select>
            </span>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-ACTIVIDADES", $fordenar, 0)?>
            </select>
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
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=pf_actividades_form&opcion=nuevo');" />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actividades_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'actividades', 'eliminar');" />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=pf_actividades_form&opcion=ver', 'BLANK', 'height=300, width=700, left=100, top=0, resizable=no', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="50">Actividad</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Au. Ar.</th>
		<th scope="col" width="25">No Afe.</th>
		<th scope="col" width="60">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				a.*,
				f.Descripcion AS NomFase,
				p.CodProceso,
				p.Descripcion AS NomProceso
			FROM
				pf_actividades a
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				a.*,
				f.Descripcion AS NomFase,
				p.CodProceso,
				p.Descripcion AS NomProceso
			FROM
				pf_actividades a
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				INNER JOIN pf_procesos p ON (f.CodProceso = p.CodProceso)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo1 != $field['CodProceso']) {
			$grupo1 = $field['CodProceso'];
			$grupo2 = "";
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=($field['NomProceso'])?></td>
            </tr>
            <?
		}
		if ($grupo2 != $field['CodFase']) {
			$grupo2 = $field['CodFase'];
			?>
            <tr class="trListaBody3">
                <td colspan="2"><?=($field['NomFase'])?></td>
            </tr>
            <?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodActividad']?>">
			<td align="center"><?=$field['CodActividad']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=printFlag($field['FlagAutoArchivo'])?></td>
			<td align="center"><?=printFlag($field['FlagNoAfectoPlan'])?></td>
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