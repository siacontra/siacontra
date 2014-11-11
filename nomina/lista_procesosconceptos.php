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
$MAXLIMIT=30;
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Tipos de Procesos</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="conceptos_procesos.php?accion=AGREGAR" method="POST" target="iProceso" onsubmit="window.close();">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<table width="100%" class="tblBotones">
    <tr>
        <td>
			<?php
            if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
            echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='".$_POST['filtro']."' />";
            ?>
        </td>
    </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th width="25" scope="col">&nbsp;</th>
        <th width="75" scope="col">Tipo</th>
        <th scope="col">Descripci&oacute;n</th>
        <th width="75" scope="col">Estado</th>
    </tr>
	<?php
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT CodTipoProceso, Descripcion, Estado FROM pr_tipoproceso WHERE (CodTipoProceso LIKE '%$filtro%' OR Descripcion LIKE '%$filtro%' OR Estado LIKE '%$filtro%') ORDER BY CodTipoProceso";
	else $sql="SELECT CodTipoProceso, Descripcion, Estado FROM pr_tipoproceso ORDER BY CodTipoProceso";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody'>
			<td align='center'><input type='checkbox' name='".$field['CodTipoProceso']."' id='".$field['CodTipoProceso']."' value='".$field['CodTipoProceso']."'  /></td>
			<td align='center'>".$field['CodTipoProceso']."</td>
			<td>".$field['Descripcion']."</td>
			<td align='center'>".$field['Estado']."</td>
		</tr>";
	}
?>
</table>
<center><input type="submit" value="Agregar Selecci&oacute;n" /></center>
</form>
</body>
</html>