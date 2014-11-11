<?php
list($CodValJur, $CodActividad) = split("[.]", $registro);
$sql = "SELECT
			vj.CodValJur,
			vj.CodOrganismoExterno,
			vj.CodDependenciaExterna,
			vj.ObjetivoGeneral,
			vjd.Secuencia,
			vjd.FechaInicioReal,
			vjd.FechaTerminoReal,
			(vjd.Duracion + vjd.Prorroga) AS DiasReal,
			a.CodActividad,
			a.Descripcion AS NomActividad,
			a.FlagAutoArchivo,
			a.FlagNoAfectoPlan,
			oe.Organismo As NomOrganismoExterno,
			de.Dependencia As NomDependenciaExterna
		FROM
			pf_valoracionjuridica vj
			INNER JOIN pf_valoracionjuridicadetalle vjd ON (vj.CodValJur = vjd.CodValJur)
			INNER JOIN pf_actividades a ON (vjd.CodActividad = a.CodActividad)
			INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
			INNER JOIN pf_organismosexternos oe ON (vj.CodOrganismoExterno = oe.CodOrganismo)
			LEFT JOIN pf_dependenciasexternas de ON (vj.CodDependenciaExterna = de.CodDependencia)
		WHERE
			vj.CodValJur = '".$CodValJur."' AND
			a.CodActividad = '".$CodActividad."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
if ($field['FlagAutoArchivo'] == "S") {
	$FlagAutoArchivo = "checked";
	$AutoArchivo = "S";
} else {
	$FlagAutoArchivo = "";
	$AutoArchivo = "N";
	$display_auto = "display:none;";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Terminar Actividades</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_valoracion_juridica_actividades" method="POST" onsubmit="return valoracion_juridica_actividades_terminar(this);">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fdependencia" id="fdependencia" value="<?=$fdependencia?>" />
<input type="hidden" name="fregistrod" id="fregistrod" value="<?=$fregistrod?>" />
<input type="hidden" name="fregistroh" id="fregistroh" value="<?=$fregistroh?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="forganismoext" id="forganismoext" value="<?=$forganismoext?>" />
<input type="hidden" name="fnomorganismoext" id="fnomorganismoext" value="<?=$fnomorganismoext?>" />
<input type="hidden" name="fdependenciaext" id="fdependenciaext" value="<?=$fdependenciaext?>" />
<input type="hidden" name="fnomdependenciaext" id="fnomdependenciaext" value="<?=$fnomdependenciaext?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="fanio" id="fanio" value="<?=$fanio?>" />
<input type="hidden" name="FlagNoAfectoPlan" id="FlagNoAfectoPlan" value="<?=$field['FlagNoAfectoPlan']?>" />
<input type="hidden" name="AutoArchivo" id="AutoArchivo" value="<?=$AutoArchivo?>" />
<table width="800" class="tblForm">
	<tr>
		<td class="tagForm" width="100">Val. Jur.:</td>
		<td>
        	<input type="hidden" id="Secuencia" value="<?=$field['Secuencia']?>" />
        	<input type="text" id="CodValJur" value="<?=$field['CodValJur']?>" style="width:110px;" class="codigo" disabled="disabled" /> - 
        	<input type="text" id="CodActividad" value="<?=$field['CodActividad']?>" style="width:90px;" class="codigo" disabled="disabled" />
		</td>
		<td class="tagForm" width="100">F.Inicio Real:</td>
		<td>
        	<input type="text" id="FechaInicioReal" value="<?=formatFechaDMA($field['FechaInicioReal'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Actividad:</td>
		<td>
        	<input type="text" id="NomActividad" value="<?=$field['NomActividad']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">F.Termino Real:</td>
		<td>
        	<input type="text" id="FechaTerminoReal" value="<?=formatFechaDMA($field['FechaTerminoReal'])?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">Ente Externo:</td>
		<td>
            <input type="hidden" id="CodOrganismoExterno" value="<?=$field['CodOrganismoExterno']?>" />
			<input type="text" id="NomOrganismoExterno" value="<?=$field['NomOrganismoExterno']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">Dias:</td>
		<td>
        	<input type="text" id="Duracion" value="<?=$field['DiasReal']?>" style="width:25px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
            <input type="hidden" id="CodDependenciaExterna" value="<?=$field['CodDependenciaExterna']?>" />
			<input type="text" id="NomDependenciaExterna" value="<?=$field['NomDependenciaExterna']?>" style="width:95%;" class="disabled" disabled="disabled" />
		</td>
		<td class="tagForm">F.Registro Cierre:</td>
		<td>
        	<input type="text" id="FechaRegistroCierre" value="<?=date("d-m-Y")?>" style="width:78px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Objetivo General:</td>
		<td colspan="3">
        	<textarea id="ObjetivoGeneral" style="width:98%; height:100px;" class="disabled" disabled="disabled"><?=$field['ObjetivoGeneral']?></textarea>
		</td>
	</tr>
	<tr style=" <?=$display_auto?>">
		<td class="tagForm">&nbsp;</td>
		<td colspan="3">
        	<input type="checkbox" id="FlagAutoArchivo" value="S" <?=$FlagAutoArchivo?> /> Auto de Archivo
		</td>
	</tr>
    
	<tr>
		<td class="tagForm">* F.Terminado:</td>
		<td>
        	<input type="text" id="FechaTerminoCierre" value="<?=formatFechaDMA($field['FechaTerminoReal'])?>" style="width:78px;" class="datepicker" onkeyup="setFechaDMA(this);" onchange="getDiasHabiles($('#FechaInicioReal').val(), this.value, document.getElementById('DiasCierre'));" />
		</td>
		<td class="tagForm">Duraci&oacute;n:</td>
		<td>
        	<input type="text" id="DiasCierre" value="<?=$field['DiasReal']?>" style="width:25px;" class="disabled" disabled="disabled" /> Dias
		</td>
	</tr>
	<tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="Observaciones" style="width:98%; height:50px;"></textarea>
		</td>
	</tr>
</table>

<center>
<input type="submit" value="Terminar Actividad" style="width:100px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="this.form.submit();" />
</center>
<br />
<div style="width:800px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<form name="frm_documentos" id="frm_documentos">
<input type="hidden" name="sel_documentos" id="sel_documentos" />
<table width="400" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'actuacion_fiscal_actividades_terminar_documento', 'documentos', true)" />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'documentos');" />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:400px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="25">#</th>
        <th scope="col">Documento</th>
        <th scope="col" width="100">Nro. Documento</th>
        <th scope="col" width="75">Fecha</th>
    </tr>
    </thead>
    
    <tbody id="lista_documentos">
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_documentos" value="<?=$nrodocumentos?>" />
<input type="hidden" id="can_documentos" value="<?=$nrodocumentos?>" />
</form>