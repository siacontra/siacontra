<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
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
<script type="text/javascript" language="javascript" src="fscript01.js"></script>
<script type="text/javascript" language="javascript" src="eliminar.js"></script>
</head>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Unidad Ejecutora</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmentrada" action="msector.php" method="POST">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'nunidadejecutora.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ed_unidadejecutora.php', 'SELF');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ver_unidadejecutora.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
	<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro6(this.form, 'munidadejecutora.php?accion=ELIMINARUNIDAD', '1', 'APLICACIONES');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pdf_unidadejecutora.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="90" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
  </tr>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php
 include "gmsector.php";
 $filtro=trim($_POST['filtro']);
 if ($filtro!="") 
    $sql="SELECT id_unidadejecutora, Unidadejecutora FROM pv_unidadejecutora WHERE (id_unidadejecutora LIKE '%$filtro%' OR Unidadejecutora LIKE '%$filtro%') ORDER BY Cod_sector";
 else 
   $sql="SELECT id_unidadejecutora, Unidadejecutora FROM pv_unidadejecutora ORDER BY id_unidadejecutora";
   $query=mysql_query($sql) or die ($sql.mysql_error());
   $rows=mysql_num_rows($query);
   //	MUESTRO LA TABLA
   for ($i=0; $i<$rows; $i++) {
	   $field=mysql_fetch_array($query);
	   echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['id_unidadejecutora']."'>";
	   echo"<td align='center'>".$field['id_unidadejecutora']."</td><td>".htmlentities($field['Unidadejecutora'])."</td></tr>";
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