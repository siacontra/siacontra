<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("../lib/rh_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/rh_fscript.js"></script>
</head>

<body onload="document.getElementById('buscar').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Grados de Calificaci&oacute;n General</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="rh_grados_calificacion_general.php" method="POST">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="buscar" id="buscar" value="<?=$buscar?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'rh_grados_calificacion_general_form.php?accion=INSERTAR');" />
			<input type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'rh_grados_calificacion_general_form.php?accion=ACTUALIZAR', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'rh_grados_calificacion_general_form.php?accion=VER', 'BLANK', 'height=300, width=750, left=150, top=150, resizable=no');" />
			<input type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'grados_calificacion_general_form', 'ELIMINAR', 'rh_fphp_ajax');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Escala</th>
		<th width="300" scope="col">Rango de Actuaci&oacute;n</th>
		<th scope="col">Definici&oacute;n de los Rangos de Actuaci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$buscar = trim($buscar); 
	if ($buscar != "")
		$filtro = "AND (Descripcion LIKE '%".utf8_decode($buscar)."%' OR
						Definicion LIKE '%".utf8_decode($buscar)."%' OR
						PuntajeMin LIKE '%".$buscar."%' OR
						PuntajeMax LIKE '%".$buscar."%')";
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM rh_gradoscalificacion WHERE 1 $filtro ORDER BY PuntajeMin";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['Grado']?>">
			<td align="center"><?=$field['PuntajeMin']?> - <?=$field['PuntajeMax']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td><?=($field['Definicion'])?></td>
			<td align="center"><?=printValores("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>