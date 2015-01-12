<?php
list($Anio, $CodOrganismo, $NroOrden, $Secuencia) = split("[.]", $registro);
//	consulto datos generales	
$sql = "SELECT
			os.NroInterno,
			os.CodTipoServicio,
			os.CodProveedor,
			osd.Anio,
			osd.CodOrganismo,
			osd.NroOrden,
			osd.Secuencia,
			osd.CodCentroCosto,
			osd.PrecioUnit,
			osd.FlagExonerado,
			osd.CommoditySub,
			osd.Descripcion,
			osd.FechaEsperadaTermino,
			osd.FechaTermino,
			osd.CantidadPedida As CantidadTotal,
			'100' AS PorcentajeTotal,
			osd.CantidadRecibida AS RecibidaTotal,
			(osd.CantidadRecibida * 100 / CantidadPedida) AS RecibidaPorcentaje,
			(osd.CantidadPedida - osd.CantidadRecibida) AS PorRecibirTotal,
			((osd.CantidadPedida - osd.CantidadRecibida) * 100 / osd.CantidadPedida) AS PorRecibirPorcentaje
		FROM
			lg_ordenserviciodetalle osd
			INNER JOIN lg_ordenservicio os ON (osd.Anio = os.Anio AND
											   osd.CodOrganismo = os.CodOrganismo AND
											   osd.NroOrden = os.NroOrden)
		WHERE
			osd.Anio = '".$Anio."' AND
			osd.CodOrganismo = '".$CodOrganismo."' AND
			osd.NroOrden = '".$NroOrden."' AND
			osd.Secuencia = '".$Secuencia."'";
$query_orden = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_orden)) $field_orden = mysql_fetch_array($query_orden);
$SaldoTotal = $field_orden['RecibidaTotal']+ $field_orden['PorRecibirTotal'];
$SaldoPorcentaje = $field_orden['RecibidaPorcentaje']+ $field_orden['PorRecibirPorcentaje'];
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Confirmar Servicio</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_orden_servicio_confirmar_lista" method="POST" onsubmit="return orden_servicio_confirmar(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fFechaPreparaciond" id="fFechaPreparaciond" value="<?=$fFechaPreparaciond?>" />
<input type="hidden" name="fFechaPreparacionh" id="fFechaPreparacionh" value="<?=$fFechaPreparacionh?>" />
<input type="hidden" id="Anio" value="<?=$field_orden['Anio']?>" />
<input type="hidden" id="CodOrganismo" value="<?=$field_orden['CodOrganismo']?>" />
<input type="hidden" id="NroOrden" value="<?=$field_orden['NroOrden']?>" />
<input type="hidden" id="CommoditySub" value="<?=$field_orden['CommoditySub']?>" />
<input type="hidden" id="CodTipoServicio" value="<?=$field_orden['CodTipoServicio']?>" />
<input type="hidden" id="CodProveedor" value="<?=$field_orden['CodProveedor']?>" />
<input type="hidden" id="PrecioUnit" value="<?=$field_orden['PrecioUnit']?>" />
<input type="hidden" id="CodCentroCosto" value="<?=$field_orden['CodCentroCosto']?>" />
<input type="hidden" id="FlagExonerado" value="<?=$field_orden['FlagExonerado']?>" />

<table width="600" class="tblForm">
    	<td colspan="4" class="divFormCaption">Datos de la Confirmaci&oacute;n</td>
	<tr>
		<td width="135">N&uacute;mero:</td>
		<td>
        	<input type="text" id="NroInterno" style="width:85px;" class="codigo" value="<?=$field_orden['NroInterno']?>" disabled="disabled" />
        	<input type="text" id="Secuencia" style="width:10px;" class="codigo" value="<?=$field_orden['Secuencia']?>" disabled="disabled" />
		</td>
		<td width="100">Total:</td>
        <td align="right" width="100">
        	<input type="text" id="CantidadTotal" value="<?=number_format($field_orden['CantidadTotal'], 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        	<input type="text" id="PorcentajeTotal" value="<?=number_format($field_orden['PorcentajeTotal'], 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td>Fecha Esperada:</td>
		<td>
        	<input type="text" id="FechaEsperadaTermino" value="<?=formatFechaDMA($field_orden['FechaEsperadaTermino'])?>" maxlength="10" style="width:83px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled="disabled" />
		</td>
		<td>Recibida:</td>
        <td align="right">
        	<input type="text" id="RecibidaTotal" value="<?=number_format($field_orden['RecibidaTotal'], 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        	<input type="text" id="RecibidaPorcentaje" value="<?=number_format($field_orden['RecibidaPorcentaje'], 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td>Fecha Termino Real:</td>
		<td>
        	<input type="text" id="FechaTermino" value="<?=formatFechaDMA($field_orden['FechaTermino'])?>" maxlength="10" style="width:83px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
		<td>Por Recibir:</td>
        <td align="right">
        	<input type="text" id="PorRecibirTotal" value="<?=number_format($field_orden['PorRecibirTotal'], 2, ',', '.')?>" style="width:35px; text-align:right;" onFocus="numeroFocus(this);" onBlur="numeroBlur(this); setSaldoTotalConfirmacionServicio();" />
        	<input type="text" id="PorRecibirPorcentaje" value="<?=number_format($field_orden['PorRecibirPorcentaje'], 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        </td>
	</tr>
	<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>Nuevo Saldo:</td>
        <td align="right">
        	<input type="text" id="SaldoTotal" value="<?=number_format($SaldoTotal, 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        	<input type="text" id="SaldoPorcentaje" value="<?=number_format($SaldoPorcentaje, 2, ',', '.')?>" style="width:35px; text-align:right;" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td colspan="4"><textarea id="Descripcion" style="width:99%; height:50px;" disabled><?=($field_orden['Descripcion'])?></textarea></td>
	</tr>
</table>
<center> 
<input type="submit" value="Confirmar" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>