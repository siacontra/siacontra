<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_pf.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pf.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte de Actuación Fiscal</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="pf_pdf_actuacion_fiscal.php" onsubmit="return pdf_actuacion_fiscal(this, '<?=$pdf?>');">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="forzarCheck(this.id)" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($_SESSION["CODORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td width="125" align="right">F. Registro:</td>
		<td>
			<input type="checkbox" name="chkfregistro" value="1" onclick="chkFiltro_2(this.checked, 'fregistrod', 'fregistroh');" />
			<input type="text" name="fregistrod" id="fregistrod" maxlength="10" size="15" disabled="disabled" /> - 
			<input type="text" name="fregistroh" id="fregistroh" maxlength="10" size="15" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right" valign="top" rowspan="2">Ente:</td>
		<td rowspan="2" valign="top">
			<input type="checkbox" name="chkorganismoext" id="chkorganismoext" value="1" onclick="chkFiltro_2(this.checked, 'forganismoext', 'fdependenciaext');" />
			<select name="forganismoext" id="forganismoext" style="width:300px;" onchange="getOptions_2(this.id, 'fdependenciaext');" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("pf_organismosexternos", "CodOrganismo", "Organismo", "", 0);?>
			</select><br />
            <input type="checkbox" style="visibility:hidden" />
            <select name="fdependenciaext" id="fdependenciaext" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelectDependiente("pf_dependenciasexternas", "CodDependencia", "Dependencia", "CodOrganismo", "", "", 0);?>
            </select>
		</td>
		<td align="right" valign="top">Estado:</td>
		<td valign="top">
            <input type="checkbox" name="chkedoreg" id="chkedoreg" value="1" onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:190px;" disabled="disabled">
                <option value=""></option>
                <?=loadSelectValores("ESTADO-ACTUACION", "", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Actuaci&oacute;n Fiscal:</td>
		<td>
			<input type="checkbox" name="chkfactuacion" value="1" checked="checked" onclick="this.checked=!this.checked;" />
			<input type="hidden" id="nomactuacion" />
            <input type="text" id="factuacion" style="width:100px;" disabled="disabled" />
            <input type="button" value="..." id="btActuacion" onclick="validarListaActuacion(this,form);" />
		</td>
	</tr>
	<tr>
		<td align="right">Proceso:</td>
		<td colspan="3">
			<input type="checkbox" name="chkproceso" id="chkproceso" value="1" checked="checked" onclick="this.checked=!this.checked;" />
			<select name="fproceso" id="fproceso" class="selectBig" onchange="document.getElementById('factuacion').value=''; document.getElementById('nomactuacion').value='';">
				<?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $fproceso, 1);?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">&nbsp;</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>