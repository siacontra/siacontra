<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_pv.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pv.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificador Presupuestario</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="pv_clasificador_presupuestario.php" method="POST">
<table width="1025px" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="75" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'pv_clasificador_presupuestario_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, document.getElementById('seleccion').value, 'pv_clasificador_presupuestario_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, document.getElementById('seleccion').value, 'pv_clasificador_presupuestario_ver.php', 'BLANK', 'height=400, width=950, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, document.getElementById('seleccion').value, 'CLASIFICADOR-PRESUPUESTARIO');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pv_clasificador_presupuestario_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="seleccion" id="seleccion" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1025px; height:600px;">
<table width="1005px" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Partida</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $filtro="WHERE (cod_partida LIKE '%".$filtro."%' OR denominacion LIKE '%".$filtro."%')"; 
	else $filtro="";
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM pv_partida $filtro ORDER BY cod_partida";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$status = printValores("ESTADO", $field['Estado']);
		$style = stylePartida($field['nivel']);
		?>
		<tr class="trListaBody" onclick="mClk(this, 'seleccion');" id="<?=$field['cod_partida']?>">
			<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
			<td><span <?=$style?>><?=htmlentities($field['denominacion'])?></span></td>
			<td align="center"><span <?=$style?>><?=$status?></span></td>
		</tr>
        <?
	}
?>
</table>
</div></td></tr></table>
</form>

<script type="text/javascript" language="javascript">
	totalRegistros(<?=$rows?>, '<?=$_ADMIN?>', '<?=$_INSERT?>', '<?=$_UPDATE?>', '<?=$_DELETE?>');
</script>
</body>
</html>