<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Items</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_items.php" method="POST">
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'tipos_items_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'tipos_items_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'tipos_items_ver.php', 'BLANK', 'height=400, width=1024, left=50, top=100, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'tipos_items.php', '1', 'TIPOS-ITEMS', 'ELIMINAR');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'tipos_items_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Tipo de Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Estado</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $where = "WHERE (CodTipoItem LIKE '%".$filtro."%' OR Descripcion LIKE '%".$filtro."%')"; 
	else $filtro="";
	
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM lg_tipoitem $where";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);		
		$status = printValores("ESTADO", $field['Estado']);		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$field['CodTipoItem']?>">
			<td align="center"><?=$field['CodTipoItem']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align='center'><?=$status?></td>
		</tr>
		<?
	}
	?>
</table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=intval($rows)?>, "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
</script>
</body>
</html>