<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Maestro";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	//$CodAplicacion = $_SESSION["APLICACION_ACTUAL"];
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodAplicacion, $CodMaestro) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT mm.*
			FROM mastmiscelaneoscab mm
			WHERE
				mm.CodMaestro = '".$CodMaestro."' AND
				mm.CodAplicacion = '".$CodAplicacion."'";
	$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Maestro";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$disabled_modificar = "disabled";
		$titulo = "Ver Maestro";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_mast['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=miscelaneos_lista" method="POST" onsubmit="return miscelaneos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="500" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Informaci&oacute;n General</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* Aplicaci&oacute;n:</td>
		<td>
            <select id="CodAplicacion" style="width:295px;" <?=$disabled_modificar?>>
                <?=loadSelectAplicacion($CodAplicacion, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="125">* Maestro:</td>
		<td>
        	<input type="text" id="CodMaestro" style="width:100px; font-weight:bold; font-size:12px;" maxlength="10" value="<?=$field_mast['CodMaestro']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:80%;" maxlength="100" value="<?=$field_mast['Descripcion']?>" <?=$disabled_ver?> />
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
			<input type="text" size="30" value="<?=$field_mast['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_mast['UltimaFecha']?>" disabled="disabled" />
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

<form name="frm_det" id="frm_det">
<input type="hidden" name="sel_det" id="sel_det" />
<center>
<div style="width:500px; height:16px;" class="divFormCaption">Sub-Grupos</div>
<table width="500" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'miscelaneos_det_insertar', 'det', true);" style=" <?=$display_submit?>" />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'det');" style=" <?=$display_submit?>" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:500px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="50">Detalle</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="50">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_det">
    <?
	$nrosub = 0;
	if ($opcion != "nuevo") {
		//	consulto datos generales
		$sql = "SELECT md.*
				FROM mastmiscelaneosdet md
				WHERE
					md.CodMaestro = '".$CodMaestro."' AND
					md.CodAplicacion = '".$CodAplicacion."'
				ORDER BY CodDetalle";
		$query_det = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_det = mysql_fetch_array($query_det)) {
			$nrodet++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_det');" id="det_<?=$nrodet?>">
				<td align="center">
					<input type="text" name="CodDetalle" maxlength="2" class="cell" style="text-align:center;" value="<?=$field_det['CodDetalle']?>" <?=$disabled_ver?> />
                </td>
                <td align="center">
                	<input type="text" name="Descripcion" maxlength="50" class="cell" style="" value="<?=$field_det['Descripcion']?>" <?=$disabled_ver?> />
                </td>
                <td align="center">
                	<select name="Estado" class="cell" <?=$disabled_ver?>>
                        <?=loadSelectGeneral("ESTADO", $field_det['Estado'], 0)?>
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
<input type="hidden" id="nro_det" value="<?=$nrodet?>" />
<input type="hidden" id="can_det" value="<?=$nrodet?>" />
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#CodMaestro").focus();
});
</script>