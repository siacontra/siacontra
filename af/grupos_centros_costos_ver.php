<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<?php
include("fphp_sia.php");
connect();
//	--------------------
$sql = "SELECT * FROM ac_grupocentrocosto WHERE CodGrupoCentroCosto = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Grupos de Centros de Costos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:550px" class="divFormCaption">Datos del Grupo de Centro de Costo</div>
<table width="550" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="4" value="<?=$field['CodGrupoCentroCosto']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="50" value="<?=htmlentities($field['Descripcion'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
	</td>
	</tr>
</table>

<br /><div class="divDivision">Sub-Grupos de Centros de Costos</div><br />

<table width="550" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Sub-Grupo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	
	<?
	$sql = "SELECT * FROM ac_subgrupocentrocosto WHERE CodGrupoCentroCosto = '".$registro."'";
	$query_subgrupo = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_subgrupo = mysql_fetch_array($query_subgrupo)) {
		if ($field_subgrupo['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field_subgrupo['CodSubGrupoCentroCosto']?></td>
			<td><?=htmlentities($field_subgrupo['Descripcion'])?></td>
			<td align="center"><?=htmlentities($status)?></td>
		</tr>
		<?
	}
	?>
</table>

</body>
</html>
