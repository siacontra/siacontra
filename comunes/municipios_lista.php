<?php
if ($filtrar == "default") {
	$fordenar = "m.CodMunicipio";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fpais = $_PARAMETRO["PAISDEFAULT"];
	$festado = $_PARAMETRO["ESTADODEFAULT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY m.CodEstado, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (m.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (m.CodMunicipio LIKE '%".$fbuscar."%' OR
					m.Municipio LIKE '%".$fbuscar."%' OR
					e.Estado LIKE '%".$fbuscar."%' OR
					p.Pais LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($fpais != "") { $cpais = "checked"; $filtro.=" AND (e.CodPais = '".$fpais."')"; } else $dpais = "disabled";
if ($festado != "") { $cestado = "checked"; $filtro.=" AND (m.CodEstado = '".$festado."')"; } else $destado = "disabled";
//	------------------------------------
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Municipios</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=municipios_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="100">Pais:</td>
		<td>
            <input type="checkbox" <?=$cpais?> onclick="chkFiltro(this.checked, 'fpais');" />
            <select name="fpais" id="fpais" style="width:206px;" onchange="getOptionsSelect(this.value, 'estado', 'festado', 206, 1);" <?=$dpais?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $fpais, 0)?>
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
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cestado?> onclick="chkFiltro(this.checked, 'festado');" />
            <select name="festado" id="festado" style="width:206px;" <?=$destado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", $festado, $fpais, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-MUNICIPIOS", $fordenar, 0)?>
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
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=municipios_form&opcion=nuevo');" />
            
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=municipios_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'municipios', 'eliminar');" />
            
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=municipios_form&opcion=ver', 'BLANK', 'height=300, width=800, left=100, top=0, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="100">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos	
	$sql = "SELECT
				m.*,
				e.Estado AS NomEstado,
				p.CodPais,
				p.Pais
			FROM
				mastmunicipios m
				INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
				INNER JOIN mastpaises p ON (e.CodPais = p.CodPais)
			WHERE 1 $filtro";
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total = mysql_num_rows($query_lista);
	
	//	consulto lista	
	$sql = "SELECT
				m.*,
				e.Estado AS NomEstado,
				p.CodPais,
				p.Pais
			FROM
				mastmunicipios m
				INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
				INNER JOIN mastpaises p ON (e.CodPais = p.CodPais)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query_lista = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_lista = mysql_num_rows($query_lista);
	while ($field_lista = mysql_fetch_array($query_lista)) {
		if ($grupo1 != $field_lista['CodPais']) {
			$grupo1 = $field_lista['CodPais'];
			$grupo2 = "";
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field_lista['CodPais']?></td>
				<td><?=($field_lista['Pais'])?></td>
			</tr>
			<?	
		}
		
		if ($grupo2 != $field_lista['CodEstado']) {
			$grupo2 = $field_lista['CodEstado'];
			?>
			<tr class="trListaBody3">
				<td align="center"><?=$field_lista['CodEstado']?></td>
				<td><?=($field_lista['NomEstado'])?></td>
			</tr>
			<?	
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field_lista['CodMunicipio']?>">
			<td align="center"><?=$field_lista['CodMunicipio']?></td>
			<td><?=($field_lista['Municipio'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field_lista['Estado'])?></td>
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