<?php
if ($filtrar == "default") {
	$fordenar = "p.CodPersona";
	$fedoreg = "A";
	$factualizar = "Persona";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (p.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($factualizar != "") $cactualizar = "checked"; else $dactualizar = "disabled";
if ($fclase != "") { $cclase = "checked"; $filtro.=" AND (p.TipoPersona = '".$fclase."')"; } else $dclase = "disabled";
if ($ftipo != "") {
	$ctipo = "checked";
	if ($ftipo == "EsEmpleado") $filtro.=" AND (p.EsEmpleado = 'S' )";
	elseif ($ftipo == "EsProveedor") $filtro.=" AND (p.EsProveedor = 'S' )";
	elseif ($ftipo == "EsCliente") $filtro.=" AND (p.EsCliente = 'S' )";
	elseif ($ftipo == "EsOtro") $filtro.=" AND (p.EsOtro = 'S' )";
} else $dtipo = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (p.CodPersona LIKE '%".$fbuscar."%' OR 
					p.NomCompleto LIKE '%".$fbuscar."%' OR 
					p.Ndocumento LIKE '%".$fbuscar."%' OR 
					p.DocFiscal LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Personas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=personas_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
            </select>
		</td>
		<td align="right" width="125">Clase de Persona:</td>
        <td width="300">
            <input type="checkbox" <?=$cclase?> onclick="chkFiltro(this.checked, 'fclase');" />
            <select name="fclase" id="fclase" style="width:100px;" <?=$dclase?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("CLASE-PERSONA", $fclase, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Tipo de Persona:</td>
		<td>
            <input type="checkbox" <?=$ctipo?> onclick="chkFiltro(this.checked, 'ftipo');" />
            <select name="ftipo" id="ftipo" style="width:100px;" <?=$dtipo?>>
                <option value="">&nbsp;</option>
                <?=loadSelectValores("TIPO-PERSONA", $ftipo, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Actualizar:</td>
		<td>
            <input type="checkbox" <?=$cactualizar?> onclick="this.checked=!this.checked;" />
            <select name="factualizar" id="factualizar" style="width:100px;" <?=$dactualizar?>>
                <?=loadSelectValores("ACTUALIZAR-PERSONA", $factualizar, 0)?>
            </select>
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-PERSONA", $fordenar, 0)?>
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
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=personas_form&opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=personas_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=personas_form&opcion=ver', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="55">Persona</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Emp.</th>
		<th scope="col" width="25">Pro.</th>
		<th scope="col" width="25">Cli.</th>
		<th scope="col" width="25">Otr.</th>
		<th scope="col" width="100">Nro. Documento</th>
		<th scope="col" width="100">Doc. Fiscal</th>
		<th scope="col" width="60">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos	
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.EsEmpleado,
				p.EsProveedor,
				p.EsCliente,
				p.EsOtros,
				p.TipoPersona,
				p.Ndocumento,
				p.DocFiscal,
				p.Estado
			FROM mastpersonas p
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.CodPersona,
				p.NomCompleto,
				p.EsEmpleado,
				p.EsProveedor,
				p.EsCliente,
				p.EsOtros,
				p.TipoPersona,
				p.Ndocumento,
				p.DocFiscal,
				p.Estado
			FROM mastpersonas p
			WHERE 1 $filtro				
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodPersona']?>">
			<td align="center"><?=$field['CodPersona']?></td>
			<td><?=($field['NomCompleto'])?></td>
			<td align="center"><?=printFlag($field['EsEmpleado'])?></td>
			<td align="center"><?=printFlag($field['EsProveedor'])?></td>
			<td align="center"><?=printFlag($field['EsCliente'])?></td>
			<td align="center"><?=printFlag($field['EsOtros'])?></td>
			<td><?=$field['Ndocumento']?></td>
			<td><?=$field['DocFiscal']?></td>
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
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_total?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>