<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
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
		<td class="titulo">Maestro Particular</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<? include "gmcorrespondencia2.php"; ?>
<form name="frmentrada" id="frmentrada">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left">
    <input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'cpe_particularnuevo.php?regresar=cpe_particular');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'cpe_particulareditar.php?regresar=cpe_particular', 'SELF');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarParticular(this.form, 'cpe_particular.php?accion=ELIMINARPARTICULAR', '1', 'APLICACIONES');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cpe_particularver.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'cpe_particularpdf.php', 'height=550, width=800, left=300, top=100, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center">
<tr>
  <td align="center">
  <div style="overflow:scroll; width:720px; height:450px;">
  
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="85">Nro. Cedula</th>
        <th width="150">Nombre</th>
        <th width="150">Direcci&oacute;n</th>
        <th width="80">Tel&eacute;fono</th>
        <th width="150">Cargo</th>
        
  </tr>
	<?php
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM 
		                   cp_particular
				      WHERE (Nombre LIKE '%$filtro%' OR Cedula LIKE '%$filtro%') 
				   ORDER BY CodParticular";
	else 
	   $sql="SELECT * FROM 
	                      cp_particular
				  ORDER BY 
				          CodParticular";
					  
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++) {
		  $field=mysql_fetch_array($query);
		  echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");'id='".$field['CodParticular']."'>
				<td align='center' width='100'>".$field['Cedula']."</td>
				<td>".($field['Nombre'])."</td>
				<td>".($field['Direccion'])."</td>
				<td>".($field['Telefono'])."</td>
				<td>".($field['Cargo'])."</td>
				
			</tr>";
	   }  
	   echo "
	   <script type='text/javascript' language='javascript'>
		     totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	   </script>";
	?>
</table>
</div>
</td></tr></table></table>
</form>
</body>
</html>


