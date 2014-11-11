<?php
$TipoPostulante = substr($sel_candidato, 10, 1);
$Postulante = substr($sel_candidato, 11);
?>
<form name="frm_evaluacion" id="frm_evaluacion" method="post" action="" target="">
<input type="hidden" name="CodOrganismo" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" name="Requerimiento" id="Requerimiento" value="<?=$Requerimiento?>" />
<input type="hidden" name="TipoPostulante" id="TipoPostulante" value="<?=$TipoPostulante?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="sel_candidato" id="sel_candidato" value="<?=$sel_candidato?>" />
<input type="hidden" name="sel_evaluacion" id="sel_evaluacion" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<center>
<div style="width:100%;" class="divFormCaption">Resultados de las Evaluaciones</div>
<div style="overflow:scroll; width:100%; height:190px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
        <th scope="col" width="20"></th>
        <th scope="col" width="35">Etapa</th>
        <th scope="col" align="left">Evaluaci&oacute;n</th>
        <th scope="col" width="60">Nota</th>
    </tr>
    </thead>
    
    <tbody id="lista_evaluacion">
	<?php
	if ($FlagMostrar == "S") {
		//	consulto todos
		$sql = "SELECT
					rep.*,
					re.Evaluacion,
					re.Etapa,
					re.PlantillaEvaluacion,
					e.Descripcion
				FROM
					rh_requerimientoevalpost rep
					INNER JOIN rh_requerimientopost rp ON (rp.CodOrganismo = rep.CodOrganismo AND
														   rp.Requerimiento = rep.Requerimiento AND
														   rp.Postulante = rep.Postulante)
					INNER JOIN rh_requerimientoeval re ON (re.CodOrganismo = rep.CodOrganismo AND
														   re.Requerimiento = rep.Requerimiento AND
														   re.Secuencia = rep.Secuencia)
					INNER JOIN rh_evaluacion e ON (e.Evaluacion = re.Evaluacion)
				WHERE
					rep.CodOrganismo = '".$CodOrganismo."' AND
					rep.Requerimiento = '".$Requerimiento."' AND
					rep.Postulante = '".$Postulante."' AND
					rp.TipoPostulante = '".$TipoPostulante."'
				ORDER BY Secuencia";
		$query_evaluacion = mysql_query($sql) or die ($sql.mysql_error());	$nro_evaluacion=0;
		while ($field_evaluacion = mysql_fetch_array($query_evaluacion)) {
			$id = "$field_evaluacion[Evaluacion].$field_evaluacion[Secuencia]";
			?>
			<tr class="trListaBody" onclick="mClk(this, 'sel_evaluacion'); actualizar_competencias();" id="<?=$id?>">
				<th><?=++$nro_evaluacion?></th>
				<td align="center">
					<input type="hidden" name="Secuencia" value="<?=$field_evaluacion['Secuencia']?>" />
					<?=$field_evaluacion['Etapa']?>
				</td>
				<td>
					<?=$field_evaluacion['Descripcion']?>
				</td>
				<td align="right">
					<?=number_format($field_evaluacion['Calificativo'], 2, ',', '.')?>
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
<input type="hidden" id="nro_evaluacion" value="<?=$nro_evaluacion?>" />
<input type="hidden" id="can_evaluacion" value="<?=$nro_evaluacion?>" />
</form>