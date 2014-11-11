<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('05', $concepto);
//	------------------------------------
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
if ($accion == "PROCESAR") {
	$sql = "UPDATE rh_controlasistencia SET Estado = 'P' WHERE Estado = 'S'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	
	$sql = "TRUNCATE TABLE rh_transfeventasistencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	?>
	<script language="javascript">
		alert("¡LOS REGISTROS SE PROCESARON EXITOSAMENTE!");
	</script>
	<?
}
elseif ($accion == "VACIAR") {
	$sql = "TRUNCATE TABLE rh_transfeventasistencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	
	$sql = "DELETE FROM rh_controlasistencia WHERE Estado = 'S'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	?>
	<script language="javascript">
		alert("¡TODOS LOS REGISTROS SE ELIMINARION EXITOSAMENTE!");
	</script>
	<?
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Asistencias</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="control_asistencias.php">
<table width="1000" class="tblBotones">
	<tr>
		<td align="right">
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Vaciar" onclick="cargarPagina(form, 'control_asistencias.php?accion=VACIAR');" />
			<input name="btProcesar" type="button" class="btLista" id="btProcesar" value="Procesar" onclick="cargarPagina(form, 'control_asistencias.php?accion=PROCESAR');" />
		</td>
	</tr>
</table>


<table width="1000" style="font-family: 'Lucida Grande', Verdana, Arial, Helvetica, sans-serif; border:solid 1px #BBBBBB;" align="center">
<?php
//	Cuerpo
$sql = "SELECT 
			ca.*,
			mp.Busqueda, 
			mp.Ndocumento, 
			me.CodEmpleado, 
			me.CodPersona, 
			me.CodDependencia, 
			me.CodCarnetProv, 
			md.Dependencia, 
			rp.DescripCargo 
		FROM 
			rh_controlasistencia ca
			INNER JOIN mastempleado me ON (ca.CodPersona = me.CodEmpleado) 
			INNER JOIN mastpersonas mp ON (me.CodPersona = mp.CodPersona) 
			INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) 
			INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
		WHERE 
			ca.Estado = 'S'
		ORDER BY CodDependencia, CodEmpleado, FechaFormat, HoraFormat";
$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
while ($field_empleado = mysql_fetch_array($query_empleado)) {
	if ($dependencia != $field_empleado['CodDependencia']) {
		$dependencia = $field_empleado['CodDependencia'];
		?>
		<tr>
			<td colspan="4"><b><?=($field_empleado['Dependencia'])?></b></td>
		</tr>
		<?
	}
	
	if ($empleado != $field_empleado['CodEmpleado']) {
		$empleado = $field_empleado['CodEmpleado'];
		?>
		<tr>
			<td colspan="4">
				<b>
				<?=$field_empleado['CodEmpleado']?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<?=$field_empleado['Ndocumento']?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<?=($field_empleado['Busqueda'])?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<?=($field_empleado['DescripCargo'])?>
				</b>
			</td>
		</tr>
		<?
	}
	?>
	
	<tr>
		<td width="100"><?=$field_empleado['CodEvento']?></td>
		<td><?=$field_empleado['Fecha']?></td>
		<td><?=$field_empleado['Hora']?></td>
		<td><?=$field_empleado['Event_Puerta']?></td>
	</tr>
<?
}
?>
</table>
</form>
</body>
</html>