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

<form name="frmentrada" id="frmentrada" action="lista_clasificador_presupuestario_insertar.php" method="POST">
<input type="hidden" name="seleccion" id="seleccion" />
<input type="hidden" name="idcuenta" id="idcuenta" value="<?=$idcuenta?>" />
<table width="100%" class="tblLista">
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
		<tr class="trListaBody" onclick="mClk(this, 'seleccion'); insertarPartidaCuenta('<?=$field['cod_partida']?>');" id="<?=$field['cod_partida']?>">
			<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
			<td><span <?=$style?>><?=($field['denominacion'])?></span></td>
			<td align="center"><span <?=$style?>><?=$status?></span></td>
		</tr>
        <?
	}
?>
</table>
</form>
</body>
</html>