<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Cuenta";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$flagdeudora = "checked";
	$flagprincipal = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT pc.*
			FROM ac_mastplancuenta pc
			WHERE pc.CodCuenta = '".$registro."'";
	$query_cuenta = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_cuenta)) $field_cuenta = mysql_fetch_array($query_cuenta);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Cuenta";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Cuenta";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
		$display_submit = "display:none;";
	}
	
	if ($field_cuenta['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_cuenta['TipoSaldo'] == "D") $flagdeudora = "checked"; else $flagacreedora = "checked";
	if ($field_cuenta['FlagTipo'] == "P") $flagprincipal = "checked"; else $flagauxiliar = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="900" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Datos de la Cuenta</a></li>
            <li id="li2" onclick="currentTab('tab', this);" style=" <?=$tabEmpleado?>"><a href="#" onclick="mostrarTab('tab', 2, 2);">Partidas Presupuestarias</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=plan_cuentas_lista" method="POST" onsubmit="return plan_cuentas(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="ftipocuenta" id="ftipocuenta" value="<?=$ftipocuenta?>" />
<input type="hidden" name="fnaturaleza" id="fnaturaleza" value="<?=$fnaturaleza?>" />

<div id="tab1" style="display:block;">
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Nivel:</td>
		<td>
            <select id="Nivel" style="width:205px;" <?=$disabled_modificar?>>
                <?=loadSelectGeneral("NIVEL-CUENTA", $field_cuenta['Nivel'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Cuenta:</td>
		<td>
        	<input type="text" id="CodCuenta" style="width:200px; font-weight:bold; font-size:12px;" maxlength="13" value="<?=$field_cuenta['CodCuenta']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:70%;" maxlength="255" value="<?=$field_cuenta['Descripcion']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Tipo de Cuenta:</td>
		<td>
            <select id="TipoCuenta" style="width:205px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field_cuenta['TipoCuenta'], "CUENTAS", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Naturaleza:</td>
		<td>
            <input type="radio" name="TipoSaldo" id="deudora" value="D" <?=$flagdeudora?> <?=$disabled_ver?> /> Deudora
            <input type="radio" name="TipoSaldo" id="acreedora" value="A" <?=$flagacreedora?> <?=$disabled_ver?> /> Acreedora
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nivel de Cuenta:</td>
		<td>
            <input type="radio" name="FlagTipo" id="principal" value="P" <?=$flagprincipal?> <?=$disabled_ver?> /> Principal
            <input type="radio" name="FlagTipo" id="auxiliar" value="A" <?=$flagauxiliar?> <?=$disabled_ver?> /> Auxiliar
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="activo" value="A" <?=$flagactivo?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="Estado" id="inactivo" value="I" <?=$flaginactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_cuenta['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_cuenta['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:900px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</div>

<div id="tab2" style="display:none;">
<center>
<div style="overflow:scroll; width:900px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">Partida</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?
	if ($opcion != "nuevo") {
		$sql = "SELECT cod_partida, denominacion
				FROM pv_partida p
				WHERE CodCuenta = '".$registro."'";
		$query_partida = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_partida = mysql_fetch_array($query_partida)) {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_nivel');" id="nivel_<?=$nronivel?>">
				<td align="center">
					<?=$field_partida['cod_partida']?>
                </td>
                <td>
                	<?=($field_partida['denominacion'])?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#CodCuenta").focus();
});
</script>