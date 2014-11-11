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
//	EDITO EL REGISTRO
if ($_GET['accion']=="EDITAR") {
	$sql="SELECT * FROM rh_postulantes_experiencia WHERE Postulante='".$_GET['registro']."' AND Secuencia='".$_GET['secuencia']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		$_POST['secuencia']=$field['Secuencia'];
		$_POST['sueldo']=strtr($field['Sueldo'], ".", ",");
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $_POST['fdesde']=$d."-".$m."-".$a; if ($_POST['fdesde']=="00-00-0000") $_POST['fdesde']="";
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $_POST['fhasta']=$d."-".$m."-".$a; if ($_POST['fhasta']=="00-00-0000") $_POST['fhasta']="";
		$_POST['empresa']=$field['Empresa'];
		$_POST['mcese']=$field['MotivoCese'];
		$_POST['area']=$field['AreaExperiencia'];
		$_POST['ente']=$field['TipoEnte'];
		$_POST['cargo']=$field['CargoOcupado'];
		$_POST['funciones']=$field['Funciones'];
	}
}
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_postulantes_experiencia WHERE Postulante='".$_GET['registro']."' AND Secuencia='".$_GET['secuencia']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "
<form name='frmbancaria' action='empleados_experiencia.php' method='POST' onsubmit='return verificarPostExperiencia(this, \"INSERTAR\");'>
<input type='hidden' name='secuencia' id='secuencia' value='".$_POST['secuencia']."' />
<input name='postulante' type='hidden' id='postulante' value='".$_POST['registro']."' />
<table width='800' class='tblForm'>
	<tr>
	<td class='tagForm'>Empresa:</td>
	<td><input name='empresa' type='text' id='empresa' size='50' maxlength='255' value='".($_POST['empresa'])."' />*</td>
	<td class='tagForm'>Motivo de Cese:</td>
	<td>
			<select name='mcese' id='mcese' class='selectMed'>
				<option value=''></option>";
				getMiscelaneos($_POST['mcese'], "MOTCESE", 0);
			echo "
			</select>*
		</td>
  </tr>
	<tr>
		<td class='tagForm'>Desde:</td>
		<td><input name='fdesde' type='text' id='fdesde' size='15' maxlength='10' value='".$_POST['fdesde']."' />*<i>(dd-mm-yyyy)</i></td>
		<td class='tagForm'>Hasta:</td>
		<td><input name='fhasta' type='text' id='fhasta' size='15' maxlength='10' value='".$_POST['fhasta']."' />*<i>(dd-mm-yyyy)</i></td>
	</tr>
	<tr>
	<td class='tagForm'>Area de Experiencia:</td>
	<td>
			<select name='area' id='area' class='selectMed'>
				<option value=''></option>";
				getMiscelaneos($_POST['area'], "AREAEXP", 0);
			echo "
			</select>
		</td>
	<td class='tagForm'>Tipo de Entidad:</td>
	<td>
			<select name='ente' id='ente' class='selectMed'>
				<option value=''></option>";
				getMiscelaneos($_POST['ente'], "TIPOENTE", 0);
			echo "
			</select>
		</td>
  </tr>
	<tr>
	<td class='tagForm'>Cargo Ocupado:</td>
	<td><input name='cargo' type='text' id='cargo' value='".($_POST['cargo'])."' size='50' maxlength='150' /></td>
	<td class='tagForm'>Sueldo:</td>
	<td><input name='sueldo' type='text' id='sueldo' value='".$_POST['sueldo']."' /><em>(999999,99)<em/></td>
  </tr>
	<tr>
	<td class='tagForm'>Funciones:</td>
	<td colspan='2'><textarea name='funciones' cols='75' rows='2'>".($_POST['funciones'])."</textarea></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetExperiencia(this.form);' />
</center>
</form>

<form name='frmtabla'>
<table width='800' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optPostExperiencia(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optPostExperiencia(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT * FROM rh_postulantes_experiencia WHERE Postulante='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='sec' id='sec' />
<table width='800' class='tblLista'>
  <tr class='trListaHead'>
		<th width='50' scope='col'>#</th>
		<th width='250' scope='col'>Empresa</th>
		<th width='100' scope='col'>Desde</th>
		<th width='100' scope='col'>Hasta</th>
		<th width='150' scope='col'>Motivo de Cese</th>
		<th width='150' scope='col'>Sueldo</th>
  </tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$sueldo=number_format($field['Sueldo'], "2", ",", ".");
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a; if ($fdesde=="00-00-0000") $fdesde="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a; if ($fhasta=="00-00-0000") $fhasta="";
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodDetalle='".$field['MotivoCese']."' AND CodMaestro='MOTCESE'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $motcese=$field1[0]; }
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodDetalle='".$field['AreaExperiencia']."' AND CodMaestro='AREAEXP'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $area=$field1[0]; }
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodDetalle='".$field['TipoEnte']."' AND CodMaestro='TIPOENTE'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $ente=$field1[0]; }
	//		
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
	<td colspan='6'>
			<table cellpadding='0' cellspacing='0'>
		  <tr>
			<td>
						<table cellpadding='0' cellspacing='0'>
				<tr>
				  <td align='center' width='50'>".$field['Secuencia']."</td>
				</tr>
						</table>
					</td>
			<td>
						<table>
				<tr>
				  <td width='250'>".($field['Empresa'])."</td>
				  <td align='center' width='100'>".$fdesde."</td>
				  <td align='center' width='100'>".$fhasta."</td>
				  <td align='center' width='150'>".$motcese."</td>
				  <td align='right' width='150'>".$sueldo."</td>
				</tr>
						</table>
						<table width='100%' cellpadding='0' cellspacing='0' border='1' style='border-color:#FFFFFF;'>
				<tr>
				  <td width='150' class='tdLista' height='25' scope='col'>Area de Experiencia: &nbsp;</th>
				  <td> &nbsp; ".($area)."</td>
				</tr>
				<tr>
				  <th class='tdLista' height='25' scope='col'>Cargo Ocupado: &nbsp;</th>
				  <td> &nbsp; ".($field['CargoOcupado'])."</td>
				</tr>
				<tr>
				  <th class='tdLista' height='25' scope='col'>Funciones: &nbsp; </th>
				  <td> &nbsp; ".($field['Funciones'])."</td>
				</tr>
						</table>
					</td>
		  </tr>
		</table>
		</th>
  </tr>";
}
echo "
</table>
</form>";
echo "
<script type='text/javascript' language='javascript'>
	totalElementos($rows);
</script>";
?>
</body>
</html>