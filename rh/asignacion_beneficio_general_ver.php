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
		<td class="titulo">Maestro de Asignación de beneficio General | Ver</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick='javascript:window.close();'>[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_ayudamedicaglobal WHERE codAyudaG='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);


//$sqlPer="SELECT CodPersona, Busqueda FROM mastpersonas WHERE CodPersona=".$fieldE['CodPerAprobar'];
//$queryPer=mysql_query($sqlPer) or die ($sqlPer.mysql_error());
//$rows=mysql_num_rows($query);
//$fieldPer=mysql_fetch_array($queryPer);


if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<form id='frmentrada' name='frmentrada' action='asignacion_global.php' method='POST'>
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:900px' class='divFormCaption'>Datos de la asignación de beneficio general</div>
	<table width='900' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Código</td>
	    <td><input name='txt_codigo' type='text' id='txt_codigo' size='3' maxlength='3' value='".$field['numAyudaG']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='txt_descripcion' type='text' id='txt_descripcion' size='60' maxlength='60' value='".$field['decripcionAyudaG']."' readonly />*</td>
	  </tr>	
	  <tr>
	    <td class='tagForm'>Limite:</td>
		<td>
			<input name='txt_limite' type='text' id='txt_limite' value='".number_format($field['limiteAyudaG'],2,',','.')."'  style='text-align:right' readonly />  Bs.F
	  	</td>	   
	  </tr>
	  <tr>
	    <td class='tagForm'>&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr class='tr4'>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
	  </tr>
	</table>
	<center> 
	
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='javascript:window.close();' />
	</center>
	</form>";
}
?>
</body>
</html>