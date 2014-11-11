<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Retenciones Judiciales | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'rjudiciales.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="rjudiciales.php" method="POST" onsubmit="return verificarRetencionJudicial(this, 'GUARDAR');">
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="chkorganismo" id="chkorganismo" value="<?=$chkorganismo?>" />
<input type="hidden" name="finicio" id="finicio" value="<?=$finicio?>" />
<input type="hidden" name="chkinicio" id="chkinicio" value="<?=$chkinicio?>" />
<input type="hidden" name="ffin" id="ffin" value="<?=$ffin?>" />
<input type="hidden" name="chkfin" id="chkfin" value="<?=$chkfin?>" />
<input type="hidden" name="fexpediente" id="fexpediente" value="<?=$fexpediente?>" />
<input type="hidden" name="chkexpediente" id="chkexpediente" value="<?=$chkexpediente?>" />
<input type="hidden" name="fstatus" id="fstatus" value="<?=$fstatus?>" />
<input type="hidden" name="chkstatus" id="chkstatus" value="<?=$chkstatus?>" />
<input type="hidden" name="fempleado" id="fempleado" value="<?=$fempleado?>" />
<input type="hidden" name="chkempleado" id="chkempleado" value="<?=$chkempleado?>" />
<input type="hidden" name="codretencion" id="codretencion" />

<div style="width:700px" class="divFormCaption">Datos de la Retenci&oacute;n</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" class="selectBig">
				<?=getOrganismos($forganismo, 3)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha de Resoluci&oacute;n:</td>
		<td><input name="fresolucion" type="text" id="fresolucion" size="15" maxlength="10" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Expediente:</td>
		<td><input name="expediente" type="text" id="expediente" size="30" maxlength="30" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Retenci&oacute;n:</td>
		<td>
			<select name="tipo" id="tipo" class="selectMed">
				<option value=""></option>
				<?=getMiscelaneos("", 'RJUDICIAL', 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Juzgado:</td>
		<td><input name="juzgado" type="text" id="juzgado" size="100" maxlength="255" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Empleado:</td>
		<td>
			<input name="codempleado" type="hidden" id="codempleado" />
			<input name="nomempleado" type="text" id="nomempleado" size="75" value="" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Demandante:</td>
		<td>
			<input name="coddemandante" type="hidden" id="coddemandante" />
			<input name="nomdemandante" type="text" id="nomdemandante" size="75" value="" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_personas_otros.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
			<select name="tipo_pago" id="tipo_pago" class="selectMed">
				<option value=""></option>
				<?=getTPago("", 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Comentarios:</td>
		<td><textarea name="comentarios" id="comentarios" style="width:90%; height:50px;"></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="estado" id="activo" checked="checked" /> Activo &nbsp;
			<input type="radio" name="estado" id="inactivo" /> Inactivo
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'rjudiciales.php');" />
</center><br />
</form>

<div style="width:700px" class="divFormCaption">Datos de N&oacute;mina</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />

<table width="700" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertarConcepto" value="Concepto" onclick="window.open('lista_conceptos_retencion.php?limit=0', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarDetalleConceptoRetencion(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:250px; width:700px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:200px; width:696px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="75">Concepto</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Porcentaje</th>
        <th scope="col" width="100">Monto</th>
    </tr>
                
    <tbody id="listaDetalles">
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
