<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
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
		<td class="titulo">Imprimir/Transferir Pagos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro.=" AND (p.Periodo LIKE '%".$fperiodo."%')"; } else $dperiodo = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="ap_pagos_imprimir_transferir.php" method="get">
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Periodo: </td>
		<td>
			<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" <?=$cperiodo?> onclick="chkFiltro(this.checked, 'fperiodo');" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="6" style="width:85px;" <?=$dperiodo?> />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<br />
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="cargarOpcion(this.form, 'ap_pagos_imprimir_transferir_confirmar.php', 'SELF');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:150px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">    
		<th scope="col" width="75">Prepago</th>
		<th scope="col">Banco</th>
		<th scope="col" width="150">Cuenta Bancaria</th>
		<th scope="col" width="150">Tipo Pago</th>
		<th scope="col" width="125">Monto Total</th>
		<th scope="col" width="75">Fecha Pago</th>
		<th scope="col" width="50"># Pagos</th>
		<th scope="col" width="25">Dif.</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				p.NroProceso,
				p.Secuencia,
				p.NroCuenta,
				SUM(p.MontoPago) AS MontoPago,
				p.FechaPago,
				p.CodTipoPago,
				p.CodProveedor,
				tp.TipoPago,
				b.Banco,
				(SELECT COUNT(*)
				 FROM ap_pagos
				 WHERE
				 	CodProveedor = p.CodProveedor AND
				 	NroProceso = p.NroProceso AND
				 	NroCuenta = p.NroCuenta AND
				 	CodTipoPago = p.CodTipoPago) AS NroPagos
			FROM
				ap_pagos p
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
				INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
				INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE
				p.Estado = 'GE'
				$filtro
			GROUP BY CodProveedor, NroProceso, CodTipoPago, NroCuenta
			ORDER BY NroProceso, CodTipoPago, NroCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		$id = $field['CodProveedor']."|".$field['NroProceso']."|".$field['CodTipoPago']."|".$field['NroCuenta']."|".$field['Secuencia'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); verOrdenesPrepago(document.getElementById('registro').value);" id="<?=$id?>">
			<td align="center"><?=$field['NroProceso']?></td>
			<td><?=($field['Banco'])?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td><?=($field['TipoPago'])?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="center"><?=$field['NroPagos']?></td>
			<td align="center"><?=printFlag($field['FlagPagoDiferido'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<div name="tabDetalles" id="tabDetalles" style="display:block;">
<div style="width:1000px" class="divFormCaption">Documentos a Pagar del Pre-Pago</div>
<form name="frmdetalles" id="frmdetalles">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_detalles"></div></td>
		<td align="right">
			<input type="button" class="btLista" value="Ver" disabled="disabled" />
		</td>
	</tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:150px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="25">#</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="300">Proveedor</th>
		<th scope="col" width="125">Monto a Pagar</th>
		<th scope="col" width="125">Monto Retenido</th>
		<th scope="col" width="125">Neto a Pagar</th>
	</tr>
    
    <tbody id="trDetalle">
    	
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>

<script language="javascript">
totalRegistros(<?=intval($rows)?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>