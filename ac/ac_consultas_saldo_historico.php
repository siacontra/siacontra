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
	$fcontabilidad = "T";
}
//	------------------------------------
$filtro = "";
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND vm.CodOrganismo = '".$forganismo."'"; } else $dorganismo = "disabled";
if ($fperiodo != "") { $cperiodo = "checked"; $filtro .= " AND vm.Periodo = '".$fperiodo."'"; } else $dperiodo = "disabled";
if ($fcontabilidad != "") { $ccontabilidad = "checked"; $filtro .= " AND vm.Periodo = '".$fcontabilidad."'"; } else $dcontabilidad = "disabled";
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
		<td class="titulo">Saldos Hist&oacute;ricos Acumulado</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_consultas_saldo_historico.php" method="POST">
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
    	<td align="right">Cuenta: </td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked;" />
        	<input type="text" name="fcodcuenta" id="fcodcuenta" style="width:75px;" value="<?=$fcodcuenta?>" onchange="getDescripcionLista('accion=getDescripcionCuenta', this, 'fnomcuenta');" />
			<input type="text" name="fnomcuenta" id="fnomcuenta" style="width:210px;" value="<?=$fnomcuenta?>" readonly="readonly" />
			<input type="button" value="..." onclick="cargarVentana(this.form, '../lib/listado_cuentas_contables.php?ventana=&cod=fcodcuenta&nom=fnomcuenta&limit=0', 'height=800, width=875, left=50, top=0, resizable=yes');" />
        </td>
		<td align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="4" style="width:65px;" <?=$dperiodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:900px; height:225px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col">Mes</th>
		<th width="100" scope="col">Debe</th>
		<th width="100" scope="col">Haber</th>
		<th width="100" scope="col">Neto</th>
		<th width="100" scope="col">Acumulado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	obtengo el saldo inicial
	$acumulado = 0.00;
	
	//	CONSULTO LA TABLA
	$sql = "SELECT *
			FROM ac_voucherbalance
			WHERE
				CodOrganismo = '".$forganismo."' AND
				Periodo LIKE '".$fperiodo."%' AND
				CodCuenta = '".$fcodcuenta."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($field['SaldoBalance'] >= 0) {
			$debe = $field['SaldoBalance'];
			$haber = 0;
		} else {
			$debe = 0;
			$haber = $field['SaldoBalance'];
		}
		$neto = $acumulado + $debe - $haber;
		$acumulado += $neto;
		?>
		<tr class="trListaBody">
			<td><?=getNombreMes($field['Periodo'])?></td>
			<td align="right"><?=number_format($debe, 2, ',', '.')?></td>
			<td align="right"><?=number_format($haber, 2, ',', '.')?></td>
			<td align="right"><?=number_format($neto, 2, ',', '.')?></td>
			<td align="right"><?=number_format($acumulado, 2, ',', '.')?></td>
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