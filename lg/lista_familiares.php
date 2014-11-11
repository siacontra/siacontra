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
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Personas</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
$sql="SELECT rh_cargafamiliar.*, mastmiscelaneosdet.Descripcion FROM rh_cargafamiliar, mastmiscelaneosdet WHERE rh_cargafamiliar.CodPersona='".$_GET['registro']."' AND rh_cargafamiliar.Parentesco=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='PARENT'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<?php echo "<form name='frmlista' id='frmlista' action='lista_personas.php?filtro=".$_GET['filtro']."'>"; ?>
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="125" scope="col">Cedula</th>
		<th scope="col">Nombre</th>
		<th width="75" scope="col">Parentesco</th>
		<th width="100" scope="col">Fecha de Nacimiento</th>
		<th width="50" scope="col">Sexo</th>
		<th width="50" scope="col">Estado</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT rh_cargafamiliar.*, mastmiscelaneosdet.Descripcion FROM rh_cargafamiliar, mastmiscelaneosdet WHERE rh_cargafamiliar.CodPersona='".$_GET['registro']."' AND rh_cargafamiliar.Parentesco=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='PARENT' LIMIT ".$_GET['limit'].", $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			$nombrecompleto=$field['ApellidosCarga'].", ".$field['NombresCarga'];
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selFamiliar(\"".$nombrecompleto."\", \"".$field['Descripcion']."\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodSecuencia']."'>
				<td align='center'>".$field['Ndocumento']."</td>
				<td align='left'>".$field['ApellidosCarga'].", ".$field['NombresCarga']."</td>
				<td align='center'>".$field['Descripcion']."</td>
				<td align='center'>".$field['FechaNacimiento']."</td>
				<td align='center'>".$field['Sexo']."</td>
				<td align='center'>".$field['Estado']."</td>
			</tr>";
		}
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