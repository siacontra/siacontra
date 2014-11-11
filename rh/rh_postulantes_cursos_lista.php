<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_cursos_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Cursos</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_cursos_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_cursos_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_cursos', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:385px;">
<table width="100%" class="tblLista">
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pc.Postulante,
				pc.Secuencia,
				pc.PeriodoCulminacion,
				pc.Observaciones,
				c.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio
			FROM
				rh_postulantes_cursos pc
				INNER JOIN rh_cursos c ON (c.CodCurso = pc.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = pc.CodCentroEstudio)
			WHERE pc.Postulante = '".$Postulante."'
			ORDER BY PeriodoCulminacion DESC, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td>
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
                    	<th width="65" align="left">Nro.</th>
                        <td width="25" style="border:none;"><strong><?=++$i?></strong></td>
                    	<th width="50" align="left">Curso.</th>
                        <td style="border:none;"><strong><?=$field['NomCurso']?></strong></td>
                    	<th width="50" align="left">Periodo.</th>
                        <td width="50" style="border:none;"><strong><?=$field['PeriodoCulminacion']?></strong></td>
                    </tr>
                	<tr>
                    	<th align="left">Centro.</th>
                        <td style="border:none;" colspan="5"><?=$field['NomCentroEstudio']?></td>
                    </tr>
                	<tr>
                    	<th align="left">Observaciones.</th>
                        <td style="border:none;" colspan="5"><?=$field['Observaciones']?></td>
                    </tr>
				</table>
            </td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>