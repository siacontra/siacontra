<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_POST);
extract($_GET);
if ($origen == "admin") {
	list($organismo, $codproveedor, $tdoc, $nrofactura) = split("[.]", $registro);
}
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
/*$organismo = "0001";
$codproveedor = "000163";
$tdoc = "PV";
$nrofactura = "0200192012";*/
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
$sql = "SELECT 
			o.*,
			(o.MontoObligacion - o.MontoAdelanto) AS MontoPagar,
			(o.MontoObligacion - o.MontoAdelanto - MontoPagoParcial) AS MontoPendiente,
			mp3.CodPersona AS CodIngresadoPor,
			mp3.NomCompleto AS NomIngresadoPor,
			cc.Abreviatura AS NomCentroCosto,
			td.CodVoucher,
			(SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP') AS CodSistemaFuente
		FROM 
			ap_obligaciones o
			INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
			INNER JOIN mastpersonas mp2 ON (o.CodProveedorPagar = mp2.CodPersona)
			INNER JOIN mastpersonas mp3 ON (o.IngresadoPor = mp3.CodPersona)
			INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			LEFT JOIN ac_mastcentrocosto cc ON (cc.CodCentroCosto = o.CodCentroCosto)
		WHERE 
			o.CodOrganismo = '".$organismo."' AND
			o.CodProveedor = '".$codproveedor."' AND
			o.CodTipoDocumento = '".$tdoc."' AND
			o.NroDocumento = '".$nrofactura."'";
$query_obligacion = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_obligacion) != 0) $field_obligacion = mysql_fetch_array($query_obligacion);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="POST" onsubmit="return generarVoucher(this);">
<input type="hidden" id="codproveedor" value="<?=$codproveedor?>" />
<input type="hidden" id="tdoc" value="<?=$tdoc?>" />
<input type="hidden" id="nrofactura" value="<?=$nrofactura?>" />
<input type="hidden" id="codsistemafuente" value="<?=$field_obligacion['CodSistemaFuente']?>" />
<input type="hidden" id="voucher" value="<?=$field_obligacion['CodVoucher']?>" />
<input type="hidden" id="comentarios" value="<?=$field_obligacion['ComentariosAdicional']?>" />
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
                            <?=getOrganismos($organismo, 1);?>
                        </select>
                    </td>
                    <td class="tagForm">Descripci&oacute;n:</td>
                    <td><input type="text" id="descripcion" style="width:297px;" value="<?=($field_obligacion['Comentarios'])?>" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Fecha:</td>
                    <td><input type="text" id="fecha" maxlength="10" value="<?=date("d-m-Y")?>" style="width:95px;" disabled="disabled" /></td>
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
                            <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_obligacion['CodVoucher'], 1);?>
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
            <table width="1000" class="tblLista" id="tblListaDis">
                <tr class="trListaHead">
                    <th scope="col" width="30">#</th>
                    <th scope="col" width="75">Cuenta</th>
                    <th scope="col" width="75">Persona</th>
                    <th scope="col" width="125">Documento</th>
                    <th scope="col" width="75">Fecha</th>
                    <th scope="col" width="100">Monto</th>
                    <th scope="col" width="75">C.Costo</th>
                    <th scope="col">Descripci&oacute;n</th>
                </tr>
                
                <tbody id="listaVouchers">
                <?
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
							oc.CodProveedor = '".$codproveedor."' AND
							oc.CodTipoDocumento = '".$tdoc."' AND
							oc.NroDocumento = '".$nrofactura."'
						 GROUP BY CodCuenta)
						
						UNION
						
						(SELECT
							(SELECT CodCuenta FROM mastimpuestos WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS CodCuenta,
							oc.ReferenciaTipoDocumento AS TipoOrden,
							oc.ReferenciaNroDocumento AS NroOrden,
							(SELECT pc2.Descripcion
							 FROM
							 	mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS NomCuenta,
							oc.MontoImpuesto AS MontoVoucher,
							(SELECT pc2.TipoSaldo
							 FROM
							 	mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS TipoSaldo,
							'02' AS Orden,
							'Debe' AS Columna
						 FROM ap_obligaciones oc
						 WHERE
						 	(SELECT FlagProvision FROM mastimpuestos WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') = 'N' AND
							oc.CodProveedor = '".$codproveedor."' AND
							oc.CodTipoDocumento = '".$tdoc."' AND
							oc.NroDocumento = '".$nrofactura."' AND
							oc.MontoImpuesto > 0
						 GROUP BY CodCuenta)
						
						UNION
						
						(SELECT
							i.CodCuenta,
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
							INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
							INNER JOIN ac_mastplancuenta pc ON (i.CodCuenta = pc.CodCuenta)
						 WHERE
						 	i.FlagProvision = 'N' AND
							oc.CodProveedor = '".$codproveedor."' AND
							oc.CodTipoDocumento = '".$tdoc."' AND
							oc.NroDocumento = '".$nrofactura."'
						 GROUP BY i.CodCuenta)
						
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
							oc.CodProveedor = '".$codproveedor."' AND
							oc.CodTipoDocumento = '".$tdoc."' AND
							oc.NroDocumento = '".$nrofactura."'
						 GROUP BY oc.CodCuenta)
						
						ORDER BY CodCuenta";
				$query_voucher_det = mysql_query($sql) or die ($sql.mysql_error());	$linea=0;
                while($field_voucher_det = mysql_fetch_array($query_voucher_det)) {	$linea++;					
					if ($field_voucher_det['Orden'] == "01") {
						$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS MontoRetencion
								FROM
									ap_obligacionesimpuesto oi1
									INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
																	  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
																	  oi1.NroDocumento = o1.NroDocumento)
									INNER JOIN mastimpuestos i1 ON (oi1.CodImpuesto = i1.CodImpuesto)
									INNER JOIN ac_mastplancuenta pc1 ON (i1.CodCuenta = pc1.CodCuenta)
								WHERE
									i1.FlagProvision = 'P' AND
									oi1.CodProveedor = '".$codproveedor."' AND
									oi1.CodTipoDocumento = '".$tdoc."' AND
									oi1.NroDocumento = '".$nrofactura."'
								GROUP BY i1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
						$query_orden1 = mysql_query($sql) or die ($sql.mysql_error());
						if (mysql_num_rows($query_orden1) != 0) $field_orden1 = mysql_fetch_array($query_orden1);
						$monto_voucher = $field_voucher_det['MontoVoucher'] + $field_orden1['MontoRetencion'];
					} else $monto_voucher = $field_voucher_det['MontoVoucher'];
					
					if ($field_voucher_det['Columna'] == "Haber") {
						$style = "style='color:red'";
						$monto = -$monto_voucher;
						$haber += $monto;
					} else {
						$style = "";
						$monto = $monto_voucher;
						$debe += $monto;
					}
					
					?>
					<tr class="trListaBody">
						<td align="center">
							<input type="hidden" name="_codcuenta" value="<?=$field_voucher_det['CodCuenta']?>" />
							<input type="hidden" name="_monto" value="<?=$monto?>" />
							<input type="hidden" name="_ccosto" value="<?=$field_obligacion['CodCentroCosto']?>" />
							<input type="hidden" name="_comentarios" value="<?=$field_voucher_det['NomCuenta']?>" />
							<input type="hidden" name="_tiposaldo" value="<?=$field_voucher_det['TipoSaldo']?>" />
							<input type="hidden" name="_torden" value="<?=$field_voucher_det['TipoOrden']?>" />
							<input type="hidden" name="_norden" value="<?=$field_voucher_det['NroOrden']?>" />
							<?=$linea?>
						</td>
						<td align="center" title="<?=($field_voucher_det['NomCuenta'])?>"><?=$field_voucher_det['CodCuenta']?></td>
						<td align="center"><?=$codproveedor?></td>
						<td align="center"><?=$nrofactura?></td>
						<td align="center"><?=date("d-m-Y")?></td>
						<td align="right"><span <?=$style?>><?=number_format($monto, 2, ',', '.')?></span></td>
						<td align="center"><?=$field_obligacion['CodCentroCosto']?></td>
						<td><?=($field_voucher_det['NomCuenta'])?></td>
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
