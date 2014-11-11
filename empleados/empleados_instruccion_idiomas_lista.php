<?php
//	------------------------------------
if ($filtrar == "default") {
	$fOrderBy = "Idioma";
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_idiomas_lista" method="post" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="registro" id="registro" />

<center>
<table width="885" class="tblBotones">
    <tr>
        <td><div id="rows"></div></td>
        <td align="right">
            <input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=empleados_instruccion_idiomas_form&opcion=nuevo');" />
            
            <input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_idiomas_form&opcion=modificar', 'SELF', '', $('#registro').val());" />
            
            <input type="button" id="btEliminar" value="Eliminar" style="width:75px; <?=$btEliminar?>" onclick="opcionRegistroParent(this.form, this.form.registro.value, 'empleados_instruccion_idiomas', 'eliminar');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=empleados_instruccion_idiomas_form&opcion=ver', 'SELF', '', $('#registro').val());" />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:885px; height:350px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" onclick="order('NomIdioma')"><a href="javascript:">Idioma</a></th>
        <th scope="col" width="19%" onclick="order('Lectura')"><a href="javascript:">Lectura</a></th>
        <th scope="col" width="19%" onclick="order('Oral')"><a href="javascript:">Oral</a></th>
        <th scope="col" width="19%" onclick="order('Escritura')"><a href="javascript:">Escritura</a></th>
        <th scope="col" width="19%" onclick="order('General')"><a href="javascript:">General</a></th>
    </tr>
    </thead>
    
    <tbody>
    <?php
    //	consulto lista
    $sql = "SELECT
				ei.CodPersona,
				ei.CodIdioma,
				i.DescripcionLocal AS Idioma,
				md1.Descripcion AS Lectura,
				md2.Descripcion AS Oral,
				md3.Descripcion AS Escritura,
				md4.Descripcion AS General
            FROM
				rh_empleado_idioma ei
				INNER JOIN mastidioma i ON (i.CodIdioma = ei.CodIdioma)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ei.NivelLectura AND
													 md1.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ei.NivelOral AND
													 md2.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md3 ON (md3.CodDetalle = ei.NivelEscritura AND
													 md3.CodMaestro = 'NIVEL')
				LEFT JOIN mastmiscelaneosdet md4 ON (md4.CodDetalle = ei.NivelGeneral AND
													 md4.CodMaestro = 'NIVEL')
            WHERE ei.CodPersona = '".$CodPersona."'
            ORDER BY $fOrderBy";
    $query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
    while ($field = mysql_fetch_array($query)) {
        $id = "$field[CodPersona]_$field[CodIdioma]";
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
            <td><?=htmlentities($field['Idioma'])?></td>
            <td align="center"><?=htmlentities($field['Lectura'])?></td>
            <td align="center"><?=htmlentities($field['Oral'])?></td>
            <td align="center"><?=htmlentities($field['Escritura'])?></td>
            <td align="center"><?=htmlentities($field['General'])?></td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
</form>