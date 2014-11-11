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
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Evaluaci&oacute;n de Desempe&ntilde;o | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'evaluacion_desempenio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
//	----------------
$sql="SELECT ep.Secuencia, ep.Periodo, ep.FechaInicio, ep.FechaFin, o.Organismo, MIN(sa.CodDependencia) AS CodDependencia FROM rh_evaluacionperiodo ep INNER JOIN mastorganismos o ON (ep.CodOrganismo=o.CodOrganismo) INNER JOIN seguridad_alterna sa ON (o.CodOrganismo=sa.CodOrganismo AND sa.CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND sa.Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND sa.CodDependencia<>'') WHERE ep.CodOrganismo='".$forganismo."' AND ep.Estado='A' GROUP BY sa.CodOrganismo";
$query_ep=mysql_query($sql) or die ($sql.mysql_error());
$rows_ep=mysql_num_rows($query_ep);
if ($rows_ep!=0) $field_ep=mysql_fetch_array($query_ep);
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaInicio']); $fecha_inicio="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaFin']); $fecha_fin="$d-$m-$a";
//	----------------
$sql="SELECT ree.*, mp.NomCompleto, me.CodEmpleado, rp.CodCargo, rp.DescripCargo, rp.Plantilla, mp2.NomCompleto AS NomEvaluador, me2.CodEmpleado AS CodEvaluador, mp3.NomCompleto AS NomSupervisor, me3.CodEmpleado AS CodSupervisor, (ree.TotalDesempenio+ree.TotalFuncion+ree.TotalMetas) AS Puntaje, ((ree.TotalDesempenio+ree.TotalFuncion+ree.TotalMetas)/3) AS Prom FROM rh_evaluacionempleado ree LEFT JOIN mastpersonas mp2 ON (ree.Evaluador=mp2.CodPersona) LEFT JOIN mastempleado me2 ON (mp2.CodPersona=me2.CodPersona) LEFT JOIN mastpersonas mp3 ON (ree.Supervisor=mp3.CodPersona) LEFT JOIN mastempleado me3 ON (mp3.CodPersona=me3.CodPersona) INNER JOIN mastpersonas mp ON (ree.CodPersona=mp.CodPersona) INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE ree.CodPersona='".$registro."' AND ree.CodOrganismo='".$forganismo."' AND ree.Periodo='".$field_ep['Periodo']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaEvaluacion']); $fecha_evaluacion="$d-$m-$a";
$desempenio=number_format($field['TotalDesempenio'], 2, ',', '.');
$funcion=number_format($field['TotalFuncion'], 2, ',', '.');
$metas=number_format($field['TotalMetas'], 2, ',', '.');
?>

<form id="frmentrada" name="frmentrada" action="evaluacion_desempenio.php" method="POST" onsubmit="return verificarEvaluacionDesempenioEmpleado(this, 'ACTUALIZAR');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fpevaluacion" id="fpevaluacion" value="<?=$fpevaluacion?>" />
<input type="hidden" name="chkempleado" id="chkempleado" value="<?=$chkempleado?>" />
<input type="hidden" name="fempleado" id="fempleado" value="<?=$fempleado?>" />
<input type="hidden" name="fnomempleado" id="fnomempleado" value="<?=$fnomempleado?>" />
<input type="hidden" name="chkresponsable" id="chkresponsable" value="<?=$chkresponsable?>" />
<input type="hidden" name="fresponsable" id="fresponsable" value="<?=$fresponsable?>" />
<input type="hidden" name="fnomresponsable" id="fnomresponsable" value="<?=$fnomresponsable?>" />
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="secuencia" id="secuencia" value="<?=$field_ep['Secuencia']?>" />
<input type="hidden" name="periodo" id="periodo" value="<?=$field_ep['Periodo']?>" />

<div style="width:1100px" class="divFormCaption">Datos de la Evaluaci&oacute;n</div>
<table width="1100" class="tblForm">
    <tr>
        <td class="tagForm" width="150">Empleado:</td>
        <td>
			<input name="persona" type="hidden" id="persona" value="<?=$field['CodPersona']?>" />
			<input name="empleado" type="text" id="empleado" size="10" readonly="readonly" value="<?=$field['CodEmpleado']?>" />
			<input name="nomempleado" type="text" id="nomempleado" size="50" readonly="readonly" value="<?=$field['NomCompleto']?>" />*
			<input type="button" value="..." disabled="disabled" />
		</td>
        <td class="tagForm">Organismo:</td>
        <td colspan="3"><input name="nomorganismo" type="text" id="nomorganismo" size="63" value="<?=$field_ep['Organismo']?>" readonly="readonly" /></td>
        <td rowspan="7" width="150">&nbsp;</td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="tagForm">Cargo:</td>
        <td><input name="nomcargo" type="text" id="nomcargo" size="65" readonly="readonly" value="<?=$field['DescripCargo']?>" /></td>
        <td class="tagForm">Desde:</td>
        <td><input name="fecha_desde" type="text" id="fecha_desde" size="15" value="<?=$fecha_inicio?>" readonly="readonly" /></td>
        <td class="tagForm">Hasta:</td>
        <td><input name="fecha_hasta" type="text" id="fecha_hasta" size="15" value="<?=$fecha_fin?>" readonly="readonly" /></td>
    </tr>
    <tr>
        <td class="tagForm">Comentario:</td>
        <td><input name="comempleado" type="text" id="comempleado" size="65" maxlength="100" value="<?=$field['ComentarioPersona']?>" /></td>
        <td class="tagForm">Evaluaci&oacute;n:</td>
        <td colspan="3">
        	<select name="pevaluacion" id="pevaluacion" style="width:275px;">
				<?=getPeriodosEvaluacion($fpevaluacion, $forganismo, 0);?>
			</select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Evaluador:</td>
        <td>
			<input name="codevaluador" type="hidden" id="codevaluador" value="<?=$field['Evaluador']?>" />
			<input name="evaluador" type="text" id="evaluador" size="10" readonly="readonly" value="<?=$field['CodEvaluador']?>" />
			<input name="nomevaluador" type="text" id="nomevaluador" size="50" readonly="readonly" value="<?=$field['NomEvaluador']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=evaluador&nom=nomevaluador&id=codevaluador', 'height=600, width=1100, left=50, top=50, resizable=yes');" />
		</td>
        <td class="tagForm">Comentario:</td>
        <td colspan="3"><input name="comevaluador" type="text" id="comevaluador" size="65" maxlength="100" value="<?=$field['ComentarioEvaluador']?>" /></td>
    </tr>
    <tr>
        <td class="tagForm">Supervisor:</td>
        <td>
			<input name="codsupervisor" type="hidden" id="codsupervisor" value="<?=$field['Supervisor']?>" />
			<input name="supervisor" type="text" id="supervisor" size="10" readonly="readonly" value="<?=$field['CodSupervisor']?>" />
			<input name="nomsupervisor" type="text" id="nomsupervisor" size="50" readonly="readonly" value="<?=$field['NomSupervisor']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=supervisor&nom=nomsupervisor&id=codsupervisor', 'height=600, width=1100, left=50, top=50, resizable=yes');" />
		</td>
        <td class="tagForm">Comentario:</td>
        <td colspan="3"><input name="comsupervisor" type="text" id="comsupervisor" size="65" maxlength="100" value="<?=$field['ComentarioSupervisor']?>" /></td>
    </tr>
    <tr>
        <td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado']=="A") $activo="checked"; elseif ($field['Estado']=="I") $inactivo="checked";?>
			<input id="activo" name="status" type="radio" value="A" <?=$activo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
		</td>
        <td class="tagForm">Fecha:</td>
		<td colspan="3"><input type="text" name="fecha_evaluacion" id="fecha_evaluacion" size="15" maxlength="10" value="<?=$fecha_evaluacion?>" />* <em>(dd-mm-yyyy)</em></td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="6">
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
        </td>
    </tr>
</table>
<div style="width:1100px" class="divFormCaption">Calificaci&oacute;n</div>
<table width="1100" class="tblForm">
    <tr>
        <td class="tagForm" width="150">Desempe&ntilde;o:</td>
		<td><input type="text" name="desempenio" id="desempenio" size="25" value="<?=$desempenio?>" readonly="readonly" dir="rtl" /></td>
        <td class="tagForm">Funciones:</td>
		<td><input type="text" name="funciones" id="funciones" size="25" value="<?=$funcion?>" readonly="readonly" dir="rtl" /></td>
        <td class="tagForm">Metas:</td>
		<td><input type="text" name="metas" id="metas" size="25" value="<?=$metas?>" readonly="readonly" dir="rtl" /></td>
        <td width="125">&nbsp;</td>
    </tr>
</table>    
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'evaluacion_desempenio.php?limit=<?=$limit?>');" />
</center>
<div style="width:1100px" class="divMsj">Campos Obligatorios *</div>

</form><br />

<table width="1100" align="center">
  <tr>
		<td>
			<div id="header">
			<ul>
			<!-- CSS Tabs -->
			<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Incidentes Cr&iacute;ticos</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Desempe&ntilde;o</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Funciones</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Fortalezas y Debilidades</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='block'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Objetivos y Metas</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='block'; document.getElementById('tab7').style.display='none';" href="#" disabled>Necesidades de Capacitaci&oacute;n</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='block';" href="#" disabled>Revisi&oacute;n de Metas</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:1100px" class="divFormCaption">Incidentes Cr&iacute;ticos</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_incidentes.php?persona=<?=$field['CodPersona']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab2" style="display:none;">
<table width="1100" align="center">
	<tr><td><input name="btGuardar" type="button" id="btGuardar" value="Guardar Calificación" onclick="setCalificacionEvaluacionCompetencia();" /></td></tr>
</table>
<div style="width:1100px" class="divFormCaption">Desempe&ntilde;o</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe name="iCompetencia" id="iCompetencia" class="frameTab" style="height:250px; width:549px;" src="evaluacion_competencias.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>&plantilla=<?=$field['Plantilla']?>&codcargo=<?=$field['CodCargo']?>"></iframe>
        </td>
        <td valign="top">
       		<iframe name="iGrafico" id="iGrafico" class="frameTab" style="height:250px; width:549px;" src="evaluacion_competencias_grafico.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>&plantilla=<?=$field['Plantilla']?>&codcargo=<?=$field['CodCargo']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab3" style="display:none;">
<div style="width:1100px" class="divFormCaption">Funciones</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_funciones.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab4" style="display:none;">
<div style="width:1100px" class="divFormCaption">Fortalezas y Debilidades</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_fortalezas_debilidades.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab5" style="display:none;">
<div style="width:1100px" class="divFormCaption">Objetivos Y Metas</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_metas_objetivos.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab6" style="display:none;">
<div style="width:1100px" class="divFormCaption">Necesidades de Capacitaci&oacute;n</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_necesidad_capacitacion.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>"></iframe>
        </td>
    </tr>
</table>
</div>

<div id="tab7" style="display:none;">
<div style="width:1100px" class="divFormCaption">Revisión de Metas</div>
<table align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
       		<iframe class="frameTab" style="height:250px; width:1098px;" src="evaluacion_revision_metas.php?persona=<?=$field['CodPersona']?>&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&evaluador=<?=$field['Evaluador']?>"></iframe>
        </td>
    </tr>
</table>
</div>

</body>
</html>
