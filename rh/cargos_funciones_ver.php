<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<?php
include("fphp.php");
connect();
$registro=$_GET['registro'];
//	CONSULTO LA TABLA
$sql = "SELECT
			rh_cargofunciones.Funcion,
			rh_cargofunciones.CodFuncion,
			mastmiscelaneosdet.Descripcion AS NomFuncion,
			rh_cargofunciones.Descripcion,
			rh_cargofunciones.Estado
		FROM
			rh_cargofunciones,
			mastmiscelaneosdet
		WHERE
			(rh_cargofunciones.CodCargo = '".$_GET['registro']."') AND
			(mastmiscelaneosdet.CodMaestro = 'FUNCION') AND
			(rh_cargofunciones.Funcion = mastmiscelaneosdet.CodDetalle)
		ORDER BY Funcion DESC, CodFuncion";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='50' scope='col'>#</th>
		<th scope='col'>Comentarios</th>
		<th width='75' scope='col'>Estado</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	
	if ($grupo != $field['Funcion']) {
		$grupo = $field['Funcion'];
		$generales++;
		$especificas = 0;
		?>
        <tr class="trListaBody2">
        	<td align="center"><?=$generales?></td>
        	<td colspan="2"><?=($field['NomFuncion'])?></td>
        </tr>
        <?
	}
	$especificas++;
	?>
	<tr class="trListaBody" onclick="mClk(this, 'det');" onmouseover="mOvr(this);" onmouseout="mOut(this);" id="<?=$field['CodFuncion']?>">
		<td align='right'><?=$especificas?></td>
		<td align='left'><?=($field['Descripcion'])?></td>
		<td align='center'><?=$field['Estado']?></td>
	</tr>
    <?
}
echo "
</table>";
?>
</body>
</html>