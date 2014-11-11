<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
if ($accion=="VER") $disabled = "disabled";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<form name="frmentrada" id="frmentrada" action="plan_cuentas_partidas.php" method="POST">
<input type="hidden" name="idcuenta" id="idcuenta" value="<?=$idcuenta?>" />
<input type="hidden" name="seleccion" id="seleccion" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:950px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Partida</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
	<?php
	$filtro=trim($filtro); 
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM pv_partida WHERE CodCuenta = '".$idcuenta."' ORDER BY cod_partida";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$status = printValores("ESTADO", $field['Estado']);
		$style = stylePartida($field['nivel']);
		?>
		<tr class="trListaBody">
			<td align="center"><span <?=$style?>><?=$field['cod_partida']?></span></td>
			<td><span <?=$style?>><?=htmlentities($field['denominacion'])?></span></td>
		</tr>
        <?
	}
?>
</table>
</div></td></tr></table>
</form>
</body>
</html>