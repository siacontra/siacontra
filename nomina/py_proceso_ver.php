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
		<td class="titulo">Maestro Medicos | Ver Medico</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'hcm_medicos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="" method="" onsubmit="">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";

if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_medicoshcm WHERE idMedHcm='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
//$rows=mysql_num_rows($query);
$fieldE=mysql_fetch_array($query);



?>

<div style="width:100%" class="divFormCaption">Datos del Tipo de Cargo</div>
<table width="100%" class="tblForm">
  <tr>
    <td class="tagForm">Codigo:</td>
    <td><input name="txt_codigo" type="text" id="txt_codigo" size="5" maxlength="5" readonly value="<?=$fieldE['idMedHcm'];?>" />
     
  </tr>
  <tr>
    <td class="tagForm">Nombre del Medico:</td>
    <td><input name="txt_nombremedico" type="text" id="txt_nombremedico" size="60" readonly maxlength="30"  value="<?=$fieldE['nombremedico'];?>"/>*</td>
  </tr>	

  <tr>
    <td class="tagForm">Telefono :</td>
    <td><label for="txt_telefono"></label>
      <input name="txt_telefono" type="text" id="txt_telefono" readonly value="<?=$fieldE['telefono'];?>" </td>
  </tr>
  <tr>
    <td class="tagForm">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  </table>
<center> 
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cerrar" onclick="window.close()" />
</center>
</form>

<script>


</script>


</body>
</html>
