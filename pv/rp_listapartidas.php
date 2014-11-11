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
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Partidas</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if($_POST['filtro']!="")$sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM pv_partida WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="rp_listapartidas.php?limit=0&campo=<?=$campo?>&destino=<?=$destino;?>">
<input type="hidden" name="destino" id="destino" value="<?=$_GET['destino'];?>" />
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=$_POST['filtro']?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
	<tr class="trListaHead">
		 <th width="100" scope="col">Partida</th>
		<th width="650" scope="col">Denominaci&oacute;n</th>
		<th width="70"scope="col">Tipo</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT * FROM pv_partida WHERE (cod_partida LIKE '%".$_POST['filtro']."%' OR partida1 LIKE '%".$_POST['filtro']."%' OR generica LIKE '%".$_POST['filtro']."%' OR especifica LIKE '%".$_POST['filtro']."%' OR subespecifica LIKE '%".$_POST['filtro']."%' OR denominacion LIKE '%".$_POST['filtro']."%') ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
 else $sql="SELECT * FROM pv_partida ORDER BY cod_partida, denominacion LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		/*for($i=0; $i<$rows; $i++) {
		   $field=mysql_fetch_array($query);
		   if($cod_partida!=$field['cod_partida']){
			 if(($field[6]!=$valor) and ($field[1]==0)){
			 echo "<tr class='trListaBody5'>
					 <td align='center'>".$field['cod_partida']."</td>
					 <td>".$field['denominacion']."</td>
					 <td align='center'>".$field['tipo']."</td>
				   </tr>";$valor=$field[6];
			}else{
			  if(($field[6]!=$valor) and ($field[1]!=0) and ($field[2]==0)){
			   echo "
			   <tr class='trListaBody'  onclick='mClk(this, \"registro\"); selPartidasRP(\"".$field['Busqueda']."\", ".$_GET['campo'].");' id='".$field['CodPartida']."' >
				   <td align='center'>".$field['cod_partida']."</td>
				   <td>".$field['denominacion']."</td>
				   <td align='center'>".$field['tipo']."</td>
			   </tr>";$valor=$field[6];
			  }else{
				 $valor=$field[6];
				 echo "
				 <tr class='trListaBody'>
					 <td align='center'>".$field['cod_partida']."</td>
					 <td>".$field['denominacion']."</td>
					 <td align='center'>".$field['tipo']."</td>
				 </tr>";$valor=$field[6];
				 
			   }
			 }
		   }
		}*/
		for($i=0; $i<$rows; $i++) {
		   $field=mysql_fetch_array($query);
		   echo"<tr class='trListaBody' onclick='mClk(this,\"registro\"); selPartidasRP(\"".$field['Busqueda']."\", ".$_GET['campo'].");' id='".$field['cod_partida']."'>
		     	 <td align='center'>".$field['cod_partida']."</td>
				 <td>".$field['denominacion']."</td>
				 <td align='center'>".$field['tipo']."</td>
			   </tr>";
		}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";	
	}
	?>
</table>
</form>
</body>
</html>