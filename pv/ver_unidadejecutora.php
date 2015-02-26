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
		<td class="titulo">Maestro Unidad Ejecutora | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM pv_unidadejecutora WHERE id_unidadejecutora='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<div style='width:700px' class='divFormCaption'>Datos del Sector</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>C&oacute;digo:</td>";
	    if($field[cod_sector]<=9){
	      echo"<td><input name='codigo' type='text' id='codigo' size='7' maxlength='8' value='".$field['cod_unidadejecutora']."' readonly /></td>";
        }else{ 
	       echo"<td><input name='codigo' type='text' id='codigo' size='7' maxlength='8' value='".$field['cod_unidadejecutora']."' readonly /></td>";
	    }
        echo"</tr>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='60' value='".htmlentities($field['Unidadejecutora'])."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
		   if($field['Estado']=="A"){
			 echo "<input name='status' type='radio' value='A' checked /> Activo";
			 echo "<input name='status' type='radio' value='I' /> Inactivo";
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
