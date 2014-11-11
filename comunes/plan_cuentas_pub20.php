<?php
if ($filtrar == "default") {
	$fordenar = "pc.CodCuenta";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (pc.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (pc.CodCuenta LIKE '%".$fbuscar."%' OR
					pc.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($ftipocuenta != "") { $ctipocuenta = "checked"; $filtro.=" AND (pc.TipoCuenta = '".$ftipocuenta."')"; } else $dtipocuenta = "disabled";
if ($fnaturaleza != "") { $cnaturaleza = "checked"; $filtro.=" AND (pc.TipoSaldo = '".$fnaturaleza."')"; } else $dnaturaleza = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plan de Cuentas Pub. 20 </td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=plan_cuentas_pub20" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right">Tipo de Cuenta:</td>
		<td>
            <input type="checkbox" <?=$ctipocuenta?> onclick="chkFiltro(this.checked, 'ftipocuenta');" />
            <select name="ftipocuenta" id="ftipocuenta" style="width:125px;" <?=$dtipocuenta?>>
                <option value="">&nbsp;</option>
                <?=getMiscelaneos($ftipocuenta, "CUENTPUB20")?>
            </select>
		</td>
		<td align="right">Naturaleza:</td>
		<td>
            <input type="checkbox" <?=$cnaturaleza?> onclick="chkFiltro(this.checked, 'fnaturaleza');" />
            <select name="fnaturaleza" id="fnaturaleza" style="width:100px;" <?=$dnaturaleza?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("TIPO-SALDO", $fnaturaleza, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right" width="125">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:125px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-PLANCUENTAS", $fordenar, 0)?>
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
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'gehen.php?anz=plan_cuentas_pub20_form&opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion2(this.form, 'gehen.php?anz=plan_cuentas_pub20_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro2(this.form, this.form.registro.value, 'plan_cuentas', 'eliminar');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion2(this.form, 'gehen.php?anz=plan_cuentas_pub20_form&opcion=ver', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="85">Cuenta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="115">Tipo de Cuenta</th>
		<th scope="col" width="75">Naturaleza</th>
		<th scope="col" width="60">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos	
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion,
				pc.TipoCuenta,
				pc.TipoSaldo,
				pc.Estado,
				md.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta20 pc
				INNER JOIN mastmiscelaneosdet md ON (pc.TipoCuenta = md.CodDetalle AND md.CodMaestro = 'CUENTPUB20')
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion,
				pc.TipoCuenta,
				pc.TipoSaldo,
				pc.Estado,
				md.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta20 pc
				INNER JOIN	mastmiscelaneosdet md ON (pc.TipoCuenta = md.CodDetalle AND md.CodMaestro = 'CUENTPUB20')
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodCuenta']?>">
			<td><?=$field['CodCuenta']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=ucwords(strtolower($field['NomTipoCuenta']));?></td>
			<td align="center"><?=printValoresGeneral("TIPO-SALDO", $field['TipoSaldo'])?></td>
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