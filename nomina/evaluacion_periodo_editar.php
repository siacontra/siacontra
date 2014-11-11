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
<?php
include("fphp.php");
connect();
$ahora=date("Y-m-d H:i:s");
//	-----------------------
list($organismo, $periodo, $secuencia)=SPLIT( '[:.:]', $registro);
$sql="SELECT * FROM rh_evaluacionperiodo WHERE CodOrganismo='".$organismo."' AND Periodo='".$periodo."' AND Secuencia='".$secuencia."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $ffin="$d-$m-$a";;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $finicio="$d-$m-$a";;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFinal']); $fcierre="$d-$m-$a";;
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Periodos de Evaluaci&oacute;n | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'evaluacion_periodo.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="evaluacion_periodo.php" method="POST" onsubmit="return verificarEvaluacionDesempenio(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$_POST['filtro']?>" />

<div style="width:700px" class="divFormCaption">Datos de la Evaluaci&oacute;n</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">Organismo:</td>
		<td colspan="3">
			<select name="organismo" id="organismo" class="selectBig">
				<?=getOrganismos($organismo, 1);?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Secuencia:</td>
		<td><input name="codigo" type="text" id="codigo" size="5" value="<?=$field['Secuencia']?>" readonly /></td>
		<td class="tagForm">Fecha de Cierre:</td>
		<td><input name="fcierre" type="text" id="fcierre" size="15" maxlength="10" value="<?=$fcierre?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><input name="descripcion" type="text" id="descripcion" size="100" maxlength="100" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Evaluaci&oacute;n:</td>
		<td>
			<select name="tipo" id="tipo" style="width:100px;">
				<?=getTEvaluaciones2($field['TipoEvaluacion'], 0);?>
			</select>*
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<select name="status" id="status" style="width:100px;">
				<?=getStatusEvaluacion($field['Estado'], 0);?>
			</select>*
		</td>
	</tr>
    <tr>
		<td class="tagForm">Periodo:</td>
		<td><input name="periodo" type="text" id="periodo" size="10" maxlength="7" value="<?=$periodo?>" readonly="readonly" />*</td>
        <td class="tagForm">Periodo Anterior:</td>
		<td>
			<select name="anterior" id="anterior" style="width:100px;">
            	<option value="">[Ninguna]</option>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Inicio:</td>
		<td><input name="finicio" type="text" id="finicio" size="15" maxlength="10" value="<?=$finicio?>" />*</td>
		<td class="tagForm">Fin:</td>
		<td><input name="ffin" type="text" id="ffin" size="15" maxlength="10" value="<?=$ffin?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>

<div style="width:700px" class="divFormCaption">Alcance de la Evaluaci&oacute;n</div>
<?
if ($field['FlagDesempenio']=="S") $desempenio="checked";
if ($field['FlagMetas']=="S") $metas="checked";
if ($field['FlagNecesidad']=="S") $necesidad="checked";
if ($field['FlagRevision']=="S") $revision="checked";
if ($field['FlagIncidentes']=="S") $incidentes="checked";
if ($field['FlagFortaleza']=="S") $fortaleza="checked";
if ($field['FlagFunciones']=="S") $funciones="checked";
?>
<table width="700" class="tblForm">
	<tr>
		<td><input type="checkbox" name="incidentes" id="incidentes" value="S" <?=$incidentes?> /></td><td>Incidentes Cr&iacute;ticos</td>
		<td><input type="checkbox" name="metas" id="metas" value="S" <?=$metas?> /></td><td>Metas y/o Objetivos</td>
		<td><input type="checkbox" name="necesidad" id="necesidad" value="S" <?=$necesidad?> /></td><td>Necesidad de Capacitaci&oacute;n</td>
	</tr>
	<tr>
		<td><input type="checkbox" name="funciones" id="funciones" value="S" <?=$funciones?> /></td><td>Funciones</td>
		<td><input type="checkbox" name="revision" id="revision" value="S" <?=$revision?> /></td><td>Revisi&oacute;n de Metas</td>
	</tr>
	<tr>
		<td><input type="checkbox" name="fortaleza" id="fortaleza" value="S" <?=$fortaleza?> /></td><td>Fortalezas y Debilidades</td>
		<td><input type="checkbox" name="desempenio" id="desempenio" value="S" <?=$desempenio?> /></td><td>Desempe&ntilde;o</td>
	</tr>
</table>   
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'evaluacion_periodo.php');" />
</center>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<br /><div class="divDivision">Rango de Valores para la evaluación de objetivos y metas</div><br />

<center><iframe name="iValores" id="iValores" class="frameTab" style="height:500px; width:900px;" src="evaluacion_periodo_valores.php?accion=EDITAR&organismo=<?=$organismo?>&periodo=<?=$periodo?>&secuencia=<?=$secuencia?>"></iframe></center>
</body>
</html>
