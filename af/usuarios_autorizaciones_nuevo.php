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
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Autorizaciones | Agregar</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="usuarios_autorizaciones.php?accion=GUARDAR" method="POST">
<?php
//	---------------------------------
$sql="SELECT u.Usuario, m.NomCompleto FROM usuarios u INNER JOIN mastpersonas m ON (u.CodPersona=m.CodPersona) WHERE Usuario='$registro'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
?>
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
  <tr>
    <td align="right">Usuario:</td>
    <td><input name="usuario" type="text" id="usuario" size="20" value="<?=$field["Usuario"]?>" readonly /></td>
  </tr>
  <tr>
    <td align="right">Empleado:</td>
    <td><input name="nompersona" type="text" id="nompersona" size="50" value="<?=$field["NomCompleto"]?>" readonly /></td>
  </tr>
</table>
</div>
<center>
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'usuarios_autorizaciones.php');" />
</center>

<br /><div class="divDivision">Listado de Autorizaciones</div><br />

<?php
$sql="SELECT FlagAdministrador FROM seguridad_autorizaciones WHERE CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND Usuario='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
if ($field['FlagAdministrador']=="S") { $chkadmin="checked"; $dadmin="disabled"; }
?>
<table width="800" align="center">
	<tr>
    	<td><input type="checkbox" name="admin" id="admin" value="S" onclick="clkSetAdministrador(this.form, this.checked);" <?=$chkadmin?> /> Administrador</td>
		<td align="right">
			<a onclick="selTodos(document.getElementById('frmentrada'), true);" href="javascript:;">Seleccionar Todos</a> | 
			<a onclick="selTodos(document.getElementById('frmentrada'), false);" href="javascript:;">Deseleccionar Todos</a>
		</td>
    </tr>
</table>
<table width="800" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="25">&nbsp;</th>
        <th scope="col">Concepto</th>
        <th scope="col" width="25">N</th>
        <th scope="col" width="25">M</th>
        <th scope="col" width="25">E</th>
    </tr>
    
    <?php
	$sql="SELECT c.Concepto, c.Descripcion AS NomConcepto, g.Grupo, g.Descripcion AS NomGrupo, a.FlagMostrar, a.FlagAgregar, a.FlagModificar, a.FlagEliminar FROM seguridad_concepto c INNER JOIN seguridad_grupo g ON (c.CodAplicacion=g.CodAplicacion AND c.Grupo=g.Grupo) LEFT JOIN seguridad_autorizaciones a ON (c.CodAplicacion=a.CodAplicacion AND c.Grupo=a.Grupo AND c.Concepto=a.Concepto AND a.Usuario='".$registro."') WHERE c.CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' ORDER BY g.Grupo, c.Concepto";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while($field=mysql_fetch_array($query)) {
		if ($field['FlagMostrar']=="S") {
			$concepto="checked"; 
			$dagregar="";
			$dmodificar="";
			$deliminar="";
		} else {
			$concepto=""; 
			$dagregar="disabled";
			$dmodificar="disabled";
			$deliminar="disabled";
		}
		if ($field['FlagAgregar']=="S") $chkagregar="checked"; else $chkagregar="";
		if ($field['FlagModificar']=="S") $chkmodificar="checked"; else $chkmodificar="";
		if ($field['FlagEliminar']=="S") $chkeliminar="checked"; else $chkeliminar="";
		
        if ($grupo!=$field['Grupo']) {
			$grupo=$field['Grupo'];
			echo "<tr class='trListaBody2'><td colspan='5'>".htmlentities($field['NomGrupo'])."</td></tr>";
		}
		$c=$field['Grupo'].":".$field['Concepto'];
		echo "
		<tr class='trListaBody'>
			<td align='center'><input type='checkbox' name='".$c."' id='".$c."' value='".$c."' onclick='checkPermisos(this.id, this.checked);' $dadmin $concepto /></td>
			<td onclick='clkPermisos(\"".$c."\");'>".htmlentities($field['NomConcepto'])."</td>
			<td align='center'><input type='checkbox' name='N_".$c."' id='N_".$c."' value='S' $chkagregar $dagregar /></td>
			<td align='center'><input type='checkbox' name='M_".$c."' id='M_".$c."' value='S' $chkmodificar $dmodificar /></td>
			<td align='center'><input type='checkbox' name='E_".$c."' id='E_".$c."' value='S' $chkeliminar $deliminar /></td>
		</tr>";
	}
	?>
</table>

</form>

</body>
</html>
