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
		<td class="titulo">Saldo de Cuentas Bancarias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fhasta = date("d-m-Y");
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (bt.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fhasta != "") { $chasta = "checked"; $filtro.=" AND (bt.FechaTransaccion <= '".formatFechaAMD($fhasta)."')"; } else $dhasta = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="ap_saldo_bancos.php" method="get" onsubmit="return validarFiltroSaldoBancos();">
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">Hasta la Fecha: </td>
		<td>
			<input type="checkbox" name="chkfhasta" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<input type="text" name="fhasta" id="fhasta" value="<?=$fhasta?>" maxlength="10" style="width:95px;" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
<input type="hidden" name="registro" id="registro" />
<br />
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btVer" value="Ver Transacciones" style="width:125px;" onclick="cargarOpcion(this.form, 'ap_saldo_bancos_ver.php?filtrar=DEFAULT&ffechah='+document.getElementById('fhasta').value+'&', 'BLANK', 'height=500, width=950, left=100, top=0, resizable=no');" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">    
		<th scope="col" width="250">Banco</th>
		<th scope="col" width="150">Nro. Cuenta</th>
		<th scope="col">Descripcion</th>
		<th scope="col" width="150">Banco</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				b.Banco,
				cb.NroCuenta,
				cb.Descripcion,
				SUM(bt.Monto) AS Monto
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_ctabancaria cb ON (bt.NroCuenta = cb.NroCuenta)
				INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE bt.Estado <> 'PR' $filtro
			GROUP BY NroCuenta
			ORDER BY Banco, NroCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['NroCuenta']?>">
			<td><?=($field['Banco'])?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="right"><strong><?=number_format($field['Monto'], 2, ',', '.')?></strong></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script language="javascript">
totalRegistros(<?=intval($rows)?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>