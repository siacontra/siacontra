<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_referencias_personales_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Referencias Personales</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_referencias_personales_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_referencias_personales_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_referencias', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:385px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="15">&nbsp;</th>
		<th scope="col" width="300">Nombre</th>
		<th scope="col">Empresa</th>
		<th scope="col" width="100">Tel&eacute;fono</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pr.Postulante,
				pr.Secuencia,
				pr.Nombre,
				pr.Telefono,
				pr.Cargo,
				pr.Empresa
			FROM rh_postulantes_referencias pr
			WHERE
				pr.Postulante = '".$Postulante."' AND
				pr.Tipo = 'P'
			ORDER BY Secuencia DESC";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<th><?=++$i?></th>
            <td><?=$field['Nombre']?></td>
            <td><?=$field['Empresa']?></td>
            <td><?=$field['Telefono']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>