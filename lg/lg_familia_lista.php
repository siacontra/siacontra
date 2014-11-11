<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fEstado = "A";
}
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (cf.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (cf.CodFamilia LIKE '%".$fBuscar."%' OR
					cf.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					cl.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Familias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_familia_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
    <tr>
		<td align="right">Linea:</td>
		<td>
            <input type="checkbox" <?=$cCodLinea?> onclick="chkFiltro(this.checked, 'fCodLinea');" />
            <select name="fCodLinea" id="fCodLinea" style="width:255px;" <?=$dCodLinea?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("lg_claselinea", "CodLinea", "Descripcion", $fCodLinea, 0)?>
            </select>
		</td>
		<td align="right">Estado:</td>
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
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=lg_familia_form&opcion=nuevo');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_familia_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, $('#registro').val(), 'familia', 'eliminar');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_familia_form&opcion=ver', 'SELF', '', 'registro');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th width="100" scope="col">Familia</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="60" scope="col">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				cf.*,
				cl.Descripcion AS NomLinea
			FROM
				lg_clasefamilia cf
				INNER JOIN lg_claselinea cl ON (cf.CodLinea = cl.CodLinea)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				cf.*,
				cl.Descripcion AS NomLinea
			FROM
				lg_clasefamilia cf
				INNER JOIN lg_claselinea cl ON (cf.CodLinea = cl.CodLinea)
			WHERE 1 $filtro
			ORDER BY cf.Codlinea, cf.CodFamilia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodLinea].$field[CodFamilia]";
		if ($grupo != $field['CodLinea']) {
			$grupo = $field['CodLinea'];
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field['CodLinea']?></td>
				<td><?=($field['NomLinea'])?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodFamilia']?></td>
			<td><?=($field['Descripcion'])?></td>
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