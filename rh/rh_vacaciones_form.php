<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Solicitud";
	$label_submit = "Guardar";
	$field['Estado'] = "PE";
	$field['CodEmpleado'] = $_SESSION["CODEMPLEADO_ACTUAL"];
	$field['CodPersona'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomPersona'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['CodDependencia'] = $_SESSION["DEPENDENCIA_ACTUAL"];
	$field['CreadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomCreadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['CodTipoNom'] = $_SESSION["NOMINA_ACTUAL"];
	//	consulto dias pendientes
	$sql = "SELECT (SUM(vp.Derecho) - SUM(vp.DiasGozados) +  sum( vp.DiasInterrumpidos )) AS Pendientes
			FROM rh_vacacionperiodo vp
			WHERE vp.CodPersona = '".$field['CodPersona']."'
			and vp.CodTipoNom = '".$field['CodTipoNom']."'";

	$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_pendientes) != 0) $field_pendientes = mysql_fetch_array($query_pendientes);
	$field['NroDias'] = $field_pendientes['Pendientes'];
	##
	$field['Anio'] = "$AnioActual";
	$field['Periodo'] = "$AnioActual-$MesActual";
	$field['Fecha'] = "$AnioActual-$MesActual-$DiaActual";
	$FechaSalida = "$DiaActual-$MesActual-$AnioActual";
	$FechaTermino = getFechaFinHabiles($FechaSalida, $field['NroDias']-1);
	$FechaIncorporacion = getFechaFinHabiles($FechaSalida, $field['NroDias']);
	$field['FechaSalida'] = "$AnioActual-$MesActual-$DiaActual";
	$field['FechaTermino'] = formatFechaAMD($FechaTermino);
	$field['FechaIncorporacion'] = formatFechaAMD($FechaIncorporacion);
	$disabled_nuevo = "disabled";
	$disabled_conformar = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "conformar" || $opcion == "aprobar" || $opcion == "anular") {
	list($Anio, $CodSolicitud) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				vs.*,
				p.NomCompleto AS NomPersona,
				e.CodEmpleado,
				e.CodTipoNom,
				p1.NomCompleto AS NomCreadoPor,
				p2.NomCompleto AS NomAprobadoPor,
				p3.NomCompleto AS NomConformadoPor,
				p4.NomCompleto AS NomRevisadoPor
			FROM
				rh_vacacionsolicitud vs
				INNER JOIN mastpersonas p ON (p.CodPersona = vs.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
				LEFT JOIN mastpersonas p1 ON (p1.CodPersona = vs.CreadoPor)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = vs.AprobadoPor)
				LEFT JOIN mastpersonas p3 ON (p3.CodPersona = vs.ConformadoPor)
				LEFT JOIN mastpersonas p4 ON (p4.CodPersona = vs.RevisadoPor)
			WHERE
				vs.Anio = '".$Anio."' AND
				vs.CodSolicitud = '".$CodSolicitud."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	//	consulto dias pendientes
	$sql = "SELECT (SUM(vp.Derecho) - SUM(vp.DiasGozados)) AS Pendientes
			FROM rh_vacacionperiodo vp
			WHERE
				vp.CodPersona = '".$field['CodPersona']."' AND
				vp.CodTipoNom = '".$field['CodTipoNom']."'";
	$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_pendientes) != 0) $field_pendientes = mysql_fetch_array($query_pendientes);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Solicitud";
		$accion = "modificar";
		if ($field['Estado'] == "AP") {
			$disabled_nuevo = "disabled";
			$disabled_modificar = "disabled";
			$disabled_ver = "disabled";
			$disabled_conformar = "disabled";
			$disabled_detalles = "disabled";
			$display_modificar = "display:none;";
			$display_ver = "display:none;";
			$visible_modificar = "visibility:hidden;";
		} else {
			$disabled_modificar = "disabled";
			//$disabled_conformar = "disabled";
			$display_modificar = "display:none;";
			$visible_modificar = "visibility:hidden;";
		}
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Solicitud";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_conformar = "disabled";
		$disabled_detalles = "disabled";
		$display_submit = "display:none;";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "revisar") {
		$titulo = "Revisar Solicitud";
		$accion = "revisar";
		$label_submit = "Revisar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_conformar = "disabled";
		$disabled_detalles = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$visible_modificar = "visibility:hidden;";
		$field['RevisadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
		$field['NomRevisadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	}
	
	elseif ($opcion == "conformar") {
		$titulo = "Conformar Solicitud";
		$accion = "conformar";
		$label_submit = "Conformar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_detalles = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$visible_modificar = "visibility:hidden;";
		$field['ConformadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
		$field['NomConformadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
		
	}
	
	elseif ($opcion == "aprobar") {
		$titulo = "Aprobar Solicitud";
		$accion = "aprobar";
		$label_submit = "Aprobar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_conformar = "disabled";
		$disabled_detalles = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$visible_modificar = "visibility:hidden;";
		$field['AprobadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
		$field['NomAprobadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	}
	
	elseif ($opcion == "anular") {
		$titulo = "Anular Solicitud";
		$accion = "anular";
		$label_submit = "Anular";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_conformar = "disabled";
		$disabled_detalles = "disabled";
		$display_modificar = "display:none;";
		$display_ver = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Periodos Disponibles</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$origen?>" method="POST"  onkeypress="return event.keyCode!=13" onsubmit="return vacaciones(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fFechad" id="fFechad" value="<?=$fFechad?>" />
<input type="hidden" name="fFechah" id="fFechah" value="<?=$fFechah?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" id="Anio" value="<?=$field['Anio']?>" />

<div id="tab1" style="display:block;">
<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Empleado</td>
    </tr>
    <tr>
		<td class="tagForm">* Empleado:</td>
		<td class="gallery clearfix" colspan="3">
            <input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:50px;" value="<?=$field['CodEmpleado']?>" readonly="readonly" />
			<input type="text" id="NomPersona" style="width:230px;" value="<?=$field['NomPersona']?>" readonly="readonly" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodEmpleado&nom=NomPersona&campo3=CodPersona&ventana=selListadoVacacionPeriodo&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" id="btPersona" style=" <?=$visible_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td colspan="3">
			<select id="CodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'CodDependencia', true);" <?=$disabled_modificar?>>
				<?=getOrganismos($field['CodOrganismo'], 1)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td colspan="2">
			<select id="CodDependencia" style="width:300px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'CodCentroCosto', true);" <?=$disabled_modificar?>>
				<?=getDependencias($field['CodDependencia'], $field['CodOrganismo'], 1);?>
			</select>
		</td>
        <td>
        	<strong>Total Pendientes:</strong>
            <span id="divTotalPendientes"><?=number_format($field_pendientes['Pendientes'])?></span>
        </td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n de la Solicitud</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">Nro. Solicitud:</td>
		<td><input type="text" id="CodSolicitud" value="<?=$field['CodSolicitud']?>" style="width:100px;" class="codigo" disabled="disabled" /></td>
		<td class="tagForm" width="150">Nro. Otorgamiento:</td>
		<td width="300"><input type="text" id="NroOtorgamiento" value="<?=$field['NroOtorgamiento']?>" style="width:100px;" class="codigo" disabled="disabled" /></td>
	</tr>
    <tr>
		<td class="tagForm">Documento:</td>
		<td><input type="text" id="Documento" maxlength="50" style="width:250px;" value="<?=$field['Documento']?>" <?=$disabled_conformar?> /></td>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:100px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-VACACIONES", $field['Estado']))?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Tipo:</td>
        <td>
            <select id="Tipo" style="width:106px;" <?=$disabled_modificar?>>
                <?=loadSelectValores("TIPO-VACACIONES", $field['Tipo'], 0)?>
            </select>
        </td>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="Periodo" value="<?=$field['Periodo']?>" style="width:60px;" class="disabled" disabled="disabled" /></td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha de Salida:</td>
		<td><input type="text" id="FechaSalida" value="<?=formatFechaDMA($field['FechaSalida'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" onChange="obtenerFechaTerminoVacacion(); vacaciones_periodos_insertar()" <?=$disabled_ver?> /></td>
		<td class="tagForm">Fecha:</td>
		<td><input type="text" id="Fecha" value="<?=formatFechaDMA($field['Fecha'])?>" style="width:60px;" class="disabled" disabled="disabled" /></td>
    </tr>
    <tr>
		<td class="tagForm">* Dias:</td>
		<td>
        	<input type="text" id="NroDias" maxlength="4" style="width:60px; text-align:right;" value="<?=number_format($field['NroDias'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this); obtenerFechaTerminoVacacion(); vacaciones_periodos_insertar();" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Creado Por:</td>
        <td>
            <input type="hidden" id="CreadoPor" value="<?=$field['CreadoPor']?>" />
            <input type="text" id="NomCreadoPor" value="<?=($field['NomCreadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
    <tr>
		<td class="tagForm">* F.T&eacute;rmino:</td>
		<td><input type="text" id="FechaTermino" value="<?=formatFechaDMA($field['FechaTermino'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
        <td class="tagForm">Revisado Por:</td>
        <td>
            <input type="hidden" id="RevisadoPor" value="<?=$field['RevisadoPor']?>" />
            <input type="text" id="NomRevisadoPor" value="<?=($field['NomRevisadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
    <tr>
		<td class="tagForm">* F.Incorporaci&oacute;n:</td>
		<td><input type="text" id="FechaIncorporacion" value="<?=formatFechaDMA($field['FechaIncorporacion'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
        <td class="tagForm">Conformado:</td>
        <td>
            <input type="hidden" id="ConformadoPor" value="<?=$field['ConformadoPor']?>" />
            <input type="text" id="NomConformadoPor" value="<?=($field['NomConformadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
    <tr>
		<td colspan="2"></td>
        <td class="tagForm">Aprobado:</td>
        <td>
            <input type="hidden" id="AprobadoPor" value="<?=$field['AprobadoPor']?>" />
            <input type="text" id="NomAprobadoPor" value="<?=($field['NomAprobadoPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
	<tr>
		<td class="tagForm">Justificaci&oacute;n:</td>
		<td colspan="3"><textarea id="Motivo" style="width:95%; height:45px;" <?=$disabled_ver?>><?=($field['Motivo'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3"><textarea id="Observaciones" style="width:95%; height:45px;" <?=$disabled_conformar?>><?=($field['Observaciones'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" class="disabled" value="<?=$field_obligacion['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field_obligacion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" /> | 
<input type="button" value="Mostrar Periodos" onclick="abrir_listado_periodos_vacaciones('mostrar');" />
</center>
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
</div>
</form>

<div id="tab2" style="display:none;">
<center>
<form name="frm_detalles" id="frm_detalles">
<input type="hidden" id="sel_detalles" />
<div style="width:800px" class="divFormCaption">Periodos Vacacionales</div>
<table width="800" class="tblBotones">
    <tr>
        <td align="right" class="gallery clearfix">
            <a id="a_detalles" href="../lib/listas/listado_periodos_vacaciones.php?filtrar=default&iframe=true&width=800&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" class="btLista" id="bti_detalles" value="Insertar" onclick="abrir_listado_periodos_vacaciones();" <?=$disabled_detalles?> style="display:none;" />
            <input type="button" class="btLista" id="btb_detalles" value="Borrar" onclick="quitarLineaPeriodoVacaciones(this, 'detalles');" <?=$disabled_detalles?> style="display:none;" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:800px; height:400px;">
<table width="1200" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
        <th scope="col" width="25">Uti.</th>
        <th scope="col">Periodo</th>
        <th scope="col" width="75">Dias</th>
        <th scope="col" width="100">Fecha Inicio</th>
        <th scope="col" width="100">Fecha Fin</th>
        <th scope="col" width="75">Dias x Derecho</th>
        <th scope="col" width="75">Usados</th>
        <th scope="col" width="75">Pendientes</th>
        <th scope="col" width="400">Observaciones</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?php
	//	detalles
	$sql = "SELECT
				vsd.*,
				vp.Anio As AnioPeriodo
			FROM
				rh_vacacionsolicituddetalle vsd
				INNER JOIN rh_vacacionperiodo vp ON (vp.CodPersona = vsd.CodPersona AND
													 vp.NroPeriodo = vsd.NroPeriodo)
			WHERE
				vsd.Anio = '".$Anio."' AND
				vsd.CodSolicitud = '".$CodSolicitud."' AND
				vp.CodTipoNom = '".$field['CodTipoNom']."'
			ORDER BY Secuencia";
	$query_detalles = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
	while($field_detalles = mysql_fetch_array($query_detalles)) {
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="<?=$field_detalles['NroPeriodo']?>">
            <th>
               <input type="text" name="NroPeriodo" id="NroPeriodo_<?=$i?>" class="cell2" style="text-align:center;" value="<?=$field_detalles['NroPeriodo']?>" readonly />
            </th>
            <td align="center">
               <input type="checkbox" name="FlagUtlizarPeriodo" checked="checked" disabled="disabled" />
            </td>
            <td align="center"><?=$field_detalles['AnioPeriodo']?> - <?=$field_detalles['AnioPeriodo']+1?></td>
            <td>
               <input type="text" name="NroDias" id="NroDias_<?=$i?>" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['NroDias'], 2, ',', '.')?>" onchange="obtenerFechaTerminoVacacionDetalle('<?=$i?>');" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" <?=$disabled_detalles?> disabled="disabled" />
            </td>
            <td>
               <input type="text" name="FechaInicio" id="FechaInicio_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" onkeyup="setFechaDMA(this);" value="<?=formatFechaDMA($field_detalles['FechaInicio'])?>" onchange="obtenerFechaTerminoVacacionDetalle('<?=$i?>');" <?=$disabled_detalles?> disabled="disabled" />
            </td>
            <td>
               <input type="text" name="FechaFin" id="FechaFin_<?=$i?>" maxlength="10" style="text-align:center;" class="cell datepicker" onkeyup="setFechaDMA(this);" value="<?=formatFechaDMA($field_detalles['FechaFin'])?>" <?=$disabled_detalles?> disabled="disabled" />
            </td>
            <td>
               <input type="text" name="Derecho" id="Derecho_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['DiasDerecho'], 2, ',', '.')?>" readonly />
            </td>
            <td>
               <input type="text" name="TotalUtilizados" id="TotalUtilizados_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['DiasUsados'], 2, ',', '.')?>" readonly />
            </td>
            <td>
               <input type="text" name="Pendientes" id="Pendientes_<?=$i?>" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['DiasPendientes'], 2, ',', '.')?>" readonly />
            </td>
            <td>
               <textarea name="Observaciones" class="cell" style="height:20px;" <?=$disabled_conformar?> onfocus="$(this).css('height', '60px');" onblur="$(this).css('height', '20px');"><?=$field_detalles['Observaciones']?></textarea>
               <input type="hidden" name="Secuencia" value="<?=$field_detalles['Secuencia']?>" />
            </td>
        </tr>
        <?
		$i++;
		$TotalNroDias += $field_detalles['NroDias'];
		$TotalPendientes += $field_detalles['DiasPendientes'];
	}
	?>
    </tbody>
    
    <tfoot>
    	<tr><td colspan="9">&nbsp;</td></tr>
    	<tr>
        	<td colspan="3">&nbsp;</td>
        	<td align="right">
            	<input type="text" id="TotalNroDias" class="cell2" style="text-align:right; font-weight:bold;" value="<?=number_format($TotalNroDias, 2, ',', '.')?>" />
            </td>
        	<td colspan="4">&nbsp;</td>
        	<td align="right">
            	<input type="text" id="TotalPendientes" class="cell2" style="text-align:right; font-weight:bold;" value="<?=number_format($TotalPendientes, 2, ',', '.')?>" />
            </td>
        </tr>
    </tfoot>
</table>
</div>
<input type="hidden" id="nro_detalles" value="<?=$nro_detalles?>" />
<input type="hidden" id="can_detalles" value="<?=$nro_detalles?>" />
</form>
</center>
</div>

<?php
if ($opcion == "nuevo") {
	?>
	<script type="text/javascript">
	
document.onkeypress = KeyPressed;
function KeyPressed(e)
{ return ((window.event) ? event.keyCode : e.keyCode) != 13; }
</script>
	<script type="text/javascript" language="javascript">
    $(document).ready(function() {
		vacaciones_periodos_insertar();
    });
    </script>
    <?
}
?>
