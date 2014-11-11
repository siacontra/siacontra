<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('04', $concepto);
//	------------------------------------
$ahora=date("Y-m-d H:i:s");
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
		<td class="titulo">Seguridad Alterna</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="seguridad_alterna.php" method="POST">
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
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Agregar" onclick="cargarOpcion(this.form, 'seguridad_alterna_agregar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'seguridad_alterna_ver.php', 'BLANK', 'height=980, width=1024, left=0, top=0, resizable=no');" />
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
    <th width="200" scope="col">Usuario</th>
    <th scope="col">Nombre</th>
  </tr>
<?php
//	GUARDAR
if ($accion=="GUARDAR") {
	$sql="DELETE FROM seguridad_alterna WHERE Usuario='$usuario' AND CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	//	------------------
	$sql="SELECT d.CodDependencia, d.Dependencia, d.CodOrganismo, o.Organismo FROM mastdependencias d INNER JOIN mastorganismos o ON (d.CodOrganismo=o.CodOrganismo) ORDER BY d.CodOrganismo, d.CodDependencia";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while($field=mysql_fetch_array($query)) {
		$c=$field['CodOrganismo'].":".$field['CodDependencia'];
		if ($_POST[$c]) $mostrar="S"; else $mostrar="N";
		if ($grupo!=$field['CodOrganismo']) {
			$grupo=$field['CodOrganismo'];
			$sql="INSERT INTO seguridad_alterna (CodAplicacion, Usuario, CodOrganismo, CodDependencia, FlagMostrar, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_SESSION["APLICACION_ACTUAL"]."', '$usuario', '".$field['CodOrganismo']."', '', '$mostrar', 'A', '".$_SESSION["USUARIO_ACTUAL"]."', '$ahora')";
			$query_insert=mysql_query($sql) or die ($sql.mysql_error());
		}
		$sql="INSERT INTO seguridad_alterna (CodAplicacion, Usuario, CodOrganismo, CodDependencia, FlagMostrar, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_SESSION["APLICACION_ACTUAL"]."', '$usuario', '".$field['CodOrganismo']."', '".$field['CodDependencia']."', '$mostrar', 'A', '".$_SESSION["USUARIO_ACTUAL"]."', '$ahora')";
		$query_insert=mysql_query($sql) or die ($sql.mysql_error());
	}
}
//	CONSULTO LA TABLA
$filtro=trim($_POST['filtro']);
if ($filtro!="") $where="AND (u.Usuario LIKE '%$filtro%' OR m.NomCompleto LIKE '%$filtro%')";
$sql="SELECT u.Usuario, m.NomCompleto FROM usuarios u INNER JOIN mastpersonas m ON (u.CodPersona=m.CodPersona) WHERE u.Estado='A' $where ORDER BY u.Usuario";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Usuario']."'>
		<td>".htmlentities($field['Usuario'])."</td>
		<td>".htmlentities($field['NomCompleto'])."</td>
	</tr>";
}
echo "
<script type='text/javascript' language='javascript'>
	totalAutorizacion($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
</script>";
?>
</table>
</form>
</body>
</html>