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
$bruto = $afecto + $noafecto;
$pordistribuir = $afecto + $noafecto;
//	--------------------------
if ($distribucion == "") {
	//	consulto
	$sql = "SELECT CodPartida, CodCuenta FROM ap_conceptogastos WHERE CodConceptoGasto = '".$concepto."'";
	$query_concepto = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_concepto) != 0) $field_concepto = mysql_fetch_array($query_concepto);
	//	-------------------------
	$distribucion = number_format($bruto, 2, ',', '.')."|$concepto|$field_concepto[CodPartida]|$field_concepto[CodCuenta]|$codccosto";
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Agregar Distribuci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:850px" class="divFormCaption">Distribuci&oacute;n</div>
<form name="frmdistribucion" id="frmdistribucion">
<input type="hidden" id="seldistribucion" />
<input type="hidden" id="codccosto" value="<?=$codccosto?>" />
<input type="hidden" id="afecto" value="<?=$afecto?>" />
<input type="hidden" id="noafecto" value="<?=$noafecto?>" />
<input type="hidden" id="bruto" value="<?=$bruto?>" />
<table width="850" class="tblBotones">
    <tr>
    	<td>
            <input type="button" style="width:80px;" id="btSelConcepto" value="Sel. Concepto" onclick="abrirSelLista('frmdistribucion', 'seldistribucion', 'concepto', 'nomconcepto', 'ap_listado_concepto_gastos.php?limit=0&ventana=selListadoConceptoDistribucion&', 600, 775);" <?=$d_ver?> />
            <input type="button" style="width:80px;" id="btSelPartida" value="Sel. Partida" onclick="abrirSelLista('frmdistribucion', 'seldistribucion', 'codpartida', 'nompartida', 'lista_clasificador_presupuestario.php?limit=0&flagproveedor=S&destino=selPartidaCuenta2&', 600, 775, 'codcuenta', 'nomcuenta');" disabled="disabled" />
            <input type="button" style="width:80px;" id="btSelCuenta" value="Sel. Cuenta" onclick="abrirSelLista('frmdistribucion', 'seldistribucion', 'codcuenta', 'nomcuenta', 'listado_cuentas_contables.php?limit=0&flagproveedor=S&', 600, 850);" disabled="disabled" />
            <input type="button" style="width:80px;" id="btSelCCosto" value="Sel. C.Costo" onclick="abrirSelLista('frmdistribucion', 'seldistribucion', 'ccosto', 'nomccosto', 'listado_centro_costos.php?limit=0&flagproveedor=S&', 600, 850);" />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Agregar" onclick="insertarDistribucionCajaChica(this);" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDistribucionCajaChica(document.getElementById('seldistribucion').value);" />
        </td>
    </tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:850px; height:250px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="30">#</th>
        <th scope="col">Concepto</th>
        <th scope="col" width="100">Partida</th>
        <th scope="col" width="100">Cuenta</th>
        <th scope="col" width="60">C.Costo</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    <tbody id="listaDistribucion">
    <?php
	$registro = split(";", $distribucion);	$candetalles=0;
	foreach ($registro as $datos) {	$candetalles++;
		list($monto, $concepto, $codpartida, $codcuenta, $codccosto) = split('[|]', $datos);
		$monto=setNumero($monto);
		$nomconcepto = getNomConceptoGasto($concepto);
		$nompartida = getNomPartida($codpartida);
		$nomcuenta = getNomCuenta($codcuenta);
		$pordistribuir -= $monto;
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldistribucion');" id="dis_<?=$candetalles?>">
            <td align="center">
                <input type="hidden" name="nro" value="<?=$candetalles?>" />
                <?=$candetalles?>
            </td>
            <td align="center">
                <input type="hidden" name="concepto" id="concepto_<?=$candetalles?>" value="<?=$concepto?>" />
                <input type="text" name="nomconcepto" id="nomconcepto_<?=$candetalles?>" value="<?=$nomconcepto?>" style="width:99%;" class="cell" disabled="disabled" />
            </td>
            <td align="center">
            	<input type="text" name="codpartida" id="codpartida_<?=$candetalles?>" value="<?=$codpartida?>" style="width:96%; text-align:center;" class="cell" disabled="disabled" />
                <input type="hidden" name="nompartida" id="nompartida_<?=$candetalles?>" value="<?=($nompartida)?>" style="width:99%;" class="cell" disabled="disabled" />
			</td>
            <td align="center" title="<?=($nomcuenta)?>">
                <input type="text" name="codcuenta" id="codcuenta_<?=$candetalles?>" value="<?=$codcuenta?>" style="width:96%; text-align:center;" class="cell" disabled="disabled" />
                <input type="hidden" name="nomcuenta" id="nomcuenta_<?=$candetalles?>" value="<?=($nomcuenta)?>" />
            </td>
            <td align="center">
                <input type="text" name="ccosto" id="ccosto_<?=$candetalles?>" value="<?=$codccosto?>" style="width:95%; text-align:center;" class="cell" disabled="disabled" />
                <input type="hidden" name="nomccosto" id="nomccosto_<?=$candetalles?>" />
            </td>
            <td align="center"><input type="text" name="monto" value="<?=number_format($monto, 2, ',', '.')?>" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setTotalesDistribucionCajaChica();" /></td>
		</tr>
        <?
	}
	?>
    </tbody>
                
    <tfoot>
    <tr><td colspan="6">&nbsp;</td></tr>
    <tr>
        <th scope="col" align="right" colspan="5">Total Distribuci&oacute;n:</th>
        <th scope="col" align="right"><input type="text" id="bruto" value="<?=number_format($bruto, 2, ',' ,'.')?>" style="border:none; background:none;width:95%; text-align:right; font-weight:bold;" readonly="readonly" /></th>
    </tr>
    <tr>
        <th scope="col" align="right" colspan="5">Por Distribuir:</th>
        <th scope="col" align="right"><input type="text" id="pordistribuir" value="<?=number_format($pordistribuir, 2, ',', '.')?>" style="border:none; background:none;width:95%; text-align:right; font-weight:bold;" readonly="readonly" /></th>
    </tr>
    </tfoot>
</table>
</div></td></tr></table>
<input type="hidden" id="nrodetalles" value="<?=$candetalles?>" />
<input type="hidden" id="candetalles" value="<?=$candetalles?>" />
</form>
<br />
<div class="divBorder" style="width:850px;">
<table width="100%" class="tblFiltro">
	<tr>
    	<td align="center">
        	<input type="button" class="btLista" value="Aceptar" onclick="aceptarDistribucionCajaChicaDetalle('<?=$nro?>');" />
        	<input type="button" class="btLista" value="Cancelar" onclick="window.close();" />
        </td>
    </tr>
</table>
</div>
</body>
</html>