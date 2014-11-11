<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
//include ("fphp.php");
include "gmactivofijo.php";
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Categor&iacute;as</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />

<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmentrada" action="af_categoriadepreciacion.php" method="POST">
<? echo"<input type='hidden' name='regresar' id='regresar' value='af_categoriadepreciacion'/>";?>
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right">
  <?php
       if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'af_categorianueva.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'af_categoriaeditar.php', 'SELF');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'af_categoriaver.php?', 'BLANK', 'height=600, width=900, left=200, top=100, resizable=no');" />
	<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarCategorias(this.form, 'af_categoriadepreciacion.php?accion=ELIMINARCATEGORIA', '1', 'APLICACIONES');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'af_categoriapdf.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:700px; height:350px;">
<table width="700" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="10">Categor&iacute;as</th>
		<th width="200" scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Cuenta Activo</th>
		<th width="25" scope="col">Cta. Depreciaci&oacute;n</th>
		<th width="25" scope="col">Estado</th>
  </tr>
  </thead>
	<?php
	
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * 
		        FROM af_categoriadeprec 
			   WHERE (CodCategoria LIKE '%$filtro%' OR DescripcionLocal LIKE '%$filtro%') 
			ORDER BY cod_programa";
	else 
	   $sql="SELECT * FROM af_categoriadeprec ORDER BY CodCategoria";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for ($i=0; $i<$rows; $i++) {
		   $field=mysql_fetch_array($query);
		   if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
		   echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['CodCategoria']."'>
					<td align='center'>".$field['CodCategoria']."</td>
	    	        <td>".($field['DescripcionLocal'])."</td>
					<td align='center'>".$field['CuentaHistorica']."</td>
					<td align='center'>".$field['CuentaDepreciacion']."</td>
					<td align='center'>$estado</td>
				</tr>";
	   }
	   echo "
	   <script type='text/javascript' language='javascript'>
		     totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	   </script>";
	?>
</table></div>
</td></tr>
</table>
</form>
</body>
</html>



