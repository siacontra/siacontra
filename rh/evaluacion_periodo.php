<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
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
$ahora=date("Y-m-d H:i:s");
$filtro=trim($filtro); 
if ($filtro!="") $filtrado="WHERE (ep.Secuencia LIKE '%$filtro%' OR ep.Descripcion LIKE '%$filtro%' OR ep.Periodo LIKE '%$filtro%' OR ep.FechaFin LIKE '%$filtro%' OR ep.FechaInicio LIKE '%$filtro%' OR ep.Estado LIKE '%$filtro%' OR o.Organismo LIKE '%$filtro%') "; else $filtrado="";
//	----------------------------
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Periodos de Evaluaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="evaluacion_periodo.php" method="POST">
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'evaluacion_periodo_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'evaluacion_periodo_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'evaluacion_periodo_ver.php', 'BLANK', 'height=700, width=1000, left=50, top=50, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'evaluacion_periodo.php', '1', 'EVALUACIONPERIODO');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'evaluacion_periodo_pdf.php', 'height=900, width=900, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="200">Organismo</th>
		<th scope="col" width="100">Periodo de Evaluaci&oacute;n</th>
		<th scope="col" width="75">Secuencia</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Inicio Evaluaci&oacute;n</th>
		<th scope="col" width="75">Fin Evaluaci&oacute;n</th>
		<th scope="col" width="75">Estado</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql="SELECT ep.CodOrganismo, ep.Secuencia, ep.Descripcion, ep.Periodo, ep.FechaFin, ep.FechaInicio, ep.Estado, o.Organismo FROM rh_evaluacionperiodo ep INNER JOIN mastorganismos o ON (ep.CodOrganismo=o.CodOrganismo)";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		//---------------------------------------------------
		$id=$field['CodOrganismo'].":".$field['Periodo'].":".$field['Secuencia'];
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $finicio="$d-$m-$a";
		list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $ffin="$d-$m-$a";
		if ($field['Estado']=="A") $status="Activo";
		elseif ($field['Estado']=="I") $status="Inactivo";
		//---------------------------------------------------
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='$id'>
			<td>".($field['Organismo'])."</td>
			<td align='center'>".$field['Periodo']."</td>
			<td align='center'>".$field['Secuencia']."</td>
			<td>".($field['Descripcion'])."</td>
			<td align='center'>".$finicio."</td>
			<td align='center'>".$ffin."</td>
			<td align='center'>".$status."</td>
		</tr>";
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
	?>
</table>
</form>
</body>
</html>