<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($lista == "todos") {
	$titulo = "Listado de Caja Chica";
	$btAprobar = "display:none;";
}
elseif ($lista == "aprobar") {
	$fEstado = "PR";
	$titulo = "Aprobar Caja Chica";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$fEstado = "PR";
	$fOrderBy = "CodOrganismo";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (cc.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (cc.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (cc.FlagCajaChica LIKE '%".$fBuscar."%' OR
					  cc.Periodo LIKE '%".$fBuscar."%' OR
					  cc.NroCajaChica LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_caja_chica_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="sel_registros" id="sel_registros" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />

<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?> onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true, 'fCodCentroCosto');">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;" <?=$dBuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onChange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Fecha Preparaci&oacute;n: </td>
		<td>
			<input type="checkbox" <?=$cFechaPreparacion?> onclick="chkFiltro_2(this.checked, 'fFechaPreparacionD', 'fFechaPreparacionH');" />
			<input type="text" name="fFechaPreparacionD" id="fFechaPreparacionD" value="<?=$fFechaPreparacionD?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" /> -
            <input type="text" name="fFechaPreparacionH" id="fFechaPreparacionH" value="<?=$fFechaPreparacionH?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto');" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", $fCodCentroCosto, 0)?>
			</select>
		</td>
		<td align="right">Estado: </td>
		<td>
        	<input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:143px;" <?=$dEstado?>>
                <option value=""></option>
                <?=loadSelectValores("ESTADO", $fEstado, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1000" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=ap_caja_chica_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#sel_registros').val(), 'accion=caja_chica_modificar', 'gehen.php?anz=ap_caja_chica_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcionValidar2(this.form, $('#sel_registros').val(), 'accion=caja_chica_modificar', 'gehen.php?anz=ap_caja_chica_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#sel_registros').val(), 'accion=caja_chica_modificar', 'gehen.php?anz=ap_caja_chica_form&opcion=modificar', 'SELF', '');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_caja_chica_form&opcion=ver', 'SELF', '', $('#sel_registros').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:1000px; height:350px;">
<table width="2300" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="20" onclick="order('FlagCajaChica')" title="Caja Chica / Reporte de Gasto">
        	<a href="javascript:">C/R</a>
        </th>
        <th scope="col" width="50" onclick="order('Periodo')"><a href="javascript:">Periodo</a></th>
        <th scope="col" width="50" onclick="order('NroCajaChica')"><a href="javascript:">N&uacute;mero</a></th>
        <th scope="col" align="left" onclick="order('Descripcion')"><a href="javascript:">Descripci&oacute;n</a></th>
        <th scope="col" width="100" onclick="order('MontoTotal')"><a href="javascript:">Monto Total</a></th>
        <th scope="col" width="75" onclick="order('FechaPreparacion')"><a href="javascript:">Fecha Preparaci&oacute;n</a></th>
        <th scope="col" width="75" onclick="order('FechaAprobacion')"><a href="javascript:">Fecha Aprobaci&oacute;n</a></th>
        <th scope="col" width="100" onclick="order('Estado')"><a href="javascript:">Estado</a></th>
        <th scope="col" width="40" onclick="order('CodCentroCosto')"><a href="javascript:">C.C.</a></th>
        <th scope="col" width="150" onclick="order('NroObligacion')"><a href="javascript:">Obligaci&oacute;n</a></th>
        <th scope="col" width="400" align="left" onclick="order('NomBeneficiario')"><a href="javascript:">Beneficiario</a></th>
        <th scope="col" width="75" onclick="order('CodOrganismo')"><a href="javascript:">Organismo</a></th>
        <th scope="col" width="400" align="left" onclick="order('Dependencia')"><a href="javascript:">Dependencia</a></th>
    </tr>
    </thead>
    
    <tbody id="lista_registros">
    <?php
    //	consulto todos
    $sql = "SELECT
				*
            FROM
				ap_cajachica
            WHERE 1";
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_total = mysql_num_rows($query);
    
    //	consulto lista
    $sql = "SELECT
				*
            FROM
				ap_cajachica
            WHERE 1 
            ORDER BY $fOrderBy
            LIMIT ".intval($limit).", ".intval($maxlimit);
    $query = mysql_query($sql) or die ($sql.mysql_error());
    $rows_lista = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[FlagCajaChica]_$field[Periodo]_$field[NroCajaChica]";
        ?>
        <tr class="trListaBody" onclick="clk($(this), 'registros', '<?=$id?>');">
            <td align="center"><?=$field['FlagCajaChica']?></td>
            <td align="center"><?=$field['Periodo']?></td>
            <td align="center"><?=$field['NroCajaChica']?></td>
            <td><?=htmlentities($field['Descripcion'])?></td>
            <td align="right"><strong><?=number_format($field['MontoTotal'], 2, ',', '.')?></strong></td>
            <td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaAprobacion'])?></td>
            <td align="center"><?=printValores("ESTADO-CAJACHICA", $field['Estado'])?></td>
            <td align="center"><?=$field['CodCentroCosto']?></td>
            <td align="center"><?=$field['NroObligacion']?></td>
            <td><?=htmlentities($field['NomBeneficiario'])?></td>
            <td align="center"><?=$field['CodOrganismo']?></td>
            <td><?=htmlentities($field['Dependencia'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<table width="1000">
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