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
		<td class="titulo">Maestro de Autorizaciones</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="usuarios_autorizaciones.php" method="POST">
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
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Agregar" onclick="cargarOpcion(this.form, 'usuarios_autorizaciones_nuevo.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'usuarios_autorizaciones_ver.php', 'BLANK', 'height=980, width=1024, left=0, top=0, resizable=no');" />
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
	if ($_POST['admin']) {		
		$sql="DELETE FROM seguridad_autorizaciones WHERE Usuario='".$_POST['usuario']."' AND CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//
		$sql="INSERT INTO seguridad_autorizaciones (CodAplicacion, Grupo, Concepto, Usuario, FlagMostrar, FlagAgregar, FlagModificar, FlagEliminar, FlagAdministrador, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_SESSION["APLICACION_ACTUAL"]."', '', '', '".$_POST['usuario']."', '', '', '', '', 'S', 'A', '".$_SESSION["USUARIO_ACTUAL"]."', '$ahora')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	} else {
		$sql="DELETE FROM seguridad_autorizaciones WHERE Usuario='".$_POST['usuario']."' AND CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		//
		$sql="SELECT c.Concepto, g.Grupo FROM seguridad_concepto c INNER JOIN seguridad_grupo g ON (c.CodAplicacion=g.CodAplicacion AND c.Grupo=g.Grupo) WHERE c.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."' ORDER BY g.Grupo, c.Concepto";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		while($field=mysql_fetch_array($query)) {
			$c=$field['Grupo'].":".$field['Concepto'];
			if ($_POST[$c]) $mostrar="S"; else $mostrar="N";
			$n="N_$c"; if ($_POST[$n]=="S") $nuevo="S"; else $nuevo="N";
			$m="M_$c"; if ($_POST[$m]=="S") $modificar="S"; else $modificar="N";
			$e="E_$c"; if ($_POST[$e]=="S") $eliminar="S"; else $eliminar="N";
			$sql="INSERT INTO seguridad_autorizaciones (CodAplicacion, Grupo, Concepto, Usuario, FlagMostrar, FlagAgregar, FlagModificar, FlagEliminar, FlagAdministrador, Estado, UltimoUsuario, UltimaFecha) VALUES ('".$_SESSION["APLICACION_ACTUAL"]."', '".$field['Grupo']."', '".$field['Concepto']."', '".$_POST['usuario']."', '".$mostrar."', '$nuevo', '$modificar', '$eliminar', 'N', 'A', '".$_SESSION["USUARIO_ACTUAL"]."', '$ahora')";	//echo $sql."<br>";
			$query_insert=mysql_query($sql) or die ($sql.mysql_error());
		}
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