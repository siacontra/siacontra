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

<script language="javascript" >
	function llamadoTabReporte(tab) {
		
		var form = document.getElementById("frmentrada");
		if (tab == "general") form.action = "reporte_compromiso_ordenes_compras_pdf.php";
		else form.action = "reporte_compromiso_ordenes_compras_detalle_pdf.php";	
		form.submit();
	}
</script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ordenes de Compras Distribuci&oacute;n/Comprometidas</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_compromiso_ordenes_compras_pdf.php" target="iReporte">
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
		<td width="125" align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkclasificacion" value="1" onclick="chkFiltro(this.checked, 'fclasificacion');" />
			<select name="fclasificacion" id="fclasificacion" style="width:200px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelectValores("ORDEN-CLASIFICACION", "", 0)?>
			</select>
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
                <option value="RV">Revisado</option>
                <option value="AP">Aprobado</option>
                <option value="CO">Completado</option>
                <!-- <?=loadSelectValores("ESTADO-ORDENES", "", 0)?> -->
            </select>
        </td>
	</tr>
	<tr>
		<td align="right">F. Aprobaci&oacute;n:/Periodo(Detallado)</td>
		<td>
			<input type="checkbox" name="chkfpreparacion" value="1" checked="checked" onclick="chkFiltro_2(this.checked, 'fpreparaciond', 'fpreparacionh');" />
			<input type="text" name="fpreparaciond" id="fpreparaciond" title="Introduzca Periodo si desea generar el reporte detallado (a&ntilde;o-mes)" maxlength="10" size="15" value="<?=$fdesde?>" /> - 
			<input type="text" name="fpreparacionh" id="fpreparacionh" title="Introduzca Periodo si desea generar el reporte detallado (a&ntilde;o-mes)" maxlength="10" size="15" value="<?=$fhasta?>" />
		</td>
		<td align="right">Monto:</td>
		<td>
			<input type="checkbox" name="chkfmonto" value="1" onclick="chkFiltro_2(this.checked, 'fmontod', 'fmontoh');" />
			<input type="text" name="fmontod" id="fmontod" maxlength="10" size="15" disabled="disabled" /> - 
			<input type="text" name="fmontoh" id="fmontoh" maxlength="10" size="15" disabled="disabled" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Ordenes de Compras</div><br />

<table width="1000" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onclick="llamadoTabReporte('general');" href="#">Distribuci&oacute;n</a></li>
			<li><a onclick="llamadoTabReporte('detallado');" href="#">Compromisos</a></li>
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