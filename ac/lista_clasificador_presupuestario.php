<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Partidas Presupuestaria</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_clasificador_presupuestario.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="destino" id="destino" value="<?=$destino?>" />

<table width="100%" class="tblBotones">
  <tr class="tr6">
		<td><div id="rows"></div></td>
    	<td width="275" align="center">
			Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='<?=$_POST['filtro']?>' />
		</td>
  </tr>
</table>

<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Partida</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	if ($filtro!="") $filtro="WHERE (cp.cod_partida LIKE '%".$filtro."%' OR cp.denominacion LIKE '%".$filtro."%')"; 
	else $filtro="";
	//	CONSULTO LA TABLA
	$sql="SELECT
				cp.*,
				c.Descripcion AS NomCuenta,
				c.TipoSaldo
			FROM 
				pv_partida cp
				LEFT JOIN ac_mastplancuenta c ON (cp.CodCuenta = c.CodCuenta)
			$filtro 
			ORDER BY cod_partida";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$status = printValores("ESTADO", $field['Estado']);
		$style = stylePartida($field['nivel']);
		if ($destino == "selPartidaCuenta") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selPartidaCuenta('<?=htmlentities($field["denominacion"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field['CodCuenta']?>', '<?=htmlentities($field["NomCuenta"])?>');" id="<?=$field['cod_partida']?>">
				<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
				<td><span <?=$style?>><?=htmlentities($field['denominacion'])?></span></td>
				<td align="center"><span <?=$style?>><?=$status?></span></td>
			</tr>
			<?
		}
		elseif ($destino == "selPartidaConceptoPerfil") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selPartidaConceptoPerfil('<?=htmlentities($field["denominacion"])?>', '<?=$field['CodCuenta']?>', '<?=$field['TipoSaldo']?>');" id="<?=$field['cod_partida']?>">
				<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
				<td><span <?=$style?>><?=htmlentities($field['denominacion'])?></span></td>
				<td align="center"><span <?=$style?>><?=$status?></span></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=htmlentities($field["denominacion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['cod_partida']?>">
				<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
				<td><span <?=$style?>><?=htmlentities($field['denominacion'])?></span></td>
				<td align="center"><span <?=$style?>><?=$status?></span></td>
			</tr>
			<?
		}
	}
?>
</table>
</form>
</body>
</html>