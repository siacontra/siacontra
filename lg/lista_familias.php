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
		<td class="titulo">Lista de Familias</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_familias.php" method="POST">
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Estado</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $where = "WHERE (f.CodFamilia LIKE '%".$filtro."%' OR f.Descripcion LIKE '%".$filtro."%' OR l.CodLinea LIKE '%".$filtro."%' OR l.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro="";
	
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				f.*,
				l.Descripcion AS NomLinea
			FROM 
				lg_clasefamilia f
				INNER JOIN lg_claselinea l ON (f.CodLinea = l.CodLinea)
			$where";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);		
		$status = printValores("ESTADO", $field['Estado']);	
		
		if ($agrupa != $field['CodLinea']) {
			$agrupa = $field['CodLinea'];
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field['CodLinea']?></td>
				<td colspan="2"><?=($field['NomLinea'])?></td>
			</tr>
			<?
		}
			
		?>
		<tr class="trListaBody" id="<?=$field['CodLinea']?>|<?=$field['CodFamilia']?>" onclick="mClk(this, 'registro'); selFamilia('<?=$campo1?>', '<?=$campo2?>', '<?=$field['NomLinea']?>', '<?=$field['Descripcion']?>');">
			<td align="center"><?=$field['CodFamilia']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align='center'><?=$status?></td>
		</tr>
		<?
	}
	?>
</table>
</form>

<script type="text/javascript" language="javascript">
	numRegistros(<?=$rows?>);
</script>
</body>
</html>