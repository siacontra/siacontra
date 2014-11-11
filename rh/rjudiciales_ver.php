<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
$sql = "SELECT 
			rj.*, 
			mp.NomCompleto, 
			mp2.CodPersona AS CodDemandante, 
			mp2.NomCompleto AS NomDemandante
		FROM 
			rh_retencionjudicial rj 
			INNER JOIN mastpersonas mp ON (rj.CodPersona = mp.CodPersona) 
			INNER JOIN mastpersonas mp2 ON (rj.Demandante = mp2.CodPersona)
		WHERE
			rj.CodRetencion = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
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
		<td class="titulo">Retenciones Judiciales | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="rjudiciales.php" method="POST">
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
<input type="hidden" name="codretencion" id="codretencion" value="<?=$field['CodRetencion']?>" />

<div style="width:700px" class="divFormCaption">Datos de la Retenci&oacute;n</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" class="selectBig" disabled="disabled">
				<?=getOrganismos($field['CodOrganismo'], 3)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha de Resoluci&oacute;n:</td>
		<td><input name="fresolucion" type="text" id="fresolucion" size="15" maxlength="10" value="<?=formatFechaDMA($field['FechaResolucion'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Expediente:</td>
		<td><input name="expediente" type="text" id="expediente" size="30" maxlength="30" value="<?=($field['Expediente'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Retenci&oacute;n:</td>
		<td>
			<select name="tipo" id="tipo" class="selectMed" disabled="disabled">
				<?=getMiscelaneos($field['TipoRetencion'], 'RJUDICIAL', 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Juzgado:</td>
		<td><input name="juzgado" type="text" id="juzgado" size="100" maxlength="255" value="<?=($field['Juzgado'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Empleado:</td>
		<td>
			<input name="codempleado" type="hidden" id="codempleado" value="<?=$field['CodPersona']?>" />
			<input name="nomempleado" type="text" id="nomempleado" size="75" value="<?=($field['NomCompleto'])?>" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Demandante:</td>
		<td>
			<input name="coddemandante" type="hidden" id="coddemandante" value="<?=$field['CodDemandante']?>" />
			<input name="nomdemandante" type="text" id="nomdemandante" size="75" value="<?=($field['NomDemandante'])?>" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_personas_otros.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
			<select name="tipo_pago" id="tipo_pago" class="selectMed" disabled="disabled">
				<?=getTPago($field['CodTipoPago'], 0);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Comentarios:</td>
		<td><textarea name="comentarios" id="comentarios" style="width:90%; height:50px;" disabled="disabled"><?=($field['Observaciones'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input type="radio" name="estado" id="activo" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;
			<input type="radio" name="estado" id="inactivo" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
</table>
<center> 
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cerrar" onClick="window.close();" />
</center><br />
</form>

<div style="width:700px" class="divFormCaption">Datos de N&oacute;mina</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />

<table width="700" class="tblBotones">
 <tr>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertarConcepto" value="Concepto" disabled="disabled" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar"disabled="disabled" />
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
    <?
	$sql = "SELECT
				rjc.*,
				c.Descripcion AS NomConcepto 
			FROM 
				rh_retencionjudicialconceptos rjc
				INNER JOIN pr_concepto c ON (rjc.CodConcepto = c.CodConcepto) 
			WHERE 
				rjc.CodOrganismo = '".$field['CodOrganismo']."' AND 
				rjc.CodRetencion = '".$field['CodRetencion']."'";
	$query_det = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_det = mysql_fetch_array($query_det)) {
		if ($field_det['TipoDescuento'] == "P") $porcentaje = $field_det['Descuento'];
		elseif ($field_det['TipoDescuento'] == "M") $monto = $field_det['Descuento'];
		?>
        <tr class="trListaBody">
            <td align="center">
                <input type="hidden" name="txtcodconcepto" value="<?=$field_det['CodConcepto']?>" />
                <?=$field_det['CodConcepto']?>
            </td>
            <td><?=($field_det['NomConcepto'])?></td>
            <td><input type="text" name="txtporcentaje" value="<?=number_format($porcentaje, 2, ',', '.')?>" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
            <td><input type="text" name="txtmonto" value="<?=number_format($monto, 2, ',', '.')?>" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		</tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
