<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Agregar Capacitaci&oacute;n";
	$label_submit = "Guardar";
	$field['Estado'] = "PE";
	$field['CodEmpleado'] = $_SESSION["CODEMPLEADO_ACTUAL"];
	$field['Solicitante'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field['NomSolicitante'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field['Anio'] = "$AnioActual";
	$field['Periodo'] = "$AnioActual-$MesActual";
	$field['CodPais'] = $_PARAMETRO["PAISDEFAULT"];
	$field['CodEstado'] = $_PARAMETRO["ESTADODEFAULT"];
	$field['CodMunicipio'] = $_PARAMETRO["MUNICIPIODEFAULT"];
	$field['CodCiudad'] = $_PARAMETRO["CIUDADDEFAULT"];
	$disabled_nuevo = "disabled";
	$display_tab4 = "display:none;";
	$display_tab5 = "display:none;";
	$display_tab6 = "display:none;";
	$display_tab7 = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "aprobar" || $opcion == "iniciar" || $opcion == "terminar") {
	list($Anio, $CodOrganismo, $Capacitacion) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				c.*,
				(c.CostoEstimado - c.MontoAsumido) AS Saldo,
				p.NomCompleto AS NomSolicitante,
				cs.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio,
				cd.CodMunicipio,
				m.CodEstado,
				e.CodPais
			FROM
				rh_capacitacion c
				INNER JOIN mastpersonas p ON (p.CodPersona = c.Solicitante)
				INNER JOIN rh_cursos cs ON (cs.CodCurso = c.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = c.CodCentroEstudio)
				INNER JOIN mastciudades cd ON (cd.CodCiudad = c.CodCiudad)
				INNER JOIN mastmunicipios m ON (m.CodMunicipio = cd.CodMunicipio)
				INNER JOIN mastestados e ON (e.CodEstado = m.CodEstado)
			WHERE
				c.Anio = '".$Anio."' AND
				c.CodOrganismo = '".$CodOrganismo."' AND
				c.Capacitacion = '".$Capacitacion."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Capacitaci&oacute;n";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		if ($field['FlagCostos'] == "S") $disabled_costos = "disabled";
		$display_modificar = "display:none;";
		$display_tab4 = "display:none;";
		$display_tab5 = "display:none;";
		$display_tab6 = "display:none;";
		$display_tab7 = "display:none;";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Capacitaci&oacute;n";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_costos = "disabled";
		$disabled_participantes = "disabled";
		$disabled_horario = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$visible_ver = "visibility:hidden;";
	}
	
	elseif ($opcion == "aprobar") {
		$titulo = "Aprobar Capacitaci&oacute;n";
		$accion = "aprobar";
		$label_submit = "Aprobar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_costos = "disabled";
		$disabled_participantes = "disabled";
		$display_modificar = "display:none;";
		$display_tab5 = "display:none;";
		$display_tab6 = "display:none;";
		$display_tab7 = "display:none;";
		$visible_ver = "visibility:hidden;";
	}
	
	elseif ($opcion == "iniciar") {
		$titulo = "Iniciar Capacitaci&oacute;n";
		$accion = "iniciar";
		$label_submit = "Iniciar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_costos = "disabled";
		$disabled_participantes = "disabled";
		$disabled_horario = "disabled";
		$dLunes = "disabled";
		$dMartes = "disabled";
		$dMiercoles = "disabled";
		$dJueves = "disabled";
		$dViernes = "disabled";
		$dSabado = "disabled";
		$dDomingo = "disabled";
		$display_modificar = "display:none;";
		$display_tab6 = "display:none;";
		$display_tab7 = "display:none;";
		$visible_ver = "visibility:hidden;";
	}
	
	elseif ($opcion == "terminar") {
		$titulo = "Terminar Capacitaci&oacute;n";
		$accion = "terminar";
		$label_submit = "Terminar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_costos = "disabled";
		$disabled_participantes = "disabled";
		$disabled_horario = "disabled";
		$disabled_gastos = "disabled";
		$dLunes = "disabled";
		$dMartes = "disabled";
		$dMiercoles = "disabled";
		$dJueves = "disabled";
		$dViernes = "disabled";
		$dSabado = "disabled";
		$dDomingo = "disabled";
		$display_modificar = "display:none;";
		$display_tab6 = "display:none;";
		$display_tab7 = "display:none;";
		$visible_ver = "visibility:hidden;";
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

<table width="950" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="mostrarTab('tab', 1, 7);">Capacitaci&oacute;n</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', 2, 7);">Fundamentaci&oacute;n</a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', 3, 7);">Participantes</a>
            </li>
            <li id="li4" onclick="currentTab('tab', this);" style=" <?=$display_tab4?>">
            	<a href="#" onclick="mostrarTab('tab', 4, 7);">Horario</a>
            </li>
            <li id="li5" onclick="currentTab('tab', this);" style=" <?=$display_tab5?>">
            	<a href="#" onclick="mostrarTab('tab', 5, 7);">Gastos</a>
            </li>
            <li id="li6" onclick="currentTab('tab', this);" style=" <?=$display_tab6?>">
            	<a href="#" onclick="mostrarTab('tab', 6, 7);">Evaluaci&oacute;n del Curso</a>
            </li>
            <li id="li7" onclick="currentTab('tab', this);" style=" <?=$display_tab7?>">
            	<a href="#" onclick="mostrarTab('tab', 7, 7);">Reporte de Evaluaci&oacute;n</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$origen?>" method="POST" onsubmit="return capacitaciones(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fFechaD" id="fFechaD" value="<?=$fFechaD?>" />
<input type="hidden" name="fFechaH" id="fFechaH" value="<?=$fFechaH?>" />
<input type="hidden" name="fCodCurso" id="fCodCurso" value="<?=$fCodCurso?>" />
<input type="hidden" name="fNomCurso" id="fNomCurso" value="<?=$fNomCurso?>" />
<input type="hidden" name="fTipoCurso" id="fTipoCurso" value="<?=$fTipoCurso?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" id="Anio" value="<?=$field['Anio']?>" />

<div id="tab1" style="display:block;">
<table width="950" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:275px;" <?=$disabled_modificar?>>
				<?=getOrganismos($field['CodOrganismo'], 1)?>
			</select>
		</td>
		<td class="tagForm" width="150">Nro. Capacitaci&oacute;n:</td>
		<td>
			<input type="text" id="Capacitacion" style="width:75px;" class="codigo" value="<?=$field['Capacitacion']?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Solicitante:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="Solicitante" value="<?=$field['Solicitante']?>" />
            <input type="hidden" id="CodEmpleado" value="<?=$field['CodEmpleado']?>" disabled />
			<input type="text" id="NomSolicitante" style="width:275px;" class="disabled" value="<?=($field['NomSolicitante'])?>" disabled />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodEmpleado&nom=NomSolicitante&campo3=Solicitante&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btSolicitante" style=" <?=$visible_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:75px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-CAPACITACION", $field['Estado']))?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Curso:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodCurso" value="<?=$field['CodCurso']?>" />
			<input type="text" id="NomCurso" style="width:275px;" class="disabled" value="<?=htmlentities($field['NomCurso'])?>" disabled />
            <a href="../lib/listas/listado_cursos.php?filtrar=default&cod=CodCurso&nom=NomCurso&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" id="btCurso" style=" <?=$visible_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Periodo:</td>
		<td>
			<input type="text" id="Periodo" style="width:75px;" class="codigo" value="<?=$field['Periodo']?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro de Capacitaci&oacute;n:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodCentroEstudio" value="<?=$field['CodCentroEstudio']?>" />
			<input type="text" id="NomCentroEstudio" style="width:275px;" class="disabled" value="<?=htmlentities($field['NomCentroEstudio'])?>" disabled />
            <a href="../lib/listas/listado_centro_estudio.php?filtrar=default&cod=CodCentroEstudio&nom=NomCentroEstudio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" id="btCentroEstudio" style=" <?=$visible_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">* Tipo de Capacitaci&oacute;n:</td>
		<td>
            <select id="TipoCurso" style="width:175px;" <?=$disabled_ver?>>
                <?=getMiscelaneos($field['TipoCurso'], "TIPOCURSO", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:175px;;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', true, 'CodMunicipio', 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $field['CodPais'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Modalidad:</td>
		<td>
            <select id="Modalidad" style="width:175px;" <?=$disabled_ver?>>
                <?=getMiscelaneos($field['Modalidad'], "MODACAPAC", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:175px;;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($field['CodEstado'], $field['CodPais'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Origen:</td>
		<td>
            <select id="TipoCapacitacion" style="width:66px;" <?=$disabled_ver?>>
                <?=loadSelectValores("TIPO-CAPACITACION", $field['TipoCapacitacion'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:175px;;" onchange="getOptionsSelect(this.value, 'ciudad', 'CodCiudad', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $field['CodMunicipio'], $field['CodEstado'], 0);?>
            </select>
		</td>
		<td class="tagForm">* Vacantes:</td>
		<td>
			<input type="text" id="Vacantes" style="width:60px;" maxlength="3" value="<?=$field['Vacantes']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CodCiudad" style="width:175px;;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $field['CodCiudad'], $field['CodMunicipio'], 0);?>
            </select>
		</td>
		<td class="tagForm">Participantes:</td>
		<td>
			<input type="text" id="Participantes" style="width:60px;" class="disabled" maxlength="10" value="<?=$field['Participantes']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Expositor:</td>
		<td>
			<input type="text" id="Expositor" style="width:275px;" maxlength="50" value="<?=htmlentities($field['Expositor'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Inicio:</td>
		<td>
        	<input type="text" id="FechaDesde" value="<?=formatFechaDMA($field['FechaDesde'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Telefono Contacto:</td>
		<td>
			<input type="text" id="TelefonoContacto" style="width:75px;" maxlength="15" value="<?=htmlentities($field['TelefonoContacto'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Fin:</td>
		<td>
        	<input type="text" id="FechaHasta" value="<?=formatFechaDMA($field['FechaHasta'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Aula:</td>
		<td>
			<input type="text" id="Aula" style="width:75px;" maxlength="5" value="<?=htmlentities($field['Aula'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Dias:</td>
		<td>
			<input type="text" id="Dias" style="width:25px;" class="disabled" maxlength="4" value="<?=$field['Dias']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td>&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagCostos" value="S" <?=chkFlag($field['FlagCostos']);?> onclick="setFlagCostos(this.checked);" <?=$disabled_ver?> />
            Capacitaci&oacute;n sin Costo
		</td>
		<td class="tagForm">Horas:</td>
		<td>
			<input type="text" id="Horas" style="width:25px;" class="disabled" maxlength="4" value="<?=$field['Horas']?>" disabled="disabled" /> 
		</td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
			<textarea id="Observaciones" style="width:92%; height:35px;" <?=$disabled_ver?>><?=$field['Observaciones']?></textarea>
		</td>
	</tr>
	<tr>
    	<td colspan="2" class="divFormCaption">Costo Estimado Total</td>
    	<td colspan="2" class="divFormCaption">Costo Real</td>
    </tr>
    <tr>
		<td class="tagForm">Costo Estimado:</td>
		<td>
			<input type="text" id="CostoEstimado" style="width:100px; text-align:right;" class=" <?=$disabled_costos?>" value="<?=number_format($field['CostoEstimado'], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" onchange="setCostoEstimadoTotal();" <?=$disabled_costos?> />
		</td>
		<td class="tagForm">Sub-Total:</td>
		<td>
			<input type="text" id="SubTotal" style="width:100px; text-align:right;" class="disabled" value="<?=number_format($field['SubTotal'], 2, ',', '.')?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Monto Asumido:</td>
		<td>
			<input type="text" id="MontoAsumido" style="width:100px; text-align:right;" class=" <?=$disabled_costos?>" value="<?=number_format($field['MontoAsumido'], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" onchange="setCostoEstimadoTotal();" <?=$disabled_costos?> />
		</td>
		<td class="tagForm">Impuestos:</td>
		<td>
			<input type="text" id="Impuestos" style="width:100px; text-align:right;" class="disabled" value="<?=number_format($field['Impuestos'], 2, ',', '.')?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Saldo:</td>
		<td>
			<input type="text" id="Saldo" style="width:100px; text-align:right;" class="disabled" value="<?=number_format($field['Saldo'], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" disabled="disabled" />
		</td>
		<td class="tagForm">Total:</td>
		<td>
			<input type="text" id="Total" style="width:100px; text-align:right;" class="disabled" value="<?=number_format($field['Total'], 2, ',', '.')?>" disabled="disabled" />
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
</div>

<div id="tab2" style="display:none;">
<table width="950" class="tblForm">
	<tr>
    	<td class="divFormCaption">Fundamentaci&oacute;n de Requerimiento (Para ser llenado por el Jefe Inmediato)</td>
    </tr>
	<tr>
		<td>
			1. ¿Cu&aacute;l es el objetivo de la capacitaci&oacute;n?.
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion1" style="width:99%; height:49px;" <?=$disabled_ver?>><?=$field['Fundamentacion1']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			2. ¿En qu&eacute; medida la capacitaci&oacute;n va en relaci&oacute;n a los objetivos del &aacute;rea y de la organizaci&oacute;n?.
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion2" style="width:99%; height:49px;" <?=$disabled_ver?>><?=$field['Fundamentacion2']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			3. Dias y horarios mas factibles para el dictado.
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion3" style="width:99%; height:49px;" <?=$disabled_ver?>><?=$field['Fundamentacion3']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			4. ¿Qu&eacute; se espera despu&eacute;s de la capacitaci&oacute;n?
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion4" style="width:99%; height:48px;" <?=$disabled_ver?>><?=$field['Fundamentacion4']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			5. ¿C&oacute;mo hace hoy su trabajo?
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion5" style="width:99%; height:48px;" <?=$disabled_ver?>><?=$field['Fundamentacion5']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			6. ¿Hay colaboradores dentro de la empresa que pueden dictar este curso?
		</td>
	</tr>
	<tr>
		<td align="center">
			<textarea id="Fundamentacion6" style="width:99%; height:48px;" <?=$disabled_ver?>><?=$field['Fundamentacion6']?></textarea>
		</td>
	</tr>
</table>
</div>
<input type="submit" style="display:none;" />
</form>

<div id="tab3" style="display:none;">
<center>
<form name="frm_participantes" id="frm_participantes">
<input type="hidden" id="sel_participantes" />
<div style="width:950px" class="divFormCaption">Lista de Participantes</div>
<table width="950" class="tblBotones">
    <tr>
        <td align="right" class="gallery clearfix">
            <a id="a_participantes" href="../lib/listas/listado_empleados.php?filtrar=default&ventana=insertar_linea_participantes&detalle=participantes&iframe=true&width=100%&height=515" rel="prettyPhoto[iframe4]" style="display:none;"></a>
            <input type="button" class="btLista" value="Insertar" onclick="document.getElementById('a_participantes').click();" <?=$disabled_participantes?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'participantes');" <?=$disabled_participantes?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:445px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="35">Cod.</th>
        <th scope="col" align="left">Nombre Completo</th>
        <th scope="col" width="55">Total Dias</th>
        <th scope="col" width="55">Total Horas</th>
        <th scope="col" width="55">Dias Asistidos</th>
        <th scope="col" width="25">Apr.</th>
        <th scope="col" width="55">Nota</th>
        <th scope="col" width="55">Importe Gastos</th>
    </tr>
    </thead>
    
    <tbody id="lista_participantes">
    <?
    $sql = "SELECT
				ce.*,
				p.NomCompleto,
				e.CodEmpleado
			FROM
				rh_capacitacion_empleados ce
				INNER JOIN mastpersonas p ON (p.CodPersona = ce.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			WHERE
				ce.Anio = '".$field['Anio']."' AND
				ce.CodOrganismo = '".$field['CodOrganismo']."' AND
				ce.Capacitacion = '".$field['Capacitacion']."'
			ORDER BY Secuencia";
    $query_participantes = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_participantes = mysql_fetch_array($query_participantes)) {	$nro_participantes++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_participantes');" id="participantes_<?=$nro_participantes?>">
            <th>
            	<input type="hidden" name="CodPersona" value="<?=$field_participantes['CodPersona']?>" />
            	<input type="hidden" name="CodDependencia" value="<?=$field_participantes['CodDependencia']?>" />
				<?=$nro_participantes?>
            </th>
            <td align="center">
            	<?=$field_participantes['CodEmpleado']?>
            </td>
            <td>
                <?=htmlentities($field_participantes['NomCompleto'])?>
            </td>
            <td align="center">
            	<?=$field_participantes['NroAsistencias']?>
            </td>
            <td align="center">
            	<?=$field_participantes['HoraAsistencias']?>
            </td>
            <td align="center">
            	<?=$field_participantes['DiasAsistidos']?>
            </td>
            <td align="center">
            	<?=printFlag($field_participantes['FlagAprobado'])?>
            </td>
            <td align="center">
            	<?=$field_participantes['Nota']?>
            </td>
            <td align="right">
            	<?=number_format($field_participantes['ImporteGastos'], 2, ',', '.')?>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_participantes" value="<?=$nro_participantes?>" />
<input type="hidden" id="can_participantes" value="<?=$nro_participantes?>" />
</form>
</center>
</div>

<div id="tab4" style="display:none;">
<center>
<form name="frm_horario" id="frm_horario">
<input type="hidden" id="sel_horario" />
<div style="width:950px" class="divFormCaption">Detalle de los Horarios por Lapsos de Fecha</div>
<table width="950" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertar(this, 'horario', true, 'accion=capacitaciones_horario_insertar');" <?=$disabled_horario?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'horario');" <?=$disabled_horario?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:445px;">
<table width="100%" class="tblLista">
    <tbody id="lista_horario">
    <?
    $sql = "SELECT *
			FROM rh_capacitacion_hora
			WHERE
				Anio = '".$field['Anio']."' AND
				CodOrganismo = '".$field['CodOrganismo']."' AND
				Capacitacion = '".$field['Capacitacion']."'
			ORDER BY Secuencia
			LIMIT 0, 1";
    $query_horario = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_horario = mysql_fetch_array($query_horario)) {	$nro_horario++;
		if ($opcion == "aprobar") {
			if ($field_horario['Lunes'] != "S") $dLunes = "disabled";
			if ($field_horario['Martes'] != "S") $dMartes = "disabled";
			if ($field_horario['Miercoles'] != "S") $dMiercoles = "disabled";
			if ($field_horario['Jueves'] != "S") $dJueves = "disabled";
			if ($field_horario['Viernes'] != "S") $dViernes = "disabled";
			if ($field_horario['Sabado'] != "S") $dSabado = "disabled";
			if ($field_horario['Domingo'] != "S") $dDomingo = "disabled";
		}
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_horario');" id="horario_<?=$nro_horario?>">
            <th width="20">
                <?=$nro_horario?>
            </th>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="75" style="border:none;"><strong>Estado</strong>:</td>
                        <td style="border:none;" width="100">
                            <select name="Estado" style="width:100px;" <?=$disabled_horario?>>
                                <?=loadSelectGeneral("ESTADO", $field_horario['Estado'], 0)?>
                            </select>
                        </td>
                        <td width="50" style="border:none;" align="center">
                            L <input type="checkbox" name="Lunes" <?=chkFlag($field_horario['Lunes'])?> onclick="chkCampos(this.checked, 'HoraInicioLunes_<?=$nro_horario?>', 'HoraFinLunes_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            M <input type="checkbox" name="Martes" <?=chkFlag($field_horario['Martes'])?> onclick="chkCampos(this.checked, 'HoraInicioMartes_<?=$nro_horario?>', 'HoraFinMartes_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            M <input type="checkbox" name="Miercoles" <?=chkFlag($field_horario['Miercoles'])?> onclick="chkCampos(this.checked, 'HoraInicioMiercoles_<?=$nro_horario?>', 'HoraFinMiercoles_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            J <input type="checkbox" name="Jueves" <?=chkFlag($field_horario['Jueves'])?> onclick="chkCampos(this.checked, 'HoraInicioJueves_<?=$nro_horario?>', 'HoraFinJueves_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            V <input type="checkbox" name="Viernes" <?=chkFlag($field_horario['Viernes'])?> onclick="chkCampos(this.checked, 'HoraInicioViernes_<?=$nro_horario?>', 'HoraFinViernes_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            S <input type="checkbox" name="Sabado" <?=chkFlag($field_horario['Sabado'])?> onclick="chkCampos(this.checked, 'HoraInicioSabado_<?=$nro_horario?>', 'HoraFinSabado_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="50" style="border:none;" align="center">
                            D <input type="checkbox" name="Domingo" <?=chkFlag($field_horario['Domingo'])?> onclick="chkCampos(this.checked, 'HoraInicioDomingo_<?=$nro_horario?>', 'HoraFinDomingo_<?=$nro_horario?>');" <?=$disabled_horario?> />
                        </td>
                        <td width="150" style="border:none;">
                            <strong>Total</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none;"><strong>Desde</strong>:</td>
                        <td style="border:none;">
                            <input type="text" name="FechaDesde" id="FechaDesde_<?=$nro_horario?>" value="<?=formatFechaDMA($field_horario['FechaDesde'])?>" maxlength="10" class="datepicker" style="width:95px;" onkeyup="setFechaDMA(this);" onchange="obtenerFechaFin($('#FechaDesde_<?=$nro_horario?>'), $('#FechaHasta_<?=$nro_horario?>'), 7);" <?=$disabled_horario?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioLunes" id="HoraInicioLunes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioLunes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dLunes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioMartes" id="HoraInicioMartes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioMartes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dMartes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioMiercoles" id="HoraInicioMiercoles_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioMiercoles'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dMiercoles?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioJueves" id="HoraInicioJueves_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioJueves'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dJueves?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioViernes" id="HoraInicioViernes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioViernes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dViernes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioSabado" id="HoraInicioSabado_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioSabado'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dSabado?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraInicioDomingo" id="HoraInicioDomingo_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraInicioDomingo'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dDomingo?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="TotalDias" maxlength="5" style="width:100px;" value="<?=$field_horario['TotalDias']?>" <?=$disabled_horario?> /> <i>dias</i>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none;"><strong>Hasta</strong>:</td>
                        <td style="border:none;">
                            <input type="text" name="FechaHasta" id="FechaHasta_<?=$nro_horario?>" value="<?=formatFechaDMA($field_horario['FechaHasta'])?>" maxlength="10" class="datepicker" style="width:95px;" onkeyup="setFechaDMA(this);" <?=$disabled_horario?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinLunes" id="HoraFinLunes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinLunes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dLunes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinMartes" id="HoraFinMartes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinMartes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dMartes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinMiercoles" id="HoraFinMiercoles_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinMiercoles'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dMiercoles?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinJueves" id="HoraFinJueves_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinJueves'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dJueves?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinViernes" id="HoraFinViernes_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinViernes'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dViernes?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinSabado" id="HoraFinSabado_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinSabado'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dSabado?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="HoraFinDomingo" id="HoraFinDomingo_<?=$nro_horario?>" value="<?=formatHora12($field_horario['HoraFinDomingo'])?>" maxlength="11" style="width:50px; text-align:center;" <?=$dDomingo?> />
                        </td>
                        <td style="border:none;">
                            <input type="text" name="TotalHoras" maxlength="5" style="width:100px;" value="<?=$field_horario['TotalHoras']?>" <?=$disabled_horario?> /> <i>horas</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_horario" value="<?=$nro_horario?>" />
<input type="hidden" id="can_horario" value="<?=$nro_horario?>" />
</form>
</center>
</div>

<div id="tab5" style="display:none;">
<center>
<form name="frm_gastos" id="frm_gastos">
<input type="hidden" id="sel_gastos" />
<div style="width:950px" class="divFormCaption">Gastos</div>
<table width="950" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertar(this, 'gastos', true, 'accion=capacitaciones_gastos_insertar');" <?=$disabled_gastos?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'gastos');" <?=$disabled_gastos?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:950px; height:445px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col">Comprobante</th>
        <th scope="col" width="100">Fecha</th>
        <th scope="col" width="150">Sub-Total</th>
        <th scope="col" width="150">Impuestos</th>
        <th scope="col" width="150">Total</th>
    </tr>
    </thead>
    
    <tbody id="lista_gastos">
    <?
    $sql = "SELECT *
			FROM rh_capacitacion_gastos
			WHERE
				Anio = '".$field['Anio']."' AND
				CodOrganismo = '".$field['CodOrganismo']."' AND
				Capacitacion = '".$field['Capacitacion']."'
			GROUP BY Secuencia
			ORDER BY Secuencia";
    $query_gastos = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_gastos = mysql_fetch_array($query_gastos)) {	$nro_gastos++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_gastos');" id="gastos_<?=$nro_gastos?>">
            <th width="20">
                <?=$nro_gastos?>
            </th>
            <td>
            	<input type="text" name="Numero" class="cell" maxlength="15" value="<?=$field_gastos['Numero']?>" <?=$disabled_gastos?> />
            </td>
            <td>
            	<input type="text" name="Fecha" class="cell datepicker" style="text-align:center;" maxlength="10" value="<?=formatFechaDMA($field_gastos['Fecha'])?>" onkeyup="setFechaDMA(this);" <?=$disabled_gastos?> />
            </td>
            <td>
            	<input type="text" name="SubTotal" id="SubTotal_<?=$nro_gastos?>" class="cell" style="text-align:right;" value="<?=number_format($field_gastos['SubTotal'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="capacitaciones_gastos_total('<?=$nro_gastos?>');" <?=$disabled_gastos?> />
            </td>
            <td>
            	<input type="text" name="Impuestos" id="Impuestos_<?=$nro_gastos?>" class="cell" style="text-align:right;" value="<?=number_format($field_gastos['Impuestos'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="capacitaciones_gastos_total('<?=$nro_gastos?>');" <?=$disabled_gastos?> />
            </td>
            <td>
            	<input type="text" name="Total" id="Total_<?=$nro_gastos?>" class="cell2" style="text-align:right;" value="<?=number_format($field_gastos['Total'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" readonly="readonly" />
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_gastos" value="<?=$nro_gastos?>" />
<input type="hidden" id="can_gastos" value="<?=$nro_gastos?>" />
</form>
</center>
</div>

<div id="tab6" style="display:none;">
</div>

<div id="tab7" style="display:none;">
</div>

<center>
<input type="button" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" onclick="capacitaciones(document.getElementById('frmentrada'), '<?=$accion?>');" />
<input type="button" value="Cancelar" style="width:75px;" onclick="document.getElementById('frmentrada').submit();" />
</center>
<div style="width:950px" class="divMsj">Campos Obligatorios *</div>
