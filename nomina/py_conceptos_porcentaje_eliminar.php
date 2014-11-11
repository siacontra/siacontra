<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

include("fphp_proyecciones.php");
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
		<td class="titulo"> Concepto Porcentaje  | Eliminar Registro</td>
		
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'py_conceptos_porcentaje.php');">[cerrar]</a></td>
	    
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="py_conceptos_porcentaje.php" method="POST" onsubmit="return verificarPorcentajeConcepto(this, 'ELIMINAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";


	

?>

<div style="width:900px" class="divFormCaption">Datos del Registro</div>
<table width="600" class="tblForm">
  <tr>
    <td class="tagForm">Proyeccion </td>
    <td align="left">
	<?php 
		$sql = "
		SELECT
pyc.CodConcepto,
pyc.Porcentaje,
pyc.CodProyeccion,
pyc.Desde,
pyc.Hasta,
py_proyeccion.Descripcion as D1,
pr_concepto.Descripcion 
FROM
py_conceptoporcentaje AS pyc
INNER JOIN py_proyeccion ON py_proyeccion.CodProyeccion = pyc.CodProyeccion
INNER JOIN pr_concepto ON pr_concepto.CodConcepto = pyc.CodConcepto
WHERE
pyc.CodConcepto = '$CodConcepto' AND
pyc.CodProyeccion = '$CodProyeccion'
		
		";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$row = mysql_fetch_array($query, MYSQL_ASSOC);
	

	?>
    	<select id="CodProyeccion" name="CodProyeccion" style="width:25 px" readonly > 
         
        <?php
             getProyeccion_py($CodProyeccion, 3) ;
		?>
        </select>
    </td>
  </tr>	
  
    <tr>
    <td class="tagForm">Concepto: </td>
    <td align="left">
	<?php 
		

	?>
    	<select id="tx_codigo" name="tx_codigo" style="width:25 px" readonly> 
       
        <?php
			
				echo "<option value='".$row['CodConcepto']."'>" .$row['CodConcepto']." - ".$row["Descripcion"]."</option>";
    		
			


		?>
        </select>
    </td>
  </tr>	

  <tr>
    <td class="tagForm">Porcentaje</td>
    <td><input name="tx_porcentaje" type="text" id="tx_porcentaje" size="10" maxlength="10"  value= <?=$row['Porcentaje'] ?> readonly />*</td>
  </tr>	
  <tr>
    <td class="tagForm">Desde</td>
    <td><input name="tx_desde" type="text" id="tx_desde" size="10" maxlength="10" value= <?=$row['Desde']?>  readonly />*</td>
  </tr>	
  
   <tr>
    <td class="tagForm">Hasta</td>
    <td><input name="tx_hasta" type="text" id="tx_hasta" size="10" maxlength="10"  value= <?=$row['Hasta'] ?> readonly />*</td>
  </tr>	

  <tr>
    <td class="tagForm">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

</table>
<center> 
<input type="submit" value="Eliminar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'py_conceptos_porcentaje.php');" />
</center>
</form>

<script>

</script>


</body>
</html>
