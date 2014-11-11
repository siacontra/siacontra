<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Grupo";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT gcc.*
			FROM ac_grupocentrocosto gcc
			WHERE gcc.CodGrupoCentroCosto = '".$registro."'";
	$query_grupo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_grupo)) $field_grupo = mysql_fetch_array($query_grupo);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Grupo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$disabled_modificar = "disabled";
		$titulo = "Ver Grupo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_submit = "display:none;";
	}
	
	if ($field_grupo['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=grupo_centro_costos_lista" method="POST" onsubmit="return grupo_centro_costos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="500" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Informaci&oacute;n General</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* Grupo:</td>
		<td>
        	<input type="text" id="CodGrupoCentroCosto" style="width:50px; font-weight:bold; font-size:12px;" maxlength="4" value="<?=$field_grupo['CodGrupoCentroCosto']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:80%;" maxlength="50" value="<?=$field_grupo['Descripcion']?>" <?=$disabled_ver?> />
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
			<input type="text" size="30" value="<?=$field_grupo['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_grupo['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
</form>
<br />
<div style="width:500px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
<br />

<form name="frm_sub" id="frm_sub">
<input type="hidden" name="sel_sub" id="sel_sub" />
<center>
<div style="width:500px; height:16px;" class="divFormCaption">Sub-Grupos</div>
<table width="500" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'grupo_centro_costos_sub_insertar', 'sub', true);" style=" <?=$display_submit?>" />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'sub');" style=" <?=$display_submit?>" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:500px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="50">SubGrupo</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="50">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_sub">
    <?
	$nrosub = 0;
	if ($opcion != "nuevo") {
		//	consulto datos generales
		$sql = "SELECT sgcc.*
				FROM ac_subgrupocentrocosto sgcc
				WHERE sgcc.CodGrupoCentroCosto = '".$registro."'";
		$query_sub = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_sub = mysql_fetch_array($query_sub)) {
			$nrosub++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_sub');" id="sub_<?=$nrosub?>">
				<td align="center">
					<input type="text" name="CodSubGrupoCentroCosto" maxlength="4" class="cell" style="text-align:center;" value="<?=$field_sub['CodSubGrupoCentroCosto']?>" <?=$disabled_ver?> />
                </td>
                <td align="center">
                	<input type="text" name="Descripcion" maxlength="50" class="cell" style="" value="<?=$field_sub['Descripcion']?>" <?=$disabled_ver?> />
                </td>
                <td align="center">
                	<select name="Estado" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$disabled_ver?>>
                        <?=loadSelectGeneral("ESTADO", $field_sub['Estado'], 0)?>
                    </select>
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
<input type="hidden" id="nro_sub" value="<?=$nrosub?>" />
<input type="hidden" id="can_sub" value="<?=$nrosub?>" />
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#CodGrupoCentroCosto").focus();
});
</script>