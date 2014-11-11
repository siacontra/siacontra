<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "PR";
	$field['ProcesadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
	$field['FechaProcesado'] = $Ahora;
	$field['NomProcesadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
	##
	$titulo = "Procesar Pensi&oacute;n";
	$accion = "nuevo";
	$label_submit = "Procesar";
	$disabled_nuevo = "disabled";
	$disabled_conformado = "disabled";
	$disabled_aprobado = "disabled";
	$display_cumple = "display:none;";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "conformar" || $opcion == "aprobar" || $opcion == "anular") {
	//	consulto datos generales
	$sql = "SELECT
				pp.*,
				p1.NomCompleto AS NomPersona,
				p1.Ndocumento,
				p1.Sexo,
				p1.Fnacimiento,
				e.CodEmpleado,
				e.CodTipoNom,
				e.CodTipoTrabajador,
				e.Estado AS SitTra,
				e.CodMotivoCes,
				e.Fegreso,
				e.ObsCese,
				p2.NomCompleto AS NomProcesadoPor,
				p3.NomCompleto AS NomConformadoPor,
				p4.NomCompleto AS NomAprobadoPor
			FROM
				rh_proceso_pension pp
				INNER JOIN mastpersonas p1 ON (p1.CodPersona = pp.CodPersona)
				INNER JOIN mastempleado e ON (e.CodPersona = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (p2.CodPersona = pp.ProcesadoPor)
				LEFT JOIN mastpersonas p3 ON (p3.CodPersona = pp.ConformadoPor)
				LEFT JOIN mastpersonas p4 ON (p4.CodPersona = pp.AprobadoPor)
			WHERE pp.CodProceso = '".$sel_registros."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$disabled_conformado = "disabled";
		$disabled_aprobado = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "conformar") {
		$field['ConformadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
		$field['FechaConformado'] = $Ahora;
		$field['NomConformadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
		##	
		$titulo = "Conformar Pensi&oacute;n";
		$accion = "conformar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_aprobado = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Conformar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "aprobar") {
		$field['AprobadoPor'] = $_SESSION['CODPERSONA_ACTUAL'];
		$field['FechaAprobado'] = $Ahora;
		$field['NomAprobadoPor'] = $_SESSION['NOMBRE_USUARIO_ACTUAL'];
		$field['SitTra'] = "I";
		##	
		$titulo = "Aprobar Pensi&oacute;n";
		$accion = "aprobar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_conformado = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Aprobar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_procesado = "disabled";
		$disabled_conformado = "disabled";
		$disabled_aprobado = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	$FlagCumple = "S";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Relaci&oacute;n Laboral</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_pensiones_invalidez_lista" method="POST" enctype="multipart/form-data" autocomplete="off" onsubmit="return pensiones_invalidez(this, '<?=$accion?>');">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" id="FlagCumple" value="<?=$FlagCumple?>" />
<input type="hidden" id="AniosServicio" value="<?=$field['AniosServicio']?>" />
<input type="hidden" id="CodProceso" value="<?=$field['CodProceso']?>" />
<input type="hidden" id="TipoPension" value="I" />
<input type="hidden" id="TipoProceso" value="P" />

<div id="tab1" style="display:block;">
<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Empleado</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
        	<input type="text" id="CodEmpleado" value="<?=$field['CodEmpleado']?>" style="width:40px;" disabled="disabled" />
			<input type="text" id="NomPersona" value="<?=htmlentities($field['NomPersona'])?>" style="width:245px;" disabled="disabled" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=pensiones_invalidez_empleados_sel&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm" width="125">Nro. Documento:</td>
		<td>
            <input type="text" id="Ndocumento" style="width:94px;" value="<?=$field['Ndocumento']?>" disabled="disabled" />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Sexo:</td>
		<td>
			<select id="Sexo" style="width:100px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelectGeneral("SEXO", $field['Sexo'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field['CodDependencia'], 0)?>
			</select>
		</td>
		<td class="tagForm">Fecha de Nacimiento:</td>
		<td>
            <input type="text" id="Fnacimiento" style="width:60px;" value="<?=formatFechaDMA($field['Fnacimiento'])?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Cargo:</td>
		<td>
			<select id="CodCargo" style="width:300px;" disabled="disabled">
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_puestos", "CodCargo", "DescripCargo", $field['CodCargo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Edad:</td>
		<td>
            <input type="text" id="Edad" style="width:20px;" value="<?=$field['Edad']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Sueldo Actual:</td>
		<td>
            <input type="text" id="UltimoSueldo" style="width:100px; text-align:right;" value="<?=number_format($field['UltimoSueldo'], 2, ',', '.')?>" disabled="disabled" />
		</td>
		<td class="tagForm">Fecha de Ingreso:</td>
		<td>
            <input type="text" id="Fingreso" style="width:60px;" value="<?=formatFechaDMA($field['Fingreso'])?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
        <td colspan="4">
        	<br />
            <!-- Highlight / Error-->
            <center>
            <div class="ui-widget" id="nocumple" style="display:none;">
                <div class="ui-state-error ui-corner-all" style="width:708px; text-align:left;">
                    <p>
                    <span class="ui-icon ui-icon-alert" style="float: left;"></span>
                    <strong>El Empleado NO cumple con los requisitos para optar a la Pensi&oacute;n</strong>
                    </p>
                </div>
            </div>
        	<br />
            <div class="ui-widget" id="cumple" style="<?=$display_cumple?>">
                <div class="ui-state-check ui-corner-all" style="width:708px; text-align:left;">
                    <p>
                    <span class="ui-icon ui-icon-check" style="float: left;"></span>
                    <strong>El empleado cumple con los requisitos para optar a la Pensi&oacute;n</strong>
                    </p>
                </div>
            </div>
        	<br />
            </center>
        </td>
    </tr>
</table>
</div>

<div id="tab2" style="display:none;">
<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos de la Pensi&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm"># Proceso:</td>
		<td>
            <input type="text" style="width:35px;" class="codigo" value="<?=$field['CodProceso']?>" disabled="disabled" />
		</td>
		<td class="tagForm">Motivo:</td>
		<td>
			<select id="MotivoPension" style="width:115px;" <?=$disabled_ver?>>
				<?=loadSelectValores("MOTIVO-PENSION2", $field['MotivoPension'], 0)?>
			</select>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$field['Estado']?>" />
            <input type="text" style="width:94px;" class="codigo" value="<?=strtoupper(printValores("ESTADO-PENSION", $field['Estado']))?>" disabled="disabled" />
		</td>
		<td class="tagForm">Sueldo Pensi&oacute;n:</td>
		<td>
			<input type="text" id="MontoPension" style="width:110px; text-align:right;" value="<?=number_format($field['MontoPension'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Procesado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="ProcesadoPor" value="<?=$field['ProcesadoPor']?>" />
			<input type="text" id="NomProcesadoPor" value="<?=htmlentities($field['NomProcesadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm" width="125">Fecha:</td>
		<td>
            <input type="text" id="FechaProcesado" style="width:110px;" value="<?=formatDateFull($field['FechaProcesado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsProcesado" style="width:95%; height:30px;" <?=$disabled_procesado?>><?=htmlentities($field['ObsProcesado'])?></textarea>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Conformado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="ConformadoPor" value="<?=$field['ConformadoPor']?>" />
			<input type="text" id="NomConformadoPor" value="<?=htmlentities($field['NomConformadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm">Fecha:</td>
		<td>
            <input type="text" id="FechaConformado" style="width:110px;" value="<?=formatDateFull($field['FechaConformado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsConformado" style="width:95%; height:30px;" <?=$disabled_conformado?>><?=htmlentities($field['ObsConformado'])?></textarea>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Aprobado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="AprobadoPor" value="<?=$field['AprobadoPor']?>" />
			<input type="text" id="NomAprobadoPor" value="<?=htmlentities($field['NomAprobadoPor'])?>" style="width:250px;" disabled="disabled" />
        </td>
		<td class="tagForm">Fecha:</td>
		<td>
            <input type="text" id="FechaAprobado" style="width:110px;" value="<?=formatDateFull($field['FechaAprobado'], 0)?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="ObsAprobado" style="width:95%; height:30px;" <?=$disabled_aprobado?>><?=htmlentities($field['ObsAprobado'])?></textarea>
		</td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Planilla</td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de N&oacute;mina:</td>
		<td>
            <select id="CodTipoNom" style="width:200px;" <?=$disabled_aprobado?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $field['CodTipoNom'], 0);?>
            </select>
		</td>
		<td class="tagForm">Tipo de Trabajador:</td>
		<td>
            <select id="CodTipoTrabajador" style="width:200px;" <?=$disabled_aprobado?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_tipotrabajador", "CodTipoTrabajador", "TipoTrabajador", $field['CodTipoTrabajador'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Cese</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">Estado:</td>
		<td>
            <input type="radio" name="SitTra" id="SitTraA" value="A" <?=chkOpt($field['SitTra'], "A");?> disabled /> Activo
			&nbsp; &nbsp; 
            <input type="radio" name="SitTra" id="SitTraI" value="I" <?=chkOpt($field['SitTra'], "I");?> disabled /> Inactivo
		</td>
		<td class="tagForm" width="125">Motivo del Cese:</td>
		<td>
            <select id="CodMotivoCes" style="width:200px;" <?=$disabled_aprobado?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("rh_motivocese", "CodMotivoCes", "MotivoCese", $field['CodMotivoCes'], 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Fecha de Cese:</td>
		<td>
            <input type="text" id="Fegreso" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['Fegreso'])?>" <?=$disabled_aprobado?> />
		</td>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td>
            <input type="text" id="ObsCese" style="width:95%;" value="<?=$field['ObsCese']?>" <?=$disabled_aprobado?> />
		</td>
	</tr>
</table>
</div>

<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>