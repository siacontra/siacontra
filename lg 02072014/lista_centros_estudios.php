<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Centros de Estudio</td>
		<td align="right"><a style="color:blue" href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="lista_centros_estudios.php?limit=0&flag_estudio=<?=$flag_estudio?>&flag_curso=<?=$flag_curso?>" method="POST">
<table width="800" class="tblBotones">
  <tr class="tr6">
		<td><div id="rows"></div></td>
    	<td width="275" align="center">
			<?php
			if ($_GET['filtro']!="") $_POST['filtro']=$_GET['filtro'];
			echo "Filtro: <input name='filtro' type='text' id='filtro' size='30' maxlength='30' value='".$_POST['filtro']."' />";
			?>
		</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
  <tr class="trListaHead">
    <th width="75" scope="col">Centro</th>
    <th scope="col">Descripci&oacute;n</th>
  </tr>
	<?php
	//	CONSULTO LA TABLA
	$filtro=trim($_POST['filtro']);
	if ($filtro!="") $sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE (CodCentroEstudio<>'') AND (FlagEstudio='".$flag_estudio."' OR FlagCurso='".$flag_curso."') AND (CodCentroEstudio LIKE '%$filtro%' OR Descripcion LIKE '%$filtro%') ORDER BY CodCentroEstudio";
	else $sql="SELECT CodCentroEstudio, Descripcion FROM rh_centrosestudios WHERE CodCentroEstudio<>'' AND (FlagEstudio='".$flag_estudio."' OR FlagCurso='".$flag_curso."') ORDER BY CodCentroEstudio";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"registro\"); selCentro(\"".$field['Descripcion']."\");' id='".$field['CodCentroEstudio']."'>
			<td align='center'>".$field['CodCentroEstudio']."</td>
	    	<td>".$field['Descripcion']."</td>
		</tr>";
	}
?>
</table>
</form>
</body>
</html>