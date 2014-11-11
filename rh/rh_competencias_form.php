<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_evaluacionfactores
			WHERE Competencia = '".$registro."'";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_competencias_lista" method="POST" onsubmit="return competencias(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fTipoEvaluacion" id="fTipoEvaluacion" value="<?=$fTipoEvaluacion?>" />

<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Registro</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td>
			<input type="text" id="Competencia" style="width:50px;" class="codigo" value="<?=$field['Competencia']?>" disabled />
		</td>
		<td class="tagForm" width="125">* Descripci&oacute;n:</td>
		<td>
			<input type="text" id="Descripcion" style="width:300px;" maxlength="100" value="<?=htmlentities($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td colspan="3">
			<textarea id="Explicacion" style="width:97%; height:35px;" <?=$disabled_ver?>><?=htmlentities($field['Explicacion'])?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Grupo:</td>
		<td>
            <select id="Area" style="width:200px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGrupoCompetencia($field['Area'], "E", 0)?>
            </select>
		</td>
		<td class="tagForm">* Nivel:</td>
		<td>
            <select id="Nivel" style="width:200px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=getMiscelaneos($field['Nivel'], "NIVELCOMPE", 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Calificativo:</td>
		<td>
            <select id="Calificacion" style="width:200px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=getMiscelaneos($field['Calificacion'], "CALICOMPE", 0)?>
            </select>
		</td>
		<td class="tagForm">* Tipo:</td>
		<td>
            <select id="TipoCompetencia" style="width:200px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoCompetencia'], "TIPOCOMPE", 0)?>
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
		<td class="tagForm">&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagPlantilla" <?=chkFlag($field['FlagPlantilla'])?> <?=$disabled_ver?> /> Disponible para Plantilla
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

<table width="800" class="tblForm">    
    <tr>
    	<td>&nbsp;</td>
    	<td rowspan="3" valign="bottom">
        	<table cellpadding="0" cellspacing="0">
            	<tr style="background-color:#333;">
                    <td>
                    <?php
                    $sql = "SELECT
								MIN(gc.PuntajeMin) AS Min,
								(SELECT MAX(PuntajeMax) FROM rh_gradoscompetencia WHERE TipoEvaluacion = gc.TipoEvaluacion) AS Max
							FROM rh_gradoscompetencia gc
							WHERE TipoEvaluacion = 'E'";
					$query_puntaje = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					$field_puntaje = mysql_fetch_array($query_puntaje);
					for($i=$field_puntaje['Min']; $i<=$field_puntaje['Max']; $i++) {
						?><div class="divHeadCompetencias" style="width:30px;"><?=$i?></div><?
                    }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?
                    for($i=$field_puntaje['Min']; $i<=$field_puntaje['Max']; $i++) {
                        if ($i <= $field['ValorRequerido']) $style = "background-color:#000;"; else $style = "";
						?>
                        <div class="divBodyCompetencias vr" style="width:30px; <?=$style?>" id="vr_<?=$i?>">
                        &nbsp;
                        </div>
						<?
                    }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?
                    for($i=$field_puntaje['Min']; $i<=$field_puntaje['Max']; $i++) {
                        if ($i <= $field['ValorMinimo']) $style = "background-color:#5F160E;"; else $style = "";
                        ?>
                        <div class="divBodyCompetencias vm" style="width:30px; <?=$style?>" id="vm_<?=$i?>">
                        &nbsp;
                        </div>
                        <?
                    }
                    ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>    
    <tr>
    	<td valign="bottom" align="right" width="200">
        	* Valor Requerido: 
            <select id="ValorRequerido" style="width:40px;" onchange="competencias_sel_puntaje(this.value, 'vr');" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=loadSelectValoresGrado("E", $field['ValorRequerido'])?>
            </select>
        </td>
    </tr>  
    <tr>
    	<td valign="bottom" align="right">
        	* Valor Minimo Esperado: 
            <select id="ValorMinimo" style="width:40px;" onchange="competencias_sel_puntaje(this.value, 'vm');" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=loadSelectValoresGrado("E", $field['ValorMinimo'])?>
            </select>
        	
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

<form name="frm_grados" id="frm_grados">
<input type="hidden" name="sel_grados" id="sel_grados" />
<center>
<div style="width:800px; height:16px;" class="divFormCaption">Grados de Calificaci&oacute;n</div>
<div style="overflow:scroll; width:800px; height:390px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col">Grado</th>
        <th scope="col" width="25">Min.</th>
        <th scope="col" width="25">Max.</th>
        <th scope="col" width="25">Valor</th>
        <th scope="col" width="200">Indicador</th>
        <th scope="col" width="200">Conductas Asociadas</th>
        <th scope="col" width="60">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_grados">
    <?
	$nro_grados = 0;
	//	consulto datos generales
	$sql = "SELECT
				gc.TipoEvaluacion,
				gc.Grado,
				gc.Descripcion,
				gc.PuntajeMin,
				gc.PuntajeMax,
				fv.Valor,
				fv.Explicacion,
				fv.Explicacion2,
				fv.Estado
			FROM
				rh_gradoscompetencia gc
				LEFT JOIN rh_factorvalor fv ON (fv.TipoEvaluacion = gc.TipoEvaluacion AND
												fv.Grado = gc.Grado AND
												fv.Competencia = '".$field['Competencia']."')
			WHERE gc.TipoEvaluacion = 'E'
			ORDER BY Grado";
	$query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_grados = mysql_fetch_array($query_grados)) {
		?>
		<tr class="trListaBody">
			<th align="center">
				<?=++$nro_grados?>
			</th>
			<td>
				<input type="hidden" name="TipoEvaluacion" value="<?=$field_grados['TipoEvaluacion']?>" />
				<input type="hidden" name="Grado" value="<?=$field_grados['Grado']?>" />
				<?=htmlentities($field_grados['Descripcion'])?>
			</td>
			<td align="center">
				<?=$field_grados['PuntajeMin']?>
			</td>
			<td align="center">
				<?=$field_grados['PuntajeMax']?>
			</td>
			<td>
				<input type="text" name="Valor" class="cell" value="<?=$field_grados['Valor']?>" <?=$disabled_ver?> />
			</td>
			<td>
				<textarea name="Explicacion" class="cell" style="height:50px;" <?=$disabled_ver?>><?=$field_grados['Explicacion']?></textarea>
			</td>
			<td>
				<textarea name="Explicacion2" class="cell" style="height:50px;" <?=$disabled_ver?>><?=$field_grados['Explicacion2']?></textarea>
			</td>
			<td>
				<select name="Estado" class="cell" <?=$disabled_ver?>>
					<?=loadSelectGeneral("ESTADO", $field_grados['Estado'], 0)?>
				</select>
			</td>
		</tr>
		<?
	}

	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_grados" value="<?=$nro_grados?>" />
<input type="hidden" id="can_grados" value="<?=$nro_grados?>" />
</form>