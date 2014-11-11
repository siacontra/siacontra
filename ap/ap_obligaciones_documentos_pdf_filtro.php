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
		<td class="titulo">Documentos Emitidos / Cheques Girados</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_obligaciones_documentos_pdf.php" method="post" target="iReporte" onsubmit="return obligaciones_documentos_pdf(this);">
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
    	<td width="125" align="right">Tipo de Pago: </td>
		<td>
        	<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="ftipo_pago" id="ftipo_pago" style="width:200px;">
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", "", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Fecha:</td>
		<td>
        	<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<input type="text" name="ffechad" id="ffechad" maxlength="10" style="width:75px;" value="<?=date("01-m-Y")?>" /> - 
			<input type="text" name="ffechah" id="ffechah" maxlength="10" style="width:75px;" value="<?=date("d-m-Y")?>" />
		</td>
		<td align="right">Cta. Bancaria:</td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fctabancaria');" />
        	<select name="fctabancaria" id="fctabancaria" style="width:200px;" disabled="disabled">
            	<option value="">:::. Seleccione .:::</option>
                <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", "", 0)?>
            </select>
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