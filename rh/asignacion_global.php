<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Asignación de beneficio General</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="asignacion_global.php" method="POST">
<table width="800" class="tblBotones">
  <tr>
		<td><div id="rows"></div></td>
    <td align="right">
			<?php
			if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
			echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='".$_POST['filtro']."' />";
			?>
	  </td>
    <td align="right">
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'asignacion_beneficio_general_nuevo.php');" />
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'asignacion_beneficio_general_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'asignacion_beneficio_general_ver.php', 'BLANK', 'height=700, width=1000, left=50, top=50, resizable=yes');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro(this.form, 'asignacion_global.php?accion=ELIMINAR', '1', 'ASIGNACIONGLOBAL');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'tiposcargo_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
    <th width="58" scope="col">C&oacute;digo</th>
    <th width="652" scope="col">Descripci&oacute;n</th>
    <th width="80" scope="col">Limite</th>    
  </tr>
	<?php 
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_tipocargo WHERE CodTipoCargo='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT codAyudaG, numAyudaG, decripcionAyudaG, limiteAyudaG FROM rh_ayudamedicaglobal WHERE (numAyudaG LIKE '%$filtro%' OR decripcionAyudaG LIKE '%$filtro%') ORDER BY numAyudaG";
	else $sql="SELECT codAyudaG, numAyudaG, decripcionAyudaG, limiteAyudaG FROM rh_ayudamedicaglobal ORDER BY numAyudaG";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['codAyudaG']."'>
			<td align='center'>".$field['numAyudaG']."</td>
	    	<td>".$field['decripcionAyudaG']."</td>
			<td align='center'>".number_format($field['limiteAyudaG'],2,',','.')."</td>	    	
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