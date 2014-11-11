<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Evaluaci&oacute;n de Desempe&ntilde;o | Evaluar</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'evaluacion_desempenio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
//	----------------
$sql="SELECT ValorParam FROM mastparametros WHERE ParametroClave='PATHFOTO'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$path_logo=mysql_fetch_array($query);
$path_blank=$path_logo['ValorParam'];
//	----------------
list($codorganismo, $codperiodo, $codpersona, $codsecuencia) = SPLIT( '[|]', $registro);
//	----------------
$sql="SELECT ep.Secuencia, ep.Periodo, ep.FechaInicio, ep.FechaFin, o.Organismo, MIN(sa.CodDependencia) AS CodDependencia FROM rh_evaluacionperiodo ep INNER JOIN mastorganismos o ON (ep.CodOrganismo=o.CodOrganismo) INNER JOIN seguridad_alterna sa ON (o.CodOrganismo=sa.CodOrganismo AND sa.CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND sa.Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND sa.CodDependencia<>'') WHERE ep.CodOrganismo='".$codorganismo."' AND ep.Estado='A' GROUP BY sa.CodOrganismo";
$query_ep=mysql_query($sql) or die ($sql.mysql_error());
$rows_ep=mysql_num_rows($query_ep);
if ($rows_ep!=0) $field_ep=mysql_fetch_array($query_ep);
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaInicio']); $fecha_inicio="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaFin']); $fecha_fin="$d-$m-$a";
//	----------------
$sql="SELECT ree.*, mp.NomCompleto, mp.Foto, me.CodEmpleado, rp.CodCargo, rp.DescripCargo, rp.Plantilla, mp2.NomCompleto AS NomEvaluador, me2.CodEmpleado AS CodEvaluador, mp3.NomCompleto AS NomSupervisor, me3.CodEmpleado AS CodSupervisor, (ree.TotalDesempenio+ree.TotalFuncion+ree.TotalMetas) AS Puntaje, ((ree.TotalDesempenio+ree.TotalFuncion+ree.TotalMetas)/3) AS Prom FROM rh_evaluacionempleado ree LEFT JOIN mastpersonas mp2 ON (ree.Evaluador=mp2.CodPersona) LEFT JOIN mastempleado me2 ON (mp2.CodPersona=me2.CodPersona) LEFT JOIN mastpersonas mp3 ON (ree.Supervisor=mp3.CodPersona) LEFT JOIN mastempleado me3 ON (mp3.CodPersona=me3.CodPersona) INNER JOIN mastpersonas mp ON (ree.CodPersona=mp.CodPersona) INNER JOIN mastempleado me ON (mp.CodPersona=me.CodPersona) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) WHERE ree.CodPersona='".$codpersona."' AND ree.Secuencia='".$codsecuencia."' AND ree.CodOrganismo='".$codorganismo."' AND ree.Periodo='".$codperiodo."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaEvaluacion']); $fecha_evaluacion="$d-$m-$a";
$total = $field['TotalMetas'] + $field['TotalDesempenio'];

if ($field['Estado'] == "CO") $disabled = "disabled";
?>

<form id="frmentrada" name="frmentrada" action="evaluacion_desempenio.php" method="POST" onsubmit="return verificarEvaluacionDesempenioEmpleado(this, 'ACTUALIZAR', '');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=$fdependencia?>" />
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
        <td class="tagForm" width="100">Empleado:</td>
        <td>
			<input name="persona" type="hidden" id="persona" value="<?=$field['CodPersona']?>" />
			<input name="empleado" type="text" id="empleado" size="10" readonly="readonly" value="<?=$field['CodEmpleado']?>" />
			<input name="nomempleado" type="text" id="nomempleado" size="50" readonly="readonly" value="<?=$field['NomCompleto']?>" />
			<input type="button" value="..." disabled="disabled" /> *
		</td>
        <td class="tagForm" width="100">Organismo:</td>
        <td colspan="3"><input name="nomorganismo" type="text" id="nomorganismo" size="63" value="<?=$field_ep['Organismo']?>" readonly="readonly" /></td>
        <td rowspan="7" width="150" align="center">
        	<img src="<?=$path_blank."../".$field["Foto"]?>" name="img_foto" width="105" height="125" id="img_foto" />
		</td>
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
        <td><textarea name="comempleado" id="comempleado" style="width:98%; height:50px;" <?=$disabled?>><?=$field['ComentarioPersona']?></textarea></td>
        <td class="tagForm">Evaluaci&oacute;n:</td>
        <td colspan="3">
        	<select name="pevaluacion" id="pevaluacion" style="width:275px;" <?=$disabled?>>
				<?=getPeriodosEvaluacion($fpevaluacion, $codorganismo, 0);?>
			</select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Jefe Inmediato:</td>
        <td>
			<input name="codevaluador" type="hidden" id="codevaluador" value="<?=$field['Evaluador']?>" />
			<input name="evaluador" type="text" id="evaluador" size="10" readonly="readonly" value="<?=$field['CodEvaluador']?>" />
			<input name="nomevaluador" type="text" id="nomevaluador" size="50" readonly="readonly" value="<?=$field['NomEvaluador']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=evaluador&nom=nomevaluador&id=codevaluador&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" <?=$disabled?> />
		</td>
        <td class="tagForm">Jefe Mediato:</td>
        <td colspan="3">
			<input name="codsupervisor" type="hidden" id="codsupervisor" value="<?=$field['Supervisor']?>" />
			<input name="supervisor" type="text" id="supervisor" size="10" readonly="readonly" value="<?=$field['CodSupervisor']?>" />
			<input name="nomsupervisor" type="text" id="nomsupervisor" size="50" readonly="readonly" value="<?=$field['NomSupervisor']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=supervisor&nom=nomsupervisor&id=codsupervisor&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" <?=$disabled?> />
		</td>
    </tr>
    <tr>
        <td class="tagForm">Comentario (Evaluador):</td>
        <td><textarea name="comevaluador" id="comevaluador" style="width:98%; height:50px;" <?=$disabled?>><?=$field['ComentarioEvaluador']?></textarea></td>
        <td class="tagForm">Comentario (Evaluado):</td>
        <td colspan="3"><textarea name="comsupervisor" id="comsupervisor" style="width:98%; height:50px;" <?=$disabled?>><?=$field['ComentarioSupervisor']?></textarea></td>
	</tr>
    <tr>
        <td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "EE") $estado = "En Evaluacion"; else $estado = "Evaluado"; ?>
        	<input type="hidden" id="status" value="<?=$field['Estado']?>" />
			<input type="text" value="<?=$estado?>" disabled="disabled" />
		</td>
        <td class="tagForm">Fecha:</td>
		<td colspan="3"><input type="text" name="fecha_evaluacion" id="fecha_evaluacion" size="15" maxlength="10" value="<?=$fecha_evaluacion?>" <?=$disabled?> />* <em>(dd-mm-yyyy)</em></td>
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
        <td class="tagForm" width="150">Competencias:</td>
		<td><input type="text" name="desempenio" id="desempenio" value="<?=number_format($field['TotalDesempenio'], 2, ',', '.')?>" style="font-size:12px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" /></td>
        <td class="tagForm">Metas:</td>
		<td>
        	<input type="text" name="metas" id="metas" value="<?=number_format($field['TotalMetas'], 2, ',', '.')?>" style="font-size:12px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" />
        	<input type="hidden" name="funciones" id="funciones" value="<?=$field['TotalFuncion']?>" />
        </td>
        <td class="tagForm">Total:</td>
		<td><input type="text" name="total" id="total" value="<?=number_format($total, 2, ',', '.')?>" style="font-size:14px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" /></td>
        <td width="250">
        	<span style="font-weight:bold;" id="escala">
            <?
			$sql = "SELECT * FROM rh_gradoscalificacion ORDER BY PuntajeMin";
			$query_gc = mysql_query($sql) or die($sql.mysql_error());	$i = 0;
			$rows_gc = mysql_num_rows($query_gc);
			while ($field_gc = mysql_fetch_array($query_gc)) {	$i++;
				if ($total <= $field_gc['PuntajeMax']) {
					echo ($field_gc['Descripcion']);
					break;
				}
				elseif ($i == $rows_gc && $total >= $field_gc['PuntajeMax']) {
					echo ($field_gc['Descripcion']);
					break;
				}
			}
			?>
            </span>
		</td>
    </tr>
</table>
<center>
<input type="submit" value="Procesar" id="btGuardar" style="width:75px;" <?=$disabled?> />
<input type="button" value="Cancelar" onclick="cargarPagina(this.form, 'evaluacion_desempenio.php?limit=<?=$limit?>');" style="width:75px;" /> | 
<input type="button" value="Finalizar Evaluación" onclick="verificarEvaluacionDesempenioEmpleado(this.form, 'ACTUALIZAR', 'FINALIZAR');" />
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
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='block'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Fortalezas y Debilidades</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='block'; document.getElementById('tab7').style.display='none';" href="#" disabled>Necesidades de Capacitaci&oacute;n</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Competencia</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='block'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Funciones</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='block'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='none';" href="#" disabled>Objetivos y Metas</a></li>
			<li><a onclick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='none'; document.getElementById('tab3').style.display='none'; document.getElementById('tab4').style.display='none'; document.getElementById('tab5').style.display='none'; document.getElementById('tab6').style.display='none'; document.getElementById('tab7').style.display='block';" href="#" disabled>Revisi&oacute;n de Metas</a></li>
			</ul>
			</div>
		</td>
	</tr>
</table>

<div id="tab1" style="display:block;">
<div style="width:1100px" class="divFormCaption">Incidentes Cr&iacute;ticos</div>
<?
$sql = "(SELECT
			mf.Secuencia, 
			mf.Documento, 
			mf.FechaDoc, 
			mf.Observacion, 
			md.Descripcion AS Clasificacion, 
			'Mérito' AS Tipo 
		 FROM 
		 	rh_meritosfaltas mf 
			LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion = md.CodDetalle AND md.CodMaestro = 'MERITO') 
		 WHERE
		 	mf.CodPersona = '".$field['CodPersona']."' AND 
			mf.Tipo = 'M' AND
			mf.FechaDoc >= '".formatFechaAMD($fecha_inicio)."' AND
			mf.FechaDoc <= '".formatFechaAMD($fecha_fin)."')
		
		UNION 
		
		(SELECT 
			mf.Secuencia, 
			mf.Documento, 
			mf.FechaDoc, 
			mf.Observacion, 
			md.Descripcion AS Clasificacion, 
			'Demérito' AS Tipo 
		 FROM
		 	rh_meritosfaltas mf 
			LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion = md.CodDetalle AND md.CodMaestro = 'DEMERITO') 
		 WHERE
		 	mf.CodPersona = '".$field['CodPersona']."' AND 
			mf.Tipo = 'D' AND
			mf.FechaDoc >= '".formatFechaAMD($fecha_inicio)."' AND
			mf.FechaDoc <= '".formatFechaAMD($fecha_fin)."')
		
		ORDER BY Secuencia";
$query_incidentes = mysql_query($sql) or die ($sql.mysql_error());
$rows_incidentes = mysql_num_rows($query_incidentes);
//	MUESTRO LA TABLA
?>
<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th width="75" scope="col">Tipo</th>
        <th width="100" scope="col">Fecha</th>
        <th width="300" scope="col">Documento</th>
        <th width="200" scope="col">Clasificaci&oacute;n</th>
        <th scope="col">Observaciones</th>
    </tr>
	<?
    for ($i=1; $i<=$rows_incidentes; $i++) {
        $field_incidentes = mysql_fetch_array($query_incidentes);
        ?>
        <tr class="trListaBody">
            <td><?=$field_incidentes['Tipo']?></td>
            <td align="center"><?=formatFechaDMA($field_incidentes['FechaDoc'])?></td>
            <td><?=($field_incidentes["Documento"])?></td>
            <td><?=$field_incidentes["Clasificacion"]?></td>
            <td><?=($field_incidentes["Observacion"])?></td>
        </tr>
    <? } ?>
</table>
</div></td></tr></table>
</td></tr></table>
</div>

<div id="tab2" style="display:none;">
<div style="width:1100px" class="divFormCaption">Competencia</div>
<form name="frmdetalles_tab2" id="frmdetalles_tab2">
<input type="hidden" id="seldetalle_tab2" />

<table width="1100" class="tblBotones">
    <tr>
    	<td>
        	<input type="button" value="Calificacion" onclick="selector(this.form, 'seldetalle_tab2', 'listado_calificativos.php?ficha=competencia&cod=txtcodcalificativo&nom=txtnomcalificativo&ventana=listaEvaluacionDesempeno&organismo=<?=$codorganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=300, width=400, left=200, top=200, resizable=yes');" <?=$disabled?> /> | 
            
            <input type="button" value="Ver Información" onclick="window.open('competencias_ver.php?registro='+document.getElementById('seldetalle_tab2').value.substr(5), '', 'height=700, width=1000, left=100, top=100, resizable=yes');" />
		</td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr>

<td valign="top" style="height:350px; width:550px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:550px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th width="25" scope="col">#</th>
        <th scope="col">Factor</th>
        <th width="50" scope="col">Calif.</th>
        <th width="50" scope="col">Peso</th>
        <th width="50" scope="col">Total</th>
    </tr>
	<?
	$sql = "SELECT
				ee.*,
				ef.TipoCompetencia,
				ef.Area,
				ef.Descripcion AS Factor,
				ef.ValorRequerido, 
				ef.ValorMinimo,
				ea.Descripcion AS NomArea,
				mmd.Descripcion AS NomTipoCompetencia
			FROM
				rh_empleado_evaluacion ee
				INNER JOIN rh_evaluacionfactores ef ON (ee.Competencia = ef.Competencia)
				INNER JOIN rh_evaluacionarea ea ON (ef.Area = ea.Area)
				INNER JOIN mastmiscelaneosdet mmd ON (ef.TipoCompetencia = mmd.CodDetalle AND mmd.CodMaestro = 'TIPOCOMPE')
			WHERE
				ee.CodPersona = '".$field['CodPersona']."'
			ORDER BY TipoCompetencia, Area, Competencia";
	$query_desempeno = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
    while ($field_desempeno = mysql_fetch_array($query_desempeno)) { $nrodetalles++;
		$idagrupa = $field_desempeno['TipoCompetencia']."-".$field_desempeno['Area'];
		
		$competencia_total = $field_desempeno['Calificacion'] * $field_desempeno['Peso'];
		
		$sum_subcompetencia_calificacion += $field_desempeno['Calificacion'];
		$sum_subcompetencia_peso += $field_desempeno['Peso'];
		$sum_subcompetencia_total += $competencia_total;
		
		$sum_competencia_calificacion += $field_desempeno['Calificacion'];
		$sum_competencia_peso += $field_desempeno['Peso'];
		$sum_competencia_total += $competencia_total;
		
		if ($agrupa != $idagrupa) {
			$agrupa = $idagrupa;
			if ($nrodetalles != 1) {
				?>
                <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td>
                        <input type="text" name="calificacion_sub_total_tab2" id="calificacion_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_calificacion, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
                    </td>
                    <td>
                        <input type="text" name="peso_sub_total_tab2" id="peso_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_peso, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
                    </td>
                    <td>
                        <input type="text" name="subtotal_tab2" id="subtotal_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
                    </td>
                </tr>
                <?
				$sum_subcompetencia_calificacion = 0;
				$sum_subcompetencia_peso = 0;
				$sum_subcompetencia_total = 0;
			}
			?>
            <tr class="trListaBody2">
                <td align="center">&nbsp;</td>
                <td colspan="4"><strong><?=($field_desempeno["NomTipoCompetencia"]." - ".$field_desempeno['NomArea'])?></strong></td>
            </tr>
            <?
		}
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab2');" id="tab2_<?=$nrodetalles?>">
            <td align="center"><?=$nrodetalles?></td>
			<td>
            	<input type="hidden" name="txtplantilla_tab2" value="<?=$field_desempeno['Plantilla']?>" />
            	<input type="hidden" name="txtcompetencia_tab2" value="<?=$field_desempeno['Competencia']?>" />
				<?=($field_desempeno["Factor"])?>
			</td>
            <td>
            	<input type="hidden" id="txtcodcalificativo_tab2_<?=$nrodetalles?>" value="<?=($field_desempeno["NomCalificacion"])?>" />
                
                <input type="text" name="txtcalificacion_tab2" id="txtnomcalificativo_tab2_<?=$nrodetalles?>" value="<?=number_format($field_desempeno['Calificacion'], 2, ',', '.')?>" style="width:99%; text-align:right;" class="calificacion_<?=$idagrupa?>_tab2" disabled="disabled" />
            </td>
            <td><input type="text" name="txtpeso_tab2" id="txtpeso_tab2_<?=$nrodetalles?>" value="<?=number_format($field_desempeno['Peso'], 2, ',', '.')?>" style="width:99%; text-align:right;" onchange="setTotalesCalificacionPesoDesempeno(<?=$nrodetalles?>);" class="peso_<?=$agrupa?>_tab2" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled?> /></td>            
            <td align="right"><span id="total_tab2_<?=$nrodetalles?>"><?=number_format($competencia_total, 2, ',', '.')?></span></td>
        </tr>
    	<?
	} ?>
    <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>
            <input type="text" name="calificacion_sub_total_tab2" id="calificacion_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_calificacion, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
        </td>
        <td>
            <input type="text" name="peso_sub_total_tab2" id="peso_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_peso, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
        </td>
        <td>
            <input type="text" name="subtotal_tab2" id="subtotal_<?=$agrupa?>_tab2" value="<?=number_format($sum_subcompetencia_total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
        </td>
    </tr>    
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>
            <input type="text" id="calificacion_total_tab2" value="<?=number_format($sum_competencia_calificacion, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold; display:none;" disabled="disabled" />
        </td>
        <td>
            <input type="text" id="peso_total_tab2" value="<?=number_format($sum_competencia_peso, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
        </td>
        <td>
            <input type="text" id="total_tab2" value="<?=number_format($sum_competencia_total, 2, ',', '.')?>" style="width:99%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
        </td>
    </tr>    
</table>
</div></td></tr></table>
</td>

<td valign="top" style="height:350px; width:550px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:550px;">

<?
$sql="SELECT MIN(PuntajeMin) AS Min, MAX(PuntajeMax) AS Max FROM rh_gradoscompetencia";
$query_puntaje=mysql_query($sql) or die ($sql.mysql_error());
$field_puntaje=mysql_fetch_array($query_puntaje);
?>

<table width="100%" class="tblLista">
    <tbody id="listaDetalles_tab2_grafico">
	<!--	Imprimo los titulos y la barra de puntutacion	-->
	<tr style="background-color:#666666">
    	<th>
			<?php
            $sql="SELECT * FROM rh_gradoscompetencia ORDER BY Grado";
            $query_grado=mysql_query($sql) or die ($sql.mysql_error());
            $rows_grado=mysql_num_rows($query_grado);
            for ($k=0; $k<$rows_grado; $k++) {
                $field_grado=mysql_fetch_array($query_grado);
                $min[$k]=$field_grado['PuntajeMin'];
                $max[$k]=$field_grado['PuntajeMax'];
                $col[$k]=$field_grado['PuntajeMax']-$field_grado['PuntajeMin']+1;
                $grado[$k]=$field_grado['Descripcion'];
            }
            echo "<table class='grillaTable'>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) echo "<td align='center' class='grillaTd' colspan='".$col[$k]."' style='font-size:8px;'><em>".$grado[$k]."</em></td>";
            echo "</tr>";
            
            echo "<tr class='grillaTr'>";
            for ($k=0; $k<$rows_grado; $k++) {
                for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' class='grillaTd' width='20%' style='font-size:9px;'><b>".$j."</b></td>";
            }
            echo "</tr>";
			
			echo "</table>";
            ?>
    	</th>
    </tr>
    
	<?php
	$k=0;
	$sql = "SELECT
				ee.*,
				ef.Descripcion AS NomCompetencia,
				ef.TipoCompetencia,
				ef.Area,
				ef.Descripcion AS Factor,
				ef.ValorRequerido, 
				ef.ValorMinimo,
				ea.Descripcion AS NomArea,
				mmd.Descripcion AS NomTipoCompetencia
			FROM
				rh_empleado_evaluacion ee
				INNER JOIN rh_evaluacionfactores ef ON (ee.Competencia = ef.Competencia)
				INNER JOIN rh_evaluacionarea ea ON (ef.Area = ea.Area)
				INNER JOIN mastmiscelaneosdet mmd ON (ef.TipoCompetencia = mmd.CodDetalle AND mmd.CodMaestro = 'TIPOCOMPE')
			WHERE
				ee.CodPersona = '".$field['CodPersona']."'
			ORDER BY TipoCompetencia, Area, Competencia";
	$query_grafico = mysql_query($sql) or die ($sql.mysql_error());
	$rows_grafico = mysql_num_rows($query_grafico);
	while($field_grafico = mysql_fetch_array($query_grafico)) {
		$l++;
		$det_grupo=$field_grafico['TipoCompetencia']." - ".$field_grafico['Area'];
		$nom_grupo=$field_grafico['NomTipoCompetencia']." - ".$field_grafico['NomArea'];
		if ($grupo!=$det_grupo) {
			$grupo=$det_grupo;
			echo "<tr class='trListaBody2'><td>".($nom_grupo)."</td></tr>";
		} 
		?>
		<tr>
			<td>
            	<?
				echo "<table class='grillaTable' width='100%' cellpadding='0' cellspacing='0'>";
					echo "<tr class='grillaTr'>";
					echo "<td>".($field_grafico['NomCompetencia'])."</td>";
					echo "</tr>";
				echo"</table>";
				
				echo "<table width='100%' cellpadding='0' cellspacing='0'>";
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='R_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
					
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='M_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
					
					echo "<tr>";
					for ($k=0; $k<$rows_grado; $k++) {
						for ($j=$min[$k]; $j<=$max[$k]; $j++) echo "<td align='center' width='20%' style='font-size:10px; height:4px;' id='P_".$j."_".$l."'>&nbsp;</td>";
					}
					echo "</tr>";
				echo"</table>";
				?>
            </td>
		</tr>
        <?
		$sql="SELECT ValorRequerido, ValorMinimo FROM rh_evaluacionfactores WHERE Competencia='".$field_grafico['Competencia']."'";
		$query_valor=mysql_query($sql) or die ($sql.mysql_error());
		$rows_valor=mysql_num_rows($query_valor);
		if ($rows_valor!=0) $field_valor=mysql_fetch_array($query_valor);
		?>
        <script language="javascript">
			setRequerido2(<?=$field_puntaje['Min']?>, <?=$field_puntaje['Max']?>, <?=$field_valor['ValorRequerido']?>, <?=$l?>);
		</script>
		<script language="javascript">
			setMinimo2(<?=$field_puntaje['Min']?>, <?=$field_puntaje['Max']?>, <?=$field_valor['ValorMinimo']?>, <?=$l?>);
		</script>
		<script language="javascript">
			setPuntaje(<?=$field_puntaje['Min']?>, <?=$field_puntaje['Max']?>, <?=$field_grafico['Calificacion']?>, <?=$l?>);
		</script>
		<?
	}
	$rows_grafico = (int) $rows_grafico;
	?>
    </tbody>
</table>

</div></td></tr></table>
</td>

</tr></table>
<input type="hidden" id="min_tab2" value="<?=$field_puntaje['Min']?>" />
<input type="hidden" id="max_tab2" value="<?=$field_puntaje['Max']?>" />
<input type="hidden" id="nrodetalles_tab2" value="0" />
<input type="hidden" id="cantdetalles_tab2" value="0" />
</form>
</div>

<div id="tab3" style="display:none;">
<div style="width:1100px" class="divFormCaption">Funciones</div>
<?
$sql = "SELECT cf.*
		FROM rh_cargofunciones cf
		WHERE cf.CodCargo = '".$field['CodCargo']."'
		ORDER BY Funcion DESC, CodFuncion";
$query_funciones = mysql_query($sql) or die ($sql.mysql_error());
$rows_funciones = mysql_num_rows($query_funciones);
//	MUESTRO LA TABLA
?>
<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="35">#</th>
        <th scope="col">Funciones</th>
    </tr>
	<?
    for ($i=1; $i<=$rows_funciones; $i++) {
        $field_funciones = mysql_fetch_array($query_funciones);
		if ($grupo != $field_funciones['Funcion']) {
			$grupo = $field_funciones['Funcion'];
			if ($grupo == "02") $g = "Funciones Generales";
			else $g = "Funciones Especificas";
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=($g)?></td>
            </tr>
        	<?
		}
        ?>
        <tr class="trListaBody">
            <td><?=$i?></td>
            <td><?=($field_funciones["Descripcion"])?></td>
        </tr>
    <? } ?>
</table>
</div></td></tr></table>
</td></tr></table>
</div>

<div id="tab4" style="display:none;">
<div style="width:1100px" class="divFormCaption">Fortalezas y Debilidades</div>
<form name="frmdetalles_tab4" id="frmdetalles_tab4">
<input type="hidden" id="seldetalle_tab4" />

<table width="1100" class="tblBotones">
    <tr>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalle(this.form, 'insertarEvaluacionFortaleza', 'tab4');" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab4').value, 'tab4');" />
        </td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="100">* Tipo</th>
        <th scope="col">* Descripci&oacute;n</th>
    </tr>
                
    <tbody id="listaDetalles_tab4">
    <?
	$sql = "SELECT * 
			FROM rh_empleado_desempenio 
			WHERE
				CodOrganismo = '".$codorganismo."' AND
				Periodo = '".$codperiodo."' AND
				CodPersona = '".$codpersona."' AND
				Secuencia = '".$codsecuencia."'";
	$query_fortalezas = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while($field_fortalezas = mysql_fetch_array($query_fortalezas)) { $nrodetalles++;
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab4');" id="tab4_<?=$nrodetalles?>">
            <td>
                <select name="slttipo_tab4" style="width:99%;" <?=$disabled?>>
                    <option value=""></option>
                    <?=getTipoFD($field_fortalezas['Tipo'], 0);?>
                </select>
            </td>
            <td><input type="text" name="txtdescripcion_tab4" value="<?=($field_fortalezas['Descripcion'])?>" maxlength="255" style="width:99%" <?=$disabled?> /></td>
		</tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
<input type="hidden" id="nrodetalles_tab4" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles_tab4" value="<?=$nrodetalles?>" />
</form>
</div>

<div id="tab5" style="display:none;">
<div style="width:1100px" class="divFormCaption">Objetivos Y Metas</div>
<form name="frmdetalles_tab5" id="frmdetalles_tab5">
<input type="hidden" id="seldetalle_tab5" />

<table width="1100" class="tblBotones">
    <tr>
    	<td><input type="button" class="btLista" value="Calificacion" onclick="selector(this.form, 'seldetalle_tab5', 'listado_calificativos.php?ficha=objetivos&cod=txtcodcalificativo&nom=txtnomcalificativo&ventana=listaEvaluacionObjetivosMetas&organismo=<?=$codorganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=300, width=400, left=200, top=200, resizable=yes');" <?=$disabled?> /></td>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalleMetas(this.form, 'insertarEvaluacionObjetivosMetas', 'tab5');" <?=$disabled?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab5').value, 'tab5');" <?=$disabled?> />
        </td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">* Descripci&oacute;n</th>
        <th scope="col" width="70">* Periodo</th>
        <th scope="col" width="300">Comentarios</th>
        <th scope="col" width="75">* Desde</th>
        <th scope="col" width="75">* Hasta</th>
        <th scope="col" width="80">* Calificaci&oacute;n</th>
        <th scope="col" width="80">* Peso</th>
        <th scope="col" width="80">Total</th>
    </tr>
                
    <tbody id="listaDetalles_tab5">
    <?
	$sql = "SELECT * 
			FROM rh_empleado_metas 
			WHERE
				CodOrganismo = '".$codorganismo."' AND
				Periodo = '".$codperiodo."' AND
				CodPersona = '".$codpersona."' AND
				Secuencia = '".$codsecuencia."'";
	$query_metas = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while($field_metas = mysql_fetch_array($query_metas)) { $nrodetalles++;
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab5');" id="tab5_<?=$nrodetalles?>">
            <td>
            	<textarea name="txtdescripcion_tab5" style="width:99%; height:15px;" onBlur="this.style.height='15px'" onFocus="this.style.height='60px'" <?=$disabled?> onchange="setDescripcionMeta(this.value, '<?=$nrodetalles?>');"><?=($field_metas['Descripcion'])?></textarea>
			</td>
            <td><input type="text" name="txtperiodo_tab5" value="<?=$field_metas['PeriodoMetas']?>" maxlength="7" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td>
            	<textarea name="txtcomentarios_tab5" style="width:99%; height:15px;" onBlur="this.style.height='15px'" onFocus="this.style.height='60px'" <?=$disabled?>><?=($field_metas['Comentarios'])?></textarea>
			</td>
            <td><input type="text" name="txtfdesde_tab5" value="<?=formatFechaDMA($field_metas['Desde'])?>" maxlength="10" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td><input type="text" name="txtfhasta_tab5" value="<?=formatFechaDMA($field_metas['Hasta'])?>" maxlength="10" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td>
                <input type="hidden" id="txtcodcalificativo_tab5_<?=$nrodetalles?>" />
                <input type="text" name="txtcalificacion_tab5" id="txtnomcalificativo_tab5_<?=$nrodetalles?>" value="<?=number_format($field_metas['Calificacion'], 2, ',', '.')?>" style="width:99%; text-align:center;" disabled="disabled" />
            </td>
            <td><input type="text" name="txtpeso_tab5" id="txtpeso_tab5_<?=$nrodetalles?>" value="<?=number_format($field_metas['Peso'], 2, ',', '.')?>" style="width:99%; text-align:right;" onchange="setTotalesCalificacionPeso(<?=$nrodetalles?>);" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled?> /></td>
            <td align="right"><span id="total_tab5_<?=$nrodetalles?>"><?=number_format(($field_metas['Calificacion']*$field_metas['Peso']), 2, ',', '.')?></span></td>
		</tr>
        <?
		$tab5_total_peso += $field_metas['Peso'];
		$tab5_total += ($field_metas['Calificacion']*$field_metas['Peso']);
	}
	?>
    </tbody>
    
    <tfoot>
    	<tr><td colspan="8">&nbsp;</td></tr>
    	<tr><td colspan="8">&nbsp;</td></tr>
    	<tr>
        	<td colspan="6">&nbsp;</td>
        	<td align="right"><span style="font-size:11px; font-weight:bold;" id="tab5_total_peso"><?=number_format($tab5_total_peso, 2, ',', '.')?></span></td>
        	<td align="right"><span style="font-size:11px; font-weight:bold;" id="tab5_total"><?=number_format($tab5_total, 2, ',', '.')?></span></td>
        </tr>
    </tfoot>
</table>
</div></td></tr></table>
</td></tr></table>
<input type="hidden" id="nrodetalles_tab5" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles_tab5" value="<?=$nrodetalles?>" />
</form>
</div>

<div id="tab6" style="display:none;">
<div style="width:1100px" class="divFormCaption">Necesidades de Capacitaci&oacute;n</div>
<form name="frmdetalles_tab6" id="frmdetalles_tab6">
<input type="hidden" id="seldetalle_tab6" />

<table width="1100" class="tblBotones">
    <tr>
    	<td><input type="button" class="btLista" value="Curso" onclick="selector(this.form, 'seldetalle_tab6', 'lista_cursos.php?cod=txtcodcurso&nom=txtnomcurso&ventana=listaEvaluacionNecesidad&organismo=<?=$codorganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=600, width=750, left=50, top=0, resizable=yes');" <?=$disabled?> /></td>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalle(this.form, 'insertarEvaluacionNecesidad', 'tab6');" <?=$disabled?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab6').value, 'tab6');" <?=$disabled?> />
        </td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" width="275">Necesidad de Capacitaci&oacute;n</th>
        <th scope="col" width="100">Prioridad</th>
        <th scope="col" width="325">Curso de Capacitaci&oacute;n</th>
        <th scope="col">Objetivo de Mejora</th>
    </tr>
                
    <tbody id="listaDetalles_tab6">
    <?
	$sql = "SELECT
				en.*,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_necesidad en
				INNER JOIN rh_cursos c ON (en.CodCurso = c.CodCurso)
			WHERE
				en.CodOrganismo = '".$codorganismo."' AND
				en.Periodo = '".$codperiodo."' AND
				en.CodPersona = '".$codpersona."' AND
				en.Secuencia = '".$codsecuencia."'";
	$query_necesidad = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while($field_necesidad = mysql_fetch_array($query_necesidad)) { $nrodetalles++;
		?>
        <tr class="trListaBody" onclick="mClk(this, 'seldetalle_tab6');" id="tab6_<?=$nrodetalles?>">
            <td><input type="text" name="txtnecesidad_tab6" value="<?=($field_necesidad['Necesidad'])?>" maxlength="100" style="width:99%" <?=$disabled?> /></td>
            <td>
            	<select name="sltprioridad_tab6" style="width:99%;" <?=$disabled?>>
                    <option value=""></option>
                    <?=loadSelectValores("PRIORIDAD", $field_necesidad['Prioridad'], 0);?>
                </select>
            </td>
            <td>
                <input type="hidden" name="txtcodcurso_tab6" id="txtcodcurso_tab6_<?=$nrodetalles?>" value="<?=$field_necesidad['CodCurso']?>" />
                <input type="text" id="txtnomcurso_tab6_<?=$nrodetalles?>" value="<?=($field_necesidad['NomCurso'])?>" style="width:99%;" disabled="disabled" />
            <td><input type="text" name="txtobjetivo_tab6" value="<?=($field_necesidad['Objetivo'])?>" maxlength="100" style="width:99%" <?=$disabled?> /></td>
		</tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
<input type="hidden" id="nrodetalles_tab6" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles_tab6" value="<?=$nrodetalles?>" />
</form>
</div>

<div id="tab7" style="display:none;">
<div style="width:1100px" class="divFormCaption">Revisión de Metas</div>
<form name="frmdetalles_tab7" id="frmdetalles_tab7">
<input type="hidden" id="seldetalle_tab7" />

<table width="1100" class="tblBotones">
    <tr>
    	<td align="right"></td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="1750" class="tblLista">
    <tr class="trListaHead">
        <th scope="col" rowspan="2">Meta</th>
        <th scope="col" colspan="3">I Revisión</th>
        <th scope="col" colspan="3">II Revisión</th>
        <th scope="col" colspan="3">III Revisión</th>
    </tr>
    <tr class="trListaHead">
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="75">Porcentaje</th>
        <th scope="col" width="300">Observacion</th>
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="75">Porcentaje</th>
        <th scope="col" width="300">Observacion</th>
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="75">Porcentaje</th>
        <th scope="col" width="300">Observacion</th>
    </tr>
                
    <tbody id="listaDetalles_tab7">
    <?
	$sql = "SELECT
				er.*,
				em.Descripcion AS Meta
			FROM 
				rh_empleado_revision er
				INNER JOIN rh_empleado_metas em ON (em.CodOrganismo = er.CodOrganismo AND 
													em.Periodo = er.Periodo AND
													em.CodPersona = er.CodPersona AND
													em.Secuencia = er.Secuencia AND
													em.SecuenciaDesempenio = er.SecuenciaDesempenio)
			WHERE
				er.CodOrganismo = '".$codorganismo."' AND
				er.Periodo = '".$codperiodo."' AND
				er.CodPersona = '".$codpersona."' AND
				er.Secuencia = '".$codsecuencia."'";
	$query_revision = mysql_query($sql) or die ($sql.mysql_error()); $nrodetalles=0;
	while($field_revision = mysql_fetch_array($query_revision)) { $nrodetalles++;
		?>
        <tr class="trListaBody">
   			<td><span id="meta_tab7_<?=$nrodetalles?>"><?=($field_revision['Meta'])?></span></td>
            <td><input type="text" name="txtfecha1_tab7" value="<?=formatFechaDMA($field_revision['Fecha1'])?>" maxlength="10" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td><input type="text" name="txtporcentaje1_tab7" value="<?=number_format($field_revision['Porcentaje1'], 2, ',', '.')?>" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled?> /></td>
            <td><textarea name="txtobservacion1_tab7" style="width:99%; height:15px;" onBlur="this.style.height='15px'" onFocus="this.style.height='60px'" <?=$disabled?> onchange="setDescripcionMeta(this.value, '<?=$nrodetalles?>');"><?=($field_revision['Observacion1'])?></textarea></td>
            <td><input type="text" name="txtfecha2_tab7" value="<?=formatFechaDMA($field_revision['Fecha2'])?>" maxlength="10" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td><input type="text" name="txtporcentaje2_tab7" value="<?=number_format($field_revision['Porcentaje2'], 2, ',', '.')?>" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled?> /></td>
            <td><textarea name="txtobservacion2_tab7" style="width:99%; height:15px;" onBlur="this.style.height='15px'" onFocus="this.style.height='60px'" <?=$disabled?> onchange="setDescripcionMeta(this.value, '<?=$nrodetalles?>');"><?=($field_revision['Observacion2'])?></textarea></td>
            <td><input type="text" name="txtfecha3_tab7" value="<?=formatFechaDMA($field_revision['Fecha3'])?>" maxlength="10" style="width:99%; text-align:center;" <?=$disabled?> /></td>
            <td><input type="text" name="txtporcentaje3_tab7" value="<?=number_format($field_revision['Porcentaje3'], 2, ',', '.')?>" style="width:99%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled?> /></td>
            <td><textarea name="txtobservacion3_tab7" style="width:99%; height:15px;" onBlur="this.style.height='15px'" onFocus="this.style.height='60px'" <?=$disabled?> onchange="setDescripcionMeta(this.value, '<?=$nrodetalles?>');"><?=($field_revision['Observacion3'])?></textarea></td>
		</tr>
        <?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
<input type="hidden" id="nrodetalles_tab7" value="<?=$nrodetalles?>" />
<input type="hidden" id="cantdetalles_tab7" value="<?=$nrodetalles?>" />
</form>
</div>

</body>
</html>
