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
$registro=$_GET['registro'];
echo "
<form name='frmentrada' action='cargos_cursos.php' method='POST' onSubmit='return verificarCargoCurso(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Curso:</td>
    <td colspan='2'>
			<input name='codcurso' type='hidden' id='codcurso' value='' />
			<input name='nomcurso' type='text' id='nomcurso' size='65' readonly />
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='cargarVentana(this.form, \"lista_cursos.php?limit=0&registro=".$_POST['registro']."\", \"height=500, width=800, left=200, top=200, resizable=yes\");' />
		</td>
	  <td class='tagForm'>Horas:</td>
		<td><input type='text' name='horas' id='horas' size='8' maxlength='4' /></td>
	  <td class='tagForm'>A&ntilde;os:</td>
		<td><input type='text' name='anios' id='anios' size='4' maxlength='2' /></td>
	</tr>
	<tr>
	  <td class='tagForm'>Observaciones:</td>
		<td colspan='6'><textarea name='observaciones' id='observaciones' cols='100' rows='1'></textarea></td>
	</tr>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Estudio' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Estudio' onClick='eliminarSubCargo(this.form, \"cargos_cursos.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargocursos WHERE Secuencia='".$_POST['det']."' AND CodCargo='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_cargocursos.Secuencia, rh_cursos.Descripcion, rh_cargocursos.TotalHoras, rh_cargocursos.AniosVigencia, rh_cargocursos.Observaciones FROM rh_cargocursos, rh_cursos WHERE (rh_cargocursos.CodCargo='".$_GET['registro']."' AND rh_cargocursos.Curso=rh_cursos.CodCurso)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='25' scope='col'>#</th>
		<th width='275' scope='col'>Curso</th>
		<th width='50' scope='col'>Horas</th>
		<th width='50' scope='col'>A&ntilde;os Vigencia</th>
		<th scope='col'>Observaciones</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='right'>".$field[0]."</td>
		<td align='left'>".$field[1]."</td>
		<td align='center'>".$field[2]."</td>
		<td align='center'>".$field[3]."</td>
		<td align='left'>".$field[4]."</td>
	</tr>";
}
echo "
</table>
<script type='text/javascript' language='javascript'>
	totalPuestos($rows);
</script>
</form>";

?>
</body>
</html>