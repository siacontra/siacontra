<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
//	------------------------------------
list($nroproceso, $secuencia) = split("[.]", $registro);
//	consulto el pago
$sql = "SELECT
			p.CodOrganismo,
			p.Periodo,
			p.VoucherPago
		FROM ap_pagos p
		WHERE
			p.NroProceso = '".$nroproceso."' AND
			p.Secuencia = '".$secuencia."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
$organismo = $field['CodOrganismo'];
$periodo = $field['Periodo'];
$voucher = $field['VoucherPago'];
//	------------------------------------
//	consulto voucher
$sql = "SELECT
			vm.*,
			p1.NomCompleto AS NomPreparadoPor
		FROM
			ac_vouchermast vm
			INNER JOIN mastpersonas p1 ON (vm.PreparadoPor = p1.CodPersona)
		WHERE
			vm.CodOrganismo = '".$organismo."' AND
			vm.Periodo = '".$periodo."' AND
			vm.Voucher = '".$voucher."'";
$query_mast = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);

//	consulto voucher
$sql = "SELECT *
		FROM ac_voucherdet
		WHERE
			CodOrganismo = '".$organismo."' AND
			Periodo = '".$periodo."' AND
			Voucher = '".$voucher."'
		ORDER BY Linea";
$query_det = mysql_query($sql) or die($sql.mysql_error());

//	valores
$codingresado = $field_mast['PreparadoPor'];
$nomingresado = $field_mast['NomPreparadoPor'];
$fecha = formatFechaDMA($field_mast['FechaVoucher']);
$estado = $field_mast['Estado'];
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/ac_fscript.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
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

<center>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ver Voucher</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="960" class="tblForm">
    <tr>
        <td width="125" class="tagForm">Periodo:</td>
        <td><input type="text" id="periodo" maxlength="7" value="<?=$periodo?>" style="width:65px; font-weight:bold;" disabled="disabled" />*</td>
        <td width="125" class="tagForm">Voucher:</td>
        <td>
            <select id="codvoucher" style="font-weight:bold;" disabled="disabled">
                <?=loadSelect("ac_voucher", "CodVoucher", "CodVoucher", $field_mast['CodVoucher'], 0);?>
            </select>
            <input type="text" id="nrovoucher" style="width:50px; font-weight:bold;" value="<?=$field_mast['NroVoucher']?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Organismo:</td>
        <td>
            <select id="organismo" style="width:300px;" onchange="getOptions(this.value, 'dependencia', '300');" disabled="disabled">
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $organismo, 0)?>
            </select>*
        </td>
        <td class="tagForm">Preparado Por:</td>
        <td>
            <input type="hidden" id="codingresado" value="<?=$codingresado?>" />
            <input type="text" style="width:297px;" value="<?=($nomingresado)?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Dependencia:</td>
        <td>
            <select id="dependencia" style="width:300px;" disabled="disabled">
            	<option value=""></option>
                <?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field_mast['CodDependencia'], 0)?>
            </select>
        </td>
        <td class="tagForm">Aprobado Por:</td>
        <td>
            <input type="hidden" id="codaprobado" value="<?=$field_mast['AprobadoPor']?>" />
            <input type="text" style="width:297px;" value="<?=($field_mast['NomAprobadoPor'])?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Libro Contable:</td>
        <td>
            <select id="libro_contable" style="width:150px;" disabled="disabled">
            	<option value=""></option>
                <?=loadSelect("ac_librocontable", "CodLibroCont", "Descripcion", $field_mast['CodLibroCont'], 0)?>
            </select>*
        </td>
        <td class="tagForm">Descripci&oacute;n:</td>
        <td><input type="text" id="descripcion" style="width:297px;" value="<?=($field_mast['ComentariosVoucher'])?>" disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">Fecha:</td>
        <td><input type="text" id="fecha" maxlength="10" value="<?=$fecha?>" style="width:65px;" disabled="disabled" /></td>
        <td class="tagForm">Nro. Interno:</td>
        <td><input type="text" id="nrointerno" maxlength="10" style="width:95px;" value="<?=$field_mast['NroInterno']?>" disabled="disabled" /></td>
    </tr>
    <tr>
    	<td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input type="text" size="30" value="<?=$field_mast['UltimoUsuario']?>" disabled="disabled" />
            <input type="text" size="25" value="<?=$field_mast['UltimaFecha']?>" disabled="disabled" />
        </td>
    </tr>
</table>
<br />

<table width="960" class="tblBotones">
	<tr>
    	<td>&nbsp;</td>
	</tr>
</table>

<div style="overflow:scroll; width:960px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="30">#</th>
        <th scope="col" width="75">Cuenta</th>
        <th scope="col" width="75">Persona</th>
        <th scope="col" width="125">Documento</th>
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="100">Monto</th>
        <th scope="col" width="75">C.Costo</th>
        <th scope="col">Descripci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalle">
    <?
	$nrodetalle = 0;
	$total_creditos = 0;
	$total_debitos = 0;
	if ($opcion != "nuevo") {
		while ($field_det = mysql_fetch_array($query_det)) {
			$nrodetalle++;
			if ($field_det['MontoVoucher'] < 0) { $total_creditos += $field_det['MontoVoucher']; $style_negativo = "color:#F00"; }
			else { $total_debitos += $field_det['MontoVoucher']; $style_negativo = ""; }
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_detalle');" id="detalle_<?=$nrodetalle?>">
				<td align="center"><?=$nrodetalle?></td>
				<td align="center">
					<input type="text" name="codcuenta" id="codcuenta_<?=$nrodetalle?>" value="<?=$field_det['CodCuenta']?>" class="cell2" style="text-align:center;" readonly />
					<input type="hidden" name="nomcuenta" id="nomcuenta_<?=$nrodetalle?>" />
				</td>
				<td align="center">
					<input type="text" name="codpersona" id="codpersona_<?=$nrodetalle?>" value="<?=$field_det['CodPersona']?>" class="cell2" style="text-align:center;" readonly />
					<input type="hidden" name="nompersona" id="nompersona_<?=$nrodetalle?>" value="<?=($field_det['NomPersona'])?>" />
				</td>
				<td align="center">
					<input type="text" name="documento" value="<?=$field_det['ReferenciaNroDocumento']?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" disabled="disabled" />
				</td>
				<td align="center">
					<input type="text" name="fecha" value="<?=formatFechaDMA($field_det['FechaVoucher'])?>" style="text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" disabled="disabled" />
				</td>
				<td align="center">
					<input type="text" name="monto" value="<?=number_format($field_det['MontoVoucher'], 2, ',', '.')?>" class="cell" style="text-align:right; font-weight:bold; <?=$style_negativo?>" onBlur="numeroBlur(this); this.className='cell'; setNegativo(this); sumar_voucher();" onFocus="numeroFocus(this); this.className='cellFocus';" disabled="disabled" />
				</td>
				<td align="center">
					<input type="text" name="codccosto" id="codccosto_<?=$nrodetalle?>" value="<?=$field_det['CodCentroCosto']?>" class="cell2" style="text-align:center;" readonly />
					<input type="hidden" name="nomccosto" id="nomccosto_<?=$nrodetalle?>" />
				</td>
				<td align="center">
					<input type="text" name="descripcion" value="<?=($field_det['Descripcion'])?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" disabled="disabled" />
				</td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
    
    <tfoot>
    	<tr><td colspan="8">&nbsp;</td></tr>
    	<tr>
        	<td colspan="5"></td>
            <td align="right"><span id="total_debitos" style="font-weight:bold; font-size:11px;"><?=number_format($total_debitos, 2, ',', '.')?></span></td>
        	<td colspan="2"></td>
        </tr>
    	<tr>
        	<td colspan="5"></td>
            <td align="right"><span id="total_creditos" style="font-weight:bold; font-size:11px; color:#F00;"><?=number_format($total_creditos, 2, ',', '.')?></span></td>
        	<td colspan="2"></td>
        </tr>
    </tfoot>
</table>
</div>
</center>

</body>
</html>