<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$field['TipoEvaluacion'] = "E";
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_evaluacionfactoresplantilla
			WHERE Plantilla = '".$registro."'";
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
		$disabled_competencias = "disabled";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_competencias_plantilla_lista" method="POST" onsubmit="return competencias_plantilla(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fTipoEvaluacion" id="fTipoEvaluacion" value="<?=$fTipoEvaluacion?>" />
<input type="hidden" id="FlagTipoEvaluacion" value="U" />

<table width="800" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Registro</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Plantilla:</td>
		<td>
			<input type="text" id="Plantilla" style="width:50px;" class="codigo" value="<?=$field['Plantilla']?>" disabled />
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
			<input type="text" id="Descripcion" style="width:90%;" maxlength="100" value="<?=htmlentities($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Evaluaci&oacute;n:</td>
		<td> 
            <select id="TipoEvaluacion" style="width:300px;" <?=$disabled_ver?>>
                <?=loadSelect("rh_tipoevaluacion", "TipoEvaluacion", "Descripcion", $field['TipoEvaluacion'], 1)?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
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
<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
<br />
<center>
<form name="frm_competencias" id="frm_competencias">
<input type="hidden" id="sel_competencias" />
<div style="width:800px" class="divFormCaption">Lista de Competencias</div>
<table width="800" class="tblBotones">
    <tr>
        <td align="right" class="gallery clearfix">
            <a id="a_competencias" href="../lib/listas/listado_competencias.php?filtrar=default&ventana=competencias_plantilla_insertar&seldetalle=competencias&iframe=true&width=100%&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" class="btLista" value="Insertar" onclick="document.getElementById('a_competencias').click();" <?=$disabled_competencias?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'competencias');" <?=$disabled_competencias?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:800px; height:250px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="35">Cod.</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="55">Peso</th>
        <th scope="col" width="55">Orden</th>
        <th scope="col" width="25">Pot.</th>
        <th scope="col" width="25">Com.</th>
        <th scope="col" width="25">Con.</th>
    </tr>
    </thead>
    
    <tbody id="lista_competencias">
    <?
    $sql = "SELECT
    			fvp.*,
    			ef.Descripcion AS NomCompetencia
			FROM
				rh_factorvalorplantilla fvp
				INNER JOIN rh_evaluacionfactores ef ON (ef.Competencia = fvp.Competencia)
			WHERE fvp.Plantilla = '".$field['Plantilla']."'
			ORDER BY Competencia";
    $query_competencias = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_competencias = mysql_fetch_array($query_competencias)) {	$nro_competencias++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_competencias');" id="competencias_<?=$nro_competencias?>">
            <th>
				<?=$nro_competencias?>
            </th>
            <td>
            	<input type="text" name="Competencia" class="cell" style="text-align:center;" value="<?=$field_competencias['Competencia']?>" />
            </td>
            <td>
            	<?=$field_competencias['NomCompetencia']?>
            </td>
            <td>
            	<input type="text" name="Peso" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_competencias['Peso']?>" <?=$disabled_competencias?> />
            </td>
            <td>
            	<input type="text" name="FactorParticipacion" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_competencias['FactorParticipacion']?>" <?=$disabled_competencias?> />
            </td>
            <td align="center">
            	<input type="checkbox" name="FlagPotencial" <?=chkFlag($field_competencias['FlagPotencial'])?> <?=$disabled_competencias?> />
            </td>
            <td align="center">
            	<input type="checkbox" name="FlagCompetencia" <?=chkFlag($field_competencias['FlagCompetencia'])?> <?=$disabled_competencias?> />
            </td>
            <td align="center">
            	<input type="checkbox" name="FlagConceptual" <?=chkFlag($field_competencias['FlagConceptual'])?> <?=$disabled_competencias?> />
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_competencias" value="<?=$nro_competencias?>" />
<input type="hidden" id="can_competencias" value="<?=$nro_competencias?>" />
</form>
</center>