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
		<td class="titulo">Obligaciones Pendientes</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_obligaciones_pendientes_provedores_pdf.php" method="post" target="iReporte" onsubmit="return obligaciones_pendientes_provedores_pdf(this);">
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
		<td align="right">F. Vencimiento:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fvencimientod', 'fvencimientoh');" />
			<input type="text" name="fvencimientod" id="fvencimientod" maxlength="10" style="width:75px;" disabled="disabled" /> - 
			<input type="text" name="fvencimientoh" id="fvencimientoh" maxlength="10" style="width:75px;" disabled="disabled" />
		</td>
    	<td align="right">C. Costo: </td>
		<td>
			<input type="checkbox" onclick="chkFiltroLista(this.checked, 'fcodccosto', 'fnomccosto', 'btCCosto');" />
        	<input type="text" name="fcodccosto" id="fcodccosto" style="width:50px;" disabled="disabled" />
			<input type="hidden" name="fnomccosto" id="fnomccosto" />
			<input type="button" value="..." id="btCCosto" onclick="cargarVentana(this.form, '../lib/listado_centro_costos.php?ventana=&cod=fcodccosto&nom=fnomccosto&limit=0', 'height=800, width=875, left=50, top=0, resizable=yes');" disabled="disabled" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</body>
</html>