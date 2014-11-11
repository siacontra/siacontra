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
list($codorganismo, $codrequerimiento, $secuencia, $numero) = split("[.]", $registro);
//	------------------------------------
//	consulto los datos del requerimiento
$sql = "SELECT
			rd.*,
			i.CtaGasto,
			cs.CodCuenta,
			(SELECT MIN(PrecioUnitIva)
			 FROM lg_cotizacion c
			 WHERE
			 	c.CodRequerimiento = rd.CodRequerimiento AND
				c.Secuencia = rd.Secuencia AND
				c.PrecioUnitIva <> 0.00) AS Minimo
		FROM
			lg_requerimientosdet rd
			LEFT JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
			LEFT JOIN lg_commoditysub cs ON (rd.CommoditySub = cs.CommoditySub)
		WHERE
			rd.CodRequerimiento = '".$codrequerimiento."' AND
			rd.Secuencia = '".$secuencia."'";
			
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
if ($field['CodItem'] == "") {
	$codigo = $field['CommoditySub'];
	$cuenta = $field['CodCuenta'];
} else {
	$codigo = $field['CodItem'];
	$cuenta = $field['CtaGasto'];
}

//	consulto los datos de la cotizacion
$sql = "SELECT
			c.*,
			p.NomCompleto AS NomProveedor,
			i.CodImpuesto,
			i.FactorPorcentaje
		FROM
			lg_cotizacion c
			INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
												   c.CodRequerimiento = rd.CodRequerimiento AND
												   c.Secuencia = rd.Secuencia)
			INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
			INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
			LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
			LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
		WHERE
			rd.CodRequerimiento = '".$codrequerimiento."' AND
			rd.Secuencia = '".$secuencia."' order by c.CodProveedor";

$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_detalle) != 0) { $disabled_insertar = "disabled"; $disabledr_insertar = "readonly"; }
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

<form name="frmentrada" id="frmentrada" action="lg_cotizaciones_invitar_cotizar.php" method="POST" onsubmit="return cotizaciones_invitar_cotizar(this, 'cotizaciones_invitar_cotizar');">
<input type="hidden" id="concepto" name="concepto" value="<?=$concepto?>" />
<input type="hidden" id="codorganismo" value="<?=$codorganismo?>" />
<input type="hidden" id="codrequerimiento" value="<?=$codrequerimiento?>" />
<input type="hidden" id="secuencia" value="<?=$secuencia?>" />
<input type="hidden" id="numero" value="<?=$numero?>" />
<table width="1000" class="tblForm">
	<tr>
		<td><input type="text" id="coditem" style="width:60px;" value="<?=$codigo?>" disabled="disabled" /></td>
		<td width="75">Unidad:</td>
		<td width="75"><input type="text" id="codunidad" style="width:60px;" value="<?=$field['CodUnidad']?>" disabled="disabled" /></td>
		<td width="75">Cantidad:</td>
		<td width="75"><input type="text" id="cantidad" style="width:60px;" value="<?=number_format($field['CantidadPedida'], 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2"><textarea id="descripcion" style="width:98%; height:40px;" disabled="disabled"><?=($field['Descripcion'])?></textarea></td>
		<td>C.Costo:</td>
		<td><input type="text" id="ccosto" style="width:60px;" value="<?=$field['CodCentroCosto']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td>C.Contable:</td>
		<td><input type="text" id="cuenta" style="width:60px;" value="<?=$cuenta?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td align="center" colspan="5">
        	<input type="submit" value="Aceptar" style="width:80px;" />
            <input type="button" value="Cancelar" style="width:80px;" onclick="window.close();" />
        </td>
	</tr>
</table>
</form>

<form name="frm_detalle" id="frm_detalle">
<div style="width:1000px" class="divFormCaption">Cotizaciones</div>
<table width="1000" class="tblBotones">
	<tr>
    	<td>
            <input type="button" value="Disp. Presupuestaria" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_disponibilidad_presupuestaria_invitacion.php?origen=cotizar&numero='+document.getElementById('numero').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'coditem');" />
            
            <input type="button" value="&Uacute;ltimas Cotizaciones" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_cotizaciones_ultimas.php?codorganismo='+document.getElementById('codorganismo').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'coditem');" />
            
        </td>
		<td align="right">
			<!--<input type="button" class="btLista" value="Insertar" onclick="abrirListadoInsertar('listado_personas.php?flagproveedor=S', 'detalle', 'insertarLineaListado', 'cotizaciones_invitar_cotizar_insertar', 'lg', 'height=800, width=750, left=50, top=50');" <?=$disabled_insertar?> />-->
            
			<!--<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalle'); setMontosProveedorItem();" />--> | 
            <input type="button" value="Imprimir Invitaci&oacute;n" style="width:125px;" onclick="cargarOpcion2(this.form, 'lg_cotizaciones_invitar_pdf.php?origen=cotizar&numero='+document.getElementById('numero').value, 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no', 'sel_detalle');" />
		</td>
	</tr>
</table>

<center>
<div style="overflow:scroll; width:1000px; height:250px;">
<table width="2850" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="35">#</th>
        <th scope="col" width="50">Prov.</th>
        <th scope="col">Raz&oacute;n Social</th>
        <!--<th scope="col" width="35">Cot. Asig.</th>-->
        <th scope="col" width="50">Cant.</th>
        <th scope="col" width="75">P.Unit. S/Imp.</th>
        <th scope="col" width="35">Exon. Imp.</th>
        <th scope="col" width="75">P.Unit. C/Imp.</th>
        <th scope="col" width="75">% Desc.</th>
        <th scope="col" width="75">Desc. Fijo</th>
        <th scope="col" width="75">P.Unit. Final</th>
        <th scope="col" width="75">Total</th>
        <th scope="col" width="75">Monto a Comparar</th>
        <th scope="col" width="35">Mejor Precio</th>
        <th scope="col" width="125">Forma de Pago</th>
        <th scope="col" width="75">F. Invitaci&oacute;n</th>
        <th scope="col" width="75">F. Limite</th>
        <th scope="col" width="300">Condiciones de Entrega</th>
        <th scope="col" width="300">Observaciones</th>
        <th scope="col" width="75">Dias Entega</th>
        <th scope="col" width="75">Validez Oferta</th>
        <th scope="col" width="125">Nro. Cotizaci&oacute;n</th>
        <th scope="col" width="75">Fecha Cotizaci&oacute;n</th>
        <th scope="col" width="100">Nro. Invitaci&oacute;n</th>
    </tr>
    </thead>    
    <tbody id="lista_detalle">
    <?php
	$i = 0;
	//	imprimo lasa cotizaciones
	$codProveedorAux = '';
	
	while ($field_detalle = mysql_fetch_array($query_detalle)) {
	
		if($field_detalle['CodProveedor'] != $codProveedorAux)
		{
			$codProveedorAux = $field_detalle['CodProveedor'];
	
		
			$i++;
			
			
			if ($field_detalle['FlagAsignado'] == "S") $flagasig = "checked"; else $flagasig = "";
			if ($field['Minimo'] > 0.00 && $field_detalle['PrecioUnitIva'] == $field['Minimo']) $flagmejor = "checked"; else $flagmejor = ""; 
			if ($field_detalle['FlagExonerado'] == "S") $flagexon = "checked"; else $flagexon = "";
			if ($field_detalle['CodImpuesto'] == "") { $flagexon = "checked"; $dexon = "disabled"; }
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_detalle');" id="<?=$field_detalle['CodProveedor']?>">
				<td align="center"><?=$i?></td>
				
				<td align="center">
					<input type="hidden" name="codproveedor" value="<?=$field_detalle['CodProveedor']?>" />
					<?=$field_detalle['CodProveedor']?>
				</td>
				
				<td>
					<?=($field_detalle['NomProveedor'])?>
					<input type="hidden" name="nomproveedor" value="<?=($field_detalle['NomProveedor'])?>" />
					<input type="radio" style="display:none;" name="flagasig"  id="flagasig_<?=$field_detalle['CodProveedor']?>" <?=$flagasig?> />
				</td>
				
				
				
				<td align="center">
					<input type="text" name="cant" value="<?=number_format($field_detalle['Cantidad'], 4, '.', '')?>" style="width:97%; text-align:right;" class="cell" onFocus="this.value=''" onchange="setMontosProveedorItem();" />
				</td>
				
				<td align="center">
					<input type="text" name="pu" value="<?=number_format($field_detalle['PrecioUnitInicio'], 4, '.', '')?>" style="width:97%; text-align:right;" class="cell" onFocus="this.value=''" onchange="setMontosProveedorItem();" />
				</td>
				
				<td align="center">
					<input type="checkbox" name="flagexon" id="flagexon_<?=$field_detalle['CodProveedor']?>" value="<?=$field_detalle['FactorPorcentaje']?>" <?=$flagexon?> <?=$dexon?> onchange="setMontosProveedorItem();" />
				</td>
				
				<td align="center">
					<input type="text" name="pu_igv" value="<?=number_format($field_detalle['PrecioUnitInicioIva'], 4, '.', '')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
				</td>
				
				<td align="center">
					<input type="text" name="descp" value="<?=number_format($field_detalle['DescuentoPorcentaje'], 4, '.', '')?>" style="width:97%; text-align:right;" class="cell" onFocus="this.value=''" onchange="setMontosProveedorItem();" />
				</td>
				
				<td align="center">
					<input type="text" name="descf" value="<?=number_format($field_detalle['DescuentoFijo'], 4, '.', '')?>" style="width:97%; text-align:right;" class="cell" onFocus="this.value=''" onchange="setMontosProveedorItem();" />
				</td>
				
				<td align="center">
					<input type="text" name="pu_total" value="<?=number_format($field_detalle['PrecioUnitIva'], 4, '.', '')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
				</td>
				
				<td align="center">
					<input type="text" name="total" value="<?=number_format($field_detalle['Total'], 4, '.', '')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
				</td>
				
				<td align="center">
					<input type="text" name="comparar" value="<?=number_format($precio_unitario_final, 4, '.', '')?>" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
				</td>
				
				<td align="center">
					<input type="checkbox" name="flagmejor" id="flagmejor_<?=$field_detalle['CodProveedor']?>" onclick="this.checked=!this.checked" <?=$flagmejor?> />
				</td>
				
				<td align="center">
					<select name="formapago" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';">
						<?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_detalle['CodFormaPago'], 0)?>
					</select>
				</td>
				
				<td align="center">
					<input type="text" name="finvitacion" value="<?=formatFechaDMA($field_detalle['FechaInvitacion'])?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
				</td>
				
				<td align="center">
					<input type="text" name="flimite" value="<?=formatFechaDMA($field_detalle['FechaLimite'])?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
				</td>
				
				<td align="center">
					<textarea name="condiciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"><?=($field_detalle['Condiciones'])?></textarea>
				</td>
				
				<td align="center">
					<textarea name="observaciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"><?=($field_detalle['Observaciones'])?></textarea>
				</td>
				
				<td align="center">
					<input type="text" name="dias" value="<?=$field_detalle['DiasEntrega']?>" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
				</td>
				
				<td align="center">
					<input type="text" name="validez" value="<?=$field_detalle['ValidezOferta']?>" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
				</td>
				
				<td align="center">
					<input type="text" name="nrocotizacion" value="<?=$field_detalle['NumeroCotizacion']?>" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
				</td>
			   
				<td align="center"><input type="text" name="fcot" value="<?=formatFechaDMA($field_detalle['FechaDocumento'])?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" /></td>
			   
				<td align="center"><?=$field_detalle['NumeroInvitacion']?></td>
			</tr>
        <?
		} //else if($field_detalle['CodProveedor'] == $codProveedorAux)
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
