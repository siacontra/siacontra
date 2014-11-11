<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "A";
	$fOrderBy = "Anio";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (Anio LIKE '%".$fBuscar."%' OR
					  Secuencia LIKE '%".$fBuscar."%' OR
					  Fecha LIKE '%".formatFechaAMD($fBuscar)."%' OR
					  GacetaOficial LIKE '%".$fBuscar."%' OR
					  ProvidenciaNro LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Unidad Tributaria</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=unidad_tributaria_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:600px;">
<table width="600" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:60%;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="600" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=unidad_tributaria_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=unidad_tributaria_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, this.form.registro.value, 'unidad_tributaria', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=unidad_tributaria_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:600px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="75" onclick="order('Anio')"><a href="javascript:">Periodo</a></th>
        <th scope="col" width="25" onclick="order('Secuencia')"><a href="javascript:">#</a></th>
        <th scope="col" width="75" onclick="order('Fecha')"><a href="javascript:">Fecha</a></th>
        <th scope="col" onclick="order('GacetaOficial')"><a href="javascript:">Gaceta</a></th>
        <th scope="col" width="75" onclick="order('Valor')"><a href="javascript:">Valor</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto todos
    $sql = "SELECT
				Anio,
				Secuencia
            FROM mastunidadtributaria ut
            WHERE 1 $filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				Anio,
				Secuencia,
				Fecha,
				GacetaOficial,
				Valor
            FROM mastunidadtributaria
            WHERE 1 $filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[Anio]_$field[Secuencia]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td align="center"><?=$field['Anio']?></td>
            <td align="center"><?=$field['Secuencia']?></td>
            <td align="center"><?=formatFechaDMA($field['Fecha'])?></td>
            <td><?=htmlentities($field['GacetaOficial'])?></td>
            <td align="right"><?=number_format($field['Valor'], 2, ',', '.')?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<table width="600">
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