<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
//	------------------------------------
$fdesde = "01-".date("m-Y");
$fhasta = date("d-m-Y");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
<script type="text/javascript" language="javascript">
//	funcion para cambiar de pestaña
function cambiarTab(ficha) {
	var form = document.getElementById("frmentrada");
	document.getElementById("ficha").value = ficha;	
	mostrarReporte(form);
}

function mostrarReporte(form) {
	var ficha = document.getElementById("ficha").value;
	if (ficha == "documento") var action = "reporte_ordenes_servicios_documentos.php";
	else if (ficha == "proveedor") var action = "reporte_ordenes_servicios_proveedores.php";
	form.action = action;
	form.submit();
	return false;
}
</script>
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
		<td class="titulo">Ordenes de Servicios</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" target="iReporte" onsubmit="return mostrarReporte(this);">
<input type="hidden" name="ficha" id="ficha" value="documento" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td width="125" align="right">C.Costo:</td>
		<td>
			<input type="checkbox" name="chkccosto" id="chkccosto" value="1" onclick="chkFiltroLista(this.checked, 'fcodccosto', 'fnomccosto', 'btCCosto');" />
            <input name="fcodccosto" type="text" id="fcodccosto" size="10" disabled="disabled" />
            <input name="fnomccosto" type="hidden" id="fnomccosto" />
            <input type="button" id="btCCosto" value="..." onclick="cargarVentana(this.form, 'listado_centro_costos.php?limit=0&cod=fcodccosto&nom=fnomccosto&ventana=&tabla=', 'height=700, width=850, left=50, top=50, resizable=yes');" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">Proveedor:</td>
		<td>
			<input type="checkbox" name="chkproveedor" id="chkproveedor" value="1" onclick="enabledProveedor(this.form);" />
			<input type="hidden" name="fproveedor" id="fproveedor" />
			<input type="text" name="fnomproveedor" id="fnomproveedor" size="60" readonly="readonly" />
			<input type="button" value="..." id="btProveedor" onclick="cargarVentana(this.form, 'listado_personas.php?limit=0&cod=fproveedor&nom=fnomproveedor&flagproveedor=S', 'height=600, width=1100, left=50, top=50, resizable=yes');" disabled="disabled" />
		</td>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" name="chkedoreg" id="chkedoreg" value="1" onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:200px;" disabled="disabled">
                <option value=""></option>
                <?=loadSelectValores("ESTADO-ORDENES", "", 0)?>
            </select>
        </td>
	</tr>
	<tr>
		<td align="right">F. Preparaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkfpreparacion" value="1" checked="checked" onclick="chkFiltro_2(this.checked, 'fpreparaciond', 'fpreparacionh');" />
			<input type="text" name="fpreparaciond" id="fpreparaciond" maxlength="10" size="15" value="<?=$fdesde?>" /> - 
			<input type="text" name="fpreparacionh" id="fpreparacionh" maxlength="10" size="15" value="<?=$fhasta?>" />
		</td>
		<td align="right">Monto:</td>
		<td>
			<input type="checkbox" name="chkfmonto" value="1" onclick="chkFiltro_2(this.checked, 'fmontod', 'fmontoh');" />
			<input type="text" name="fmontod" id="fmontod" maxlength="10" size="15" disabled="disabled" /> - 
			<input type="text" name="fmontoh" id="fmontoh" maxlength="10" size="15" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">F. Aprobaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkfaprobacion" value="1" onclick="chkFiltro_2(this.checked, 'faprobaciond', 'faprobacionh');" />
			<input type="text" name="faprobaciond" id="faprobaciond" maxlength="10" size="15" /> - 
			<input type="text" name="faprobacionh" id="faprobacionh" maxlength="10" size="15" />
		</td>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
    <tr><td colspan="4"><hr style="width:90%; color:#CCC;" /></td></tr>
    <tr>
		<td align="right">&nbsp;</td>
		<td><input type="checkbox" name="chkverdet" id="chkverdet" value="S" /> Ver Detalle</td>
        <td align="right">Dias Atraso:</td>
        <td>
            <input type="checkbox" name="chkatraso" id="chkatraso" value="1" disabled="disabled" />
            <input type="text" name="fatraso" id="fatraso" style="width:50px;" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td align="right">Estado Detalle:</td>
		<td>
            <input type="checkbox" name="chkedodet" id="chkedodet" value="1" onclick="chkFiltro(this.checked, 'fedodet');" />
            <select name="fedodet" id="fedodet" style="width:200px;" disabled="disabled">
                <option value=""></option>
                <?=loadSelectValores("ESTADO-ORDENES-DET", "", 0)?>
            </select>
        </td>
		<td align="right">Commodity:</td>
		<td>
			<input type="checkbox" name="chkcommodity" id="chkcommodity" value="1" onclick="chkFiltroLista(this.checked, 'fcodcommodity', 'fnomcommodity', 'btCommodity');" />
            <input name="fcodcommodity" type="text" id="fcodcommodity" size="15" disabled="disabled" />
            <input name="fnomcommodity" type="hidden" id="fnomcommodity" />
            <input type="button" id="btCommodity" value="..." onclick="cargarVentana(this.form, 'listado_commodities.php?limit=0&cod=fcodcommodity&nom=fnomcommodity&ventana=&tabla=', 'height=700, width=800, left=50, top=50, resizable=yes');" disabled="disabled" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Ordenes de Servicios</div><br />

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="cambiarTab('documento');" href="#">Por Documento</a></li>
            <li><a onclick="cambiarTab('proveedor');" href="#">Por Proveedor</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>