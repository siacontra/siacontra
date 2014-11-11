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
if ($tipoorden == "OC") { $lg_distribucion = "lg_distribucionoc"; $lg_ordendetalle = "lg_ordencompradetalle"; }
else { $lg_distribucion = "lg_distribucionos"; $lg_ordendetalle = "lg_ordenserviciodetalle"; }
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
		<td class="titulo">Disponibilidad Presupuestaria</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '1', 2)">General</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '2', 2);">Detallada</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<div style="overflow:scroll; width:995px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="90">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="50">Nro. Items</th>
        <th scope="col" width="100">Monto</th>
        <th scope="col" width="100">Disponible</th>
        <th scope="col" width="100">Resta</th>
    </tr>
    </thead>
    
    <tbody>        
    <?php
	//	obtengo la distribucion insertada por el usuario para la orden
	$detalle = split(";", $detalles);
	$i = 0;
	foreach ($detalle as $linea) {	$i++;
		list($registro, $descripcion, $cantidad, $preciounit, $descp, $descf, $flagexon, $codpartida) = split("[|]", $linea);
		$partida_orden[$codpartida] += ($cantidad * ($preciounit - ($preciounit * $descp / 100) - $descf));
		$codpartida_orden[$codpartida] = $codpartida;
		$nroitem[$codpartida] += 1;
		if ($flagexon == "N") $nroitemigv += 1;
	}
	if ($total_impuesto > 0) {
		$codpartida = $_PARAMETRO['IVADEFAULT'];
		$partida_orden[$codpartida] += $total_impuesto;
		$codpartida_orden[$codpartida] = $codpartida;
		$nroitem[$codpartida] = $nroitemigv;
	}

	//	obtengo la ditribucion anterior insertada por el usuario para la orden (en caso de que este en modo de edicion)
	foreach ($codpartida_orden as $codpartida) {
		$sql = "SELECT Monto
				FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodProveedor = '".$codproveedor."' AND
					CodTipoDocumento = '".$tipoorden."' AND
					NroDocumento = '".$nroorden."' AND
					cod_partida = '".$codpartida."'";	
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while($field = mysql_fetch_array($query)) {
			$partida_anterior[$codpartida] += $field['Monto'];
		}
	}
	
	//	obtengo la disponibilidad actual de cada una de las partidas de la orden	
	foreach ($codpartida_orden as $codpartida) {
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + ".floatval($partida_anterior[$codpartida]).") AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
													 pvp.EjercicioPpto = '".$anio."')
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
														 pvp.Organismo = pvpd.Organismo AND
														 pvp.CodPresupuesto = pvpd.CodPresupuesto)
				WHERE p.cod_partida = '".$codpartida."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			if ($partida_orden[$codpartida] > $field['MontoDisponible']) $style = "style='font-weight:bold; background-color:#F8637D;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			$resta = $field['MontoDisponible'] - $partida_orden[$codpartida];
			$partida_orden_disponible[$codpartida] = $field['MontoDisponible'];
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field['cod_partida']?></td>
				<td><?=($field['denominacion'])?></td>
				<td align="center"><?=$nroitem[$codpartida]?></td>
				<td align="right"><?=number_format($partida_orden[$codpartida], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field['MontoDisponible'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($resta, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	?>
    </tbody>
</table>
</div>
</div>

<div id="tab2" style="display:none;">
<div style="overflow:scroll; width:995px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="75">Item / Commodity</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="75">Partida</th>
        <th scope="col" width="60">Cant.</th>
        <th scope="col" width="100">Precio Unit.</th>
        <th scope="col" width="100">Total</th>
        <th scope="col" width="100">Disponible</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
	$detalle = split(";", $detalles);	$i=0;
    foreach ($detalle as $linea) {	$i++;
		list($registro, $descripcion, $cantidad, $preciounit, $descp, $descf, $flagexon, $codpartida) = split("[|]", $linea);
		if ($descp > 0) $pu_desc = $preciounit - ($preciounit * $descp / 100);
		else $pu_desc = $preciounit - $descf;
		$monto = $cantidad * $pu_desc;
				
		if ($monto > $partida_orden_disponible[$codpartida]) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
		<tr class="trListaBody" <?=$style?>>
			<td align="center"><?=$registro?></td>
			<td><?=$descripcion?></td>
			<td align="center"><?=$codpartida?></td>
			<td align="right"><?=number_format($cantidad, 2, ',', '.')?></td>
			<td align="right"><?=number_format($pu_desc, 2, ',', '.')?></td>
			<td align="right"><?=number_format($monto, 2, ',', '.')?></td>
			<td align="right"><?=number_format($partida_orden_disponible[$codpartida], 2, ',', '.')?></td>
		</tr>
		<?php
	}
	?>
    </tbody>
</table>
</div>
</div>
</center>

<table width="995" align="center">
	<tr>
    	<td width="35"><div style="background-color:#F8637D; width:25px; height:20px;"></div></td>
        <td>Sin disponibilidad presupuestaria</td>
    	<td width="35"><div style="background-color:#D0FDD2; width:25px; height:20px;"></div></td>
        <td>Disponibilidad presupuestaria</td>
    </tr>
</table>

</body>
</html>