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
		<td class="titulo">Maestro de Sub-Programa</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<form name="frmentrada" action="mactividad.php" method="POST">
<table width="700" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right"><?php
      if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
		 echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' value='".$_POST['filtro']."' />";
	?></td>
  <td align="left"><input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'nactividad.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ed_actividad.php', 'SELF');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ver_actividad.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
			<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro4(this.form, 'mactividad.php?accion=eliminarActividad', '1', 'APLICACIONES');" />
			<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pdf_actividad.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
  <tr class="trListaHead">
         <!--<th width="75" scope="col">Proyecto</th>-->
		<th width="90" scope="col">Sub-Programa</th>
		<th scope="col">Descripci&oacute;n</th>
  </tr>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php
	//include "gmeliminar.php";
	include "gmsector.php";
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM pv_actividad1 WHERE (cod_actividad LIKE '%$filtro%' OR descp_actividad LIKE '%$filtro%') ORDER BY id_actividad";
	else 
	   $sql="SELECT * FROM pv_actividad1 ORDER BY id_proyecto";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++){
		   $field=mysql_fetch_array($query);
		   if($idProyecto!=$field['id_proyecto']){
		      $codProyecto=$field['cod_proyecto'];
			  $idProyecto=$field['id_proyecto'];
			  $sql2=mysql_query("SELECT * FROM pv_proyecto1 WHERE id_proyecto='$idProyecto' ORDER BY id_proyecto");
			 if(mysql_num_rows($sql2)!=0){
			    $field2=mysql_fetch_array($sql2);
			    echo"<tr class='trListaBody2'><td colspan='3'>".$field2['cod_proyecto']."-".$field2['descp_proyecto']."</td></tr>";
		     }
		   }
		   echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' 
		         onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['id_actividad']."'>
					<td align='center'>".$field['cod_actividad']."</td>
	    	        <td>".htmlentities($field['descp_actividad'])."</td>
				 </tr>";
	   }
	   echo"
	   <script type='text/javascript' language='javascript'>
		     totalRegistros($rows, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
	   </script>";
	?><!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</table>
</form>
</body>
</html>
