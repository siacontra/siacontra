<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_cargos_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Cargos Aplicables</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_cargos_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_cargos_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_cargos', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:385px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="15">&nbsp;</th>
		<th scope="col" width="400">Cargo</th>
		<th scope="col">Comentario</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pc.Postulante,
				pc.Secuencia,
				pc.Comentario,
				pt.DescripCargo,
				o.Organismo
			FROM
				rh_postulantes_cargos pc
				INNER JOIN rh_puestos pt ON (pt.CodCargo = pc.CodCargo)
				INNER JOIN mastorganismos o ON (o.CodOrganismo = pc.CodOrganismo)
			WHERE pc.Postulante = '".$Postulante."'
			ORDER BY Organismo, DescripCargo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		if ($grupo != $field['CodOrganismo']) {
			$grupo = $field['CodOrganismo'];
			?>
			<tr class="trListaBody2">
				<td colspan="3"><?=$field['Organismo']?></td>
			</tr>
			<?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<th><?=++$i?></th>
            <td><?=$field['DescripCargo']?></td>
            <td><?=$field['Comentario']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>