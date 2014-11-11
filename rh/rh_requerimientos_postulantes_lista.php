<?php
if ($Modalidad == "I") $btPostulante = "disabled";
else if ($Modalidad == "E") $btEmpleado = "disabled";
if ($opcion == "ver" || $opcion == "finalizar") {
	$btEmpleado = "disabled";
	$btPostulante = "disabled";
	$disabled_ver = "disabled";
}
?>
<form name="frm_candidato" id="frm_candidato" method="post" action="" target="">
<input type="hidden" name="CodOrganismo" id="CodOrganismo" value="<?=$CodOrganismo?>" />
<input type="hidden" name="Requerimiento" id="Requerimiento" value="<?=$Requerimiento?>" />
<input type="hidden" name="NumeroPendiente" id="NumeroPendiente" value="<?=$NumeroPendiente?>" />
<input type="hidden" name="sel_candidato" id="sel_candidato" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<center>
<div style="width:100%;" class="divFormCaption">Candidatos a Evaluar</div>
<table width="100%" class="tblBotones">
	<tr>
    <?php
	if ($opcion == "contratar") {
		?>
		<td align="right">
            <input type="button" class="btLista" value="Contratar" onclick="requerimientos_postulantes_contratar('contratar-candidato');" />
		</td>
        <?
	} else {
		?>
		<td>
            <input type="button" class="btLista" value="Empleado" <?=$btEmpleado?> onclick="parent.document.getElementById('a_empleado1').click();" />
            <input type="button" class="btLista" value="Postulante" <?=$btPostulante?> onclick="parent.document.getElementById('a_postulante1').click();" />
            <input type="button" class="btLista" value="Borrar" <?=$disabled_ver?> onclick="quitarLineaPostulante(this, 'candidato');" />
		</td>
		<td align="right">
            <input type="button" class="btLista" value="Aprobar" <?=$disabled_ver?> onclick="requerimientos_postulantes_aprobar('aprobar-candidato');" />
            <input type="button" class="btLista" value="Descalificar" <?=$disabled_ver?> onclick="requerimientos_postulantes_aprobar('descalificar-candidato');" />
		</td>
        <?
	}
	?>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:190px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
        <th scope="col" width="20"></th>
        <th scope="col" width="60">Postulante</th>
        <th scope="col" align="left">Nombre Completo</th>
        <th scope="col" width="60">Puntaje</th>
        <th scope="col" width="60">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_candidato">
	<?php
    //	consulto todos
	if ($opcion == "contratar") $filtro = " AND rp.Estado = 'A'";
    $sql = "(SELECT
                rp.Postulante,
				rp.Postulante AS CodEmpleado,
                rp.TipoPostulante,
                rp.Puntaje,
                rp.Estado,
                rp.Observaciones,
                CONCAT(p.Nombres, ' ', p.Apellido1, ' ', p.Apellido2) AS NomCompleto
             FROM
                rh_requerimientopost rp
                INNER JOIN rh_postulantes p ON (p.Postulante = rp.Postulante)
             WHERE
                rp.CodOrganismo = '".$CodOrganismo."' AND
                rp.Requerimiento = '".$Requerimiento."' AND
                rp.TipoPostulante = 'E' $filtro)
            UNION
            (SELECT
                p.CodPersona AS Postulante,
				e.CodEmpleado,
                rp.TipoPostulante,
                rp.Puntaje,
                rp.Estado,
                rp.Observaciones,
                p.NomCompleto
             FROM
                rh_requerimientopost rp
                INNER JOIN mastpersonas p ON (p.CodPersona = rp.Postulante)
				INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
             WHERE
                rp.CodOrganismo = '".$CodOrganismo."' AND
                rp.Requerimiento = '".$Requerimiento."' AND
                rp.TipoPostulante = 'I' $filtro)
            ORDER BY TipoPostulante, Postulante";
    $query_candidato = mysql_query($sql) or die ($sql.mysql_error());	$nro_candidato=0;
    while ($field_candidato = mysql_fetch_array($query_candidato)) {
        $id = "candidato_$field_candidato[TipoPostulante]$field_candidato[Postulante]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_candidato'); actualizar_evaluaciones_competencias();" id="<?=$id?>">
            <th><?=++$nro_candidato?></th>
            <td align="center">
                <input type="hidden" name="TipoPostulante" value="<?=$field_candidato['TipoPostulante']?>" />
                <input type="hidden" name="Postulante" value="<?=$field_candidato['Postulante']?>" />
                <?=$field_candidato['TipoPostulante'].$field_candidato['CodEmpleado']?>
            </td>
            <td>
                <?=$field_candidato['NomCompleto']?>
            </td>
            <td>
                <input type="text" name="Puntaje" class="cell2" style="text-align:right;" value="<?=number_format($field_candidato['Puntaje'], 2, ',', '.')?>" readonly="readonly" />
            </td>
            <td align="center">
                <?=printValores("ESTADO-POSTULANTE", $field_candidato['Estado'])?>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_candidato" value="<?=$nro_candidato?>" />
<input type="hidden" id="can_candidato" value="<?=$nro_candidato?>" />
</form>