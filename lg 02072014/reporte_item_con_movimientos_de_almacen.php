<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
//	------------------------------------
$fdesde = date("Y-m-d");
$fhasta = date("Y-m-d");
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
		<td class="titulo">Ítems con Movimientos en el Almacen</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_item_con_movimientos_de_almacen_pdf.php" target="iReporte" onsubmit="return validar_reporte_movimientos_de_almacen_pdf();">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
		<td width="125" align="right">Almacen:</td>
		<td>
        	<input type="hidden" name="falmacen" id="falmacen" value="ALCEDA" />
			<input type="checkbox" name="chkalmacen" id="chkalmacen" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select  id="" style="width:200px;" disabled="disabled">
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", "", 0)?>
			</select>
		</td>
        <td width="125" align="right" style="display:none">Linea:</td>
        <td style="display:none">
			<input type="checkbox" name="chklinea" id="chklinea" value="1" onclick="chkFiltroLinea(this.checked);" />
            <input name="fcodlinea" type="text" id="fcodlinea" size="15" readonly="readonly" />
            <input name="fnomlinea" type="hidden" id="fnomlinea" />
            <input type="button" id="btLinea" value="..." onclick="window.open('lista_subfamilias.php?limit=0&campo1=flinea&campo2=ffamilia&campo3=fsubfamilia', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>		
	</tr>
	<tr>
		<td align="right" style="display:">Periodo:</td>
		<td style="display:">
			<!--<input type="checkbox" id="chkfecha" name="chkfecha" value="1" onclick="forzarCheck(this.id);" checked="checked" />-->
			<input type="text" name="fdesde" id="fdesde" size="12" maxlength="7" value="" /> 
			- 
			<input type="text" name="fhasta" id="fhasta" size="12" maxlength="7" value="" /> 
			(aaaa-mm) 
		</td>
        
        <td align="right" style="display:none">Familia:</td>
        <td style="display:none">
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodfamilia" type="text" id="fcodfamilia" value="<?=$fcodfamilia?>" size="15" readonly="readonly" />
            <input name="fnomfamilia" type="hidden" id="fnomfamilia" />
        </td>
	</tr>
	<tr>
		<td align="right" style="display:none">Buscar:</td>
		<td style="display:none">
			<input type="checkbox" name="chkbuscar" value="1" onclick="chkFiltro(this.checked, 'fbuscar');" />
			<input type="text" name="fbuscar" id="fbuscar" size="50" disabled="disabled" />
		</td>
        <td align="right" style="display:none">Sub-Familia:</td>
        <td style="display:none">
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodsubfamilia" type="text" id="fcodsubfamilia" value="<?=$fcodsubfamilia?>" size="15" readonly="readonly" />
            <input name="fnomsubfamilia" type="hidden" id="fnomsubfamilia" />
        </td>
	</tr>
    <tr>
        <td align="right" style="display:none">Item:</td>
        <td style="display:none">
			<input type="checkbox" name="chkitem" id="chkitem" value="1" onclick="chkFiltroItem(this.checked);" disabled="disabled" />
            <input name="fcoditem" type="text" id="fcoditem" size="15" readonly="readonly" />
            <input name="fnomitem" type="hidden" id="fnomitem" />
            <input type="button" id="btItem" value="..." onclick="window.open('listado_items.php?limit=0&cod=fcoditem&nom=fnomitem', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=900, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
		<td align="right" style="display:none">Ordenar por:</td>
		<td style="display:none">
			<input type="checkbox" name="chkorden" id="chkorden" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="forden" id="forden" style="width:100px;">
				<?=loadSelectValores("ORDENAR-MOVIMIENTOS-ALMACEN", "i.CodItem", 0)?>
			</select>
		</td>
	</tr>

</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar" /></center>
</form> 

<br /><div class="divDivision"></div><br />

<!--<table width="1000" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs --/>
			<li><a onclick="tab_reporte_movimientos_de_almacen('general');" href="#">General</a></li>
			<li><a onclick="tab_reporte_movimientos_de_almacen('detallado');" href="#">Detallado</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table> -->

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:650px;"></iframe>
</center>


</body>
</html>