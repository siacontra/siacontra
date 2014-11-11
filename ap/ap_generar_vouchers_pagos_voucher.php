<?php
if ($opcion == "ver") {
	$disabled_ver = "disabled";
	$display_ver = "display:none;";
}
//	consulto datos generales de la transaccion
list($NroProceso, $Secuencia) = split("[.]", $registro);
$sql = "SELECT
			p.CodOrganismo,
			p.CodProveedor,
			p.NroPago,
			p.CodTipoPago,
			p.MontoPago,
			p.NroOrden,
			p.Periodo,
			p.NroCuenta,
			p.FechaPago,
			op.Concepto,
			op.CodCentroCosto,
			op.CodTipoDocumento,
			op.NroDocumento,
			op.NroOrden,
			o.CodCuenta AS CodCuentaPago,
			o.Comentarios,
			td.Descripcion AS NomCuenta,
			td.FlagProvision,
			b.Banco,
			(SELECT PrefVoucherPA FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodVoucher,
			(SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodSistemaFuente
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
			INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."'";
$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);

//	consulto si el periodo esta abierto
$sql = "SELECT Estado
		FROM ac_controlcierremensual
		WHERE
			TipoRegistro = 'AB' AND
			CodOrganismo = '".$field_mast['CodOrganismo']."' AND
			Periodo = '".$field_mast['Periodo']."'";
$query_periodo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_periodo) != 0) $field_periodo = mysql_fetch_array($query_periodo);

//	sistema fuente
$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_prefpa) != 0) $field_prefpa = mysql_fetch_array($query_prefpa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return generar_vouchers(this, 'pagos', '<?=$imprimir?>');">
<input type="hidden" id="NroProceso" value="<?=$NroProceso?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="CodProveedor" value="<?=$field_mast['CodProveedor']?>" />
<input type="hidden" id="Periodo" value="<?=$field_mast['Periodo']?>" />
<input type="hidden" id="PeriodoEstado" value="<?=($field_periodo['Estado'])?>" />
<input type="hidden" id="CodTipoPago" value="<?=$field_mast['CodTipoPago']?>" />
<input type="hidden" id="NroCuenta" value="<?=$field_mast['NroCuenta']?>" />
<input type="hidden" id="CodDependencia" value="<?=$_SESSION['DEPENDENCIA_ACTUAL']?>" />
<input type="hidden" id="CodSistemaFuente" value="<?=$field_prefpa['CodSistemaFuente']?>" />
<table align="center">
	<tr>
    	<td valign="top">
            <table width="400" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:400px; height:125px;">
            <table width="400" class="tblLista">
            	<thead>
                <tr>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Fecha</th>
                    <th width="75" scope="col">Status</th>
                    <th scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista1">
                </tbody>
                
                <tfoot id="foot1">
                <tr><td colspan="5">&nbsp;</td></tr>
                <tr>
                    <th scope="col" colspan="4">&nbsp;</th>
                    <th scope="col" align="right">&nbsp;</th>
                </tr>
                </tfoot>
            </table>
            </div></td></tr></table>
        </td>
        
        <td valign="top">
            <table width="550" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:550px; height:125px;">
            <table width="100%" class="tblLista">
            	<thead>
                <tr>
                    <th width="50" scope="col">Linea</th>
                    <th scope="col">Errores Encontrados</th>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Organismo</th>
                </tr>
                </thead>
                
                <tbody id="lista_errores">
                </tbody>
                
                <tfoot id="foot_errores">
                	<tr><td colspan="5">&nbsp;</td></tr>
                	<tr>
                        <th scope="col" colspan="4">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
					</tr>
                </tfoot>
            </table>
            </div></td></tr></table>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2">
            <table width="960" class="tblForm">
                <tr>
                    <td class="tagForm" width="125">* Organismo:</td>
                    <td>
                        <select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?>>
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_mast['CodOrganismo'], 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="ComentariosVoucher" style="width:297px;" value="<?=($field_mast['Comentarios'])?>" <?=$disabled_ver?> /></td>
                </tr>
                <tr>
                    <td class="tagForm">* Fecha:</td>
                    <td><input type="text" id="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaPago'])?>" style="width:75px;" disabled /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="PreparadoPor" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
                        <select id="CodVoucher" <?=$disabled_ver?>>
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_mast['CodVoucher'], 1)?>
                        </select>
                        <input type="text" id="NroVoucher" style="width:50px;" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="AprobadoPor" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">* Libro Contable:</td>
                    <td colspan="3">
                        <select id="CodLibroCont" style="width:150px;" <?=$disabled_ver?>>
                            <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", "", 0)?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
	<tr>
    	<td valign="top" colspan="2">
            <table width="960" class="tblBotones">
                <tr>
                    <td align="right">
                        <input type="submit" class="btLista" value="Aceptar" id="btAceptar" style=" <?=$display_ver?>" />
                        <input type="button" class="btLista" value="Rechazar" onclick="javascript:window.close();" style=" <?=$display_ver?>" />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:960px; height:225px;">
            <table width="1100" class="tblLista">
            	<thead>
                <tr>
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="110">Cuenta</th>
                    <th scope="col">Descripci&oacute;n</th>
                    <th scope="col" width="125">Monto</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="150">Documento</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col" width="75">Fecha</th>
                </tr>
                </thead>
                
                <tbody>
                <?php
				//	impuestos que provisionan en el documento
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
															  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
															  oi1.NroDocumento = o1.NroDocumento)
						WHERE
							oi1.FlagProvision = 'N' AND
							oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
							oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
							oi1.NroDocumento = '".$field_mast['NroDocumento']."'
						GROUP BY oi1.FlagProvision";
//echo $sql;
				$query_impuesto = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
				
				//	impuestos que provisionan en el pago
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
															  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
															  oi1.NroDocumento = o1.NroDocumento)
						WHERE
							oi1.FlagProvision = 'P' AND
							oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
							oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
							oi1.NroDocumento = '".$field_mast['NroDocumento']."'
						GROUP BY oi1.FlagProvision";
						//echo $sql;//consulta para traer el monto de las retenciones
				$query_impuesto3 = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuesto3) != 0) $field_impuesto3 = mysql_fetch_array($query_impuesto3);
				
				//	si el tipo de documento no provisiona
				if ($field_mast['FlagProvision'] == "N") {
					$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
							FROM ap_obligacionesimpuesto oi1
							WHERE
								oi1.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oi1.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oi1.NroDocumento = '".$field_mast['NroDocumento']."'";
					$query_impuesto2 = mysql_query($sql) or die($sql.mysql_error());
					
					if (mysql_num_rows($query_impuesto2) != 0) $field_impuesto2 = mysql_fetch_array($query_impuesto2);
				} else $field_impuesto2['Monto'] = 0.00;
				
				if ($field_mast['FlagProvision'] == "S")
				{
					$sql = "(SELECT
								cb.CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion) AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta pc ON (cb.CodCuenta = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								i.CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
								pc.TipoSaldo,
								'02' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligacionesimpuesto oc
								INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
																 oc.CodTipoDocumento = o.CodTipoDocumento AND
																 oc.NroDocumento = o.NroDocumento)
								INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
								INNER JOIN ac_mastplancuenta pc ON (i.CodCuenta = pc.CodCuenta)
							 WHERE
								i.FlagProvision = 'P' AND
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY i.CodCuenta)
							 UNION
							(SELECT
								td.CodCuentaProv AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion + ".abs(floatval($field_impuesto3['Monto'])).") AS MontoVoucher,
								pc.TipoSaldo,
								'03' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
								INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							ORDER BY CodCuenta";
							
							
				} else {
					$sql = "(SELECT
								cb.CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion) AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta pc ON (cb.CodCuenta = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								i.CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
								pc.TipoSaldo,
								'02' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligacionesimpuesto oc
								INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
																 oc.CodTipoDocumento = o.CodTipoDocumento AND
																 oc.NroDocumento = o.NroDocumento)
								INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
								INNER JOIN ac_mastplancuenta pc ON (i.CodCuenta = pc.CodCuenta)
							 WHERE
								i.FlagProvision = 'P' AND
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY i.CodCuenta)
							UNION
							(SELECT
								td.CodCuentaProv AS CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion + ABS(o.MontoImpuestoOtros)) AS MontoVoucher,
								pc.TipoSaldo,
								'03' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
								INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$field_mast['CodProveedor']."' AND
								o.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY CodCuenta)
							UNION
							(SELECT
								oc.CodCuenta,
								oc.TipoOrden,
								oc.NroOrden,
								pc.Descripcion AS NomCuenta,
								(SUM(oc.Monto) - ".floatval(abs($field_impuesto['Monto']+$field_impuesto2['Monto'])).") AS MontoVoucher,
								pc.TipoSaldo,
								'04' AS Orden,
								'Debe' AS Columna
							 FROM
								ap_obligacionescuenta oc
								INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
							 WHERE
								oc.CodProveedor = '".$field_mast['CodProveedor']."' AND
								oc.CodTipoDocumento = '".$field_mast['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field_mast['NroDocumento']."'
							 GROUP BY oc.CodCuenta)
							ORDER BY CodCuenta";
							
				}
				
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
				while($field_det = mysql_fetch_array($query_det)) {
					if ($field_det['Columna'] == "Haber") {
						$style = " color:red;";
						$Monto = abs($field_det['MontoVoucher']) * (-1);
						$Debitos += $Monto;
					} else {
						$style = "";
						$Monto = abs($field_det['MontoVoucher']);
						$Creditos += $Monto;
					}
					?>
					<tr class="trListaBody">
                    	<td>
                        	<input type="text" name="Linea" value="<?=++$Linea?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCuenta" value="<?=$field_det['CodCuenta']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="hidden" name="Descripcion" value="<?=$field_mast['Comentarios']?>" />
                        	<input type="text" value="<?=$field_det['NomCuenta']?>" class="cell2" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="MontoVoucher" value="<?=number_format($Monto, 2, ',', '.')?>" class="cell2" style="text-align:right; <?=$style?>" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodPersona" value="<?=$field_mast['CodProveedor']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="ReferenciaTipoDocumento" value="OP" class="cell2" style="width:20px;" readonly /> - 
                        	<input type="text" name="ReferenciaNroDocumento" value="<?=$field_mast['NroOrden']?>" class="cell2" style="width:120px;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCentroCosto" value="<?=$_PARAMETRO['CCOSTOVOUCHER']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaPago'])?>" class="cell2" style="width:75px; text-align:center;" readonly />
                        </td>
					</tr>
					<?
				}
				?>
                </tbody>
            </table>
            </div></td></tr></table>
            
            <table>
                <tr>
                    <th scope="col" width="140">Nro Lineas: <input type="text" id="Lineas" value="<?=$Linea?>" class="cell2" style="text-align:center; font-weight:bold; font-size:12px; width:20px;" readonly /></th>
                    <th scope="col" width="75">&nbsp;</th>
                    <th scope="col" width="150">&nbsp;</th>
                    <th scope="col" width="75">Total:</th>
                    <th scope="col" width="125">
                    	<input type="text" id="Creditos" value="<?=number_format($Creditos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px;" readonly />
                    </th>
                    <th scope="col" width="125">
                    	<input type="text" id="Debitos" value="<?=number_format($Debitos, 2, ',', '.')?>" class="cell2" style="text-align:right; font-weight:bold; font-size:12px; color:red;" readonly />
					</th>
                    <th scope="col" width="125">&nbsp;</th>
                </tr>
			</table>
            
        </td>
    </tr>
</table>
</form>

<?php
if ($opcion != "ver") {
	?>
    <!-- JS	-->
    <script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        validarErroresVoucher();
    });
    </script>
    <?
}
?>
