<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Ordenes de Pago";
	$btPrepago = "display:none;";
}
elseif ($lista == "prepago") {
	$titulo = "Preparaci&oacute;n del Pre-Pago";
	$fEstado = "PE";
	$btModificar = "display:none;";
	$btImprimir = "display:none;";
	$btAnular = "display:none;";
}
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fEstado = "PE";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fordenar = "NroOrden";
	$FlagPagoDiferido = "";
	if ($lista == "todos") {
		$fFechaOrdenPagod = "01-$Mes-$Anio";
		$fFechaOrdenPagoh = "$Dia-$Mes-$Anio";
	}
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (op.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (op.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fCodSistemaFuente != "") { $cCodSistemaFuente = "checked"; $filtro.=" AND (op.CodSistemaFuente = '".$fCodSistemaFuente."')"; } else $dCodSistemaFuente = "disabled";
if ($fCodTipoDocumento != "") { $cCodTipoDocumento = "checked"; $filtro.=" AND (op.CodTipoDocumento = '".$fCodTipoDocumento."')"; } else $dCodTipoDocumento = "disabled";
if ($fNroDocumento != "") { $cNroDocumento = "checked"; $filtro.=" AND (op.NroDocumento LIKE '%".$fNroDocumento."%')"; } else $dNroDocumento = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (op.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fFechaOrdenPagod != "" || $fFechaOrdenPagoh != "") {
	$cFechaOrdenPago = "checked";
	if ($fFechaOrdenPagod != "") $filtro.=" AND (op.FechaOrdenPago >= '".formatFechaAMD($fFechaOrdenPagod)."')";
	if ($fFechaOrdenPagoh != "") $filtro.=" AND (op.FechaOrdenPago <= '".formatFechaAMD($fFechaOrdenPagoh)."')";
} else $dFechaOrdenPago = "disabled";
if ($FlagPagoDiferido == "S") { $cFlagPagoDiferido = "checked"; $filtro.=" AND (op.FlagPagoDiferido = 'S')"; }
if ($fCodBanco != "") {
	$cCodBanco = "checked";
	$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro.= " AND (op.NroCuenta = '".$fNroCuenta."')";
} else $dCodBanco = "disabled";
if ($fMontoTotald != "" || $fMontoTotalh != "") {
	$cMontoTotal = "checked";
	if ($fMontoTotald != "") $filtro.=" AND (op.MontoTotal >= ".setNumero($fMontoTotald).")";
	if ($fMontoTotalh != "") $filtro.=" AND (op.MontoTotal <= ".setNumero($fMontoTotalh).")"; 
} else $dMontoTotal = "disabled";
if ($fordenar != "") $cordenar = "checked"; else $dordenar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_orden_pago_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
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
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" <?=$cCodTipoDocumento?> onclick="chkFiltro(this.checked, 'fCodTipoDocumento');" />
			<select name="fCodTipoDocumento" id="fCodTipoDocumento" style="width:300px;" <?=$dCodTipoDocumento?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $fCodTipoDocumento, 0)?>
			</select>
		</td>
		<td align="right">Sistema Fuente:</td>
		<td>
			<input type="checkbox" <?=$cCodSistemaFuente?> onclick="chkFiltro(this.checked, 'fCodSistemaFuente');" />
			<select name="fCodSistemaFuente" id="fCodSistemaFuente" style="width:300px;" <?=$dCodSistemaFuente?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ac_sistemafuente", "CodSistemaFuente", "Descripcion", $fCodSistemaFuente, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Nro Doc.:</td>
		<td>
			<input type="checkbox" <?=$cNroDocumento?> onclick="chkFiltro(this.checked, 'fNroDocumento');" />
			<input type="text" name="fNroDocumento" id="fNroDocumento" value="<?=$fNroDocumento?>" maxlength="20" style="width:100px;" <?=$dNroDocumento?> />
		</td>
		<td align="right">Banco:</td>
		<td>
			<input type="checkbox" <?=$cCodBanco?> onclick="chkFiltro(this.checked, 'fCodBanco');" />
			<select name="fCodBanco" id="fCodBanco" style="width:300px;" <?=$dCodBanco?> onChange="getOptionsSelect(this.value, 'cuentas_bancarias', 'fNroCuenta', true)">
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", $fCodBanco, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "prepago") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:105px;">
                    <?=loadSelectValores("ESTADO-ORDEN-PAGO", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:105px;" <?=$dEstado?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelectValores("ESTADO-ORDEN-PAGO", $fEstado, 0)?>
                </select>
                <?
			} 
			?>
		</td>
		<td align="right">Cta. Bancaria:</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
			<select name="fNroCuenta" id="fNroCuenta" style="width:300px;" <?=$dCodBanco?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", $fNroCuenta, $fCodBanco, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">F.Orden: </td>
		<td>
			<input type="checkbox" <?=$cFechaOrdenPago?> onclick="chkFiltro_2(this.checked, 'fFechaOrdenPagod', 'fFechaOrdenPagoh');" />
			<input type="text" name="fFechaOrdenPagod" id="fFechaOrdenPagod" value="<?=$fFechaOrdenPagod?>" <?=$dFechaOrdenPago?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaOrdenPagoh" id="fFechaOrdenPagoh" value="<?=$fFechaOrdenPagoh?>" <?=$dFechaOrdenPago?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
		<td align="right">Montos: </td>
		<td>
			<input type="checkbox" <?=$cMontoTotal?> onclick="chkFiltro_2(this.checked, 'fMontoTotald', 'fMontoTotalh');" />
			<input type="text" name="fMontoTotald" id="fMontoTotald" value="<?=$fMontoTotald?>" <?=$dMontoTotal?> style="width:60px;" />-
            <input type="text" name="fMontoTotalh" id="fMontoTotalh" value="<?=$fMontoTotalh?>" <?=$dMontoTotal?> style="width:60px;" />
        </td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
			<input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
			<select name="fordenar" id="fordenar" style="width:200px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-ORDEN-PAGO", $fordenar, 0)?>
			</select>
		</td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="FlagPagoDiferido" id="FlagPagoDiferido" value="S" <?=$cFlagPagoDiferido?> /> Pago Diferido</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_pago_modificar', 'gehen.php?anz=ap_orden_pago_form&opcion=modificar', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_orden_pago_form&opcion=ver', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_pago_anular', 'gehen.php?anz=ap_orden_pago_form&opcion=anular', 'SELF', '');" />
            
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="cargarOpcion2(this.form, 'ap_orden_pago_pdf.php?', 'BLANK', 'height=800, width=750, left=200, top=200, resizable=no', $('#registro').val());" /> | 
            
			<input type="button" id="btPrepago" value="Generar Pre-Pago" style="width:125px; <?=$btPrepago?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_orden_pago_prepago&accion=prepago', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2500" class="tblLista">
	<thead>
		<th width="25" scope="col">Sist.</th>
		<th width="100" scope="col">Nro. Orden</th>
		<th scope="col">Pagar A</th>
		<th width="100" scope="col">Doc. Fiscal</th>
		<th width="150" scope="col">Nro. Documento</th>
		<th width="100" scope="col">Total a Pagar</th>        
		<th width="90" scope="col">Fecha Venc.</th>
		<th width="90" scope="col">Fecha Prog. Pago</th>
		<th width="100" scope="col">Cta. Bancaria</th>
		<th width="125" scope="col">Tipo Pago</th>
		<th width="100" scope="col">Voucher</th>
		<th width="100" scope="col">Nro. Registro</th>
		<th scope="col">Proveedor</th>
		<th width="80" scope="col">Estado</th>
		<th width="90" scope="col">Fecha Orden</th>
		<th width="50" scope="col">Pago Dif.</th>
		<th width="60" scope="col">C.Costo</th>
    </thead>
    
    <tbody>
	<?php
	
	//	consulto todos
	$sql = "SELECT
				op.Anio,
				op.CodOrganismo,
				op.NroOrden
			FROM
				ap_ordenpago op
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = op.CodProveedor)
				INNER JOIN mastpersonas p2 ON (p2.CodPersona = op.CodProveedorPagar)
				INNER JOIN masttipopago tp ON (tp.CodTipoPago = op.CodTipoPago)
				INNER JOIN ap_ctabancaria cb ON (cb.NroCuenta = op.NroCuenta)
			WHERE 1 $filtro
			ORDER BY $fordenar";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				op.Anio,
				op.CodOrganismo,
				op.NroOrden,
				op.CodAplicacion,
				op.NomProveedorPagar,
				op.CodTipoDocumento,
				op.NroDocumento,
				op.MontoTotal,
				op.FechaVencimiento,
				op.FechaProgramada,
				op.NroCuenta,
				op.Voucher,
				op.NroRegistro,
				op.Estado,
				op.FechaOrdenPago,
				op.FlagPagoDiferido,
				op.CodCentroCosto,
				p1.NomCompleto AS NomProveedor,
				p1.DocFiscal AS DocFiscalProveedor,
				p2.DocFiscal AS DocFiscalPagarA,
				tp.TipoPago
			FROM
				ap_ordenpago op
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = op.CodProveedor)
				INNER JOIN mastpersonas p2 ON (p2.CodPersona = op.CodProveedorPagar)
				INNER JOIN masttipopago tp ON (tp.CodTipoPago = op.CodTipoPago)
				INNER JOIN ap_ctabancaria cb ON (cb.NroCuenta = op.NroCuenta)
			WHERE 1 $filtro
			ORDER BY $fordenar
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodAplicacion']?></td>
			<td align="center"><?=$field['NroOrden']?></td>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td><?=$field['DocFiscalPagarA']?></td>
			<td align="center"><?=$field['CodTipoDocumento']?>-<?=$field['NroDocumento']?></td>
			<td align="right"><strong><?=number_format($field['MontoTotal'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaVencimiento'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaProgramada'])?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td><?=($field['TipoPago'])?></td>
			<td align="center"><?=$field['Voucher']?></td>
			<td align="center"><?=$field['NroRegistro']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=printValores("ESTADO-ORDEN-PAGO", $field['Estado'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaOrdenPago'])?></td>
			<td align="center"><?=printFlag($field['FlagPagoDiferido'])?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
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
        	<? //paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>