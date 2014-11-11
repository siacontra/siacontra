<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
//	------------------------------------
if ($accion == "VER") $disabled = "disabled";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<form name="frmentrada" id="frmentrada" action="commodity_sub_clasificacion.php" method="POST" onsubmit="return verificarSubCommodity(this, document.getElementById('accion').value);">
<input type="hidden" name="codigo" id="codigo" />
<table align="center" width="98%" class="tblForm">
	<tr>
		<td>Descripci&oacute;n:</td>
		<td width="12%">Unidad:</td>
		<td width="15%">Partida:</td>
		<td width="15%">Cuenta:</td>
		<td width="15%">Activo:</td>
		<td width="10%">Estado:</td>
	</tr>
	<tr>
		<td><input type="text" name="descripcion" id="descripcion" maxlength="50" style="width:99%;" <?=$disabled?> /></td>
		<td>
			<select name="unidad" id="unidad" <?=$disabled?> style="width:99%;">
				<option value=""></option>
				<?=loadSelect("mastunidades", "CodUnidad", "Descripcion", "", 0)?>
			</select>
		</td>
        <td>
        	<input type="text" name="codpartida" id="codpartida" disabled="disabled" style="width:60%;" />
			<input type="hidden" name="nompartida" id="nompartida" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'lista_clasificador_presupuestario.php?cod=codpartida&nom=nompartida&destino=selPartidaCuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" <?=$disabled?> />
        </td>
        <td>
        	<input type="text" name="codcuenta" id="codcuenta" disabled="disabled" style="width:60%;" />
			<input type="hidden" name="nomcuenta" id="nomcuenta" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" disabled="disabled" />
        </td>
        <td>
        	<input type="text" name="codactivo" id="codactivo" disabled="disabled" style="width:60%;" />
			<input type="hidden" name="nomactivo" id="nomactivo" />
            <? if ($clasificacion_commodity != "ACT" && $clasificacion_commodity != "BME") $disabled_clasificacion = "disabled"; ?>
			<input type="button" id="btActivo" value="..." onclick="cargarVentana(this.form, 'listado_clasificacion_activos.php?cod=codactivo&nom=nomactivo', 'height=500, width=900, left=50, top=50, resizable=yes');" <?=$disabled?> <?=$disabled_clasificacion?> />
        </td>
		<td>
			<select name="estado" id="estado" <?=$disabled?> style="width:99%;">
				<?=loadSelectValores("ESTADO", "A", 0)?>
			</select>
		</td>
	</tr>
</table>

<table width="98%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input name="btNuevo" type="submit" class="btLista" id="btNuevo" value="Insertar" <?=$disabled?> />
			<input name="btLimpiar" type="button" class="btLista" id="btLimpiar" value="Limpiar" <?=$disabled?> onclick="cargarPagina(this.form, 'commodity_sub_clasificacion.php');" /> | 
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" <?=$disabled?> onclick="editarSubCommodity(this.form, 'EDITAR-DET')" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Borrar" <?=$disabled?> onclick="borrarSubCommodity(this.form, 'ELIMINAR-DET')" />
		</td>
	</tr>
</table>

<input type="hidden" name="commoditymast" id="commoditymast" value="<?=$commoditymast?>" />
<input type="hidden" name="clasificacion_commodity" id="clasificacion_commodity" value="<?=$clasificacion_commodity?>" />
<input type="hidden" name="accion" id="accion" value="GUARDAR-DET" />
<input type="hidden" name="registro" id="registro" />
<table width="98%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Sub Clase</th>
		<th scope="col">Descripcion</th>
		<th width="100" scope="col">Partida</th>
		<th width="100" scope="col">Cuenta</th>
		<th width="100" scope="col">Clasificaci&oacute;n</th>
		<th width="75" scope="col">Unidad</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM lg_commoditysub WHERE CommodityMast = '".$commoditymast."' ORDER BY Codigo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo";
		elseif ($field['Estado'] == "I") $status = "Inactivo";
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['Codigo']?>">
			<td align="center"><?=$field['CommoditySub']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['cod_partida']?></td>
			<td align="center"><?=$field['CodCuenta']?></td>
			<td align="center"><?=$field['CodClasificacion']?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align='center'><?=$status?></td>
		</tr>
		<?
	}
	?>
</table>
</form>

<script type="text/javascript" language="javascript">
	numRegistros(<?=$rows?>);
</script>
</body>
</html>