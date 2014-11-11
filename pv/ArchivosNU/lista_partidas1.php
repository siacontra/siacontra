
<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../fscript.js"></script>
</head>

<body>
<table width="679" height="19" cellpadding="0" cellspacing="0">
	<tr>
	    <td width="87"></td>
		<td width="249" class="titulo">Lista de Partidas</td>
		<td width="121"></td>
		<td width="220" align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="600" color="#333333" />

<?php
include ("../fphp.php");
connect();
$MAXLIMIT='30';
if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM pv_partida WHERE 1";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  $registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="../lista_partidas.php?limit=0">
<table width="600" class="tblBotones">
<tr>
 <td><div id="rows"></div></td>
 <td width="250">
   <?php echo "
		 <table align='center'>
		 <tr>
		  <td><input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
			  <input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");'/></td>
		  <td>Del</td><td><div id='desde'></div></td>
          <td>Al</td><td><div id='hasta'></div></td>
		  <td><input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");'/>
		  <input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");'/></td>
		 </tr>
		</table>";?></td>
 <td align="center"><input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar"/></td>
</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="600" class="tblLista">
	<tr class="trListaHead">
	    <th width="40" scope="col">Partida</th>
		<th width="300" scope="col">Denominaci&oacute;n</th>
		<th width="10"scope="col"></th>
	</tr>
<?php 
if($registros!=0){
  //$filtro= trim($_POST['filtro']);
  if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%') ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 else $sql="SELECT * FROM pv_partida ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 $query=mysql_query($sql) or die ($sql.mysql_error());
 $rows=mysql_num_rows($query);
 ////	***** CARGAR TABLA CON LISTADO DE PARTIDAS PARA QUE SEAN SELECCIONADAS *****   //////
 for($i=0; $i<$rows; $i++) {
 $field=mysql_fetch_array($query);
 if(($field[6]!=$valor) and ($field[1]==0)){
   echo "<tr class='trListaBody2'>
          <td align='center'>".$field['cod_partida']."</td>
		  <td>$field[5]</td>
		  <td align='center'><input type='checkbox' name='seleccion[]' value='seleccion'/></td></tr>";$valor=$field[6];
 }else{
   if(($field[6]!=$valor) and ($field[1]!=0) and ($field[2]==0)){
     echo "<tr class='trListaBody2'>
			<td align='center'>".$field['cod_partida']."</td>
			<td>$field[5]</td><td align='center'><input type='checkbox' name='seleccion[]' value='seleccion'/></td>
			</tr>";$valor=$field[6];
   }else{$valor=$field[6];
    echo "<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", \"".$_GET['campo']."\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['cod_partida']."'>
	       <td align='center'>".$field['cod_partida']."</td>
	       <td>".htmlentities($field['denominacion'])."</td>
		   <td align='center'><input type='checkbox' name='seleccion[]' value='seleccion'/></td>
	      </tr>";
	     $rows=(int)$rows;
     }
	   echo "
	   <script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	  </script>";	

}
}}
	?>
</table>
</form>
</body>
</html>