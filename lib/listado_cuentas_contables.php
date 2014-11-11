<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Plan de Cuentas</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_cuentas_contables.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:700px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Cuenta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="125" scope="col">Tipo de Cuenta</th>
		<th width="75" scope="col">Naturaleza</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") $filtro = "WHERE (ampc.CodCuenta LIKE '%".$filtro."%' OR ampc.Descripcion LIKE '%".$filtro."%' OR mmd.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro = "";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				ampc.*,
				mmd.Descripcion AS NomTipoCuenta
			FROM
				ac_mastplancuenta ampc
				INNER JOIN mastmiscelaneosdet mmd ON (ampc.TipoCuenta = mmd.CodDetalle AND mmd.CodMaestro = 'CUENTAS')
			$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		if ($field['Naturaleza'] == "D") $naturaleza = "Deudora"; else $naturaleza = "Acreeedora";
		
		$sql = "SELECT * FROM ac_mastplancuenta WHERE CodCuenta LIKE '".$field['CodCuenta']."%' AND CodCuenta <> '".$field['CodCuenta']."' ORDER BY `CodCuenta` ASC";
		$query_sub = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($rows) == 0 && strlen($field['CodCuenta']) >= 3) {
			?>
			<tr class="trListaBody" onclick="selListado('<?=$field['CodCuenta']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCuenta']?>">
				<td><?=$field['CodCuenta']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=($field['NomTipoCuenta'])?></td>
				<td align="center"><?=($naturaleza)?></td>
				<td align="center"><?=($status)?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>));
</script>
</body>
</html>
