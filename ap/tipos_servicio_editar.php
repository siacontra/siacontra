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

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_sia.php");
connect();
//	--------------------
$sql = "SELECT * FROM masttiposervicio WHERE CodTipoServicio = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Servicio | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipos_servicio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_servicio.php" method="POST" onsubmit="return verificarTipoServicio(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Tipo de Servicio</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="5" value="<?=$field['CodTipoServicio']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="25" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">R&eacute;gimen Fiscal:</td>
		<td colspan="3">
			<select name="regimen" id="regimen" style="width:200px;">
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field['CodRegimenFiscal'], 0)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
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
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipos_servicio.php');" />
</center>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>

<br /><div class="divDivision">Impuestos aplicables a este servicio</div><br />

<form name="frmelementos" id="frmelementos" action="tipos_servicio_editar.php" method="post" onsubmit="return verificarTipoServicioImpuesto(this, document.getElementById('accion').value);">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="accion" id="accion" value="INSERTAR" />
<input type="hidden" name="elemento" id="elemento" />
<table width="500" class="tblForm">
	<tr>
		<td width="100" align="right">Impuesto: </td>
		<td>
			<select name="impuesto" id="impuesto" style="width:250px;">
				<option value=""></option>
				<?=loadSelect("mastimpuestos", "CodImpuesto", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
</table>

<table width="500" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" /> | 
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" onclick="borrarTipoServicioImpuesto(this.form, document.getElementById('elemento').value);" />
	</td>
 </tr>
</table>

<table width="500" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
	
	<?
	$sql = "SELECT 
				tsi.*,
				mi.Descripcion AS NomImpuesto
			FROM 
				masttiposervicioimpuesto  tsi
				INNER JOIN mastimpuestos mi ON (tsi.CodImpuesto = mi.CodImpuesto)
			WHERE tsi.CodTipoServicio = '".$registro."'";
	$query_subgrupo = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_subgrupo = mysql_fetch_array($query_subgrupo)) {
		if ($field_subgrupo['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'elemento');" id="<?=$field_subgrupo['CodImpuesto']?>">
			<td align="center"><?=$field_subgrupo['CodImpuesto']?></td>
			<td><?=htmlentities($field_subgrupo['NomImpuesto'])?></td>
		</tr>
		<?
	}
	?>
</table>
</form>

</body>
</html>
