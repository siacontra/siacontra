<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tasa de Intereses</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tasa_intereses.php" method="POST">
<table width="400" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'tasa_intereses_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'tasa_intereses_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'tasa_intereses_ver.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" style="display:none" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'tasa_intereses.php', '1', 'TASA-INTERESES');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'tasa_intereses_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" style="display:none" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="400" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Periodo</th>
		<th scope="col">Porcentaje</th>
		<th width="125" scope="col">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM masttasainteres";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo";
		elseif ($field['Estado'] == "I") $status = "Inactivo";
		//---------------------------------------------------
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['Periodo']?>">
			<td align="center"><?=$field['Periodo']?></td>
			<td align="right"><?=number_format($field['Porcentaje'], 2, ',', '.')?></td>
			<td align="center"><?=$status?></td>
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