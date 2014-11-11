<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Transacciones de Cuentas Bancarias</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	if ($ffechah == "") $ffechah = date("d-m-Y");
	list($d, $m, $a) = SPLIT( '[-./]', $ffechah);
	$ffechad = "01-$m-$a";
}
//	-------------------------------
if ($fttransaccion != "") { $cttransaccion = "checked"; $filtro.=" AND (bt.CodTipoTransaccion = '".$fttransaccion."')"; } else $dttransaccion = "disabled";
if ($ffechad != "" || $ffechah != "") { 
	$cffecha = "checked"; 
	if ($ffechad != "") $filtro.=" AND (bt.FechaTransaccion >= '".formatFechaAMD($ffechad)."')";
	if ($ffechah != "") $filtro.=" AND (bt.FechaTransaccion <= '".formatFechaAMD($ffechah)."')";
} else $dffecha = "disabled";
//	-------------------------------
$sql = "SELECT
			cb.Descripcion,
			cbb.SaldoActual
		FROM
			ap_ctabancaria cb
			INNER JOIN ap_ctabancariabalance cbb ON (cb.NroCuenta = cbb.NroCuenta)
		WHERE cb.NroCuenta = '".$registro."'";
$query_cuenta = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_cuenta) != 0) $field_cuenta = mysql_fetch_array($query_cuenta);
?>

<form name="frmfiltro" id="frmfiltro" action="ap_saldo_bancos_ver.php" method="get" onsubmit="return validarFiltroSaldoBancosVer();">
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />

<div class="divBorder" style="width:900px;">
<div style="width:900px" class="divFormCaption">Informaci&oacute;n de la Cuenta Bancaria</div>
<table width="900" class="tblFiltro">
    <tr>
        <td align="right" width="125">Cta. Bancaria:</td>
        <td>
        	<input type="text" name="registro" id="registro" value="<?=$registro?>" style="width:100px;" disabled="disabled" />
            <input type="text" value="<?=$field_cuenta['Descripcion']?>" style="width:250px;" disabled="disabled" />
		</td>
        <td align="right">Saldo a la fecha:</td>
        <td>
        	<input type="text" value="<?=date("d-m-Y")?>" style="width:65px;" disabled="disabled" />
        	<input type="text" value="<?=number_format($field_cuenta['SaldoActual'], 2, ',', '.')?>" style="width:100px; text-align:right; font-weight:bold;" disabled="disabled" />
		</td>
    </tr>
</table>
</div>
<br /><hr align="center" width="900" /><br />
<div class="divBorder" style="width:900px;">
<div style="width:900px" class="divFormCaption">Criterio de Selecci&oacute;n para Transaciones</div>
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Tipo de Transacci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkttransaccion" id="chkttransaccion" value="1" <?=$cttransaccion?> onclick="chkFiltro(this.checked, 'fttransaccion');" />
			<select name="fttransaccion" id="fttransaccion" style="width:250px;" <?=$dttransaccion?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_bancotipotransaccion", "CodTipoTransaccion", "Descripcion", $fttransaccion, 0)?>
			</select>
		</td>
		<td width="125" align="right">Fecha: </td>
		<td>
        	<input type="checkbox" name="chkffecha" id="chkffecha" value="1" <?=$cffecha?> onclick="chkFiltro_2(this.checked, 'ffechad', 'ffechah');" />
			<input type="text" name="ffechad" id="ffechad" value="<?=$ffechad?>" <?=$dffecha?> maxlength="10" style="width:75px;" /> - 
            <input type="text" name="ffechah" id="ffechah" value="<?=$ffechah?>" <?=$dffecha?> maxlength="10" style="width:75px;" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:275px;">
<table width="1600" class="tblLista">
	<tr class="trListaHead">    
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="75">Nro. Transacci&oacute;n</th>
		<th scope="col" width="275">Tipo</th>
		<th scope="col" width="100">Abonos</th>
		<th scope="col" width="100">Cargos</th>
		<th scope="col" width="75">Periodo</th>
		<th scope="col" width="150">Cheque #</th>
		<th scope="col" width="100">Monto</th>
		<th scope="col" width="175">Referencia Banco</th>
		<th scope="col">Comentarios</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				bt.*,
				btt.Descripcion AS NomTipoTransaccion
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
			WHERE
				bt.NroCuenta = '".$registro."'
				$filtro
			ORDER BY NroTransaccion, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		if ($field['TipoTransaccion'] == "I") { $abonos = $field['Monto']; $cargos = 0; }
		else { $abonos = 0; $cargos = $field['Monto']; }
		?>
		<tr class="trListaBody">
			<td align="center"><?=formatFechaDMA($field['FechaTransaccion'])?></td>
			<td align="center"><?=$field['NroTransaccion']?></td>
			<td><?=($field['NomTipoTransaccion'])?></td>
			<td align="right"><strong><?=number_format($abonos, 2, ',', '.')?></strong></td>
			<td align="right"><strong><?=number_format($cargos, 2, ',', '.')?></strong></td>
			<td align="center"><?=$field['PeriodoContable']?></td>
			<td align="center"><?=$field['NroCheque']?></td> 
			<td align="right"><strong><?=number_format($field['Monto'], 2, ',', '.')?></strong></td>
			<td><?=($field['CodigoReferenciaBanco'])?></td>
			<td><?=($field['Comentarios'])?></td>
		</tr>
		<?
		$sum_abonos += $abonos;
		$sum_cargos += $cargos;
	}
	?>
    <tfoot id="footImpuestos">
    <tr><td colspan="10">&nbsp;</td></tr>
    <tr>
        <th scope="col" colspan="3">&nbsp;</th>
		<th scope="col" align="right"><strong><?=number_format($sum_abonos, 2, ',', '.')?></strong></td>
		<th scope="col" align="right"><strong><?=number_format($sum_cargos, 2, ',', '.')?></strong></td>
    </tr>
    </tfoot>
</table>
</div></td></tr></table>
</form>
</body>
</html>