<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ap_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ap_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Obligaciones</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_lista_obligaciones_pdf.php" method="post" target="iReporte" onsubmit="return lista_obligaciones_pdf(this);">
<input type="hidden" name="ficha" id="ficha" value="documento" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td width="125" align="right">Proveedor:</td>
		<td>
			<input type="checkbox" onclick="chkFiltroLista(this.checked, 'fproveedor', 'fnomproveedor', 'btProveedor');" />
			<input type="hidden" name="fproveedor" id="fproveedor" />
			<input type="text" name="fnomproveedor" id="fnomproveedor" style="width:250px;" disabled="disabled" />
			<input type="button" value="..." id="btProveedor" onclick="cargarVentana(this.form, '../lib/listado_personas.php?limit=0&cod=fproveedor&nom=fnomproveedor&flagproveedor=S', 'height=800, width=775, left=50, top=0, resizable=yes');" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'ftdoc');" />
			<select name="ftdoc" id="ftdoc" style="width:173px;" disabled="disabled">
            	<option value=""></option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", "", 0)?>
			</select>
		</td>
		<td align="right">Ingresado Por: </td>
		<td>
			<input type="checkbox" onclick="chkFiltroLista(this.checked, 'fcodingresado', 'fnomingresado', 'btIngresado');" />
        	<input type="hidden" name="fcodingresado" id="fcodingresado" />
			<input type="text" name="fnomingresado" id="fnomingresado" style="width:250px;" disabled="disabled" />
			<input type="button" value="..." id="btIngresado" onclick="cargarVentana(this.form, '../lib/listado_personas.php?ventana=ap_obligaciones_lista&cod=fcodingresado&nom=fnomingresado&limit=0&flagempleado=S', 'height=800, width=775, left=50, top=0, resizable=yes');" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td align="right">F. Documento:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fdocumentod', 'fdocumentoh');" />
			<input type="text" name="fdocumentod" id="fdocumentod" maxlength="10" style="width:75px;" disabled="disabled" /> - 
			<input type="text" name="fdocumentoh" id="fdocumentoh" maxlength="10" style="width:75px;" disabled="disabled" />
		</td>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
            <select name="fedoreg" id="fedoreg" style="width:100px;">
                <?=loadSelectValores("ESTADO-OBLIGACIONES-FILTRO", "", 0)?>
            </select>
        </td>
	</tr>
	<tr>
		<td align="right">F. Pago:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fpagod', 'fpagoh');" />
			<input type="text" name="fpagod" id="fpagod" maxlength="10" style="width:75px;" disabled="disabled" /> - 
			<input type="text" name="fpagoh" id="fpagoh" maxlength="10" style="width:75px;" disabled="disabled" />
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="chkFiltro(this.checked, 'fperiodo');" />
			<input type="text" name="fperiodo" id="fperiodo" maxlength="7" style="width:95px;" value="<?=date("Y-m")?>" />
		</td>
	</tr>
	<tr>
		<td align="right">F. Aprobaci&oacute;n:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'faprobaciond', 'faprobacionh');" />
			<input type="text" name="faprobaciond" id="faprobaciond" maxlength="10" style="width:75px;" disabled="disabled" /> - 
			<input type="text" name="faprobacionh" id="faprobacionh" maxlength="10" style="width:75px;" disabled="disabled" />
		</td>
    	<td align="right">&nbsp;</td>
		<td><input type="checkbox" name="flagvertotales" id="flagvertotales" value="S" /> Totales x Persona</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_lista_obligaciones_pdf.php');">Lista de Obligaciones</a>
            </li>
            <!--<li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_lista_obligaciones_adelantos_pdf.php');">
                	Obligaciones Vs. Adelantos
                </a>
            </li>-->
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border-left:solid 1px #CDCDCD; border-right:solid 1px #CDCDCD; border-bottom:solid 1px #CDCDCD; border-top:0; width:1000px; height:600px;"></iframe>
</center>
</body>
</html>