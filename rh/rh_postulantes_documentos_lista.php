<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_documentos_lista" method="post">
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" name="registro" id="registro" />
<center>
<div style="width:100%;" class="divFormCaption">Documentos Entregados</div>
<table width="100%" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" value="Insertar" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=rh_postulantes_documentos_form&opcion=nuevo');" />
            
			<input type="button" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=rh_postulantes_documentos_form&opcion=modificar', 'SELF', '', 'registro');" />
            
			<input type="button" value="Borrar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, $('#registro').val(), 'postulantes_documentos', 'eliminar');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:385px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="15">&nbsp;</th>
		<th scope="col" width="150">Documento</th>
		<th scope="col">Observaciones</th>
		<th scope="col" width="30">¿Pre.?</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				pd.Postulante,
				pd.Secuencia,
				pd.Documento,
				pd.FlagPresento,
				pd.Observaciones,
				md.Descripcion AS NomDocumento
			FROM
				rh_postulantes_documentos pd
				LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pd.Documento AND
													md.CodMaestro = 'DOCUMENTOS')
			WHERE pd.Postulante = '".$Postulante."'
			ORDER BY Secuencia DESC";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query); $i=0;
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Postulante].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
        	<th><?=++$i?></th>
            <td><?=$field['NomDocumento']?></td>
            <td><?=$field['Observaciones']?></td>
            <td align="center"><?=printFlag($field['FlagPresento'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>