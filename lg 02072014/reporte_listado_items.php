<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Item</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_listado_items_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" onclick="chkFiltro(this.checked, 'fbuscar');" />
			<input type="text" name="fbuscar" id="fbuscar" size="50" disabled="disabled" />
		</td>
        <td align="right" width="125">Linea:</td>
        <td>
			<input type="checkbox" name="chklinea" id="chklinea" value="1" onclick="chkFiltroLinea(this.checked);" />
            <input name="fcodlinea" type="text" id="fcodlinea" size="15" readonly="readonly" />
            <input name="fnomlinea" type="hidden" id="fnomlinea" />
            <input type="button" id="btLinea" value="..." onclick="window.open('lista_subfamilias.php?limit=0&campo1=flinea&campo2=ffamilia&campo3=fsubfamilia', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td align="right">Ordenar por:</td>
		<td>
			<input type="checkbox" name="chkorden" id="chkorden" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="forden" id="forden" style="width:100px;">
				<?=loadSelectValores("ORDENAR-LISTADO-ITEMS", "i.CodItem", 0)?>
			</select>
		</td>
        <td align="right">Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodfamilia" type="text" id="fcodfamilia" value="<?=$fcodfamilia?>" size="15" readonly="readonly" />
            <input name="fnomfamilia" type="hidden" id="fnomfamilia" />
        </td>
	</tr>
	<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Sub-Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodsubfamilia" type="text" id="fcodsubfamilia" value="<?=$fcodsubfamilia?>" size="15" readonly="readonly" />
            <input name="fnomsubfamilia" type="hidden" id="fnomsubfamilia" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form>
<br /><div class="divDivision">Listado de Items</div><br />
<center><iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:650px;"></iframe></center>
</body>
</html>