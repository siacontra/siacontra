<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$flagtitulo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				p.*,
				pc.Descripcion AS NomCuenta
			FROM
				pv_partida p
				LEFT JOIN ac_mastplancuenta pc ON (p.CodCuenta = pc.CodCuenta)
			WHERE p.cod_partida = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_partida = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Registro";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$disabled_modificar = "disabled";
		$titulo = "Ver Registro";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_partida['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_partida['tipo'] == "T") $flagtitulo = "checked"; else $flagdetalle = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=clasificador_presupuestario_lista" method="POST" onsubmit="return clasificador_presupuestario(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="cod_partida" id="cod_partida" value="<?=$field_partida['cod_partida']?>" />

<table width="900" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Tipo de Cuenta:</td>
		<td>
        	<select id="cod_tipocuenta" style="width:150px;" <?=$disabled_modificar?>>
        		<?=loadSelect("pv_tipocuenta", "cod_tipocuenta", "descp_tipocuenta", $field_partida['cod_tipocuenta'], 10)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Clasificador:</td>
		<td>
        	<input type="text" id="partida1" style="width:25px; font-weight:bold; font-size:12px;" maxlength="2" value="<?=$field_partida['partida1']?>" <?=$disabled_modificar?> />.
        	<input type="text" id="generica" style="width:25px; font-weight:bold; font-size:12px;" maxlength="2" value="<?=$field_partida['generica']?>" <?=$disabled_modificar?> />.
        	<input type="text" id="especifica" style="width:25px; font-weight:bold; font-size:12px;" maxlength="2" value="<?=$field_partida['especifica']?>" <?=$disabled_modificar?> />.
        	<input type="text" id="subespecifica" style="width:25px; font-weight:bold; font-size:12px;" maxlength="2" value="<?=$field_partida['subespecifica']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="denominacion" style="width:95%;" maxlength="300" value="<?=($field_partida['denominacion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo:</td>
		<td>
            <input type="radio" name="tipo" id="titulo" value="T" <?=$flagtitulo?> <?=$disabled_ver?> /> Titulo
            <input type="radio" name="tipo" id="detalle" value="D" <?=$flagdetalle?> <?=$disabled_ver?> /> Detalle
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td class="gallery clearfix">
            <input type="text" id="CodCuenta" style="width:100px;" maxlength="13" value="<?=$field_partida['CodCuenta']?>" <?=$disabled_ver?> onChange="getDescripcionLista2('accion=getDescripcionCuenta', this, $('#NomCuenta'))" />
			<input type="text" id="NomCuenta" style="width:300px;" value="<?=($field_partida['NomCuenta'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CodCuenta&nom=NomCuenta&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_submit?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
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
			<input type="text" size="30" value="<?=$field_partida['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_partida['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:900px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#cod_tipocuenta").focus();
});
</script>