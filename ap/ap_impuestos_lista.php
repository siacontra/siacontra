<?php
if ($filtrar == "default") {
	$fEstado = "A";
	$fOrderBy = "CodImpuesto";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodRegimenFiscal != "") { $cCodRegimenFiscal = "checked"; $filtro.=" AND (i.CodRegimenFiscal = '".$fCodRegimenFiscal."')"; } else $dCodRegimenFiscal = "disabled";
if ($fFlagProvision != "") { $cFlagProvision = "checked"; $filtro.=" AND (i.FlagProvision = '".$fFlagProvision."')"; } else $dFlagProvision = "disabled";
if ($fFlagImponible != "") { $cFlagImponible = "checked"; $filtro.=" AND (i.FlagImponible = '".$fFlagImponible."')"; } else $dFlagImponible = "disabled";
if ($fTipoComprobante != "") { $cTipoComprobante = "checked"; $filtro.=" AND (i.TipoComprobante = '".$fTipoComprobante."')"; } else $dTipoComprobante = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (i.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (i.CodImpuesto LIKE '%".$fBuscar."%' OR
					  i.Descripcion LIKE '%".$fBuscar."%' OR
					  rf.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Impuestos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_impuestos_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />

<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
    <tr>
		<td align="right" width="100">Regimen Fiscal: </td>
		<td>
       		<input type="checkbox" <?=$cCodRegimenFiscal?> onclick="chkFiltro(this.checked, 'fCodRegimenFiscal');" />
            <select name="fCodRegimenFiscal" id="fCodRegimenFiscal" style="width:150px;" <?=$dCodRegimenFiscal?>>
                <option value=""></option>
                <?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $fCodRegimenFiscal, 0)?>
            </select>
		</td>
		<td align="right" width="100">Imponible: </td>
		<td>
       		<input type="checkbox" <?=$cFlagImponible?> onclick="chkFiltro(this.checked, 'fFlagImponible');" />
            <select name="fFlagImponible" id="fFlagImponible" style="width:100px;" <?=$dFlagImponible?>>
                <option value=""></option>
                <?=loadSelectValores("IMPUESTO-IMPONIBLE", $fFlagImponible, 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td align="right">Provisi&oacute;n En: </td>
		<td>
       		<input type="checkbox" <?=$cFlagProvision?> onclick="chkFiltro(this.checked, 'fFlagProvision');" />
            <select name="fFlagProvision" id="fFlagProvision" style="width:150px;" <?=$dFlagProvision?>>
                <option value=""></option>
                <?=loadSelectValores("IMPUESTO-PROVISION", $fFlagProvision, 0)?>
            </select>
		</td>
		<td align="right">Tipo: </td>
		<td>
       		<input type="checkbox" <?=$cTipoComprobante?> onclick="chkFiltro(this.checked, 'fTipoComprobante');" />
            <select name="fTipoComprobante" id="fTipoComprobante" style="width:100px;" <?=$dTipoComprobante?>>
                <option value=""></option>
                <?=loadSelectValores("IMPUESTO-COMPROBANTE", $fTipoComprobante, 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:264px;" <?=$dBuscar?> />
		</td>
		<td align="right">Estado: </td>
		<td>
       		<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fEstado, 0)?>
            </select>
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=ap_impuestos_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=impuestos_modificar', 'gehen.php?anz=ap_impuestos_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_impuestos_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:350px;">
<table width="1000" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="50" onclick="order('CodImpuesto')"><a href="javascript:">C&oacute;digo</a></th>
        <th scope="col" align="left" onclick="order('Descripcion')"><a href="javascript:">Descripci&oacute;n</a></th>
        <th scope="col" width="60" onclick="order('TipoComprobante')"><a href="javascript:">Tipo</a></th>
        <th scope="col" width="125" align="left" onclick="order('NomRegimenFiscal')"><a href="javascript:">Regimen Fiscal</a></th>
        <th scope="col" width="50" align="right" onclick="order('FactorPorcentaje')"><a href="javascript:">%</a></th>
        <th scope="col" width="150" align="left" onclick="order('FlagProvision')"><a href="javascript:">Provisi&oacute;n En</a></th>
        <th scope="col" width="100" align="left" onclick="order('FlagImponible')"><a href="javascript:">Imponible</a></th>
        <th scope="col" width="35" onclick="order('Signo')"><a href="javascript:">Signo</a></th>
        <th scope="col" width="65" onclick="order('Estado')"><a href="javascript:">Estado</a></th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto todos
    $sql = "SELECT i.CodImpuesto
            FROM mastimpuestos i
            WHERE 1 $filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				i.CodImpuesto,
				i.Descripcion,
				i.TipoComprobante,
				i.FactorPorcentaje,
				i.FlagProvision,
				i.FlagImponible,
				i.Signo,
				i.Estado,
				rf.Descripcion AS NomRegimenFiscal
            FROM
				mastimpuestos i
				INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = i.CodRegimenFiscal)
            WHERE 1 $filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodImpuesto]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <td align="center"><?=$field['CodImpuesto']?></td>
            <td><?=($field['Descripcion'])?></td>
            <td align="center"><?=printValores("IMPUESTO-COMPROBANTE", $field['TipoComprobante'])?></td>
            <td><?=($field['NomRegimenFiscal'])?></td>
            <td align="right"><strong><?=number_format($field['FactorPorcentaje'], 2, ',', '.')?></strong></td>
            <td><?=printValores("IMPUESTO-PROVISION", $field['FlagProvision'])?></td>
            <td><?=printValores("IMPUESTO-IMPONIBLE", $field['FlagImponible'])?></td>
            <td align="center"><?=printSigno($field['Signo'])?></td>
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