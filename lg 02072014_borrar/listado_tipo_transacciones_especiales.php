<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Tipo de Transacciones</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_tipo_transacciones.php" method="POST">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:600px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Transacci&oacute;n</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Estado</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $where = "AND (tt.CodTransaccion LIKE '%".$filtro."%' OR tt.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro="";
	
	//	CONSULTO LA TABLA
	$sql = "SELECT
				tt.CodOperacion,
				tt.Descripcion,
				tt.TipoMovimiento,
				tt.TipoDocGenerado,
				tt.Estado,
				td.Descripcion AS DocGenerado
			FROM 
				lg_operacioncommodity tt
				LEFT JOIN lg_tipodocumento td ON (tt.TipoDocGenerado = td.CodDocumento)
			WHERE 1 $where 
			ORDER BY tt.TipoMovimiento, tt.CodOperacion";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);		
		$status = printValores("ESTADO", $field['Estado']);	
		$tipo = printValores("TIPOMOVIMIENTO", $field['TipoMovimiento']);		
		$flagconsumo = printFlag($field['FlagVoucherConsumo']);	
		$flagajuste = printFlag($field['FlagVoucherAjuste']);
		
		if ($grupo != $field['TipoMovimiento']) {
			$grupo = $field['TipoMovimiento'];
			?>
			<tr class="trListaBody2">
				<td colspan="5"><?=$tipo?></td>
			</tr>
			<?
		}
		
		if ($ventana == "selListadoAgregarTransaccionEspecial") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoAgregarTransaccionEspecial('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field["TipoMovimiento"]?>', '<?=$field["TipoDocGenerado"]?>', '<?=($field["DocGenerado"])?>');" id="<?=$field['CodOperacion']?>">
                <td align="center"><?=$field['CodOperacion']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
            </tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodOperacion']?>">
                <td align="center"><?=$field['CodOperacion']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$flagconsumo?></td>
                <td align='center'><?=$flagajuste?></td>
                <td align='center'><?=$status?></td>
            </tr>
			<?
		}
	}
	?>
</table></div></td></tr></table>
</form>

<script type="text/javascript" language="javascript">
	numRegistros(<?=$rows?>);
</script>
</body>
</html>