<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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
		<td class="titulo">Maestro de Municipios</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="municipios.php" method="POST">
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
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'municipios_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'municipios_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'municipios_ver.php', 'BLANK', 'height=225, width=775, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'municipios.php?accion=ELIMINAR', '1', 'MUNICIPIOS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'municipios_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">Municipio</th>
    <th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
  </tr>
	<?php
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM mastmunicipios WHERE CodMunicipio='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT mastmunicipios.CodMunicipio, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais, mastmunicipios.Estado AS Status FROM mastmunicipios, mastestados, mastpaises WHERE (mastmunicipios.CodMunicipio LIKE '%$filtro%' OR mastmunicipios.Municipio LIKE '%$filtro%' OR mastestados.Estado LIKE '%$filtro%' OR mastpaises.Pais LIKE '%$filtro%') AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio";
	else $sql="SELECT mastmunicipios.CodMunicipio, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais, mastmunicipios.Estado AS Status FROM mastmunicipios, mastestados, mastpaises WHERE mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	$pais=""; $edo="";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//if ($field[3]!=$pais) echo "<tr class='tr9'><td colspan='2'>Pais:</td><td>$field[3]</td></tr>";
		if ($field[2]!=$edo) echo "<tr class='trListaBody2'><td>&nbsp;</td><td>".htmlentities($field[2])."</td><td>&nbsp;</td></tr>";
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
			<td align='center'>".$field[0]."</td>
			<td>".htmlentities($field[1])."</td>
			<td align='center'>".$field['Status']."</td>
		</tr>";
		$pais=$field[3]; $edo=$field[2];
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