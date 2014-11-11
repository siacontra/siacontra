<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tabla de Sueldos Minimos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="sueldos_minimos.php" method="POST">
<table width="300px" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'sueldos_minimos_nuevo.php');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, document.getElementById('seleccion').value, 'SUELDOS-MINIMOS');" />
		</td>
	</tr>
</table>

<input type="hidden" name="seleccion" id="seleccion" />
<table width="300px" class="tblLista">
	<tr class="trListaHead">
		<th width="50" scope="col">#</th>
		<th scope="col">Periodo</th>
		<th width="125" scope="col">Monto</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM mastsueldosmin $filtro ORDER BY secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		?>
		<tr class="trListaBody" onclick="mClk(this, 'seleccion');" id="<?=$field['Secuencia']?>">
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$field['Periodo']?></td>
			<td align="right"><?=number_format($field['Monto'], 2, ',', '.')?></td>
		</tr>
        <?
	}
?>
</table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>