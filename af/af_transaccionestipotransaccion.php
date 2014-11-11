<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
//include ("fphp.php");
include "gmactivofijo.php";
connect();
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
</head>
<body>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Tipo Transacci&oacute;n</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>

<hr width="100%" color="#333333" />

<form name="frmentrada" action="af_transaccionestipotransaccion.php" method="POST">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'af_transaccionestipotransaccionnuevo.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'af_transacciontipoeditar.php', 'SELF');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarSituacion(this.form, 'af_situacion.php?accion=ELIMINARSITU', '1', 'APLICACIONES');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'af_transacciontipover.php?', 'BLANK', 'height=500, width=825, left=200, top=100, resizable=no');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'af_transacciontipopdf.php?', 'BLANK','height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="70" scope="col">Tipo</th>
        <th scope="col">Descripci&oacute;n</th>
        <th width="80" scope="col">Estado</th>
        <th width="80">TipoTransacci&oacute;n</th>
  </tr>
 </thead>
	<?php
	
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM af_tipotransaccion WHERE (TipoTransaccion LIKE '%$filtro%' OR Descripcion LIKE '%$filtro%') ORDER BY TipoTransaccion";
	else 
	   $sql="SELECT * FROM af_tipotransaccion ORDER BY TipoTransaccion";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++) {
		  $field=mysql_fetch_array($query);
		  if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
		  if($field['FlagAltaBaja']=='A') $t_trans = 'Alta'; else $t_trans = 'Baja';
		  
		  echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$field['TipoTransaccion']."'>
				<td align='center'>".$field['TipoTransaccion']."</td>
				<td>".htmlentities($field['Descripcion'])."</td>
				<td align='center'>$estado</td>
				<td align='center'>$t_trans</td>
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


