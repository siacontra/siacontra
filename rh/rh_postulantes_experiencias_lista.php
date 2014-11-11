<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_experiencias_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Experiencias Laborales</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_experiencias_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_experiencias_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_experiencias', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:185px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="15" rowspan="2">&nbsp;</th>
		<th scope="col" rowspan="2">Empresa</th>
		<th scope="col" width="75" rowspan="2">Desde</th>
		<th scope="col" width="75" rowspan="2">Hasta</th>
		<th scope="col" colspan="3">Tiempo</th>
	</tr>
    <tr>
		<th scope="col" width="20" title="A&ntilde;os">A</th>
		<th scope="col" width="20" title="Meses">M</th>
		<th scope="col" width="20" title="Dias">D</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pe.Postulante,
				pe.Secuencia,
				pe.Empresa,
				pe.FechaDesde,
				pe.FechaHasta
			FROM rh_postulantes_experiencia pe
			WHERE pe.Postulante = '".$Postulante."'
			ORDER BY FechaHasta DESC";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		list($Anios, $Meses, $Dias) = getEdad(formatFechaDMA($field['FechaDesde']), formatFechaDMA($field['FechaHasta']));
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<th><?=++$i?></th>
            <td><?=$field['Empresa']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaDesde'])?></td>
            <td align="center"><?=formatFechaDMA($field['FechaHasta'])?></td>
            <td align="center"><?=$Anios?></td>
            <td align="center"><?=$Meses?></td>
            <td align="center"><?=$Dias?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>