<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
extract($_POST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Detalle de Requerimiento</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<!--<form name="frmentrada" id="frmentrada" method="post" action="reporte_detalle_requerimiento_pdf.php" target="iReporte" onsubmit="return reporte_detalle_requerimiento(this);">-->
<form name="frmentrada" id="frmentrada" method="post" action="reporte_detalle_requerimiento_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="this.checked=!this.checked" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig" onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($_SESSION["ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td width="125" align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkclasificacion" value="1" onclick="chkFiltro(this.checked, 'fclasificacion');" />
			<select name="fclasificacion" id="fclasificacion" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelect("lg_clasificacion", "Clasificacion", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" onclick="enabledDependencia(this.form);" />
			<select name="fdependencia" id="fdependencia" class="selectBig" disabled="disabled">
				<option value=''></option>
				<?=getDependencias("", $_SESSION["ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td align="right">Centro de Costo:</td>
		<td>
			<!--<input type="checkbox" name="chkccosto" id="chkccosto" value="1" onclick="enabledCCosto(this.form);" />-->
			<input type="checkbox" name="chkccosto" id="chkccosto" value="1" onclick="enabledCCosto(this.form);"/>
			<input type="text" name="fccosto" id="fccosto" size="15" readonly="readonly" />

			<input type="hidden" name="nomccosto" id="nomccosto" />
			<input type="button" value="..." id="btCCosto" onclick="cargarVentana(this.form,'listado_centro_costos.php?cod=fccosto&nom=nomccosto', 'height=600, width=1100, left=50, top=50, resizable=yes');" disabled="disabled" />
            

		</td>
	</tr>
	<tr>
		<td align="right">Estado:</td>
		<td>
			<input type="checkbox" name="chkedoreg" value="1" onclick="chkFiltro(this.checked, 'fedoreg');" />
			<select name="fedoreg" id="fedoreg" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelectValores("ESTADO-REQUERIMIENTO-DET", "", 0)?>
			</select>
		</td>
		<td align="right" rowspan="2" valign="top">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-REQUERIMIENTOS-DET", "", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dirigido a:</td>
		<td>
			<input type="checkbox" name="chkdirigido" value="1" onclick="chkFiltro(this.checked, 'fdirigido')" />
			<select name="fdirigido" id="fdirigido" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelectValores("DIRIGIDO", "", 0)?>
			</select>
		</td>
		<td><input type="text" name="fbuscar" size="50" disabled="disabled" /></td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form>
<br /><div class="divDivision">Detalle de Requerimiento</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>