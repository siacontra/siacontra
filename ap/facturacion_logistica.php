<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_ap.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
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
		<td class="titulo">Facturaci&oacute;n de Log&iacute;stica</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fclasificacion = "ROC";
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (d.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (d.DocumentoReferencia LIKE '%".$fbuscar."%' OR d.ReferenciaNroDocumento LIKE '%".($fbuscar)."%' OR d.TransaccionNroDocumento LIKE '%".($fbuscar)."%' OR d.Comentarios LIKE '%".($fbuscar)."%')";	
	else $filtro.=" AND ($sltbuscar LIKE '%".$fbuscar."%')";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
if ($fcodproveedor != "") { $cproveedor = "checked"; $filtro.=" AND (d.CodProveedor = '".$fcodproveedor."')"; } else $dproveedor = "disabled";
if ($fclasificacion != "") { $cclasificacion = "checked"; $filtro.=" AND (d.DocumentoClasificacion = '".$fclasificacion."')"; } else $dclasificacion = "disabled";
if ($fpago != "") { $cpago = "checked"; /*$filtro.=" AND (d.ReferenciaTipoDocumento = '".$fpago."')";*/ } else $dpago = "disabled";
?>

<form name="frmfiltro" id="frmfiltro" action="facturacion_logistica.php?filtrar=" method="get" onsubmit="return validarFiltroFacturacionLogistica(this);">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" checked="checked" onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right" rowspan="2" valign="top">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" <?=$cbuscar?> onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-FACTURACION", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Proveedor: </td>
		<td>
			<input type="checkbox" name="chkproveedor" value="1" <?=$cproveedor?> onclick="chkFiltroProveedor(this.checked);" />
        	<input type="hidden" name="fcodproveedor" id="fcodproveedor" value="<?=$fcodproveedor?>" />
			<input type="text" name="fnomproveedor" id="fnomproveedor" value="<?=$fnomproveedor?>" readonly="readonly" style="width:300px;" />
			<input type="button" value="..." id="btProveedor" <?=$dproveedor?> onclick="cargarVentana(this.form, 'listado_personas.php?ventana=&cod=fcodproveedor&nom=fnomproveedor&limit=0&flagproveedor=S', 'height=600, width=1100, left=50, top=50, resizable=yes');" />
        </td>
		<td><input type="text" name="fbuscar" size="50" value="<?=$fbuscar?>" <?=$dbuscar?> /></td>
	</tr>
	<tr>
		<td align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" name="chkclasificacion" id="chkclasificacion" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="fclasificacion" id="fclasificacion" style="width:200px;">
                <?=loadSelect("ap_documentosclasificacion", "DocumentoClasificacion", "Descripcion", $fclasificacion, 0)?>
			</select>
		</td>
		<td align="right">Forma de Pago:</td>
		<td>
			<input type="checkbox" name="chkfpago" id="chkfpago" value="1" <?=$cpago?> onclick="chkFiltro(this.checked, 'fpago');" />
			<select name="fpago" id="fpago" style="width:200px;" <?=$dpago?>>
				<option value=""></option>
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $fpago, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>

<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form>

<br />

<div name="tab1" id="tab1" style="display:block;">
<form name="frmdocumentos" id="frmdocumentos">
<input type="hidden" id="seldocumento" />
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_documentos"></div></td>
		<td align="right">
			<input type="button" id="btFactura" value="Preparar Factura" style="width:115px;" onclick="abrirPreparacionFactura();" />
		</td>
	</tr>
</table>

<div style="width:1000px" class="divFormCaption">Lista de Documentos</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:225px;">
<table width="1600" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="100">Doc. Interno</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="100">Nro. OC/OS</th>
		<th scope="col" width="100">Transacci&oacute;n</th>
		<th scope="col">Comentarios</th>
		<th scope="col" width="175">Almacen</th>
		<th scope="col" width="100">Monto Afecto</th>
		<th scope="col" width="100">Monto No Afecto</th>
		<th scope="col" width="100">Impuestos</th>
		<th scope="col" width="100">Total</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
	</tr>
	<?php
	if ($fcodproveedor != "") {
		//	CONSULTO LA TABLA
		if ($fclasificacion == "RCD")
			$sql = "SELECT
						d.*,
						a.Descripcion AS NomAlmacen,
						fp.Descripcion AS NomFormaPago
					FROM 
						ap_documentos d
						INNER JOIN lg_commoditytransaccion t ON (d.CodOrganismo = t.CodOrganismo AND
																d.TransaccionTipoDocumento = t.CodDocumento AND
																d.TransaccionNroDocumento = t.NroDocumento)
						INNER JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
						INNER JOIN lg_ordencompra oc ON (d.CodOrganismo = oc.CodOrganismo AND
														 d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR'  AND
						d.ReferenciaTipodocumento = 'OC' $filtro
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento, TransaccionNroDocumento";
		if ($fclasificacion == "ROC")
			$sql = "SELECT
						d.*,
						a.Descripcion AS NomAlmacen,
						fp.Descripcion AS NomFormaPago
					FROM 
						ap_documentos d
						INNER JOIN lg_transaccion t ON (d.CodOrganismo = t.CodOrganismo AND
														d.TransaccionTipoDocumento = t.CodDocumento AND
														d.TransaccionNroDocumento = t.NroDocumento)
						INNER JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
						INNER JOIN lg_ordencompra oc ON (d.CodOrganismo = oc.CodOrganismo AND
														 d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR'  AND
						d.ReferenciaTipodocumento = 'OC' $filtro
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento, TransaccionNroDocumento";
			
		elseif ($fclasificacion == "SER")
			$sql = "SELECT
						d.*,
						'' AS NomAlmacen,
						fp.Descripcion AS NomFormaPago
					FROM 
						ap_documentos d
						INNER JOIN lg_ordenservicio oc ON (d.CodOrganismo = oc.CodOrganismo AND
														   d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR' AND
						d.ReferenciaTipodocumento = 'OS' $filtro					
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento";
		
		$query_documento = mysql_query($sql) or die ($sql.mysql_error());
		$rows_documento = mysql_num_rows($query_documento);
		//	MUESTRO LA TABLA
		while ($field_documento = mysql_fetch_array($query_documento)) {
			$id = $field_documento['Anio']." ".$field_documento['CodOrganismo']." ".$field_documento['CodProveedor']." ".$field_documento['DocumentoClasificacion']." ".$field_documento['DocumentoReferencia'];
			$estado = printValores("ESTADO-DOCUMENTOS", $field_documento['Estado'])
			?>
            <tr class="trListaBody" onclick="mClkMulti(this); verDocumentoDetalles('<?=$id?>');" id="row_<?=$id?>">
				<td align="center">
                	<input type="checkbox" name="documento" id="<?=$id?>" value="<?=$id?>" style="display:none" />
					<?=$field_documento['DocumentoReferencia']?>
				</td>
				<td align="center"><?=formatFechaDMA($field_documento['Fecha'])?></td>
				<td align="center"><?=$field_documento['ReferenciaTipoDocumento']?>-<?=$field_documento['ReferenciaNroDocumento']?></td>
				<td align="center"><?=$field_documento['TransaccionTipoDocumento']?>-<?=$field_documento['TransaccionNroDocumento']?></td>
				<td><?=($field_documento['Comentarios'])?></td>
				<td align="center"><?=($field_documento['NomAlmacen'])?></td>
				<td align="right"><?=number_format($field_documento['MontoAfecto'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoNoAfecto'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoImpuestos'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoTotal'], 2, ',', '.')?></td>
				<td align="center"><?=($field_documento['NomFormaPago'])?></td>
				<td align="center"><?=$estado?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div></td></tr></table>
</form>
</div>


<div name="tabDetalles" id="tabDetalles" style="display:block;">
<form name="frmdetalles" id="frmdetalles">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_detalles"></div></td>
		<td align="right">
		</td>
	</tr>
</table>

<div style="width:1000px" class="divFormCaption">Detalles</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:225px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="50">#</th>
		<th scope="col" width="100">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="100">C. Costos</th>
		<th scope="col" width="100">Cant.</th>
		<th scope="col" width="100">Precio Unit.</th>
		<th scope="col" width="100">Total</th>
	</tr>
    
    <tbody id="trDetalle">
    
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>

<script type="text/javascript" language="javascript">
</script>
</body>
</html>