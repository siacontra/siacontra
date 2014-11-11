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
		<td class="titulo"> Crear Proceso | Eliminar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'proyeccion_procesos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="proyeccion_procesos.php" method="POST" onsubmit="return verificarProcesos(this, 'ELIMINAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";

$CodProyeccion = $ftCodProyeccion;

?>

<div style="width:900px" class="divFormCaption">Datos del Proceso</div>
<table width="600" class="tblForm">

  
  <tr>
	  	 <?
					 $sql="SELECT * FROM py_proyeccion WHERE CodProyeccion = '$CodProyeccion'";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$field=mysql_fetch_array($query);
		  ?>
	  
    <td class="tagForm">Proyeccion</td>

    
    <input type="hidden" name="CodProyeccion" id="CodProyeccion" value= "<?=$CodProyeccion?>" /> 
    <td><input name="tx_proyeccion" type="text" id="tx_proyeccion"   size="100px" value="<?=$field['CodProyeccion']."-".$field['Descripcion']?>"   readonly /></td>
   
  </tr>	
  
   <tr>
    <td class="tagForm">A&ntilde;o</td>
    <td><input name="tx_anio" type="text" id="tx_anio" size="5" value="2014" maxlength="5"  readonly />*</td>
  </tr>	
  
    <tr>
    <td class="tagForm">Nomina:</td>
    <td>
     <select name="tx_nomina" id="tx_nomina" style="width:200px;"  readonly>
				<?=getTNomina($ftCodTipoNomina, 0)?>
				
			</select>
    </td>
  </tr>	
  <tr>
    <td class="tagForm">Proceso:</td>
    <td >
     <select name="tx_proceso" id="tx_proceso" style="width:200px;"  readonly>
				 <?=
					 $sql="SELECT pr_tipoproceso.CodTipoProceso as Codigo, pr_tipoproceso.Descripcion  FROM pr_tipoproceso LIMIT 0, 1000";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					for ($i=0; $i<$rows; $i++) {
						$field=mysql_fetch_array($query);
						if ($field['Codigo']==$ftCodTipoProceso) echo "<option value='".$field['Codigo']."' selected>".$field['Codigo']." - ".($field['Descripcion'])."</option>"; 
						else echo "<option value='".$field['Codigo']."'>".$field['Codigo']." - ".($field['Descripcion'])."</option>";
					}
				 ?>
				
			</select>
    </td>
  </tr>	
  <tr>
    <td class="tagForm">Periodo</td>
    
    
    <td>
    <select name="tx_periodo" id="tx_periodo" style="width:90px;" readonly >
		       	<option value="<?=$ftPeriodo?>"><?=$ftPeriodo?></option>
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
<input type="submit" value="Eliminar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'proyeccion_procesos.php');" />
</center>
</form>

<script>

</script>


</body>
</html>
