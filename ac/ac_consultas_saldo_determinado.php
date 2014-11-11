<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ac_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y");
	$ffecha = date("d-m-Y");
	$fcontabilidad = "T";
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND vb.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND vb.Periodo LIKE '".$fperiodo."-%'"; } else $dperiodo = "disabled";
if ($fcontabilidad != "") { $ccontabilidad = "checked"; $filtro .= " AND lc.CodContabilidad = '".$fcontabilidad."'"; } else $dcontabilidad = "disabled";
if ($ffecha != "") { $cfecha = "checked"; $filtro .= " AND vm.FechaVoucher <= '".formatFechaAMD($ffecha)."'"; } else $dfecha = "disabled";
if ($fcodcuentad != "") { $ccodcuentad = "checked"; $filtro .= " AND vb.CodCuenta >= '".$fcodcuentad."'"; } else $dcodcuentad = "disabled";
if ($fcodcuentah != "") { $ccodcuentah = "checked"; $filtro .= " AND vb.CodCuenta <= '".$fcodcuentah."'"; } else $dcodcuentah = "disabled";
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

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Saldos Contables a una Fecha Determinada</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_consultas_saldo_determinado.php" method="POST" onsubmit="return consultas_saldo_determinado(this);">
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?> onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Contabilidad:</td>
		<td>
			<input type="checkbox" <?=$ccontabilidad?> onclick="this.checked=!this.checked;" />
			<select name="fcontabilidad" id="fcontabilidad" style="width:125px;" <?=$dcontabilidad?>>
				<?=loadSelect("ac_contabilidades", "CodContabilidad", "Descripcion", $fcontabilidad, 0)?>
			</select>
		</td>
	</tr>
    <tr>
    	<td align="right">Cuenta Desde: </td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked;" />
        	<input type="text" name="fcodcuentad" id="fcodcuentad" style="width:75px;" value="<?=$fcodcuentad?>" />
        </td>
		<td align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="4" style="width:65px;" <?=$dperiodo?> readonly="readonly" />
		</td>
	</tr>
    <tr>
    	<td align="right">Cuenta Hasta: </td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked;" />
        	<input type="text" name="fcodcuentah" id="fcodcuentah" style="width:75px;" value="<?=$fcodcuentah?>" />
        </td>
		<td align="right">Fecha:</td>
		<td>
			<input type="checkbox" <?=$cfecha?> onclick="this.checked=!this.checked;" />
			<input type="text" name="ffecha" id="ffecha" value="<?=$ffecha?>" maxlength="10" style="width:65px;" <?=$dfecha?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:900px; height:350px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th width="125" scope="col">CodCuenta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Saldo Inicial</th>
		<th width="100" scope="col">Movimiento Mes</th>
		<th width="100" scope="col">Saldo Final</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	obtengo el saldo inicial
	$acumulado = 0.00;
	
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vb.CodCuenta,
				vb.SaldoInicial,
				pc.Descripcion,
				SUM(vd.MontoVoucher) AS Movimientos
			FROM
				ac_voucherbalance vb
				INNER JOIN ac_mastplancuenta pc ON (vb.CodCuenta = pc.CodCuenta)
				INNER JOIN ac_voucherdet vd ON (vb.CodCuenta = vd.CodCuenta)
				INNER JOIN ac_vouchermast vm ON (vd.CodOrganismo = vm.CodOrganismo AND
												 vd.Periodo = vm.Periodo AND
												 vd.Voucher = vm.Voucher)
				INNER JOIN ac_librocontabilidades lc ON (vm.CodLibroCont = lc.CodLibroCont)
			WHERE 1 $filtro
			GROUP BY vb.CodOrganismo, vb.Periodo, vb.CodCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$saldo_final = $saldo_inicial + $field['Movimientos'];
		?>
		<tr class="trListaBody">
			<td><?=$field['CodCuenta']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td align="right"><?=number_format($saldo_inicial, 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['Movimientos'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($saldo_final, 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
</body>
</html>