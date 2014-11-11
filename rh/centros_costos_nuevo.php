<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<?php
include("fphp_sia.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Centros de Costos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'centros_costos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="centros_costos.php" method="POST" onsubmit="return verificarCentrosCostos(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Centro de Costo</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Centro de Costo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="4" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:80%;" maxlength="50" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Dependencia:</td>
		<td>
			<select name="dependencia" id="dependencia" style="width:80%;">
				<option value=""></option>
				<?=getDependencias("", $_SESSION["FILTRO_ORGANISMO_ACTUAL"], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
        <td class="tagForm">Empleado:</td>
        <td>
            <input name="codempleado" type="text" id="codempleado" size="10" disabled="disabled" />
            <input name="nomempleado" type="text" id="nomempleado" size="75" disabled="disabled" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=700, width=800, left=350, top=350, resizable=yes');" />*
        </td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de C.Costos:</td>
		<td>
			<select name="tipo_ccosto" id="tipo_ccosto" style="width:150px;">
				<option value=""></option>
				<?=cargarSelect("TIPO-CENTRO-COSTO", "", 0);?>
			</select>*
		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption">Informaci&oacute;n Presupuestal</div>
<table width="700" class="tblForm">
	
	<tr>
        <td class="tagForm">Grupo C.C:</td>
        <td>
            <input name="codgrupo_cc" type="text" id="codgrupo_cc" size="10" disabled="disabled" />
            <input name="nomgrupo_cc" type="text" id="nomgrupo_cc" size="75" disabled="disabled" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'lista_grupos_centros_costos.php', 'height=700, width=800, left=350, top=350, resizable=yes');" />*
        </td>
    </tr>
	<tr>
        <td class="tagForm">Sub-Grupo C.C:</td>
        <td>
            <input name="codsubgrupo_cc" type="text" id="codsubgrupo_cc" size="10" disabled="disabled" />
            <input name="nomsubgrupo_cc" type="text" id="nomsubgrupo_cc" size="75" disabled="disabled" />
        </td>
    </tr>
</table>

<div style="width:700px" class="divFormCaption">Tipo de Costo</div>
<table width="700" class="tblForm">
	<tr>
		<td width="150"></td>
		<td>
			<input type="checkbox" name="tipo_costo" id="flagadministrativo" /> Administrativo <br />
			<input type="checkbox" name="tipo_costo" id="flagventas" /> Ventas <br />
			<input type="checkbox" name="tipo_costo" id="flagfinanciero" /> Financiero <br />
			<input type="checkbox" name="tipo_costo" id="flagproduccion" /> Producci&oacute;n <br />
			<input type="checkbox" name="tipo_costo" id="flagcentroingreso" /> Centro de Ingresos
		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption">Auditoria</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'centros_costos.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
