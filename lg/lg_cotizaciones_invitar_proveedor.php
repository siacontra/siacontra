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
//	obtengo los registros seleccionados
$nrosel = 0;
$filtro = "";
$detalle = split(";", $registro);
foreach ($detalle as $linea) {
	list($organismo, $requerimiento, $secuencia, $numero) = split("[.]", $linea);
	if ($nrosel == 0) $filtro .= " WHERE (rd.CodRequerimiento = '".$requerimiento."' )";
	else $filtro .= " OR (rd.CodRequerimiento = '".$requerimiento."' )";
	$nrosel++;
}
//	------------------------------------
$flimite = getFechaFin(date("d-m-Y"), $_PARAMETRO['DIASLIMCOT']);
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

<body onload="document.getElementById('observaciones').focus();">
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
	<tr><!--Invitar Proveedores a Cotizar-->
		<td class="titulo">Seleccionar Proveedores</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 1, 2);">Prov. Seleccionados</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Ingresar Cotizaciones</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="lg_cotizaciones_invitar_proveedor.php" method="POST" onsubmit="return cotizaciones_invitar_proveedor(this, 'cotizaciones_invitar_proveedor');">
<table width="800px" class="tblForm">
	<tr>
	 	<td class="tagForm" width="100">Fecha Límite para Cotizar:</td>
		<td width="175"><input type="text" id="flimite" maxlength="10" style="width:60px;" value="<?=$flimite?>" onkeyup="setFechaDMA(this);" />*<em>(dd-mm-yyyy)</em></td>
		<td class="tagForm" width="100">Condiciones de Entrega:</td>
		<td><textarea id="condiciones" style="width:95%; height:40px;"></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3"><textarea id="observaciones" style="width:97%; height:40px;"></textarea></td>
	</tr>
	<tr>
		<td align="center" colspan="4">
        	<input type="submit" value="Aceptar" style="width:80px; " />
            <input type="button" value="Cancelar" style="width:80px;" onclick="window.close();" />
        </td>
	</tr>
</table>
</form>

<form name="frm_proveedor" id="frm_proveedor">
<div style="width:800px" class="divFormCaption">Proveedores</div>
<table width="800" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="abrirListadoInsertar('listado_personas.php?flagproveedor=S', 'proveedor', 'insertarLineaListado', 'cotizaciones_invitar_proveedor_insertar', 'lg', 'height=800, width=750, left=50, top=50', 'item');" />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'proveedor');" />
		</td>
	</tr>
</table>

<center>
<div style="overflow:scroll; width:800px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="25">#</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="125">Forma de Pago</th>
    </tr>
    </thead>    
    <tbody id="lista_proveedor"></tbody>
</table>
</div>
</center>
<input type="hidden" name="sel_proveedor" id="sel_proveedor" />
<input type="hidden" name="can_proveedor" id="can_proveedor" />
<input type="hidden" name="nro_proveedor" id="nro_proveedor" />
</form>

<div style="width:800px" class="divFormCaption">Requerimientos</div>
<div style="width:800px; text-align:left;" class="tblBotones">Seleccione un requerimiento para ver sus invitaciones</div>
<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tabreq">
            <!-- CSS Tabs -->
            <li id="lireq1" onclick="currentTab('tabreq', this);" class="current">
            	<a href="#" onclick="mostrarTab('tabreq', 1, 2);">Requerimientos a Invitar</a>
            </li>
            <li id="lireq2" onclick="currentTab('tabreq', this);">
            	<a href="#" onclick="mostrarTab('tabreq', 2, 2);">Invitaciones ya Realizadas</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tabreq1" style="display:block;">
<form name="frm_requerimiento" id="frm_requerimiento">
<input type="hidden" name="sel_requerimiento" id="sel_requerimiento" />
<center>
<div style="overflow:scroll; width:800px; height:250px;">
<table width="2000" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="50">#</th>
		<th scope="col" width="100">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="50">Uni.</th>
		<th scope="col" width="75">Cant.</th>
		<th scope="col" width="50"># Inv.</th>
		<th scope="col" width="100">C. Costos</th>
		<th scope="col" width="100">Prioridad</th>
		<th scope="col" width="150">Linea-Familia</th>
		<th scope="col" width="400">Comentario</th>
		<th scope="col" width="100">F.Requerida</th>
		<th scope="col" width="250">Dependencia</th>
	</tr>
    </thead>
    
    <tbody>
    <?php
	$sql = "SELECT
				rd.*,
				o.Organismo,
				d.Dependencia,
				r.Clasificacion,
				r.FechaRequerida,
				i.CodLinea,
				i.CodFamilia,
				r.Prioridad,
				r.CodInterno
			FROM
				lg_requerimientosdet rd
				LEFT JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				LEFT JOIN lg_commoditysub cs ON (rd.CommoditySub = cs.Codigo)
				INNER JOIN mastorganismos o ON (rd.CodOrganismo = o.CodOrganismo)
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
			$filtro
			ORDER BY Secuencia, CodRequerimiento, CodItem, CommoditySub";
	$query_req = mysql_query($sql) or die ($sql.mysql_error());
	$rows_req = mysql_num_rows($query_req);
	//	MUESTRO LA TABLA
	while ($field_req = mysql_fetch_array($query_req)) {
		if ($field_req['CodItem'] != "") $codigo = $field_req['CodItem']; else $codigo = $field_req['CommoditySub'];
		$id = $field_req['CodOrganismo'].".".$field_req['CodRequerimiento'].".".$field_req['Secuencia'];
		if (strlen($field_req['Comentarios']) > 200) $comentarios = substr($field_req['Comentarios'], 0, 200)."...";
		else $comentarios = $field_req['Comentarios'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_requerimiento');
        								 imprimirAjaxResponse('accion=cotizaciones_invitar_proveedor_ver_invitaciones&requerimiento='+this.id,
                                         					  'tab_invitaciones', 
                                                              'lg');" id="<?=$id?>">
			<td align="center">
            	<input type="hidden" name="requerimiento" value="<?=$id?>" />
            	<input type="hidden" name="cantidad" value="<?=$field_req['CantidadPedida']?>" />
            	<input type="hidden" name="flagexonerado" value="<?=$field_req['FlagExonerado']?>" />
				<?=$field_req['CodInterno']?>
            </td>
			<td align="center"><?=$field_req['Secuencia']?></td>
			<td align="center"><?=$codigo?></td>
			<td><?=($field_req['Descripcion'])?></td>
			<td align="center"><?=$field_req['CodUnidad']?></td>
			<td align="right"><?=number_format($field_req['CantidadPedida'], 4, ',', '.')?></td>
			<td align="center"><?=$field_req['CotizacionRegistro']?></td>
			<td align="center"><?=$field_req['CodCentroCosto']?></td>
			<td align="center"><?=printValores("PRIORIDAD", $field_req['Prioridad'])?></td>
			<td align="center"><?=$field_req['CodLinea']?> - <?=$field_req['CodFamilia']?></td>
			<td title="<?=$field_req['Comentarios']?>"><?=($comentarios)?></td>
			<td align="center"><?=formatFechaDMA($field_req['FechaRequerida'])?></td>
			<td><?=($field_req['Dependencia'])?></td>
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

<div id="tabreq2" style="display:none;">
<center>
<div style="overflow:scroll; width:800px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
    <tr class="trListaHead">
        <th scope="col" width="50">#</th>
        <th scope="col">Raz&oacute;n Social</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="75">Nro. Invitaci&oacute;n</th>
        <th scope="col" width="200">Condiciones de la Invitaci&oacute;n</th>
        <th scope="col" width="100">Fecha Invitaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="tab_invitaciones">
    </tbody>
</table>
</div>
</center>
</div>

</div>

<div id="tab2" style="display:none;"></div>

</body>
</html>
