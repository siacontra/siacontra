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
		<td class="titulo">Maestro Intituciones | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'hcm_instituciones.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="hcm_instituciones.php" method="POST" onsubmit="return verificarInstituciones(this, 'GUARDAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";



$sql="SELECT MAX(idInstHcm) AS CANT FROM rh_institucionhcm";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
$field=mysql_fetch_array($query);

$sig = $field['CANT']+1;
	if(strlen($sig)==1){$codigo='0'.$sig;}
	else{$codigo = $sig;}

?>

<div style="width:900px" class="divFormCaption">Datos Institucion</div>
<table width="600" class="tblForm">
  <tr>
    <td class="tagForm">Código:</td>
    <td><input name="txt_codigo" type="text" id="txt_codigo" value="<?=$codigo?>" size="3" maxlength="3" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Nombre de la Institucion:</td>
    <td><input name="txt_descripcioninsthcm" type="text" id="txt_descripcioninsthcm" size="120" maxlength="120" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Direccion:</td>
    <td><input name="txt_direccion" type="text" id="txt_direccion" size="120" maxlength="120" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Telefonos</td>
    <td><input name="txt_telefonos" type="text" id="txt_telefonos" size="50" maxlength="50" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'hcm_instituciones.php');" />
</center>
</form>

<script>

</script>


</body>
</html>
