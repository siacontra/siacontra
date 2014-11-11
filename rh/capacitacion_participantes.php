<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_POST);
extract($_GET);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<?  
if ($accion=="APROBAR") $disabled="disabled"; else $disabled="";
?>
<form name="frmentrada" action="capacitacion_participantes.php" method="POST">
<table width="850" class="tblBotones">
	<tr>
		<td align="right">
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Agregar" onclick="cargarVentana(this.form, 'lista_participantes.php?capacitacion=<?=$capacitacion?>&organismo=<?=$organismo?>&limit=0', 'height=500, width=800, left=0, top=100, resizable=no');" <?=$disabled?> />
		<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'capacitacion_participantes.php?accion=ELIMINAR&capacitacion=<?=$capacitacion?>&organismo=<?=$organismo?>', '0', '');" <?=$disabled?> />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="850" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Empleado</th>
		<th width="50" scope="col">Total Dias</th>
		<th width="50" scope="col">Total Horas</th>
		<th width="75" scope="col">Dias Asistidos</th>
		<th width="75" scope="col">Aprobado?</th>
		<th width="50" scope="col">Nota</th>
		<th width="75" scope="col">Importe Gastos</th>
  </tr>
	<?php 
	include("fphp.php");
	connect();
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="AGREGAR") {
		$sql="SELECT * FROM rh_capacitacion_empleados WHERE Capacitacion='".$capacitacion."' AND CodOrganismo='".$organismo."' AND CodPersona='".$codpersona."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		if ($rows==0) {
			$secuencia=getSecuencia2("Secuencia", "Capacitacion", "CodOrganismo", "rh_capacitacion_empleados", $capacitacion, $organismo);
			$sql="INSERT INTO rh_capacitacion_empleados (Capacitacion, CodOrganismo, Secuencia, CodPersona, CodDependencia, CodDivision, UltimoUsuario, UltimaFecha) VALUES ('$capacitacion', '$organismo', '$secuencia', '$codpersona', '$dependencia', '$division', '".$_SESSION['USUARIO_ACTUAL']."', '$ahora')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	if ($_GET['accion']=="ELIMINAR") {		
		$sql = "DELETE FROM rh_capacitacion_empleados
				WHERE
					Capacitacion = '".$capacitacion."' AND
					CodOrganismo = '".$organismo."' AND
					Secuencia = '".$registro."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM rh_capacitacion_hora
				WHERE
					Capacitacion = '".$capacitacion."' AND
					CodOrganismo = '".$organismo."' AND
					Secuencia = '".$registro."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM rh_capacitacion_gastos WHERE Capacitacion = '".$capacitacion."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$sql="SELECT rce.*, mp.NomCompleto, me.CodEmpleado FROM rh_capacitacion_empleados rce INNER JOIN mastpersonas mp ON (rce.CodPersona=mp.CodPersona) INNER JOIN mastempleado me ON (rce.CodPersona=me.CodPersona) WHERE rce.Capacitacion='".$capacitacion."' AND rce.CodOrganismo='".$organismo."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$gastos=number_format($field['ImporteGastos'], 2, ',', '.');
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
			<td align='center'>".$field['CodEmpleado']."</td>
	    	<td>".$field['NomCompleto']."</td>
			<td align='center'>".$field['NroAsistencias']."</td>
			<td align='center'>".$field['HoraAsistencias']."</td>
			<td align='center'>".$field['DiasAsistidos']."</td>
			<td align='center'><input type='checkbox' $aprobado disabled /></td>
			<td align='center'>".$field['Nota']."</td>
			<td align='right'>".$gastos."</td>
		</tr>";
	}
	?>
</table>
</form>
</body>
</html>