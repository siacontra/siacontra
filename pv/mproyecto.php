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
</head>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Proyecto</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmentrada" action="mproyecto.php" method="POST">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'nproyecto.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ed_proyecto.php', 'SELF');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ver_proyecto.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro3(this.form, 'mproyecto.php?accion=ELIMINARPROY', '1', 'APLICACIONES');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pdf_proyecto.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="90" scope="col">Proyecto</th>
		<th scope="col">Descripci&oacute;n</th>
  </tr>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
	<?php
	include "gmsector.php";
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM pv_proyecto1 WHERE (cod_proyecto LIKE '%$filtro%' OR descp_proyecto LIKE '%$filtro%') ORDER BY id_proyecto ";
	else 
	   $sql="SELECT * FROM pv_proyecto1 ORDER BY id_proyecto";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++){
		  $field=mysql_fetch_array($query);
		  if($idsub!=$field['id_sub']){
		    $codsubprog=$field['cod_subprog'];
			$idsub=$field['id_sub'];
			$sql2=mysql_query("SELECT * FROM pv_subprog1 WHERE id_sub='$idsub' ORDER BY id_sub");
			if(mysql_num_rows($sql2)!=0){
			  $field2=mysql_fetch_array($sql2);
			  echo"<tr class='trListaBody2'><td colspan='3'>".$field2['cod_subprog']."-".$field2['descp_subprog']."</td></tr>";
		     }
		   }
		   echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' 
		         onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['id_proyecto']."'>
					<td align='center'>".$field['cod_proyecto']."</td>
	    	        <td>".htmlentities($field['descp_proyecto'])."</td>
				 </tr>";
	   }
	   echo"
	   <script type='text/javascript' language='javascript'>
		     totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	   </script>";
	?>
	<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</table>
</form>
</body>
</html>
