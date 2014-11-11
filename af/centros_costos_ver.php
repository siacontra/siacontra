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
//	----------------------
$sql = "SELECT
			amcc.*,
			agcc.Descripcion AS NomGrupoCentroCosto,
			asgcc.Descripcion AS NomSubGrupoCentroCosto,
			me.CodEmpleado,
			mp.Busqueda
		FROM
			ac_mastcentrocosto amcc
			INNER JOIN mastpersonas mp ON (amcc.CodPersona = mp.CodPersona)
			INNER JOIN mastempleado me ON (amcc.CodPersona = me.CodPersona)
			INNER JOIN ac_grupocentrocosto agcc ON (amcc.CodGrupoCentroCosto = agcc.CodGrupoCentroCosto)
			INNER JOIN ac_subgrupocentrocosto asgcc ON (amcc.CodSubGrupoCentroCosto = asgcc.CodSubGrupoCentroCosto)
		WHERE
			amcc.CodCentroCosto = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Centros de Costos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px" class="divFormCaption">Centro de Costo</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Centro de Costo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="4" value="<?=$field['CodCentroCosto']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:80%;" maxlength="50" value="<?=htmlentities($field['Descripcion'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Dependencia:</td>
		<td>
			<select name="dependencia" id="dependencia" style="width:80%;" disabled="disabled">
				<?=getDependencias($field['CodDependencia'], $_SESSION["FILTRO_ORGANISMO_ACTUAL"], 0);?>
			</select>
		</td>
	</tr>
	<tr>
        <td class="tagForm">Empleado:</td>
        <td>
            <input name="codempleado" type="text" id="codempleado" size="10" disabled="disabled" value="<?=$field['CodEmpleado']?>" />
            <input name="nomempleado" type="text" id="nomempleado" size="75" disabled="disabled" value="<?=$field['Busqueda']?>" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=700, width=800, left=350, top=350, resizable=yes');" />
        </td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de C.Costos:</td>
		<td>
			<select name="tipo_ccosto" id="tipo_ccosto" style="width:150px;" disabled="disabled">
				<?=cargarSelect("TIPO-CENTRO-COSTO", $field['TipoCentroCosto'], 0);?>
			</select>
		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption">Informaci&oacute;n Presupuestal</div>
<table width="700" class="tblForm">
	
	<tr>
        <td class="tagForm">Grupo C.C:</td>
        <td>
            <input name="codgrupo_cc" type="text" id="codgrupo_cc" size="10" disabled="disabled" value="<?=$field['CodGrupoCentroCosto']?>" />
            <input name="nomgrupo_cc" type="text" id="nomgrupo_cc" size="75" disabled="disabled" value="<?=htmlentities($field['NomGrupoCentroCosto'])?>" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'lista_grupos_centros_costos.php', 'height=700, width=800, left=350, top=350, resizable=yes');" />
        </td>
    </tr>
	<tr>
        <td class="tagForm">Sub-Grupo C.C:</td>
        <td>
            <input name="codsubgrupo_cc" type="text" id="codsubgrupo_cc" size="10" value="<?=$field['CodSubGrupoCentroCosto']?>" disabled="disabled" />
            <input name="nomsubgrupo_cc" type="text" id="nomsubgrupo_cc" size="75" value="<?=$field['NomSubGrupoCentroCosto']?>" disabled="disabled" />
        </td>
    </tr>
</table>

<div style="width:700px" class="divFormCaption">Tipo de Costo</div>
<table width="700" class="tblForm">
	<tr>
		<td width="150"></td>
		<td>
			<?
			if ($field['FlagAdministrativo'] == "S") $flagadministrativo = "checked";
			if ($field['FlagVentas'] == "S") $flagventas = "checked";
			if ($field['FlagFinanciero'] == "S") $flagfinanciero = "checked";
			if ($field['FlagProduccion'] == "S") $flagproduccion = "checked";
			if ($field['FlagCentroIngreso'] == "S") $flagcentroingreso = "checked";
			?>
			<input type="checkbox" name="tipo_costo" id="flagadministrativo" <?=$flagadministrativo?> disabled="disabled" /> Administrativo <br />
			<input type="checkbox" name="tipo_costo" id="flagventas" <?=$flagventas?> disabled="disabled" /> Ventas <br />
			<input type="checkbox" name="tipo_costo" id="flagfinanciero" <?=$flagfinanciero?> disabled="disabled" /> Financiero <br />
			<input type="checkbox" name="tipo_costo" id="flagproduccion" <?=$flagproduccion?> disabled="disabled" /> Producci&oacute;n <br />
			<input type="checkbox" name="tipo_costo" id="flagcentroingreso" <?=$flagcentroingreso?> disabled="disabled" /> Centro de Ingresos

		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption">Auditoria</div>
<table width="700" class="tblForm">
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
</body>
</html>
