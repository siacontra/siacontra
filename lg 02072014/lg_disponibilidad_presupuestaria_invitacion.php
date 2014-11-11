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
if ($origen == "cotizar") {
	$sql = "SELECT NroCotizacionProv
			FROM lg_cotizacion
			WHERE Numero = '".$numero."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$registro = $field['NroCotizacionProv'];
}
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
	$sql = "(SELECT
				p.cod_partida,
				p.denominacion,
				SUM(c.PrecioCantidad) AS Monto,
				SUM(c.Total - c.PrecioCantidad) AS Impuesto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible,
				pvp.EjercicioPpto,
				c.CodOrganismo
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (c.CodOrganismo = pvp.Organismo AND
												 SUBSTRING(c.FechaDocumento, 1, 4) = pvp.EjercicioPpto)
				LEFT JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 p.cod_partida = pvpd.cod_partida)
			 WHERE c.NroCotizacionProv = '".$registro."'
			 GROUP BY cod_partida)
			 
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				SUM(c.PrecioCantidad) AS Monto,
				SUM(c.Total - c.PrecioCantidad) AS Impuesto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible,
				pvp.EjercicioPpto,
				c.CodOrganismo
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_commoditysub i ON (rd.CommoditySub = i.Codigo)
				INNER JOIN pv_partida p ON (i.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (c.CodOrganismo = pvp.Organismo AND
												 SUBSTRING(c.FechaDocumento, 1, 4) = pvp.EjercicioPpto)
				LEFT JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 p.cod_partida = pvpd.cod_partida)
			 WHERE c.NroCotizacionProv = '".$registro."'
			 GROUP BY cod_partida)";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$sql = "SELECT 
					COUNT(*) AS NroItems,
					(SELECT COUNT(*)
					 FROM
						lg_cotizacion c2
						INNER JOIN lg_requerimientosdet rd2 ON (c2.CodOrganismo = rd2.CodOrganismo AND
																c2.CodRequerimiento = rd2.CodRequerimiento AND
																c2.Secuencia = rd2.Secuencia)
						INNER JOIN lg_commoditysub cs2 ON (rd2.CommoditySub = cs2.Codigo)
					 WHERE
						c2.NroCotizacionProv = c1.NroCotizacionProv AND
						cs2.cod_partida = i1.PartidaPresupuestal
					 GROUP BY cs2.cod_partida) AS NroCommodities
				FROM
					lg_cotizacion c1
					INNER JOIN lg_requerimientosdet rd1 ON (c1.CodOrganismo = rd1.CodOrganismo AND
														    c1.CodRequerimiento = rd1.CodRequerimiento AND
														    c1.Secuencia = rd1.Secuencia)
					INNER JOIN lg_itemmast i1 ON (rd1.CodItem = i1.CodItem)
				WHERE
					c1.NroCotizacionProv = '".$registro."' AND
					i1.PartidaPresupuestal = '".$field_general['cod_partida']."'
				GROUP BY i1.PartidaPresupuestal";
		$query_nroitems = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_nroitems) > 0) {
			$field_nroitems = mysql_fetch_array($query_nroitems);
			if ($field_nroitems['NroItems'] != 0) $nro_items = $field_nroitems['NroItems'];
			elseif ($field_nroitems['NroCommodities'] != 0) $nro_items = $field_nroitems['NroCommodities'];
		} else $nro_items = 0;
		##
		$resta = $field_general['Disponible'] - $field_general['Monto'];
		if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		##
		$total_impuesto += $field_general['Impuesto'];
		##
		$anio = $field_general['EjercicioPpto'];
		$organismo = $field_general['CodOrganismo'];
		?>
        <tr class="trListaBody" <?=$style?>>
        	<td align="center"><?=$field_general['cod_partida']?></td>
            <td><?=($field_general['denominacion'])?></td>
        	<td align="center"><?=$nro_items?></td>
        	<td align="right"><?=number_format($field_general['Monto'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field_general['Disponible'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($resta, 2, ',', '.')?></td>
		</tr>
        <?php
	}
	
	$sql = "SELECT *
			FROM lg_cotizacion
			WHERE
				(Total - PrecioCantidad) > 0 AND
				NroCotizacionProv = '".$registro."'";
	$query_items_igv = mysql_query($sql) or die ($sql.mysql_error());
	$nro_items = mysql_num_rows($query_items_igv);
	
	$sql = "SELECT
				p.cod_partida,
				p.denominacion,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS Disponible
			FROM
				pv_partida p
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
												 pvp.EjercicioPpto = '".$anio."')
				LEFT JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 p.cod_partida = pvpd.cod_partida)
			WHERE p.cod_partida = '".$_PARAMETRO["IVADEFAULT"]."'";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$resta = $field_general['Disponible'] - $total_impuesto;		
		##
		if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
        <tr class="trListaBody" <?=$style?>>
        	<td align="center"><?=$field_general['cod_partida']?></td>
            <td><?=($field_general['denominacion'])?></td>
        	<td align="center"><?=$nro_items?></td>
        	<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field_general['Disponible'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($resta, 2, ',', '.')?></td>
		</tr>
        <?php
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
	$sql = "(SELECT
				p.cod_partida,
				c.PrecioUnit,
				c.Cantidad,
				(c.Cantidad * c.PrecioUnit) AS Total,
				(c.Total - c.PrecioCantidad) AS Impuesto,
				pvpd.MontoAjustado AS Disponible,
				(pvpd.MontoAjustado - c.PrecioCantidad) AS Resta,
				pvp.EjercicioPpto,
				rd.CodItem AS Codigo,
				rd.Descripcion
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (c.CodOrganismo = pvp.Organismo AND
												 SUBSTRING(c.FechaDocumento, 1, 4) = pvp.EjercicioPpto)
				LEFT JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 p.cod_partida = pvpd.cod_partida)
			 WHERE c.NroCotizacionProv = '".$registro."')
			 
			UNION
			
			(SELECT
				p.cod_partida,
				c.PrecioUnit,
				c.Cantidad,
				(c.Cantidad * c.PrecioUnit) AS Total,
				(c.Total - c.PrecioCantidad) AS Impuesto,
				pvpd.MontoAjustado AS Disponible,
				(pvpd.MontoAjustado - c.PrecioCantidad) AS Resta,
				pvp.EjercicioPpto,
				rd.CommoditySub AS Codigo,
				rd.Descripcion
			 FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_commoditysub i ON (rd.CommoditySub = i.Codigo)
				INNER JOIN pv_partida p ON (i.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (c.CodOrganismo = pvp.Organismo AND
												 SUBSTRING(c.FechaDocumento, 1, 4) = pvp.EjercicioPpto)
				LEFT JOIN pv_presupuestodet pvpd ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 p.cod_partida = pvpd.cod_partida)
			 WHERE c.NroCotizacionProv = '".$registro."')
			ORDER BY cod_partida, Codigo";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		if ($field_general['Resta'] <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		##
		$total_impuesto += $field_general['Impuesto'];
		##
		$anio = $field_general['EjercicioPpto'];
		?>
        <tr class="trListaBody" <?=$style?>>
        	<td align="center"><?=$field_general['Codigo']?></td>
            <td><?=($field_general['Descripcion'])?></td>
        	<td align="center"><?=$field_general['cod_partida']?></td>
        	<td align="right"><?=number_format($field_general['Cantidad'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field_general['PrecioUnit'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field_general['Total'], 2, ',', '.')?></td>
        	<td align="right"><?=number_format($field_general['Disponible'], 2, ',', '.')?></td>
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