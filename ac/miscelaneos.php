<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Miscel&aacute;neos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="miscelaneos.php" method="POST">
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
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'miscelaneos_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'miscelaneos_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'miscelaneos_ver.php', 'BLANK', 'height=600, width=750, left=50, top=50, resizable=yes');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'miscelaneos.php?accion=ELIMINAR', '1', 'MISCELANEOS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'miscelaneos_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:800px; height:400px;">
<table width="800" class="tblLista">
<thead>
  <tr class="trListaHead">
    <th width="125" scope="col">Maestro</th>
    <th scope="col">Descripci&oacute;n</th>
		<th scope="col">Aplicaci&oacute;n</th>
  </tr>
 </thead>
	<?php
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		list($maestro, $aplicacion)=SPLIT('[-]', $_POST['registro']);
		$sql="DELETE FROM mastmiscelaneoscab WHERE CodMaestro='".$maestro."' AND CodAplicacion='".$aplicacion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT mastmiscelaneoscab.CodMaestro, mastmiscelaneoscab.CodAplicacion, mastmiscelaneoscab.Descripcion, mastaplicaciones.Descripcion FROM mastmiscelaneoscab, mastaplicaciones WHERE (mastmiscelaneoscab.CodMaestro like '%$filtro%' OR mastmiscelaneoscab.CodAplicacion like '%$filtro%' OR mastmiscelaneoscab.Descripcion like '%$filtro%' OR mastaplicaciones.Descripcion like '%$filtro%') AND (mastmiscelaneoscab.CodAplicacion=mastaplicaciones.CodAplicacion) ORDER BY mastaplicaciones.CodAplicacion, mastmiscelaneoscab.CodMaestro";
	else $sql="SELECT mastmiscelaneoscab.CodMaestro, mastmiscelaneoscab.CodAplicacion, mastmiscelaneoscab.Descripcion, mastaplicaciones.Descripcion FROM mastmiscelaneoscab, mastaplicaciones WHERE (mastmiscelaneoscab.CodAplicacion=mastaplicaciones.CodAplicacion) ORDER BY mastaplicaciones.CodAplicacion, mastmiscelaneoscab.CodMaestro";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$codigo=$field[0]."-".$field[1];
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$codigo."'>
			<td align='center'>".$field[0]."</td>
	    <td>".$field[2]."</td>
			<td>".$field[3]."</td>
		</tr>";
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
?>
</table>
</div></center>
</form>
</body>
</html>