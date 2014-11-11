<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Evaluacion, $CodItem) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				ei.*,
				e.TipoEvaluacion
			FROM
				rh_evaluacionitems ei
				INNER JOIN rh_evaluacion e ON (e.Evaluacion = ei.Evaluacion)
			WHERE
				ei.Evaluacion = '".$Evaluacion."' AND
				ei.CodItem = '".$CodItem."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_submit = "display:none;";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_evaluacion_items_lista" method="POST" onsubmit="return evaluacion_items(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fEvaluacion" id="fEvaluacion" value="<?=$fEvaluacion?>" />

<table width="700" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Item / Pregunta</td>
    </tr>
	<tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td colspan="3">
			<input type="text" id="CodItem" style="width:50px;" class="codigo" value="<?=$field['CodItem']?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Evaluaci&oacute;n:</td>
		<td colspan="3">
            <select id="Evaluacion" style="width:300px;" onChange="appendAjax('accion=evaluacion_items_grados&Evaluacion='+this.value, $('#lista_grados'));" <?=$disabled_modificar?>>
                <option value="">&nbsp;</option>
                <?=loadSelectEvaluacion($field['Evaluacion'], 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td colspan="3">
			<textarea id="Descripcion" style="width:95%; height:35px;" <?=$disabled_ver?>><?=$field['Descripcion']?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm" width="125">* Puntaje Min.:</td>
		<td>
			<input type="text" id="PuntajeMin" style="width:60px;" maxlength="4" value="<?=htmlentities($field['PuntajeMin'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm" width="125">* Puntaje Max.:</td>
		<td>
			<input type="text" id="PuntajeMax" style="width:60px;" maxlength="4" value="<?=htmlentities($field['PuntajeMax'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A")?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" class="disabled" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="this.form.submit();" />
</center>
</form>
<br />
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
<br />

<center>
<div style="width:700px; height:16px;" class="divFormCaption">Grados de Calificaci&oacute;n</div>
<div style="overflow:scroll; width:700px; height:250px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="15">Grado</th>
        <th scope="col" align="left">Denominaci&oacute;n</th>
        <th scope="col" width="50">Puntaje Min.</th>
        <th scope="col" width="50">Puntaje Max.</th>
    </tr>
    </thead>
    
    <tbody id="lista_grados">
    <?
	$nro_grados = 0;
	if ($opcion != "nuevo") {
		//	consulto datos generales
		$sql = "SELECT
					Grado,
					Descripcion,
					PuntajeMin,
					PuntajeMax
				FROM rh_gradoscompetencia
				WHERE TipoEvaluacion = '".$field['TipoEvaluacion']."'	
				ORDER BY Grado";
		$query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_grados = mysql_fetch_array($query_grados)) {
			?>
			<tr class="trListaBody">
				<th align="center">
                	<?=++$nro_grados?>
                </th>
				<td align="center">
                	<?=$field_grados['Grado']?>
                </td>
				<td>
                	<?=htmlentities($field_grados['Descripcion'])?>
                </td>
				<td align="center">
                	<?=$field_grados['PuntajeMin']?>
                </td>
				<td align="center">
                	<?=$field_grados['PuntajeMax']?>
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
