<?php
if ($filtrar == "default") {
	$fordenar = "mm.CodMaestro";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY mm.CodAplicacion, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (mm.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (mm.CodMaestro LIKE '%".$fbuscar."%' OR
					mm.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Miscel&aacute;neos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=miscelaneos_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
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
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:125px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-MISCELANEO", $fordenar, 0)?>
            </select>
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
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=miscelaneos_form&opcion=nuevo');" />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=miscelaneos_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'miscelaneos', 'eliminar');" />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=miscelaneos_form&opcion=ver', 'BLANK', 'height=550, width=600, left=100, top=0, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="100">Maestro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="50">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				mm.CodAplicacion,
				mm.CodMaestro,
				mm.Descripcion,
				mm.Estado,
				a.Descripcion AS NomAplicacion
			FROM
				mastmiscelaneoscab mm
				INNER JOIN mastaplicaciones a ON (mm.CodAplicacion = a.CodAplicacion)
			WHERE
				(mm.CodAplicacion = 'GE' OR mm.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."')
				$filtro";
	$query_lista = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query_lista);
	
	//	consulto lista
	$sql = "SELECT
				mm.CodAplicacion,
				mm.CodMaestro,
				mm.Descripcion,
				mm.Estado,
				a.Descripcion AS NomAplicacion
			FROM
				mastmiscelaneoscab mm
				INNER JOIN mastaplicaciones a ON (mm.CodAplicacion = a.CodAplicacion)
			WHERE
				(mm.CodAplicacion = 'GE' OR mm.CodAplicacion = '".$_SESSION["APLICACION_ACTUAL"]."')
				$filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query_lista = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query_lista);
	while ($field_lista = mysql_fetch_array($query_lista)) {
		$id = "$field_lista[CodAplicacion].$field_lista[CodMaestro]";
		if ($grupo != $field_lista['CodAplicacion']) {
			$grupo = $field_lista['CodAplicacion'];
			?>
            <tr class="trListaBody2">
                <td align="center"><?=$field_lista['CodAplicacion']?></td>
                <td><?=($field_lista['NomAplicacion'])?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=$field_lista['CodMaestro']?></td>
			<td><?=($field_lista['Descripcion'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field_lista['Estado'])?></td>
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