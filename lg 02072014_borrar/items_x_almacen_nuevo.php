<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
<?php
include("fphp_lg.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Items x Almacen | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'items_x_almacen.php?limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="items_x_almacen.php?limit=0" method="POST" onsubmit="return verificarItemAlmacen(this, 'GUARDAR');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="falmacen" id="falmacen" value="<?=$falmacen?>" />
<input type="hidden" name="ftipo" id="ftipo" value="<?=$ftipo?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="sltbuscar" id="sltbuscar" value="<?=$sltbuscar?>" />
<input type="hidden" name="fcodlinea" id="fcodlinea" value="<?=$fcodlinea?>" />
<input type="hidden" name="fcodfamilia" id="fcodfamilia" value="<?=$fcodfamilia?>" />
<input type="hidden" name="fcodsubfamilia" id="fcodsubfamilia" value="<?=$fcodsubfamilia?>" />

<table width="700" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Lote</a></li>	
            </ul>
            </div>
        </td>
    </tr>
</table>

<div name="tab1" id="tab1" style="display:block;">
<div style="width:700px;" class="divFormCaption">Datos Generales</div>
<table width="700px" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Almacen:</td>
		<td colspan="3">
        	<?
			$sql = "SELECT Descripcion FROM lg_almacenmast WHERE CodAlmacen = '".$falmacen."'";
			$query_almacen = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_almacen) != 0) $field_almacen = mysql_fetch_array($query_almacen);
			?>
        	<input type="hidden" id="codalmacen" value="<?=$falmacen?>" />
        	<input type="text" id="nomalmacen" size="45" value="<?=($field_almacen['Descripcion'])?>" disabled="disabled" />*
		</td>
	</tr>
</table>    
<div style="width:700px;" class="divFormCaption">Datos del Item</div>
<table width="700px" class="tblForm">
	<tr>
        <td class="tagForm" width="150">Item:</td>
        <td>
            <input name="coditem" type="text" id="coditem" size="15" disabled="disabled" />
            <input name="nomitem" type="text" id="nomitem" style="width:300px;" disabled="disabled" />
            <input type="button" value="..." onclick="window.open('listado_items.php?limit=0&cod=coditem&nom=nomitem&ventana=item_x_almacen', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=900, width=1050, left=50, top=50, resizable=yes');" />*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Unidad:</td>
		<td><input name="codunidad" type="text" id="codunidad" size="15" disabled="disabled" />*</td>
	</tr>
</table>
<table width="700px" class="tblForm">
	<tr>
    	<td colspan="2"><div style="width:100%;" class="divFormCaption">Datos Referentes a Stock</div></td>
    	<td colspan="2"><div style="width:100%;" class="divFormCaption">Ubicaci&oacute;n F&iacute;sica</div></td>
	</tr>
	<tr>
		<td class="tagForm" width="100">Stock M&iacute;nimo:</td>
		<td><input name="stockmin" type="text" id="stockmin" size="15" style="text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm" width="100">Principal:</td>
		<td><input name="ubicacion1" type="text" id="ubicacion1" size="45" /></td>
	</tr>
	<tr>
		<td class="tagForm">Stock M&aacute;ximo:</td>
		<td><input name="stockmax" type="text" id="stockmax" size="15" style="text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">2da. Ubicaci&oacute;n:</td>
		<td><input name="ubicacion2" type="text" id="ubicacion2" size="45" /></td>
	</tr>
	<tr>
		<td class="tagForm">Punto Reorden:</td>
		<td><input name="reorden" type="text" id="reorden" size="15" style="text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">3ra. Ubicaci&oacute;n:</td>
		<td><input name="ubicacion3" type="text" id="ubicacion3" size="45" /></td>
	</tr>
	<tr>
		<td class="tagForm">Tiempo de Espera:</td>
		<td colspan="3"><input name="espera" type="text" id="espera" size="5" style="text-align:right;" /> <i>(en dias)</i></td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Estado:</td>
		<td colspan="3">
			<input id="activo" name="status" type="radio" value="A" checked /> Activo
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>
</div>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:700px;" class="divFormCaption">Lote</div>
</div>

<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'items_x_almacen.php?limit=0');" />
</center><br />
</form>

<div style="width:700px%" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
