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
		<td class="titulo">Listado de Commodities</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_listado_commodities_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
		<td width="125" align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkclasificacion" value="1" onclick="chkFiltro(this.checked, 'fclasificacion');" />
			<select name="fclasificacion" id="fclasificacion" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelect("lg_commodityclasificacion", "Clasificacion", "Descripcion", $fclasificacion, 0)?>
			</select>
		</td>
		<td width="125" align="right">Commodity:</td>
		<td>
			<input type="checkbox" name="chkcommodity" value="1" onclick="chkFiltro(this.checked, 'fcommodity');" />
			<select name="fcommodity" id="fcommodity" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelect("lg_commoditymast", "CommodityMast", "Descripcion", $fcommodity, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form>
<br /><div class="divDivision">Listado de Commodities</div><br />
<center><iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:650px;"></iframe></center>
</body>
</html>