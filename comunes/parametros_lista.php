<?php
if ($filtrar == "default") {
	$fedoreg = "A";
	$forganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($faplicacion != "") { $caplicacion = "checked"; $filtro.=" AND (p.CodAplicacion = '".$faplicacion."')"; } else $daplicacion = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (p.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (p.ParametroClave LIKE '%".$fbuscar."%' OR 
					p.DescripcionParam LIKE '%".$fbuscar."%' OR 
					p.ValorParam LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Par&aacute;metros</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=parametros_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
        <td>
            <input type="checkbox" <?=$corganismo?> onclick="chkFiltro(this.checked, 'forganismo');" />
            <select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $forganismo, 0)?>
            </select>            
		</td>
		<td align="right" width="125">Aplicaci&oacute;n:</td>
        <td>
            <input type="checkbox" <?=$caplicacion?> onclick="chkFiltro(this.checked, 'faplicacion');" />
            <select name="faplicacion" id="faplicacion" style="width:205px;" <?=$daplicacion?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastaplicaciones", "CodAplicacion", "Descripcion", $faplicacion, 0)?>
            </select>            
		</td>
	</tr>
	<tr>
		<td align="right">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
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
<table width="950" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=parametros_form&opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=parametros_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'parametros', 'eliminar');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=parametros_form&opcion=ver', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div class="divScroll" style="width:950px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="125">Par&aacute;metro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Tipo</th>
		<th scope="col" width="150">Valor</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				p.*,
				o.Organismo
			FROM
				mastparametros p
				INNER JOIN mastorganismos o ON (p.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.*,
				o.Organismo
			FROM
				mastparametros p
				INNER JOIN mastorganismos o ON (p.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro
			ORDER BY ParametroClave
			LIMIT ".intval($limit).", $maxlimit";
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
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['ParametroClave']?>">
			<td><?=$field['ParametroClave']?></td>
			<td><?=($field['DescripcionParam'])?></td>
			<td align="center"><?=printValoresGeneral("TIPO-CAMPO", $field['TipoValor'])?></td>
			<td><?=$field['ValorParam']?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="950">
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