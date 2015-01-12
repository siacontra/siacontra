<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fEstado = "A";
	$fOrdenar = "CodItem";
}
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (CodItem LIKE '%".$fBuscar."%' OR
					CodInterno LIKE '%".$fBuscar."%' OR
					Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					CodLinea LIKE '%".$fBuscar."%' OR
					CodFamilia LIKE '%".$fBuscar."%' OR
					CodSubFamilia LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fCodProcedencia != "") { $cCodProcedencia = "checked"; $filtro.=" AND (CodProcedencia = '".$fCodProcedencia."')"; } else $dCodProcedencia = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Items</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_item_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
    <tr>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:150px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fEstado, 0)?>
            </select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:300px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Procedencia:</td>
		<td>
			<input type="checkbox" <?=$cCodProcedencia?> onclick="chkFiltro(this.checked, 'fCodProcedencia')" />
			<select name="fCodProcedencia" id="fCodProcedencia" style="width:150px;" <?=$dCodProcedencia?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_procedencias", "CodProcedencia", "Descripcion", $fCodProcedencia, 0)?>
			</select>
		</td>
		<td align="right">Ordenar:</td>
		<td>
			<input type="checkbox" <?=$cOrdenar?> onclick="this.checked=!this.checked;" />
			<select name="fOrdenar" id="fOrdenar" style="width:100px;" <?=$dOrdenar?>>
				<option value="">&nbsp;</option>
				<?=loadSelectGeneral("ORDENAR-ITEMS", $fOrdenar, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=lg_item_form&opcion=nuevo');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_item_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, $('#registro').val(), 'items', 'eliminar');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_item_form&opcion=ver', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th width="100" scope="col">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Und.</th>
		<th width="90" scope="col">Linea</th>
		<th width="90" scope="col">Familia</th>
		<th width="90" scope="col">Sub-Familia</th>
		<th width="75" scope="col">Cod. Interno</th>
		<th width="60" scope="col">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT *
			FROM lg_itemmast
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT *
			FROM lg_itemmast
			WHERE 1 $filtro
			ORDER BY $fOrdenar
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodItem']?>">
			<td align="center"><?=$field['CodItem']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="center"><?=$field['CodLinea']?></td>
			<td align="center"><?=$field['CodFamilia']?></td>
			<td align="center"><?=$field['CodSubFamilia']?></td>
			<td align="center"><?=$field['CodInterno']?></td>
			<td align="center"><?=printValoresGeneral("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
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