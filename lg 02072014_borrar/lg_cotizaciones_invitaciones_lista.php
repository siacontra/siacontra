<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
	$fedoreg = "PE";
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (c.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($finvitaciond != "" || $finvitacionh != "") {
	$cinvitacion = "checked";
	if ($finvitaciond != "") $filtro .= "AND (c.FechaInvitacion >= '".formatFechaAMD($finvitaciond)."')"; else $dinvitacion = "disabled";
	if ($finvitacionh != "") $filtro .= "AND (c.FechaInvitacion <= '".formatFechaAMD($finvitacionh)."')"; else $dinvitacion = "disabled";
} else $dinvitacion = "disabled";

if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (rd.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") { 
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (rd.CodRequerimiento LIKE '%".$fbuscar."%' OR
										  rd.CodItem LIKE '%".$fbuscar."%' OR
										  rd.CommoditySub LIKE '%".$fbuscar."%' OR
										  rd.Descripcion LIKE '%".utf8_decode($fbuscar)."%' OR
										  r.CodCentroCosto LIKE '%".utf8_decode($fbuscar)."%' OR
										  c.NroCotizacionProv LIKE '%".utf8_decode($fbuscar)."%' OR
										  c.CodProveedor LIKE '%".utf8_decode($fbuscar)."%' OR
										  c.NomProveedor LIKE '%".utf8_decode($fbuscar)."%')";
	elseif ($sltbuscar == "rd.CodItem, rd.CommoditySub") $filtro.=" AND (rd.CodItem LIKE '%".$fbuscar."%' OR
																		 rd.CommoditySub LIKE '%".$fbuscar."%')";
	else $filtro.=" AND $sltbuscar LIKE '%".$fbuscar."%'";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
if ($fmonto != "") {
	$cmonto = "checked";
	if ($fmonto == "C") $filtro.="AND (SELECT SUM(c3.Total) AS Total
									   FROM lg_cotizacion c3
									   WHERE (c3.CotizacionNumero = c.CotizacionNumero)
									   GROUP BY CotizacionNumero) > 0";
	if ($fmonto == "S") $filtro.="AND (SELECT SUM(c3.Total) AS Total
									   FROM lg_cotizacion c3
									   WHERE (c3.CotizacionNumero = c.CotizacionNumero)
									   GROUP BY CotizacionNumero) = 0";
} else $dmonto = "disabled";
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
		<td class="titulo">Listar Invitaciones de Cotizaciones</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_cotizaciones_invitaciones_lista.php" method="post">
<input type="hidden" id="concepto" name="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;" onchange="getOptions(this.value, 'dependencia', 'fdependencia', '300');">
				<option value=""></option>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td width="125" align="right">F. Invitaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cfinvitacion?> onclick="chkFiltro_2(this.checked, 'finvitaciond', 'finvitacionh');" />
			<input type="text" name="finvitaciond" id="finvitaciond" style="width:75px;" maxlength="10" value="<?=$finvitaciond?>" <?=$dinvitacion?> /> - 
			<input type="text" name="finvitacionh" id="finvitacionh" style="width:75px;" maxlength="10" value="<?=$finvitacionh?>" <?=$dinvitacion?> />
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cbuscar?> onclick="chkFiltro_2(this.checked, 'sltbuscar', 'fbuscar')" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-REQUERIMIENTOS-DETALLE", $sltbuscar, 0)?>
			</select>
		</td>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
            <select name="fedoreg" id="fedoreg" style="width:144px;" <?=$dedoreg?>>
                <?=loadSelectValores("ESTADO-REQUERIMIENTO-DETALLE", $fedoreg, 1)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" style="width:195px;" <?=$dbuscar?> />
		</td>
		<td align="right">Por Monto</td>
		<td>
            <input type="checkbox" <?=$cmonto?> onclick="chkFiltro(this.checked, 'fmonto');" />
        	<input type="text" name="fmonto" id="fmonto" value="<?=$fmonto?>" style="width:140px;" <?=$dmonto?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
<br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<!--<input type="button" class="btLista" id="btCotizar" value="Cotizar" onclick="cargarOpcion(this.form, 'lg_cotizaciones_invitaciones_cotizar.php?', 'BLANK', 'height=600, width=1100, left=100, top=0, resizable=no');" />-->
            
			<input type="button" class="btLista" id="btImprimir" value="Imprimir"  onclick="cargarOpcion(this.form, 'lg_cotizaciones_invitar_pdf.php?', 'BLANK', 'height=800, width=800, left=100, top=0, resizable=no');" />
            
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'cotizaciones', 'eliminar', 'lg')" /> | 
            
			<input type="button" id="btDisponibilidad" value="Disponibilidad Presupuestaria" onclick="cargarOpcion(this.form, 'lg_disponibilidad_presupuestaria_invitacion.php?', 'BLANK', 'height=550, width=1050, left=100, top=0, resizable=no');" />
		</td>
	</tr>
</table>

<center>
<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">F. Invitaci&oacute;n</th>
		<th scope="col" width="100"># Solicitud Cotizaci&oacute;n</th>
		<th scope="col" width="100">Proveedor</th>
		<th scope="col">Raz&oacute;n Social</th>
		<th scope="col" width="100">Total Cotizado</th>
		<th scope="col" width="75">L&iacute;neas</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto los requerimientos para stock de almacen
	$sql = "SELECT
				c.*,
				SUM(c.Total) AS Total,				
				(SELECT COUNT(*)
				 FROM
					lg_requerimientosdet rd2
					LEFT JOIN lg_itemmast i2 ON (rd2.CodItem = i2.CodItem)
					LEFT JOIN lg_commoditysub cs2 ON (rd2.CommoditySub = cs2.Codigo)
					INNER JOIN mastorganismos o2 ON (rd2.CodOrganismo = o2.CodOrganismo)
					INNER JOIN lg_requerimientos r2 ON (rd2.CodRequerimiento = r2.CodRequerimiento)
					INNER JOIN mastdependencias d2 ON (r2.CodDependencia = d2.CodDependencia)
					INNER JOIN lg_cotizacion c2 ON (rd2.CodOrganismo = c2.CodOrganismo AND 
													rd2.CodRequerimiento = c2.CodRequerimiento AND 
													rd2.Secuencia = c2.Secuencia)
				 WHERE c2.NroCotizacionProv = c.NroCotizacionProv) AS Nrolineas
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
			WHERE 1 $filtro
			GROUP BY CodProveedor, NroCotizacionProv
			ORDER BY NroCotizacionProv";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); imprimirAjaxResponse('accion=cotizaciones_invitaciones_ver_detalles&nrocotizacionprov='+ this.id, 'invitacion_detalles', 'lg');" id="<?=$field['NroCotizacionProv']?>">
			<td align="center"><?=formatFechaDMA($field['FechaInvitacion'])?></td>
            <td align="center"><?=$field['NroSolicitudCotizacion']?></td>
            <td align="center"><?=$field['CodProveedor']?></td>
            <td><?=($field['NomProveedor'])?></td>
            <td align="right"><?=number_format($field['Total'], 2, ',', '.')?></td>
            <td align="center"><?=$field['Nrolineas']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>

<div style="overflow:scroll; width:1000px; height:200px;">
<table width="2000" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="50">Uni.</th>
		<th scope="col" width="75">Cant.</th>
		<th scope="col" width="75">Precio Unit.</th>
		<th scope="col" width="75">Precio Unit Iva.</th>
		<th scope="col" width="75">Precio Cantidad</th>
		<th scope="col" width="75">Total</th>
		<th scope="col" width="50">Exon.</th>
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="50">#</th>
		<th scope="col" width="100">F.Requerida</th>
		<th scope="col" width="75">C. Costos</th>
		<th scope="col" width="400">Observaciones</th>
	</tr>
    </thead>
    
    <tbody id="invitacion_detalles">
    </tbody>
</table>
</div>
</center>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>
