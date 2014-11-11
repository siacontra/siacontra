<?php
//	------------------------------------
if ($filtrar == "default") {
	$fEstado = "A";
	$fOrderBy = "CodTipoDocumento";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (td.CodTipoDocumento LIKE '%".$fBuscar."%' OR
					  td.Descripcion LIKE '%".$fBuscar."%' OR
					  rf.Descripcion LIKE '%".$fBuscar."%' OR
					  tv.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro .= " AND (td.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fCodVoucher != "") { $cCodVoucher = "checked"; $filtro .= " AND (td.CodVoucher = '".$fCodVoucher."')"; } else $dCodVoucher = "disabled";
if ($fCodRegimenFiscal != "") { $cCodRegimenFiscal = "checked"; $filtro .= " AND (td.CodRegimenFiscal = '".$fCodRegimenFiscal."')"; } else $dCodRegimenFiscal = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Documentos Ctas. x Pagar</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_tipo_documento_cxp_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:950px;">
<table width="950" class="tblFiltro">
	<tr>
		<td align="right" width="125">Tipo de Voucher:</td>
		<td>
            <input type="checkbox" <?=$cCodVoucher?> onclick="chkFiltro(this.checked, 'fCodVoucher');" />
            <select name="fCodVoucher" id="fCodVoucher" style="width:300px;" <?=$dCodVoucher?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $fCodVoucher, 0)?>
            </select>
		</td>
		<td align="right" width="125">Regimen Fiscal:</td>
		<td>
            <input type="checkbox" <?=$cCodRegimenFiscal?> onclick="chkFiltro(this.checked, 'fCodRegimenFiscal');" />
            <select name="fCodRegimenFiscal" id="fCodRegimenFiscal" style="width:150px;" <?=$dCodRegimenFiscal?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $fCodRegimenFiscal, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:150px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
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
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=ap_tipo_documento_cxp_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_tipo_documento_cxp_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistro2(this.form, this.form.registro.value, 'tipo_documento_cxp', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_tipo_documento_cxp_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:350px;">
<table width="1200" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="35" onclick="order('CodTipoDocumento')"><a href="javascript:">Cod.</a></th>
        <th scope="col" onclick="order('Descripcion')"><a href="javascript:">Descripci&oacute;n</a></th>
        <th scope="col" width="30" onclick="order('FlagAdelanto')"><a href="javascript:">Ade.</a></th>
        <th scope="col" width="30" onclick="order('FlagProvision')"><a href="javascript:">Pro.</a></th>
        <th scope="col" width="30" onclick="order('FlagFiscal')"><a href="javascript:">Fis.</a></th>
        <th scope="col" width="30" onclick="order('FlagAutoNomina')"><a href="javascript:">Nom.</a></th>
        <th scope="col" width="200" onclick="order('NomVoucher')"><a href="javascript:">Tipo de Voucher</a></th>
        <th scope="col" width="150" onclick="order('Clasificacion')"><a href="javascript:">Clasificaci&oacute;n</a></th>
        <th scope="col" width="125" onclick="order('NomRegimenFiscal')"><a href="javascript:">R&eacute;gimen Fiscal</a></th>
        <th scope="col" width="60" onclick="order('Estado')"><a href="javascript:">Estado</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto todos
    $sql = "SELECT td.CodTipoDocumento
            FROM
				ap_tipodocumento td
				INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
				INNER JOIN ac_voucher tv ON (tv.CodVoucher = td.CodVoucher)
            WHERE 1 $filtro";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				td.CodTipoDocumento,
				td.Descripcion,
				td.FlagAdelanto,
				td.FlagProvision,
				td.FlagFiscal,
				td.FlagAutoNomina,
				td.Clasificacion,
				td.Estado,
				rf.Descripcion AS NomRegimenFiscal,
				tv.Descripcion AS NomVoucher
            FROM
				ap_tipodocumento td
				INNER JOIN ap_regimenfiscal rf ON (rf.CodRegimenFiscal = td.CodRegimenFiscal)
				INNER JOIN ac_voucher tv ON (tv.CodVoucher = td.CodVoucher)
            WHERE 1 $filtro
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodTipoDocumento]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td align="center"><?=$field['CodTipoDocumento']?></td>
            <td><?=($field['Descripcion'])?></td>
            <td align="center"><?=printFlag($field['FlagAdelanto'])?></td>
            <td align="center"><?=printFlag($field['FlagProvision'])?></td>
            <td align="center"><?=printFlag($field['FlagFiscal'])?></td>
            <td align="center"><?=printFlag($field['FlagAutoNomina'])?></td>
            <td><?=($field['NomVoucher'])?></td>
            <td><?=printValores("CLASIFICACION-CXP", $field['Clasificacion'])?></td>
            <td><?=($field['NomRegimenFiscal'])?></td>
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