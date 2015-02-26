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
		<td class="titulo">Maestro de Sub-Programa| Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<div style='width:700px' class='divFormCaption'>Datos de la Actividad</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Sub-Programa:</td>
	    <td><input name='codigo' type='text' id='codigo' size='8' maxlength='5' value='".$field['cod_actividad']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='60' value='".htmlentities($field['descp_actividad'])."' readonly /></td>
	  </tr>
	  <tr>
	 <td class='tagForm'>Proyecto:</td>";
	  $sql2=mysql_query("SELECT descp_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$field['id_proyecto']."'");
	  $rows2=mysql_num_rows($sql2);
	  if($rows2!=0){
        $field2=mysql_fetch_array($sql2);
		 echo"<td><input name='programa' type='text' id='programa' size='60' value='".$field2['descp_proyecto']."' readonly /></td>";
	  }
	echo"</tr>
	  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
		   if($field['Estado']=="A"){
			 echo "<input name='status' type='radio' value='A' checked /> Activo";
			 echo "<input name='status' type='radio' value='I'/> Inactivo";
		   }else{
		    echo "<input name='status' type='radio' value='A'/> Activo";
		    echo "<input name='status' type='radio' value='I' checked /> Inactivo";
		   }
		   
			echo "
			</td>
	  </tr>
	<tr>
	  <td class='tagForm'>&Uacute;ltima Modif.:</td>
	  <td>
		  <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
		  <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
	  </td>
	</tr>
	</table>";
}
?>

</body>
</html>