<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
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
		<td class="titulo">Calificativos</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="listado_calificativos.php">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<?php
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='".$organismo."' AND Periodo='".$periodo."' AND Secuencia='".$secuencia."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<table width="300" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="300" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Valor</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT * FROM rh_evaluacionperiodovalor WHERE CodOrganismo='".$organismo."' AND Periodo='".$periodo."' AND Secuencia='".$secuencia."' LIMIT $limit, $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$valor=number_format($field['Valor'], 2, ',', '.');
			
			if ($ventana == "lista") {
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoDetalle('<?=number_format($valor, 2, ',', '.')?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Rango']?>">
					<td align="center"><?=number_format($valor, 2, ',', '.')?></td>
					<td><?=$field["Descripcion"]?></td>
				</tr>
				<?
			}
			elseif ($ventana == "listaEvaluacionObjetivosMetas") {
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); listaEvaluacionObjetivosMetas('<?=number_format($valor, 2, ',', '.')?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Rango']?>">
					<td align="center"><?=number_format($valor, 2, ',', '.')?></td>
					<td><?=$field["Descripcion"]?></td>
				</tr>
				<?
			}
			elseif ($ventana == "listaEvaluacionDesempeno") {
				$id = strtoupper($field["Descripcion"]);
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); listaEvaluacionDesempeno('<?=number_format($valor, 2, ',', '.')?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$id?>">
					<td align="center"><?=number_format($valor, 2, ',', '.')?></td>
					<td><?=$field["Descripcion"]?></td>
				</tr>
				<?
			} else {
				?>
				<tr class="trListaBody" onclick="mClk(this, 'registro'); selCalificativo('<?=$valor?>');" id="<?=$field['Rango']?>">
					<td align="center"><?=number_format($valor, 2, ',', '.')?></td>
					<td><?=$field["Descripcion"]?></td>
				</tr>
				<?
			}
		}
	}
	$rows=(int)$rows;
	?>
	
</table>
</form>

<script type='text/javascript' language='javascript'>
	totalLista(<?=$registros?>);
</script>
</body>
</html>