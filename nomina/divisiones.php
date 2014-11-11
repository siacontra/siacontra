<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', '0012');
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Divisiones</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="divisiones.php" method="POST">
<table width="800" class="tblBotones">
  <tr>
		<td><div id="rows"></div></td>
    <td align="right">
			<?php
			if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
			echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='".$_POST['filtro']."' />";
			?>
		</td>
    <td align="right">
			<input name="btNuevo" type="button" class="btLista" d="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'divisiones_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" d="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'divisiones_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" d="btVer" value="Ver" onclick="cargarOpcion(this.form, 'divisiones_ver.php', 'BLANK', 'height=250, width=775, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" d="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'divisiones.php?accion=ELIMINAR', '1', 'DIVISIONES');" />
			<input name="btPDF" type="button" class="btLista" d="btPDF" value="PDF" onclick="cargarVentana(this.form, 'divisiones_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">Division</th>
    <th scope="col">Descripci&oacute;n</th>
  </tr>
	<?php 
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM mastdivisiones WHERE CodDivision='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT mastdivisiones.CodDivision, mastdivisiones.Division, mastdependencias.Dependencia, mastorganismos.Organismo FROM mastdivisiones, mastdependencias, mastorganismos WHERE mastdivisiones.CodDivision<>'' AND (mastdivisiones.CodDivision LIKE '%$filtro%' OR mastdivisiones.Division LIKE '%$filtro%' OR mastdependencias.Dependencia LIKE '%$filtro%' OR mastorganismos.Organismo LIKE '%$filtro%') AND mastdivisiones.CodDependencia=mastdependencias.CodDependencia AND mastdependencias.CodOrganismo=mastorganismos.CodOrganismo ORDER BY mastorganismos.CodOrganismo, mastdependencias.CodDependencia, mastdivisiones.CodDivision";
	else $sql="SELECT mastdivisiones.CodDivision, mastdivisiones.Division, mastdependencias.Dependencia, mastorganismos.Organismo FROM mastdivisiones, mastdependencias, mastorganismos WHERE mastdivisiones.CodDivision<>'' AND mastdivisiones.CodDependencia=mastdependencias.CodDependencia AND mastdependencias.CodOrganismo=mastorganismos.CodOrganismo ORDER BY mastorganismos.CodOrganismo, mastdependencias.CodDependencia, mastdivisiones.CodDivision";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	$org=""; $dep="";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field[3]!=$org) { echo "<tr class='trListaBody2'><td>&nbsp;</td><td>$field[3]</td></tr>"; $org=$field[3]; } else $org=$field[3];
		if ($field[2]!=$dep) { echo "<tr class='trListaBody3'><td>&nbsp;</td><td>$field[2]</td></tr>"; $dep=$field[2]; } else $dep=$field[2];
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
			<td align='center'>".$field[0]."</td>
			<td>".$field[1]."</td>
		</tr>";
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
?>
</table>
</form>
</body>
</html>