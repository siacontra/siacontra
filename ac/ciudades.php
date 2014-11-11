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
		<td class="titulo">Maestro de Ciudades</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="ciudades.php" method="POST">
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
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'ciudades_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ciudades_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ciudades_ver.php', 'BLANK', 'height=250, width=775, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'ciudades.php?accion=ELIMINAR', '1', 'CIUDADES');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'ciudades_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">C&oacute;digo</th>
    <th scope="col">Descripci&oacute;n</th>
    <th scope="col">Municipio</th>
  </tr>
	<?php
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM mastciudades WHERE CodPostal='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT mastciudades.CodCiudad, mastciudades.Ciudad, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais, mastciudades.CodPostal FROM mastciudades, mastmunicipios, mastestados, mastpaises WHERE (mastciudades.CodCiudad LIKE '%$filtro%' OR mastciudades.Ciudad LIKE '%$filtro%' OR mastmunicipios.Municipio LIKE '%$filtro%' OR mastestados.Estado LIKE '%$filtro%' OR mastpaises.Pais LIKE '%$filtro%' OR mastciudades.CodPostal LIKE '%$filtro%') AND mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio, mastciudades.CodPostal";
	else $sql="SELECT mastciudades.CodCiudad, mastciudades.Ciudad, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais, mastciudades.CodPostal FROM mastciudades, mastmunicipios, mastestados, mastpaises WHERE mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio, mastciudades.CodPostal";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	$pais=""; $edo="";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//if ($field[4]!=$pais) { echo "<tr class='tr9'><td colspan='2'>Pais:</td><td colspan='2'>$field[4]</td></tr>"; $pais=$field[4]; } else $pais=$field[4];
		if ($field['Estado']!=$edo) { echo "<tr class='trListaBody2'><td></td><td colspan='2'>".htmlentities($field['Estado'])."</td></tr>"; $edo=$field['Estado']; } else $edo=$field['Estado'];
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodCiudad']."'>
			<td align='center'>".$field['CodCiudad']."</td>
			<td>".htmlentities($field['Ciudad'])."</td>
			<td>".htmlentities($field['Municipio'])."</td>
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