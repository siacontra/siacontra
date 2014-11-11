<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
$ftiponom = $_SESSION["NOMINA_ACTUAL"];
$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$dSeleccion1 = "disabled"; 
$dSeleccion2 = "disabled"; 
$dfsittra = "disabled";
	//	---------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
<script type="text/javascript" language="javascript" src="fscript_proyeccion.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte Proyeccion Conceptos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="excel_proyeccion_conceptos.php" target="iReporte" onsubmit="return validarProyeccion(this);">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">

	<tr>  <td> Proyeccion </td>
	     <td>
        	
        	<select name="ftproyeccion" id="ftproyeccion" class="selectBig">
				<option value=""></option>
                <? 
                
					$sql="SELECT py_proyeccion.CodProyeccion, py_proyeccion.Descripcion FROM py_proyeccion";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					$rows=mysql_num_rows($query);
					for ($i=0; $i<$rows; $i++) {
					$field=mysql_fetch_array($query);
					if ($field[0]==$aplicacion) echo "<option value='".$field[0]."' selected>".($field[1])."</option>"; 
					else echo "<option value='".$field[0]."'>".($field[1])."</option>";
					}         
                
                ?>
			</select>
		</td>
    </tr>
	

	
</table>
</div>
<center><input type="submit" name="btBuscar" value="Ver Proyeccion"></center>
</form> 

<br /><div class="divDivision"> Proyeccion de Nomina</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; min-width:100%; height:400px;"></iframe>
</center>
</body>
</html>
