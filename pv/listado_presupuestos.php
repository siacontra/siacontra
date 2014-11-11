<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Empleados</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_POST['filtro']!="") $sql="SELECT * FROM pv_presupuesto WHERE (Organismo LIKE '%".$_POST['filtro']."%' OR CodPresupuesto LIKE '%".$_POST['filtro']."%' OR EjercicioPpto LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM pv_presupuesto WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="lista_presupuestos.php?limit=0&campo=<?=$campo?>">
<table width="800" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
echo "
<table align='center'>
<tr>
<td>
<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
</td>
<td>Del</td><td><div id='desde'></div></td>
<td>Al</td><td><div id='hasta'></div></td>
<td>
<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
</td>
</tr>
</table>";
?>
</td>
<td align="center">
<input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar" />
</td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="45">Nro. Presupuesto</th>
		<th width="45">Ejercicio Ppto.</th>
        <th width="50">Fecha Presupuesto</th>
        <th width="150">Elaborado Por</th>
        <th width="150">Aprobado Por</th>
		<th width="50">Fecha Inicio</th>
		<th width="50">Fecha Fin</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT 
		                                      CodPresupuesto,
											  EjercicioPpto,
											  FechaPresupuesto,
											  FechaInicio,
											  FechaFin,
											  AprobadoPor,
											  PreparadoPor
										FROM 
										     pv_presupuesto
									   WHERE 
									         (CodPresupuesto LIKE '%".$_POST['filtro']."%' OR 
											  EjercicioPpto LIKE '%".$_POST['filtro']."%' OR 
											  FechaPresupuesto LIKE '%".$_POST['filtro']."%' OR 
											  FechaInicio LIKE '%".$_POST['filtro']."%' OR 
											  FechaFin LIKE '%".$_POST['filtro']."' OR 
											  AprobadoPor LIKE '%".$_POST['filtro']."' OR 
											  PreparadoPor LIKE '%".$_POST['filtro'].") 
									 ORDER BY 
									          Organismo, CodPresupuesto LIMIT ".$_GET['limit'].", $MAXLIMIT";
		else $sql="SELECT CodPresupuesto,
				  EjercicioPpto,
				  FechaPresupuesto,
				  FechaInicio,
				  FechaFin,
				  AprobadoPor,
				  PreparadoPor 
			 FROM 
				  pv_presupuesto 
			WHERE 
				  Organismo ='".$_SESSION['ORGANISMO_ACTUAL']."' 
		 ORDER BY 
				 Organismo, CodPresupuesto LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			list($ano, $mes, $dia)= split('[-]',$field['FechaPresupuesto']);$fechaPresupuesto = $dia.'-'.$mes.'-'.$ano;
			list($ano1, $mes1, $dia1)= split('[-]',$field['FechaInicio']); $fechaInicio = $dia1.'-'.$mes1.'-'.$ano1;
			list($ano2, $mes2, $dia2)= split('[-]',$field['FechaFin']); $fechaFin = $dia2.'-'.$mes2.'-'.$ano2;
			
			echo "
			<tr class='trListaBody' onclick='mClk(this,\"registro\"); selEmpleado2(\"".$field['CodPresupuesto']."\", ".$_GET['campo'].");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPresupuesto']."'>
				<td align='center'>".$field['CodPresupuesto']."</td>
				<td align='center'>".$field['EjercicioPpto']."</td>
				<td align='center'>$fechaPresupuesto</td>
				<td align='center'>".$field['PreparadoPor']."</td>
				<td align='center'>".$field['AprobadoPor']."</td>
				<td align='center'>$fechaInicio</td>
				<td align='left'>$fechaFin</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>
