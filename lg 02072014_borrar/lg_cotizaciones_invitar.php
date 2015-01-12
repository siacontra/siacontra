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
	$fordenar = "r.CodInterno, rd.Secuencia, rd.CodRequerimiento";
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled"; 
if ($fdependencia != "") { $cdependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fdependencia."')"; } else $ddependencia = "disabled"; 
if ($fccosto != "") { $cccosto = "checked"; $filtro.=" AND (rd.CodCentroCosto = '".$fccosto."')"; } else $dccosto = "disabled"; 
if ($fclasificacion != "") { $cclasificacion = "checked"; $filtro.=" AND (r.Clasificacion = '".$fclasificacion."')"; } else $dclasificacion = "disabled"; 
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (rd.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled"; 
if ($fdirigido != "") { $cdirigido = "checked"; $filtro.=" AND (r.TipoClasificacion = '".$fdirigido."')"; } else $ddirigido = "disabled"; 
if ($fbuscar != "") { 
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (rd.CodRequerimiento LIKE '%".$fbuscar."%' OR
										  rd.CodItem LIKE '%".$fbuscar."%' OR
										  rd.CommoditySub LIKE '%".$fbuscar."%' OR
										  rd.Descripcion LIKE '%".utf8_decode($fbuscar)."%' OR
										  r.CodCentroCosto LIKE '%".utf8_decode($fbuscar)."%')";
	elseif ($sltbuscar == "rd.CodItem, rd.CommoditySub") $filtro.=" AND (rd.CodItem LIKE '%".$fbuscar."%' OR
																		 rd.CommoditySub LIKE '%".$fbuscar."%')";
	else $filtro.=" AND $sltbuscar LIKE '%".$fbuscar."%'";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
if ($fordenar != "") $cordenar = "checked";
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

	<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='js/funcionalidadCes.js' charset="utf-8"></script>
    <!--*********************************************** -->
    
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
		<td class="titulo">Invitar/Cotizar Proveedores</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_cotizaciones_invitar.php" method="post">
<input type="hidden" id="concepto" name="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;" onchange="getOptions(this.value, 'dependencia', 'fdependencia', '300');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right">Dirigido a:</td>
		<td>
			<input type="checkbox" <?=$cdirigido?> onclick="chkFiltro(this.checked, 'fdirigido')" />
			<select name="fdirigido" id="fdirigido" style="width:100px;" <?=$ddirigido?>>
				<option value=""></option>
				<?=loadSelectValores("DIRIGIDO", $fdirigido, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia')" />
            <span>
			<select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
				<option value=''></option>
				<?=getDependencias($fdependencia, $forganismo, 3);?>
			</select>
            </span>
		</td>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cccosto?> onclick="chkFiltroLista(this.checked, 'fccosto', 'nomccosto', 'btCCosto');" />
			<input type="text" name="fccosto" id="fccosto" style="width:60px;" value="<?=$fccosto?>" readonly="readonly" />
			<input type="hidden" name="nomccosto" id="nomccosto" />
			<input type="button" value="..." id="btCCosto" onclick="cargarVentana(this.form, 'listado_centro_costos.php?cod=fccosto&nom=nomccosto', 'height=600, width=1100, left=50, top=50, resizable=yes');" <?=$dccosto?> />
		</td>
	</tr>
	<tr>
		<td align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cclasificacion?> onclick="chkFiltro(this.checked, 'fclasificacion');" />
			<select name="fclasificacion" id="fclasificacion" style="width:300px;" <?=$dclasificacion?>>
				<option value=""></option>
				<?=loadSelect("lg_clasificacion", "Clasificacion", "Descripcion", $fclasificacion, 0)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cbuscar?> onclick="chkFiltro_2(this.checked, 'sltbuscar', 'fbuscar')" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-REQUERIMIENTOS-DETALLE", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="this.checked=!this.checked;" />
            <select name="fedoreg" id="fedoreg" style="width:144px;" <?=$dedoreg?>>
                <?=loadSelectValores("ESTADO-REQUERIMIENTO-DETALLE", $fedoreg, 1)?>
            </select>
		</td>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" style="width:195px;" <?=$dbuscar?> />
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td colspan="4">
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:144px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-REQUERIMIENTO-DETALLE", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center>
</form>
<br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '1', 2)">Stock</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '2', 2);">Commodities</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frm_stock" id="frm_stock" action="">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_stock"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btInvitar_stock" value="Invitar" onclick="cargarOpcionMultiple(this.form, 'lg_cotizaciones_invitar_proveedor.php?', 'BLANK', 'height=725, width=875, left=100, top=100, resizable=no', 'stock', 'registro', false);" />
            
			<input type="button" class="btLista" id="btCotizar_stock" value="Cotizar" onclick="cargarOpcionMultiple(this.form, 'lg_cotizaciones_invitar_cotizar.php?', 'BLANK', 'height=500, width=1075, left=100, top=100, resizable=no', 'stock', 'registro', false);" /> | 
            
			<!--<input type="button" class="btLista" id="btVer_stock" value="Ver" onclick="cargarOpcionMultiple(this.form, 'lg_requerimientos_form.php?opcion=ver', 'BLANK', 'height=700, width=950, left=100, top=0, resizable=no', 'stock', 'registro', false);" /> | -->
            
            <!-- BOTON PARA REALIZAR EVALUACION CUALITATIVA-->
            <!--<input type="button" class="btLista" style="width:125px;" id="botonEvaluaCuali" value="Evaluaci&oacute;n Cualitativa" onclick="cargarOpcionMultiple(this.form, 'lg_evaluacion_cualitativa.php?opcion=ver', 'BLANK', 'height=700, width=950, left=100, top=0, resizable=no', 'stock', 'registro', false);" />--> 
            <!-- BOTON PARA REALIZAR EVALUACION CUALITATIVA-->
            
            <!-- BOTON PARA REALIZAR EL ACTA DE INICIO-->
            <input type="button" class="btLista" style="width:125px;" id="botonActaInicio" value="Acta Inicio" onclick="iniciarActaInicioCompra(this.form, 'lg_acta_inicio_compra.php?tipoReque=stock&opcion=ver', 'SELF', 'height=700, width=950, left=100, top=0, resizable=no', 'stock', 'registro', false);" /> | 
            <!-- BOTON PARA REALIZAR EL ACTA DE INICIO-->
            
			<input type="button" style="width:125px;" id="btCuadro_stock" value="Cuadro Comparativo" onclick="cargarOpcionMultiple(this.form, 'lg_cuadro_comparativo_pdf.php?', 'BLANK', 'height=600, width=950, left=100, top=0, resizable=no', 'stock', 'registro', true);" />
		</td>
	</tr>
</table>

<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="2800" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="35">#</th>
		<th scope="col" width="100">Item</th>
		<th scope="col" width="400">Descripci&oacute;n</th>
		<th scope="col" width="50">Uni.</th>
		<th scope="col" width="75">Cant.</th>
		<th scope="col" width="50"># Inv.</th>
		<th scope="col" width="75">C.Costo</th>
		<th scope="col" width="100">Prioridad</th>
		<th scope="col" width="150">Linea-Familia</th>
		<th scope="col">Comentario</th>
		<th scope="col" width="100">F.Requerida</th>
		<th scope="col" width="300">Proveedor Asignado</th>
		<th scope="col" width="100">F. Asignaci&oacute;n</th>
		<th scope="col" width="300">Proveedor Sugerido</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto los requerimientos para stock de almacen
	$sql = "SELECT
				rd.*,
				o.Organismo,
				d.Dependencia,
				r.CodInterno,
				r.Clasificacion,
				r.FechaRequerida,
				i.CodLinea,
				i.CodFamilia,
				r.Prioridad,
				c.CodProveedor,
				c.FechaDocumento,
				c.FlagAsignado,
				c.Numero,
				p.NomCompleto As NomProveedorSugerido,
				c2.NomProveedor AS NomProveedorAsignado
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN mastorganismos o ON (rd.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				LEFT JOIN lg_cotizacion c ON (rd.CodOrganismo = c.CodOrganismo AND
											  rd.CodRequerimiento = c.CodRequerimiento AND
											  rd.Secuencia = c.Secuencia)
				LEFT JOIN lg_cotizacion c2 ON (rd.CodOrganismo = c2.CodOrganismo AND
											   rd.CodRequerimiento = c2.CodRequerimiento AND
											   rd.Secuencia = c2.Secuencia AND
											   c2.FlagAsignado = 'S')
				LEFT JOIN mastpersonas p ON (r.ProveedorSugerido = p.CodPersona)
			WHERE
				rd.FlagCompraAlmacen = 'C' AND
				r.FlagCajaChica <> 'S'
				$filtro
			GROUP BY CodOrganismo, CodRequerimiento, Secuencia
			ORDER BY $fordenar";
	$query_stock = mysql_query($sql) or die ($sql.mysql_error());
	$rows_stock = mysql_num_rows($query_stock);
	
	$t = 0;
	$CodInternoAux = '';
	while ($field_stock = mysql_fetch_array($query_stock)) {
	
		$id = $field_stock['CodOrganismo'].".".$field_stock['CodRequerimiento'].".".$field_stock['Secuencia'].".".$field_stock['Numero'];
		if (strlen($field_stock['Comentarios']) > 200) $comentarios = substr($field_stock['Comentarios'], 0, 200)."...";
		else $comentarios = $field_stock['Comentarios'];
		
		
			if($field_stock['CodInterno'] != $CodInternoAux)
			{
				$CodInternoAux = $field_stock['CodInterno'];
				$t++;
			}
		?>
		<tr class="trListaBody <?=estiloGrupo($t)?>"  onclick="mClkMultiReque(this,<?=$t?>);" id="row_<?=$id?>">
			<td align="center"><input type="checkbox" name="stock" id="<?=$id?>" value="<?=$id?>" style="display:none" /><?=$field_stock['CodInterno']?></td>
			<td align="center"><?=$field_stock['Secuencia']?></td>
			<td align="center"><?=$field_stock['CodItem']?></td>
			<td><?=($field_stock['Descripcion'])?></td>
			<td align="center"><?=$field_stock['CodUnidad']?></td>
			<td align="right"><?=number_format($field_stock['CantidadPedida'], 2, ',', '.')?></td>
			<td align="center"><?=$field_stock['CotizacionRegistros']?></td>
			<td align="center"><?=$field_stock['CodCentroCosto']?></td>
			<td align="center"><?=printValores("PRIORIDAD", $field_stock['Prioridad'])?></td>
			<td align="center"><?=$field_stock['CodLinea']?> - <?=$field_stock['CodFamilia']?></td>
			<td title="<?=$field_stock['Comentarios']?>"><?=($comentarios)?></td>
			<td align="center"><?=formatFechaDMA($field_stock['FechaRequerida'])?></td>
			<td><?=($field_stock['NomProveedorAsignado'])?></td>
			<td align="center"><?=formatFechaDMA($field_stock['CotizacionFechaAsignacion'])?></td>
			<td><?=($field_stock['NomProveedorSugerido'])?></td>
		</tr>
		<?
		
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
</div>

<div id="tab2" style="display:none;">
<form name="frm_comm" id="frm_comm">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_comm"></div></td>
		<td align="right">
			<input type="button" class="btLista" id="btInvitar_comm" value="Invitar" onclick="cargarOpcionMultiple(this.form, 'lg_cotizaciones_invitar_proveedor.php?', 'BLANK', 'height=725, width=875, left=100, top=100, resizable=no', 'commodity', 'registro', true);" />
            
			<input type="button" class="btLista" id="btCotizar_comm" value="Cotizar" onclick="cargarOpcionMultiple(this.form, 'lg_cotizaciones_invitar_cotizar.php?', 'BLANK', 'height=500, width=1075, left=100, top=100, resizable=no', 'commodity', 'registro', false);" /> | 
            
			<!--<input type="button" class="btLista" id="btVer_comm" value="Ver" onclick="cargarOpcionMultiple(this.form, 'lg_requerimientos_form.php?opcion=ver', 'BLANK', 'height=700, width=950, left=100, top=0, resizable=no', 'commodity', 'registro', false);" /> | -->
           
            <!-- BOTON PARA REALIZAR EL ACTA DE INICIO-->
            <input type="button" class="btLista" style="width:125px;" id="botonActaInicio" value="Acta Inicio" onclick="iniciarActaInicioCompra(this.form, 'lg_acta_inicio_compra.php?tipoReque=commodity&opcion=ver', 'SELF', 'height=700, width=950, left=100, top=0, resizable=no', 'commodity', 'registro', true);" /> | 
            <!-- BOTON PARA REALIZAR EL ACTA DE INICIO--> 
            
			<input type="button" style="width:125px;" id="btCuadro_comm" value="Cuadro Comparativo" onclick="cargarOpcionMultiple(this.form, 'lg_cuadro_comparativo_pdf.php?', 'BLANK', 'height=600, width=950, left=100, top=0, resizable=no', 'commodity', 'registro', true);" />
		</td>
	</tr>
</table>

<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="2750" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="35">#</th>
		<th scope="col" width="100">Commodity</th>
		<th scope="col" width="400">Descripci&oacute;n</th>
		<th scope="col" width="50">Uni.</th>
		<th scope="col" width="75">Cant.</th>
		<th scope="col" width="50"># Inv.</th>
		<th scope="col" width="75">C.Costo</th>
		<th scope="col" width="100">Prioridad</th>
		<th scope="col">Comentario</th>
		<th scope="col" width="100">F.Requerida</th>
		<th scope="col" width="300">Proveedor Asignado</th>
		<th scope="col" width="100">F. Asignaci&oacute;n</th>
		<th scope="col" width="300">Proveedor Sugerido</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto los requerimientos para stock de almacen
	$sql = "SELECT
				rd.*,
				o.Organismo,
				d.Dependencia,
				r.CodInterno,
				r.Clasificacion,
				r.FechaRequerida,
				r.Prioridad,
				c.CodProveedor,
				c.FechaDocumento,
				c.FlagAsignado,
				c.Numero,
				p.NomCompleto As NomProveedorSugerido,
				c2.NomProveedor AS NomProveedorAsignado
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_commoditysub cs ON (rd.CommoditySub = cs.Codigo)
				INNER JOIN mastorganismos o ON (rd.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				LEFT JOIN lg_cotizacion c ON (rd.CodOrganismo = c.CodOrganismo AND
											  rd.CodRequerimiento = c.CodRequerimiento AND
											  rd.Secuencia = c.Secuencia)
				LEFT JOIN lg_cotizacion c2 ON (rd.CodOrganismo = c2.CodOrganismo AND
											   rd.CodRequerimiento = c2.CodRequerimiento AND
											   rd.Secuencia = c2.Secuencia AND
											   c2.FlagAsignado = 'S')
				LEFT JOIN mastpersonas p ON (r.ProveedorSugerido = p.CodPersona)
			WHERE
				rd.FlagCompraAlmacen = 'C' AND
				r.FlagCajaChica <> 'S' $filtro
			GROUP BY CodOrganismo, CodRequerimiento, Secuencia
			ORDER BY $fordenar";
	$query_comm = mysql_query($sql) or die ($sql.mysql_error());
	$rows_comm = mysql_num_rows($query_comm);
	
	$t = 0;
	$CodInternoAux = '';
	
	while ($field_comm = mysql_fetch_array($query_comm)) {
		$id = $field_comm['CodOrganismo'].".".$field_comm['CodRequerimiento'].".".$field_comm['Secuencia'].".".$field_comm['Numero'];
		if (strlen($field_comm['Comentarios']) > 200) $comentarios = substr($field_comm['Comentarios'], 0, 200)."...";
		else $comentarios = $field_comm['Comentarios'];

		if($field_comm['CodInterno'] != $CodInternoAux)
		{
			$CodInternoAux = $field_comm['CodInterno'];
			$t++;
		}
			
		?>
		<tr class="trListaBody <?=estiloGrupo($t)?>" onclick="mClkMultiReque(this,<?=$t?>);" id="row_<?=$id?>">
			<td align="center">
            	<input type="checkbox" name="commodity" id="<?=$id?>" value="<?=$id?>" style="display:none" />
				<?=$field_comm['CodInterno']?>
            </td>
			<td align="center"><?=$field_comm['Secuencia']?></td>
			<td align="center"><?=$field_comm['CommoditySub']?></td>
			<td><?=($field_comm['Descripcion'])?></td>
			<td align="center"><?=$field_comm['CodUnidad']?></td>
			<td align="right"><?=number_format($field_comm['CantidadPedida'], 2, ',', '.')?></td>
			<td align="center"><?=$field_comm['CotizacionRegistros']?></td>
			<td align="center"><?=$field_comm['CodCentroCosto']?></td>
			<td align="center"><?=printValores("PRIORIDAD", $field_comm['Prioridad'])?></td>
			<td title="<?=$field_comm['Comentarios']?>"><?=($comentarios)?></td>
			<td align="center"><?=formatFechaDMA($field_comm['FechaRequerida'])?></td>
			<td><?=($field_comm['NomProveedorAsignado'])?></td>
			<td align="center"><?=formatFechaDMA($field_comm['CotizacionFechaAsignacion'])?></td>
			<td><?=($field_comm['NomProveedorSugerido'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>
</div>

<script type="text/javascript" language="javascript">
	totalRegistrosStock(parseInt(<?=$rows_stock?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	totalRegistrosCommodity(parseInt(<?=$rows_comm?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>
