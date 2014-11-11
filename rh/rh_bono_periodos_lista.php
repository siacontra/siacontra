<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($lista == "todos") {
	$titulo = "Lista de Periodos";
	$btRegistrar = "display:none;";
}
elseif ($lista == "eventos") {
	$titulo = "Lista de Periodos";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btCerrar = "display:none;";
	$fEstado = "A";
}
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "A";
	$fOrderBy = "CodOrganismo";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (ba.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (ba.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (ba.Periodo LIKE '%".$fBuscar."%' OR
					  tn.Nomina LIKE '%".$fBuscar."%' OR
					  ba.CodBonoAlim LIKE '%".$fBuscar."%' OR
					  o.Organismo LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_bono_periodos_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />

<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<div class="divBorder" style="width:700px;">
<table width="700" class="tblFiltro">
	<tr>
		<td align="right" width="100">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:270px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="75">Estado: </td>
		<td>
        <?php
		if ($lista == "eventos") {
			?>
        	<input type="checkbox" <?=$cEstado?> onclick="this.checked=!this.checked;" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <?=loadSelectValores("ESTADO-BONO", $fEstado, 1)?>
            </select>
            <?
		} else {
			?>
        	<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectValores("ESTADO-BONO", $fEstado, 0)?>
            </select>
            <?
		}
		?>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:264px;" <?=$dBuscar?> />
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_bono_periodos_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=bono_periodos_modificar', 'gehen.php?anz=rh_bono_periodos_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btCerrar" value="Cerrar" style="width:75px; <?=$btCerrar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=bono_periodos_cerrar', 'gehen.php?anz=rh_bono_periodos_form&opcion=cerrar', 'SELF', '');" />
            
            <input type="button" id="btRegistrar" value="Registrar" style="width:75px; <?=$btRegistrar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_bono_periodos_registrar_lista&filtrar=default', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_bono_periodos_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:700px; height:350px;">
<table width="1500" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50" onclick="order('CodBonoAlim')"><a href="javascript:">Nro.</a></th>
        <th scope="col" width="75" onclick="order('Periodo')"><a href="javascript:">Periodo</a></th>
        <th scope="col" width="200" align="left" onclick="order('Nomina')"><a href="javascript:">N&oacute;mina</a></th>
        <th scope="col" align="left" onclick="order('Organismo')"><a href="javascript:">Organismo</a></th>
        <th scope="col" width="400" align="left" onclick="order('Descripcion')"><a href="javascript:">Descripci&oacute;n</a></th>
        <th scope="col" width="75" onclick="order('Estado')"><a href="javascript:">Estado</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto todos
    $sql = "SELECT
				ba.Anio,
				ba.CodOrganismo,
				ba.CodBonoAlim
            FROM rh_bonoalimentacion ba
            WHERE 1 $filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				ba.Anio,
				ba.CodOrganismo,
				ba.CodBonoAlim,
				ba.Periodo,
				ba.Estado,
				ba.Descripcion,
				tn.Nomina,
				o.Organismo
            FROM
				rh_bonoalimentacion ba
				INNER JOIN tiponomina tn ON (tn.CodTipoNom = ba.CodTipoNom)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = ba.CodOrganismo)
            WHERE 1 $filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[Anio]_$field[CodOrganismo]_$field[CodBonoAlim]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td align="center"><?=$field['CodBonoAlim']?></td>
            <td align="center"><?=$field['Periodo']?></td>
            <td><?=htmlentities($field['Nomina'])?></td>
            <td><?=htmlentities($field['Organismo'])?></td>
            <td><?=htmlentities($field['Descripcion'])?></td>
            <td align="center"><?=printValores("ESTADO-BONO", $field['Estado'])?></td>
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