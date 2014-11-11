<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
//	------------------------------------
$fdesde = date("Y-m");
$fhasta = date("Y-m");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Stock</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_listado_stock_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
    	<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", "", 0)?>
			</select>
		</td>
		<td width="125" align="right">Almacen:</td>
		<td>
			<input type="checkbox" name="chkalmacen" id="chkalmacen" value="1" onclick="chkFiltro(this.checked, 'falmacen');" />
			<select name="falmacen" id="falmacen" style="width:150px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
    <tr>
        <td align="right">Item:</td>
        <td>
			<input type="checkbox" name="chkitem" id="chkitem" value="1" onclick="chkFiltroItem(this.checked);" />
            <input name="fcoditem" type="text" id="fcoditem" size="15" readonly="readonly" />
            <input name="fnomitem" type="hidden" id="fnomitem" />
            <input type="button" id="btItem" value="..." onclick="window.open('listado_items.php?limit=0&cod=fcoditem&nom=fnomitem', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=900, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
		<td align="right">Ordenar por:</td>
		<td>
			<input type="checkbox" name="chkorden" id="chkorden" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="forden" id="forden" style="width:150px;">
				<?=loadSelectValores("ORDENAR-MOVIMIENTOS-ALMACEN", "i.CodItem", 0)?>
			</select>
		</td>
	</tr>
    <tr>
        <td align="right">Tipo Item:</td>
        <td>
			<input type="checkbox" name="chktipoitem" id="chktipoitem" value="1" onclick="chkFiltro(this.checked, 'ftipoitem');" />
			<select name="ftipoitem" id="ftipoitem" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("lg_tipoitem", "CodTipoItem", "Descripcion", "", 0)?>
			</select>
		</td>
		<td align="right">Cantidad:</td>
		<td>
			<input type="checkbox" name="chkcantidad" id="chkcantidad" value="1" onclick="chkFiltro(this.checked, 'fcantidad');" />
            <input type="text" name="fcantidad" id="fcantidad" style="width:50px;" maxlength="5" disabled="disabled" />
		</td>
	</tr>

</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 
<br /><div class="divDivision">Listado de Stock</div><br />
<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:650px;"></iframe>
</center>


</body>
</html>