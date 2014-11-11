<?php
//	consulto datos generales de la transaccion
list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento, $CodVoucher) = split("[.]", $registro);
$sql = "SELECT 
			o.*,
			(o.MontoObligacion - o.MontoAdelanto) AS MontoPagar,
			(o.MontoObligacion - o.MontoAdelanto - MontoPagoParcial) AS MontoPendiente,
			mp3.CodPersona AS CodIngresadoPor,
			mp3.NomCompleto AS NomIngresadoPor,
			cc.Abreviatura AS NomCentroCosto
		FROM 
			ap_obligaciones o
			INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
			INNER JOIN mastpersonas mp2 ON (o.CodProveedorPagar = mp2.CodPersona)
			INNER JOIN mastpersonas mp3 ON (o.IngresadoPor = mp3.CodPersona)
			INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			LEFT JOIN ac_mastcentrocosto cc ON (cc.CodCentroCosto = o.CodCentroCosto)
		WHERE 
			o.CodOrganismo = '".$CodOrganismo."' AND
			o.CodProveedor = '".$CodProveedor."' AND
			o.CodTipoDocumento = '".$CodTipoDocumento."' AND
			o.NroDocumento = '".$NroDocumento."'";
			
			
$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_mast) != 0) $field_mast = mysql_fetch_array($query_mast);

//	consulto si el periodo esta abierto
$sql = "SELECT Estado
		FROM ac_controlcierremensual
		WHERE
			TipoRegistro = 'AB' AND
			CodOrganismo = '".$field_mast['CodOrganismo']."' AND
			Periodo = '".substr($field_mast['FechaAprobado'], 0, 7)."'";
			
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

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return generar_vouchers(this, 'provision');">
<input type="hidden" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" id="CodProveedor" value="<?=$CodProveedor?>" />
<input type="hidden" id="CodTipoDocumento" value="<?=$CodTipoDocumento?>" />
<input type="hidden" id="NroDocumento" value="<?=$NroDocumento?>" />
<input type="hidden" id="Periodo" value="<?=substr($field_mast['FechaAprobado'], 0, 7)?>" />
<input type="hidden" id="PeriodoEstado" value="<?=($field_periodo['Estado'])?>" />
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
                        <select id="CodOrganismo" style="width:300px;">
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_mast['CodOrganismo'], 1)?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="ComentariosVoucher" style="width:297px;" value="<?=($field_mast['Comentarios'])?>" /></td>
                </tr>
                <tr>
                    <td class="tagForm">* Fecha:</td>
                    <td><input type="text" id="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaAprobado'])?>" style="width:75px;" disabled /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="PreparadoPor" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" disabled />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
                        <select id="CodVoucher">
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $CodVoucher, 1)?>
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
                        <select id="CodLibroCont" style="width:150px;">
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
                        <input type="submit" class="btLista" value="Aceptar" id="btAceptar" />
                        <input type="button" class="btLista" value="Rechazar" onclick="javascript:window.close();" />
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
				$sql = "(SELECT
							td.CodCuentaProv AS CodCuenta,
							oc.ReferenciaTipoDocumento AS TipoOrden,
							oc.ReferenciaNroDocumento AS NroOrden,
							pc.Descripcion AS NomCuenta,
							(oc.MontoObligacion) AS MontoVoucher,
							pc.TipoSaldo,
							'01' AS Orden,
							'Haber' AS Columna
						 FROM
							ap_obligaciones oc
							INNER JOIN ap_tipodocumento td ON (oc.CodTipoDocumento = td.CodTipoDocumento)
							INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						 GROUP BY CodCuenta)
						UNION
						(SELECT
							(SELECT CodCuenta FROM mastimpuestos WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
							oc.ReferenciaTipoDocumento AS TipoOrden,
							oc.ReferenciaNroDocumento AS NroOrden,
							(SELECT pc2.Descripcion
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
							oc.MontoImpuesto AS MontoVoucher,
							(SELECT pc2.TipoSaldo
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
							'02' AS Orden,
							'Debe' AS Columna
						 FROM ap_obligaciones oc
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."' AND
							oc.MontoImpuesto > 0
						 GROUP BY CodCuenta)
						UNION
						(SELECT
							oc.CodCuenta,
							o.ReferenciaTipoDocumento AS TipoOrden,
							o.ReferenciaNroDocumento AS NroOrden,
							pc.Descripcion AS NomCuenta,
							ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
							pc.TipoSaldo,
							'03' AS Orden,
							'Haber' AS Columna
						 FROM
							ap_obligacionesimpuesto oc
							INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
															 oc.CodTipoDocumento = o.CodTipoDocumento AND
															 oc.NroDocumento = o.NroDocumento)
							INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
						 WHERE
							oc.FlagProvision = 'N' AND
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						 GROUP BY oc.CodCuenta)
						UNION
						(SELECT
							oc.CodCuenta,
							oc.TipoOrden,
							oc.NroOrden,
							pc.Descripcion AS NomCuenta,
							SUM(oc.Monto) AS MontoVoucher,
							pc.TipoSaldo,
							'04' AS Orden,
							'Debe' AS Columna
						 FROM
							ap_obligacionescuenta oc
							INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
						 WHERE
							oc.CodProveedor = '".$CodProveedor."' AND
							oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oc.NroDocumento = '".$NroDocumento."'
						 GROUP BY oc.CodCuenta)
						ORDER BY CodCuenta";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
				while($field_det = mysql_fetch_array($query_det)) {					
					if ($field_det['Orden'] == "01") {
						$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS MontoRetencion
								FROM
									ap_obligacionesimpuesto oi1
									INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
																	  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
																	  oi1.NroDocumento = o1.NroDocumento)
									INNER JOIN mastimpuestos i1 ON (oi1.CodImpuesto = i1.CodImpuesto)
									INNER JOIN ac_mastplancuenta pc1 ON (i1.CodCuenta = pc1.CodCuenta)
								WHERE
									oi1.FlagProvision = 'P' AND
									oi1.CodProveedor = '".$CodProveedor."' AND
									oi1.CodTipoDocumento = '".$CodTipoDocumento."' AND
									oi1.NroDocumento = '".$NroDocumento."'
								GROUP BY i1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
						$query_orden1 = mysql_query($sql) or die ($sql.mysql_error());
						if (mysql_num_rows($query_orden1) != 0) $field_orden1 = mysql_fetch_array($query_orden1);
						$Monto = $field_det['MontoVoucher'] + $field_orden1['MontoRetencion'];
					} else $Monto = $field_det['MontoVoucher'];
					
					if ($field_det['Columna'] == "Haber") {
						$style = " color:red;";
						$Monto = abs($Monto) * (-1);
						$Debitos += $Monto;
					} else {
						$style = "";
						$Monto = abs($Monto);
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
                        	<input type="text" name="CodPersona" value="<?=$CodProveedor?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="ReferenciaTipoDocumento" value="<?=$CodTipoDocumento?>" class="cell2" style="width:20px;" readonly /> - 
                        	<input type="text" name="ReferenciaNroDocumento" value="<?=$NroDocumento?>" class="cell2" style="width:120px;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="CodCentroCosto" value="<?=$_PARAMETRO['CCOSTOVOUCHER']?>" class="cell2" style="text-align:center;" readonly />
                        </td>
                    	<td>
                        	<input type="text" name="FechaVoucher" value="<?=formatFechaDMA($field_mast['FechaDocumento'])?>" class="cell2" style="width:75px; text-align:center;" readonly />
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

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	validarErroresVoucher();
});
</script>