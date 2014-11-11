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
//	consulto organismo
$sql = "SELECT Organismo FROM mastorganismos WHERE CodOrganismo = '".$codorganismo."'";
$query_org = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_org)) $field_org = mysql_fetch_array($query_org);
//	------------------------------------
//	consulto cuenta
$sql = "SELECT Descripcion FROM ac_mastplancuenta WHERE CodCuenta = '".$codcuenta."'";
$query_cta = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_cta)) $field_cta = mysql_fetch_array($query_cta);
//	------------------------------------
//	consulto balance por periodo
$sql = "SELECT *
		FROM ac_voucherbalance
		WHERE
			CodOrganismo = '".$codorganismo."' AND
			Periodo = '".$periodo."' AND
			CodCuenta = '".$codcuenta."'";
$query_bal = mysql_query($sql) or die($sql.mysql_error());
//	------------------------------------
list($anio, $mes) = split("[-]", $periodo);
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
		<td class="titulo">Balance de la Cuenta</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<center>
<table width="500" class="tblFiltro divBorder">
    <tr>
        <td style="background-color:#898989; height:25px;" width="125">Organismo:</td><td><?=$field_org['Organismo']?></td>
    </tr>
    <tr>
        <td style="background-color:#898989; height:25px;">Libro Contable:</td><td><?=$field_org['NomLibro']?></td>
    </tr>
    <tr>
        <td style="background-color:#898989; height:25px;">Cuenta Contable:</td><td><?=$codcuenta?> <?=$field_cta['Descripcion']?></td>
    </tr>
    <tr>
        <td style="background-color:#898989; height:25px;">A&ntilde;o:</td><td><?=$anio?></td>
    </tr>
</table>
<br />

<table width="500" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col">Periodo</th>
        <th scope="col" width="150">Actividad</th>
        <th scope="col" width="150">Saldos</th>
    </tr>
    </thead>
    
    <tbody>
    <tr class="trListaBody">
        <td><strong>Saldo Inicial</strong></td>
        <td>&nbsp;</td>
        <td align="right"><?=number_format($field_cta['SaldoInicial'], 2, ',', '.')?></td>
    </tr>
    <?
	while ($field_bal = mysql_fetch_array($query_bal)) {
		?>
		<tr class="trListaBody">
            <td><?=getNombreMes($field_bal['Periodo'])?></td>
            <td align="right"><?=number_format($field_bal['SaldoBalance'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field_bal['SaldoBalance'], 2, ',', '.')?></td>
		</tr>
		<?
	}

	?>
    </tbody>
</table>
</center>

</body>
</html>