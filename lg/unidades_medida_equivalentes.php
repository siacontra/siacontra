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
<form name="frmentrada" id="frmentrada" action="unidades_medida_equivalentes.php" method="POST" onsubmit="return verificarUnidadesEquivalentes(this, document.getElementById('accion').value);">
<table align="center" width="500" class="tblForm">
	<tr>
		<td>Unidad:</td>
		<td>Cantidad:</td>
		<td>Estado:</td>
	</tr>
	<tr>
		<td>
			<select name="equivalente" id="equivalente" style="width:250px;" <?=$disabled?>>
				<option value=""></option>
				<?=loadSelect("mastunidades", "CodUnidad", "Descripcion", "", 0)?>
			</select>
		</td>
		<td><input type="text" name="cantidad" id="cantidad" <?=$disabled?> /></td>
		<td>
			<select name="estado" id="estado" <?=$disabled?>>
				<option value=""></option>
				<?=loadSelectValores("ESTADO", "", 0)?>
			</select>
		</td>
	</tr>
</table>


<table width="500" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input name="btNuevo" type="submit" class="btLista" id="btNuevo" value="Insertar" <?=$disabled?> />
			<input name="btLimpiar" type="button" class="btLista" id="btLimpiar" value="Limpiar" <?=$disabled?> onclick="cargarPagina(this.form, 'unidades_medida_equivalentes.php');" /> | 
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" <?=$disabled?> onclick="editarUnidadesEquivalentes(this.form, 'EDITAR-DET')" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Borrar" <?=$disabled?> onclick="borrarUnidadesEquivalentes(this.form, 'ELIMINAR-DET')" />
		</td>
	</tr>
</table>

<input type="hidden" name="codunidad" id="codunidad" value="<?=$codunidad?>" />
<input type="hidden" name="accion" id="accion" value="GUARDAR-DET" />
<input type="hidden" name="registro" id="registro" />
<table width="500" class="tblLista">
	<tr class="trListaHead">
		<th scope="col">Unidad</th>
		<th width="150" scope="col">Cantidad</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				muc.*, 
				mu.Descripcion AS NomEquivalente
			FROM 
				mastunidadesconv muc 
				INNER JOIN mastunidades mu ON (muc.CodEquivalente = mu.CodUnidad)
			WHERE 
				muc.CodUnidad = '".$codunidad."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo";
		elseif ($field['Estado'] == "I") $status = "Inactivo";
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodEquivalente']?>">
			<td><?=($field['NomEquivalente'])?></td>
			<td align='right'><?=number_format($field['Cantidad'], 2, ',', '.')?></td>
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