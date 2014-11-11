<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
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
//	------------------------------
$filtro = "";
$i = 0;
$detalle = split(";", $detalles);
foreach ($detalle as $registro) {	$i++;
	list($codorganismo, $nroorden) = SPLIT( '[|]', $registro);
	if ($i==1) $filtro .= "(op.CodOrganismo = '".$codorganismo."' AND op.NroOrden = '".$nroorden."')";
	else $filtro .= " OR (op.CodOrganismo = '".$codorganismo."' AND op.NroOrden = '".$nroorden."')";
}
//	------------------------------
//	consulto los montos por cuenta bancaria
$sql = "SELECT
			op.NroCuenta,
			SUM(op.MontoTotal) AS MontoTotal,
			cbb.SaldoActual
		FROM
			ap_ordenpago op
			INNER JOIN ap_ctabancariabalance cbb ON (op.NroCuenta = cbb.NroCuenta)
		WHERE 1 AND ($filtro)
		GROUP BY NroCuenta";
$query_balance = mysql_query($sql) or die($sql.mysql_error());
while($field_balance = mysql_fetch_array($query_balance)) {
	if ($field_balance['MontoTotal'] > $field_balance['SaldoActual']) {
		$btSubmit = "display:none;";
	} else {
		$divMsj = "display:none;";
	}
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Confirmar/Rechazar Pre-Pago</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<center><div style="background-color: #FFFFB0; color: #BB0000; width: 1100px; height: 25px; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divMsj?>">
	Se encontraron Obligaciones sin Disponibilidad Financiera en Banco para Generar el Pre-Pago
</div></center>

<div class="divBorder" style="width:1100px;">
<table width="100%" class="tblFiltro">
    <tr>
        <td align="right" width="125">Fecha de Proceso:</td>
        <td>
        	<input type="text" name="fproceso" id="fproceso" value="<?=date("d-m-Y")?>" style="width:100px;" disabled="disabled" />
		</td>
        <td align="right">
        	<input type="button" style="width:75px; <?=$btSubmit?>" value="Aceptar" onclick="generarPrepago();" />
        	<input type="button" style="width:75px;" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
		</td>
    </tr>
</table>
</div>

<table width="1105" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Detallado x Obligaciones</a></li>
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Total x Cuentas Bancarias</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="ap_ordenes_pago_listar.php" method="POST" onsubmit="return verificarOrdenPago(this, 'ACTUALIZAR');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fcodproveedor" id="fcodproveedor" value="<?=$fcodproveedor?>" />
<input type="hidden" name="fnomproveedor" id="fnomproveedor" value="<?=$fnomproveedor?>" />
<input type="hidden" name="ftdoc" id="ftdoc" value="<?=$ftdoc?>" />
<input type="hidden" name="fsfuente" id="fsfuente" value="<?=$fsfuente?>" />
<input type="hidden" name="fndoc" id="fndoc" value="<?=$fndoc?>" />
<input type="hidden" name="fbanco" id="fbanco" value="<?=$fbanco?>" />
<input type="hidden" name="fprogramadad" id="fprogramadad" value="<?=$fprogramadad?>" />
<input type="hidden" name="fprogramadah" id="fprogramadah" value="<?=$fprogramadah?>" />
<input type="hidden" name="fctabancaria" id="fctabancaria" value="<?=$fctabancaria?>" />
<input type="hidden" name="fprocesod" id="fprocesod" value="<?=$fprocesod?>" />
<input type="hidden" name="fprocesoh" id="fprocesoh" value="<?=$fprocesoh?>" />
<input type="hidden" name="fmontosd" id="fmontosd" value="<?=$fmontosd?>" />
<input type="hidden" name="fmontosh" id="fmontosh" value="<?=$fmontosh?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="chkflagpago" id="chkflagpago" value="<?=$chkflagpago?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" id="detalles" value="<?=$detalles?>" />
</form>

<div id="tab1" style="display:block;">
<form name="frmdetallado" id="frmdetallado">
<input type="hidden" id="seldetallado" />
<div style="width:1100px" class="divFormCaption">Detallado x Obligaciones</div>
<table width="1100" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" value="Saldo de Bancos" onclick="verSaldoBancos(this.form, document.getElementById('seldetallado').value)" />
            <input type="button" value="Imprimir" />
        </td>
    </tr>
</table>

<table align="center"><tr><td><div style="overflow:scroll; width:1100px; height:250px;">
<table width="2000" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="200">Tipo de Pago</th>
        <th scope="col">Pagar A</th>
        <th scope="col" width="75">Proveedor</th>
        <th scope="col" width="100">Total a Pagar</th>
        <th scope="col" width="150">Nro. Documento</th>
        <th scope="col" width="125">Doc. Fiscal</th>
        <th scope="col" width="100">Imponible</th>
        <th scope="col" width="100">No Afecto</th>
        <th scope="col" width="100">Monto Impuesto</th>
        <th scope="col" width="100">Monto Retenido</th>
        <th scope="col" width="100">Total Obligaci&oacute;n</th>
        <th scope="col" width="100">Monto Adelantos</th>
        <th scope="col" width="100">Monto Pago Parcial</th>
        <th scope="col" width="75">Fecha Documento</th>
        <th scope="col" width="125">Doc. Relacionado</th>
    </tr>
    
    <tbody id="listaDetallado">
    <?php
	//	consulto todas las ordenes seleccionadas
	$sql = "SELECT
				op.*,
				o.MontoObligacion,
				o.MontoAfecto,
				o.MontoNoAfecto,
				o.MontoImpuesto,
				o.MontoImpuestoOtros,
				o.MontoAdelanto,
				o.MontoPagoParcial,
				o.FechaDocumento,
				o.ReferenciaTipoDocumento,
				o.ReferenciaNroDocumento,
				tp.TipoPago,
				mp.DocFiscal,
				cb.Descripcion AS NomCuenta,
				cb.CtaBanco,
				cb.CodBanco,
				b.Banco,
				mo.Organismo
			FROM
				ap_ordenpago op
				INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
												 op.CodTipoDocumento = o.CodTipoDocumento AND
												 op.NroDocumento = o.NroDocumento)
				INNER JOIN masttipopago tp ON (tp.CodTipoPago = op.CodTipoPago)
				INNER JOIN mastpersonas mp ON (op.CodProveedor = mp.CodPersona)
				INNER JOIN mastorganismos mo ON (op.CodOrganismo = mo.CodOrganismo)
				LEFT JOIN ap_ctabancaria cb ON (op.NroCuenta = cb.NroCuenta)
				LEFT JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE 1 AND ($filtro)
			ORDER BY CodBanco";
	$query_detallado = mysql_query($sql) or die($sql.mysql_error());
	while($field_detallado = mysql_fetch_array($query_detallado)) {
		$id = $field_detallado['CodOrganismo']."|".$field_detallado['NroOrden']."|".$field_detallado['NroCuenta']."|".$field_detallado['Anio'];
		if ($grupo1 != $field_detallado['CodOrganismo']) {
			$grupo1 = $field_detallado['CodOrganismo'];
			$grupo2 = "";
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=($field_detallado['Organismo'])?></td>
            </tr>
            <?php			
		}
		
		if ($grupo2 != $field_detallado['NroCuenta']) {
			$grupo2 = $field_detallado['NroCuenta'];
			?>
            <tr class="trListaBody2">
                <td><?=($field_detallado['Banco'])?></td>
                <td>Cuenta: <?=$field_detallado['NroCuenta']?></td>
            </tr>
            <?php			
		}
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetallado');" id="<?=$id?>">
			<td><?=($field_detallado['TipoPago'])?></td>
			<td><?=($field_detallado['NomProveedorPagar'])?></td>
			<td align="center"><?=$field_detallado['CodProveedor']?></td>
			<td align="right"><strong><?=number_format($field_detallado['MontoTotal'], 2, ',', '.')?></strong></td>
			<td align="center"><?=$field_detallado['NroDocumento']?></td>
			<td><?=$field_detallado['DocFiscal']?></td>
			<td align="right"><?=number_format($field_detallado['MontoAfecto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoNoAfecto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoImpuesto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoImpuestoOtros'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoObligacion'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoAdelanto'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_detallado['MontoPagoParcial'], 2, ',', '.')?></td>
			<td align="center"><?=formatFechaDMA($field_detallado['FechaDocumento'])?></td>
			<td align="center"></td>
		</tr>
        <?php
	}
    ?>
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>

<div id="tab2" style="display:none;">
<form name="frmcuenta" id="frmcuenta">
<input type="hidden" id="selcuenta" />
<div style="width:1100px" class="divFormCaption">Total x Cuentas Bancarias</div>
<table width="1100" class="tblBotones">
    <tr>
        <td align="right">&nbsp;</td>
    </tr>
</table>

<table align="center"><tr><td><div style="overflow:scroll; width:1100px; height:250px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" colspan="2">Cuenta Bancaria</th>
        <th scope="col" width="125">Monto</th>
        <th scope="col" width="125">Disponible</th>
        <th scope="col" width="50"># Doc.</th>
    </tr>
    
    <tbody id="listaDetallado">
    <?php
	$grupo1 = "";
	$grupo2 = "";
	$sub_total = 0;
	$sub_cantidad = 0;
	//	consulto los montos por cuenta bancaria
	$sql = "SELECT
				SUM(op.MontoTotal) AS MontoTotal,
				op.CodOrganismo,				
				op.NroCuenta,
				op.NroOrden,
				cb.Descripcion AS NomCuenta,
				cb.CtaBanco,
				cb.CodBanco,
				cbb.SaldoActual,
				b.Banco,
				mo.Organismo,
				(SELECT COUNT(*)
				 FROM ap_ordenpago
				 WHERE
				 	CodOrganismo = op.CodOrganismo AND
					NroOrden = op.NroOrden AND
					NroCuenta = op.NroCuenta) AS CantidadDocumentos
			FROM
				ap_ordenpago op
				INNER JOIN mastorganismos mo ON (op.CodOrganismo = mo.CodOrganismo)
				INNER JOIN ap_ctabancaria cb ON (op.NroCuenta = cb.NroCuenta)
				INNER JOIN ap_ctabancariabalance cbb ON (cb.NroCuenta = cbb.NroCuenta)
				INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE 1 AND ($filtro)
			GROUP BY CodOrganismo, CodBanco, NroCuenta
			ORDER BY CodBanco";
	$query_cuenta = mysql_query($sql) or die($sql.mysql_error());	$i=0;
	while($field_cuenta = mysql_fetch_array($query_cuenta)) {		$i++;
		if ($grupo1 != $field_cuenta['CodOrganismo']) {
			$grupo1 = $field_cuenta['CodOrganismo'];
			$grupo2 = "";
			?>
            <tr class="trListaBody2">
                <td colspan="5"><?=($field_cuenta['Organismo'])?></td>
            </tr>
            <?php			
		}
		
		if ($grupo2 != $field_detallado['NroCuenta']) {
			$grupo2 = $field_detallado['NroCuenta'];
			if ($i > 1) {
				?>
				<tr class="trListaBody2">
					<td colspan="3"></td>
					<td align="right"><strong><?=number_format($sub_total, 2, ',', '.')?></strong></td>
				</tr>
				<?php
			}
		}
		?>
        <tr class="trListaBody">
			<td align="center" width="125"><?=$field_cuenta['NroCuenta']?></td>
			<td><?=($field_cuenta['NomCuenta'])?></td>
			<td align="right"><strong><?=number_format($field_cuenta['MontoTotal'], 2, ',', '.')?></strong></td>
			<td align="right"><strong><?=number_format($field_cuenta['SaldoActual'], 2, ',', '.')?></strong></td>
			<td align="center"><?=$field_cuenta['CantidadDocumentos']?></td>
		</tr>
        <?php
		$sub_total += $field_cuenta['MontoTotal'];
		$sub_cantidad += $field_cuenta['CantidadDocumentos'];
		$sub_disponible += $field_cuenta['SaldoActual'];
	}
    ?>
    <tr class="trListaBody2">
        <td colspan="2"></td>
        <td align="right"><strong><?=number_format($sub_total, 2, ',', '.')?></strong></td>
        <td align="right"><strong><?=number_format($sub_disponible, 2, ',', '.')?></strong></td>
        <td align="center"><strong><?=$sub_cantidad?></strong></td>
    </tr>
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>

</body>
</html>
