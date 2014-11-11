<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$fordenar = "ee.Empleado";
	$fedoreg = "EE";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
}
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte de Evaluaci&oacute;n de Desempe&ntilde;o</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="rh_evaluacion_desempenio_pdf.php?" method="post" target="iReporte">
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
            <input type="checkbox" <?=$corganismo?> onclick="this.checked=!this.checked;" />
            <select name="forganismo" id="forganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia_filtro', 'fdependencia', true);" <?=$dorganismo?>>
                <?=getOrganismos($forganismo, 3)?>
            </select>
		</td>
		<td align="right" width="125">Empleado:</td>
        <td class="gallery clearfix">
            <input type="checkbox" <?=$cempleado?> onclick="chkFiltroLista_3(this.checked, 'fpersona', 'fempleado', 'fnomempleado', 'btEmpleado');" />
        	<input type="hidden" name="fpersona" id="fpersona" value="<?=$fpersona?>" />
            <input type="text" name="fempleado" id="fempleado" style="width:45px;" value="<?=$fempleado?>" disabled="disabled" />
			<input type="text" name="fnomempleado" id="fnomempleado" style="width:245px;" value="<?=$fnomempleado?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=&cod=fempleado&nom=fnomempleado&campo3=fpersona&iframe=true&width=1000&height=475" rel="prettyPhoto[iframe1]" id="btEmpleado" style=" <?=$dempleado?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
            <input type="checkbox" <?=$cdependencia?> onclick="chkFiltro(this.checked, 'fdependencia');" />
            <select name="fdependencia" id="fdependencia" style="width:300px;" <?=$ddependencia?>>
                <option value="">&nbsp;</option>
                <?=getDependencias($fdependencia, $forganismo, 3)?>
            </select>
		</td>
		<td align="right" width="125">Evaluador:</td>
        <td class="gallery clearfix">
            <input type="checkbox" <?=$cevaluador?> onclick="chkFiltroLista_3(this.checked, 'fevaluador', 'fcodevaluador', 'fnomevaluador', 'btEvaluador');" />
        	<input type="hidden" name="fevaluador" id="fevaluador" value="<?=$fevaluador?>" />
            <input type="text" name="fcodevaluador" id="fcodevaluador" style="width:45px;" value="<?=$fcodevaluador?>" disabled="disabled" />
			<input type="text" name="fnomevaluador" id="fnomevaluador" style="width:245px;" value="<?=$fnomevaluador?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=&cod=fcodevaluador&nom=fnomevaluador&campo3=fevaluador&iframe=true&width=1000&height=475" rel="prettyPhoto[iframe2]" id="btEvaluador" style=" <?=$devaluador?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right">Periodo:</td>
        <td>
            <input type="checkbox" checked="checked" onclick="this.checked=!this.checked;" />
            <select name="fperiodo" id="fperiodo" style="width:100px;">
                <?=loadSelectDependiente("rh_evaluacionperiodo", "Periodo", "Periodo", "CodOrganismo", $fperiodo, $forganismo, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectValores("ORDENAR-EVALUACION", $fordenar, 0)?>
            </select>
		</td>
		<td align="right">Estado Reg.:</td>
		<td>
            <input type="checkbox" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
            <select name="fedoreg" id="fedoreg" style="width:100px;" <?=$dedoreg?>>
                <option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-EVALUACION", $fedoreg, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />
</form>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1050px; height:500px;"></iframe>
</center>
</body>
</html>