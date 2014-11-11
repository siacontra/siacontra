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
<?php
if ($FlagMostrar == "S") {
?>
<table width="100%" class="tblLista">
	<thead>
	<?=printHeadCompetencias("R", 35, 15)?>
    </thead>
   
   	<tbody id="lista_competencias">
    <?php
    //	consulto los grados
	$sql = "SELECT
       			PuntajeMin,
       			PuntajeMax,
       			(PuntajeMax - PuntajeMin + 1) AS Cols,
       			Descripcion
    		FROM rh_gradoscompetencia
    		WHERE TipoEvaluacion = 'R'
    		ORDER BY PuntajeMin";
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
				md.Descripcion AS NomTipoCompetencia
			FROM
				rh_cargocompetencia cc
				INNER JOIN rh_evaluacionfactores ef ON (ef.Competencia = cc.Competencia)
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = ef.TipoCompetencia AND
													md.CodAplicacion = 'RH' AND
													md.CodMaestro = 'TIPOCOMPE')
			WHERE cc.CodCargo = '".$CodCargo."'
			ORDER BY NomTipoCompetencia, TipoCompetencia, Competencia";
			
	$sql = "SELECT
				ei.CodItem,
				ei.Descripcion,
				ei.PuntajeMin,
				ei.PuntajeMax,
				rc.Puntaje
			FROM
				rh_evaluacionitems ei
				LEFT JOIN rh_requerimientocomp rc ON (rc.Competencia = ei.CodItem AND
													  rc.CodOrganismo = '".$CodOrganismo."' AND
													  rc.Requerimiento = '".$Requerimiento."' AND
													  rc.TipoPostulante = '".$TipoPostulante."' AND
													  rc.Postulante = '".$Postulante."' AND
													  rc.Evaluacion = '".$Evaluacion."')
			WHERE ei.Evaluacion = '".$Evaluacion."'
			ORDER BY CodItem";
	$query_competencias = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_competencias = mysql_fetch_array($query_competencias)) {
		$nro_competencias++;
		$x = $field_competencias['CodItem'];
		?>
		<tr class="trListaBody">
			<td>
				<strong><?=htmlentities($field_competencias['Descripcion'])?></strong><br />
                <?
				//	imprimo valor requerido
				foreach ($grados as $grado) {
					for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
						if ($field_competencias['PuntajeMax'] >= $i) $style = "background-color:#000;"; else $style = "";
            			?><div class="divBodyCompetencias" style=" <?=$style?> width:35px; height:15px;"></div><?
        			}
				}
				?><br style="clear:both;" /><?
				//	imprimo valor minimo
				foreach ($grados as $grado) {
					for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
						if ($field_competencias['PuntajeMin'] >= $i) $style = "background-color:#5F160E;"; else $style = "";
            			?><div class="divBodyCompetencias" style=" <?=$style?> width:35px; height:15px;"></div><?
        			}
				}
				?><br style="clear:both;" /><?
				//	imprimo puntaje
				$y=0;
				foreach ($grados as $grado) {
					for ($i=$grado['PuntajeMin']; $i<=$grado['PuntajeMax']; $i++) {
						++$y;
						if ($field_competencias['Puntaje'] >= $i) $style = "background-color:#09C;"; else $style = "";
            			?>
                        <div class="divBodyCompetencias Puntaje_<?=$x?>" id="div_<?=$x?>_<?=$y?>" style=" <?=$style?> width:35px; height:15px;" onclick="selPuntaje('<?=$x?>_<?=$y?>');">
                        <input type="radio" name="Puntaje_<?=$x?>" id="c_<?=$x?>_<?=$y?>" value="<?=$y?>" style="display:none;" />
                        </div>
						<?
        			}
				}
				?>
            </td>
		</tr>
		<?
	}
    ?>
   	</tbody>
</table>
<?
}
?>
</center>

</form>

<script type="text/javascript" language="javascript">
function selPuntaje(id) {
	var datos = id.split("_");
	var x = parseInt(datos[0]);
	var y = parseInt(datos[1]);
	//	
	$(".divBodyCompetencias.Puntaje_"+x).css("background-color", "transparent");
	for(var i=1; i<=y; i++) {
		if (i <= y) $("#div_"+x+"_"+i).css("background-color", "#09C");
	}
	$("#c_"+id).prop("checked", true);
}

$(document).ready(function() {
	
});
</script>