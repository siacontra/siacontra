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
		<td class="titulo">Maestro Proyecciones - Procesos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="proyeccion_procesos.php" method="POST">
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
			<input name="btNuevo" type="button" class="" id="btNuevo" value="Agregar Empleado" onclick="cargarPagina(this.form, 'hcm_institucion_nuevo.php'); " width="30"/>
			<input name="btNuevo" type="button" class="" id="btNuevo" value="Editar Conceptos de Empleado" onclick="cargarPagina(this.form, 'hcm_institucion_nuevo.php'); " width="30"/>
			<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'hcm_institucion_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'hcm_institucion_ver.php', 'BLANK', 'height=400, width=600, left=50, top=50, resizable=yes');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="controlador(this.form, 'proyeccion_procesos.php?accion=ELIMINAR', '1', 'ASIGNACIONESPECIFICO', 'lib/InstitucionesControlador.php');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'tiposcargo_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="400" class="tblLista">
  <tr class="trListaHead">
    <th width="25" scope="col">C&oacute;digo</th>
    <th width="25" scope="col" align="center">Descripcion</th>
    <th width="25" scope="col" align="center">Monto</th>
    </tr>
	<?php 
	//	ELIMINO EL REGISTRO
	/*if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_tipocargo WHERE CodTipoCargo='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}*/
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT * FROM rh_institucionhcm WHERE (descripcioninsthcm LIKE '%$filtro%') ORDER BY idInstHcm";
	else  $sql="SELECT
pyepc.CodProceso,
pyepc.CodPersona,
pyepc.CodConcepto,
pyepc.Monto,
pyepc.MontoP,
pyepc.Cantidad,
pyepc.Saldo,
prc.Descripcion
FROM
py_empleadoprocesoconcepto AS pyepc
INNER JOIN pr_concepto AS prc ON pyepc.CodConcepto = prc.CodConcepto

 ";
				
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	
	//	MUESTRO LA TABLA
	
	while ($field=mysql_fetch_array($query))
	{
		
	 	echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPersona']."'>
		<td align='center'>".$field['CodConcepto']."</td>
        <td align='center'>".$field['Descripcion']."</td>
         <td align='center'>".$field['Monto']."</td>
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
