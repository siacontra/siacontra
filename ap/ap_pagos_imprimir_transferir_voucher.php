<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body>
<?php
include("fphp_ap.php");
connect();
//	--------------------------------------
$_CCOSTOVOUCHER = getParametro("CCOSTOVOUCHER");
$sql = "SELECT
			cc.CodCentroCosto,
			cc.CodDependencia,
			cc.Descripcion,
			d.CodOrganismo
		FROM
			ac_mastcentrocosto cc
			INNER JOIN mastdependencias d ON (cc.CodDependencia = d.CodDependencia)
		WHERE cc.CodCentroCosto = '".$_CCOSTOVOUCHER["CCOSTOVOUCHER"]."'";
$query_cc = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_cc) != 0) $field_cc = mysql_fetch_array($query_cc);
//	--------------------------------------
/*$codproveedor = "000163";
$nroproceso = "000002";
$codtipopago = "01";
$nrocuenta = "010200012098";*/
$sql = "SELECT
			p.CodOrganismo,
			p.CodProveedor,
			p.NroPago,
			p.CodTipoPago,
			p.MontoPago,
			p.NroOrden,
			op.Concepto,
			op.CodCentroCosto,
			op.CodTipoDocumento,
			op.NroDocumento,
			o.CodCuenta AS CodCuentaPago,
			o.Comentarios,
			td.CodVoucher,
			td.Descripcion AS NomCuenta,
			td.FlagProvision,
			b.Banco,
			(SELECT PrefVoucherPA FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS Voucher,
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
			p.CodProveedor = '".$codproveedor."' AND
			p.NroProceso = '".$nroproceso."' AND
			p.CodTipoPago = '".$codtipopago."' AND
			p.NroCuenta = '".$nrocuenta."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return generarVoucherPago(this);">
<input type="hidden" id="nroproceso" value="<?=$nroproceso?>" />
<input type="hidden" id="codtipopago" value="<?=$codtipopago?>" />
<input type="hidden" id="nrocuenta" value="<?=$nrocuenta?>" />
<input type="hidden" id="codproveedor" value="<?=$codproveedor?>" />
<input type="hidden" id="codsistemafuente" value="<?=$field['CodSistemaFuente']?>" />
<input type="hidden" id="codproveedor" value="<?=$field['CodProveedor']?>" />
<input type="hidden" id="nroorden" value="<?=$field['NroOrden']?>" />
<input type="hidden" id="CodDependencia" value="<?=$field_cc['CodDependencia']?>" />

<table align="center">
	<tr>
    	<td valign="top">
            <table width="400" class="tblBotones">
                <tr><td align="right">&nbsp;</td></tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:400px; height:125px;">
            <table width="400" class="tblLista">
                <tr class="trListaHead">
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Fecha</th>
                    <th width="75" scope="col">Status</th>
                    <th scope="col">Organismo</th>
                </tr>
                
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
                <tr class="trListaHead">
                    <th width="50" scope="col">Linea</th>
                    <th scope="col">Errores Encontrados</th>
                    <th width="75" scope="col">Periodo</th>
                    <th width="75" scope="col">Voucher</th>
                    <th width="75" scope="col">Organismo</th>
                </tr>
                
                <tbody id="lista21">
                </tbody>
                
                <tfoot id="foot21">
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
                    <td class="tagForm" width="125">Organismo:</td>
                    <td>
                        <select id="organismo" style="width:300px;">
                            <?=getOrganismos($field['CodOrganismo'], 1);?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="descripcion" style="width:297px;" value="<?=($field['Comentarios'])?>" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Fecha:</td>
                    <td><input type="text" id="fecha" maxlength="10" value="<?=date('d-m-Y')?>" style="width:95px;" disabled="disabled" /></td>
                    <td class="tagForm">Preparado Por:</td>
                    <td>
                        <input type="hidden" id="codingresado" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Voucher:</td>
                    <td>
                        <select id="codvoucher">
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field['Voucher'], 1);?>
                        </select>
                        <input type="text" id="nrovoucher" style="width:50px;" disabled="disabled" />
                    </td>
                    <td class="tagForm">Aprobado Por:</td>
                    <td>
                        <input type="hidden" id="codaprobado" value="<?=$_SESSION['CODPERSONA_ACTUAL']?>" />
                        <input type="text" style="width:297px;" value="<?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?>" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Libro Contable:</td>
                    <td colspan="3">
                        <select id="libro_contable" style="width:150px;">
                            <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", "", 0)?>
                        </select>*
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
                        <input type="submit" class="btLista" value="Aceptar" />
                        <input type="button" class="btLista" value="Rechazar" onclick="javascript:window.close();" />
                    </td>
                </tr>
            </table>
            
            <table><tr><td><div style="overflow:scroll; width:960px; height:175px;">
            <table width="1000" class="tblLista">
                <tr class="trListaHead">
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="110">Cuenta</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="150">Documento</th>
                    <th scope="col" width="75">Fecha</th>
                    <th scope="col" width="125">Monto</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col">Descripci&oacute;n</th>
                </tr>
                
                <tbody>
                <?php
				//	impuestos
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
							oi1.CodTipoDocumento = o1.CodTipoDocumento AND
							oi1.NroDocumento = o1.NroDocumento)
							INNER JOIN mastimpuestos i1 ON (oi1.CodImpuesto = i1.CodImpuesto)
							INNER JOIN ac_mastplancuenta pc1 ON (i1.CodCuenta = pc1.CodCuenta)
						WHERE
							i1.FlagProvision = 'N' AND
							oi1.CodProveedor = '".$codproveedor."' AND
							oi1.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
							oi1.NroDocumento = '".$field['NroDocumento']."'
						GROUP BY i1.CodCuenta";
				$query_impuesto = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
				
				//	conceptos
				if ($field['FlagProvision'] == "N") {
					$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS Monto
							FROM ap_obligacionesimpuesto oi1
							WHERE
								oi1.CodProveedor = '".$codproveedor."' AND
								oi1.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								oi1.NroDocumento = '".$field['NroDocumento']."'";
					$query_impuesto2 = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_impuesto2) != 0) $field_impuesto2 = mysql_fetch_array($query_impuesto2);
				} else $field_impuesto2['Monto'] = 0.00;
				
				if ($field['FlagProvision'] == "S")
					$sql = "(SELECT
								cb.CodCuenta,
								o.ReferenciaTipoDocumento AS TipoOrden,
								o.ReferenciaNroDocumento AS NroOrden,
								pc.Descripcion AS NomCuenta,
								(o.MontoObligacion + ".abs(floatval($field_impuesto['Monto'])).") AS MontoVoucher,
								pc.TipoSaldo,
								'01' AS Orden,
								'Haber' AS Columna
							 FROM
								ap_obligaciones o
								INNER JOIN ap_ctabancaria cb ON (o.NroCuenta = cb.NroCuenta)
								INNER JOIN ac_mastplancuenta pc ON (cb.CodCuenta = pc.CodCuenta)
							 WHERE
								o.CodProveedor = '".$codproveedor."' AND
								o.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field['NroDocumento']."'
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
								oc.CodProveedor = '".$codproveedor."' AND
								oc.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field['NroDocumento']."'
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
								o.CodProveedor = '".$codproveedor."' AND
								o.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field['NroDocumento']."'
							 GROUP BY CodCuenta)
							
							ORDER BY CodCuenta";
				else
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
								o.CodProveedor = '".$codproveedor."' AND
								o.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field['NroDocumento']."'
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
								oc.CodProveedor = '".$codproveedor."' AND
								oc.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field['NroDocumento']."'
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
								o.CodProveedor = '".$codproveedor."' AND
								o.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								o.NroDocumento = '".$field['NroDocumento']."'
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
								oc.CodProveedor = '".$codproveedor."' AND
								oc.CodTipoDocumento = '".$field['CodTipoDocumento']."' AND
								oc.NroDocumento = '".$field['NroDocumento']."'
							 GROUP BY oc.CodCuenta)
							
							ORDER BY CodCuenta";
				
				$query_voucher = mysql_query($sql) or die ($sql.mysql_error());
				while($field_voucher = mysql_fetch_array($query_voucher)) {
					if ($field_voucher['Columna'] == "Haber") {
						$style = "style='color:red'";
						$monto = -abs($field_voucher['MontoVoucher']);
						$haber+=$monto;
					} else {
						$style = "";
						$monto = abs($field_voucher['MontoVoucher']);
						$debe+=$monto;
					}
					?>
					<tr class="trListaBody">
						<td align="center">
							<input type="hidden" name="_codcuenta" value="<?=$field_voucher['CodCuenta']?>" />
							<input type="hidden" name="_monto" value="<?=$monto?>" />
							<input type="hidden" name="_ccosto" value="<?=$field['CodCentroCosto']?>" />
							<input type="hidden" name="_comentarios" value="<?=$field_voucher['NomCuenta']?>" />
							<input type="hidden" name="_tsaldo" value="<?=$field_voucher['TipoSaldo']?>" />
							<?=++$linea?>
						</td>
						<td align="center" title="<?=($field_voucher['NomCuenta'])?>"><?=$field_voucher['CodCuenta']?></td>
						<td align="center"><?=$codproveedor?></td>
						<td align="center"><?=$field['NroDocumento']?></td>
						<td align="center"><?=date("d-m-Y")?></td>
						<td align="right"><span <?=$style?>><?=number_format($monto, 2, ',', '.')?></span></td>
						<td align="center"><?=$field['CodCentroCosto']?></td>
						<td><?=($field_voucher['NomCuenta'])?></td>
					</tr>
					<?
				}
				?>
                </tbody>
            </table>
            </div></td></tr></table>
            
            <table>
                <tr>
                    <th scope="col" width="140">Nro Lineas: <?=$linea?></th>
                    <th scope="col" width="75">&nbsp;</th>
                    <th scope="col" width="150">&nbsp;</th>
                    <th scope="col" width="75">Total:</th>
                    <th scope="col" width="125"><span><?=number_format($debe, 2, ',', '.')?></span></th>
                    <th scope="col" width="125"><span style="color:red;"><?=number_format($haber, 2, ',', '.')?></span></th>
                    <th scope="col" width="125">&nbsp;</th>
                </tr>
			</table>
            
        </td>
    </tr>
</table>
</form>
</body>
</html>
