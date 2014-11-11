<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/ac_fphp.php");
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
		<td class="titulo">Lista de Vouchers por Cuenta</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="ac_consultas_saldo_cuenta_voucher.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<table width="1100" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btVer" value="Ver Detalle" onclick="cargarOpcion(this.form, 'ac_voucher_form.php?opcion=ver&return=ac_voucher_lista', 'BLANK', 'height=700, width=1000, left=100, top=0, resizable=no');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1100px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Voucher</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="125" scope="col">Libro</th>
		<th width="125" scope="col">Cr&eacute;ditos</th>
		<th width="125" scope="col">D&eacute;bitos</th>
		<th width="100" scope="col">Fecha Aprobado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vm.*,
				o.Organismo,
				d.Dependencia,
				lc.Descripcion AS NomLibroContable
			FROM
				ac_vouchermast vm
				INNER JOIN ac_voucherdet vd ON (vm.CodOrganismo = vd.CodOrganismo AND
												vm.Periodo = vd.Periodo AND
												vm.Voucher = vd.Voucher)
				INNER JOIN mastorganismos o ON (vm.CodOrganismo = o.CodOrganismo)
				LEFT JOIN mastdependencias d ON (vm.CodDependencia = d.CodDependencia)
				LEFT JOIN ac_librocontable lc ON (vm.CodLibroCont = lc.CodLibroCont)
			WHERE
				vm.CodOrganismo = '".$codorganismo."' AND
				vm.Periodo = '".$periodo."' AND
				vd.CodCuenta = '".$codcuenta."'
			GROUP BY vm.CodOrganismo, vm.Periodo, vm.Voucher
			ORDER BY vm.CodOrganismo, vm.Periodo, vm.Voucher";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodOrganismo] $field[Periodo] $field[Voucher]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['Voucher']?></td>
			<td><?=htmlentities($field['TituloVoucher'])?></td>
			<td align="center"><?=htmlentities($field['NomLibroContable'])?></td>
			<td align="right"><?=number_format($field['Creditos'], 2, ',', '.')?></td>
			<td align="right" style="color:#900;"><?=number_format($field['Debitos'], 2, ',', '.')?></td>
			<td align="center"><?=formatFechaDMA($field['FechaAprobacion'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>