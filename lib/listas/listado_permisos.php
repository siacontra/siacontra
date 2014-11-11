<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
include("../fphp.php");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Permisos del Empleado</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:260px;">
<table width="950" class="tblLista">
	<thead>
    <tr>
        <th scope="col" width="25" rowspan="2">&nbsp;</th>
        <th scope="col" width="70" rowspan="2"># Permiso</th>
        <th scope="col" width="60" rowspan="2">Fecha Registro</th>
        <th scope="col" rowspan="2">Tipo Permiso</th>
        <th scope="col" width="150" rowspan="2">Tipo Falta</th>
        <th scope="col" colspan="2">Fecha</th>
        <th scope="col" colspan="2">Hora</th>
        <th scope="col" width="60" rowspan="2">Estado</th>
    </tr>
    <tr>
        <th scope="col" width="60">Desde</th>
        <th scope="col" width="60">Hasta</th>
        <th scope="col" width="60">Desde</th>
        <th scope="col" width="60">Hasta</th>>
        </tr>
    </thead>
    
    <tbody>
    <?php
	$sql = "SELECT
				pm.CodPermiso,
				pm.FechaIngreso,
				pm.FechaDesde,
				pm.FechaHasta,
				pm.HoraDesde,
				pm.HoraHasta,
				pm.Estado,
				md1.Descripcion AS TipoPermiso,
				md2.Descripcion AS TipoFalta
			FROM
				rh_permisos pm
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pm.TipoPermiso AND
													 md1.CodMaestro = 'PERMISOS')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = pm.TipoFalta AND
													 md2.CodMaestro = 'TIPOFALTAS')
			WHERE
				pm.CodPersona = '".$CodPersona."' AND
				((pm.FechaDesde >= '".formatFechaAMD($FechaInicio)."' AND pm.FechaDesde <= '".formatFechaAMD($FechaFin)."') OR 
				 (pm.FechaHasta >= '".formatFechaAMD($FechaInicio)."' AND pm.FechaHasta <= '".formatFechaAMD($FechaFin)."'))
			ORDER BY CodPermiso";
	$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalles = mysql_fetch_array($query_detalles)) {	$nro_detalles++;
		?>
		<tr class="trListaBody">
			<th>
				<?=$nro_detalles?>
			</th>
			<td align="center">
				<?=$field_detalles['CodPermiso']?>
			</td>
			<td align="center">
				<?=formatFechaDMA($field_detalles['FechaIngreso'])?>
			</td>
			<td>
				<?=htmlentities($field_detalles['TipoPermiso'])?>
			</td>
			<td align="center">
				<?=htmlentities($field_detalles['TipoFalta'])?>
			</td>
			<td align="center">
				<?=formatFechaDMA($field_detalles['FechaDesde'])?>
			</td>
			<td align="center">
				<?=formatFechaDMA($field_detalles['FechaHasta'])?>
			</td>
			<td align="center">
				<?=formatHora12($field_detalles['HoraDesde'])?>
			</td>
			<td align="center">
            	<?=formatHora12($field_detalles['HoraHasta'])?>
			</td>
			<td align="center">
            	<?=printValoresGeneral("ESTADO-PERMISOS", $field_detalles['Estado'])?>
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
</center>
</body>
</html>