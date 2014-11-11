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
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<?php
include("fphp_sia.php");
connect();
//	--------------------
$sql = "SELECT * FROM ac_mastplancuenta WHERE CodCuenta = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plan de Cuentas | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'plan_cuentas.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="plan_cuentas.php" method="POST" onsubmit="return verificarPlanCuentas(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />

<table width="1000" align="center">
  <tr>
   	<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos de la Cuenta</a></li>
			<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Partidas Presupuestarias</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:1000px" class="divFormCaption">Datos del Plan de Cuenta</div>
<table width="1000" class="tblForm">
	<tr>
		<td class="tagForm">Nivel:</td>
		<td>
			<select name="nivel" id="nivel" style="width:200px;">
				<?=cargarSelect("NIVELES-CUENTAS", $field['Nivel'], 1);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta:</td>
		<td><input name="codigo" type="text" id="codigo" style="width:200px;" maxlength="12" value="<?=$field['CodCuenta']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="100" maxlength="255" value="<?=htmlentities($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Cuenta:</td>
		<td>
			<select name="tipo_cuenta" id="tipo_cuenta" style="width:200px;">
				<?=getMiscelaneos($field['TipoCuenta'], "CUENTAS", 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Naturaleza:</td>
		<td>
			<? if ($field['TipoSaldo'] == "D") $flagdeudora = "checked"; else $flagacreedora = "checked"; ?>
			<input id="deudora" name="naturaleza" type="radio" value="D" <?=$flagdeudora?> /> Deudora
			<input id="acreedora" name="naturaleza" type="radio" value="A" <?=$flagacreedora?> /> Acreedora
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nivel de Cuenta:</td>
		<td>
			<? if ($field['FlagTipo'] == "P") $flagprincipal = "checked"; else $flagauxiliar = "checked"; ?>
			<input id="principal" name="tipo" type="radio" value="P" <?=$flagprincipal?> /> Principal
			<input id="auxiliar" name="tipo" type="radio" value="A" <?=$flagauxiliar?> /> Auxiliar
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'plan_cuentas.php');" />
</center><br /><div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</div>
</form>

<div id="tab2" style="display:none;">
<div style="width:1000px" class="divFormCaption">Partidas Presupuestarias</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top"><iframe class="frameTab" style="height:500px; width:998px;" src="plan_cuentas_partidas.php?idcuenta=<?=$registro?>"></iframe></td>
    </tr>
</table>
</div>


</body>
</html>
