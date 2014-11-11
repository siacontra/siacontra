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
<script type="text/javascript" language="javascript" src="fscript_proyeccion.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"> Crear Proceso | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'py_proyeccion.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="py_proyeccion.php" method="POST" onsubmit="return verificarProyeccion(this, 'GUARDAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";



$sql="SELECT MAX(CodProyeccion) AS CANT FROM py_proyeccion";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
$field=mysql_fetch_array($query);

$sig = $field['CANT']+1;
	if(strlen($sig)==1){$codigo='0'.$sig;}
	else{$codigo = $sig;}

?>

<div style="width:900px" class="divFormCaption">Datos del Proceso</div>
<table width="600" class="tblForm">
  <tr>
    <td class="tagForm">Código:</td>
    <td><input name="tx_codigo" type="text" id="tx_codigo" value="<?=$codigo?>" size="3" maxlength="3" readonly /></td>
  </tr>

  
    <tr>
    <td class="tagForm">Descripcion</td></td>
     
    <td><input name="tx_descripcion" type="text" id="tx_descripcion" size="60" height="100" maxlength="100" />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Desde</td>
    
    
    <td>
    <select name="tx_desde" id="tx_desde" style="width:90px;" >
				<option value="2014-05">2014-05</option>
				<option value="2014-06">2014-06</option>
				<option value="2014-07">2014-07</option>
				<option value="2014-08">2014-08</option>
				<option value="2014-09">2014-09</option>
				<option value="2014-10">2014-10</option>
				<option value="2014-11">2014-11</option>
				<option value="2014-12">2014-12</option>
				
			</select>
    </td>
  </tr>	
  
    <tr>
    <td class="tagForm">Hasta</td>
    
    
    <td>
    <select name="tx_hasta" id="tx_hasta" style="width:90px;" >
				<option value="2014-05">2014-05</option>
				<option value="2014-06">2014-06</option>
				<option value="2014-07">2014-07</option>
				<option value="2014-08">2014-08</option>
				<option value="2014-09">2014-09</option>
				<option value="2014-10">2014-10</option>
				<option value="2014-11">2014-11</option>
				<option value="2014-12">2014-12</option>
				
			</select>
    </td>
  </tr>	
  <tr>
    <td class="tagForm">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'py_proyeccion.php');" />
</center>
</form>

<script>

</script>


</body>
</html>
