<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_idiomas_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Idiomas</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_idiomas_form&opcion=nuevo');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_idiomas', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:125px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col">Idioma</th>
		<th scope="col" width="19%">Lectura</th>
		<th scope="col" width="19%">Oral</th>
		<th scope="col" width="19%">Escritura</th>
		<th scope="col" width="19%">General</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pi.*,
				i.DescripcionLocal AS NomIdioma,
				md1.Descripcion AS Lectura,
				md2.Descripcion AS Oral,
				md3.Descripcion AS Escritura,
				md4.Descripcion AS General
			FROM
				rh_postulantes_idioma pi
				INNER JOIN mastidioma i ON (i.CodIdioma = pi.CodIdioma)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pi.NivelLectura AND
													 md1.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = pi.NivelOral AND
													 md2.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md3 ON (md3.CodDetalle = pi.NivelEscritura AND
													 md3.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md4 ON (md4.CodDetalle = pi.NivelGeneral AND
													 md4.CodMaestro = 'NIVEL')
			WHERE Postulante = '".$Postulante."'
			ORDER BY NomIdioma";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[CodIdioma]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=$field['NomIdioma']?></td>
			<td align="center"><?=$field['Lectura']?></td>
			<td align="center"><?=$field['Oral']?></td>
			<td align="center"><?=$field['Escritura']?></td>
			<td align="center"><?=$field['General']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>