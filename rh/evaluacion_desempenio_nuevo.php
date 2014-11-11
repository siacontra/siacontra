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
		<td class="titulo">Evaluaci&oacute;n de Desempe&ntilde;o | Nuevo Registro</td>
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
$sql="SELECT ep.Secuencia, ep.Periodo, ep.FechaInicio, ep.FechaFin, o.Organismo, MIN(sa.CodDependencia) AS CodDependencia FROM rh_evaluacionperiodo ep INNER JOIN mastorganismos o ON (ep.CodOrganismo=o.CodOrganismo) INNER JOIN seguridad_alterna sa ON (o.CodOrganismo=sa.CodOrganismo AND sa.CodAplicacion='".$_SESSION['APLICACION_ACTUAL']."' AND sa.Usuario='".$_SESSION['USUARIO_ACTUAL']."' AND sa.CodDependencia<>'') WHERE ep.CodOrganismo='".$forganismo."' AND ep.Estado='A' GROUP BY sa.CodOrganismo";
$query_ep=mysql_query($sql) or die ($sql.mysql_error());
$rows_ep=mysql_num_rows($query_ep);
if ($rows_ep!=0) $field_ep=mysql_fetch_array($query_ep);
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaInicio']); $fecha_inicio="$d-$m-$a";
list($a, $m, $d)=SPLIT( '[/.-]', $field_ep['FechaFin']); $fecha_fin="$d-$m-$a";
?>

<form id="frmentrada" name="frmentrada" action="evaluacion_desempenio.php" method="POST" onsubmit="return verificarEvaluacionDesempenioEmpleado(this, 'GUARDAR', '');">
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
<input type="hidden" name="path" id="path" value="<?=$path_blank?>" />

<div style="width:1100px" class="divFormCaption">Datos de la Evaluaci&oacute;n</div>
<table width="1100" class="tblForm">
    <tr>
        <td class="tagForm" width="100">Empleado:</td>
        <td>
			<input name="persona" type="hidden" id="persona" />
			<input name="empleado" type="text" id="empleado" size="10" readonly="readonly" />
			<input name="nomempleado" type="text" id="nomempleado" size="50" readonly="readonly" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados_evaluacion.php?forganismo=<?=$forganismo?>&fdependencia=<?=$field_ep['CodDependencia']?>&limit=0&cod=empleado&nom=nomempleado&id=persona&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" /> *
		</td>
        <td class="tagForm" width="100">Organismo:</td>
        <td colspan="3"><input name="nomorganismo" type="text" id="nomorganismo" size="63" value="<?=$field_ep['Organismo']?>" readonly="readonly" /></td>
        <td rowspan="7" width="150" align="center">
        	<img src="<?=$path_blank."/blank.png"?>" name="img_foto" width="105" height="125" id="img_foto" />
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="tagForm">Cargo:</td>
        <td><input name="nomcargo" type="text" id="nomcargo" size="65" readonly="readonly" /></td>
        <td class="tagForm">Desde:</td>
        <td><input name="fecha_desde" type="text" id="fecha_desde" size="15" value="<?=$fecha_inicio?>" readonly="readonly" /></td>
        <td class="tagForm">Hasta:</td>
        <td><input name="fecha_hasta" type="text" id="fecha_hasta" size="15" value="<?=$fecha_fin?>" readonly="readonly" /></td>
    </tr>
    <tr>
        <td class="tagForm">Comentario:</td>
        <td><textarea name="comempleado" id="comempleado" style="width:98%; height:50px;"></textarea></td>
        <td class="tagForm">Evaluaci&oacute;n:</td>
        <td colspan="3">
        	<select name="pevaluacion" id="pevaluacion" style="width:275px;">
				<?=getPeriodosEvaluacion($fpevaluacion, $forganismo, 0);?>
			</select>
        </td>
    </tr>
    <tr>
        <td class="tagForm">Jefe Inmediato:</td>
        <td>
			<input name="codevaluador" type="hidden" id="codevaluador" />
			<input name="evaluador" type="text" id="evaluador" size="10" readonly="readonly" />
			<input name="nomevaluador" type="text" id="nomevaluador" size="50" readonly="readonly" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=evaluador&nom=nomevaluador&id=codevaluador&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" />
		</td>
        <td class="tagForm">Jefe Mediato:</td>
        <td colspan="3">
			<input name="codsupervisor" type="hidden" id="codsupervisor" />
			<input name="supervisor" type="text" id="supervisor" size="10" readonly="readonly" />
			<input name="nomsupervisor" type="text" id="nomsupervisor" size="50" readonly="readonly" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_empleados.php?forganismo=<?=$forganismo?>&limit=0&fdependencia=<?=$field_ep['CodDependencia']?>&cod=supervisor&nom=nomsupervisor&id=codsupervisor&filtrar=DEFAULT', 'height=600, width=1100, left=50, top=50, resizable=yes');" />
		</td>
    </tr>
    <tr>
        <td class="tagForm">Comentario (Evaluador):</td>
        <td><textarea name="comevaluador" id="comevaluador" style="width:98%; height:50px;"></textarea></td>
        <td class="tagForm">Comentario (Evaluado):</td>
        <td colspan="3"><textarea name="comsupervisor" id="comsupervisor" style="width:98%; height:50px;"></textarea></td>
	</tr>
    <tr>
        <td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="status" value="EE" />
			<input type="text" value="En Evaluacion" disabled="disabled" />
		</td>
        <td class="tagForm">Fecha:</td>
		<td colspan="3"><input type="text" name="fecha_evaluacion" id="fecha_evaluacion" size="15" maxlength="10" />* <em>(dd-mm-yyyy)</em></td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="6">
            <input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
        </td>
    </tr>
</table>
<div style="width:1100px" class="divFormCaption">Calificaci&oacute;n</div>
<table width="1100" class="tblForm">
    <tr>
        <td class="tagForm" width="150">Competencias:</td>
		<td><input type="text" name="desempenio" id="desempenio" value="0,00" style="font-size:12px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" /></td>
        <td class="tagForm">Metas:</td>
		<td>
        	<input type="text" name="metas" id="metas" value="0,00" style="font-size:12px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" />
        	<input type="hidden" name="funciones" id="funciones" />
        </td>
        <td class="tagForm">Total:</td>
		<td><input type="text" name="total" id="total" value="0,00" style="font-size:14px; font-weight:bold; width:100px; text-align:right;" disabled="disabled" /></td>
        <td width="250"><span style="font-weight:bold;" id="escala"></span></td>
    </tr>
</table>    
<center> 
<input type="submit" value="Procesar" id="btGuardar" style="width:75px;" />
<input type="button" value="Cancelar" onclick="cargarPagina(this.form, 'evaluacion_desempenio.php?limit=<?=$limit?>');" style="width:75px;" />
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
                
    <tbody id="listaDetalles_tab1">
    	
    </tbody>
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
        	<input type="button" value="Competencia" onclick="cargarVentana(this.form, 'lista_competencias.php?accion=selCompetenciaEvaluacionDesempenio&limit=0', 'height=800, width=800, left=200, top=0, resizable=yes');" /> | 
            
        	<input type="button" value="Calificacion" onclick="selector(this.form, 'seldetalle_tab2', 'listado_calificativos.php?ficha=competencia&cod=txtcodcalificativo&nom=txtnomcalificativo&ventana=listaEvaluacionDesempeno&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=300, width=400, left=200, top=200, resizable=yes');" disabled="disabled" /> | 
            
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
                
    <tbody id="listaDetalles_tab2">
    	
    </tbody> 
</table>
</div></td></tr></table>
</td>

<td valign="top" style="height:350px; width:550px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:550px;">
<table width="100%" class="tblLista">
	<thead>
    <?php
    $sql="SELECT MIN(PuntajeMin) AS Min, MAX(PuntajeMax) AS Max FROM rh_gradoscompetencia";
	$query_puntaje=mysql_query($sql) or die ($sql.mysql_error());
	$field_puntaje=mysql_fetch_array($query_puntaje);
	?>	
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
    </thead>

    <tbody id="listaDetalles_tab2_grafico">
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
<form name="frmdetalles_tab3" id="frmdetalles_tab3">
<input type="hidden" id="seldetalle_tab3" />
<input type="hidden" id="nrodetalles_tab3" value="0" />
<input type="hidden" id="cantdetalles_tab3" value="0" />

<table width="1100" class="tblBotones" style="display:none;">
    <tr>
    	<td><input type="button" class="btLista" value="Calificacion" onclick="selector(this.form, 'seldetalle_tab3', 'listado_calificativos.php?ficha=funciones&cod=txtcodcalificativo&nom=txtnomcalificativo&ventana=lista&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=300, width=400, left=200, top=200, resizable=yes');" /></td>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalle(this.form, 'insertarEvaluacionFunciones', 'tab3');" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab3').value, 'tab3');" />
        </td>
    </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:350px; width:1100px;">
<table align="center" width="100%"><tr><td align="center"><div style="overflow:scroll; height:300px; width:1100px;">
<table width="100%" class="tblLista">
    <tr class="trListaHead">
        <th scope="col">Funci&oacute;n</th>
    </tr>
                
    <tbody id="listaDetalles_tab3">
    	
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
</div>

<div id="tab4" style="display:none;">
<div style="width:1100px" class="divFormCaption">Fortalezas y Debilidades</div>
<form name="frmdetalles_tab4" id="frmdetalles_tab4">
<input type="hidden" id="seldetalle_tab4" />
<input type="hidden" id="nrodetalles_tab4" value="0" />
<input type="hidden" id="cantdetalles_tab4" value="0" />

<table width="1100" class="tblBotones">
    <tr>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalle(this.form, 'insertarEvaluacionFortaleza', 'tab4');" disabled="disabled" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab4').value, 'tab4');" disabled="disabled" />
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
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
</div>

<div id="tab5" style="display:none;">
<div style="width:1100px" class="divFormCaption">Objetivos Y Metas</div>
<form name="frmdetalles_tab5" id="frmdetalles_tab5">
<input type="hidden" id="seldetalle_tab5" />
<input type="hidden" id="nrodetalles_tab5" value="0" />
<input type="hidden" id="cantdetalles_tab5" value="0" />

<table width="1100" class="tblBotones">
    <tr>
    	<td><input type="button" class="btLista" value="Calificacion" onclick="selector(this.form, 'seldetalle_tab5', 'listado_calificativos.php?ficha=objetivos&cod=txtcodcalificativo&nom=txtnomcalificativo&ventana=listaEvaluacionObjetivosMetas&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=300, width=400, left=200, top=200, resizable=yes');" disabled="disabled" /></td>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalleMetas(this.form, 'insertarEvaluacionObjetivosMetas', 'tab5');" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab5').value, 'tab5');" />
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
    	
    </tbody>
    
    <tfoot>
    	<tr><td colspan="8">&nbsp;</td></tr>
    	<tr><td colspan="8">&nbsp;</td></tr>
    	<tr>
        	<td colspan="6">&nbsp;</td>
        	<td align="right"><span style="font-size:11px; font-weight:bold;" id="tab5_total_peso">0,00</span></td>
        	<td align="right"><span style="font-size:11px; font-weight:bold;" id="tab5_total">0,00</span></td>
        </tr>
    </tfoot>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
</div>

<div id="tab6" style="display:none;">
<div style="width:1100px" class="divFormCaption">Necesidades de Capacitaci&oacute;n</div>
<form name="frmdetalles_tab6" id="frmdetalles_tab6">
<input type="hidden" id="seldetalle_tab6" />

<table width="1100" class="tblBotones">
    <tr>
    	<td><input type="button" class="btLista" value="Curso" onclick="selector(this.form, 'seldetalle_tab6', 'lista_cursos.php?cod=txtcodcurso&nom=txtnomcurso&ventana=listaEvaluacionNecesidad&organismo=<?=$forganismo?>&secuencia=<?=$field_ep['Secuencia']?>&periodo=<?=$field_ep['Periodo']?>&limit=0', 'height=600, width=750, left=50, top=0, resizable=yes');" disabled="disabled" /></td>
        <td align="right">
        	<input type="button" class="btLista" value="Insertar" onclick="insertarLineaDetalle(this.form, 'insertarEvaluacionNecesidad', 'tab6');" disabled="disabled" />
            <input type="button" class="btLista" value="Borrar" onclick="quitarDetalleListaTab(document.getElementById('seldetalle_tab6').value, 'tab6');" disabled="disabled" />
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
<input type="hidden" id="nrodetalles_tab7" value="0" />
<input type="hidden" id="cantdetalles_tab7" value="0" />

<table width="1100" class="tblBotones">
    <tr>
        <td align="right">&nbsp;</td>
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
    	
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
</form>
</div>
</body>
</html>
