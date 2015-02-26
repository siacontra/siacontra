<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="709" height="21" cellpadding="0" cellspacing="0">
	<tr>
	    <td width="60"></td>
		<td width="261" class="titulo">Lista de Partidas</td>
		<td width="215"></td>
		<td width="160" align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="650" color="#333333" />

<?php
include ("fphp.php");
connect();
$MAXLIMIT='30';
if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM pv_partida WHERE 1";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  $registros=mysql_num_rows($query);
?>
<form name="frmentrada" id="frmentrada" action="lista_partidas.php?limit=0" method="post">
<input type="hidden" name="organismo" id="organismo" value="<?=$organismo?>" />
<input type="hidden" name="Org" id="Org" value="<?=$Org?>" />
<input type="hidden" name="regresar" id="regresar" value="<?=$regresar?>" />
<input type="hidden" name="npresupuesto" id="npresupuesto" value="<?=$npresupuesto?>"/>
<input type="hidden" name="num_presupuesto" id="num_presupuesto" value="<?=$num_presupuesto?>"/>
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<input type="hidden" name="cod_anteproyecto" id="cod_anteproyecto" value="<?=$cod_anteproyecto?>"/>
<input type="hidden" name="ejercicioPpto" id="ejercicioPpto" value="<?=$ejercicioPpto?>"/>
<input type="hidden" name="registros" id="registros" value="<?=$registros?>" />
<input type="hidden" name="target" id="target" value="<?=$target?>" />
<input type="hidden" name="pagina" id="pagina" value="<?=$pagina?>" />
<table width="650" class="tblBotones">
<tr>
 <td><div id="rows"></div></td>
 <td width="260">
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
<table width="650" class="tblLista">
	<tr class="trListaHead">
	    <th width="40" scope="col">Partida</th>
		<th width="300" scope="col">Denominaci&oacute;n</th>
		<th width="10"scope="col">Tipo</th>
	</tr>
<?php 
if($registros!=0){
  if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%') ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 else $sql="SELECT * FROM pv_partida ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 $query=mysql_query($sql) or die ($sql.mysql_error());
 $rows=mysql_num_rows($query);
 ////	***** CARGAR TABLA CON LISTADO DE PARTIDAS PARA QUE SEAN SELECCIONADAS *****   //////
 for($i=0; $i<$rows; $i++) {
 $field=mysql_fetch_array($query);
   if($cod_partida!=$field['cod_partida']){
	 if(($field[6]!=$valor) and ($field[1]==0)){
	 echo "<tr class='trListaBody5'>
	         <td align='center'>".$field['cod_partida']."</td>
			 <td>".$field['denominacion']."</td>
			 <td align='center'>".$field['tipo']."</td>
			 <td align='center'><input type='checkbox' name='$i' value='".$field['cod_partida']."'/></td>
		   </tr>";$valor=$field[6];
    }else{
	  if(($field[6]!=$valor) and ($field[1]!=0) and ($field[2]==0)){
	   echo "
	   <tr class='trListaBody'>
		   <td align='center'>".$field['cod_partida']."</td>
		   <td>".$field['denominacion']."</td>
		   <td align='center'>".$field['tipo']."</td>
		   <td align='center'><input type='checkbox' name='$i' value='".$field['cod_partida']."' /></td>
	   </tr>";$valor=$field[6];
	  }else{
	     $valor=$field[6];
		 echo "
	     <tr class='trListaBody'>
			 <td align='center'>".$field['cod_partida']."</td>
			 <td>".$field['denominacion']."</td>
			 <td align='center'>".$field['tipo']."</td>
			 <td align='center' width='20'><input type='checkbox' name='$i' value='".$field['cod_partida']."' /></td>
		 </tr>";$valor=$field[6];
	     
       }
	 }
   }
  }
 }
$rows=(int)$rows;
echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";
?>
</table>
<center>
<input type="button" name="btAceptar" id="btAceptar" value="Agregar Partidas" onclick="cargarPartida('<?=$pagina?>','<?=$target?>');" /></center>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</form>
</body>
</html>