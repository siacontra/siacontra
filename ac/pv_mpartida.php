<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="eliminar.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
	<td class="titulo">Maestro de Partida</td>
	<td align="right"><a class="cerrar" href="../presupuesto/framemain.php">[cerrar]</a></td>
 </tr>
</table><hr width="100%" color="#333333" />
<?php
include ("fphp.php");
connect();
$MAXLIMIT='30';
if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM pv_partida ORDER BY cod_partida";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  $registros=mysql_num_rows($query);
?>
<form name="frmentrada" action="mpartida.php?limit=0" method="POST">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<table width="950" class="tblBotones">
 <tr>
  <td><div id="rows"></div></td>
  <td width="230"><input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar"/></td>
  <td width="250"><?php echo "
		 <table align='center'>
		 <tr>
		  <td><input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
			  <input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");'/></td>
		  <td>Del</td><td><div id='desde'></div></td>
          <td>Al</td><td><div id='hasta'></div></td>
		  <td><input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");'/>
		  <input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");'/></td>
		 </tr>
		</table>";?>
   <td width="400" align="center">
   <input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'npartida.php');" />
   <input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'ed_partida.php', 'SELF');"/>
   <input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'ver_partida.php', 'BLANK', 'height=275, width=750, left=200, top=200, resizable=no');" />
	<input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="eliminarRegistro5(this.form, 'mpartida.php?accion=ELIMINARPART', '1', 'APLICACIONES');" />
	<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'pdf_partida.php', 'height=800, width=750, left=200, top=200, resizable=yes');" /></td>
 </tr>
</table>

<!--<input type="text" name="registro" id="registro" />-->
<table width="950" class="tblLista">
  <tr class="trListaHead">
		<th width="100" scope="col">Partida</th>
		<th scope="col">Denominaci&oacute;n</th>
		<th width="10"scope="col">Estado</th>
  </tr>
  <!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php
include "gmsector.php";
$filtro=trim($_POST['filtro']);
  if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%') ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 else $sql="SELECT * FROM pv_partida ORDER BY cod_partida LIMIT ".$_GET['limit'].", $MAXLIMIT";
 $query=mysql_query($sql) or die ($sql.mysql_error());
 $rows=mysql_num_rows($query);
 /// *******   MOSTRAR DATOS DE PARTIDAS **** /////////
 for($i=0;$i<$rows;$i++){
  $field=mysql_fetch_array($query);
  if(($field['cod_tipocuenta']!=$valor) and ($field['partida1']==0)){
     echo "<tr class='trListaBody6'>
	       <td>&nbsp;</td>
		   <td>".$field['denominacion']."</td>
		   </tr>";$valor=$field['cod_tipocuenta'];
  }else{
		 $valor=$field['cod_tipocuenta'];
		 echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' id='".$field['cod_partida']."'>
			     <td align='center'>".$field['cod_partida']."</td>
	    	     <td>".htmlentities($field['denominacion'])."</td>
				 <td align='center'>".$field['Estado']."</td>
			 </tr>";
  }
	  $rows=(int)$rows;
	   echo"
	   <script type='text/javascript' language='javascript'>
	     totalLista($registros);
		 totalLotes($registros, $rows, ".$limit.");
	   </script>";
}
	?>
	<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
</table>
</form>

</body>
</html>