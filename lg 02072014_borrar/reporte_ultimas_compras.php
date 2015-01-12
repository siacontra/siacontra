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
	if (ficha == "stock") {
		var action = "reporte_ultimas_compras_stock.php";
		document.getElementById("tab1").style.display = "block";
		document.getElementById("tab2").style.display = "none";
	}
	else if (ficha == "commodities") {
		var action = "reporte_ultimas_compras_commodities.php";
		document.getElementById("tab1").style.display = "none";
		document.getElementById("tab2").style.display = "block";
	}
	form.action = action;
	form.submit();
	return false;
}
</script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">&Uacute;ltimas Compras Realizadas</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" target="iReporte" onsubmit="return mostrarReporte(this);">
<input type="hidden" name="ficha" id="ficha" value="stock" />
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
		<td align="right">F. Aprobaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkfaprobacion" value="1" checked="checked" onclick="this.checked=!this.checked;" />
			<input type="text" name="faprobaciond" id="faprobaciond" maxlength="10" size="15" value="<?=$fdesde?>" /> - 
			<input type="text" name="faprobacionh" id="faprobacionh" maxlength="10" size="15" value="<?=$fhasta?>" />
		</td>
		<td align="right">&nbsp;</td>
		<td><input type="checkbox" name="chkverdet" id="chkverdet" value="S" /> Ver Detalle</td>
	</tr>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="cambiarTab('stock');" href="#">Stock</a></li>
            <li><a onclick="cambiarTab('commodities');" href="#">Commodities</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<table width="1000" class="tblFiltro">
	<tr style="height:25px;">
        <td align="right">Linea:</td>
        <td>
			<input type="checkbox" name="chklinea" id="chklinea" value="1" onclick="chkFiltroLinea(this.checked);" />
            <input name="fcodlinea" type="text" id="fcodlinea" size="15" readonly="readonly" />
            <input name="fnomlinea" type="hidden" id="fnomlinea" />
            <input type="button" id="btLinea" value="..." onclick="window.open('lista_subfamilias.php?limit=0&campo1=flinea&campo2=ffamilia&campo3=fsubfamilia', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
        <td align="right">Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodfamilia" type="text" id="fcodfamilia" size="15" readonly="readonly" />
            <input name="fnomfamilia" type="hidden" id="fnomfamilia" />
        </td>
        <td align="right">Sub-Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodsubfamilia" type="text" id="fcodsubfamilia" size="15" readonly="readonly" />
            <input name="fnomsubfamilia" type="hidden" id="fnomsubfamilia" />
        </td>
        <td align="right">Item:</td>
        <td>
			<input type="checkbox" name="chkitem" id="chkitem" value="1" onclick="chkFiltroLista(this.checked, 'fcoditem', 'fnomitem', 'btItem');" />
            <input name="fcoditem" type="text" id="fcoditem" size="15" disabled="disabled" />
            <input name="fnomitem" type="hidden" id="fnomitem" />
            <input type="button" id="btItem" value="..." onclick="window.open('listado_items.php?limit=0&cod=fcoditem&nom=fnomitem', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=900, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
	</tr>
</table>
</div>

<div id="tab2" style="display:none;"><table width="1000" class="tblFiltro"><tr style="height:25px;"><td></td></tr></table></div>
</form>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>