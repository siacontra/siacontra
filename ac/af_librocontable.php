<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Libro Contable</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmentrada" action="af_librocontable.php" method="POST">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'af_librocontablenuevo.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'af_librocontableeditar.php', 'SELF');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'af_librocontablever.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarLibrocontable(this.form, 'af_librocontable.php?accion=ELIMINARLIBROC', '1', 'APLICACIONES');" />
    
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'af_librocontablepdf.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:700px; height:350px;">
<table width="700" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="90" scope="col">Programa</th>
		<th scope="col">Descripci&oacute;n</th>
        <th width="90" scope="col">Estado</th>
  </tr>
 </thead>
 <tbody>
	<?php
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM ac_librocontable WHERE (CodLibroCont LIKE '%$filtro%' OR Descripcion LIKE '%$filtro%') ORDER BY CodLibroCont";
	else 
	   $sql="SELECT * FROM ac_librocontable ORDER BY CodLibroCont";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++) {
		  $field=mysql_fetch_array($query);
		  if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
		  echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");'id='".$field['CodLibroCont']."'>
				<td align='center'>".$field['CodLibroCont']."</td>
				<td>".htmlentities($field['Descripcion'])."</td>
				<td align='center'>$estado</td>
			</tr>";
	   }
	   echo "
	   <script type='text/javascript' language='javascript'>
		     totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	   </script>";
	?>
    </tbody>
</table>
</div>
</center>
</form>
</body>
</html>


