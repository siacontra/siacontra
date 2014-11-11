<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
</head>

<body>
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

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">&Uacute;ltimas Cotizaciones</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<center>
<table width="900" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="100">Proveedor</th>
        <th scope="col">Raz&oacute;n Social</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="100">Precio Unit.</th>
        <th scope="col" width="100">Monto Total</th>
        <th scope="col" width="30">Asig.</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalle">
    <?
    $sql = "SELECT
				c.FechaDocumento,
				c.CodProveedor,
				c.NomProveedor,
				c.Cantidad,
				c.PrecioUnit,
				c.PrecioCantidad,
				c.Total,
				c.FlagAsignado
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND 
													   c.CodRequerimiento = rd.CodRequerimiento AND 
													   c.Secuencia = rd.Secuencia)
			WHERE
				rd.CodItem = '".$registro."' OR
				rd.Commoditysub = '".$registro."'
			ORDER BY FechaDocumento, CodProveedor";
    $query_cotizaciones = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_cotizaciones = mysql_fetch_array($query_cotizaciones)) {
		?>
        <tr class="trListaBody">
			<td align="center"><?=formatFechaDMA($field_cotizaciones['FechaDocumento'])?></td>
			<td align="right"><?=$field_cotizaciones['CodProveedor']?></td>
			<td><?=($field_cotizaciones['NomProveedor'])?></td>
			<td align="right"><?=number_format($field_cotizaciones['Cantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_cotizaciones['PrecioUnit'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_cotizaciones['PrecioCantidad'], 2, ',', '.')?></td>
			<td align="center"><?=printFlag($field_cotizaciones['FlagAsignado'])?></td>
		</tr>
        <?
    }
	?>
    </tbody>
</table>
</center>
</body>
</html>