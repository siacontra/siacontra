<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
$ahora=date("Y-m-d H:i:s");
$filtro=trim($_POST['filtro']);
if ($filtro!="") $where="WHERE (CodTipoProceso LIKE '%$filtro%' OR Descripcion LIKE '%$filtro%')";
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Procesos</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_tipos_proceso.php" method="POST">
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="center">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
        <td><input type="button" value="Aceptar" onclick="selTipoProceso(this.form);" /></td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="2%" scope="col">&nbsp;</th>
		<th width="10%" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="10%" scope="col">Tipo</th>
		<th width="10%" scope="col">Estado</th>
	</tr>
	<?php 
	$sql="SELECT * FROM pr_tipoproceso $where ORDER BY CodTipoProceso";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['FlagAdelanto']=="S") $checked="checked"; else $checked="";
		echo "
		<tr class='trListaBody'>
			<td><input type='checkbox' name='proceso' id='".$field['CodTipoProceso']."' value='".$field['CodTipoProceso']."' /></td>
			<td align='center'>".$field['CodTipoProceso']."</td>
		    <td>".($field['Descripcion'])."</td>
			<td align='center'><input type='checkbox' disabled $checked /></td>
			<td align='center'>".$field['Estado']."</td>
		</tr>";
	}
	?>
</table>
</form>

<script language="javascript">
	numRegistros(<?=$rows?>);
</script>
</body>
</html>