<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_instruccion_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Instrucci&oacute;n</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_instruccion_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_instruccion_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_instruccion', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:185px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="25">&nbsp;</th>
		<th scope="col" width="225">Grado de Instrucci&oacute;n</th>
		<th scope="col">Profesi&oacute;n</th>
		<th scope="col" width="25">Fecha de Graduaci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pi.Postulante,
				pi.Secuencia,
				pi.FechaGraduacion,
				gi.Descripcion AS NomGradoInstruccion,
				p.Descripcion AS NomProfesion
			FROM
				rh_postulantes_instruccion pi
				LEFT JOIN rh_gradoinstruccion gi ON (gi.CodGradoInstruccion = pi.CodGradoInstruccion)
				LEFT JOIN rh_profesiones p ON (p.CodProfesion = pi.CodProfesion)
			WHERE Postulante = '".$Postulante."'
			ORDER BY FechaGraduacion DESC";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<th align="center"><?=++$i?></th>
			<td><?=$field['NomGradoInstruccion']?></td>
			<td><?=$field['NomProfesion']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaGraduacion'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>