<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
$ahora=date("Y-m-d H:i:s");
$filtro=trim($filtro); 
if ($filtro!="") $filtrado="WHERE (CodConcepto LIKE '%".$filtro."%' OR Descripcion LIKE '%".$filtro."%') "; else $filtrado="";
//	----------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Conceptos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="conceptos.php" method="POST">
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
		<td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'conceptos_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'conceptos_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'conceptos_ver.php', 'BLANK', 'height=750, width=800, left=50, top=50, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'conceptos.php', '1', 'CONCEPTOS');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'conceptos_pdf.php', 'height=900, width=900, left=200, top=200, resizable=yes');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:600px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Concepto</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="100" scope="col">Tipo</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php 
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM pr_concepto $filtrado ORDER BY Tipo DESC, CodConcepto";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Tipo']=="I") $tipo="Ingresos"; 
		elseif ($field['Tipo']=="D") $tipo="Descuentos";
		elseif ($field['Tipo']=="A") $tipo="Aportes";
		elseif ($field['Tipo']=="P") $tipo="Provisiones";
		elseif ($field['Tipo']=="T") $tipo="Totales";
		if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
		//---------------------------------------------------
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['CodConcepto']."'>
			<td align='center'>".$field['CodConcepto']."</td>
			<td>".($field['Descripcion'])."</td>
			<td align='center'>".$tipo."</td>
			<td align='center'>".$status."</td>
		</tr>";
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
	?>
</table>
</div></td></tr></table>
</form>
</body>
</html>