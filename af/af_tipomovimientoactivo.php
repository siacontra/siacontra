<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("af_fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Movimiento Activos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="af_clasificacion_activos.php" method="POST">
<table width="825px" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="45" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'af_tipomovimientoactivo_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcionClasfActivos(this.form, document.getElementById('seleccion').value, 'af_tipomovimientoactivo_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcionClasfActivos(this.form, document.getElementById('seleccion').value, 'af_tipomovimientoactivo_ver.php', 'BLANK', 'height=200, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, document.getElementById('seleccion').value, 'CLASIFICACION-ACTIVOS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'af_tipomovimientoactivo_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="seleccion" id="seleccion" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:825px; height:400px;">
<table width="805px" class="tblLista">
<thead>
	<tr class="trListaHead">
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="60" scope="col">Tipo Movimiento</th>
		<th width="100" scope="col">Estado</th>
	</tr>
 </thead>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $filtro="WHERE (TipoMovimiento LIKE '%".$filtro."%' OR DescpMovimiento LIKE '%".$filtro."%')"; 
	else $filtro="";
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM af_tipomovimientos $filtro ORDER BY CodTipoMovimiento";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if($field['Estado']=='I') $status = 'Inactivo'; else $status = 'Activo';
		if($field['TipoMovimiento']=='IN') $t_movimiento = 'Incorporaci&oacute;n'; else $t_movimiento = 'Desincorporaci&oacute;n';
		?>
		<tr class="trListaBody" onclick="mClk(this, 'seleccion');" id="<?=$field['CodTipoMovimiento']?>">
			<td><?=$field['CodTipoMovimiento']?></td>
			<td><span <?=$style?>><?=htmlentities($field['DescpMovimiento'])?></span></td>
			<td align="center"><?=$t_movimiento?></td>
			<td align="center"><?=$status?></td>
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