<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../fscript.js"></script>
<!--<script type="text/javascript" language="javascript" src="fscript01.js"></script>-->
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Programa | Eliminar</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'mprograma.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="fentrada" id="fentrada" action="../eli_proyecto.php?accion=eliminarp" method="POST" onsubmit="return verificarprograma(this, 'EDITAR');">

<?php
include("../fphp.php");
connect();
$sql="SELECT * FROM pv_subprog WHERE cod_subprog='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	//////   PRUEBA  //////    //////    //////  //////    //////
	echo "
	<div style='width:700px' class='divFormCaption'>Datos del Proyecto</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>C&oacute;digo:</td>
	    <td><input name='codigo' type='text' id='codigo' size='8' maxlength='5' value='".$field['cod_subprog']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='60' 
		    value='".htmlentities($field['descp_subprog'])."'/></td>
	  </tr>
	</table>";
	//////         //////       //////      //////    //////    
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<center> 
	<input type='submit' value='Guardar Registro' name='editar' id='editar' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"msubprog.php\");' />
	</center><br />";
}
?>
</form>
<? include "gmsubprog.php";?>
</body>
</html>
