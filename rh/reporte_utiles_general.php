<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../nomina/fphp_nomina.php");
connect();

include_once ("../clases/MySQL.php");
	
include_once("../comunes/objConexion.php");
	
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
$ftiponom = $_SESSION["NOMINA_ACTUAL"];
$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$dSeleccion1 = "disabled"; 
$dSeleccion2 = "disabled"; 
$dfsittra = "disabled";
//	---------------------------------


$sql ="select periodoutiles from rh_utilesayuda";

$resp = $objConexion->consultar($sql,'matriz');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../nomina/css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../nomina/fscript_nomina.js"></script>


<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    <script  type='text/JavaScript' src='../js/funciones.js' charset="utf-8"></script>
		    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='../rh/js/funcionalidadCes.js' charset="utf-8"></script>

<!--*********************************************** -->

</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte General &Uacute;tiles</td>
		<td align="right"><a class="cerrar"; href="../rh/framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<!--<form name="frmentrada" id="frmentrada" method="post" action="../nomina/pdf_resumen_conceptos.php" target="iReporte">-->
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">N&oacute;mina:</td>
        <td>
        	<select name="ftiponom" id="ftiponom" class="selectBig" onchange="">
				<?=getTNomina($ftiponom, 0)?>
			</select>
        </td>
        <td align="right">Per&iacute;odo:</td>
        <td>
        	<!--<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="forzarCheck('chktiponom');" checked="checked" />-->
			<select name="fperiodo" id="fperiodo" style="width:100px;" onchange="getFOptions_Proceso(this.id, 'ftproceso', 'chktproceso', document.getElementById('ftiponom').value, document.getElementById('forganismo').value, '6');">
				<option value="-1"></option>
				<?php
					
					for($i=0; $i < count($resp); $i++)
					{
					
						echo '<option value="'.$resp[$i]['periodoutiles'].'">'.$resp[$i]['periodoutiles'].'</option>';
					}
				
				?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right"></td>
        <td>
        	<!--<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="forzarCheck('chkperiodo');" checked="checked" />-->
			
		</td>
        <td align="right"><!--Proceso:--></td>
        <td>
        <!--	<input type="checkbox" name="chktproceso" id="chktproceso" value="1" onclick="forzarCheck('chktproceso');" checked="checked" />
			<select name="ftproceso" id="ftproceso" class="selectBig">
				<option value=""></option>
                <?=getTipoProcesoNomina($ftproceso, $fperiodo, $ftiponom, $forganismo, 6)?>
			</select>-->
		</td>
    </tr>
</table>
</div>
<center><input type="button" name="btBuscar" onclick="generarReporteGeneralUtiles();" value="Buscar"></center>
<!--</form> -->

<br /><!--<div class="divDivision">Reporte Resumen de Conceptos</div>--><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>
