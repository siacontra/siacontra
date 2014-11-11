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
		<td class="titulo">Seguridad Alterna | Agregar</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="seguridad_alterna.php?accion=GUARDAR" method="POST">
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'seguridad_alterna.php');" />
</center>

<br /><div class="divDivision">Listado de Autorizaciones</div><br />

<table width="800" align="center">
	<tr>
		<td align="right">
			<a onclick="seleccionarTodos(document.getElementById('frmentrada'), true);" href="javascript:;">Seleccionar Todos</a> | 
			<a onclick="seleccionarTodos(document.getElementById('frmentrada'), false);" href="javascript:;">Deseleccionar Todos</a>
		</td>
    </tr>
</table>
<table width="800" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">Concepto</th>
    </tr>
    
    <?php
	$sql="SELECT d.CodDependencia, d.Dependencia, d.CodOrganismo, o.Organismo, s.FlagMostrar FROM mastdependencias d INNER JOIN mastorganismos o ON (d.CodOrganismo=o.CodOrganismo) LEFT JOIN seguridad_alterna s ON (d.CodOrganismo=s.CodOrganismo AND d.CodDependencia=s.CodDependencia AND s.Usuario='$registro' AND s.CodAplicacion='".$_SESSION["APLICACION_ACTUAL"]."') ORDER BY d.CodOrganismo, d.CodDependencia";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while($field=mysql_fetch_array($query)) {
		if ($grupo!=$field['CodOrganismo']) {
			$grupo=$field['CodOrganismo'];
			$c=$field['CodOrganismo'];
			$d=$field['Organismo'];
			echo "
			<tr class='trListaBody'>
				<td class='trListaBody2'>
					".htmlentities($field['Organismo'])."
				</td>
			</tr>";
		}
		if ($field['FlagMostrar']=="S") $checked="checked"; else $checked="";
		$c=$field['CodOrganismo'].":".$field['CodDependencia'];
		echo "
		<tr class='trListaBody'>
			<td onclick='clkPermisosAlterno(\"".$c."\");'>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='checkbox' name='".$c."' id='".$c."' value='".$c."' $checked />
				".htmlentities($field['Dependencia'])."
			</td>
		</tr>";
	}
	?>
</table>

</form>

</body>
</html>
