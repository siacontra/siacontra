<?php 
include ("fphp_lg.php");
//	--------------------------
if ($accion == "setDirigidoA") {
	connect();
	$sql = "SELECT ReqAlmacenCompra FROM lg_clasificacion WHERE Clasificacion = '".$clasificacion."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) { $field = mysql_fetch_array($query); echo $field['ReqAlmacenCompra']; }
	else echo $sql;
}

//	--------------------------
elseif ($accion == "mostrarRequerimientoDetalles") {
	connect();
	list($codorganismo, $codrequerimiento)=SPLIT( '[|]', $registroreq);
	?>
	<table class="tblLista" width="100%">
		<tr class="trListaHead">
			<th width="100" scope="col"># Req.</th>
			<th scope="col" width="75">Item</th>
			<th scope="col" width="75">Cod. Interno</th>
			<th scope="col">Descripci&oacute;n</th>
			<th scope="col" width="50">Uni.</th>
			<th scope="col" width="75">Cant.</th>
			<th scope="col" width="100">C. Costos</th>
			<th scope="col" width="75">Estado</th>
		</tr>
		<?php
		//	CONSULTO LA TABLA
		$sql = "SELECT
					lrd.*,
					o.Organismo,
					d.Dependencia,
					lr.Clasificacion
				FROM
					lg_requerimientosdet lrd
					INNER JOIN mastdependencias d ON (lrd.CodDependencia = d.CodDependencia)
					INNER JOIN mastorganismos o ON (lrd.CodOrganismo = o.CodOrganismo)
					INNER JOIN lg_requerimientos lr ON (lrd.CodRequerimiento = lr.CodRequerimiento)
				WHERE
					lrd.CodRequerimiento = '".$codrequerimiento."' AND
					lrd.CodOrganismo = '".$codorganismo."'
				ORDER BY CodRequerimiento, CodItem, CommodityMast";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		$rows_det = mysql_num_rows($query_det);
		//	MUESTRO LA TABLA
		while ($field_det = mysql_fetch_array($query_det)) {
			$status = printValores("ESTADO-REQUERIMIENTO-DET", $field_det['Estado']);
			if ($field_det['CodItem'] != "") $coddetalle = $field_det['CodItem']; else $coddetalle = $field_det['CommodityMast'];
			$id = $field_det['CodOrganismo']."|".$field_det['CodRequerimiento']."|".$field_det['Secuencia'];
			?>
			<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$id?>">
				<td align="center"><input type="checkbox" name="<?=$id?>" id="<?=$id?>" value="<?=$id?>" style="display:none" /><?=$field_det['CodRequerimiento']?></td>
				<td align="center"><?=$field_det['CodInterno']?></td>
				<td align="center"><?=$coddetalle?></td>
				<td><?=htmlentities($field_det['Descripcion'])?></td>
				<td align="center"><?=$field_det['CodUnidad']?></td>
				<td align="center"><?=$field_det['CantidadPedida']?></td>
				<td align="center"><?=$field_det['CodCentroCosto']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		}
		?>
	</table>
	<input type="hidden" name="rows_detalle" id="rows_detalle" value="Registros: <?=$rows_det?>" />
	<?
}

//	--------------------------
elseif ($accion == "insertarProveedorCotizacion") {
	connect();
	$lineas = split(";", $detalles);
	echo "||";
	foreach ($lineas as $detalle) {
		list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $detalle);
		
		$cotizacion_numero = getCodigo_2("lg_cotizacion", "CotizacionNumero", "CotizacionSecuencia", $secuencia, 10);
		
		//	inserto en lg_cotizacion
		$sql = "INSERT INTO lg_cotizacion (CodOrganismo,
											CotizacionSecuencia,
											CotizacionNumero,
											Numero,
											Proveedor,
											FechaEntrega,
											UltimoUsuario,
											UltimaFecha)
									VALUES ('".$codorganismo."',
											'".$secuencia."',
											'".$cotizacion_numero."',
											'".$numero."',
											'".$codproveedor."',
											'".$flimite."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	?>
    <table width="100%" class="tblLista">
        <tr class="trListaHead">
            <th scope="col" width="50">#</th>
            <th scope="col">Nombre de la Persona</th>
            <th scope="col" width="200">Forma de Pago</th>
        </tr>
        
        <?php
        //	CONSULTO LA TABLA
        $sql = "SELECT
                    c.*,
                    p.NomCompleto AS NomProveedor
                FROM
                    lg_cotizacion c
                    INNER JOIN mastpersonas p ON (c.Proveedor = p.CodPersona)
				WHERE 
					c.Numero = '".$numero."'
				GROUP BY c.Proveedor";
        $query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
        $rows_proveedores = mysql_num_rows($query_proveedores);
        $i=0;
        //	MUESTRO LA TABLA
        while ($field_proveedores = mysql_fetch_array($query_proveedores)) {
            $i++;
            ?>
            <tr class="trListaBody" onclick="mClk(this, 'selproveedor');" id="<?=$field_proveedores['CodPersona']?>">
                <td align="center"><?=$i?></td>
                <td><?=htmlentities($field_proveedores['NomProveedor'])?></td>
                <td align="center"><?=$field_proveedores['NomFormaPago']?></td>
            </tr>
            <?
        }
        ?>
    </table>
    <?
}

//	--------------------------
elseif ($accion == "mostrarListaProveedores") {
	?>
	<table width="100%" class="tblLista">
        <tr class="trListaHead">
            <th scope="col" width="50">#</th>
            <th scope="col">Nombre de la Persona</th>
            <th scope="col" width="200">Forma de Pago</th>
        </tr>
        
        <?php
        //	CONSULTO LA TABLA
        $sql = "SELECT
                    c.*,
                    p.NomCompleto AS NomProveedor
                FROM
                    lg_cotizacion c
                    INNER JOIN mastpersonas p ON (c.Proveedor = p.CodPersona)";
        $query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
        $rows_proveedores = mysql_num_rows($query_proveedores);
        $i=0;
        //	MUESTRO LA TABLA
        while ($field_proveedores = mysql_fetch_array($query_proveedores)) {
            $i++;
            ?>
            <tr class="trListaBody" onclick="mClk(this, 'selproveedor');" id="<?=$field_proveedores['CodPersona']?>">
                <td align="center"><?=$i?></td>
                <td><?=htmlentities($field_proveedores['NomProveedor'])?></td>
                <td align="center"><?=$field_proveedores['NomFormaPago']?></td>
            </tr>
            <?
        }
        ?>
    </table>
	<?
}
?>