<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
//	------------------------------------
//	datos generales
$sql = "SELECT *
		FROM lg_cotizacion
		WHERE NroCotizacionProv = '".$registro."'
		GROUP BY CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------------
if (formatFechaDMA($field['FechaRecepcion']) == "") $frecepcion = $field['FechaDocumento']; else $frecepcion = $field['FechaRecepcion'];
if (formatFechaDMA($field['FechaApertura']) == "") $fapertura = $field['FechaDocumento']; else $frecepcion = $field['FechaApertura'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
</head>

<body>
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Cotizaciones de los Proveedores Invitados</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_cotizaciones_invitaciones_cotizar.php" method="POST" onsubmit="return cotizaciones_invitaciones_cotizar(this, 'cotizaciones_invitaciones_cotizar');">
<input type="hidden" id="concepto" name="concepto" value="<?=$concepto?>" />
<table width="1050" class="tblForm">
	<tr>
		<td width="100">Fecha Invitaci&oacute;n:</td>
		<td><input type="text" id="finvitacion" style="width:60px;" value="<?=formatFechaDMA($field['FechaInvitacion'])?>" disabled="disabled" /></td>
		<td width="100">Proveedor:</td>
		<td>
        	<input type="hidden" id="codproveedor" value="<?=$field['CodProveedor']?>" disabled="disabled" />
        	<input type="text" id="nomproveedor" value="<?=($field['NomProveedor'])?>" style="width:350px;" disabled="disabled" />
        </td>
		<td width="100">Dcto. (%):</td>
		<td><input type="text" id="descp" style="width:40px; text-align:right;" value="<?=number_format($field['DescuentoPorcentaje'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	</tr>
	<tr>
		<td>Fecha Limite:</td>
		<td><input type="text" id="flimite" style="width:60px;" value="<?=formatFechaDMA($field['FechaLimite'])?>" disabled="disabled" /></td>
		<td>Cotizaci&oacute;n:</td>
		<td>
        	<input type="text" id="nrocotizacionprov" value="<?=$field['NumeroCotizacion']?>" style="width:60px;" />
        	<input type="text" id="fcotizacion" value="<?=formatFechaDMA($field['FechaDocumento'])?>" style="width:60px;" />
        </td>
		<td>Dcto. (Monto):</td>
		<td><input type="text" id="descf" style="width:40px; text-align:right;" value="<?=number_format($field['DescuentoMonto'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	</tr>
	<tr>
		<td>Fecha Recepci&oacute;n:</td>
		<td><input type="text" id="frecepcion" style="width:60px;" value="<?=formatFechaDMA($frecepcion)?>" onkeyup="setFechaDMA(this);" /></td>
		<td>Forma de Pago:</td>
		<td>
        	<select id="codformapago" style="width:135px;">
            	<?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field['CodFormaPago'], 0)?>
            </select>
        </td>
		<td>Validez Oferta:</td>
		<td><input type="text" id="validez" style="width:40px;" value="<?=$field['ValidezOferta']?>" /></td>
	</tr>
	<tr>
		<td>Fecha Apertura:</td>
		<td><input type="text" id="fapertura" style="width:60px;" value="<?=formatFechaDMA($fapertura)?>" onkeyup="setFechaDMA(this);" /></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Plazo Entrega:</td>
		<td><input type="text" id="dias" style="width:40px;" value="<?=$field['DiasEntrega']?>" /></td>
	</tr>
	<tr>
		<td align="center" colspan="6">
        	<input type="submit" value="Aceptar" style="width:80px;" />
            <input type="button" value="Cancelar" style="width:80px;" onclick="window.close();" />
        </td>
	</tr>
</table>
</form>

<form name="frm_detalle" id="frm_detalle">
<div style="width:1050px" class="divFormCaption">Cotizaciones</div>
<center>
<div style="overflow:scroll; width:1050px; height:375px;">
<table width="2250" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="100"># Requerimiento</th>
        <th scope="col" width="35">#</th>
        <th scope="col" width="75">Item Commodity</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="50">Und.</th>
        <th scope="col" width="75">Cant. Pedida</th>
        <th scope="col" width="75">Cant. Faltante</th>
        <th scope="col" width="75">Cant. Asignada</th>
        <th scope="col" width="100">P.Unit. S/Imp.</th>
        <th scope="col" width="45">Cot. Asig.</th>
        <th scope="col" width="45">Exon. Imp.</th>
        <th scope="col" width="100">P.Unit. C/Imp.</th>
        <th scope="col" width="100">% Desc.</th>
        <th scope="col" width="100">Desc. Fijo</th>
        <th scope="col" width="100">P.Unit. s/Imp. c/Desc.</th>
        <th scope="col" width="100">P.Unit. c/Imp. c/Desc.</th>
        <th scope="col" width="100">Total</th>
        <th scope="col" width="250">Observaciones</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalle">
    <?php
	$i = 0;
	//	imprimo las cotizaciones
	$sql = "SELECT
				c.*,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida,
				r.CodInterno,
				i.CodImpuesto,
				i.FactorPorcentaje
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_cotizacion c ON (rd.CodOrganismo = c.CodOrganismo AND 
											   rd.CodRequerimiento = c.CodRequerimiento AND 
											   rd.Secuencia = c.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
			WHERE c.NroCotizacionProv = '".$registro."'";
	$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalle = mysql_fetch_array($query_detalle)) {
		$i++;
		
		if ($field_detalle['CodItem'] != "") $codigo = $field_detalle['CodItem']; else $codigo = $field_detalle['CommoditySub'];		
		if ($field_detalle['FlagAsignado'] == "S") $flagasig = "checked"; else $flagasig = "";
		if ($field_detalle['FlagExonerado'] == "S") $flagexon = "checked"; else $flagexon = "";
		if ($field_detalle['CodImpuesto'] == "") { $flagexon = "checked"; $dexon = "disabled"; }
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');" id="<?=$field_detalle['CotizacionSecuencia']?>">
            <td align="center">
                <input type="hidden" name="cotizacion_secuencia" value="<?=$field_detalle['CotizacionSecuencia']?>" />
				<?=$field_detalle['CodInterno']?>
            </td>
            
            <td align="center"><?=$field_detalle['Secuencia']?></td>
            
        	<td align="center"><?=$codigo?></td>
            
        	<td><?=($field_detalle['Descripcion'])?></td>
            
        	<td align="center"><?=$field_detalle['CodUnidad']?></td>
            
        	<td align="right"><?=number_format($field_detalle['CantidadPedida'], 2, ',', '.')?></td>
            
        	<td align="right"></td>
            
            <td align="center">
            	<input type="text" name="cant" value="<?=number_format($field_detalle['Cantidad'], 2, ',', '.')?>" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosItemProveedor();" />
            </td>
            
            <td align="center">
            	<input type="text" name="pu" value="<?=number_format($field_detalle['PrecioUnitInicio'], 2, ',', '.')?>" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosItemProveedor();" />
			</td>
            
            <td align="center"><input type="checkbox" name="flagasig" id="flagasig_<?=$field_detalle['CodProveedor']?>" <?=$flagasig?> /></td>
            
            <td align="center">
            	<input type="checkbox" name="flagexon" id="flagexon_<?=$field_detalle['CodProveedor']?>" value="<?=$field_detalle['FactorPorcentaje']?>" <?=$flagexon?> <?=$dexon?> onchange="setMontosItemProveedor();" />
            </td>
            
            <td align="center">
            	<input type="text" name="pu_igv" value="<?=number_format($field_detalle['PrecioUnitInicioIva'], 2, ',', '.')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
            </td>
            
            <td align="center">
            	<input type="text" name="descp" value="<?=number_format($field_detalle['DescuentoPorcentaje'], 2, ',', '.')?>" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosItemProveedor();" />
            </td>
            
            <td align="center">
            	<input type="text" name="descf" value="<?=number_format($field_detalle['DescuentoFijo'], 2, ',', '.')?>" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosItemProveedor();" />
            </td>
            
            <td align="center">
            	<input type="text" name="pu_desc" value="<?=number_format($field_detalle['PrecioUnit'], 2, ',', '.')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
            </td>
            
            <td align="center">
            	<input type="text" name="pu_total" value="<?=number_format($field_detalle['PrecioUnitIva'], 2, ',', '.')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
            </td>
            
            <td align="center">
            	<input type="text" name="total" value="<?=number_format($field_detalle['Total'], 2, ',', '.')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
            </td>
            
            <td align="center">
                <textarea name="observaciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"><?=($field_detalle['Observaciones'])?></textarea>
            </td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" name="sel_detalle" id="sel_detalle" />
<input type="hidden" name="can_detalle" id="can_detalle" value="<?=$i?>" />
<input type="hidden" name="nro_detalle" id="nro_detalle" value="<?=$i?>" />
</form>
</body>
</html>