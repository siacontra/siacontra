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
		<td class="titulo">Maestro de Par&aacute;metros</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="parametros.php" method="POST">
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
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'parametros_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'parametros_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'parametros_ver.php', 'BLANK', 'height=375, width=775, left=100, top=50, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'parametros.php?accion=ELIMINAR', '1', 'parametros');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'parametros_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
    <th width="100" scope="col">Par&aacute;metro</th>
    <th scope="col">Descripci&oacute;n</th>
	<th width="75" scope="col">Tipo</th>
	<th width="125" scope="col">Valor</th>
	<th width="75" scope="col">Estado</th>
  </tr>
	<?php
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM mastparametros WHERE ParametroClave='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT mastparametros.ParametroClave, mastparametros.DescripcionParam, mastparametros.TipoValor, mastparametros.ValorParam, mastparametros.Estado, mastaplicaciones.Descripcion FROM mastparametros, mastaplicaciones WHERE (mastparametros.ParametroClave LIKE '%$filtro%' OR mastparametros.DescripcionParam LIKE '%$filtro%' OR mastparametros.TipoValor LIKE '%$filtro%' OR mastparametros.ValorParam LIKE '%$filtro%' OR mastparametros.Estado LIKE '%$filtro%' OR mastaplicaciones.Descripcion LIKE '%$filtro%') AND (mastparametros.CodAplicacion=mastaplicaciones.CodAplicacion) ORDER BY mastparametros.CodAplicacion, mastparametros.ParametroClave";
	else $sql="SELECT mastparametros.ParametroClave, mastparametros.DescripcionParam, mastparametros.TipoValor, mastparametros.ValorParam, mastparametros.Estado, mastaplicaciones.Descripcion FROM mastparametros, mastaplicaciones WHERE (mastparametros.CodAplicacion=mastaplicaciones.CodAplicacion) ORDER BY mastparametros.CodAplicacion, mastparametros.ParametroClave";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
			<td align='center'>".$field[0]."</td>
			<td>".htmlentities($field[1])."</td>
			<td align='center'>".$field[2]."</td>
			<td align='center'>".$field[3]."</td>
			<td align='center'>".$field[4]."</td>
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