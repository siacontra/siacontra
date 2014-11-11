<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------
extract($_POST);
extract($_GET);
//	--------------------------
include("fphp_ap.php");
connect();
//	--------------------------
list($nroproceso, $secuencia) = split("[.]", $registro);
$sql = "SELECT
			p.*,
			mp.NomCompleto AS NomProveedor,
			b.CodBanco,
			b.Banco
		FROM
			ap_pagos p
			INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
		WHERE
			p.NroProceso = '".$nroproceso."' AND
			p.Secuencia = '".$secuencia."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	--------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('codorganismo').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ver Pagos</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Informaci&oacute;n General</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Sustento del Pago</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada">
<table width="1000" class="tblForm">
    <tr>
    	<td colspan="4">
        	<table align="center" width="100%">
            	<tr>
                	<td colspan="4" class="divFormCaption" style="height:20px;">Informaci&oacute;n Adicional</td>
				</tr>
            	<tr>
                    <td class="tagForm" width="125">Organismo:</td>
                    <td>
                        <select id="codorganismo" style="width:300px;" disabled="disabled">
                            <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 1);?>
                        </select>
                    </td>
                    <td class="tagForm" width="125">Pagar A: </td>
                    <td>
                        <input type="hidden" id="codpagara" value="<?=$field['CodProveedor']?>" />
                        <input type="text" id="nompagara" value="<?=($field['NomProveedor'])?>" style="width:300px;" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Cta. Bancaria:</td>
                    <td>
                        <select id="nrocuenta" style="width:175px;" disabled="disabled">
                            <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field['NroCuenta'], 1);?>
                        </select>
                    </td>
                    <td class="tagForm">Pago:</td>
                    <td>
                        <input type="text" id="nroprepago" style="width:60px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['NroProceso']?>" disabled="disabled" />
                        <input type="text" id="secuencia" style="width:20px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['Secuencia']?>" disabled="disabled" /> - 
                        <input type="text" id="nropago" style="width:85px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['NroPago']?>" disabled="disabled" />
                    </td>
                </tr>
			</table>
        </td>
	</tr>
    <tr>
    	<td colspan="4">
        	<table align="center" width="100%">
            	<tr>
                	<td colspan="2" class="divFormCaption" style="height:20px;">Datos del pago</td>
                	<td colspan="2" class="divFormCaption">Estados del Pago</td>
                	<td colspan="2" class="divFormCaption">Contabilizaci&oacute;n</td>
				</tr>
            	<tr>
                	<td class="tagForm">Fecha de Pago:</td>
                	<td><input type="text" id="fpago" style="width:75px;" value="<?=formatFechaDMA($field['FechaPago'])?>" /></td>
                	<td class="tagForm">De Impresi&oacute;n:</td>
                	<td><input type="text" id="estado_impresion" style="width:75px;" value="<?=printValores("ESTADO-PAGO", $field['Estado'])?>" /></td>
                	<td class="tagForm">Contabilizado:</td>
                	<td><input type="text" id="contabilizado" style="width:20px;" value="<?=printValores("FLAG-CONTABILIZADO", $field['FlagContabilizacionPendiente'])?>" disabled="disabled" /></td>
                </tr>
            	<tr>
                	<td class="tagForm">Tipo de Pago</td>
                	<td>
                    	<select id="tpago" style="width:175px;">
							<?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field['CodTipoPago'], 1)?>
                        </select>
                    </td>
                	<td class="tagForm">De Entrega:</td>
                	<td><input type="text" id="estado_entrega" style="width:75px;" value="<?=printValores("ESTADO-CHEQUE", $field['EstadoEntrega'])?>" disabled="disabled" /></td>
                	<td class="tagForm">Voucher:</td>
                	<td>
                    	<input type="text" id="periodo" style="width:50px;" value="<?=$field['Periodo']?>" disabled="disabled" />-
                    	<input type="text" id="voucher" style="width:50px;" value="<?=$field['VoucherPago']?>" disabled="disabled" />
					</td>
                </tr>
            	<tr>
                	<td class="tagForm">Origen</td>
                	<td><input type="text" id="origen" style="width:75px;" value="<?=printValores("ORIGEN-PAGO", $field['OrigenGeneracion'])?>" disabled="disabled" /></td>
                	<td class="tagForm">Fecha de Entrega:</td>
                	<td><input type="text" id="fentrega" style="width:75px;" value="<?=formatFechaDMA($field['FechaEntregado'])?>" /></td>
                	<td class="divFormCaption" colspan="2" style="height:20px;">Inf. Adicional</td>
                </tr>
            	<tr>
                	<td class="tagForm">Monto Pago</td>
                	<td><input type="text" id="monto" style="width:125px; text-align:right; font-weight:bold; font-size:14px;" value="<?=number_format($field['MontoPago'], 2, ',', '.')?>" disabled="disabled" /></td>
                	<td class="tagForm">De Cobro:</td>
                	<td><input type="text" id="estado_cobro" style="width:75px;" value="<?=printValores("ESTADO-CHEQUE-COBRO", $field['FlagCobrado'])?>" disabled="disabled" /></td>
                	<td class="tagForm">&nbsp;</td>
                	<td>
                    	<input type="checkbox" id="flagnonegociable" <?=$flagnonegociable?> disabled="disabled" /> Cheque No Negociable
					</td>
                </tr>
            </table>
        </td>
	</tr>
    <tr>
    	<td colspan="4">
        	<table align="center" width="100%">
            	<tr>
                	<td colspan="4" class="divFormCaption" style="height:20px;">Anulaci&oacute;n / Reemplazo</td>
				</tr>
            	<tr>
                    <td class="tagForm" width="125">Fecha:</td>
                    <td><input type="text" id="fanulacion" style="width:75px;" value="<?=formatFechaDMA($field['FechaAnulacion'])?>" disabled="disabled" /></td>
                    <td class="tagForm" width="125">Voucher: </td>
                    <td>
                        <input type="text" id="anulacion_periodo" style="width:50px;" value="<?=$field['PeriodoAnulacion']?>" disabled="disabled" />-
                    	<input type="text" id="anulacion_voucher" style="width:50px;" value="<?=$field['VoucherAnulacion']?>" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td class="tagForm">Anulado Por:</td>
                    <td colspan="3"><input type="text" id="anuladopor" style="width:300px;" value="<?=($field['NomAnuladoPor'])?>" disabled="disabled" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Motivo:</td>
                    <td colspan="3"><input type="text" id="motivoanulacion" style="width:300px;" value="<?=($field['MotivoAnulacion'])?>" disabled="disabled" /></td>
                </tr>
                <tr>
                    <td class="tagForm">Reemplazado Por:</td>
                    <td colspan="3"><input type="text" id="reemplazadopor" style="width:300px;" value="<?=($field['NomReemplazadoPor'])?>" disabled="disabled" /></td>
                </tr>
                <tr>
                    <td class="tagForm">&Uacute;ltima Modif.:</td>
                    <td colspan="3">
                        <input name="ult_usuario" type="text" id="ult_usuario" value="<?=$field['UltimoUsuario']?>" size="30" disabled="disabled" />
                        <input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
                    </td>
                </tr>
			</table>
        </td>
	</tr>
</table>
<center>
<input type="button" value="Cancelar" style="width:75px;" onClick="window.close();" />
</center>
</form>
</div>

<div id="tab2" style="display:none;">
<div style="width:1000px" class="divFormCaption">Sustento del Pago</div>
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">Proveedor</th>
        <th scope="col" width="150">Documento</th>
        <th scope="col" width="100">Fecha</th>
        <th scope="col" width="100">Estado</th>
        <th scope="col" width="125">Monto Pagado</th>
        <th scope="col" width="125">Monto Retenci&oacute;n</th>
    </tr>
    
    <tbody id="listaDetalles">
    <?php
	$sql = "SELECT
				mp.NomCompleto As NomProveedor,
				o.CodTipoDocumento,
				o.NroDocumento,
				o.MontoObligacion,
				o.MontoImpuestoOtros,
				o.FechaRegistro,
				o.Estado
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
				INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND
											   p.NroOrden = op.NroOrden) 
				INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
												 op.CodTipoDocumento = o.CodTipoDocumento AND
												 op.NroDocumento = o.NroDocumento)
			WHERE
				p.NroProceso = '".$nroproceso."' AND
				p.Secuencia = '".$secuencia."'";
	$query_obligacion = mysql_query($sql) or die($sql.mysql_error());
	while($field_obligacion = mysql_fetch_array($query_obligacion)) {
		?>
        <tr class="trListaBody">
        	<td><?=($field_obligacion['NomProveedor'])?></td>
        	<td align="center"><?=$field_obligacion['CodTipoDocumento']?>-<?=$field_obligacion['NroDocumento']?></td>
        	<td align="center"><?=formatFechaDMA($field_obligacion['FechaRegistro'])?></td>
            <td align="center"><?=printValores("ESTADO-OBLIGACIONES", $field_obligacion['Estado'])?></td>
        	<td align="right"><strong><?=number_format($field_obligacion['MontoObligacion'], 2, ',', '.')?></strong></td>
        	<td align="right"><strong><?=number_format($field_obligacion['MontoImpuestoOtros'], 2, ',', '.')?></strong></td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
<input type="hidden" id="nrodetalles" value="<?=$nrodetalles?>" />
<input type="hidden" id="candetalles" value="<?=$nrodetalles?>" />
</form>
</div>

</body>
</html>