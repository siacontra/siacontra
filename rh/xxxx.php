<?php
if ($FlagMostrar == "S") {
	list($Evaluacion, $Secuencia) = split("[.]", $sel_evaluacion);
	//	consulto evaluacion
	$sql = "SELECT Descripcion FROM rh_evaluacion WHERE Evaluacion = '".$Evaluacion."'";
	$query_evaluacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_evaluacion) != 0) $field_evaluacion = mysql_fetch_array($query_evaluacion);
	//	consulto candidato
	if ($TipoPostulante == "I") {
		$sql = "SELECT NomCompleto FROM mastpersonas WHERE CodPersona = '".$Postulante."'";
	}
	elseif ($TipoPostulante == "E") {
		$sql = "SELECT CONCAT(Nombres, ' ', Apellido1, ' ', Apellido2) AS NomCompleto FROM rh_postulantes WHERE Postulante = '".$Postulante."'";
	}
	$query_candidato = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_candidato) != 0) $field_candidato = mysql_fetch_array($query_candidato);
}
?>
<form name="frm_competencias" id="frm_competencias" method="post" action="" target="">
<input type="hidden" name="CodOrganismo" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" name="Requerimiento" id="Requerimiento" value="<?=$Requerimiento?>" />
<input type="hidden" name="TipoPostulante" id="TipoPostulante" value="<?=$TipoPostulante?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="Secuencia" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" name="Evaluacion" id="Evaluacion" value="<?=$Evaluacion?>" />
<input type="hidden" name="sel_evaluacion" id="sel_evaluacion" value="<?=$sel_evaluacion?>" />
<input type="hidden" name="FlagMostrar" id="FlagMostrar" value="<?=$FlagMostrar?>" />
<input type="hidden" name="sel_candidato" id="sel_candidato" value="<?=$sel_candidato?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />

<div style="width:100%;" class="divFormCaption">Competencias</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
        	<?php
			if ($FlagMostrar == "S" && $opcion == "asignar") {
				$onclick = "selPuntaje(this.id);";
				?>
                <input type="button" class="btLista" value="Registrar" onclick="requerimientos_registrar();" />
                <input type="button" class="btLista" value="Imprimir" />
                <?
			}
			?>
		</td>
	</tr>
</table>
<?php
if ($FlagMostrar == "S") {
	?>
    <div style="font-size:14px; font-weight:bold;"><?=$field_evaluacion['Descripcion']?></div>
    <div style="font-size:10px; font-weight:bold;"><?=$field_candidato['NomCompleto']?></div>
    <?
}
?>
<center>
<table width="100%" class="tblLista">
	<?=headCompetencias2()?>
    
    <tbody id="lista_competencias">
    <?php
	if ($FlagMostrar == "S") {
		//	consulto los grados
		$sql = "SELECT PuntajeMax, Descripcion FROM rh_gradoscompetencia ORDER BY PuntajeMax";
		$query_grados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while($field_grados = mysql_fetch_array($query_grados)) {
			$grados[] = $field_grados;
		}
		$nro_competencias = 0;
		//	consulto datos generales
		$sql = "SELECT
					ef.Competencia,
					ef.Descripcion,
					ef.TipoCompetencia,
					ef.ValorRequerido,
					ef.ValorMinimo,
					md.Descripcion AS NomTipoCompetencia,
					rc.Puntaje
				FROM
					rh_evaluacion e
					INNER JOIN rh_factorvalorplantilla fvp ON (e.Plantilla = fvp.Plantilla)
					INNER JOIN rh_evaluacionfactores ef ON (ef.Competencia = fvp.Competencia)
					LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = ef.TipoCompetencia AND
														md.CodAplicacion = 'RH' AND
														md.CodMaestro = 'TIPOCOMPE')
					LEFT JOIN rh_requerimientocomp rc ON (rc.Evaluacion = e.Evaluacion AND
														  rc.Competencia = fvp.Competencia AND
														  rc.Requerimiento = '".$Requerimiento."' AND
														  rc.CodOrganismo = '".$CodOrganismo."' AND
														  rc.TipoPostulante = '".$TipoPostulante."' AND
														  rc.Postulante = '".$Postulante."')
				WHERE e.Evaluacion = '".$Evaluacion."'
				ORDER BY NomTipoCompetencia, TipoCompetencia, Competencia";
		$query_competencias = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_competencias = mysql_fetch_array($query_competencias)) {	$i++;
			$nro_competencias++;
			if ($grupo != $field_competencias['TipoCompetencia']) {
				$grupo = $field_competencias['TipoCompetencia'];
				?>
				<tr class="trListaBody2">
					<td colspan="2"><?=($field_competencias['NomTipoCompetencia'])?></td>
				</tr>
				<?
			}
			?>
			<tr class="trListaBody5">
				<td>
					<strong><?=($field_competencias['Descripcion'])?></strong><br />
					<?
					//	imprimo valor requerido
					foreach ($grados as $grado) {
						if ($field_competencias['ValorRequerido'] >= $grado['PuntajeMax']) $style = "background-color:#000;";
						else $style = "";
						?>
						<div class="divCompetencias2" id="vr_<?=$grado['PuntajeMax']?>" style="width:30px; height:18px; <?=$style?>"></div>
						<?
					}
					?><br style="clear:both;" /><?
					//	imprimo valor minimo
					foreach ($grados as $grado) {
						if ($field_competencias['ValorMinimo'] >= $grado['PuntajeMax']) $style = "background-color:#5F160E;";
						else $style = "";
						?>
						<div class="divCompetencias2" id="vm_<?=$grado['PuntajeMax']?>" style="width:30px; height:18px; <?=$style?>"></div>
						<?
					}
					?><br style="clear:both;" /><?
					//	imprimo valor minimo
					foreach ($grados as $grado) {
						if ($field_competencias['Puntaje'] >= $grado['PuntajeMax']) {
							$style = "background-color:#09C;";
							$checked = "checked";
						}
						else {
							$style = "";
							$checked = "";
						}
						$id = "$field_competencias[Competencia]_$grado[PuntajeMax]";
						?>
						<div class="divCompetencias4 <?=$field_competencias['Competencia']?>" id="<?=$id?>" style="width:30px; height:18px; <?=$style?>" onClick="<?=$onclick?>">
							<input type="radio" name="c_<?=$field_competencias['Competencia']?>" id="c_<?=$id?>" value="<?=$grado['PuntajeMax']?>" style="display:none;" <?=$checked?> />
						</div>
						<?
					}
					?>
				</td>
			</tr>
			<?
		}
	}
	?>
    </tbody>
</table>
</center>

</form>

<script type="text/javascript" language="javascript">
function selPuntaje(id) {
	var datos = id.split("_");
	var Competencia = datos[0];
	var Puntaje = new Number(parseInt(datos[1]));
	
	$(".divCompetencias4."+Competencia).css("background-color", "transparent");
	for(var i=1; i<=Puntaje; i++) {
		if (i <= Puntaje) $("#"+Competencia+"_"+i).css("background-color", "#09C");
	}
	$("#c_"+id).attr("checked", "checked");
}

$(document).ready(function() {
	
});
</script>