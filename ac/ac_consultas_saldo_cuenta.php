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
	$fperiodo = date("Y-m");
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
		<td class="titulo">Saldo en Cuentas</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_consultas_saldo_cuenta.php" method="POST">
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
		<td align="right">Per&iacute;odo:</td>
		<td>
			<input type="checkbox" <?=$cperiodo?> onclick="this.checked=!this.checked;" />
			<input type="text" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" maxlength="7" style="width:65px;" <?=$dperiodo?> />
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
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
		<th width="125" scope="col">Cuenta Mayor</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Actividad</th>
		<th width="100" scope="col">Saldos</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion,
				pc.Nivel,
				(SELECT SUM(SaldoBalance)
				 FROM ac_voucherbalance vb1
				 WHERE
					vb1.CodOrganismo = '".$forganismo."' AND
					vb1.Periodo = '".$fperiodo."' AND
					SUBSTRING(vb1.CodCuenta, 1, 5) = pc.CodCuenta
				) AS SaldoBalance
			FROM ac_mastplancuenta pc
			WHERE
				pc.Nivel >= '4' AND
				((SELECT SUM(SaldoBalance)
				 FROM ac_voucherbalance vb1
				 WHERE
					vb1.CodOrganismo = '".$forganismo."' AND
					vb1.Periodo = '".$fperiodo."' AND
					SUBSTRING(vb1.CodCuenta, 1, 5) = pc.CodCuenta
				) IS NOT NULL)
			ORDER BY CodCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); imprimirAjaxResponse('accion=consultas_saldo_cuenta_detalle&codorganismo=<?=$forganismo?>&periodo=<?=$fperiodo?>&codcuenta='+this.id, 'detalles', 'ac');" id="<?=$field['CodCuenta']?>">
			<td align="center"><?=$field['CodCuenta']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td align="right"><?=number_format($field['SaldoBalance'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['SaldoBalance'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<input type="hidden" id="detalle" />
<table width="900" class="tblBotones">
	<tr>
		<td>Selecione una cuenta mayor para mayor detalle</td>
		<td align="right">
			<input type="button" value="Ver Detalle de la Cuenta" onclick="cargarOpcion(this.form, 'ac_consultas_saldo_cuenta_detalle.php?codorganismo=<?=$forganismo?>&periodo=<?=$fperiodo?>&codcuenta='+document.getElementById('detalle').value, 'BLANK', 'height=700, width=600, left=100, top=0, resizable=no');" />
			<input type="button" value="Ver Voucher de la Cuenta" onclick="cargarOpcion(this.form, 'ac_consultas_saldo_cuenta_voucher.php?codorganismo=<?=$forganismo?>&periodo=<?=$fperiodo?>&codcuenta='+document.getElementById('detalle').value, 'BLANK', 'height=750, width=1150, left=100, top=100, resizable=no');" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:900px; height:225px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th width="125" scope="col">Cuenta Mayor</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Act.</th>
		<th width="100" scope="col">Saldos</th>
	</tr>
    </thead>
    
    <tbody id="detalles">
    </tbody>
</table>
</div>
</center>
</form>
</body>
</html>