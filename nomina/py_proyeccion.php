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
		<td class="titulo">Maestro Proyecciones -  Proyeccion</td>
		<td align="right"><a class="cerrar" href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="py_proyeccion.php" method="POST">
	
<input type="hidden" name="CodProyeccion" id="CodProyeccion" /> 	
<input type="hidden" name="ftproyeccion" id="ftproyeccion" /> 	
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
			
			<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'py_proyeccion_nuevo.php');" />
			<!--<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarPagina(this.form, 'py_proyeccion_editar.php');" />
			<input name="btEliminar" type="button" class="btLista" id="btEditar" value="Eliminar" onclick="cargarPagina(this.form, 'py_proyeccion_eliminar.php');" />!-->
			<input name="btAumentos" type="button" class="btLista" id="btAumentos" value="Aumentos" onclick="validar('py_conceptos_porcentaje.php'              ,this.form );" />
			<input name="btNominas" type="button" class="btLista" id="btNominas" value="Nominas"    onclick="validar('py_ejecucion_procesos.php?filtrar=DEFAULT',this.form);" />
			<input name="btNomina" type="button" class="btLista" id="btNomina" value="Crear Nominas" onclick="validar('proyeccion_procesos.php',this.form);" />
			<!-- <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'py_proceso_editar.php', 'SELF');" />
			<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarPagina(this.form, 'py_proceso_ver.php');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="controlador(this.form, 'py_proceso_eliminar.php?accion=ELIMINAR', '1', 'ASIGNACIONESPECIFICO', 'lib/ProyeccionControlador.php');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'py_proceso_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
	        !-->
	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="400" class="tblLista">
  <tr class="trListaHead">
    <th width="25" scope="col">C&oacute;digo</th>
    <th width="300" scope="col" align="center">Descripcion</th>
    <th width="25" scope="col" align="center">Desde</th>
    <th width="25" scope="col" align="center">Hasta</th>
    </tr>
	<?php 
	//	ELIMINO EL REGISTRO
	/*if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_tipocargo WHERE CodTipoCargo='".$_POST['registro']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}*/
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT * FROM py_proyeccion WHERE (Descripcion LIKE '%$filtro%') ORDER BY CodProyeccion";
	else  $sql="SELECT * FROM py_proyeccion  ";
				
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	
	//	MUESTRO LA TABLA
	
	while ($field=mysql_fetch_array($query))
	{
		
		?>
		<tr class='trListaBody' id= "proy<?= $field['CodProyeccion'];?>"  onclick="activar( this,'proy<?= $field['CodProyeccion']; ?>', '<?=$field['CodProyeccion']; ?>');"    >
		<td align='center'><?=$field['CodProyeccion']?></td>
	    <td align='center'><?=$field['Descripcion']?></td>
	    <td align='center'><?=$field['Desde']?></td>
	    <td align='center'><?=$field['Hasta']?></td>
		</tr>
		
		<?php
	}
	echo "
	<script type='text/javascript' language='javascript'>
		totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	</script>";
	
?>
</table>
</form>

<script type="text/javascript" language="javascript"> 

function activar(src,idfila, id) {



var seleccionado=document.getElementsByTagName("tr");
	for (var i=0; i<seleccionado.length; i++) {
		if (seleccionado[i].getAttribute((document.all ? 'className' : 'class')) ==	'trListaBodySel') {
			seleccionado[i].setAttribute((document.all ? 'className' : 'class'), "trListaBody");
			break;
		}
	}
	var row=document.getElementById(idfila);	//	OBTENGO LA FILA DEL CLICK
	row.className="trListaBodySel";	//	CAMBIO EL COLOR DE LA FILA
 	
    var registro=document.getElementById('CodProyeccion');
	registro.value=id;
	var registro=document.getElementById('ftproyeccion');
	registro.value=id;
}



function validar (url,form) {
	var registro=document.getElementById('ftproyeccion');

	 
	
	if (registro.value == "" ) 
	alert("Debe seleccionar un registro!");
	else  cargarPagina(form, url);
}

</script>
</body>
</html>
