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
//	CONSULTO LA TABLA
$sql="SELECT * FROM rh_postulantes_experiencia WHERE Postulante='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
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
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
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
echo "</table>";
?>
</body>
</html>