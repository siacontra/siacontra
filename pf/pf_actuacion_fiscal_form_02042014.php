<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Planificaci&oacute;n";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$NomOrganismo = $_SESSION["NOMBRE_ORGANISMO_ACTUAL"];
	if ($_SESSION["CONTROL_FISCAL"] == "S") {
		$CodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
		$NomDependencia = $_SESSION["NOMBRE_DEPENDENCIA_ACTUAL"];
		$CodCentroCosto = $_SESSION["CCOSTO_ACTUAL"];
		$NomCentroCosto = $_SESSION["NOMBRE_CCOSTO_ACTUAL"];
	}
	$Estado = "PR";
	$FechaRegistro = date("Y-m-d");
	$FechaInicio = date("Y-m-d");
	$FechaInicioReal = date("Y-m-d");
	$PreparadoPor = $_SESSION["CODPERSONA_ACTUAL"];
	$NomPreparadoPor = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$CodProceso = "01";
	$label_submit = "Guardar";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "aprobar" || $opcion == "cerrar") {
	if ($origen == "ejecucion") {
		list($CodActuacion, $CodActividad) = split("[.]", $registro);
		$registro = $CodActuacion;
	}
	//	consulto datos generales
	$sql = "SELECT
				af.*,
				o.Organismo AS NomOrganismo,
				d.Dependencia AS NomDependencia,
				cc.Abreviatura AS NomCentroCosto,
				de.Dependencia AS NomDependenciaExterna,
				oe.Organismo AS NomOrganismoExterno,
				p1.NomCompleto AS NomPreparadoPor,
				p2.NomCompleto AS NomRevisadoPor,
				p3.NomCompleto AS NomAprobadoPor,
				(SELECT SUM(afd1.Duracion)
				 FROM
				 	pf_actuacionfiscaldetalle afd1
					INNER JOIN pf_actividades a1 ON (afd1.CodActividad = a1.CodActividad)
				 WHERE
				 	afd1.CodActuacion = af.CodActuacion AND
					a1.FlagNoAfectoPlan = 'S') AS DuracionNo
			FROM
				pf_actuacionfiscal af
				INNER JOIN mastorganismos o ON (af.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (af.CodDependencia = d.CodDependencia)
				INNER JOIN ac_mastcentrocosto cc ON (af.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN pf_organismosexternos oe ON (af.CodOrganismoExterno = oe.CodOrganismo)
				LEFT JOIN pf_dependenciasexternas de ON (af.CodDependenciaExterna = de.CodDependencia)
				INNER JOIN mastpersonas p1 ON (af.PreparadoPor = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (af.RevisadoPor = p2.CodPersona)
				LEFT JOIN mastpersonas p3 ON (af.AprobadoPor = p3.CodPersona)
			WHERE af.CodActuacion = '".$registro."'";
	$query_mast = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_mast)) $field_mast = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Planificaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Planificaci&oacute;n";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "revisar") {
		$accion = "revisar";
		$disabled_ver = "disabled";
		$titulo = "Revisar Planificaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Revisar";
	}
	
	elseif ($opcion == "aprobar") {
		$accion = "aprobar";
		$disabled_ver = "disabled";
		$titulo = "Aprobar Planificaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Aprobar";
	}
	
	elseif ($opcion == "cerrar") {
		$accion = "cerrar";
		$disabled_ver = "disabled";
		$titulo = "Cerrar Planificaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_modificar = "display:none;";
		$disabled_modificar = "disabled";
		$label_submit = "Cerrar";
	}
	
	$CodProceso = $field_mast['CodProceso'];
	$CodOrganismo = $field_mast["CodOrganismo"];
	$CodDependencia = $field_mast["CodDependencia"];
	$NomOrganismo = $field_mast["NomOrganismo"];
	$NomDependencia = $field_mast["NomDependencia"];
	$CodCentroCosto = $field_mast["CodCentroCosto"];
	$NomCentroCosto = $field_mast["NomCentroCosto"];
	$Estado = $field_mast['Estado'];
	$FechaRegistro = $field_mast['FechaRegistro'];
	$FechaInicio = $field_mast['FechaInicio'];
	$FechaInicioReal = $field_mast['FechaInicioReal'];
	$PreparadoPor = $field_mast['PreparadoPor'];
	$NomPreparadoPor = $field_mast['NomPreparadoPor'];
	$DuracionTotal = $field_mast['Duracion'] + $field_mast['Prorroga'] + $field_mast['DuracionNo'] - $field_mast['DiasAdelanto'];
}
//	------------------------------------
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	<?
	if ($opcion == "nuevo") {
		?>
		setListaActividades();
		<?
	}
	?>
});
</script>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 4);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 4);">Auditores</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 4);">Actividades</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 4);">Pr&oacute;rrogas</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$action?>" method="POST" onsubmit="return actuacion_fiscal(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=$fdependencia?>" />
<input type="hidden" name="fnomorganismo" id="fnomorganismo" value="<?=$fnomorganismo?>" />
<input type="hidden" name="fnomdependencia" id="fnomdependencia" value="<?=$fnomdependencia?>" />
<input type="hidden" name="fregistrod" id="fregistrod" value="<?=$fregistrod?>" />
<input type="hidden" name="fregistroh" id="fregistroh" value="<?=$fregistroh?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
<input type="hidden" name="fnomorganismoext" id="fnomorganismoext" value="<?=$fnomorganismoext?>" />
<input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
<input type="hidden" name="fnomdependenciaext" id="fnomdependenciaext" value="<?=$fnomdependenciaext?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="fanio" id="fanio" value="<?=$fanio?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="FechaInicioReal" id="FechaInicioReal" value="<?=formatFechaDMA($FechaInicioReal)?>" />
<input type="hidden" name="FechaTermino" id="FechaTermino" value="<?=formatFechaDMA($field_mast['FechaTermino'])?>" />

<div id="tab1" style="display:block;">
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm">Actuaci&oacute;n:</td>
		<td>
        	<input type="text" id="CodActuacion" value="<?=$field_mast['CodActuacion']?>" style="width:110px;" class="codigo" disabled="disabled" />
		</td>
		<td class="tagForm">Estado:</td>
		<td>
        	<input type="hidden" id="Estado" value="<?=$Estado?>" />
        	<input type="text" value="<?=strtoupper(printValoresGeneral("ESTADO-ACTUACION", $Estado))?>" style="width:110px;" class="codigo" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">* Organismo:</td>
		<td>
            <select id="CodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia_fiscal', 'CodDependencia', true, 'CodCentroCosto');" <?=$disabled_modificar?>>
                <?=getOrganismos($CodOrganismo, 0);?>
            </select>            
		</td>
		<td class="tagForm">F.Registro:</td>
		<td>
        	<input type="text" id="FechaRegistro" value="<?=formatFechaDMA($FechaRegistro)?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">* Dependencia:</td>
		<td>
            <select id="CodDependencia" style="width:300px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'CodCentroCosto', true);" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
                <?=loadDependenciaFiscal($CodDependencia, $CodOrganismo, 0)?>
            </select>
		</td>
		<td class="tagForm">* F.Inicio:</td>
		<td>
        	<input type="text" id="FechaInicio" value="<?=formatFechaDMA($FechaInicio)?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" onchange="setFechaActividades();" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* C.Costo:</td>
		<td>
            <select id="CodCentroCosto" style="width:300px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $CodCentroCosto, $CodDependencia, 0);?>
            </select>
		</td>
		<td class="tagForm">F. T&eacute;rmino:</td>
		<td>
        	<input type="text" id="FechaTerminoReal" value="<?=formatFechaDMA($field_mast['FechaTerminoReal'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Tipo de Actuaci&oacute;n:</td>
		<td>
            <select id="CodTipoActuacion" style="width:225px;" <?=$disabled_ver?>>
                <?=loadSelect("pf_tipoactuacionfiscal", "CodTipoActuacion", "Descripcion", $field_mast['CodTipoActuacion'], 0);?>
            </select>
		</td>
		<td class="tagForm">Duraci&oacute;n Afecta:</td>
		<td>
        	<input type="text" id="Duracion" value="<?=$field_mast['Duracion']?>" style="width:25px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Proceso:</td>
		<td>
            <select id="CodProceso" style="width:225px;" <?=$disabled_ver?>>
                <?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $CodProceso, 1);?>
            </select>
		</td>
		<td class="tagForm">Duraci&oacute;n No Afecta:</td>
		<td>
        	<input type="text" id="DuracionNo" value="<?=intval($field_mast['DuracionNo'])?>" style="width:25px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">* Ente Externo:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodOrganismoExterno" value="<?=$field_mast['CodOrganismoExterno']?>" />
			<input type="text" id="NomOrganismoExterno" style="width:295px;" value="<?=$field_mast['NomOrganismoExterno']?>" disabled="disabled" />
            <a href="../lib/listas/listado_entes_externos.php?filtrar=default&cod=CodOrganismoExterno&nom=NomOrganismoExterno&cod2=CodDependenciaExterna&nom2=NomDependenciaExterna&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_modificar?>" id="btEnte">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td class="tagForm">Prorrogas:</td>
		<td>
        	<input type="text" id="Prorroga" value="<?=intval($field_mast['Prorroga'])?>" style="width:25px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="hidden" id="CodDependenciaExterna" value="<?=$field_mast['CodDependenciaExterna']?>" />
			<input type="text" id="NomDependenciaExterna" style="width:295px;" value="<?=$field_mast['NomDependenciaExterna']?>" disabled="disabled" />
		</td>
		<td class="tagForm">Duraci&oacute;n Total:</td>
		<td>
        	<input type="text" id="DuracionTotal" value="<?=intval($DuracionTotal)?>" style="width:25px;" class="codigo" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Origen de la Actuaci&oacute;n:</td>
		<td>
            <select id="Origen" style="width:300px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field_mast['Origen'], "ORIGENACT", 0);?>
            </select>
		</td>
		<td class="tagForm">F. Completada:</td>
		<td>
        	<input type="text" id="FechaCompletada" value="<?=formatFechaDMA($field_mast['FechaCompletada'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Objetivo General:</td>
		<td colspan="3">
        	<textarea id="ObjetivoGeneral" style="width:98%; height:100px;" <?=$disabled_ver?>><?=$field_mast['ObjetivoGeneral']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Alcance:</td>
		<td colspan="3">
        	<textarea id="Alcance" style="width:98%; height:50px;" <?=$disabled_ver?>><?=$field_mast['Alcance']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="Observacion" style="width:98%; height:50px;" <?=$disabled_ver?>><?=$field_mast['Observacion']?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Preparado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$PreparadoPor?>" />
        	<input type="text" id="NomPreparadoPor" value="<?=$NomPreparadoPor?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Revisado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$field_mast['RevisadoPor']?>" />
        	<input type="text" id="NomRevisadoPor" value="<?=$field_mast['NomRevisadoPor']?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Aprobado Por:</td>
		<td colspan="3">
        	<input type="hidden" value="<?=$field_mast['AprobadoPor']?>" />
        	<input type="text" id="NomAprobadoPor" value="<?=$field_mast['NomAprobadoPor']?>" style="width:295px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" value="<?=$field_mast['UltimoUsuario']?>" style="width:200px;" class="disabled" disabled="disabled" />
			<input type="text" value="<?=$field_mast['UltimaFecha']?>" style="width:150px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
</table>

<center>
<input type="submit" value="<?=$label_submit?>" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:800px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</div>
</form>

<div id="tab2" style="display:none;">
<form name="frm_auditores" id="frm_auditores">
<input type="hidden" name="sel_auditores" id="sel_auditores" />
<table width="800" class="tblBotones">
	<tr>
		<td align="right" class="gallery clearfix">
        	<a id="aInsertarAuditor" href="../lib/listas/listado_empleados.php?filtrar=default&ventana=actuacion_fiscal_auditores_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
			<input type="button" class="btLista" value="Insertar" id="btInsertarAuditor" onclick="document.getElementById('aInsertarAuditor').click();" <?=$disabled_ver?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'auditores');" <?=$disabled_ver?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:800px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="35">Coord.</th>
        <th scope="col" width="60">Empleado</th>
        <th scope="col" align="left">Nombre Completo</th>
        <th scope="col" width="350" align="left">Cargo</th>
    </tr>
    </thead>
    
    <tbody id="lista_auditores">
    <?
	$nroauditores = 0;
	if ($opcion != "nuevo") {
		$sql = "SELECT
					afa.*,
					p.NomCompleto,
					p.Ndocumento,
					e.CodEmpleado,
					pu.DescripCargo
				FROM
					pf_actuacionfiscalauditores afa
					INNER JOIN mastpersonas p ON (afa.CodPersona = p.CodPersona)
					INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
					INNER JOIN rh_puestos pu ON (e.CodCargo = pu.CodCargo)
				WHERE afa.CodActuacion = '".$registro."'";
		$query_auditores = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_auditores = mysql_fetch_array($query_auditores)) {
			$nroauditores++;
			if ($field_auditores['FlagCoordinador'] == "S") $FlagCoordinador = "checked"; else $FlagCoordinador = "";
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_auditores');" id="<?=$field_auditores['CodPersona']?>">
				<td align="center">
                    <input type="hidden" name="CodPersona" value="<?=$field_auditores['CodPersona']?>" />
                    <input type="radio" name="FlagCoordinador" value="S" <?=$FlagCoordinador?> <?=$disabled_ver?> />
                </td>
                <td align="center">
                	<?=$field_auditores['CodEmpleado']?>
                </td>
                <td>
                	<?=$field_auditores['NomCompleto']?>
                </td>
                <td>
                	<?=$field_auditores['DescripCargo']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_auditores" value="<?=$nroauditores?>" />
<input type="hidden" id="can_auditores" value="<?=$nroauditores?>" />
</form>
</div>

<div id="tab3" style="display:none;">
<form name="frm_actividades" id="frm_actividades">
<input type="hidden" name="sel_actividades" id="sel_actividades" />
<table width="800" class="tblBotones">
	<tr>
		<td align="right" class="gallery clearfix">
			<input type="button" class="btLista" value="Insertar" onclick="document.getElementById('btActividades').click();" <?=$disabled_ver?> />
            <a href="../lib/listas/listado_actividades.php?filtrar=default&CodProceso=<?=$CodProceso?>&nom=&ventana=setListaActividadesSel&iframe=true&width=850&height=525" rel="prettyPhoto[iframe4]" style="display:none;" id="btActividades">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'actividades'); setFechaActividades();" <?=$disabled_ver?> />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:800px; height:400px;">
<table width="1075" class="tblLista">
	<thead>
	<tr>
        <th scope="col" colspan="2" style="background-color:#FFF;">&nbsp;</th>
        <th scope="col" colspan="3">Planificaci&oacute;n Inicial</th>
        <th scope="col" colspan="3">Planificaci&oacute;n Real</th>
        <th scope="col" colspan="3">Ejecuci&oacute;n</th>
        <th scope="col" colspan="2" style="background-color:#FFF;">&nbsp;</th>
    </tr>
	<tr>
        <th scope="col" width="20">Est.</th>
        <th scope="col" align="left">Actividad</th>
        <th scope="col" width="30">Dias</th>
        <th scope="col" width="60">Inicio</th>
        <th scope="col" width="60">Fin</th>
        <th scope="col" width="30">Prorr.</th>
        <th scope="col" width="60">Inicio Real</th>
        <th scope="col" width="60">Fin Real</th>
        <th scope="col" width="30">Dur. Real</th>
        <th scope="col" width="60">Fecha Termino</th>
        <th scope="col" width="60">Fecha Registro</th>
        <th scope="col" width="25">Au. Ar.</th>
        <th scope="col" width="25">No Afe.</th>
    </tr>
    </thead>
    
    <tbody id="lista_actividades">
    <?
	$nroactividades = 0;
	if ($opcion != "nuevo") {
		$sql = "SELECT
					afd.*,
					a.CodFase,
					afd.Descripcion AS NomActividad,
					a.FlagNoAfectoPlan,
					a.FlagAutoArchivo,
					f.Descripcion AS NomFase,
					(SELECT SUM(Prorroga)
					 FROM pf_prorroga
					 WHERE
						Estado = 'AP' AND
						CodActividad = afd.CodActividad AND
						CodActuacion = afd.CodActuacion
					) AS ProrrogaAcu
				FROM
					pf_actuacionfiscaldetalle afd
					INNER JOIN pf_actividades a ON (afd.CodActividad = a.CodActividad)
					INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
				WHERE afd.CodActuacion = '".$registro."'
				ORDER BY CodActividad";
		$query_actividades = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
			
			
		while ($field_actividades = mysql_fetch_array($query_actividades)) {
			$nroactividades++;
			
			if ($nroactividades == 1) {
				$FechaInicioReal = formatFechaDMA($field_actividades['FechaInicioReal']);
				$finicio = formatFechaDMA($field_actividades['FechaInicioReal']);
			}
			/*if ($field_actividades['Estado'] == "EJ")
				$tiempo = $field_actividades['Prorroga'] + $field_mast['Prorroga'] + $field_actividades['Duracion'];
			else*/
				$tiempo = $field_actividades['Prorroga'] + $field_actividades['Duracion'];
			$FechaTerminoReal = getFechaFinHabiles($FechaInicioReal, $tiempo);
			if ($grupo != $field_actividades['CodFase']) {
				if ($nroactividades > 1)  {
					?>
					<tr>
                        <th colspan="2">&nbsp;</th>
                        <th align="center">
                            <span style="font-weight:bold;"><?=$fase_duracion?></span>
                        </th>
                        <th colspan="2">&nbsp;</th>
                        <th align="center">
                            <span style="font-weight:bold;"><?=$fase_prorroga?></span>
                        </th>
                        <th colspan="2">&nbsp;</th>
                        <th align="center">
                            <span style="font-weight:bold;"><?=$fase_cierre?></span>
                        </th>
                    </tr>
					<?
					$fase_duracion = 0;
					$fase_prorroga = 0;
					$fase_cierre = 0;
				}
				$grupo = $field_actividades['CodFase'];
				?>
				<tr class="trListaBody2">
					<td colspan="2"><?=$field_actividades['CodFase']?> <?=$field_actividades['NomFase']?></td>
				</tr>
				<?
				
			
			}
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_actividades');" id="<?=$field_actividades['CodActividad']?>">
				<td align="center"><?=printEstadoActuacion($field_actividades['Estado'])?></td>
                <td>
                    <input type="hidden" name="CodFase" value="<?=$field_actividades['CodFase']?>" />
                    <input type="hidden" name="NomFase" value="<?=($field_actividades['NomFase'])?>" />
                    <input type="hidden" name="CodActividad" value="<?=$field_actividades['CodActividad']?>" />
                    <input type="hidden" name="Descripcion" value="<?=($field_actividades['NomActividad'])?>" />
                    <input type="hidden" name="FlagAutoArchivo" value="<?=$field_actividades['FlagAutoArchivo']?>" />
                    <input type="hidden" name="FlagNoAfectoPlan" value="<?=$field_actividades['FlagNoAfectoPlan']?>" />
                    <?=($field_actividades['NomActividad'])?>
                </td>
                <td align="center"><input type="text" name="Duracion" style="text-align:center;" value="<?=$field_actividades['Duracion']?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="setFechaActividades();" <?=$disabled_ver?> /></td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaInicio'])?>
                    <input type="hidden" name="FechaInicio" value="<?=formatFechaDMA($field_actividades['FechaInicio'])?>" />
                </td>
                <td align="center">
                    <?=formatFechaDMA($field_actividades['FechaTermino'])?>
                    <input type="hidden" name="FechaTermino" value="<?=formatFechaDMA($field_actividades['FechaTermino'])?>" />
                </td>
                <td align="center">
                    <?=$field_actividades['Prorroga']?>
                    <input type="hidden" name="Prorroga" value="<?=$field_actividades['Prorroga']?>" />
                </td>
                <td align="center">
                    <?=$FechaInicioReal?>
                    <input type="hidden" name="FechaInicioReal" value="<?=$FechaInicioReal?>" />
                </td>
                <td align="center">
                    <?=$FechaTerminoReal?>
                    <input type="hidden" name="FechaTerminoReal" value="<?=$FechaTerminoReal?>" />
                </td>
                <td align="center">
                    <?=$field_actividades['DiasCierre']?>
                </td>
                <td>
                    <?=formatFechaDMA($field_actividades['FechaTerminoCierre'])?>
                </td>
                <td>
                    <?=formatFechaDMA($field_actividades['FechaRegistroCierre'])?>
                </td>
                <td align="center"><?=printFlag($field_actividades['FlagAutoArchivo'])?></td>
                <td align="center"><?=printFlag($field_actividades['FlagNoAfectoPlan'])?></td>
			</tr>
			<?
			$FechaInicioReal = getFechaFinHabiles($FechaTerminoReal, 2);
			if ($field_actividades['FlagNoAfectoPlan'] == "N") {
				$total_duracion += $field_actividades['Duracion'];
				$fase_duracion += $field_actividades['Duracion'];
				$total_prorroga += $field_actividades['Prorroga'];
				$fase_prorroga += $field_actividades['Prorroga'];
				$total_cierre += $field_actividades['DiasCierre'];
				$fase_cierre += $field_actividades['DiasCierre'];
			}
		}
		?>
    	<tr>
        	<th colspan="2">&nbsp;</th>
        	<th align="center">
            	<span style="font-weight:bold;"><?=$fase_duracion?></span>
            </th>
        	<th colspan="2">&nbsp;</th>
        	<th align="center">
            	<span style="font-weight:bold;"><?=$fase_prorroga?></span>
            </th>
        	<th colspan="2">&nbsp;</th>
        	<th align="center">
            	<span style="font-weight:bold;"><?=$fase_cierre?></span>
            </th>
        </tr>
	<?
	}
	if ($field_mast['DiasAdelanto'] > 0) $duracion_detalle = "$total_duracion (-$field_mast[DiasAdelanto])";
	else $duracion_detalle = $total_duracion;
	?>
    </tbody>
    
    <tfoot>
    	<tr>
        	<td colspan="13">&nbsp;</td>
        </tr>
    	<tr>
        	<td colspan="2">&nbsp;</td>
        	<td align="center">
            	<span id="total_duracion" style="font-weight:bold;"><?=$duracion_detalle?></span>
            </td>
        	<td colspan="2">&nbsp;</td>
        	<td align="center">
            	<span id="total_prorroga" style="font-weight:bold;"><?=$total_prorroga?></span>
            </td>
        	<td colspan="2">&nbsp;</td>
        	<td align="center">
            	<span id="total_cierre" style="font-weight:bold;"><?=$total_cierre?></span>
            </td>
        </tr>
    </tfoot>
</table>
</div>
</center>
<input type="hidden" id="nro_actividades" value="<?=$nroactividades?>" />
<input type="hidden" id="can_actividades" value="<?=$nroactividades?>" />
</form>
<table align="center" width="800">
	<tr>
    	<td width="100" valign="middle">
        	<strong>Leyenda -></strong>
        </td>
    	<td>
        	<i>Pendientes: </i>
            <img src="../imagenes/circle_red.png" width="16" height="16" align="absmiddle" />
            &nbsp; &nbsp; 
        	<i>Ejecutadas: </i>
            <img src="../imagenes/checked.png" width="16" height="16" align="absmiddle" />
            &nbsp; &nbsp; 
        	<i>En Ejecuci&oacute;n: </i>
            <img src="../imagenes/circle_green.png" width="16" height="16" align="absmiddle" />
        </td>
    </tr>
</table>
</div>

<div id="tab4" style="display:none;">
<center>
<div style="overflow:scroll; width:800px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="400">Actividad</th>
        <th scope="col" align="left">Motivo</th>
        <th scope="col" width="25">Dias</th>
    </tr>
    </thead>
    
    <tbody id="lista_prorrogas">
    <?
	$nroprorrogas = 0;
	if ($opcion != "nuevo") {
		$sql = "SELECT
					p.*,
					a.Descripcion AS NomActividad
				FROM
					pf_prorroga p
					INNER JOIN pf_actividades a ON (p.CodActividad = a.CodActividad)
				WHERE p.CodActuacion = '".$registro."'
				ORDER BY Secuencia";
		$query_prorrogas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_prorrogas = mysql_fetch_array($query_prorrogas)) {
			$nroprorrogas++;
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_prorrogas');" id="prorrogas_<?=$nroprorrogas?>">
                <td>
                	<?=$field_prorrogas['NomActividad']?>
                </td>
                <td>
                	<?=$field_prorrogas['Motivo']?>
                </td>
                <td align="center">
                	<?=$field_prorrogas['Prorroga']?>
                </td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</div>
</center>
</div>
