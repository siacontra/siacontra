<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pv.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_pv.php");
connect();
//	---------------------------
$sql = "SELECT
			p.*,
			c.Descripcion AS NomCuenta
		FROM
			pv_partida p
			LEFT JOIN ac_mastplancuenta c ON (p.CodCuenta = c.CodCuenta)
		WHERE
			p.cod_partida = '".$registro."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
$codigo = $field['cod_tipocuenta'].$field['partida1']."-".$field['generica']."-".$field['especifica']."-".$field['subespecifica'];
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificador Presupuestario | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'pv_clasificador_presupuestario.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="pv_clasificador_presupuestario.php" method="POST" onsubmit="return verificarClasificadorPresupuestario(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
<div style="width:900px" class="divFormCaption">Datos de la Partida</div>
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm">Tipo de Cuenta:</td>
		<td>
        	<select name="cuenta" id="cuenta" disabled="disabled">
            	<?=loadSelect("pv_tipocuenta", "cod_tipocuenta", "descp_tipocuenta", $field['cod_tipocuenta'], 10)?>
            </select>*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Partida:</td>
		<td><input name="par" type="text" id="par" size="2" maxlength="2" value="<?=$field['partida1']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Generica:</td>
		<td><input name="gen" type="text" id="gen" size="2" maxlength="2" value="<?=$field['generica']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Especifica:</td>
		<td><input name="esp" type="text" id="esp" size="2" maxlength="2" value="<?=$field['especifica']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Sub-Especifica:</td>
		<td><input name="subesp" type="text" id="subesp" size="2" maxlength="2" value="<?=$field['subespecifica']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="125" maxlength="300" value="<?=htmlentities($field['denominacion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo:</td>
		<td>
        	<? if ($field['tipo'] == "T") $flagtitulo = "checked"; else $flagdetalle = "checked"; ?>
			<input id="titulo" name="tipo" type="radio" value="T" <?=$flagtitulo?> /> Titulo
			<input id="detalle" name="tipo" type="radio" value="D" <?=$flagdetalle?> /> Detalle
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" value="<?=$field['CodCuenta']?>" />
			<input type="text" name="nomcuenta" id="nomcuenta" size="75" value="<?=$field['NomCuenta']?>" disabled="disabled" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['tipo'] == "T") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'pv_clasificador_presupuestario.php');" />
</center><br />
</form>

<div style="width:900px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
