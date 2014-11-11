<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);
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
		<td class="titulo">Maestro Tipo Correspondencia</td>
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
    <input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'cp_tipocorrespondencianuevo.php');" />
    <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'cp_tipocorrespondenciaeditar.php', 'SELF');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarTcorrespondencia(this.form, 'cp_tipocorrespondencia.php?accion=ELIMINARCOR', '1', 'APLICACIONES');" />
    <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'cp_tipocorrespondenciaver.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'cp_tipocorrespondenciapdf.php', 'height=800, width=750, left=200, top=200, resizable=yes');" />	  </td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="85">Cod. Documento</th>
      <th width="222">Descripci&oacute;n</th>
        <th width="94">Uso Interno</th>
        <th width="94">Uso Externo</th>
        <th width="94">Proc. Externa</th>
      <th width="83">Estado</th>
  </tr>
	<?php
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") 
	    $sql="SELECT * FROM 
		                   cp_tipocorrespondencia 
				      WHERE (Descripcion LIKE '%$filtro%') 
				   ORDER BY Cod_TipoDocumento";
	else 
	   $sql="SELECT * FROM 
	                      cp_tipocorrespondencia 
				  ORDER BY 
				          Cod_TipoDocumento";
	   $query=mysql_query($sql) or die ($sql.mysql_error());
	   $rows=mysql_num_rows($query);
	   //	MUESTRO LA TABLA
	   for($i=0; $i<$rows; $i++) {
		  $field=mysql_fetch_array($query);
		  if($field['FlagUsoInterno']=='1'){$UI='<img src="imagenes/flag.png" width="15" height="15"/>';}else{$UI='';}
		  if($field['FlagUsoExterno']=='1'){$UE='<img src="imagenes/flag.png" width="15" height="15"/>';}else{$UE='';}
		  if($field['FlagProcedenciaExterna']=='1'){$UPE='<img src="imagenes/flag.png" width="15" height="15"/>';}else{$UPE='';}
		  
		  if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
		  echo "<tr class='trListaBody' onclick='mClk(this, \"registro\");'id='".$field['Cod_TipoDocumento']."'>
				<td align='center' width='100'>".$field['Cod_TipoDocumento']."</td>
				<td>".htmlentities($field['Descripcion'])."</td>
				<td align='center'>$UI</td>
				<td align='center'>$UE</td>
				<td align='center'>$UPE</td>
				<td align='center'>$estado</td>
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


