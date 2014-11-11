<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_ap.php");
connect();
//	-------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Documentos del Proveedor</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_documentos_obligaciones.php?filtrar=" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="detalles_doc" id="detalles_doc" value="<?=$detalles_doc?>" />

<table width="1000" align="center">
    <tr>
        <td>
            <div id="header">
            <ul>
            <!-- CSS Tabs -->
            <li><a onclick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none';" href="#">Documentos Recibidos</a></li>	
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'" href="#">O. Compra</a></li>			
            <li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'" href="#">O. Servicios</a></li>	
            </ul>
            </div>
        </td>
    </tr>
</table>

<div name="tab1" id="tab1" style="display:block;">
<div style="width:1000px" class="divFormCaption">Documentos Recibidos</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="1500" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="100">Nro. Orden</th>
		<th scope="col" width="100">F. Preparaci&oacute;n</th>
		<th scope="col">Descripcion</th>
		<th scope="col" width="100">Monto A Pagar</th>
		<th scope="col" width="100">Monto Pendiente</th>
		<th scope="col" width="100">Monto Pagado</th>
		<th scope="col" width="100">Monto Total</th>
		<th scope="col" width="150">Almacen</th>
		<th scope="col" width="150">Almacen Ingreso</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "(SELECT 
				d.*,
				oc.Estado,
				a1.Descripcion AS NomAlmacen,
				a2.Descripcion AS NomAlmacenIngreso,
				fp.Descripcion AS NomFormaPago
			FROM 
				ap_documentos d
				INNER JOIN lg_ordencompra oc ON (d.ReferenciaNrodocumento = oc.NroOrden)
				INNER JOIN lg_almacenmast a1 ON (oc.CodAlmacen = a1.Codalmacen)
				LEFT JOIN lg_almacenmast a2 ON (oc.CodAlmacenIngreso = a2.Codalmacen)
				LEFT JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
			WHERE
				d.ReferenciaTipoDocumento = 'OC' AND
				d.CodProveedor = '".$codproveedor."' AND
				d.Estado = 'PR')
				
			UNION
			
			(SELECT 
				d.*,
				os.Estado,
				'' AS NomAlmacen,
				'' AS NomAlmacenIngreso,
				'' AS NomFormaPago
			FROM 
				ap_documentos d
				INNER JOIN lg_ordenservicio os ON (d.ReferenciaNrodocumento = os.NroOrden)
			WHERE
				d.ReferenciaTipoDocumento = 'OS' AND
				d.CodProveedor = '".$codproveedor."' AND
				d.Estado = 'PR')";
	$query_ap = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field_ap = mysql_fetch_array($query_ap)) {
		$estado_ap = printValores("ESTADO-ORDENES", $field_ap['Estado']);
		$iddoc = $field_ap['CodOrganismo']."|".$field_ap['CodProveedor']."|".$field_ap['ReferenciaTipoDocumento']."|".$field_ap['ReferenciaNroDocumento']."|".$field_ap['DocumentoClasificacion']."|".$field_ap['DocumentoReferencia'];
		?>
		<tr class="trListaBody" id="<?=$idoc?>">
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=$field_ap['ReferenciaNroDocumento']?></td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=formatFechaDMA($field_ap['Fecha'])?></td>
			<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['Comentarios'])?></td>
			<td align="center">
            	<input type="hidden" name="monto" id="monto_<?=$iddoc?>" value="<?=$field_ap['MontoPendiente']?>" />
                <?=number_format($field_ap['MontoPendiente'], 2, ',', '.')?>
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="pendiente" id="pendiente_<?=$iddoc?>" value="<?=$field_ap['MontoPendiente']?>" />
				<?=number_format($field_ap['MontoPendiente'], 2, ',', '.')?>
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="pagado" id="pagado_<?=$iddoc?>" value="<?=$field_ap['MontoPagado']?>" />                
				<?=number_format($field_ap['MontoPagado'], 2, ',', '.')?>
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="total" id="total_<?=$iddoc?>" value="<?=$field_ap['MontoTotal']?>" />                
				<?=number_format($field_ap['MontoTotal'], 2, ',', '.')?>
			</td>
			<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomAlmacen'])?></td>
			<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomAlmacenIngreso'])?></td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomFormaPago'])?></td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($estado_ap)?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</div>

<div name="tab2" id="tab2" style="display:none;">
<div style="width:1000px" class="divFormCaption">Ordenes de Compra</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="1500" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="100">Nro. Orden</th>
		<th scope="col" width="100">F. Preparaci&oacute;n</th>
		<th scope="col">Descripcion</th>
		<th scope="col" width="100">Monto A Pagar</th>
		<th scope="col" width="100">Monto Pendiente</th>
		<th scope="col" width="100">Monto Pagado</th>
		<th scope="col" width="100">Monto Total</th>
		<th scope="col" width="150">Almacen</th>
		<th scope="col" width="150">Almacen Ingreso</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				oc.*,
				a1.Descripcion AS NomAlmacen,
				a2.Descripcion AS NomAlmacenIngreso,
				fp.Descripcion AS NomFormaPago
			FROM 
				lg_ordencompra oc
				INNER JOIN lg_almacenmast a1 ON (oc.CodAlmacen = a1.Codalmacen)
				LEFT JOIN lg_almacenmast a2 ON (oc.CodAlmacenIngreso = a2.Codalmacen)
				LEFT JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
			WHERE
				oc.Estado = 'AP' AND
				oc.CodProveedor = '".$codproveedor."'";
	$query_ap = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field_ap = mysql_fetch_array($query_ap)) {
		$sql = "SELECT SUM(MontoTotal) AS MontoDocumento
				FROM ap_documentos
				WHERE
					ReferenciaTipoDocumento = 'OC' AND
					ReferenciaNroDocumento = '".$field_ap['NroOrden']."' AND
					Estado = 'RV'";
		$query_doc = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		$pendiente_real = $field_ap['MontoTotal'] - $field_doc['MontoDocumento'];
		
		if ($pendiente_real > 0) {
			$estado_ap = printValores("ESTADO-ORDENES", $field_ap['Estado']);
			$iddoc = $field_ap['CodOrganismo']."|".$field_ap['CodProveedor']."|OC|".$field_ap['NroOrden']."|ROC|";		
			?>
			<tr class="trListaBody" id="<?=$idoc?>">
				<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=$field_ap['NroOrden']?></td>
				<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=formatFechaDMA($field_ap['FechaPreparacion'])?></td>
				<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['Observaciones'])?></td>
				<td align="center">
					<input type="text" name="monto" id="monto_<?=$iddoc?>" value="<?=number_format($pendiente_real, 2, ',', '.')?>" style="width:95%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
				</td>
				<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
					<input type="hidden" name="pendiente" id="pendiente_<?=$iddoc?>" value="<?=$pendiente_real?>" />                
					<?=number_format($pendiente_real, 2, ',', '.')?>
				</td>
				<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
					<input type="hidden" name="pagado" id="pagado_<?=$iddoc?>" value="<?=$field_ap['MontoPagado']?>" />                
					<?=number_format($field_ap['MontoPagado'], 2, ',', '.')?>
				</td>
				<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
					<input type="hidden" name="total" id="total_<?=$iddoc?>" value="<?=$field_ap['MontoTotal']?>" />                
					<?=number_format($field_ap['MontoTotal'], 2, ',', '.')?>
				</td>
				<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomAlmacen'])?></td>
				<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomAlmacenIngreso'])?></td>
				<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomFormaPago'])?></td>
				<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($estado_ap)?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div></td></tr></table>
</div>

<div name="tab3" id="tab3" style="display:none;">
<div style="width:1000px" class="divFormCaption">Ordenes de Servicio</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="1500" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="100">Nro. Orden</th>
		<th scope="col" width="100">F. Preparaci&oacute;n</th>
		<th scope="col">Descripcion</th>
		<th scope="col" width="100">Monto A Pagar</th>
		<th scope="col" width="100">Monto Pendiente</th>
		<th scope="col" width="100">Monto Pagado</th>
		<th scope="col" width="100">Monto Total</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				os.*,
				(os.TotalMontoIva - os.MontoGastado) AS MontoPendiente,
				fp.Descripcion AS NomFormaPago
			FROM 
				lg_ordenservicio os
				LEFT JOIN mastformapago fp ON (os.CodFormaPago = fp.CodFormaPago)
			WHERE
				(os.Estado = 'AP' OR os.Estado = 'CO') AND
				os.CodProveedor = '".$codproveedor."'";
	$query_ap = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field_ap = mysql_fetch_array($query_ap)) {
		$estado_ap = printValores("ESTADO-ORDENES", $field_ap['Estado']);
		$iddoc = $field_ap['CodOrganismo']."|".$field_ap['CodProveedor']."|OS|".$field_ap['NroOrden']."|ROC|";
		?>
		<tr class="trListaBody" id="<?=$idoc?>">
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=$field_ap['NroOrden']?></td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=formatFechaDMA($field_ap['FechaPreparacion'])?></td>
			<td ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['Descripcion'])?></td>
			<td align="center">
            	<input type="text" name="monto" id="monto_<?=$iddoc?>" value="<?=number_format($field_ap['MontoPendiente'], 2, ',', '.')?>" style="width:95%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="pendiente" id="pendiente_<?=$iddoc?>" value="<?=$field_ap['MontoPendiente']?>" />                
				<?=number_format($field_ap['MontoPendiente'], 2, ',', '.')?>
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="pagado" id="pagado_<?=$iddoc?>" value="<?=$field_ap['MontoGastado']?>" />                
				<?=number_format($field_ap['MontoGastado'], 2, ',', '.')?>
			</td>
			<td align="right" ondblclick="insertarDocumento('<?=$iddoc?>');">
            	<input type="hidden" name="total" id="total_<?=$iddoc?>" value="<?=$field_ap['TotalMontoIva']?>" />                
				<?=number_format($field_ap['TotalMontoIva'], 2, ',', '.')?>
			</td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($field_ap['NomFormaPago'])?></td>
			<td align="center" ondblclick="insertarDocumento('<?=$iddoc?>');"><?=($estado_ap)?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</div>

</form>
</body>
</html>