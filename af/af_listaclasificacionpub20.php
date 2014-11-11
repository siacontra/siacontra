<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista Clasificaci&oacute;n Publicaci&oacute;n 20</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_POST['filtro']!="") $sql="SELECT * FROM af_clasificacionactivo20 WHERE (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%' OR Nivel LIKE '%".$_POST['filtro']."%')";
else $sql="SELECT * FROM af_clasificacionactivo20";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<form name="frmlista" id="frmlista" method="post" action="af_listaclasificacionpub20.php?limit=<?=$limit;?>&campo=<?=$campo;?>&ventana=<?=$ventana;?>">
<input type="hidden" name="tabla" id="tabla" value="<?=$_GET['tabla']?>"/>
<input type="hidden" name="limit" id="limit" value="<?=$_GET['limit']?>"/>
<!--<input type="hidden" name="limit" id="limit" value="<?=$_post['limit']?>"/>-->
<input type="hidden" name="campo" id="campo" value="<?=$_GET['campo']?>"/>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="ventana" id="ventana" value="<?=$_GET['ventana']?>"/>
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table width='300' align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes2(this.form, \"P\", $registros, ".$_GET['limit'].");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes2(this.form, \"A\", $registros, ".$_GET['limit'].");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes2(this.form, \"S\", $registros, ".$_GET['limit'].");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes2(this.form, \"U\", $registros, ".$_GET['limit'].");' />
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
<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="70" scope="col">CodClasificaci&oacute;n</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="25" scope="col">Nivel</th>
		<th width="25" scope="col">Estado</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($_POST['filtro']!="") $sql="SELECT * FROM af_clasificacionactivo20 WHERE (CodClasificacion LIKE '%".$_POST['filtro']."%' OR Descripcion LIKE '%".$_POST['filtro']."%' OR Nivel LIKE '%".$_POST['filtro']."%') ORDER BY CodClasificacion LIMIT $limit, $MAXLIMIT";
		else $sql="SELECT * FROM af_clasificacionactivo20 ORDER BY  CodClasificacion LIMIT $limit, $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($field['Estado']=="A") $Estado="Activo"; else $Estado="Inactivo";
			if($ventana=="insertarClasificacionPub20"){
			 echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); insertarClasificacionPub20(this.id,\"".$ventana."\", \"".$_GET['campo']."\",\"".$field['Descripcion']."\");' id='".$field['CodClasificacion']."'>
				<td align='center'>".$field['CodClasificacion']."</td>
				<td align='left'>".htmlentities($field['Descripcion'])."</td>
				<td align='left'>".$field['Nivel']."</td>
				<td align='left'>$Estado</td>
			</tr>";
			
			}else{
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selEmpleado(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$field['NomCompleto']."\", \"".$field['CodCargo']."\");' id='".$field['CodPersona']."'>
				<td align='center'>".$field['CodEmpleado']."</td>
				<td align='left'>".htmlentities($field['Busqueda'])."</td>
				<td align='center'><input type='checkbox' $escliente disabled /></td>
				<td align='center'><input type='checkbox' $esproveedor disabled /></td>
				<td align='center'><input type='checkbox' $esempleado disabled /></td>
				<td align='center'><input type='checkbox' $esotros disabled /></td>
				<td align='left'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['DocFiscal']."</td>
			</tr>";
		}}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$_GET['limit'].");
	</script>";				
	?>
</table>
</form>
</body>
</html>