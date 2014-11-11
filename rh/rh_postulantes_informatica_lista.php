<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_informatica_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Inform&aacute;tica</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_informatica_form&opcion=nuevo');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_informatica', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:125px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col">Curso</th>
		<th scope="col" width="19%">Nivel</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pi.*,
				md1.Descripcion AS Curso,
				md2.Descripcion AS NivelCurso
			FROM
				rh_postulantes_informat pi
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pi.Informatica AND
													 md1.CodMaestro = 'INFORMAT')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = pi.Nivel AND
													 md2.CodMaestro = 'NIVEL')
			WHERE Postulante = '".$Postulante."'
			ORDER BY Curso";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Informatica]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=$field['Curso']?></td>
			<td align="center"><?=$field['NivelCurso']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>