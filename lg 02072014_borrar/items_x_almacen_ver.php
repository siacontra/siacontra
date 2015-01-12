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
//	-------------------
list($codalmacen, $coditem)=SPLIT( '[-]', $registro);
$sql = "SELECT
			ia.*,
			a.Descripcion AS NomAlmacen,
			i.Descripcion AS NomItem,
			i.CodUnidad
		FROM
			lg_itemalmacen ia
			INNER JOIN lg_almacenmast a ON (ia.CodAlmacen = a.CodAlmacen)
			INNER JOIN lg_itemmast i ON (ia.CodItem = i.CodItem)
		WHERE
			ia.CodAlmacen = '".$codalmacen."' AND
			ia.CodItem = '".$coditem."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Items x Almacen | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

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
        	<input type="hidden" id="codalmacen" value="<?=$field['CodAlmacen']?>" />
        	<input type="text" id="nomalmacen" size="45" value="<?=($field['NomAlmacen'])?>" disabled="disabled" />*
		</td>
	</tr>
</table>    
<div style="width:700px;" class="divFormCaption">Datos del Item</div>
<table width="700px" class="tblForm">
	<tr>
        <td class="tagForm" width="150">Item:</td>
        <td>
            <input name="coditem" type="text" id="coditem" size="15" value="<?=$field['CodItem']?>" disabled="disabled" />
            <input name="nomitem" type="text" id="nomitem" style="width:300px;" value="<?=($field['NomItem'])?>" disabled="disabled" />*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Unidad:</td>
		<td><input name="codunidad" type="text" id="codunidad" size="15" value="<?=$field['CodUnidad']?>" disabled="disabled" />*</td>
	</tr>
</table>
<table width="700px" class="tblForm">
	<tr>
    	<td colspan="2"><div style="width:100%;" class="divFormCaption">Datos Referentes a Stock</div></td>
    	<td colspan="2"><div style="width:100%;" class="divFormCaption">Ubicaci&oacute;n F&iacute;sica</div></td>
	</tr>
	<tr>
		<td class="tagForm" width="100">Stock M&iacute;nimo:</td>
		<td><input name="stockmin" type="text" id="stockmin" size="15" value="<?=number_format($field['StockMin'], 2, ',', '.')?>" style="text-align:right;" disabled="disabled" /></td>
		<td class="tagForm" width="100">Principal:</td>
		<td><input name="ubicacion1" type="text" id="ubicacion1" size="45" value="<?=($field['Ubicacion1'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Stock M&aacute;ximo:</td>
		<td><input name="stockmax" type="text" id="stockmax" size="15" value="<?=number_format($field['StockMax'], 2, ',', '.')?>" style="text-align:right;" disabled="disabled" /></td>
		<td class="tagForm">2da. Ubicaci&oacute;n:</td>
		<td><input name="ubicacion2" type="text" id="ubicacion2" size="45" value="<?=($field['Ubicacion2'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Punto Reorden:</td>
		<td><input name="reorden" type="text" id="reorden" size="15" value="<?=number_format($field['StockReorden'], 2, ',', '.')?>" style="text-align:right;" disabled="disabled" /></td>
		<td class="tagForm">3ra. Ubicaci&oacute;n:</td>
		<td><input name="ubicacion3" type="text" id="ubicacion3" size="45" value="<?=($field['Ubicacion3'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Tiempo de Espera:</td>
		<td colspan="3"><input name="espera" type="text" id="espera" size="5" value="<?=$field['TiempoEspera']?>" style="text-align:right;" disabled="disabled" /> <i>(en dias)</i></td>
	</tr>
	<tr>
		<td class="tagForm" width="150">Estado:</td>
		<td colspan="3">
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
</div>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:700px;" class="divFormCaption">Lote</div>
</div>

</body>
</html>
