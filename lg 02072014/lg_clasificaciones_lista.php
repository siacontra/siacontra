<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
$filtro = "";
if ($buscar != "") $filtro .= " AND (c.Clasificacion LIKE '%".$buscar."%' OR c.Descripcion LIKE '%".$buscar."%')";
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

<body onload="document.getElementById('buscar').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaciones</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_clasificaciones_lista.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="buscar" id="buscar" value="<?=$buscar?>" size="30" /></td>
		<td align="right">
			<input type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'lg_clasificaciones_form.php?opcion=nuevo');" />
			<input type="button" class="btLista" id="btModificar" value="Modificar" onclick="cargarOpcion(this.form, 'lg_clasificaciones_form.php?opcion=modificar', 'SELF');" />
			<input type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'lg_clasificaciones_form.php?opcion=ver', 'BLANK', 'height=400, width=675, left=100, top=0, resizable=no');" />
			<input type="button" class="btLista" id="btBorrar" value="Borrar" onclick="opcionRegistro(this.form, document.getElementById('registro').value, 'clasificaciones', 'eliminar', 'lg')" />
		</td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th width="75" scope="col">Clasificaci&oacute;n</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Compras</th>
		<th width="150" scope="col">Tipo de Requerimiento</th>
		<th width="50" scope="col">Estado</th>
	</tr>
    </thead>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				c.*,
				m.Descripcion AS NomTipoRequerimiento
			FROM
				lg_clasificacion c
				INNER JOIN mastmiscelaneosdet m ON (c.TipoRequerimiento = m.CodDetalle AND m.CodMaestro = 'TIPOREQ')
			WHERE 1 $filtro
			ORDER BY Clasificacion";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['Clasificacion']?>">
			<td align="center"><?=$field['Clasificacion']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=printFlagCompras($field['ReqOrdenCompra'])?></td>
			<td><?=($field['NomTipoRequerimiento'])?></td>
            <td align="center"><?=printValores("ESTADO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>