<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Requerimiento de Personal";
	$label_submit = "Guardar";
	$field['Estado'] = "PE";
	$field['CodEmpleado'] = $_SESSION["CODEMPLEADO_ACTUAL"];
	$field['CodPersona'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomPersona'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['CodDependencia'] = $_SESSION["DEPENDENCIA_ACTUAL"];
	$field['VigenciaInicio'] = "$AnioActual-01-01";
	$field['VigenciaFin'] = "$AnioActual-12-31";
	$field['NumeroSolicitado'] = 1;
	$disabled_nuevo = "disabled";
	$disabled_desde = "disabled";
	$disabled_hasta = "disabled";
	$display_tab3 = "display:none;";
	$display_tab4 = "display:none;";
	$display_tab5 = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "aprobar" || $opcion == "asignar" || $opcion == "contratar" || $opcion == "finalizar") {
	list($CodOrganismo, $Requerimiento) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				r.*,
				DATEDIFF(NOW(), r.FechaSolicitud) AS TiempoTranscurridoAhora,
				DATEDIFF(r.FechaTermino, r.FechaSolicitud) AS TiempoTranscurridoTermino,
				p.NomCompleto AS NomPersona,
				e.CodEmpleado,
				pt.CodDesc,
				pt.DescripCargo,
				tc.FlagVencimiento
			FROM
				rh_requerimiento r
				INNER JOIN mastpersonas p ON (p.CodPersona = r.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = r.CodPersona)
				INNER JOIN rh_puestos pt ON (pt.CodCargo = r.CodCargo)
				INNER JOIN rh_tipocontrato tc ON (tc.TipoContrato = r.TipoContrato)
			WHERE
				r.CodOrganismo = '".$CodOrganismo."' AND
				r.Requerimiento = '".$Requerimiento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	if ($field['TiempoTranscurridoTermino'] == "") $field['TiempoTranscurrido'] = $field['TiempoTranscurridoAhora'];
	else $field['TiempoTranscurrido'] = $field['TiempoTranscurridoTermino'];
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Requerimiento";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		if ($field['FlagVencimiento'] != "S") $disabled_hasta = "disabled";
		$display_modificar = "display:none;";
		$display_tab3 = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Requerimiento";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_desde = "disabled";
		$disabled_hasta = "disabled";
		$disabled_evaluacion = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$display_tab3 = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "aprobar") {
		$titulo = "Aprobar Requerimiento";
		$accion = "aprobar";
		$label_submit = "Aprobar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_desde = "disabled";
		$disabled_hasta = "disabled";
		$disabled_evaluacion = "disabled";
		$display_modificar = "display:none;";
		$display_tab3 = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "asignar") {
		$titulo = "Evaluar Candidatos";
		$accion = "asignar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_desde = "disabled";
		$disabled_hasta = "disabled";
		$disabled_evaluacion = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "contratar") {
		$titulo = "Contratar Candidatos";
		$accion = "contratar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_desde = "disabled";
		$disabled_hasta = "disabled";
		$disabled_evaluacion = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "finalizar") {
		$titulo = "Finalizar Requerimiento";
		$accion = "finalizar";
		$label_submit = "Terminar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_desde = "disabled";
		$disabled_hasta = "disabled";
		$disabled_evaluacion = "disabled";
		$display_modificar = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
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

<table width="1100" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="mostrarTab('tab', 1, 5);">Requerimiento</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', 2, 5);">Evaluaci&oacute;n + Perfil</a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);" style=" <?=$display_tab3?>">
            	<a href="#" onclick="mostrarTab('tab', 3, 5);">Candidatos</a>
            </li>
            <li id="li4" onclick="currentTab('tab', this);" style=" <?=$display_tab4?>">
            	<a href="#" onclick="mostrarTab('tab', 4, 5);">Detalle Evaluaci&oacute;n</a>
            </li>
            <li id="li5" onclick="currentTab('tab', this);" style=" <?=$display_tab5?>">
            	<a href="#" onclick="mostrarTab('tab', 5, 5);">Comparativo</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$origen?>" method="POST" onsubmit="return requerimientos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fFechaSolicitudd" id="fFechaSolicitudd" value="<?=$fFechaSolicitudd?>" />
<input type="hidden" name="fFechaSolicitudh" id="fFechaSolicitudh" value="<?=$fFechaSolicitudh?>" />
<input type="hidden" name="fCodCargo" id="fCodCargo" value="<?=$fCodCargo?>" />
<input type="hidden" name="fCodDesc" id="fCodDesc" value="<?=$fCodDesc?>" />
<input type="hidden" name="fDescripCargo" id="fDescripCargo" value="<?=$fDescripCargo?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />

<div id="tab1" style="display:block;">
<table width="1100" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n del Requerimiento</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'CodDependencia', true);" <?=$disabled_modificar?>>
				<?=getOrganismos($field['CodOrganismo'], 1)?>
			</select>
		</td>
		<td class="tagForm" width="150">Nro. Requerimiento:</td>
		<td>
			<input type="text" id="Requerimiento" style="width:75px;" class="codigo" value="<?=$field['Requerimiento']?>" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:300px;" <?=$disabled_modificar?>>
				<?=getDependencias($field['CodDependencia'], $field['CodOrganismo'], 0);?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:100px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-REQUERIMIENTO", $field['Estado']))?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Empleado:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:50px;" class="disabled" value="<?=$field['CodEmpleado']?>" disabled />
			<input type="text" id="NomPersona" style="width:237px;" class="disabled" value="<?=$field['NomPersona']?>" disabled />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodEmpleado&nom=NomPersona&campo3=CodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btPersona" style=" <?=$visible_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Fecha Solicitud:</td>
		<td>
            <input type="text" id="FechaSolicitud" value="<?=formatFechaDMA($field['FechaSolicitud'])?>" maxlength="10" style="width:75px;" class="datepicker disabled" onkeyup="setFechaDMA(this);" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Cargo:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodCargo" value="<?=$field['CodCargo']?>" />
            <input type="text" id="CodDesc" style="width:50px;" class="disabled" value="<?=$field['CodDesc']?>" disabled />
			<input type="text" id="DescripCargo" style="width:237px;" class="disabled" value="<?=$field['DescripCargo']?>" disabled />
            <a href="../lib/listas/listado_cargos.php?filtrar=default&cod=CodCargo&nom=DescripCargo&campo3=CodDesc&ventana=requerimientos_cargo_selector&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe2]" id="btCargo" style=" <?=$visible_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Fecha T&eacute;rmino:</td>
		<td>
            <input type="text" id="FechaTermino" value="<?=formatFechaDMA($field['FechaTermino'])?>" maxlength="10" style="width:75px;" class="datepicker disabled" onkeyup="setFechaDMA(this);" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Motivo del Pedido:</td>
		<td>
			<select id="Motivo" style="width:225px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['Motivo'], "MOTPEDIDO", 0);?>
			</select>
        </td>
		<td class="tagForm">Tiempo Transcurrido:</td>
		<td>
			<input type="text" id="TiempoTranscurrido" style="width:75px;" class="disabled" value="<?=$field['TiempoTranscurridoTermino']?>" disabled />
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Vigencia:</td>
		<td>
        	<input type="text" id="VigenciaInicio" value="<?=formatFechaDMA($field['VigenciaInicio'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /> -
        	<input type="text" id="VigenciaFin" value="<?=formatFechaDMA($field['VigenciaFin'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
        </td>
		<td class="tagForm">Pendientes:</td>
		<td>
			<input type="text" id="NumeroPendiente" style="width:75px;" class="disabled" value="<?=$field['NumeroPendiente']?>" disabled />
		</td>
    </tr>
    <tr>
		<td class="tagForm">* Tipo de Contrato:</td>
		<td>
			<select id="TipoContrato" style="width:145px;" onchange="setTipoContrato(this.value, $('#FechaDesde'), $('#FechaHasta'));" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_tipocontrato", "TipoContrato", "Descripcion", $field['TipoContrato'], 0)?>
			</select>
        </td>
		<td class="tagForm">Vacantes:</td>
		<td>
			<input type="text" id="NumeroSolicitado" style="width:75px;" maxlength="3" value="<?=$field['NumeroSolicitado']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha del Contrato:</td>
		<td>
        	<input type="text" id="FechaDesde" value="<?=formatFechaDMA($field['FechaDesde'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_desde?> /> -
        	<input type="text" id="FechaHasta" value="<?=formatFechaDMA($field['FechaHasta'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_hasta?> />
        </td>
		<td class="tagForm">* Modalidad:</td>
		<td>
			<select id="Modalidad" style="width:80px;" <?=$disabled_ver?>>
				<?=loadSelectValores("MODALIDAD-REQUERIMIENTO", $field['Modalidad'], 0)?>
			</select>
        </td>
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
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
<div style="width:1100px" class="divMsj">Campos Obligatorios *</div>
</div>
</form>

<div id="tab2" style="display:none;">
<center>
<form name="frm_evaluacion" id="frm_evaluacion">
<input type="hidden" id="sel_evaluacion" />
<div style="width:1100px" class="divFormCaption">Evaluaciones</div>
<table width="1100" class="tblBotones">
    <tr>
        <td align="right" class="gallery clearfix">
            <a id="a_evaluacion" href="../lib/listas/listado_competencia_evaluaciones.php?filtrar=default&ventana=insertar_linea_evaluacion&detalle=evaluacion&iframe=true&width=700&height=400" rel="prettyPhoto[iframe3]" style="display:none;"></a>
            <input type="button" class="btLista" value="Insertar" id="bti_evaluacion" onclick="document.getElementById('a_evaluacion').click();" <?=$disabled_evaluacion?> />
            <input type="button" class="btLista" value="Borrar" id="btb_evaluacion" onclick="quitarLinea(this, 'evaluacion');" <?=$disabled_evaluacion?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:1100px; height:150px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" align="left">Evaluaci&oacute;n</th>
        <th scope="col" width="75">Etapa</th>
    </tr>
    </thead>
    
    <tbody id="lista_evaluacion">
    <?
    $sql = "SELECT
				re.Requerimiento,
				re.CodOrganismo,
				re.Secuencia,
				re.Evaluacion,
				re.Etapa,
				re.PlantillaEvaluacion,
				e.Descripcion
			FROM
				rh_requerimientoeval re
				INNER JOIN rh_evaluacion e ON (e.Evaluacion = re.Evaluacion)
			WHERE
				re.CodOrganismo = '".$field['CodOrganismo']."' AND
				re.Requerimiento = '".$field['Requerimiento']."'
			ORDER BY Secuencia";
    $query_evaluacion = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_evaluacion = mysql_fetch_array($query_evaluacion)) {	$nro_evaluacion++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_evaluacion');" id="evaluacion_<?=$field_evaluacion['Evaluacion']?>">
            <th>
            	<input type="hidden" name="Secuencia" value="<?=$field_evaluacion['Secuencia']?>" />
            	<input type="hidden" name="Evaluacion" value="<?=$field_evaluacion['Evaluacion']?>" />
            	<input type="hidden" name="Etapa" value="<?=$field_evaluacion['Etapa']?>" />
            	<input type="hidden" name="PlantillaEvaluacion" value="<?=$field_evaluacion['PlantillaEvaluacion']?>" />
				<?=$nro_evaluacion?>
            </th>
            <td>
                <?=$field_evaluacion['Descripcion']?>
            </td>
            <td align="center">
                <?=$field_evaluacion['Etapa']?>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_evaluacion" value="<?=$nro_evaluacion?>" />
<input type="hidden" id="can_evaluacion" value="<?=$nro_evaluacion?>" />
</form>

<div style="width:1100px" class="divFormCaption">Perfil del Puesto (Informativo)</div>
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<?=printHeadCompetencias("E", 150, 30, 1)?>
    </thead>
    
    <tbody id="lista_competencias">
    <?=printBodyCompetenciasCargo($field['CodCargo'], "E", 150, 8)?>
    </tbody>
</table>
</div>
</center>
</div>

<div id="tab3" style="display:none;">
<table align="center" width="1100" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<iframe name="postulantes" id="postulantes" src="gehen.php?anz=rh_requerimientos_postulantes_lista&CodOrganismo=<?=$CodOrganismo?>&Requerimiento=<?=$Requerimiento?>&opcion=<?=$opcion?>&Modalidad=<?=$field['Modalidad']?>&NumeroPendiente=<?=$field['NumeroPendiente']?>" style="border:solid 1px #CDCDCD; width:100%; height:260px;"></iframe>
        </td>
    	<td rowspan="2" valign="top">
        	<iframe name="competencias" id="competencias" src="gehen.php?anz=rh_requerimientos_competencias_lista&CodOrganismo=<?=$CodOrganismo?>&Requerimiento=<?=$Requerimiento?>&opcion=<?=$opcion?>&FlagMostrar=N" style="border:solid 1px #CDCDCD; width:100%; height:522px;"></iframe>
        </td>
    </tr>
	<tr>
    	<td>
        	<iframe name="evaluaciones" id="evaluaciones" src="gehen.php?anz=rh_requerimientos_evaluaciones_lista&CodOrganismo=<?=$CodOrganismo?>&Requerimiento=<?=$Requerimiento?>&opcion=<?=$opcion?>&FlagMostrar=N" style="border:solid 1px #CDCDCD; width:100%; height:260px;"></iframe>
        </td>
    </tr>
</table>
</div>



<div id="tab4" style="display:none;">
</div>

<div id="tab5" style="display:none;">

</div>

<div class="gallery clearfix">
<a href="../lib/listas/listado_postulantes.php?filtrar=default&marco=postulantes&ventana=selListadoIFrameRequerimientoPostulante&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe4]" id="a_postulante1" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
<a href="../lib/listas/listado_empleados.php?filtrar=default&marco=postulantes&ventana=selListadoIFrameRequerimientoPostulante&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe5]" id="a_empleado1" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
<a href="pagina.php?iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe6]" id="a_contratar" style=" display:none;">
    <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
</a>
</div>