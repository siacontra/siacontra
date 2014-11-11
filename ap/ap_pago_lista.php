<?php
$Ahora = ahora();
if ($filtrar == "default") {
	list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fFechaPagod = "01-$Mes-$Anio";
	$fFechaPagoh = "$Dia-$Mes-$Anio";
	$fEstado = "IM";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (p.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fNroProceso != "") { $cNroProceso = "checked"; $filtro.=" AND (p.NroProceso LIKE '%".$fNroProceso."%')"; } else $dNroProceso = "disabled";
if ($fNroPago != "") { $cNroPago = "checked"; $filtro.=" AND (p.NroPago LIKE '%".$fNroPago."%')"; } else $dNroPago = "disabled";
if ($fFechaPagod != "" || $fFechaPagoh != "") {
	$cFechaPago = "checked";
	if ($fFechaPagod != "") $filtro.=" AND (p.FechaPago >= '".formatFechaAMD($fFechaPagod)."')";
	if ($fFechaPagoh != "") $filtro.=" AND (p.FechaPago <= '".formatFechaAMD($fFechaPagoh)."')";
} else $dFechaPago = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (p.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Pagos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_pago_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:200px;" value="<?=$fNomProveedor?>" readonly />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dCodProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td align="right">Nro. Pre-Pago:</td>
		<td>
			<input type="checkbox" <?=$cNroProceso?> onclick="chkFiltro(this.checked, 'fNroProceso');" />
			<input type="text" name="fNroProceso" id="fNroProceso" value="<?=$fNroProceso?>" maxlength="20" style="width:100px;" <?=$dNroProceso?> />
		</td>
		<td align="right">Nro. Pago:</td>
		<td>
			<input type="checkbox" <?=$cNroPago?> onclick="chkFiltro(this.checked, 'fNroPago');" />
			<input type="text" name="fNroPago" id="fNroPago" value="<?=$fNroPago?>" maxlength="20" style="width:100px;" <?=$dNroPago?> />
		</td>
	</tr>
	<tr>
		<td align="right">F.Orden: </td>
		<td>
			<input type="checkbox" <?=$cFechaPago?> onclick="chkFiltro_2(this.checked, 'fFechaPagod', 'fFechaPagoh');" />
			<input type="text" name="fFechaPagod" id="fFechaPagod" value="<?=$fFechaPagod?>" <?=$dFechaPago?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaPagoh" id="fFechaPagoh" value="<?=$fFechaPagoh?>" <?=$dFechaPago?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
            <select name="fEstado" id="fEstado" style="width:105px;">
                <?=loadSelectValores("ESTADO-PAGO2", $fEstado, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btImprimirSustento" value="Imprimir Sustento" style="width:100px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_orden_pago_transferir_reportes', 'BLANK', 'height=800, width=1050, left=0, top=50, resizable=no', $('#registro').val());" />
            
			<input type="button" id="btVerVoucher" value="Ver Voucher" style="width:100px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_generar_vouchers_pagos_voucher&opcion=ver', 'BLANK', 'height=600, width=1050, left=0, top=50, resizable=no', $('#registro').val());" />
            
			<input type="button" id="btVerPago" value="Ver Pago" style="width:100px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_pago_form&opcion=ver', 'SELF', '', $('#registro').val());" /> | 
            
			<input type="button" id="btModificar" value="Modificar Pago" style="width:100px;" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=pago_modificar', 'gehen.php?anz=ap_pago_form&opcion=modificar', 'SELF', '');" />
            
			<input type="button" id="btAnular" value="Anular Pago" style="width:100px;" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=pago_anular', 'gehen.php?anz=ap_pago_form&opcion=anular', 'SELF', '');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1650" class="tblLista">
	<thead>
		<th scope="col" width="100">Cta. Bancaria</th>
		<th scope="col" width="90">N&uacute;mero</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="100">Monto</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="75">Estado</th>
		<th scope="col" width="75">Pre-Pago</th>
		<th scope="col" width="20">#</th>
		<th scope="col" width="125">Tipo de Pago</th>
		<th scope="col" width="125">Origen</th>
		<th scope="col" width="125">Voucher</th>
		<th scope="col" width="125">Voucher Anulaci&oacute;n</th>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				p.*,
				tp.TipoPago,
				sf.Descripcion AS Origen
			FROM
				ap_pagos p
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
				LEFT JOIN ac_sistemafuente sf ON (p.CodSistemaFuente = sf.CodSistemaFuente)
			WHERE 1 $filtro
			ORDER BY NroProceso, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				p.*,
				tp.TipoPago,
				sf.Descripcion AS Origen
			FROM
				ap_pagos p
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
				LEFT JOIN ac_sistemafuente sf ON (p.CodSistemaFuente = sf.CodSistemaFuente)
			WHERE 1 $filtro
			ORDER BY NroProceso, Secuencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[NroProceso].$field[Secuencia].$field[CodTipoPago]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroCuenta']?></td>
			<td align="center"><?=$field['NroPago']?></td>
			<td><?=(htmlspecialchars($field['NomProveedorPagar']))?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="center"><?=printValores("ESTADO-PAGO", $field['Estado'])?></td>
			<td align="center"><?=$field['NroProceso']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=($field['TipoPago'])?></td>
			<td align="center"><?=($field['CodSistemaFuente'])?></td>
			<td align="center"><?=$field['Periodo']?>-<?=$field['VoucherPago']?></td>
			<td align="center"><?=$field['VoucherAnulacion']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
